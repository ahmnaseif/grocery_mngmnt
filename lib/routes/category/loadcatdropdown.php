<?php

//include the function page
include_once('../../function/categoryFunction.php');


$categoryObj = new Category();

$result = $categoryObj->loadcatdropdown();

echo($result);
?>