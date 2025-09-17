<?php
// sms_update_status.php
session_start();
include 'cfg/dbconnect.php';

// Validate inputs
if (
    !isset($_GET['id']) ||
    !isset($_GET['action']) ||
    !in_array($_GET['action'], ['accept','reject'])
) {
    header('Location: sms_record.php');
    exit;
}

$id     = (int) $_GET['id'];
$action = $_GET['action'] === 'accept' ? 'accepted' : 'rejected';

// Update the SMS row
$stmt = $conn->prepare("
    UPDATE sms_alert
       SET status = ?
     WHERE id = ?
");
$stmt->bind_param('si', $action, $id);
$stmt->execute();
$stmt->close();

// Redirect back to the SMS record list
header('Location: sms_record.php');
exit;
?>
