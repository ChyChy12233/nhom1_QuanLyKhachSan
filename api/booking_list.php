<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// ── Filters ──────────────────────────────────────────────
$kw     = trim($_GET['keyword'] ?? '');
$typeId = $_GET['type']   ?? '';
$status = $_GET['status'] ?? '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;

// ── Stats ─────────────────────────────────────────────────
$stmtStats = $conn->query("SELECT RoomStatus, COUNT(*) AS cnt FROM room GROUP BY RoomStatus");
$stats = ['available' => 0, 'occupied' => 0, 'reserved' => 0, 'cleaning' => 0];
while ($s = $stmtStats->fetch_assoc()) {
    switch ($s['RoomStatus']) {
        case 'Trống':
        case 'Phòng trống':     $stats['available'] += (int)$s['cnt']; break;
        case 'Phòng đang thuê': $stats['occupied']   = (int)$s['cnt']; break;
        case 'Phòng đã đặt':   $stats['reserved']   = (int)$s['cnt']; break;
        case 'Đang dọn':       $stats['cleaning']   = (int)$s['cnt']; break;
    }
}

// ── Room types for dropdown ───────────────────────────────
$rtResult  = $conn->query("SELECT RoomTypeId, RoomTypeName FROM room_type ORDER BY RoomTypeName");
$roomTypes = [];
while ($rt = $rtResult->fetch_assoc()) $roomTypes[] = $rt;

// ── Badge helper ──────────────────────────────────────────
function statusInfo(string $s): array {
    if (in_array($s, ['Trống', 'Phòng trống']))
        return ['class' => 'badge-available', 'label' => 'AVAILABLE', 'key' => 'available'];
    if ($s === 'Phòng đang thuê')
        return ['class' => 'badge-occupied',  'label' => 'OCCUPIED',  'key' => 'occupied'];
    if ($s === 'Phòng đã đặt')
        return ['class' => 'badge-reserved',  'label' => 'RESERVED',  'key' => 'reserved'];
    if ($s === 'Đang dọn')
        return ['class' => 'badge-cleaning',  'label' => 'CLEANING',  'key' => 'cleaning'];
    return ['class' => '', 'label' => $s, 'key' => ''];
}

// ── Build WHERE clause ────────────────────────────────────
$whereParts   = [];
$filterParams = [];
$filterTypes  = '';

if ($kw !== '') {
    $like = '%' . $kw . '%';
    $whereParts[]   = "(r.RoomNumber LIKE ? OR c.CustomerName LIKE ?)";
    $filterParams[] = $like;
    $filterParams[] = $like;
    $filterTypes   .= 'ss';
}
if ($typeId !== '') {
    $whereParts[]   = "r.RoomTypeId = ?";
    $filterParams[] = $typeId;
    $filterTypes   .= 's';
}
if ($status !== '') {
    if ($status === 'Trống') {
        $whereParts[] = "r.RoomStatus IN ('Trống','Phòng trống')";
    } else {
        $whereParts[]   = "r.RoomStatus = ?";
        $filterParams[] = $status;
        $filterTypes   .= 's';
    }
}

$whereSQL = $whereParts ? 'WHERE ' . implode(' AND ', $whereParts) : '';

$baseFrom = "FROM room r
             JOIN room_type rt ON r.RoomTypeId = rt.RoomTypeId
             LEFT JOIN booking b
               ON r.RoomId = b.RoomId AND b.Status IN ('Đã đặt','Đang ở')
             LEFT JOIN customer c ON b.CustomerId = c.CustomerId
             LEFT JOIN room_customer rc
               ON rc.RoomId = r.RoomId AND rc.Status = 'Đang ở'";

// ── Count for pagination ──────────────────────────────────
$sqlCount  = "SELECT COUNT(DISTINCT r.RoomId) AS total $baseFrom $whereSQL";
$stmtCount = $conn->prepare($sqlCount);
if ($filterTypes) $stmtCount->bind_param($filterTypes, ...$filterParams);
$stmtCount->execute();
$totalRows  = (int)$stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = max(1, (int)ceil($totalRows / $perPage));
$page   = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

