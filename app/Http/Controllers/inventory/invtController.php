<?php

namespace App\Http\Controllers\inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DateTime;


class invtController extends Controller
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

//CATEGORY    
    public function invt_category(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cat_invt = DB::table('inv_category')->orderBy("code_category","asc")->paginate(10);
        $cat_vendor = DB::table('inv_cat_vendor')->orderBy("kodeCat","asc")->paginate(10);
        $cat_item = DB::table('inv_cat_item')->orderBy("idItemCat","asc")->paginate(10);
        return view('inventory.category',["getUser"=>$this->user,"category"=>$cat_invt,"cat_vendor"=>$cat_vendor,"cat_item"=>$cat_item]);
    }
    public function categoryNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('inventory.form.category',["getUser"=>$this->user,"NewCategory"=>"OK"]);
    }
    public function postNewCat(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($request->desc=="") return redirect()->back()->with("failed","Description Cannot Null!");
        $invt = DB::table('inv_category')->where("code_category",$request->code)->count();
        if($invt>0){
            return redirect()->back()->with("failed","Insert Category Item Failed!");
        }else{
            $in_cat = DB::table("inv_category") 
                        ->insert([
                        "code_category" =>$request->code,
                        "desc_category" => $request->desc
                        ]);
            if($in_cat){
            return redirect()->back()->with("success","Insert Category Item Success!");
            }else{
            return redirect()->back()->with("failed","Insert Category Item Failed!");
            }
            return redirect()->back()->with("success","Insert Category Item Success!");
        }
    }
    public function editCat(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $editCat = DB::table("inv_category")->where("code_category",hex2bin($request->data_id))->first();
        return view('inventory.form.category',["getUser"=>$this->user,"NewCategory"=>"OK","editCat"=>$editCat,
                        "data_id"=>$request->data_id]);
    }

    public function putCat(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $code = hex2bin($request->data_id);
            $put_cat = DB::table("inv_category")
                        ->where("code_category",$code)
                        ->update([
                        "code_category" => $request->code,
                        "desc_category" => $request->desc
                        ]);
            if($put_cat>=0){
            return redirect()->back()->with("success","Update Category Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Category Item Failed!");
            }
    }
    public function statCat(Request $request,$idCat,$status)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            if($status=="enable"){
            $status = DB::table("inv_category")
                        ->where([
                                    ["code_category",hex2bin($idCat)],
                                    ["status",0]
                                ])
                        ->update([
                                    "status"=>1
                                ]);
            }elseif($status=="disable"){
            $status = DB::table("inv_category")
                        ->where([
                                    ["code_category",hex2bin($idCat)],
                                    ["status",1]
                                ])
                        ->update([
                                    "status"=>0
                                ]);

            }
            if($status>=0){
            return redirect()->back()->with("success","Update Status Category Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Status Category Item Failed!");
            }
    }
    public function delCat(Request $request,$idCat)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            $put_cat = DB::table("inv_category")
                        ->where("code_category",hex2bin($idCat))
                        ->delete();
            if($put_cat>=0){
            return redirect()->back()->with("success","Delete Category Item Success!");
            }else{
            return redirect()->back()->with("failed","Delete Category Item Failed!");
            }
    }


//CONDITION
    public function condition(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $condition = DB::table('inv_condition')->paginate(10);
        return view('inventory.condition',["getUser"=>$this->user,"condition"=>$condition]);
    }
    public function conditionNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('inventory.form.condition',["getUser"=>$this->user,"NewCondition"=>"OK"]);
    }

    public function conditionPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($request->desc=="") return redirect()->back()->with("failed","Description Cannot Null!");
        $invt = DB::table('inv_condition')->where("code",$request->code)->count();
        if($invt>0){
            return redirect()->back()->with("failed","Insert Condition Item Failed!");
        }else{
            $in_cat = DB::table("inv_condition") 
                        ->insert([
                        "code" =>$request->code,
                        "code_desc" => $request->desc
                        ]);
            if($in_cat){
            return redirect()->back()->with("success","Insert Condition Item Success!");
            }else{
            return redirect()->back()->with("failed","Insert Condition Item Failed!");
            }
            return redirect()->back()->with("success","Insert Condition Item Success!");
        }
    }

    public function editCond(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $editCon = DB::table("inv_condition")->where("code",hex2bin($request->data_id))->first();
        return view('inventory.form.condition',["getUser"=>$this->user,"NewCondition"=>"OK","editCon"=>$editCon,
                        "data_id"=>$request->data_id]);
    }
    public function putcond(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $id_Cond = hex2bin($request->data_id);
            $putCon = DB::table("inv_condition")
                        ->where("code",$id_Cond)
                        ->update([
                        "code"      =>  $request->code,
                        "code_desc" =>  $request->desc
                        ]);
            if($putCon>=0){
            return redirect()->back()->with("success","Update Condition Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Condition Item Failed!");
            }
    }
    public function statCond(Request $request,$idCon,$status)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            if($status=="enable"){
            $status = DB::table("inv_condition")
                        ->where([
                                    ["code",hex2bin($idCon)],
                                    ["status",0]
                                ])
                        ->update([
                                    "status"=>1
                                ]);
            }elseif($status=="disable"){
            $status = DB::table("inv_condition")
                        ->where([
                                    ["code",hex2bin($idCon)],
                                    ["status",1]
                                ])
                        ->update([
                                    "status"=>0
                                ]);

            }
            if($status>=0){
            return redirect()->back()->with("success","Update Status Condition Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Status Condition Item Failed!");
            }
    }
    public function delCond(Request $request,$idCon)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            $put_cat = DB::table("inv_condition")
                        ->where("code",hex2bin($idCon))
                        ->delete();
            if($put_cat>=0){
            return redirect()->back()->with("success","Delete Condition Item Success!");
            }else{
            return redirect()->back()->with("failed","Delete Condition Item Failed!");
            }
    }


//LOCATION
    public function location(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $location = DB::table('inv_location')->orderBy("code_loc")->paginate(10);
        return view('inventory.location',["getUser"=>$this->user,"location"=>$location]);
    }
    public function locationNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('inventory.form.location',["getUser"=>$this->user,"NewLocation"=>"OK"]);
    }

    public function locationPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($request->desc=="") return redirect()->back()->with("failed","Description Cannot Null!");
        $invt = DB::table('inv_location')->where("code_loc",$request->code)->count();
        if($invt>0){
            return redirect()->back()->with("failed","Insert Location Item Failed!");
        }else{
            $in_cat = DB::table("inv_location") 
                        ->insert([
                        "code_loc" =>$request->code,
                        "location" => $request->desc
                        ]);
            if($in_cat){
            return redirect()->back()->with("success","Insert Location Item Success!");
            }else{
            return redirect()->back()->with("failed","Insert Location Item Failed!");
            }
            return redirect()->back()->with("success","Insert Location Item Success!");
        }
    }

    public function editLoc(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $editLoc = DB::table("inv_location")->where("code_loc",hex2bin($request->data_id))->first();
        return view('inventory.form.location',["getUser"=>$this->user,"NewLocation"=>"OK","editLoc"=>$editLoc,
                        "data_id"=>$request->data_id]);
    }
    public function putLoc(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $id_loc = hex2bin($request->data_id);
            $putLoc = DB::table("inv_location")
                        ->where("code_loc",$id_loc )
                        ->update([
                        "code_loc" => $request->code,
                        "location" => $request->desc
                        ]);
            if($putLoc>=0){
            return redirect()->back()->with("success","Update Location Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Location Item Failed!");
            }
    }
    public function statLoc(Request $request,$idLoc,$status)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            if($status=="enable"){
            $status = DB::table("inv_location")
                        ->where([
                                    ["code_loc",hex2bin($idLoc)],
                                    ["status",0]
                                ])
                        ->update([
                                    "status"=>1
                                ]);
            }elseif($status=="disable"){
            $status = DB::table("inv_location")
                        ->where([
                                    ["code_loc",hex2bin($idLoc)],
                                    ["status",1]
                                ])
                        ->update([
                                    "status"=>0
                                ]);

            }
            if($status>=0){
            return redirect()->back()->with("success","Update Status Location Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Status Location Item Failed!");
            }
    }
    public function delLoc(Request $request,$idLoc)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            $put_cat = DB::table("inv_location")
                        ->where("code_loc",hex2bin($idLoc))
                        ->delete();
            if($put_cat>=0){
            return redirect()->back()->with("success","Delete Location Item Success!");
            }else{
            return redirect()->back()->with("failed","Delete Location Item Failed!");
            }
    }

//METHOD
    public function method(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $method = DB::table('inv_methode')->paginate(10);
        return view('inventory.method',["getUser"=>$this->user,"method"=>$method]);
    }

    public function methodNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('inventory.form.method',["getUser"=>$this->user,"NewMethod"=>"OK"]);
    }

    public function methodPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($request->desc=="") return redirect()->back()->with("failed","Description Cannot Null!");
        $invt = DB::table('inv_methode')->where("code_methode",$request->code)->count();
        if($invt>0){
            return redirect()->back()->with("failed","Insert Method Item Failed!");
        }else{
            $in_cat = DB::table("inv_methode") 
                        ->insert([
                        "code_methode" =>$request->code,
                        "code_desc" => $request->desc
                        ]);
            if($in_cat){
            return redirect()->back()->with("success","Insert Method Item Success!");
            }else{
            return redirect()->back()->with("failed","Insert Method Item Failed!");
            }
            return redirect()->back()->with("success","Insert Method Item Success!");
        }
    }

    public function editMethod(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $editMethod = DB::table("inv_methode")->where("code_methode",hex2bin($request->data_id))->first();
        return view('inventory.form.method',["getUser"=>$this->user,"NewMethod"=>"OK","editMethod"=>$editMethod,
                        "data_id"=>$request->data_id]);
    }
    public function putMethod(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $id = hex2bin($request->data_id);
            $putLoc = DB::table("inv_methode")
                        ->where("code_methode",$id )
                        ->update([
                        "code_methode"=>$request->code,
                        "code_desc" => $request->desc
                        ]);
            if($putLoc>=0){
            return redirect()->back()->with("success","Update Method Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Method Item Failed!");
            }
    }
    public function statMethod(Request $request,$idMethod,$status)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            if($status=="enable"){
            $status = DB::table("inv_methode")
                        ->where([
                                    ["code_methode",hex2bin($idMethod)],
                                    ["status",0]
                                ])
                        ->update([
                                    "status"=>1
                                ]);
            }elseif($status=="disable"){
            $status = DB::table("inv_methode")
                        ->where([
                                    ["code_methode",hex2bin($idMethod)],
                                    ["status",1]
                                ])
                        ->update([
                                    "status"=>0
                                ]);

            }
            if($status>=0){
            return redirect()->back()->with("success","Update Status Method Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Status Method Item Failed!");
            }
    }
    public function delMethod(Request $request,$idMethod)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            $del_Method = DB::table("inv_methode")
                        ->where("code_methode",hex2bin($idMethod))
                        ->delete();
            if($del_Method>=0){
            return redirect()->back()->with("success","Delete Method Item Success!");
            }else{
            return redirect()->back()->with("failed","Delete Method Item Failed!");
            }
    }
