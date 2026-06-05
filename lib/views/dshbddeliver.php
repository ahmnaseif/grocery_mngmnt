<?php 
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'delivery_Person') {
    header('Location: ../../login.php');
    exit;
}

// Define delivery sidebar BEFORE including common.php
$currentpage = basename($_SERVER['PHP_SELF']);
$sidebar_html = '
<aside id="sidebar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <div class="row">
            <div class="col"><span class="brand-text fw-light">Delivery Panel</span></div>
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
                            <a href="dshbddeliver.php" class="nav-link ' . ($currentpage == 'dshbddeliver.php' ? 'active' : '') . '">
                                <i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="ordermngmnt.php" class="nav-link ' . ($currentpage == 'ordermngmnt.php' ? 'active' : '') . '">
                                <i class="nav-icon bi bi-bag-check"></i><p>Assigned Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="listmngmnt.php" class="nav-link ' . ($currentpage == 'listmngmnt.php' ? 'active' : '') . '">
                                <i class="nav-icon bi bi-list-ul"></i><p>Delivery List</p>
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
<title>Delivery Dashboard</title>
<style>
.stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
.quick-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; color: #333; background: #f8f9fa; border: 1px solid #eee; transition: 0.2s; margin-bottom: 10px; }
.quick-link:hover { background: #fff3cd; border-color: #ffc107; color: #856404; }
.quick-link i { width: 32px; height: 32px; background: #ffc107; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.welcome-bar { background: linear-gradient(135deg, #1a2a3a, #b8860b); color: white; border-radius: 14px; padding: 24px 28px; margin-bottom: 24px; }
.app-main { background: #f4f6f9; min-height: 100vh; }
</style>
</head>

<?php include_once('common.php'); ?>

<main class="app-main">
  <div class="app-content p-4">

    <div class="welcome-bar">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1 fw-bold"><i class="bi bi-truck me-2"></i>Delivery Dashboard</h4>
          <p class="mb-0 opacity-75">Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Delivery Person'); ?></strong>! Check your deliveries for today.</p>
        </div>
        <div class="text-end"><small class="opacity-75"><?php echo date('l, d F Y'); ?></small></div>
      </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="text-muted small">Pending</div><h4 class="mb-0 fw-bold" id="pendingOrders">0</h4></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-truck"></i></div>
            <div><div class="text-muted small">Out for Delivery</div><h4 class="mb-0 fw-bold" id="outForDelivery">0</h4></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
            <div><div class="text-muted small">Delivered Today</div><h4 class="mb-0 fw-bold" id="deliveredToday">0</h4></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card shadow border-0 p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle"></i></div>
            <div><div class="text-muted small">Failed</div><h4 class="mb-0 fw-bold" id="failedDeliveries">0</h4></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delivery Table + Profile + Actions -->
    <div class="row g-4">
      <div class="col-md-8">
        <div class="card shadow border-0 p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-task text-warning me-2"></i>Today's Deliveries</h6>
            <a href="ordermngmnt.php" class="btn btn-sm btn-outline-warning">View All</a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr><th>Order ID</th><th>Customer</th><th>Address</th><th>Amount</th><th>Status</th></tr>
              </thead>
              <tbody id="deliveryList">
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>No deliveries assigned yet
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-4 d-flex flex-column gap-4">
        <div class="card shadow border-0 p-3">
          <h6 class="fw-bold mb-3"><i class="bi bi-person-circle text-primary me-2"></i>My Profile</h6>
          <div class="text-center py-2">
            <div style="width:65px;height:65px;background:linear-gradient(135deg,#ffc107,#e0a800);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:26px;font-weight:700;margin:0 auto 12px;">
              <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'D', 0, 1)); ?>
            </div>
            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></h6>
            <span class="badge bg-warning text-dark">Delivery Person</span>
            <div class="mt-2 text-muted small"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></div>
          </div>
        </div>
        <div class="card shadow border-0 p-3">
          <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions</h6>
          <a href="ordermngmnt.php" class="quick-link"><i class="bi bi-bag-check"></i><span>View Assigned Orders</span></a>
          <a href="listmngmnt.php" class="quick-link"><i class="bi bi-list-ul"></i><span>Delivery List</span></a>
          <a href="/grocery_mngmnt/index.php" class="quick-link"><i class="bi bi-shop"></i><span>Go to Shop</span></a>
        </div>
      </div>
    </div>

  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sweetalert2.js"></script>
</body>
</html>