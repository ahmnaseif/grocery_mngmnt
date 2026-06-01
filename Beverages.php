<?php
ob_start();
$page_title = 'Beverages';
?>
<div id="categoryProducts" class="category-grid"></div>
<script>
$(document).ready(function(){
    $.get('/grocery_mngmnt/lib/routes/product/loadProductbyCategory.php', { catId: 'CAT010' }, function(res){
        $('#categoryProducts').html(res);
    });
});
</script>
<?php
$page_content = ob_get_clean();
include 'commonpr.php';
?>
