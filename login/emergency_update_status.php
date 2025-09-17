<?php
// emergency_update_status.php
session_start();
include 'cfg/dbconnect.php';

// ensure we have an ID and action
if (
    !isset($_GET['id']) ||
    !isset($_GET['action']) ||
    !in_array($_GET['action'], ['accept','reject'])
) {
    header('Location: emergency_record.php');
    exit;
}

$id     = (int) $_GET['id'];
$action = $_GET['action'] === 'accept' ? 'accepted' : 'rejected';

// update the record’s status
$stmt = $conn->prepare("
    UPDATE emergency_reports 
       SET status = ?
     WHERE id = ?
");
$stmt->bind_param('si', $action, $id);
$stmt->execute();
$stmt->close();

// go back to the admin panel list
header('Location: emergency_record.php');
exit;
?>
