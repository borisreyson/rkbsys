<?php

header("Access-Control-Allow-Origin:*");
include('config.php');
/*
if(isset($_SERVER['HTTP_ORIGIN'])){
      $r = parse_url($_SERVER['HTTP_ORIGIN']);
      $myorigin = explode(".",$r['host']);
      if($myorigin[1]=="lapaketam"){
header("Access-Control-Allow-Origin:http://".$myorigin[0].".lapaketam.xyz");
      }else if($myorigin[1]=="xyz"){
header("Access-Control-Allow-Origin:http://lapaketam.xyz");
      }    
    }
    
    */
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    set_include_path('C:\xampp\php\pear');
} else {
    set_include_path("/usr/share/pear");
}
//die();

include "Mail.php";
include "Mail/mime.php" ;   

 if(isset($_GET['sender']))
    {
$sender = $_GET['sender'];
    $judul = $_GET['judul'];
    $teks = $_GET['teks'];
    if(isset($_GET['from'])){
    $f = $_GET['from'];
    $from =  hex2bin($f);        
    }else{
        $f = "No-Reply <noreply@tripconnector.xyz>";
        $from =  ($f);  
    }
    $title =  hex2bin($judul);
    $konten =  hex2bin($teks);     
 
$email_to = hex2bin($sender);  //Enter the email you want to send the form to

$email_subject = $title;  // You can put whatever subject here

//$host = "tls://tridadi.idweb.host";  // The hostname of your mail server
//$username = "cc@tripconnector.xyz";

$host = "ssl://smtp.gmail.com";  // The hostname of your mail server
$username = "admin.it@abpenergy.co.id";
$from_address = $from;
//$password = "boltox2601";
$password = "bijikodokemail";
$reply_to = $from;

$port = "465";

$email_message = $konten;

$auth = array('host' => $host, 'auth' => true, 'username' => $username, 'password' => $password, 'port' => $port);
$mime =  new Mail_mime();
$mime->setHTMLBody($html);

$mime->addAttachment($document,'Application/pdf');
$body = $mime->get();

$headers = array('From' => $from_address, 'To' => $email_to, 'Subject' => $email_subject, 'Reply-To '=> $reply_to,'MIME-Version'=>' 1.0','Content-type'=>'text/html; charset=iso-8859-1','X-Mailer'=>'PHP/' . phpversion());

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
                        "Data"=>   array( $sender, $judul, $teks ),
                        "PHP" =>phpversion()
                    ) 
                        );
    }  
}
?>
