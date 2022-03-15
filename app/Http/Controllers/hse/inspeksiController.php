<?php

namespace App\Http\Controllers\hse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class inspeksiController extends Controller
{
	public function createInspeksi(Request $request)
	{
		$id = uniqid();
		$header = DB::table("hse.form_inspeksi_header")
					->insert([
						"idInspeksi"=>$id,
						"idForm"=>$request->idForm,
						"tgl_inspeksi"=>date("Y-m-d",strtotime($request->tglInspeksi)),
						"userInput"=>$request->user,
						"perusahaan"=>$request->perusahaan,
						"lokasiInspeksi"=>$request->lokasi,
						"tglInput"=>date("Y-m-d"),	
						"saran"=>$request->saran,
						"status"=>"Sedang Dikerjakan"
					]);
		if($header){
		$item = DB::table("hse.form_inspeksi_temp")
				->where("idTemp",$request->idTemp)
				->get();
				foreach ($item as $k => $value) {
					$itemIn = DB::table("hse.form_inspeksi_detail")
					->insert([
											"idInspeksi"=>$id,
											"inspeksi"=>$value->idItem,
											"answer"=>$value->answer
										]);
				}
				if($item){
		$team = DB::table("hse.team_inspeksi_temp")
				->where("idTemp",$request->idTemp)
				->get();
				foreach ($team as $k => $value) {
					$teamIn = DB::table("hse.team_inspeksi")->insert([
											"idInspeksi"=>$id,
											"nikTeam"=>$value->nikTeam
										]);
				}
				if($team){
		$pica = DB::table("hse.pica_temp")
				->where("idTemp",$request->idTemp)
				->get();
				foreach ($pica as $k => $value) {
					$picaIn = DB::table("hse.form_inspeksi_pika")->insert([
											"idInspeksi"=>$id,
											"picaTemuan"=>$value->temuan,
											"picaSebelum"=>$value->buktiTemuan,
											"picaNikPJ"=>$value->nikPJ,
											"picaNamaPJ"=>$value->namaPJ,
											"picaTenggat"=>date("Y-m-d",strtotime($value->tglTenggat)),
											"status"=>$value->status
										]);
				}
				if($pica){
					$validasi = DB::table('hse.form_inspeksi_validasi')
									->insert([
										"idInspeksi"=>$id
									]);
					if($validasi){
						return ["success"=>true];
					}else{
						return ["success"=>false];
					}
				}else{
					return ["success"=>false];
				}
				}else{
					return ["success"=>false];
				}
			}else{
				return ["success"=>false];
			}
		}else{
			return ["success"=>false];
		}
	}
	public function getInspeksiUser(Request $request)
	{
		$header = DB::table("hse.form_inspeksi_header as a")
				->leftJoin("hse.form_inspeksi as b","b.idForm","a.idForm")
				->leftJoin("db_karyawan.perusahaan as c","c.id_perusahaan","a.perusahaan")
				->where([
					["a.userInput",$request->userInput],
					["a.idForm",$request->idForm]
				])
				->orderBy("INC","desc")
				->paginate(10);
		return ["inspeksi"=>$header];
	}
	public function androidInspeksiPica(Request $request)
	{
		$pica = DB::table("hse.form_inspeksi_pika")
        ->where("idInspeksi",$request->idInspeksi)
        ->get();
        return ["inspeksiPicaDetail"=>$pica];
	}
	

    public function teamInspeksi(Request $request){
        $check = DB::table("hse.team_inspeksi as a")
        ->join("user_login as b","b.nik","a.nikTeam")
        ->join("section as c","c.id_sect","b.section")
        ->join("department as d","d.id_dept","b.department")
        ->where("a.idInspeksi",$request->idInspeksi)
        ->groupBy("b.nik")
        ->get();
        return ["teamInspeksi"=>$check];
    }

