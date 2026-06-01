<?php
//include mainphp
include_once('main.php');

//include autoidphp
include_once('auto_id.php');

//include image function page
include_once('img_upload.php');



class Product extends Main{
public function addProduct($productName, $productCategory, $productSupplier, $productDescription, $productPrice, $priceType, $productImgName, $productImgSize, $productImgType, $productImgLocation){

    // checking existing of product
    $checkProduct = $this->dbResult->prepare("SELECT productId FROM product_tbl WHERE productName=? AND d_status=0");
    $checkProduct->bind_param("s", $productName);
    $checkProduct->execute();
    $resProduct = $checkProduct->get_result();

    if ($resProduct->num_rows > 0) {
        return json_encode([
            'status' => false,
            'message' => "Product already exists",
            'error_type' => "product_exists"
        ]);
    }
        $autonumber = new AutoNumber;
        $id = $autonumber->NumberGeneration("productId", "product_tbl", "PROD");
    
        $imageupload = new ImageUpload;
        $imageurl = $imageupload->imgUpload($productImgName, $productImgType, 'product', $productImgLocation, $id);

        if ($imageurl === 'error') {
            return json_encode([
                'status' => false,
                'message' => "Image upload failed. Check file type and size.",
                'error_type' => "image_upload_failed"
            ]);
        }

        $sqlinsertproduct = $this->dbResult->prepare("INSERT INTO product_tbl
        (productId, productName, productCategory, productSupplier, productImg, productDescription, productPrice, priceType, d_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
    
        $sqlinsertproduct->bind_param("ssssssds", $id, $productName, $productCategory, $productSupplier, $imageurl, $productDescription, $productPrice, $priceType);
    
        if($sqlinsertproduct->execute()){
            return json_encode([
                'status' => true,
                'message' => "Product added successfully"
            ]);
        }else{
            return json_encode([
                'status' => false,
                'message' => "Product insertion failed",
                'error_type' => "insertPr_failed"
            ]);
        }  
}







public function editProduct($productName, $productCategory, $productSupplier, $productDescription, $productPrice, $priceType, $pid){

    $sqlinsertproduct = $this->dbResult->prepare("UPDATE product_tbl SET productName = ?, productCategory = ?, productSupplier = ?, productDescription = ?, productPrice = ?, priceType = ? WHERE productId = ?");
    $sqlinsertproduct->bind_param("ssssdss", $productName, $productCategory, $productSupplier, $productDescription, $productPrice, $priceType, $pid);

    if($sqlinsertproduct->execute()){
        return "success";
    } else {
        return "error";
    }
}


public function editproduct2($productName, $productCategory, $productSupplier, $productDescription, $productPrice, $priceType, $pid, $productImgName, $productImgSize, $productImgType, $productImgLocation){
    $imageupload = new ImageUpload;
    $imageurl = $imageupload->imgUpload($productImgName, $productImgType, 'product', $productImgLocation, $pid);

    if ($imageurl === 'error') {
        return 'error';
    }

    $sqlinsertproduct = $this->dbResult->prepare("UPDATE product_tbl SET productName = ?, productCategory = ?, productSupplier = ?, productDescription = ?, productPrice = ?, priceType = ?, productImg = ? WHERE productId = ?");
    $sqlinsertproduct->bind_param("ssssdsss", $productName, $productCategory, $productSupplier, $productDescription, $productPrice, $priceType, $imageurl, $pid);

    if ($sqlinsertproduct->execute()) {
        return "success";
    } else {
        return "error2";
    }
}

public function loadProductData(){
    $getquery = "SELECT *,product_tbl.productId	AS pid FROM product_tbl JOIN category_tbl ON category_tbl.id = product_tbl.productCategory WHERE product_tbl.d_status = 0;"; 
    if($this->dbResult->error){
        echo($this->dbResult->error);
        exit;
    }

        $sqlresult = $this->dbResult->query($getquery);
        $nor = $sqlresult->num_rows;
        if($nor >0){
        while($rec = $sqlresult->fetch_assoc()){

            
    echo('<tr class="table-success">
    <td>'.$rec['productId'].'</td>
                <td>'.$rec['productName'].'</td>
                <td>'.$rec['productCategory'].'</td>
                <td>'.$rec['categoryName'].'</td>
                <td><img style="width:80px; height:80px;" src="../'.$rec['productImg'].'"></td>
                <td>'.$rec['productSupplier'].'</td>
                <td>Rs. '.$rec['productPrice'].' / '.$rec['priceType'].'</td>
                <td class="my-0 py-0"><button type="button" data-id="'.$rec['pid'].'" class="btn btn-warning editbtn">Edit details</button>
                <button type="button" class="btn btn-danger deletebtn" data-id="'.$rec['pid'].'">View</button></td>
                </tr>');
    }
    }
    return($nor);

    }

    public function allProductData(){
        $getquery = "SELECT *,product_tbl.productId	AS pid FROM product_tbl JOIN category_tbl ON category_tbl.id = product_tbl.productCategory WHERE product_tbl.d_status = 0;"; 
        if($this->dbResult->error){
            echo($this->dbResult->error);
            exit;
        }
    
            $sqlresult = $this->dbResult->query($getquery);
            $nor = $sqlresult->num_rows;
            if($nor >0){
            while($rec = $sqlresult->fetch_assoc()){
    
                
        echo('<tr class="table-success">
                    <th scope="row">'.$rec['productName'].'</th>
                    
                    <td>'.$rec['categoryName'].'</td>
                    <td><img style="width:80px; height:80px;" src="../'.$rec['productImg'].'"></td>
                    <td>'.$rec['productSupplier'].'</td>
                    <td>Rs. '.$rec['productPrice'].' / '.$rec['priceType'].'</td>
                    <td>
                    <button class="btn btn-warning editbtn" data-id="'.$rec['pid'].'">
                        <i class="fas fa-eye"></i>
                    </button>
            
                    <button class="btn btn-danger deletebtn" data-id="'.$rec['pid'].'">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            
                  </tr>');
        }
        }
        return($nor);
    
        }


    public function loadProductById($id){
        $userdetails = "SELECT productId, productName, productCategory, productSupplier, productImg, productDescription, productPrice, priceType FROM product_tbl WHERE d_status = 0 AND productId = '$id';";
            if($this->dbResult->error){
        echo($this->dbResult->error);
        exit;
            }
            $sqlresult = $this->dbResult->query($userdetails);
    
            $nor = $sqlresult->num_rows;
            if($nor >0){
                $rec = $sqlresult->fetch_assoc();
                return json_encode($rec);
            }
    
    }



    public function loadProductByCategory($catId){
      $getquery = "SELECT * FROM product_tbl  WHERE productCategory = ? AND d_status = 0";

    //         $getquery = "SELECT product_tbl.*, category_tbl.categoryName
    // JOIN category_tbl ON category_tbl.id = product_tbl.productCategory
    // WHERE product_tbl.d_status = 0 AND product_tbl.productCategory = ?";
    
        $getprdCard = $this->dbResult->prepare($getquery);
        $getprdCard->bind_param("s", $catId);
        $getprdCard->execute();
        $resprdCard = $getprdCard->get_result();
    
        if($resprdCard->num_rows > 0){

            while($rec = $resprdCard->fetch_assoc()){
                $imgSrc = '/grocery_mngmnt/lib/routes/product/getProductImage.php?id=' . urlencode($rec['productId']);
                $imgCart = $imgSrc;
                echo '
                <div class="product-card">
                    <img class="product-img" src="'.$imgSrc.'" alt="'.htmlspecialchars($rec['productName']).'"
                         onerror="this.src=\'/grocery_mngmnt/assets/image/placeholder.png\'">
                    <div class="product-body">
                        <div class="category-badge">'.$rec['productCategory'].'</div>
                        <h3 class="product-title">'.htmlspecialchars($rec['productName']).'</h3>
                        <p class="weight-info">'.htmlspecialchars($rec['productDescription']).'</p>
                        <div class="product-price">Rs. '.$rec['productPrice'].' / '.$rec['priceType'].'</div>
                        <div class="stock-badge">
                            <i class="fas fa-check-circle"></i> In Stock
                        </div>
                        <button class="add-to-cart"
                            onclick="addToCart(\''.$rec['productId'].'\',
                            \''.addslashes($rec['productName']).'\',
                            '.$rec['productPrice'].',
                            \''.$imgCart.'\')">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
                ';
            }

        } else {
            echo "<h3>No Products Found</h3>";
        }
    }






















}




?>