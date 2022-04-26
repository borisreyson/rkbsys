<?php

namespace App\Http\Controllers\flutter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;
class FlutterController extends Controller
{
    //
    public function LoginValidate(Request $request)
 	   {
 	   		$user =null;
	 	   	if(isset($request->username) && isset($request->password)){
	 	   		$user = DB::table("user_login")
	 	   				->whereRaw("(username = '".$request->username."' or nik = '".$request->username."') and password = '".md5($request->password)."' and status ='0'")
	 	   				->first();
                if($user!=null){
                    if(isset($request->android_token)){
                        $android_token =$request->android_token;
                        $app_version = $request->app_version;
                        $app_name =$request->app_name;
                        $cekToken = DB::table('keamanan.user_android')->where([
                                        ["nik",$user->nik],
                                        ["phone_token",$android_token]
                                    ])->count();
                        if($cekToken==0){
                        $tokenIn= DB::table('keamanan.user_android')
                                    ->insert([
                                        "nik"=>$user->nik,
                                        "phone_token"=>$android_token,
                                        "app_version"=>$app_version,
                                        "tgl"=>date("Y-m-d"),
                                        "jam"=>date("H:i:s"),
                                        "app"=>$app_name
                                    ]);
                        }else{

                        $tokenIn= DB::table('keamanan.user_android')
                                    ->where([
                                        ["nik",$user->nik],
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
	 	   		return array("success"=>true,"user"=>$user);

                }else{
	 	   		return array("success"=>false,"user"=>$user);

                }
	 	   	}else{
	 	   		return array("success"=>false,"user"=>$user);
	 	   	}
 	   }
 	   public function loginFace(Request $request)
 	   {
 	   	$dataLogin= array("nik"=>null,"nama"=>null);
 	   	$dbKaryawan = DB::table("db_karyawan.data_karyawan")->where([
                                    ["nik",$request->username],
                                    ["password",md5($request->password)],
                                    ["flag",0]
                                ])->first();
			if($dbKaryawan!=null){
 	   			return (array("success"=>true,"dataLogin"=>$dbKaryawan));
 	   		}else{
 	   			return (array("success"=>false,"dataLogin"=>$dbKaryawan));
 	   		}
 	   }
       public function saveMapPoint(Request $request)
       {
           $mapPoint = DB::table("absensi.map_area")
                    ->insert([
                                "company"=>"0",
                                "lat"=>$request->lat,
                                "lng"=>$request->lng,
                            ]);
            if($mapPoint){
                return ["success"=>true];
            }else{
                return ["success"=>false];
            }
       }

       public function delMapPoint(Request $request)
       {
           $mapPoint = DB::table("absensi.map_area")
                    ->where("idLok",$request->idLok)->delete();
            if($mapPoint){
                return ["success"=>true];
            }else{
                return ["success"=>false];
            }
       }
       public function editMapPoint(Request $request)
       {
           $mapPoint = DB::table("absensi.map_area")
                    ->where("idLok",$request->idLok)
                    ->update([
                        "lat"=>$request->lat,
                        "lng"=>$request->lng,
                    ]);
            if($mapPoint){
                return ["success"=>true];
            }else{
                return ["success"=>false];
            }
       }

    public function lastAbsen(Request $request)
    {
        $roster = DB::table("db_karyawan.roster_kerja as a")
                    ->join("db_karyawan.kode_jam_masuk as b","b.id_kode","a.jam_kerja")
                    ->where([["a.nik",$request->nik],["a.tanggal",date("Y-m-d",strtotime("-1 day"))]])
                    ->join("db_karyawan.jam_kerja as c","c.no","b.id_jam_kerja")
                    ->select("a.id_roster","a.nik","a.tanggal","b.kode_jam","c.masuk","c.pulang")
                    ->first();
        if($roster!=null){

            $sekarang = strtotime(date("H:i:s"));
            $masuk = strtotime(date("H:i:s",strtotime($roster->masuk)));
            $pulang = strtotime(date("H:i:s",strtotime($roster->pulang)));
            // dd($roster);

            if($sekarang>=$masuk){
                $idRoster = $roster->id_roster;
                $kodeRoster = $roster->kode_jam;
                $tglRoster = $roster->tanggal;
                $jamKerja = date('H:i',strtotime($roster->masuk))." - ".date('H:i',strtotime($roster->pulang));
            }else{
                $ceklog = DB::table("absensi.ceklog")->where([["nik",$roster->nik],["tanggal",$roster->tanggal],["status","Pulang"]])->first();
                // dd($ceklog);
                if(isset($ceklog->status)){
                if($ceklog->status=="Pulang"){
                    $roster = DB::table("db_karyawan.roster_kerja as a")
                    ->join("db_karyawan.kode_jam_masuk as b","b.id_kode","a.jam_kerja")
                    ->join("db_karyawan.jam_kerja as c","c.no","b.id_jam_kerja")
                    ->where([["a.nik",$request->nik],["a.tanggal",date("Y-m-d")]])
                    ->select("a.id_roster","a.nik","a.tanggal","b.kode_jam","c.masuk","c.pulang")
                    ->first();
                    $idRoster = $roster->id_roster;
                    $kodeRoster = $roster->kode_jam;
                    $tglRoster = $roster->tanggal;
                    $jamKerja = date('H:i',strtotime($roster->masuk))." - ".date('H:i',strtotime($roster->pulang));
                }else{
                    $idRoster = $roster->id_roster;
                    $kodeRoster = $roster->kode_jam;
                    $tglRoster = $roster->tanggal;
                    $jamKerja = date('H:i',strtotime($roster->masuk))." - ".date('H:i',strtotime($roster->pulang));
                }
              }else{
                $idRoster = $roster->id_roster;
                $kodeRoster = $roster->kode_jam;
                $tglRoster = $roster->tanggal;
                $jamKerja = date('H:i',strtotime($roster->masuk))." - ".date('H:i',strtotime($roster->pulang));
              }
            }
        }else{
                    $idRoster = 0;
                    $kodeRoster = "";
                    $tglRoster = "";
                    $jamKerja = "";
        }
        $last = DB::table('absensi.ceklog')
                ->where([
                  ["nik",$request->nik],
                  ["tanggal",date("Y-m-d")]
                ])
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal","desc")
                ->first();
        $lastNew = DB::table('absensi.ceklog')
                ->where("nik",$request->nik)
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->first();
        $presensiMasuk = DB::table('absensi.ceklog')
                ->where([["nik",$request->nik],["status","Masuk"],
                  ["tanggal",date("Y-m-d")]])
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->first();

        $presensiPulang = DB::table('absensi.ceklog')
                ->where([["nik",$request->nik],["status","Pulang"],
                  ["tanggal",date("Y-m-d")]])
                ->select("absensi.ceklog.*",DB::raw("CONCAT(tanggal,' ',jam) as tanggal_jam"))
                ->orderBy("tanggal_jam","desc")
                ->first();
        $roster = DB::table('db_karyawan.roster_kerja')
                ->join("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
                ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                ->where([
                  ["nik",$request->nik],
                  ["tanggal",date("Y-m-d",strtotime($lastNew->tanggal))]
                ])
                ->first();
        if(isset($last)){
          if(isset($lastNew)){
            if(isset($roster)){
              return array("idRoster"=>$idRoster,
              "kodeRoster"=>$kodeRoster,
              "tglRoster"=>$tglRoster,
              "jamKerja"=>$jamKerja,
              "lastAbsen"=>$last->status,
              "lastNew"=>$lastNew->status,
              "tanggal"=>$lastNew->tanggal,
              "masuk"=>$roster->masuk,
              "pulang"=>$roster->pulang,
              "presensiMasuk"=>$presensiMasuk,
              "presensiPulang"=>$presensiPulang,
              "jam_server"=>array("jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s")));
            }else{
              return array("idRoster"=>$idRoster,
              "kodeRoster"=>$kodeRoster,
              "tglRoster"=>$tglRoster,
              "jamKerja"=>$jamKerja,
              "lastAbsen"=>$last->status,
              "lastNew"=>$lastNew->status,
              "masuk"=>null,"pulang"=>null,
              "presensiPulang"=>$presensiPulang,
              "jam_server"=>array("jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s")));
            }
          }else{
              return array("idRoster"=>$idRoster,
              "kodeRoster"=>$kodeRoster,
              "tglRoster"=>$tglRoster,
              "jamKerja"=>$jamKerja,
              "lastAbsen"=>$last->status,
              "lastNew"=>null,
              "masuk"=>null,
              "pulang"=>null,
              "presensiMasuk"=>null,
              "presensiPulang"=>null,
              "presensiPulang"=>$presensiPulang,
              "jam_server"=>array("jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s")));
          }
        }else{
          if(isset($lastNew)){
            if(isset($roster)){
              $masuk = strtotime($roster->masuk);
              $pulang = strtotime($roster->pulang);
              if($pulang<$masuk){
                $masukNew = date("Y-m-d H:i:s",$masuk);
                $pulangNew = date("Y-m-d H:i:s",strtotime("+1 day ",$pulang));
              return array("idRoster"=>$idRoster,
                            "kodeRoster"=>$kodeRoster,
                            "tglRoster"=>$tglRoster,
                            "jamKerja"=>$jamKerja,
                            "lastAbsen"=>null,
                            "lastNew"=>$lastNew->status,
                            "masuk"=>date("H:i:s",$masuk),
                            "pulang"=>$pulangNew,
                            "presensiMasuk"=>$presensiMasuk,
                            "presensiPulang"=>$presensiPulang,
                            "presensiPulang"=>$presensiPulang,
                            "jam_server"=>
                                          array("jam"=>date("H"),
                                                "menit"=>date("i"),
                                                "detik"=>date("s"))
                          );
              }else{
                $pulangNew = date("Y-m-d H:i:s",$pulang);
                return array("idRoster"=>$idRoster,
                              "kodeRoster"=>$kodeRoster,
                              "tglRoster"=>$tglRoster,
                              "jamKerja"=>$jamKerja,
                              "lastAbsen"=>null,
                              "lastNew"=>$lastNew->status,
                              "masuk"=>date("H:i:s",$masuk),
                              "pulang"=>$pulangNew,
                              "presensiMasuk"=>$presensiMasuk,
                              "presensiPulang"=>$presensiPulang,
                              "presensiPulang"=>$presensiPulang,
                              "jam_server"=>array("jam"=>date("H"),
                              "menit"=>date("i"),
                              "detik"=>date("s")));
              }
            }else{
              if(isset($lastNew->status)){
                return array("idRoster"=>$idRoster,
                              "kodeRoster"=>$kodeRoster,
                              "tglRoster"=>$tglRoster,
                              "jamKerja"=>$jamKerja,
                              "lastAbsen"=>null,
                              "lastNew"=>$lastNew->status,
                              "masuk"=>null,
                              "pulang"=>null,
                              "presensiMasuk"=>$presensiMasuk,
                              "presensiPulang"=>$presensiPulang,
                              "presensiPulang"=>$presensiPulang,
                              "jam_server"=>array("jam"=>date("H"),
                              "menit"=>date("i"),"detik"=>date("s")));
              }else{
                return array("idRoster"=>$idRoster,
                              "kodeRoster"=>$kodeRoster,
                              "tglRoster"=>$tglRoster,
                              "jamKerja"=>$jamKerja,
                              "lastAbsen"=>null,
                              "lastNew"=>null,
                              "masuk"=>null,
                              "pulang"=>null,
                              "presensiMasuk"=>$presensiMasuk,
                              "presensiPulang"=>$presensiPulang,
                              "presensiPulang"=>$presensiPulang,
                              "jam_server"=>array("jam"=>date("H"),
                              "menit"=>date("i"),
                              "detik"=>date("s")));
              }

            }

        }else{
          return array("idRoster"=>null,
          "kodeRoster"=>null,
          "tglRoster"=>null,
          "jamKerja"=>null,
          "lastAbsen"=>null,
          "lastNew"=>null,
          "masuk"=>null,
          "pulang"=>null,
          "presensiMasuk"=>null,
          "presensiPulang"=>null,
          "presensiPulang"=>$presensiPulang,
          "jam_server"=>array("jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s")));
        }

        }
    }
    public function saveBuletin(Request $request)
    {
        $in = DB::table("hse.message_info")->insert([
                                                      "judul"=>$request->judul,
                                                      "pesan"=>$request->pesan,
                                                      "tgl"=>($request->tgl)?date("Y-m-d",strtotime($request->tgl)):date("Y-m-d"),
                                                      ]);
        if($in>0){
          return ["success"=>true];
        }else{
          return ["success"=>false];
        }

    }
    public function deleteBuletin(Request $request)
    {
        $in = DB::table("hse.message_info")->where("id_info",$request->idInfo)->delete();
        if($in){
          return ["success"=>true];
        }else{
          return ["success"=>false];
        }

    }
    public function updateBuletin(Request $request)
    {
        $in = DB::table("hse.message_info")
        ->where("id_info",$request->id_info)
        ->update([
          "judul"=>$request->judul,
          "pesan"=>$request->pesan,
          "tgl"=>($request->tgl)?date("Y-m-d",strtotime($request->tgl)):date("Y-m-d"),
        ]);
        if($in){
          return ["success"=>true,"data"=>$request->id_info];
        }else{
          return ["success"=>false,"data"=>$request->id_info];
        }
    }
    public function showHideBuletin(Request $request)
    {
        $in = DB::table("hse.message_info")
        ->where("id_info",$request->id_info)
        ->update([
          "status"=>$request->status
        ]);
        if($in){
          return ["success"=>true,"data"=>$request->id_info];
        }else{
          return ["success"=>false,"data"=>$request->id_info];
        }

    }

    public function loginTest(Request $request)
    {
        $result = ["success"=>false,"users"=>null];

        $user = DB::table("test.login")->whereRaw("username='".$request->username."' and password ='".md5($request->password)."'")->first();
        if($user!=null){
            $result = ["success"=>true,"users"=>$user];
        }
        return $result;
    }
    public function testDataSiswa(Request $request)
    {
        $result = ["success"=>false,"users"=>null];

        $user = DB::table("test.data_siswa")->get();
        if(count($user)>0){
            $result = ["success"=>true,"data_siswa"=>$user];
        }
        return $result;
    }

    public function siswaIn(Request $request)
    {
      $dir = "test_upload/";
      $foto_siswa = $request->file('foto_siswa');
      $nama_foto = $foto_siswa->getClientOriginalName();
      if($foto_siswa->move($dir,$nama_foto)){
      $in = DB::table("test.data_siswa")
            ->insert([
              "nama_depan"=>$request->nama_depan,
              "nama_belakang"=>$request->nama_belakang,
              "no_hp"=>$request->no_hp,
              "gender"=>$request->gender,
              "jenjang"=>$request->jenjang,
              "alamat"=>$request->alamat,
              "hobi"=>$request->hobi,
              "foto"=>$nama_foto,
            ]);
      if($in){
        return ["success"=>true];
      }else{
        return ["success"=>false];
      }
      }else{
        return ["success"=>false];
      }
    }

    public function siswaPut(Request $request)
    {
      $dir = "test_upload/";
      $foto_siswa = $request->file('foto_siswa');
      $nama_foto = $foto_siswa->getClientOriginalName();
      if($foto_siswa->move($dir,$nama_foto)){
        $up = DB::table("test.data_siswa")
              ->where("id_siswa",$request->id_siswa)
              ->update([
                "nama_depan"=>$request->nama_depan,
                "nama_belakang"=>$request->nama_belakang,
                "no_hp"=>$request->no_hp,
                "gender"=>$request->gender,
                "jenjang"=>$request->jenjang,
                "alamat"=>$request->alamat,
                "hobi"=>$request->hobi,
                "foto"=>$nama_foto,
              ]);
        if($up){
          return ["success"=>true];
        }else{
          return ["success"=>false];
        }
      }else{
        return ["success"=>false];
      }
    }
    public function siswaPutData(Request $request)
    {
        $up = DB::table("test.data_siswa")
              ->where("id_siswa",$request->id_siswa)
              ->update([
                "nama_depan"=>$request->nama_depan,
                "nama_belakang"=>$request->nama_belakang,
                "no_hp"=>$request->no_hp,
                "gender"=>$request->gender,
                "jenjang"=>$request->jenjang,
                "alamat"=>$request->alamat,
                "hobi"=>$request->hobi,
              ]);
        if($up){
          return ["success"=>true];
        }else{
          return ["success"=>false];
        }
    }

    public function siswaDel(Request $request)
    {
      $del = DB::table("test.data_siswa")
            ->where("id_siswa",$request->id_siswa)
            ->delete();
      if($del){
        return ["success"=>true];
      }else{
        return ["success"=>false];
      }
    }
    public function createIDcard(Request $request)
    {
      return view("landing.index");
    }

    public function testDataLogin(Request $request)
    {
        $result = ["success"=>false,"users"=>null];

        $user = DB::table("test.login")->get();
        if(count($user)>0){
            $result = ["success"=>true,"user_login"=>$user];
        }
        return $result;
    }

    public function inTestDataLogin(Request $request)
    {
      $in = DB::table("test.login")
            ->insert([
              "username"=>$request->username,
              "password"=>md5($request->password),
              "nama"=>$request->nama,
            ]);
      if($in){
        return ["success"=>true];
      }else{
        return ["success"=>false];
      }
    }
    public function putTestDataLogin(Request $request)
    {
      $up = DB::table("test.login")
            ->where("id_login",$request->id_login)
            ->update([
              "username"=>$request->username,
              "password"=>md5($request->password),
              "nama"=>$request->nama,
            ]);
      if($up){
        return ["success"=>true];
      }else{
        return ["success"=>false];
      }
    }

    public function loginDel(Request $request)
    {
      $del = DB::table("test.login")
            ->where("id_login",$request->id_login)
            ->delete();
      if($del){
        return ["success"=>true];
      }else{
        return ["success"=>false];
      }
    }
    public function rosterKaryawan(Request $request)
    {
      $db = DB::table("db_karyawan.roster_kerja as a")
            ->join("db_karyawan.kode_jam_masuk as b","b.id_kode","a.jam_kerja")
            ->join("db_karyawan.jam_kerja as c","c.no","b.id_jam_kerja")
            ->where([["a.nik",$request->nik],["a.tahun",$request->tahun],["a.bulan",$request->bulan]])
            ->get();
      return ["roster"=>$db];
    }

}
