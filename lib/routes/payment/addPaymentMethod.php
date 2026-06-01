<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    echo json_encode(['status' => false, 'message' => 'Not logged in']);
    exit;
}

include_once('../../function/paymentFunction.php');

$customerId  = $_SESSION['user'];
$cardHolder  = $_POST['cardHolder'] ?? '';
$cardNumber  = preg_replace('/\D/', '', $_POST['cardNumber'] ?? '');
$cardExpiry  = $_POST['cardExpiry'] ?? '';
$cardType    = $_POST['cardType'] ?? 'Visa';

if (strlen($cardNumber) < 4) {
    echo json_encode(['status' => false, 'message' => 'Invalid card number']);
    exit;
}

$lastFour = substr($cardNumber, -4);
$pmObj = new PaymentMethod();
echo $pmObj->addMethod($customerId, $cardHolder, $lastFour, $cardExpiry, $cardType);
?>
