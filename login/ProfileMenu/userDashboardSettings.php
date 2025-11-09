<?php

  session_start();
  include '../connect.php';
  $user_name = $_SESSION['name'];

  $query = mysqli_query($connection, "SELECT * FROM user_info WHERE name='$user_name'");
  $row = mysqli_fetch_array($query);
  $oldName = $row['name'];
  $oldEmail = $row['email'];
  $oldNumber = $row['mobile_no'];
  $oldAddress = $row['address'];


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
            <form action="userDashboardSettings.php" method="POST">
              <div class="change-profile">
                <img src="assets/profile-icon.png">
                  <input type="file" accept="image/*">
              </div>
              <div class="change-information">
                <div class="information-field">
                  <label for="name">Name:</label>
                  <input name="name" type="text" >
                  <label for="address">Address:</label>
                  <input name="address" type="text" name="address" >
                  <label for="number">Mobile Number:</label>
                  <input name="number" type="tel" pattern="[0-9]{11}" name="number" >
                  <label for="email">Email:</label>
                  <input name="email" type="email" name="email" >
                  <input type="submit" name="submit">
                </div>
                <div class="logout">
                  <div>
                    <img src="assets/dashboard.png">
                    <a href="../HomePage/homePage.php">Back to Dashboard</a>
                  </div>
                  <div>
                    <img src="assets/creds.png">
                    <a href="changeCredentials.php">Change Login Credentials</a>
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

                  if(empty($_POST['name'])){
                    $newName = $oldName;
                  }else{
                    $newName = $_POST['name'];
                  }

                  if(empty($_POST['address'])){
                    $newAddress = $oldAddress;
                  }else{
                    $newAddress = $_POST['address'];
                  }

                  if(empty($_POST['number'])){
                    $newNumber = $oldNumber;
                  }else{
                    $newNumber = $_POST['number'];
                  }

                  if(empty($_POST['email'])){
                    $newEmail = $oldEmail;
                  }else{
                    $newEmail = $_POST['email'];
                  }
                  

                  $updateQuery = "UPDATE user_info SET name='$newName', address='$newAddress', mobile_no='$newNumber', email='$newEmail' WHERE name='$user_name'";
                    if ($connection->query($updateQuery) === TRUE) {
                        $_SESSION['name'] = $newName;
                        header("location: userDashboardSettings.php?status=success");
                        
                    } else {
                        header("location: userDashboardSettings.php?status=error");
                    }
                  
                }

            
            
            ?>
          </div>
      </div>
    </div>
  </div>
  <div id="php-status" data-status="<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>"></div>
  <script src="settings.js"></script>
</body>
</html>