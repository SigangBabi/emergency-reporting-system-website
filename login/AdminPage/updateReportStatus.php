<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';

if (!isset($_SESSION['admin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated as admin']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');

if ($id <= 0 || $status === '') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

$allowed = ['Ongoing','Accomplished','ongoing','accomplished'];
if (!in_array($status, $allowed, true)) {
    $status = ucfirst(strtolower($status));
}

$stmt = $connection->prepare("UPDATE emergency_reports SET status = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'DB prepare failed']);
    exit;
}
$stmt->bind_param('si', $status, $id);

if ($stmt->execute()) {
    // return a result flag and the new status with unique keys
    echo json_encode([
        'status' => 'success',
        'message' => 'Status updated',
        'id' => $id,
        'new_status' => $status
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed']);
}
$stmt->close();
$connection->close();
?>