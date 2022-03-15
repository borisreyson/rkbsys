<?php

namespace App\Http\Controllers\sarana;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use App\EmailSend;

class kabagController extends Controller
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
    public function keluar_masuk_sarana_appr(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->leftjoin("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->leftjoin("user_login","user_login.nik","vihicle.v_out_h.nik")
                ->select("vihicle.v_out_h.*","vihicle.v_out_h.tanggal_entry as out_entry","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*","user_login.*")
                ->where("user_login.department",$_SESSION['department']);
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
        }
             $K_M=$filter
                ->groupBy("vihicle.v_out_h.tanggal_entry")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->paginate(10);
             //dd($K_M);
        return view('sarana.kabag.k_m',["getUser"=>$this->user,"K_M"=>$K_M]);
    }
    public function keluar_masuk_sarana_appr_hse(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->leftjoin("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->leftjoin("user_login","user_login.nik","vihicle.v_out_h.nik")
                ->select("vihicle.v_out_h.*","vihicle.v_out_h.tanggal_entry as out_entry","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*","user_login.*")
                ->where("user_login.department",'hse');
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
        }
             $K_M=$filter
                ->groupBy("vihicle.v_out_h.tanggal_entry")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->paginate(10);
             //dd($K_M);
        return view('sarana.kabag.k_m',["getUser"=>$this->user,"K_M"=>$K_M]);
    }
    public function ReportK_M_kabag(Request $request)
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
                ->where("user_login.department",$_SESSION['department'])
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
    public function ReportK_M_kabag_hse(Request $request)
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
                ->where("user_login.department",'hse')
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
    public function enp(Request $request)
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
                ->where("user_login.department",'enp')
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
