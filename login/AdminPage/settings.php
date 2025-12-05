<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

include '../connect.php';

if (!isset($_SESSION['admin'])) {
    header('Location: adminLogin/adminLogin.php');
    exit();
}

$adminName = $_SESSION['admin'];
$query = mysqli_query($connection, "SELECT * FROM admin_info WHERE name='$adminName'");
$row = mysqli_fetch_array($query);

if (!$row) {
    session_destroy();
    header('Location: adminLogin/adminLogin.php');
    exit();
}

$fullName = $row['name'];
$role = $row['role'];
$email = $row['email'] ?? '';
$mobile = $row['mobile_no'] ?? '';
$photo = $row['photo'] ?? '';

// Determine photo source
$photoSrc = 'assets/profile-icon.png';
if (!empty($photo)) {
    $imgInfo = @getimagesizefromstring($photo);
    if ($imgInfo && isset($imgInfo['mime'])) {
        $photoSrc = 'data:' . $imgInfo['mime'] . ';base64,' . base64_encode($photo);
    }
}

// Handle form submissions
$statusMessage = '';
$statusType = '';

// Handle profile info update
if (isset($_POST['update_profile'])) {
    $newName = !empty($_POST['staffName']) ? trim($_POST['staffName']) : null;
    $newEmail = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $newMobile = !empty($_POST['mobile']) ? trim($_POST['mobile']) : null;

    $uploadedBlob = null;
    if (!empty($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['photo'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $uploadedBlob = file_get_contents($file['tmp_name']);
            }
        }
    }

    $updateFields = [];
    $types = '';
    $values = [];

    if ($newName !== null) {
        $updateFields[] = "name = ?";
        $types .= 's';
        $values[] = $newName;
    }
    if ($newEmail !== null) {
        $updateFields[] = "email = ?";
        $types .= 's';
        $values[] = $newEmail;
    }
    if ($newMobile !== null) {
        $updateFields[] = "mobile_no = ?";
        $types .= 's';
        $values[] = $newMobile;
    }
    if ($uploadedBlob !== null) {
        $updateFields[] = "photo = ?";
        $types .= 'b';
        $values[] = &$uploadedBlob; // pass by reference for blob
    }

    if (!empty($updateFields)) {
        $updateFields[] = "name = ?"; // WHERE clause
        $types .= 's';
        $values[] = $adminName;

        $sql = "UPDATE admin_info SET " . implode(", ", array_slice($updateFields, 0, -1)) . " WHERE name = ?";
        $stmt = $connection->prepare($sql);

        if ($stmt) {
            // Bind parameters (bind_param requires references)
            $bindParams = [];
            $bindParams[] = $types;
            for ($i = 0; $i < count($values); $i++) {
                // ensure each value is a reference
                $bindParams[] = &$values[$i];
            }
            call_user_func_array([$stmt, 'bind_param'], $bindParams);

            // Send blob data if exists: find 0-based index of the photo parameter among SET fields
            if ($uploadedBlob !== null) {
                $setFields = array_slice($updateFields, 0, -1); // exclude the appended WHERE clause
                foreach ($setFields as $idx => $fieldDef) {
                    if (strpos($fieldDef, 'photo') !== false) {
                        // $idx is 0-based position for send_long_data
                        $stmt->send_long_data($idx, $uploadedBlob);
                        break;
                    }
                }
            }

            if ($stmt->execute()) {
                // Update session if name changed
                if ($newName !== null) {
                    $_SESSION['admin'] = $newName;
                }
                $statusMessage = "Profile updated successfully!";
                $statusType = "success";
                // Refresh to show new data
                header("Refresh: 1; url=settings.php");
            } else {
                $statusMessage = "Failed to update profile.";
                $statusType = "error";
            }
            $stmt->close();
        }
    } else {
        $statusMessage = "No changes to save.";
        $statusType = "warning";
    }
}

