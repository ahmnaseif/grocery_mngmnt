<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    echo json_encode(['status' => false]);
    exit;
}

include_once('../../function/paymentFunction.php');

$methodId   = intval($_POST['methodId'] ?? 0);
$customerId = $_SESSION['user'];

$pmObj = new PaymentMethod();
echo $pmObj->deleteMethod($methodId, $customerId);
?>
