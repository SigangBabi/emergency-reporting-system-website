<?php
session_start();
include "cfg/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name     = $_POST['name']     ?? null;
    $location = $_POST['location'] ?? '';
    $type     = $_POST['type']     ?? '';
    $details  = $_POST['details']  ?? '';

    // Prepare & execute
    $stmt = $conn->prepare("
        INSERT INTO emergency_reports (name, location, type, details) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $name, $location, $type, $details);

    if ($stmt->execute()) {
        // JS alert + redirect
        echo "<script>
              alert('✅ Emergency report submitted successfully!');
              window.location.href = 'Usersdashboard.html';
            </script>";
        exit;

    } else {
        $error = $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Submit Emergency Report</title>
  <style>
    /* your existing styles… */
  </style>
</head>
<body>

  <h1>📢 Report an Emergency</h1>

  <?php if (!empty($error)): ?>
    <p style="color:red; text-align:center;">Error submitting report: <?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form action="reports.php" method="POST" onsubmit="return showAlert()">
    <!-- your existing form fields… -->
  </form>

  <div style="text-align: center; margin-top: 1rem;">
    <button onclick="history.back()">⬅️ Back</button>
  </div>

  <script>
    function showAlert() {
      alert("🚨 Emergency Report is being sent...");
      return true;
    }
  </script>

</body>
</html>
