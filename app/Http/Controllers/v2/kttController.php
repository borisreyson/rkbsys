<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Illuminate\Support\Facades\Response;
use App\pictures;

class kttController extends Controller
{	
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

    public function rkb(Request $request)
    {
    	if(!isset($_SESSION['username'])) return redirect('/');
    	if(isset($request->expired)){
            $expired = "IS NOT NULL";
        }else{
            $expired = "IS NULL";
        }
            $sql = DB::table('e_rkb_header') 
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_header.no_rkb")
                    ->leftJoin("department","department.id_dept","e_rkb_header.dept")
                    ->leftJoin("section","section.id_sect","e_rkb_header.section")
                    ->leftJoin("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
                    ->leftJoin("user_login","user_login.username","e_rkb_detail.user_entry")
                    ->select("e_rkb_header.*","e_rkb_approve.*","department.*","section.*","e_rkb_detail.*","user_login.*")
                    ->orderBy("e_rkb_header.no_rkb","desc")
                    ->groupBy("e_rkb_detail.no_rkb")
                    ->whereRaw("user_expired ".$expired);
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

            if($_SESSION['section']=="KTT"){
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
                    $rkb = $query->paginate(10); 
                }
                $kabag = DB::table('user_login')->where("section","KABAG")->get();

	        return view('page.ktt.v2.rkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]); 
	        }

    }
}
