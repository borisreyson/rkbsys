<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class sysController extends Controller
{
    private $user;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
        //event(new onlineUserEvent("USER ONLINE FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
    }

    public function department(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
    	$dep = DB::table('department')
                ->leftjoin("section","section.id_dept","department.id_dept")
                ->select("department.*","section.*")
                ->where('department.id_dept',$request->dept)->get();

    	return view("page.seksi",['dep'=>$dep]);
    }
    public function expired_rkb(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $no_rkb = hex2bin($request->no_rkb);
        return view("page.expired",['no_rkb'=>$no_rkb,"expired"=>"true"]);

    }
    public function expired_send(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $expr = DB::table('e_rkb_approve')
                ->where("no_rkb",hex2bin($request->no_rkb))
                ->update([
                    "user_expired"  => $_SESSION['username'],
                    "tgl_expired"   => date("Y-m-d"),
                    "expired_remarks"   => $request->expired
                ]);
        if($expr>0){
            return redirect()->back()->with("success","Record Updated!");
        }else{
            return redirect()->back()->with("failed","Record Filed To Update!");
        }
    }
    public function print_preview(Request $request,$f_name)
    {
        
        if(!isset($_SESSION['username'])) return redirect('/');
        if(file_exists("RKB_Preview.pdf")){
            unlink("RKB_Preview.pdf");
        }

        $no_rkb = hex2bin($f_name);

        $print = DB::table("e_rkb_header")
                ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_header.no_rkb")
                ->leftjoin("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_detail.no_rkb")
                ->leftjoin("user_login","user_login.username","e_rkb_detail.user_entry")
                ->leftjoin("department","department.id_dept","e_rkb_header.dept")
                ->leftjoin("section","section.id_sect","e_rkb_header.section")
                ->select("e_rkb_approve.*","e_rkb_header.*","e_rkb_detail.*","user_login.*","department.*","section.*")
                ->where("e_rkb_header.no_rkb",($no_rkb))
                ->first();
        if($request->ext =="html"){
        return $prev =  view("print.preview" ,["Print_prev"=>$print,"no_rkb"=>$no_rkb])->render();
        }else{
        $tglIndo = $this->tgl_indo(date("Y-m-d",strtotime($print->tgl_order)));
        $header =  view("print.header" ,["Print_prev"=>$print,"no_rkb"=>($no_rkb),"tglIndo"=>$tglIndo])->render();
        $prev =  view("print.preview" ,["Print_prev"=>$print,"no_rkb"=>($no_rkb)])->render();
        }
        $footer = view("print.sign" ,["Print_prev"=>$print,"no_rkb"=>($no_rkb)])->render();
        // return $footer;
        $pdf_output = PDF::loadHTML($prev)
                        ->setPaper('a4')
                        ->setOrientation('Portrait')
                        ->setOption('margin-bottom',80)
                        ->setOption('margin-top',75)
                        ->setOption('header-html',$header)
                        ->setOption('footer-html',$footer)
                        ->output();
        
        return response($pdf_output, 200)
            ->header('Content-Disposition' , 'filename='.($f_name))
               ->header('Content-Type',"application/pdf");

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

      public function username(Request $request)
      {
        if(!isset($_SESSION['username'])) return redirect('/');

        $user = DB::table("user_login")->whereRaw("username like '%".$_GET['query']."%' or nama_lengkap like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>$value->nama_lengkap,"data"=>$value->username));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
      }
      public function get_norkb(Request $request)
      {
        if(!isset($_SESSION['username'])) return redirect('/');
        $term = $_GET['query'];
        $stockIn=null;
        $Squantity=null;
        $noRkb=null;

        $Stock =    DB::table("e_rkb_detail")
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                    ->select("e_rkb_detail.*","e_rkb_approve.*")
                    ->whereRaw("e_rkb_approve.diketahui = 1 and e_rkb_detail.no_rkb like '%".$term."%'")
                    ->get();

                    
        foreach ($Stock as $keyB => $Svalue) {
            $stokIn =   DB::table("invin_header")
                        ->join("invin_detail","invin_detail.no_rkb","invin_header.no_rkb")
                        ->select("invin_header.*","invin_detail.*")
                        ->whereRaw("invin_header.no_rkb = '".$Svalue->no_rkb."' and part_name = '".$Svalue->part_name."'")
                        ->first();
            if(isset($stokIn)){
                if($Svalue->quantity >= $stokIn->stock_in ){
                    $noRkb[] = $stokIn->no_rkb;
                }
            }else{
                $noRkb[] = $Svalue->no_rkb;
            }
        }
        

        $uniq = array_unique($noRkb);
        foreach($uniq as $value){
                $suggestions[] = array('data'=>$value,'value'=>$value);
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
        
      }
      public function getRkb(Request $request)
      {
        if(!isset($_SESSION['username'])) return redirect('/');
        $term = $_GET['query'];
        $query = DB::table("e_rkb_header")->whereRaw("no_rkb LIKE '%".$term."%' ")->get();
        foreach ($query as $k => $v)
        {
            $suggestions[] = array('data'=>$v->no_rkb,'value'=>$v->no_rkb);
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
      }
      public function get_partname(Request $request)
      {
        if(!isset($_SESSION['username'])) return redirect('/');
        $term = $_GET['query'];
        $no_rkb = $_GET['no_rkb'];
        $query = DB::table("e_rkb_detail")->whereRaw("no_rkb = '".$no_rkb."' and part_name LIKE '%".$term."%' ")->get();
        foreach ($query as $k => $v)
        {
            $suggestions[] = array('data'=>$v->part_name,'value'=>$v->part_name);
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
      }

      public function get_partnumber(Request $request)
      {
        if(!isset($_SESSION['username'])) return redirect('/');
        $term = $_GET['query'];
        $no_rkb = $_GET['no_rkb'];
        $part_name = $_GET['part_name'];
        $query = DB::table("e_rkb_detail")->whereRaw("no_rkb = '".$no_rkb."' and part_name = '".$part_name."' and part_number LIKE '%".$term."%' ")->get();
        foreach ($query as $k => $v)
        {
            $suggestions[] = array('data'=>$v->part_number,'value'=>$v->part_number);
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
      }

      public function notif_open(Request $request)
      {
        if(!isset($_SESSION['username'])) return redirect('/');
        $notifOpen = DB::table('notification')
                    ->where("user_notif",$_SESSION['username'])
                    ->whereIn("idNotif",$request->idNotif)
                    ->update([
                        "flag"=>1
                    ]);
        if($notifOpen>=0){
            return "OK";
        }else{
            return "Error";
        }

      }
      public function inbox(Request $request)
      {
        //USER INBOX
        if(!isset($_SESSION['username'])) return redirect('/');

        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->orWhere("user_to",$_SESSION['username'])
                    ->orderBy("timelog","desc")
                    ->paginate(10);

        return view('page.v2.inbox',["getUser"=>$this->user,"inbox"=>$inbox]);
      }
      public function inbox1(Request $request,$id_pesan)
      {

        if(!isset($_SESSION['username'])) return redirect('/');
        if(ctype_xdigit($id_pesan) && strlen($id_pesan) % 2 == 0) {
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->select("pesan.*","user_login.*")
                    ->orWhere("user_to",$_SESSION['username'])
                    ->orderBy("timelog","desc")
                    ->paginate(10);
        $pesan = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->orWhere("id_pesan",hex2bin($id_pesan))
                    ->first();
        $update = DB::table('pesan')->where([
                                                ["id_pesan",hex2bin($id_pesan)],
                                                ["flag_message",0]
                                            ])
                                    ->update([
                                        "flag_message"=>1
                                    ]);
        return view('page.v2.inbox',["getUser"=>$this->user,"inbox"=>$inbox,"pesan"=>$pesan,"id_pesan"=>$id_pesan]);
        }else{
            return redirect("/inbox"); 
        }
      }

      
    public function send(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $idPesan= uniqid();
        $send = DB::table('pesan')->insert([
                                            "id_pesan"  =>$idPesan,
                                            "user_from" =>$_SESSION['username'],
                                            "user_to"   =>$request->username_to,
                                            "tree"      =>$request->tree,
                                            "subjek"    =>$request->subjek,
                                            "pesan_teks"=>$request->message,
                                            "no_rkb"    =>$request->no_rkb,
                                            "part_name" =>$request->part_name,
                                            "timelog"   => date("Y-m-d H:i:s")
                                            ]);
        if($send){
            $notif = DB::table('notification')
                                ->insert([
                                    "idNotif"       =>$idPesan,
                                    "user_notif"    =>$request->username_to,
                                    "user_send"     =>$_SESSION['username'],
                                    "notif"         =>substr($request->message, 0,50),
                                    "timelog"       =>date("Y-m-d H:i:s")
                                ]);
            if($notif){
                return redirect()->back()->with("success","Message Sent!");
             }else{
                return redirect()->back()->with("failed","Failed Sent!");                
             }
        }else{
            return redirect()->back()->with("failed","Failed Sent!");

        }
    }
    public function send1(Request $request,$id_pesan)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        # code...
        $idPesan = uniqid();
        $send = DB::table('pesan')->insert([
                                            "id_pesan"  =>$idPesan,
                                            "user_from" =>$_SESSION['username'],
                                            "user_to"   => $request->username_to,
                                            "tree"      =>$request->tree,
                                            "subjek"    =>$request->subjek,
                                            "pesan_teks"=>$request->message,
                                            "no_rkb"    =>$request->no_rkb,
                                            "part_name" =>$request->part_name,
                                            "timelog"   => date("Y-m-d H:i:s")
                                            ]);
        if($send){
            $notif = DB::table('notification')
                                ->insert([
                                    "idNotif"       =>$idPesan,
                                    "user_notif"    =>$request->username_to,
                                    "user_send"     =>$_SESSION['username'],
                                    "notif"         =>substr($request->message, 0,50),
                                    "timelog"       =>date("Y-m-d H:i:s")
                                ]);
            if($notif){
                return redirect()->back()->with("success","Message Sent!");
             }else{
                return redirect()->back()->with("failed","Failed Sent!");                
             }
        }else{
            return redirect()->back()->with("failed","Failed Sent!");

        }
    }



    public function json_user(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("user_login")->whereRaw("username like '%".$_GET['query']."%' or nama_lengkap like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>$value->nama_lengkap,"data"=>$value->username));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
    public function dataItem(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("invmaster_item")->whereRaw("invmaster_item.item like '%".$_GET['query']."%' or invmaster_item.item_desc like '%".$_GET['query']."%'")
            ->leftjoin("inventory_sys","inventory_sys.item","invmaster_item.item")
            ->select("invmaster_item.*","invmaster_item.item as leftItem","inventory_sys.*")
            ->get();
           // dd($user);
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>"( ".$value->leftItem." ) ".$value->item_desc,"data"=>$value->leftItem,"satuan"=>$value->satuan,"stock"=>$value->stock_total));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
    public function dataStockItem(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("invin_detail")
            ->join("invmaster_item","invmaster_item.item","invin_detail.item")
            ->whereRaw("invin_detail.item like '%".$_GET['query']."%' or invmaster_item.item_desc = '%".$_GET['query']."%'  ")
            ->groupBy("stock_in")
            ->get();
           // dd($user);
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>"( ".$value->item." ) ".$value->item_desc,"data"=>$value->item,"satuan"=>$value->satuan,"stock"=>$value->stock_in));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
    public function dataLocation(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("inv_location")->whereRaw("code_loc like '%".$_GET['query']."%' or location like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>"( ".$value->code_loc." ) ".$value->location,"data"=>$value->code_loc));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
    public function dataSuplier(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("inv_supplier")->whereRaw("nama_supplier like '%".$_GET['query']."%' or nama_instansi like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>"( ".$value->nama_supplier." ) ".$value->nama_instansi,"data"=>$value->nama_supplier));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
    public function dataCategory(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("inv_category")->whereRaw("code_category like '%".$_GET['query']."%' or desc_category like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>"( ".$value->code_category." ) ".$value->desc_category,"data"=>$value->code_category));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
    public function dataCondition(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $user = DB::table("inv_condition")->whereRaw("code like '%".$_GET['query']."%' or code_desc like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>"( ".$value->code." ) ".$value->code_desc,"data"=>$value->code));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    } 
    public function GetRKBDetail(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
     
        $stockIn=null;
        $Squantity=null;
        $detail=null;

        $Stock = DB::table("invin_header")
                    ->join("invin_detail",function($join){
                        $join->on("invin_detail.no_rkb","invin_header.no_rkb");
                        $join->on("invin_detail.item","invin_header.item");
                    })
                    ->select("invin_header.*","invin_detail.*")
                    ->whereRaw("invin_header.no_rkb = '".$request->noRKB."'")
                    ->groupBy("part_name")
                    ->get();
        foreach ($Stock as $key => $value) {
            $sumStock = DB::table("invin_detail")->where("item",$value->item)->sum("stock_in");
            $stockIn[] = json_decode(json_encode(array("part_name"=>$value->part_name,"stockSum" => $sumStock )));
        }
        if(is_array($stockIn)){
            $len = count($stockIn);
        }else{
            $len = 0;
        }
        $Quantity = DB::table("e_rkb_detail")
                        ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                        ->select("e_rkb_detail.*","e_rkb_approve.diketahui")
                        ->whereRaw("e_rkb_approve.diketahui=1 and e_rkb_detail.no_rkb = '".$request->noRKB."'")
                        ->get();
        
        foreach ($Quantity as $Q => $vQ) {
            $Squantity[] = json_decode(json_encode(array("no_rkb"=>$vQ->no_rkb,"part_name"=>$vQ->part_name,"QuantitySum" => $vQ->quantity )));
        }

        foreach ($Squantity as $keyB => $banding) {
            if(is_array($stockIn)){
            foreach ($stockIn as $keyA => $valueA) {
                  if($banding->part_name==$valueA->part_name){
                    if($valueA->stockSum<$banding->QuantitySum){
                        $detail[] = json_decode(json_encode(array("part_name"=>$valueA->part_name,"stockSum"=>$valueA->stockSum)));
                    }
                  }else{
                    $detail[] = json_decode(json_encode(array("part_name"=>$banding->part_name))); 
                  }
              }
              }else{
                $detail[] = json_decode(json_encode(array("part_name"=>$banding->part_name))); 
              }  
            }
        
        return $detail;
    }
    public function GetRKBDetailPopUp(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $stockIn=null;
        $Squantity=null;
        $detail=null;


        if($request->part_name==NULL){

        $Stock = DB::table("invin_header")
                    ->join("invin_detail",function($join){
                        $join->on("invin_detail.no_rkb","invin_header.no_rkb");
                        $join->on("invin_detail.item","invin_header.item");
                    })
                    ->select("invin_header.*","invin_detail.*")
                    ->whereRaw("invin_header.no_rkb = '".$request->no_rkb."'")
                    ->groupBy("part_name")
                    ->get();
        foreach ($Stock as $key => $value) {
            $sumStock = DB::table("invin_detail")->where("item",$value->item)->sum("stock_in");
            $stockIn[] = json_decode(json_encode(array("part_name"=>$value->part_name,"stockSum" => $sumStock )));
        }
        if(is_array($stockIn)){
            $len = count($stockIn);
        }else{
            $len = 0;
        }
        $Quantity = DB::table("e_rkb_detail")
                        ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                        ->select("e_rkb_detail.*","e_rkb_approve.diketahui")
                        ->whereRaw("e_rkb_approve.diketahui=1 and e_rkb_detail.no_rkb = '".$request->no_rkb."'")
                        ->get();
        foreach ($Quantity as $Q => $vQ) {
            $Squantity[] = json_decode(json_encode(array("no_rkb"=>$vQ->no_rkb,"part_name"=>$vQ->part_name,"part_number"=>$vQ->part_number,"QuantitySum" => $vQ->quantity ,"quantity"=>$vQ->quantity,"user_entry"=>$vQ->user_entry)));
        }


        foreach ($Squantity as $keyB => $banding) {
            if(is_array($stockIn)){
            foreach ($stockIn as $keyA => $valueA) {
                if($banding->part_name==$valueA->part_name){
        if($valueA->stockSum<$banding->QuantitySum){
$detail[] = json_decode(json_encode(array("no_rkb"=>$banding->no_rkb,"part_name"=>$valueA->part_name,"part_number"=>$valueA->part_number,"quantity"=>$banding->quantity,"stockSum"=>$valueA->stockSum)));
        }
                }else{
$detail[] = json_decode(json_encode(array("no_rkb"=>$banding->no_rkb,"part_name"=>$banding->part_name,"part_number"=>$banding->part_number,"quantity"=>$banding->quantity,"user_entry"=>$banding->user_entry)));
                }
            }
            }else{
                $detail[] = json_decode(json_encode(array("no_rkb"=>$banding->no_rkb,"part_name"=>$banding->part_name,"part_number"=>$banding->part_number,"quantity"=>$banding->quantity,"user_entry"=>$banding->user_entry)));
            }
        } 

            return view("inventory.detPopUp",["GetRKB"=>$detail]);
        }else{
            $pName = explode(',',$request->part_name);

        $Stock = DB::table("invin_header")
                    ->join("invin_detail",function($join){
                        $join->on("invin_detail.no_rkb","invin_header.no_rkb");
                        $join->on("invin_detail.item","invin_header.item");
                    })
                    ->select("invin_header.*","invin_detail.*")
                    ->whereRaw("invin_header.no_rkb = '".$request->no_rkb."'")
                    ->whereNotIn("part_name",$pName)
                    ->groupBy("part_name")
                    ->get();
        foreach ($Stock as $key => $value) {
            $sumStock = DB::table("invin_detail")->where("item",$value->item)->sum("stock_in");
            $stockIn[] = json_decode(json_encode(array("part_name"=>$value->part_name,"stockSum" => $sumStock )));
        }
        if(is_array($stockIn)){
            $len = count($stockIn);    
        }else{
            $len = 0;
        }

        $Quantity = DB::table("e_rkb_detail")
                        ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                        ->select("e_rkb_detail.*","e_rkb_approve.diketahui")
                        ->whereRaw("e_rkb_approve.diketahui=1 and e_rkb_detail.no_rkb = '".$request->no_rkb."'")
                        ->whereNotIn("part_name",$pName)
                        ->get();
        foreach ($Quantity as $Q => $vQ) {
            $Squantity[] = json_decode(json_encode(array("no_rkb"=>$vQ->no_rkb,"part_name"=>$vQ->part_name,"part_number"=>$vQ->part_number,"QuantitySum" => $vQ->quantity ,"quantity"=>$vQ->quantity,"user_entry"=>$vQ->user_entry)));
        }
        if($Squantity!=null){
        foreach ($Squantity as $keyB => $banding) {
            if(!empty($stockIn[$keyB])){
             if($stockIn[$keyB]->stockSum<$banding->QuantitySum){
                $detail[] = json_decode(json_encode(array("no_rkb"=>$banding->no_rkb,"part_name"=>$stockIn[$keyB]->part_name,"part_number"=>$stockIn[$keyB]->part_number,"quantity"=>$banding->quantity,"stockSum"=>$stockIn[$keyB]->stockSum)));
             }else{
                if(!($banding->part_name==$stockIn[$keyB]->part_name)){
                    $detail[] = json_decode(json_encode(array("no_rkb"=>$banding->no_rkb,"part_name"=>$banding->part_name,"part_number"=>$banding->part_number,"quantity"=>$banding->quantity,"user_entry"=>$banding->user_entry)));
                     }
                 }
              }
            }
        }else{
            $detail = 0;
        }

            return view("inventory.detPopUp",["GetRKB"=>$detail]);
        }
    }
    public function GetRKBDetailPopUpAll(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $arrNORKB = [];
        $rkbItem = DB::table("e_rkb_approve")
                    ->join("e_rkb_header",function($join){
                        $join->on("e_rkb_header.no_rkb","e_rkb_approve.no_rkb");
                    })
                    ->select("e_rkb_approve.*","e_rkb_header.*")
                    ->whereRaw("user_close !='SYSTEM' and diketahui= 1 and e_rkb_approve.no_rkb like '%".$request->no_rkb."%'")
                    ->get();
        $statusItem = DB::table("e_rkb_approve")
                    ->join("item_status",function($join){
                        $join->on("item_status.no_rkb","e_rkb_approve.no_rkb");
                    })
                    ->select("e_rkb_approve.*","item_status.*")
                    ->whereRaw("diketahui= 1 and e_rkb_approve.no_rkb like '%".$request->no_rkb."%'")
                    ->get();
            foreach ($rkbItem as $key => $value) {
                $arrNORKB[]=$value->no_rkb;
            }
            dd($rkbItem);
            return view("inventory.detPopUp",["GetRKB"=>$detail,"popAll"=>"OK"]);
        
    }
    public function sent(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $sent = DB::table("pesan")
                ->where("user_from",$_SESSION['username'])
                ->join("user_login","user_login.username","pesan.user_to")
                ->paginate(10);
        
        return view('page.v2.sent',["getUser"=>$this->user,"sent"=>$sent]);
    }
    public function sentOpen(Request $request,$id_pesan)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(ctype_xdigit($id_pesan) && strlen($id_pesan) % 2 == 0) {
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_to")
                    ->select("pesan.*","user_login.*")
                    ->orWhere("user_from",$_SESSION['username'])
                    ->orderBy("timelog","desc")
                    ->paginate(10);
        $pesan = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_to")
                    ->orWhere("id_pesan",hex2bin($id_pesan))
                    ->first();
        $update = DB::table('pesan')->where([
                                                ["id_pesan",hex2bin($id_pesan)],
                                                ["flag_message",0]
                                            ])
                                    ->update([
                                        "flag_message"=>1
                                    ]);
        return view('page.v2.sent',["getUser"=>$this->user,"sent"=>$inbox,"pesan"=>$pesan,"id_pesan"=>$id_pesan]);
        }else{
            return redirect("/inbox"); 
        }
    }
    public function sessionUser(Request $request)
    {
        if(isset($_SESSION['username'])){
            echo $_SESSION['username'];
        }else{
            echo "null";
        }
    }
    public function sessionDel($value='')
    {
        session_destroy();
        return redirect("/"); 
    }
    public function importForm(Request $request)
    {
        return view('page.admin.import');
    }
    public function importPost(Request $request)
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

        foreach($sheetData as $k => $v){
            foreach($v as $k1 => $v1){
                if($v1!=""){
                    if($k==1){
                    }else{    
                    /*      
                    $plan_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['B']);
                    $actual_daily=preg_replace('/[ ,]+/', '', $sheetData[$k]['C']);
                    $mtd_plan=preg_replace('/[ ,]+/', '', $sheetData[$k]['D']);
                    $mtd_actual=preg_replace('/[ ,]+/', '', $sheetData[$k]['E']);
                    $remark=$sheetData[$k]['F']; 
                    */
                        $cek=DB::table("monitoring_mhu.hauling")->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))->count();
                        
                        if($cek==0){
                            
                        $in = DB::table("monitoring_mhu.hauling")->insert([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['B']),
                                                            "actual_daily"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['C']),
                                                            "mtd_plan"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['D']),
                                                            "mtd_actual"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['E']),
                                                            "remark"=>$sheetData[$k]['F'],
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]);

                        }else{
                                $update = DB::table("monitoring_mhu.hauling")
                                                    ->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['B']),
                                                            "actual_daily"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['C']),
                                                            "mtd_plan"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['D']),
                                                            "mtd_actual"=>preg_replace('/[ ,]+/', '', $sheetData[$k]['E']),
                                                            "remark"=>$sheetData[$k]['F'],
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]); 
                                   
                        }
                        
                    }
                }
            }
            
        }

        unlink($file);
        return redirect()->back();
        
    }
}