<?php
session_start();


if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /grocery_mngmnt/login.php');
    exit;
}

//  cart operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Please login to add items to cart', 'require_login' => true]);
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';
    $product_price = floatval($_POST['product_price'] ?? 0);
    $product_image = $_POST['product_image'] ?? '';
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if ($action === 'add') {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'image' => $product_image,
                'quantity' => 1
            ];
        }
        $total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));
        echo json_encode(['success' => true, 'cart_count' => $total_items]);
        exit;
    }
    
    if ($action === 'remove') {
        unset($_SESSION['cart'][$product_id]);
        $total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));
        echo json_encode(['success' => true, 'cart_count' => $total_items]);
        exit;
    }
    
    if ($action === 'update') {
        $delta = intval($_POST['quantity'] ?? 0);
        $currentQty = $_SESSION['cart'][$product_id]['quantity'] ?? 0;
        $newQty = $currentQty + $delta;
        if ($newQty <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $newQty;
        }
        $total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));
        echo json_encode(['success' => true, 'cart_count' => $total_items]);
        exit;
    }
    
    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart_count' => 0]);
        exit;
    }
}

// Handle get cart request
if (isset($_GET['get_cart'])) {
    header('Content-Type: application/json');
    if (isset($_SESSION['user'])) {
        echo json_encode($_SESSION['cart'] ?? []);
    } else {
        echo json_encode([]);
    }
    exit;
}

// Check login status
if (isset($_GET['check_login'])) {
    header('Content-Type: application/json');
    if (isset($_SESSION['user'])) {
        echo json_encode(['logged_in' => true, 'user_name' => $_SESSION['user_name'] ?? '']);
    } else {
        echo json_encode(['logged_in' => false]);
    }
    exit;
}

