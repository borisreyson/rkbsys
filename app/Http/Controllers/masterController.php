<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Events\onlineUserEvent;

class masterController extends Controller
{
    //
    private $user;
    private $all_user;
    private $all_rkb;
    private $all_rkb_approve;
    private $all_rkb_decline;
    private $all_rkb_cancel;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
        //event(new onlineUserEvent("USER ONLINE FROM ".$_SERVER['REMOTE_ADDR'],$_SESSION['username']));
    }
    public function index(Request $request)
    {
    	if(isset($_SESSION['username'])==""){
              return view('user.login');
    	}else{
        if($_SESSION['section']=="KTT"||$_SESSION['level']=="administrator"||$_SESSION['section']=="PURCHASING"){
        $this->all_user = DB::table('user_login')->count(); 
        $this->all_rkb = DB::table('e_rkb_header')->count();
        $this->all_rkb_approve = DB::table('e_rkb_approve')
        ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
        ->select("e_rkb_header.*","e_rkb_approve.*")
        ->where([
            ['diketahui',">",0],
            ["cancel_user",null],
            ["e_rkb_header.user_close",null]
        ])->count();
        $this->all_rkb_decline = DB::table('e_rkb_approve')
        ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
        ->select("e_rkb_header.*","e_rkb_approve.*")
        ->where([
            ['e_rkb_approve.diketahui',0],
            ["e_rkb_approve.cancel_user",null],
            ["e_rkb_header.user_close",null]
        ])->count();
        $this->all_rkb_cancel = DB::table('e_rkb_approve')->where("cancel_user","!=",null)->count();
        }elseif($_SESSION['section']=="KABAG"||$_SESSION['section']=="SECTION_HEAD"){
        $this->all_user = DB::table('user_login')->count(); 
        $this->all_rkb = DB::table('e_rkb_header')->where('dept',$_SESSION['department'])->count();
        $this->all_rkb_approve = DB::table('e_rkb_approve')
            ->leftjoin("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
            ['diketahui',">",0],
            ["cancel_user",null],
            ['e_rkb_header.dept',$_SESSION['department']]
        ])->count();
        $this->all_rkb_decline = DB::table('e_rkb_approve')
            ->leftjoin("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
            ['diketahui',0],
            ["cancel_user",null],
            ['e_rkb_header.dept',$_SESSION['department']]
        ])->count();
        $this->all_rkb_cancel = DB::table('e_rkb_approve')
            ->leftjoin("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
                                ["cancel_user","!=",null],
                                ['e_rkb_header.dept',$_SESSION['department']]
                                ])->count();
        }else if($_SESSION['section']=="BOD"){
            
        }else{

        $this->all_user = DB::table('user_login')->count(); 
        $this->all_rkb = DB::table('e_rkb_header')->where('dept',$_SESSION['department'])->count();
        $this->all_rkb_approve = DB::table('e_rkb_approve')
            ->leftjoin("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
            ['diketahui',">",0],
            ["cancel_user",null],
                                ['e_rkb_header.dept',$_SESSION['department']]
        ])->count();
        $this->all_rkb_decline = DB::table('e_rkb_approve')
            ->leftjoin("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
            ['diketahui',0],
            ["cancel_user",null],
            ['e_rkb_header.dept',$_SESSION['department']]
        ])->count();
        $this->all_rkb_cancel = DB::table('e_rkb_approve')
            ->leftjoin("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
                                ["cancel_user","!=",null],
                                ['e_rkb_header.dept',$_SESSION['department']]
                                ])->count();
        }


        if($_SESSION['section']=="KTT"){
            $data = json_encode(array(
                    "0"=>[
                        "kode"=>"user",
                        "name"=>"Total User",   
                        "color"=>"bg-blue-sky",
                        "value"=> $this->all_user,
                        "url"=> url('/user')
                        ],
                    "1"=>[
                        "kode"=>"rkb",
                        "name"=>"Total RKB",   
                        "color"=>"bg-blue",
                        "value"=> $this->all_rkb,
                        "url"=> url('/ktt/rkb')
                        ],   
                    "2"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Approve",   
                        "color"=>"rkb_success",
                        "value"=> $this->all_rkb_approve,
                        "url"=> url('/ktt/rkb?diketahui=1')
                        ],
                    "3"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Waiting",   
                        "color"=>"bg-orange",
                        "value"=> $this->all_rkb_decline,
                        "url"=> url('/ktt/rkb?approve=1')
                        ],
                    "4"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Cancel",   
                        "color"=>"bg-red",
                        "value"=> $this->all_rkb_cancel,
                        "url"=> url('/ktt/rkb?cancel=1')
                        ]
                    ));
        }elseif($_SESSION['section']=="KABAG"||$_SESSION['section']=="SECTION_HEAD"){
            $data = json_encode(array(
                    "0"=>[
                        "kode"=>"user",
                        "name"=>"Total User",   
                        "color"=>"bg-blue-sky",
                        "value"=> $this->all_user,
                        "url"=> url('/user')
                        ],
                    "1"=>[
                        "kode"=>"rkb",
                        "name"=>"Total RKB",   
                        "color"=>"bg-blue",
                        "value"=> $this->all_rkb,
                        "url"=> url('/kabag/rkb')
                        ],   
                    "2"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Approve",   
                        "color"=>"rkb_success",
                        "value"=> $this->all_rkb_approve,
                        "url"=> url('/kabag/rkb?diketahui=1')
                        ],
                    "3"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Waiting",   
                        "color"=>"bg-orange",
                        "value"=> $this->all_rkb_decline,
                        "url"=> url('/kabag/rkb?approve=1')
                        ],
                    "4"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Cancel",   
                        "color"=>"bg-red",
                        "value"=> $this->all_rkb_cancel,
                        "url"=> url('/kabag/rkb?cancel=1')
                        ]
                    ));
        }elseif($_SESSION['section']=="PURCHASING"){
    if($_SESSION['department']=="mtk") {

            $data = json_encode(array(
                    "0"=>[
                        "kode"=>"user",
                        "name"=>"Total User",   
                        "color"=>"bg-blue-sky",
                        "value"=> $this->all_user,
                        "url"=> url('/user')
                        ],
                    "1"=>[
                        "kode"=>"rkb",
                        "name"=>"Total RKB",   
                        "color"=>"bg-blue",
                        "value"=> $this->all_rkb,
                        "url"=> url('/mtk/rkb')
                        ],   
                    "2"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Approve",   
                        "color"=>"rkb_success",
                        "value"=> $this->all_rkb_approve,
                        "url"=> url('/mtk/rkb?diketahui=1')
                        ],
                    "3"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Waiting",   
                        "color"=>"bg-orange",
                        "value"=> $this->all_rkb_decline,
                        "url"=> url('/mtk/rkb?approve=1')
                        ],
                    "4"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Cancel",   
                        "color"=>"bg-red",
                        "value"=> $this->all_rkb_cancel,
                        "url"=> url('/mtk/rkb?cancel=1')
                        ]
                    ));
            }else{
            $data = json_encode(array(
                    "0"=>[
                        "kode"=>"user",
                        "name"=>"Total User",   
                        "color"=>"bg-blue-sky",
                        "value"=> $this->all_user,
                        "url"=> url('/user')
                        ],
                    "1"=>[
                        "kode"=>"rkb",
                        "name"=>"Total RKB",   
                        "color"=>"bg-blue",
                        "value"=> $this->all_rkb,
                        "url"=> url('/logistic/rkb')
                        ],   
                    "2"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Approve",   
                        "color"=>"rkb_success",
                        "value"=> $this->all_rkb_approve,
                        "url"=> url('/logistic/rkb?diketahui=1')
                        ],
                    "3"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Waiting",   
                        "color"=>"bg-orange",
                        "value"=> $this->all_rkb_decline,
                        "url"=> url('/logistic/rkb?approve=1')
                        ],
                    "4"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Cancel",   
                        "color"=>"bg-red",
                        "value"=> $this->all_rkb_cancel,
                        "url"=> url('/logistic/rkb?cancel=1')
                        ]
                    ));
            }
        }elseif($_SESSION['level']=="administrator"){
             $data = json_encode(array(
                    "0"=>[
                        "kode"=>"user",
                        "name"=>"Total User",   
                        "color"=>"bg-blue-sky",
                        "value"=> $this->all_user,
                        "url"=> url('/user')
                        ],
                    "1"=>[
                        "kode"=>"rkb",
                        "name"=>"Total RKB",   
                        "color"=>"bg-blue",
                        "value"=> $this->all_rkb,
                        "url"=> url('/admin/rkb')
                        ],   
                    "2"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Approve",   
                        "color"=>"rkb_success",
                        "value"=> $this->all_rkb_approve,
                        "url"=> url('/admin/rkb?diketahui=1')
                        ],
                    "3"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Waiting",   
                        "color"=>"bg-orange",
                        "value"=> $this->all_rkb_decline,
                        "url"=> url('/admin/rkb?approve=1')
                        ],
                    "4"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Cancel",   
                        "color"=>"bg-red",
                        "value"=> $this->all_rkb_cancel,
                        "url"=> url('/admin/rkb?cancel=1')
                        ]
                    ));
        }else{
            $data = json_encode(array(
                    "0"=>[
                        "kode"=>"user",
                        "name"=>"Total User",   
                        "color"=>"bg-blue-sky",
                        "value"=> $this->all_user,
                        "url"=> url('/user')
                        ],
                    "1"=>[
                        "kode"=>"rkb",
                        "name"=>"Total RKB",   
                        "color"=>"bg-blue",
                        "value"=> $this->all_rkb,
                        "url"=> url('/v3/rkb')
                        ],   
                    "2"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Approve",   
                        "color"=>"rkb_success",
                        "value"=> $this->all_rkb_approve,
                        "url"=> url('/v3/rkb?diketahui=1')
                        ],
                    "3"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Waiting",   
                        "color"=>"bg-orange",
                        "value"=> $this->all_rkb_decline,
                        "url"=> url('/v3/rkb?approve=1')
                        ],
                    "4"=>[
                        "kode"=>"rkb",
                        "name"=>"RKB Cancel",   
                        "color"=>"bg-red",
                        "value"=> $this->all_rkb_cancel,
                        "url"=> url('/v3/rkb?cancel=1')
                        ]
                    ));
            //dd($_SESSION['department']);
        }