//MASTER ITEM
    public function master(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $master = DB::table('invmaster_item')
                    ->leftjoin("inv_category","inv_category.code_category","invmaster_item.category")
                    ->leftjoin("inv_cat_item","inv_cat_item.CodeCat","invmaster_item.item_cat")
                    ->select("invmaster_item.*","invmaster_item.status as statusMaster","inv_category.code_category","inv_category.desc_category","inv_cat_item.*");
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $filter = $master->whereRaw("item like '%".$cari."%' or item_desc like '%".$cari."%' or part_number like '%".$cari."%'");
        }else{
            $filter = $master;
        }
                $res = $filter->orderBy("item","desc")
                        ->paginate(10);
        return view('inventory.master',["getUser"=>$this->user,"master"=>$res]);
    }

    public function masterNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $satuan = DB::table("satuan")->orderBy("satuannya","asc")->get();
        $category = DB::table("inv_category")->orderBy("code_category","asc")->get();
        $catItem = DB::table("inv_cat_item")->orderBy("CodeCat","asc")->get();
        return view('inventory.form.master',[
                        "getUser"   =>  $this->user,
                        "masterNew" =>  "OK",
                        "satuan"    =>  $satuan,
                        "category"  =>  $category,
                        "catItem"   =>  $catItem
                    ]);
    }

    public function masterPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //if($request->desc=="") return redirect()->back()->with("failed","Description Cannot Null!");
        $invt = DB::table('invmaster_item')->where("item",$request->item)->count();
        if($invt>0){
            return redirect()->back()->with("failed","Master Item Duplicate!");
        }else{
            $in_Master = DB::table("invmaster_item") 
                        ->insert([
                        "item" =>$request->item,
                        "item_desc" => $request->desc,
                        "part_number" => $request->part_number,
                        "satuan" => $request->satuan,
                        "category" => $request->category,
                        "item_cat" => $request->catItem,
                        "minimum" => $request->minstok,
                        "user_entry" => $_SESSION['username'],
                        "timelog" => date("Y-m-d H:i:s")
                        ]);
            if($in_Master){
            return redirect()->back()->with("success","Insert Master Item Success!");
            }else{
            return redirect()->back()->with("failed","Insert Master Item Failed!");
            }
            return redirect()->back()->with("success","Insert Master Item Success!");
        }
    }

    public function editMaster(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $satuan = DB::table("satuan")->orderBy("satuannya","asc")->get();
        $category = DB::table("inv_category")->orderBy("code_category","asc")->get();
        $catItem = DB::table("inv_cat_item")->orderBy("CodeCat","asc")->get();
        $editMaster = DB::table("invmaster_item")->where("item",hex2bin($request->data_id))->first();
        return view('inventory.form.master',[
                        "getUser"=>$this->user,
                        "masterNew" =>"OK",
                        "editMaster"=>  $editMaster,
                        "satuan"    =>  $satuan,
                        "category"  =>  $category,
                        "data_id"   =>  $request->data_id,
                        "catItem"   =>  $catItem
                        ]);
    }
    public function putMaster(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $item = hex2bin($request->data_id);
            $putMaster = DB::table("invmaster_item")
                        ->where("item", $item)
                        ->update([
                        "item"      => $request->item,
                        "item_desc" => $request->desc,
                        "part_number" => $request->part_number,
                        "satuan"    => $request->satuan,
                        "category"  => $request->category,
                        "item_cat"  => $request->catItem,
                        "minimum" => $request->minstok
                        ]);
            if($putMaster>=0){
            return redirect()->back()->with("success","Update Master Item Success!");
            }else{
            return redirect()->back()->with("failed","Update Master Item Failed!");
            }
    }
    public function statMaster(Request $request,$idMaster,$status)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($status);
            if($status=="enable"){
            $status1 = DB::table("invmaster_item")
                        ->where([
                                    ["item",hex2bin($idMaster)],
                                    ["status",'0']
                                ])
                        ->update([
                                    "status"=>'1'
                                ]);
                        
            }
            if($status=="disable"){

            $status1 = DB::table("invmaster_item")
                        ->where([
                                    ["item",hex2bin($idMaster)],
                                    ["status",'1']
                                ])
                        ->update([
                                   "status"=>'0'
                              ]);
//dd(($status1));
            }
            if($status=="enable"){
            return redirect()->back()->with("success","Master Item Enable!");
            }else{
            return redirect()->back()->with("failed","Master Item Disable!");
            }
    }
    public function delMaster(Request $request,$idMaster)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            $del_Method = DB::table("invmaster_item")
                        ->where("item",hex2bin($idMaster))
                        ->delete();
            if($del_Method>=0){
            return redirect()->back()->with("success","Delete Master Item Success!");
            }else{
            return redirect()->back()->with("failed","Delete Master Item Failed!");
            }
    }

//SUPLIER
    public function suplier(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $suplier = DB::table('inv_supplier')
                    ->join("inv_cat_vendor","inv_cat_vendor.kodeCat","inv_supplier.category_vendor")
                    ->select("inv_supplier.*","inv_cat_vendor.*")
                    ->orderBy("nama_supplier")
                    ->paginate(10);
        return view('inventory.suplier',["getUser"=>$this->user,"suplier"=>$suplier]);
    }

    public function suplierNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $catVendor = DB::table("inv_cat_vendor")->get();
        return view('inventory.form.suplier',[
                        "getUser"   =>  $this->user,
                        "suplierNew" =>  "OK",
                        "catVendor"=>$catVendor
                    ]);
    }

    public function suplierPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if($request->alamat=="") return redirect()->back()->with("failed","Description Cannot Null!");
        $invt = DB::table('inv_supplier')->where("nama_supplier",$request->suplier)->count();
        if($invt>0){
            return redirect()->back()->with("failed","Insert Suplier Failed!");
        }else{
            $in_Suplier = DB::table("inv_supplier") 
                        ->insert([
                        "nama_supplier" =>$request->suplier,
                        "nama_instansi" => $request->nama_instansi,
                        "alamat" => $request->alamat,
                        "nmr_contact" => $request->phone,
                        "category_vendor"   => $request->category
                        ]);
            if($in_Suplier){
            return redirect()->back()->with("success","Insert Suplier Success!");
            }else{
            return redirect()->back()->with("failed","Insert Suplier Failed!");
            }
            return redirect()->back()->with("success","Insert Suplier Success!");
        }
    }

    public function editSuplier(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $editSuplier = DB::table("inv_supplier")->where("nama_supplier",hex2bin($request->data_id))->first();

        $catVendor  = DB::table("inv_cat_vendor")->orderBy("kodeCat","asc")->get();
        return view('inventory.form.suplier',[
                        "getUser"=>$this->user,
                        "suplierNew" =>"OK",
                        "editSuplier"=>  $editSuplier,
                        "data_id"=>$request->data_id,
                        "catVendor"=>$catVendor
                        ]);
    }
    public function putSuplier(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $code = hex2bin($request->data_id);
            $putMaster = DB::table("inv_supplier")
                        ->where("nama_supplier",$code )
                        ->update([
                        "nama_supplier" => $request->suplier,
                        "nama_instansi" => $request->nama_instansi,
                        "alamat"        => $request->alamat,
                        "nmr_contact"   => $request->phone,
                        "category_vendor"   => $request->category
                        ]);
            if($putMaster>=0){
            return redirect()->back()->with("success","Update Suplier Success!");
            }else{
            return redirect()->back()->with("failed","Update Suplier Failed!");
            }
    }
    public function statSuplier(Request $request,$suplier,$status)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            if($status=="enable"){
            $status = DB::table("inv_supplier")
                        ->where([
                                    ["nama_supplier",hex2bin($suplier)],
                                    ["status",0]
                                ])
                        ->update([
                                    "status"=>1
                                ]);
            }elseif($status=="disable"){
            $status = DB::table("inv_supplier")
                        ->where([
                                    ["nama_supplier",hex2bin($suplier)],
                                    ["status",1]
                                ])
                        ->update([
                                    "status"=>0
                                ]);

            }
            if($status>=0){
            return redirect()->back()->with("success","Update Status Suplier Success!");
            }else{
            return redirect()->back()->with("failed","Update Status Suplier Failed!");
            }
    }
    public function delSuplier(Request $request,$suplier)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            $del_Method = DB::table("inv_supplier")
                        ->where("nama_supplier",hex2bin($suplier))
                        ->delete();
            if($del_Method>=0){
            return redirect()->back()->with("success","Delete Suplier Success!");
            }else{
            return redirect()->back()->with("failed","Delete Suplier Failed!");
            }
    }

    public function stock(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        //$det=[];

            $det = DB::table("invmaster_item")
                ->WhereIn('item',function($query)
                    {
                        $query->select('item')
                              ->from('invin_detail')
                              ->whereRaw('invin_detail.item = invmaster_item.item');
                    });
            if(isset($_GET['cari'])){
                $filter = $det->whereRaw("invmaster_item.item like '%".$_GET['cari']."%' or invmaster_item.item_desc like '%".$_GET['cari']."%' or invmaster_item.part_number like '%".$_GET['cari']."%' ");
            
            }else{
                $filter = $det;
            }
        $res = $filter->orderBy("timeTransaction","desc")->paginate(10);      
  
        //dd($det);
        if(isset($_GET['notif'])){
            $notif = DB::table("status_inv")->where("item",$_GET['cari'])->update(["flag"=>"1"]);
        }
        return view('inventory.stock',["getUser"=>$this->user,"stock"=>$res]);
    }
    

    public function stockUpdateStatus(Request $request)
    {
            $det = DB::table("invmaster_item")
                ->WhereIn('item',function($query)
                    {
                        $query->select('item')
                              ->from('invin_detail')
                              ->whereRaw('invin_detail.item = invmaster_item.item');
                    })->get();   

        foreach ($det as $key => $value) {
        $inv_in = DB::table("invin_detail")->where("item",$value->item)->orderBy("date_entry","desc")->first();
        $inv_out = DB::table("invout_detail")->where("item",$value->item)->orderBy("date_entry","desc")->first();
        if(isset($inv_in) && isset($inv_out)){
        $timeIn = date("Y-m-d",strtotime($inv_in->date_entry));
        $timeOut = date("Y-m-d",strtotime($inv_out->date_entry));

            if(strtotime($timeIn) > strtotime($timeOut)){
$updateMasterItem = DB::table("invmaster_item")
                    ->where("item",$value->item)
                    ->update([
                        "timeTransaction"=>date("Y-m-d",strtotime($timeIn))
                    ]);
if($updateMasterItem){
    echo "Master Item : ".$value->item." | IN : ".date("d F Y",strtotime($timeIn))."Success".$updateMasterItem." <br>";
}else{
  echo "Master Item : ".$value->item." | IN : ".date("d F Y",strtotime($timeIn))."Error ".$updateMasterItem."<br>";  
}

            }else{
$updateMasterItem = DB::table("invmaster_item")
                    ->where("item",$value->item)
                    ->update([
                        "timeTransaction"=>date("Y-m-d",strtotime($timeOut))
                    ]);
if($updateMasterItem){
echo "Master Item : ".$value->item." | OUT : ".date("d F Y",strtotime($timeOut))."Success ".$updateMasterItem."<br>";
}else{
echo "Master Item : ".$value->item." | OUT : ".date("d F Y",strtotime($timeOut))."Error ".$updateMasterItem."<br>";
}
            }
          }elseif (isset($inv_in) && !isset($inv_out)) {
$updateMasterItem = DB::table("invmaster_item")
                    ->where("item",$value->item)
                    ->update([
                        "timeTransaction"=>date("Y-m-d",strtotime($timeIn))
                    ]);
if($updateMasterItem){
    echo "Master Item : ".$value->item." | IN : ".date("d F Y",strtotime($timeIn))."Success".$updateMasterItem." <br>";
}else{
  echo "Master Item : ".$value->item." | IN : ".date("d F Y",strtotime($timeIn))."Error ".$updateMasterItem."<br>";  
}
          }

        }
    }
    public function updateRkb(Request $request)
    {
       $rkbHead = DB::table("e_rkb_header")->whereRaw("user_close IS NULL")->get();
       foreach ($rkbHead as $key => $value) {
           $rkbDet = DB::table("e_rkb_detail")
                        ->where("no_rkb",$value->no_rkb)
                        ->get();
           $rkbPO = DB::table("e_rkb_po")
                        ->where("no_rkb",$value->no_rkb)
                        ->get();
           // echo $value->no_rkb." | ".$value->user_close." | Item =".count($rkbDet)." | PO =".count($rkbPO)."<br>";
                        if(count($rkbDet)==count($rkbPO)){
                            echo $value->no_rkb."<br>";
                            $rkbHeadClose = DB::table("e_rkb_header")
                            ->where("no_rkb",$value->no_rkb)
                            ->update([
                                "status"=>"Semua RKB Telah Close",
                                "user_close"=>"SYSTEM",
                                "time_status"=>date("Y-m-d H:i:s")
                            ]);
                        }
            // foreach ($rkbDet as $k => $v) {
            //     echo $value->no_rkb." | ".$value->user_close." | ".count($rkbDet)." = ".$v->part_name." | ".$v->part_number."<br>";
            // }
       }
    }

