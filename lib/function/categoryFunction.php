<?php
//include mainphp
include_once('main.php');

//include autoidphp
include_once('auto_id.php');

//include image function page
include_once('img_upload.php');


class Category extends Main{

    public function loadcatdropdown(){
        $getquery = "SELECT * FROM category_tbl ORDER BY id;"; 
        if($this->dbResult->error){
            echo($this->dbResult->error);
            exit;
        }
    
            $sqlresult = $this->dbResult->query($getquery);
            $nor = $sqlresult->num_rows;
            if($nor >0){
            while($rec = $sqlresult->fetch_assoc()){
        //$btn="#productCategory";
    
    
        echo('<option value='.$rec['id'].'>'.$rec['categoryName'].'</option>');
        
        
        
        // <tr class="table-success">
        //             <th scope="row">'.$rec['productName'].'</th>
        //             <td>'.$rec['productCategory'].'</td>
        //             <td>'.$rec['productSupplier'].'</td>
        //             <td>'.$rec['productDescription'].'</td>
        //             <td class="my-0 py-0"><button type="button" onclick="edituser(\''.$rec['customerID'].'\')" class="btn btn-warning">Edit details</button>
        //             <button type="button" class="btn btn-danger deletebtn" data-id="'.$rec['customerID'].'">Delete</button>'.$btn.'</td>
        //           </tr>');
        }
        }
        return($nor);
    
        }




}
?>
