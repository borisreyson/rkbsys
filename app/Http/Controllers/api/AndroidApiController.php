<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class AndroidApiController extends Controller
{

    public $IP;
    public $user_agent;
    public function __construct()
    {
        $this->IP="";
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $this->IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $this->IP = $_SERVER['REMOTE_ADDR'];
        }

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
    
 	public function LoginValidate(Request $request)
 	   {
	 	   	if(isset($request->username) && isset($request->password)){
	 	   		$user = DB::table("user_login")
	 	   				->where([
	 	   						["username",$request->username],
                                ["password",md5($request->password)],
                                ["status",0]
                            	])
	 	   				->first();
                if($user!=null){
                    if(isset($request->android_token)){
                        $android_token =$request->android_token;
                        $app_version = $request->app_version;
                        $app_name =$request->app_name;
                        $cekToken = DB::table('keamanan.user_android')->where([
                                        ["nik",$user->nik],                                        
                                        ["phone_token",$android_token]
                                    ])->count();
                        if($cekToken==0){
                        $tokenIn= DB::table('keamanan.user_android')
                                    ->insert([
                                        "nik"=>$user->nik,
                                        "phone_token"=>$android_token,
                                        "app_version"=>$app_version,
                                        "tgl"=>date("Y-m-d"),
                                        "jam"=>date("H:i:s"),
                                        "app"=>$app_name
                                    ]);
                        }else{

                        $tokenIn= DB::table('keamanan.user_android')
                                    ->where([
                                        ["nik",$user->nik],                                        
                                        ["phone_token",$android_token]
                                    ])
                                    ->update([
                                        "app_version"=>$app_version,
                                        "tgl"=>date("Y-m-d"),
                                        "jam"=>date("H:i:s"),
                                        "app"=>$app_name
                                    ]);
                        }
                    }
                }
	 	   		return array("success"=>true,"user"=>$user);
	 	   	}else{
	 	   		return array("success"=>false);
	 	   	}
 	   }   
	public function checkApi(Request $request)
	{
		dd($request);
	}
	public function rkbUser(Request $request)
	{

	 	if(isset($request->username) && isset($request->department)){
           
            

		 if(isset($request->expired)){
            $expired = "IS NOT NULL";
        }else{
            $expired = "IS NULL";
        }
        if(isset($request->close_rkb)=="all"){
            $close_rkb = "IS NOT NULL";
        }else{
            $close_rkb = "IS NULL";
        }
            $sql = DB::table('e_rkb_header') 
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_header.no_rkb")
                    ->leftJoin("department","department.id_dept","e_rkb_header.dept")
                    ->leftJoin("section","section.id_sect","e_rkb_header.section")
                    ->leftJoin("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
                    ->leftJoin("user_login","user_login.username","e_rkb_detail.user_entry")
                    ->select("e_rkb_header.*","e_rkb_header.status as myStatus","e_rkb_approve.*","department.*","section.*","e_rkb_detail.*","user_login.*")
                    ->orderBy("e_rkb_detail.timelog","desc")
                    ->groupBy("e_rkb_detail.no_rkb")
                    ->whereRaw("user_expired ".$expired)
                    ->whereRaw("user_close ".$close_rkb);
            if($request->search){
            $filter = $sql->whereRaw("(e_rkb_header.no_rkb like '%".$request->search."%' or e_rkb_header.dept like '%".$request->search."%' or e_rkb_header.section like '%".$request->search."%' or e_rkb_header.tgl_order like '%".$request->search."%')");
            }else{
            $filter = $sql;
            }
            if(isset($request->disetujui)){
                if(isset($request->approve)){
                    $query = $filter->whereRaw("e_rkb_approve.disetujui ='".$request->disetujui."' or (e_rkb_approve.disetujui ='0' or e_rkb_approve.diketahui ='0')");
                }else{
                    $query = $filter->whereRaw("e_rkb_approve.disetujui ='".$request->disetujui."'");
                }
                }else if(isset($request->diketahui)){
                    $query = $filter->whereRaw("e_rkb_approve.diketahui ='".$request->diketahui."'");
                }else if(isset($request->approve)){
                    $query = $filter->whereRaw("(e_rkb_approve.disetujui =0 or e_rkb_approve.diketahui =0) and e_rkb_approve.cancel_section IS NULL ");
                }else if(isset($request->cancel)=="1"){                	
                    $query = $filter->whereRaw("e_rkb_approve.cancel_section IS NOT NULL");
                }else{
                    $query = $filter;
                }
                if(isset($request->level)=="administrator"){
                $rkb1 = $query;
                }else{
                     if($request->department=="ALL"){
                        $rkb1 = $query;
                    }else{
                        $rkb1 = $query->where([
                        ['e_rkb_header.dept',$request->department]
                        ]);
                    }
                
                }
                  $rkb= $rkb1->paginate(9); 
                return $rkb;
	 	}
	}
    public function rkbAdmin(Request $request)
    {
        if(isset($request->expired)=="1"){
            $expired = "IS NOT NULL";
        }else{
            $expired = "IS NULL";
        }
        if(isset($request->close_rkb)=="all"){
            $close_rkb = "IS NOT NULL";
        }else{
            if(isset($request->cancel)=="1"){

                $close_rkb = "IS NOT NULL";
            }else{
                $close_rkb = "IS NULL";
            }
        }
            $sql = DB::table('e_rkb_header')
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_header.no_rkb")
                    ->leftJoin("department","department.id_dept","e_rkb_header.dept")
                    ->leftJoin("section","section.id_sect","e_rkb_header.section")
                    ->leftJoin("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
                    ->leftJoin("user_login","user_login.username","e_rkb_detail.user_entry")
                    ->select("e_rkb_header.*","e_rkb_header.no_rkb","e_rkb_header.status as myStatus","e_rkb_approve.*","department.*","section.*","e_rkb_detail.*","user_login.*")
                    ->orderBy("e_rkb_detail.timelog","desc")
                    ->groupBy("e_rkb_detail.no_rkb")
                    ->whereRaw("user_expired ".$expired)
                    ->whereRaw("user_close ".$close_rkb);
            if($request->search){
            $filter = $sql->whereRaw("(e_rkb_header.no_rkb like '%".$request->search."%' or e_rkb_header.dept like '%".$request->search."%' or e_rkb_header.section like '%".$request->search."%' or e_rkb_header.tgl_order like '%".$request->search."%')");
            }else{
            $filter = $sql;
            }
            if(isset($request->disetujui)){
                if(isset($request->approve)){
                    $query = $filter->whereRaw("e_rkb_approve.disetujui ='".$request->disetujui."' or (e_rkb_approve.disetujui ='0' or e_rkb_approve.diketahui ='0')");
                }else{
                    $query = $filter->whereRaw("e_rkb_approve.disetujui ='".$request->disetujui."'");
                }
                }else if(isset($request->diketahui)){
                    $query = $filter->whereRaw("e_rkb_approve.diketahui ='".$request->diketahui."'");
                }else if(isset($request->approve)){
                    $query = $filter->whereRaw("(e_rkb_approve.disetujui =0 or e_rkb_approve.diketahui =0) and cancel_section IS NULL ");
                }else if(isset($request->cancel)=="1"){  

                    $query = $filter->whereRaw("cancel_section IS NOT NULL");
                }else{
                    
                    $query = $filter;
                }

                if(isset($request->dep)){
                    if(isset($request->seksi)){
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                    ['e_rkb_header.section',$request->seksi]
                ])->paginate(10);
                    }else{
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                ])->paginate(10);
                    }
                }else{
                    $rkb = $query->paginate(12);
                }
                return $rkb;          
    }
    public function rkbDetail(Request $request)
    {
        if(isset($request->no_rkb)){
            $detail = DB::table("e_rkb_detail")->where('no_rkb',$request->no_rkb)->get(); 
            return (array("detailRkb"=>$detail));   
        }
    }
    public function rkbKabagApprove(Request $request)
    {
        if(isset($request->username) && isset($request->no_rkb)){
        $no_rkb=$request->no_rkb;
        $username=$request->username;
        $ip = $this->IP;
        $approve = DB::table('e_rkb_approve')
                    ->where("no_rkb",$no_rkb)
                    ->update([
                        "disetujui" => 1,
                        "tgl_disetujui" => date("Y-m-d H:i:s")
                    ]);
        if($approve>0){
            $user_app = DB::table("user_approve")
                        ->insert([
                            "username"=>$username,
                            "no_rkb"=>$no_rkb,
                            "desk"=>"Disetujui",
                            "tgl_approve"=>date("Y-m-d H:i:s") 
                                ]);
        if($user_app>0){
        $ip = $this->IP;
        $kabag = DB::table('user_login')
                            ->where([
                                ["section","KTT"],
                                ["level","!=","PLT"]
                            ])->first();
        if($kabag->email!=null){
        $result = DB::table("queue_email.queue_rkb")
                        ->insert([
                                    "subjek"=>"New e-RKB",
                                    "tipe"=>"kabag",
                                    "no_rkb"=>$no_rkb,
                                    "email"=>$kabag->email,
                                    "date_in"=>date("Y-m-d H:i:s")
                                ]);
        $user_log = DB::table('user_log')->insert([
                        "username"  =>  $username,
                        "ip"        =>  $ip,
                        "time_log"  =>  date("Y-m-d H:i:s"),
                        "description"   => $username." Approve RKB : ".$no_rkb." | (".$ip.") Status Email  ".$kabag->email,
        "OS"            => $this->getOS(),
        "BROWSER"       => $this->getBrowser()
                       ]);
        $log = DB::table("email_log")
                        ->insert([
                            "email"   => $kabag->email,
                            "no_rkb"  => $no_rkb,
                            "timelog" => date("Y-m-d H:i:s"),
                            "status"  => $result
                        ]);
        }else{
            $user_log = DB::table('user_log')->insert([
                        "username"  =>  $username,
                        "ip"        =>  $ip,
                        "time_log"  =>  date("Y-m-d H:i:s"),
                        "description"   => $username." (".$ip.") Status Email  ".$kabag->email,
                        "OS"            => $this->getOS(),
                        "BROWSER"       => $this->getBrowser()
                       ]);
        }
            return array("aprrove"=>true);
        }else{
            return array("aprrove"=>false);
        }
            }else{
                return array("aprrove"=>false);
            }
        }
    }

    public function approveKTT(Request $request)
    {
        if(isset($request->username) && isset($request->no_rkb)){
        $no_rkb=$request->no_rkb;
        $username=$request->username;
        $ip = $this->IP;
        $approve = DB::table('e_rkb_approve')
                    ->where("no_rkb",$no_rkb)
                    ->update([
                        "diketahui" => 1,
                        "tgl_diketahui" => date("Y-m-d H:i:s")
                    ]);
        if($approve>=0)
        {
            $user_app = DB::table("user_approve")
            ->insert([
                "username"=>$username,
                "no_rkb"=>$no_rkb,
                "desk"=>"Diketahui",
                "tgl_approve"=>date("Y-m-d H:i:s") 
                    ]);
            if($user_app>=0){

$ip = $this->IP;
$kabag = DB::table('user_login')
                    ->where([
                        ["section","PURCHASING"],
                        ["status",0]
                    ])->get();
foreach ($kabag as $l => $p) {
if($p->email!=null){
$result = DB::table("queue_email.queue_rkb")
                ->insert([
                            "subjek"=>"New e-RKB",
                            "tipe"=>"ktt",
                            "no_rkb"=>$no_rkb,
                            "email"=>$p->email,
                            "date_in"=>date("Y-m-d H:i:s")
                        ]);
$user_log = DB::table('user_log')->insert([
                "username"  =>  $username,
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $username." Approve RKB : ".$no_rkb." | (".$ip.") Status Email  ".$p->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
$log = DB::table("email_log")
                ->insert([
                    "email"   => $p->email,
                    "no_rkb"  => $no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $result
                ]);
}else{
    $user_log = DB::table('user_log')->insert([
                "username"  =>  $username,
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $username." (".$ip.") Status Email  ".$p->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
}

}
                return array("aprrove"=>true);
            }else{
                return array("aprrove"=>false);
            }
        }else{
            return array("aprrove"=>false);
        }
        }
    }
    public function loginFaceId(Request $request)
    {
        $dataLogin= array("nik"=>null,"nama"=>null);
        if(isset($request->imei)){
            $imei= $request->imei;
        }else{
            $imei= "";
        }
        if(isset($request->username) && isset($request->password)){
            $user = DB::table("db_karyawan.data_karyawan")
                            ->where([
                                    ["nik",$request->username],
                                    ["password",md5($request->password)],
                                    ["flag",0]
                                ])->first();
            if($user!=null){
                    if(isset($request->android_token)){
                        $android_token =$request->android_token;
                        $app_name =$request->app_name;

                        $app_version = $request->app_version;
                        $cekToken = DB::table('keamanan.user_android')->where([
                                        ["nik",$user->nik],                                        
                                        ["phone_token",$android_token]
                                    ])->count();
                        if($cekToken==0){
                        $tokenIn= DB::table('keamanan.user_android')
                                    ->insert([
                                        "nik"=>$user->nik,
                                        "phone_token"=>$android_token,
                                        "app_version"=>$app_version,
                                        "tgl"=>date("Y-m-d"),
                                        "jam"=>date("H:i:s"),
                                        "app"=>$app_name,
                                        "imei"=>$imei
                                    ]);
                        }else{

                        $tokenIn= DB::table('keamanan.user_android')
                                    ->where([
                                        ["nik",$user->nik],                                        
                                        ["phone_token",$android_token]
                                    ])
                                    ->update([
                                        "app_version"=>$app_version,
                                        "tgl"=>date("Y-m-d"),
                                        "jam"=>date("H:i:s"),
                                        "app"=>$app_name,
                                        "imei"=>$imei
                                    ]);
                        }
                    }
                if(is_dir("face_id/".$user->nik.'/')){
                    $dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama,"show_absen"=>$user->show_absen,"perusahaan"=>$user->perusahaan);
                }else{
                    if(mkdir('face_id/'.$user->nik.'/')){
                        if(chmod("face_id/*", 0777)){
                            $dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama,"show_absen"=>$user->show_absen,"perusahaan"=>$user->perusahaan);
                        }else{
                            $dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama,"show_absen"=>$user->show_absen,"perusahaan"=>$user->perusahaan);
                        }
                    }else{
                        $dataLogin= array("nik"=>"error","nama"=>"error");
                    }
                }
                
                return (array("success"=>true,"dataLogin"=>$dataLogin));
            }else{
                return (array("success"=>false,"dataLogin"=>$dataLogin));
            }
        }
    }
    public function updatePasswordFaceid(Request $request)
    {
        if(isset($request->username) && isset($request->password)){
            $user = DB::table("db_karyawan.data_karyawan")
                            ->where([
                                    ["nik",$request->username],
                                    ["password",md5($request->password)]
                                ])->first();
            if($user!=null){
                $update = DB::table("db_karyawan.data_karyawan")
                                ->where([
                                    ["nik",$request->username],
                                    ["password",md5($request->password)]
                                ])->update([
                                    "password"=>md5($request->newPassword)
                                ]);
                if($update>=0){
                    $dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama);
                    return (array("success"=>true,"dataLogin"=>$dataLogin));
                }else{
                    $dataLogin= array("nik"=>$user->nik,"nama"=>$user->nama);
                    return (array("success"=>false,"dataLogin"=>$dataLogin));
                }             
            }else{
                return (array("success"=>false,"dataLogin"=>$dataLogin));
            }
        }
    }
    public function listAbsen(Request $request)
    {
        $nik = $request->nik;
        $url = url('/face_id/'.$nik.'/');
        $status = $request->status;
        $list = DB::table("absensi.ceklog")
                        ->leftJoin("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                        ->where([
                            ["absensi.ceklog.nik",$nik],
                            ["absensi.ceklog.status",$status]
                        ])
                        ->select("absensi.ceklog.*","db_karyawan.data_karyawan.nama",DB::raw("CONCAT('".$url."','/',gambar) as gambar"))
                        ->orderBy("absensi.ceklog.tanggal","desc")
                        ->paginate(10);
        if($list!=null){
        return array("listAbsen"=>$list);
        }else{
        return array("listAbsen"=>null);

        }
    }

    public function lastAbsen(Request $request)
    {
        $nik = $request->nik;
        $url = url('/face_id/'.$nik.'/');
        $list = DB::table("absensi.ceklog")
                        ->leftJoin("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                        ->where("absensi.ceklog.nik",$nik)
                        ->select("absensi.ceklog.*",DB::raw('DATE_FORMAT(absensi.ceklog.tanggal,"%d %b %Y") as tanggal'),"db_karyawan.data_karyawan.nama",DB::raw("CONCAT('".$url."','/',gambar) as gambar"))
                        ->orderBy("absensi.ceklog.tanggal","desc")
                        ->first();
        if($list!=null){
        return array("lastAbsen"=>$list);
        }else{
        return array("lastAbsen"=>null);

        }
    }
    public function updateTokenAbpEnergy(Request $request)
    {
        $cekToken = DB::table("keamanan.user_android")->where([
            ["nik",$request->nik],
            ["app",$request->app],
            ["phone_token",$request->phone_token]
        ])->first();
        if($cekToken!=null){
            if(isset($request->phone_token)){

            if($cekToken->phone_token!=$request->phone_token){
                $update = DB::table("keamanan.user_android")
                ->insert([
                    "nik"=>$request->nik,
                    "phone_token"=>$request->phone_token,
                    "app_version"=>$request->app_version,
                    "tgl"=>date("Y-m-d"),
                    "jam"=>date("H:i:s"),
                    "app"=>$request->app,
                    "imei"=>$request->imei
                ]);
                if ($update) {
                    return ["success"=>true];
                }else{
                    return ["success"=>false];
                }
             }else{
                return ["success"=>false];
             }
            }else{
                return ["success"=>false];
            }
        }else{
            $update = DB::table("keamanan.user_android")
                ->insert([
                    "nik"=>$request->nik,
                    "phone_token"=>$request->phone_token,
                    "app_version"=>$request->app_version,
                    "tgl"=>date("Y-m-d"),
                    "jam"=>date("H:i:s"),
                    "app"=>$request->app,
                    "imei"=>$request->imei
                ]);
                if($update) {
                    return ["success"=>true];
                }else{
                    return ["success"=>false];
                }
        }
    }
}
