<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hotel Management System</title>
    <link rel="stylesheet" href="../style.css" />

    <!-- ICON -->
    <script src="https://unpkg.com/lucide@latest"></script>
  </head>
<div id="loader">
  <div class="spinner"></div>
</div>
  <body>
    <div class="container">

      <!-- LEFT -->
      <div class="left">
        <div class="logo-box">
          <i data-lucide="building"></i>
        </div>

        <h1>Hotel Management System</h1>

        <p>
          Streamline your hotel operations with our comprehensive management
          platform. Manage bookings, guests, rooms, and revenue all in one place.
        </p>

        <!-- FEATURES -->
        <div class="features">

          <div class="feature-item">
            <i data-lucide="calendar-check"></i>
            <span>Real-time room and booking management</span>
          </div>

          <div class="feature-item">
            <i data-lucide="bar-chart"></i>
            <span>Invoice tracking, revenue reports, and analytics</span>
          </div>

          <div class="feature-item">
            <i data-lucide="shield-check"></i>
            <span>Role-based access control (Admin, Manager, Staff)</span>
          </div>

        </div>
      </div> 
      <!-- RIGHT -->
      <div class="right">
        <div class="card">
          <h2>Welcome back</h2>
          <p class="subtitle">Please sign in to your account</p>

          <form action="login.php" method="POST">

            <label>Username</label>
            <input type="text" name="username" placeholder="Enter your username" required />

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required />

            <!-- Role hidden -->
            <input type="hidden" name="role" id="roleInput" value="admin">

            <label>Select Role</label>
            <div class="roles">

              <div class="role active" data-role="admin">
                <i data-lucide="shield-check"></i>
                <span>Admin</span>
              </div>

              <div class="role" data-role="manager">
                <i data-lucide="briefcase"></i>
                <span>Manager</span>
              </div>

              <div class="role" data-role="staff">
                <i data-lucide="users"></i>
                <span>Staff</span>
              </div>

            </div>

            <div class="forgot">
              <a href="#">Forgot password?</a>
            </div>

            <button type="submit">Sign In</button>

            <p class="support">Need help? Contact support</p>
          </form>
        </div>
      </div>

    </div>

    <!-- JS -->
    <script>
      lucide.createIcons();

      const roles = document.querySelectorAll(".role");
      const roleInput = document.getElementById("roleInput");

      roles.forEach(role => {
        role.addEventListener("click", () => {
          roles.forEach(r => r.classList.remove("active"));
          role.classList.add("active");

          roleInput.value = role.getAttribute("data-role");
        });
      });
    </script>
  <script>
  window.addEventListener("load", function () {
    const loader = document.getElementById("loader");
    if (loader) {
      loader.style.opacity = "0";
      loader.style.transition = "0.3s";

      setTimeout(() => {
        loader.style.display = "none";
      }, 300);
    }
  });
</script>
  </body>
</html>