if($_SESSION['section']=="PURCHASING") 
    if($_SESSION['department']=="mtk") {
        return view('page.logistik_mtk.index',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
    }else{
        return view('page.logistic.index',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
    }
    
if($_SESSION['section']=="KABAG"||$_SESSION['section']=="SECTION_HEAD") return view('page.kabag.index',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
if($_SESSION['section']=="KTT") return view('page.ktt.index',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
if($_SESSION['section']=="BOD") return view('page.ktt.bod',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
if($_SESSION['level']=="administrator") return view('page.admin.index',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
return view('page.index',["getUser"=>$this->user,"DaTa"=>$data,"total_rkb"=>$this->all_rkb]);
    	}
    }
//DEPARTMENT
    public function dept(Request $request)
    {
        if(isset($_SESSION['username'])==""){
            return redirect('/');
        }else{
            $dept = DB::table('department')->paginate(10);
            return view('page.master.dept',["dept"=>$dept,"getUser"=>$this->user]);
        }
    }
    public function form_dept(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('page.master.form_dept',["getUser"=>$this->user]);
    }   
    public function submit_dept(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table('department')->whereRaw("id_dept='".$request->id_department."' or dept='".$request->department."'")->get();
        if(count($cek)==0){
            $insert_dept = DB::table('department')->insert([
                "id_dept"          =>  $request->id_department,
                "dept"          =>  $request->department,
                "user_entry"    =>  $_SESSION['username'],
                "timelog"       =>  date("Y-m-d H:i:s"),
            ]);
            if($insert_dept){
                return redirect('/dept')->with('success','Department Added!');
            }else{
                return redirect()->back()->with('failed','Add New Department Failed!');
            }
        }else{
                return redirect()->back()->with('failed','Duplicate Department! Please Use Different Department!!');
            }
    }
    public function get_dept(Request $request,$iddept,$dept)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $get_dept = DB::table('department')->where([
                    ["id_dept",hex2bin($iddept)],
                    ["dept",hex2bin($dept)]
                    ])->first();
        return view('page.master.form_dept',["edit_dept"=>$get_dept,"getUser"=>$this->user]);
    }
    public function update_dept(Request $request,$iddept,$dept)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up_dept = DB::table('department')
        ->where([
                    ["id_dept",hex2bin($iddept)],
                    ["dept",hex2bin($dept)]
                ])
        ->update([
            "id_dept"          =>  $request->id_department,
            "dept"          =>  $request->department,
            "timelog"       =>  date("Y-m-d H:i:s"),
        ]);

        if($up_dept){
            return redirect('/dept')->with('success','Department Updated!');
        }else{
            return redirect()->back()->with('failed','Update Department Failed!');
        }
    }
    public function del_dept(Request $request,$sect)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $del_dept = DB::table('department')
                    ->where("sect",$sect)
                    ->delete();
        if($del_dept){
            return redirect('/dept')->with('success','Delete departments and sections successfully!');
        }else{
            return redirect()->back()->with('failed','Delete Department Or Section Failed!');
        }
    }
//SATUAN
    public function satuan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $satuan = DB::table('satuan');
        if(isset($_GET['cari']))
        {
            $filter = $satuan->whereRaw("satuannya like '%".$_GET['cari']."%'");
        }else
        {
            $filter = $satuan;
        }
        $data = $filter->orderBy('timelog','desc')->paginate(10);
        return view('page.master.satuan',["satuan"=>$data,"getUser"=>$this->user]);
    }    
    public function form_satuan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        return view('page.master.form_satuan',["getUser"=>$this->user]);
    }   
    public function submit_satuan(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $in_satuan = DB::table('satuan')
                    ->insert([
                        "satuannya"     =>  $request->satuan,
                        "user_entry"    =>  $_SESSION['username'],
                        "timelog"       =>  date("Y-m-d H:i:s"),
                    ]);
        if($in_satuan){
            return redirect('/satuan')->with('success','Units successfully added!');
        }else{
            return redirect()->back()->with('failed','Adding Units Failed!');
        }
    }
    public function get_satuan(Request $request,$no)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $edit_satuan = DB::table('satuan')->where('no',$no)->first();
        return view('page.master.form_satuan',["edit_satuan"=>$edit_satuan,"getUser"=>$this->user]);

    }
    public function up_satuan(Request $request,$no)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up_satuan = DB::table('satuan')
                    ->where('no',$no)
                    ->update([
                        "satuannya"     =>  $request->satuan,
                        "timelog"       =>  date("Y-m-d H:i:s"),
                    ]);
        if($up_satuan){
            return redirect('/satuan')->with('success','Update Satuan Berhasil!');
        }else{
            return redirect()->back()->with('failed','Gagal Mengupdate Satuan!');
        }
    }
    public function del_satuan(Request $request,$no)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $up_satuan = DB::table('satuan')
                    ->where('no',$no)
                    ->delete();
        if($up_satuan){
            return redirect('/satuan')->with('success','Update Satuan Berhasil!');
        }else{
            return redirect()->back()->with('failed','Gagal Mengupdate Satuan!');
        }
    }
    public function section(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $section = DB::table('section')->where('id_dept',urldecode($request->dept))->get();
            $option = "<option value=\"\">-- Pilih -- </option>";
        foreach ($section as $key => $value) {
            if($request->selected==$value->id_sect){
                $option .= "<option value=\"".$value->id_sect."\" selected=\"selected\">".$value->sect."</option>";
            }else{
                $option .= "<option value=\"".$value->id_sect."\">".$value->sect."</option>";
            }
        }
        return $option;
    }
    public function sect(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $sect = DB::table('section')
                ->join("department","department.id_dept","=","section.id_dept")
                ->select("department.*","section.*")
                ->paginate(10);
        return view('page.master.section',["sect"=>$sect,"getUser"=>$this->user]);
    }
    public function sect_form(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $dept = DB::table('department')->get();
        $sect = DB::table('section')->get();
        return view('page.master.form_sect',["dept"=>$dept,"sect"=>$sect,"getUser"=>$this->user]);
    }
    public function sect_create(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $cek = DB::table('section')->whereRaw("id_dept ='".$request->id_department."' and (sect='".$request->sect."' or id_sect ='".$request->id_section."')")->get();
        if(count($cek)==0){
            $sect = DB::table('section')
                    ->insert([
                            "id_sect"   => $request->id_section,
                            "sect"      => $request->sect,
                            "id_dept"   => $request->id_department,
                            "user_entry"=> $_SESSION['username'],
                            "timelog"   => date("Y-m-d H:i:s")
                            ]);
            if($sect){
            return redirect('/sect')->with('success','Create Section Success!');
            }else{
                return redirect()->back()->with('failed','Create Section Failed!');
            }
        }else{
                return redirect()->back()->with('failed','Duplicate Section! Please Use Different Section!!!');
        }
    }
    public function sect_edit(Request $request,$iddept,$idsect)
    {
        $dept = DB::table('department')->get();
        $edit_sect = DB::table('section')->whereRaw("id_dept='".hex2bin($iddept)."' and id_sect='".hex2bin($idsect)."'")->first();
        return view('page.master.form_sect',["dept"=>$dept,"edit_sect"=>$edit_sect,"getUser"=>$this->user]);
    }
    public function sect_update(Request $request,$idsect,$sect)
    {
        $up_sect = DB::table('section')
                    ->whereRaw("id_sect='".hex2bin($idsect)."' and sect='".hex2bin($sect)."'")
                    ->update([
                            "id_sect"   =>$request->id_section,
                            "sect"      =>$request->sect,
                            "id_dept"   =>$request->id_department
                            ]);
        if($up_sect>=0)
        {
        return redirect('/sect')->with('success','Update Section Success!');
        }else{
            return redirect()->back()->with('failed','Update Section Failed!');
        }
    }

    


}
