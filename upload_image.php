<?php
$dir = "face_id/";
$res="";
$img = $_FILES["fileToUpload"]["name"];
$target = $dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target,PATHINFO_EXTENSION));
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)) {
        $res= "The file ". $target. " has been uploaded.";
    } else {
        $res= "Sorry, there was an error uploading your file.";
    }
echo json_encode(array("image"=>$img,"res"=>$res));
?>