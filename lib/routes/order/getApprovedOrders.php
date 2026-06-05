<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'delivery_Person') {
    http_response_code(403); echo json_encode([]); exit;
}
header('Content-Type: application/json');
include_once('../../function/orderFunction.php');
$orderObj = new Order();
$result = $orderObj->getApprovedOrders();
$rows = [];
while ($row = $result->fetch_assoc()) $rows[] = $row;
echo json_encode($rows);
