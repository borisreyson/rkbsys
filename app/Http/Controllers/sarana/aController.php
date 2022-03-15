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

class aController extends Controller
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

    public function index(Request $request)
    {
    	if(!isset($_SESSION['username'])) return redirect('/');
    	dd($request);

    }
//KARYAWAN
    public function karyawan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $karyawan = DB::table("vihicle.v_karyawan")
                    ->join("department","department.id_dept","vihicle.v_karyawan.department")
                    ->join("section","section.id_sect","vihicle.v_karyawan.section")
                    ->groupBy("vihicle.v_karyawan.nik");
        if(isset($_GET['search'])){
            $filter = $karyawan->whereRaw("nik like '%".$_GET['search']."%' or department.dept like '%".$_GET['search']."%' or section.sect like '%".$_GET['search']."%' or nama like '%".$_GET['search']."%' or jabatan like '%".$_GET['search']."%' ");
        }else{
            $filter = $karyawan;
        }   
                    $res = $filter->paginate(10);
        $jumlah = DB::table("vihicle.v_karyawan")->count();
        return view('sarana.karyawan',["getUser"=>$this->user,"karyawan"=>$res,"jumlah"=>$jumlah]);
    }
    public function karyawanForm(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dep = DB::table("department")->get();
        return view('sarana.form.karyawan',["getUser"=>$this->user,"dep"=>$dep]);
    }
    public function karyawanEdit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dep = DB::table("department")->get();
        $edit = DB::table("vihicle.v_karyawan")->where("nik",hex2bin($request->data_id))->first();

        return view('sarana.form.karyawan',["getUser"=>$this->user,"dep"=>$dep,"edit"=>$edit,"data_id"=>$request->data_id]);
    }
    public function karyawanPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $in = DB::table("vihicle.v_karyawan")
                ->insert([
                    "nik"=>$request->nik,
                    "nama"=>$request->nama,
                    "department"=>$request->department,
                    "section"=>$request->section,
                    "jabatan"=>$request->jabatan,
                    "user_entry"=>$_SESSION['username'],
                    "tanggal_entry"=>date("Y-m-d H:i:s")
                ]);
            if($in){
                return redirect()->back()->with("success","Insert Data Karyawan Success!");
            }else{
                return redirect()->back()->with("failed","Insert Data Karyawan Failed!");
            }
    }
    public function karyawanPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up = DB::table("vihicle.v_karyawan")
                ->where("nik",hex2bin($request->data_id))
                ->update([
                    "nik"=>$request->nik,
                    "nama"=>$request->nama,
                    "department"=>$request->department,
                    "section"=>$request->section,
                    "jabatan"=>$request->jabatan,
                    "user_entry"=>$_SESSION['username'],
                    "tanggal_entry"=>date("Y-m-d H:i:s")
                ]);
            if($up){
                return redirect()->back()->with("success","Update Data Karyawan Success!");
            }else{
                return redirect()->back()->with("failed","Update Data Karyawan Failed!");
            }
    }
    public function karyawanDel(Request $request,$data_id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $del = DB::table("vihicle.v_karyawan")
                ->where("nik",hex2bin($request->data_id))
                ->delete();
        if($del)
        {
            return redirect()->back()->with("success","Delete Data Karyawan Success!");
        }else{
            return redirect()->back()->with("failed","Delete Data Karyawan Failed!");
        }
    }
//DRIVER
    public function driver(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $j_driver = DB::table("vihicle.v_driver")->count();
        $driver = DB::table("vihicle.v_driver")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_driver.nik");
        if(isset($_GET['cari'])){
            $filter = $driver->whereRaw("db_karyawan.data_karyawan.nama like '%".$_GET['cari']."%'");
        }else{
            $filter=$driver;
        }
            $res = $filter->paginate(10);
        return view('sarana.driver',["getUser"=>$this->user,"driver"=>$res,"j_dr" => $j_driver]);
    }
    public function driverForm(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                    ->get();
        return view('sarana.form.driver',["getUser"=>$this->user,"karyawan"=>$karyawan]);
    }

    public function driverEdit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                    ->get();
        $edit = DB::table("vihicle.v_driver")->where("no",hex2bin($request->data_id))->first();
        //dd($edit);
        return view('sarana.form.driver',["getUser"=>$this->user,"edit"=>$edit,"data_id"=>$request->data_id,"karyawan"=>$karyawan]);
    }
    public function driverPOST(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $in = DB::table('vihicle.v_driver')
                ->insert([
                    "no_sim"            =>$request->noSim,
                    "nik"               =>$request->nik,
                    "jenis_sim"         =>$request->jenisSim,
                    "berlaku_sim"       =>date("Y-m-d",strtotime($request->berlaku_sim)),
                    "kota_dikeluarkan"  =>$request->kota_dikeluarkan,
                    "user_entry"        =>$_SESSION['username']
                ]);
            if($in)
        {
            return redirect()->back()->with("success","Insert Data Driver Success!");
        }else{
            return redirect()->back()->with("failed","Insert Data Driver Failed!");
        }
    }
    public function driverPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $update = DB::table('vihicle.v_driver')
                ->update([
                    "no_sim"            =>$request->noSim,
                    "nik"               =>$request->nik,
                    "jenis_sim"         =>$request->jenisSim,
                    "berlaku_sim"       =>date("Y-m-d",strtotime($request->berlaku_sim)),
                    "kota_dikeluarkan"  =>$request->kota_dikeluarkan
                ]);
            if($update)
        {
            return redirect()->back()->with("success","Update Data Driver Success!");
        }else{
            return redirect()->back()->with("failed","Update Data Driver Failed!");
        }
    }

    public function driverDel(Request $request,$data_id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $del = DB::table("vihicle.v_driver")
                ->where("no",hex2bin($request->data_id))
                ->delete();
        if($del)
        {
            return redirect()->back()->with("success","Delete Data Driver Success!");
        }else{
            return redirect()->back()->with("failed","Delete Data Driver Failed!");
        }
    }
