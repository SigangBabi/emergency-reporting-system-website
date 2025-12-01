<?php

  session_start();

  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header("Expires: 0");

  if (!isset($_SESSION['name'])) {
      header('Location: ../HomePage/homePage.php');
      exit();
  }

  include '../connect.php';
  $user_name = $_SESSION['name'];
  $query = mysqli_query($connection, "SELECT * FROM user_info WHERE name='$user_name'");
  $row = mysqli_fetch_array($query);
  $fullName = $row['name'];
  $address = $row['address'];
  $photo = $row['photo'];
  $number = $row['mobile_no'];
  $email = $row['email'];

/* determine image src: if DB contains binary image render data: url, if it's a path use it,
   otherwise fallback to default asset */
$photoSrc = 'assets/profile-icon.png';
if (!empty($photo)) {
    // if binary image data
    $imgInfo = @getimagesizefromstring($photo);
    if ($imgInfo && isset($imgInfo['mime'])) {
        $photoSrc = 'data:' . $imgInfo['mime'] . ';base64,' . base64_encode($photo);
    } else {
        // if stored value appears to be a path or URL, use it
        $maybePath = trim($photo);
        if ($maybePath !== '' && (strpos($maybePath, '/') !== false || file_exists(__DIR__ . '/' . $maybePath))) {
            $photoSrc = $maybePath;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="userdashboard.css">
</head>
<body>
  <div class="header">
    <div class="icon-container">
      <img src="../GeneralAssets/antipoloLogo.png">
    </div>
    <div class="options-container">
      <a href="userDashboardReport.php">Report</a>
      <a href="userDashboardSettings.php">Settings</a>
      <a href="">Profile</a>
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
        <div class="profile-container">
          <div class="profile-main">
            <div class="profile-img">
              <img src="<?php echo htmlspecialchars($photoSrc, ENT_QUOTES); ?>" alt="Profile">

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
            
            <div class="profile-info">
              <div class="informations">
                <?php
                  echo "<p>Name: </p>
                        <h1>$fullName</h1>
                        <p>Address: </p>
                        <h1>" . (!empty($address) ? $address : "No Address Provided") . "</h1>
                        <p>Mobile Number: </p>
                        <h1>" . (!empty($number) ? $number : "No Mobile Number Provided") . "</h1>
                        <p>Email: </p>
                        <h1>$email</h1>";
                ?>
                
              </div>
            </div>
          </div>
          <div class="update-info">
            <h1>To Update your information <a href="userDashboardSettings.php">Click here!</a></h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="php-location" data-location="<?php echo isset($_GET['location']) ? $_GET['location'] : ''; ?>"></div>
  <script src="userdashboard.js"></script>
</body>
</html>