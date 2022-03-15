<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;

class poController extends Controller
{
    //
    private $user;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
        //event(new onlineUserEvent("USER ONLINE FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
    }
    public function ConvertRkb(Request $request,$no_rkb)
    {
    	if(!isset($_SESSION['username'])) return redirect('/');
    	if($_SESSION['section']=="PURCHASING"){
                $rkb = DB::table('e_rkb_header')
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_header.no_rkb")
                    ->select("e_rkb_header.*","e_rkb_approve.*")
                    ->where("e_rkb_header.no_rkb",hex2bin($no_rkb))
                    ->orderBy("e_rkb_header.no_rkb","desc")
                    ->get();
        return view('page.logistic.convertRkb',["rkb"=>$rkb,"getUser"=>$this->user,"no_rkb"=>$no_rkb]);  
            }
    }
    public function printRkb(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        
        if(isset($request->startDate) && isset($request->endDate)){
            $startDate = date("Y-m-d H:i:s",strtotime($request->startDate));
            $endDate = date("Y-m-d H:i:s",strtotime($request->endDate.date(' H:i:s')));
        }else{            
            $startDate = date("Y-m-d H:i:s",strtotime('-24hour'));
            $endDate = date("Y-m-d H:i:s");
        } 
        $query = DB::table('e_rkb_detail')
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("e_rkb_header","e_rkb_header.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("department","e_rkb_header.dept","=","department.id_dept")
                    ->join("section","e_rkb_header.section","=","section.id_sect")
                    ->leftJoin("e_rkb_cancel",function($join){
                        $join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb");   
                        $join->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");   
                    })
                    ->select("e_rkb_header.*","section.sect as det_sect","department.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section");


        if($request->USER=="KABAG"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section = 'KABAG' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.disetujui = '0' and ( e_rkb_approve.cancel_section IS NULL and e_rkb_cancel.cancel_by_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.disetujui = '1' and ( e_rkb_approve.cancel_section !='KABAG' or e_rkb_cancel.cancel_by_section IS NULL  )");

        }
        }

       else if($request->USER=="KTT"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.cancel_section = 'KTT' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.diketahui = '0' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.diketahui = '1' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ) ");
        }
        }else{

        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section IS NOT NULL or e_rkb_cancel.cancel_by_section IS NOT NULL)");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.disetujui = '0' or e_rkb_approve.diketahui = '0') and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL )");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and e_rkb_cancel.cancel_by_section IS NULL and (e_rkb_approve.disetujui = '1' or e_rkb_approve.diketahui = '1')");
        }else{

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')");   
        }

        }


        
        
        $row = $rkb->orderBy("e_rkb_header.no_rkb","desc")
                    ->get();
        return view('page.logistic.printRkb',["rkb"=>$row,"getUser"=>$this->user]);
    }
    public function rkbPrint(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        
        if(isset($request->startDate) && isset($request->endDate)){
            $startDate = date("Y-m-d H:i:s",strtotime($request->startDate));
            $endDate = date("Y-m-d H:i:s",strtotime($request->endDate.date(' H:i:s')));
        }else{            
            $startDate = date("Y-m-d H:i:s",strtotime('-1month'));
            $endDate = date("Y-m-d H:i:s");
        } 
        $query = DB::table('e_rkb_detail')
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("e_rkb_header","e_rkb_header.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("department","e_rkb_header.dept","=","department.id_dept")
                    ->join("section","e_rkb_header.section","=","section.id_sect")
                    ->leftJoin("e_rkb_cancel",function($join){
                        $join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb");   
                        $join->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");   
                    })
                    ->select("e_rkb_header.*","section.sect as det_sect","department.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section");


        if($request->USER=="KABAG"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section = 'KABAG' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.disetujui = '0' and ( e_rkb_approve.cancel_section IS NULL and e_rkb_cancel.cancel_by_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.disetujui = '1' and ( e_rkb_approve.cancel_section !='KABAG' or e_rkb_cancel.cancel_by_section IS NULL  )");

        }
        }

       else if($request->USER=="KTT"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.cancel_section = 'KTT' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.diketahui = '0' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.diketahui = '1' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ) ");
        }
        }else{

        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section IS NOT NULL or e_rkb_cancel.cancel_by_section IS NOT NULL)");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.disetujui = '0' or e_rkb_approve.diketahui = '0') and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL )");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and e_rkb_cancel.cancel_by_section IS NULL and (e_rkb_approve.disetujui = '1' or e_rkb_approve.diketahui = '1')");
        }else{

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')");   
        }

        }


        
        
        $row = $rkb->orderBy("e_rkb_header.no_rkb","desc")
                    ->get();

        return view('page.logistic.rkbPrint',["rkb"=>$row,"getUser"=>$this->user]);
    }
    public function upload_penawaran(Request $request,$no_rkb,$part_name)
    {
       
        if(isset($_SESSION['username'])=="") return redirect('/');
       $gdata = DB::table('e_rkb_detail')->where([
        ['no_rkb',hex2bin($no_rkb)],
        ['part_name',hex2bin($part_name)]
        ])->first();
       return view('page.logistic.upload_penawaran',["getUser"=>$this->user,"gdata"=>$gdata]);
    }
    public function post_penawaran(Request $request,$no_rkb,$part_name)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $timelog = date('Y-m-d H:i:s');
        $z=0;
        if($request->hasfile('penawaran')){
                foreach($request->file('penawaran') as $k => $v){
                    $filename = "penawaran-".hex2bin($part_name)."_".uniqid().".".$v->getClientOriginalExtension();
                    $fileTemp = $v;
                    $size = $v->getClientSize();
                   $file_up = DB::table('e_rkb_penawaran')->insert([
                    "no_rkb"        =>hex2bin($no_rkb),
                    "part_name"     =>hex2bin($part_name),
                    "file"          => $filename,
                    "user_entry"    =>$_SESSION['username'],
                    "timelog"       =>$timelog
                    ]);
                      $destinationPath = '/penawaran';
                      $v->storeAs($destinationPath,$filename);
                   $z++;
                }
                if(count($request->file('penawaran'))==$z){
                    return redirect()->back()->with('success','Upload penawaran berhasil!');
                }else{
                    return redirect()->back()->with('success','Upload penawaran berhasil!');
                }
            }else{
                return redirect()->back()->with('success','Upload penawaran berhasil!');
            }
    }
    public function replace(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $f_name = hex2bin($request->f_name);
        $file = DB::table('e_rkb_penawaran')->where('file',$f_name)->first();
        return view('page.logistic.modal',['f_name'=>$file,"replace"=>"ok"]);
       
    }
    public function replace_send(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $timelog = date('Y-m-d H:i:s');
        $filename=$request->file_name;
        if($request->hasfile('penawaran')){
            $v=$request->file('penawaran');
            $file_up = DB::table('e_rkb_penawaran')->insert([
                    "file"          => $filename,
                    "user_entry"    =>$_SESSION['username'],
                    "timelog"       =>$timelog
                    ]);
                      $destinationPath = '/penawaran';
                      $v->storeAs($destinationPath,$filename);
              if($file_up){
                 return redirect()->back()->with('success','Upload penawaran berhasil!');
             }else{
                 return redirect()->back()->with('success','Upload penawaran berhasil!');
             }
        }
    }
    public function delete_penawaran(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $f_name = hex2bin($request->f_name);
         $delete = DB::table('e_rkb_penawaran')->where('file',$f_name)->delete();
         if($delete){
            return "OK";
         }else{
            return "ERROR" ;
         }
    }
    public function edit_qty(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $rkb = DB::table('e_rkb_detail')->where("no_rkb",$request->no_rkb)->get();
        $satuan = DB::table('satuan')->get();
        return view('page.logistic.modal',["rkb"=>$rkb,"ID"=>$request->no_rkb,"satuan"=>$satuan,"qty"=>"ok"]);
    }
    public function update_qty(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');      
        foreach ($request->part_name as $key => $value) {
          $his = DB::table("e_rkb_history")->insert([
                                                    "no_rkb"        =>$request->no_rkb,
                                                    "part_name"     =>$value,
                                                    "part_number"   => $request->part_number[$key],
                                                    "old_qty"       => $request->old_qty[$key],
                                                    "old_satuan"    => $request->old_satuan[$key],
                                                    "remarks"       => $request->remarks[$key],
                                                    "quantity"      => $request->qty[$key],
                                                    "satuan"        => $request->satuan[$key],
                                                    "due_date"      => $request->due_date[$key],
                                                    "user_entry"    => $request->user_entry[$key],
                                                    "timelog"       => $request->timelog[$key],
                                                    "user_update"   => $_SESSION['username'],
                                                    "remark_update" => "Update Quantity & Satuan",
                                                    "tgl_update"    => date("Y-m-d H:i:s")
                                                    ]);
          if($his){
                $update_qty = DB::table('e_rkb_detail')->where([
                                                                ["no_rkb" , $request->no_rkb],
                                                                ["part_name" , $value],
                                                                ["part_number",$request->part_number[$key]]
                                                              ])
                                ->update([
                                    "quantity"   => $request->qty[$key],
                                    "satuan"   => $request->satuan[$key]
                                ]);
                                if($update_qty){
                                    return redirect()->back()->with("success","Update Quantity Success!");
                                }
                }
      }         
    }
}
