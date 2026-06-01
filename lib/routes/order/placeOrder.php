<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    echo json_encode(['status' => false, 'message' => 'Not logged in']);
    exit;
}

if (empty($_SESSION['cart'])) {
    echo json_encode(['status' => false, 'message' => 'Cart is empty']);
    exit;
}

include_once('../../function/orderFunction.php');
include_once('../../function/paymentFunction.php');

$customerId     = $_SESSION['user'];
$paymentMethod  = $_POST['paymentMethod'] ?? 'cash_on_delivery';
$deliveryAddress = $_POST['deliveryAddress'] ?? '';
$saveCard       = $_POST['saveCard'] ?? '0';
$cardHolder     = $_POST['cardHolder'] ?? '';
$cardNumber     = $_POST['cardNumber'] ?? '';
$cardExpiry     = $_POST['cardExpiry'] ?? '';
$cardType       = $_POST['cardType'] ?? 'Visa';

if ($paymentMethod === 'saved_card') {
    $paymentMethod = 'card';
}

$orderObj = new Order();
$result = $orderObj->placeOrder($customerId, $_SESSION['cart'], $paymentMethod, $deliveryAddress);
$resultData = json_decode($result, true);

if ($resultData['status'] && $paymentMethod === 'card' && $saveCard === '1' && strlen($cardNumber) >= 4) {
    $lastFour = substr(preg_replace('/\D/', '', $cardNumber), -4);
    $pmObj = new PaymentMethod();
    $pmObj->addMethod($customerId, $cardHolder, $lastFour, $cardExpiry, $cardType);
}

if ($resultData['status']) {
    $_SESSION['cart'] = [];
}

echo $result;
?>
