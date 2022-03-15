<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$koneksi     = new mysqli("localhost", "root", "bujanginam26011995", "db_karyawan");
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
} 
if(isset($_POST['nik'])){
$nik 	= $_POST['nik'];
$dir = __DIR__ ."/face_id/recognized/".$nik."/";
$res="";

$img = $_FILES["fileToUpload"]["name"];
$target = $dir . basename($_FILES["fileToUpload"]["name"]);
$urlImg = $img;
$imageFileType = strtolower(pathinfo($target,PATHINFO_EXTENSION));
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)) {
		
$sql =  "INSERT INTO data_wajah (nik,wajah,tgl_in) VALUES ('".$nik."','".$urlImg."','".date("Y-m-d H:i:s")."')";
if ($koneksi->query($sql) === TRUE) {
		    $res= "The file ".$sql. " has been uploaded.";
		} else {
		    $res= "Error ".$sql. " | Failed!!.";
		} 
	}else {
        $res= "Sorry, there was an error uploading your file.";
    }
echo json_encode(array("image"=>$img,"res"=>$res));
}
?>