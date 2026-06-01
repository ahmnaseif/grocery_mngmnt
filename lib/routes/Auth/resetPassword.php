<?php
include_once('../../function/AuthFunction.php');

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'verify') {
    $email = $_POST['email'] ?? '';
    if ($email === '') {
        echo json_encode(['status' => false, 'message' => 'Email is required']);
        exit;
    }
    $auth = new Auth();
    $check = $auth->dbResult->prepare(
        "SELECT loginId FROM login_tbl WHERE loginEmail = ? AND loginRole = 'customer' AND d_status = 0 AND loginStatus = 1"
    );
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['status' => true, 'message' => 'Email found']);
    } else {
        echo json_encode(['status' => false, 'message' => 'No active customer account found with that email']);
    }
    exit;
}

if ($action === 'reset') {
    $email       = $_POST['email'] ?? '';
    $nic         = $_POST['nic'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    if ($email === '' || $nic === '' || $newPassword === '') {
        echo json_encode(['status' => false, 'message' => 'All fields are required']);
        exit;
    }

    if (strlen($newPassword) < 4) {
        echo json_encode(['status' => false, 'message' => 'Password must be at least 4 characters']);
        exit;
    }

    $auth = new Auth();
    echo $auth->resetPassword($email, $nic, $newPassword);
    exit;
}

echo json_encode(['status' => false, 'message' => 'Invalid action']);
?>
