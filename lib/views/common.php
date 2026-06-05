<?php 
$currentpage = basename($_SERVER['PHP_SELF']);

// Default sidebar (Admin) — pages can override by setting $sidebar_html before including common.php
if (!isset($sidebar_html)) {
    $sidebar_html = '
    <aside id="sidebar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <div class="row">
                <div class="col"><span class="brand-text fw-light">Admin Panel</span></div>
                <div class="col">
                    <button id="toggleSidebar" class="sidebar-toggle-btn"><i class="bi bi-list"></i></button>
                </div>
            </div>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                    <li class="nav-item menu-open">
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/grocery_mngmnt/index.php" class="nav-link">
                                    <i class="nav-icon bi bi-shop"></i><p>Go to Shop</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="dshbdadmin.php" class="nav-link ' . ($currentpage == 'dshbdadmin.php' ? 'active' : '') . '">
                                    <i class="nav-icon bi bi-speedometer2"></i><p>Admin Panel</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="usermngmnt.php" class="nav-link ' . ($currentpage == 'usermngmnt.php' ? 'active' : '') . '">
                                    <i class="nav-icon bi bi-people"></i><p>User Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="employee.php" class="nav-link ' . ($currentpage == 'employee.php' ? 'active' : '') . '">
                                    <i class="nav-icon bi bi-person-plus"></i><p>Add Employee</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="employeemngmnt.php" class="nav-link ' . ($currentpage == 'employeemngmnt.php' ? 'active' : '') . '">
                                    <i class="nav-icon bi bi-person-badge"></i><p>Employee Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="productmngmnt.php" class="nav-link ' . ($currentpage == 'productmngmnt.php' ? 'active' : '') . '">
                                    <i class="nav-icon bi bi-box-seam"></i><p>Product Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="allproducts.php" class="nav-link ' . ($currentpage == 'allproducts.php' ? 'active' : '') . '">
                                    <i class="nav-icon bi bi-grid"></i><p>All Products</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link rel="preload" href="../../css/adminlte.css" as="style" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
  <script src="../../js/jquery.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="../../css/adminlte.css" />

  <style>
    .app-sidebar { width: 250px; transition: 0.3s; }
    .app-main { margin-left: 250px; transition: 0.3s; }
    .app-sidebar.collapsed { margin-left: -250px; }
    .app-main.expanded { margin-left: 0; }
    .sidebar-toggle-btn { background: rgba(255,255,255,0.1); border: none; color: white; cursor: pointer; padding: 8px 10px; border-radius: 8px; transition: all 0.3s; }
    .sidebar-toggle-btn:hover { background: rgba(255,255,255,0.2); }
    .app-content { padding: 24px 28px !important; }
    .app-content-header { padding: 16px 0 20px !important; margin-bottom: 8px; }
    .card { border-radius: 12px !important; box-shadow: 0 2px 8px rgba(0,0,0,0.07) !important; border: none !important; margin-bottom: 20px; }
    .card-header { padding: 14px 20px !important; border-radius: 12px 12px 0 0 !important; }
    .card-body { padding: 20px !important; }
    .table th, .table td { padding: 12px 14px; vertical-align: middle; }
    .btn { border-radius: 8px; }
    .form-control, .form-select { border-radius: 8px; padding: 10px 14px; }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

  <!-- Sidebar: rendered from $sidebar_html (set per page, defaults to admin) -->
  <?php echo $sidebar_html; ?>

<script>
  $('#toggleSidebar').click(function () {
    $('#sidebar').toggleClass('collapsed');
    $('main.app-main, .app-main').toggleClass('expanded');
  });
</script>