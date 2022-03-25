<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
// echo date("H:i:s");
// die();
$unknown = FALSE;
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
$nik 	= $_POST['nik'];
$dir = __DIR__ ."/face_id/".$nik."/";
$res="";
$img = $_FILES["fileToUpload"]["name"];
$target = $dir . basename($_FILES["fileToUpload"]["name"]);
$urlImg = $img;
$imageFileType = strtolower(pathinfo($target,PATHINFO_EXTENSION));
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)) {
		$tgl 	= $_POST['tgl'];
		$jam 	= $_POST['jam'];
		$status = $_POST['status'];
		if(isset($_POST['lat'])){
			$lat = $_POST['lat'];
			$lng = $_POST['lng'];
		}else{
			$lat = 0.0;
			$lng = 0.0;
		}
		if(isset($_POST['id_roster'])){
			$id_roster= $_POST['id_roster'];
		}else{
			$id_roster = 0;
		}
		$id = $_POST['id'];
		$lupa_absen = $_POST['lupa_absen'];
		$split = explode(' ', $tgl);
		if(empty($split)){
			if(in_array($split[1], $bulanIndo)){
				$tglNya = date("Y-m-d",strtotime(tanggal_indo($split)));
			}else{
				$tglNya = date("Y-m-d",strtotime($tgl));
			}
		}else{
			$tglNya = date("Y-m-d",strtotime($tgl));
		}

// $command = escapeshellcmd('python3 /var/www/html/rkbsys/public/face_id/identity.py '.$nik." ".$urlImg);
// $output = shell_exec($command);
// $string = trim(preg_replace('/\s+/', ' ', $output));
// $unknown = $string;
if($unknown==TRUE){
		$res= " | Failed!! (Wajah Tidak Dikenal = ".$unknown.")";
		$q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$res."','".date("Y-m-d H:i:s")."')";
		   	$koneksi->query($q);
}else{
		if($lupa_absen==null){
$sql =  "INSERT INTO ceklog (nik,tanggal,jam,gambar,status,face_id,lupa_absen,lat,lng,id_roster) VALUES ('".$nik."','".date("Y-m-d")."','".date("H:i:s")."','".$urlImg."','".$status."','".$id."','','".$lat."','".$lng."','".$id_roster."')";
		}else{
$sql =  "INSERT INTO ceklog (nik,tanggal,jam,gambar,status,face_id,lupa_absen,lat,lng,id_roster) VALUES ('".$nik."','".$tglNya."','".date("H:i:s")."','".$urlImg."','".$status."','".$id."','".$lupa_absen."','".$lat."','".$lng."','".$id_roster."')";
		}
		if ($koneksi->query($sql) === TRUE) {

		    $res= "The file ".$sql. " has been uploaded. Tidak Dikenal =".$unknown;

			$q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$unknown."','".date("Y-m-d H:i:s")."')";

		   	$koneksi->query($q);
		} else {
			// $unknown = TRUE;
		    $res= "Error ".$sql. " | Failed!! (Wajah Tidak Dikenal = ".$unknown.") ";
		    $errResult = $koneksi->error;
		    $q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$errResult."','".date("Y-m-d H:i:s")."')";
		   	$koneksi->query($q);
		}
}

    } else {
		// $unknown = TRUE;
        $res= "Sorry, there was an error uploading your file. ".$unknown;
        $errResult = $res;
		    $q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$errResult."','".date("Y-m-d H:i:s")."')";
		   	$koneksi->query($q);
    }
echo json_encode(array("image"=>$img,"res"=>$res,"tidak_dikenal"=>$unknown));
?>
