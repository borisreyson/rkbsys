<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use App\EmailSend;

class mhuController extends Controller
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
        $db     = config('database.db_manual2');


    	$conn = mysqli_connect($host,$user,$password,$db);

    	if (mysqli_connect_errno())
		  {
		  return "Failed to connect to MySQL: " . mysqli_connect_error();
		  }else{
		   return $conn;
		  }
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
        return view("mhu.hauling",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
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
        //dd($data);
        $LABEL = array('ACTUAL MONTHLY','PLAN MONTHLY');
        return view("mhu.hauling",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
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
        return view("mhu.achHAUL",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
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
        return view("mhu.crushing",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
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
        return view("mhu.crushing",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
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
        return view("mhu.achCRUSH",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
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
        return view("mhu.barging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL]);
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
        return view("mhu.barging",["data" => $data,"dataY"=>$dataY,"montH"=>$montH,"getUser"=>$this->user,"LABEL"=>$LABEL,"bulanan"=>"OK"]);
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
        return view("mhu.achBARGE",["data" => $dataX,"data_Y"=>$data,"dataY"=>$dataY,"montH"=>$month,"getUser"=>$this->user,"LABEL"=>$LABEL,"ach"=>"OK","LABEL_M"=>$LABEL_M]);
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

    public function formBOAT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

        return view("mhu.form.boat",["getUser"=>$this->user,"konek"=>$konek]);
    }
    public function postBOAT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $tonase = preg_replace('/[ ,]+/', '', $request->tonase);
            $insert = DB::table("monitoring_mhu.barge_boat")
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

        return view("mhu.form.boat",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateBOAT(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $tonase = preg_replace('/[ ,]+/', '', $request->tonase);

            $OB = DB::table("monitoring_mhu.barge_boat")
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
             return redirect('/mhu/form/boat')->with('success',"Boat Telah Di Update");   
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
             return redirect('/mhu/form/boat')->with('success',"Boat Telah Di Hapus!");   
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
             return redirect('/mhu/form/boat')->with('success',"Boat Telah Di Kembalikan!");   
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }
        
    }
    public function formSTOCK(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();

        return view("mhu.form.stock",["getUser"=>$this->user,"konek"=>$konek]);
    }
    public function postSTOCK(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $check = DB::table("monitoring_mhu.stock")->where('tgl',date("Y-m-d",strtotime($request->tgl)))->count();
        if($check>0){            
                return redirect()->back()->with("failed","Harap Memeriksa Tanggal!");
        }
        $stock_rom = preg_replace('/[ ,]+/', '', $request->stock_rom);
        $stock_product = preg_replace('/[ ,]+/', '', $request->stock_product);
            $insert = DB::table("monitoring_mhu.stock")
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

        return view("mhu.form.stock",["getUser"=>$this->user,"daily"=>$row,"konek"=>$konek,"edit"=>"true"]);
    }
    public function updateSTOCK(Request $request,$dataID)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $konek = $this->Connection();
        $stock_rom = preg_replace('/[ ,]+/', '', $request->stock_rom);
        $stock_product = preg_replace('/[ ,]+/', '', $request->stock_product);

            $OB = DB::table("monitoring_mhu.stock")
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
             return redirect('/mhu/form/stock')->with('success',"Stock Telah Di Update");   
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
             return redirect('/mhu/form/stock')->with('success',"Stock Telah Di Hapus!");   
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
             return redirect('/mhu/form/stock')->with('success',"Stock Telah Di Kembalikan!");   
            }else{
                return redirect()->back()->with('failed',"Undo Data Error!");
            }
        
    }
}