// ── Fetch rooms ───────────────────────────────────────────
$sqlData = "SELECT r.RoomId, r.RoomNumber, r.RoomStatus,
                   rt.RoomTypeId, rt.RoomTypeName, rt.Price,
                   b.BookingId, b.CheckInDate, b.CheckOutDate,
                   c.CustomerName,
                   rc.RoomCustomerId
            $baseFrom
            $whereSQL
            GROUP BY r.RoomId
            ORDER BY r.RoomNumber
            LIMIT ? OFFSET ?";

$allParams = array_merge($filterParams, [$perPage, $offset]);
$allTypes  = $filterTypes . 'ii';
$stmtData  = $conn->prepare($sqlData);
$stmtData->bind_param($allTypes, ...$allParams);
$stmtData->execute();
$roomsResult = $stmtData->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đặt phòng</title>
    <link rel="stylesheet" href="../booking_list.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<div class="booking-page">

  <!-- Header -->
  <div class="page-header">
    <h1>Quản lý đặt phòng</h1>
    <a href="booking_checkin.php" class="btn-new">
      <i data-lucide="plus-circle" style="width:16px;height:16px;"></i>
      Đặt phòng mới
    </a>
  </div>

  <!-- Alerts -->
  <?php if (isset($_GET['ok'])): ?>
    <?php $msgs = [
      'booked'    => 'Đặt phòng thành công!',
      'received'  => 'Nhận phòng thành công!',
      'clean_done'=> 'Phòng đã sẵn sàng!',
      'cancelled' => 'Đã hủy đặt phòng.',
    ]; ?>
    <div class="alert-ok"><?= e($msgs[$_GET['ok']] ?? 'Thành công!') ?></div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
  <?php endif; ?>

  <!-- Filters -->
  <form method="GET" class="filters">
    <div class="filter-search">
      <i data-lucide="search" class="filter-icon" style="width:14px;height:14px;"></i>
      <input type="text" name="keyword" placeholder="Số phòng, tên khách hàng..." value="<?= e($kw) ?>">
    </div>

    <select name="type" onchange="this.form.submit()">
      <option value="">Tất cả loại phòng</option>
      <?php foreach ($roomTypes as $rt): ?>
        <option value="<?= e($rt['RoomTypeId']) ?>" <?= $typeId === $rt['RoomTypeId'] ? 'selected' : '' ?>>
          <?= e($rt['RoomTypeName']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="status" onchange="this.form.submit()">
      <option value="">Tất cả trạng thái</option>
      <option value="Trống"           <?= $status === 'Trống'           ? 'selected' : '' ?>>Sẵn sàng</option>
      <option value="Phòng đang thuê" <?= $status === 'Phòng đang thuê' ? 'selected' : '' ?>>Đang ở</option>
      <option value="Phòng đã đặt"   <?= $status === 'Phòng đã đặt'   ? 'selected' : '' ?>>Đã đặt</option>
      <option value="Đang dọn"       <?= $status === 'Đang dọn'       ? 'selected' : '' ?>>Đang dọn</option>
    </select>

    <button type="submit" class="btn-filter">
      <i data-lucide="filter" style="width:14px;height:14px;"></i> Lọc
    </button>
  </form>

  <!-- Stats bar -->
  <div class="stats-bar">
    <div class="stat-item">
      <span class="stat-dot dot-green"></span>
      Sẵn sàng: <span class="stat-count"><?= $stats['available'] ?></span>
    </div>
    <div class="stat-item">
      <span class="stat-dot dot-blue"></span>
      Đang ở: <span class="stat-count"><?= $stats['occupied'] ?></span>
    </div>
    <div class="stat-item">
      <span class="stat-dot dot-yellow"></span>
      Đã đặt: <span class="stat-count"><?= $stats['reserved'] ?></span>
    </div>
    <div class="stat-item">
      <span class="stat-dot dot-red"></span>
      Đang dọn: <span class="stat-count"><?= $stats['cleaning'] ?></span>
    </div>
  </div>

  <!-- Room grid -->
  <div class="room-grid">
  <?php if ($roomsResult->num_rows === 0): ?>
    <div class="no-data">Không có phòng nào phù hợp.</div>
  <?php else: ?>
    <?php while ($room = $roomsResult->fetch_assoc()):
      $si = statusInfo($room['RoomStatus']);
    ?>
    <div class="room-card">
      <div class="card-top">
        <span class="room-number">P.<?= e($room['RoomNumber']) ?></span>
        <span class="badge <?= $si['class'] ?>"><?= $si['label'] ?></span>
      </div>
      <p class="room-type-label"><?= e($room['RoomTypeName']) ?></p>

      <div class="info-grid">
        <div class="info-row">
          <span class="info-label">Khách:</span>
          <span class="info-value"><?= $room['CustomerName'] ? e($room['CustomerName']) : 'Trống' ?></span>
        </div>
        <?php if ($si['key'] === 'reserved' && $room['CheckInDate']): ?>
        <div class="info-row">
          <span class="info-label">Check-in:</span>
          <span class="info-value"><?= e(format_date($room['CheckInDate'])) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($si['key'] === 'occupied' && $room['CheckInDate']): ?>
        <div class="info-row">
          <span class="info-label">Từ:</span>
          <span class="info-value"><?= e(format_date($room['CheckInDate'])) ?></span>
        </div>
        <?php endif; ?>
        <div class="info-row">
          <span class="info-label">Giá:</span>
          <span class="info-value price"><?= format_money($room['Price']) ?>/đêm</span>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="card-actions">
        <?php if ($si['key'] === 'available'): ?>
          <a href="checkin.php" class="btn-action btn-checkin">
            <i data-lucide="log-in" style="width:14px;height:14px;"></i> Check-in
          </a>

        <?php elseif ($si['key'] === 'occupied'): ?>
          <?php if ($room['RoomCustomerId']): ?>
          <form method="POST" action="checkout_room_usage.php"
                onsubmit="return confirm('Check-out phòng <?= e($room['RoomNumber']) ?>?')">
            <input type="hidden" name="id" value="<?= e($room['RoomCustomerId']) ?>">
            <button type="submit" class="btn-icon danger" title="Check-out">
              <i data-lucide="log-out" style="width:15px;height:15px;"></i>
            </button>
          </form>
          <?php endif; ?>
          <a href="edit_booking.php?id=<?= e($room['BookingId'] ?? '') ?>" class="btn-icon" title="Sửa">
            <i data-lucide="pencil" style="width:15px;height:15px;"></i>
          </a>
          <a href="booking_detail.php?id=<?= e($room['BookingId'] ?? '') ?>" class="btn-icon" title="Chi tiết">
            <i data-lucide="info" style="width:15px;height:15px;"></i>
          </a>

        <?php elseif ($si['key'] === 'reserved'): ?>
          <a href="action_receive_guest.php?booking_id=<?= e($room['BookingId']) ?>"
             class="btn-action btn-receive"
             onclick="return confirm('Nhận phòng <?= e($room['RoomNumber']) ?>?')">
            <i data-lucide="check-circle" style="width:14px;height:14px;"></i> Nhận phòng
          </a>

        <?php elseif ($si['key'] === 'cleaning'): ?>
          <a href="action_done_clean.php?room_id=<?= e($room['RoomId']) ?>"
             class="btn-action btn-clean-done"
             onclick="return confirm('Xác nhận phòng <?= e($room['RoomNumber']) ?> đã dọn xong?')">
            <i data-lucide="check" style="width:14px;height:14px;"></i> Xác nhận xong
          </a>

        <?php else: ?>
          <span style="font-size:12px;color:#9ca3af;">—</span>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile; ?>
  <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php
    $qs = http_build_query(array_filter(['keyword' => $kw, 'type' => $typeId, 'status' => $status]));
    $qs = $qs ? '&' . $qs : '';
    ?>
    <a href="?page=<?= max(1,$page-1) . $qs ?>" class="<?= $page <= 1 ? 'pg-disabled' : '' ?>">&lsaquo;</a>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i . $qs ?>" class="<?= $i === $page ? 'pg-active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <a href="?page=<?= min($totalPages,$page+1) . $qs ?>" class="<?= $page >= $totalPages ? 'pg-disabled' : '' ?>">&rsaquo;</a>
  </div>
  <?php endif; ?>

</div>

<script>lucide.createIcons();</script>
</body>
</html>
