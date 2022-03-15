<?php session_start();
		$id_session = session_id();
		?>
<!DOCTYPE html>
<html>
<head>
	<title>SMKP Awareness Test</title>
</head>
<body>
	<center>
		<h1>SMKP Awareness Test</h1>
	</center>

<form action="hasil.php" method="post" name="form_quiz">
	<input type="hidden" name="id_session" value="<?php echo $id_session;?>">
<table border="1" cellspacing="0" cellpadding="10" style="margin-left: 50px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td><input type="text" style="font-size: 16px;" name="nama" placeholder="Nama Lengkap" required="required"></td>
		</tr>
		<tr>
			<th>B/N</th>
			<th> : </th>
			<td><input type="text"  style="font-size: 16px;" name="b_n" placeholder="B / N" required="required"></td>
		</tr>
</table>
<br>
<br>
<br>
<table border="0" cellpadding="7" cellspacing="0">
	<?php
		
		$koneksi = mysqli_connect("localhost","root","bijikodokhp","ujian_online");
		if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }

		 $soal =  mysqli_query($koneksi,"SELECT * from soal where status='1'");
		 while ($rowSoal = mysqli_fetch_object($soal)){
	?>
	<thead>
		<tr>
			<th><?php echo $rowSoal->id?></th>
			<th> . </th>
			<td colspan="4" style="font-weight: bolder;font-size: 17px;"><?php echo $rowSoal->soal?></td>
		</tr>
	</thead>
	<tbody>
		<?php		
		 $pilihan =  mysqli_query($koneksi,"SELECT * from pilihan_jawaban where id_soal='".$rowSoal->id."'");
		while( $rowPilihan = mysqli_fetch_object($pilihan)){
		?>
		<tr>
			<td>&nbsp;</td>
			<td><input type="radio" name="jawaban[<?php echo $rowSoal->id;?>]" id="<?php echo $rowSoal->id."_".$rowPilihan->kode;?>" value="<?php echo $rowPilihan->kode;?>" required></td>
			<td><label for="<?php echo $rowSoal->id."_".$rowPilihan->kode;?>"><?php echo $rowPilihan->kode?></label></td>
			<td>.</td>
			<td><label for="<?php echo $rowSoal->id."_".$rowPilihan->kode;?>"><?php echo $rowPilihan->jawaban?></label></td>
		</tr>
	<?php } ?>
	</tbody>
<?php } ?>
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<tr>
	<td colspan="4">&nbsp;</td>
	<td align="right"><input type="submit" style="font-size: 20px;" name="proses" value="Hitung Jawaban">
		<button type="reset" name="reset"  style="font-size: 20px;">Reset</button></td>
</tr>
</table>
	<br>
	<br>
	<br>
	<br>
</form>
</body>
</html>
