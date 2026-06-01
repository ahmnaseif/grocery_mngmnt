<?php
$currentpage = basename($_SERVER['PHP_SELF']);
$user_name   = $_SESSION['user_name']  ?? 'Customer';
$user_email  = $_SESSION['user_email'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title ?? 'My Account'); ?> - FreshMart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }

        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: 280px;
            background: linear-gradient(135deg, #1a2a3a 0%, #0f1a24 100%);
            color: white; transition: all 0.3s ease; z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1); overflow-y: auto;
            display: flex; flex-direction: column;
        }
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .logo span,
        .sidebar.collapsed .nav-label { display: none; }
        .sidebar.collapsed .cstmr-nav-link { justify-content: center; padding: 12px; }
        .sidebar.collapsed .cstmr-nav-link i { margin: 0; font-size: 20px; }
        .sidebar.collapsed .sidebar-footer { padding: 10px; }
        .sidebar.collapsed .sidebar-footer .cstmr-nav-link { justify-content: center; }

        .sidebar-header {
            padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .logo { display: flex; align-items: center; gap: 12px; }
        .logo i { font-size: 28px; color: #28a745; }
        .logo span { font-size: 20px; font-weight: 600; white-space: nowrap; }

        .toggle-btn {
            background: rgba(255,255,255,0.1); border: none; color: white;
            cursor: pointer; padding: 8px 10px; border-radius: 8px; transition: all 0.3s;
        }
        .toggle-btn:hover { background: rgba(255,255,255,0.2); }

        .nav-menu { list-style: none; padding: 20px 0; margin: 0; flex: 1; }
        .nav-menu li { margin: 5px 15px; }

        .cstmr-nav-link {
            display: flex; align-items: center; gap: 15px; padding: 12px 15px;
            color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 12px;
            transition: all 0.3s; white-space: nowrap;
        }
        .cstmr-nav-link:hover { background: rgba(40,167,69,0.2); color: #28a745; }
        .cstmr-nav-link.active { background: #28a745; color: white; }
        .cstmr-nav-link i { width: 24px; font-size: 18px; text-align: center; flex-shrink: 0; }
        .nav-label { font-size: 14px; font-weight: 500; }

        .sidebar-footer {
            padding: 15px; border-top: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;
        }

        .main-content { margin-left: 280px; transition: all 0.3s ease; min-height: 100vh; }
        .main-content.expanded { margin-left: 80px; }

        .top-header {
            background: white; padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 99;
        }
        .page-title { font-size: 24px; font-weight: 600; color: #1a2a3a; margin: 0; }
        .header-right { display: flex; align-items: center; gap: 15px; }

        .shop-btn {
            background: #28a745; color: white; border: none; padding: 10px 18px;
            border-radius: 40px; display: flex; align-items: center; gap: 8px;
            cursor: pointer; transition: all 0.3s; text-decoration: none;
            font-weight: 500; font-size: 14px;
        }
        .shop-btn:hover { background: #1e7e34; color: white; }

        .user-profile {
            display: flex; align-items: center; gap: 12px; background: #f8f9fa;
            padding: 8px 16px; border-radius: 40px;
        }
        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 15px; flex-shrink: 0;
        }
        .user-info { text-align: right; }
        .user-name-text { font-weight: 600; font-size: 14px; color: #1a2a3a; }
        .user-email-text { font-size: 11px; color: #6c757d; }

        .page-content { padding: 30px; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #28a745; border-radius: 3px; }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar .logo span, .sidebar .nav-label { display: none; }
            .sidebar .cstmr-nav-link { justify-content: center; padding: 12px; }
            .sidebar .cstmr-nav-link i { margin: 0; font-size: 20px; }
            .main-content { margin-left: 80px; }
            .user-info { display: none; }
            .page-content { padding: 15px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-user-circle"></i>
                <span>My Account</span>
            </div>
            <button class="toggle-btn" id="toggleSidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <ul class="nav-menu">
            <li>
                <a href="/grocery_mngmnt/index.php" class="cstmr-nav-link">
                    <i class="fas fa-store"></i>
                    <span class="nav-label">Go Shopping</span>
                </a>
            </li>
            <li>
                <a href="dshbdcustomer.php"
                   class="cstmr-nav-link <?php echo $currentpage === 'dshbdcustomer.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span class="nav-label">My Profile</span>
                </a>
            </li>
            <li>
                <a href="ordermngmnt.php"
                   class="cstmr-nav-link <?php echo $currentpage === 'ordermngmnt.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="nav-label">My Orders</span>
                </a>
            </li>
            <li>
                <a href="listmngmnt.php"
                   class="cstmr-nav-link <?php echo $currentpage === 'listmngmnt.php' ? 'active' : ''; ?>">
                    <i class="fas fa-list-check"></i>
                    <span class="nav-label">Save List</span>
                </a>
            </li>
            <li>
                <a href="paymentmngmnt.php"
                   class="cstmr-nav-link <?php echo $currentpage === 'paymentmngmnt.php' ? 'active' : ''; ?>">
                    <i class="fas fa-credit-card"></i>
                    <span class="nav-label">Payment Methods</span>
                </a>
            </li>
            <li>
                <a href="creditmngmnt.php"
                   class="cstmr-nav-link <?php echo $currentpage === 'creditmngmnt.php' ? 'active' : ''; ?>">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span class="nav-label">Credit History</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="cstmr-nav-link" style="color: rgba(255,120,120,0.9);">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-label">Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="top-header">
            <h1 class="page-title"><?php echo htmlspecialchars($page_title ?? 'My Account'); ?></h1>
            <div class="header-right">
                <a href="/grocery_mngmnt/index.php" class="shop-btn">
                    <i class="fas fa-store"></i> Back to Shop
                </a>
                <div class="user-profile">
                    <div class="user-info">
                        <div class="user-name-text"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="user-email-text"><?php echo htmlspecialchars($user_email); ?></div>
                    </div>
                    <div class="user-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <?php echo $page_content ?? ''; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#toggleSidebar').click(function () {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');
                $(this).find('i').toggleClass('fa-chevron-left fa-chevron-right');
            });
        });
    </script>
    <?php include_once __DIR__ . '/../../chatbot.php'; ?>
</body>
</html>
