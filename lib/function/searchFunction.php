<?php
include_once('main.php');

class Seacrh extends Main{

    public function searchproduct($keyword){
        $like = '%' . $keyword . '%';
        $stmt = $this->dbResult->prepare("SELECT * FROM product_tbl WHERE d_status = 0 AND (productName LIKE ? OR productDescription LIKE ?)");
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        $nor = $result->num_rows;

        while ($rec = $result->fetch_assoc()) {
            $imgSrc = '/grocery_mngmnt/lib/routes/product/getProductImage.php?id=' . urlencode($rec['productId']);
            echo '
            <div class="col-6 col-md-3 mb-3">
              <div class="card h-100 shadow-sm border-0" style="border-radius:14px;overflow:hidden;">
                <img src="' . $imgSrc . '" class="card-img-top" style="height:150px;object-fit:cover;"
                     onerror="this.src=\'/grocery_mngmnt/assets/image/placeholder.png\'" alt="' . htmlspecialchars($rec['productName']) . '">
                <div class="card-body p-3">
                  <p class="mb-1" style="font-size:0.75rem;color:#28a745;font-weight:600;">' . htmlspecialchars($rec['productCategory']) . '</p>
                  <h6 class="fw-bold mb-1" style="font-size:0.9rem;">' . htmlspecialchars($rec['productName']) . '</h6>
                  <p class="text-muted mb-2" style="font-size:0.8rem;">' . htmlspecialchars($rec['productDescription']) . '</p>
                  <div class="fw-bold text-success mb-2">Rs. ' . $rec['productPrice'] . ' / ' . $rec['priceType'] . '</div>
                  <button class="btn btn-success btn-sm w-100"
                    onclick="addToCart(\'' . $rec['productId'] . '\',\'' . addslashes($rec['productName']) . '\',' . $rec['productPrice'] . ',\'' . $imgSrc . '\')">
                    <i class="fas fa-cart-plus me-1"></i>Add to Cart
                  </button>
                </div>
              </div>
            </div>';
        }
        return $nor;
    }
}
?>














