<?php

//include mainphp
include_once('main.php');

//creste class
class AutoNumber extends Main{

    //number generate function
    function NumberGeneration($id, $table, $string){
            $currentID = "SELECT $id FROM $table ORDER BY $id DESC LIMIT 1;";
            if($this->dbResult->error){
                echo($this->dbResult->error);
                exit;
            }

            $sqlResult = $this->dbResult->query($currentID);

            if(!$sqlResult){
                echo($this->dbResult->error);
                exit;
            }
            $nor = $sqlResult->num_rows;

            if($nor >0){
                $rec = $sqlResult->fetch_assoc();
                $prevID = $rec[$id];
                $num = substr($prevID, strlen($string));
                $num = intval($num)+1;
                $id = str_pad($num, 5 , '0', STR_PAD_LEFT);
                $newID = $string.$id;
                return $newID;
            }else{
                $newID = $string."00001";
            }
            return $newID;
    }
}

?>