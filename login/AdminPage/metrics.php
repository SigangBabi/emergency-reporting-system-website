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

/**
 * Aggregate counts for emergency types and users.
 * Adjust emergency type strings below if your DB stores different values.
 */
$counts = [
  'flood'   => 0,
  'crime'   => 0,
  'fire'    => 0,
  'medical' => 0,
  'other'   => 0
];

$sql = "
  SELECT
    SUM(CASE WHEN LOWER(emergency_type) = 'flood'   THEN 1 ELSE 0 END) AS flood,
    SUM(CASE WHEN LOWER(emergency_type) = 'crime'   THEN 1 ELSE 0 END) AS crime,
    SUM(CASE WHEN LOWER(emergency_type) = 'fire'    THEN 1 ELSE 0 END) AS fire,
    SUM(CASE WHEN LOWER(emergency_type) = 'medical' THEN 1 ELSE 0 END) AS medical,
    SUM(CASE WHEN LOWER(emergency_type) = 'other'   THEN 1 ELSE 0 END) AS other_count
  FROM emergency_reports
";
$res = mysqli_query($connection, $sql);
if ($res) {
  $r = mysqli_fetch_assoc($res);
  if ($r) {
    $counts['flood']   = (int)($r['flood'] ?? 0);
    $counts['crime']   = (int)($r['crime'] ?? 0);
    $counts['fire']    = (int)($r['fire'] ?? 0);
    $counts['medical'] = (int)($r['medical'] ?? 0);
    $counts['other']   = (int)($r['other_count'] ?? 0);
  }
  mysqli_free_result($res);
}

// count users
$userCount = 0;
$r2 = mysqli_query($connection, "SELECT COUNT(*) AS user_count FROM user_info");
if ($r2) {
  $row2 = mysqli_fetch_assoc($r2);
  $userCount = (int)($row2['user_count'] ?? 0);
  mysqli_free_result($r2);
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
      <img src="assets/profile-icon.png" alt=""> <!-- Profile icon -->
      <div class="profile-name">
        <h1><?php echo htmlspecialchars($fullName); ?></h1> <!-- Admin name -->
        <p><?php echo htmlspecialchars($role); ?></p> <!-- User role -->
      </div>
    </div>

    <!-- General navigation links -->
    <div class="general-panel">
      <h3>General</h3>
      <hr>
      <div class="nav-btn">
        <a href="dashboard.php">🏠 Dashboard</a> <!-- Dashboard link -->
        <a href="">📋 Metrics</a> <!-- Metrics link -->
        <a href="">👥 Users</a> <!-- Users management link -->
        <a href="settings.html">⚙️ Settings</a> <!-- Settings link -->
      </div>  
    </div>

    <!-- Logout button -->
    <div class="logout">
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>

  <!-- Main content body -->
  <div id="body-container">
    <div class="metrics-header">
      <h1>Metrics</h1>
    </div>
    <div class="metrics-container">
      <a href="#" id="flood">
        <img src="assets/flood.png">
        <div class="metric">
          <h3>Flood</h3>
          <p>No. of Flood reports</p>
          <p class="count"><?php echo $counts['flood']; ?></p>
        </div>
      </a>
      <a href="#" id="crime">
        <img src="assets/crime.png">
        <div class="metric">
          <h3>Crime</h3>
          <p>No. of Criminal Reports</p>
          <p class="count"><?php echo $counts['crime']; ?></p>
        </div>
      </a>
      <a href="#" id="fire">
        <img src="assets/fire.png">
        <div class="metric">
          <h3>Fire</h3>
          <p>No. of Fire Reports</p>
          <p class="count"><?php echo $counts['fire']; ?></p>
        </div>
      </a>
      <a href="#" id="medical">
        <img src="assets/med.png">
        <div class="metric">
          <h3>Medic</h3>
          <p>No. of Medical Reports</p>
          <p class="count"><?php echo $counts['medical']; ?></p>
        </div>
      </a>
      <a href="#" id="other">
        <img src="assets/hazard.png">
        <div class="metric">
          <h3>Other</h3>
          <p>No. of Other Concerns</p>
          <p class="count"><?php echo $counts['other']; ?></p>
        </div>
      </a>
    </div>
    <a class="no-users" href="usersList.html">
      <h1>Current No. of Users:</h1>
      <h1><?php echo $userCount; ?></h1>
    </a>
  <script src="dashboard.js"></script>
</body>
</html>
