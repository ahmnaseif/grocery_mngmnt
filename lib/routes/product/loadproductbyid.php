<?php

//include the function page
include_once('../../function/productFunction.php');


$prdobject = new Product();
$pid = $_GET['pid'];


//$result = $prdobject->loaddatabyid($userid);
$result = $prdobject->loadProductById($pid);
echo($result);
?>