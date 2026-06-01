<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin', 'employee'])) {
    http_response_code(403);
    echo json_encode(['status' => false, 'message' => 'Unauthorized']);
    exit;
}
include_once('../../function/productFunction.php');

// $ProdName = $_POST['productName'];
// $ProdCategory = $_POST['productCategory'];
// $ProdSupplier = $_POST['productSupplier'];
// $ProdDes = $_POST['productDescription'];

// $ProdImageName = $_FILES['formfile']['name'];
// $ProdImageSize = $_FILES['formfile']['size'];
// $ProdImageType = $_FILES['formfile']['type'];
// $ProdImageLocation = $_FILES['formfile']['temp_name'];


$productName = $_POST['productName'];
$productCategory = $_POST['productCategory'];
$productSupplier = $_POST['productSupplier'];
$productDescription = $_POST['productDescription'];
$productPrice = $_POST['productPrice'];
$priceType = $_POST['priceType'];
$productImgName = $_FILES['productImg']['name'];
$productImgSize = $_FILES['productImg']['size'];
$productImgType = $_FILES['productImg']['type'];
$productImgLocation = $_FILES['productImg']['tmp_name'];

$prdobject = new Product();

//$result = $prdobject->addproduct($ProdName, $ProdCategory, $ProdSupplier, $ProdDes, $ProdImageName, $ProdImageSize, $ProdImageType, $ProdImageLocation);
$result = $prdobject->addProduct($productName, $productCategory, $productSupplier, $productDescription, $productPrice, $priceType,  $productImgName, $productImgSize, $productImgType, $productImgLocation);




echo($result);
?>