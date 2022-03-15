<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailSend extends Model
{
    public function Via_Tripconn($url)
    {
    	$options = array(
              CURLOPT_CUSTOMREQUEST  =>"GET",    // Atur type request, get atau post
              CURLOPT_POST           =>false,    // Atur menjadi GET
              CURLOPT_FOLLOWLOCATION => true,    // Follow redirect aktif
              CURLOPT_CONNECTTIMEOUT => 120,     // Atur koneksi timeout
              CURLOPT_TIMEOUT        => 120,
              CURLOPT_RETURNTRANSFER => true,    // Atur response timeout
          );

          $ch      = curl_init( $url );          // Inisialisasi Curl
          curl_setopt_array( $ch, $options );    // Set Opsi
          $content = curl_exec( $ch );           // Eksekusi Curl
          curl_close( $ch );                     // Stop atau tutup script

          $header = $content;
          return $header;
    }
    public function Post_Tripconn($url,$data)
    {
      $post = $data;        
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,
                  $post);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $server_output = curl_exec($ch);
      curl_close ($ch);
      return $server_output;
    }
    public function sending($subject,$tipe,$no_rkb,$to)
    {      
          $subject =  $subject." | Sistem Rencana Kebutuhan Barang";
          $m = bin2hex($to);
          $judul = bin2hex($subject);
          
            $message = view("email.".$tipe ,["no_rkb"=>$no_rkb])->render();
            $teks = bin2hex($message);

          $f = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
          $get = "?sender=".$m."&teks=".$teks."&judul=".$judul."&from=".$f;
          //$sendEMAIL = new EmailSend();
          $result = $this->Via_Tripconn('https://abpjobsite.com/sendmail.php'.$get);
          return  $result;
    }
    public function ToKTT($subject,$tipe,$no_rkb,$to)
    {      
          $subject =  $subject." | Sistem Rencana Kebutuhan Barang";
          $m = bin2hex($to);
          $judul = bin2hex($subject);
          $RKBGET = DB::table("e_rkb_detail")
                              ->where("no_rkb",$no_rkb)
                              ->join("user_login","user_login.username","e_rkb_detail.user_entry")
                              ->get();
            $message = view("email.".$tipe ,["no_rkb"=>$no_rkb,"rkb"=>$RKBGET])->render();
            $teks = bin2hex($message);
          //  return $message;
          $f = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
          $get = "?sender=".$m."&teks=".$teks."&judul=".$judul."&from=".$f;
          //$sendEMAIL = new EmailSend();
          $result = $this->Via_Tripconn('https://abpjobsite.com/sendmail.php'.$get);
          return  $result;
    }
    public function Sarpas_mail($url,$to,$subject,$noid_out)
    {

      $subject1 =  $subject." | Sarana & Prasarana";
      $m = bin2hex($to);

          $sarpras = $db = DB::table("vihicle.v_out_h")
                        ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("db_karyawan.data_karyawan as p_nama","p_nama.nik","vihicle.v_out_h.nik")
                        ->leftjoin("db_karyawan.data_karyawan as driver","driver.nik","vihicle.v_out_h.driver")
                        ->leftjoin("vihicle.v_approve","v_approve.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("user_login","user_login.username","vihicle.v_approve.user_appr")
                        ->leftjoin("db_karyawan.data_karyawan as appr","appr.nik","user_login.nik")
                        ->select("vihicle.v_out_h.*","vihicle.v_in.*","p_nama.nama as nama_p","driver.nama as nama_d","vihicle.v_approve.*","appr.nama as nama_appr")
                        ->where("vihicle.v_out_h.noid_out",$noid_out)
                        ->first();
        // dd($noid_out);
          $message = view("email.sarpras" ,["noid_out"=>$noid_out,"sarpras"=>$sarpras])->render();
          
          $teks = bin2hex($message);
          //echo $message;

          $from = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
          $data1 = array("from"=>$from,"to"=>$m,"subject"=>$subject1,"message"=>$teks);
          $result = $this->Post_Tripconn($url,$data1);
            return $result;     

    }
    public function Sarpas_mail_user($url,$to,$subject,$noid_out,$urlPDF)
    {

      $subject1 =  $subject." | Sarana & Prasarana";
      $m = bin2hex($to);

          $sarpras = $db = DB::table("vihicle.v_out_h")
                        ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("db_karyawan.data_karyawan as p_nama","p_nama.nik","vihicle.v_out_h.nik")
                        ->leftjoin("db_karyawan.data_karyawan as driver","driver.nik","vihicle.v_out_h.driver")
                        ->leftjoin("vihicle.v_approve","v_approve.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("user_login","user_login.username","vihicle.v_approve.user_appr")
                        ->leftjoin("db_karyawan.data_karyawan as appr","appr.nik","user_login.nik")
                        ->select("vihicle.v_out_h.*","vihicle.v_in.*","p_nama.nama as nama_p","driver.nama as nama_d","vihicle.v_approve.*","appr.nama as nama_appr")
                        ->where("vihicle.v_out_h.noid_out",$noid_out)
                        ->first();

        $message = view("email.u_sarpras" ,["noid_out"=>$noid_out,"sarpras"=>$sarpras,"urlPDF"=>$urlPDF])->render();
        $teks = bin2hex($message);
        //echo $message;
        $from = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
        $data1 = array("from"=>$from,"to"=>$m,"subject"=>$subject1,"message"=>$teks);
        $result = $this->Post_Tripconn($url,$data1);
          return $result;
        
    }
    public function Sarpas_mail_security($url,$to,$subject,$noid_out)
    {

      $subject1 =  $subject." | Sarana & Prasarana";
      $m = bin2hex($to);

          $sarpras = $db = DB::table("vihicle.v_out_h")
                        ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("db_karyawan.data_karyawan as p_nama","p_nama.nik","vihicle.v_out_h.nik")
                        ->leftjoin("db_karyawan.data_karyawan as driver","driver.nik","vihicle.v_out_h.driver")
                        ->leftjoin("vihicle.v_approve","v_approve.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("user_login","user_login.username","vihicle.v_approve.user_appr")
                        ->leftjoin("db_karyawan.data_karyawan as appr","appr.nik","user_login.nik")
                        ->select("vihicle.v_out_h.*","vihicle.v_in.*","p_nama.nama as nama_p","driver.nama as nama_d","vihicle.v_approve.*","appr.nama as nama_appr")
                        ->where("vihicle.v_out_h.noid_out",$noid_out)
                        ->first();

        $message = view("email.w_sarpras" ,["noid_out"=>$noid_out,"sarpras"=>$sarpras])->render();
        $teks = bin2hex($message);
        //echo $message;
        $from = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
        $data1 = array("from"=>$from,"to"=>$m,"subject"=>$subject1,"message"=>$teks);
        $result = $this->Post_Tripconn($url,$data1);
          return $result;
        
    }
    public function Sarpas_mail_security_appr($url,$to,$subject,$noid_out,$urlPDF)
    {

      $subject1 =  $subject." | Sarana & Prasarana";
      $m = bin2hex($to);

          $sarpras = $db = DB::table("vihicle.v_out_h")
                        ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("db_karyawan.data_karyawan as p_nama","p_nama.nik","vihicle.v_out_h.nik")
                        ->leftjoin("db_karyawan.data_karyawan as driver","driver.nik","vihicle.v_out_h.driver")
                        ->leftjoin("vihicle.v_approve","v_approve.noid_out","vihicle.v_out_h.noid_out")
                        ->leftjoin("user_login","user_login.username","vihicle.v_approve.user_appr")
                        ->leftjoin("db_karyawan.data_karyawan as appr","appr.nik","user_login.nik")
                        ->select("vihicle.v_out_h.*","vihicle.v_in.*","p_nama.nama as nama_p","driver.nama as nama_d","vihicle.v_approve.*","appr.nama as nama_appr")
                        ->where("vihicle.v_out_h.noid_out",$noid_out)
                        ->first();

        $message = view("email.s_sarpras" ,["noid_out"=>$noid_out,"sarpras"=>$sarpras,"urlPDF"=>$urlPDF])->render();
        $teks = bin2hex($message);
        //echo $message;
        $from = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
        $data1 = array("from"=>$from,"to"=>$m,"subject"=>$subject1,"message"=>$teks);
        $result = $this->Post_Tripconn($url,$data1);
          return $result;
        
    }

    public function resetPassMail($subject,$id_user,$token,$to)
    {      
          $subject =  $subject." | Abp-System";
          $m = bin2hex($to);
          $judul = bin2hex($subject);
            $message = view("email.reset_password" ,["token"=>$token,"id_user"=>bin2hex($id_user)])->render();
            $teks = bin2hex($message);
          //  return $message;
          $f = bin2hex("Abp-System <admin.it@abpenergy.co.id>");
          $get = "?sender=".$m."&teks=".$teks."&judul=".$judul."&from=".$f;
          //$sendEMAIL = new EmailSend();
          $result = $this->Via_Tripconn('https://abpjobsite.com/sendmail.php'.$get);
          return  $result;
    }
}