//UNIT SARANA    
    public function unit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $J_unit = DB::table("vihicle.v_unit_h")->count();
        $unit = DB::table("vihicle.v_unit_h")
                    ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                    ->join("vihicle.v_pemilik","vihicle.v_pemilik.no_pol","vihicle.v_unit_h.no_pol")
                    ->join("vihicle.v_driver","vihicle.v_driver.no","vihicle.v_unit_d.driver")
                    ->join("db_karyawan.data_karyawan as k1","k1.nik","=","vihicle.v_driver.nik")
                    ->join("db_karyawan.data_karyawan as k2","k2.nik","=","vihicle.v_unit_d.pic_lv")
                    ->select("vihicle.v_unit_h.*","vihicle.v_pemilik.*","vihicle.v_unit_d.*","vihicle.v_driver.*","k1.nama as nama_d","k2.nama as nama_k","vihicle.v_unit_h.flag as flag_h");
        if(isset($_GET['cari'])){
            $filter = $unit->whereRaw("vihicle.v_unit_h.no_pol like '%".$_GET['cari']."%' or merek_type like '%".$_GET['cari']."%' or jenis like '%".$_GET['cari']."%' or model like '%".$_GET['cari']."%' or thn_pembuatan like '%".$_GET['cari']."%' or isi_slinder like '%".$_GET['cari']."%' or warna_kb like '%".$_GET['cari']."%' or warna_tnkb like '%".$_GET['cari']."%' or no_lv like '%".$_GET['cari']."%' or k1.nama like '%".$_GET['cari']."%' or k2.nama like '%".$_GET['cari']."%'");
        }else{
            $filter = $unit;
        }
            $res = $filter->paginate(10);
        return view('sarana.unit',["getUser"=>$this->user,"unit"=>$res,"j_unit"=>$J_unit]);
    }
    public function unitForm(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                    ->get();
        $driver = DB::table("vihicle.v_driver")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_driver.nik")
                    ->get();
        return view('sarana.form.unit',["getUser"=>$this->user,"karyawan"=>$karyawan,"driver"=>$driver]);
    }
    public function unitEdit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                    ->get();
        $driver = DB::table("vihicle.v_driver")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_driver.nik")
                    ->get();
        $edit = DB::table("vihicle.v_unit_h")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->join("vihicle.v_pemilik","vihicle.v_pemilik.no_pol","vihicle.v_unit_h.no_pol")
                ->where("vihicle.v_unit_h.no_pol",hex2bin($request->data_id))->first();
        return view('sarana.form.unit',["getUser"=>$this->user,"edit"=>$edit,"data_id"=>$request->data_id,"karyawan"=>$karyawan,"driver"=>$driver]);
    }
    public function unitPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $z=false;
        $noPol = str_replace(' ', '', $request->no_pol);
        $in = DB::table('vihicle.v_unit_h')
                ->insert([
                    "no_pol"=>$noPol,
                    "merek_type"=>$request->merek_type,
                    "jenis"=>$request->jenis,
                    "model"=>$request->model,
                    "thn_pembuatan"=>$request->tahun_pembuatan,
                    "isi_slinder"=>$request->isi_slinder,
                    "warna_kb"=>$request->warna_kb,
                    "warna_tnkb"=>$request->warna_tnkb,
                    "user_entry"=>$_SESSION['username'],
                    "tanggal_entry"=>date("Y-m-d H:i:s")
                ]);
        if($in){
        $d = DB::table("vihicle.v_unit_d")
                ->insert([
                    "no_pol"=>$noPol,
                    "no_lv"=>"LV ".$request->no_lv,
                    "pic_lv"=>$request->pic_lv,
                    "driver"=>$request->driver               
                     ]);
                if($d){
                    $p = DB::table("vihicle.v_pemilik")
                ->insert([
                    "no_pol"=>$noPol,
                    "nama_p"=>$request->nama_p,
                    "alamat_p"=>$request->alamat_p,
                    "user_entry"=>$_SESSION['username']  ,
                    "tanggal_entry"=>date("Y-m-d H:i:s")               
                     ]);
                    if($p)
                    {
                        $z=true;
                    }else{
                        $z=false;
                    }
                }else{
                    $z=false;
                }
        }else{
            $z=false;
        }
        if($z)
        {
            return redirect()->back()->with("success","Insert Data Sarana Success!");
        }else{
            return redirect()->back()->with("failed","Insert Data Sarana Failed!");
        }
    }
    public function unitPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $z=false;
        $data_id = str_replace(' ', '', hex2bin($request->data_id));
        $noPol = str_replace(' ', '', $request->no_pol);
        $up = DB::table("vihicle.v_unit_h")
                ->where("no_pol",$data_id)
                ->update([
                    "no_pol"=>$noPol,
                    "merek_type"=>$request->merek_type,
                    "jenis"=>$request->jenis,
                    "model"=>$request->model,
                    "thn_pembuatan"=>$request->tahun_pembuatan,
                    "isi_slinder"=>$request->isi_slinder,
                    "warna_kb"=>$request->warna_kb,
                    "warna_tnkb"=>$request->warna_tnkb
                ]);
        if($up>=0){
            $d = DB::table("vihicle.v_unit_d")
                ->where("no_pol",$data_id)
                ->update([
                    "no_pol"=>$noPol,
                    "no_lv"=>"LV ".$request->no_lv,
                    "pic_lv"=>$request->pic_lv,
                    "driver"=>$request->driver               
                     ]);
            if($d>=0){
                $p = DB::table("vihicle.v_pemilik")
                ->where("no_pol",$data_id)
                ->update([
                    "no_pol"=>$noPol,
                    "nama_p"=>$request->nama_p,
                    "alamat_p"=>$request->alamat_p,           
                     ]);
                if($p){
                    $z=true; 
               }else{
                    $z=false;
               }
                
            }else{
                $z=false;
            }
        }else{
            $z=false;
        }
            if($z){
                return redirect()->back()->with("success","Update Data Sarana Success!");
            }else{
                return redirect()->back()->with("failed","Update Data Sarana Failed!");
            }
    }

    public function unitDel(Request $request,$data_id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $data_id =hex2bin($request->data_id);
        
        $del = DB::table("vihicle.v_unit_h")
                ->where("vihicle.v_unit_h.no_pol",hex2bin($request->data_id))
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->join("vihicle.v_pemilik","vihicle.v_pemilik.no_pol","vihicle.v_unit_h.no_pol")
                ->update([
                    "vihicle.v_unit_h.flag"=>1,
                    "vihicle.v_unit_d.flag"=>1,
                    "vihicle.v_pemilik.flag"=>1
                ]);
        
        if($del)
        {
            return redirect()->back()->with("success","Delete Data Sarana Success!");
        }else{
            return redirect()->back()->with("failed","Delete Data Sarana Failed!");
        }
    }
    public function unitUndo(Request $request,$data_id)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $data_id =hex2bin($request->data_id);
        
        $del = DB::table("vihicle.v_unit_h")
                ->where("vihicle.v_unit_h.no_pol",hex2bin($request->data_id))
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->join("vihicle.v_pemilik","vihicle.v_pemilik.no_pol","vihicle.v_unit_h.no_pol")
                ->update([
                    "vihicle.v_unit_h.flag"=>0,
                    "vihicle.v_unit_d.flag"=>0,
                    "vihicle.v_pemilik.flag"=>0
                ]);
        if($del)
        {
            return redirect()->back()->with("success","Restore Data Sarana Success!");
        }else{
            return redirect()->back()->with("failed","Restore Data Sarana Failed!");
        }
    }
    public function vendor(Request $request)
    {       
        if(!isset($_SESSION['username'])) return redirect('/');
        $vendor = DB::table("vihicle.v_pemilik")
                    ->join("vihicle.v_unit_h","vihicle.v_unit_h.no_pol","vihicle.v_pemilik.no_pol")
                    ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                    ->select("vihicle.v_pemilik.*","vihicle.v_pemilik.flag as flag_p","vihicle.v_unit_h.*","vihicle.v_unit_d.*")
                    ->paginate(10);
        return view('sarana.vendor',["getUser"=>$this->user,"vendor"=>$vendor]);
    }
    public function vendorForm(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $unit = DB::table("vihicle.v_unit_h")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->where("vihicle.v_unit_h.flag",0)
                ->get();
        return view('sarana.form.vendor',["getUser"=>$this->user,"unit"=>$unit]);
    }
    public function vendorPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $unit = DB::table("vihicle.v_pemilik")
                ->insert([
                    "no_pol"=>$request->no_pol,
                    "nama_p"=>$request->nama,
                    "alamat_p"=>$request->alamat,
                    "user_entry"=>$_SESSION['username'],
                    "tanggal_entry"=>date("Y-m-d H:i:s")
                ]);
        if($unit ){
            return redirect()->back()->with("success","Restore Data Vendor Success!");
        }else{
            return redirect()->back()->with("failed","Restore Data Vendor Failed!");
        }
    }
    public function vendorEdit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $unit = DB::table("vihicle.v_unit_h")
                    ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                    ->get();
        $edit = DB::table("vihicle.v_pemilik")
                ->join("vihicle.v_unit_h","vihicle.v_unit_h.no_pol","vihicle.v_pemilik.no_pol")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->where("vihicle.v_pemilik.no_pol",hex2bin($request->data_id))->first();
        return view('sarana.form.vendor',["getUser"=>$this->user,"edit"=>$edit,"data_id"=>$request->data_id,"unit"=>$unit]);
    }
    public function vendorPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up = DB::table("vihicle.v_pemilik")
                ->where("no_pol",hex2bin($request->data_id))
                ->update([
                    "no_pol"=>$request->no_pol,
                    "nama_p"=>$request->nama,
                    "alamat_p"=>$request->alamat
                ]);
        if($up)
        {
            return redirect()->back()->with("success","Update Data Vendor Success!");
        }else{
            return redirect()->back()->with("failed","Update Data Vendor Failed!");
        }
    }
    public function vendorDel(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up = DB::table("vihicle.v_pemilik")
                ->where("no_pol",hex2bin($request->data_id))
                ->update([
                    "flag"=>'1'
                ]);
        if($up)
        {
            return redirect()->back()->with("success","Delete Data Vendor Success!");
        }else{
            return redirect()->back()->with("failed","Delete Data Vendor Failed!");
        }
    }
    public function vendorUndo(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up = DB::table("vihicle.v_pemilik")
                ->where("no_pol",hex2bin($request->data_id))
                ->update([
                    "flag"=>'0'
                ]);
        if($up)
        {
            return redirect()->back()->with("success","Restore Data Vendor Success!");
        }else{
            return redirect()->back()->with("failed","Restore Data Vendor Failed!");
        }
    }
    public function keluar_masuk_sarana(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->where("nik",$this->user->nik);
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
        }
             $K_M=$filter->paginate(10);
        return view('sarana.K_M_sarana',["getUser"=>$this->user,"K_M"=>$K_M]);
    }
    public function admin_sarana(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc");
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
        }
             $K_M=$filter->paginate(10);
        return view('sarana.K_M_sarana',["getUser"=>$this->user,"K_M"=>$K_M]);
    }
    public function keluar_sarana(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $unit = DB::table("vihicle.v_unit_h")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->where("vihicle.v_unit_h.flag",0)
                ->groupBy("vihicle.v_unit_d.no_lv")
                ->get();
        $driver = DB::table("vihicle.v_driver")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_driver.nik")
                    ->where("vihicle.v_driver.flag",0)
                    ->get();
        $karyawan = DB::table("db_karyawan.data_karyawan")->where("flag",0)->get();
        // dd($karyawan);
        $pemohon = DB::table("user_login")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","user_login.nik")
                    ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                    ->leftjoin("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                    ->where("username",$_SESSION['username'])->first();
        //dd($pemohon);
        return view('sarana.form.keluar',["getUser"=>$this->user,"unit"=>$unit,"driver"=>$driver,"karyawan"=>$karyawan,"pemohon"=>$pemohon]);
    }
    public function formIzinKeluar(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $unit = DB::table("vihicle.v_unit_h")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->where("vihicle.v_unit_h.flag",0)
                ->groupBy("vihicle.v_unit_d.no_lv")
                ->get();
        $driver = DB::table("vihicle.v_driver")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_driver.nik")
                    ->where("vihicle.v_driver.flag",0)
                    ->get();
        $karyawan = DB::table("db_karyawan.data_karyawan")->where("flag",0)->get();
        $pemohon = DB::table("user_login")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","user_login.nik")
                    ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                    ->leftjoin("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                    ->where("username",$_SESSION['username'])->first();
        //dd($pemohon);
        return view('sarana.form.motor',["getUser"=>$this->user,"unit"=>$unit,"driver"=>$driver,"karyawan"=>$karyawan,"pemohon"=>$pemohon]);
    }
    public function checkUnit(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $unit = DB::table("vihicle.v_unit_h")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->join("vihicle.v_driver","vihicle.v_driver.no","vihicle.v_unit_d.driver")
                ->join("db_karyawan.data_karyawan as d","d.nik","vihicle.v_driver.nik")
                ->whereRaw("vihicle.v_unit_d.no_lv = '".$request->no_lv."'")
                ->first();

        echo json_encode($unit);
    }
    public function cekKaryawan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $karyawan = DB::table("db_karyawan.data_karyawan")
                ->where("db_karyawan.data_karyawan.nik",$request->nik)
                ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                ->leftjoin("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                ->first();
        echo json_encode($karyawan);
    }
    public function keluar_masuk_post(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $z=false;
        $check = DB::table("vihicle.v_out_h")->where("tanggal_entry","like",'%'.date("Y-m-d").'%')->count();
        $no = $check+1;
        $penumpang = implode(",",$request->penumpang);
        $noid_out = uniqid();

        $v_h = DB::table("vihicle.v_out_h")
                ->insert([
                "noid_out"      => $noid_out,
                "nomor"         =>$no,
                "nik"           =>$request->pemohon,
                "driver"        =>$request->driver,
                "no_lv"         =>$request->no_lv,
                "penumpang_out" =>$penumpang,
                "keperluan"     =>$request->keterangan,
                "jam_out"       =>$request->jam_keluar,
                "tgl_out"       =>date("Y-m-d",strtotime($request->tgl_keluar)),
                "tanggal_entry" =>date("Y-m-d H:i:s")
                ]);
        if($v_h){
            if(isset($request->waktu_masuk)){
            $v_i = DB::table("vihicle.v_in")
                    ->insert([
                        "noid_out"  =>  $noid_out,
                        "tgl_in"  =>  date("Y-m-d",strtotime($request->tgl_masuk)),
                        "jam_in"  =>  date("H:i:s",strtotime($request->jam_masuk)),
                        "user_entry"=>$_SESSION['username'],
                        "tanggal_entry"=>date("Y-m-d H:i:s")
                    ]);
            }else{
            $v_i = DB::table("vihicle.v_in")
                    ->insert([
                        "noid_out"  =>  $noid_out,
                    ]);
            }
            if($v_i){
                $v_a = DB::table("vihicle.v_approve")
                        ->insert([
                            "noid_out"  =>  $noid_out
                        ]);
                    if($v_a){

        //$send = new EmailSend();
        $ck_user1 = DB::table("user_login")->where("nik",$request->pemohon)->first();
        $ck_user = DB::table("user_login")
                        ->whereRaw("(department='".$ck_user1->department."' and section='kabag' and status =0) or rule like '%notif sarpras%'")
                        ->get();
        //dd($ck_user);
        foreach ($ck_user as $key => $value) {
            //$res = $send->Sarpas_mail("https://abpjobsite.com:8443/sendmail_post.php",$value->email,"Surat Keluar",$noid_out);
                $res = DB::table("queue_email.queue_vihicle")
                            ->insert([
                                "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                                "email"=>$value->email,
                                "subjek"=>"Surat Keluar",
                                "noid_out"=>$noid_out,
                                "date_in"=>date("Y-m-d H:i:s")
                            ]);
            if($res){
                $z=true;
            }else{
                $z=false;
            }
                        }
             $sekurity = DB::table("db_karyawan.email_security")
                        ->get();
        foreach ($sekurity as $k => $v) 
            {
                $res = DB::table("queue_email.queue_vihicle")
                            ->insert([
                                "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                                "email"=>$v->email,
                                "subjek"=>"Surat Keluar",
                                "noid_out"=>$noid_out,
                                "date_in"=>date("Y-m-d H:i:s"),
                                "tipe"=>'security'
                            ]);
                if($res){
                    $z=true;
                }else{
                    $z=false;
                }
            }
        }
                        $z=true;
                    }else{
                        $z=false;
                    }
        }else{
            $z=false;
        }
        if($z==true){
            return redirect()->back()->with("success","Create Data Keluar Sarana Success!");
        }else{
            return redirect()->back()->with("failed","Create Data Keluar Sarana Failed!");
        }
    }
     public function postIzinKeluar(Request $request)
    {
        //dd($request);
        if(!isset($_SESSION['username'])) return redirect('/');

        $z=false;
        $check = DB::table("vihicle.v_out_h")->where("tanggal_entry","like",'%'.date("Y-m-d").'%')->count();
        $no = $check+1;
        $penumpang = implode(",",$request->penumpang);
        $noid_out = uniqid();

        $v_h = DB::table("vihicle.v_out_h")
                ->insert([
                "noid_out"      =>$noid_out,
                "nomor"         =>$no,
                "nik"           =>$request->pemohon,
                "driver"        =>$request->merk,
                "no_lv"         =>$request->no_lv,
                "no_pol"         =>$request->no_pol,
                "penumpang_out" =>$penumpang,
                "keperluan"     =>$request->keterangan,
                "jam_out"       =>$request->jam_keluar,
                "tgl_out"       =>date("Y-m-d",strtotime($request->tgl_keluar)),
                "tanggal_entry" =>date("Y-m-d H:i:s")
                ]);
        if($v_h){
            if(isset($request->waktu_masuk)){
            $v_i = DB::table("vihicle.v_in")
                    ->insert([
                        "noid_out"  =>  $noid_out,
                        "tgl_in"  =>  date("Y-m-d",strtotime($request->tgl_masuk)),
                        "jam_in"  =>  date("H:i:s",strtotime($request->jam_masuk)),
                        "user_entry"=>$_SESSION['username'],
                        "tanggal_entry"=>date("Y-m-d H:i:s")
                    ]);
            }else{
            $v_i = DB::table("vihicle.v_in")
                    ->insert([
                        "noid_out"  =>  $noid_out,
                    ]);
            }
            if($v_i){
                $v_a = DB::table("vihicle.v_approve")
                        ->insert([
                            "noid_out"  =>  $noid_out
                        ]);
                    if($v_a){

        //$send = new EmailSend();
        $ck_user1 = DB::table("user_login")->where("nik",$request->pemohon)->first();
        $ck_user = DB::table("user_login")
                        ->whereRaw("(department='".$ck_user1->department."' and section='kabag' and status =0) or rule like '%notif sarpras%'")
                        ->get();
        //dd($ck_user);
        foreach ($ck_user as $key => $value) {
            //$res = $send->Sarpas_mail("https://abpjobsite.com:8443/sendmail_post.php",$value->email,"Surat Keluar",$noid_out);
                $res = DB::table("queue_email.queue_vihicle")
                            ->insert([
                                "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                                "email"=>$value->email,
                                "subjek"=>"Surat Keluar",
                                "noid_out"=>$noid_out,
                                "date_in"=>date("Y-m-d H:i:s")
                            ]);
            if($res){
                $z=true;
            }else{
                $z=false;
            }
                        }
             $sekurity = DB::table("db_karyawan.email_security")
                        ->get();
        foreach ($sekurity as $k => $v) 
            {
                $res = DB::table("queue_email.queue_vihicle")
                            ->insert([
                                "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                                "email"=>$v->email,
                                "subjek"=>"Surat Keluar",
                                "noid_out"=>$noid_out,
                                "date_in"=>date("Y-m-d H:i:s"),
                                "tipe"=>'security'
                            ]);
                if($res){
                    $z=true;
                }else{
                    $z=false;
                }
            }
        }
                        $z=true;
                    }else{
                        $z=false;
                    }
        }else{
            $z=false;
        }
        if($z==true){
            return redirect()->back()->with("success","Create Data Keluar Sarana Success!");
        }else{
            return redirect()->back()->with("failed","Create Data Keluar Sarana Failed!");
        }
    }

    
    public function appr_form(Request $request)
    {
        //dd($request->pdfURL);
        if(!isset($_SESSION['username'])) return redirect('/');
        $appr = DB::table("vihicle.v_approve")
                ->where("vihicle.v_approve.noid_out",$request->data_id)
                ->update([
                    "flag_appr"=>'1',
                    "tanggal_appr"=>date("Y-m-d H:i:s"),
                    "user_appr"=>$_SESSION['username']
                ]);
        if($appr){
            
        $send = new EmailSend();
        $ck_user = DB::table("vihicle.v_out_h")
                    ->join("user_login","user_login.nik","vihicle.v_out_h.nik")
                    ->whereRaw("noid_out = '".$request->data_id."' or rule like '%admin abp%'")->get();
        foreach($ck_user as $k => $v){
        $res = DB::table("queue_email.queue_vihicle")
                ->insert([
                    "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                    "email"=>$v->email,
                    "subjek"=>"Surat Keluar",
                    "noid_out"=>$request->data_id,
                    "url_pdf"=>$request->pdfURL,
                    "date_in"=>date("Y-m-d H:i:s")
                ]);
            }

        $security = DB::table("db_karyawan.email_security")->get();
        foreach($security as $kS => $vS){
        $res1 = DB::table("queue_email.queue_vihicle")
                ->insert([
                    "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                    "email"=>$vS->email,
                    "subjek"=>"Surat Keluar",
                    "noid_out"=>$request->data_id,
                    "url_pdf"=>$request->pdfURL,
                    "date_in"=>date("Y-m-d H:i:s")
                ]);
        }
            
        

            echo "Approve Success!";
        }else{
            echo "Approve Failed!";
        }
    }

    public function cancel(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        return view('sarana.form.cancel',["getUser"=>$this->user,"data_id"=>$request->data_id]);
    }
    public function cancel_put(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $appr = DB::table("vihicle.v_approve")
                ->where("vihicle.v_approve.noid_out",$request->data_id)
                ->update([
                    "flag_appr"=>'0',
                    "tanggal_appr"=>date("Y-m-d H:i:s"),
                    "keterangan"=>$request->keterangan,
                    "user_appr"=>$_SESSION['username']
                ]);
        if($appr>=0){
            return redirect()->back()->with("success","Cancel Keluar Sarana Success!");
        }else{
            return redirect()->back()->with("failed","Cancel Keluar Sarana Failed!");
        }
    }
    public function printOut(Request $request,$noid_out)
    {
        //if(!isset($_SESSION['username'])) return redirect('/');
        $f_name = "PRINT OUT";
        $db = DB::table("vihicle.v_out_h")
                ->leftjoin("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->leftjoin("db_karyawan.data_karyawan as p_nama","p_nama.nik","vihicle.v_out_h.nik")
                ->leftjoin("db_karyawan.data_karyawan as driver","driver.nik","vihicle.v_out_h.driver")
                ->leftjoin("vihicle.v_approve","v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*","p_nama.nama as nama_p","driver.nama as nama_d","vihicle.v_approve.*")
                ->where("vihicle.v_out_h.noid_out",$request->noid_out)
                ->first();

        $header =  view("sarana.print.header",["OK"=>"OK"])->render();
        $konten = view("sarana.print.konten",["konten"=>$db])->render();
        $footer = view("sarana.print.footer",["konten"=>$db])->render();
        
        $pdf_output = PDF::loadHTML($konten)
                        ->setPaper('a4')
                        ->setOrientation('Portrait')
                        ->setOption('margin-bottom',55)
                        ->setOption('margin-top',55)
                        ->setOption('header-html',$header)
                        ->setOption('footer-html',$footer);
        if(isset($_GET['saving'])){
            $pdf_create = $pdf_output->save($f_name."_".date("Ymd_His").".pdf");
            die();
        }else{
            $pdf_create = $pdf_output->output();
        }
        return response($pdf_create, 200)
            ->header('Content-Disposition' , 'filename='.($f_name))
               ->header('Content-Type',"application/pdf");
    }
    public function ReportK_M(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->where("nik",$this->user->nik);
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1;
        }
             $K_M=$filter->paginate(10);
        return view('sarana.report',["getUser"=>$this->user,"K_M"=>$K_M]);
    }

    public function ReportK_M_admin(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
        $K_M1 = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_out_h.tanggal_entry as entry_keluar","vihicle.v_in.*","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc");
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            $filter = $K_M1;
        }
             $K_M=$filter->paginate(10);
        return view('sarana.admin.report',["getUser"=>$this->user,"K_M"=>$K_M]);
    }
    
    public function t_m_in(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('sarana.form.timein',["getUser"=>$this->user,"noid_out"=>$request->noid_out]);
    }
    public function t_m_in_post(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $update = DB::table("vihicle.v_in")
                    ->where("noid_out",$request->noid_out)
                    ->update([
                        "tgl_in"=>date("Y-m-d",strtotime($request->tgl_in)),
                        "jam_in"=>date("H:i",strtotime($request->jam_in)),
                        "keterangan"=>$request->keterangan,
                        "user_entry"=>$_SESSION['username'],
                        "tanggal_entry"=>date("Y-m-d")
                    ]);
        if($update>=0){
            return redirect()->back()->with("success","Update Tanggal & Jam Masuk Success!");
        }else{
            return redirect()->back()->with("failed","Update Tanggal & Jam Masuk Failed!");            
        }
    }
    public function createPWD(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        return view('sarana.form.f_pass',["data_id"=>$request->nik,"getUser"=>$this->user]);
    }
    public function editDoc(Request $request,$noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $get = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_lv","vihicle.v_out_h.no_lv")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*","vihicle.v_unit_d.*","vihicle.v_out_h.driver as supirnya")
                ->where("vihicle.v_out_h.noid_out",$noid_out)->first();

        $unit = DB::table("vihicle.v_unit_h")
                ->join("vihicle.v_unit_d","vihicle.v_unit_d.no_pol","vihicle.v_unit_h.no_pol")
                ->where("vihicle.v_unit_h.flag",0)
                ->groupBy("vihicle.v_unit_d.no_lv")
                ->get();
        $driver = DB::table("vihicle.v_driver")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_driver.nik")
                    ->where("vihicle.v_driver.flag",0)
                    ->get();
        $karyawan = DB::table("db_karyawan.data_karyawan")->where("flag",0)->get();
        $pemohon = DB::table("user_login")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","user_login.nik")
                    ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                    ->leftjoin("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                    ->where("username",$_SESSION['username'])->first();
        //dd( $get );
        return view('sarana.form.edit',["get"=>$get,"noid_out"=>$noid_out,"getUser"=>$this->user,"unit"=>$unit,"driver"=>$driver,"karyawan"=>$karyawan,"pemohon"=>$pemohon]);
    }
    public function editMotor(Request $request,$noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $get = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*")
                ->where("vihicle.v_out_h.noid_out",$noid_out)->first();

        $karyawan = DB::table("db_karyawan.data_karyawan")->where("flag",0)->get();
        $pemohon = DB::table("user_login")
                    ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","user_login.nik")
                    ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                    ->leftjoin("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                    ->where("username",$_SESSION['username'])->first();
        //dd( $get );
        return view('sarana.form.eMotor',["get"=>$get,"noid_out"=>$noid_out,"getUser"=>$this->user,"karyawan"=>$karyawan,"pemohon"=>$pemohon]);
    }
    
    public function UpdateMotor(Request $request,$noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $penumpang = implode(",", $request->penumpang);
        $z=false;
        //dd($request);
        $a1 = DB::table("vihicle.v_out_h")
                    ->where("noid_out",$noid_out)
                    ->update([
                                "nik"   =>$request->pemohon,
                                "driver"=>$request->driver,
                                "no_lv" =>$request->no_lv,
                                "no_pol" =>$request->no_pol,
                                "penumpang_out" => $penumpang,
                                "keperluan" => $request->keterangan,
                                "jam_out" => date("H:i",strtotime($request->jam_keluar)),
                                "tgl_out" => date("Y-m-d",strtotime($request->tgl_keluar)),
                                "edit_at" => date("Y-m-d H:i:s")
                                ]);
        if($a1>=0){
            $z=true;
        
            if(isset($request->jam_masuk)){
            $a2 = DB::table("vihicle.v_in")
                        ->where("noid_out",$noid_out)
                        ->update([
                                    "jam_in" => date("H:i",strtotime($request->jam_masuk)),
                                    "tgl_in" => date("Y-m-d",strtotime($request->tgl_masuk))
                                    ]);
                if($a2>=0){
                        $z=true;
                    }else{
                        $z=false;
                    }
                }
        
        }else{
            $z=false;
        }
        
        if($z==true){
            return redirect()->back()->with("success","Update Success!");
        }else{
            return redirect()->back()->with("failed","Update Failed!");
        }
    }
    public function editPost(Request $request,$noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $penumpang = implode(",", $request->penumpang);
        $z=false;
        //dd($request);
        $a1 = DB::table("vihicle.v_out_h")
                    ->where("noid_out",$noid_out)
                    ->update([
                                "nik"   =>$request->pemohon,
                                "driver"=>$request->driver,
                                "no_lv" =>$request->no_lv,
                                "penumpang_out" => $penumpang,
                                "keperluan" => $request->keterangan,
                                "jam_out" => date("H:i",strtotime($request->jam_keluar)),
                                "tgl_out" => date("Y-m-d",strtotime($request->tgl_keluar)),
                                "edit_at" => date("Y-m-d H:i:s")
                                ]);
        if($a1>=0){
            $z=true;
        
            if(isset($request->jam_masuk)){
            $a2 = DB::table("vihicle.v_in")
                        ->where("noid_out",$noid_out)
                        ->update([
                                    "jam_in" => date("H:i",strtotime($request->jam_masuk)),
                                    "tgl_in" => date("Y-m-d",strtotime($request->tgl_masuk))
                                    ]);
                if($a2>=0){
                        $z=true;
                    }else{
                        $z=false;
                    }
                }
        
        }else{
            $z=false;
        }
        
        if($z==true){
            return redirect()->back()->with("success","Update Success!");
        }else{
            return redirect()->back()->with("failed","Update Failed!");
        }
    }
    
    public function cancelDoc(Request $request,$noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('sarana.form.cancel',["data_id"=>$noid_out,"getUser"=>$this->user,"user"=>"true"]);
    }
    public function cancelPost(Request $request,$noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $a1 = DB::table("vihicle.v_out_h")
                    ->where("noid_out",$noid_out)
                    ->update([
                                "flag"   =>1,
                                "flag_note"=>$request->keterangan
                                ]);
        if($a1>=0){
            return redirect()->back()->with("success","Cancel Success!");
        }else{
            return redirect()->back()->with("failed","Cancel Failed!");
        }
    }
    public function androidSaranaKeluar(Request $request)
    {
        // die();
        $z=false;
        $check = DB::table("vihicle.v_out_h")->where("tanggal_entry","like",'%'.date("Y-m-d").'%')->count();
        $no = $check+1;
        $penumpang = implode(",",$request->penumpang);
        $noid_out = uniqid();

        $v_h = DB::table("vihicle.v_out_h")
                ->insert([
                "noid_out"      => $noid_out,
                "nomor"         =>$no,
                "nik"           =>$request->pemohon,
                "driver"        =>$request->driver,
                "no_lv"         =>$request->no_lv,
                "penumpang_out" =>$penumpang,
                "keperluan"     =>$request->keterangan,
                "jam_out"       =>$request->jam_keluar,
                "tgl_out"       =>date("Y-m-d",strtotime($request->tgl_keluar)),
                "tanggal_entry" =>date("Y-m-d H:i:s")
                ]);
        if($v_h){
            if(isset($request->waktu_masuk)){
            $v_i = DB::table("vihicle.v_in")
                    ->insert([
                        "noid_out"  =>  $noid_out,
                        "tgl_in"  =>  (isset($request->tgl_masuk))?date("Y-m-d",strtotime($request->tgl_masuk)):NULL,
                        "jam_in"  =>  (isset($request->jam_masuk))?date("H:i:s",strtotime($request->jam_masuk)):NULL,
                        "user_entry"=>$request->username,
                        "tanggal_entry"=>date("Y-m-d H:i:s")
                    ]);
            }else{
            $v_i = DB::table("vihicle.v_in")
                    ->insert([
                        "noid_out"  =>  $noid_out,
                    ]);
            }
            if($v_i){
                $v_a = DB::table("vihicle.v_approve")
                        ->insert([
                            "noid_out"  =>  $noid_out
                        ]);
                    if($v_a){

        //$send = new EmailSend();
        $ck_user1 = DB::table("user_login")->where("nik",$request->pemohon)->first();
        $ck_user = DB::table("user_login")
                        ->whereRaw("(department='".$ck_user1->department."' and section='kabag' and status =0) or rule like '%notif sarpras%'")
                        ->get();
        //dd($ck_user);
        foreach ($ck_user as $key => $value) {
            //$res = $send->Sarpas_mail("https://abpjobsite.com:8443/sendmail_post.php",$value->email,"Surat Keluar",$noid_out);
                $res = DB::table("queue_email.queue_vihicle")
                            ->insert([
                                "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                                "email"=>$value->email,
                                "subjek"=>"Surat Keluar",
                                "noid_out"=>$noid_out,
                                "date_in"=>date("Y-m-d H:i:s")
                            ]);
            if($res){
                $z=true;
            }else{
                $z=false;
            }
                        }
             $sekurity = DB::table("db_karyawan.email_security")
                        ->get();
        foreach ($sekurity as $k => $v) 
            {
                $res = DB::table("queue_email.queue_vihicle")
                            ->insert([
                                "url"=>"https://abpjobsite.com:8443/sendmail_post.php",
                                "email"=>$v->email,
                                "subjek"=>"Surat Keluar",
                                "noid_out"=>$noid_out,
                                "date_in"=>date("Y-m-d H:i:s"),
                                "tipe"=>'security'
                            ]);
                if($res){
                    $z=true;
                }else{
                    $z=false;
                }
            }
        }
                        $z=true;
                    }else{
                        $z=false;
                    }
        }else{
            $z=false;
        }
        if($z==true){
            return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function androidSaranaKeluarKabag(Request $request)
    {
        if(isset($_GET['kirim'])){
            $dt = $request->dt_expr;
            $request->dt_expr= bin2hex(date("Y-m-d",strtotime($dt)));
        }
      $K_M1 = DB::table("vihicle.v_out_h")
                ->join("vihicle.v_in","vihicle.v_in.noid_out","vihicle.v_out_h.noid_out")
                ->join("db_karyawan.data_karyawan","db_karyawan.data_karyawan.nik","vihicle.v_out_h.nik")
                ->join("vihicle.v_approve","vihicle.v_approve.noid_out","vihicle.v_out_h.noid_out")
                ->select("vihicle.v_out_h.*","vihicle.v_in.*","db_karyawan.data_karyawan.nama as userPemohon","vihicle.v_in.keterangan as keterangan_in","vihicle.v_approve.*")
                ->orderBy("vihicle.v_out_h.tanggal_entry","desc")
                ->where("db_karyawan.data_karyawan.departemen",$request->dept)
                ->whereRaw("date(vihicle.v_out_h.tanggal_entry)='".date("Y-m-d")."'");
        if(isset($request->dt_expr)){
            $filter = $K_M1->where("vihicle.v_out_h.tgl_out",hex2bin($request->dt_expr));
        }else{
            //$filter = $K_M1->where("vihicle.v_out_h.tgl_out",">",date("Y-m-d",strtotime("-1 Days")));
          $filter= $K_M1;
        }
             $K_M=$filter->paginate(10);

        return $K_M;
    }
}