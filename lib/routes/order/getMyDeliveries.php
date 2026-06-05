<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'delivery_Person') {
    http_response_code(403); echo json_encode([]); exit;
}
header('Content-Type: application/json');
include_once('../../function/orderFunction.php');

$deliveryPersonId = $_SESSION['user'];
$status = $_GET['status'] ?? 'all';

$orderObj = new Order();
$result = $orderObj->getMyDeliveries($deliveryPersonId);
$rows = [];
while ($row = $result->fetch_assoc()) {
    if ($status === 'all' || $row['orderStatus'] === $status) {
        $rows[] = $row;
    }
}
echo json_encode($rows);
