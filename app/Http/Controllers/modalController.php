<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;
use \App\Events\onlineUserEvent;

class modalController extends Controller
{
    //
    private $user;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
    }
    //
    public function cancel_item(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $part_name = $request->part_name;
        $no_rkb = $request->no_rkb;

        $cancel = DB::table('e_rkb_cancel')
                  ->insert([
                    "no_rkb"=>$no_rkb,
                    "part_name"=>$part_name,
                    "remarks"=>$request->remarks,
                    "cancel_by"=>$_SESSION['username'],
                    "timelog"=>date("Y-m-d H:i:s"),
                  ]);
        if($cancel){
            return "OK";
        }else{
            return "ERROR";
        }
    }
    public function setRemaks(Request $request)
    {       
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $part_name = $request->part_name;
        $no_rkb = $request->no_rkb;
        return view("page.modal",[ "no_rkb"=>$no_rkb,"part_name"=>$part_name,"setRemarks"=>"OK"]);
    }
    public function files_item(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $part_name = $request->part_name;
        $no_rkb = $request->no_rkb;
        $_penawaran = DB::table('e_rkb_penawaran')->where([
            ['no_rkb',$no_rkb],
            ['part_name',$part_name],
        ])->get();
        return view("page.modal",[ "no_rkb"=>$no_rkb,"part_name"=>$part_name,"itemFiles"=>"OK","penawaran"=>$_penawaran]);
    }
    public function pictures(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $part_name = $request->part_name;
        $parent_eq = $request->parent_eq;
        $no_rkb = $request->no_rkb;
        $_penawaran = DB::table('e_rkb_pictures')->where([
            ['no_rkb',$no_rkb],
            ['part_name',$part_name],
        ])->orderBy("timelog")->get();
        return view("page.modal",[ "no_rkb"=>$no_rkb,"part_name"=>$part_name,"parent_eq"=>$parent_eq,"itemPICTURES"=>"OK","penawaran"=>$_penawaran]);
    }
    public function files_view(Request $request,$img_name)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $get = Storage::disk('pictures')->get($img_name);

         $imgExt =  explode('.',$img_name);
         $Ext = end($imgExt);
        if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg"){
            return Response::make($get, 200)
                  ->header('Content-Disposition' , 'filename='.$img_name)
                  ->header('Content-Type', "image/jpg,image/png");
        }elseif($Ext=="pdf"){
            return Response::make($get, 200)
                  ->header('Content-Disposition' , 'filename='.$img_name)
                  ->header('Content-Type', "application/pdf");
        }else{
            $header = "*";
            return Response::make($get, 200)
                  ->header('Content-Type', $header);
        }
    }
    public function files_view_penawaran(Request $request,$img_name)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['section']=="PURCHASING")
        {
        $img_name = hex2bin($img_name);

        $penawaran = DB::table('e_rkb_penawaran')->where('file',$img_name)->first();
        $get = Storage::disk('penawaran')->get($img_name);        
        return response($get)
                  ->header('Content-Disposition' , 'filename='.'PENAWARAN-'.$penawaran->no_rkb.'-'.$penawaran->part_name)
                  ->header('Content-Type', 'application/pdf');
              }else{

        $img_name = ($img_name);

        $penawaran = DB::table('e_rkb_penawaran')->where('file',$img_name)->first();
        $get = Storage::disk('pictures')->get($img_name);        
        return response($get)
                  ->header('Content-Disposition' , 'filename='.'PENAWARAN-'.$penawaran->no_rkb.'-'.$penawaran->part_name)
                  ->header('Content-Type', 'application/pdf');
              }
    }
    public function delete_file(Request $request,$img_name)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $delete_db = DB::table('e_rkb_penawaran')->where('file',$img_name)->delete();
        if($delete_db){
            $delete_file = Storage::disk("penawaran")->delete($img_name);
            if($delete_file){
                return redirect()->back()->with("success","Successfully deleted!");
            }else{
                return redirect()->back()->with("failed","Failed to delete!");
            }
        }

    }
    public function delete_gambar(Request $request,$img_name)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $delete_db = DB::table('e_rkb_pictures')->where('file',$img_name)->delete();
        if($delete_db){
            $delete_file = Storage::disk("pictures")->delete($img_name);
            if($delete_file){
                return redirect()->back()->with("success","Successfully deleted!");
            }else{
                return redirect()->back()->with("failed","Failed to delete!");
            }
        }

    }
    public function cancel_rkb(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $rkb_no = hex2bin($request->no_rkb);
        $header = DB::table('e_rkb_header')->where("no_rkb",$rkb_no)->first();
        
        return view("page.modal",[ "header"=>$header,"rkb_cancel"=>"OK"]);
    }
    public function cancel_rkb_post(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
            $up = DB::table('e_rkb_approve')
                ->where([
                    ['no_rkb',$request->no_rkb]
                ])
                ->update([
                  "cancel_user" => $_SESSION['username'],
                  "cancel_section" => $_SESSION['section'],
                  "tgl_cancel_user" =>date("Y-m-d H:i:s"),
                  "remark_cancel" =>$request->remarks
                 ]);
            return redirect()->back()->with('success',"Has been canceled!");
    }

	function pearMail(Request $request){

//ini_set("include_path", '/usr/share/pear:' . ini_get("include_path") ); 
set_include_path("/usr/share/pear");
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
$port = "465";
$email_message = $from_address;


$auth = array(	'host' => $host, 
		'auth' => true, 
		'username' => $username, 
		'password' => $password, 
		'port' => $port);

$mime = new Mail_mime(); 
$mime->setHTMLBody($html); 
$mime->addAttachment($document,'Application/pdf');
$body = $mime->get();


$headers = array('From' => $from_address, 
		'To' => $email_to, 
		'Subject' => $email_subject, 
		'Reply-To '=> $reply_to,
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
}
}
