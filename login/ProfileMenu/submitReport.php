<?php
session_start();
header('Content-Type: application/json');

include '../connect.php';

if (!isset($_SESSION['name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$name = trim($_POST['reporter_name'] ?? $_SESSION['name']);
$emergency_type = trim($_POST['emergency_type'] ?? '');
$other_emergency = trim($_POST['other_emergency'] ?? '');
$location = trim($_POST['location'] ?? '');
$mobile_no = trim($_POST['mobile_no'] ?? '');
$short_desc = trim($_POST['short_desc'] ?? '');
$save_address = isset($_POST['save_address']) && $_POST['save_address'] === '1';

// Basic validation
if ($emergency_type === '') {
    echo json_encode(['status' => 'error', 'message' => 'Emergency type is required']);
    exit;
}
if ($location === '') {
    $location = 'Location not provided';
}
if ($mobile_no === '') {
    $mobile_no = 'Not provided';
}

$mysqli = $connection;

// handle optional photo upload
$emer_photo_path = '';
if (!empty($_FILES['emer_photo']) && $_FILES['emer_photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../uploads/emer_photos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fileTmp = $_FILES['emer_photo']['tmp_name'];
    $origName = basename($_FILES['emer_photo']['name']);
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowed)) {
        // reject invalid types but continue without photo
        $emer_photo_path = '';
    } else {
        $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $uploadDir . $newName;
        if (move_uploaded_file($fileTmp, $dest)) {
            // store relative path for DB
            $emer_photo_path = 'uploads/emer_photos/' . $newName;
        }
    }
}

// Insert report with default status 'Ongoing'
$stmt = $mysqli->prepare("INSERT INTO emergency_reports (`name`, `location`, `mobile_no`, `emergency_type`, `other_emergency`, `short_desc`, `emer_photo`, `status`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database prepare failed (insert)']);
    exit;
}

$status = 'Ongoing';
$stmt->bind_param('ssssssss', $name, $location, $mobile_no, $emergency_type, $other_emergency, $short_desc, $emer_photo_path, $status);

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
        $upd->bind_param('ss', $location, $_SESSION['name']);
        $upd->execute();
        $upd->close();
    }
}

echo json_encode(['status' => 'success', 'message' => 'Your report has been submitted.']);
$mysqli->close();
?>