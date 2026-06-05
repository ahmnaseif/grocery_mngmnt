<?php
session_start();
header('Content-Type: application/json');
include_once('../../function/orderFunction.php');
$orderId = $_GET['orderId'] ?? '';
if (!$orderId) { echo json_encode([]); exit; }
$orderObj = new Order();
$result = $orderObj->getOrderItems($orderId);
$rows = [];
while ($row = $result->fetch_assoc()) $rows[] = $row;
echo json_encode($rows);
