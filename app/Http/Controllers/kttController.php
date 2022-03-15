<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;

class kttController extends Controller
{
	//
    private $user;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
        //event(new onlineUserEvent("USER ONLINE",$_SESSION['username']));
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
                    ->leftJoin("e_rkb_cancel",function($join){
                        $join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb");   
                        $join->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");   
                    })
                    ->select("e_rkb_header.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section");


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
        return view('page.ktt.printRkb',["rkb"=>$row,"getUser"=>$this->user]);
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
                    ->leftJoin("e_rkb_cancel",function($join){
                        $join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb");   
                        $join->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");   
                    })
                    ->select("e_rkb_header.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section");


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

        return view('page.ktt.rkbPrint',["rkb"=>$row,"getUser"=>$this->user]);
    }
    public function rkb(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['section']!="KTT") return redirect('/');
        if(isset($request->expired)){
            $expired = "IS NOT NULL";
        }else{
            $expired = "IS NULL";
        }
        if(isset($request->close_rkb)){
            $close_rkb = "IS NOT NULL";
        }else{
            $close_rkb = "IS NULL";
        }
            $sql = DB::table('e_rkb_header') 
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_header.no_rkb")
                    ->leftJoin("department","department.id_dept","e_rkb_header.dept")
                    ->leftJoin("section","section.id_sect","e_rkb_header.section")
                    ->leftJoin("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
                    ->leftJoin("user_login","user_login.username","e_rkb_detail.user_entry")
                    ->select("e_rkb_header.*","e_rkb_header.status as myStatus","e_rkb_approve.*","department.*","section.*","e_rkb_detail.*","user_login.*")
                    ->orderBy("e_rkb_detail.timelog","desc")
                    ->groupBy("e_rkb_detail.no_rkb")
                    ->whereRaw("user_expired ".$expired)
                    ->whereRaw("user_close ".$close_rkb);
            if($request->search){
            $filter = $sql->whereRaw("(e_rkb_header.no_rkb like '%".$request->search."%' or e_rkb_header.dept like '%".$request->search."%' or e_rkb_header.section like '%".$request->search."%' or e_rkb_header.tgl_order like '%".$request->search."%')");
            }else{
            $filter = $sql;
            }
            if(isset($request->disetujui)){
                if(isset($request->approve)){
                    $query = $filter->whereRaw("e_rkb_approve.disetujui ='".$request->disetujui."' or (e_rkb_approve.disetujui ='0' or e_rkb_approve.diketahui ='0')");
                }else{
                    $query = $filter->whereRaw("e_rkb_approve.disetujui ='".$request->disetujui."'");
                }
                }else if(isset($request->diketahui)){
                    $query = $filter->whereRaw("e_rkb_approve.diketahui ='".$request->diketahui."'");
                }else if(isset($request->approve)){
                    $query = $filter->whereRaw("(e_rkb_approve.disetujui =0 or e_rkb_approve.diketahui =0) and cancel_section IS NULL ");
                }else if(isset($request->cancel)){
                    $query = $filter->whereRaw("cancel_section IS NOT NULL");
                }else{
                    $query = $filter;
                }

                if(isset($request->dep)){
                    if(isset($request->seksi)){
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                    ['e_rkb_header.section',$request->seksi]
                ])->paginate(10); 
                    }else{
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                ])->paginate(10); 
                    }
                }else{
                    $rkb = $query->paginate(12); 
                }
                $kabag = DB::table('user_login')->where("section","KABAG")->get();
        return view('page.ktt.v2.rkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]); 
    }
        public function inbox(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->orWhere("user_to",$_SESSION['username'])
                    ->orderBy("timelog","desc")
                    ->paginate(5);
                    //dd($inbox);
        return view('page.ktt.v2.inbox',["getUser"=>$this->user,"inbox"=>$inbox]);
    }
    public function message(Request $request,$id_pesan)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(ctype_xdigit($id_pesan) && strlen($id_pesan) % 2 == 0) {
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->select("pesan.*","user_login.*")
                    ->orWhere("user_to",$_SESSION['username'])
                    ->orderBy("timelog","desc")
                    ->paginate(5);
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
        return view('page.ktt.v2.inbox',["getUser"=>$this->user,"inbox"=>$inbox,"pesan"=>$pesan,"id_pesan"=>$id_pesan]);
        }else{
            return redirect("/ktt/inbox"); 
        }
    }
    public function send(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $idPesan = uniqid();
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
                                    "idNotif"      =>$idPesan,
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
        $idPesan=uniqid();
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
    public function kttSarpras(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->leftjoin("user_login","user_login.nik","vihicle.v_out_h.nik")
                ->select("vihicle.v_out_h.*","vihicle.v_out_h.tanggal_entry as entry_keluar","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*","user_login.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->groupBy("vihicle.v_out_h.tanggal_entry");
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1;
        }
             $K_M=$filter->paginate(10);
             //dd($K_M);
        return view('sarana.kabag.report',["getUser"=>$this->user,"K_M"=>$K_M]);
    }
}
