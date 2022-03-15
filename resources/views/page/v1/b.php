<?php

namespace App\Http\Controllers\v1;

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
    public function rkb_form(Request $request)
    {

        if(isset($_SESSION['username'])=="") return redirect('/');
        $rkb = DB::table('e_rkb_temp')->where([
            ['user_entry',$_SESSION['username']]
        ])->get();
        if(count($rkb)>0) return redirect('/v1/temperory-rkb')->with("info","Ada Entry Yang Telah Anda Buat");
        $satuan = DB::table('satuan')->get();
        $master = DB::table('invmaster_item')->where("status",1)->get();
        return view('page.v1.rkb',["tmp_rkb"=>$rkb,"satuan"=>$satuan,"getUser"=>$this->user,"master"=>$master]);
    }
    public function rkb_post(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $dateNow = date("Y-m-d H:i:s");
        $len = count($request->id_tmp);
        $z = 0;
        //dd($request);
        foreach($request->id_tmp as $k => $v){
                $RKB_IN = DB::table('e_rkb_temp')->insert([
                    "id_rkb"        => $v,
                    "item"          => $request->part_name[$k],
                    "part_name"     => $request->item[$k],
                    "part_number"   => $request->part_number[$k],
                    "quantity"      => $request->quantity[$k],
                    "satuan"        => $request->satuan[$k],
                    "due_date"      => date("Y-m-d H:i:s",strtotime($request->due_date[$k])),
                    "remarks"       => $request->remark[$k],
                    "timelog"       => $dateNow,
                    "user_entry"    => $_SESSION['username']
                ]);
            $z++;
        }
        if($len==$z){
            $tmp_data = DB::table('e_rkb_temp')->where("user_entry",$_SESSION['username'])->get();
            return redirect('/v1/temperory-rkb')->with("success","RKB Temperory!");
        }   
    }
    public function file_post(Request $request)
    {

        if(isset($_SESSION['username'])=="") return redirect('/');
        $result=[];
            if($request->hasfile('file')){
                    $v = $request->file('file');
                    $filename   = $request->oriname."_".uniqid().".".$v->getClientOriginalExtension();
                    $fileTemp   = $v;
                    $size       = $v->getClientSize();
                    $destinationPath = '/pictures';
                    $store = $v->storeAs($destinationPath,$filename);
                    if($store){
                    if(isset($request->no_rkb)){
                        $insIMG = DB::table('e_rkb_pictures')->insert([
                                                            "id_rkb"        =>$request->oriname,
                                                            "no_rkb"        =>$request->no_rkb,
                                                            "part_name"     =>$request->part_name,
                                                            "user_entry"    =>$_SESSION['username'],
                                                            "timelog"       =>date("Y-m-d H:i:s"),
                                                            "file"          =>$filename
                                                          ]);  
                    }else{
                        $insIMG = DB::table('e_rkb_pictures')->insert([
                                                            "id_rkb"        =>$request->oriname,
                                                            "user_entry"    =>$_SESSION['username'],
                                                            "timelog"       =>date("Y-m-d H:i:s"),
                                                            "file"          =>$filename
                                                          ]);  
                    }
                        
                    if($insIMG){
                        $arr_respone = array("result"=>"OK","name"=>$filename,"ori_name"=>$request->oriname);
                        echo json_encode($arr_respone);
                        }
                    }          
            }

    }
    public function recentIMG(Request $request , $id_rkb)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $result=[];
        $img = DB::table('e_rkb_pictures')
                        ->whereRaw("id_rkb = '".$id_rkb."' ")
                        ->get();
        foreach ($img as $key => $value) {
            $obj['name'] = $value->file;
            $obj['size'] = Storage::disk("pictures")->size($value->file);
            $obj['url'] = url('rkb/detail/files/view-'.$value->file);
            $result[] = $obj;
        }
        return response()->json($result);
    }
    public function soft_delete(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $str_img    = Storage::disk("pictures")->delete($request->token_);
        if($str_img)
            {
                $file_name  = pictures::where("file",$request->token_)->delete();
                if($file_name){
                    return "OK";
                }else{
                    return "ERROR";
                }
            }else{
                return "ERROR";
            }
    }
    public function fast_delete(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        foreach ($request->keyID_ as $key => $value) {
            $file_name = pictures::where("id_rkb",$value)->get();
            foreach ($file_name as $k => $v) {
               $str_img = Storage::disk("pictures")->delete($v->file);
               if($str_img){
                    $f_Delete = pictures::where("file",$v->file)->delete();
                    if($f_Delete){
                        
                    }else{
                        return "ERROR";
                    }
               }else
               {
                    return "ERROR";
                }
            }
            
        }

    }
    public function temp_rkb(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');

            $tmp_data = DB::table('e_rkb_temp')->where("user_entry",$_SESSION['username'])->orderby("timelog","desc")->get();
            if(count($tmp_data)>0){
                return view('page.v1.tmp',["tmp_rkb"=>$tmp_data,"getUser"=>$this->user]); 
            }else{
                return redirect('/v1/form_rkb')->with("info","Temperory Rkb Not Found!");
            }
    }
    public function edit_tmp(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');

        $tmp_data = DB::table('e_rkb_temp')->where([
                                                    ["user_entry",$_SESSION['username']],
                                                    ["id_rkb",$request->id_tmp]
                                                  ])->first();

        return response()->json($tmp_data);
    }

    public function update_tmp(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $id = $request->tmp_id;
        $update_tmp = DB::table('e_rkb_temp')
                      ->where('id_rkb','=',$id)
                      ->update([
                        "part_name"     => $request->part_name,
                        "part_number"   => $request->part_number,
                        "quantity"      => $request->qty,
                        "satuan"        => $request->satuan,
                        "due_date"      => date("Y-m-d H:i:s",strtotime($request->due_date.date(" H:i:s"))),
                        "remarks"       => $request->remark
                      ]);
        if($update_tmp>=0){
            return redirect()->back()->with("success","Update Entry Success!");
        }else{
            return redirect()->back()->with("failed","Update Entry Failed!");
        }
    }
    public function satuan(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $satuan = DB::table('satuan')->get();
        return response()->json($satuan);
    }
    public function delete_entry(Request $request)
    { 
        if(isset($_SESSION['username'])=="") return redirect('/');
        $id_rkb = $request->id_rkb;
        $g = DB::table("e_rkb_temp")->where("id_rkb",$id_rkb)->first();
        $his = DB::table("e_rkb_history")->insert([
                                                "id_rkb"        => $g->id_rkb,
                                                "part_name"     => $g->part_name,
                                                "part_number"   => $g->part_number,
                                                "quantity"      => $g->quantity,
                                                "satuan"        => $g->satuan,
                                                "timelog"       => $g->timelog,
                                                "user_entry"    => $g->user_entry,
                                                "due_date"      => $g->due_date,    
                                                "remarks"      => $g->remarks    
                                                ]);
        if($his){
            $d = DB::table("e_rkb_temp")->where("id_rkb",$id_rkb)->delete();
            if($d){
                return "OK";
            }else{
                return "Failed";
            }
        }else{
            return "Failed";
        }
    }
    public function edit_all(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');

        $rkb = DB::table('e_rkb_temp')->where([
            ['user_entry',$_SESSION['username']]
        ])->get();
        //if(count($rkb)>0) return redirect('/v1/temperory-rkb')->with("success","Ada Entry Yang Telah Anda Buat");
        $master = DB::table('invmaster_item')->get();
        $satuan = DB::table('satuan')->get();
        return view('page.v1.edit',["tmp_rkb"=>$rkb,"satuan"=>$satuan,"getUser"=>$this->user,"master"=>$master]);
    }
    public function update_all(Request $request)
    {   
        if(isset($_SESSION['username'])=="") return redirect('/');
        // dd( $request);
        foreach ($request->id_tmp as $key => $value) {
            // echo $value."<br>";
        $g_tmp = DB::table('e_rkb_temp')->where([
            ["user_entry",$_SESSION['username']],
            ["id_rkb",$value]])->first();
            if($g_tmp!=null){
                $update = DB::table('e_rkb_temp')->where("id_rkb",$value)
                        ->update([
                            "item"          => $request->part_name[$key],       
                            "part_name"     => $request->item[$key],       
                            "part_number"   => $request->part_number[$key],
                            "quantity"      => $request->quantity[$key],
                            "satuan"        => $request->satuan[$key],
                            "timelog"       => date("Y-m-d H:i:s"),
                            "due_date"      => date("Y-m-d H:i:s",strtotime($request->due_date[$key].date(" H:i:s"))),
                            "remarks"      => $request->remark[$key]   
                        ]);
                echo "Update ".$update."<br>";
            }else{
                $insert = DB::table('e_rkb_temp')->insert([
                            "id_rkb"        => $value,  
                            "item"     => $request->part_name[$key],       
                            "part_name"     => $request->item[$key],       
                            "part_number"   => $request->part_number[$key],
                            "quantity"      => $request->quantity[$key],
                            "satuan"        => $request->satuan[$key],
                            "timelog"       => date("Y-m-d H:i:s"),
                            "user_entry"    => $_SESSION['username'],
                            "due_date"      => date("Y-m-d H:i:s",strtotime($request->due_date[$key].date(" H:i:s"))),
                            "remarks"       => $request->remark[$key]   
                        ]);
                echo "Insert ".$insert."<br>";    
            }
        }
        // die();
        return redirect('/v1/temperory-rkb')->with('success',"Update Entry Success!");
    }
    public function create_rkb(Request $request)
    {
      if(isset($_SESSION['username'])=="") return redirect('/');
      $rkb = DB::table('e_rkb_header')->whereYear("tgl_order",date("Y"))->count();
      $number = ($rkb+1);
      $rkbNumb = sprintf('%05d',$number);
      $nomor_rkb = $rkbNumb."/ABP/RKB/".$_SESSION['section']."/".date("Y");
      $tmp_rkb = DB::table('e_rkb_temp')
                 ->where('user_entry',$_SESSION['username'])
                 ->get();
     return $nomor_rkb;
     
    }
    public function rkb_delete(Request $request)
    {
      if(isset($_SESSION['username'])=="") return redirect('/');
      $z=0; 
      $data = DB::table('e_rkb_temp')->where("user_entry",$_SESSION['username'])->get();
      foreach ($data as $k => $g) {
        $his = DB::table('e_rkb_history')->insert([
                                                "id_rkb"        => $g->id_rkb,
                                                "part_name"     => $g->part_name,
                                                "part_number"   => $g->part_number,
                                                "quantity"      => $g->quantity,
                                                "satuan"        => $g->satuan,
                                                "timelog"       => $g->timelog,
                                                "user_entry"    => $g->user_entry,
                                                "due_date"      => date("Y-m-d H:i:s",strtotime($g->due_date)),    
                                                "remarks"      => $g->remarks    
                                                ]);
        if($his){
            $del = DB::table('e_rkb_temp')->where("id_rkb",$g->id_rkb)->delete();
            $z++;
        }
      }
      if(count($data)==$z){
        return "OK";
      }else{
        return "Error";
      }
      
    }
    public function masterPartnumber(Request $request)
    {
      if(isset($_SESSION['username'])=="") return redirect('/');
        $p = DB::table("invmaster_item")->where("item",$request->part_name)->first();
        $in = DB::table("invin_detail")->where("item",$p->item)->sum("stock_in");
        $out = DB::table("invout_detail")->where("item",$p->item)->sum("stock_out");
        $stok = $in-$out;
        $data = array("part_number"=>$p->part_number,"satuanA"=>$p->satuan,"item"=>$p->item,"part_name"=>$p->item_desc,"stok"=>$stok);
        return $data;
    }

}
