<?php 
    // start session and DB connect
    session_start();
    include '../../connect.php';

    // If admin already logged in, replace current history entry with dashboard
    if (isset($_SESSION['admin'])) {
        // use location.replace so the login page is not kept in history
        echo '<script>location.replace("../dashboard.html");</script>';
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="adminLogin.css">
</head>
<body>
  <div class="background-blur"></div>

  <div class="login-container">
    <img src="assets/profile-icon.png" alt="">
    <h1>ADMIN LOGIN</h1>
    <form action="" method="post">
      <input type="text" name="username" id="username" placeholder="Username" required>
      <input type="password" name="password" id="password" placeholder="Password" required>
      <div class="show-password">
        <input type="checkbox" id="showpw">
        <label for="showpw">Show Password</label>
      </div>
      <button type="submit" id="login" name="login">Login</button>
    </form>

    <?php 
      if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $verifyAdmin = "SELECT * FROM admin_info WHERE username = '$username'";
        $verifyResult = $connection->query($verifyAdmin);

        if($verifyResult->num_rows > 0) {
          $row = $verifyResult->fetch_assoc();

          if (password_verify($password, $row['password'])) {
            // set session and replace history entry so Back won't return to login
            $_SESSION['admin'] = $row['name'];
            echo '<script>location.replace("../dashboard.php");</script>';
            exit();
          } else {
            echo "<script>alert('Invalid Admin Credentials')</script>";
          }
        } else {
          echo "<script>alert('Invalid Admin Credentials')</script>";
        }
      }   
    ?>
    <p id="note">Note: in case of forgotten password, please contact the Administrator</p>
  </div>
  <script>
    // optional: toggle password visibility
    document.getElementById('showpw')?.addEventListener('change', function(e){
      const pw = document.getElementById('password');
      if(pw) pw.type = this.checked ? 'text' : 'password';
    });
  </script>
</body>
</html>