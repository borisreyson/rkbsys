
<?php

$koneksi = mysqli_connect("localhost","root","bijikodokhp","ujian_online");
		if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
	$soal = [];
	if(isset($_POST['proses'])){
		$jawaban = $_POST['jawaban'];
		//print_r($jawaban);
		$session_user = $_POST['id_session'];
		$nama = $_POST['nama'];
		$b_n = $_POST['b_n'];
		$user =  mysqli_query($koneksi,"SELECT * from user where session_id='".$session_user."'");
			$rowUser = mysqli_fetch_object($user);
		if(!isset($rowUser)){
			$userIN =  mysqli_query($koneksi,"INSERT INTO user (session_id,nik,nama,tgl) VALUES ('".$session_user."','".$b_n."','".$nama."','".date("Y-m-d H:i:s")."')");
		}
		$benar=0;
		$salah=0;
		foreach($jawaban as $k => $v){
			$koreksi =  mysqli_query($koneksi,"SELECT * from soal where id='".$k."'");
			$rowKoreksi = mysqli_fetch_object($koreksi);

			$pilihan =  mysqli_query($koneksi,"SELECT * from pilihan_jawaban where kode='".$v."' and id_soal='".$k."'");
			$rowPilihan = mysqli_fetch_object($pilihan);
			$cek = mysqli_query($koneksi,"SELECT * from hasil_jawaban where id_soal='".$k."' and session_user ='".$session_user."' ");
			$rowCek = mysqli_fetch_object($cek);
			if(!isset($rowCek)){
				$inJawaban =mysqli_query($koneksi,"INSERT INTO hasil_jawaban  (id_soal,jawaban_dipilih,kode,session_user) VALUES ('".$k."','".$rowPilihan->jawaban."','".$rowPilihan->kode."','".$session_user."')");
			}
			///echo $v;
			if($rowPilihan->jawaban==$rowKoreksi->jawaban){
				$soal[$k]=1;
				$benar=$benar+1;
			}else{
				$soal[$k]=0;
				$salah=$salah+1;
			}
		}
		$nilai = ($benar/(count($jawaban))*100);

		//print_r($salah);
		//$koreksi =  mysqli_query($koneksi,"SELECT * from soal where id='".."'");
		//print_r($jawaban);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hasil SMKP QUIZ</title>
	<style>
		.dipilih{
			border:2px solid #333;
			border-radius: 50%;
			padding-right:5px;
			padding-left:5px;
			padding-bottom:2px;
			padding-top:2px;
			width: 20px;
		}
	</style>
</head>
<body>
	<center>
	<h1>Hasil SMK QUIZ</h1>
	<br><br><br><br>
<table border="1" cellpadding="10" cellspacing="0">
	<?php
	$sqlSoal =  mysqli_query($koneksi,"SELECT * from soal order by soal.id");
			while($rowSoal = mysqli_fetch_object($sqlSoal)){

					$gandaPilihan =  mysqli_query($koneksi,"SELECT * from pilihan_jawaban where id_soal='".$rowSoal->id."' and jawaban='".$rowSoal->jawaban."'");
					$rowgandaPilihan = mysqli_fetch_object($gandaPilihan);

					$sqlDijawab =  mysqli_query($koneksi,"SELECT * from hasil_jawaban where id_soal='".$rowSoal->id."'");
					$rowDijawab = mysqli_fetch_object($sqlDijawab);
	?>
	<tr>
		<td style="font-size: 20px;"><?php echo $rowSoal->id; ?></td>
		<td>
			<table border="0" cellspacing="0" cellpadding="10">
				<tr>
					<?php $sqlJawab =  mysqli_query($koneksi,"SELECT * from pilihan_jawaban where id_soal='".$rowSoal->id."' order by kode asc");
					while($rowJawab = mysqli_fetch_object($sqlJawab)){
					if($rowJawab->kode == $rowDijawab->kode){
					?>

					<td style="padding-left: 50px;padding-right: 50px;font-size: 20px;">
						<span class="dipilih">
						<?php echo $rowJawab->kode; ?>
						</span>
					</td>
					<?php }else{ ?>
					<td style="padding-left: 50px;padding-right: 50px;font-size: 20px;"><?php  echo $rowJawab->kode;?></td>

				<?php }  }?>
				</tr>
			</table>
		</td>
		<td>
			<?php 
				if($rowgandaPilihan->kode==$rowDijawab->kode){
					?>
					<span style="color: green;font-size: 20px;font-weight: bolder;">&checkmark;</span>
					<?php
				}else{
					?>
					<span style="color: red;font-size: 20px;font-weight: bolder;">&cross;</span>
					<?php
				}
			?>
		</td>		
	</tr>
	<?php } ?>
</table>
<br><br><br>
<table width="500px" border="1" cellspacing="0" cellpadding="10">
	<tr>
		<td width="150px">
			NAMA
		</td>
		<td colspan="2">
			<?php echo strtoupper($nama);?>
		</td>
	</tr>
	<tr>
		<td>
			B/N
		</td>
		<td colspan="2">
			<?php echo strtoupper($b_n);?>			
		</td>
	</tr>

	<tr style="height: 100px">
		<td>
			SCORE
		</td>
		<td style="vertical-align: top;">
			<span>Pre-Test</span>
			<br><br>
			<span></span>
		</td>
		<td style="vertical-align: top;">
			<span style="margin-top: 0px;">Pre-Test</span>
			<br><br>
			<span style="font-size: 30px;"><?php echo number_format($nilai,2); ?></span>
		</td>
	</tr>
</table>
</center>
</body>
</html>