<?php 
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'employee') {
    header('Location: ../../login.php');
    exit;
}

// Define employee sidebar BEFORE including common.php
$currentpage = basename($_SERVER['PHP_SELF']);
$sidebar_html = '
<aside id="sidebar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <div class="row">
            <div class="col"><span class="brand-text fw-light">Employee Panel</span></div>
            <div class="col">
                <button id="toggleSidebar" class="sidebar-toggle-btn"><i class="bi bi-list"></i></button>
            </div>
        </div>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/grocery_mngmnt/index.php" class="nav-link">
                                <i class="nav-icon bi bi-shop"></i><p>Go to Shop</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="dshbdemployee.php" class="nav-link ' . ($currentpage == 'dshbdemployee.php' ? 'active' : '') . '">
                                <i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p>
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
                        <li class="nav-item">
                            <a href="ordermngmnt.php" class="nav-link ' . ($currentpage == 'ordermngmnt.php' ? 'active' : '') . '">
                                <i class="nav-icon bi bi-bag-check"></i><p>Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>';
?>

<!doctype html>
<html lang="en">
<head>
<title>Employee Dashboard</title>
<style>
.stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
.quick-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; color: #333; background: #f8f9fa; border: 1px solid #eee; transition: 0.2s; margin-bottom: 10px; }
.quick-link:hover { background: #e9f7ef; border-color: #28a745; color: #28a745; }
.quick-link i { width: 32px; height: 32px; background: #28a745; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.welcome-bar { background: linear-gradient(135deg, #1a2a3a, #2d7a45); color: white; border-radius: 14px; padding: 24px 28px; margin-bottom: 24px; }
.app-main { background: #f4f6f9; min-height: 100vh; }
</style>
</head>

<?php include_once('common.php'); ?>

<main class="app-main">
  <div class="app-content p-4">

    <div class="welcome-bar">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1 fw-bold"><i class="bi bi-person-badge me-2"></i>Employee Dashboard</h4>
          <p class="mb-0 opacity-75">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Employee'); ?></strong>!</p>
        </div>
        <div class="text-end"><small class="opacity-75"><?php echo date('l, d F Y'); ?></small></div>
      </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-box-seam"></i></div>
            <div><div class="text-muted small">Total Products</div><h4 class="mb-0 fw-bold" id="totalProducts">—</h4></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-cart-check"></i></div>
            <div><div class="text-muted small">Total Orders</div><h4 class="mb-0 fw-bold" id="totalOrders">—</h4></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
            <div><div class="text-muted small">Customers</div><h4 class="mb-0 fw-bold" id="totalCustomers">—</h4></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
            <div><div class="text-muted small">Low Stock</div><h4 class="mb-0 fw-bold">—</h4></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions + Profile + Status -->
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card shadow border-0 p-3 h-100">
          <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions</h6>
          <a href="productmngmnt.php" class="quick-link"><i class="bi bi-plus-lg"></i><span>Add New Product</span></a>
          <a href="allproducts.php" class="quick-link"><i class="bi bi-grid"></i><span>View All Products</span></a>
          <a href="ordermngmnt.php" class="quick-link"><i class="bi bi-bag-check"></i><span>Manage Orders</span></a>
          <a href="/grocery_mngmnt/index.php" class="quick-link"><i class="bi bi-shop"></i><span>Go to Shop</span></a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow border-0 p-3 h-100">
          <h6 class="fw-bold mb-3"><i class="bi bi-person-circle text-primary me-2"></i>My Profile</h6>
          <div class="text-center py-2">
            <div style="width:65px;height:65px;background:linear-gradient(135deg,#28a745,#1e7e34);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:26px;font-weight:700;margin:0 auto 12px;">
              <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'E', 0, 1)); ?>
            </div>
            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></h6>
            <span class="badge bg-success">Employee</span>
            <div class="mt-2 text-muted small"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow border-0 p-3 h-100">
          <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-info me-2"></i>System Status</h6>
          <div class="d-flex align-items-center gap-2 mb-3 p-2 bg-light rounded"><span class="badge bg-success">●</span><span class="small">Server Running</span></div>
          <div class="d-flex align-items-center gap-2 mb-3 p-2 bg-light rounded"><span class="badge bg-success">●</span><span class="small">Database Connected</span></div>
          <div class="d-flex align-items-center gap-2 p-2 bg-light rounded"><span class="badge bg-primary">●</span><span class="small">Role: Employee</span></div>
        </div>
      </div>
    </div>

  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sweetalert2.js"></script>
<script>
$(document).ready(function () {
  $.getJSON('../routes/stats/getDashboardStats.php', function (data) {
    $('#totalProducts').text(data.products);
    $('#totalOrders').text(data.orders);
    $('#totalCustomers').text(data.customers);
  });
});
</script>
</body>
</html>