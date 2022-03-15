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

class StockController extends Controller
{
    ////
    private $user;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
        //event(new onlineUserEvent("USER ONLINE",$_SESSION['username']));
    }
    public function viewStock(Request $request)
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
        $res = $filter->paginate(10);      
  
        //dd($det);
        if(isset($_GET['notif'])){
            $notif = DB::table("status_inv")->where("item",$_GET['cari'])->update(["flag"=>"1"]);
        }
        return view('inventory.view.stock',["getUser"=>$this->user,"stock"=>$res]);
    }
    public function getMasterItem(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $master = DB::table("invmaster_item")
                    ->join("inv_category","inv_category.code_category","invmaster_item.category")
                    ->join("inv_cat_item","inv_cat_item.CodeCat","invmaster_item.item_cat")
                    ->get();
        
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }

            $z=2;
            $filename = "export/Master Item.xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Master Item');
            $sheet->setCellValue('A1', 'Kode Barang');
            $sheet->setCellValue('B1', 'Part Name');
            $sheet->setCellValue('C1', 'Part Number');
            $sheet->setCellValue('D1', 'Satuan');
            $sheet->setCellValue('E1', 'Label');
            $sheet->setCellValue('F1', 'Kategori');
            $sheet->setCellValue('G1', 'Stok Minimal');
            foreach($master as $k => $v){
            $sheet->setCellValue('A'.$z, $v->item);
            $sheet->setCellValue('B'.$z, $v->item_desc);
            $sheet->setCellValue('C'.$z, $v->part_number);
            $sheet->setCellValue('D'.$z, $v->satuan);
            $sheet->setCellValue('E'.$z, "( ".strtoupper($v->code_category)." ) ".strtoupper($v->desc_category));
            $sheet->setCellValue('F'.$z, $v->DeskCat);
            $sheet->setCellValue('G'.$z, $v->minimum);
            $z++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export Master Item",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){

                return redirect($filename);
            }
    }
    public function chkItemOnsite(Request $request)
    {
        $z =0 ;
        $chkRKB = DB::table("e_rkb_detail")
                         ->orderBy("id","desc")
                        ->get();
        foreach($chkRKB as $k => $v){
            $db =DB::table("invin_detail")
                ->join("invin_header",function($join){
                        $join->on("invin_header.idInv","invin_detail.idInv");   
                        $join->on("invin_header.item","invin_detail.item");   
                    })
                ->where([
                    ["invin_header.no_rkb",$v->no_rkb],
                    ["invin_header.item",$v->item]
                ])
                ->first();
                if($db){
                    echo $v->id."( ".$db->no_rkb." | ".$v->no_rkb." ) :  ".$db->part_name." ".$db->part_number." | RKB QTY : ".$v->quantity." | INV QTY:".$db->stock_in." || ";
                    if($db->stock_in<$v->quantity){
                        echo "Kurang <br><br>";
                    }else{
                        echo "<br><br>";
                    }
                $z++;
                }else{
                   // echo "Kosong <br>";
                }
        }
        echo "<br>".$z;
        // $db =DB::table("invin_detail")
        //         ->join("invin_header","invin_header.idInv","invin_detail.idInv")
        //         ->orderBy("invin_detail.date_entry","desc")
        //         ->get();
        // foreach($db as $k => $v){
        //     $chkRKB = DB::table("e_rkb_detail")
        //                 ->where([
        //                     ["no_rkb",$v->no_rkb],
        //                     ["item",$v->item],
        //                 ])
        //                 ->first();
        //                 if($chkRKB){
        //                     echo $chkRKB->id." | ".$chkRKB->part_name." = ".$v->idInv." ( ".$v->no_rkb." ) <br>";
        //                 }else{
        //                     echo "Kosong <br>";
        //                 }
        // }
    }
}
