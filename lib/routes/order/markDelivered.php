<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'delivery_Person') {
    http_response_code(403); echo json_encode(['status'=>false,'message'=>'Unauthorized']); exit;
}
include_once('../../function/orderFunction.php');
$orderId          = $_POST['orderId'] ?? '';
$deliveryPersonId = $_SESSION['user'];
$orderObj         = new Order();
echo $orderObj->markDelivered($orderId, $deliveryPersonId);
