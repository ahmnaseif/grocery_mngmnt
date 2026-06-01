<?php
//include mainphp
include_once('main.php');

//include autoidphp
include_once('auto_id.php');
class Customer extends Main{
public function addCustomer($name, $email, $nic, $phoneno , $gender, $birthday, $age, $passwd){

        //email exisitng chek
        $checkEmail = $this->dbResult->prepare("SELECT customerID FROM customer_tbl WHERE customerEmail=? AND d_status=0");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $resEmail = $checkEmail->get_result();

        if ($resEmail->num_rows == 0) {

            // nic exisitng chek
            $checkNIC = $this->dbResult->prepare("SELECT customerID FROM customer_tbl WHERE customerNIC=? AND d_status=0");
            $checkNIC->bind_param("s", $nic);
            $checkNIC->execute();
            $resNIC = $checkNIC->get_result();

            if ($resNIC->num_rows == 0) {

                // phn no exisitng chek
                $checkPhone = $this->dbResult->prepare("SELECT customerID FROM customer_tbl WHERE customerPhone=? AND d_status=0");
                $checkPhone->bind_param("s", $phoneno);
                $checkPhone->execute();
                $resPhone = $checkPhone->get_result();

                if ($resPhone->num_rows == 0) {

                    
                    $autonumber = new AutoNumber;
                    $id = $autonumber->NumberGeneration("customerId", "customer_tbl", "CUS");

                    $sqlInsert = $this->dbResult->prepare("INSERT INTO customer_tbl VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())");
                    $sqlInsert->bind_param("sssssssss", $id, $name, $email, $nic, $phoneno , $gender, $birthday, $age, $passwd);

                    if ($sqlInsert->execute()) {
                        $newpassword = md5($passwd);
                        $sqlinsertlogin = $this->dbResult->prepare("INSERT INTO login_tbl VALUES (?, ?, ?, 'customer', 1, 0, NOW())");
                        $sqlinsertlogin->bind_param("sss", $id, $email, $newpassword);

                        if ($sqlinsertlogin->execute()) {

                            return json_encode([
                                'status' => true,
                                'message' => "New Account created",
                                'path' =>  "../views/dshbdcustomer.php"
                            ]);

                        } else {

                            return json_encode([
                                'status' => false,
                                'message' => "Login table insert failed",
                                'error_type' => "login_error"
                            ]);
                        }

                    } else {

                        return json_encode([
                            'status' => false,
                            'message' => "Customer insert failed",
                            'error_type' => "insert_error"
                        ]);
                    }

                } else {
                    return json_encode([
                        'status' => false,
                        'message' => "Phone number already exists",
                        'error_type' => "phnNo_exists"
                    ]);
                }

            } else {
                return json_encode([
                    'status' => false,
                    'message' => "NIC already exists",
                    'error_type' => "nic_exists"
                ]);
            }

        } else {
            return json_encode([
                'status' => false,
                'message' => "Email already exists",
                'error_type' => "email_exists"
            ]);
        }

   
}


public function editCustomer($name, $email, $nic, $phoneno, $birthday, $gender, $userid){

 //email exisitng chek
 $checkEmail = $this->dbResult->prepare("SELECT customerID FROM customer_tbl WHERE customerEmail=? AND customerID !=? AND d_status=0");
 $checkEmail->bind_param("ss", $email, $userid);
 $checkEmail->execute();
 $resEmail = $checkEmail->get_result();

 if ($resEmail->num_rows == 0) {

     // nic exisitng chek
     $checkNIC = $this->dbResult->prepare("SELECT customerID FROM customer_tbl WHERE customerNIC=? AND customerID !=? AND  d_status=0");
     $checkNIC->bind_param("ss", $nic, $userid);
     $checkNIC->execute();
     $resNIC = $checkNIC->get_result();

     if ($resNIC->num_rows == 0) {

         // phn no exisitng chek
         $checkPhone = $this->dbResult->prepare("SELECT customerID FROM customer_tbl WHERE customerPhone=? AND customerID !=? AND d_status=0");
         $checkPhone->bind_param("ss", $phoneno, $userid);
         $checkPhone->execute();
         $resPhone = $checkPhone->get_result();

         if ($resPhone->num_rows == 0) {

        if($this->dbResult->error){
                        echo($this->dbResult->error);
                        exit;
                        }
                        $mysqlUpdate = $this->dbResult->prepare("UPDATE customer_tbl SET customerName=?, customerEmail=?, customerNIC=?, customerPhone=?, customerGender=?, customerBirthday=? 
                        WHERE customerID=?");
                
                    $mysqlUpdate->bind_param("sssssss", $name, $email, $nic, $phoneno, $gender, $birthday, $userid);
                        if($mysqlUpdate->execute()){
                        
                        $sqlUpdatetlogin = $this->dbResult->prepare("UPDATE login_tbl SET loginEmail=?  WHERE loginId=?");
                        $sqlUpdatetlogin->bind_param("ss", $email, $userid);

                 if ($sqlUpdatetlogin->execute()) {

                     return json_encode([
                         'status' => true,
                         'message' => "Account details changed"
                     ]);

                 } else {

                     return json_encode([
                         'status' => false,
                         'message' => "Details changed get wong",
                         'error_type' => "login_error"
                     ]);
                 }

             } else {

                 return json_encode([
                     'status' => false,
                     'message' => "padte faile",
                     'error_type' => "insert_error"
                 ]);
             }

         } else {
             return json_encode([
                 'status' => false,
                 'message' => "Phone number already exists",
                 'error_type' => "phnNo_exists"
             ]);
         }

     } else {
         return json_encode([
             'status' => false,
             'message' => "NIC already exists",
             'error_type' => "nic_exists"
         ]);
     }

 } else {
     return json_encode([
         'status' => false,
         'message' => "Email already exists",
         'error_type' => "email_exists"
     ]);
 }
}


public function loadData(){
            $getquery = "SELECT * FROM customer_tbl LEFT JOIN login_tbl on customer_tbl.customerID = login_tbl.loginId WHERE customer_tbl.d_status = 0;"; 
            if($this->dbResult->error){
                echo($this->dbResult->error);
                exit;
            }
    
                $sqlresult = $this->dbResult->query($getquery);
                $nor = $sqlresult->num_rows;
        if($nor >0){
        while($rec = $sqlresult->fetch_assoc()){
            $btn="";
    
            if($rec['loginStatus'] == '1'){
                $btn = '<button type="button" class="btn btn-warning deactivatebtn" data-id="'.$rec['customerID'].'" data-status="Active">Deactivate</button>';
            }else if($rec['loginStatus'] == '0') {
                $btn = '<button type="button" class="btn btn-warning deactivatebtn" data-id="'.$rec['customerID'].'" data-status="Deactive">Activate</button>';
            }else{
            $btn="";
        }
    
            echo('<tr class="table-success">
            <td>'.$rec['customerID'].'</td>
                        <td>'.$rec['customerName'].'</td>
                        <td>'.$rec['customerEmail'].'</td>
                        <td>'.$rec['customerPhone'].'</td>
                        <td>'.$rec['customerNIC'].'</td>
                        <td class="my-0 py-0"><button type="button" onclick="edituser(\''.$rec['customerID'].'\')" class="btn btn-warning">Edit details</button>
                        <button type="button" class="btn btn-danger deletebtn" data-id="'.$rec['customerID'].'">Delete</button>'.$btn.'</td>
                      </tr>');
        }
     }
     return($nor);
    
    }
    
    public function loadDataSearch($text){
        $getsearchdataquery = "SELECT * FROM customer_tbl WHERE d_status = 0 AND (customerName LIKE '%$text%' OR customerPhone LIKE '%$text%');";
        if($this->dbResult->error){
        echo($this->dbResult->error);
        exit;
        }
        $sqlresult = $this->dbResult->query($getsearchdataquery);
        $nor = $sqlresult->num_rows;
        if($nor >0){
        while($rec = $sqlresult->fetch_assoc()){
            echo('<tr class="table-success">
                        <th scope="row">'.$rec['customerName'].'</th>
                        <td>'.$rec['customerEmail'].'</td>
                        <td>'.$rec['customerPhone'].'</td>
                        <td>'.$rec['customerNIC'].'</td>
                        <td class="my-0 py-0"><button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="edituser(\''.$rec['customerID'].'\')" class="btn btn-warning">Warning</button>
                        <button type="button" class="btn btn-danger">Danger</button></td>
                      </tr>');
        }
     }
    
    }
    
    public function loadDataById($id){
        $userdetails = " SELECT * FROM customer_tbl WHERE d_status = 0 AND customerID = '$id';";
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
    
    public function deleteById($id){
        if($this->dbResult->error){
            echo($this->dbResult->error);
            exit;
        }
        $deletecustomer = $this->dbResult->prepare( "UPDATE customer_tbl JOIN login_tbl ON customer_tbl.customerID = login_tbl.loginId
        SET customer_tbl.d_status = 1, login_tbl.d_status =1 WHERE customerID = ?");
        $deletecustomer->bind_param("s",$id);
        if(!$deletecustomer->execute()){
            return("error");
        }else {return("success");}
    
        $deletecustomer->close();
        $sqlinsert->close();
    
    }
    
    // public function deactivatebyid($id){
    //     if($this->dbResult->error){
    //         echo($this->dbResult->error);
    //         exit;
    //     }
    //     $deletecustomer = $this->dbResult->prepare( "UPDATE login_tbl SET loginStatus = IF(loginStatus = 1, 0, 1) WHERE loginId = ?");
    //     $deletecustomer->bind_param("s",$id);
    //     if(!$deletecustomer->execute()){
    //         return("error");
    //     }else {return("deact");}
    
    //     $deletecustomer->close();
    //     $sqlinsert->close();
    
    // }
    public function deactivateById($id){
        if($this->dbResult->error){
            echo($this->dbResult->error);
            exit;
        }
        $deactcustomer = $this->dbResult->prepare( "UPDATE login_tbl SET loginStatus = IF(loginStatus = 1, 0, 1) WHERE loginId = ?");
        $deactcustomer->bind_param("s",$id);

        if(!$deactcustomer->execute()){
            return("error");
        }else {return("success");}
    
        $deactcustomer->close();
        $sqlinsert->close();
    
    }

}


// public function loaddata(){
        //         $getempdata =$this->dbresult->prepare("SELECT customerEmail, customerName, customerPhone FROM customer_tbl WHERE d_status =0;");
        //         $getempdata->execute();
        //         $getempdata->store_result();
        //         $getempdata->bind_result($email, $name, $phoneno);
    
        //         while($getempdata->fetch()){
        //             echo('<tr class="table-success">
        //             <th scope="row">'.$name.'</th>
        //             <td>'.$email.'</td>
        //             <td>'.$phoneno.'</td>
        //             <td></td>
        //           </tr>');
        //         }
    
        // }

?>