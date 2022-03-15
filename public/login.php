<?php
$dataLogin= json_decode(array("nik"=>null,"nama"=>null));
if(isset($_POST['username']) && isset($_POST['password'])){
	
	$username = $_POST['username'];
	$password = md5($_POST['password']);
$koneksi     = mysqli_connect("localhost", "root", "bujanginam26011995", "db_karyawan");
$sql      = mysqli_query($koneksi, "SELECT * FROM data_karyawan where nik='".$username."' and password='".$password."'");

	$user = mysqli_fetch_object($sql);
	$cek = mysqli_nums_row($sql);

	if($cek>0){
		if(is_dir("face_id/".$user->nik)){
			$dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama);
		}else{
              if(mkdir('face_id/'.$user->nik.'/')){
              	if(chmod("face_id/*", 0777)){
					$dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama);
              	}else{
					$dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama);
              	}
              }else{
				$dataLogin= array("nik"=>"error","nama"=>"error");
              }
		}
		echo json_encode(array("success"=>true,"dataLogin"=>$dataLogin));
	}else{
		echo json_encode(array("success"=>false,"dataLogin"=>$dataLogin));
	}
}

echo json_encode(array("success"=>false,"dataLogin"=>$dataLogin));