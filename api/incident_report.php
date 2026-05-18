<?php
$conn = mysqli_connect("localhost","root","","hotel");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <title>Gửi báo cáo sự cố</title>

    <link rel="stylesheet" href="../incident.css">
</head>

<body>

<div class="incident-container">

    <h2>Gửi báo cáo sự cố</h2>

    <form
        action="save_incident.php"
        method="POST"
        class="incident-form"
    >

        <!-- SỐ PHÒNG -->
        <div class="form-group">

            <label>Số phòng</label>

            <select name="RoomNumber" required>

                <option value="">
                    -- Chọn phòng --
                </option>

                <?php
                $rooms = mysqli_query($conn,"
                    SELECT * FROM room
                ");

                while($r = mysqli_fetch_assoc($rooms)):
                ?>

                <option value="<?= $r['RoomNumber'] ?>">

                    <?= $r['RoomNumber'] ?>

                </option>

                <?php endwhile; ?>

            </select>

        </div>

        <!-- NGƯỜI BÁO -->
        <div class="form-group">

            <label>Người báo</label>

            <select name="ReportedBy" required>

                <option value="">
                    -- Chọn nhân viên --
                </option>

                <?php
                $staffs = mysqli_query($conn,"
                    SELECT * FROM staff
                ");

                while($s = mysqli_fetch_assoc($staffs)):
                ?>

                <option value="<?= $s['StaffName'] ?>">

                    <?= $s['StaffName'] ?>

                </option>

                <?php endwhile; ?>

            </select>

        </div>

        <!-- NỘI DUNG -->
        <div class="form-group full">

            <label>Nội dung sự cố</label>

            <textarea
                name="Description"
                required
            ></textarea>

        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            class="submit-btn"
        >
            Gửi báo cáo sự cố
        </button>

    </form>

</div>

</body>
</html>