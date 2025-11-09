<?php
session_start();
header('Content-Type: application/json');

// Ensure DB connection (adjust path if needed)
include '../connect.php';

if (!isset($_SESSION['name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$name = $_SESSION['name'];
$emergency_type = trim($_POST['emergency_type'] ?? '');
$location = trim($_POST['location'] ?? '');
$other_emergency = trim($_POST['other_emergency'] ?? '');
$save_address = isset($_POST['save_address']) && $_POST['save_address'] === '1';

// Basic validation
if ($emergency_type === '') {
    echo json_encode(['status' => 'error', 'message' => 'Emergency type is required']);
    exit;
}
if ($location === '') {
    $location = 'Location not provided';
}

// Use existing connection
$mysqli = $connection;

// Insert report
$stmt = $mysqli->prepare("INSERT INTO emergency_reports (`name`, `location`, `emergency_type`, `other_emergency`, `time`) VALUES (?, ?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database prepare failed (insert)']);
    exit;
}

$stmt->bind_param('ssss', $name, $location, $emergency_type, $other_emergency);

if (!$stmt->execute()) {
    $stmt->close();
    echo json_encode(['status' => 'error', 'message' => 'Failed to save report']);
    exit;
}
$stmt->close();

// Optionally save address into user_info if requested
if ($save_address && $location !== '') {
    $upd = $mysqli->prepare("UPDATE user_info SET address = ? WHERE name = ?");
    if ($upd) {
        $upd->bind_param('ss', $location, $name);
        $upd->execute();
        $upd->close();
    }
}

echo json_encode(['status' => 'success', 'message' => 'Your report has been submitted.']);
$mysqli->close();
?>