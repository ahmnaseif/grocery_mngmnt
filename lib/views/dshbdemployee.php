<?php 
//start session
session_start();
if(isset($_SESSION['user'])){
    if(isset($_SESSION['usertype'])){

        $usertype = $_SESSION['usertype'];
            if($usertype == "employee"){

            }else{
            header('Location:../../login.php');
        }

    }else{
        header('Location:../../login.php');
    }

}else{
    header('Location:../../login.php');
}

?>



<!doctype html>
<html lang="en">
<head>
<title>Delive</title>

<?php
      include_once('common.php')
      ?>
      </head>
<body>
  <main class="app-main">
    <div class="container mt-4">
      <h4>Employee Dashboard</h4>
      <p class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>. You can manage products from here.</p>
      <a href="/grocery_mngmnt/lib/views/productmngmnt.php" class="btn btn-success">
        <i class="fas fa-boxes me-2"></i>Manage Products
      </a>
      <a href="/grocery_mngmnt/index.php" class="btn btn-outline-secondary ms-2">
        <i class="fas fa-store me-2"></i>Go to Shop
      </a>
    </div>
  </main>

</body>
 
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>

<script src="../../js/jquery.js"></script>
<script src="../../js/sweetalert2.js"></script>
  <script>
  



  </script>



</html>
