<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use \config\database;
use Response;
use PDF;


class monUnitController extends Controller
{
    //
    private $conn;
    private $user;
    public $IP;
    public $user_agent;
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
        //event(new onlineUserEvent("USER ONLINE FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));

    }
    public function Connection()
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $host =config('database.connections.mysql.host');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $db     = config('database.db_manual3');

        $conn = mysqli_connect($host,$user,$password,$db);

        if (mysqli_connect_errno())
          {
          return "Failed to connect to MySQL: " . mysqli_connect_error();
          }else{
           return $conn;
          }
    }
    public function form_unit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        $unit = DB::table("monitoring_unit.unit")->paginate(10);
        return view('rental.form.unit',["getUser"=>$this->user,"unit"=>$unit]); 
    }
    public function post_unit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        $db = DB::table("monitoring_unit.unit")
            ->insert([
                    "nama_unit"     =>$request->unit,
                    "user_entry"    =>$_SESSION['username'],
                    "tglIn"         =>date("Y-m-d H:i:s")
                     ]);
        if($db){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }
    }
    public function edit_unit(Request $request,$id_unit)
    {
        //dd($id_unit);
        if(!isset($_SESSION['username'])) return redirect('/'); 
        
        $edit = DB::table("monitoring_unit.unit")->where("id_unit",hex2bin($id_unit))->first();
        
        $unit = DB::table("monitoring_unit.unit")->paginate(10);
        return view('rental.form.unit',["getUser"=>$this->user,"unit"=>$unit,"edit"=>$edit,"id_unit"=>$id_unit]); 
    }
    public function put_unit(Request $request,$id_unit)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 

        $edit = DB::table("monitoring_unit.unit")
                ->where("id_unit",hex2bin($id_unit))
                ->update([
                    "nama_unit"=>$request->unit
                ]);
        if($edit){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }    
    }
    public function del_unit(Request $request,$id_unit)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
         $edit = DB::table("monitoring_unit.unit")
                ->where("id_unit",hex2bin($id_unit))
                ->update([
                    "flag"=>0
                ]); 
        if($edit){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }  
    }
    public function undo_unit(Request $request,$id_unit)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
         $edit = DB::table("monitoring_unit.unit")
                ->where("id_unit",hex2bin($id_unit))
                ->update([
                    "flag"=>1
                ]);
        if($edit){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }  

    }

    public function form_rental(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        $unit = DB::table("monitoring_unit.unit")->get();
        $rental = DB::table("monitoring_unit.data_hm_unit")->paginate(10);
        return view('rental.form.rental',["getUser"=>$this->user,"unit"=>$unit,"rental"=>$rental]); 
    }
    public function post_rental(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        $hm_awal = preg_replace('/[ ,]+/', '', $request->hm_awal);
        $hm_akhir = preg_replace('/[ ,]+/', '', $request->hm_akhir);
        $abp = preg_replace('/[ ,]+/', '', $request->abp);
        $mtk = preg_replace('/[ ,]+/', '', $request->mtk);
        $stb = preg_replace('/[ ,]+/', '', $request->stb);
        $bd = preg_replace('/[ ,]+/', '', $request->bd);
        $rental = DB::table("monitoring_unit.data_hm_unit")
                ->insert([
                    "tgl"       =>date("Y-m-d",strtotime($request->tgl)),
                    "shift"      =>$request->shift,
                    "unit"      =>$request->unit,
                    "nama"      =>$request->nama,
                    "hm_awal"   =>$hm_awal,
                    "hm_akhir"  =>$hm_akhir,
                    "abp"       =>$abp,
                    "mtk"       =>$mtk,
                    "stb"       =>$stb,
                    "bd"        =>$bd,
                    "user_input"=>$_SESSION['username'],
                    "timeinput" =>date("Y-m-d H:i:s")
                ]);

        if($rental){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }  
    }
    public function edit_hm(Request $request,$id_hm)
    {
        if(!isset($_SESSION['username'])) return redirect('/');        
        $unit = DB::table("monitoring_unit.unit")->get();
        $rental = DB::table("monitoring_unit.data_hm_unit")->paginate(10);
        $edit = DB::table("monitoring_unit.data_hm_unit")->where("id_hm",hex2bin($id_hm))->first();
        //dd($edit);
        return view('rental.form.rental',["getUser"=>$this->user,"unit"=>$unit,"rental"=>$rental,"edit"=>$edit,"id_hm"=>$id_hm]); 
    }
    public function put_hm(Request $request,$id_hm)
    {
        
         if(!isset($_SESSION['username'])) return redirect('/'); 
            $hm_awal = preg_replace('/[ ,]+/', '', $request->hm_awal);
            $hm_akhir = preg_replace('/[ ,]+/', '', $request->hm_akhir);
            $abp = preg_replace('/[ ,]+/', '', $request->abp);
            $mtk = preg_replace('/[ ,]+/', '', $request->mtk);
            $stb = preg_replace('/[ ,]+/', '', $request->stb);
            $bd = preg_replace('/[ ,]+/', '', $request->bd);
            $rental = DB::table("monitoring_unit.data_hm_unit")
                    ->where("id_hm",hex2bin($id_hm))
                    ->update([
                        "shift"      =>$request->shift,
                        "unit"      =>$request->unit,
                        "nama"      =>$request->nama,
                        "hm_awal"   =>$hm_awal,
                        "hm_akhir"  =>$hm_akhir,
                        "abp"       =>$abp,
                        "mtk"       =>$mtk,
                        "stb"       =>$stb,
                        "bd"        =>$bd
                    ]);
            if($rental){
                return redirect()->back()->with("success","Success!");
            }else{          
                return redirect()->back()->with("failed","Failed!");
            }  
    }

    public function del_hm(Request $request,$id_hm)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
         $edit = DB::table("monitoring_unit.data_hm_unit")
                ->where("id_hm",hex2bin($id_hm))
                ->update([
                    "flag"=>0
                ]); 
        if($edit){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }  
    }
    public function undo_hm(Request $request,$id_hm)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
         $edit = DB::table("monitoring_unit.data_hm_unit")
                ->where("id_hm",hex2bin($id_hm))
                ->update([
                    "flag"=>1
                ]);
        if($edit){
            return redirect()->back()->with("success","Success!");
        }else{          
            return redirect()->back()->with("failed","Failed!");
        }  

    }
    public function viewRental(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        # code...

        $rental = DB::table("monitoring_unit.data_hm_unit")
                    ->leftjoin("monitoring_unit.unit","monitoring_unit.unit.id_unit","monitoring_unit.data_hm_unit.id_hm")
                    ->select("monitoring_unit.data_hm_unit.*","monitoring_unit.unit.*");
        if(isset($_GET['cari'])){
            $filter = $rental->whereRaw("unit like'%".$_GET['cari']."%' or nama like'%".$_GET['cari']."%' or hm_awal like'%".$_GET['cari']."%' or hm_akhir like'%".$_GET['cari']."%' or abp like'%".$_GET['cari']."%' or mtk like'%".$_GET['cari']."%' or stb like'%".$_GET['cari']."%' or bd like'%".$_GET['cari']."%' ");
        }else{
            $filter = $rental;
        }

        $res = $filter->paginate(10);
        return view('rental.view.hm',["getUser"=>$this->user,"rental"=>$res]); 
    }

    public function rentalView(Request $request,$shift)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from data_hm_unit where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from data_hm_unit where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from data_hm_unit where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from data_hm_unit join unit on data_hm_unit.unit=unit.id_unit where shift='".$shift."' and year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from data_hm_unit join unit on data_hm_unit.unit=unit.id_unit where shift='".$shift."' and  year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from data_hm_unit join unit on data_hm_unit.unit=unit.id_unit where shift='".$shift."' and  year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("rental.view.hm",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"shift"=>$shift]);
    }
    public function rentalViewTotal(Request $request)
    {if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from data_hm_unit where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) asc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from data_hm_unit where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) asc");
        }else{
         $Month =  mysqli_query($konek,"select * from data_hm_unit where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) asc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
    if(isset($_GET['unit'])){
        $ob = mysqli_query($konek,"select * from data_hm_unit left join unit on data_hm_unit.unit=unit.id_unit where  unit='".$_GET['unit']."' and year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' group by tgl order by tgl desc");
    }else{
        $ob = mysqli_query($konek,"select * from data_hm_unit left join unit on data_hm_unit.unit=unit.id_unit where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' group by tgl order by tgl desc");
    }
        }else if(isset($request->year) && isset($request->m)){
    if(isset($_GET['unit'])){
        $ob = mysqli_query($konek,"select * from data_hm_unit left join unit on data_hm_unit.unit=unit.id_unit where  unit='".$_GET['unit']."' and  year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by tgl order by tgl desc");
    }else{
        $ob = mysqli_query($konek,"select * from data_hm_unit left join unit on data_hm_unit.unit=unit.id_unit where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by tgl order by tgl desc");
    }
        }else{
    if(isset($_GET['unit'])){
        $unit = $_GET['unit'];
            $ob = mysqli_query($konek,"select * from data_hm_unit left join unit on data_hm_unit.unit=unit.id_unit where unit='".$unit."' and year(tgl) = '".date("Y")."' and month(tgl) = '".date("m")."' group by tgl order by tgl desc");
    }else{
        $ob = mysqli_query($konek,"select * from data_hm_unit left join unit on data_hm_unit.unit=unit.id_unit where year(tgl) = '".date("Y")."' and month(tgl) = '".date("m")."' group by tgl order by tgl desc");
    }
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }

        $unit = DB::table("monitoring_unit.unit")->get();
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("rental.view.hm",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"unit"=>$unit]);

    }
}
