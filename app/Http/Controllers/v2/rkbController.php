<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Illuminate\Support\Facades\Response;
use App\pictures;

class rkbController extends Controller
{
    //
    private $user;
    public $IP;
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
    public function close_rkb(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        return view("page.v2.modal",["no_rkb"=>hex2bin($request->no_rkb),"close_rkb_po"=>"NO_PO"]);
    }
    public function close_rkb_cancel(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        return view("page.v2.modal",["no_rkb"=>hex2bin($request->no_rkb),"close_rkb_cancel"=>"close_rkb_cancel"]);
    }
    public function close_rkb_cancel_put(Request $request)
    {
        # code...
        if(isset($_SESSION['username'])=="") return redirect('/');
        $close = DB::table("e_rkb_header")->where("no_rkb",$request->no_rkb)->update(["user_close"=>$_SESSION['username']]);
        if($close>0){
            return redirect()->back()->with("success","RKB Telah Di Close!");
        }else{

            return redirect()->back()->with("failed","Close RKB Gagal!");
        }
    }

    public function close_rkb_send(Request $request)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
    	   $cek =DB::table("e_rkb_po")
                            ->where([
                                ["no_rkb",$request->no_rkb],
                                ["item",$request->item]
                            ])->count();
            if($cek>0)
            {
                $set_close = DB::table("e_rkb_po")
                            ->where([
                                ["no_rkb",$request->no_rkb],
                                ["item",$request->item]
                            ])
                            ->update([
                                "keterangan"     => $request->keterangan,
                                "no_po"     => $request->no_po,
                                "time_input"=> date("Y-m-d H:i:s"),
                                "user_input"=> $_SESSION['username']
                            ]);
            }else{
                $set_close = DB::table("e_rkb_po")
                            ->insert([
                                "no_rkb"    =>$request->no_rkb,
                                "item"      =>$request->item,
                                "keterangan"=> $request->keterangan,
                                "no_po"     => $request->no_po,
                                "time_input"=> date("Y-m-d H:i:s"),
                                "user_input"=> $_SESSION['username']
                            ]);
            }
                            
            if($request->total_item==1){
                $set_close = DB::table("e_rkb_header")
                            ->where([
                                ["no_rkb",$request->no_rkb]
                            ])
                            ->update([
                                "status"     => "Semua RKB Telah Close",
                                "time_status"=> date("Y-m-d H:i:s"),
                                "user_close"=> $_SESSION['username']
                            ]);
               echo "refresh";
            }

    }
    public function export(Request $request)
    {
        $filename = "website_data_" . date('Ymd') . ".xls";
        //header("Content-Disposition: attachment; filename=\"$filename\"");
        //header("Content-Type: application/vnd.ms-excel");

        $tmp = view("page.v2.xls")->render();
        response($tmp, 200)
            ->header('Content-Disposition' , 'filename='.($filename))
               ->header('Content-Type',"application/vnd.ms-excel");
    }
    public function rkb(Request $request) 
    {

        if(!isset($_SESSION['username'])) return redirect('/');
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
        if($_SESSION['section']=="KABAG"){
                return redirect("/kabag/rkb");
            if(isset($request->seksi)){
                $rkb = $query->where([
                    ['e_rkb_header.dept',$_SESSION['department']],
                    ["e_rkb_header.section",$request->seksi]
                ])->paginate(9); 
            }else{
                $rkb = $query->where([
                        ['e_rkb_header.dept',$_SESSION['department']]
                        ])
                        ->paginate(9); 
            }
            }elseif($_SESSION['section']=="PURCHASING"){
                
                if(isset($request->dep)){
                    if(isset($request->seksi)){
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                    ['e_rkb_header.section',$request->seksi]
                ])->paginate(9); 
                    }else{
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                ])->paginate(9); 
                    }
                }else{
                    $rkb = $query->paginate(9); 
                }
            return view('page.logistic.reportRkb',["rkb"=>$rkb,"getUser"=>$this->user]);   
            }elseif($_SESSION['section']=="KTT"){
                return redirect("/ktt/rkb");
                if(isset($request->dep)){
                    if(isset($request->seksi)){
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                    ['e_rkb_header.section',$request->seksi]
                ])->paginate(9); 
                    }else{
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                ])->paginate(9); 
                    }
                }else{
                    $rkb = $query->paginate(9); 
                }
                $kabag = DB::table('user_login')->where("section","KABAG")->get();
        return view('page.ktt.reportRkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]); 

        }elseif($_SESSION['level']=="administrator"){
                return redirect("/admin/rkb");
                
                    $rkb = $query->paginate(9); 
                
                $kabag = DB::table('user_login')->where("section","KABAG")->get();
        return view('page.admin',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]);   

            }else{
                $rkb = $query->where([
                        ['e_rkb_header.dept',$_SESSION['department']]
                        ])
                        ->paginate(9); 
            }
            
                if(isset($_GET['android'])){
                    return $rkb;
                }
        return view('page.user.rkb',["rkb"=>$rkb,"getUser"=>$this->user]);
    }
    public function form_upload(Request $request) 
    {
        if(isset($_SESSION['username'])=="") return redirect('/');

        $rkb = DB::table('e_rkb_detail')->where([
                        ["no_rkb",hex2bin($request->no_rkb)],
                        ["part_name",hex2bin($request->part_name)]
                    ])->first();
        return view('page.v2.modal',["rkb_det"=>$rkb,"no_rkb"=>hex2bin($request->no_rkb),"parent_eq"=>$request->parent_eq,"upload"=>"OK"]);
    }
}
