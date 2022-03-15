<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;

class adminController extends Controller
{
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
        if($_SESSION['level']!="administrator") return redirect('/');
        if(isset($request->expired)){
            $expired = "IS NOT NULL";
        }else{
            $expired = "IS NULL";
        }
        if(isset($request->close_rkb)){
            $close_rkb = "IS NOT NULL";
        }else{
            if(isset($request->cancel)){
                $close_rkb = "IS NOT NULL";
            }else{
                $close_rkb = "IS NULL";
            }
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

                if(isset($_GET['android'])==="administrator"){
                    return $rkb;
                }
                $kabag = DB::table('user_login')->where("section","KABAG")->get();
        return view('page.admin.rkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]);
    }
    public function rkbAdmin(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($_SESSION['level']!="administrator") return redirect('/');
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
                    ->orderBy("e_rkb_header.no_rkb","desc")
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
                $kabag = DB::table('user_login')->whereRaw("status='0' and (section='KABAG' or section='SECTION_HEAD') ")->get();
          return view('page.admin',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]);
    }
    public function ttd(Request $request,$f_name)
    {

         if(!isset($_SESSION['username'])) return redirect('/');
         $img_name= hex2bin($f_name);
        $get = Storage::disk('ttd')->get($img_name);

         $imgExt =  explode('.',$img_name);
         $Ext = end($imgExt);
        if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg"){
            return Response::make($get, 200)
                  ->header('Content-Disposition' , 'filename='.$img_name)
                  ->header('Content-Type', "image/jpg,image/png");
        }
    }

    public function inbox(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->orWhere("user_to",$_SESSION['username'])
                    ->orderBy("timelog","desc")
                    ->paginate(10);
                    //dd($inbox);
        return view('page.admin.inbox',["getUser"=>$this->user,"inbox"=>$inbox]);
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
        return view('page.admin.inbox',["getUser"=>$this->user,"inbox"=>$inbox,"pesan"=>$pesan,"id_pesan"=>$id_pesan]);
        }else{
            return redirect("/admin/inbox");
        }
    }
    public function send(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $send = DB::table('pesan')->insert([
                                            "id_pesan"  =>uniqid(),
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
                                    "idNotif"      =>uniqid(),
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
        $send = DB::table('pesan')->insert([
                                            "id_pesan"  =>uniqid(),
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
                                    "idNotif"      =>uniqid(),
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
    public function AllInbox(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->orderBy("timelog","desc")
                    ->paginate(10);
                    //dd($inbox);
        return view('page.admin.AllMessage',["getUser"=>$this->user,"inbox"=>$inbox]);
    }

    public function AllMessage(Request $request,$id_pesan)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(ctype_xdigit($id_pesan) && strlen($id_pesan) % 2 == 0) {
        $inbox = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->select("pesan.*","user_login.*")
                    ->orderBy("timelog","desc")
                    ->paginate(10);
        $pesan = DB::table('pesan')
                    ->leftJoin("user_login","user_login.username","pesan.user_from")
                    ->orWhere("id_pesan",hex2bin($id_pesan))
                    ->first();
/*
        $update = DB::table('pesan')->where([
                                                ["id_pesan",hex2bin($id_pesan)],
                                                ["flag_message",0]
                                            ])
                                    ->update([
                                        "flag_message"=>1
                                    ]);
*/
        return view('page.admin.AllMessage',["getUser"=>$this->user,"inbox"=>$inbox,"pesan"=>$pesan,"id_pesan"=>$id_pesan]);
        }else{
            return redirect("/admin/inbox");
        }
    }

    public function AllRKB(Request $request)
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
            $sql1 = DB::table('e_rkb_header')
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
            if(isset($request->keyDept)){
                $sql = $sql1->whereRaw("e_rkb_header.dept ='".$request->keyDept."'");
            }else{
                $sql = $sql1;
            }

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
        return view('page.rkbAll',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]);
    }

public function mtkRKB(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $request->keyDept="mtk";
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
            $sql1 = DB::table('e_rkb_header')
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
            if(isset($request->keyDept)){
                $sql = $sql1->whereRaw("e_rkb_header.dept ='".$request->keyDept."'");
            }else{
                $sql = $sql1;
            }

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
        return view('page.rkbAll',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]);
    }

    public function rkbPrint(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
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
                    ->leftJoin("e_rkb_po",function($join){
                        $join->on("e_rkb_po.no_rkb","e_rkb_detail.no_rkb");
                        $join->on("e_rkb_po.item","e_rkb_detail.item");
                    })
                    ->select("e_rkb_header.*","section.sect as det_sect","department.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section","e_rkb_po.no_po","e_rkb_po.keterangan","e_rkb_po.time_input","e_rkb_po.user_input");
                    //->where("e_rkb_header.section",$_SESSION['section']);


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
        }elseif($_SESSION['level']=="administrator"){

        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section IS NOT NULL or e_rkb_cancel.cancel_by_section IS NOT NULL)");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.disetujui = '0' or e_rkb_approve.diketahui = '0') and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL )");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and e_rkb_cancel.cancel_by_section IS NULL and (e_rkb_approve.disetujui = '1' or e_rkb_approve.diketahui = '1')");
        }else{

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')");
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
        return view('page.admin.report',["rkb"=>$row,"getUser"=>$this->user]);
    }

    public function dataJson(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        $usr = DB::table('user_login')
            ->leftjoin("department","department.id_dept","user_login.department")
            ->leftjoin("section","section.id_sect","user_login.section")
            ->get();
        return $usr;
    }
    public function ruleUser(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_SESSION['username'])=="") return redirect('/');
        if(isset($_GET['cari'])){
        $user = DB::table("user_login")
                ->whereRaw("username like '%".$_GET['cari']."%' or nama_lengkap like '%".$_GET['cari']."%' or rule like '%".$_GET['cari']."%'")
                ->paginate(10);
        }else{
        $user = DB::table("user_login")->paginate(10);
        }
        return view('page.master.rule',["dataUser"=>$user,"getUser"=>$this->user]);
    }
    public function ruleUserEdit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_SESSION['username'])=="") return redirect('/');
        $id_user = hex2bin($request->id_user);
        $data = DB::table('user_login')->where("id_user",$id_user)->first();
        return view('page.master.modal',["data"=> $data,"modal"=>"true"]);
    }
    public function ruleUpdate(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_SESSION['username'])=="") return redirect('/');
        //dd($request);
        $upRule = DB::table("user_login")
                    ->where("id_user", hex2bin($request->idUser))
                    ->update([
                        "rule"=>$request->userRule
                    ]);

        if($upRule){
            return redirect()->back()->with("success","Data Di Update!");
        }else{

            return redirect()->back()->with("failed","Gagal Mengupdate!");
        }
    }
    public function dataKaryawan(Request $request)
    {
        //if(!isset($_SESSION['username'])) return redirect('/');
        //if(isset($_SESSION['username'])=="") return redirect('/');
        $aktiv = DB::table("db_karyawan.data_karyawan")->where("flag",0)->count();
        $nonaktiv = DB::table("db_karyawan.data_karyawan")->where("flag",1)->count();
        $karyawan = DB::table("db_karyawan.data_karyawan as a")
                    ->join("department as b","b.id_dept","a.departemen")
                    ->leftJoin("db_karyawan.perusahaan as c","c.id_perusahaan","a.perusahaan")
                    ->select("a.*","a.flag as disableKaryawan","b.*","c.*");
        if(isset($_GET['status'])){
            if($_GET['status']=="aktif"){
                $status =$karyawan->where("a.flag",0);
            }else if($_GET['status']=="tidak_aktif"){
                $status =$karyawan->where("a.flag",1);
            }
        }else{
            $status =$karyawan;
        }
        
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $filter = $status->whereRaw("
                        a.nik like '%".$cari."%' or 
                        a.nama like '%".$cari."%' or 
                        b.dept like '%".$cari."%' or 
                        a.jabatan like '%".$cari."%'");
        }else{
            $filter = $status;
        }
        
                    $data = $filter->orderBy("nik")->paginate(30);
                    // dd( $data );
        return view('page.admin.karyawan',["karyawan"=>$data,"getUser"=>$this->user,"aktiv"=>$aktiv,"nonaktiv"=>$nonaktiv]);
    }
    
    public function dataKaryawanDisable(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_SESSION['username'])=="") return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                    ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")->whereRaw("
                        db_karyawan.data_karyawan.nik ='".$request->nik."'")
                    ->update(['db_karyawan.data_karyawan.flag'=>1]);
        return redirect()->back()->with("success","Data Karyawan Telah Di Update");
    }
    public function dataKaryawanEnable(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_SESSION['username'])=="") return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                    ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")->whereRaw("
                        db_karyawan.data_karyawan.nik ='".$request->nik."'")
                    ->update(['db_karyawan.data_karyawan.flag'=>0]);
        return redirect()->back()->with("success","Data Karyawan Telah Di Update");
    }
    
    public function karyawanCPASS(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('page.admin.f_pass',["data_id"=>$request->nik,"getUser"=>$this->user]);
       //dd($request->nik);
    }
    public function dataKaryawanKirim(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table("user_login")->where("nik",$request->data_id)->count();
        $karyawan = DB::table("db_karyawan.data_karyawan")->where("nik",$request->data_id)->first();
        //dd($request);

        if($cek<=0){
            if($request->username==null){
            $cr = DB::table("user_login")
                ->insert([
                "username"=>$request->data_id,
                "nama_lengkap"=>$karyawan->nama,
                "password"=>md5($request->r_pass),
                "department"=>$karyawan->departemen,
                "tglentry"=>date("Y-m-d"),
                "rule"=>"sarpras,menu sarpras",
                "nik"=>$request->data_id
                ]);
            }else{
            $cr = DB::table("user_login")
                ->insert([
                "username"=>$request->username,
                "nama_lengkap"=>$karyawan->nama,
                "password"=>md5($request->r_pass),
                "department"=>$karyawan->departemen,
                "tglentry"=>date("Y-m-d"),
                "rule"=>"sarpras,menu sarpras",
                "nik"=>$request->data_id
                ]);
            }
            return redirect()->back()->with("success","Create Username & Password Success!");
        }else{
            return redirect()->back()->with("failed","Username & Password Sudah Ada!");
        }
    }
    public function users(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $users = DB::table("user_login")
                ->leftjoin("department","department.id_dept","user_login.department")
                ->leftjoin("section","section.id_sect","user_login.section");
        if(isset($_GET['cari']))
        {
            $filter = $users->whereRaw("username like '%".$_GET['cari']."%'");
        }else{
            $filter = $users;
        }

                $get = $filter->paginate(12);
        //dd($get);
        return view('page.admin.user',["user"=>$get,"getUser"=>$this->user]);
    }
    public function hotspotData(Request $request)
    {
      //dd($request);
      $insert = DB::table("db_karyawan.hotspot")
                ->insert([
                  "username"=>$request->username,
                  "ip"      =>$request->ip,
                  "time_input"=>date("Y-m-d H:i:s")
                ]);
      return redirect('https://google.com');
    }
    public function users_json(Request $request)
    {
        $users = DB::table("user_login")->get();
        return $users;
    }
    public function neardealJson(Request $request)
    {
        setcookie("username", "admin", time() + (86400 * 30), "/");
        if(isset($_REQUEST['store_id'])){
        $store_id = $_REQUEST['store_id'];
        $nearDeal = DB::table("neardeal.product")
                    ->where("store_id",$store_id)
                    ->get();
        $json = array("success"=>0,"product"=>$nearDeal);
        return ($json);
        }else{      
            if(isset($_REQUEST['csrf_token'])){
                return array("csrf_token"=>csrf_token());
            }else{
                if(isset($_REQUEST['deal_id'])){
                    $store_id= $_REQUEST['deal_id'];
                    $nearDeal = DB::table("neardeal.product")
                    ->join("neardeal.deal","neardeal.deal.product_id","neardeal.product.id")
                    ->where("neardeal.product.store_id",$store_id)
                    ->get();
                    $json = array("success"=>0,"deal"=>$nearDeal);
                    return ($json);
                }else{
                    if(isset($_REQUEST['product_detail'])){
                        $product_id= $_REQUEST['product_detail'];
                    $nearDeal = DB::table("neardeal.store")
                    ->join("neardeal.product","neardeal.product.store_id","neardeal.store.id")
                    ->join("neardeal.deal","neardeal.product.id","neardeal.deal.product_id")
                    ->select("store.*","deal.*","product.*","store.photo as photo_store","product.photo as product_photo",DB::raw("(product.price-(product.price*deal.discount/100)) as newPrice"))
                    ->where("neardeal.product.id",$product_id)
                    ->first();
                    echo json_encode($nearDeal);
                }else{
                    $lat = $_REQUEST['lat'];
                    $lng = $_REQUEST['lng'];
                    $nearDeal = DB::table("neardeal.store")
                                ->select("id","name","lat","lng","photo","telp","description","open_hour","address",DB::raw("(6371 * acos(cos(radians({$lat})) * cos(radians(lat)) ". 
                "* cos(radians(lng) - radians({$lng})) + sin(radians({$lat})) * sin(radians(lat))))  as distance")
                                )
                                ->having("distance","<=","0.1")
                                ->orderBy("distance")
                                ->get();

                    $json = array("success"=>0,"store"=>$nearDeal);
                    return ($json); 
                    }   
                }
                
            }
        }
    }
    public function neardealPost(Request $request)
    {
        //dd($request);
        if(isset($request->username) && isset($request->password) ){
            $username = $request->username ;
            $password = $request->password;
            $user = DB::table("neardeal.user")
                    ->where(["username"=>$username,"password"=>$password])
                    ->first();
            if(!empty($user)){
                $_SESSION['user_id']= $user->id;

                echo json_encode(array(
                    "success"=>true,
                    "user"=>$user
                ));
            }else{
                echo json_encode(array(
                    "success"=>false,
                    "user"=>array(
                        "id"=>0,
                        "username"=>"",
                        "password"=>""
                    )
                ));
            }

        }else{
            if(isset($_SESSION['user_id'])){
            $cekUser = DB::table("neardeal.user")->where("id",$_SESSION['user_id'])->first();
            //echo json_encode($cekUser);
            if($cekUser){
                $checkout = DB::table("neardeal.order")
                            ->insertGetId([
                                "user_id" => $cekUser->id
                            ]);
                            //dd($checkout);
                if($checkout){
                    $idOrder = $checkout;
                    $products = $request->products;
                    $prices = $request->prices;
                    foreach($products as $k => $v){
                    $inItem = DB::table("neardeal.item_order")
                                ->insert([
                                    "order_id"=>$idOrder,
                                    "product_id"=>$v,
                                    "price"=>$prices[$k]
                                ]);    
                                if($inItem){
                                    echo json_encode(array("success"=>true));
                                }else{
                                    echo json_encode(array("success"=>false));
                                }
                    }
                    

                }
                        }
            }else{
                                    print_r($_SESSION);
            }
        }
    }
    public function sql_backup(Request $request)
    {
        $dbHost =config('database.connections.mysql.host');
        $dbUsername = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');
        $db1     = config('database.db_manual1');
        $db2     = config('database.db_manual2');
        $db3     = config('database.db_manual3');
        $db4     = config('database.db_manual4');
        $db5     = config('database.db_manual5');
        $db6     = config('database.db_manual6');
        $db7     = config('database.db_manual7');
        $db8     = config('database.db_manual8');
        $db9     = config('database.db_manual9');
        $db10     = config('database.DB_DATABASE');
        $mysqlExportPath = "backup_sql/";
        //Please do not change the following points
        //Export of the database and output of the status
        $command='mysqldump --opt -h' .$dbHost .' -u' .$dbUsername .' -p' .$dbPassword .' ' .$db1 .' > ' .$db1.".sql";
        $worked = exec($command);
        $mysqlDatabaseName = $db1;
        switch($worked){
        case 0:
        echo 'The database <b>' .$mysqlDatabaseName .'</b> was successfully stored in the following path '.getcwd().'/' .$mysqlExportPath .'</b>';
        break;
        case 1:
        echo 'An error occurred when exporting <b>' .$mysqlDatabaseName .'</b> zu '.getcwd().'/' .$mysqlExportPath .'</b>';
        break;
        case 2:
        echo 'An export error has occurred, please check the following information: <br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$dbUsername .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$dbHost .'</b></td></tr></table>';
        break;
        }

    }
}