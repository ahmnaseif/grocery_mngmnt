<?php
header('Content-Type: application/json');
include_once('../../function/main.php');

$keyword = trim($_GET['q'] ?? '');
if (strlen($keyword) < 2) {
    echo json_encode(['url' => null]);
    exit;
}

// Map category ID to page file
$catPages = [
    'CAT001' => 'Vegetables',
    'CAT002' => 'Fruits',
    'CAT003' => 'Dairy_Products',
    'CAT004' => 'Baking_Pantry',
    'CAT005' => 'Pasta_Rice',
    'CAT006' => 'Spices',
    'CAT007' => 'Snacks',
    'CAT008' => 'Desserts_Mix',
    'CAT009' => 'Coffee_Tea_Malts',
    'CAT010' => 'Beverages',
    'CAT011' => 'Baby_Products',
    'CAT012' => 'Health_Beauty',
    'CAT013' => 'Household',
    'CAT014' => 'Stationery',
];

$main = new Main();
$like = '%' . $keyword . '%';
$stmt = $main->dbResult->prepare("SELECT productCategory FROM product_tbl WHERE d_status = 0 AND (productName LIKE ? OR productDescription LIKE ?) LIMIT 1");
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row && isset($catPages[$row['productCategory']])) {
    echo json_encode(['url' => '/grocery_mngmnt/' . $catPages[$row['productCategory']] . '.php']);
} else {
    echo json_encode(['url' => null]);
}
