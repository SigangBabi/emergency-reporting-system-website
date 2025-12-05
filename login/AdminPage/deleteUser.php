
<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';

// only admin can delete
if (!isset($_SESSION['admin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated as admin']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid id']);
    exit;
}

$stmt = $connection->prepare("DELETE FROM user_info WHERE id = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'DB prepare failed']);
    exit;
}
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User deleted', 'id' => $id]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
}
$stmt->close();
$connection->close();
?>