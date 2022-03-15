<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use App\EmailSend;

class rkbController extends Controller
{
    private $user;
    public $IP;
    public $user_agent;
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

    Public function getOS() { 

        $os_platform  = "Unknown OS Platform";

        $os_array     = array(
                              '/windows nt 10/i'      =>  'Windows 10',
                              '/windows nt 6.3/i'     =>  'Windows 8.1',
                              '/windows nt 6.2/i'     =>  'Windows 8',
                              '/windows nt 6.1/i'     =>  'Windows 7',
                              '/windows nt 6.0/i'     =>  'Windows Vista',
                              '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                              '/windows nt 5.1/i'     =>  'Windows XP',
                              '/windows xp/i'         =>  'Windows XP',
                              '/windows nt 5.0/i'     =>  'Windows 2000',
                              '/windows me/i'         =>  'Windows ME',
                              '/win98/i'              =>  'Windows 98',
                              '/win95/i'              =>  'Windows 95',
                              '/win16/i'              =>  'Windows 3.11',
                              '/macintosh|mac os x/i' =>  'Mac OS X',
                              '/mac_powerpc/i'        =>  'Mac OS 9',
                              '/linux/i'              =>  'Linux',
                              '/ubuntu/i'             =>  'Ubuntu',
                              '/iphone/i'             =>  'iPhone',
                              '/ipod/i'               =>  'iPod',
                              '/ipad/i'               =>  'iPad',
                              '/android/i'            =>  'Android',
                              '/blackberry/i'         =>  'BlackBerry',
                              '/webos/i'              =>  'Mobile'
                        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $this->user_agent))
                $os_platform = $value;

