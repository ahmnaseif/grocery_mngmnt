<?php
include_once('../../function/searchFunction.php');

$keyword = $_GET['q'] ?? '';
if (strlen(trim($keyword)) < 2) {
    echo '<p class="text-muted">Please enter at least 2 characters to search.</p>';
    exit;
}

$searchObj = new Seacrh();
$count = $searchObj->searchproduct(trim($keyword));

if ($count === 0) {
    echo '<p class="text-muted">No products found for "<strong>' . htmlspecialchars($keyword) . '</strong>".</p>';
}
