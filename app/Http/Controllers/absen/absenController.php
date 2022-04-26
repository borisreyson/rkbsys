<?php

namespace App\Http\Controllers\absen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class absenController extends Controller
{
    private $user;
    public function __construct()
    {
        session_start();
        $id_session = session_id();
        $IP="";
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $this->IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $this->IP = $_SERVER['REMOTE_ADDR'];
        }
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
    }
    public function tgl_indo($tanggal)
          {
              $bulan = array (1 =>   'Januari',
                  'Februari',
                  'Maret',
                  'April',
                  'Mei',
                  'Juni',
                  'Juli',
                  'Agustus',
                  'September',
                  'Oktober',
                  'November',
                  'Desember'
                );
            $split = explode('-', $tanggal);
            return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
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
    public function absenUserHGE(Request $requset)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $nik="";
        $status="";
        if(isset($requset->status)){
          if($requset->status!="all"){
            $status=$requset->status;
          }else{
            $status="";
          }
        }
        if(isset($requset->dari)){
          $dari = date("Y-m-d",strtotime($requset->dari));
        }else{
          $dari = date("Y-m-01");
        }
        if(isset($requset->sampai)){
          $sampai = date("Y-m-d",strtotime($requset->sampai));
        }else{
          $sampai = date("Y-m-t");
        }
        if(isset($requset->nik)){
          $nik= $requset->nik;
        }
        $hge = DB::table('absensi.ceklog')
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                ->where([
                         ["db_karyawan.data_karyawan.departemen","hrga"],
                         ["db_karyawan.data_karyawan.nik",$nik]
                       ]);
                if($status!=""){
                  $filter = $hge->where("absensi.ceklog.status",$status);
                }else{
                  $filter= $hge;
                }
                $data = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                ->orderBy("absensi.ceklog.tanggal",'asc')
                ->paginate(10);
        $db_karyawanHGE = DB::table("db_karyawan.data_karyawan")->where("db_karyawan.data_karyawan.departemen","hrga")->get();
        return view('absen.absen',["dataAbsen"=>$data,"getUser"=>$this->user,"kar_HGE"=>$db_karyawanHGE,"dept"=>"hrga"]);

    }
    public function absenUserHSE(Request $requset)
    {
if(!isset($_SESSION['username'])) return redirect('/');
        $nik="";
        $status="";
        if(isset($requset->status)){
          if($requset->status!="all"){
            $status=$requset->status;
          }else{
            $status="";
          }
        }
        if(isset($requset->dari)){
          $dari = date("Y-m-d",strtotime($requset->dari));
        }else{
          $dari = date("Y-m-01");
        }
        if(isset($requset->sampai)){
          $sampai = date("Y-m-d",strtotime($requset->sampai));
        }else{
          $sampai = date("Y-m-t");
        }
        if(isset($requset->nik)){
          $nik= $requset->nik;
        }
        $hge = DB::table('absensi.ceklog')
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                ->where([
                         ["db_karyawan.data_karyawan.departemen","hse"],
                         ["db_karyawan.data_karyawan.nik",$nik]
                       ]);
                if($status!=""){
                  $filter = $hge->where("absensi.ceklog.status",$status);
                }else{
                  $filter= $hge;
                }
                $data = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                ->orderBy("absensi.ceklog.tanggal",'asc')
                ->paginate(10);
        $db_karyawanHGE = DB::table("db_karyawan.data_karyawan")->where("db_karyawan.data_karyawan.departemen","hse")->get();
        return view('absen.absen',["dataAbsen"=>$data,"getUser"=>$this->user,"kar_HGE"=>$db_karyawanHGE,"dept"=>"hse"]);
    }
    public function absenUserENP(Request $requset)
    {
    if(!isset($_SESSION['username'])) return redirect('/');
            $nik="";
            $status="";
            if(isset($requset->status)){
              if($requset->status!="all"){
                $status=$requset->status;
              }else{
                $status="";
              }
            }
            if(isset($requset->dari)){
              $dari = date("Y-m-d",strtotime($requset->dari));
            }else{
              $dari = date("Y-m-01");
            }
            if(isset($requset->sampai)){
              $sampai = date("Y-m-d",strtotime($requset->sampai));
            }else{
              $sampai = date("Y-m-t");
            }
            if(isset($requset->nik)){
              $nik= $requset->nik;
            }
            $hge = DB::table('absensi.ceklog')
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                    ->where([
                             ["db_karyawan.data_karyawan.departemen","enp"],
                             ["db_karyawan.data_karyawan.nik",$nik]
                           ]);
                    if($status!=""){
                      $filter = $hge->where("absensi.ceklog.status",$status);
                    }else{
                      $filter= $hge;
                    }
                    $data = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                    ->orderBy("absensi.ceklog.tanggal",'asc')
                    ->paginate(10);
            $db_karyawanHGE = DB::table("db_karyawan.data_karyawan")->where("db_karyawan.data_karyawan.departemen","enp")->get();
            return view('absen.absen',["dataAbsen"=>$data,"getUser"=>$this->user,"kar_HGE"=>$db_karyawanHGE,"dept"=>"enp"]);
    }

    public function absenUserMANAGEMENT(Request $requset)
    {
    if(!isset($_SESSION['username'])) return redirect('/');
            $nik="";
            $status="";
            if(isset($requset->status)){
              if($requset->status!="all"){
                $status=$requset->status;
              }else{
                $status="";
              }
            }
            if(isset($requset->dari)){
              $dari = date("Y-m-d",strtotime($requset->dari));
            }else{
              $dari = date("Y-m-01");
            }
            if(isset($requset->sampai)){
              $sampai = date("Y-m-d",strtotime($requset->sampai));
            }else{
              $sampai = date("Y-m-t");
            }
            if(isset($requset->nik)){
              $nik= $requset->nik;
            }
            $hge = DB::table('absensi.ceklog')
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                    ->where([
                             ["db_karyawan.data_karyawan.departemen","Management"],
                             ["db_karyawan.data_karyawan.nik",$nik]
                           ]);
                    if($status!=""){
                      $filter = $hge->where("absensi.ceklog.status",$status);
                    }else{
                      $filter= $hge;
                    }
                    $data = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                    ->orderBy("absensi.ceklog.tanggal",'asc')
                    ->paginate(10);
            $db_karyawanHGE = DB::table("db_karyawan.data_karyawan")->where("db_karyawan.data_karyawan.departemen","Management")->get();
            return view('absen.absen',["dataAbsen"=>$data,"getUser"=>$this->user,"kar_HGE"=>$db_karyawanHGE,"dept"=>"Management"]);
    }


    public function absenUserMTK(Request $requset)
    {
    if(!isset($_SESSION['username'])) return redirect('/');
            $nik="";
            $status="";
            if(isset($requset->status)){
              if($requset->status!="all"){
                $status=$requset->status;
              }else{
                $status="";
              }
            }
            if(isset($requset->dari)){
              $dari = date("Y-m-d",strtotime($requset->dari));
            }else{
              $dari = date("Y-m-01");
            }
            if(isset($requset->sampai)){
              $sampai = date("Y-m-d",strtotime($requset->sampai));
            }else{
              $sampai = date("Y-m-t");
            }
            if(isset($requset->nik)){
              $nik= $requset->nik;
            }
            $hge = DB::table('absensi.ceklog')
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                    ->where([
                             ["db_karyawan.data_karyawan.departemen","MTK"],
                             ["db_karyawan.data_karyawan.nik",$nik]
                           ]);
                    if($status!=""){
                      $filter = $hge->where("absensi.ceklog.status",$status);
                    }else{
                      $filter= $hge;
                    }
                    $data = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                    ->orderBy("absensi.ceklog.tanggal",'asc')
                    ->paginate(10);
            $db_karyawanHGE = DB::table("db_karyawan.data_karyawan")->where("db_karyawan.data_karyawan.departemen","MTK")->get();
            return view('absen.absen',["dataAbsen"=>$data,"getUser"=>$this->user,"kar_HGE"=>$db_karyawanHGE,"dept"=>"MTK"]);
    }
    public function absenError(Request $request)
    {
    if(!isset($_SESSION['username'])) return redirect('/');
            $nik="";
            $status="";
            if(isset($request->status)){
              if($request->status!="all"){
                $status=$request->status;
              }else{
                $status="";
              }
            }
            if(isset($request->dari)){
              $dari = date("Y-m-d",strtotime($request->dari));
            }else{
              $dari = date("Y-m-01");
            }
            if(isset($request->sampai)){
              $sampai = date("Y-m-d",strtotime($request->sampai));
            }else{
              $sampai = date("Y-m-t");
            }
            if(isset($request->nik)){
              if($request->nik == "error"){
              $nik= "NIK";
              }else{
              $nik= $request->nik;
              }
            }
            $hge = DB::table('absensi.ceklog')
                    ->leftJoin("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                    ->where("absensi.ceklog.nik",$nik);
                    if($status!=""){
                      $filter = $hge->where("absensi.ceklog.status",$status);
                    }else{
                      $filter= $hge;
                    }
                    $data = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                    ->orderBy("absensi.ceklog.tanggal",'asc')
                    ->paginate(10);
                    // dd($data);
            $db_karyawanHGE = DB::table("db_karyawan.data_karyawan")->where("db_karyawan.data_karyawan.departemen","MTK")->get();
            return view('absen.error',["dataAbsen"=>$data,"getUser"=>$this->user,"kar_HGE"=>$db_karyawanHGE,"dept"=>"MTK"]);
    }

    public function absenKTTHGE(Request $requset)
    {

    }
    public function absenKTTHSE(Request $requset)
    {

    }
    public function absenKTTENP(Request $requset)
    {

    }
    public function absenKabagHGE(Request $requset)
    {

    }
    public function absenKabagHSE(Request $requset)
    {

    }
    public function absenKabagENP(Request $requset)
    {

    }
    public function rekapAbsen(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $start = strtotime(date("Y-m-d"));
        $end = strtotime(date("Y-m-d"));
        if(isset($_GET['dari'])){
          $start = strtotime(date("Y-m-d",strtotime($_GET['dari'])));
        }
        if(isset($_GET['sampai'])){
          $end = strtotime(date("Y-m-d",strtotime($_GET['sampai'])));
        }
        if(isset($request->jml_perhalaman)){
          $hal = (int) $request->jml_perhalaman;
        }else{
          $hal = 3;
        }
        if($_SESSION['department']=="mtk"){
          $dept = DB::table("department")->where("id_dept","mtk")->get();
        }else{
          $dept = DB::table("department")->get();
        }
        $dbKaryawan = DB::table("db_karyawan.data_karyawan")->where("flag",0);
        $vRoster = DB::table("absensi.view_roster");
        $vCeklog = DB::table("absensi.view_ceklog");
          if(isset($_GET['dept']))
          {
            if($_GET['dept']=="error"){
            $filter = $dbKaryawan;
            $viewFilter = $vRoster;
            $ceklogFilter = $vCeklog;
            }else{
            $filter = $dbKaryawan->where("db_karyawan.data_karyawan.departemen",$_GET['dept']);
            $viewFilter = $vRoster->where("departemen",$_GET['dept']);
            $ceklogFilter = $vCeklog->where("departemen",$_GET['dept']);
            }
          }else{
            $filter = $dbKaryawan;
            $viewFilter = $vRoster;
            $ceklogFilter = $vCeklog;
          }
          if(isset($_GET['search'])){
            $filter1 = $filter->whereRaw("db_karyawan.data_karyawan.nik like '%".$_GET['search']."%' or db_karyawan.data_karyawan.nama like '%".$_GET['search']."%'");
            $viewFilter1 = $viewFilter->whereRaw("nrp like '%".$_GET['search']."%' or nama like '%".$_GET['search']."%'");
            $ceklogFilter1 = $ceklogFilter->whereRaw("nik like '%".$_GET['search']."%' or nama like '%".$_GET['search']."%'");
          }else{
            $filter1 = $filter;
            $viewFilter1 = $viewFilter;
            $ceklogFilter1 = $ceklogFilter;
          }

          $data = $filter1->paginate($hal);
          $rosterTB = $viewFilter1->whereBetween("tanggal",[date("Y-m-d",$start),date("Y-m-d",$end)])->get();
          $ceklogTB = $ceklogFilter1->whereBetween("tanggal",[date("Y-m-d",$start),date("Y-m-d",$end)])->get();
          // dd($data);
            // dd($ceklogTB);

        return view('absen.rekap',["getUser"=>$this->user,
          "dbKaryawan"=>$data,
          "dept"=>$dept,
          "rosterTB"=>$rosterTB,
          "ceklogTB"=>$ceklogTB
        ]);

    }
    public function rekapAbsenNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $start = strtotime(date("Y-m-d"));
        $end = strtotime(date("Y-m-d"));
        if(isset($_GET['dari'])){
          $start = strtotime(date("Y-m-d",strtotime($_GET['dari'])));
        }
        if(isset($_GET['sampai'])){
          $end = strtotime(date("Y-m-d",strtotime($_GET['sampai'])));
        }
        if(isset($request->jml_perhalaman)){
          $hal = (int) $request->jml_perhalaman;
        }else{
          $hal = 3;
        }
        if($_SESSION['department']=="mtk"){
          $dept = DB::table("department")->where("id_dept","mtk")->get();
        }else{
          $dept = DB::table("department")->get();
        }
        $dbKaryawan = DB::table("db_karyawan.data_karyawan")->where("flag",0);
        $vRoster = DB::table("absensi.view_roster");
        $vCeklog = DB::table("absensi.view_ceklog");
          if(isset($_GET['dept']))
          {
            if($_GET['dept']=="error"){
            $filter = $dbKaryawan;
            $viewFilter = $vRoster;
            $ceklogFilter = $vCeklog;
            }else{
            $filter = $dbKaryawan->where("db_karyawan.data_karyawan.departemen",$_GET['dept']);
            $viewFilter = $vRoster->where("departemen",$_GET['dept']);
            $ceklogFilter = $vCeklog->where("departemen",$_GET['dept']);
            }
          }else{
            $filter = $dbKaryawan;
            $viewFilter = $vRoster;
            $ceklogFilter = $vCeklog;
          }
          if(isset($_GET['search'])){
            $filter1 = $filter->whereRaw("db_karyawan.data_karyawan.nik like '%".$_GET['search']."%' or db_karyawan.data_karyawan.nama like '%".$_GET['search']."%'");
            $viewFilter1 = $viewFilter->whereRaw("nrp like '%".$_GET['search']."%' or nama like '%".$_GET['search']."%'");
            $ceklogFilter1 = $ceklogFilter->whereRaw("nik like '%".$_GET['search']."%' or nama like '%".$_GET['search']."%'");
          }else{
            $filter1 = $filter;
            $viewFilter1 = $viewFilter;
            $ceklogFilter1 = $ceklogFilter;
          }

          $data = $filter1->paginate($hal);
          $rosterTB = $viewFilter1->whereBetween("tanggal",[date("Y-m-d",$start),date("Y-m-d",$end)])->get();
          $ceklogTB = $ceklogFilter1->whereBetween("tanggal",[date("Y-m-d",$start),date("Y-m-d",$end)])->orderBy("jam","desc")->get();
          // dd($data);
            // dd($ceklogTB);

        return view('absen.rekap_new',["getUser"=>$this->user,
          "dbKaryawan"=>$data,
          "dept"=>$dept,
          "rosterTB"=>$rosterTB,
          "ceklogTB"=>$ceklogTB
        ]);

    }
    public function exportRekap(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');

        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
$start = strtotime(date("Y-m-d"));
$end = strtotime(date("Y-m-d"));
if(isset($_GET['dari'])){
  $start = strtotime(date("Y-m-d",strtotime($_GET['dari'])));
}
if(isset($_GET['sampai'])){
  $end = strtotime(date("Y-m-d",strtotime($_GET['sampai'])));
}
        $dbKaryawan = DB::table("db_karyawan.data_karyawan")->where("flag",0);
        $viewRoster = DB::table("absensi.view_roster");
        $viewCeklog = DB::table("absensi.view_ceklog");
          if(isset($_GET['dept']))
          {
            $dept = DB::table("department")->where("id_dept",$_GET['dept'])->first();
            $filter = $dbKaryawan->where("db_karyawan.data_karyawan.departemen",$_GET['dept']);
            $filterRoster = $viewRoster->where("departemen",$_GET['dept']);
            $filterCeklog = $viewCeklog->where("departemen",$_GET['dept']);
          }else{
            $filter = $dbKaryawan;
            $filterRoster = $viewRoster;
            $filterCeklog = $viewCeklog;
          }
          $data = $filter->get();

          $rosterTB = $filterRoster->whereBetween("tanggal",[date("Y-m-d",$start),date("Y-m-d",$end)])->get();
          $masukTB = $filterCeklog->whereBetween("tanggal",[date("Y-m-d",$start),date("Y-m-d",$end)])->get();
          $newArr=[];
          $arrAbsen=[];
          $finger=array();
          $flag=array();
          $telat=array();;

$strNew=$start;
$stlEnd=$end;
$DayNew=$start;
$DayEnd=$end;
$j = [];
          foreach ($data as $key => $value) {
 ob_start();
$abc="C";
$sA=$start;
$sE=$end;
            array_push($newArr, $value->nama);
            array_push($newArr, $value->nik);
            array_push($arrAbsen, "Masuk");
            array_push($arrAbsen, "Pulang");

while($sA <= $sE)
{
   ob_start();
$masuk = $masukTB->where('nik',$value->nik)->where("status","Masuk")->where("tanggal",date("Y-m-d",$sA))->first();
  // $masuk = DB::table("absensi.ceklog")
  //               ->where([
  //                 ["tanggal",date("Y-m-d",$sA)],
  //                 ["nik",$value->nik],
  //                 ["status","Masuk"]])->first();
                  if($masuk!=null){
$roster = $rosterTB->where("nik",$masuk->nik)->where("tanggal",date("Y-m-d",$sA))->first();
// $roster = DB::table("absensi.view_roster")
//           ->where([
//             ["nik",$masuk->nik],
//             ["tanggal",date("Y-m-d",strtotime($masuk->tanggal))]
//           ])->first();
           // dd($roster);
if(isset($roster->masuk)){
  if(strtotime($masuk->jam)>strtotime($roster->masuk)){
$telat[$abc][] = 1;
  }else{
    if($roster->kode_jam=="OFF"){
      $telat[$abc][] = 2;
    }else{
      $telat[$abc][] = 0;
    }
  }
}else{
$telat[$abc][] = 0;
}
                    $finger[$abc][] = date("H:i",strtotime($masuk->jam));
                    $flag[$abc][] = $masuk->flag;
                  }else{
                    $finger[$abc][]="";
                    $flag[$abc][] = 0;
                    $telat[$abc][] = 0;
                  }
$pulang = $masukTB->where('nik',$value->nik)->where("status","Pulang")->where("tanggal",date("Y-m-d",$sA))->first();

  // $pulang = DB::table("absensi.ceklog")
  //              ->where([
  //                 ["tanggal",date("Y-m-d",$sA)],
  //                 ["nik",$value->nik],
  //                 ["status","Pulang"]])->first();
                  if($pulang!=null){
$roster = $rosterTB->where("nik",$pulang->nik)->where("tanggal",date("Y-m-d",$sA))->first();

// $roster = DB::table("absensi.view_roster")
//           ->where([
//             ["nik",$pulang->nik],
//             ["tanggal",date("Y-m-d",strtotime($pulang->tanggal))]
//           ])->first();
if(isset($roster->pulang)){

  if(strtotime($pulang->jam) < strtotime($roster->pulang)){

$telat[$abc][] = 1;

  }else{
    if($roster->kode_jam=="OFF"){
      $telat[$abc][] = 2;
    }else{
      $telat[$abc][] = 0;
    }
  }
}else{
$telat[$abc][] = 0;
}
                    $finger[$abc][]= date("H:i",strtotime($pulang->jam));
                    $flag[$abc][] = $pulang->flag;
                  }else{
                    $finger[$abc][]= "";
                    $flag[$abc][] = 0;
                    $telat[$abc][] = 0;
                  }

  $abc++;
  $sA = strtotime("+1 day",$sA);
  ob_end_flush();
        ob_flush();
        flush();
}
ob_end_flush();
        ob_flush();
        flush();
          }

            $filename = "export/Export-Rekap-Absen-".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle('A:ZH')->getAlignment()->setHorizontal('center');
            $sheet->getColumnDimension("A")->setAutoSize(true);
            $sheet->getColumnDimension("B")->setAutoSize(true);
            $sheet->mergeCells('A1:Z1');
            $sheet->mergeCells('A2:Z2');

            $sheet->setTitle("Export Rekap Absen");
            $sheet->setCellValue('A1', 'ABSENSI '.$dept->dept);
            $sheet->setCellValue('A2', "Periode ".date("d F Y",$start)." - ".date("d F Y",$end));
            $sheet->setCellValue('A4', 'Name');
            $sheet->setCellValue('B4', 'Finger');
$a="C";
while($DayNew <= $DayEnd)
{

  $sheet->setCellValue($a."4", date("d F Y",$DayNew));

  $DayNew = strtotime("+1 day",$DayNew);
  $a++;
}

$i=5;
//$abcd="C";
foreach($newArr as $k => $v){

$sNew = $strNew;
$eNew = $stlEnd;
$sheet->setCellValue('A'.$i, $v);
$sheet->setCellValue('B'.$i, $arrAbsen[$k]);
$i++;
}
foreach($finger as $kF => $vF)
{
  foreach($vF as $kk => $vv)
  {
  $sheet->setCellValue($kF.($kk+5), $vv);

    if($flag[$kF][$kk]==1){
      $sheet->getStyle($kF.($kk+5))->getFont()->getColor()->setARGB('FFFFFF');
      $sheet->getStyle($kF.($kk+5))->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F0160A');
    }else{
    if($telat[$kF][$kk]==1){
      $sheet->getStyle($kF.($kk+5))->getFont()->getColor()->setARGB('F0160A');
    }else if($telat[$kF][$kk]==2){
      $sheet->getStyle($kF.($kk+5))->getFont()->getColor()->setARGB('2509E6');
    }
    }


  $sheet->getColumnDimension($kF)->setAutoSize(true);
  // echo $telat[$kF][$kk]."<br>";
  }

}

  // die();

            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export Rekap Absen",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){
                return redirect($filename);
            }
    }
    public function absenUserHGEexport(Request $requset)
    {
    if(!isset($_SESSION['username'])) return redirect('/');
        $dept=$requset->dept;
        $nik="";
        $status="";
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }

        if(isset($requset->status)){
          if($requset->status!="all"){
            $status=$requset->status;
          }else{
            $status="";
          }
        }
        if(isset($requset->dari)){
          $dari = date("Y-m-d",strtotime($requset->dari));
        }else{
          $dari = date("Y-m-01");
        }
        if(isset($requset->sampai)){
          $sampai = date("Y-m-d",strtotime($requset->sampai));
        }else{
          $sampai = date("Y-m-t");
        }
        if(isset($requset->nik)){
          $nik= $requset->nik;
        }
        $hge = DB::table('absensi.ceklog')
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                ->where([
                         ["db_karyawan.data_karyawan.departemen",$dept],
                         ["db_karyawan.data_karyawan.nik",$nik]
                       ]);
                if($status!=""){
                  $filter = $hge->where("absensi.ceklog.status",$status);
                }else{
                  $filter= $hge;
                }
                $det = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                ->orderBy("absensi.ceklog.tanggal",'asc')
                ->get();
            $z=2;
            $filename = "export/Export-Absen-".$nik."-".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($nik);
            $sheet->setCellValue('A1', 'Tanggal');
            $sheet->setCellValue('B1', 'NIK');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Jam Absen');
            $sheet->setCellValue('E1', 'Status');
            $sheet->setCellValue('F1', 'Foto');
            foreach($det as $k => $v){
            $sheet->setCellValue('A'.$z, date("d F Y",strtotime($v->tanggal)));
            $sheet->setCellValue('B'.$z, $v->nik);
            $sheet->setCellValue('C'.$z, $v->nama);
            $sheet->setCellValue('D'.$z, date("H:i",strtotime($v->jam)));
            $sheet->setCellValue('E'.$z, $v->status);
            $sheet->setCellValue('F'.$z, $v->gambar);
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export Absen".$nik,
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }

    public function absenErrorexport(Request $requset)
    {
    if(!isset($_SESSION['username'])) return redirect('/');
        $dept=$requset->dept;
        $nik="";
        $status="";
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }

        if(isset($requset->status)){
          if($requset->status!="all"){
            $status=$requset->status;
          }else{
            $status="";
          }
        }
        if(isset($requset->dari)){
          $dari = date("Y-m-d",strtotime($requset->dari));
        }else{
          $dari = date("Y-m-01");
        }
        if(isset($requset->sampai)){
          $sampai = date("Y-m-d",strtotime($requset->sampai));
        }else{
          $sampai = date("Y-m-t");
        }
        if(isset($requset->nik)){
          if($requset->nik == "error"){
            $nik= "NIK";
          }else{
            $nik= $requset->nik;
          }
        }
        $hge = DB::table('absensi.ceklog')
                ->leftJoin("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik")
                ->where("absensi.ceklog.nik",$nik);
                if($status!=""){
                  $filter = $hge->where("absensi.ceklog.status",$status);
                }else{
                  $filter= $hge;
                }
                $det = $filter->whereBetween("absensi.ceklog.tanggal",[$dari,$sampai])
                ->orderBy("absensi.ceklog.tanggal",'asc')
                ->get();
            $z=2;
            $filename = "export/Export-Absen-".$nik."-".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($nik);
            $sheet->setCellValue('A1', 'Tanggal');
            $sheet->setCellValue('B1', 'NIK');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Jam Absen');
            $sheet->setCellValue('E1', 'Status');
            $sheet->setCellValue('F1', 'Foto');
            foreach($det as $k => $v){
            $sheet->setCellValue('A'.$z, date("d F Y",strtotime($v->tanggal)));
            $sheet->setCellValue('B'.$z, $v->nik);
            $sheet->setCellValue('C'.$z, $v->nama);
            $sheet->setCellValue('D'.$z, date("H:i",strtotime($v->jam)));
            $sheet->setCellValue('E'.$z, $v->status);
            $sheet->setCellValue('F'.$z, $v->gambar);
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export Absen".$nik,
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }

    public function kodeJamRoster(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
              $kodeJam = DB::table('db_karyawan.kode_jam_masuk')
                      ->paginate(10);
              $jamkerja = DB::table('db_karyawan.jam_kerja')
                      ->get();
              return view('absen.kode_jam',["kodeJam"=>$kodeJam,"jamkerja"=>$jamkerja,"getUser"=>$this->user]);
    }
    public function kodeJamRosterPut(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
        $file =$_FILES['fileExcel']['name'];
        $tmp_file =$_FILES['fileExcel']['tmp_name'];
        $inputFileType = ucwords(pathinfo($file, PATHINFO_EXTENSION));
        $target = basename($file);
        move_uploaded_file($tmp_file, $target);
        chmod($file,0777);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet =$reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        //dd($sheetData);
        foreach($sheetData as $k => $v){
                    if($k>1){
                    $no = $sheetData[$k]['A'];
                    $masuk=$sheetData[$k]['B'];
                    $pulang=$sheetData[$k]['C'];
                        $cek=DB::table("db_karyawan.jam_kerja")->where("no",$no)->count();
                        if($cek==0){
                        $in = DB::table("db_karyawan.jam_kerja")->insert([
                                                            "no"=>$no,
                                                            "masuk"=>$masuk,
                                                            "pulang"=>$pulang,
                                                            ]);
                        }else{
                                $update = DB::table("db_karyawan.jam_kerja")
                                                    ->where("no",$no)
                                                    ->update([
                                                            "masuk"=>$masuk,
                                                            "pulang"=>$pulang,
                                                            ]);
                        }

            }

        }
        unlink($file);
        return redirect()->back()->with("success","Data Telah Di Proses!");
    }
    public function rosterKerja(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
            $sub_bagian = DB::table("db_karyawan.sub_bagian")
                          ->join("department","department.id_dept","db_karyawan.sub_bagian.id_dept")
                          ->groupBy("db_karyawan.sub_bagian.id_dept")
                          ->get();
            $jamKerja = DB::table("db_karyawan.kode_jam_masuk")
                        ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                        ->get();
            $karyawan = DB::table("db_karyawan.data_karyawan")
                        ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")->get();
              return view('absen.roster',["sub_bagian"=>$sub_bagian,"karyawan"=>$karyawan,"getUser"=>$this->user,"jamKerja"=>$jamKerja]);
    }

    public function rosterKerjaLihat(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
            $sub_bagian = DB::table("db_karyawan.sub_bagian")
                          ->join("department","department.id_dept","db_karyawan.sub_bagian.id_dept")
                          ->groupBy("db_karyawan.sub_bagian.id_dept")
                          ->get();
            $kar = DB::table("db_karyawan.data_karyawan")
                        ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")->get();
            $jamKerja = DB::table("db_karyawan.kode_jam_masuk")
                        ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                        ->get();

              return view('absen.lihat_roster',["sub_bagian"=>$sub_bagian,"jamKerja"=>$jamKerja,"getUser"=>$this->user,"kar"=>$kar]);
    }

    public function kodeJamRosterPost(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
              $kodeJam = DB::table('db_karyawan.kode_jam_masuk')
                      ->insert([
                        "kode_jam"=>$request->kode_jam,
                        "deskripsi"=>$request->deskripsi,
                        "id_jam_kerja"=>$request->id_jam,
                        "background"=>$request->warna,
                        "tulisan"=>$request->tulisan,
                        "user_entry"=>$_SESSION['username'],
                        "create_at"=>date("Y-m-d H:i:s"),
                      ]);
              if($kodeJam){
                return redirect()->back()->with("success","Kode Jam Roster Telah Di Input");
              }else{
                return redirect()->back()->with("failed","Kode Jam Roster Gagal Di Input");
              }
    }
    public function newSub(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
      return view('absen.sub',["getUser"=>$this->user]);
    }
    public function newSubPost(Request $request)
    {
      if(!isset($_SESSION['username'])) return redirect('/');
      $cek = DB::table('db_karyawan.sub_bagian')->where("bagian",$request->sub_bagian)->count();
      if($cek<1){
        $save = DB::table('db_karyawan.sub_bagian')
              ->insert([
                "bagian"=>$request->sub_bagian,
                "id_dept"=>$request->dept,
                "user_entry"=>$_SESSION['username'],
                "tgl_entry"=>$request->tgl_entry
              ]);
            if($save){
              return redirect()->back()->with("success","Success!");
            }else{
              return redirect()->back()->with("failed","Failed!");
            }
          }else{
              return redirect()->back()->with("failed","Sub Bagian Sudah Ada!");
          }

    }
    public function postRoster(Request $request)
    {
      $sub_bagian = $request->id_sub;
      $tahun = $request->tahun;
      $bulan = $request->bulan;
      $nik = $request->nik;
      $jam_kerja = $request->jam_kerja;
      $tgl = $request->tgl;

      foreach($nik as $k => $v){
        foreach($jam_kerja[$k] as $kk => $vv){
          // echo $v." : ".$vv."<br>";
          $roster = DB::table("db_karyawan.roster_kerja")
                    ->insert([
                      "sub_bagian"=>$sub_bagian,
                      "nik" =>$v,
                      "tahun"=>$tahun,
                      "bulan"=>$bulan,
                      "tanggal"=>date("Y-m-d",$tgl[$k][$kk]),
                      "jam_kerja"=>$vv,
                      "user_entry"=>$_SESSION['username'],
                      "time_input"=>date("Y-m-d H:i:s")
                    ]);
        }
      }
      return redirect()->back()->with("success","Update!");

    }

    public function updateRoster(Request $request)
    {
      $sub_bagian = $request->id_sub;
      $tahun = $request->tahun;
      $bulan = $request->bulan;
      $nik = $request->nik;
      $jam_kerja = $request->jam_kerja;
      $tgl = $request->tgl;
      foreach($nik as $k => $v){
        foreach($jam_kerja[$k] as $kk => $vv){
          // echo $v." : ".$vv."<br>";
          $roster = DB::table("db_karyawan.roster_kerja")
                    ->where([
                      ["nik",$v],
                      ["tanggal",date("Y-m-d",$tgl[$k][$kk])]
                    ])
                    ->update([
                      "sub_bagian"=>$sub_bagian,
                      "tahun"=>$tahun,
                      "bulan"=>$bulan,
                      "jam_kerja"=>$vv,
                      "user_entry"=>$_SESSION['username'],
                      "time_input"=>date("Y-m-d H:i:s")
                    ]);
        }
      }
      return redirect('/absen/roster/karyawan/lihat?id_sub='.$sub_bagian.'&tahun='.$tahun.'&bulan='.$bulan)->with("success","Update!");
    }


    public function lastAbsen(Request $request)
    {
        $last = DB::table('absensi.ceklog')
                ->where([
                  ["nik",$request->nik],
                  ["tanggal",date("Y-m-d")]
                ])
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal","desc")
                ->first();
        $lastNew = DB::table('absensi.ceklog')
                ->where("nik",$request->nik)
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->first();
        $presensiMasuk = DB::table('absensi.ceklog')
                ->where([["nik",$request->nik],["status","Masuk"],
                  ["tanggal",date("Y-m-d")]])
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->first();

        $presensiPulang = DB::table('absensi.ceklog')
                ->where([["nik",$request->nik],["status","Pulang"],
                  ["tanggal",date("Y-m-d")]])
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->first();
        $roster = DB::table('db_karyawan.roster_kerja')
                ->join("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
                ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                ->where([
                  ["nik",$request->nik],
                  ["tanggal",date("Y-m-d",strtotime($lastNew->tanggal))]
                ])
                ->first();
        if(isset($last)){

          if(isset($lastNew)){
            if(isset($roster)){
              return array("lastAbsen"=>$last->status,"lastNew"=>$lastNew->status,"tanggal"=>$lastNew->tanggal,"masuk"=>$roster->masuk,"pulang"=>$roster->pulang,"presensiMasuk"=>$presensiMasuk,"presensiPulang"=>$presensiPulang);
            }else{
              return array("lastAbsen"=>$last->status,"lastNew"=>$lastNew->status,"masuk"=>null,"pulang"=>null);
            }
          }else{
              return array("lastAbsen"=>$last->status,"lastNew"=>null,"masuk"=>null,"pulang"=>null,"presensiMasuk"=>null,"presensiPulang"=>null);
          }
        }else{
          if(isset($lastNew)){
            if(isset($roster)){
              $masuk = strtotime($roster->masuk);
              $pulang = strtotime($roster->pulang);
              if($pulang<$masuk){
                $masukNew = date("Y-m-d H:i:s",$masuk);
                $pulangNew = date("Y-m-d H:i:s",strtotime("+1 day ",$pulang));
              return array("lastAbsen"=>null,"lastNew"=>$lastNew->status,"masuk"=>date("H:i:s",$masuk),"pulang"=>$pulangNew,"presensiMasuk"=>$presensiMasuk,"presensiPulang"=>$presensiPulang);

                // echo "pulang lebih kecil";
              }else{
                $pulangNew = date("Y-m-d H:i:s",$pulang);
                return array("lastAbsen"=>null,"lastNew"=>$lastNew->status,"masuk"=>date("H:i:s",$masuk),"pulang"=>$pulangNew,"presensiMasuk"=>$presensiMasuk,"presensiPulang"=>$presensiPulang);
                // echo "pulang lebih besar";
              }
            }else{
              if(isset($lastNew->status)){
                return array("lastAbsen"=>null,"lastNew"=>$lastNew->status,"masuk"=>null,"pulang"=>null,"presensiMasuk"=>$presensiMasuk,"presensiPulang"=>$presensiPulang);
              }else{
                return array("lastAbsen"=>null,"lastNew"=>null,"masuk"=>null,"pulang"=>null,"presensiMasuk"=>$presensiMasuk,"presensiPulang"=>$presensiPulang);
              }

            }

        }else{
          return array("lastAbsen"=>null,"lastNew"=>null,"masuk"=>null,"pulang"=>null,"presensiMasuk"=>null,"presensiPulang"=>null);
        }

        }
    }
    public function AbsenTigaHari(Request $request)
    {
      $url = url('/face_id');
      $dari = date("Y-m-d",strtotime("-3 Day"));
      $sampai = date("Y-m-d");
      $last3 = DB::table('absensi.ceklog')
                ->where("nik",$request->nik)
                ->whereBetween("tanggal",[$dari,$sampai])
                ->select("absensi.ceklog.*",DB::raw("CONCAT('".$url."/',absensi.ceklog.nik,'/',gambar) as gambar"),DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->get();
                return ["AbsenTigaHari"=>$last3];
    }
    public function exportJamkerja(Request $request)
    {
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
      $kodeJam = DB::table('db_karyawan.kode_jam_masuk')
                  ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                      ->get();
            $z=2;
            $filename = "export/jamkerja".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("jam Kerja");
            $sheet->setCellValue('A1', 'Id Kode');
            $sheet->setCellValue('B1', 'Kode');
            $sheet->setCellValue('C1', 'Masuk');
            $sheet->setCellValue('D1', 'Pulang');
            foreach($kodeJam as $k => $v){
            $sheet->setCellValue('A'.$z, $v->id_kode);
            $sheet->setCellValue('B'.$z, $v->kode_jam);
            $sheet->setCellValue('C'.$z, $v->masuk);
            $sheet->setCellValue('D'.$z, $v->pulang);
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export Absen",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }
    public function importRoster(Request $request)
    {
        // dd($request);
        $jam = $request->jam;
        $file =$_FILES['fileRoster']['name'];
        $tmp_file =$_FILES['fileRoster']['tmp_name'];
        $inputFileType = ucwords(pathinfo($file, PATHINFO_EXTENSION));
        $target = basename($file);
        move_uploaded_file($tmp_file, $target);
        chmod($file,0777);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet =$reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $nik;
        $rs;
        $tgl;
         foreach($sheetData as $k => $v){
            foreach ($v as $key => $value) {
              if($k<=1){
                if($key!="A" && $key!="B"){
                  $tgl[]=$value;
                }
              }else{
                if($key=="A"){
                  $nik = $value;
                }else if($key!="A" && $key!="B"){
                  $rs[$nik][]= $value;
                }
              }
            }
         }

         foreach ($rs as $key => $value) {
          foreach($value as $k => $v){
            $jamnya = DB::table('db_karyawan.kode_jam_masuk')
                  ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                  ->whereIn("id_kode",$jam)
                  ->get();
            foreach($jamnya as $j => $jV){
              if($jV->kode_jam==$v){
                if($request->bulan==date("m",strtotime($tgl[$k])))
{                $import = DB::table("db_karyawan.roster_kerja")
                          ->insert([
                            "nik"=>$key,
                            "sub_bagian"=>$request->id_sub,
                            "tahun"=>$request->tahun,
                            "bulan"=>$request->bulan,
                            "tanggal"=>date("Y-m-d",strtotime($tgl[$k])),
                            "jam_kerja"=>$jV->id_kode,
                            "user_entry"=>$_SESSION['username'],
                            "time_input"=>date("Y-m-d H:i:s")
                          ]);
                        }else{
return redirect()->back()->with("failed","Periksa Bulan di File Anda");
                        }
                // echo date("Y-m-d",strtotime($tgl[$k]))."<br>";
              }

            }
          }
         }
        return redirect('/absen/roster/karyawan/lihat?id_sub='.$request->id_sub.'&tahun='.$request->tahun.'&bulan='.$request->bulan)->with("success","Update!");
    }
    public function updateJamKerja(Request $request)
    {
      $update = DB::table("db_karyawan.kode_jam_masuk")
                ->where("id_kode",$request->id_kode)
                ->update([
                  "kode_jam"=>$request->kode_jam,
                  "deskripsi"=>$request->deskripsi,
                  "background"=>$request->warna,
                  "tulisan"=>$request->tulisan,
                  "id_jam_kerja"=>$request->id_jam,
                  "update_at"=>date("Y-m-d H:i:s"),
                  "user_entry"=>$_SESSION['username']
                ]);
      if($update>=0){
        return redirect()->back()->with("success","Update!");
      }else{
        return redirect()->back()->with("failed","Update Error!");
      }
    }
    public function validasiAbsen(Request $request)
    {
      $nik=$request->nik;
      $tgl=$request->tgl;
      $status=$request->status;
      $absensi = DB::table("absensi.ceklog")
                ->where([
                  ["nik",$nik],
                  ["tanggal",date("Y-m-d",$tgl)],
                  ["status",$status]
                ])
                ->update([
                  "flag"=>"1"
                ]);
      if($absensi>=0){
        echo "berhasil";
      }else{
        echo "gagal";
      }
    }
    public function faceidToken(Request $request)
    {
      $absensi = DB::table("keamanan.user_android")
      ->where([
        ["nik",$request->nik],
        ['app',$request->app]
      ])
      ->orderBy("id","desc")
      ->first();
      // if($absensi!=null){
      echo json_encode($absensi);
    }
    function hari_ini(){
      $hari = date ("D");

      switch($hari){
        case 'Sun':
          $hari_ini = "Minggu";
        break;

        case 'Mon':
          $hari_ini = "Senin";
        break;

        case 'Tue':
          $hari_ini = "Selasa";
        break;

        case 'Wed':
          $hari_ini = "Rabu";
        break;

        case 'Thu':
          $hari_ini = "Kamis";
        break;

        case 'Fri':
          $hari_ini = "Jumat";
        break;

        case 'Sat':
          $hari_ini = "Sabtu";
        break;

        default:
          $hari_ini = "Tidak di ketahui";
        break;
      }

      return $hari_ini;
  }
    public function faceidTokenNew(Request $request)
    {
      $tglIndo = $this->tgl_indo(date("Y-m-d"));
      $hariIni = $this->hari_ini();
      $absensi = DB::table("keamanan.user_android")
      ->where([
        ["nik",$request->nik],
        ['app',$request->app],
        ["phone_token",$request->android_token]
      ])
      ->orderBy("id","desc")
      ->first();
      $roster = DB::table("db_karyawan.roster_kerja")
      ->leftJoin("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
      ->leftJoin("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
      ->select(
        "db_karyawan.roster_kerja.id_roster",
        "db_karyawan.roster_kerja.nik",
        "db_karyawan.roster_kerja.tanggal",
        "db_karyawan.kode_jam_masuk.kode_jam",
        "db_karyawan.jam_kerja.masuk",
        "db_karyawan.jam_kerja.pulang"
      )
      ->where([
        ["nik",$request->nik],
        ["tanggal",date("Y-m-d")]
      ])
      ->first();

      $presensi = DB::table("db_karyawan.roster_kerja")
      ->leftJoin("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
      ->leftJoin("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
      ->leftJoin("absensi.ceklog","absensi.ceklog.id_roster","db_karyawan.roster_kerja.id_roster")
      ->select(
        "db_karyawan.roster_kerja.id_roster",
        "db_karyawan.roster_kerja.nik",
        "db_karyawan.roster_kerja.tanggal",
        "db_karyawan.kode_jam_masuk.kode_jam",
        "db_karyawan.jam_kerja.masuk",
        "db_karyawan.jam_kerja.pulang",
        "absensi.ceklog.jam",
        "absensi.ceklog.status"
      )
      ->where([
        ["db_karyawan.roster_kerja.nik",$request->nik],
        ["db_karyawan.roster_kerja.tanggal",date("Y-m-d")]
      ])
      ->get();

      if($absensi!=null){
       if($absensi->phone_token==$request->android_token){
          return array("area"=>"abp","jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s"),"absensi"=>$absensi,"hari"=>$hariIni,"tanggal"=>$tglIndo,"roster"=>$roster,"presensi"=>$presensi);
       }else{
          $ck = DB::table("keamanan.user_android")->where([
            ["nik",$request->nik],
            ["app",$request->app],
          ])->update([
            "phone_token"=>$request->android_token
          ]);
          return array("area"=>"abp","jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s"),"absensi"=>$absensi,"hari"=>$hariIni,"tanggal"=>$tglIndo,"roster"=>$roster,"presensi"=>$presensi);
       }
     }else{
          return array("area"=>"abp","jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s"),"absensi"=>$absensi,"hari"=>$hariIni,"tanggal"=>$tglIndo,"roster"=>$roster,"presensi"=>$presensi);
     }

    }
    public function updatePhoneToken(Request $request)
    {
      $absensi = DB::table("keamanan.user_android")
      ->where([
        ["nik",$request->nik],
        ['app',$request->app],
        ['android_token',$request->android_token]
      ])
      ->orderBy("id","desc")
      ->first();
      if($absensi!=null){
        echo "OK";
      }else{
        echo "NOKE";
      }
    }
    public function listAllAbsen(Request $request)
    {
      $url = url('/face_id');
      $absensi = DB::table("absensi.ceklog")
      ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","absensi.ceklog.nik");
      if(isset($request->tanggal)){
        $tanggal = date("Y-m-d",strtotime($request->tanggal));
        if(isset($request->status)){
          $status = $request->status;
          $filter = $absensi->where([
            ["tanggal",$tanggal],
            ["status",$status]
          ]);
        }else{
          $filter = $absensi->where("tanggal",$tanggal);
        }
      }else{
        $tanggal = date("Y-m-d");
        $filter = $absensi->where("tanggal",$tanggal);
      }
      $row = $filter
       ->select("db_karyawan.data_karyawan.*","absensi.ceklog.*",DB::raw("CONCAT('".$url."/',absensi.ceklog.nik,'/',gambar) as gambar"))
      ->orderBy("absensi.ceklog.id","desc")->paginate(10);
      return ($row);
    }
    public function deleteRosterUser(Request $request)
    {
     $roster = DB::table("db_karyawan.roster_kerja")
                ->where([
                  ["nik",$request->nik],
                  ["sub_bagian",$request->id_sub],
                  ["tahun",$request->tahun],
                  ["bulan",$request->bulan],
                ])->delete();
      if($roster){
        return redirect()->back()->with("success","Delete Roster Success");
      }else{
        return redirect()->back()->with("failed","Delete Roster Failed");
      }
    }
    public function persentasiPengguna(Request $request)
    {
      $pengguna = DB::table("absensi.ceklog")
              ->groupBy("nik")
              ->get();
      $penggunaHari = DB::table("absensi.ceklog")
      ->where("tanggal",date("Y-m-d"))
              ->groupBy("nik")
              ->get();
      $totalKaryawan = DB::table("db_karyawan.data_karyawan")
                        ->where("flag",0)
                        ->count();
      $persentasi = ( count($pengguna)/ $totalKaryawan) * 100;
      return array("persentasi"=>$persentasi,"jumlah_karyawan"=>$totalKaryawan,"jumlah_pengguna"=>count($pengguna),"penggunaPerhari"=>count($penggunaHari));
    }
    public function rosterOnAndroud(Request $request)
    {
      # code...
    }

    public function formKaryawan(Request $request)
    {
      $dep = DB::table("department")->get();
      $company = DB::table("db_karyawan.perusahaan")->get();
      return view('absen.form_karyawan',["getUser"=>$this->user,"dep"=>$dep,"company"=>$company]);
    }
    public function postKaryawan(Request $request)
    {
      $nik = $request->nik;
      $cek = DB::table("db_karyawan.data_karyawan")->where("nik",$nik)->count();
      if($cek==0){
      $inKar = DB::table("db_karyawan.data_karyawan")
              ->insert([
                "nik"=>$nik,
                "nama"=>$request->nama,
                "departemen"=>$request->department,
                "devisi"=>$request->section,
                "jabatan"=>$request->jabatan,
                "user_entry"=>$_SESSION['username'],
                "tgl_entry"=>date("Y-m-d"),
                "password"=>md5("12345"),
                "perusahaan"=>$request->perusahaan,
                "show_absen"=>0
              ]);
      if($inKar){
        return redirect()->back()->with("success","Add New User Success");

      }else{
        return redirect()->back()->with("failed","Add New User Failed");

      }
      }else{
        return redirect()->back()->with("failed","Add New User Failed");
      }
    }
    public function editKaryawan(Request $request)
    {
      $nik = $request->nik;
      $dep = DB::table("department")->get();
      $company = DB::table("db_karyawan.perusahaan")->get();

      $getKar = DB::table("db_karyawan.data_karyawan as a")
                ->leftJoin("db_karyawan.perusahaan as b","b.id_perusahaan","a.perusahaan")
                ->where("a.nik",$nik)
                ->first();
      return view('absen.form_karyawan',["getUser"=>$this->user,"dep"=>$dep,"getKar"=>$getKar,"company"=>$company]);

    }
    public function putKaryawan(Request $request)
    {
      $upKar = DB::table("db_karyawan.data_karyawan")
              ->where("nik",$request->oldNik)
              ->update([
                "nik"=>$request->nik,
                "nama"=>$request->nama,
                "departemen"=>$request->department,
                "devisi"=>$request->section,
                "jabatan"=>$request->jabatan,
                "user_entry"=>$_SESSION['username'],
                "perusahaan"=>$request->perusahaan,
                "tgl_up"=>date("Y-m-d")
              ]);
        if($upKar>=0){
        return redirect()->back()->with("success","Update User Success");
        }else{
        return redirect()->back()->with("failed","Update User Failed");

        }
    }
    public function kirimMasukan(Request $request)
    {
      $nik = $request->nik;
      $nama = $request->nama;
      $masukan = $request->masukan;
      $masukanIn = DB::table("absensi.masukan")
                  ->insert([
                    "nik"=>$nik,
                    "nama"=>$nama,
                    "masukan"=>$masukan,
                    "tgl_entry"=>date("Y-m-d H:i:s")
                  ]);

      return array("success"=>true);
    }
    public function absenLog(Request $request)
    {
      $z=0;
      // $dir    = 'face_id/';
      // $files1 = scandir($dir);
      // dd($files1);

      if ($handle = opendir('face_id/')) {
          while (false !== ($file = readdir($handle)))
          {
              if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'jpg')
              {
                  $filenya[$z] = $file;
                  $thelist[$z] = explode("_", $file);
              }
              $z++;
          }
          closedir($handle);
          // dd($thelist,$filenya);
          foreach ($thelist as $key => $value) {
            # code...
            if(is_dir("face_id/".$value[0])){

              if(file_exists('face_id/'.$filenya[$key])){

                if(copy("face_id/".$filenya[$key] , "face_id/".$value[0]."/".$filenya[$key])){

                   echo "Copy ".$filenya[$key]." Success!! <br>";

                   if(unlink("face_id/".$filenya[$key])){

                      echo "Delete File ".$filenya[$key]." Sucess <br>";

                   }else{
                    echo "Delete File ".$filenya[$key]." Error <br>";
                   }
                }else{
                  echo "Error while copy ".$filenya[$key]." <br>";
                }
              }else{
                echo "File Not Found <br>";
              }
            }else{
              echo "Dir Not Found <br>";
              if(mkdir('face_id/'.$value[0].'/')){
                echo "Dir Created <br>";
                      if(file_exists('face_id/'.$filenya[$key])){

                      if(copy("face_id/".$filenya[$key] , "face_id/".$value[0]."/".$filenya[$key])){

                         echo "Copy ".$filenya[$key]." Success!! <br>";

                         if(unlink("face_id/".$filenya[$key])){

                            echo "Delete File ".$filenya[$key]." Sucess <br>";

                         }else{
                          echo "Delete File ".$filenya[$key]." Error <br>";
                         }
                      }else{
                        echo "Error while copy ".$filenya[$key]." <br>";
                      }
                    }else{
                      echo "File Not Found <br>";
                    }
              }else{
                echo "Dir Created Error <br>";

              }
            }
          }
      }
      // $absenLog = DB::table('absensi.ceklog')
      //   ->orderBy("tanggal","asc")
      //   ->groupBy("nik")
      //   ->get();
      //   foreach ($absenLog as $key => $value) {
      //     $absenByNik = DB::table('absensi.ceklog')
      //     ->where("nik",$value->nik)
      //     ->orderBy("nik","asc")
      //     ->get();
      //     foreach ($absenByNik as $k => $v) {
      //         // echo $value->nik." : ".$v->id."<br/>";
      //         $dipindah[$value->nik][$v->id] =$v->gambar;
      //     }
      //     // $dipindah[$value->nik][$value->id]=." : ".." | ".$value->gambar."<br>";
      //   }
      //   // dd($dipindah);
      //   foreach ($dipindah as $key => $vl) {
      //     if(is_dir("face_id/".$key."/")){
      //       foreach ($vl as $kN => $vN) {
      //         if(file_exists('face_id/'.$vN)){
      //           if(copy("face_id/".$vN , "face_id/".$key."/".$vN)){
      //             echo $vN." Dicopy <br/>";
      //             if(unlink("face_id/".$vN)){
      //             echo $vN." deleted <br/>";
      //             }else{
      //             echo $vN." Failed to delete <br/>";
      //             }
      //           }else{
      //             echo $vN." Failed 1<br/>";
      //           }
      //         }
      //         // else{
      //         //   if(copy('face_id/'.$vN, 'face_id/'.$key.'/'.$vN)){
      //         //     echo $vN." Dicopy <br/>";
      //         //       if(unlink('face_id/'.$vN)){
      //         //       echo $vN." deleted <br/>";

      //         //       }else{
      //         //       echo $vN." Failed to delete <br/>";

      //         //       }
      //         //   }else{
      //         //     echo $vN." Failed <br/>";
      //         //   }
      //         // }

      //       }
      //     }else{
      //       if(mkdir('face_id/'.$key.'/')){
      //         foreach ($vl as $kN => $vN) {
      //           if(copy('face_id/'.$vN, 'face_id/'.$key.'/'.$vN)){
      //             echo "Folder ".$key." Dibuat & ".$vN." Dicopy <br/>";
      //               if(unlink('face_id/'.$vN)){
      //               echo $vN." deleted <br/>";

      //               }else{
      //               echo $vN." Failed to delete <br/>";

      //               }
      //             }else{
      //             echo "Folder ".$key." Dibuat & ".$vN." Failed <br/>";
      //             }
      //         }
      //       }else{
      //         echo "Folder ".$key." Gagal dibuat <br/>";
      //       }
      //     }

      //     // if(is_dir("face_id/recognized/")){
      //     //     echo "face_id/recognized/ ada<br>";
      //     //    if(is_dir("face_id/recognized/".$key."/")){
      //     //     echo "face_id/recognized/".$key."/ ada<br>";
      //     //   // foreach ($vl as $kN => $vN) {
      //     //   //   if(copy('face_id/'.$vN, 'face_id/recognized/'.$key.'/'.$vN)){
      //     //   //   echo $vN." Dicopy <br/>";
      //     //   //   }else{
      //     //   //   echo $vN." Failed <br/>";
      //     //   //   }
      //     //   // }
      //     //    }else{
      //     //     if(mkdir('face_id/recognized/'.$key.'/')){
      //     //       echo 'face_id/recognized/'.$key.'/ telah dibuat<br>';

      //     //     }
      //     //    }
      //     // }else{
      //     //   if(mkdir('face_id/recognized/')){
      //     //       echo 'face_id/recognized/ telah dibuat<br>';
      //     //     if(mkdir('face_id/recognized/'.$key.'/')){
      //     //       echo 'face_id/recognized/'.$key.'/ telah dibuat<br>';
      //     //     }
      //     //     // foreach ($vl as $kN => $vN) {
      //     //     //   if(copy('face_id/'.$vN, 'face_id/recognized/'.$key.'/'.$vN)){
      //     //     //     echo "Folder recognized/".$key." Dibuat & ".$vN." Dicopy <br/>";
      //     //     //     }else{
      //     //     //     echo "Folder recognized/".$key." Dibuat & ".$vN." Failed <br/>";
      //     //     //     }
      //     //     // }
      //     //   }else{
      //     //     echo "Folder recognized/".$key." Gagal dibuat <br/>";
      //     //   }
      //     // }
      //     $z++;
      //   }
      //   echo $z;
    }
    public function checkFolder(Request $request)
    {
      if(isset($request->nik)){
        if(is_dir("face_id/recognized/".$request->nik."/")){
          $files = glob('face_id/recognized/'.$request->nik.'/*');
          if(count($files)==3){
            return (array("folder"=>true));
          }else{
            return (array("folder"=>false));
          }

        }else{
          if(mkdir('face_id/recognized/'.$request->nik.'/')){
              if(chmod("face_id/recognized/".$request->nik."/",0777)){
                 $files = glob('face_id/recognized/'.$request->nik.'/*');
                  if(count($files)==3){
                    return (array("folder"=>true));
                  }else{
                    return (array("folder"=>false));
                  }
              }else{
                return (array("folder"=>false));
              }
          }else{
            return (array("folder"=>false));
          }

        }

      }
    }
    public function aplMasukan(Request $request)
    {
      $masukan = DB::table("absensi.masukan")
                  ->orderBy("id_masukan","desc")
                  ->paginate(20);
      return $masukan;
    }
    public function mapArea(Request $request)
    {
      $mapArea = DB::table("absensi.map_area")->whereRaw("company='".$request->company."' and flag =0")->get();
      return ["mapArea"=>$mapArea];
    }
    public function getAbsensiUser(Request $request)
    {
      $absensi =DB::table("absensi.ceklog")->where("nik",$request->nik)->limit(10)->orderBy("id","desc")->get();
      return ["absensi"=>$absensi];
    }
}
