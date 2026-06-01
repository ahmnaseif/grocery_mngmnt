<?php
include_once('main.php');

class ImageUpload extends Main{

    // public function imgUpload($imageName, $imageType, $folderName, $tempName, $id){
    //     $customName = $id."_".$imageName;
    //     $path = "../../upload/".$folderName."/".$customName;
    //     $dbpath = "upload/".$folderName."/".$customName;
    //     move_uploaded_file($tempName, $path);
    //     return($dbpath);

    // }
    
    // public function imgUpload($productImgName, $productImgType, $folderName, $productImgLocation, $id){


    //     $customName = $id."_".$productImgName;
    //     $path = "../../upload/".$folderName."/".$customName;
    //     $dbpath = "upload/".$folderName."/".$customName;
    //     move_uploaded_file($productImgLocation, $path);
    //     return($dbpath);

    // }


    public function imgUpload($fileName, $fileType, $folderName, $tmpLocation, $id){
        $uploadFolder = __DIR__ . "/../upload/" . $folderName . "/";

        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $fileName = str_replace(" ", "_", $fileName);
        $newFileName = $id . "_" . $fileName;
        $destination = $uploadFolder . $newFileName;

        if (move_uploaded_file($tmpLocation, $destination)) {
            return "upload/" . $folderName . "/" . $newFileName;
        } else {
            return "error";
        }
    }



   
}

?>



