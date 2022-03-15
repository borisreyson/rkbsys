<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;

class logisticController extends Controller
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

    public function rkb(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['section']!="PURCHASING") return redirect('/');
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
                    // ->whereRaw("e_rkb_header.dept !='mtk'");
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
        return view('page.logistic.rkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]); 
    }
    public function rkb_mtk(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['department']!="mtk") return redirect('/');
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
                    ->whereRaw("user_close ".$close_rkb)
                    ->whereRaw("e_rkb_header.dept ='mtk'");
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
        return view('page.logistik_mtk.rkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]); 
    }
    public function detail_rkb(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $rkb = DB::table('e_rkb_header')->where([
            ["e_rkb_header.no_rkb",$request->no_rkb]
        ])
        ->join("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
        ->select("e_rkb_header.no_rkb","e_rkb_detail.*")
        ->orderBy("timelog","asc")
        ->get();
        
        return view('page.logistic.modal',["rkb_det"=>$rkb,"no_rkb"=>$request->no_rkb,"detail_rkb"=>"OK"]);
    }
    public function close_item(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');

        return view('page.logistic.modal',["request"=>$request,"close_form"=>"OK"]);
    }
    public function item_close(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        //dd($request->timelog);
        $closeItem = DB::table("item_status")
                    ->insert([
                        "id_status"     => uniqid(),
                        "no_rkb"        => $request->no_rkb,
                        "part_name"     => $request->part_name,
                        "part_number"   => $request->part_number,
                        "quantity"   => $request->quantity,
                        "satuan"   => $request->satuan,
                        "remarks"   => $request->remarks,
                        "timelog"   => date("Y-m-d H:i:s",strtotime($request->timelog)),
                        "close_remark"   => $request->close_remarks
                    ]);
        if($closeItem){
            return redirect()->back()->with("success","Close Item Success!");
        }else{
            return redirect()->back()->with("success","Close Item Failed!");
        }
    }
    public function update_status(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');

        return view('page.logistic.modal',["request"=>$request,"update_status"=>"OK"]);
    }
    public function update_send(Request $request)
    {if(isset($_SESSION['username'])=="") return redirect('/');
        //dd($request->timelog);

        $closeUpdate = DB::table("item_status")->where("id_status",$request->id_status)->update(['void'=>1]);
        if($closeUpdate){
                $closeItem = DB::table("item_status")
                            ->insert([
                                "id_status"     => uniqid(),
                                "no_rkb"        => $request->no_rkb,
                                "part_name"     => $request->part_name,
                                "part_number"   => $request->part_number,
                                "quantity"   => $request->quantity,
                                "satuan"   => $request->satuan,
                                "remarks"   => $request->remarks,
                                "timelog"   => date("Y-m-d H:i:s",strtotime($request->timelog)),
                                "close_remark"   => $request->close_remark
                            ]);
            if($closeItem)
            {
                return redirect()->back()->with("success","Update Close Item Success!");
            }else{
                return redirect()->back()->with("success","Update Close Item Failed!");
            }
        }else{
            return redirect()->back()->with("success","Update Close Item Failed!");
        }

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
        return view('page.kabag.v2.inbox',["getUser"=>$this->user,"inbox"=>$inbox]);
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
        return view('page.kabag.v2.inbox',["getUser"=>$this->user,"inbox"=>$inbox,"pesan"=>$pesan,"id_pesan"=>$id_pesan]);
        }else{
            return redirect("/kabag/inbox"); 
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
    public function adjustStock(Request $request)
    {
     if(!isset($_SESSION['username'])) return redirect('/');
       return view('page.logistic.adjust',["getUser"=>$this->user]);
    }
    public function cariItem(Request $request)
    {
     if(!isset($_SESSION['username'])) return redirect('/');
     
         $user = DB::table("inventory_sys")
                ->join("invmaster_item","invmaster_item.item","inventory_sys.item")
                ->select("inventory_sys.*","invmaster_item.*")
                ->whereRaw("inventory_sys.item like '%".$_GET['query']."%' or invmaster_item.item_desc like '%".$_GET['query']."%'")->get();
        foreach ($user as $key => $value) {
            $suggestions[] = (array("value"=>$value->item."  ( ".$value->item_desc." )","item"=>$value->item,"stockTot"=>$value->stock_total));
        }
        $data = json_encode(array(
                                "query"=>"Unit",
                                "suggestions"=>$suggestions
                             ));
        return $data;
    }
}