        return $os_platform;
    }

    Public function getBrowser() {

        $browser        = "Unknown Browser";

        $browser_array = array(
                                '/msie/i'      => 'Internet Explorer',
                                '/firefox/i'   => 'Firefox',
                                '/safari/i'    => 'Safari',
                                '/chrome/i'    => 'Chrome',
                                '/edge/i'      => 'Edge',
                                '/opera/i'     => 'Opera',
                                '/netscape/i'  => 'Netscape',
                                '/maxthon/i'   => 'Maxthon',
                                '/konqueror/i' => 'Konqueror',
                                '/mobile/i'    => 'Handheld Browser'
                         );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $this->user_agent))
                $browser = $value;

        return $browser;
    }
    public function rkb(Request $request) 
    {
        
    	if(!isset($_SESSION['username'])) return redirect('/');
        return redirect("/v3/rkb");
    	if(isset($request->expired)){
            $expired = "IS NOT NULL";
        }else{
            $expired = "IS NULL";
        }
        if(isset($request->close_rkb)){
            $close_rkb = "IS NOT NULL";
        }else{
            $close_rkb = "IS NULL";
        }
            $sql = DB::table('e_rkb_header') 
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_header.no_rkb")
                    ->leftJoin("department","department.id_dept","e_rkb_header.dept")
                    ->leftJoin("section","section.id_sect","e_rkb_header.section")
                    ->leftJoin("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
                    ->leftJoin("user_login","user_login.username","e_rkb_detail.user_entry")
                    ->select("e_rkb_header.*","e_rkb_header.status as myStatus","e_rkb_approve.*","department.*","section.*","e_rkb_detail.*","user_login.*")
                    ->orderBy("e_rkb_detail.timelog","desc")
                    ->groupBy("e_rkb_detail.no_rkb")
                    ->whereRaw("user_expired ".$expired)
                    ->whereRaw("user_close ".$close_rkb);
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
        if($_SESSION['section']=="KABAG"){
                return redirect("/kabag/rkb");
            if(isset($request->seksi)){
                $rkb = $query->where([
                    ['e_rkb_header.dept',$_SESSION['department']],
                    ["e_rkb_header.section",$request->seksi]
                ])->paginate(9); 
            }else{
                $rkb = $query->where([
                        ['e_rkb_header.dept',$_SESSION['department']]
                        ])
                        ->paginate(9); 
            }
            }elseif($_SESSION['section']=="PURCHASING"){
                
                if(isset($request->dep)){
                    if(isset($request->seksi)){
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                    ['e_rkb_header.section',$request->seksi]
                ])->paginate(9); 
                    }else{
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                ])->paginate(9); 
                    }
                }else{
                    $rkb = $query->paginate(9); 
                }
            return view('page.logistic.reportRkb',["rkb"=>$rkb,"getUser"=>$this->user]);   
            }elseif($_SESSION['section']=="KTT"){
                return redirect("/ktt/rkb");
                if(isset($request->dep)){
                    if(isset($request->seksi)){
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                    ['e_rkb_header.section',$request->seksi]
                ])->paginate(9); 
                    }else{
                 $rkb = $query->where([
                    ['e_rkb_header.dept',$request->dep],
                ])->paginate(9); 
                    }
                }else{
                    $rkb = $query->paginate(9); 
                }
                $kabag = DB::table('user_login')->where("section","KABAG")->get();
        return view('page.ktt.reportRkb',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]); 

        }elseif($_SESSION['level']=="administrator"){
                //return redirect("/admin/rkb");
                
                    $rkb = $query->paginate(9); 
                
                $kabag = DB::table('user_login')->where("section","KABAG")->get();
        return view('page.admin',["rkb"=>$rkb,"getUser"=>$this->user,"kabag"=>$kabag]);   

            }else{
                $rkb = $query->where([
                        ['e_rkb_header.section',$_SESSION['section']]
                        ])
                        ->paginate(9); 
            }
        return view('page.reportRkb',["rkb"=>$rkb,"getUser"=>$this->user]);
    }
    public function rkb_form(Request $request)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
    	$rkb = DB::table('e_rkb_temp')->where([
    		['user_entry',$_SESSION['username']]
    	])->get();
        $satuan = DB::table('satuan')->get();
        return redirect("/v1/form_rkb");
    	return view('page.rkb',["tmp_rkb"=>$rkb,"satuan"=>$satuan,"getUser"=>$this->user]);
    }

    public function rkb_rubah(Request $request,$no_rkb)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
    	$rkb = DB::table('e_rkb_temp')->where([
    		['user_entry',$_SESSION['username']]
    	])->get();
    	$edit_rkb = DB::table('e_rkb_temp')->where([
    		['id_rkb',$no_rkb]
    	])->first();

        $_penawaran = DB::table('e_rkb_pictures')->where([
            ['no_rkb',null],
            ['user_entry',$_SESSION['username']],
            ['part_name',$edit_rkb->part_name],
        ])->get();
        $satuan = DB::table('satuan')->get();
    	return view('page.rkb',[ "tmp_rkb"=>$rkb,"edit_rkb"=>$edit_rkb ,"satuan"=>$satuan,"getUser"=>$this->user,"penawaran"=>$_penawaran]);
    }

    public function e_rkb_temp(Request $request)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
        $timelog = date('Y-m-d H:i:s');
        $due_date = date("Y-m-d",strtotime($request->due_date));
    	$rkb = DB::table('e_rkb_temp')->insert([
    		"part_name"   =>  $request->part_name,
    		"part_number" =>  $request->part_number,
    		"quantity"    =>  $request->quantity,
    		"remarks"     =>  $request->remark,
            "quantity"    =>  $request->quantity,
            "satuan"      =>  $request->satuan,
            "due_date"    =>  $due_date,
    		"timelog"     =>  $timelog,
    		"user_entry"  =>  $_SESSION['username']
    	]);
    	if($rkb==true){
            $z=0;
            if($request->hasfile('files')){
                foreach($request->file('files') as $k => $v){
                    $filename   = $request->part_name."_".uniqid().".".$v->getClientOriginalExtension();
                    $fileTemp   = $v;
                    $size       = $v->getClientSize();
                   $file_up     = DB::table('e_rkb_pictures')->insert([
                    "part_name"     =>$request->part_name,
                    "file"          => $filename,
                    "user_entry"    =>$_SESSION['username'],
                    "timelog"       =>$timelog
                    ]);
                      $destinationPath = '/pictures';
                      $v->storeAs($destinationPath,$filename);
                   $z++;
                }
                if(count($request->file('files'))==$z){
                    return redirect()->back()->with('success','Successfully saved!');
                }else{
                    return redirect()->back()->with('success','Successfully saved!');
                }
            }else{
                return redirect()->back()->with('success','Successfully saved!');
            }
    	}
    }
    public function up_rkb_temp(Request $request,$no_rkb)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
    	//dd($request);
        $due_date = date("Y-m-d",strtotime($request->due_date));
        $timelog = date('Y-m-d H:i:s');

            if($request->hasfile('files')){
                foreach($request->file('files') as $k => $v){
                    $filename   = $request->part_name."_".uniqid().".".$v->getClientOriginalExtension();
                    $fileTemp   = $v;
                    $size       = $v->getClientSize();
                   $file_up     = DB::table('e_rkb_pictures')->insert([
                    "part_name"     =>  $request->part_name,
                    "file"          =>  $filename,
                    "user_entry"    =>  $_SESSION['username'],
                    "timelog"       =>  $timelog
                    ]);
                      $destinationPath = '/pictures';
                      $v->storeAs($destinationPath,$filename);
                }
            }
    	$rkb = DB::table('e_rkb_temp')
    	->where('id_rkb','=',$no_rkb)
    	->update([
    		"part_name"   =>$request->part_name,
    		"part_number" =>$request->part_number,
    		"quantity"    =>$request->quantity,
    		"remarks"     =>$request->remark,
    		"quantity"    =>$request->quantity,
            "due_date"    =>$due_date,
            "satuan"      =>$request->satuan,
    		"timelog"     =>$timelog
    	]);
    	if($rkb>0){
            $rkb_his = DB::table('e_rkb_history')->insert([
                    "part_name"     =>  $request->part_name,
                    "part_number"   =>  $request->part_number,
                    "quantity"      =>  $request->quantity,
                    "satuan"        =>  $request->satuan,
                    "due_date"      =>  $request->due_date,
                    "user_entry"    =>  $_SESSION['username'],
                    "timelog"       =>  $timelog,
                    "remarks"       =>  $request->remark,
                    "void"          =>  1
                    ]);
            $_penawaran = DB::table('e_rkb_pictures')->where([
                            ["user_entry",$_SESSION['username']],
                            ["part_name",$request->part_name],
                            ["no_rkb",null]
                            ])
                        ->update(["timelog"=>$timelog]);
            if($rkb_his)
            {
                return redirect('/form_rkb')->with('success','Successfully updated!');
            }
    	}
    }
    public function del_rkb_temp_all(Request $request)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
    	$len =  count($request->id_record);
        $z=0;
    	foreach($request->id_record as $key => $val){
        $rkb_get = DB::table('e_rkb_temp')->where('id_rkb',$val)->first();
                $rkb_his = DB::table('e_rkb_history')->insert([
                            "part_name"     =>  $rkb_get->part_name,
                            "part_number"   =>  $rkb_get->part_number,
                            "quantity"      =>  $rkb_get->quantity,
                            "satuan"        =>  $rkb_get->satuan,
                            "user_entry"    =>  $_SESSION['username'],
                            "timelog"       =>  date('Y-m-d H:i:s'),
                            "remarks"       =>  $rkb_get->remarks,
                            "void"          =>  2
                            ]);
                if($rkb_his){
                    $z++;
                    }   
    	}
        if($z==$len){
                foreach($request->id_record as $key => $val){
                    $rkb = DB::table('e_rkb_temp')->where('id_rkb',$val)->delete();
                }
            }
    }
    public function del_rkb_temp(Request $request,$no_rkb)
    {
    	if(isset($_SESSION['username'])=="") return redirect('/');
        $rkb_get = DB::table('e_rkb_temp')->where('id_rkb',$no_rkb)->first();
        $rkb_his = DB::table('e_rkb_history')->insert([
                    "part_name"     =>  $rkb_get->part_name,
                    "part_number"   =>  $rkb_get->part_number,
                    "quantity"      =>  $rkb_get->quantity,
                    "satuan"        =>  $rkb_get->satuan,
                    "user_entry"    =>  $_SESSION['username'],
                    "timelog"       =>  date('Y-m-d H:i:s'),
                    "remarks"       =>  $rkb_get->remarks,
                    "void"          =>  2
                    ]);
        if($rkb_his){
            $rkb = DB::table('e_rkb_temp')->where('id_rkb','=',$no_rkb)->delete();
            if($rkb){
                    return redirect()->back()->with('success','Successfully Deleted!');
                }
                else
                {
                    return redirect()->back()->with('failed','Failed Deleted!');
                }
            }
    }
    public function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    public function create_rkb(Request $request)
    {
        
    	if(isset($_SESSION['username'])=="") return redirect('/');
        $thisMonth = $this->numberToRomanRepresentation(date("m"));

    	$z=0;
      $rkb = DB::table('e_rkb_header')->whereYear("tgl_order",date("Y"))->count();
      $number = ($rkb+1);

       $rkbNumb = sprintf('%05d',$number);
       $nomor_rkb = $rkbNumb."/ABP/RKB/".$_SESSION['section']."/".date("Y");
      $cek = DB::table('e_rkb_header')->where("no_rkb",$nomor_rkb)->count();
      if($cek > 0){
       $nomor_rkb = ($rkbNumb+1)."/ABP/RKB/".$_SESSION['section']."/".date("Y");
      }else{
       $nomor_rkb = $rkbNumb."/ABP/RKB/".$_SESSION['section']."/".date("Y");
      }
	  $tmp_rkb = DB::table('e_rkb_temp')
	  			 ->where('user_entry',$_SESSION['username'])
	  			 ->get();
	  $create_rkb = DB::table('e_rkb_header')->insert([
	  				"no_rkb"	=>	$nomor_rkb,
	  				"dept"		=>	$_SESSION['department'],
	  				"section"	=>	$_SESSION['section'],
	  				"tgl_order" =>	date('Y-m-d H:i:s')
	  				]);
	  if($create_rkb){
	  	foreach ($tmp_rkb as $key => $value) {
            // echo date("Y-m-d H:i:s",strtotime($value->due_date));
            // die();
	  		$rkb_det = DB::table('e_rkb_detail')->insert([
                    "no_rkb"        =>  $nomor_rkb,
                    "item"          =>  $value->item,
                    "part_name"     =>  $value->part_name,
                    "part_number"   =>  $value->part_number,
                    "quantity"      =>  $value->quantity,
                    "satuan"        =>  $value->satuan,
                    "due_date"      =>  date("Y-m-d H:i:s",strtotime($value->due_date)),
                    "user_entry"    =>  $_SESSION['username'],
                    "timelog"       =>  date('Y-m-d H:i:s'),
                    "remarks"       =>  $value->remarks
                    ]);
            $_penawaran = DB::table('e_rkb_pictures')
                    ->where([
                        ["user_entry",$_SESSION['username']],
                        ["id_rkb",$value->id_rkb]
                    ])
                    ->update([
                    "no_rkb"=>$nomor_rkb,
                    "part_name"     =>  $value->part_name
                    ]);
	  		$z++;
	  	}
	  	if(count($tmp_rkb)==$z){

$ip = $this->IP;
$user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$ip.") Create RKB  ".$nomor_rkb,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);

            $status_rkb = DB::table('e_rkb_approve')->insert([ "no_rkb" =>  $nomor_rkb ]);
	  		$tmp_rkb_delete = DB::table('e_rkb_temp')
	  						  ->where('user_entry',$_SESSION['username'])
	  						  ->delete();
	  		if($tmp_rkb_delete){

$kabag = DB::table('user_login')
                    ->where([
                        ["department",$_SESSION['department']],
                        ["section","KABAG"],
                        ["status",'0']
                    ])->get();
                    
if(count($kabag)>0){
foreach($kabag as $k => $vK){
$sendEMAIL = new EmailSend();
$result = $sendEMAIL->ToKTT("New e-RKB","new",$nomor_rkb,$vK->email);
    $result = DB::table("queue_email.queue_rkb")
                ->insert([
                            "subjek"=>"New e-RKB",
                            "tipe"=>"new",
                            "no_rkb"=>$nomor_rkb,
                            "email"=>$vK->email,
                            "date_in"=>date("Y-m-d H:i:s")
                        ]);         
$user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$ip.") Status Email  ".$vK->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
$log = DB::table("email_log")
                ->insert([
                    "email"   => $vK->email,
                    "no_rkb"  => $nomor_rkb,
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $result
                ]);
    }
}

	  			return redirect('/mtk/rkb')->with('success','New RKB has been added!');
	  		}else{
	  			return redirect()->back()->with('failed','Adding RKB failed!');
	  		}
	  	}
	  	
	  }
    }
    public function detail_rkb(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $rkb = DB::table('e_rkb_header')->where([
            ["e_rkb_header.no_rkb",$request->no_rkb]
        ])
        ->join("e_rkb_detail","e_rkb_detail.no_rkb","e_rkb_header.no_rkb")
        ->select("e_rkb_header.no_rkb","e_rkb_detail.*")
        ->orderBy("timelog","asc")
        ->get();
        
        return view('page.modal',["rkb_det"=>$rkb,"no_rkb"=>$request->no_rkb,"parent_eq"=>$request->parent_eq,"detail_rkb"=>"OK"]);
    }
    public function img_replace(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $_penawaran = DB::table("e_rkb_pictures")->where([
            ['file',$request->img_name],
            ['user_entry',$_SESSION['username']]
        ])->first();

        return view('page.modal',["file"=>$_penawaran,"replace_file"=>"OK"]);
    }
    public function img_reupload(Request $request,$filename)
    {
     $imgExt =  explode('.',$filename);
     $Ext = end($imgExt);
     $fname = $imgExt[0];
        //die($fname);
        if(isset($_SESSION['username'])=="") return redirect('/');
        $timelog = date('Y-m-d H:i:s');
            if($request->hasfile('file')){
            $delete_file = Storage::disk("penawaran")->delete($filename);
                        $upfilename = $fname.".".$request->file('file')->getClientOriginalExtension();
                       $file_up = DB::table('e_rkb_pictures')
                       ->where("file",$filename)
                       ->update([
                        "file"          => $upfilename,
                        "timelog"       =>$timelog
                        ]);
                          $destinationPath = '/penawaran';
                          $request->file('file')->storeAs($destinationPath,$upfilename);
                return redirect()->back()->with("success","File Uploaded");
            }
    }
    public function pic_reupload(Request $request,$filename)
    {
     $imgExt =  explode('.',$filename);
     $Ext = end($imgExt);
     $fname = $imgExt[0];
        //die($fname);
        if(isset($_SESSION['username'])=="") return redirect('/');
        $timelog = date('Y-m-d H:i:s');
            if($request->hasfile('file')){
            $delete_file = Storage::disk("pictures")->delete($filename);
                        $upfilename = $fname.".".$request->file('file')->getClientOriginalExtension();
                       $file_up = DB::table('e_rkb_pictures')
                       ->where("file",$filename)
                       ->update([
                        "file"          => $upfilename,
                        "timelog"       =>$timelog
                        ]);
                          $destinationPath = '/pictures';
                          $request->file('file')->storeAs($destinationPath,$upfilename);
                return redirect()->back()->with("success","File Uploaded");
            }
    }
    public function approveKABAG(Request $request,$no_rkb)
    {
        
        if(isset($_SESSION['username'])=="") return redirect('/');
        if(isset($_SESSION['section'])!="KABAG") return redirect('/');
        $approve = DB::table('e_rkb_approve')
                    ->where("no_rkb",hex2bin($no_rkb))
                    ->update([
                        "disetujui" => 1,
                        "tgl_disetujui" => date("Y-m-d H:i:s")
                    ]);
        if($approve>=0){
            $user_app = DB::table("user_approve")
                        ->insert([
                            "username"=>$_SESSION['username'],
                            "no_rkb"=>hex2bin($no_rkb),
                            "desk"=>"Disetujui",
                            "tgl_approve"=>date("Y-m-d H:i:s") 
                                ]);
        if($user_app>=0){
$ip = $this->IP;
$kabag = DB::table('user_login')
                    ->whereRaw("section='KTT' and level IS NULL")->first();
                    // dd($kabag);
if($kabag->email!=null){
$sendEMAIL = new EmailSend();
// $result = $sendEMAIL->ToKTT("New e-RKB","kabag",hex2bin($no_rkb),$kabag->email);
// return $result ;

$result = DB::table("queue_email.queue_rkb")
                ->insert([
                            "subjek"=>"New e-RKB",
                            "tipe"=>"kabag",
                            "no_rkb"=>hex2bin($no_rkb),
                            "email"=>$kabag->email,
                            "date_in"=>date("Y-m-d H:i:s")
                        ]);
$user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." Approve RKB : ".hex2bin($no_rkb)." | (".$ip.") Status Email  ".$kabag->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
$log = DB::table("email_log")
                ->insert([
                    "email"   => $kabag->email,
                    "no_rkb"  => hex2bin($no_rkb),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $result
                ]);
}else{
    $user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$ip.") Status Email  ".$kabag->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
}

                return redirect()->back()->with("success","Approved Success!");
        }else{
        return redirect()->back()->with("failed","Approve Failed!");
        }
        }else{
        return redirect()->back()->with("failed","Approve Failed!");
        }
    }
    public function approveKTT(Request $request,$no_rkb)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        if(isset($_SESSION['section'])!="KTT") return redirect('/');
        $approve = DB::table('e_rkb_approve')
                    ->where("no_rkb",hex2bin($no_rkb))
                    ->update([
                        "diketahui" => 1,
                        "tgl_diketahui" => date("Y-m-d H:i:s")
                    ]);
        if($approve>=0)
        {
            $user_app = DB::table("user_approve")
            ->insert([
                "username"=>$_SESSION['username'],
                "no_rkb"=>hex2bin($no_rkb),
                "desk"=>"Diketahui",
                "tgl_approve"=>date("Y-m-d H:i:s") 
                    ]);
            if($user_app>=0){

$ip = $this->IP;
$kabag = DB::table('user_login')
                    ->where([
                        ["section","PURCHASING"],
                        ["status",0]
                    ])->get();
foreach ($kabag as $l => $p) {
if($p->email!=null){
$sendEMAIL = new EmailSend();
// $result = $sendEMAIL->sending("New e-RKB","ktt",hex2bin($no_rkb),$p->email);
$result = DB::table("queue_email.queue_rkb")
                ->insert([
                            "subjek"=>"New e-RKB",
                            "tipe"=>"ktt",
                            "no_rkb"=>hex2bin($no_rkb),
                            "email"=>$p->email,
                            "date_in"=>date("Y-m-d H:i:s")
                        ]);
$user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." Approve RKB : ".hex2bin($no_rkb)." | (".$ip.") Status Email  ".$p->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
$log = DB::table("email_log")
                ->insert([
                    "email"   => $p->email,
                    "no_rkb"  => hex2bin($no_rkb),
                    "timelog" => date("Y-m-d H:i:s"),
                    "status"  => $result
                ]);
}else{
    $user_log = DB::table('user_log')->insert([
                "username"  =>  $_SESSION['username'],
                "ip"        =>  $ip,
                "time_log"  =>  date("Y-m-d H:i:s"),
                "description"   => $_SESSION['username']." (".$ip.") Status Email  ".$p->email,
"OS"            => $this->getOS(),
"BROWSER"       => $this->getBrowser()
               ]);
}

}
                return redirect()->back()->with("success","Success Approve!");
            }else{
                return redirect()->back()->with("failed","Failed Approve!");
            }
        }else{
            return redirect()->back()->with("failed","Failed Approve!");
        }
        
    }

    public function printRkb(Request $request)
    {

        if(isset($_SESSION['username'])=="") return redirect('/');
        
        if(isset($request->startDate) && isset($request->endDate)){
            $startDate = date("Y-m-d H:i:s",strtotime($request->startDate));
            $endDate = date("Y-m-d H:i:s",strtotime($request->endDate.date(' H:i:s')));
        }else{            
            $startDate = date("Y-m-d H:i:s",strtotime('-24hour'));
            $endDate = date("Y-m-d H:i:s");
        } 
        $query = DB::table('e_rkb_detail')
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("e_rkb_header","e_rkb_header.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("department","e_rkb_header.dept","=","department.id_dept")
                    ->join("section","e_rkb_header.section","=","section.id_sect")
                    ->leftJoin("e_rkb_cancel",function($join){
                        $join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb");   
                        $join->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");   
                    })
                    ->select("e_rkb_header.*","section.sect as det_sect","department.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section")
                    ->where("e_rkb_header.section",$_SESSION['section']);


        if($request->USER=="KABAG"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section = 'KABAG' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.disetujui = '0' and ( e_rkb_approve.cancel_section IS NULL and e_rkb_cancel.cancel_by_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.disetujui = '1' and ( e_rkb_approve.cancel_section !='KABAG' or e_rkb_cancel.cancel_by_section IS NULL  )");

        }
        }

       else if($request->USER=="KTT"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.cancel_section = 'KTT' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.diketahui = '0' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.diketahui = '1' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ) ");
        }
        }elseif($_SESSION['level']=="administrator"){

        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section IS NOT NULL or e_rkb_cancel.cancel_by_section IS NOT NULL)");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.disetujui = '0' or e_rkb_approve.diketahui = '0') and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL )");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and e_rkb_cancel.cancel_by_section IS NULL and (e_rkb_approve.disetujui = '1' or e_rkb_approve.diketahui = '1')");
        }else{

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')");   
        }
        
        }else{

        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section IS NOT NULL or e_rkb_cancel.cancel_by_section IS NOT NULL)");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.disetujui = '0' or e_rkb_approve.diketahui = '0') and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL )");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and e_rkb_cancel.cancel_by_section IS NULL and (e_rkb_approve.disetujui = '1' or e_rkb_approve.diketahui = '1')");
        }else{

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')");   
        }

        }


        
        
        $row = $rkb->orderBy("e_rkb_header.no_rkb","desc")
                    ->get();
        return view('page.printRkb',["rkb"=>$row,"getUser"=>$this->user]);
    }
    public function rkbPrint(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        
        if(isset($request->startDate) && isset($request->endDate)){
            $startDate = date("Y-m-d H:i:s",strtotime($request->startDate));
            $endDate = date("Y-m-d H:i:s",strtotime($request->endDate.date(' H:i:s')));
        }else{            
            $startDate = date("Y-m-d H:i:s",strtotime('-1month'));
            $endDate = date("Y-m-d H:i:s");
        } 
        $query = DB::table('e_rkb_detail')
                    ->join("e_rkb_approve","e_rkb_approve.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("e_rkb_header","e_rkb_header.no_rkb","=","e_rkb_detail.no_rkb")
                    ->join("department","e_rkb_header.dept","=","department.id_dept")
                    ->join("section","e_rkb_header.section","=","section.id_sect")
                    ->leftJoin("e_rkb_cancel",function($join){
                        $join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb");   
                        $join->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");   
                    })
                    ->select("e_rkb_header.*","section.sect as det_sect","department.*","e_rkb_approve.*","e_rkb_detail.*","e_rkb_cancel.cancel_by","e_rkb_cancel.remarks as cancel_remarks","e_rkb_cancel.cancel_by_section")
                    ->where("e_rkb_header.section",$_SESSION['section']);


        if($request->USER=="KABAG"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section = 'KABAG' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.disetujui = '0' and ( e_rkb_approve.cancel_section IS NULL and e_rkb_cancel.cancel_by_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.disetujui = '1' and ( e_rkb_approve.cancel_section !='KABAG' or e_rkb_cancel.cancel_by_section IS NULL  )");

        }
        }

       else if($request->USER=="KTT"){
           
        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.cancel_section = 'KTT' or e_rkb_cancel.cancel_by_section = '".$request->USER."')");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and ( e_rkb_approve.diketahui = '0' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ))");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')   and e_rkb_approve.diketahui = '1' and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL ) ");
        }
        }else{

        if($request->STATUS=="cancel"){

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and (e_rkb_approve.cancel_section IS NOT NULL or e_rkb_cancel.cancel_by_section IS NOT NULL)");
        }else if($request->STATUS=="0"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')  and (e_rkb_approve.disetujui = '0' or e_rkb_approve.diketahui = '0') and (e_rkb_cancel.cancel_by_section IS NULL and e_rkb_approve.cancel_section IS NULL )");

        }else if($request->STATUS=="1"){
        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."') and e_rkb_cancel.cancel_by_section IS NULL and (e_rkb_approve.disetujui = '1' or e_rkb_approve.diketahui = '1')");
        }else{

        $rkb = $query->whereRaw("(e_rkb_header.tgl_order BETWEEN '".$startDate."' and '".$endDate."')");   
        }

        }
        
        $row = $rkb->orderBy("e_rkb_header.no_rkb","desc")
                    ->get();

        return view('print.rkbPrint',["rkb"=>$row,"getUser"=>$this->user]);
    }
    public function AdminSend(Request $request)
    {
        if(isset($_SESSION['username'])=="") return redirect('/');
        $kabag = DB::table('user_login')
                    ->where([
                        ["department",$request->department],
                        ["section",$request->section]
                    ])
                    ->whereRaw("level IS NULL")
                    ->first();
if($kabag->email!=null){
$sendEMAIL = new EmailSend();
$result = $sendEMAIL->ToKTT("New e-RKB",$request->tmp_view,hex2bin($request->nomor_rkb),$kabag->email);
}
$log = DB::table("email_log")
                            ->insert([
                                "email"   => $kabag->email,
                                "no_rkb"  => hex2bin($request->nomor_rkb),
                                "timelog" => date("Y-m-d H:i:s"),
                                "status"  => $result
                            ]);
            /*
          $sub_em = $request->sub_em;
          $subject =  $sub_em." | Sistem Rencana Kebutuhan Barang";
          $m = bin2hex("admin.it@abpenergy.co.id");
          $judul = bin2hex($subject);

            $message = view("email.".$request->tipe ,["no_rkb"=>$request->no_rkb])->render();
            $teks = bin2hex($message);

          $f = bin2hex("e-RKB System <admin.it@abpenergy.co.id>");
          $get = "?sender=".$m."&teks=".$teks."&judul=".$judul."&from=".$f;
          $sendEMAIL = new EmailSend();
          $result = $sendEMAIL->Via_Tripconn('https://www.tripconnector.xyz/sendmail.php'.$get);
          */
          return $result;
    }
    public function viewTemplate(Request $request,$view)
    {
        return view("email.".$view,["no_rkb"=>$request->no_rkb]);
    }
    public function masterItem(Request $request)
    {

        if(isset($_SESSION['username'])=="") return redirect('/');
         $term = $_GET['query'];
         $query = DB::table("invmaster_item")->whereRaw("item like '%".$term."' or item_desc LIKE '%".$term."%' ")->get();
            foreach ($query as $k => $v)
            {
                $suggestions[] = array('data'=>$v->item,'value'=>"( ".$v->item." ) ".$v->item_desc);
            }
            $data = json_encode(array(
                                    "query"=>"Unit",
                                    "suggestions"=>$suggestions
                                 ));
            return $data;
    }
}
