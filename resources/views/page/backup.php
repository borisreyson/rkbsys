if(!isset($_SESSION['username'])){ return redirect('/')};  

        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        } 

        $dbKaryawan = DB::table("db_karyawan.data_karyawan")->where("flag",0);
          if(isset($_GET['dept']))
          {

            $dept = DB::table("department")->where("id_dept",$_GET['dept'])->first();
            $filter = $dbKaryawan->where("db_karyawan.data_karyawan.departemen",$_GET['dept']);
          }else{
            $filter = $dbKaryawan;
          } 
          $data = $filter->get();
          // $x = 'B';
          // foreach ($data as $key => $value) {
          // echo $x."<br/>";
          // $x++;
          // }
          $newArr=[];
          $arrAbsen=[];
          $finger=array();
          $flag=array();
          $telat=array();
$start = strtotime(date("Y-m-d"));
$end = strtotime(date("Y-m-d"));
if(isset($_GET['dari'])){
  $start = strtotime(date("Y-m-d",strtotime($_GET['dari'])));
}
if(isset($_GET['sampai'])){
  $end = strtotime(date("Y-m-d",strtotime($_GET['sampai'])));
}
$strNew=$start;
$stlEnd=$end;
$DayNew=$start;
$DayEnd=$end;
$j = [];

          foreach ($data as $key => $value) {

$abc="C";
$sA=$start;
$sE=$end;
            array_push($newArr, $value->nama);
            array_push($newArr, $value->nik);
            array_push($arrAbsen, "Masuk");
            array_push($arrAbsen, "Pulang");

while($sA <= $sE)
{

  $masuk = DB::table("absensi.ceklog")
                ->where([
                  ["tanggal",date("Y-m-d",$sA)],
                  ["nik",$value->nik],
                  ["status","Masuk"]])->first();
                  if($masuk!=null){
$roster = DB::table("db_karyawan.roster_kerja")
          ->join("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
          ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
          ->where([
            ["db_karyawan.roster_kerja.nik",$masuk->nik],
            ["db_karyawan.roster_kerja.tanggal",date("Y-m-d",strtotime($masuk->tanggal))]
          ])->first();
           // dd($roster);
if(isset($roster->masuk)){
  if(strtotime($masuk->jam)>strtotime($roster->masuk)){
$telat[$abc][] = 1;
  }else{
    if($roster->kode_jam=="OFF"){
      $telat[$abc][] = 2;
    }else{
      $telat[$abc][] = 0;
    }
  }
}else{
$telat[$abc][] = 0;
}
                    $finger[$abc][] = date("H:i",strtotime($masuk->jam));
                    $flag[$abc][] = $masuk->flag;
                  }else{
                    $finger[$abc][]="";
                    $flag[$abc][] = 0;
                    $telat[$abc][] = 0;
                  }
  $pulang = DB::table("absensi.ceklog")
               ->where([
                  ["tanggal",date("Y-m-d",$sA)],
                  ["nik",$value->nik],
                  ["status","Pulang"]])->first();
                  if($pulang!=null){
$roster = DB::table("db_karyawan.roster_kerja")
          ->join("db_karyawan.kode_jam_masuk","db_karyawan.kode_jam_masuk.id_kode","db_karyawan.roster_kerja.jam_kerja")
          ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
          ->where([
            ["db_karyawan.roster_kerja.nik",$pulang->nik],
            ["db_karyawan.roster_kerja.tanggal",date("Y-m-d",strtotime($pulang->tanggal))]
          ])->first();
if(isset($roster->pulang)){

  if(strtotime($pulang->jam) < strtotime($roster->pulang)){

$telat[$abc][] = 1;  

  }else{
    if($roster->kode_jam=="OFF"){
      $telat[$abc][] = 2;
    }else{
      $telat[$abc][] = 0;
    }
  }
}else{
$telat[$abc][] = 0;
}
                    $finger[$abc][]= date("H:i",strtotime($pulang->jam));
                    $flag[$abc][] = $pulang->flag;
                  }else{
                    $finger[$abc][]= "";
                    $flag[$abc][] = 0;
                    $telat[$abc][] = 0;
                  }
  // $j[$abc][]=$value->nik;
  // $j[$abc][]=$value->nik."Pulang";

  $abc++;
  $sA = strtotime("+1 day",$sA);
} 
          }
          
            $filename = "export/Export-Rekap-Absen-".date('d F Y').".xlsx";
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
            $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle('A:ZH')->getAlignment()->setHorizontal('center');
            $sheet->getColumnDimension("A")->setAutoSize(true);
            $sheet->getColumnDimension("B")->setAutoSize(true);
            $sheet->mergeCells('A1:Z1');
            $sheet->mergeCells('A2:Z2');

            $sheet->setTitle("Export Rekap Absen");
            $sheet->setCellValue('A1', 'ABSENSI '.$dept->dept);
            $sheet->setCellValue('A2', "Periode ".date("d F Y",$start)." - ".date("d F Y",$end));
            $sheet->setCellValue('A4', 'Name');
            $sheet->setCellValue('B4', 'Finger');
$a="C";
while($DayNew <= $DayEnd)
{

  $sheet->setCellValue($a."4", date("d F Y",$DayNew));

  $DayNew = strtotime("+1 day",$DayNew);
  $a++;
}

$i=5;
//$abcd="C";
foreach($newArr as $k => $v){

$sNew = $strNew;
$eNew = $stlEnd;
$sheet->setCellValue('A'.$i, $v);
$sheet->setCellValue('B'.$i, $arrAbsen[$k]);
$i++;
}
foreach($finger as $kF => $vF)
{
  foreach($vF as $kk => $vv)
  {
  $sheet->setCellValue($kF.($kk+5), $vv); 

    if($flag[$kF][$kk]==1){
      $sheet->getStyle($kF.($kk+5))->getFont()->getColor()->setARGB('FFFFFF');
      $sheet->getStyle($kF.($kk+5))->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F0160A'); 
    }else{
    if($telat[$kF][$kk]==1){
      $sheet->getStyle($kF.($kk+5))->getFont()->getColor()->setARGB('F0160A');
    }else if($telat[$kF][$kk]==2){
      $sheet->getStyle($kF.($kk+5))->getFont()->getColor()->setARGB('2509E6');
    }  
    }

    
  $sheet->getColumnDimension($kF)->setAutoSize(true);
  // echo $telat[$kF][$kk]."<br>";
  }
  
}

  // die();

            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $logExport = DB::table('export_log')
                        ->insert([
                            "user_export"=>$_SESSION['username'],
                            "desc"      =>"Export Rekap Absen",
                            "date"      => date("Y-m-d H:i:s")
                        ]);
            if($logExport){
                return redirect($filename);
            }