<?php
include_once('../../function/main.php');
$main = new Main();
$conn = $main->dbResult;

$productId = $_GET['id'] ?? '';
if (!$productId) {
    http_response_code(404);
    exit;
}

$stmt = $conn->prepare("SELECT productImageData, productImageMime, productImg FROM product_tbl WHERE productId = ? AND d_status = 0");
$stmt->bind_param("s", $productId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    http_response_code(404);
    exit;
}

// Serve BLOB image if available
if (!empty($row['productImageData'])) {
    header('Content-Type: ' . ($row['productImageMime'] ?: 'image/jpeg'));
    header('Cache-Control: public, max-age=86400');
    echo $row['productImageData'];
    exit;
}

// Fall back to file-based image (for products added via admin upload)
if (!empty($row['productImg'])) {
    $filePath = __DIR__ . '/../../' . $row['productImg'];
    if (file_exists($filePath)) {
        $mime = mime_content_type($filePath) ?: 'image/jpeg';
        header('Content-Type: ' . $mime);
        header('Cache-Control: public, max-age=3600');
        readfile($filePath);
        exit;
    }
}

http_response_code(404);
exit;
