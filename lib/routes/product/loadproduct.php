<?php

//include the function page
include_once('../../function/productFunction.php');


$prdobject = new Product();

$result = $prdobject->loadProductData();

echo($result);
?>