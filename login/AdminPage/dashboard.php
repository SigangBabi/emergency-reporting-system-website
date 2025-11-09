<?php
session_start();

// Prevent caching so browser won't show a cached dashboard when pressing Back
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies

include '../connect.php';

// Require login — if not logged in, redirect to admin login
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel - Dashboard</title>
  <link href="dashboard.css" rel="stylesheet">
</head>
<body>

  <!-- Side Panel -->
  <div class="side-panel">
    <div class="profile-container">
      <img src="assets/profile-icon.png" alt="">
      <div class="profile-name">
        <h1><?php echo $fullName?></h1>
        <p><?php echo $role?></p>
      </div>
    </div>
    <div class="general-panel">
      <h3>General</h3>
      <hr>

      <!-- Navigation Buttons -->
      <div class="nav-btn">
        <a href="">🏠 Dashboard</a>
        <a href="" id="open-metrics">📋 Metrics</a>
        <a href="usersList.html">👥 Users</a>
        <a href="settings.html">⚙️ Settings</a>
      </div>  
    </div>
    <div class="logout">
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>
  <div id="body-container">
    <div class="header">
      <h1>Admin Dashboard</h1>
      <a class="notif-container" href="#">
        <img src="assets/notif-icon.png">
      </a>
    </div>

    <!-- Changes to Metrics Tab -->
    <div class="dashboard-container">
      <h2>Emergency Reports</h2>
      <table>
        <thead>
          <th>No.</th>
          <th id="name">Name</th>
          <th id="location">Location</th>
          <th id="TOE">Emergency Type</th>
          <th id="time">Time</th>
          <th id="status">Status</th>
          <th>Action</th>
        </thead>
        <tbody>
          <td>1</td>
          <td>Jule Andre Evaristo</td>
          <td id="td-location">MARIA SANTOS, 123 LOPEZ STREET, BARANGAY KAPITAN KILYONG, QUEZON CITY, 1101 METRO MANILA, PHILIPPINES</td>
          <td>Flood</td>
          <td>18:30:00 - 09/15/25</td>
          <td>
            <select id="select-status">
              <option value="ongoing" selected>Ongoing</option>
              <option value="accomplished">Accomplished</option>
            </select>
          </td>
          <td>
            <button>Delete</button>
          </td>
        </tbody>
      </table>
    </div>
  </div> 

  <!-- External JS Script -->
  <script src="dashboard.js"></script>
</body>
</html>
