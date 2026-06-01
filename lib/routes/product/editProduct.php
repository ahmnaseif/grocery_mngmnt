<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin', 'employee'])) {
    http_response_code(403);
    echo 'unauthorized';
    exit;
}

include_once('../../function/productFunction.php');

$pid              = $_POST['pid']              ?? '';
$productName      = $_POST['productName']      ?? '';
$productCategory  = $_POST['productCategory']  ?? '';
$productSupplier  = $_POST['productSupplier']  ?? '';
$productDescription = $_POST['productDescription'] ?? '';
$productPrice     = $_POST['productPrice']     ?? 0;
$priceType        = $_POST['priceType']        ?? '';

$prdobject = new Product();

$hasNewImage = isset($_FILES['productImg']) && $_FILES['productImg']['name'] !== '';

if ($hasNewImage) {
    $productImgName     = $_FILES['productImg']['name'];
    $productImgSize     = $_FILES['productImg']['size'];
    $productImgType     = $_FILES['productImg']['type'];
    $productImgLocation = $_FILES['productImg']['tmp_name'];

    $result = $prdobject->editproduct2(
        $productName, $productCategory, $productSupplier,
        $productDescription, $productPrice, $priceType, $pid,
        $productImgName, $productImgSize, $productImgType, $productImgLocation
    );
} else {
    $result = $prdobject->editProduct(
        $productName, $productCategory, $productSupplier,
        $productDescription, $productPrice, $priceType, $pid
    );
}

echo $result;
