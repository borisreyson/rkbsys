<?php

namespace App\Http\Controllers\android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use App\EmailSend;
class notifController extends Controller
{ 
    public function create_notification_hazard_verify(Request $request)
    {
        $res;
        $res1;
        $res2;
        $hazardTo = DB::table("keamanan.user_android as a")
                ->leftJoin("hse.hazard_report_detail as b","b.nikPJ","a.nik")
                ->leftJoin("hse.hazard_report_header as c","c.uid","b.uid")
                ->select("a.id","a.nik","c.uid","c.deskripsi","a.phone_token as pjToken","c.tgl_hazard","c.status_perbaikan")
                ->whereRaw("a.app='abpsystem' and c.status_perbaikan != 'SELESAI'")
                ->whereNotIn("a.id",function($q){ 
                  $q->select("id")->from("hse.notif_dibuat");
              })
              ->get();
              // return $hazardTo;
        if(count($hazardTo)>0){
        foreach ($hazardTo as $key => $value) {
            $notifDibuat = DB::table("hse.notif_dibuat")
            ->insert([
              "id"     =>$value->id,
              "uid"     =>$value->uid,
              "phone_token"  =>$value->pjToken,
              "tipe"  =>"kepada",
              "date_in" =>date("Y-m-d H:i:s")
            ]);
            $res = $notifDibuat;
        }
        }else{
          $res = $hazardTo;
        }

        $tenggatPJ = DB::table("keamanan.user_android as a")
                ->leftJoin("hse.hazard_report_detail as b","b.nikPJ","a.nik")
                ->leftJoin("hse.hazard_report_header as c","c.uid","b.uid")
                ->select("a.id","a.nik","c.uid","c.deskripsi","a.phone_token as pjToken","c.tgl_hazard","c.status_perbaikan")
                ->whereRaw("a.app='abpsystem' and tgl_tenggat <= '".date("Y-m-d")."' and c.status_perbaikan != 'SELESAI'")
                ->whereNotIn("a.id",function($q){ 
                  $q->select("id")->from("hse.notif_dibuat")->where("tipe","tenggat_pj");
              })
              ->groupBy("a.id")
              ->get();
        if(count($tenggatPJ)>0){
        foreach ($tenggatPJ as $key => $value) {
            $notifDibuat = DB::table("hse.notif_dibuat")
            ->insert([
              "id"     =>$value->id,
              "uid"     =>$value->uid,
              "phone_token"  =>$value->pjToken,
              "tipe"  =>"tenggat_pj",
              "date_in" =>date("Y-m-d H:i:s")
            ]);
            $res1 = $notifDibuat;
        }
        }else{
          $res1 = $tenggatPJ;
        }
        $tenggatUser = DB::table("keamanan.user_android as a")
                ->leftJoin("user_login as d","d.nik","a.nik")
                ->leftJoin("hse.hazard_report_header as c","c.user_input","d.username")
                ->leftJoin("hse.hazard_report_detail as b","b.uid","c.uid")
                ->select("a.id","a.nik","c.uid","c.deskripsi","a.phone_token as pjToken","c.tgl_hazard","c.status_perbaikan","c.user_input","b.nikPJ","d.username","d.nik")
                ->whereRaw("a.app='abpsystem' and tgl_tenggat <= '".date("Y-m-d")."' and c.status_perbaikan != 'SELESAI'")
                ->whereNotIn("a.id",function($q){ 
                  $q->select("id")->from("hse.notif_dibuat")->where("tipe","tenggat_user");
              })
              ->groupBy("a.id")
              ->get();
        if(count($tenggatUser)>0){
        foreach ($tenggatUser as $key => $value) {
            $notifDibuat = DB::table("hse.notif_dibuat")
            ->insert([
              "id"     =>$value->id,
              "uid"     =>$value->uid,
              "phone_token"  =>$value->pjToken,
              "tipe"  =>"tenggat_user",
              "date_in" =>date("Y-m-d H:i:s")
            ]);
            $res2 = $notifDibuat;
        }
        }else{
          $res2 = $tenggatUser;
        }
        echo json_encode([$res,$res1,$res2]);
    }
    public function notifKepada(Request $request)
    {
      $dailyDibuat="";
      $dailyTenggat="";
      $dailyPJ ="";
      $userNew="";
      if($request->tipe=="daily"){
        $notifDibuat = DB::table("hse.notif_dibuat")->whereRaw("tipe ='kepada' and DATE(date_send)>'".date("Y-m-d")."'")->get();
        $phone_token=[];
        if(count($notifDibuat)>0){
        foreach ($notifDibuat as $key => $value) {
          $phone_token[] = $value->phone_token;
          $update = DB::table("hse.notif_dibuat")->where([["id",$value->id],["flag",0]])->update(["date_send"=>date("Y-m-d H:i:s")]);
        }
          $dailyDibuat = $this->notifInside("penaggung_jawab",$phone_token);
        }
        $tenggat_user = DB::table("hse.notif_dibuat")->whereRaw("tipe ='tenggat_user' and DATE(date_send)>'".date("Y-m-d")."'")->get();
        if(count($tenggat_user)>0){
          foreach ($tenggat_user as $key => $value) {
            $phone_token[] = $value->phone_token;
            $update = DB::table("hse.notif_dibuat")->where([["id",$value->id],["flag",0]])->update(["date_send"=>date("Y-m-d H:i:s")]);
          }
          $dailyTenggat = $this->notifInside("tenggat_user",$phone_token);
        }
        $tenggatpj = DB::table("hse.notif_dibuat")->whereRaw("tipe ='tenggat_pj'")->get();
        if(count($tenggatpj)>0){
          foreach ($tenggatpj as $key => $value) {
            $phone_token[] = $value->phone_token;
            $update = DB::table("hse.notif_dibuat")->where([["id",$value->id],["flag",0]])->update(["date_send"=>date("Y-m-d H:i:s")]);
          }
          $dailyPJ = $this->notifInside("tenggat_pj",$phone_token);
        }
      }else{
        $notifDibuat = DB::table("hse.notif_dibuat")->where([["tipe","kepada"],["flag",0]])->get();
        $phone_token=[];
        foreach ($notifDibuat as $key => $value) {
          echo $value->uid."<br>";
          $phone_token[] = $value->phone_token;
          $update = DB::table("hse.notif_dibuat")->where([["id",$value->id],["flag",0]])->update(["flag"=>1]);
        }
        if(count($notifDibuat)>0){
          $user_login = DB::table("user_login as a")
                      ->join("keamanan.user_android as b","b.nik","a.nik")
                      ->select("a.nama_lengkap","a.email","a.nik","b.*")
                      ->where([["department","hse"],["status",0],["section","SAFETY"],["app","abpSystem"]])
                      ->get();
          foreach ($user_login as $key => $v) {
            echo $v->nama_lengkap."<br>";
            $phone_token[] = $v->phone_token;
          }  
          $userNew = $this->notifInside("penanggung_jawab",$phone_token);
        }
      }
      echo "dailyDibuat ".$dailyDibuat."<br> dailyTenggat ".$dailyTenggat."<br> dailyPJ ".$dailyPJ."<br> userNew ".$userNew;
    }
    public function hazardSetujui(Request $request)
    {
        $resiko = DB::table("hse.metrik_resiko")->get();
          $hazard = DB::table("hse.hazard_report_validation as a")
                ->join("hse.hazard_report_header as b","a.uid","b.uid")
                ->join("hse.hazard_report_detail as c","a.uid","c.uid")
                ->join("user_login as d","d.username","b.user_input")
                ->join("hse.metrik_resiko_kemungkinan as e","e.idKemungkinan","b.idKemungkinan")
                ->join("hse.metrik_resiko_keparahan as f","f.idKeparahan","b.idKeparahan")
                ->rightJoin("keamanan.user_android as g","g.nik","d.nik")
                ->select("a.*","b.*","c.*","d.nama_lengkap","e.kemungkinan","f.keparahan","e.nilai as nil_kemungkinan","f.nilai as nil_keparahan","b.user_input as dibuatOleh","g.phone_token")
                ->whereRaw("(YEAR(tgl_hazard)='".date("Y")."' and MONTH(tgl_hazard)='".date("m")."') and g.app='abpsystem' and user_valid IS NULL")->get();
          foreach ($hazard as $key => $value) {
            
            $nilaiResiko = $value->nil_kemungkinan*$value->nil_keparahan;
            $hsResiko = $resiko->where("max",">=",$nilaiResiko)->where("min","<=",$nilaiResiko)->first();

            $judul = "Hazard Report $value->nama_lengkap Belum Di Setujui";
            if($value->tgl_selesai==null){
            $tenggat = date('d F Y',strtotime($value->tgl_tenggat));
            }else{
            $tenggat = "-";
            }
            $line1 = "Perusahaan           : $value->perusahaan";
              $line2 = "Bahaya                   : $value->deskripsi";
              $line3 = "Batas Perbaikan   : $tenggat";
              $line4 = "Dibuat                    : $value->nama_lengkap";
              $line5 = "PIC                         : $value->namaPJ";
              $line6 = "Nilai Resiko           : $nilaiResiko";
              $line7 = "Resiko                    : $hsResiko->kategori";
              $pesan = [$line1,$line2,$line3,$line4,$line5,$line6,$line7];
              $hazardNotClose[]=["judul"=>$judul,"pesan"=>$pesan,"uid"=>$value->uid,"phone_token"=>$value->phone_token];
          }
          return ["hazardNotClose"=>$hazardNotClose];
    }
    public function tenggatHazard(Request $request)
    {
        $user_login = DB::table("user_login as a")
                    ->join("keamanan.user_android as b","b.nik","a.nik")
                    ->select("a.nama_lengkap","a.email","a.nik","b.*")
                    ->where([["department","hse"],["status",0],["section","SAFETY"],["app","abpSystem"]])
                    ->get();
        foreach ($user_login as $key => $v) {
          $safetyToken[] = $v->phone_token;
        }
        array_push($safetyToken,"ftsCCEv4RweGNQkDov1VMN:APA91bF1MyQnayR0hSVl2bzxq2HfJSDuQT0Onl_fNsv-QdNQNmXzax-RRbAux8oUKb3weFILZYxFPNvtXFPUhjAEK_TZQUbl-FirSS66QHaFx5qvCAs4atT3jC-e26NP_tDklTnl5tNE","fTxTeqZKQvqiUtv2Dqy2Yj:APA91bGpeGhGlvGUpfRGah0W3WlOcpGpRcDI6vV42zJzZQwIyqin13xQMIvMlLgTIBm2d2Mk2yeVtKWfTXPcTfsuZX9Ue7ym75PHHcD7t-7sKCE3dOzGrU2otl2K6YyfRkGCz3AyjI1r");

        return $this->notifInside("tenggat_hazard",$safetyToken);
    }

