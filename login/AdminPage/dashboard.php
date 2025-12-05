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
  <link href="dashboard.css" rel="stylesheet">
</head>
<body>

  <!-- Side Panel -->
  <div class="side-panel">
    <div class="profile-container">
      <img src="<?php echo htmlspecialchars($photoSrc, ENT_QUOTES); ?>" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
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
        <a href="metrics.php">📋 Metrics</a>
        <a href="usersList.php">👥 Users</a>
        <a href="settings.php">⚙️ Settings</a>
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
          <th id='name'>Name</th>
          <th id='location'>Location</th>
          <th id='TOE'>Emergency Type</th>
          <th id='time'>Time</th>
          <th id='status'>Status</th>
          <th>Action</th>
        </thead>
        <tbody id="reports-body">
        <?php
            $getEmergencyReport = mysqli_query($connection, "SELECT * FROM emergency_reports ORDER BY time DESC");
            $counter = 0;
            while($emergencyInfo = mysqli_fetch_assoc($getEmergencyReport)){
              $counter++;
              $id = isset($emergencyInfo['id']) ? (int)$emergencyInfo['id'] : $counter;
              $nameOfReporter = htmlspecialchars($emergencyInfo['name'] ?? '', ENT_QUOTES);
              $locationOfEmergency = htmlspecialchars($emergencyInfo['location'] ?? '', ENT_QUOTES);
              $typeOfEmergency = htmlspecialchars($emergencyInfo['emergency_type'] ?? '', ENT_QUOTES);
              $otherEmergency = htmlspecialchars($emergencyInfo['other_emergency'] ?? '', ENT_QUOTES);
              $timeOfReport = htmlspecialchars($emergencyInfo['time'] ?? '', ENT_QUOTES);
              $statusOfReport = htmlspecialchars($emergencyInfo['status'] ?? 'Ongoing', ENT_QUOTES);

              $isOtherEmergency = ($typeOfEmergency === "Other") ? $otherEmergency : $typeOfEmergency;

              echo "<tr id='report-row-{$id}'>
                      <td>{$counter}</td>
                      <td>{$nameOfReporter}</td>
                      <td id='td-location'>{$locationOfEmergency}</td>
                      <td>{$isOtherEmergency}</td>
                      <td>{$timeOfReport}</td>
                      <td>
                        <select class='status-select' data-id='{$id}'>
                          <option value='Ongoing'".($statusOfReport === 'Ongoing' ? ' selected' : '').">Ongoing</option>
                          <option value='Accomplished'".($statusOfReport === 'Accomplished' ? ' selected' : '').">Accomplished</option>
                        </select>
                      </td>
                      <td>
                        <button class='delete-report' data-id='{$id}'>See Details</button>
                      </td>
                    </tr>";
            }
        ?>
          </tbody>
        </table>
      </div>
  </div>
  <script src="dashboard.js"></script>
</body>
</html>
