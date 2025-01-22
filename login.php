<?php
session_start();
$message = $_GET['message'] ?? $_SESSION['message'] ?? null;
$message_type = $_GET['type'] ?? $_SESSION['message_type'] ?? null;

// Hapus pesan setelah ditampilkan
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href= "css/login.css" />
    <title>Log In</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
  <header>
  <div class="logo">
    <a href="index.php">
      <img src="./img/logo2.png" alt="Logo" />
      <h3>IFRIT</h3>
    </a>
  </div>
  </header>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <!-- Sign In Form -->
          <form action="prosess_login.php" method="POST" class="sign-in-form" id="student-form">
            <h2 class="title">Sign In</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="gridbutton" style="display: flex; gap: 10px; padding-top: 20px;">
            <button class="btn" id="sign-up-btn" type="button">Sign Up</button>
              <button class="btn" type="submit">Login</button>
            </div>
          </form>

          <!-- Sign Up Form -->
          <form action="prosess_regist.php" method="POST" class="sign-up-form" id="teacher-form">
            <h2 class="title">Sign Up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="gridbutton" style="display: flex; gap: 10px; padding-top: 20px;">
             <button class="btn" type="submit">Register</button>
             <button class="btn" id="sign-in-btn" type="button">Sign In</button>

            </div>
          </form>
        </div>
      </div>
      <!-- Panel structure -->
      <div class="panels-container">
        <!-- Sign In Panel -->
        <div class="panel left-panel">
          <img src="img/login1.svg" class="image" alt="Log Image" />
        </div>
        <!-- Sign Up Panel -->
        <div class="panel right-panel">
          <img src="img/regist1.svg" class="image" alt="Register Image" />
        </div>
      </div>
    </div>
    <script src="js/login.js"></script>

    <!-- SweetAlert Script -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          <?php if ($message): ?>
              let type = '<?php echo $message_type; ?>';
              let title = 'Informasi';
              let icon = 'info';

              if (type === 'error') {
                  icon = 'error';
                  title = 'Oops!';
              } else if (type === 'success') {
                  icon = 'success';
                  title = "Berhasil";
              }

              Swal.fire({
                  icon: icon,
                  title: title,
                  text: '<?php echo $message; ?>',
                  timer: 5000,
                  showConfirmButton: true,
                  confirmButtonText: 'OK',
                  customClass: {
                    confirmButton: 'swal-confirm-btn'
                    }
              });
          <?php endif; ?>
      });
    </script>
</body>
</html>
