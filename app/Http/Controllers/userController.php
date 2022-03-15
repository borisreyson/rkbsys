<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Events\onlineUserEvent;

class userController extends Controller
{

    //
    private $user;
    public $IP;
    public $user_agent;
    public function __construct()
    {
        //ini_set('session.cookie_domain', '.rkb.it');
        session_set_cookie_params(0,"/",".rkb.it"); 
        session_set_cookie_params(0,"/","rkb.it"); 
        session_start();
        $id_session = session_id();
        $IP="";
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $this->IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $this->IP = $_SERVER['REMOTE_ADDR'];
        }

        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
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

    public function login(Request $request)
    {
    	if(isset($_SESSION['username'])!=""){
    		return redirect('/')->with('msg','Anda Harus Login!');
    	}
    	$u = $request->username;
    	$p = $request->password;
 		$user = DB::table('user_login')->where([
 			['username',$u],
 			['password',md5($p)],
            ['status',0]
 		]);
 		$count = $user->count();
 		$first = $user->first();
 		if($count>0){
 			$_SESSION['username'] = $first->username;
 			$_SESSION['department'] = $first->department;
            $_SESSION['section'] = $first->section;
            $_SESSION['jabatan'] = $first->section;
            $_SESSION['level'] = $first->level;
        //event(new onlineUserEvent("USER LOGIN FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
            $ip = $this->IP;

$user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$ip.") Logged In "
               ]);
 			return "OK";
 		}else{
 			return "FAILED";
 		}
    }
    public function logout(Request $request)
    {

    	if(isset($_SESSION['username'])==""){
    		return redirect('/')->with('msg','Anda Harus Login!');
    	}
        //event(new onlineUserEvent("USER LOGOUT FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
        $user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $this->IP,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$this->IP.") Logged Out "
               ]);
    	unset($_SESSION['username']);
    	unset($_SESSION['department']);
        unset($_SESSION['section']);
        unset($_SESSION['jabatan']);
        unset($_SESSION['level']);
    	return redirect()->back()->with('success','Anda sudah logout!');
    }
    public function user(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($request->cari)){
            $cari = $request->cari;
        }else{
            $cari = "";
        }
        if($_SESSION['section']!="IT") return redirect('/')->with("failed","Page Not Found!");
        $user = DB::table('user_login')
                ->leftjoin("department","department.id_dept","user_login.department")
                ->leftjoin("section","section.id_sect","user_login.section")
                ->leftjoin("user_level","user_level.level","user_login.level")
                ->leftjoin("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","user_login.nik")
                ->select("user_login.*","department.*","section.*","user_level.desk as desk_lvl","db_karyawan.data_karyawan.*")
                ->whereRaw("user_login.department like '%".$cari."%' or
                            user_login.username like '%".$cari."%' or
                            user_login.section  like '%".$cari."%' or
                            user_login.status like '%".$cari."%' or
                            user_login.nama_lengkap like '%".$cari."%'
                            ")
                ->groupBy("user_login.username")
                ->paginate(10);
        return view("page.master.user",["user"=>$user,"getUser"=>$this->user]);
    }
    public function form_user(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dept = DB::table('department')->groupBy('dept')->get();
        $level = DB::table('user_level')->get();
        return view("page.master.form_user",[
            "department"=>$dept,
            "getUser"=>$this->user,
            "level"=>$level]);
    }    
    public function form_edit_user(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $edit_user = DB::table("user_login")->where("username",hex2bin($username))->first();
        $dept = DB::table('department')->groupBy('dept')->get();
        $level = DB::table('user_level')->get();
        return view("page.master.form_user",[
            "edit_user"=>$edit_user,
            "department"=>$dept,
            "getUser"=>$this->user,
            "level"=>$level]);
    }
    public function create_user(Request $request)
    {
        
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = $create = DB::table('user_login')->where("username" ,$request->username)->get();
        if(count($cek)==0){
            $create = DB::table('user_login')->insert([
                "username"      =>  $request->username,
                "password"      =>  md5($request->password),
                "nama_lengkap"  =>  $request->nama_lengkap,
                "department"    =>  $request->dept,
                "section"       =>  $request->section,
                "level"       =>  $request->level,
                "tglentry"       => date('Y-m-d H:i:s')
            ]);
            if($create){
                $user_log = DB::table('user_log')->insert([
                    "username"  =>  $_SESSION['username'],
                    "ip"        =>  $this->IP,
                    "time_log"  =>  date("Y-m-d H:i:s"),
                    "description"   => $_SESSION['username']." (".$this->IP.") Create User ".$request->username
                   ]);
                return redirect('/user')->with("success","User Baru Telah Di Tambah!");
            }else{
                return redirect()->back()->with("failed","Menambah User Baru Gagal!");
            }
        }else{
                return redirect()->back()->with("failed","Duplicate Username! Please Use Different Username!");
        }
    }
    public function update_user(Request $request,$username)
    {
        //dd($request);
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['level']!="administrator" ){
            $update = DB::table('user_login')->where("username",hex2bin($username))
                ->update([
                "username"       =>  $request->username,
                "nama_lengkap"   =>  $request->nama_lengkap,
                "tglentry"       => date('Y-m-d H:i:s')
            ]);
                if($update){
                    $_SESSION['username'] = $request->username;
                }
        }else{
            $update = DB::table('user_login')->where("username",hex2bin($username))
                ->update([
                "username"       =>  $request->username,
                "nama_lengkap"   =>  $request->nama_lengkap,
                "department"     =>  $request->dept,
                "section"        =>  $request->section,
                "level"          =>  $request->level,
                "tglentry"       => date('Y-m-d H:i:s')
            ]);
        }
        if($update){

            $user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $this->IP,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$this->IP.") Update User ".$request->username
               ]);
            if($_SESSION['section']!="administrator"){
            return redirect('/')->with("success","User Telah Di Update!");
            }else{
            return redirect('/')->with("success","User Telah Di Update!");                
            }
        }else{
            return redirect('/')->with("success","User Telah Di Update!");
        }
    }
    public function form_password_user(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //if($_SESSION['username']==$username || $_SESSION['section']=="IT"){
                $password_user = DB::table("user_login")->where("username",hex2bin($username))->first();
                $dept = DB::table('department')->groupBy('dept')->get();
                return view("page.master.password",[
                    "password_user"=>$password_user,
                    "department"=>$dept,
                    "getUser"=>$this->user]);
        //    }else{
                return redirect()->back()->with("failed","Halaman Tidak Ditemukan");
        //    }

    }
    public function update_password_user(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $hash = password_hash($request->retype_password,PASSWORD_DEFAULT);
        if(password_verify($request->password,$hash)){
            $change = DB::table('user_login')->where("username",hex2bin($username))
                      ->update([
                        "password"=>md5($request->password)
                      ]);
            if($_SESSION['section']=="IT")
            {

            $user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $this->IP,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$this->IP.") Change Password User ".hex2bin($username),
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
                return redirect('/user')->with("success","Password Telah Diganti!");
            }else{
                return redirect()->back()->with("success","Password Telah Diganti!");            
            }

        }else{
                return redirect()->back()->with("failed","Password Tidak Sama!");       
        }
    }
    public function level_form(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view("page.master.level_form",[
            "getUser"=>$this->user]);
    }
    public function level_user(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $level = DB::table('user_level')->paginate(10);
        return view("page.master.level_user",[
            "getUser"=>$this->user,"level"=>$level]);
    }
    public function level_create(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table('user_level')->where("level",$request->level)->get();
        if(count($cek)>0){
                return redirect()->back()->with("failed","Duplicate New Level! Please Create Different Level!");
        }else{
            $IN = DB::table('user_level')->insert([
                                        "level" => $request->level,
                                        "desk"  => $request->desk
                                        ]);
            if($IN){
                return redirect('/level/user')->with("success","Create New Level Success!");
            }else{

                return redirect()->back()->with("failed","Create New Level Failed!");
            }
        }
    }
    public function level_edit(Request $request,$level)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $DB_level = DB::table('user_level')->where("level",hex2bin($level))->first();
        return view("page.master.level_form",[
            "getUser"=>$this->user,"edit_level"=>$DB_level]);
    }
    public function level_update(Request $request,$level)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $DB_level = DB::table('user_level')
                    ->where("level",hex2bin($level))
                    ->update([
                        "level" => $request->level,
                        "desk"  => $request->desk
                            ]);
        if($DB_level>=0){
            return redirect('/level/user')->with("success","Update Level Success!");
        }else{
            return redirect()->back()->with("failed","Update Level Failed!");
        }
    }
    public function user_dis(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $dis = DB::table('user_login')
                ->where("username",hex2bin($username))
                ->update([
                    "status"=>1
                    ]);
        if($dis>=0){
            return redirect()->back()->with("success","Disable User Success!");   
        }else{
            return redirect('/user')->with("failed","Disable User Failed!");
        }
    }
    public function user_en(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $dis = DB::table('user_login')
                ->where("username",hex2bin($username))
                ->update([
                    "status"=>0
                    ]);
        if($dis>=0){
            return redirect()->back()->with("success","Enable User Success!");
        }else{
            return redirect('/user')->with("failed","Enable User Failed!");
        }
    }
    public function user_plt(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $PLT = DB::table("user_login")->where([
                ["user_login.department",$_SESSION['department']],
                ["user_login.section",$_SESSION['section']],
                ["user_login.username","!=",hex2bin($username)]
            ])
            ->leftjoin("department","department.id_dept","user_login.department")
            ->leftjoin("section","section.id_sect","user_login.section")
            ->select("user_login.*","department.*","section.*")
            ->groupBy("username")
            ->paginate(10);
        //dd($PLT);
        return view('page.plt',["getUser"=>$this->user,"PLT"=>$PLT]);
    }
    public function login_v1(Request $request)
    {

      $cek = DB::table('user_login')->whereRaw("(username='".$request->username."' or nik='".$request->username."') and password='".md5($request->password)."'")->first();
     if($cek==null){
        return redirect()->back()->with("failed","Username Or Password Wrong!");
     }else{
        if($cek->status==1){           
        return redirect()->back()->with("disable","Username Disabled!"); 
        }else{
$_SESSION['username'] = $cek->username;
$_SESSION['department'] = $cek->department;
$_SESSION['section'] = $cek->section;
$_SESSION['jabatan'] = $cek->section;
$_SESSION['level'] = $cek->level;
$_SESSION['UserRule'] = $cek->rule;
$_SESSION['perusahaan'] = $cek->perusahaan;
//event(new onlineUserEvent("USER LOGIN FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
$ip = $this->IP;

$user_log = DB::table('user_log')->insert([
"username"      =>  $_SESSION['username'],
"ip"            =>  $ip,
"time_log"      =>  date("Y-m-d H:i:s"),
"description"   => $_SESSION['username']." (".$ip.") Logged In ",
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
]);
return redirect("/inventory/update/data");
        }
     }
    }
    public function userdel(Request $request,$id_user)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['level']=="administrator"){
        $del = DB::table("user_login")->where("id_user",hex2bin($id_user))->delete();
            if($del){
                return redirect()->back()->with("success","User Deleted!");
            }else{
                return redirect()->back()->with("failed","Delete User Failed!");
            }
        }else{
            return redirect()->back()->with("failed","Page Not Found!");
        }
    }
    public function email_form(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $email = DB::table("user_login")->where("username",hex2bin($username))->first();
        $suggestMail = DB::table("db_karyawan.data_email")->get();
        return view("page.admin.email",["getUser"=>$this->user,"email"=>$email,"sMail"=>$suggestMail]);
    }
    public function email_post(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $email = DB::table("user_login")
                    ->where("username",hex2bin($username))
                    ->update([
                        "email"=>$request->email
                    ]);
        if($email>=0)
        {
                return redirect()->back()->with("success","Email Updated!");

        }else{
                return redirect()->back()->with("failed","Email Update Failed!");
        }
    }


    public function nik_form(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $email = DB::table("user_login")->where("username",hex2bin($username))->first();
        $karyawan = DB::table("db_karyawan.data_karyawan")->get();
        return view("page.admin.nik",["getUser"=>$this->user,"email"=>$email,"karyawan"=>$karyawan]);
    }
    public function nik_post(Request $request,$username)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $z=false;
        $k = DB::table("db_karyawan.data_karyawan")->where("nik",$request->nik)->first();
        if($k->nik==$request->nik){
        $email = DB::table("user_login")
                    ->where("username",hex2bin($username))
                    ->update([
                        "nama_lengkap"=>ucwords($k->nama),
                        "nik"=>$k->nik
                    ]);
                    $z=true;
        }else{
            $z=false;
        }
        if($z)
        {
                return redirect()->back()->with("success","Nik Updated!");

        }else{
                return redirect()->back()->with("failed","Nik Update Failed!");
        }
    }

    public function CookieLogin(Request $request)
    {
        if(isset($_COOKIE['username'])){
      $cek = DB::table('user_login')->where([
            ["username",$_COOKIE['username']],
            ["password",($_COOKIE['password'])]
            ])->first();
     if($cek==null){
        return redirect()->back()->with("failed","Username Or Password Wrong!");
     }else{
        if($cek->status==1){           
        return redirect()->back()->with("disable","Username Disabled!"); 
        }else{
$_SESSION['username'] = $cek->username;
$_SESSION['department'] = $cek->department;
$_SESSION['section'] = $cek->section;
$_SESSION['jabatan'] = $cek->section;
$_SESSION['level'] = $cek->level;
$_SESSION['UserRule'] = $cek->rule;
//event(new onlineUserEvent("USER LOGIN FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
$ip = $this->IP;

$user_log = DB::table('user_log')->insert([
"username"      =>  $_SESSION['username'],
"ip"            =>  $ip,
"time_log"      =>  date("Y-m-d H:i:s"),
"description"   => $_SESSION['username']." (".$ip.") Logged In ",
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
]);
return redirect()->back()->with("success","Login Success!");
        }
     }  
 }
    }
}