public function stockUser(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');

        if(isset($_GET['category'])){
            $s = $_GET['category'];
        }else{
            $s = null;
        }
        $cekRKB = DB::table("e_rkb_header")
                    ->join("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
                    ->where("e_rkb_header.dept",$_SESSION['department'])
                    ->get();
        //dd($cekRKB);
        $stock = DB::table('inventory_sys')
                ->join("invmaster_item","invmaster_item.item","inventory_sys.item")
                ->join("inv_category","inv_category.code_category","invmaster_item.category")
                ->select("inventory_sys.*","invmaster_item.*","inv_category.*");
        if($s!=null){
            
        if(isset($_GET['startDate']) && isset($_GET['endDate'])){
            $filter = $stock->whereRaw("(date(timelog) between '".date("Y-m-d",strtotime($_GET['startDate']))."' and '".date("Y-m-d",strtotime($_GET['endDate']))."') and  inv_category.code_category like '%".$s."%'")
                ->paginate(10);
        }else{
            $filter = $stock->whereRaw("inv_category.code_category like '%".$s."%'")
                ->paginate(10);
        }
                
            }else
            {
                if(isset($_GET['startDate']) && isset($_GET['endDate'])){
                    $filter = $stock->whereRaw("(date(timelog) between '".date("Y-m-d",strtotime($_GET['startDate']))."' and '".date("Y-m-d",strtotime($_GET['endDate']))."')")
                    ->paginate(10);
                 }else{
                    if(isset($_GET['cari'])){
                        $filter = $stock->whereRaw("invmaster_item.item like '%".$_GET['cari']."%' or category like '%".$_GET['cari']."%' or invmaster_item.item_desc like '%".$_GET['cari']."%'")
                    ->paginate(10);
                    }else{
                        $filter = $stock->orderBy("timeTransaction","desc")->paginate(10);
                    }
                }                
            }


        $cat   = DB::table("inv_category")->orderBy("code_category","asc")->get();
        return view('inventory.stock',["getUser"=>$this->user,"stock"=>$filter,"cat"=>$cat]);
    }    
    public function stockIn(Request $request)
    {   
        if(!isset($_SESSION['username'])) return redirect('/');
        $method = DB::table('inv_methode')->paginate(10);
        return view('inventory.form.stock',["getUser"=>$this->user,"method"=>$method,"StokIn"=>"OK"]);
    }
    public function stockFade(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        return view('inventory.form.stock',["getUser"=>$this->user,"metode"=>$request->metode,"StokInFade"=>"OK"]);
    }
    public function stockPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $uid = uniqid();
        $dateIn = date("Y-m-d H:i:s");
        $closeRKB=null;
        $cekRKB = DB::table("e_rkb_detail")->where("no_rkb",$request->no_rkb)->get();
        $jmlItem = count($cekRKB);
        $itemLen = count($request->item);
        
        if($itemLen==$jmlItem){
            $closeRKB = "Close";
        }

        foreach ($request->item as $key => $value) {
            $getItem = DB::table("invmaster_item")->where("item",$request->item_hide[$key])->first();
            $Detail = DB::table("e_rkb_detail")->where([
                            ["part_name",$request->part_name[$key]],
                            ["no_rkb",$request->no_rkb]
                        ])->first();
            $rkb_item = DB::table("e_rkb_detail")->where([
                                ["no_rkb",$request->no_rkb],
                                ["part_name",$Detail->part_name]
                                ])->first();
                if(isset($rkb_item)){
                if($request->stock_in[$key]>=$rkb_item->quantity){
                    if($closeRKB==null){
                    $closeITEM = DB::table("item_status")
                    ->insert([
                        "id_status"=>uniqid(),
                        "no_rkb"=>$request->no_rkb,
                        "part_name"=>$Detail->part_name,
                        "part_number"=>$Detail->part_number,
                        "quantity" =>$Detail->quantity,
                        "satuan"=>$Detail->satuan,
                        "remarks"=>$Detail->remarks,
                        "timelog"=>$Detail->timelog,
                        "close_user"=>"SYSTEM",
                        "close_remark"=>"Item Close Karena Stock Sudah Ada",
                        "close_date"=>$dateIn
                    ]);
                }else{                    
                    $closeITEM = DB::table("item_status")
                    ->insert([
                        "id_status"=>uniqid(),
                        "no_rkb"=>$request->no_rkb,
                        "part_name"=>$Detail->part_name,
                        "part_number"=>$Detail->part_number,
                        "quantity" =>$Detail->quantity,
                        "satuan"=>$Detail->satuan,
                        "remarks"=>$Detail->remarks,
                        "timelog"=>$Detail->timelog,
                        "close_user"=>"SYSTEM",
                        "close_remark"=>"Item Close Karena Stock Sudah Ada",
                        "close_date"=>$dateIn
                    ]);


                    $closeITEM = DB::table("e_rkb_header")
                    ->where("no_rkb",$request->no_rkb)
                    ->update([
                        "user_close"=>"SYSTEM",
                        "status"=>"Semua Item Stock Sudah Ada",
                        "time_status"=>$dateIn,
                        "no_po"=>$request->no_po
                    ]);
                }

                }else{
                    //echo "NO";
                }
                }else{
                    //echo "NO DATA";
                }
                //die();
            $invin_header = DB::table("invin_header")
                            ->insert([
                                "idInv"         =>$uid,
                                "no_rkb"        =>$request->no_rkb,
                                "part_name"     =>$Detail->part_name,
                                "part_number"   =>$Detail->part_number,
                                "no_po"         =>$request->no_po,
                                "no_surat"      =>$request->no_surat,
                                "item"          =>$request->item_hide[$key],
                                "condition"     =>$request->condition_hide[$key]
                            ]);
            if($invin_header){
                $invin_detail = DB::table("invin_detail")
                            ->insert([
                                "idInv"      =>$uid,
                                "no_rkb"     =>$request->no_rkb,
                                "item"       =>$getItem->item,
                                "item_desc"  =>$getItem->item_desc,
                                "satuan"     =>$getItem->satuan,
                                "stock_in"   =>$request->stock_in[$key],
                                "code_loc"   =>$request->location_hide[$key],
                                "methode"    =>$request->method,
                                "supplier"   =>$request->suplier_hide[$key],
                                "date_entry" =>$dateIn,
                                "user_entry" =>$_SESSION['username'],
                                "remark"     =>$request->remarks[$key]

                            ]);
                if($invin_detail){
                    $invhistory_in = DB::table("invhistory_in")
                            ->insert([
                                "no_rkb" =>$request->no_rkb,
                                "part_name" =>$Detail->part_name,
                                "part_number" =>$Detail->part_number,
                                "no_po" =>$request->no_po,
                                "no_surat" =>$request->no_surat,
                                "item" =>$getItem->item,
                                "item_desc" =>$getItem->item_desc,
                                "satuan" =>$getItem->satuan,
                                "stock_in" =>$request->stock_in[$key],
                                "code_loc" =>$request->location_hide[$key],
                                "methode" =>$request->method,
                                "supplier" =>$request->suplier_hide[$key],
                                "condition" =>$request->condition_hide[$key],
                                "date_entry" =>$dateIn,
                                "user_entry" =>$_SESSION['username'],
                                "remark" =>$request->remarks[$key]
                            ]);  
                       
                if($invhistory_in){
$cekStock = DB::table("inventory_sys")->where("item",$getItem->item)->first();      
if($cekStock){   
//$StkInBefore    = $cekStock->stock_in_before;
//$StkOutBefore   = $cekStock->stock_out_before;
$stock_in   = $cekStock->stock_in;
$stock_out   = $cekStock->stock_out;
$stock_total   = $cekStock->stock_total;

$stockNew = ($request->stock_in[$key]+$stock_in);
$stockTotalNew = ($stockNew-$stock_out);
$UpdateStock = DB::table("inventory_sys")
                ->where("item",$cekStock->item)
                ->update([
                        "stock_in"=>$stockNew,                        
                        "stock_total"=>$stockTotalNew,
                        "stok_terakhir" => $stock_total,
                        "timelog" =>$dateIn
                        ]); 
}else{
//$StkInBefore    = 0;
//$StkOutBefore   = 0;
$stock_in   = 0;
$stock_out   = 0;
$stock_total   = 0;

$stockNew = ($request->stock_in[$key]+$stock_in);
$stockTotalNew = ($stockNew-$stock_out);
$UpdateStock = DB::table("inventory_sys")
                ->insert([
                        "item"=>$getItem->item,
                        "stock_in"=>$stockNew,
                        "stock_out"=>   $stock_out,                     
                        "stock_total"=>$stockTotalNew,
                        "stok_terakhir" => $stock_total,
                        "timelog" =>$dateIn
                        ]);
}
if($UpdateStock){    
$hisStock = DB::table("inventory_history")
            ->insert([
                "item"=>$getItem->item,
                "stock_in"=>$stockNew,
                "stock_out"=>0,                     
                "stock_total"=>$stockTotalNew,
                "date_entry" =>$dateIn,
                "user_entry" =>$_SESSION['username']
            ]);
            if($hisStock){
                return redirect()->back()->with("success","Stock Updated!");
            }
}
                    }
                }

            }

            
        }
    }

    public function cekStock($item)
    {

         $cekStock = DB::table("inventory_sys")->where("item",$item)->first();
    }
    public function detailStock(Request $request, $item)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $cari=' '; 
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
        }
        $det = DB::table("invin_detail")
                    ->join("inv_location","inv_location.code_loc","invin_detail.code_loc")
                    ->join("invin_header",function($join){
                        $join->on("invin_header.idInv","invin_detail.idInv");
                        $join->on("invin_header.item","invin_detail.item");
                    })
                    ->join("invmaster_item","invmaster_item.item","invin_detail.item")
                    ->select("invin_header.*","invin_detail.*","invin_detail.user_entry as invUser","inv_location.*","invmaster_item.*");
        if(isset($_GET['cari'])){
                    $filter = $det->whereRaw("invin_detail.item = '".hex2bin($item)."' and (invin_detail.supplier like '%".$cari."%' or invin_header.condition like '%".$cari."%' or inv_location.location like '%".$cari."%' or invin_detail.remark like '%".$cari."%' or invin_header.no_rkb like '%".$cari."%') ")
                    ->paginate(10);
        }else{
                    $filter = $det->whereRaw("invin_detail.item = '".hex2bin($item)."'")
                    ->paginate(10);
        }
        //dd($filter);
        return view('inventory.detailStock',["getUser"=>$this->user,"det"=>$filter,"item"=>$item]);
    }
    public function reportItemIn(Request $request, $item)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $det = DB::table("invhistory_in")
                    ->where("item",hex2bin($item))
                    ->join("inv_location","inv_location.code_loc","invhistory_in.code_loc")
                    ->select("invhistory_in.*","inv_location.*")
                    ->paginate(10);
        
        $logExport = DB::table("export_log")->insert([
            "user_export"=>$_SESSION['username'],            
            "desc"=>"Export Stock Item ".hex2bin($item),            
            "date"=>date("Y-m-d H:i:s")
        ]);

        return view('inventory.report.itemIn',["getUser"=>$this->user,"det"=>$det,"item"=>$item]);
    }


    public function reportItemOut(Request $request, $item)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $det = DB::table("invout_detail")
                    ->leftjoin("inv_location","inv_location.code_loc","invout_detail.code_loc")
                    ->leftjoin("invout_header","invout_header.noid_out","invout_detail.noid_out")
                    ->leftjoin("invmaster_item","invmaster_item.item","invout_detail.item")
                    ->leftjoin("department","department.id_dept","invout_header.dept")
                    ->leftjoin("section","section.id_sect","invout_header.section")
                    ->select("invout_detail.*","inv_location.*","invout_header.*","department.dept as department","section.sect","invmaster_item.*")
                    ->where("invout_detail.item",hex2bin($item))
                    ->orderBy("invout_header.tglOut","desc");
              if(isset($_GET['cari'])){
                $filter = $det->whereRaw("invout_detail.user_reciever like '%".$_GET['cari']."%' or department.dept like '%".$_GET['cari']."%' or section like '%".$_GET['cari']."%' or inv_location.code_loc like '%".$_GET['cari']."%' or remark like '%".$_GET['cari']."%'")
                            ->paginate(10);
              }else{
                $filter = $det->paginate(10);
              } 
        $logExport = DB::table("export_log")->insert([
            "user_export"=>$_SESSION['username'],            
            "desc"=>"Export Stock Item ".hex2bin($item),            
            "date"=>date("Y-m-d H:i:s")
        ]);

        return view('inventory.report.itemOut',["getUser"=>$this->user,"det"=>$filter,"item"=>$item]);
    }

    
    public function detailStockOut(Request $request, $item)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $det = DB::table("invout_detail")
                    ->leftjoin("inv_location","inv_location.code_loc","invout_detail.code_loc")
                    ->leftjoin("invout_header","invout_header.noid_out","invout_detail.noid_out")
                    ->leftjoin("invmaster_item","invmaster_item.item","invout_detail.item")
                    ->leftjoin("department","department.id_dept","invout_header.dept")
                    ->leftjoin("section","section.id_sect","invout_header.section")
                    ->select("invout_detail.*","inv_location.*","invout_header.*","department.dept as department","section.sect","invmaster_item.*")
                    ->where("invout_detail.item",hex2bin($item))
                    ->orderBy("invout_header.tglOut","asc");
              if(isset($_GET['cari'])){
                $filter = $det->whereRaw("invout_detail.user_reciever like '%".$_GET['cari']."%' or department.dept like '%".$_GET['cari']."%' or section like '%".$_GET['cari']."%' or inv_location.code_loc like '%".$_GET['cari']."%' or remark like '%".$_GET['cari']."%'")
                            ->paginate(10);
              }else{
                $filter = $det->paginate(10);
              }      

        return view('inventory.detailStockOut',["getUser"=>$this->user,"det"=>$filter,"item"=>$item]);
    }

    public function stockOut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $item=[];

        $master = DB::table("invmaster_item")->get();
        foreach ($master as $key => $value) {
           $stokin = DB::table("invin_detail")->where("item",$value->item)->sum("stock_in");
           $stokout = DB::table("invout_detail")->where("item",$value->item)->sum("stock_out");
           $stok = $stokin-$stokout;
           if($stok>0){
           array_push($item, json_decode(json_encode(array("item"=>$value->item,"item_desc"=>$value->item_desc,"part_number"=>$value->part_number,"stock"=>$stok,"satuan"=>$value->satuan))));
           }
        }
        $lokasi = DB::table("inv_location")->where("status",1)->get();
        return view('inventory.form.stockOut',["getUser"=>$this->user,"StokOut"=>"OK","item"=>$item,"lokasi"=>$lokasi]);
    }
    public function stockPostOut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //dd($request);
        $cek = DB::table("invout_detail")->count();
        
        $INVNumb = sprintf('%06d',($cek+1));
        $header = DB::table("invout_header")->insert([
                    "noid_out"      => $INVNumb,
                    "user_reciever" => $request->penerima,
                    "dept"          => $request->dept,
                    "section"       => $request->section,
                    "jabatan"       => $request->jabatan,
                    "diterima_dari" => $request->dari,
                    "jabatan_a"     => $request->jabatan_a,
                    "tglOut"        => date('Y-m-d',strtotime($request->tglOut))
                    ]);
        if($header){
        foreach($request->item as $key => $value) {
        $invMaster = DB::table("invmaster_item")->where("item",$value)->first();
            if($invMaster){
        
         $invOUT = DB::table("invout_detail")
                    ->insert([
                        "noid_out"      => $INVNumb,
                        "item"          => $invMaster->item,
                        "item_desc"     => $invMaster->item_desc,
                        "stock_out"     => $request->stock_out[$key],
                        "satuan"        => $invMaster->satuan,
                        "code_loc"      => $request->lokasi,
                        "date_entry"    => date("Y-m-d H:i:s"),
                        "user_entry"    => $_SESSION['username'],
                        "user_reciever" => $request->penerima,
                        "remark"        => $request->remark[$key]
                    ]); 

$cekStock = DB::table("inventory_sys")->where("item",$value)->first();
if($cekStock){ 
$StkInBefore    = $cekStock->stock_in_before;
$StkOutBefore   = $cekStock->stock_out_before;
$stock_in   = $cekStock->stock_in;
$stock_out   = $cekStock->stock_out;
$stock_total   = $cekStock->stock_total;

$stockNew = ($request->stock_out[$key]+$stock_out);
$stockTotalNew = ($stock_in-$stockNew);
$UpdateStock = DB::table("inventory_sys")
                ->where("item",$cekStock->item)
                ->update([
                        "stock_out_before"=>$stock_out,
                        "stock_out"=>$stockNew,                        
                        "stock_total"=>$stockTotalNew
                        ]); 
}
if(isset($UpdateStock)){
 $invOUTHist = DB::table("inventory_history")
            ->insert([
                "item"          => $invMaster->item,
                "stock_in"      => 0,
                "stock_out"     => $request->stock_out[$key],
                "stock_total"   => $stockTotalNew,  
                "date_entry"    => date("Y-m-d H:i:s"),
                "user_entry"    => $_SESSION['username']
            ]);
}      
            }
        }
}
        if(isset($invOUTHist)){
            return redirect()->back()->with("success","Process Success!");
        }else{
            return redirect()->back()->with("success","Process Success!");
        }
        
    }

    public function reportStock(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $totalStock = DB::table('inventory_sys')
                        ->join("invmaster_item","invmaster_item.item","inventory_sys.item")
                        ->join("inv_category","inv_category.code_category","invmaster_item.category")
                        ->select("invmaster_item.*","inventory_sys.*","inv_category.*")
                        ->get();
        $logExport = DB::table("export_log")->insert([
            "user_export"=>$_SESSION['username'],            
            "desc"=>"Export Stock",            
            "date"=>date("Y-m-d H:i:s")
        ]);
        return view("inventory.report.stock",["totalStock"=>$totalStock]);    
    }
    public function reportStockIn(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $totalStock = DB::table('invin_header')
                        ->join("invin_detail","invin_header.idInv","invin_detail.idInv")
                        ->join("invmaster_item","invin_header.item","invmaster_item.item")
                        ->join("inv_methode","invin_detail.methode","inv_methode.code_methode")
                        ->join("inv_location","invin_detail.code_loc","inv_location.code_loc")
                        ->join("inv_category","inv_category.code_category","invmaster_item.category")
                        ->select("invmaster_item.*","invin_header.*","inv_category.*","invin_detail.*","inv_methode.*","inv_location.*")
                        ->get();
        $logExport = DB::table("export_log")->insert([
            "user_export"=>$_SESSION['username'],            
            "desc"=>"Export Stock In",            
            "date"=>date("Y-m-d H:i:s")
        ]);
        return view("inventory.report.stockIn",["totalStock"=>$totalStock]);    
    }
    public function reportStockOut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $totalStock = DB::table('invout_header')
                        ->join("invout_detail","invout_header.noid_out","invout_detail.noid_out")
                        ->join("invmaster_item","invout_detail.item","invmaster_item.item")
                        ->join("inv_location","invout_detail.code_loc","inv_location.code_loc")
                        ->join("inv_category","inv_category.code_category","invmaster_item.category")
                        ->select("invout_header.*","invout_detail.*","inv_category.*","invmaster_item.*","inv_location.*")
                        ->orderBy("invout_header.noid_out","asc")
                        ->get();
        $logExport = DB::table("export_log")->insert([
            "user_export"=>$_SESSION['username'],            
            "desc"=>"Export Stock Out",            
            "date"=>date("Y-m-d H:i:s")
        ]);
