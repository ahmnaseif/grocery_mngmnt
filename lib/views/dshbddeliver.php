<?php 
//start session
session_start();
if(isset($_SESSION['user'])){
    if(isset($_SESSION['usertype'])){

        $usertype = $_SESSION['usertype'];
            if($usertype == "delivery_Person"){

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
<title>User Management</title>

<?php
      include_once('common.php')
      ?>
      </head>
<body>
  <main class="app-main">
    
    

   
  </main>

</body>
 
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>

<script src="../../js/jquery.js"></script>
<script src="../../js/sweetalert2.js"></script>
  <script>
  



  </script>


  <?php
      include_once('footer.php')
      ?>

</html>
