<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use App\EmailSend;

class monitorController extends Controller
{
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
    	$host ="localhost";
    	$user = "root";
    	$password = "bujanginam26011995";
    	$db 	= "monitoring_produksi";


    	$conn = mysqli_connect($host,$user,$password,$db);

    	if (mysqli_connect_errno())
		  {
		  return "Failed to connect to MySQL: " . mysqli_connect_error();
		  }else{
		   return $conn;
		  }
    }

//ob
    public function ob(Request $request)
    {
    	if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
    	$data=[];
        $dataY = [];
        $montH = [];
    	$konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from ob where year(tgl) <= '".date("Y")."' and flag = '1' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from ob where year(tgl) ='".$request->year."' and flag ='1' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y")."' and flag ='1' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
        //dd(date("Y",strtotime($dataY[0]->tgl)));

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' and flag='1' order by tgl asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' and flag='1' order by tgl asc");
        }else{
            $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' and flag='1' order by tgl asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
    	return view("monitoring.ob",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }
    public function obMonthly(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from ob where year(tgl) <= '".date("Y")."' group by year(tgl) order by tgl desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        //dd($dataY);
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from ob where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) asc");
        }else{
            $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y")."' group by month(tgl) order by month(tgl) asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $dDay = mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;
        }
        //dd($data);
        $LABEL = array('ACTUAL MONTHLY','PLAN MONTHLY');
        return view("monitoring.ob",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
    }
    public function obACH(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $montH = [];
        $dataX=[];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from ob where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from ob where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $month[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by month(tgl) order by month(tgl) desc");
        }else{
            $ob = mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' order by tgl desc");
        }
            $row = mysqli_fetch_object($ob);

            $dDay = mysqli_query($konek,"select * from ob where year(tgl)='".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;

        if(count($month)>0){
            if(isset($request->year) && !isset($request->m)){
            $daily = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by month(tgl) asc");
            }else if(isset($request->year) && isset($request->m)){
            $daily = mysqli_query($konek,"select * from ob where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl asc");
            }else{
                $daily = mysqli_query($konek,"select * from ob where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by day(tgl) asc");
            }
            while($rowx = mysqli_fetch_object($daily)){
                $dataX[] = $rowx;
            }
        }
        $LABEL = array('ACH DAILY');
        $LABEL_M = array('ACH MONTHLY');
        return view("monitoring.achOB",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
    }

//hauling
    public function hauling(Request $request)
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
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl asc");
        }else{
            $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.hauling",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }

    public function haulMonthly(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
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

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' group by month(tgl) order by tgl asc");
        }else{
            $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' group by month(tgl) order by month(tgl) asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $dDay = mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;
        }
        $LABEL = array('ACTUAL MONTHLY','PLAN MONTHLY');
        return view("monitoring.hauling",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
    }
    public function haulACH(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $month = [];
        $dataX=[];
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
            $month[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by month(tgl) order by month(tgl) desc");
        }else{
            $ob = mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' order by tgl desc");
        }
        $row = mysqli_fetch_object($ob);
        //dd($row);
            $dDay = mysqli_query($konek,"select * from hauling where year(tgl)='".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;

//dd($data);
        if(count($month)>0){
        if(isset($request->year) && !isset($request->m)){
        $daily = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by month(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $daily = mysqli_query($konek,"select * from hauling where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by month(tgl) asc");
        }else{
            $daily = mysqli_query($konek,"select * from hauling where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by day(tgl) asc");
        }

        while($rowx = mysqli_fetch_object($daily)){
            $dataX[] = $rowx;
        }
            }
        $LABEL = array('ACH DAILY');
        $LABEL_M = array('ACH MONTHLY');
        return view("monitoring.achHAUL",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
    }

//chrushing

    public function crushing(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from crushing where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from crushing where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl asc");
        }else{
            $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.crushing",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }


    public function crushMonthly(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from crushing where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from crushing where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' group by month(tgl) order by tgl asc");
        }else{
            $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' group by month(tgl) order by month(tgl) asc");
        }
        while($row = mysqli_fetch_object($ob)){
            $dDay = mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;
        }
        //dd($data);
        $LABEL = array('ACTUAL MONTHLY','PLAN MONTHLY');
        return view("monitoring.crushing",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
    }
    public function crushACH(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $month = [];
        $dataX=[];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from crushing where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from crushing where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $month[] = $row2;
        }

        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' and month(tgl) ='".$request->m."' group by month(tgl) order by month(tgl) desc");
        }else{
           $ob = mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' order by tgl desc");
        }
        $row = mysqli_fetch_object($ob);
            $dDay = mysqli_query($konek,"select * from crushing where year(tgl)= '".date("Y",strtotime($row->tgl))."' and month(tgl) = '".date("m",strtotime($row->tgl))."' order by day(tgl) desc");
            $rowD = mysqli_fetch_object($dDay);
            $data[] = $rowD;


        if(count($month)>0){
        if(isset($request->year) && !isset($request->m)){
        $daily = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by month(tgl) asc");
        }else if(isset($request->year) && isset($request->m)){
        $daily = mysqli_query($konek,"select * from crushing where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl asc");
        }else{
            $daily = mysqli_query($konek,"select * from crushing where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($month[0]->tgl))."' order by day(tgl) asc");
        }

        while($rowx = mysqli_fetch_object($daily)){
            $dataX[] = $rowx;
        }
            }

        $LABEL = array('ACH DAILY');
        $LABEL_M = array('ACH MONTHLY');
        return view("monitoring.achCRUSH",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
    }
//barging

    public function barging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
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
        return view("monitoring.barging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }


    public function bargeMonthly(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dayD=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
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
        return view("monitoring.barging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
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
        $konek = $this->Connection();
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
        return view("monitoring.achBARGE",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
    }

//boat
    public function boat(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
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
        $ob = mysqli_query($konek,"select * from barge_boat where flag='1' and year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from barge_boat where flag='1' and year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from barge_boat where flag='1' and year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.boat",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }

//stockProduct
        public function stockProduct(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from stock where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from stock where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from stock where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from stock where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from stock where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from stock where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.stock",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }
    public function formOB(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from plan_ob_daily where flag ='1' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.ob",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek]);
    }

    public function postOB(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $dailyPlan =  mysqli_query($konek,"insert into plan_ob_daily (tgl,ob_daily_planing,ob_mtd_planing,months,years) values ('".$date."','".$plan_daily."','".$mtd_plan."','".date("m")."','".date("Y")."')");
        if($dailyPlan){
            $OB = mysqli_query($konek,"insert into ob (tgl,plan_daily,actual_daily,mtd_plan,mtd_actual,remark,user_input,time_input,flag) values ('".$date."','".$plan_daily."','".$actual_daily."','".$mtd_plan."','".$mtd_actual."','".$remarks."','".$_SESSION['username']."','".date("Y-m-d H:i:s")."','1')");
            if($OB)
            {
             return redirect()->back()->with('success',"OB Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Update Data Error!");
        }
    }
    public function editOB(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from ob where tgl = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.ob",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateOB(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_ob_daily set ob_daily_planing='".$plan_daily."',ob_mtd_planing='".$mtd_plan."',months='".date("m",strtotime($date))."',years='".date("Y",strtotime($date))."' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update ob SET plan_daily='".$plan_daily."', actual_daily='".$actual_daily."',mtd_plan='".$mtd_plan."' ,mtd_actual='".$mtd_actual."' ,remark='".$remarks."' ,time_input='".date("Y-m-d H:i:s")."',user_input='".$_SESSION['username']."' where tgl = '".$date."'");
            if($OB)
            {
             return redirect('/monitoring/form/ob')->with('success',"OB Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
             return redirect()->back()->with('failed',"Update Data Error!");
        }

    }
    public function deleteOB(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_ob_daily set flag='2' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update ob SET flag='2' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"OB Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Delete Data Error!");
        }

    }
    public function undoOB(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_ob_daily set flag='1' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update ob SET flag='1' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"OB Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Undo Data Error!");
        }

    }
    public function formHAULING(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from plan_hl_daily where flag ='1' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.hauling",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek]);
    }

    public function editHAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from hauling where tgl = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.hauling",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateHAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_hl_daily set hl_daily_planing='".$plan_daily."',hl_mtd_planing='".$mtd_plan."',months='".date("m",strtotime($date))."',years='".date("Y",strtotime($date))."' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update hauling SET plan_daily='".$plan_daily."', actual_daily='".$actual_daily."',mtd_plan='".$mtd_plan."' ,mtd_actual='".$mtd_actual."' ,remark='".$remarks."' ,time_input='".date("Y-m-d H:i:s")."',user_input='".$_SESSION['username']."' where tgl = '".$date."'");
            if($OB)
            {
             return redirect('/monitoring/form/hauling')->with('success',"HAULING Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
             return redirect()->back()->with('failed',"Update Data Error!");
        }

    }

    public function undoHAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_hl_daily set flag='1' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update hauling SET flag='1' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"HAULING Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Undo Data Error!");
        }

    }
    public function deleteHAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_hl_daily set flag='2' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update hauling SET flag='2' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"HAULING Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Delete Data Error!");
        }

    }
    public function postHAULING(Request $request)
    {
     if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $dailyPlan =  mysqli_query($konek,"insert into plan_hl_daily (tgl,hl_daily_planing,hl_mtd_planing,months,years) values ('".$date."','".$plan_daily."','".$mtd_plan."','".date("m")."','".date("Y")."')");
        if($dailyPlan){
            $OB = mysqli_query($konek,"insert into hauling (tgl,plan_daily,actual_daily,mtd_plan,mtd_actual,remark,user_input,time_input,flag) values ('".$date."','".$plan_daily."','".$actual_daily."','".$mtd_plan."','".$mtd_actual."','".$remarks."','".$_SESSION['username']."','".date("Y-m-d H:i:s")."','1')");
            if($OB)
            {
             return redirect()->back()->with('success',"Hauling Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Update Data Error!");
        }
    }
    public function formCRUSHING(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from plan_cr_daily where flag ='1' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.crushing",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek]);
    }

    public function editCRUSHING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from crushing where tgl = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.crushing",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateCRUSHING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_cr_daily set cr_daily_planing='".$plan_daily."',cr_mtd_planing='".$mtd_plan."',months='".date("m",strtotime($date))."',years='".date("Y",strtotime($date))."' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update crushing SET plan_daily='".$plan_daily."', actual_daily='".$actual_daily."',mtd_plan='".$mtd_plan."' ,mtd_actual='".$mtd_actual."' ,remark='".$remarks."' ,time_input='".date("Y-m-d H:i:s")."',user_input='".$_SESSION['username']."' where tgl = '".$date."'");
            if($OB)
            {
             return redirect('/monitoring/form/crushing')->with('success',"CRUSHING Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
             return redirect()->back()->with('failed',"Update Data Error!");
        }

    }

    public function deleteCRUSHING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_hl_daily set flag='1' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update hauling SET flag='1' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"HAULING Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Undo Data Error!");
        }

    }
    public function undoCRUSHING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_hl_daily set flag='2' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update hauling SET flag='2' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"HAULING Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Delete Data Error!");
        }

    }
    public function postCRUSHING(Request $request)
    {
     if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $dailyPlan =  mysqli_query($konek,"insert into plan_cr_daily (tgl,cr_daily_planing,cr_mtd_planing,months,years) values ('".$date."','".$plan_daily."','".$mtd_plan."','".date("m")."','".date("Y")."')");
        if($dailyPlan){
            $OB = mysqli_query($konek,"insert into crushing (tgl,plan_daily,actual_daily,mtd_plan,mtd_actual,remark,user_input,time_input,flag) values ('".$date."','".$plan_daily."','".$actual_daily."','".$mtd_plan."','".$mtd_actual."','".$remarks."','".$_SESSION['username']."','".date("Y-m-d H:i:s")."','1')");
            if($OB)
            {
             return redirect()->back()->with('success',"Hauling Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Update Data Error!");
        }
    }



    public function formBarging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from plan_br_daily where flag ='1' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.barging",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek]);
    }


    public function editBARGING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from barging where tgl = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.barging",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateBARGING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_br_daily set br_daily_planing='".$plan_daily."',br_mtd_planing='".$mtd_plan."',months='".date("m",strtotime($date))."',years='".date("Y",strtotime($date))."' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update barging SET plan_daily='".$plan_daily."', actual_daily='".$actual_daily."',mtd_plan='".$mtd_plan."' ,mtd_actual='".$mtd_actual."' ,remark='".$remarks."' ,time_input='".date("Y-m-d H:i:s")."',user_input='".$_SESSION['username']."' where tgl = '".$date."'");
            if($OB)
            {
             return redirect('/monitoring/form/barging')->with('success',"Barging Telah Di Update ");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
             return redirect()->back()->with('failed',"Update Data Error!");
        }

    }

    public function undoBARGING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_br_daily set flag='1' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update barging SET flag='1' where tgl = '".$date."'");
            if($OB)
            {
             return redirect('/monitoring/form/barging')->with('success',"Barging Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Undo Data Error!");
        }

    }
    public function deleteBARGING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  mysqli_query($konek,"update plan_br_daily set flag='2' where tgl = '".$date."'");
        if($dailyPlan){
            $OB = mysqli_query($konek,"update barging SET flag='2' where tgl = '".$date."'");
            if($OB)
            {
             return redirect()->back()->with('success',"Barging Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Delete Data Error!");
        }

    }
    public function postBarging(Request $request)
    {
     if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $dailyPlan =  mysqli_query($konek,"insert into plan_br_daily (tgl,br_daily_planing,br_mtd_planing,months,years) values ('".$date."','".$plan_daily."','".$mtd_plan."','".date("m")."','".date("Y")."')");
        if($dailyPlan){
            $OB = mysqli_query($konek,"insert into barging (tgl,plan_daily,actual_daily,mtd_plan,mtd_actual,remark,user_input,time_input,flag) values ('".$date."','".$plan_daily."','".$actual_daily."','".$mtd_plan."','".$mtd_actual."','".$remarks."','".$_SESSION['username']."','".date("Y-m-d H:i:s")."','1')");
            if($OB)
            {
             return redirect()->back()->with('success',"Barging Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
        }else{
            return redirect()->back()->with('failed',"Update Data Error!");
        }
    }

    public function formBOAT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

        return view("monitoring.form.boat",["getUser"=>$this->user,"konek"=>$konek]);
    }
    public function postBOAT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $tonase = preg_replace('/[ ,]+/', '', $request->tonase);
            $insert = DB::table("monitoring_produksi.barge_boat")
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
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from barge_boat where no = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.boat",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $tonase = preg_replace('/[ ,]+/', '', $request->tonase);

            $OB = DB::table("monitoring_produksi.barge_boat")
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
             return redirect('/monitoring/form/boat')->with('success',"Boat Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }

    public function deleteBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

            $OB = mysqli_query($konek,"update barge_boat SET flag='2' where no = '".hex2bin($dataID)."'");
            if($OB)
            {
             return redirect('/monitoring/form/boat')->with('success',"Boat Telah Di Hapus!");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
    public function undoBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

            $OB = mysqli_query($konek,"update barge_boat SET flag='1' where no = '".hex2bin($dataID)."'");
            if($OB)
            {
             return redirect('/monitoring/form/boat')->with('success',"Boat Telah Di Kembalikan!");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }

    public function formSTOCK(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

        return view("monitoring.form.stock",["getUser"=>$this->user,"konek"=>$konek]);
    }
    public function postSTOCK(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $check = DB::table("monitoring_produksi.stock")->where('tgl',date("Y-m-d",strtotime($request->tgl)))->count();
        if($check>0){
                return redirect()->back()->with("failed","Harap Memeriksa Tanggal!");
        }
        $stock_rom = preg_replace('/[ ,]+/', '', $request->stock_rom);
        $stock_product = preg_replace('/[ ,]+/', '', $request->stock_product);
            $insert = DB::table("monitoring_produksi.stock")
                  ->insert([
                    "tgl"=>date("Y-m-d",strtotime($request->tgl)),
                    //"dl_daily_actual"=>$request->dl_daily,
                    //"dl_mtd_actual"=>$request->dl_mtd,
                    "stock_rom"=>$stock_rom,
                    "stock_product"=>$stock_product,
                    "remark"=>$request->keterangan,
                    "user_input"=>$_SESSION['username'],
                    "time_input"=>date("Y-m-d H:i:s")
                  ]);
            if($insert){
                return redirect()->back()->with("success","Data Success Input!");
            }else{
                return redirect()->back()->with("failed","Failed Input Data!");
            }
    }

    public function editSTOCK(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $konek = $this->Connection();
        $daily =  mysqli_query($konek,"select * from stock where tgl = '".$dataID."' order by tgl desc");
        $row = mysqli_fetch_object($daily);

        return view("monitoring.form.stock",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateSTOCK(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $stock_rom = preg_replace('/[ ,]+/', '', $request->stock_rom);
        $stock_product = preg_replace('/[ ,]+/', '', $request->stock_product);

            $OB = DB::table("monitoring_produksi.stock")
                    ->where("tgl",hex2bin($dataID))
                    /*->get();*/
                    ->update([
                    //"dl_daily_actual"=>$request->dl_daily,
                    //"dl_mtd_actual"=>$request->dl_mtd,
                    "stock_rom"=>$stock_rom,
                    "stock_product"=>$stock_product,
                    "remark"=>$request->keterangan,
                    "time_input"=>date("Y-m-d H:i:s")]);

            if($OB)
            {
             return redirect('/monitoring/form/stock')->with('success',"Stock Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }

    public function deleteSTOCK(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

            $OB = mysqli_query($konek,"update stock SET flag='2' where tgl = '".hex2bin($dataID)."'");
            if($OB)
            {
             return redirect('/monitoring/form/stock')->with('success',"Stock Telah Di Hapus!");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
    public function undoSTOCK(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

            $OB = mysqli_query($konek,"update stock SET flag='1' where tgl = '".hex2bin($dataID)."'");
            if($OB)
            {
             return redirect('/monitoring/form/stock')->with('success',"Stock Telah Di Kembalikan!");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }

    //delay Hauling

    public function formDLhauling(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $typeDelay =  DB::table("monitoring_produksi.type_delay")
                        ->where("groupID","OPR")
                        ->get();

        return view("monitoring.form.dl_hauling",["getUser"=>$this->user,"konek"=>$konek,"typeDelay"=>$typeDelay]);
    }

    public function postDLhauling(Request $request)
    {
       if(!isset($_SESSION['username'])) return redirect('/');
        //$konek = $this->Connection();

        $hlDelay =  DB::table("monitoring_produksi.hl_delay_daily")
                    ->insert([
                        "tgl"           =>  date("Y-m-d",strtotime($request->tgl)),
                        "shift"         =>  $request->shift,
                        "start"         =>  $request->start,
                        "finish"        =>  $request->finish,
                        "type_delay"    =>  $request->type_delay,
                        "dynamicField"  =>  $request->dynamicField,
                        "keterangan"    =>  $request->keterangan,
                        "user_entry"    =>  $_SESSION['username']
                    ]);
        if($hlDelay){
            return redirect()->back()->with("success","Data Sudah Di Update!");
        }else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }

    public function editDLhauling(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $typeDelay =  DB::table("monitoring_produksi.type_delay")->get();
        $HLDelay =  DB::table("monitoring_produksi.hl_delay_daily")->where("no",hex2bin($dataID))->first();
       // dd($HLDelay);
        return view("monitoring.form.dl_hauling",["getUser"=>$this->user,"konek"=>$konek,"typeDelay" => $typeDelay , "HLDelay" => $HLDelay,"edit"=>"true"]);
    }
    public function updateDLhauling(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.hl_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "shift"         =>  $request->shift,
                        "start"         =>  $request->start,
                        "finish"        =>  $request->finish,
                        "type_delay"    =>  $request->type_delay,
                        "dynamicField"  =>  $request->dynamicField,
                        "keterangan"    =>  $request->keterangan
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/hauling')->with("success","Data Sudah Di Update!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }

    public function deleteDLhauling(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.hl_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "flag"         =>  2
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/hauling')->with("success","Data Sudah Di Hapus!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Menghapus Data!");
        }
    }
    public function undoDLhauling(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.hl_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "flag"         =>  1
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/hauling')->with("success","Data Sudah Di Kembalikan!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengembalikan Data!");
        }
    }
    public function DelayHauling(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from hl_delay_daily where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from hl_delay_daily where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from hl_delay_daily where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from hl_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from hl_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by tgl order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from hl_delay_daily where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' group by tgl order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.dlHauling",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }

    //delay Barging

    public function formDLBarging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

        return view("monitoring.form.dl_barging",["getUser"=>$this->user,"konek"=>$konek]);
    }
    public function postDLBarging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //$konek = $this->Connection();

        $hlDelay =  DB::table("monitoring_produksi.br_delay_daily")
                    ->insert([
                        "tgl"           =>  date("Y-m-d",strtotime($request->tgl)),
                        "shift"         =>  $request->shift,
                        "start"         =>  $request->start,
                        "finish"        =>  $request->finish,
                        "keterangan"    =>  $request->keterangan,
                        "user_entry"    =>  $_SESSION['username']
                    ]);
        if($hlDelay){
            return redirect()->back()->with("success","Data Sudah Di Update!");
        }else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }


    public function editDLBarging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $HLDelay =  DB::table("monitoring_produksi.br_delay_daily")->where("no",hex2bin($dataID))->first();
       // dd($HLDelay);
        return view("monitoring.form.dl_barging",["getUser"=>$this->user,"konek"=>$konek, "HLDelay" => $HLDelay,"edit"=>"true"]);
    }

    public function updateDLBarging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.br_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "shift"         =>  $request->shift,
                        "start"         =>  $request->start,
                        "finish"        =>  $request->finish,
                        "keterangan"    =>  $request->keterangan
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/barging')->with("success","Data Sudah Di Update!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }


    public function deleteDLBarging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.br_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "flag"         =>  2
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/barging')->with("success","Data Sudah Di Hapus!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Menghapus Data!");
        }
    }
    public function undoDLBarging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.br_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "flag"         =>  1
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/barging')->with("success","Data Sudah Di Kembalikan!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengembalikan Data!");
        }
    }

    public function DelayBarging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from br_delay_daily where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from br_delay_daily where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from br_delay_daily where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from br_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from br_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by tgl order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from br_delay_daily where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' group by tgl order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.dlBarging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }


//DELAY OB
    public function formDLob(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $typeDelay = DB::table('monitoring_produksi.type_delay')->where("groupID","OB")->get();
        return view("monitoring.form.dl_ob",["getUser"=>$this->user,"konek"=>$konek,"typeDelay"=>$typeDelay]);
    }

    public function postDLob(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //$konek = $this->Connection();

        $hlDelay =  DB::table("monitoring_produksi.ob_delay_daily")
                    ->insert([
                        "tgl"           =>  date("Y-m-d",strtotime($request->tgl)),
                        "shift"         =>  $request->shift,
                        "type_delay"         =>  $request->type_delay,
                        "delay"         =>  $request->delay,
                        "keterangan"    =>  $request->keterangan,
                        "user_entry"    =>  $_SESSION['username']
                    ]);
        if($hlDelay){
            return redirect()->back()->with("success","Data Sudah Di Update!");
        }else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }


    public function editDLob(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $HLDelay =  DB::table("monitoring_produksi.ob_delay_daily")->where("no",hex2bin($dataID))->first();
       // dd($HLDelay);
        $typeDelay = DB::table('monitoring_produksi.type_delay')->where("groupID","OB")->get();
        return view("monitoring.form.dl_ob",["getUser"=>$this->user,"konek"=>$konek, "HLDelay" => $HLDelay,"edit"=>"true", "typeDelay"=>$typeDelay]);
    }

    public function updateDLob(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.ob_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "shift"         =>  $request->shift,
                        "delay"         =>  $request->delay,
                        "type_delay"        =>  $request->type_delay,
                        "keterangan"    =>  $request->keterangan
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/ob')->with("success","Data Sudah Di Update!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }


    public function deleteDLob(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.ob_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "flag"         =>  2
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/ob')->with("success","Data Sudah Di Hapus!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Menghapus Data!");
        }
    }
    public function undoDLob(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.ob_delay_daily")
                    ->where("no",hex2bin($dataID))
                    ->update([
                        "flag"         =>  1
                    ]);
        if($HLDelay){
            return redirect('/monitoring/form/delay/ob')->with("success","Data Sudah Di Kembalikan!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengembalikan Data!");
        }
    }

    public function DelayOb(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from ob_delay_daily where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        if($year){
            while($row1 = mysqli_fetch_object($year)){
                $dataY[] = $row1;
            }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from ob_delay_daily where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from ob_delay_daily where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from ob_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by tgl order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from ob_delay_daily where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' group by tgl order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }

        }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.dlOb",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }

    //DELAY CRUSHING
    public function formDLCrushing(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $typeDelay =  DB::table("monitoring_produksi.type_delay")
                        ->where("groupID","CCP")
                        ->get();
        // dd($konek);
        return view("monitoring.form.dl_crushing",["getUser"=>$this->user,"konek"=>$konek,"typeDelay"=>$typeDelay]);
    }


    public function postDLCrushing(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //$konek = $this->Connection();
        //dd($request);
        $w_h =  DB::table("monitoring_produksi.cr_delay_daily")
                    ->insert([
                        "tgl"           =>  date("Y-m-d",strtotime($request->tgl)),
                        "type_delay"    =>  $request->type_delay,
                        "shift"         =>  $request->shift,
                        "timeCR"         =>  $request->w_h,
                        "typeCR"        =>  "Work Hour",
                        "remark"         =>  $request->w_h_r,
                        "user_entry"    =>  $_SESSION['username']
                    ]);
        if($w_h){
        $stb =  DB::table("monitoring_produksi.cr_delay_daily")
                    ->insert([
                        "tgl"           =>  date("Y-m-d",strtotime($request->tgl)),
                        "type_delay"    =>  $request->type_delay,
                        "shift"         =>  $request->shift,
                        "timeCR"         =>  $request->stb,
                        "typeCR"        =>  "Standby",
                        "remark"         =>  $request->stb_r,
                        "user_entry"    =>  $_SESSION['username']
                    ]);
        if($stb){
        $b_d =  DB::table("monitoring_produksi.cr_delay_daily")
                    ->insert([
                        "tgl"           =>  date("Y-m-d",strtotime($request->tgl)),
                        "type_delay"    =>  $request->type_delay,
                        "shift"         =>  $request->shift,
                        "timeCR"         =>  $request->b_d,
                        "typeCR"        =>  "Breakdown",
                        "remark"         =>  $request->b_d_r,
                        "user_entry"    =>  $_SESSION['username']
                    ]);
                if($b_d){
                    $hlDelay = true;
                }else{
                    $hlDelay = false;
                }
            }else{
                 $hlDelay=false;
            }
        }else{
             $hlDelay=false;
        }
        if($hlDelay){
            return redirect()->back()->with("success","Data Sudah Di Update!");
        }else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }


    public function editDLCrushing(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $data = null;
        $konek = $this->Connection();
        $typeDelay =  DB::table("monitoring_produksi.type_delay")
                        ->where("groupID","CCP")
                        ->get();
        $HLDelay =  DB::table("monitoring_produksi.cr_delay_daily")
                    ->where([
                        ["tgl",hex2bin($dataID)],
                        ["shift",hex2bin($request->sh)],
                        ["type_delay",hex2bin($request->t)]
                    ])->get();
        foreach($HLDelay as $k => $v){
            $typeCR[$v->typeCR] = $v->timeCR;
            $data = array("tgl"=>$v->tgl,
                          "shift"=>$v->shift,
                          "type_delay"=>$v->type_delay,
                          "DL"=>$typeCR
                        );
           // print_r($v);
        }
        //dd($HLDelay);
        //return($data);
        return view("monitoring.form.dl_crushing",["getUser"=>$this->user,"konek"=>$konek, "HLDelay" => json_encode($data),"edit"=>"true","typeDelay"=>$typeDelay]);
    }


    public function updateDLCrushing(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd(hex2bin($request->t));
        //dd($request);
        if($request->Work_Hour){

        $W_h =  DB::table("monitoring_produksi.cr_delay_daily")
                    ->where([
                        ["tgl",hex2bin($dataID)],
                        ["shift",hex2bin($request->s)],
                        ["type_delay",hex2bin($request->t)],
                        ["typeCR","Work Hour"]
                    ])
                    ->update([
                        "shift"         =>  $request->shift,
                        "timeCR"         =>  $request->Work_Hour,
                        "type_delay"    =>  $request->type_delay
                    ]);
    if($W_h>=0){
        $Stb =  DB::table("monitoring_produksi.cr_delay_daily")
            ->where([
                ["tgl",hex2bin($dataID)],
                ["shift",hex2bin($request->s)],
                ["type_delay",hex2bin($request->t)],
                ["typeCR","Standby"]
            ])
            ->update([
                "shift"         =>  $request->shift,
                "timeCR"         =>  $request->Standby,
                "type_delay"    =>  $request->type_delay
            ]);
        if($Stb>=0){
            $bd =  DB::table("monitoring_produksi.cr_delay_daily")
            ->where([
                ["tgl",hex2bin($dataID)],
                ["shift",hex2bin($request->s)],
                ["type_delay",hex2bin($request->t)],
                ["typeCR","Breakdown"]
            ])
            ->update([
                "shift"         =>  $request->shift,
                "timeCR"         =>  $request->Breakdown,
                "type_delay"    =>  $request->type_delay
            ]);
            if($bd>=0){
                $HLDelay=true;
            }else{
                $HLDelay=false;
            }
        }else{
            $HLDelay=false;
        }
    }else{
        $HLDelay=false;
    }
        }
        if($HLDelay){
            return redirect('/monitoring/form/delay/crushing')->with("success","Data Sudah Di Update!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }
public function deleteDLCrushing(Request $request,$dataID)
{
    if(!isset($_SESSION['username'])) return redirect('/');

    $db = DB::table("monitoring_produksi.cr_delay_daily")
        ->whereRaw("tgl='".hex2bin($dataID)."' and shift='".hex2bin($request->sh)."' and  type_delay='".hex2bin($request->t)."'")
        ->update([
                    "flag"=>0
                ]);
    if($db){
            return redirect()->back()->with("success","Data Telah Di Hapus!");
    }else{
            return redirect()->back()->with("failed","Gagal menghapus data!");
        }
}
public function undoDLCrushing(Request $request,$dataID)
{
    $tgl = (hex2bin($dataID));
    $t = (hex2bin($request->t));
    $sh = (hex2bin($request->sh));

        if(!isset($_SESSION['username'])) return redirect('/');
        $HLDelay =  DB::table("monitoring_produksi.cr_delay_daily")
                    ->whereRaw("tgl = '".$tgl."' and type_delay='".$t."' and shift = '".$sh."'")
                    ->update([
                        "flag"         =>  1
                    ]);

        if($HLDelay){
            return redirect('/monitoring/form/delay/crushing')->with("success","Data Sudah Di Kembalikan!");
        }
        else
        {
            return redirect()->back()->with("failed","Gagal Mengembalikan Data!");
        }

}
public function DelayCrushing(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $thisYear = $request->year;
        $data=[];
        $dataY = [];
        $montH = [];
        $konek = $this->Connection();
        $year =  mysqli_query($konek,"select * from cr_delay_daily where year(tgl) <= '".date("Y")."' group by year(tgl) order by year(tgl) desc");
        while($row1 = mysqli_fetch_object($year)){
            $dataY[] = $row1;
        }
        if(isset($request->year)){
         $Month =  mysqli_query($konek,"select * from cr_delay_daily where year(tgl) ='".$request->year."' group by month(tgl) order by month(tgl) desc");
        }else{
         $Month =  mysqli_query($konek,"select * from cr_delay_daily where year(tgl) = '".date("Y")."' group by year(tgl),month(tgl) order by month(tgl) desc");
        }

        while($row2 = mysqli_fetch_object($Month)){
            $montH[] = $row2;
        }
if(count($montH)>0){
        if(isset($request->year) && !isset($request->m)){
        $ob = mysqli_query($konek,"select * from cr_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' order by tgl desc");
        }else if(isset($request->year) && isset($request->m)){
        $ob = mysqli_query($konek,"select * from cr_delay_daily where year(tgl) = '".$request->year."' and month(tgl) = '".$request->m."' group by tgl order by tgl desc");
        }else{
            $ob = mysqli_query($konek,"select * from cr_delay_daily where year(tgl) = '".date("Y",strtotime($dataY[0]->tgl))."' and month(tgl) = '".date("m",strtotime($montH[0]->tgl))."' group by tgl order by tgl desc");
        }
        while($row = mysqli_fetch_object($ob)){
            $data[] = $row;
        }
    }
        $LABEL = array('ACTUAL DAILY','PLAN DAILY');
        return view("monitoring.dlCrushing",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
    }



//MHU ABP
//FORM HAULING
    public function mhuHauling(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $daily = DB::table("monitoring_mhu.hauling")->first();
        return view("mhu.form.hauling",["getUser"=>$this->user,"daily"=>$daily ]);
    }
    public function cek_hl_mhu(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($request->date)){
            $c = DB::table("monitoring_mhu.hauling")->where("tgl","<",date("Y-m-d",strtotime($request->date)))->orderBy("tgl","desc")->sum("actual_daily");
            $d=number_format($c+$request->actual_daily,3);
        }else{
            $c = DB::table("monitoring_mhu.hauling")->orderBy("tgl","desc")->first();
            if(isset($c->mtd_actual)){
            $a= $c->mtd_actual;
            }else{
                $a= 0;
            }
            $d=number_format($a+$request->actual_daily,3);
        }

        echo $d;
    }
    public function mhuHauling_POST(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $in_mhu_hl = DB::table("monitoring_mhu.hauling")
                    ->insert([
                        "tgl"=>$date,
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                        "user_input"=>$_SESSION['username'],
                        "time_input"=>date("Y-m-d H:i:s")
                    ]);
            if($in_mhu_hl)
            {
             return redirect()->back()->with('success',"Hauling Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }

    public function edit_mhu_HAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $hl_mhu = DB::table("monitoring_mhu.hauling")->where("tgl",$dataID)->orderBy("tgl","desc")->first();
        //dd($hl_mhu);
        return view("mhu.form.hauling",["getUser"=>$this->user,"daily"=>$hl_mhu,"edit"=>"true"]);
    }

    public function update_mhu_HAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);
        $in_mhu_hl = DB::table("monitoring_mhu.hauling")
                    ->where("tgl",$date)
                    ->update([
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                    ]);

            if($in_mhu_hl)
            {
             return redirect('/mhu/form/hauling')->with('success',"Hauling Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }

    }
    public function delete_mhu_HAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_mhu.hauling")
                        ->where("tgl",$date)
                        ->update([
                                    "flag"=>'2'
                                ]);

            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Hauling Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }


    }
    public function undo_mhu_HAULING(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_mhu.hauling")->where("tgl",$date)->update(["flag"=>'1']);
            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Hauling Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
//mhuCrushing

    public function mhuCrushing(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $daily = DB::table("monitoring_mhu.crushing")->first();
        return view("mhu.form.crushing",["getUser"=>$this->user,"daily"=>$daily ]);
    }
    public function cek_cr_mhu(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($request->date)){
            $c = DB::table("monitoring_mhu.crushing")->where("tgl","<",date("Y-m-d",strtotime($request->date)))->orderBy("tgl","desc")->sum("actual_daily");
            $d=number_format($c+$request->actual_daily,3);
        }else{
            $c = DB::table("monitoring_mhu.crushing")->orderBy("tgl","desc")->first();
            if(isset($c->mtd_actual)){
            $a= $c->mtd_actual;
            }else{
                $a= 0;
            }
            $d=number_format($a+$request->actual_daily,3);
        }

        echo $d;
    }
    public function mhuCrushing_POST(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $in_mhu_hl = DB::table("monitoring_mhu.crushing")
                    ->insert([
                        "tgl"=>$date,
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                        "user_input"=>$_SESSION['username'],
                        "time_input"=>date("Y-m-d H:i:s")
                    ]);
            if($in_mhu_hl)
            {
             return redirect()->back()->with('success',"Crushing Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }
    public function edit_mhu_Crushing(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $hl_mhu = DB::table("monitoring_mhu.crushing")->where("tgl",$dataID)->orderBy("tgl","desc")->first();
        //dd($hl_mhu);
        return view("mhu.form.crushing",["getUser"=>$this->user,"daily"=>$hl_mhu,"edit"=>"true"]);
    }
    public function update_mhu_Crushing(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);
        $in_mhu_hl = DB::table("monitoring_mhu.crushing")
                    ->where("tgl",$date)
                    ->update([
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                    ]);

            if($in_mhu_hl)
            {
             return redirect('/mhu/form/crushing')->with('success',"Crushing Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }

    }
    public function delete_mhu_Crushing(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_mhu.crushing")
                        ->where("tgl",$date)
                        ->update([
                                    "flag"=>'2'
                                ]);

            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Crushing Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }


    }
    public function undo_mhu_Crushing(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_mhu.crushing")->where("tgl",$date)->update(["flag"=>'1']);
            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Crushing Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
		//plnBarging
		public function plnBarging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $daily = DB::table("monitoring_pln.barging")->first();
        return view("monitoring.form.pln",["getUser"=>$this->user,"daily"=>$daily ]);
    }
		public function cek_br_pln(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($request->date)){
            $c = DB::table("monitoring_pln.barging")->where("tgl","<",date("Y-m-d",strtotime($request->date)))->orderBy("tgl","desc")->sum("actual_daily");
            $d=number_format($c+$request->actual_daily,3);
        }else{
            $c = DB::table("monitoring_pln.barging")->orderBy("tgl","desc")->first();
            if(isset($c->mtd_actual)){
            $a= $c->mtd_actual;
            }else{
                $a= 0;
            }
            $d=number_format($a+$request->actual_daily,3);
        }

        echo $d;
    }
		public function plnBarging_POST(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $in_mhu_hl = DB::table("monitoring_pln.barging")
                    ->insert([
                        "tgl"=>$date,
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                        "user_input"=>$_SESSION['username'],
                        "time_input"=>date("Y-m-d H:i:s")
                    ]);
            if($in_mhu_hl)
            {
             return redirect()->back()->with('success',"Barging Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }
		public function edit_pln_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $hl_mhu = DB::table("monitoring_pln.barging")->where("tgl",$dataID)->orderBy("tgl","desc")->first();
        //dd($hl_mhu);
        return view("monitoring.form.pln",["getUser"=>$this->user,"daily"=>$hl_mhu,"edit"=>"true"]);
    }
    public function update_pln_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);
        $in_mhu_hl = DB::table("monitoring_pln.barging")
                    ->where("tgl",$date)
                    ->update([
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                    ]);

            if($in_mhu_hl)
            {
             return redirect('/pln/form/barging')->with('success',"Barging Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }

    }
    public function delete_pln_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_pln.barging")
                        ->where("tgl",$date)
                        ->update([
                                    "flag"=>'2'
                                ]);

            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Barging Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }


    }
    public function undo_pln_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_pln.barging")->where("tgl",$date)->update(["flag"=>'1']);
            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Barging Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }
//mhuBarging

    public function mhuBarging(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $daily = DB::table("monitoring_mhu.barging")->first();
        return view("mhu.form.barging",["getUser"=>$this->user,"daily"=>$daily ]);
    }
    public function cek_br_mhu(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($request->date)){
            $c = DB::table("monitoring_mhu.barging")->where("tgl","<",date("Y-m-d",strtotime($request->date)))->orderBy("tgl","desc")->sum("actual_daily");
            $d=number_format($c+$request->actual_daily,3);
        }else{
            $c = DB::table("monitoring_mhu.barging")->orderBy("tgl","desc")->first();
            if(isset($c->mtd_actual)){
            $a= $c->mtd_actual;
            }else{
                $a= 0;
            }
            $d=number_format($a+$request->actual_daily,3);
        }

        echo $d;
    }
    public function mhuBarging_POST(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(($request->tgl)));

        $in_mhu_hl = DB::table("monitoring_mhu.barging")
                    ->insert([
                        "tgl"=>$date,
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                        "user_input"=>$_SESSION['username'],
                        "time_input"=>date("Y-m-d H:i:s")
                    ]);
            if($in_mhu_hl)
            {
             return redirect()->back()->with('success',"Barging Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }
    }

    public function edit_mhu_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dataID= hex2bin($dataID);
        $hl_mhu = DB::table("monitoring_mhu.barging")->where("tgl",$dataID)->orderBy("tgl","desc")->first();
        //dd($hl_mhu);
        return view("mhu.form.barging",["getUser"=>$this->user,"daily"=>$hl_mhu,"edit"=>"true"]);
    }
    public function update_mhu_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $plan_daily = preg_replace('/[ ,]+/', '', $request->plan_daily);
        $actual_daily = preg_replace('/[ ,]+/', '', $request->actual_daily);
        $mtd_plan = preg_replace('/[ ,]+/', '', $request->mtd_plan);
        $mtd_actual = preg_replace('/[ ,]+/', '', $request->mtd_actual);
        $remarks = $request->keterangan;
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);
        $in_mhu_hl = DB::table("monitoring_mhu.barging")
                    ->where("tgl",$date)
                    ->update([
                        "actual_daily"=>$actual_daily,
                        "mtd_plan"=>$mtd_plan,
                        "mtd_actual"=>$mtd_actual,
                        "remark"=>$remarks,
                    ]);

            if($in_mhu_hl)
            {
             return redirect('/mhu/form/barging')->with('success',"Barging Telah Di Update");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }

    }
    public function delete_mhu_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_mhu.barging")
                        ->where("tgl",$date)
                        ->update([
                                    "flag"=>'2'
                                ]);

            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Barging Telah Di Hapus");
            }else{
                return redirect()->back()->with('failed',"Update Data Error!");
            }


    }
    public function undo_mhu_Barging(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $date = date("Y-m-d",strtotime(hex2bin($dataID)));
        //dd($date);

        $dailyPlan =  DB::table("monitoring_mhu.barging")->where("tgl",$date)->update(["flag"=>'1']);
            if($dailyPlan)
            {
             return redirect()->back()->with('success',"Barging Telah Di Kembalikan");
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }

    }

    public function intervalTime(Request $request)
    {
        $send = new EmailSend();
        $queue_vihicle = DB::table("queue_email.queue_vihicle")->where([["url_pdf",NULL],["flag",0],["tipe",NULL]])->get();
        foreach($queue_vihicle as $k => $v){
            $res = $send->Sarpas_mail($v->url,$v->email,$v->subjek,$v->noid_out);
            if($res){
                $update = DB::table("queue_email.queue_vihicle")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }

        $queue_vihicle_user = DB::table("queue_email.queue_vihicle")->where([["url_pdf","!=",NULL],["flag",0],["tipe",NULL]])->get();
        foreach($queue_vihicle_user as $k => $v){
            $res = $send->Sarpas_mail_user($v->url,$v->email,$v->subjek,$v->noid_out,$v->url_pdf);
            if($res){
                $update = DB::table("queue_email.queue_vihicle")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }
        $queue_vihicle_security = DB::table("queue_email.queue_vihicle")->where([["url_pdf",NULL],["flag",0],["tipe","security"]])->get();
        foreach($queue_vihicle_security as $k => $v){
            $res = $send->Sarpas_mail_security($v->url,$v->email,$v->subjek,$v->noid_out);
            if($res){
                $update = DB::table("queue_email.queue_vihicle")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }

        $queue_vihicle_user = DB::table("queue_email.queue_vihicle")->where([["url_pdf","!=",NULL],["flag",0],["tipe","security"]])->get();
        foreach($queue_vihicle_user as $k => $v){
            $res = $send->Sarpas_mail_security_appr($v->url,$v->email,$v->subjek,$v->noid_out,$v->url_pdf);
            if($res){
                $update = DB::table("queue_email.queue_vihicle")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => "Sarpras Noid = ".($v->noid_out),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }

        $queue_vihicle_user = DB::table("queue_email.queue_rkb")->where([["flag",0],["tipe","new"]])->get();
        foreach($queue_vihicle_user as $k => $v){
            $res = $send->ToKTT($v->subjek,$v->tipe,$v->no_rkb,$v->email);
            if($res){
                $update = DB::table("queue_email.queue_rkb")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }
        $queue_vihicle_user = DB::table("queue_email.queue_rkb")->where([["flag",0],["tipe","kabag"]])->get();
        //die($queue_vihicle_user);
        foreach($queue_vihicle_user as $k => $v){
            $res = $send->ToKTT($v->subjek,$v->tipe,$v->no_rkb,$v->email);
            // print_r($v->email);
            if($res){
                $update = DB::table("queue_email.queue_rkb")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }
        // die();

        $queue_vihicle_user = DB::table("queue_email.queue_rkb")->where([["flag",0],["tipe","ktt"]])->get();
        foreach($queue_vihicle_user as $k => $v){
            $res = $send->ToKTT($v->subjek,$v->tipe,$v->no_rkb,$v->email);
            if($res){
                $update = DB::table("queue_email.queue_rkb")->where("id",$v->id)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->no_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }
        //Email Reset

        $queue_password = DB::table("queue_email.reset_password")->where("flag",0)->get();
        foreach($queue_password as $k => $v){
            $res = $send->resetPassMail($v->subject,$v->id_user,$v->token,$v->email);
            if($res){
                $update = DB::table("queue_email.reset_password")->where("id_email",$v->id_email)->update(["flag"=>1,"date_send"=>date("Y-m-d H:i:s")]);
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->token,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }else{
                $log = DB::table("email_log")
                ->insert([
                    "email"   => $v->email,
                    "no_rkb"  => $v->token,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $res
                ]);
            }
                print_r($res);

        }

        // $this->hazardReportTenggat();
    }
    public function hazardReportTenggat()
    {
        $z=1;
        $dateNow = date("Y-m-d");
        $checkNotif = DB::table("keamanan.informasi")->where("tglIn",$dateNow)->count();
        if($checkNotif<1){
        $cekData = DB::table("hse.hazard_report_header as a")
                    ->leftJoin("hse.hazard_report_detail as b","b.uid","a.uid")
                    ->leftJoin("hse.hazard_report_validation as c","c.uid","a.uid")
                    ->leftJoin("user_login as d","d.username","a.user_input")
                    ->leftJoin("user_login as e","e.nik","b.nikPJ")
                    ->select("a.*","b.*","c.*","d.nama_lengkap as dibuat","e.nama_lengkap as dikerjakan")
                    ->whereRaw("tgl_tenggat <='".$dateNow."' and tgl_selesai IS NULL")
                    ->get();
        foreach($cekData as $k => $vA){
                // $dataUser = DB::table("keamanan.user_android as a")
                //     ->join("user_login as b","b.nik","a.nik")
                //     ->whereRaw("app = 'abpSystem' and (b.username = '".$vA->user_input."' or a.nik = '".$vA->nikPJ."' or a.nik = '18060207')")
                //     ->get();
                $dataUser = DB::table("keamanan.user_android as a")
                ->join("user_login as b","b.nik","a.nik")
                ->whereRaw("app = 'abpSystem' and (a.nik = '18060207')")
                ->get();
                foreach($dataUser as $k => $v){
                    $tenggat = date('d F Y',strtotime($vA->tgl_tenggat));
 $pesan = <<<EOT
        Perusahaan      : $vA->perusahaan \r\n,
        Bahaya          : $vA->deskripsi \r\n,
        Batas Perbaikan : $tenggat \r\n,
        Dibuat          : $vA->dibuat \r\n,
        PIC             : $vA->dikerjakan \r\n
EOT;
                    echo $v->username." : ".$v->nik." | ".$pesan." = ".$v->app."<br><br><br>";
                $dataInformasi = DB::table("keamanan.informasi")
                ->insert([
                    "nik"=>$v->nik,
                    "subjek"=>"Hazard Report Belum Selesai",
                    "pesan"=>$pesan,
                    "userIn"=>$v->username,
                    "tglIn"=>date("Y-m-d H:i:s"),
                    "phone_token"=>$v->phone_token,
                    "app"=>"abpenergy"
                ]);
                $z++;
                }
            }
        }
    }
}
