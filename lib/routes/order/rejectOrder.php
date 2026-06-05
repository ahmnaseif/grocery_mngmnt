<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin', 'employee'])) {
    http_response_code(403); echo json_encode(['status'=>false,'message'=>'Unauthorized']); exit;
}
include_once('../../function/orderFunction.php');
$orderId    = $_POST['orderId'] ?? '';
$employeeId = $_SESSION['user'];
$orderObj   = new Order();
echo $orderObj->rejectOrder($orderId, $employeeId);