    public function dibuat(Request $request)
    {
        $user_login = DB::table("hse.notif_dibuat as a")->where("flag",0)
                    ->get();
        $resiko = DB::table("hse.metrik_resiko")->get();
         
         
        foreach ($user_login as $key => $v) {
          $safetyToken[] = $v->phone_token;
        }
        array_push($safetyToken,"ftsCCEv4RweGNQkDov1VMN:APA91bF1MyQnayR0hSVl2bzxq2HfJSDuQT0Onl_fNsv-QdNQNmXzax-RRbAux8oUKb3weFILZYxFPNvtXFPUhjAEK_TZQUbl-FirSS66QHaFx5qvCAs4atT3jC-e26NP_tDklTnl5tNE","fTxTeqZKQvqiUtv2Dqy2Yj:APA91bGpeGhGlvGUpfRGah0W3WlOcpGpRcDI6vV42zJzZQwIyqin13xQMIvMlLgTIBm2d2Mk2yeVtKWfTXPcTfsuZX9Ue7ym75PHHcD7t-7sKCE3dOzGrU2otl2K6YyfRkGCz3AyjI1r");
        echo "<br>";
        var_dump($safetyToken);

        return $this->notifInside("tenggat_hazard",$safetyToken);
    }
    public function sendNotifAbpEnergy($tipe,$phone_token,$judul,$message,$uid)
    {
      $device_id = "ecnjR1VeQsqX6kUUDrsTm0:APA91bH72Ll-8s0-4Ts7f5_pFZfwB8S9zvPyL9jODwNHIy5OgSd42YmnIiZbzPRTXTZt-X4wgrEJ5whv0YTgVcCTzIjZaXRe4j1f9hqeF1deG6_bx3J-FVf0e8DkkhDDRRtEa7bieteY";
      $boris = "cC6VsnHxRDqr0P7v8tL0aw:APA91bFpcojnNrDvvGY0_vnsCkPfnC3uBeLp49kx1x0pbpSXUyRCWi7IkIHBd_O5_Te-ByzTpZPJgtlFYw5yyuNUluqbNm8OlugweIdzy4eGtg_c5j5NbJoS0L3Wy72vYxXOLawLW_p5";
 
    $url = 'https://fcm.googleapis.com/fcm/send';

      /*api_key available in:
      Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
      $api_key = 'AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe';
                  
      $msg = array
    (
      'text'  => "$message",
      'title'   => "$judul",
      'tab_index'=>0,
      'tipe'=>$tipe,
      'uid'=>"$uid",
      'notif'=>"true"
      
      );
    $fields = array
    (  
      'registration_ids'  => [$phone_token,$boris,$device_id],
      'data'    => $msg
      );
      //header includes Content type and api key
      $headers = array(
          'Content-Type:application/json',
          'Authorization:key='.$api_key
      );
                  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('FCM Send Error: ' . curl_error($ch));
      }
      curl_close($ch);
      return $result;
    }
    public function notifAbpenergyInbox($tipe,$phone_token,$judul,$message,$uid,$id_notif)
    {
      $device_id = "ecnjR1VeQsqX6kUUDrsTm0:APA91bH72Ll-8s0-4Ts7f5_pFZfwB8S9zvPyL9jODwNHIy5OgSd42YmnIiZbzPRTXTZt-X4wgrEJ5whv0YTgVcCTzIjZaXRe4j1f9hqeF1deG6_bx3J-FVf0e8DkkhDDRRtEa7bieteY";
      $boris = "cC6VsnHxRDqr0P7v8tL0aw:APA91bFpcojnNrDvvGY0_vnsCkPfnC3uBeLp49kx1x0pbpSXUyRCWi7IkIHBd_O5_Te-ByzTpZPJgtlFYw5yyuNUluqbNm8OlugweIdzy4eGtg_c5j5NbJoS0L3Wy72vYxXOLawLW_p5";
 
    $url = 'https://fcm.googleapis.com/fcm/send';

      /*api_key available in:
      Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
      $api_key = 'AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe';
                  
      $msg = array
    (
      'text'  => "$message",
      'title'   => "$judul",
      'tab_index'=>0,
      'tipe'=>$tipe,
      'uid'=>"$uid",
      'notif'=>"true",
      'id_notif'=>$id_notif,
      'url'=>"https://abpjobsite.com"
      
      );
    $fields = array
    (  
      'registration_ids'  => [$phone_token,$boris,$device_id],
      'data'    => $msg
      );
      //header includes Content type and api key
      $headers = array(
          'Content-Type:application/json',
          'Authorization:key='.$api_key
      );
                  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('FCM Send Error: ' . curl_error($ch));
      }
      curl_close($ch);
      return $result;
    }
    public function notifInside($tipe,$phone_token){
    $url = 'https://fcm.googleapis.com/fcm/send';

      /*api_key available in:
      Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
      $api_key = 'AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe';
      $msg = array
    (
      'tipe'=>$tipe,
      );
    $fields = array
    (  
      'registration_ids'  => $phone_token,
      'data'    => $msg
      );
      //header includes Content type and api key
      $headers = array(
          'Content-Type:application/json',
          'Authorization:key='.$api_key
      );
                  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('FCM Send Error: ' . curl_error($ch));
      }
      curl_close($ch);
      return $result;
    }
    public function notificationGroup(Request $request)
    {
        $resiko = DB::table("hse.metrik_resiko")->get();
        $hazard = DB::table("hse.hazard_report_validation as a")
                ->rightJoin("hse.hazard_report_header as b","b.uid","a.uid")
                ->rightJoin("hse.hazard_report_detail as c","c.uid","a.uid")
                ->rightJoin("user_login as d","d.username","b.user_input")
                ->rightJoin("hse.metrik_resiko_kemungkinan as e","e.idKemungkinan","b.idKemungkinan")
                ->rightJoin("hse.metrik_resiko_keparahan as f","f.idKeparahan","b.idKeparahan")
                ->rightJoin("keamanan.user_android as g","g.nik","d.nik")
                ->select("a.*","b.*","c.*","d.nama_lengkap","d.nik as nikPembuat","e.kemungkinan","f.keparahan","e.nilai as nil_kemungkinan","f.nilai as nil_keparahan","b.user_input as dibuatOleh","g.phone_token")
                ->whereRaw("(YEAR(tgl_hazard)='".date("Y")."' and MONTH(tgl_hazard)='".date("m")."') and tgl_tenggat <= '".date("Y-m-d")."' and (b.status_perbaikan ='BELUM SELESAI' or b.status_perbaikan ='DIKERJAKAN' or b.status_perbaikan ='BERLANJUT' ) and g.app='abpsystem'")
                ->groupBy("b.user_input")
                ->get();
                // dd($hazard);
                // dd(array_unique($hazard));
          $hazardNotClose=[];
          foreach ($hazard as $key => $value) {
            $nilaiResiko = $value->nil_kemungkinan*$value->nil_keparahan;
            $hsResiko = $resiko->where("max",">=",$nilaiResiko)->where("min","<=",$nilaiResiko)->first();
            $judul = "Hazard Report $value->nama_lengkap Belum Dikerjakan!";
            if($value->tgl_selesai==null){
            $tenggat = date('d F Y',strtotime($value->tgl_tenggat));
            }else{
            $tenggat = "-";
            }
              $line1 = "Perusahaan           : $value->perusahaan";
              $line2 = "Bahaya                   : $value->deskripsi";
              $line3 = "Batas Perbaikan   : $tenggat";
              $line4 = "Dibuat                    : $value->nama_lengkap";
              $line5 = "PIC                         : $value->namaPJ";
              $line6 = "Nilai Resiko           : $nilaiResiko";
              $line7 = "Resiko                    : $hsResiko->kategori";
              $pesan = [$line1,$line2,$line3,$line4,$line5,$line6,$line7];
                      array_push($hazardNotClose,["judul"=>$judul,"pesan"=>$pesan,"uid"=>$value->uid,"phone_token"=>$value->phone_token]);
                        }
      return ["hazardNotClose"=>$hazardNotClose];
    }

    public function notifto(Request $request)
    {
        $resiko = DB::table("hse.metrik_resiko")->get();
        $hazard = DB::table("hse.notif_dibuat as g")
                ->leftJoin("hse.hazard_report_validation as a","a.uid","g.uid")
                ->leftJoin("hse.hazard_report_header as b","b.uid","a.uid")
                ->leftJoin("hse.hazard_report_detail as c","c.uid","a.uid")
                ->leftJoin("user_login as d","d.username","b.user_input")
                ->leftJoin("hse.metrik_resiko_kemungkinan as e","e.idKemungkinan","b.idKemungkinan")
                ->leftJoin("hse.metrik_resiko_keparahan as f","f.idKeparahan","b.idKeparahan")
                ->select("g.id","a.*","b.*","c.*","d.nama_lengkap","d.nik as nikPembuat","e.kemungkinan","f.keparahan","e.nilai as nil_kemungkinan","f.nilai as nil_keparahan","b.user_input as dibuatOleh","g.phone_token")
                ->where("g.phone_token","=",$request->token)
                ->get();
                 // return ($hazard);
          foreach ($hazard as $key => $value) {
            $nilaiResiko = $value->nil_kemungkinan*$value->nil_keparahan;
            $hsResiko = $resiko->where("max",">=",$nilaiResiko)->where("min","<=",$nilaiResiko)->first();
            $judul = "$value->nama_lengkap Membuat Hazard Report!";
            if($value->tgl_selesai==null){
            $tenggat = date('d F Y',strtotime($value->tgl_tenggat));
            }else{
            $tenggat = "-";
            }
              $line1 = "Perusahaan           : $value->perusahaan";
              $line2 = "Bahaya                   : $value->deskripsi";
              $line3 = "Batas Perbaikan   : $tenggat";
              $line4 = "Dibuat                    : $value->nama_lengkap";
              $line5 = "PIC                         : $value->namaPJ";
              $line6 = "Nilai Resiko           : $nilaiResiko";
              $line7 = "Resiko                    : $hsResiko->kategori";
              $pesan = [$line1,$line2,$line3,$line4,$line5,$line6,$line7];
                      $hazardNotClose[]=["judul"=>$judul,"pesan"=>$pesan,"uid"=>$value->uid,"phone_token"=>$value->phone_token];
                        }
      return ["hazardNotClose"=>$hazardNotClose];
    }

    public function tenggatUser(Request $request)
    {
        $resiko = DB::table("hse.metrik_resiko")->get();
        $hazard = DB::table("hse.notif_dibuat as g")
                ->leftJoin("hse.hazard_report_validation as a","a.uid","g.uid")
                ->leftJoin("hse.hazard_report_header as b","b.uid","a.uid")
                ->leftJoin("hse.hazard_report_detail as c","c.uid","a.uid")
                ->leftJoin("user_login as d","d.username","b.user_input")
                ->leftJoin("hse.metrik_resiko_kemungkinan as e","e.idKemungkinan","b.idKemungkinan")
                ->leftJoin("hse.metrik_resiko_keparahan as f","f.idKeparahan","b.idKeparahan")
                ->select("g.id","a.*","b.*","c.*","d.nama_lengkap","d.nik as nikPembuat","e.kemungkinan","f.keparahan","e.nilai as nil_kemungkinan","f.nilai as nil_keparahan","b.user_input as dibuatOleh","g.phone_token")
                ->where("g.phone_token","=",$request->token)
                ->get();
                 // return ($hazard);
          foreach ($hazard as $key => $value) {
            $nilaiResiko = $value->nil_kemungkinan*$value->nil_keparahan;
            $hsResiko = $resiko->where("max",">=",$nilaiResiko)->where("min","<=",$nilaiResiko)->first();
            $judul = "Hazard Report $value->nama_lengkap Belum Dikerjakan!";
            if($value->tgl_selesai==null){
            $tenggat = date('d F Y',strtotime($value->tgl_tenggat));
            }else{
            $tenggat = "-";
            }
              $line1 = "Perusahaan           : $value->perusahaan";
              $line2 = "Bahaya                   : $value->deskripsi";
              $line3 = "Batas Perbaikan   : $tenggat";
              $line4 = "Dibuat                    : $value->nama_lengkap";
              $line5 = "PIC                         : $value->namaPJ";
              $line6 = "Nilai Resiko           : $nilaiResiko";
              $line7 = "Resiko                    : $hsResiko->kategori";
              $pesan = [$line1,$line2,$line3,$line4,$line5,$line6,$line7];
                      $hazardNotClose[]=["judul"=>$judul,"pesan"=>$pesan,"uid"=>$value->uid,"phone_token"=>$value->phone_token];
                        }
      return ["hazardNotClose"=>$hazardNotClose];
    }
}
