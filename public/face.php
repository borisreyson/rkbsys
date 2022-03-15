<?php 
// if(isset($_GET['nik'])){
// 	$nik = $_GET['nik'];
// 	$filename = $_GET['filename'];
// }
$nik= "18060207";
$filename="18060207_Masuk_2020-10-14__81029.jpg";
$command = escapeshellcmd("python3 /var/www/html/rkbsys/public/face_id/identity.py $nik $filename ");
$output = shell_exec($command);
$string = trim(preg_replace('/\s+/', ' ', $output));
echo json_encode(["tidak_dikenal"=>$string]);

?>
