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

class importController extends Controller
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

    public function importAbpOb(Request $request)
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
        //dd($sheetData);
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
                        $cek=DB::table("monitoring_produksi.ob")->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))->count();
                        $tgl = date("Y-m-d",strtotime($sheetData[$k]['A']));
                        if($sheetData[$k]['B']=='-'){
                            $plan_daily = 0;
                        }else{
                            $plan_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['B']);
                        }
                        if($sheetData[$k]['C']=='-'){
                            $actual_daily = 0;
                        }else{
                            $actual_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['C']);
                        } 
                        if($sheetData[$k]['D']=='-'){
                            $mtd_plan=0;
                        }else{
                            $mtd_plan = preg_replace('/[ ,]+/', '', $sheetData[$k]['D']);
                        }
                        if($sheetData[$k]['E']=='-'){
                            $mtd_actual=0;
                        }else{
                            $mtd_actual=preg_replace('/[ ,]+/', '', $sheetData[$k]['E']);
                        }

                        if($cek==0){
                            
                        $in = DB::table("monitoring_produksi.ob")->insert([
                                                    "tgl"           =>$tgl,
                                                    "plan_daily"    =>$plan_daily,
                                                    "actual_daily"  =>$actual_daily,
                                                    "mtd_plan"      =>$mtd_plan,
                                                    "mtd_actual"    =>$mtd_actual,
                                                    "remark"        =>$sheetData[$k]['F'],
                                                    "user_input"    =>$_SESSION['username'],
                                                    "time_input"    =>date("Y-m-d H:i:s")
                                                    ]);

                        }else{
                                $update = DB::table("monitoring_produksi.ob")
                                                    ->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))
                                                    ->update([
                                                    "tgl"           =>$tgl,
                                                    "plan_daily"    =>$plan_daily,
                                                    "actual_daily"  =>$actual_daily,
                                                    "mtd_plan"      =>$mtd_plan,
                                                    "mtd_actual"    =>$mtd_actual,
                                                    "remark"        =>$sheetData[$k]['F'],
                                                    "user_input"    =>$_SESSION['username'],
                                                    "time_input"    =>date("Y-m-d H:i:s")
                                                    ]); 
                                   
                        }
                        
                    }
                }
            }
            
        }

       	unlink($file);
        return redirect()->back()->with("success","Data Telah Di Proses!");
        
    }
    public function importAbpHauling(Request $request)
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
                    if($sheetData[$k]['B']=='-'){
                        $plan_daily = 0;
                    }else{
                        $plan_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['B']);
                    }
                    if($sheetData[$k]['C']=='-'){
                        $actual_daily=0;
                    }else{
                        $actual_daily=preg_replace('/[ ,]+/', '', $sheetData[$k]['C']);  
                    }
                    if($sheetData[$k]['D']=='-'){
                        $mtd_plan=0;
                    }else{
                        $mtd_plan=preg_replace('/[ ,]+/', '', $sheetData[$k]['D']);
                    }
                    if($sheetData[$k]['E']=='-'){
                        $mtd_actual=0;
                    }else{
                        $mtd_actual=preg_replace('/[ ,]+/', '', $sheetData[$k]['E']);
                    }
                    
                    $remark=$sheetData[$k]['F']; 
                    
                        $cek=DB::table("monitoring_produksi.hauling")->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))->count();
                        
                        if($cek==0){
                            
                        $in = DB::table("monitoring_produksi.hauling")->insert([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>$plan_daily,
                                                            "actual_daily"=>$actual_daily,
                                                            "mtd_plan"=>$mtd_plan,
                                                            "mtd_actual"=>$mtd_actual,
                                                            "remark"=>$sheetData[$k]['F'],
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]);

                        }else{
                                $update = DB::table("monitoring_produksi.hauling")
                                                    ->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>$plan_daily,
                                                            "actual_daily"=>$actual_daily,
                                                            "mtd_plan"=>$mtd_plan,
                                                            "mtd_actual"=>$mtd_actual,
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
        return redirect()->back()->with("success","Data Telah Di Proses!");
        
    }
    public function importAbpCrushing(Request $request)
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
        //dd($sheetData);
        //die();
        foreach($sheetData as $k => $v){
            foreach($v as $k1 => $v1){
                if($v1!=""){
                    if($k==1){
                    }else{    
                    if($sheetData[$k]['B']=='-'){
                        $plan_daily = 0;
                    }else{
                        $plan_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['B']);
                    }
                    if($sheetData[$k]['C']=='-'){
                        $actual_daily = 0;
                    }else{
                        $actual_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['C']);
                    }
                    if($sheetData[$k]['D']=='-'){
                        $mtd_plan=0;
                    }else{
                        $mtd_plan=preg_replace('/[ ,]+/', '', $sheetData[$k]['D']);
                    }
                    if($sheetData[$k]['E']=='-'){
                        $mtd_actual=0;
                    }else{
                        $mtd_actual=preg_replace('/[ ,]+/', '', $sheetData[$k]['E']);
                    }
                    $remark=$sheetData[$k]['F']; 
                    
                        $cek=DB::table("monitoring_produksi.crushing")->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))->count();
                        
                        if($cek==0){
                            
                        $in = DB::table("monitoring_produksi.crushing")->insert([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>$plan_daily,
                                                            "actual_daily"=>$actual_daily,
                                                            "mtd_plan"=>$mtd_plan,
                                                            "mtd_actual"=>$mtd_actual,
                                                            "remark"=>$sheetData[$k]['F'],
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]);

                        }else{
                                $update = DB::table("monitoring_produksi.crushing")
                                                    ->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>$plan_daily,
                                                            "actual_daily"=>$actual_daily,
                                                            "mtd_plan"=>$mtd_plan,
                                                            "mtd_actual"=>$mtd_actual,
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
        return redirect()->back()->with("success","Data Telah Di Proses!");
        
    }
    public function importAbpBarging(Request $request)
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
//dd($sheetData);
        foreach($sheetData as $k => $v){
            foreach($v as $k1 => $v1){
                if($v1!=""){
                    if($k==1){
                    }else{    
                    if($sheetData[$k]['B']=='-'){
                        $plan_daily=0;
                    }else{
                        $plan_daily = preg_replace('/[ ,]+/', '', $sheetData[$k]['B']);
                    }
                    if($sheetData[$k]['C']=='-'){
                        $actual_daily=0;
                    }else{
                        $actual_daily=preg_replace('/[ ,]+/', '', $sheetData[$k]['C']);
                    }
                    if($sheetData[$k]['D']=='-'){
                        $mtd_plan=0;
                    }else{
                        $mtd_plan=preg_replace('/[ ,]+/', '', $sheetData[$k]['D']);
                    }
                    if($sheetData[$k]['E']=='-'){
                        $mtd_actual=0;
                    }else{
                        $mtd_actual=preg_replace('/[ ,]+/', '', $sheetData[$k]['E']); 
                    }                    
                    $remark=$sheetData[$k]['F']; 
                    
                        $cek=DB::table("monitoring_produksi.barging")->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))->count();
                        
                        if($cek==0){
                            
                        $in = DB::table("monitoring_produksi.barging")->insert([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>$plan_daily,
                                                            "actual_daily"=>$actual_daily,
                                                            "mtd_plan"=>$mtd_plan,
                                                            "mtd_actual"=>$mtd_actual,
                                                            "remark"=>$sheetData[$k]['F'],
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]);

                        }else{
                                $update = DB::table("monitoring_produksi.barging")
                                                    ->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "plan_daily"=>$plan_daily,
                                                            "actual_daily"=>$actual_daily,
                                                            "mtd_plan"=>$mtd_plan,
                                                            "mtd_actual"=>$mtd_actual,
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
        return redirect()->back()->with("success","Data Telah Di Proses!");
        
    }

    public function importAbpboat(Request $request)
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
        $rows=[];
        $cols=[];

        foreach($sheetData as $k => $v){
            if($k>1){
            $rows[$k] = (json_decode(json_encode($v)));
            $cek=DB::table("monitoring_produksi.barge_boat")->whereRaw("tgl='".date("Y-m-d",strtotime($rows[$k]->A))."' and tugboat='".$rows[$k]->B."'")->count();
            if($cek==0){

            $cek1=DB::table("monitoring_produksi.barge_boat")->whereRaw("tgl='".date("Y-m-d",strtotime($rows[$k]->A))."' and xls_key='".$k."'")->count();
            if($cek1==0){
                $in = DB::table("monitoring_produksi.barge_boat")->insert([
                                                            "tgl"=>date("Y-m-d",strtotime($rows[$k]->A)),
                                                            "tugboat"=>$rows[$k]->B,
                                                            "barge"=>$rows[$k]->C,
                                                            "time_board"=>$rows[$k]->D,
                                                            "tonase"=>preg_replace('/[ ,]+/', '',$rows[$k]->E),
                                                            "status"=>$rows[$k]->F,
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s"),
                                                            "xls_key"=>$k
                                                            ]);
                }else{
                $update = DB::table("monitoring_produksi.barge_boat")
                                                    ->whereRaw("tgl='".date("Y-m-d",strtotime($rows[$k]->A))."' and xls_key='".$k."'")
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($rows[$k]->A)),
                                                            "tugboat"=>$rows[$k]->B,
                                                            "barge"=>$rows[$k]->C,
                                                            "time_board"=>$rows[$k]->D,
                                                            "tonase"=>preg_replace('/[ ,]+/', '',$rows[$k]->E),
                                                            "status"=>$rows[$k]->F,
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]); 
                }
            }else{
                $update = DB::table("monitoring_produksi.barge_boat")
                                                    ->whereRaw("tgl='".date("Y-m-d",strtotime($rows[$k]->A))."' and xls_key='".$k."'")
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($rows[$k]->A)),
                                                            "tugboat"=>$rows[$k]->B,
                                                            "barge"=>$rows[$k]->C,
                                                            "time_board"=>$rows[$k]->D,
                                                            "tonase"=>preg_replace('/[ ,]+/', '',$rows[$k]->E),
                                                            "status"=>$rows[$k]->F,
                                                            "user_input"=>$_SESSION['username'],
                                                            "time_input"=>date("Y-m-d H:i:s")
                                                            ]); 
                }            
            }
        }

        unlink($file);
        return redirect()->back()->with("success","Data Telah Di Proses!");
        
    }
    public function importSrExpose(Request $request)
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
        //dd($sheetData);
        foreach($sheetData as $k => $v){
            foreach($v as $k1 => $v1){
                if($v1!=""){
                    if($k==1){
                    }else{    
                    if($sheetData[$k]['B']=='-'){
                        $inventory=0;
                    }else{
                        $inventory = preg_replace('/[ ,]+/', '', $sheetData[$k]['B']);
                    }              
                    $keterangan=$sheetData[$k]['C']; 
                    
                        $cek=DB::table("monitoring_produksi.stripping_ratio")->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))->count();
                        //dd(date("Y-m-d",strtotime($sheetData[$k]['A'])));
                        if($cek==0){
                            
                        $in = DB::table("monitoring_produksi.stripping_ratio")->insert([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "inventory"=>$inventory,
                                                            "keterangan"=>$keterangan,
                                                            "user_input"=>$_SESSION['username'],
                                                            "timeInput"=>date("Y-m-d H:i:s")
                                                            ]);

                        }else{
                                $update = DB::table("monitoring_produksi.stripping_ratio")
                                                    ->where("tgl",date("Y-m-d",strtotime($sheetData[$k]['A'])))
                                                    ->update([
                                                            "tgl"=>date("Y-m-d",strtotime($sheetData[$k]['A'])),
                                                            "inventory"=>$inventory,
                                                            "keterangan"=>$keterangan,
                                                            "user_input"=>$_SESSION['username'],
                                                            "timeInput"=>date("Y-m-d H:i:s")
                                                            ]); 
                                   
                        }
                        
                    }
                }
            }
            
        }
        unlink($file);
        return redirect()->back()->with("success","Data Telah Di Proses!");
    }
    public function formKaryawanImport(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');   
        $karyawan = DB::table("db_karyawan.data_karyawan")->paginate(10);
        return view('page.admin.formkaryawan',["getUser"=>$this->user,"karyawan"=>$karyawan]); 
    }
    public function importDataKaryawan(Request $request)
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
        //dd($sheetData);
        foreach($sheetData as $k => $v){
                    if($k>1){    
                    $nik = $sheetData[$k]['A'];
                    $nama=$sheetData[$k]['B']; 
                    $departemen=$sheetData[$k]['C'];    
                    $jabatan=$sheetData[$k]['D'];  
                    //echo "NIK : ".$nik." Nama : ".$nama." Dept : ".$departemen." Jabatan : ".$jabatan."<br/>";                   
                        $cek=DB::table("db_karyawan.data_karyawan")->where("nik",$sheetData[$k]['A'])->count();                        
                        if($cek==0){                            
                        $in = DB::table("db_karyawan.data_karyawan")->insert([
                                                            "nik"=>$nik,
                                                            "nama"=>$nama,
                                                            "departemen"=>$departemen,
                                                            "jabatan"=>$jabatan,
                                                            "user_entry"=>$_SESSION['username'],
                                                            "tgl_entry"=>date("Y-m-d"),
                                                            "password"=>md5("12345")
                                                            ]);
                        }else{
                                $update = DB::table("db_karyawan.data_karyawan")
                                                    ->where("nik",$nik)
                                                    ->update([
                                                            "nama"=>$nama,
                                                            "departemen"=>$departemen,
                                                            "jabatan"=>$jabatan,
                                                            "user_entry"=>$_SESSION['username'],
                                                            "tgl_entry"=>date("Y-m-d")
                                                            ]);      
                        }
                     
            }
            
        }
        unlink($file);
        return redirect()->back()->with("success","Data Telah Di Proses!");
    }

    public function compareDataKaryawan(Request $request)
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
        //dd($sheetData);
        $niks = [];
        foreach($sheetData as $k => $v){
                    if($k>1){    
                    $nik = $sheetData[$k]['A'];
                    $nama=$sheetData[$k]['B']; 
                    $departemen=$sheetData[$k]['C'];    
                    $jabatan=$sheetData[$k]['D'];  
                    //echo "NIK : ".$nik." Nama : ".$nama." Dept : ".$departemen." Jabatan : ".$jabatan."<br/>";
                        array_push($niks, $nik);
            }            
        }

        $cek=DB::table("db_karyawan.data_karyawan")->whereNotIn("nik",$niks)->get();
        foreach($cek as $k => $v){                            
            echo $v->nik." - ".$v->nama."<br/>";
        }
        unlink($file);
        //return redirect()->back()->with("success","Data Telah Di Proses!");
    }
}
