<?php

namespace App\Http\Controllers\android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class abpenergyController extends Controller
{
    public function asyncHazardAll(Request $request)
    {
      $db="hse";
      $header = $db.".hazard_report_header";
      $detail = $db.".hazard_report_detail";
      $validation = $db.".hazard_report_validation";
      $hazard = DB::table($header." as a")
                ->leftJoin($detail." as b","b.uid","a.uid")
                ->leftJoin($validation." as c","c.uid","a.uid")
                ->get();

      return array(
                    "hazard_report"=>$hazard,
                  );
    }
    public function asyncHazardUpdate(Request $request)
    {
      $idHazard = $request->idHazard;
      dd($idHazard);
      $db="hse";
      $header = $db.".hazard_report_header";
      $detail = $db.".hazard_report_detail";
      $validation = $db.".hazard_report_validation";
      $hazard = DB::table($header." as a")
                ->leftJoin($detail." as b","b.uid","a.uid")
                ->leftJoin($validation." as c","c.uid","a.uid")
                ->whereNotIn("a.idHazard",$idHazard)
                ->get();

      return array(
                    "hazard_report"=>$hazard,
                  );
    }
    public function updateGambarBukti(Request $request)
    {
      $result = ["success"=>false];
      $dir = "bukti_hazard";
      if($request->hasFile("bukti_sebelum")){
        $buktiTemuan = $request->file("bukti_sebelum");
        $fileName = $buktiTemuan->getClientOriginalName();
        if($buktiTemuan->move($dir,$fileName)){
          $updateGambar = DB::table("hse.hazard_report_detail")->where("uid",$request->uid)->update([
            "bukti"=>$fileName
          ]);
          $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
          if($updateGambar){
            $result = ["success"=>true];
          }else{
            $result = ["success"=>false];
          }
        }else{
          $result = ["success"=>false];
        }
      }else{
        $result = ["success"=>false];
      }
      return $result;
    }
    public function updateGambarPerbaikan(Request $request)
    {
      $result = ["success"=>false];
      $dir = "bukti_hazard/update";
      if($request->hasFile("bukti_selesai")){
        $buktiTemuan = $request->file("bukti_selesai");
        $fileName = $buktiTemuan->getClientOriginalName();
        if($buktiTemuan->move($dir,$fileName)){
          $updateGambar = DB::table("hse.hazard_report_detail")->where("uid",$request->uid)->update([
            "update_bukti"=>$fileName
          ]);
          $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
          if($updateGambar){
            $result = ["success"=>true];
          }else{
            $result = ["success"=>false];
          }
        }else{
          $result = ["success"=>false];
        }
      }else{
        $result = ["success"=>false];
      }
      return $result;
    }

    public function updateDeskripsi(Request $request)
    {
      $result = ["success"=>false];
      $updateBahaya=false;
      if(isset($request->tipe)){
        $deskripsi = $request->deskripsi;
        if($request->tipe=="bahaya"){
          $table = DB::table("hse.hazard_report_header")
                        ->where("uid",$request->uid);
          $updateBahaya = $table->update([
                                    "deskripsi"=>$deskripsi
                                  ]);
          $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
        }
        if($request->tipe=="tindakan"){
          $table = DB::table("hse.hazard_report_detail")
                        ->where("uid",$request->uid);
          $updateBahaya = $table->update([
                                    "tindakan"=>$deskripsi
                                  ]);
          $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
        }
        if($request->tipe=="perbaikan"){
          $table = DB::table("hse.hazard_report_detail")
                        ->where("uid",$request->uid);
          $updateBahaya = $table->update([
                                    "keterangan_update"=>$deskripsi
                                  ]);
          $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
        }
        if($updateBahaya){
          $result = ["success"=>true];
        }else{
          $result = ["success"=>false];
        }
      }else{
        $result = ["success"=>false];
      }
      return $result;
    }
    public function updateResiko(Request $request)
    {
      $result = ["success"=>false];
      if(isset($request->tipe)){
        $resiko = $request->idResiko;
        if($request->tipe == "kemungkinan_sebelum"){
          $table = DB::table("hse.hazard_report_header")
                        ->where("uid",$request->uid);
          $updateBahaya = $table->update([
                                    "idKemungkinan"=>$resiko
                                  ]);
          $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);

        }else if($request->tipe == "keparahan_sebelum"){
          $table = DB::table("hse.hazard_report_header")
                        ->where("uid",$request->uid);
              $updateBahaya = $table->update([
                                    "idKeparahan"=>$resiko
                                  ]);
              $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
        }else if($request->tipe == "kemungkinan_sesudah"){
          $table = DB::table("hse.hazard_report_detail")
                        ->where("uid",$request->uid);
              $updateBahaya = $table->update([
                                    "idKemungkinanSesudah"=>$resiko
                                  ]);
              $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
        }else if($request->tipe == "keparahan_sesudah"){
          $table = DB::table("hse.hazard_report_detail")
                        ->where("uid",$request->uid);
              $updateBahaya = $table->update([
                                    "idKeparahanSesudah"=>$resiko
                                  ]);
              $up = DB::table("hse.hazard_report_validation")
                    ->where("uid",$request->uid)
                    ->update([
                              "user_valid"=>null,
                              "tgl_valid"=>null,
                              "jam_valid"=>null,
                              "option_flag"=>null,
                              ]);
        }
        if($updateBahaya){
          $result = ["success"=>true];
        }else{
          $result = ["success"=>false];
        }
      }else{
        $result = ["success"=>false];
      }
      return $result;
    }
}
