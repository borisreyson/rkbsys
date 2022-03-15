<?php
//ini_set("include_path", '/usr/share/pear:' . ini_get("include_path") ); 

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    set_include_path('C:\xampp\php\pear');
} else {
    set_include_path("/usr/share/pear");
}
//die();

include "Mail.php";
include "Mail/mime.php" ;		

$sender = "borisreyson26@gmail.com";
$judul = "TEST";
$teks = "OK";
$title =  $judul;
$konten =  $teks;

$email_to = $sender;
$email_subject = $title; 


$host = "ssl://smtp.gmail.com"; // The hostname of your mail server 
$username = "admin.it@abpenergy.co.id"; 
$from_address = $username;  
$password = "bijikodokemail";
$reply_to = $username; 
$port = "587";
$email_message = "TESTING";


$auth = array(	'host' => $host, 
		'auth' => true, 
		'username' => $username, 
		'password' => $password, 
		'port' => $port);

$mime = new Mail_mime(); 
//$mime->setHTMLBody($html); 
//$mime->addAttachment($document,'Application/pdf');
$body = $mime->get();


$headers = array('From' => "corporate@mahkotagroup.com", 
		'To' => $email_to, 
		'Subject' => "corporate@mahkotagroup.com", 
		'Reply-To '=> "corporate@mahkotagroup.com",
		'MIME-Version'=>' 1.0',
		'Content-type'=>'text/html; charset=iso-8859-1',
		'X-Mailer'=>'PHP/' . phpversion());

$smtp = Mail::factory('smtp', $auth);

$mail = $smtp->send($email_to, $headers, $email_message);


    if (PEAR::isError($mail)) {
    echo json_encode(
                array(
                    "Status"=> $mail->getMessage(),
                    "Data" => array(
                                $sender,
                                $judul,
                                $teks
                                )
                    )
                );
    } else {
        echo json_encode(
                array(
                        "Status"=> $mail,
                        "Data"=> array( $sender, $judul, $teks ),
                        "PHP" =>phpversion()
                    )
                        );
    }

?>