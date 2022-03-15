<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class AndroidController extends Controller
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
    public function cancel_rkb_post(Request $request)
    {
    	if(isset($request->no_rkb)){
	    	$no_rkb=$request->no_rkb;
	    	$username = $request->username;
	    	$section = $request->section;
	    	$remarks = $request->remarks;

	            $up = DB::table('e_rkb_approve')
	                ->where([
	                    ['no_rkb',$request->no_rkb]
	                ])
	                ->update([
	                  "cancel_user" => $username,
	                  "cancel_section" => $section,
	                  "tgl_cancel_user" =>date("Y-m-d H:i:s"),
	                  "remark_cancel" =>$request->remarks
	                 ]);	
	         if($up>=0){
	         	return array("success"=>true);
	         }else{
	         	return array("success"=>false);
	         }
    	}else{
	         	return array("success"=>false);
    	}
    }

    public function getSarprasUser(Request $request)
    {
      if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
      $K_M1 = DB::table("vihicle.v_out_h")
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_out_h.nik")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","db_karyawan.data_karyawan.nama as userPemohon","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->where("vihicle.v_out_h.nik",$request->nik);
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            //$filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
          $filter= $K_M1;
        }
             $K_M=$filter->paginate(10);

        return $K_M;
    }
    public function getSarprasAll(Request $request)
    {
      if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
      $K_M1 = DB::table("vihicle.v_out_h")
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_out_h.nik")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","db_karyawan.data_karyawan.nama as userPemohon","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc");
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            //$filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
          $filter= $K_M1;
        }
             $K_M=$filter->paginate(10);

        return $K_M;
    }

    public function getSarprasUserDetail(Request $request)
    {

      if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
      $K_M1 = DB::table("vihicle.v_out_h")
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_out_h.nik")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","db_karyawan.data_karyawan.nama as userPemohon","vihicle.v_approve.*","vihicle.v_in.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->where("vihicle.v_out_h.noid_out",$request->noid_out);
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            //$filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
          $filter= $K_M1;
        }
             $K_M=$filter->first();
      
        echo json_encode($K_M);
    }

    public function getListSarana(Request $request)
    {
      $sarana = DB::table("vihicle.v_unit_d")->where("flag",0)->orderBy("no_lv")->get();
      $karyawan = DB::table("db_karyawan.data_karyawan")
                  ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                  ->where("flag",0)
                  ->orderBy("nik")
                  ->get();
      $awalBulan = date("01 F Y");
      $akhirBulan = date("t F Y");
      // die();
      return array("data"=>$sarana,"karyawan"=> $karyawan,"awalBulan"=>$awalBulan,"akhirBulan"=>$akhirBulan);
    }
    public function getListKaryawan(Request $request)
    {
      $karyawan = DB::table("db_karyawan.data_karyawan")
                  ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                  ->where("flag",0)
                  ->orderBy("nik")
                  ->get();
      return array("karyawan"=>$karyawan);
    }
}
