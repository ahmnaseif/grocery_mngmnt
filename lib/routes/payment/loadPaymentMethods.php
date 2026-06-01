<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    echo json_encode([]);
    exit;
}

include_once('../../function/paymentFunction.php');

$pmObj = new PaymentMethod();
$result = $pmObj->getByCustomer($_SESSION['user']);
$methods = [];
while ($row = $result->fetch_assoc()) {
    $methods[] = $row;
}
echo json_encode($methods);
?>
