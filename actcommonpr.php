<?php 
    $currentpage = basename ($_SERVER['PHP_SELF']);
    ?>
     
     


    <link rel="preload" href="../../css/adminlte.css" as="style" />


<!doctype html>
<html lang="en">
  <head>
  <link rel="preload" href="./css/adminlte.css" as="style" />
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

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
           </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            <!--end::Navbar Search-->
</ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item menu-open">
                
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd1.php" class="nav-link <?php echo $currentpage == 'prd1.php' ? 'active' : '' ?>">
                      <p>Vegetable</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd2.php" class="nav-link <?php echo $currentpage == 'prd2.php' ? 'active' : '' ?>">
                      <p>Fruits</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd3.php" class="nav-link <?php echo $currentpage == 'prd3.php' ? 'active' : '' ?>">
                      <p>Snacks</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd4.php" class="nav-link <?php echo $currentpage == 'prd4.php' ? 'active' : '' ?>">
                      <p>Spices</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd5.php" class="nav-link <?php echo $currentpage == 'prd5.php' ? 'active' : '' ?>">
                      <p>Diary Products</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd6.php" class="nav-link <?php echo $currentpage == 'prd6.php' ? 'active' : '' ?>">
                      <p>Pasta & Rice</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd7.php" class="nav-link <?php echo $currentpage == 'prd7.php' ? 'active' : '' ?>">
                      <p>Desserts Mix</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd8.php" class="nav-link <?php echo $currentpage == 'prd8.php' ? 'active' : '' ?>">
                      <p>Coffee,Tea & Malts</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd9.php" class="nav-link <?php echo $currentpage == 'prd9.php' ? 'active' : '' ?>">
                      <p>Beverages</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd10.php" class="nav-link <?php echo $currentpage == 'prd10.php' ? 'active' : '' ?>">
                      <p>Mom & Baby Care</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd11.php" class="nav-link <?php echo $currentpage == 'prd11.php' ? 'active' : '' ?>">
                      <p>Health & Beauty</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../grocery_mngmnt/prd12.php" class="nav-link <?php echo $currentpage == 'prd12.php' ? 'active' : '' ?>">
                      <p>Stationery</p>
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
      <!--end::Sidebar-->
      
    </div>
    
  </body>
</html>
