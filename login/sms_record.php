<?php
// sms_record.php
session_start();
include 'cfg/dbconnect.php';

// Fetch all SMS alerts, newest first
$sql = "SELECT id, number, message, status FROM sms_alert ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel - SMS Record</title>
  <style>
    body { margin:0; font-family:Arial,sans-serif; background:#fff; color:#333; }
    .sidebar { width:220px; height:100vh; background:#b71c1c; position:fixed; top:0; left:0; padding-top:20px; }
    .sidebar .logo img { width:150px; margin:0 auto 20px; display:block; filter:brightness(0) invert(1); }
    .sidebar a { display:block; color:#fff; padding:15px 20px; text-decoration:none; font-weight:600; }
    .sidebar a.active, .sidebar a:hover { background:#f44336; }
    .sidebar-line { height:1px; background:#ef9a9a; margin:10px 0; }
    .container { margin-left:220px; padding:20px; }
    .header { background:#ffcdd2; padding:15px; border-radius:8px; margin-bottom:20px; display:flex; justify-content:flex-end; align-items:center; color:#b71c1c; }
    .img-case img { width:35px; height:35px; border-radius:50%; border:2px solid #b71c1c; }
    .dashboard-content h2 { color:#b71c1c; margin-bottom:15px; }
    table { width:100%; border-collapse:collapse; }
    table, th, td { border:1px solid #b71c1c; }
    th, td { padding:10px; text-align:left; color:#b71c1c; }
    th { background:#ffcdd2; font-weight:700; }
    .action-btn { display:inline-block; padding:5px 10px; margin-right:5px; text-decoration:none; border-radius:4px; font-weight:600; color:white; }
    .accept-btn { background-color:#4caf50; }
    .reject-btn { background-color:#ff9800; }
    .detail-btn { background-color:#2196f3; }
    .delete-btn { background-color:#f44336; }
  </style>
</head>
<body>

  <div class="sidebar">
    <div class="logo"><img src="logo.png" alt="Logo"></div>
    <div class="sidebar-line"></div>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="emergency_record.php">📋 Emergency Record</a>
    <a class="active" href="sms_record.php">📨 SMS Record</a>
    <a href="users.php">👥 Users</a>
    <a href="settings.php">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="container">
    <div class="header">
      <div class="img-case"><img src="user.png" alt="User"></div>
    </div>
    <div class="dashboard-content">
      <h2>SMS Record</h2>
      <table>
        <thead>
          <tr>
            <th>Num#</th>
            <th>Recipient</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['number']) ?></td>
              <td><?= htmlspecialchars($row['message']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
              <td>
                <?php if ($row['status'] === 'pending'): ?>
                  <a
                    class="action-btn accept-btn"
                    href="sms_update_status.php?id=<?= $row['id'] ?>&action=accept"
                    onclick="return confirm('Accept this SMS?')"
                  >Accept</a>
                  <a
                    class="action-btn reject-btn"
                    href="sms_update_status.php?id=<?= $row['id'] ?>&action=reject"
                    onclick="return confirm('Reject this SMS?')"
                  >Reject</a>
                <?php endif; ?>

                <a class="action-btn detail-btn" href="sms_detail.php?id=<?= $row['id'] ?>">Detail</a>
                <a class="action-btn delete-btn" href="sms_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this alert?')">Delete</a>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No SMS alerts found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
