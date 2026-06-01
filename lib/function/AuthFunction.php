<?php

//include mainphp
include_once('main.php');

//include autoidphp
include_once('auto_id.php');

//start sessions
session_start();

class Auth extends Main {

    public function authentication2($email1, $passwd1) {
        

        if ($email1 != "" && $passwd1 != "") {

            $auth = $this->dbResult->prepare("SELECT loginPasswd, loginStatus, loginRole, loginId FROM login_tbl WHERE loginEmail = ? AND d_status = 0");
            $auth->bind_param("s", $email1);
            $auth->execute();
            $sqlresult = $auth->get_result();
            $nor = $sqlresult->num_rows;

            if ($nor > 0) {
$newpassword = md5($passwd1) ;
                $row = $sqlresult->fetch_assoc();
                $dbpassword = $row['loginPasswd'];

                
                //if (admin or customer) 
                if ($passwd1 == $dbpassword or $newpassword == $dbpassword ) {
$loginstatus = $row['loginStatus'];

                    if ($loginstatus == 1) {
$loginrole = $row['loginRole'];

                        switch ($loginrole) {
                            case "admin":
                                $userId = $row['loginId'];
                                $_SESSION['user'] = $userId;
                                $_SESSION['user_id'] = $userId;
                                $_SESSION['usertype'] = 'admin';
                                $_SESSION['user_name'] = 'Admin';
                                $_SESSION['user_email'] = $email1;
                                return json_encode([
                                    'loginstatus' => true,
                                    'status' => true,
                                    'message' => "Logged in as Admin",
                                    'path' => "/grocery_mngmnt/lib/views/dshbdadmin.php"
                                ]);

                            case "customer":
                                $userId = $row['loginId'];
                                $_SESSION['user'] = $userId;
                                $_SESSION['user_id'] = $userId;
                                $_SESSION['usertype'] = 'customer';
                                $nameQ = $this->dbResult->prepare("SELECT customerName, customerEmail FROM customer_tbl WHERE customerID = ?");
                                $nameQ->bind_param("s", $userId);
                                $nameQ->execute();
                                if ($nameRow = $nameQ->get_result()->fetch_assoc()) {
                                    $_SESSION['user_name'] = $nameRow['customerName'];
                                    $_SESSION['user_email'] = $nameRow['customerEmail'];
                                } else {
                                    $_SESSION['user_name'] = $email1;
                                    $_SESSION['user_email'] = $email1;
                                }
                                return json_encode([
                                    'loginstatus' => true,
                                    'status' => true,
                                    'message' => "Logged in as Customer",
                                    'path' => "/grocery_mngmnt/lib/views/dshbdcustomer.php"
                                ]);

                                case "employee":
                                    $userId = $row['loginId'];
                                    $_SESSION['user'] = $userId;
                                    $_SESSION['user_id'] = $userId;
                                    $_SESSION['usertype'] = 'employee';
                                    $_SESSION['user_name'] = 'Employee';
                                    $_SESSION['user_email'] = $email1;
                                    return json_encode([
                                        'loginstatus' => true,
                                        'status' => true,
                                        'message' => "Logged in as Employee",
                                        'path' => "/grocery_mngmnt/lib/views/dshbdemployee.php"
                                    ]);

                                    case "delivery_Person":
                                        $userId = $row['loginId'];
                                        $_SESSION['user'] = $userId;
                                        $_SESSION['user_id'] = $userId;
                                        $_SESSION['usertype'] = 'delivery_Person';
                                        $_SESSION['user_name'] = 'Delivery Person';
                                        $_SESSION['user_email'] = $email1;
                                        return json_encode([
                                            'loginstatus' => true,
                                            'status' => true,
                                            'message' => "Logged in as Delivery Person",
                                            'path' => "/grocery_mngmnt/lib/views/dshbddeliver.php"
                                        ]);

                            default:
                                return json_encode([
                                    'status' => false,
                                    'message' => "Unknown role"
                                ]);
                        }

                    } else {
                        return json_encode([
                            'status' => false,
                            'message' => "Account is deactivated",
                            'error_type' => "acc_deactivated"
                        ]);
                    }

                } else {
                    return json_encode([
                        'status' => false,
                        'message' => "Password is incorrect",
                        'error_type' => "wrong_password"
                    ]);
                }

            } 
        else {
                return json_encode([
                    'status' => false,
                    'message' => "Email is invalid",
                    'error_type' => "email_not_found"
                ]);
            }

        } else {
            return json_encode([
                'status' => false,
                'message' => "Fill all inputs",
                'error_type' => "fill_in"
            ]);
        }
    }


    public function resetPassword($email, $nic, $newPassword) {
        $check = $this->dbResult->prepare(
            "SELECT customerID FROM customer_tbl WHERE customerEmail = ? AND customerNIC = ? AND d_status = 0"
        );
        $check->bind_param("ss", $email, $nic);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            return json_encode([
                'status' => false,
                'message' => 'Email and NIC do not match any account',
                'error_type' => 'no_match'
            ]);
        }

        $row = $result->fetch_assoc();
        $customerId = $row['customerID'];
        $hashedPassword = md5($newPassword);

        $updateCustomer = $this->dbResult->prepare(
            "UPDATE customer_tbl SET customerPasswd = ? WHERE customerID = ?"
        );
        $updateCustomer->bind_param("ss", $newPassword, $customerId);
        $updateCustomer->execute();

        $updateLogin = $this->dbResult->prepare(
            "UPDATE login_tbl SET loginPasswd = ? WHERE loginId = ?"
        );
        $updateLogin->bind_param("ss", $hashedPassword, $customerId);
        $updateLogin->execute();

        return json_encode([
            'status' => true,
            'message' => 'Password reset successfully'
        ]);
    }

}


                      