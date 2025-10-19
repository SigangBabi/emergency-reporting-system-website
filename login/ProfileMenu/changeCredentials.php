<?php

  session_start();
  include '../connect.php';
  $user_name = $_SESSION['name'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="userdashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="header">
    <div class="icon-container">
      <img src="../GeneralAssets/antipoloLogo.png">
    </div>
    <div class="options-container">
      <a href="userDashboardReport.php">Report</a>
      <a href="userDashboardSettings.php">Settings</a>
      <a href="Userdashboard.php">Profile</a>
    </div>
  </div>

  <div class="body-container">
    <div class="left-container">
      <div class="img-elements">
        <img src="assets/side-poster.png" class="background-img">
        <a href="../HomePage/homePage.php"><img src="../GeneralAssets/logo.jpeg"></a>
      </div>
    </div>
    <div class="right-container">
      <div id="item-container" class="item-container">
        <div class="settings-container">
            <form action="changeCredentials.php" method="POST">
              <div class="change-profile">
                <img src="assets/profile-icon.png">
                  <input type="file" accept="image/*">
              </div>
              <div class="change-information">
                <div class="information-field">
                  <label for="username" id="change-login" style="color: red;">Username</label>
                  <input name="username" type="text" style="width: 80%;">
                  
                  <label for="currPassword" id="change-login" style="color: red;">Current Password:</label>
                  <div class="password-field" style="display: flex; align-items: center; justify-content: left; width: 100%;">
                    <input name="currPassword" type="password" required style="width: 80%;">
                    <img src="assets/hide-pass.png" style="width: 5%;">
                  </div>
                  
                  <label for="newPassword" id="change-login" style="color: red;">New Password</label>
                  <div class="password-field" style="display: flex; align-items: center; justify-content: left; width: 100%;">
                    <input name="newPassword" type="password" required style="width: 80%;">
                    <img src="assets/hide-pass.png" style="width: 5%;">
                  </div>
                  
                  <label for="confPassword" id="change-login" style="color: red;">Confirm Password:</label>
                  <div class="password-field" style="display: flex; align-items: center; justify-content: left; width: 100%;">
                    <input name="confPassword" type="password" required style="width: 80%;">
                    <img src="assets/hide-pass.png" style="width: 5%;">
                  </div>
                  
                  <input type="submit" name="submit">
                </div>
                <div class="logout">
                  <div>
                    <img src="assets/dashboard.png">
                    <a href="../HomePage/homePage.php">Back to Dashboard</a>
                  </div>
                  <div>
                    <img src="assets/creds.png">
                    <a href="#" id="change-creds">Change Login Credentials</a>
                  </div>
                  <div>
                    <img src="assets/logout.png">
                    <a href="logout.php">Logout</a>
                  </div>
                </div>
              </div>
            </form>

            <?php
            
                if (isset($_POST['submit'])) {
                  $username = $_POST['username'];
                  $currPassword = $_POST['currPassword'];
                  $newPassword = $_POST['newPassword'];
                  $confPassword = $_POST['confPassword'];

                  // Fetch current password from database
                  $query = "SELECT * FROM user_info WHERE name='$user_name'";
                  $result = mysqli_query($connection, $query);
                  $row = mysqli_fetch_assoc($result);
                  $dbUsername = $row['user_username'];
                  $dbPassword = $row['password'];

                  if (password_verify($currPassword, $dbPassword) === false) {
                    echo "<script>alert('Current password is incorrect.');</script>";
                    exit();
                  }

                  if ($newPassword !== $confPassword) {
                    echo "<script>alert('The password you entered did not match');</script>";
                    exit();
                  }else{
                    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                  }

                  if(!isset($username) || empty($username)){
                    $username = $dbUsername;
                  }
                
                  // Update credentials in database
                  $updateQuery = "UPDATE user_info SET user_username='$username', password='$newPassword' WHERE name='$user_name'";
                  if (mysqli_query($connection, $updateQuery)) {
                    header("location: changeCredentials.php?status=success");
                  } else {
                    echo "<script>alert('Error in changing Login Credentials');</script>";
                    exit();
                  }
                }

            
            
            ?>
          </div>
      </div>
    </div>
  </div>
  <div id="php-status" data-status="<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>"></div>
  <script src="changeCredentials.js"></script>
</body>
</html>