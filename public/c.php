<?php

date_default_timezone_set('Asia/Kuala_Lumpur');
// echo date("H:i:s");
$bulanIndo = array ('Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);

function tanggal_indo($indoSplit)
{
	$bulan = array ('Januari'=>'01',
				'Februari'=>'02',
				'Maret'=>'03',
				'April'=>'04',
				'Mei'=>'05',
				'Juni'=>'06',
				'Juli'=>'07',
				'Agustus'=>'08',
				'September'=>'09',
				'Oktober'=>'10',
				'November'=>'11',
				'Desember'=>'12'
			);
	return $indoSplit[2] . '-' . $bulan[$indoSplit[1]] . '-' . $indoSplit[0];
}

$koneksi     = new mysqli("localhost", "root", "bujanginam26011995", "absensi");
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
} 
$dir = __DIR__ ."/face_id/";
$res="";
$img = $_FILES["fileToUpload"]["name"];
$target = $dir . basename($_FILES["fileToUpload"]["name"]);
$urlImg = $img;
$imageFileType = strtolower(pathinfo($target,PATHINFO_EXTENSION));
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)) {
		$nik 	= $_POST['nik'];
		$tgl 	= $_POST['tgl'];
		$jam 	= $_POST['jam'];
		$status = $_POST['status'];
		$id = $_POST['id'];
		$lupa_absen = $_POST['lupa_absen'];

		$split = explode(' ', $tgl);
		if(in_array($split[1], $bulanIndo)){
			$tglNya = date("Y-m-d",strtotime(tanggal_indo($split)));
		}else{
			$tglNya = date("Y-m-d",strtotime($tgl));
		}
		if($lupa_absen==null){
$sql =  "INSERT INTO ceklog (nik,tanggal,jam,gambar,status,face_id,lupa_absen) VALUES ('".$nik."','".date("Y-m-d")."','".date("H:i:s")."','".$urlImg."','".$status."','".$id."','')";
		}else{			
$sql =  "INSERT INTO ceklog (nik,tanggal,jam,gambar,status,face_id,lupa_absen) VALUES ('".$nik."','".date("Y-m-d",strtotime($tglNya))."','".date("H:i:s")."','".$urlImg."','".$status."','".$id."','".$lupa_absen."')";
		}
		if ($koneksi->query($sql) === TRUE) {
		    $res= "The file ".$sql. " has been uploaded.";
		} else {
		    $res= "Error ".$sql. " | Failed!!.";
		}        
    } else {
        $res= "Sorry, there was an error uploading your file.";
    }
echo json_encode(array("image"=>$img,"res"=>$res));

?>