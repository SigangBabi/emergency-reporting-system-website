<?php
  session_start();

  // Prevent caching so Back won't show a cached protected page
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header("Expires: 0");

  // If not logged in, go to public homepage
  if (!isset($_SESSION['name'])) {
      header('Location: ../HomePage/homePage.php');
      exit();
  }

  include '../connect.php';
  $user_name = $_SESSION['name'];

  $query = mysqli_query($connection, "SELECT * FROM user_info WHERE name='$user_name'");
  $row = mysqli_fetch_array($query);
  $oldName = $row['name'];
  $oldEmail = $row['email'];
  $oldNumber = $row['mobile_no'];
  $oldAddress = $row['address'];
  $photo = $row['photo'];

/* compute src for preview (same logic as profile) */
$photoSrc = 'assets/profile-icon.png';
if (!empty($photo)) {
    $imgInfo = @getimagesizefromstring($photo);
    if ($imgInfo && isset($imgInfo['mime'])) {
        $photoSrc = 'data:' . $imgInfo['mime'] . ';base64,' . base64_encode($photo);
    } else {
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
            <form method="post" enctype="multipart/form-data">
              <div class="change-profile">
                <img src="<?php echo htmlspecialchars($photoSrc, ENT_QUOTES); ?>" alt="Profile">
                <input type="file" name="picture" accept="image/*">
              </div>
              <div class="change-information">
                <div class="information-field">
                  <label for="name">Name:</label>
                  <input name="name" type="text" >
                  <label for="address">Address:</label>
                  <input name="address" type="text" >
                  <label for="number">Mobile Number:</label>
                  <input name="number" type="tel" pattern="[0-9]{11}" >
                  <label for="email">Email:</label>
                  <input name="email" type="email" >
                  <input type="submit" name="submit" value="Save">
                </div>
              </div>
            </form>

            <?php
              // handle file upload + update
              if (isset($_POST['submit'])) {

                $newName = empty($_POST['name']) ? $oldName : $_POST['name'];
                $newAddress = empty($_POST['address']) ? $oldAddress : $_POST['address'];
                $newNumber = empty($_POST['number']) ? $oldNumber : $_POST['number'];
                $newEmail = empty($_POST['email']) ? $oldEmail : $_POST['email'];

                $uploadedBlob = null;
                // check for uploaded file
                if (!empty($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                  $file = $_FILES['picture'];
                  if ($file['error'] === UPLOAD_ERR_OK) {
                    $tmp = $file['tmp_name'];
                    // read binary
                    $uploadedBlob = file_get_contents($tmp);
                  }
                }

                if ($uploadedBlob !== null) {
                  // update including photo blob
                  $stmt = $connection->prepare("UPDATE user_info SET name = ?, address = ?, mobile_no = ?, email = ?, photo = ? WHERE name = ?");
                  if ($stmt) {
                    // bind a null placeholder for blob, will send via send_long_data
                    $null = NULL;
                    $stmt->bind_param('ssssbs', $newName, $newAddress, $newNumber, $newEmail, $null, $user_name);
                    // parameter index 4 (zero-based) is the 5th parameter (photo)
                    $stmt->send_long_data(4, $uploadedBlob);
                    if ($stmt->execute()) {
                      $_SESSION['name'] = $newName;
                      $stmt->close();
                      header("Location: userDashboardSettings.php?status=success");
                      exit();
                    } else {
                      $stmt->close();
                      header("Location: userDashboardSettings.php?status=error");
                      exit();
                    }
                  } else {
                    header("Location: userDashboardSettings.php?status=error");
                    exit();
                  }
                } else {
                  // update without changing photo
                  $stmt = $connection->prepare("UPDATE user_info SET name = ?, address = ?, mobile_no = ?, email = ? WHERE name = ?");
                  if ($stmt) {
                    $stmt->bind_param('sssss', $newName, $newAddress, $newNumber, $newEmail, $user_name);
                    if ($stmt->execute()) {
                      $_SESSION['name'] = $newName;
                      $stmt->close();
                      header("Location: userDashboardSettings.php?status=success");
                      exit();
                    } else {
                      $stmt->close();
                      header("Location: userDashboardSettings.php?status=error");
                      exit();
                    }
                  } else {
                    header("Location: userDashboardSettings.php?status=error");
                    exit();
                  }
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