$currentpage = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['user']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Grocery Management System'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #1a2a3a 0%, #0f1a24 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .logo span,
        .sidebar.collapsed .nav-label {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px;
        }
        
        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 20px;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo i {
            font-size: 28px;
            color: #28a745;
        }
        
        .logo span {
            font-size: 20px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .toggle-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .toggle-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .nav-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }
        
        .nav-item {
            margin: 5px 15px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .nav-link:hover {
            background: rgba(40,167,69,0.2);
            color: #28a745;
        }
        
        .nav-link.active {
            background: #28a745;
            color: white;
        }
        
        .nav-link i {
            width: 24px;
            font-size: 18px;
            text-align: center;
        }
        
        .nav-label {
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: 80px;
        }
        
        /* Header */
        .top-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a2a3a;
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        /* User Profile Section */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-profile:hover {
            background: #e9ecef;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #1a2a3a;
        }
        
        .user-email {
            font-size: 11px;
            color: #6c757d;
        }
        
        .logout-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
        }
        
        .logout-btn:hover {
            text-decoration: underline;
        }
        
        /* Cart Button */
        .cart-btn {
            position: relative;
            background: #f8f9fa;
            border: none;
            padding: 10px 20px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .cart-btn:hover {
            background: #e9ecef;
        }
        
        .cart-count {
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Login Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        .login-modal {
            background: white;
            border-radius: 20px;
            width: 450px;
            max-width: 90%;
            padding: 30px;
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .modal-header h2 {
            margin-bottom: 5px;
            color: #1a2a3a;
        }
        
        .modal-header p {
            color: #6c757d;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1a2a3a;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40,167,69,0.1);
        }
        
        .login-btn, .register-btn {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 15px;
        }
        
        .login-btn:hover, .register-btn:hover {
            background: #1e7e34;
        }
        
        .switch-mode {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .switch-mode a {
            color: #28a745;
            text-decoration: none;
            cursor: pointer;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            display: none;
        }
        
        /* Cart Modal */
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }
        
        .cart-overlay.show {
            display: block;
        }
        
        .cart-modal {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 20px rgba(0,0,0,0.1);
            z-index: 1050;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .cart-modal.open {
            right: 0;
        }
        
        .cart-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-header h3 {
            margin: 0;
            font-size: 20px;
        }
        
        .close-cart {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #6c757d;
        }
        
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .cart-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .cart-item-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: #28a745;
            font-weight: 600;
            font-size: 14px;
        }
        
        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .cart-item-actions button {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .cart-item-actions button:hover {
            background: #f0f0f0;
        }
        
        .cart-footer {
            padding: 20px;
            border-top: 1px solid #eee;
        }
        
        .cart-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            margin-bottom: 10px;
            cursor: pointer;
        }
        
        .checkout-btn:hover {
            background: #1e7e34;
        }
        
        .clear-cart-btn {
            width: 100%;
            padding: 10px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        
        .clear-cart-btn:hover {
            background: #c82333;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            z-index: 1060;
            display: none;
            animation: slideIn 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .toast-notification.error {
            background: #dc3545;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Products Container */
        .products-container {
            padding: 30px;
        }
        
        /* Product Cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .product-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        
        .product-body {
            padding: 20px;
        }
        
        .category-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 12px;
        }
        
        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a2a3a;
        }
        
        .weight-info {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-size: 22px;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 15px;
        }
        
        .product-price small {
            font-size: 12px;
            color: #6c757d;
            font-weight: normal;
        }
        
        .stock-badge {
            font-size: 12px;
            color: #28a745;
        }
        
        .add-to-cart {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .add-to-cart:hover {
            background: #1e7e34;
            transform: scale(1.02);
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-cart i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #28a745;
            border-radius: 3px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar .logo span,
            .sidebar .nav-label {
                display: none;
            }
            .main-content {
                margin-left: 80px;
            }
            .cart-modal {
                width: 100%;
                right: -100%;
            }
            .category-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }
            .products-container {
                padding: 15px;
            }
            .user-info {
                display: none;
            }
            .user-profile {
                padding: 8px;
            }
        }
    </style>
    <style>
        /* Account for fixed top navbar */
        .sidebar { top: 56px !important; height: calc(100vh - 56px) !important; }
        .top-header { top: 56px !important; }
        /* Keep our sidebar nav-link styles from being overridden by BS5 */
        .sidebar .nav-link { color: rgba(255,255,255,0.8) !important; padding: 12px 15px !important; }
        .sidebar .nav-link:hover { background: rgba(40,167,69,0.2) !important; color: #28a745 !important; }
        .sidebar .nav-link.active { background: #28a745 !important; color: white !important; }
    </style>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</head>
<body style="padding-top: 56px;">
    <!-- Top Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-shopping-basket"></i>
                <span>Shop by Category</span>
            </div>
            <button class="toggle-btn" id="toggleSidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="../grocery_mngmnt/Vegetables.php" class="nav-link <?php echo $currentpage == 'Vegetables.php' ? 'active' : '' ?>">
                    <i class="fas fa-carrot"></i>
                    <span class="nav-label">Vegetables</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Fruits.php" class="nav-link <?php echo $currentpage == 'Fruits.php' ? 'active' : '' ?>">
                    <i class="fas fa-apple-alt"></i>
                    <span class="nav-label">Fruits</span>
                </a>
            </li>
           
            <li class="nav-item">
                <a href="../grocery_mngmnt/Dairy_Products.php" class="nav-link <?php echo $currentpage == 'Dairy_Products.php' ? 'active' : '' ?>">
                    <i class="fas fa-cheese"></i>
                    <span class="nav-label">Dairy Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Baking_Pantry.php" class="nav-link <?php echo $currentpage == 'Baking_Pantry.php' ? 'active' : '' ?>">
                    <i class="fas fa-cheese"></i>
                    <span class="nav-label">Baking & Pantry</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Pasta_Rice.php" class="nav-link <?php echo $currentpage == 'Pasta_Rice.php' ? 'active' : '' ?>">
                    <i class="fas fa-utensils"></i>
                    <span class="nav-label">Pasta & Rice</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Spices.php" class="nav-link <?php echo $currentpage == 'Spices.php' ? 'active' : '' ?>">
                    <i class="fas fa-pepper-hot"></i>
                    <span class="nav-label">Spices</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Snacks.php" class="nav-link <?php echo $currentpage == 'Snacks.php' ? 'active' : '' ?>">
                    <i class="fas fa-cookie-bite"></i>
                    <span class="nav-label">Snacks</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Desserts_Mix.php" class="nav-link <?php echo $currentpage == 'Desserts_Mix.php' ? 'active' : '' ?>">
                    <i class="fas fa-cake"></i>
                    <span class="nav-label">Desserts Mix</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Coffee_Tea_Malts.php" class="nav-link <?php echo $currentpage == 'Coffee_Tea_Malts.php' ? 'active' : '' ?>">
                    <i class="fas fa-mug-hot"></i>
                    <span class="nav-label">Coffee, Tea & Malts</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Beverages.php" class="nav-link <?php echo $currentpage == 'Beverages.php' ? 'active' : '' ?>">
                    <i class="fas fa-wine-bottle"></i>
                    <span class="nav-label">Beverages</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Baby_Products.php" class="nav-link <?php echo $currentpage == 'Baby_Products.php' ? 'active' : '' ?>">
                    <i class="fas fa-baby-carriage"></i>
                    <span class="nav-label">Baby Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Health_Beauty.php" class="nav-link <?php echo $currentpage == 'Health_Beauty.php' ? 'active' : '' ?>">
                    <i class="fas fa-spa"></i>
                    <span class="nav-label">Health & Beauty</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Household.php" class="nav-link <?php echo $currentpage == 'Household.php' ? 'active' : '' ?>">
                    <i class="fas fa-broom"></i>
                    <span class="nav-label">Household</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../grocery_mngmnt/Stationery.php" class="nav-link <?php echo $currentpage == 'Stationery.php' ? 'active' : '' ?>">
                    <i class="fas fa-pen"></i>
                    <span class="nav-label">Stationery</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="top-header">
            <h1 class="page-title"><?php echo $page_title ?? 'Dashboard'; ?></h1>
            <div class="header-right">
                <?php if ($is_logged_in): ?>
                <div class="user-profile" id="userProfile">
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="user-email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
                    </div>
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                    </div>
                    <button class="logout-btn" id="logoutBtn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
                <?php else: ?>
                <button class="cart-btn" id="loginBtn">
                    <i class="fas fa-user"></i>
                    <span>Login / Register</span>
                </button>
                <?php endif; ?>
                <button class="cart-btn" id="cartBtn">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Cart</span>
                    <span class="cart-count" id="cartCount"><?php 
                        $count = 0;
                        if ($is_logged_in && isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $count += $item['quantity'];
                            }
                        }
                        echo $count;
                    ?></span>
                </button>
            </div>
        </div>
        
        <div class="products-container">
            <?php echo $page_content ?? '<div class="alert alert-info">No products to display.</div>'; ?>
        </div>
    </div>
    
    <!-- Login/Register Modal -->
    <div class="modal-overlay" id="authModal">
        <div class="login-modal">
            <div class="modal-header">
                <h2 id="modalTitle">Welcome Back!</h2>
                <p id="modalSubtitle">Login to your account to start shopping</p>
            </div>
            <div class="error-message" id="errorMessage"></div>
            
            <!-- Login Form -->
            <div id="loginForm">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="loginEmail" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="loginPassword" placeholder="Enter your password">
                </div>
                <button class="login-btn" id="doLoginBtn">Login</button>
                <div class="switch-mode">
                    Don't have an account? <a id="switchToRegister">Create Account</a>
                </div>
            </div>
            
            <!-- Register Form (hidden initially) -->
            <div id="registerForm" style="display: none;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="regName" placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="regEmail" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="regPassword" placeholder="Create a password">
                </div>
                <button class="register-btn" id="doRegisterBtn">Create Account</button>
                <div class="switch-mode">
                    Already have an account? <a id="switchToLogin">Login</a>
                </div>
            </div>
            
            <div style="margin-top: 15px; text-align: center;">
                <small style="color: #6c757d;">Demo: demo@example.com / password</small>
            </div>
        </div>
    </div>
    
    <!-- Cart Modal -->
    <div class="cart-overlay" id="cartOverlay"></div>
    <div class="cart-modal" id="cartModal">
        <div class="cart-header">
            <h3><i class="fas fa-shopping-cart"></i> Your Cart</h3>
            <button class="close-cart" id="closeCartBtn">&times;</button>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <p>Your cart is empty</p>
                <small>Add some delicious items to get started!</small>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotal">Rs. 0.00</span>
            </div>
            <button class="checkout-btn" id="checkoutBtn">
                <i class="fas fa-credit-card"></i> Proceed to Checkout
            </button>
            <button class="clear-cart-btn" id="clearCartBtn">
                <i class="fas fa-trash"></i> Clear Cart
            </button>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div class="toast-notification" id="toast">
        <i class="fas fa-check-circle"></i> <span id="toastMessage">Item added to cart!</span>
    </div>
    
    <script>
        $(document).ready(function() {
            let isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
            
            // Sidebar Toggle
            $('#toggleSidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');
                $(this).find('i').toggleClass('fa-chevron-left fa-chevron-right');
            });
            
            // Auth Modal Functions
            function showAuthModal() {
                $('#authModal').addClass('show');
            }
            
            function hideAuthModal() {
                $('#authModal').removeClass('show');
                $('#errorMessage').hide();
            }
            
            $('#loginBtn').click(showAuthModal);
            $('#authModal').click(function(e) {
                if (e.target === this) hideAuthModal();
            });
            
            // Switch to login (from register prompt)
            $('#switchToLogin').click(function() {
                $('#registerForm').hide();
                $('#loginForm').show();
                $('#modalTitle').text('Welcome Back!');
                $('#modalSubtitle').text('Login to your account to start shopping');
                $('#errorMessage').hide();
            });
            
            // Login
            $('#doLoginBtn').click(function() {
                const email = $('#loginEmail').val();
                const password = $('#loginPassword').val();

                if (!email || !password) {
                    showError('Please fill in all fields');
                    return;
                }

                $.ajax({
                    url: '/grocery_mngmnt/lib/routes/Auth/authentication.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        loginEmail: email,
                        loginPasswd: password
                    },
                    success: function(response) {
                        if (response.loginstatus == true) {
                            hideAuthModal();
                            showToast('Login successful!');
                            if (response.path && !response.path.includes('customer')) {
                                window.location.href = response.path;
                            } else {
                                setTimeout(() => location.reload(), 1000);
                            }
                        } else if (response.error_type === 'acc_deactivated') {
                            showError('This account is deactivated.');
                        } else if (response.error_type === 'wrong_password') {
                            showError('Incorrect password.');
                        } else if (response.error_type === 'email_not_found') {
                            showError('Email not found.');
                        } else {
                            showError(response.message || 'Login failed. Please try again.');
                        }
                    },
                    error: function() {
                        showError('Login failed. Please try again.');
                    }
                });
            });

            // Register — redirect to the full registration page
            $('#doRegisterBtn').click(function() {
                window.location.href = '/grocery_mngmnt/register.php';
            });

            $('#switchToRegister').click(function() {
                window.location.href = '/grocery_mngmnt/register.php';
            });
            
            // Logout
            $('#logoutBtn').click(function() {
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = '/grocery_mngmnt/commonpr.php?logout=1';
                }
            });
            
            function showError(message) {
                $('#errorMessage').text(message).show();
                setTimeout(() => $('#errorMessage').fadeOut(), 3000);
            }
            
            function showToast(message, isError = false) {
                $('#toastMessage').text(message);
                const toast = $('#toast');
                if (isError) {
                    toast.addClass('error');
                } else {
                    toast.removeClass('error');
                }
                toast.fadeIn().delay(3000).fadeOut();
            }
            
            // Cart Modal Functions
            function openCart() {
                if (!isLoggedIn) {
                    showAuthModal();
                    return;
                }
                $('#cartModal').addClass('open');
                $('#cartOverlay').addClass('show');
                loadCart();
            }
            
            function closeCart() {
                $('#cartModal').removeClass('open');
                $('#cartOverlay').removeClass('show');
            }
            
            $('#cartBtn').click(openCart);
            $('#closeCartBtn, #cartOverlay').click(closeCart);
            
            // Load Cart
            function loadCart() {
                $.ajax({
                    url: 'commonpr.php?get_cart=1',
                    method: 'GET',
                    success: function(cart) {
                        updateCartDisplay(cart);
                    },
                    error: function() {
                        console.error('Error loading cart');
                    }
                });
            }
            
            function updateCartDisplay(cart) {
                const cartItemsDiv = $('#cartItems');
                const cartTotalSpan = $('#cartTotal');
                let total = 0;
                let html = '';
                
                if (Object.keys(cart).length === 0) {
                    html = '<div class="empty-cart"><i class="fas fa-shopping-basket" style="font-size: 48px; margin-bottom: 15px;"></i><p>Your cart is empty</p><small>Add some delicious items to get started!</small></div>';
                } else {
                    for (let id in cart) {
                        const item = cart[id];
                        const itemTotal = item.price * item.quantity;
                        total += itemTotal;
                        html += `
                            <div class="cart-item" data-id="${id}">
                                <img src="${item.image}" class="cart-item-img" onerror="this.src='https://via.placeholder.com/70'">
                                <div class="cart-item-details">
                                    <div class="cart-item-name">${item.name}</div>
                                    <div class="cart-item-price">Rs. ${item.price.toFixed(2)}</div>
                                    <div class="cart-item-actions">
                                        <button class="decr-item" data-id="${id}">-</button>
                                        <span>${item.quantity}</span>
                                        <button class="incr-item" data-id="${id}">+</button>
                                        <button class="remove-item" data-id="${id}" style="background: #dc3545; color: white;">×</button>
                                    </div>
                                </div>
                                <div style="font-weight: 600;">Rs. ${itemTotal.toFixed(2)}</div>
                            </div>
                        `;
                    }
                }
                
                cartItemsDiv.html(html);
                cartTotalSpan.text('Rs. ' + total.toFixed(2));
                
                const itemCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
                $('#cartCount').text(itemCount);
                
                $('.decr-item').click(function() {
                    updateCartItem($(this).data('id'), 'decr');
                });
                $('.incr-item').click(function() {
                    updateCartItem($(this).data('id'), 'incr');
                });
                $('.remove-item').click(function() {
                    updateCartItem($(this).data('id'), 'remove');
                });
            }
            
            function updateCartItem(id, action) {
                let quantity = 0;
                if (action === 'incr') quantity = 1;
                if (action === 'decr') quantity = -1;
                
                $.ajax({
                    url: 'commonpr.php',
                    method: 'POST',
                    data: {
                        action: action === 'remove' ? 'remove' : 'update',
                        product_id: id,
                        quantity: quantity
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response.success) {
                            loadCart();
                        } else if (response.require_login) {
                            showAuthModal();
                            closeCart();
                        }
                    }
                });
            }
            
            // Add to Cart function (global)
            window.addToCart = function(id, name, price, image) {
                if (!isLoggedIn) {
                    showAuthModal();
                    return;
                }
                
                $.ajax({
                    url: 'commonpr.php',
                    method: 'POST',
                    data: {
                        action: 'add',
                        product_id: id,
                        product_name: name,
                        product_price: price,
                        product_image: image
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response.success) {
                            showToast(name + ' added to cart!');
                            $('#cartCount').text(response.cart_count);
                            if (navigator.vibrate) navigator.vibrate(100);
                        } else if (response.require_login) {
                            showAuthModal();
                        }
                    },
                    error: function() {
                        showToast('Error adding item to cart', true);
                    }
                });
            };
            
            // Clear Cart
            $('#clearCartBtn').click(function() {
                if (confirm('Are you sure you want to clear your cart?')) {
                    $.ajax({
                        url: 'commonpr.php',
                        method: 'POST',
                        data: { action: 'clear' },
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(response) {
                            if (response.success) {
                                loadCart();
                                showToast('Cart cleared!');
                            }
                        }
                    });
                }
            });
            
            // Checkout
            $('#checkoutBtn').click(function() {
                const itemCount = parseInt($('#cartCount').text()) || 0;
                if (itemCount > 0) {
                    window.location.href = '/grocery_mngmnt/checkout.php';
                } else {
                    alert('Your cart is empty. Please add some items first.');
                }
            });
            
            // Enter key support for login
            $('#loginPassword, #loginEmail').keypress(function(e) {
                if (e.which === 13) $('#doLoginBtn').click();
            });
            $('#regPassword, #regEmail, #regName').keypress(function(e) {
                if (e.which === 13) $('#doRegisterBtn').click();
            });
        });
    </script>
    <?php include_once __DIR__ . '/chatbot.php'; ?>
</body>
</html>