// Handle login credentials update
if (isset($_POST['update_credentials'])) {
    $newUsername = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $newPassword = !empty($_POST['password']) ? trim($_POST['password']) : null;

    if ($newUsername === null && $newPassword === null) {
        $statusMessage = "Please fill in at least one field.";
        $statusType = "warning";
    } else {
        $updateFields = [];
        $types = '';
        $values = [];

        if ($newUsername !== null) {
            $updateFields[] = "name = ?";
            $types .= 's';
            $values[] = $newUsername;
        }
        if ($newPassword !== null) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateFields[] = "password = ?";
            $types .= 's';
            $values[] = $hashedPassword;
        }

        $updateFields[] = "name = ?";
        $types .= 's';
        $values[] = $adminName;

        $sql = "UPDATE admin_info SET " . implode(", ", array_slice($updateFields, 0, -1)) . " WHERE name = ?";
        $stmt = $connection->prepare($sql);

        if ($stmt) {
            // Bind parameters with references
            $bindParams = [];
            $bindParams[] = $types;
            for ($i = 0; $i < count($values); $i++) {
                $bindParams[] = &$values[$i];
            }
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
            
            if ($stmt->execute()) {
                $stmt->close();
                // Logout and redirect to login
                session_destroy();
                header('Location: adminLogin/adminLogin.php?message=credentials_updated');
                exit();
            } else {
                $statusMessage = "Failed to update credentials.";
                $statusType = "error";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
  <title>Admin Panel - Settings</title>
  <link href="dashboard.css" rel="stylesheet">
</head>
<body>

  <!-- Sidebar panel for navigation and profile -->
  <div class="side-panel">
    <!-- Admin profile section -->
    <div class="profile-container">
      <img src="<?php echo htmlspecialchars($photoSrc, ENT_QUOTES); ?>" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
      <div class="profile-name">
        <h1><?php echo htmlspecialchars($fullName); ?></h1>
        <p><?php echo htmlspecialchars($role); ?></p>
      </div>
    </div>

    <!-- Navigation section -->
    <div class="general-panel">
      <h3>General</h3>
      <hr>
      <div class="nav-btn">
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="metrics.php">📋 Metrics</a>
        <a href="usersList.php">👥 Users</a>
        <a href="">⚙️ Settings</a>
      </div>  
    </div>

    <!-- Logout button -->
    <div class="logout">
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>

  <!-- Main body container -->
  <div id="body-container">
    <!-- Settings header -->
    <div class="settings-header">
      <h1>Settings</h1>
    </div>

    <?php if ($statusMessage): ?>
      <div class="status-message <?php echo htmlspecialchars($statusType); ?>">
        <?php echo htmlspecialchars($statusMessage); ?>
      </div>
    <?php endif; ?>

    <!-- Update Profile Information -->
    <div class="settings-form">
      <h2>Update Profile Information</h2>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Current Photo:</label>
          <img src="<?php echo htmlspecialchars($photoSrc, ENT_QUOTES); ?>" alt="Profile" class="photo-preview">
        </div>

        <div class="form-group">
          <label for="photo">Change Profile Photo (optional):</label>
          <input type="file" id="photo" name="photo" accept="image/*">
        </div>

        <div class="form-group">
          <label for="staffName">Full Name (leave blank to keep current):</label>
          <input type="text" id="staffName" name="staffName" placeholder="<?php echo htmlspecialchars($fullName); ?>">
        </div>

        <div class="form-group">
          <label for="email">Email (leave blank to keep current):</label>
          <input type="email" id="email" name="email" placeholder="<?php echo htmlspecialchars($email); ?>">
        </div>

        <div class="form-group">
          <label for="mobile">Mobile Number (leave blank to keep current):</label>
          <input type="tel" id="mobile" name="mobile" placeholder="<?php echo htmlspecialchars($mobile); ?>">
        </div>

        <button type="submit" name="update_profile" class="btn btn-primary">Save Profile Changes</button>
      </form>
    </div>

    <!-- Update Login Credentials -->
    <div class="settings-form">
      <h2>Update Login Credentials</h2>
      <p style="color: #07306b; margin-bottom: 1rem;">⚠️ You will be logged out and need to login again after changing credentials.</p>
      <form method="POST">
        <div class="form-group">
          <label for="username">New Username (leave blank to keep current):</label>
          <input type="text" id="username" name="username" placeholder="<?php echo htmlspecialchars($fullName); ?>">
        </div>

        <div class="form-group">
          <label for="password">New Password (leave blank to keep current):</label>
          <input type="password" id="password" name="password" placeholder="Enter new password">
        </div>

        <button type="submit" name="update_credentials" class="btn btn-primary">Save Changes</button>
      </form>
    </div>

  </div>

  <script src="dashboard.js"></script>
</body>
</html>