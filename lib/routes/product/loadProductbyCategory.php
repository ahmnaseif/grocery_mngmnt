<?php

//include the function page
include_once('../../function/productFunction.php');


$prdobject = new Product();
$catId = $_GET['catId'];


$result = $prdobject->loadProductByCategory($catId);
echo($result);
?>
