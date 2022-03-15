<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FirebaseController extends Controller
{
    //
    public $IP;
    public $user_agent;
    private $user;

    public function __construct()
    {
        session_start();
        $id_session = session_id();
        $this->IP="";
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $this->IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $this->IP = $_SERVER['REMOTE_ADDR'];
        }
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
    }
    Public function getOS() { 

        $os_platform  = "Unknown OS Platform";

        $os_array     = array(
                              '/windows nt 10/i'      =>  'Windows 10',
                              '/windows nt 6.3/i'     =>  'Windows 8.1',
                              '/windows nt 6.2/i'     =>  'Windows 8',
                              '/windows nt 6.1/i'     =>  'Windows 7',
                              '/windows nt 6.0/i'     =>  'Windows Vista',
                              '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                              '/windows nt 5.1/i'     =>  'Windows XP',
                              '/windows xp/i'         =>  'Windows XP',
                              '/windows nt 5.0/i'     =>  'Windows 2000',
                              '/windows me/i'         =>  'Windows ME',
                              '/win98/i'              =>  'Windows 98',
                              '/win95/i'              =>  'Windows 95',
                              '/win16/i'              =>  'Windows 3.11',
                              '/macintosh|mac os x/i' =>  'Mac OS X',
                              '/mac_powerpc/i'        =>  'Mac OS 9',
                              '/linux/i'              =>  'Linux',
                              '/ubuntu/i'             =>  'Ubuntu',
                              '/iphone/i'             =>  'iPhone',
                              '/ipod/i'               =>  'iPod',
                              '/ipad/i'               =>  'iPad',
                              '/android/i'            =>  'Android',
                              '/blackberry/i'         =>  'BlackBerry',
                              '/webos/i'              =>  'Mobile'
                        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $this->user_agent))
                $os_platform = $value;

        return $os_platform;
    }
    Public function getBrowser() {

        $browser        = "Unknown Browser";

        $browser_array = array(
                                '/msie/i'      => 'Internet Explorer',
                                '/firefox/i'   => 'Firefox',
                                '/safari/i'    => 'Safari',
                                '/chrome/i'    => 'Chrome',
                                '/edge/i'      => 'Edge',
                                '/opera/i'     => 'Opera',
                                '/netscape/i'  => 'Netscape',
                                '/maxthon/i'   => 'Maxthon',
                                '/konqueror/i' => 'Konqueror',
                                '/mobile/i'    => 'Handheld Browser'
                         );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $this->user_agent))
                $browser = $value;

        return $browser;
    }

    public function sendNotification(Request $request)
    {
    	$device_id ="dCGFIGZVTzCvZl0fyetucJ:APA91bHsXockZh9HcnHH_wRSTtmJOTuWFPrKUicS-EuP22FALA0fYw-KBk6zqt_f0gcPVxg_PCIPwGANuAXcYZ7683RvVpnQ0WeGIAWdb_-ghW6AdgtIs9zwSNRcHagssac1CC0pIFQQ";
    	$message ="00163/ABP/RKB/ENP/2020 Baru saja dibuat!";
      $tipe = $request->tipe;
    	# code...
    	 //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
    $api_key = 'AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe';
                
    $msg = array
	(
		'text' 	=> "$message",
		'title'		=> "Rkb Baru Telah Dibuat",
    'tab_index'=>0,
    'tipe'=>$tipe,
    'no_rkb'=>"00163/ABP/RKB/ENP/2020",
    'notif'=>"true"
    
		);
	$fields = array
	(  
		'registration_ids' 	=> [$device_id],
		'data'		=> $msg
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
    public function sendNotificationFaceId(Request $request)
    {
      $app = DB::table("keamanan.app_version")->where("app","face")->first();
        if($app!=null){
          $versionName = $app->version;
          $urlUpdate = $app->url;
        }else{
          $versionName = null;
          $urlUpdate = null;
        }
      $roster = DB::table("db_karyawan.roster_kerja")
                ->join("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
                ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","db_karyawan.roster_kerja.nik")
                ->where([
                  ["tanggal",date("Y-m-d")]
                ])
                ->groupBy("db_karyawan.roster_kerja.nik")
                ->get();
      foreach($roster as $k => $v){
      $absen = DB::table("absensi.ceklog")
      ->where([
        ['nik',$v->nik],
        ["tanggal",date("Y-m-d",strtotime($v->tanggal))]
      ])->first(); 
      // $this->faceIdNotif($v->nik,"Absen Masuk","Anda Belum Absen Masuk");

      //VALID NOTF OFF
        if($v->kode_jam!="OFF"){
          echo $v->kode_jam."<br>";
        //BELUM ABSEN
        if($absen==null){
          $jam = strtotime(date("H:i:s"));
          $masuk = strtotime($v->masuk);
          $sebelumMasuk = strtotime("-5 minutes",$masuk);
          $lewatMasuk = strtotime("+5 minutes",$masuk);
          if($jam>=$sebelumMasuk && $jam<=$lewatMasuk){
            $this->faceIdNotif($v->nik,"Absen Masuk","Anda Belum Absen Masuk",0,"belum_absen",$v->nik,$urlUpdate);
          }               
        }else{          
          $jam = strtotime(date("H:i:s"));
          $pulang = strtotime("+10 minutes",strtotime($v->pulang));
          // dd($pulang);
          $sebelumPulang = strtotime("+15 minutes",$pulang);
          if($absen->status=="Masuk"){
            if($jam>=$pulang && $jam<=$sebelumPulang){
              $checkPulang = DB::table("absensi.ceklog")->where([
                ["nik",$absen->nik],
                ["tanggal",date("Y-m-d",strtotime($absen->tanggal))],
                ["status","Pulang"]
              ])->count();
              if($checkPulang<=0){
                  $this->faceIdNotif($absen->nik,"Absen Pulang","Anda Belum Absen Pulang",0,"belum_absen",$v->nik,$urlUpdate);
                // echo $absen->nik."<br>";
              }
              // echo date("Y-m-d",strtotime($absen->tanggal))." ".$absen->status."<br>";
              // $this->faceIdNotif($absen->nik,"Absen Pulang","Anda Belum Absen Pulang",0,"belum_absen",$v->nik,$urlUpdate);
            } 
          }
        }
        //BELUM ABSEN 

        //NOTIF TERLAMBAT DAN BELUM PULANG
        if($absen!=null){
          $dbSendNotif = DB::table("db_karyawan.data_karyawan")->where("show_absen",1)->get();
          if($absen->status=="Masuk"){
              $masuk = strtotime($absen->jam);
              $rMasuk = strtotime("+10 minutes",strtotime($v->masuk));
              if($masuk>$rMasuk){
                  $dbMasuk = DB::table("absensi.notif_absen")
                  ->whereRaw("nik_terlambat='".$v->nik."' and date(time_input) = '".date("Y-m-d",strtotime($v->tanggal))."' and flag=1")
                  ->orderBy("time_input","desc")
                  ->count();
                  if($dbMasuk==0){
              // echo $absen->nik."<br>";
                    foreach ($dbSendNotif as $key => $value) {
                      // echo $dbMasuk->nik."-".$v->nama.$dbMasuk->status." | ".$dbMasuk->flag."<br>";
                      $this->faceIdNotif($value->nik,$v->nama." Terlambat","( ".$v->nik." ) ".$v->nama." Terlambat, Absen Pada Pukul: ".date("H:i:s",$masuk),1,"terlambat",$v->nik,$urlUpdate);
                      $this->faceIdNotif($v->nik,$v->nama." Terlambat","( ".$v->nik." ) ".$v->nama." Terlambat, Absen Pada Pukul: ".date("H:i:s",$masuk),1,"terlambat",$v->nik,$urlUpdate);
                    }
                  }
              }
             
          }

          $checkPulang = DB::table("absensi.ceklog")->where([
                ["nik",$absen->nik],
                ["tanggal",date("Y-m-d",strtotime($absen->tanggal))],
                ["status","Pulang"]
              ])->first();
          if($checkPulang!=null){
            // echo $checkPulang->nik;
            $pulang = strtotime($checkPulang->jam);
            $rPulang = strtotime($v->pulang);
             if($pulang<$rPulang){
              $dbMasuk = DB::table("absensi.notif_absen")
                  ->whereRaw("nik_terlambat='".$v->nik."' and date(time_input) = '".date("Y-m-d",strtotime($v->tanggal))."'")
                  ->orderBy("time_input","desc")
                  ->count();
                  if($dbMasuk==0){
                    foreach ($dbSendNotif as $key => $value) {
                      $this->faceIdNotif($value->nik," Belum Jam Pulang","( ".$v->nik." ) ".$v->nama." Absen Sebelum Jam Pulang, Absen Pada Pukul: ".date("H:i:s",$pulang),1,"terlambat",$v->nik,$urlUpdate);
                      $this->faceIdNotif($v->nik," Belum Jam Pulang","( ".$v->nik." ) ".$v->nama." Absen Sebelum Jam Pulang, Absen Pada Pukul: ".date("H:i:s",$pulang),1,"terlambat",$v->nik,$urlUpdate);

                      // $this->faceIdNotif("18060207","Belum Jam Pulang","( ".$v->nik." ) ".$v->nama." Absen Sebelum Jam Pulang, Absen Pada Pukul: ".date("H:i:s",$masuk),1,"terlambat",$v->nik,$urlUpdate);
                    }
                  }
             }

          }
        } 
        //NOTIF TERLAMBAT DAN BELUM PULANG      
        }
        //VALID OFF NOT NOTIF
      }
      //UPDATE
      $user_android=DB::table("keamanan.user_android")->where("app","faceId")->orderBy("tgl","desc")->groupBy("phone_token")->get();
      foreach ($user_android as $key => $value) {  
      $currentVersion = $value->app_version;
      $updateVersion = $versionName;
      if($currentVersion < $updateVersion){
        // echo $value->nik." | ".$value->app_version." | ".$value->phone_token."<br>";
        // $this->updateNotif($value->nik,"Pembaharuan Tersedia","Versi ".$versionName." Telah di rilis, Mohon untuk memperbaharui!",26,"update",$value->nik,$urlUpdate,$value->phone_token);
        }
        // $this->updateNotif($value->nik,"Informasi","Pemberitahuan untuk tidak melakukan Absen Pulang Sebelum Jam Pulang. Terima Kasih",31,"update",$value->nik,"",$value->phone_token);
      }
      //UPDATE
      $this->kirimPesan();
    }
    public function tulisPesan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataInformasi = DB::table("keamanan.informasi")
                        ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","keamanan.informasi.nik")
                        ->select("keamanan.informasi.*","db_karyawan.data_karyawan.nik","db_karyawan.data_karyawan.nama")
                        ->paginate(10);
        return view('page.tulispesan',["dataInformasi"=>$dataInformasi,"getUser"=>$this->user]); 
    }
    public function simpanPesan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $z=0;
        $dataUser = DB::table("keamanan.user_android")->get();
        foreach($dataUser as $k => $v){
          $dataInformasi = DB::table("keamanan.informasi")
                        ->insert([
                          "nik"=>$v->nik,
                          "subjek"=>$request->subjek_informasi,
                          "pesan"=>$request->pesan_informasi,
                          "userIn"=>$_SESSION['username'],
                          "tglIn"=>date("Y-m-d H:i:s"),
                          "phone_token"=>$v->phone_token
                        ]);
                        $z++;
        }
        
        if($z==count($dataUser)){
          return redirect()->back()->with("success","Data Telah Di Proses!");
        }else{
          return redirect()->back()->with("success","Data Telah Di Proses!");
        }
    }
    public function kirimPesan()
    {
      //INFORMASI
      $user_android=DB::table("keamanan.informasi")->where("flag",0)->get();
      foreach ($user_android as $key => $value) { 
        $this->kirimNotifInformasi($value->nik,$value->subjek,$value->pesan,"19","update",$value->nik,"",$value->phone_token,$value->id_informasi,$value->app);
      }
      //INFORMASI
    }
    public function kirimNotifInformasi($nik,$title,$message,$flag,$type,$nikNotif,$urlUpdate,$phone_token,$id_informasi,$app)
    {
              
              if($phone_token!=null){
              $device_id = $phone_token;
              $tipe = $type;
              # code...
               //API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/  
            
$api_key = 'AAAAM3Gi_5Y:APA91bFa8dsma0GzZOSYVdMmlg5OBZtXomvaX83nCA5DL2JKn-alB0PyMlfVjMXXSgz6CIInwe7mNkK7q37eYFu7IGcenf1mh1zwkCag1-LvZlYcQampeKP3fvps6fQkbRV_NCJxn88_'; 
echo "faceId<br><br><br>";
            
            $msg = array
          (
            'text'  => "$message",
            'title'   => "$title",
            'tab_index'=>0,
            'tipe'=>$tipe,
            'urlUpdate'=>$urlUpdate,
            'nik'=>$nikNotif,
            'no_rkb'=>"",
            'notif'=>"true"
            );
          $fields = array
          (  
            'registration_ids'  => [$device_id],
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
            }else{
              $dbMasuk = DB::table("absensi.notif_absen")
                        ->insert([
                          "nik"=>$nik,
                          "status"=>$message,
                          "time_input"=>date("Y-m-d H:i:s"),
                          "flag"=>19                       
                        ]);
            if($app=="faceId"){

            $user_android=DB::table("keamanan.informasi")
                      ->where("id_informasi",$id_informasi)
                      ->update([
                        "flag"=>1,
                        "kodeSukses"=>1
                      ]);  
                    }else if($app=="abpenergy"){
                      $user_android=DB::table("keamanan.informasi")
                      ->where("id_informasi",$id_informasi)
                      ->update([
                        "flag"=>1,
                        "kodeSukses"=>2
                      ]);  
                    }

            }
            curl_close($ch);              
            }
            // $this->sendNotificationABPSYSTEM("rkb",$phone_token);
    }
    public function faceIdNotif($nik,$title,$message,$flag,$type,$nikNotif,$urlUpdate)
    {
      $faceId=DB::table("keamanan.user_android")->where([
          ["app","faceId"],
          ['nik',$nik]
        ])
      ->orderBy("id","desc")->first();
              if($faceId!=null){
                
              $device_id = $faceId->phone_token;
              $tipe = $type;
              # code...
               //API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
            $api_key = 'AAAAM3Gi_5Y:APA91bFa8dsma0GzZOSYVdMmlg5OBZtXomvaX83nCA5DL2JKn-alB0PyMlfVjMXXSgz6CIInwe7mNkK7q37eYFu7IGcenf1mh1zwkCag1-LvZlYcQampeKP3fvps6fQkbRV_NCJxn88_'; 
            $msg = array
          (
            'text'  => "$message",
            'title'   => "$title",
            'tab_index'=>0,
            'tipe'=>$tipe,
            'urlUpdate'=>$urlUpdate,
            'nik'=>$nikNotif,
            'no_rkb'=>"",
            'notif'=>"true"
            
            );
          $fields = array
          (  
            'registration_ids'  => [$device_id],
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
            }else{
              if(isset($nikNotif)){
                
                $dbMasuk = DB::table("absensi.notif_absen")
                        ->insert([
                          "nik"=>$nik,
                          "nik_terlambat"=>$nikNotif,
                          "status"=>$message,
                          "time_input"=>date("Y-m-d H:i:s"),
                          "flag"=>$flag                        
                        ]);

              }else{
                $dbMasuk = DB::table("absensi.notif_absen")
                        ->insert([
                          "nik"=>$nik,
                          "status"=>$message,
                          "time_input"=>date("Y-m-d H:i:s"),
                          "flag"=>$flag                        
                        ]);
                      }              
            }
            curl_close($ch);
              
            }
    }


    public function updateNotif($nik,$title,$message,$flag,$type,$nikNotif,$urlUpdate,$phone_token)
    {
      $faceId=DB::table("keamanan.user_android")->where([
          ["app","faceId"],
          ['nik',$nik],
          ['phone_token',$phone_token]
        ])
      ->orderBy("id","desc")->first();
              if($faceId!=null){
                
              $device_id = $faceId->phone_token;
              $tipe = $type;
              # code...
               //API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
            $api_key = 'AAAAM3Gi_5Y:APA91bFa8dsma0GzZOSYVdMmlg5OBZtXomvaX83nCA5DL2JKn-alB0PyMlfVjMXXSgz6CIInwe7mNkK7q37eYFu7IGcenf1mh1zwkCag1-LvZlYcQampeKP3fvps6fQkbRV_NCJxn88_'; 
            $msg = array
          (
            'text'  => "$message",
            'title'   => "$title",
            'tab_index'=>0,
            'tipe'=>$tipe,
            'urlUpdate'=>$urlUpdate,
            'nik'=>$nikNotif,
            'no_rkb'=>"",
            'notif'=>"true"
            
            );
          $fields = array
          (  
            'registration_ids'  => [$device_id],
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
            }else{
              $dbMasuk = DB::table("absensi.notif_absen")
                        ->insert([
                          "nik"=>$nik,
                          "status"=>$message,
                          "time_input"=>date("Y-m-d H:i:s"),
                          "flag"=>$flag                        
                        ]);
            }
            curl_close($ch);              
            }
    }



    public function sendNotificationBAK(Request $request)
    {
      $tab_index= $request->tab_index;
      $device_id =$request->device_id;
      $message =$request->message;
      $title =$request->title;
      # code...
       //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
    $api_key = 'AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe';
                
    $msg = array
  (
    'text'  => "$message",
    'title'   => "$title",
    'tab_index'=>$tab_index
    
    );
  $fields = array
  (  
    'registration_ids'  => $device_id,
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
    public function sendNotificationABPSYSTEM(Request $request)
    {
      $device_id ="ecnjR1VeQsqX6kUUDrsTm0:APA91bH72Ll-8s0-4Ts7f5_pFZfwB8S9zvPyL9jODwNHIy5OgSd42YmnIiZbzPRTXTZt-X4wgrEJ5whv0YTgVcCTzIjZaXRe4j1f9hqeF1deG6_bx3J-FVf0e8DkkhDDRRtEa7bieteY";
      $message ="00163/ABP/RKB/ENP/2020 Baru saja dibuat!";
      # code...
       //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
    $api_key = 'AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe';
                
    $msg = array
  (
    'text'  => "$message",
    'title'   => "Rkb Baru Telah Dibuat",
    'tab_index'=>0,
    'tipe'=>$request->tipe,
    'uid'=>"00163/ABP/RKB/ENP/2020",
    'notif'=>"true"
    
    );
  $fields = array
  (  
    'registration_ids'  => [$device_id],
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
    public function abpenergyAndroid(Request $request)
    {
      $url = 'https://fcm.googleapis.com/fcm/send';

      $api_key = "AAAAi0MKLZI:APA91bHGc0GmoUtJThmJI_o8n8kH1LQ71hKRzc89FHC2-wJ0QrAJddqvbSh-o5JZWmh9URBsvKYXgt3Ak_6YC9Cu9Gwv0P14UPWobVr44ugRjvLadUWyrwlHo1QK8PCTJw7BAATNSHOe";
      $device_id;
    }
      
}