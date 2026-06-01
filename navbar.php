<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$currentpage     = basename($_SERVER['PHP_SELF']);
$is_logged_in    = isset($_SESSION['user']);
$nav_user_name   = htmlspecialchars($_SESSION['user_name'] ?? '');
$nav_user_type   = $_SESSION['usertype'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top"
     style="background-color: #28a745; font-size: 0.93rem; box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/grocery_mngmnt/index.php"
       style="font-size: 1.1rem; letter-spacing: 0.3px;">
      <img src="/grocery_mngmnt/assets/elements/AMI Icon.png" width="32" height="32" alt="AMI"
           style="object-fit:contain; filter: brightness(0) invert(1);">
      <span>AMI Grocery</span>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo $currentpage === 'index.php' ?  'active' : '' ; ?>"
             href="/grocery_mngmnt/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $currentpage === 'about.php' ? 'active' : ''; ?>"
             href="/grocery_mngmnt/about.php">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $currentpage === 'contact.php' ? 'active' : ''; ?>" 
            href="/grocery_mngmnt/contact.php">Contact</a>
            <!-- <a class="nav-link <?php echo $currentpage === 'contact.php' ? 'fw-semibold' : ''; ?>" 
            href="/grocery_mngmnt/contact.php">Contact</a> -->
        </li>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <?php if ($is_logged_in): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#"
               data-bs-toggle="dropdown" id="userDropdown" aria-expanded="false">
              <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold me-1"
                    style="width:28px;height:28px;background:rgba(255,255,255,0.25);font-size:13px;">
                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
              </span>
              <?php echo $nav_user_name; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
              <?php if ($nav_user_type === 'customer'): ?>
                <li><a class="dropdown-item" href="/grocery_mngmnt/lib/views/dshbdcustomer.php">
                    <i class="fas fa-user me-2 text-success"></i>My Profile</a></li>
                <li><a class="dropdown-item" href="/grocery_mngmnt/lib/views/ordermngmnt.php">
                    <i class="fas fa-shopping-bag me-2 text-success"></i>My Orders</a></li>
                <li><a class="dropdown-item" href="/grocery_mngmnt/lib/views/paymentmngmnt.php">
                    <i class="fas fa-credit-card me-2 text-success"></i>Payment Methods</a></li>
              <?php elseif ($nav_user_type === 'admin'): ?>
                <li><a class="dropdown-item" href="/grocery_mngmnt/lib/views/dshbdadmin.php">
                    <i class="fas fa-cog me-2 text-success"></i>Admin Panel</a></li>
              <?php elseif ($nav_user_type === 'employee'): ?>
                <li><a class="dropdown-item" href="/grocery_mngmnt/lib/views/dshbdemployee.php">
                    <i class="fas fa-tachometer-alt me-2 text-success"></i>Dashboard</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/grocery_mngmnt/commonpr.php?logout=1">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
          </li>
        

        <?php else: ?>
          <li class="nav-item">
          <a class="btn btn-outline-light btn-sm fw-semibold 
           <?php echo $currentpage === 'login.php' ? 'active text-dark bg-white border-white' : ''; ?>"
           href="/grocery_mngmnt/login.php">
           Login
        </a>
          </li>
          <li class="nav-item">
          <a class="btn btn-outline-light btn-sm fw-semibold 
           <?php echo $currentpage === 'register.php' ? 'active text-dark bg-white border-white' : ''; ?>"
           href="/grocery_mngmnt/register.php">
           Register
        </a>
          </li>
<?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
