<?php 
    $currentpage = basename ($_SERVER['PHP_SELF']);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome-free-7.1.0-web/css/all.min.css">

  <link rel="preload" href="../../css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->
<script src="../../js/jquery.js"></script>
    

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- <style>.sidebar-toggle-btn {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1050;
    background: #343a40;
    color: #fff;
    border: none;
    padding: 10px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.sidebar-toggle-btn:hover {
    background: #495057;
}</style> -->
    
<style>
/* Sidebar layout */
.app-sidebar {
    width: 250px;
    transition: 0.3s;
}
.app-main {
    margin-left: 250px;
    transition: 0.3s;
}
.app-sidebar.collapsed {
    margin-left: -250px;
}
.app-main.expanded {
    margin-left: 0;
}

/* Toggle button */
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

/* Content padding */
.app-content {
    padding: 24px 28px !important;
}
.app-content-header {
    padding: 16px 0 20px !important;
    margin-bottom: 8px;
}

/* Card improvements */
.card {
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07) !important;
    border: none !important;
    margin-bottom: 20px;
}
.card-header {
    padding: 14px 20px !important;
    border-radius: 12px 12px 0 0 !important;
}
.card-body {
    padding: 20px !important;
}

/* Table spacing */
.table th, .table td {
    padding: 12px 14px;
    vertical-align: middle;
}

/* Buttons */
.btn {
    border-radius: 8px;
}

/* Form controls */
.form-control, .form-select {
    border-radius: 8px;
    padding: 10px 14px;
}
</style>
   
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      
     <!--begin::Sidebar-->
      <aside id="sidebar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
         
          <div class="row">
          <div class="col"><span class="brand-text fw-light">Admin Panel</span></div>
            <div class="col">
            <button id="toggleSidebar" class="sidebar-toggle-btn">
              <i class="bi bi-list"></i>
            </button>
</button></div>
           
          </div>
          <!-- <span class="brand-text fw-light">Admin Panel</span> -->
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
       
<!--begin::Sidebar Wrapper-->
<div class="sidebar-wrapper">
          <nav class="mt-2">
           <!--begin::Sidebar Wrapper-->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <!--begin::Sidebar Menu-->
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
            aria-label="Main navigation" data-accordion="false" id="navigation">
            <li class="nav-item menu-open">
              <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="/grocery_mngmnt/index.php" class="nav-link">
                    <i class="nav-icon bi bi-shop"></i>
                    <p>Go to Shop</p>
                  </a>
                </li>
              <li class="nav-item">
                  <a href="dshbdadmin.php" class="nav-link <?php echo $currentpage == 'dshbdadmin.php' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Admin Panel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="usermngmnt.php" class="nav-link <?php echo $currentpage == 'usermngmnt.php' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>User Management</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="employee.php" class="nav-link <?php echo $currentpage == 'employee.php' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Employee</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="employeemngmnt.php" class="nav-link <?php echo $currentpage == 'employeemngmnt.php' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Employee Management</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="productmngmnt.php" class="nav-link <?php echo $currentpage == 'productmngmnt.php' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Product Management</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="allproducts.php"
                    class="nav-link <?php echo $currentpage == 'allproducts.php' ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Products</p>
                  </a>
                </li>
              </ul>
            </li>






          </ul>
          <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
        </aside>

</body>
<script>
   $('#toggleSidebar').click(function () {
    $('#sidebar').toggleClass('collapsed');
    $('#mainContent').toggleClass('expanded');
  });
</script>

</html>