<?php
//include mainphp
include_once('main.php');

//include autoidphp
include_once('auto_id.php');
class Employee extends Main{
    
public function addEmployee($employeeName, $employeeEmail, $employeeNIC, $employeePhone, $employeeGender, $employeePassword){

        //email exisitng chek
        $checkEmail = $this->dbResult->prepare("SELECT employeeID FROM employee_tbl WHERE employeeEmail=? AND d_status=0");
        $checkEmail->bind_param("s", $employeeEmail);
        $checkEmail->execute();
        $resEmail = $checkEmail->get_result();

        if ($resEmail->num_rows == 0) {

            // nic exisitng chek
            $checkNIC = $this->dbResult->prepare("SELECT employeeID FROM employee_tbl WHERE employeeNIC=? AND d_status=0");
            $checkNIC->bind_param("s", $employeeNIC);
            $checkNIC->execute();
            $resNIC = $checkNIC->get_result();

            if ($resNIC->num_rows == 0) {

                // phn no exisitng chek
                $checkPhone = $this->dbResult->prepare("SELECT employeeID FROM employee_tbl WHERE employeePhone=? AND d_status=0");
                $checkPhone->bind_param("s", $employeePhone);
                $checkPhone->execute();
                $resPhone = $checkPhone->get_result();

                if ($resPhone->num_rows == 0) {

                    
                    $autonumber = new AutoNumber;
                    $id = $autonumber->NumberGeneration("employeeId", "employee_tbl", "EMP");

                    $sqlInsert = $this->dbResult->prepare("INSERT INTO employee_tbl VALUES (?, ?, ?, ?, ?, ?, ?, 0, NOW())");
                    $sqlInsert->bind_param("sssssss", $id, $employeeName, $employeeEmail, $employeeNIC, $employeePhone , $employeeGender, $employeePassword);

                    if ($sqlInsert->execute()) {
                        $newpassword = md5($employeePassword);
                        $sqlinsertlogin = $this->dbResult->prepare("INSERT INTO login_tbl VALUES (?, ?, ?, 'employee', 1, 0, NOW())");
                        $sqlinsertlogin->bind_param("sss", $id, $employeeEmail, $newpassword);

                        if ($sqlinsertlogin->execute()) {

                            return json_encode([
                                'status' => true,
                                'message' => "New Employee Account created",
                                'path' =>  "../views/employeemngmnt.php"
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
                            'message' => "Employee insertion failed",
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




public function editEmployee($employeeName, $employeeEmail, $employeeNIC, $employeePhone, $employeeGender, $empid){

 //email existing check (exclude current employee)
 $checkEmail = $this->dbResult->prepare("SELECT employeeID FROM employee_tbl WHERE employeeEmail=? AND d_status=0 AND employeeID != ?");
 $checkEmail->bind_param("ss", $employeeEmail, $empid);
 $checkEmail->execute();
 $resEmail = $checkEmail->get_result();

 if ($resEmail->num_rows == 0) {

     // nic existing check (exclude current employee)
     $checkNIC = $this->dbResult->prepare("SELECT employeeID FROM employee_tbl WHERE employeeNIC=? AND d_status=0 AND employeeID != ?");
     $checkNIC->bind_param("ss", $employeeNIC, $empid);
     $checkNIC->execute();
     $resNIC = $checkNIC->get_result();

     if ($resNIC->num_rows == 0) {

         // phone existing check (exclude current employee)
         $checkPhone = $this->dbResult->prepare("SELECT employeeID FROM employee_tbl WHERE employeePhone=? AND d_status=0 AND employeeID != ?");
         $checkPhone->bind_param("ss", $employeePhone, $empid);
         $checkPhone->execute();
         $resPhone = $checkPhone->get_result();

         if ($resPhone->num_rows == 0) {

        
        if($this->dbResult->error){
                        echo($this->dbResult->error);
                        exit;
                        }
                        $mysqlUpdate = $this->dbResult->prepare("UPDATE employee_tbl SET employeeName=?, employeeEmail=?, employeeNIC=?, employeePhone=?, employeeGender=? 
                        WHERE employeeID=?");
                
                    $mysqlUpdate->bind_param("ssssss", $employeeName, $employeeEmail, $employeeNIC, $employeePhone, $employeeGender, $empid);
                        if($mysqlUpdate->execute()){
                            $sqlUpdatetlogin = $this->dbResult->prepare("UPDATE login_tbl SET loginEmail=?  WHERE loginId=?");
                            $sqlUpdatetlogin->bind_param("ss", $employeeEmail, $empid);


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



















// public function editCustomer($name, $email, $nic, $phoneno, $birthday, $gender, $userid){
//             $checkPhone = $this->dbResult->prepare("SELECT customerPhone FROM customer_tbl WHERE customerPhone = ?");
//             $checkPhone->bind_param("s", $phoneno);
//             $checkPhone->execute();
//             $checkPhone->store_result();
    
//             if($checkPhone->num_rows >0){
//                 $checkPhone->close();
//                 return("Phone number exists");
//             }else{
    
//                 $autonumber = new AutoNumber;
//             $id = $autonumber->NumberGeneration("customerId", "customer_tbl", "CUS");
    
    
//     if($this->dbResult->error){
//         echo($this->dbResult->error);
//         exit;
//     }
//     $sqlInsert = $this->dbResult->prepare("INSERT INTO customer_tbl VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())");
//     $sqlInsert->bind_param("ssssssss", $id, $name, $email, $nic, $phoneno , $gender, $birthday, $passwd);
    
//     if($sqlInsert->execute()){
//         $newpassword = md5($passwd);
//         $sqlinsertlogin = $this->dbResult->prepare("INSERT INTO login_tbl VALUES (?, ?, ?, 'customer', 1, 0, NOW())");
//         $sqlinsertlogin->bind_param("sss", $id, $email, $newpassword);
    
//         if($sqlinsertlogin->execute()){
//             //return("success");
//             return json_encode([
//                 'status' => true,
//                 'error_type' => 'success',
//                 'message' => 'account registered'
//             ]);
//         }else{
//             return("error2"); 
//         }
        
//     }{
//         return("error");
//     }
//     $sqlinsert->close();
//             }
//         }
    
    
    
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




public function loadData(){
            $getquery = "SELECT * FROM employee_tbl LEFT JOIN login_tbl on employee_tbl.employeeID = login_tbl.loginId WHERE employee_tbl.d_status = 0;"; 
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
                $btn = '<button type="button" class="btn btn-warning deactivatebtn" data-id="'.$rec['employeeID'].'" data-status="Active">Deactivate</button>';
            }else if($rec['loginStatus'] == '0') {
                $btn = '<button type="button" class="btn btn-warning deactivatebtn" data-id="'.$rec['employeeID'].'" data-status="Deactive">Activate</button>';
            }else{
            $btn="";
        }
    
            echo('<tr class="table-success">
                        
                        <td>'.$rec['employeeID'].'</td>
                        <td>'.$rec['employeeName'].'</td>
                        <td>'.$rec['employeeEmail'].'</td>
                        <td>'.$rec['employeePhone'].'</td>
                        <td>'.$rec['employeeNIC'].'</td>
                        <td class="my-0 py-0"><button type="button" onclick="edituser(\''.$rec['employeeID'].'\')" class="btn btn-warning">Edit details</button>
                        <button type="button" class="btn btn-danger deletebtn" data-id="'.$rec['employeeID'].'">Delete</button>'.$btn.'</td>
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
        $userdetails = " SELECT * FROM employee_tbl WHERE d_status = 0 AND employeeID = '$id';";
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
        $deletecustomer = $this->dbResult->prepare( "UPDATE employee_tbl JOIN login_tbl ON employee_tbl.employeeID = login_tbl.loginId
        SET employee_tbl.d_status = 1, login_tbl.d_status = 1 WHERE employeeID = ?");
        $deletecustomer->bind_param("s",$id);
        if(!$deletecustomer->execute()){
            return("error");
        }else {return("success");}
    
        $deletecustomer->close();
        $sqlinsert->close();
    
    }
    
    public function deactivateById($id){
        if($this->dbResult->error){
            echo($this->dbResult->error);
            exit;
        }
        $deletecustomer = $this->dbResult->prepare( "UPDATE login_tbl SET loginStatus = IF(loginStatus = 1, 0, 1) WHERE loginId = ?");
        $deletecustomer->bind_param("s",$id);
        if(!$deletecustomer->execute()){
            return("error");
        }else {return("success");}

        $deletecustomer->close();
        $sqlinsert->close();

    }

}



?>