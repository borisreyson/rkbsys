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
    private $connPLN;
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
    //barging

    public function ConnectionPLN()
    {
    	if(!isset($_SESSION['username'])) return redirect('/');
    	$host =config('database.connections.mysql.host');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $db     = "monitoring_pln";


    	$connPLN = mysqli_connect($host,$user,$password,$db);

    	if (mysqli_connect_errno())
		  {
		  return "Failed to connect to MySQL: " . mysqli_connect_error();
		  }else{
		   return $connPLN;
		  }
    }
    public function barging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->ConnectionPLN();
        $year =  mysqli_query($konek,"select * from barging where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from barging where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by (tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by (tgl) asc");
        }else{
            $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by (tgl) asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("pln.barging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }


    public function bargeMonthly(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $montH = [];
        $konek = $this->ConnectionPLN();
        $year =  mysqli_query($konek,"select * from barging where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from barging where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' group by month(tgl) order by tgl asc");
        }else{
            $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' group by month(tgl) order by month(tgl) asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $dDay = mysqli_query($konek,"select * from barging where year(tgl)='".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;
        }
        //dd($data);
        $LABEL = array('ACTUAL MONTHLY','PLAN MONTHLY');
        return view("pln.barging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
    }
    public function bargeACH(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $month = [];
        $dataX=[];
        $konek = $this->ConnectionPLN();
        $year =  mysqli_query($konek,"select * from barging where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from barging where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $month[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by month(tgl) order by month(tgl) desc");
        }else{
            $ob = mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' order by tgl desc");
        }
        $row = mysqli_fetch_object($ob);
            $dDay = mysqli_query($konek,"select * from barging where year(tgl)='".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by (tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;

//dd($data);
        if(count($month)>0){
        if(isset($request->year) && !isset($request->m)){
        $daily = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by day(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $daily = mysqli_query($konek,"select * from barging where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by day(tgl) asc");
        }else{
            $daily = mysqli_query($konek,"select * from barging where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by day(tgl) asc");
        }

        while($rowx = mysqli_fetch_object($daily)){
            $dataX[] = $rowx;
        }
            }

        $LABEL = array('ACH DAILY');
        $LABEL_M = array('ACH MONTHLY');
        return view("pln.achBARGE",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
    }

    //boat
    public function boat(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->ConnectionPLN();
        $year =  mysqli_query($konek,"select * from barge_boat where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from barge_boat where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from barge_boat where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from barge_boat where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from barge_boat where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from barge_boat where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.boat_pln",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }
    //BOAT

    public function formBOAT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->ConnectionPLN();

        return view("pln.form.boat",["getUser"=>$this->user,"konek"=>$konek]);
    }
    public function postBOAT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $tonase = preg_replace('/[ ,]+/', '', $request->tonase);
            $insert = DB::table("monitoring_pln.barge_boat")
                  ->insert([
                    "tgl"=>date("Y-m-d",strtotime($request->tgl)),
                    "tugboat"=>$request->boat,
                    "barge"=>$request->barge,
                    "time_board"=>$request->time_board,
                    "tonase"=>$tonase,
                    "status"=>$request->keterangan,
                    "user_input"=>$_SESSION['username'],
                    "time_input"=>date("Y-m-d H:i:s")

                  ]);
            if($insert){
                return redirect()->back()->with("success","Data Success Input!");
            }else{
                return redirect()->back()->with("failed","Failed Input Data!");
            }
    }

    public function editBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $konek = $this->ConnectionPLN();
        $daily =  mysqli_query($konek,"select * from barge_boat where no = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("pln.form.boat",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->ConnectionPLN();
        $tonase = preg_replace('/[ ,]+/', '', $request->tonase);

            $OB = DB::table("monitoring_pln.barge_boat")
                    ->where("no",hex2bin($dataID))
                    /*->get();*/
                    ->update([
                    "tugboat"=>$request->boat,
                    "barge"=>$request->barge,
                    "time_board"=>$request->time_board,
                    "tonase"=>$tonase,
                    "status"=>$request->keterangan,
                    "time_input"=>date("Y-m-d H:i:s")]);

            if($OB)
            {
             return redirect('/pln/form/boat')->with('success',"Boat Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }

    public function deleteBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->ConnectionPLN();

            $OB = mysqli_query($konek,"update barge_boat SET flag='2' where no = '".hex2bin($dataID)."'");
            if($OB)
            {
             return redirect('/pln/form/boat')->with('success',"Boat Telah Di Hapus!");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
    public function undoBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->ConnectionPLN();

            $OB = mysqli_query($konek,"update barge_boat SET flag='1' where no = '".hex2bin($dataID)."'");
            if($OB)
            {
             return redirect('/pln/form/boat')->with('success',"Boat Telah Di Kembalikan!");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
}
