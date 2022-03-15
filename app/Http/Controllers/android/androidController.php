<?php

namespace App\Http\Controllers\android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class androidController extends Controller
{
    public function get_user(Request $request)
    {
        $db = DB::table("user_login as a")
            ->leftJoin("department as b","b.id_dept","a.department")
            ->leftJoin("section as c","c.id_sect","a.section")
            ->leftJoin("db_karyawan.perusahaan as d","d.id_perusahaan","a.perusahaan")
            ->where("a.username",$request->username)
            ->first();
        $dataHazard = DB::table("hse.hazard_report_header")
                      ->leftjoin("hse.hazard_report_validation","hse.hazard_report_validation.uid","hse.hazard_report_header.uid")->where("hse.hazard_report_header.user_input",$request->username)
                      ->whereRaw("month(hse.hazard_report_header.tgl_hazard) ='".date("m")."' and hse.hazard_report_validation.option_flag=1 and hse.hazard_report_validation.user_valid IS NOT NULL and hse.hazard_report_validation.option_flag = '1'")
                      ->count();

        $datInspeksi = DB::table("hse.form_inspeksi_header as a")
                      ->leftjoin("hse.form_inspeksi_validasi as b","b.idInspeksi","a.idInspeksi")->where("a.userInput",$request->username)
                      ->whereRaw("month(a.tgl_inspeksi) ='".date("m")."' and b.userValidasi IS NOT NULL")
                      ->count();
    	return array("dataUser"=>$db,"dataHazard"=>$dataHazard,'datInspeksi'=>$datInspeksi);
    }
    public function LoginValidate(Request $request)
   {
        if(isset($request->username) && isset($request->password)){
            $user = DB::table("user_login")
                    ->whereRaw("(nik ='".$request->username."' or username ='".$request->username."') and password ='".md5($request->password)."' and status='0'")
                    ->first();
            if($user!=null){
                if(isset($request->android_token)){
                    $android_token =$request->android_token;
                    $app_version = $request->app_version;
                    $app_name =$request->app_name;
                    if($user->nik!=null){
                    $cekToken = DB::table('keamanan.user_android')->where([
                                    ["nik",$user->nik],
                                    ["phone_token",$android_token]
                                ])->count();
                        $userORnik = $user->nik;

                    }else{
                    $cekToken = DB::table('keamanan.user_android')->where([
                                    ["nik",$user->username],
                                    ["phone_token",$android_token]
                                ])->count();
                        $userORnik = $user->username;
                    }
                    if($cekToken==0){
                    $tokenIn= DB::table('keamanan.user_android')
                                ->insert([
                                    "nik"=>$userORnik,
                                    "phone_token"=>$android_token,
                                    "app_version"=>$app_version,
                                    "tgl"=>date("Y-m-d"),
                                    "jam"=>date("H:i:s"),
                                    "app"=>$app_name
                                ]);
                    }else{
                    $tokenIn= DB::table('keamanan.user_android')
                                ->where([
                                    ["nik",$userORnik],
                                    ["phone_token",$android_token]
                                ])
                                ->update([
                                    "app_version"=>$app_version,
                                    "tgl"=>date("Y-m-d"),
                                    "jam"=>date("H:i:s"),
                                    "app"=>$app_name
                                ]);
                    }
                }
                return array("success"=>true,"user_login"=>$user);
            }else{
              return array("success"=>false,"user_login"=>$user);
            }
        }else{
            return array("success"=>false,"user_login"=>$user);
        }
   }
   public function LoginValidateNew(Request $request)
      {
           if(isset($request->username) && isset($request->password)){
               $user_login = DB::table("user_login")
                       ->whereRaw("(nik ='".$request->username."' or username ='".$request->username."') and password ='".md5($request->password)."' and status='0'")
                       ->first();
               if($user_login!=null){
                   if(isset($request->android_token)){
                       $android_token =$request->android_token;
                       $app_version = $request->app_version;
                       $app_name =$request->app_name;
                       if($user_login->nik!=null){
                       $cekToken = DB::table('keamanan.user_android')->where([
                                       ["nik",$user_login->nik],
                                       ["phone_token",$android_token]
                                   ])->count();
                           $userORnik = $user_login->nik;

                       }else{
                       $cekToken = DB::table('keamanan.user_android')->where([
                                       ["nik",$user_login->username],
                                       ["phone_token",$android_token]
                                   ])->count();
                           $userORnik = $user_login->username;
                       }
                       if($cekToken==0){
                       $tokenIn= DB::table('keamanan.user_android')
                                   ->insert([
                                       "nik"=>$userORnik,
                                       "phone_token"=>$android_token,
                                       "app_version"=>$app_version,
                                       "tgl"=>date("Y-m-d"),
                                       "jam"=>date("H:i:s"),
                                       "app"=>$app_name
                                   ]);
                       }else{
                       $tokenIn= DB::table('keamanan.user_android')
                                   ->where([
                                       ["nik",$userORnik],
                                       ["phone_token",$android_token]
                                   ])
                                   ->update([
                                       "app_version"=>$app_version,
                                       "tgl"=>date("Y-m-d"),
                                       "jam"=>date("H:i:s"),
                                       "app"=>$app_name
                                   ]);
                       }
                   }
                   return ["login"=>array("success"=>true,'user_login'=>$user_login)];
               }else{
                 return ["login"=>array("success"=>false,"user_login"=>null)];
               }
           }else{
             return ["login"=>array("success"=>false,"user_login"=>null)];
           }
      }
      public function getUserLogin(Request $request)
     {
          if(isset($request->username)){
              $user_login = DB::table("user_login")
                      ->whereRaw("(nik ='".$request->username."' or username ='".$request->username."') and password ='".md5($request->password)."' and status='0'")
                      ->first();
              if($user_login!=null){
                  // if(isset($request->android_token)){
                  //     $android_token =$request->android_token;
                  //     $app_version = $request->app_version;
                  //     $app_name =$request->app_name;
                  //     if($user->nik!=null){
                  //     $cekToken = DB::table('keamanan.user_android')->where([
                  //                     ["nik",$user->nik],
                  //                     ["phone_token",$android_token]
                  //                 ])->count();
                  //         $userORnik = $user->nik;
                  //
                  //     }else{
                  //     $cekToken = DB::table('keamanan.user_android')->where([
                  //                     ["nik",$user->username],
                  //                     ["phone_token",$android_token]
                  //                 ])->count();
                  //         $userORnik = $user->username;
                  //     }
                  //     if($cekToken==0){
                  //     $tokenIn= DB::table('keamanan.user_android')
                  //                 ->insert([
                  //                     "nik"=>$userORnik,
                  //                     "phone_token"=>$android_token,
                  //                     "app_version"=>$app_version,
                  //                     "tgl"=>date("Y-m-d"),
                  //                     "jam"=>date("H:i:s"),
                  //                     "app"=>$app_name
                  //                 ]);
                  //     }else{
                  //     $tokenIn= DB::table('keamanan.user_android')
                  //                 ->where([
                  //                     ["nik",$userORnik],
                  //                     ["phone_token",$android_token]
                  //                 ])
                  //                 ->update([
                  //                     "app_version"=>$app_version,
                  //                     "tgl"=>date("Y-m-d"),
                  //                     "jam"=>date("H:i:s"),
                  //                     "app"=>$app_name
                  //                 ]);
                  //     }
                  // }
                  return ["login"=>array("success"=>true,"user_login"=>$user_login)];
              }else{
                return ["login"=>array("success"=>false,"user_login"=>null)];
              }
          }else{
              return ["login"=>array("success"=>false,"user_login"=>null)];
          }
     }
    public function getOB(Request $request)
    {
        $fDate = date("Y-m-01");
        $lDate = date("Y-m-t");
        if($request->fDate && $request->lDate){
            $fDate = date("Y-m-d",strtotime($request->fDate));
            $lDate = date("Y-m-d",strtotime($request->lDate));
        }
        if($request->mtr == "OB"){
        $db = DB::table("monitoring_produksi.ob")
        ->whereBetween("tgl",[$fDate,$lDate])
        ->where("flag",1)
        ->orderBy("tgl","desc")->get();
        }elseif($request->mtr == "HAULING"){
        $db = DB::table("monitoring_produksi.hauling")
        ->whereBetween("tgl",[$fDate,$lDate])
        ->where("flag",1)
        ->orderBy("tgl","desc")->get();
        }elseif($request->mtr == "CRUSHING"){
        $db = DB::table("monitoring_produksi.crushing")
        ->whereBetween("tgl",[$fDate,$lDate])
        ->where("flag",1)
        ->orderBy("tgl","desc")->get();
        }elseif($request->mtr == "BARGING"){
        $db = DB::table("monitoring_produksi.barging")
        ->whereBetween("tgl",[$fDate,$lDate])
        ->where("flag",1)
        ->orderBy("tgl","desc")->get();
        }
        foreach($db as $k => $v){
            $OB_Total[] = $v->actual_daily;
        }
        // dd(array_sum($OB_Total));
        if(isset($OB_Total)){
        return array(
                        "ProduksiDaily"=>$db,
                        "ProduksiTotal"=>array_sum($OB_Total)
                    );
        }else{
        return array(
                        "ProduksiDaily"=>$db,
                        "ProduksiTotal"=>"0"
                    );
        }
    }
    public function getStock(Request $request)
    {
        $fDate = date("Y-m-01");
        $lDate = date("Y-m-t");
        if($request->fDate && $request->lDate){
            $fDate = date("Y-m-d",strtotime($request->fDate));
            $lDate = date("Y-m-d",strtotime($request->lDate));
        }
            $db = DB::table("monitoring_produksi.stock")
            ->whereBetween("tgl",[$fDate,$lDate])
            ->where("flag",1)
            ->orderBy("tgl","desc")->get();
        return array(
                        "Coal"=>$db
                    );
    }
    public function getSumberBahaya(Request $request)
    {
       $db = DB::table("hse.sumber_bahaya")->get();
       return array("sumber"=>$db);
    }
    public function postHazardReport(Request $request)
    {

        $fileToUpload = $request->file('fileToUpload');
        $fileToUploadPJ = $request->file('fileToUploadPJ');
        if($request->tglTenggat==""){
            $tglTenggat = null;
        }else{
            $tglTenggat = date("Y-m-d",strtotime($request->tglTenggat));
        }
        $uid = uniqid();
        $z=false;
        $dir = "bukti_hazard/";
        $dir2 = "bukti_hazard/penanggung_jawab/";

        $fileName = $fileToUpload->getClientOriginalName();
        $fileNamePJ = $fileToUploadPJ->getClientOriginalName();
        if(($fileToUpload->move($dir,$fileName)) && ($fileToUploadPJ->move($dir2,$fileNamePJ))) {
        $header = DB::table("hse.hazard_report_header")
            ->insert([
                "uid"=>$uid,
                "perusahaan"=>$request->perusahaan,
                "tgl_hazard"=>date("Y-m-d",strtotime($request->tgl_hazard)),
                "jam_hazard"=>date("H:i:s",strtotime($request->jam_hazard)),
                "idKemungkinan"=>$request->kemungkinan,
                "idKeparahan"=>$request->keparahan,
                "deskripsi"=>$request->deskripsi,
                "lokasi"=>$request->lokasi,
                "lokasi_detail"=>$request->lokasi_detail,
                "status_perbaikan"=>$request->status,
                "user_input"=>$request->user_input,
                "time_input"=>date("Y-m-d H:i:s")
            ]);
            if($header){
            $detail = DB::table("hse.hazard_report_detail")
                ->insert([
                "uid"=>$uid,
                "tindakan"=>$request->tindakan,
                "namaPJ"=>$request->namaPJ,
                "nikPJ"=>$request->nikPJ,
                "fotoPJ"=>$fileNamePJ,
                "katBahaya"=>$request->katBahaya,
                "idPengendalian"=>$request->pengendalian,
                "bukti"=>$fileName,
                "tgl_tenggat"=>$tglTenggat
            ]);
                if($detail){
                    $validation = DB::table("hse.hazard_report_validation")
                        ->insert([
                        "uid"=>$uid
                    ]);
                        if($validation){
                            $z=true;
                        }else{
            $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
            $detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
            $validation =  DB::table("hse.hazard_report_validation")->where("uid",$uid)->delete();
            $z=false;
                        }
                }else{
$headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
$detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
$z=false;
                }
            }else{
                $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
                $z=false;
            }
        return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function postHazardReportNoFoto(Request $request)
    {

        $fileToUpload = $request->file('fileToUpload');
        $fileToUploadPJ = $request->file('fileToUploadPJ');
        if($request->tglTenggat==""){
            $tglTenggat = null;
        }else{
            $tglTenggat = date("Y-m-d",strtotime($request->tglTenggat));
        }
        $uid = uniqid();
        $z=false;
        $dir = "bukti_hazard/";
        $dir2 = "bukti_hazard/penanggung_jawab/";

        $fileName = $fileToUpload->getClientOriginalName();
        $fileNamePJ = $fileToUploadPJ->getClientOriginalName();
        if(($fileToUpload->move($dir,$fileName)) && ($fileToUploadPJ->move($dir2,$fileNamePJ))) {
        $header = DB::table("hse.hazard_report_header")
            ->insert([
                "uid"=>$uid,
                "perusahaan"=>$request->perusahaan,
                "tgl_hazard"=>date("Y-m-d",strtotime($request->tgl_hazard)),
                "jam_hazard"=>date("H:i:s",strtotime($request->jam_hazard)),
                "idKemungkinan"=>$request->kemungkinan,
                "idKeparahan"=>$request->keparahan,
                "deskripsi"=>$request->deskripsi,
                "lokasi"=>$request->lokasi,
                "lokasi_detail"=>$request->lokasi_detail,
                "status_perbaikan"=>$request->status,
                "user_input"=>$request->user_input,
                "time_input"=>date("Y-m-d H:i:s")
            ]);
            if($header){
            $detail = DB::table("hse.hazard_report_detail")
                ->insert([
                "uid"=>$uid,
                "tindakan"=>$request->tindakan,
                "namaPJ"=>$request->namaPJ,
                "nikPJ"=>$request->nikPJ,
                "fotoPJ"=>$fileNamePJ,
                "katBahaya"=>$request->katBahaya,
                "idPengendalian"=>$request->pengendalian,
                "bukti"=>$fileName,
                "tgl_tenggat"=>$tglTenggat
            ]);
                if($detail){
                    $validation = DB::table("hse.hazard_report_validation")
                        ->insert([
                        "uid"=>$uid
                    ]);
                        if($validation){
                            $z=true;
                        }else{
            $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
            $detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
            $validation =  DB::table("hse.hazard_report_validation")->where("uid",$uid)->delete();
            $z=false;
                        }
                }else{
$headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
$detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
$z=false;
                }
            }else{
                $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
                $z=false;
            }
        return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function postHazardReportSelesai(Request $request)
    {
        $fileToUpload = $request->file('fileToUpload');
        $fileToUploadSelesai = $request->file('fileToUploadSelesai');
        $fileToUploadPJ = $request->file('fileToUploadPJ');
        $uid = uniqid();
        $z=false;
        if($request->tglTenggat==""){
            $tglTenggat = null;
        }else{
            $tglTenggat = date("Y-m-d",strtotime($request->tglTenggat));
        }
        if($request->tglSelesai==""){
            $tglSelesai = null;
        }else{
            $tglSelesai = date("Y-m-d",strtotime($request->tglSelesai));
        }
        if($request->jamSelesai==""){
            $jamSelesai = null;
        }else{
            $jamSelesai = date("H:i:s",strtotime($request->jamSelesai));
        }
        $dir = "bukti_hazard/";
        $dir1 = "bukti_hazard/update/";
        $dir2 = "bukti_hazard/penanggung_jawab/";

        $fileName = $fileToUpload->getClientOriginalName();
        $fileNameSelesai = $fileToUploadSelesai->getClientOriginalName();
        $fileNamePJ = $fileToUploadPJ->getClientOriginalName();
        if(
                ($fileToUpload->move($dir,$fileName)) &&
                ($fileToUploadSelesai->move($dir1,$fileNameSelesai)) &&
                ($fileToUploadPJ->move($dir2,$fileNamePJ))
            ) {
        $header = DB::table("hse.hazard_report_header")
            ->insert([
                "uid"=>$uid,
                "perusahaan"=>$request->perusahaan,
                "tgl_hazard"=>date("Y-m-d",strtotime($request->tgl_hazard)),
                "jam_hazard"=>date("H:i:s",strtotime($request->jam_hazard)),
                "idKemungkinan"=>$request->kemungkinan,
                "idKeparahan"=>$request->keparahan,
                "deskripsi"=>$request->deskripsi,
                "lokasi"=>$request->lokasi,
                "lokasi_detail"=>$request->lokasi_detail,
                "status_perbaikan"=>$request->status,
                "user_input"=>$request->user_input,
                "time_input"=>date("Y-m-d H:i:s")
            ]);
            if($header){
            $detail = DB::table("hse.hazard_report_detail")
                ->insert([
                "uid"=>$uid,
                "tindakan"=>$request->tindakan,
                "namaPJ"=>$request->namaPJ,
                "nikPJ"=>$request->nikPJ,
                "fotoPJ"=>$fileNamePJ,
                "katBahaya"=>$request->katBahaya,
                "idPengendalian"=>$request->pengendalian,
                "tgl_selesai"=>$tglSelesai,
                "jam_selesai"=>$jamSelesai,
                "bukti"=>$fileName,
                "update_bukti"=>$fileNameSelesai,
                "keterangan_update"=>$request->keteranganPJ,
                "idKemungkinanSesudah"=>$request->kemungkinanSesudah,
                "idKeparahanSesudah"=>$request->keparahanSesudah,
                "tgl_tenggat"=>$tglTenggat
            ]);
                if($detail){
                    $validation = DB::table("hse.hazard_report_validation")
                        ->insert([
                        "uid"=>$uid
                    ]);
                        if($validation){
                            $z=true;
                        }else{
            $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
            $detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
            $validation =  DB::table("hse.hazard_report_validation")->where("uid",$uid)->delete();
            $z=false;
                        }
                }else{
$headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
$detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
$z=false;
                }
            }else{
                $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
                $z=false;
            }
        return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function postHazardReportNoFotoSelesai(Request $request)
    {
        $fileToUpload = $request->file('fileToUpload');
        $fileToUploadSelesai = $request->file('fileToUploadSelesai');
        $fileToUploadPJ = $request->file('fileToUploadPJ');
        $uid = uniqid();
        $z=false;
        if($request->tglSelesai==""){
            $tglSelesai = null;
        }else{
            $tglSelesai = date("Y-m-d",strtotime($request->tglSelesai));
        }
        if($request->jamSelesai==""){
            $jamSelesai = null;
        }else{
            $jamSelesai = date("H:i:s",strtotime($request->jamSelesai));
        }
        $dir = "bukti_hazard/";
        $dir1 = "bukti_hazard/update/";
        $dir2 = "bukti_hazard/penanggung_jawab/";

        $fileName = $fileToUpload->getClientOriginalName();
        $fileNameSelesai = $fileToUploadSelesai->getClientOriginalName();
        $fileNamePJ = $fileToUploadPJ->getClientOriginalName();
        if(
                ($fileToUpload->move($dir,$fileName)) &&
                ($fileToUploadSelesai->move($dir1,$fileNameSelesai)) &&
                ($fileToUploadPJ->move($dir2,$fileNamePJ))
            ) {
        $header = DB::table("hse.hazard_report_header")
            ->insert([
                "uid"=>$uid,
                "perusahaan"=>$request->perusahaan,
                "tgl_hazard"=>date("Y-m-d",strtotime($request->tgl_hazard)),
                "jam_hazard"=>date("H:i:s",strtotime($request->jam_hazard)),
                "idKemungkinan"=>$request->kemungkinan,
                "idKeparahan"=>$request->keparahan,
                "deskripsi"=>$request->deskripsi,
                "lokasi"=>$request->lokasi,
                "lokasi_detail"=>$request->lokasi_detail,
                "status_perbaikan"=>$request->status,
                "user_input"=>$request->user_input,
                "time_input"=>date("Y-m-d H:i:s")
            ]);
            if($header){
            $detail = DB::table("hse.hazard_report_detail")
                ->insert([
                "uid"=>$uid,
                "tindakan"=>$request->tindakan,
                "namaPJ"=>$request->namaPJ,
                "nikPJ"=>$request->nikPJ,
                "fotoPJ"=>$fileNamePJ,
                "katBahaya"=>$request->katBahaya,
                "idPengendalian"=>$request->pengendalian,
                "tgl_selesai"=>$tglSelesai,
                "jam_selesai"=>$jamSelesai,
                "bukti"=>$fileName,
                "update_bukti"=>$fileNameSelesai,
                "keterangan_update"=>$request->keteranganPJ,
                "idKemungkinanSesudah"=>$request->kemungkinanSesudah,
                "idKeparahanSesudah"=>$request->keparahanSesudah
            ]);
                if($detail){
                    $validation = DB::table("hse.hazard_report_validation")
                        ->insert([
                        "uid"=>$uid
                    ]);
                        if($validation){
                            $z=true;
                        }else{
            $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
            $detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
            $validation =  DB::table("hse.hazard_report_validation")->where("uid",$uid)->delete();
            $z=false;
                        }
                }else{
$headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
$detail =  DB::table("hse.hazard_report_detail")->where("uid",$uid)->delete();
$z=false;
                }
            }else{
                $headerDel =  DB::table("hse.hazard_report_header")->where("uid",$uid)->delete();
                $z=false;
            }
        return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function getListHazard(Request $request)
    {
        $fDate = date("Y-m-01");
        $lDate = date("Y-m-t");
        if($request->dari && $request->sampai){
            $fDate = date("Y-m-d",strtotime($request->dari));
            $lDate = date("Y-m-d",strtotime($request->sampai));
        }
         $header =  DB::table("hse.hazard_report_header as a")
->join("hse.hazard_report_detail as b","b.uid","a.uid")
->leftjoin("hse.hazard_report_validation as c","c.uid","a.uid")
->leftjoin("hse.lokasi as d","d.idLok","a.lokasi")
->leftjoin("user_login as e","e.username","a.user_input")
->leftjoin("hse.metrik_resiko_kemungkinan as f","f.idKemungkinan","a.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as g","g.idKeparahan","a.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as h","h.idHirarki","b.idPengendalian")
->select("a.*","b.*","c.*","d.lokasi as lokasiHazard","e.nama_lengkap","f.*","g.*","h.*")
->whereBetween("a.tgl_hazard",[$fDate,$lDate])
->where("a.user_input",$request->username)
->orderBy("a.time_input","desc")
->paginate(10);

        return $header;
    }

    public function getListHazardOnline(Request $request)
    {
        $fDate = date("Y-m-01");
        $lDate = date("Y-m-t");
        if($request->dari && $request->sampai){
            $fDate = date("Y-m-d",strtotime($request->dari));
            $lDate = date("Y-m-d",strtotime($request->sampai));
        }
         $sql =  DB::table("hse.hazard_report_header as a")
->join("hse.hazard_report_detail as b","b.uid","a.uid")
->leftjoin("hse.hazard_report_validation as c","c.uid","a.uid")
->leftjoin("hse.lokasi as d","d.idLok","a.lokasi")
->leftjoin("user_login as e","e.username","a.user_input")
->leftjoin("hse.metrik_resiko_kemungkinan as f","f.idKemungkinan","a.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as g","g.idKeparahan","a.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as h","h.idHirarki","b.idPengendalian")
->select("a.*","b.*","c.*","d.lokasi as lokasiHazard","e.nama_lengkap","f.*","g.*","h.*")
->whereBetween("a.tgl_hazard",[$fDate,$lDate])
->where("a.user_input",$request->username);

      if(isset($request->user_valid)){
          if($request->user_valid=="1"){
              $filter = $sql->whereRaw("(c.option_flag='1' or c.option_flag IS NULL) and c.user_valid IS NOT NULL");
          }else if($request->user_valid=="0"){
              $filter = $sql->whereRaw("c.user_valid IS NULL");
          }else if($request->user_valid=="2"){
              $filter = $sql->whereRaw("c.option_flag ='0'");
          }else{
              $filter=$sql;
          }
      }else{
          $filter = $sql;
      }
      $header = $filter->orderBy("a.time_input","desc")
          ->paginate(10);
        return $header;
    }
    public function getListHazardSync(Request $request)
    {
        $fDate = date("Y-m-01");
        $lDate = date("Y-m-t");
        if($request->dari && $request->sampai){
            $fDate = date("Y-m-d",strtotime($request->dari));
            $lDate = date("Y-m-d",strtotime($request->sampai));
        }
         $sql =  DB::table("hse.hazard_report_header as a")
->join("hse.hazard_report_detail as b","b.uid","a.uid")
->leftjoin("hse.hazard_report_validation as c","c.uid","a.uid")
->leftjoin("hse.lokasi as d","d.idLok","a.lokasi")
->leftjoin("user_login as e","e.username","a.user_input")
->leftjoin("hse.metrik_resiko_kemungkinan as f","f.idKemungkinan","a.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as g","g.idKeparahan","a.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as h","h.idHirarki","b.idPengendalian")
->select("a.*","b.*","c.*","d.lokasi as lokasiHazard","e.nama_lengkap","f.*","g.*","h.*")
->whereBetween("a.tgl_hazard",[$fDate,$lDate])
->where("a.user_input",$request->username);

if(isset($request->user_valid)){
    if($request->user_valid=="1"){
        $filter = $sql->whereRaw("(c.option_flag='1' or c.option_flag IS NULL) and c.user_valid IS NOT NULL");
    }else if($request->user_valid=="0"){
        $filter = $sql->whereRaw("c.user_valid IS NULL");
    }else if($request->user_valid=="2"){
        $filter = $sql->whereRaw("c.option_flag ='0'");
    }else{
        $filter=$sql;
    }
}else{
    $filter = $sql;
}

$header = $filter->orderBy("a.time_input","desc")
         ->get();

        return ["data"=>$header];
    }
    public function getListHazardSyncNew(Request $request)
    {
        $fDate = date("Y-m-01");
        $lDate = date("Y-m-t");
        if($request->dari && $request->sampai){
            $fDate = date("Y-m-d",strtotime($request->dari));
            $lDate = date("Y-m-d",strtotime($request->sampai));
        }
         $sql =  DB::table("hse.hazard_report_header as a")
->join("hse.hazard_report_detail as b","b.uid","a.uid")
->leftjoin("hse.hazard_report_validation as c","c.uid","a.uid")
->leftjoin("hse.lokasi as d","d.idLok","a.lokasi")
->leftjoin("user_login as e","e.username","a.user_input")
->leftjoin("hse.metrik_resiko_kemungkinan as f","f.idKemungkinan","a.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as g","g.idKeparahan","a.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as h","h.idHirarki","b.idPengendalian")
->select("a.*","b.*","c.*","d.lokasi as lokasiHazard","e.nama_lengkap","f.*","g.*","h.*")
->whereBetween("a.tgl_hazard",[$fDate,$lDate])
->where("a.user_input",$request->username);

if(isset($request->user_valid)){
    if($request->user_valid=="1"){
        $filter = $sql->whereRaw("(c.option_flag='1' or c.option_flag IS NULL) and c.user_valid IS NOT NULL");
    }else if($request->user_valid=="0"){
        $filter = $sql->whereRaw("c.user_valid IS NULL");
    }else if($request->user_valid=="2"){
        $filter = $sql->whereRaw("c.option_flag ='0'");
    }else{
        $filter=$sql;
    }
}else{
    $filter = $sql;
}

$header = $filter->orderBy("a.time_input","desc")
         ->get();

        return ["data"=>$header];
    }
    public function getListHazardAll(Request $request)
    {
        $header =  DB::table("hse.hazard_report_header as a")
->join("hse.hazard_report_detail as b","b.uid","a.uid")
->leftjoin("hse.hazard_report_validation as c","c.uid","a.uid")
->leftjoin("hse.lokasi as d","d.idLok","a.lokasi")
->leftjoin("user_login as e","e.username","a.user_input")
->leftjoin("hse.metrik_resiko_kemungkinan as f","f.idKemungkinan","a.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as g","g.idKeparahan","a.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as h","h.idHirarki","b.idPengendalian")
->leftjoin("hse.metrik_resiko_kemungkinan as i","i.idKemungkinan","b.idKemungkinanSesudah")
->leftjoin("hse.metrik_resiko_keparahan as j","j.idKeparahan","b.idKeparahanSesudah")
->select("a.*","b.*","c.*","d.lokasi as lokasiHazard","e.nama_lengkap","f.nilai as nilaiKemungkinan","g.nilai as nilaiKeparahan","h.*","i.nilai as nilaiKemungkinanSesudah","j.nilai as nilaiKeparahanSesudah")
->orderBy("a.time_input","desc")
->paginate(10);
        return $header;
    }
    public function getItemHazard(Request $request)
    {
        $header =  DB::table("hse.hazard_report_header as a")
->join("hse.hazard_report_detail as b","b.uid","a.uid")
->leftjoin("hse.hazard_report_validation as c","c.uid","a.uid")
->leftjoin("hse.lokasi as d","d.idLok","a.lokasi")
->leftjoin("user_login as e","e.username","a.user_input")
->leftjoin("hse.metrik_resiko_kemungkinan as f","f.idKemungkinan","a.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as g","g.idKeparahan","a.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as h","h.idHirarki","b.idPengendalian")
->leftjoin("hse.metrik_resiko_kemungkinan as i","i.idKemungkinan","b.idKemungkinanSesudah")
->leftjoin("hse.metrik_resiko_keparahan as j","j.idKeparahan","b.idKeparahanSesudah")
->select("a.*","b.*","c.*","d.lokasi as lokasiHazard","e.nama_lengkap","f.nilai as nilaiKemungkinan","g.nilai as nilaiKeparahan","i.nilai as nilaiKemungkinanSesudah","j.nilai as nilaiKeparahanSesudah","h.*","f.kemungkinan as kemungkinanSebelum","g.keparahan as keparahanSebelum","i.kemungkinan as kemungkinanSesudah","j.keparahan as keparahanSesudah")
                    ->where("a.uid",$request->uid)
                    ->first();
                    $nilaiSebelum = $header->nilaiKemungkinan*$header->nilaiKeparahan;
                    $nilaiSesudah = $header->nilaiKemungkinanSesudah*$header->nilaiKeparahanSesudah;
                    $riskSebelum = DB::table("hse.metrik_resiko")
                    ->where("max",">=",$nilaiSebelum)->where("min","<=",$nilaiSebelum)
                    ->first();
                    $riskSesudah = DB::table("hse.metrik_resiko")
                    ->where("max",">=",$nilaiSesudah)
                    ->where("min","<=",$nilaiSesudah)
                    ->first();
        return array("ItemHazardList"=>$header,
            "nilaiRiskSebelum"=>$nilaiSebelum,
            "nilaiRiskSesudah"=>$nilaiSesudah,
            "RiskSebelum"=>$riskSebelum,
            "RiskSesudah"=>$riskSesudah);
    }
    public function matrikResiko(Request $request)
    {
        $matrikResiko = DB::table("hse.metrik_resiko")->get();
        return ["metrikResiko"=>$matrikResiko];
    }
    public function forgotPassword(Request $request)
    {
        $cekUser = DB::table("user_login")->where("username",$request->username)->first();
        return array("success"=>$cekUser);
    }
    public function cekUser(Request $request)
    {
        $login = DB::table("user_login")
                ->where("nik",$request->nik)
                ->count();
        if($login==0){
           $cekUser = DB::table("db_karyawan.data_karyawan")
                    ->leftJoin("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                    ->whereRaw("nik='".$request->nik."' and flag='0'")
                    ->first();
                }else{
                    $cekUser=null;
                }

        return array("dataUser"=>$cekUser);
    }
    public function getPerusahaan(Request $request)
    {
        $company = DB::table("db_karyawan.perusahaan")->get();
        return ["company"=>$company];
    }
    public function getDepartment(Request $request)
    {
        $department = DB::table("department")->where("company",$request->company)->get();
        return ["department"=>$department];
    }
    public function daftarUser(Request $request)
    {
        $cekUser = DB::table("user_login")
                    ->whereRaw("nik='".$request->nik."'")
                    ->first();
        return array("dataUser"=>$cekUser);
    }

    public function checkSection(Request $request)
    {
        $section = DB::table("section")
                    ->leftJoin("department","department.id_dept","section.id_dept")
                    ->where("section.id_dept",$request->idDept)
                    ->groupBy("section.sect")
                    ->orderBy("section.inc")
                    ->get();
        return array("section"=>$section);
    }
    public function daftarkanAkun(Request $request)
    {
        $rule = "user sarpras,menu sarpras,pengguna sarpras,user form";
        $cekUsername = DB::table("user_login")->where("username",$request->username)->count();
        if($cekUsername==0){
                    $daftar = DB::table("user_login")
                ->insert([
                    "nik"=>$request->nik,
                    "username"=>$request->username,
                    "password"=>md5($request->password),
                    "nama_lengkap"=>$request->nama,
                    "email"=>$request->email,
                    "department"=>$request->departemen,
                    "section"=>$request->devisi,
                    "rule"=>$rule,
                    "tglentry"=>date("Y-m-d")
                ]);
            if($daftar){
                return ["success"=>true,"login"=>true];
            }else{
                return ["success"=>false,"login"=>true];
            }
        }else{
                return ["success"=>false,"login"=>false];
        }

    }

    public function daftarkanAkunMitra(Request $request)
    {
        $rule = "user sarpras,menu sarpras,pengguna sarpras,user form";
        $cekUsername = DB::table("user_login")->where("username",$request->username)->count();
        if($cekUsername==0){
                    $daftar = DB::table("user_login")
                ->insert([
                    "nik"=>$request->nik,
                    "username"=>$request->username,
                    "password"=>md5($request->password),
                    "nama_lengkap"=>$request->nama,
                    "email"=>$request->email,
                    "department"=>$request->departemen,
                    "section"=>$request->devisi,
                    "perusahaan"=>$request->perusahaan,
                    "rule"=>$rule,
                    "tglentry"=>date("Y-m-d")
                ]);
            if($daftar){
                return ["success"=>true,"login"=>true];
            }else{
                return ["success"=>false,"login"=>true];
            }
        }else{
                return ["success"=>false,"login"=>false];
        }

    }

    public function updateBuktiSelesaiBergambar(Request $request)
    {
        $file = $request->file('fileToUpload');
        $uid = $request->uid;
        $idKemungkinanSesudah = $request->idKemungkinanSesudah;
        $idKeparahanSesudah = $request->idKeparahanSesudah;
        if($request->tgl_selesai==""){
            $tglSelesai = null;
        }else{
            $tglSelesai = date("Y-m-d",strtotime($request->tgl_selesai));
        }
        if($request->jam_selesai==""){
            $jamSelesai = null;
        }else{
            $jamSelesai = date("H:i:s",strtotime($request->jam_selesai));
        }
        $dir = "bukti_hazard/update";

        $fileName = $file->getClientOriginalName();
        if($file->move($dir,$fileName)) {
            $header = DB::table("hse.hazard_report_header")
                ->where("uid",$uid)
                ->update([
                "status_perbaikan"=>"SELESAI"
            ]);
            if($header>=0){
            $detail = DB::table("hse.hazard_report_detail")
                ->where("uid",$uid)
                ->update([
                "tgl_selesai"=>$tglSelesai,
                "jam_selesai"=>$jamSelesai,
                "update_bukti"=>$fileName,
                "idKemungkinanSesudah"=>$idKemungkinanSesudah,
                "idKeparahanSesudah"=>$idKeparahanSesudah,
                "keterangan_update"=>$request->keterangan
            ]);
                if($detail>=0){
                    return array("success"=>true,"error"=>"3");
                }else{
                    return array("success"=>false,"error"=>"4");
                }
            }else{
                return array("success"=>false,"error"=>"5");
            }
        }else{
            return array("success"=>false,"error"=>"5");
        }
    }
    public function updateBuktiSelesai(Request $request)
    {
        $uid = $request->uid;
        if($request->tgl_selesai==""){
            $tglSelesai = null;
        }else{
            $tglSelesai = date("Y-m-d",strtotime($request->tgl_selesai));
        }
        if($request->jam_selesai==""){
            $jamSelesai = null;
        }else{
            $jamSelesai = date("H:i:s",strtotime($request->jam_selesai));
        }
        $dir = "bukti_hazard/update";

            $header = DB::table("hse.hazard_report_header")
                ->where("uid",$uid)
                ->update([
                "status_perbaikan"=>"SELESAI"
            ]);
            if($header>=0){
                $detail = DB::table("hse.hazard_report_detail")
                ->where("uid",$uid)
                ->update([
                "tgl_selesai"=>$tglSelesai,
                "jam_selesai"=>$jamSelesai,
                "keterangan_update"=>$request->keterangan
            ]);

                if($detail>=0){
                    return array("success"=>true,"error"=>"1");
                }else{
                    return array("success"=>false,"error"=>"2");
                }
            }else
            {
                return array("success"=>false,"error"=>"2");
            }

    }
    public function getLokasi(Request $request)
    {
        $lokasi = DB::table("hse.lokasi")->get();
        return array('lokasi' => $lokasi );
    }

    public function getRisk(Request $request)
    {
        $risk = DB::table("hse.risk_category")->get();
        return array('risk' => $risk );
    }
    public function deleteHazard(Request $request)
    {
        if(isset($request->uid)){
                $deleteHazard = DB::table("hse.hazard_report_header as a")
                        ->join("hse.hazard_report_detail as b","b.uid","a.uid")
                        ->where("b.uid",$request->uid)->delete();
                        if($deleteHazard){
                            return redirect()->back()->with("success","Hazard Report Dihapus!");
                        }else{
                            return redirect()->back()->with("failed","Hazard Report Gagal Dihapus!");
                        }
        }else{
                        return redirect()->back()->with("failed","Hazard Report Gagal Dihapus!");
        }
    }
}