    public function inspeksiDetail(Request $request)
    {
        $androidSubInspeksi = DB::table("hse.form_inspeksi_sub as a")->where("idForm",$request->idForm)->get();
        
        if(count($androidSubInspeksi)>0){
            foreach ($androidSubInspeksi as $key => $value) {
             $itemInspeksi[] = ["nameSub"=>$value->nameSub,"numSub"=>$value->numSub,"items"=>DB::table("hse.form_inspeksi_list as b")->leftJoin("hse.form_inspeksi_detail as c","c.inspeksi","b.idList")->where([["idSub",$value->idSub],["c.idInspeksi",$request->idInspeksi]])->get()];
            }
        }else{
            $itemInspeksi[] = ["nameSub"=>null,"numSub"=>null,"items"=>DB::table("hse.form_inspeksi_list as b")->leftJoin("hse.form_inspeksi_detail as c","c.inspeksi","b.idList")->where([["idForm",$request->idForm],["c.idInspeksi",$request->idInspeksi]])->get()];
        }
        return ["itemDetailInspeksi"=>$itemInspeksi];
    }
    public function gantiSandi(Request $request)
    {
    	$username = $request->username;
    	$oldPass = $request->oldPass;
    	$newPass = $request->newPass;
    	$reNewPass = $request->reNewPass;
    	$check = DB::table("user_login")->where([
    			["username",$username],
    			["password",md5($oldPass)]
    		])->count();
    	if($check>0){
    		$change = DB::table("user_login")->where([
    					["username",$username],
    					["password",md5($oldPass)]
    				])->update(["password"=>md5($newPass)]);
    		if($change){
    		return ["success"=>true,"login"=>false];    			
    		}else{
    		return ["success"=>false,"login"=>true];
    		}

    	}else{
    		return ["success"=>false,"login"=>$oldPass];
    	}
    }
    public function loadProfile(Request $request)
    {
    	$profile = DB::table("user_login as a")
    				->leftJoin("db_karyawan.perusahaan as b","b.id_perusahaan","a.perusahaan")
    				->leftJoin("department as c","c.id_dept","a.department")
    				->leftJoin("section as d","d.id_sect","a.section")
    				->where("a.username",$request->username)
    				->first();
    	return ["Profile"=>$profile];
    }
    public function simpanProfile(Request $request)
    {
    	$check = DB::table("user_login")->where("username",$request->username)->count();
    	if($check){
    		$update = DB::table("user_login")
    		->where("username",$request->username)
    		->update([
    			"nama_lengkap"=>$request->nama_lengkap
    		]);

    	}
    }
    public function saveCompany(Request $request)
    {
    	$cek = DB::table("db_karyawan.perusahaan")->where("nama_perusahaan",$request->perusahaan)->count();
    	if($cek<1){
    	$company = DB::table("db_karyawan.perusahaan")
    				->insert([
    					"nama_perusahaan"=>$request->perusahaan
    				]);
		if($company){
    		return ["success"=>true,"login"=>true];
    	}else{
    		return ["success"=>false,"login"=>true];
    	}
		}else{
    		return ["success"=>false,"login"=>false];
    	
		}	
    }
    public function updateCompany(Request $request)
    {
    	$company = DB::table("db_karyawan.perusahaan")
    				->where("id_perusahaan",$request->idCompany)
    				->update([
    					"nama_perusahaan"=>$request->perusahaan
    				]);
		if($company){
    		return ["success"=>true,"login"=>true];
    	}else{
    		return ["success"=>false,"login"=>true];
    	}
    }
    public function hazardKeSaya(Request $request)
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
->where("b.nikPJ",$request->nik)
->orderBy("a.time_input","desc")
->paginate(10);
       return $header;
    }
    public function hazardHSE(Request $request)
    {
        // dd($request->cari);
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
->whereBetween("a.tgl_hazard",[$fDate,$lDate]);
if(isset($request->cari)){
    $filter = $sql->whereRaw("nama_lengkap LIKE '%".$request->cari."%'");
}else{
    $filter = $sql;
}
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
    public function fotoProfile(Request $request)
    {
        $fileToUpload = $request->file('fileToUpload');
        $nik = $request->nik;
        $dir = "foto_profile/";
        // $fileName = $fileToUpload->getClientOriginalName();
        $fileName = url('/')."/foto_profile/".$fileToUpload->getClientOriginalName();
        if($fileToUpload->move($dir,$fileName)){
            $upFoto = DB::table("user_login")->where("nik",$nik)
            ->update([
                "photo_profile"=>$fileName
            ]);
            if($upFoto){
                return ["success"=>true,"login"=>true,"resultLog"=>$fileName];
            }else{
                return ["success"=>false,"login"=>false,"resultLog"=>null];
            }
        }
    }
    public function listUser(Request $request)
    {
        $cari = $request->cari;
        $user = DB::table("user_login as a")
        ->join("department as b","b.id_dept","a.department")
        ->leftJoin("section as c",function($join){
            $join->on("c.id_sect","a.section");
            $join->on("c.id_dept","b.id_dept");
        })
        ->join("db_karyawan.perusahaan as d","d.id_perusahaan","a.perusahaan")
        ->select("a.*","b.dept","c.sect","d.nama_perusahaan")
        ->whereRaw("a.nik IS NOT NULL and a.status ='0' and (a.level != 'PLT' or a.level IS NULL)");
        if($cari!=null){        
            $filter = $user->whereRaw("
                a.nik like '%".$cari."%' or 
                a.nama_lengkap like '%".$cari."%' or 
                c.sect like '%".$cari."%' or 
                b.dept like '%".$cari."%' or 
                d.nama_perusahaan like '%".$cari."%'
                ");
        }else{
            $filter = $user;
        }
        $result = $filter->groupBy("a.nik")
        ->orderBy("a.nik","asc")
        ->paginate(10);
        return $result;
    }
    public function listUserAll(Request $request)
    {
        $cari = $request->cari;
        $user = DB::table("user_login as a")
        ->join("department as b","b.id_dept","a.department")
        ->leftJoin("section as c",function($join){
            $join->on("c.id_sect","a.section");
            $join->on("c.id_dept","b.id_dept");
        })
        ->join("db_karyawan.perusahaan as d","d.id_perusahaan","a.perusahaan")
        ->select("a.*","b.dept","c.sect","d.nama_perusahaan")
        ->whereRaw("a.nik IS NOT NULL and a.status ='0' and (a.level != 'PLT' or a.level IS NULL)");
        if($cari!=null){        
            $filter = $user->whereRaw("
                a.nik like '%".$cari."%' or 
                a.nama_lengkap like '%".$cari."%' or 
                c.sect like '%".$cari."%' or 
                b.dept like '%".$cari."%' or 
                d.nama_perusahaan like '%".$cari."%'
                ");
        }else{
            $filter = $user;
        }
        $result = $filter->groupBy("a.nik")
        ->orderBy("a.nik","asc")
        ->get();
        return ["UsersList"=>$result];
    }
    public function hazardReportVerifikasi(Request $request)
    {
        $uid= $request->uid;
        $username= $request->username;
        $flag = $request->option;
        $keterangan = (isset($request->keterangan))?$request->keterangan:"";
        $validator = DB::table("hse.hazard_report_validation")
                    ->where("uid",$uid)
                    ->update([
                        "user_valid"=>$username,
                        "tgl_valid"=>date("Y-m-d"),
                        "jam_valid"=>date("H:i:s"),
                        "option_flag"=>$flag,
                        "keterangan_admin"=>$keterangan
                    ]);
        if($validator){
            return ["success"=>true,"login"=>true,"resultLog"=>"Sudah Di Verifikasi"];
        }else{
            return ["success"=>false,"login"=>false,"resultLog"=>"Gagal Verifikasi"];
        }
    }
	public function inspeksiAll(Request $request)
	{
		$sql = DB::table("hse.form_inspeksi_header as a")
                            ->leftJoin("hse.form_inspeksi as c","c.idForm","a.idForm")
                            ->leftJoin("user_login as d","d.username","a.userInput")
                            ->leftJoin("db_karyawan.perusahaan as d","d.id_perusahaan","a.perusahaan");
                            if(isset($request->dari)){
                                // dd($request->dari);
                                $filter = $sql->whereBetween("a.tgl_inspeksi",
                                                [date("Y-m-d",strtotime($request->dari))
                                                ,date("Y-m-d",strtotime($request->sampai))]);
                            }else{
                                $filter = $sql;
                            }
                            $inspeksiHeader = $filter->orderBy("a.tgl_inspeksi","DESC")
                            ->paginate(10);
							return ["allInspection"=>$inspeksiHeader];
	}
    public function inspeksiUser(Request $request)
	{
        $inspeksiHeader = array();
        $user = DB::table("hse.team_inspeksi")->where("nikTeam",$request->nikTeam)->first();
        if($user!=null){
            $sql = DB::table("hse.form_inspeksi_header as a")
            ->leftJoin("hse.form_inspeksi as c","c.idForm","a.idForm")
            ->leftJoin("user_login as d","d.username","a.userInput")
            ->leftJoin("db_karyawan.perusahaan as d","d.id_perusahaan","a.perusahaan")
            ->where("a.idInspeksi",$user->idInspeksi)
            ->orWhere("a.userInput",$request->userInput);
            if(isset($request->dari)){
                // dd($request->dari);
                $filter = $sql->whereBetween("a.tgl_inspeksi",
                                [date("Y-m-d",strtotime($request->dari))
                                ,date("Y-m-d",strtotime($request->sampai))]);
            }else{
                $filter = $sql;
            }
            $inspeksiHeader = $filter->orderBy("a.tgl_inspeksi","DESC")
            ->paginate(10);
        }
		
		return ["allInspection"=>$inspeksiHeader];
	}

    public function deleteHazard(Request $request)
    {
        if(isset($request->uid)){
            $deleteHazard = DB::table("hse.hazard_report_header as a")
                    ->join("hse.hazard_report_detail as b","b.uid","a.uid")
                    ->where("b.uid",$request->uid)->delete();
                if($deleteHazard){
                    return ["success"=>true];
                }else{
                    return ["success"=>false];
                }
        }else{
            return ["success"=>false];
        }
    }
    public function resetPassword(Request $request)
    {
        $user = DB::table("user_login")
                ->leftJoin("db_karyawan.perusahaan as a","a.id_perusahaan","user_login.perusahaan")
                ->select("user_login.*","a.nama_perusahaan")
                ->whereRaw("username = '".$request->login."' or nik = '".$request->login."'")->first();
        return ["user"=>$user];
    }
    public function createTokenReset(Request $request)
    {
        $token =date("Y-m-d H:i:s",strtotime("+1 Hour"));
        $user = DB::table("user_login")->whereRaw("username = '".$request->login."' or nik = '".$request->login."'")->first();
        if($user!=null){
            $update = DB::table("user_login")->where("id_user",$user->id_user)->update([
                "token_password"=>bin2hex($token)
            ]);
            if($update){
                $createEmail = DB::table("queue_email.reset_password")
                ->insert([
                    "subject"=>"Reset Password",
                    "id_user"=>$user->id_user,
                    "token"=>bin2hex($token),
                    "email"=>$user->email,
                    "date_in"=>date("Y-m-d H:i:s")
                ]);
                if($createEmail){
                    return ["create_token"=>bin2hex($token),"success"=>true];
                }else{
                    return ["create_token"=>null,"success"=>false];
                }
            }else{
                return ["create_token"=>null,"success"=>false];
            }
        }else{
        return ["create_token"=>null,"success"=>false];
        }
    }
    public function updatePassword(Request $request)
    {
        $now = strtotime(date("Y-m-d H:i:s"));
        $user = DB::table("user_login")->whereRaw("id_user = '".hex2bin($request->idUser)."'")->first();
        if($user!=null){
            $token = hex2bin($user->token_password);
            $epr = strtotime($token);
            if($epr >= $now){
                return view("email.formReset",["user"=>$user]);
            }else{
                return view("errors.password");
            }
        }else{
                echo "no1";

        }
    }
    public function newPassword(Request $request)
    {
        $user = DB::table("user_login")->whereRaw("id_user = '".hex2bin($request->idUser)."'")->first();
        if($user!=null){
            $newPwd = $request->newPwd;
            $rePwd = $request->rePwd;
            if($newPwd==$rePwd){
                if(md5($newPwd)!=$user->password){
                    $up = DB::table("user_login")->where("id_user",$user->id_user)->update(["password"=>md5($newPwd)]);
                    if($up){
                        return redirect()->back()->with("success","Password Berhasil Dirubah");
                    }else{
                        return redirect()->back()->with("failed","Password Gagal Dirubah , Coba Lagi");
                    }
                }else{
                        return redirect()->back()->with("failed","Password Sama Dengan Yang Lama , Coba Lagi");
                }
            }else{
                return redirect()->back()->with("failed","Password Tidak Sama, Coba Lagi");
            }
        }else{
            return redirect()->back()->with("failed","Gagal , Coba Lagi");
        }
    }
    public function mailResetView(Request $request)
    {
     $user = DB::table("user_login")->where("id_user",$request->id_user)->first();
     if($user!=null){
         return view("email.reset_password" ,["token"=>bin2hex($user->token_password),"id_user"=>bin2hex($user->id_user)]);
     }else{
        die();
     }
    }
    public function resetAllPassword(Request $request)
    {
        if(isset($request->login)){
            $user = DB::table("user_login")->whereRaw("username = '".$request->login."' or nik = '".$request->login."'")->first();
        }else{
            $user = null;
        }
         return view("email.resetAll",["user"=>$user] );
       
    }
}