<?php

//include the function page
include_once('../../function/empFunction.php');



$empObj = new Employee();
$searchtext = $_GET['searchtext'];

$result = $empObj->loadDataSearch($searchtext);
echo($result);
?>