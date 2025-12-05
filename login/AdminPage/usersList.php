<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

include '../connect.php';

if (!isset($_SESSION['admin'])) {
    header('Location: adminLogin/adminLogin.php');
    exit();
}

$userLoggedIn = true;
$adminName = $_SESSION['admin'];
$query = mysqli_query($connection, "SELECT * FROM admin_info WHERE name='$adminName'");
$row = mysqli_fetch_array($query);

$fullName = $row['name'];
$role = $row['role'];
$photo = $row['photo'];

$photoSrc = 'assets/profile-icon.png';
if (!empty($photo)) {
    $imgInfo = @getimagesizefromstring($photo);
    if ($imgInfo && isset($imgInfo['mime'])) {
        $photoSrc = 'data:' . $imgInfo['mime'] . ';base64,' . base64_encode($photo);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
  <title>Admin Panel - Dashboard</title>
  <link href="dashboard.css" rel="stylesheet"> <!-- Link external CSS stylesheet -->
</head>
<body>
  <!-- Sidebar navigation panel -->
  <div class="side-panel">
    <!-- Profile section -->
    <div class="profile-container">
      <img src="<?php echo htmlspecialchars($photoSrc, ENT_QUOTES); ?>" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
      <div class="profile-name">
        <h1><?php echo $fullName?></h1> <!-- Admin name -->
        <p><?php echo $role?></p> <!-- User role -->
      </div>
    </div>

    <!-- General navigation links -->
    <div class="general-panel">
      <h3>General</h3>
      <hr>
      <div class="nav-btn">
        <a href="dashboard.php">🏠 Dashboard</a> <!-- Dashboard link -->
        <a href="metrics.php">📋 Metrics</a> <!-- Metrics link -->
        <a href="">👥 Users</a> <!-- Users management link -->
        <a href="settings.php">⚙️ Settings</a> <!-- Settings link -->
      </div>  
    </div>

    <!-- Logout button -->
    <div class="logout">
      <a href="#">🚪 Logout</a>
    </div>
  </div>

  <!-- Main content body -->
  <div id="body-container">
    <!-- Page header -->
    <div class="profile-header">
      <h1>Registered Users</h1>
    </div>

    <!-- User data table -->
    <div class="user-container">
      <table>
        <thead>
          <th>ID No.</th>
          <th id="name">Name</th>
          <th id="location">Location</th>
          <th id="mobile">Mobile No.</th>
          <th id="email">Email</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php
            $userQuery = mysqli_query($connection, "SELECT * FROM user_info");
            while ($userInfo = mysqli_fetch_array($userQuery)) {
              $id = htmlspecialchars($userInfo['id'] ?? '', ENT_QUOTES);
              $name = htmlspecialchars($userInfo['name'] ?? '', ENT_QUOTES);
              $location = htmlspecialchars($userInfo['address'] ?? '', ENT_QUOTES);
              $mobile = htmlspecialchars($userInfo['mobile_no'] ?? '', ENT_QUOTES);
              $email = htmlspecialchars($userInfo['email'] ?? '', ENT_QUOTES);

              echo "<tr>
                      <td>{$id}</td>
                      <td>{$name}</td>
                      <td id='td-location'>{$location}</td>
                      <td>{$mobile}</td>
                      <td>{$email}</td>
                      <td>
                        <button class='delete-user' data-id='{$id}'>Delete</button>
                      </td>
                    </tr>";
            }
          
          
          
          ?>
        </tbody>
      </table>
    </div>
  </div> 

  <!-- External JavaScript file -->
  <script src="usersList.js"></script>
</body>
</html>
