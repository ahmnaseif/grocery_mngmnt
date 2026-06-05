<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin', 'employee'])) {
    http_response_code(403); echo json_encode([]); exit;
}
header('Content-Type: application/json');
include_once('../../function/orderFunction.php');

$orderObj = new Order();
$status = $_GET['status'] ?? 'all';

// Add getOrdersByStatus to orderFunction if needed, or use getAllOrders + filter
$result = $orderObj->getAllOrders();
$rows = [];
while ($row = $result->fetch_assoc()) {
    if ($status === 'all' || $row['orderStatus'] === $status) {
        $rows[] = $row;
    }
}
echo json_encode($rows);