//                        dd($totalStock);
        return view("inventory.report.stockOut",["totalStock"=>$totalStock]);    
    }

    public function checkStockOut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $cek = DB::table("invout_header")
                ->join("invout_detail","invout_detail.noid_out","invout_header.noid_out")
                ->join("inv_location","inv_location.code_loc","invout_detail.code_loc")
                ->select("invout_header.*","invout_detail.*","inv_location.*")
                ->groupBy("invout_detail.noid_out")
                ->orderBy("invout_header.noid_out","desc");

        if(isset($_GET['cari'])){
            $filter = $cek->whereRaw("invout_header.noid_out like '%".$_GET['cari']."%' or invout_header.user_reciever like '%".$_GET['cari']."%' or invout_header.dept like '%".$_GET['cari']."%' or invout_header.section like '%".$_GET['cari']."%' or invout_detail.item like '%".$_GET['cari']."%' or invout_detail.code_loc like '%".$_GET['cari']."%' or inv_location.location like '%".$_GET['cari']."%' ")->paginate(10);
            }else{
                $filter = $cek->paginate(10);
            }

        if(isset($_GET['startDate'])){
$filter = $cek->whereBetween("invout_detail.date_entry",[date("Y-m-d",strtotime($_GET['startDate'])),date("Y-m-d",strtotime($_GET['endDate']))])->paginate(10);
        }

        $cat   = DB::table("inv_category")->orderBy("code_category","asc")->get();
        return view("inventory.CheckStockOut",["cek"=>$filter,"getUser"=>$this->user,"cat"=>$cat]);
    }
    public function printStockOut(Request $request, $noid_out)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $stokOut = DB::table("invout_header")
                    ->leftJoin("invout_detail","invout_detail.noid_out","invout_header.noid_out")
                    ->leftJoin("department","department.id_dept","invout_header.dept")
                    ->leftJoin("section","section.id_sect","invout_header.section")
                    ->select("invout_header.*","invout_detail.*","department.*","section.*","invout_detail.user_entry as dr_user")
                    ->groupBy("invout_header.noid_out")
                    ->where("invout_header.noid_out",hex2bin($noid_out))->first();
                    // dd(($stokOut));
        $PrintPrev = view("inventory.report.print",["noid_out"=>$noid_out,"stokOut"=>$stokOut])->render();

        $header =  view("inventory.report.header")->render();
        //return $PrintPrev;
        $pdf_output = PDF::loadHTML($PrintPrev)
                        ->setPaper('a4')
                        ->setOrientation('Portrait')
                        ->setOption('margin-top',40)
                        ->setOption('header-html',$header)
                        ->output();
        
        return response($pdf_output, 200)
            ->header('Content-Disposition' , 'filename='.("Stock out-".hex2bin($noid_out)))
               ->header('Content-Type',"application/pdf");
    }


    public function categoryVendor(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('inventory.form.category',["getUser"=>$this->user,"NewCategory"=>"Vendor"]);
    }
    public function categoryVendorPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        
        $cek = DB::table('inv_cat_vendor')->where("kodeCat",$request->code)->count();
        if($cek==0){
            $addCat = DB::table('inv_cat_vendor')
                        ->insert([
                            "kodeCat" => $request->code,
                            "CategoryVendor"    => $request->desc
                        ]);
            if($addCat){
                return redirect()->back()->with("success","Category Ditambah!");
            }else{
                return redirect()->back()->with("failed","Gagal Menambah Category!");
            }
        }else{
                return redirect()->back()->with("failed","Categori Sudah Ada!");
        }
    }
    public function categoryVendorDel(Request $request,$kode)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table("inv_cat_vendor")
                ->where("kodeCat",hex2bin($kode))
                ->count();

        if($cek){
            $delete = DB::table("inv_cat_vendor")
                        ->where("kodeCat",hex2bin($kode))
                        ->delete();
            if($delete){
                return redirect()->back()->with("success","Delete Category Success!");
            }else{
                return redirect()->back()->with("failed","Delete Category Failed!");
            }
        }else{
                return redirect()->back()->with("failed","Category Tidak Ditemukan!");
        }
    }

    public function categoryVendorEdt(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table("inv_cat_vendor")
                ->where("kodeCat",hex2bin($request->data_id))
                ->first();
        if(($cek)){
            return view('inventory.form.category',["getUser"=>$this->user,"NewCategory"=>"Vendor","data_id"=>$request->data_id ,"editCat"=>$cek]);
        }
    }
    public function categoryVendorPut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $data_id = hex2bin($request->data_id);
        $upCat = DB::table("inv_cat_vendor")
                ->where("kodeCat",$data_id)
                ->update([
                    "kodeCat"=>$request->code,
                    "CategoryVendor"=>$request->desc
                ]);
        if($upCat>0){
            return redirect()->back()->with("success","Data Telah Diupdate!");
        }else{
            return redirect()->back()->with("failed","Gagal Mengupdate Data!");
        }
    }
    public function categoryItemNew(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
            return view('inventory.form.category',["getUser"=>$this->user,"NewCategory"=>"Item"]);
    }
    public function categoryItemPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table("inv_cat_item")
                ->where("CodeCat",$request->code)
                ->count();
         if($cek>0){

            return redirect()->back()->with("failed","Code Tidak Boleh Sama!");

         }else{
            
            $in = DB::table("inv_cat_item")
                    ->insert([
                    "CodeCat"=>$request->code,
                    "DeskCat"=>$request->desc
                    ]);
            if($in){
                return redirect()->back()->with("success","Menambah Data Berhasil!");
            }else{
                return redirect()->back()->with("failed","Menambah Data Gagal!");
            }

         }      

    }
    public function categoryItemEdt(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table("inv_cat_item")
                ->where("CodeCat",hex2bin($request->data_id))
                ->first();
        if(($cek)){
            return view('inventory.form.category',["getUser"=>$this->user,"NewCategory"=>"Item","data_id"=>$request->data_id ,"editCat"=>$cek]);
        }
    }
    public function categoryItemDel($item, Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $item = (hex2bin($item));
        $del = DB::table("inv_cat_item")
                ->where("CodeCat",$item)
                ->delete();
        if($del){
            return redirect()->back()->with("success","Menghapus Data Berhasil!");
        }else{
            return redirect()->back()->with("failed","Menghapus Data Gagal!");
        }
    }

    public function detailItem(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        $detail = DB::table("invmaster_item")
                    ->join("inv_category","inv_category.code_category","invmaster_item.category")
                    ->join("inv_cat_item","inv_cat_item.CodeCat","invmaster_item.item_cat")
                    ->select("invmaster_item.*","inv_category.*","inv_cat_item.*")
                    ->where("item",hex2bin($request->idInvSys))->first();

        return view("inventory.modal",["detailItem"=>$detail,"invData"=>"OK"]);
    }
    public function detPOP(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/'); 
        $Stock =    DB::table("e_rkb_detail")
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                    ->select("e_rkb_detail.*","e_rkb_approve.*")
                    ->whereRaw("e_rkb_approve.diketahui = 1")
                    ->get();

                    
        foreach ($Stock as $keyB => $Svalue) {
            $stokIn =   DB::table("invin_header")
                        ->join("invin_detail","invin_detail.no_rkb","invin_header.no_rkb")
                        ->select("invin_header.*","invin_detail.*")
                        ->whereRaw("invin_header.no_rkb = '".$Svalue->no_rkb."' and part_name = '".$Svalue->part_name."'")
                        ->first();
            if(isset($stokIn)){
                //dd($stokIn);
                if($Svalue->quantity >= $stokIn->stock_in ){
                    $noRkb[] = $stokIn->no_rkb;
                    $items[] = $stokIn->part_name;
                    $qty[] = ($Svalue->quantity." ".$stokIn->no_rkb);
                }
            }else{
                $noRkb[] = $Svalue->no_rkb;
                $items[] = $Svalue->part_name;
                $qty[] = $Svalue->quantity;
            }
        }
        $uniq = array_unique($noRkb);
        return view("inventory.cekRKB",["invData"=>"OK","noRkb"=>$uniq,"detRKB"=>$noRkb,"items"=>$items,"qty"=>$qty]);
    }
        
    public function stokmasuk(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $master = DB::table("e_rkb_detail")
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                    ->join("invmaster_item","invmaster_item.item","e_rkb_detail.item")
                    ->whereRaw("invmaster_item.status ='1' and e_rkb_approve.diketahui='1'")
                    ->groupBy("e_rkb_detail.item")
                    ->get();
        $lokasi = DB::table("inv_location")
                    ->where("status","1")
                    ->get();
        $kondisi = DB::table("inv_condition")
                    ->where("status","1")
                    ->get();
        $vendor = DB::table("inv_supplier")
                    ->where("status","1")
                    ->get();
        return view("inventory.form.stokmasuk",
                        ["StokIn"=>"OK",
                        "master"=>$master,
                        "lokasi"=>$lokasi,
                        "kondisi"=>$kondisi,
                        "vendor"=>$vendor
                    ]);
    }
    public function itemQRcode(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        if(isset($request->jquery)=="TRUE"){
            return view("inventory.qrcode",["qrcode"=>$request->data_id,"jquery"=>true]);
        }else{
            return view("inventory.qrcode",["qrcode"=>$request->data_id]);
        }
    }
    public function listItem(Request $request,$eq)
    {        
        if(!isset($_SESSION['username'])) return redirect('/');
        $detail =[];
        $status = DB::table("item_status")->get();
        if(count($status)>0){
        foreach ($status as $key => $value) {
        
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $detail= DB::select(DB::raw("select * from e_rkb_header join e_rkb_detail on e_rkb_detail.no_rkb=e_rkb_header.no_rkb join e_rkb_approve on e_rkb_approve.no_rkb=e_rkb_detail.no_rkb join department on e_rkb_header.dept = department.id_dept join section on section.id_sect = e_rkb_header.section where e_rkb_approve.diketahui='1' and  (e_rkb_header.user_close IS NULL and (e_rkb_detail.no_rkb != '".$value->no_rkb."' and e_rkb_detail.part_name != '".$value->part_name."') and (e_rkb_detail.part_name like '%".$cari."%' or e_rkb_detail.part_number like '%".$cari."%' or e_rkb_header.dept like '%".$cari."%' or e_rkb_header.section like '%".$cari."%')order by e_rkb_detail.no_rkb asc"));
        }else{
            $detail= DB::select(DB::raw("select * from e_rkb_detail  join e_rkb_header on e_rkb_detail.no_rkb=e_rkb_header.no_rkb join e_rkb_approve on e_rkb_approve.no_rkb=e_rkb_detail.no_rkb join department on e_rkb_header.dept = department.id_dept join section on section.id_sect = e_rkb_header.section where e_rkb_approve.diketahui='1' and e_rkb_header.user_close IS NULL and (e_rkb_detail.no_rkb != '".$value->no_rkb."' and e_rkb_detail.part_name != '".$value->part_name."')  order by e_rkb_detail.no_rkb asc"));
        } 
        }
        }else{
            if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $detail= DB::select(DB::raw("select * from e_rkb_header join e_rkb_detail on e_rkb_detail.no_rkb=e_rkb_header.no_rkb join e_rkb_approve on e_rkb_approve.no_rkb=e_rkb_detail.no_rkb join department on e_rkb_header.dept = department.id_dept join section on section.id_sect = e_rkb_header.section where e_rkb_approve.diketahui='1' and (e_rkb_detail.part_name like '%".$cari."%' or e_rkb_detail.part_number like '%".$cari."%' or e_rkb_header.dept like '%".$cari."%' or e_rkb_header.section like '%".$cari."%')order by e_rkb_detail.no_rkb asc"));
            }else{
                $detail= DB::select(DB::raw("select * from e_rkb_detail  join e_rkb_header on e_rkb_detail.no_rkb=e_rkb_header.no_rkb join e_rkb_approve on e_rkb_approve.no_rkb=e_rkb_detail.no_rkb join department on e_rkb_header.dept = department.id_dept join section on section.id_sect = e_rkb_header.section where e_rkb_approve.diketahui='1' and e_rkb_header.user_close IS NULL  order by e_rkb_detail.no_rkb asc"));
            } 
        }
              //dd($detail);      
        return view("inventory.form.detRKB",["detail"=>$detail,"eq"=>$eq]);
    }
    public function listMasterItem(Request $request,$eq)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $data=[];
        $item = DB::table("invmaster_item");
        //if(isset($_GET['cari'])){
            //$cari = $_GET['cari'];
            $filter= $item->whereRaw("status=1 and item = '".$request->item."'");
        //}else{
            //$filter= $item->where("status",0);
        //}
        $res= $filter->first();
        
        $rkb = DB::table("e_rkb_detail")
                ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                ->whereRaw("item ='".$request->item."' and cancel_user IS NULL")->get();
        foreach ($rkb as $key => $value) {
            $stok = DB::table("invin_detail")
                        ->join("invin_header","invin_header.idInv","invin_detail.idInv")
                        ->where([
                                                        ["invin_detail.item",$value->item],
                                                        ["invin_header.no_rkb",$value->no_rkb]
                                                    ])->sum("stock_in");
            if($value->quantity>$stok)
            {
                array_push($data ,array("no_rkb"=>$value->no_rkb,"quantity"=>$value->quantity,"satuan"=>$value->satuan));
            }
        }
        $json = array("part_name"=>$res->item_desc,"part_number"=>$res->part_number,"eq"=>$eq,"stok"=>$data);
        //$data = json_encode($json);
        
        return ($json);
           
        
    }
    public function listLokasi(Request $request,$eq)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $item = DB::table("inv_location");
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $filter= $item->whereRaw("status=1 and code_loc like '%".$cari."%' or location like '%".$cari."%' ");
        }else{
            $filter= $item->where("status",1);
        }
        $res= $filter->get();
        //dd($res);
        return view("inventory.form.detLokasi",["detail"=>$res,"eq"=>$eq]);
        
    }
    public function listVendor(Request $request,$eq)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $item = DB::table("inv_supplier");
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $filter= $item->whereRaw("status=1 and nama_supplier like '%".$cari."%' or nama_instansi like '%".$cari."%' or alamat like '%".$cari."%' or nmr_contact like '%".$cari."%' or category_vendor like '%".$cari."%' ");
        }else{
            $filter= $item->where("status",1);
        }
        $res= $filter->get();
        //dd($res);
        return view("inventory.form.detVendor",["detail"=>$res,"eq"=>$eq]);
    }
    public function listKondisi(Request $request,$eq)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $item = DB::table("inv_condition");
        if(isset($_GET['cari'])){
            $cari = $_GET['cari'];
            $filter= $item->whereRaw("status=1 and code like '%".$cari."%' or code_desc like '%".$cari."%'");
        }else{
            $filter= $item->where("status",1);
        }
        $res= $filter->get();
        //dd($res);
        return view("inventory.form.detKondisi",["detail"=>$res,"eq"=>$eq]);
    }
    public function storeStok(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $idINV = uniqid();
        $res=false;
        $NOrkb=[];
        foreach ($request->item as $key => $value) {
            $in = DB::table("invin_header")
              ->insert([
                "idInv"=>$idINV,
                "no_rkb"=>$request->no_rkb[$key],
                "item"=>$value,
                "part_name"=>$request->part_name[$key],
                "part_number"=>$request->part_number[$key],
                "condition"=>$request->kondisi[$key]

              ]);
            if($in){
              $det = DB::table("invin_detail")
              ->insert([
                "idInv"=>$idINV,
                "item"=>$value,
                "stock_in"=>$request->stok[$key],
                "code_loc"=>$request->lokasi[$key],
                "supplier"=>$request->vendor[$key],
                "remark"=>$request->desk[$key],
                "date_entry"=>date("Y-m-d",strtotime($request->tgl[$key])),
                "user_entry"=>$_SESSION['username']
              ]); 
              if($det){
                $master = DB::table("invmaster_item")->where("item",$value)->first();
                $lokasi = DB::table("inv_location")->where("code_loc",$request->lokasi[$key])->first();
                $rkb = DB::table("item_status")
                        ->insert([
                            "id_status"=>uniqid(),
                            "no_rkb"=>$request->no_rkb[$key],
                            "part_name"=>$request->part_name[$key],
                            "part_number"=>$request->part_number[$key],
                            "quantity"=>$request->stok[$key],
                            "close_remark"=>$request->stok[$key]." ".$master->satuan." Sudah Ada Di ".$lokasi->location,
                            "timelog"=>date("Y-m-d H:i:s"),
                            "close_user"=>$_SESSION['username'],
                            "close_date"=>date("Y-m-d H:i:s")
                        ]);
                if($rkb){
                    $res = true;
                }else{
                    $res = false;
                }
              }else{
                $res = false;
              }
          }else{
            $res = false;
          }
        }
        if($res){
            return redirect()->back()->with("success","Barang Telah Di Update!");
        }else{
            return redirect()->back()->with("failed","Gagal Mengupdate Barang!");
        }
    }
    public function stokAllIn(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        # code...
        $res = DB::table("invin_header");

        if(isset($_GET['cari'])){
            $filter = $res->whereRaw("invin_detail.item like '%".$_GET['cari']."%'");
        }else{
            $filter = $res;
        }
            $det= $filter->join("invin_detail",function($j){
                        $j->on("invin_detail.idInv","invin_header.idInv");
                        $j->on("invin_detail.item","invin_header.item");
                })
                ->join("invmaster_item","invmaster_item.item","invin_detail.item")
                ->join("inv_location","inv_location.code_loc","invin_detail.code_loc")
                ->join("inv_category","inv_category.code_category","invmaster_item.category")
                ->join("inv_cat_item","inv_cat_item.CodeCat","invmaster_item.item_cat")
                ->select("invin_header.*","invin_detail.user_entry as invUser","invin_detail.*","invmaster_item.*","inv_location.*","inv_category.*","inv_cat_item.*")
                ->orderBy("date_entry")
                ->paginate();

        return view("inventory.StockInAll",["detail"=>$det,"getUser"=>$this->user]);
    }
    
    public function stokAllOut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        # code...
        $det = DB::table("invout_header")
                ->join("invout_detail",function($j){
                        $j->on("invout_detail.noid_out","invout_header.noid_out");
                })
                ->join("invmaster_item","invmaster_item.item","invout_detail.item")
                ->join("inv_location","inv_location.code_loc","invout_detail.code_loc")
                ->orderBy("invout_header","desc")
                ->paginate();
                //dd($det);
        return view("inventory.StockOutAll",["detail"=>$det,"getUser"=>$this->user]);
    }
    public function UpdateData(Request $request)
    {
        $rkb =DB::table("e_rkb_detail")
                ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_detail.no_rkb")
                ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                ->whereRaw("e_rkb_approve.diketahui='1' and e_rkb_header.user_close IS NULL")
                ->get();
                
    return redirect()->back()->with("success","Login Success!");
    }
    public function userSTOCK(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        //$det=[];

            $det = DB::table("invmaster_item")
                ->WhereIn('item',function($query)
                    {
                        $query->select('item')
                              ->from('invin_detail')
                              ->whereRaw('invin_detail.item = invmaster_item.item');
                    });
            if(isset($_GET['cari'])){
                $filter = $det->whereRaw("invmaster_item.item like '%".$_GET['cari']."%' or invmaster_item.item_desc like '%".$_GET['cari']."%' or invmaster_item.part_number like '%".$_GET['cari']."%' ");
            
            }else{
                $filter = $det;
            }
        $res = $filter->orderBy("timeTransaction","desc")->paginate(10);      
  
        //dd($det);
        if(isset($_GET['notif'])){
            $notif = DB::table("status_inv")->where("item",$_GET['cari'])->update(["flag"=>"1"]);
        }
        return view('inventory.user',["getUser"=>$this->user,"stock"=>$res]);
                

    }
    public function cekMasterItem(Request $request)
    {
        $last = DB::table("invmaster_item")->orderBy("item","desc")->first();
        $cek = DB::table("invmaster_item")->count();
        
        $INVNumb = sprintf('%08d',($cek+1));
        if(isset($last)){
            if($last->item>=$INVNumb){
               $INVNumb = sprintf('%08d',($last->item+1)); 
            }
        }
        return $INVNumb;
    }
    public function compare(Request $request)
    {

        $cek = DB::table("item_status")
                ->where([
                        ["no_rkb",$request->no_rkb],
                        ["part_name",$request->part_name]
                    ])
                ->sum("quantity");
        $stok = $request->quantity-$cek;
        echo $stok;
    }
    public function cekStokUser(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $det=[];

        $rkb = DB::table("e_rkb_detail")
                ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_detail.no_rkb")
                ->join("e_rkb_approve","e_rkb_approve.no_rkb","e_rkb_detail.no_rkb")
                ->whereRaw("e_rkb_header.dept = '".$_SESSION['department']."' and e_rkb_approve.diketahui='1' and e_rkb_detail.no_rkb='".hex2bin($request->no_rkb)."' and item ='".hex2bin($request->item)."'")
                ->get();
        foreach ($rkb as $key => $value) {
            if(isset($_GET['cari'])){
        $det = DB::table("invin_detail")
                ->join("invin_header","invin_header.idInv","invin_detail.idInv")
                ->join("invmaster_item","invmaster_item.item","invin_detail.item")
                ->whereRaw("invin_header.no_rkb='".$value->no_rkb."' and invin_header.part_name = '".$value->part_name."' and invin_header.part_number='".$value->part_number."' and (invin_detail.item like '%".$_GET['cari']."%' or invmaster_item.item_desc like '%".$_GET['cari']."%' or invin_detail.supplier like '%".$_GET['cari']."%' or invmaster_item.part_number like '%".$_GET['cari']."%')")
                ->groupBy("invin_detail.item")
                ->orderBy("invin_detail.date_entry","asc")
                ->paginate(10);
            }else{
         $det = DB::table("invin_detail")
                ->join("invin_header","invin_header.idInv","invin_detail.idInv")
                ->join("invmaster_item","invmaster_item.item","invin_detail.item")
                ->whereRaw("invin_header.no_rkb='".$value->no_rkb."' and invin_header.part_name = '".$value->part_name."' and invin_header.part_number='".$value->part_number."' ")
                ->groupBy("invin_detail.item")
                ->orderBy("invin_detail.date_entry","asc")
                ->paginate(10);
            }
        }
        return view('inventory.user',["getUser"=>$this->user,"stock"=>$det]);

    }

    public function masteritemRequest(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $satuan = DB::table("satuan")->orderBy("satuannya","asc")->get();
        $kategori = DB::table("inv_cat_item")
                    ->where("status","1")
                    ->get();
        return view('inventory.form.request',[
                        "getUser"   =>  $this->user,
                        "satuan"    =>  $satuan,
                        "kategori"    =>  $kategori
                    ]);
    }
    public function masteritemRequestPOST(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $error=null;
        
        foreach ($request->part_name as $key => $value) {
         
            $check = DB::table("master_request")->whereRaw("part_name ='".$value."' and part_number ='".$request->part_number[$key]."'")->first();
            if(isset($check)){
                $error[] =(object) (array("part_name"=>$check->part_name,"part_number"=>$check->part_number,"status"=>"Sudah Ada"));
            }else{
                $kode = uniqid();
                $userLog = DB::table("user_login")->where("section","LOGISTIC")->get();
                //dd($userLog);
                //die();
                $in = DB::table("master_request")
                    ->insert([
                                "part_name"=>$value,
                                "part_number"=>$request->part_number[$key],
                                "satuan"=>$request->satuan[$key],
                                "minimum"=>$request->stok[$key],
                                "item_cat"=>$request->kategori[$key],
                                "user_request"=>$_SESSION['username'],
                                "kode"=>$kode
                            ]);
                if($in)
                {
                    foreach($userLog as $k => $v){
                    $notif = DB::table("status_inv")
                            ->insert([
                                "idStatus"=>$kode,
                                "user_notif"=>$v->username,
                                "user_send"=>$_SESSION['username'],
                                "notif"=>"User ".$_SESSION['username']." Has Request Master Item : (".$value.") ".$request->part_number[$key]." URL: <a href=\"url('/masteritem/request/detail/log')\">Request Master Item</a>"
                            ]);
                    }
                    $error=null;
                }                    
            }
            
        }

        if($error!=null){
            //return $error;
            return redirect()->back()->with("failed",$error);
        }else{
            return redirect('/masteritem/request/detail')->with("success","Data Telah Di Kirim!");
        }
    }
    public function masteritemRequestDetail(Request $request)
    {
         if(!isset($_SESSION['username'])) return redirect('/');
         $req = DB::table("master_request")
                ->join("inv_cat_item","inv_cat_item.CodeCat","master_request.item_cat")
                ->where("user_request",$_SESSION['username'])
                ->orderBy("id","desc")
                ->paginate(10);
         return view('inventory.request',[
                        "getUser"   =>  $this->user,
                        "data"=>$req
                    ]);
    }
    public function masteritemRequestDetailLog(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
         $sql = DB::table("master_request")
                ->join("inv_cat_item","inv_cat_item.CodeCat","master_request.item_cat");
        if(isset($request->token)){
            $filter = $sql->where("kode",$request->token);
        }else{
             $filter= $sql->whereRaw("part_name like '%".$request->cari."%' or part_number like '%".$request->cari."%' or user_request like '%".$request->cari."%'");
        }  
                $req = $filter->orderBy("id","desc")->paginate(10);
         return view('inventory.request',[
                        "getUser"   =>  $this->user,
                        "data"=>$req
                    ]);
    }
    public function editRequestItem(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $edit = DB::table("master_request")->where("id",hex2bin($request->data_id))->first();
        $satuan = DB::table("satuan")->get();
        $kategori = DB::table("inv_cat_item")->where("status",'1')->get();

        return view('inventory.form.editreq',[
                        "getUser"   =>  $this->user,
                        "edit"      =>  $edit,
                        "editReq"   =>  "OK",
                        "data_id"   =>  $request->data_id,
                        "satuan"    =>  $satuan,
                        "kategori"  =>  $kategori
                    ]);
    }
    public function RequestItemPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $update = DB::table("master_request")
                    ->where("id",hex2bin($request->id))
                    ->update([
                        "part_name"=>$request->part_name,
                        "part_number"=>$request->part_number,
                        "satuan"=>$request->satuan,
                        "minimum"=>$request->minimum,
                        "item_cat"=>$request->kategori

                    ]);
        if($update>=0){
            return redirect()->back()->with("success","Mengirim Data Berhasil!");
        }else{
            return redirect()->back()->with("failed","Gagal Mengirim Data!");
        }
    }
    public function RequestCreate(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $edit = DB::table("master_request")->where("id",hex2bin($request->data_id))->first();
        $satuan = DB::table("satuan")->get();
        $kategori = DB::table("inv_cat_item")->where("status",'1')->get();
        $label = DB::table("inv_category")->where("status",'1')->get();
        
        $idMaster = DB::table("invmaster_item")->count();
        
        $last = DB::table("invmaster_item")->orderBy("item","desc")->first();
        $cek = DB::table("invmaster_item")->count();
        
        $INVNumb = sprintf('%08d',($cek+1));
        if(isset($last)){
            if($last->item>=$INVNumb){
               $INVNumb = sprintf('%08d',($last->item+1)); 
            }
        }
        return view('inventory.form.createmaster',[
                        "getUser"   =>  $this->user,
                        "edit"      =>  $edit,
                        "editReq"   =>  "OK",
                        "data_id"   =>  $request->data_id,
                        "satuan"    =>  $satuan,
                        "kategori"  =>  $kategori,
                        "label"     =>  $label,
                        "INVNumb"  =>  $INVNumb
                    ]);
    }
    public function RequestPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table("invmaster_item")->where("item",$request->kode_barang)->count();
        if($cek>0){
            return redirect()->back()->with("failed","Gagal Mendaftar Master Item Karna Kode Barang ".$request->kode_barang." sudah ada!");
        }else{
            $create = DB::table("invmaster_item")
                    ->insert([
                        "item"          =>$request->kode_barang,
                        "item_desc"     =>$request->part_name,
                        "part_number"   =>$request->part_number,
                        "category"      =>$request->label,
                        "item_cat"      =>$request->kategori,
                        "minimum"       =>$request->minimum,
                        "user_entry"    =>$_SESSION['username'],
                        "user_request"  =>$request->user_request,
                        "satuan"        =>$request->satuan
                    ]);
                    if($create){
                        $update = DB::table('master_request')->where([
                                ['part_name',$request->part_name],
                                ['part_number',$request->part_number],
                                ['user_request',$_SESSION['username']]
                            ])->update([
                                'status'=>'1'
                            ]);
                        if($update>=0){

$notif = DB::table('status_inv')->where("idStatus",$request->reqKode)->update(["flag"=>'1']);
if($notif){
    return redirect('/masteritem/request/detail/log')->with("success","Master Item Telah Di Buat!");
}else{
    return redirect('/masteritem/request/detail/log')->with("failed","Gagal Master Item Telah Di Buat!");            
}
                        }
                    }
        }
    }
    public function reportStokIn(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        # code...
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
        $res = DB::table("invin_header");

        if(isset($_GET['cari'])){
            $filter = $res->whereRaw("invin_detail.item like '%".$_GET['cari']."%'");
        }else{
            $filter = $res;
        }
            $det= $filter->join("invin_detail",function($j){
                        $j->on("invin_detail.idInv","invin_header.idInv");
                        $j->on("invin_detail.item","invin_header.item");
                })
                ->join("invmaster_item","invmaster_item.item","invin_detail.item")
                ->join("inv_location","inv_location.code_loc","invin_detail.code_loc")
                ->join("inv_category","inv_category.code_category","invmaster_item.category")
                ->join("inv_cat_item","inv_cat_item.CodeCat","invmaster_item.item_cat")
                ->orderBy("date_entry")
                ->get();

            $z=2;
            $filename = "export/All Stock In-".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('STOCK');
            $sheet->setCellValue('A1', 'Kode Barang');
            $sheet->setCellValue('B1', 'Part Name');
            $sheet->setCellValue('C1', 'Part Number');
            $sheet->setCellValue('D1', 'Stok Masuk');
            $sheet->setCellValue('E1', 'Lokasi');
            $sheet->setCellValue('F1', 'Vendor');
            $sheet->setCellValue('G1', 'Kondisi');
            $sheet->setCellValue('H1', 'Last Update');
            foreach($det as $k => $v){
            $sheet->setCellValue('A'.$z, $v->item);
            $sheet->setCellValue('B'.$z, $v->item_desc);
            $sheet->setCellValue('C'.$z, $v->part_number);
            $sheet->setCellValue('D'.$z, $v->stock_in);
            $sheet->setCellValue('E'.$z, $v->location);
            $sheet->setCellValue('F'.$z, $v->supplier);
            $sheet->setCellValue('G'.$z, $v->condition);
            $sheet->setCellValue('H'.$z, date("d F Y",strtotime($v->date_entry)));
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export data Stok In",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }
    public function reportStokAll(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
        $det = DB::table("invmaster_item")
                ->WhereIn('item',function($query)
                    {
                        $query->select('item')
                              ->from('invin_detail')
                              ->whereRaw('invin_detail.item = invmaster_item.item');
                    });
            if(isset($_GET['cari'])){
                $filter = $det->whereRaw("invmaster_item.item like '%".$_GET['cari']."%' or invmaster_item.item_desc like '%".$_GET['cari']."%' or invmaster_item.part_number like '%".$_GET['cari']."%' ");
            
            }else{
                $filter = $det;
            }
        $res = $filter->get();
        //dd($res);
        $z=2;
            if(isset($_GET['end_date'])){
                $filename = "export/Stock Sampai -".date('d F Y',strtotime($_GET['end_date'])).".xlsx";
            }else
            {
                $filename = "export/Stock Sampai -".date('d F Y').".xlsx";
            }
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('STOCK');

            $sheet->setCellValue('A1', 'Kode Barang');
            $sheet->setCellValue('B1', 'Part Name');
            $sheet->setCellValue('C1', 'Part Number');
        if(isset($_GET['end_date'])){
            $sheet->setCellValue('D1', 'Stok Masuk '.date("F",strtotime("-1 months",strtotime($_GET['end_date']))) );
            $sheet->setCellValue('E1', 'Stok Keluar '.date("F",strtotime("-1 months",strtotime($_GET['end_date']))) );
        }else{
            $sheet->setCellValue('D1', 'Stok Masuk '.date("F",strtotime("-1 months",strtotime(date('Y-m-d')))));
            $sheet->setCellValue('E1', 'Stok Keluar '.date("F",strtotime("-1 months",strtotime(date('Y-m-d')))));
        }
            $sheet->setCellValue('F1', 'Stok Masuk');
            $sheet->setCellValue('G1', 'Stok Keluar');
            $sheet->setCellValue('H1', 'Stok');
            $sheet->setCellValue('I1', 'Umur');
            $sheet->setCellValue('J1', 'Last Update');
            foreach($res as $k => $v){
                if(isset($_GET['end_date']))
                    {                        
                        $dateLAST = DB::table("invin_detail")->whereRaw("item ='".$v->item."' and date_entry <='".date("Y-m-d",strtotime($_GET['end_date']))."'" )->orderBy("date_entry","desc")->first();
                        $lastMonth = date("Y-m-d",strtotime("-1 months",strtotime($_GET['end_date'])));

                        if(isset($dateLAST)!=null){
                            $stokIn = DB::table("invin_detail")->whereRaw("item ='".$v->item."' and date_entry <='".$dateLAST->date_entry."'")->sum("stock_in");
                            $stokOut = DB::table("invout_detail")->whereRaw("item ='".$v->item."' and date_entry <='".$dateLAST->date_entry."'")->sum("stock_out");
                        }else{
                           $stokIN = 0;
                           $stokOUT = 0;  
                        }

                        
                    }else{
                        $dateLAST = DB::table("invin_detail")->whereRaw("item ='".$v->item."'" )->orderBy("date_entry","desc")->first();
                        $lastMonth = date("Y-m-d",strtotime("-1 months",strtotime(date('Y-m-d'))));

                         $stokIn = DB::table("invin_detail")->where("item",$v->item)->sum("stock_in");
                         $stokOut = DB::table("invout_detail")->where("item",$v->item)->sum("stock_out");
                    }
$lastDM = date('Y-m-t', strtotime("$lastMonth"));
$FirstDM = date('Y-m-01', strtotime("$lastMonth"));

 $stokIN_L = DB::table("invin_detail")->whereRaw("item ='".$v->item."' and (date_entry between '".$FirstDM."' and '".$lastDM."')")->sum("stock_in");
 $stokOUT_L = DB::table("invout_detail")->whereRaw("item='".$v->item."' and (date_entry between '".$FirstDM."' and '".$lastDM."')")->sum("stock_out");

                    $stok = $stokIn-$stokOut;
            $sheet->setCellValue('A'.$z, $v->item);
            $sheet->setCellValue('B'.$z, $v->item_desc);
            $sheet->setCellValue('C'.$z, $v->part_number);
            $sheet->setCellValue('D'.$z, $stokIN_L);
            $sheet->setCellValue('E'.$z, $stokOUT_L);
            $sheet->setCellValue('F'.$z, $stokIn);
            $sheet->setCellValue('G'.$z, $stokOut);
            $sheet->setCellValue('H'.$z, $stok);
             if(isset($dateLAST)){
                 $date = new DateTime($dateLAST->date_entry);
                 $now = new DateTime();
                 $interval = $now->diff($date);
             if($interval->y>0) 
             {
                  $sheet->setCellValue('I'.$z, $interval->y." Tahun, ".$interval->m." Bulan ,".$interval->d." Hari ");
             }
             if($interval->m>0){
                 $sheet->setCellValue('I'.$z, $interval->m." Bulan ,".$interval->d." Hari ");
              }else{
                 $sheet->setCellValue('I'.$z, $interval->d." Hari ");
              }
            
                $sheet->setCellValue('J'.$z, date("d F Y",strtotime($dateLAST->date_entry)));
              }
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export data Stok In",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }

    public function reportStokOutAll(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
        $det = DB::table("invout_header")
                ->join("invout_detail",function($j){
                        $j->on("invout_detail.noid_out","invout_header.noid_out");
                })
                ->join("invmaster_item","invmaster_item.item","invout_detail.item")
                ->join("inv_location","inv_location.code_loc","invout_detail.code_loc")
                ->orderBy("date_entry")
                ->paginate();

        $z=2;
            $filename = "export/Stock Out-".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('STOCK');

            $sheet->setCellValue('A1', 'Kode Barang');
            $sheet->setCellValue('B1', 'Part Name');
            $sheet->setCellValue('C1', 'Part Number');
            $sheet->setCellValue('D1', 'Stok Keluar');
            $sheet->setCellValue('E1', 'Diterima');
            $sheet->setCellValue('F1', 'Departemen');
            $sheet->setCellValue('G1', 'Devisi');
            $sheet->setCellValue('H1', 'Jabatan');
            $sheet->setCellValue('I1', 'Dari');
            $sheet->setCellValue('J1', 'Last Update');
            foreach($det as $k => $v){
            $dateLAST = DB::table("invin_detail")->where("item",$v->item)->orderBy("date_entry","desc")->first();
            $sheet->setCellValue('A'.$z, $v->item);
            $sheet->setCellValue('B'.$z, $v->item_desc);
            $sheet->setCellValue('C'.$z, $v->part_number);
            $sheet->setCellValue('D'.$z, $v->stock_out." ".$v->satuan);
            $sheet->setCellValue('E'.$z, ucwords($v->location));          
            $sheet->setCellValue('F'.$z, $v->user_reciever);     
            $sheet->setCellValue('G'.$z, $v->dept);     
            $sheet->setCellValue('H'.$z, $v->section);     
            $sheet->setCellValue('I'.$z, $v->jabatan);          
            $sheet->setCellValue('I'.$z, $v->diterima_dari);            
            $sheet->setCellValue('J'.$z, date("d F Y",strtotime($dateLAST->date_entry)));
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export data Stok In",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){
                return redirect($filename);
            }
    }
    public function exportFormat(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
        $det = DB::table("invmaster_item")
                ->WhereIn('item',function($query)
                    {
                        $query->select('item')
                              ->from('invin_detail')
                              ->whereRaw('invin_detail.item = invmaster_item.item');
                    });
            if(isset($_GET['cari'])){
                $filter = $det->whereRaw("invmaster_item.item like '%".$_GET['cari']."%' or invmaster_item.item_desc like '%".$_GET['cari']."%' or invmaster_item.part_number like '%".$_GET['cari']."%' ");
            
            }else{
                $filter = $det;
            }
        $res = $filter->get();
        //dd($res);
        $z=5;
            if(isset($_GET['end_date'])){
                $filename = "export/Stock Sampai -".date('d F Y',strtotime($_GET['end_date'])).".xlsx";
            }else
            {
                $filename = "export/Stock Sampai -".date('d F Y').".xlsx";
            }

            
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('STOCK');
            $sheet->mergeCells('A1:J2');
            $sheet->mergeCells('A3:J3');
            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(70);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(12);
            $sheet->getColumnDimension('H')->setWidth(12);
            $sheet->getColumnDimension('I')->setWidth(20);
            $sheet->getColumnDimension('J')->setWidth(20);
 //           $sheet->getColumnDimension('')->setWidth(70);
            $sheet->setCellValue('A1', 'Laporan Mutasi Barang');
            $sheet->setCellValue('A4', 'Kode Barang');
            $sheet->setCellValue('B4', 'Part Name');            
            $sheet->setCellValue('C4', 'Part Number');
 if(isset($_GET['end_date'])){
            $sheet->setCellValue('D4', 'Stok Masuk '.date("F",strtotime("-1 months",strtotime($_GET['end_date']))));
            $sheet->setCellValue('E4', 'Stok Keluar '.date("F",strtotime("-1 months",strtotime($_GET['end_date']))));
 }else{
            $sheet->setCellValue('D4', 'Stok Masuk '.date("F",strtotime("-1 months",strtotime(date('Y-m-d')))));
            $sheet->setCellValue('E4', 'Stok Keluar '.date("F",strtotime("-1 months",strtotime(date('Y-m-d')))));
}
            $sheet->setCellValue('F4', 'Stok Masuk');
            $sheet->setCellValue('G4', 'Stok Keluar');
            $sheet->setCellValue('H4', 'Stok');
            $sheet->setCellValue('I4', 'Umur');
            $sheet->setCellValue('J4', 'Last Update');
            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '333333'],
                    ],
                ],
            ];
            

            //CENTER MIDDLE WRAPTEXT
            $style = $sheet->getStyle('A1:J'.(count($res)+$z));
            $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $style->getAlignment()->setWrapText(true);

            //BACKGROUND COLOR
            $header = $sheet->getStyle('A1:J1');
                    //COLUMN SIZE
            $header->getFont()->setSize(16);
            $header->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFDA00');
            $sheet->getStyle('A4:J4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('250CE8');
            //BACKGROUND COLOR

            //FONT COLOR
            $sheet->getStyle('A4:J4')->getFont()->getColor()->setARGB('f8f8f8');
            //FONT COLOR
            $sheet->getStyle('A4')->applyFromArray($styleArray);
            $sheet->getStyle('B4')->applyFromArray($styleArray);
            $sheet->getStyle('C4')->applyFromArray($styleArray);
            $sheet->getStyle('D4')->applyFromArray($styleArray);
            $sheet->getStyle('E4')->applyFromArray($styleArray);
            $sheet->getStyle('F4')->applyFromArray($styleArray);
            $sheet->getStyle('G4')->applyFromArray($styleArray);
            $sheet->getStyle('H4')->applyFromArray($styleArray);
            $sheet->getStyle('I4')->applyFromArray($styleArray);
            $sheet->getStyle('J4')->applyFromArray($styleArray);
            foreach($res as $k => $v){

            $sheet->getStyle('A'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('B'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('C'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('D'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('E'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('F'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('G'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('H'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('I'.$z)->applyFromArray($styleArray);
            $sheet->getStyle('J'.$z)->applyFromArray($styleArray);
                if(isset($_GET['end_date']))
                    {                        
                        $dateLAST = DB::table("invin_detail")->whereRaw("item ='".$v->item."' and date_entry <='".date("Y-m-d",strtotime($_GET['end_date']))."'" )->orderBy("date_entry","desc")->first();
                        $lastMonth = date("Y-m-d",strtotime("-1 months",strtotime($_GET['end_date'])));
                        if(isset($dateLAST)!=null){
                            $stokIn = DB::table("invin_detail")->whereRaw("item ='".$v->item."' and date_entry <='".$dateLAST->date_entry."'")->sum("stock_in");
                            $stokOut = DB::table("invout_detail")->whereRaw("item ='".$v->item."' and date_entry <='".$dateLAST->date_entry."'")->sum("stock_out");
                        }else{
                           $stokIn = 0;
                           $stokIn = 0;  
                        }

                        
                    }else{
                        $dateLAST = DB::table("invin_detail")->whereRaw("item ='".$v->item."'" )->orderBy("date_entry","desc")->first();
                        $lastMonth = date("Y-m-d",strtotime("-1 months",strtotime(date('Y-m-d'))));
                         $stokIn = DB::table("invin_detail")->where("item",$v->item)->sum("stock_in");
                         $stokOut = DB::table("invout_detail")->where("item",$v->item)->sum("stock_out");
                    }

$lastDM = date('Y-m-t', strtotime("$lastMonth"));
$FirstDM = date('Y-m-01', strtotime("$lastMonth"));

 $stokIN_L = DB::table("invin_detail")->whereRaw("item ='".$v->item."' and (date_entry between '".$FirstDM."' and '".$lastDM."')")->sum("stock_in");
 $stokOUT_L = DB::table("invout_detail")->whereRaw("item='".$v->item."' and (date_entry between '".$FirstDM."' and '".$lastDM."')")->sum("stock_out");


                    $stok = $stokIn-$stokOut;
            if($stok==0){        
            $sheet->getStyle('H'.$z)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF1F07');
                }
            $sheet->setCellValue('A'.$z, $v->item);
            $sheet->setCellValue('B'.$z, $v->item_desc);
            $sheet->setCellValue('C'.$z, $v->part_number);

            $sheet->setCellValue('D'.$z, $stokIN_L);
            $sheet->setCellValue('E'.$z, $stokOUT_L);
            
            $sheet->setCellValue('F'.$z, $stokIn);
            $sheet->setCellValue('G'.$z, $stokOut);
            $sheet->setCellValue('H'.$z, $stok);
             if(isset($dateLAST)){
                 $date = new DateTime($dateLAST->date_entry);
                 $now = new DateTime();
                 $interval = $now->diff($date);
             if($interval->y>0) 
             {
                  $sheet->setCellValue('I'.$z, $interval->y." Tahun, ".$interval->m." Bulan ,".$interval->d." Hari ");
             }
             if($interval->m>0){
                 $sheet->setCellValue('I'.$z, $interval->m." Bulan ,".$interval->d." Hari ");
              }else{
                 $sheet->setCellValue('I'.$z, $interval->d." Hari ");
              }
            
                $sheet->setCellValue('J'.$z, date("d F Y",strtotime($dateLAST->date_entry)));
              }
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export data Stok In",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }
    public function datakaryawan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dt_karyawan = DB::table("db_karyawan.data_karyawan")
                        ->join("department",'department.id_dept',"db_karyawan.data_karyawan.departemen")
                        ->where("db_karyawan.data_karyawan.nik",$request->nik)
                        ->first();
        echo json_encode($dt_karyawan);
    }
}