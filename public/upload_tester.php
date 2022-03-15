<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
// echo date("H:i:s");
// die();
$unknown = TRUE;
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
	$query = $koneksi->query("SELECT * FROM db_karyawan.roster_kerja LEFT JOIN db_karyawan.kode_jam_masuk on db_karyawan.kode_jam_masuk.id_kode=db_karyawan.roster_kerja.jam_kerja WHERE db_karyawan.roster_kerja.nik ='".$nik."' and db_karyawan.roster_kerja.tanggal='".date("Y-m-d")."'");
	$row = $query->fetch_object();
	print_r($row->nama);

die();

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
			$lat = 0;
			$lng = 0;
		}
		$id = $_POST['id'];
		$lupa_absen = $_POST['lupa_absen'];
		$split = explode(' ', $tgl);
		if(in_array($split[1], $bulanIndo)){
			$tglNya = date("Y-m-d",strtotime(tanggal_indo($split)));
		}else{
			$tglNya = date("Y-m-d",strtotime($tgl));
		}

$command = escapeshellcmd('python3 /var/www/html/rkbsys/public/face_id/kenali.py '.$nik." ".$img);
$output = shell_exec($command);
$string = trim(preg_replace('/\s+/', ' ', $output));
$unknown = $string;
// echo $unknown;
// die();
// unlink($target);
if($unknown=="True"){
	$validasi = True;
		$res= " | Failed!! (Wajah Tidak Dikenal = ".$validasi.")";
		$q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$res."','".date("Y-m-d H:i:s")."')";
		   	$koneksi->query($q);
}elseif($unknown=="False"){
		if($lupa_absen==null){
$sql =  "INSERT INTO ceklog (nik,tanggal,jam,gambar,status,face_id,lupa_absen,lat,lng) VALUES ('".$nik."','".date("Y-m-d")."','".date("H:i:s")."','".$urlImg."','".$status."','".$id."','','".$lat."','".$lng."')";
		}else{			
$sql =  "INSERT INTO ceklog (nik,tanggal,jam,gambar,status,face_id,lupa_absen,lat,lng) VALUES ('".$nik."','".$tglNya."','".date("H:i:s")."','".$urlImg."','".$status."','".$id."','".$lupa_absen."','".$lat."','".$lng."')";
		}
		if ($koneksi->query($sql) === TRUE) {
	$validasi=False;

		    $res= "The file ".$sql. " has been uploaded.";
			
			$q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$validasi."','".date("Y-m-d H:i:s")."')";

		   	$koneksi->query($q);
		} else {
			unlink($target);
			$validasi=False;

			// $unknown = TRUE;
		    $res= "Error ".$sql. " | Failed!! (Wajah Tidak Dikenal = ".$validasi.") ";
		    $errResult = $mysqli->error;
		    $q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$errResult."','".date("Y-m-d H:i:s")."')";
		   	$koneksi->query($q);
		}   
}
    } else {
    	
		$validasi = TRUE;
        $res= "Sorry, there was an error uploading your file. ".$validasi;
        $errResult = $res;
		    $q = "INSERT INTO error_log (nik,error_result,datelog) VALUES ('".$nik."','".$errResult."','".date("Y-m-d H:i:s")."')";
		   	$koneksi->query($q);
    }
echo json_encode(array("image"=>$img,"res"=>$res,"tidak_dikenal"=>$validasi));
?>