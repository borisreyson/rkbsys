<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use App\EmailSend;

class monSRController extends Controller
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
        $db     = config('database.db_manual1');


    	$conn = mysqli_connect($host,$user,$password,$db);

    	if (mysqli_connect_errno())
		  {
		  return "Failed to connect to MySQL: " . mysqli_connect_error();
		  }else{
		   return $conn;
		  }
    }
    public function dailySR(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from hauling where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from hauling where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
    	//dd($konek);
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.srDaily",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"konek"=>$konek]);
    }
    public function exposeForm(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

        return view("monitoring.form.expose",["getUser"=>$this->user,"konek"=>$konek]);
    }

    public function exposeFormEdit(Request $request,$id)
    {
    	//dd(hex2bin($id));

        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $data_edit = DB::table("monitoring_produksi.stripping_ratio")->where("id",hex2bin($id))->first();
        //dd($row);
        return view("monitoring.form.expose",["getUser"=>$this->user,"data_edit"=>$data_edit,"konek"=>$konek,"edit"=>"OK"]);
    }
    public function exposePost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $inventory = preg_replace('/[ ,]+/', '', $request->inventory);
        $in_sr = DB::table("monitoring_produksi.stripping_ratio")
        			->insert([
        				"tgl"		=>date("Y-m-d",strtotime($request->tgl)),
        				"inventory"	=>$inventory,
        				"keterangan"=>$request->keterangan,
        				"user_input"=>$_SESSION['username'],
        				"timeInput"	=>date("Y-m-d H:i:s")
        			]);
        if($in_sr){
        	return redirect()->back()->with("success","Success!");
        }else{        	
        	return redirect()->back()->with("failed","Failed!");
        }
    }
    public function exposeUpdate(Request $request,$id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $inventory = preg_replace('/[ ,]+/', '', $request->inventory);
        $in_sr = DB::table("monitoring_produksi.stripping_ratio")
        			->where("id",hex2bin($id))
        			->update([
        				"inventory"	=>$inventory,
        				"keterangan"=>$request->keterangan,
        			]);
        if($in_sr){
        	return redirect()->back()->with("success","Success!");
        }else{        	
        	return redirect()->back()->with("failed","Failed!");
        }
    }
    public function exposeDelete(Request $request,$id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
    	$in_sr = DB::table("monitoring_produksi.stripping_ratio")
        			->where("id",hex2bin($id))
        			->update([
        				"flag"	=>2
        			]);
    	# code...
        if($in_sr){
        	return redirect()->back()->with("success","Success!");
        }else{        	
        	return redirect()->back()->with("failed","Failed!");
        }
    }
    public function exposeUndo(Request $request,$id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
    	# code...
    	$in_sr = DB::table("monitoring_produksi.stripping_ratio")
        			->where("id",hex2bin($id))
        			->update([
        				"flag"	=>1
        			]);
        if($in_sr){
        	return redirect()->back()->with("success","Success!");
        }else{        	
        	return redirect()->back()->with("failed","Failed!");
        }
    }
}
