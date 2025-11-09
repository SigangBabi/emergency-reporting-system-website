<?php

  session_start();
  include '../connect.php';
  $user_name = $_SESSION['name'];
  $query = mysqli_query($connection, "SELECT * FROM user_info WHERE name='$user_name'");
  $row = mysqli_fetch_array($query);
  $fullName = $row['name'];
  $address = $row['address'];
  $photo = $row['photo'];
  $number = $row['mobile_no'];
  $email = $row['email'];

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
      <a href="">Report</a>
      <a href="userDashboardSettings.php">Settings</a>
      <a href="Userdashboard.php">Profile</a>
    </div>
  </div>

  <div class="body-container">
    <div class="left-container">
      <div class="img-elements">
        <img src="assets/side-poster.png" class="background-img">
        <a href="../HomePage/homePage.html"><img src="../GeneralAssets/logo.jpeg"></a>
      </div>
    </div>
    <div class="right-container">
      <div id="item-container" class="item-container">
        <div class="report-container">
            <h1>🚨 REPORT AN EMERGENCY 🚨</h1>
            <p>Select the type of Emergency</p>
            <hr>
            <div class="top-option">
              <a href="#" class="fire">FIRE</a>
              <a href="#" class="flood">FLOOD RESCUE</a>
            </div>
            <div class="bottom-option">
              <a href="#" class="medical">MEDICAL</a>
              <a href="#" class="crime">CRIME</a>
              <a href="#" class="other">OTHER</a>
            </div>
          </div>
      </div>
    </div>
  </div>
  <div id="report-data"
       data-name="<?php echo htmlspecialchars($fullName, ENT_QUOTES); ?>"
       data-address="<?php echo htmlspecialchars($address, ENT_QUOTES); ?>"></div>
  <script src="userdashboardReport.js"></script>
</body>
</html>