<?php

namespace App\Http\Controllers\hse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use \App\Events\onlineUserEvent;
use Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class hseController extends Controller
{
    private $user;
    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['username'])) return redirect('/');
        $this->user = DB::table('user_login')->where('username',$_SESSION['username'])->first();
    }
    // HAZARD
    public function hazardReport(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');

        $sql = DB::table("hse.hazard_report_header as s")
->leftJoin("hse.hazard_report_detail as a","a.uid","s.uid")
->leftJoin("hse.lokasi as b","b.idLok","s.lokasi")
->leftJoin("hse.hazard_report_validation as c","c.uid","s.uid")
->leftjoin("hse.metrik_resiko_kemungkinan as d","d.idKemungkinan","s.idKemungkinan")
->leftjoin("hse.metrik_resiko_keparahan as e","e.idKeparahan","s.idKeparahan")
->leftjoin("hse.hirarki_pengendalian as f","f.idHirarki","a.idPengendalian")
->leftjoin("hse.metrik_resiko_kemungkinan as g","g.idKemungkinan","a.idKemungkinanSesudah")
->leftjoin("hse.metrik_resiko_keparahan as h","h.idKeparahan","a.idKeparahanSesudah")
->leftJoin("user_login as i","i.username","s.user_input")
->leftJoin("hse.hazard_report_validation as j","j.uid","s.uid")
->leftJoin("user_login as k","k.username","j.user_valid")
->select("s.*","a.*","c.*","b.lokasi as lokasiHazard","d.kemungkinan as kSebelum","d.nilai as nilaiKemungkinan","e.keparahan as kpSebelum","e.nilai as nilaiKeparahan","f.*","g.nilai as nilaiKemungkinanSesudah","e.*","h.nilai as nilaiKeparahanSesudah","i.nama_lengkap as namaPelapor","i.nik as nikPelapor","g.kemungkinan as kSesudah","h.keparahan as kpSesudah","k.nama_lengkap as namaVerify","j.user_valid")
->orderBy("s.idHazard","DESC");
        if(isset($request->dari)){
            $filter = $sql->whereBetween("s.tgl_hazard",
                            [date("Y-m-d",strtotime($request->dari))
                            ,date("Y-m-d",strtotime($request->sampai))]);
        }else{
            $filter = $sql;
        }
        if(isset($request->validasi)){
            if($request->validasi=='1'){
            $valid = $filter->whereRaw("c.user_valid IS NOT NULL and c.option_flag ='1'");
            }elseif($request->validasi=='2'){
            $valid = $filter->whereRaw("c.user_valid IS NULL ");
            }elseif($request->validasi=='3'){
            $valid = $filter->whereRaw("c.option_flag ='0' and c.user_valid IS NOT NULL");
            }
        }else{
            $valid = $filter;
        }
		$hazard = $valid->paginate(5);
        return view('hse.hazard',["hazard"=>$hazard,"getUser"=>$this->user]);
    }
    public function hazardReportVerifikasi(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
    	$uid= hex2bin($request->uid);
    	$validator = DB::table("hse.hazard_report_validation")
    				->where("uid",$uid)
    				->update([
    					"user_valid"=>$_SESSION['username'],
    					"tgl_valid"=>date("Y-m-d"),
    					"jam_valid"=>date("H:i:s")
    				]);
    	if($validator>=0){
    		return redirect()->back()->with("success","Berhasil Verifikasi");
    	}else{
    		return redirect()->back()->with("failed","Verifikasi Tidak Berhasil");

    	}
    }
    public function exportHazardReport(Request $request)
    {
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
                }
        $path = $_SERVER['DOCUMENT_ROOT']."/bukti_hazard/";
        $path1 = $_SERVER['DOCUMENT_ROOT']."/bukti_hazard/penanggung_jawab/";
        $path2 = $_SERVER['DOCUMENT_ROOT']."/bukti_hazard/update/";

        // die();
        $sql = DB::table("hse.hazard_report_header as s")
        ->leftJoin("hse.hazard_report_detail as a","a.uid","s.uid")
        ->leftJoin("hse.lokasi as b","b.idLok","s.lokasi")
        ->leftJoin("hse.hazard_report_validation as c","c.uid","s.uid")
        ->leftjoin("hse.metrik_resiko_kemungkinan as d","d.idKemungkinan","s.idKemungkinan")
        ->leftjoin("hse.metrik_resiko_keparahan as e","e.idKeparahan","s.idKeparahan")
        ->leftjoin("hse.hirarki_pengendalian as f","f.idHirarki","a.idPengendalian")
        ->leftjoin("hse.metrik_resiko_kemungkinan as g","g.idKemungkinan","a.idKemungkinanSesudah")
        ->leftjoin("hse.metrik_resiko_keparahan as h","h.idKeparahan","a.idKeparahanSesudah")
        ->leftJoin("user_login as i","i.username","s.user_input")
        ->select("s.*","a.*","c.*","b.lokasi as lokasiHazard","d.kemungkinan as kSebelum","d.nilai as nilaiKemungkinan","e.keparahan as kpSebelum","e.nilai as nilaiKeparahan","f.*","g.nilai as nilaiKemungkinanSesudah","e.*","h.nilai as nilaiKeparahanSesudah","i.nama_lengkap as namaPelapor","i.nik as nikPelapor","g.kemungkinan as kSesudah","h.keparahan as kpSesudah")
        ->orderBy("s.idHazard","ASC");
        if(isset($request->dari)){
            $filter = $sql->whereBetween("s.tgl_hazard",
                            [date("Y-m-d",strtotime($request->dari))
                            ,date("Y-m-d",strtotime($request->sampai))]);
        $filename = "export/Hazard Report ".$request->dari."-".$request->sampai.".xlsx";
        }else{
            $filter = $sql;
            $filename = "export/All Hazard Report.xlsx";
        }
        if(isset($request->validasi)){
            if($request->validasi=='1'){
            $valid = $filter->whereRaw("c.user_valid IS NOT NULL and c.option_flag ='1'");
            }elseif($request->validasi=='2'){
            $valid = $filter->whereRaw("c.user_valid IS NULL ");
            }elseif($request->validasi=='3'){
            $valid = $filter->whereRaw("c.option_flag ='0' and c.user_valid IS NOT NULL");
            }
        }else{
            $valid = $filter;
        }
        $hazard = $valid->get();
        $z=2;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
        $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
        $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(65);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A:AD')
                ->getAlignment()
                ->setHorizontal('center')
                ->setVertical('center');
        $sheet
        ->getStyle('A1:AD1')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('071A40');
        $sheet->getStyle('A1:AD1')->getFont()->getColor()->setARGB("FFFFFF");
        $sheet->getStyle('A1:AD1')
                ->getAlignment()
                ->setWrapText(true);
        $sheet->setTitle("HAZARD REPORT");
        foreach ($this->excelColumnRange("A","AD") as $value) {
            if($value!="A" && $value!="B" && $value!="N"  && $value!="T" ){
                $sheet->getColumnDimension($value)->setAutoSize(true);
            }
        }
        // $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(30);
        $sheet->getColumnDimension('T')->setWidth(30);
        $sheet->setCellValue('A1', strtoupper('No'));
        $sheet->setCellValue('B1', strtoupper('Bukti Temuan'));
        $sheet->setCellValue('C1', strtoupper('Tanggal Temuan'));
        $sheet->setCellValue('D1', strtoupper('Jam Temuan'));
        $sheet->setCellValue('E1', strtoupper('Nama Pelapor'));
        $sheet->setCellValue('F1', strtoupper('Nik Pelapor'));
        $sheet->setCellValue('G1', strtoupper('Kondisi'));
        $sheet->setCellValue('H1', strtoupper('Perusahaan'));
        $sheet->setCellValue('I1', strtoupper('Lokasi'));
        $sheet->setCellValue('J1', strtoupper('Detail Lokasi'));
        $sheet->setCellValue('K1', strtoupper('Deskripsi Temuan'));
        $sheet->setCellValue('L1', strtoupper('Nilai Kemungkinan'));
        $sheet->setCellValue('M1', strtoupper('Nilai Keparahan'));
        $sheet->setCellValue('N1', strtoupper('Tingkat Resiko'));
        $sheet->setCellValue('O1', strtoupper('Nama Penanggung Jawab'));
        $sheet->setCellValue('P1', strtoupper('Nik Penanggung Jawab'));
        $sheet->setCellValue('Q1', strtoupper('Foto Penanggung Jawab'));
        $sheet->setCellValue('R1', strtoupper('Hiaraki Pengendalian'));
        $sheet->setCellValue('S1', strtoupper('Tindakan'));
        $sheet->setCellValue('T1', strtoupper('Status Perbaikan'));
        $sheet->setCellValue('U1', strtoupper('Tanggal Tenggat'));
        $sheet->setCellValue('V1', strtoupper('Tanggal Selesai'));
        $sheet->setCellValue('W1', strtoupper('Jam Selesai'));
        $sheet->setCellValue('X1', strtoupper('Bukti Perbaikan'));
        $sheet->setCellValue('Y1', strtoupper('Keterangan Perbaikan'));
        $sheet->setCellValue('Z1', strtoupper('Nilai Kemungkinan'));
        $sheet->setCellValue('AA1', strtoupper('Nilai Keparahan'));
        $sheet->setCellValue('AB1', strtoupper('Tingkat Resiko'));
        $sheet->setCellValue('AC1', strtoupper('Status'));
        $sheet->setCellValue('AD1', strtoupper('Keterangan Hazard'));
        foreach($hazard as $k => $v){
// INITIAL DRAWING
// INITIAL DRAWING
        $sheet->setCellValue('A'.$z, ($z-1));
        $drawing[$k]=new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing[$k]->setWorksheet($sheet);
        $drawing[$k]->setPath($path.$v->bukti);
        $drawing[$k]->setCoordinates('B'.$z);
        $drawing[$k]->setHeight(70);
        $drawing[$k]->getShadow()->setVisible(true);
        $drawing[$k]->getShadow()->setDirection(50);
        $drawing[$k]->setOffsetX(30);
        $drawing[$k]->setOffsetY(6);


        // $sheet->setCellValue('B'.$z, $v->bukti);
        $sheet->setCellValue('C'.$z, date("d F Y",strtotime($v->tgl_hazard)));
        $sheet->setCellValue('D'.$z, date("H:i:s",strtotime($v->jam_hazard)));
        $sheet->setCellValue('E'.$z, $v->namaPelapor);
        $sheet->setCellValue('F'.$z, $v->nikPelapor);
        $sheet->setCellValue('G'.$z, $v->katBahaya);
        $sheet->setCellValue('H'.$z, $v->perusahaan);
        $sheet->setCellValue('I'.$z, $v->lokasiHazard);
        $sheet->setCellValue('J'.$z, $v->lokasi_detail);
        $sheet->setCellValue('K'.$z, $v->deskripsi);
// MATRIK RESIKO
        $sheet->setCellValue('L'.$z, "".$v->kSebelum."\nNilai: ".$v->nilaiKemungkinan."");
        $sheet->getStyle('L'.$z)
                ->getAlignment()
                ->setWrapText(true);

        $sheet->setCellValue('M'.$z, "".$v->kpSebelum."\nNilai: ".$v->nilaiKeparahan."");
        $sheet->getStyle('M'.$z)
                ->getAlignment()
                ->setWrapText(true);

        $hasil = $v->nilaiKemungkinan*$v->nilaiKeparahan;
        $hsResiko = DB::table("hse.metrik_resiko")->where("max",">=",$hasil)->where("min","<=",$hasil)->first();

        $txtColor = explode("#",$hsResiko->txtColor);
        $bg = explode("#",$hsResiko->bgColor);
        $sheet->getStyle("N".$z)->getFont()->getColor()->setARGB($txtColor[1]);
        $sheet->getStyle("N".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB($bg[1]);
        $sheet->setCellValue('N'.$z,$hsResiko->kodeBahaya." \n ".$hsResiko->kategori." \n ".$hsResiko->tindakan);
        $sheet->getStyle('N'.$z)
                ->getAlignment()
                ->setWrapText(true);
// MATRIK RESIKO
        $sheet->setCellValue('O'.$z, $v->namaPJ);
        $sheet->setCellValue('P'.$z, $v->nikPJ);
        // $sheet->setCellValue('L'.$z, $v->fotoPJ);
        if($v->fotoPJ!=null){
            $drawing1[$k]=new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing1[$k]->setWorksheet($sheet);
            $drawing1[$k]->setPath($path1.$v->fotoPJ);
            $drawing1[$k]->setCoordinates('Q'.$z);
            $drawing1[$k]->setHeight(70);
            $drawing1[$k]->getShadow()->setVisible(true);
            $drawing1[$k]->getShadow()->setDirection(50);
            $drawing1[$k]->setOffsetX(75);
            $drawing1[$k]->setOffsetY(6);
        }else{
        $sheet->setCellValue('Q'.$z, "-");
        }

        $sheet->setCellValue('R'.$z, $v->namaPengendalian);
        $sheet->setCellValue('S'.$z, $v->tindakan);
        $sheet->getStyle("T".$z)->getFont()->getColor()->setARGB("FFFFFF");
        if($v->status_perbaikan=="SELESAI"){
            $sheet
                ->getStyle("T".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('009C5E');
            $sheet->setCellValue('T'.$z, $v->status_perbaikan);
        }else if($v->status_perbaikan=="BELUM SELESAI"){
            $sheet
                ->getStyle("T".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('BF0A0A');
            $sheet->setCellValue('T'.$z, $v->status_perbaikan);
        }else if($v->status_perbaikan=="BERLANJUT"){
            $sheet
                ->getStyle("T".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('0D518C');
            $sheet->setCellValue('T'.$z, $v->status_perbaikan);
        }else if($v->status_perbaikan=="Dalam Pengerjaan"){
            $sheet
                ->getStyle("T".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('8D7E3E');
            $sheet->setCellValue('T'.$z, $v->status_perbaikan);
        }
        if($v->tgl_selesai==null){
            $sheet->setCellValue('U'.$z, date("d F Y",strtotime($v->tgl_tenggat)));
        }else{
            $sheet->setCellValue('U'.$z,"-");
        }
        if($v->tgl_selesai!=null){
            $sheet->setCellValue('V'.$z, date("d F Y",strtotime($v->tgl_selesai)));
        }else{
            $sheet->setCellValue('V'.$z, "-");
        }
        if($v->jam_selesai!=null){
            $sheet->setCellValue('W'.$z, date("H:i:s",strtotime($v->jam_selesai)));
        }else{
            $sheet->setCellValue('W'.$z, "-");
        }
        if($v->update_bukti!=null){
            // $sheet->setCellValue('R'.$z, $v->update_bukti);
            $drawing2[$k]=new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing2[$k]->setWorksheet($sheet);
            $drawing2[$k]->setPath($path2.$v->update_bukti);
            $drawing2[$k]->setCoordinates('X'.$z);
            $drawing2[$k]->setHeight(70);
            $drawing2[$k]->getShadow()->setVisible(true);
            $drawing2[$k]->getShadow()->setDirection(50);
            $drawing2[$k]->setOffsetX(75);
            $drawing2[$k]->setOffsetY(6);
        }else{
            $sheet->setCellValue('X'.$z, "-");
        }
        if($v->keterangan_update!=null){
        $sheet->setCellValue('Y'.$z, $v->keterangan_update);
        }else{
        $sheet->setCellValue('Y'.$z, "-");
        }
        if($v->tgl_selesai!=null){
// MATRIK RESIKO SESUDAH
        $sheet->setCellValue('Z'.$z, "".$v->kSesudah."\nNilai: ".$v->nilaiKemungkinanSesudah."");
        $sheet->getStyle('Z'.$z)
                ->getAlignment()
                ->setWrapText(true);

        $sheet->setCellValue('AA'.$z, "".$v->kpSesudah."\nNilai: ".$v->nilaiKeparahanSesudah."");
        $sheet->getStyle('AA'.$z)
                ->getAlignment()
                ->setWrapText(true);

        $hasilSesudah = $v->nilaiKemungkinanSesudah*$v->nilaiKeparahanSesudah;
        $hsResikoSesudah = DB::table("hse.metrik_resiko")->where("max",">=",$hasilSesudah)->where("min","<=",$hasilSesudah)->first();

        $txtColor = explode("#",$hsResikoSesudah->txtColor);
        $bg = explode("#",$hsResikoSesudah->bgColor);
        $sheet->getStyle("AB".$z)->getFont()->getColor()->setARGB($txtColor[1]);
        $sheet->getStyle("AB".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB($bg[1]);
        $sheet->setCellValue('AB'.$z,$hsResikoSesudah->kodeBahaya." \n ".$hsResikoSesudah->kategori." \n ".$hsResikoSesudah->tindakan);
        $sheet->getStyle('AB'.$z)
                ->getAlignment()
                ->setWrapText(true);
// MATRIK RESIKO SESUDAH
}else{
        $sheet->setCellValue('Z'.$z, "-");
        $sheet->setCellValue('AA'.$z, "-");
        $sheet->setCellValue('AB'.$z, "-");

}
        if($v->user_valid!=null){
            if($v->option_flag=='0'){
                $sheet->getStyle("AC".$z)->getFont()->getColor()->setARGB("BF0A0A");
                $sheet->setCellValue('AC'.$z, "Di Batalkan");
                $sheet->setCellValue('AD'.$z, ($v->keterangan_admin)?$v->keterangan_admin:"-");
            }elseif($v->option_flag=="1"){
                $sheet->getStyle("AC".$z)->getFont()->getColor()->setARGB("009C5E");
                $sheet->setCellValue('AC'.$z, "Di Disetujui");
                $sheet->setCellValue('AD'.$z, ($v->keterangan_admin)?$v->keterangan_admin:"-");
            }else{
                $sheet->getStyle("AC".$z)->getFont()->getColor()->setARGB("009C5E");
                $sheet->setCellValue('AC'.$z, "Di Disetujui");
                $sheet->setCellValue('AD'.$z, ($v->keterangan_admin)?$v->keterangan_admin:"-");
            }
            
        }else{
            $sheet->getStyle("AC".$z)->getFont()->getColor()->setARGB("BF0A0A");
            $sheet->setCellValue('AC'.$z, "Belum Di Setujui");
            $sheet->setCellValue('AD'.$z, ($v->keterangan_admin)?$v->keterangan_admin:"-");
        }


        $z++;
        }
        // $drawing1->setWorksheet($spreadsheet->getActiveSheet());
        // $drawing2->setWorksheet($spreadsheet->getActiveSheet());
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $logExport = DB::table('export_log')
                    ->insert([
                        "user_export"=>$_SESSION['username'],
                        "desc"      =>"All Hazard Report",
                        "date"      => date("Y-m-d H:i:s")
                    ]);
        if($logExport){

            return redirect($filename);
        }
    }
    public function excelColumnRange($lower, $upper) {
        ++$upper;
        for ($i = $lower; $i !== $upper; ++$i) {
            yield $i;
        }
    }
    // INSPEKSI

    public function inspeksiReport(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $sql = DB::table("hse.form_inspeksi_header as a")
                            ->leftJoin("hse.form_inspeksi as c","c.idForm","a.idForm")
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
                            ->paginate(5);

        return view('hse.inspeksi',["inspeksiHeader"=>$inspeksiHeader,"getUser"=>$this->user]);

    }
    public function exportInspeksiReport(Request $request)
    {
        $files = glob('export/*');
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
                }
        $path = $_SERVER['DOCUMENT_ROOT']."/bukti_inspeksi/sebelum/";
        $path1 = $_SERVER['DOCUMENT_ROOT']."/bukti_inspeksi/sesudah/";

        // die();
        $sql = DB::table("hse.form_inspeksi_header as a")
        ->leftJoin("hse.form_inspeksi as c","c.idForm","a.idForm")
        ->leftJoin("db_karyawan.perusahaan as d","d.id_perusahaan","a.perusahaan");
        if(isset($request->dari)){
            $filter = $sql->whereBetween("a.tgl_inspeksi",
                            [date("Y-m-d",strtotime($request->dari))
                            ,date("Y-m-d",strtotime($request->sampai))])
                            ->orderBy("a.INC","DESC");
        $filename = "export/Inspection Report ".$request->dari."-".$request->sampai.".xlsx";
        }else{
            $filter = $sql->orderBy("a.INC","ASC");
            $filename = "export/All Inspection Report.xlsx";
        }
        $inspeksi = $filter->get();
        $z=2;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("IT ABP ENERGY");
        $spreadsheet->getProperties()->setLastModifiedBy("SYSTEM ABP ENERGY");
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle('A:R')
                ->getAlignment()
                ->setHorizontal('center')
                ->setVertical('center');
        $sheet
        ->getStyle('A1:R1')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('071A40');
        $sheet->getStyle('A1:R1')->getFont()->getColor()->setARGB("FFFFFF");
        $sheet->getStyle('A1:R1')
                ->getAlignment()
                ->setWrapText(true);
        $sheet->setTitle("INSPECTION REPORT");
        foreach ($this->excelColumnRange("A","R") as $value) {
            if($value!="A" && $value!="H" && $value!="N" ){
                $sheet->getColumnDimension($value)->setAutoSize(true);
            }
        }
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(30);
        $sheet->setCellValue('A1', strtoupper('No'));
        $sheet->setCellValue('B1', strtoupper('Inspeksi'));
        $sheet->setCellValue('C1', strtoupper('Tanggal Inspeksi'));
        $sheet->setCellValue('D1', strtoupper('Perusahaan'));
        $sheet->setCellValue('E1', strtoupper('Lokasi'));
        $sheet->setCellValue('F1', strtoupper('Saran'));
        $sheet->setCellValue('G1', strtoupper('Team Inspeksi'));
        $sheet->setCellValue('H1', strtoupper('Foto Temuan'));
        $sheet->setCellValue('I1', strtoupper('Temuan'));
        $sheet->setCellValue('J1', strtoupper('Tindakan'));
        $sheet->setCellValue('K1', strtoupper('Nik Penanggung Jawab'));
        $sheet->setCellValue('L1', strtoupper('Nama Penanggung Jawab'));
        $sheet->setCellValue('M1', strtoupper('Tanggal Tenggat'));
        $sheet->setCellValue('N1', strtoupper('Foto Perbaikan'));
        $sheet->setCellValue('O1', strtoupper('Keterangan Perbaikan'));
        $sheet->setCellValue('P1', strtoupper('Tanggal Selesai'));
        $sheet->setCellValue('Q1', strtoupper('Status'));
        $sheet->setCellValue('R1', strtoupper('Validasi'));
        foreach($inspeksi as $k => $v){
            $pica[$k] = DB::table("hse.form_inspeksi_pika as a")
                            ->where("a.idInspeksi",$v->idInspeksi)
                            ->get();
        if(count($pica[$k])>0){
            foreach($pica[$k] as $kk => $vv){
        // $sheet->setHeight(($z-1),100);

        $sheet->setCellValue('A'.$z, ($k+1));
        if($kk==0){
            if(count($pica[$k])<=1){
                // $sheet->setCellValue('B'.$z, $v->namaForm);
            }else{
                // $sheet->setCellValue('B'.$z, $v->namaForm);

                $sheet->mergeCells('A'.$z.':A'.($z+count($pica[$k])-1));
                $sheet->mergeCells('B'.$z.':B'.($z+count($pica[$k])-1));
                $sheet->mergeCells('C'.$z.':C'.($z+count($pica[$k])-1));
                $sheet->mergeCells('D'.$z.':D'.($z+count($pica[$k])-1));
                $sheet->mergeCells('E'.$z.':E'.($z+count($pica[$k])-1));
                $sheet->mergeCells('F'.$z.':F'.($z+count($pica[$k])-1));
                $sheet->mergeCells('G'.$z.':G'.($z+count($pica[$k])-1));
            }
        }
        // else{
        $sheet->setCellValue('B'.$z, $v->namaForm);
        // }
        $sheet->setCellValue('C'.$z, date("d F Y",strtotime($v->tgl_inspeksi)));
        $sheet->setCellValue('D'.$z, $v->nama_perusahaan);
        $sheet->setCellValue('E'.$z, $v->lokasiInspeksi);
        $sheet->setCellValue('F'.$z, $v->saran);


        $teamInspeksi = DB::table("hse.team_inspeksi as aa")
                          ->leftJoin('user_login as bb',"bb.nik","aa.nikTeam")
                          ->where("aa.idInspeksi",$v->idInspeksi)
                          ->get();
          if(count($teamInspeksi)>0){
            foreach($teamInspeksi as $kTeam => $vTeam){
                $teamInspeksiAll[$k][] = $vTeam->nikTeam." | ".$vTeam->nama_lengkap;
            }
            $sheet->setCellValue('G'.$z, implode("\n",$teamInspeksiAll[$k]));

            }else{
            $sheet->setCellValue('G'.$z, "-");
            }
            $drawing[$k]=new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing[$k]->setWorksheet($sheet);
            $drawing[$k]->setPath($path.$vv->picaSebelum);
            $drawing[$k]->setCoordinates('H'.$z);
            $drawing[$k]->setHeight(70);
            $drawing[$k]->getShadow()->setVisible(true);
            $drawing[$k]->getShadow()->setDirection(70);
            $drawing[$k]->setOffsetX(30);
            $drawing[$k]->setOffsetY(6);
        $sheet->setCellValue('I'.$z, $vv->picaTemuan);
        $sheet->setCellValue('J'.$z, $vv->picaTindakan);
        $sheet->setCellValue('K'.$z, $vv->picaNikPJ);
        $sheet->setCellValue('L'.$z, $vv->picaNamaPJ);
        $sheet->setCellValue('M'.$z, date("d F Y",strtotime($vv->picaTenggat)));
        $sheet->setCellValue('N'.$z, "-");
        $sheet->setCellValue('O'.$z, $vv->picaPerbaikan);
        if($vv->tgl_selesai!=null){
        $sheet->setCellValue('P'.$z, date("d F Y",strtotime($vv->tgl_selesai)));
        }else{
        $sheet->setCellValue('P'.$z, "-");
            }
        // $sheet->setCellValue('Q'.$z, "-");
        $sheet->getStyle("Q".$z)->getFont()->getColor()->setARGB("FFFFFF");

        if($vv->status=="SELESAI"){
            $sheet
                ->getStyle("Q".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('4CAF50');
            $sheet->setCellValue('Q'.$z, $vv->status);
        }elseif($vv->status=="BELUM SELESAI"){
            $sheet
                ->getStyle("Q".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('F44336');
            $sheet->setCellValue('Q'.$z, $vv->status);
        }elseif($vv->status=="BERLANJUT"){
            $sheet
                ->getStyle("Q".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('2196F3');
            $sheet->setCellValue('Q'.$z, $vv->status);
        }elseif($vv->status=="DALAM PENGERJAAN"){
            $sheet
                ->getStyle("Q".$z)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('2196F3');
            $sheet->setCellValue('Q'.$z, $vv->status);
            }

        $sheet->setCellValue('R'.$z, "-");
        $sheet->getRowDimension($z)->setRowHeight(80);
        $sheet->getStyle('G:G')
                ->getAlignment()
                ->setHorizontal('left')
                ->setVertical('center');
        $z++;
            }
            }else{
                $sheet->setCellValue('A'.$z, ($z-1));
                $sheet->setCellValue('B'.$z, $v->namaForm);
                $sheet->setCellValue('C'.$z, date("d F Y",strtotime($v->tgl_inspeksi)));
                $sheet->setCellValue('D'.$z, $v->nama_perusahaan);
                $sheet->setCellValue('E'.$z, $v->lokasiInspeksi);
                $sheet->setCellValue('F'.$z, $v->saran);
                $teamInspeksi = DB::table("hse.team_inspeksi as aa")
                          ->leftJoin('user_login as bb',"bb.nik","aa.nikTeam")
                          ->where("aa.idInspeksi",$v->idInspeksi)
                          ->get();
          if(count($teamInspeksi)>0){
            foreach($teamInspeksi as $kTeam => $vTeam){
                $teamInspeksiAll[$k][] = $vTeam->nikTeam." | ".$vTeam->nama_lengkap;
            }
            $sheet->setCellValue('G'.$z, implode("\n",$teamInspeksiAll[$k]));

            }else{
            $sheet->setCellValue('G'.$z, "-");
        }
        $sheet->setCellValue('H'.$z, "Tidak Ada Temuan");

        // $sheet->setCellValue('H'.$z, "-");
        $sheet->setCellValue('I'.$z, "-");
        $sheet->setCellValue('J'.$z, "-");
        $sheet->setCellValue('K'.$z, "-");
        $sheet->setCellValue('L'.$z, "-");
        $sheet->setCellValue('M'.$z, "-");
        $sheet->setCellValue('N'.$z, "-");
        $sheet->setCellValue('O'.$z, "-");
        $sheet->setCellValue('P'.$z, "-");
        $sheet->setCellValue('Q'.$z, "-");
        $sheet->setCellValue('R'.$z, "-");
        $sheet->getStyle('G:G')
                ->getAlignment()
                ->setHorizontal('left')
                ->setVertical('center');
                $sheet->getRowDimension($z)->setRowHeight(80);

                $z++;
            }
        }
        $writer = new Xlsx($spreadsheet);

        $writer->save($filename);

        $logExport = DB::table('export_log')
                    ->insert([
                        "user_export"=>$_SESSION['username'],
                        "desc"      =>"All Inspection Report",
                        "date"      => date("Y-m-d H:i:s")
                    ]);

        if($logExport){
            return redirect($filename);
        }
    }
    public function inspeksiReportVerifikasi(Request $request)
    {
    	# code...
    }
    public function inspeksiForm(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $formInspeksi = DB::table("hse.form_inspeksi")->paginate(10);
        $editFormInspeksi = DB::table("hse.form_inspeksi")->where("idForm",hex2bin($request->uid))
                ->first();
        return view('hse.inspeksi_form',["formInspeksi"=>$formInspeksi,"editFormInspeksi"=>$editFormInspeksi,"getUser"=>$this->user]);
    }

    public function inspeksiFormPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $FormInspeksi = DB::table("hse.form_inspeksi")
                ->insert([
                    "kodeForm"=>$request->kodeForm,
                    "namaForm"=>$request->namaForm,
                    "userEntry"=>$_SESSION['username'],
                    "tglEntry"=>date("Y-m-d")
                ]);
        if($FormInspeksi){
        return redirect()->back()->with("success","Form Inspeksi Berhasil Ditambahkan!");
        }else{
        return redirect()->back()->with("failed","Form Inspeksi Gagal Ditambah!");
        }
    }

    public function inspeksiFormPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $FormInspeksi = DB::table("hse.form_inspeksi")->where("idForm",hex2bin($request->uid))
                ->update([
                    "kodeForm"=>$request->kodeForm,
                    "namaForm"=>$request->namaForm
                ]);
        if($FormInspeksi>=0){
        return redirect()->back()->with("success","Form Inspeksi Berhasil Diperbaharui!");
        }else{
        return redirect()->back()->with("failed","Form Inspeksi Gagal Diperbaharui!");
        }
    }
    public function inspeksiFormFlag(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $FormInspeksi = DB::table("hse.form_inspeksi")->where("idForm",hex2bin($request->uid));

        if($request->action=="enable"){
            $update = $FormInspeksi->update([
                    "flag"=>0
                ]);
            $result = "Diaktifkan";
        }elseif($request->action=="disable"){
                $update = $FormInspeksi->update([
                    "flag"=>1,
                ]);
            $result = "Tidak Aktif";

        }
        if($update>=0){
        return redirect()->back()->with("success","Form Inspeksi Berhasil ".$result."!");
        }else{
        return redirect()->back()->with("failed","Form Inspeksi Gagal ".$result."!");
        }
    }
    public function inspeksiFormCreate(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $createForm = DB::table("hse.form_inspeksi")->where("idForm",hex2bin($request->uid))
                ->first();
        $subData = DB::table("hse.form_inspeksi_sub")->where("idForm",hex2bin($request->uid))->get();
        $db = DB::table("hse.form_inspeksi_sub")->where("idForm",hex2bin($request->uid))->get();

        $inspeksiListEdit = DB::table("hse.form_inspeksi_list")->where("idList",hex2bin($request->idList))->first();
        return view('hse.form.inspeksi_form_new',["createForm"=>$createForm,"dataSub"=>$db,"subData"=>$subData,"inspeksiListEdit"=>$inspeksiListEdit, "getUser"=>$this->user]);
    }
    public function inspeksiFormCreatePost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $insert = DB::table("hse.form_isnpeksi_field")
                    ->insert([
                        "idForm"=>hex2bin($request->uid),
                        "nameField"=>$request->nameField,
                        "tipeField"=>$request->tipeField,
                        "tagField"=>$request->tagField,
                        "descField"=>$request->descField,
                        "valueField"=>$request->valueField,
                        "sizeField"=>$request->sizeField,
                        "sizeLabel"=>$request->sizeLabel,
                        "textLabel"=>$request->textLabel
                    ]);
        if($insert){
        return redirect()->back()->with("success","Field Berhasil Ditambahkan!");
        }else{
        return redirect()->back()->with("failed","Field Gagal Ditambah!");
        }
    }
    public function inspeksiFormCreateSub(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $createForm = DB::table("hse.form_inspeksi")->where("idForm",hex2bin($request->uid))
                ->first();
        $db = DB::table("hse.form_inspeksi_sub")->where("idForm",hex2bin($request->uid))->get();
        $inspeksiFieldEdit = DB::table("hse.form_inspeksi_sub")->where("idSub",hex2bin($request->idSub))->first();
        return view('hse.form.inspeksi_new_sub',["createForm"=>$createForm,"dataSub"=>$db,"inspeksiFieldEdit"=>$inspeksiFieldEdit, "getUser"=>$this->user]);
    }
    public function inspeksiFormCreateSubPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        // dd($request);
        $in = DB::table("hse.form_inspeksi_sub")
                ->insert([
                    "idForm"=>$request->idForm,
                    "numSub"=>$request->numSub,
                    "nameSub"=>$request->nameSub,
                    "user_input"=>$_SESSION['username'],
                    "tgl_input"=>date("Y-m-d"),
                ]);
        if($in){
            return redirect()->back()->with("success","Berhasil Membuat Sub!");
        }else{
            return redirect()->back()->with("failed","Gagal Membuat Sub!");
        }

    }
    public function inspeksiFormNewPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $in = DB::table("hse.form_inspeksi_list")
                ->insert([
                    "idForm"=>hex2bin($request->uid),
                    "idSub"=>$request->idSub,
                    "listInspeksi"=>$request->listInspeksi,
                    "user_input"=>$_SESSION['username'],
                    "tgl_input"=>date("Y-m-d")
                ]);
        if($in){
            return redirect()->back()->with("success","Berhasil Membuat List Inspeksi!");
        }else{
            return redirect()->back()->with("failed","Gagal Membuat List Inspeksi!");
        }
    }
    public function inspeksiFormNewPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $in = DB::table("hse.form_inspeksi_list")
                ->where("idList",hex2bin($request->idListPut))
                ->update([
                    "idSub"=>$request->idSub,
                    "listInspeksi"=>$request->listInspeksi,
                    "user_input"=>$_SESSION['username'],
                    "tgl_input"=>date("Y-m-d")
                ]);
        if($in>=0){
            return redirect('/hse/admin/inspeksi/form/create?uid='.$request->uid)->with("success","Berhasil Mengupdate List Inspeksi!");
        }else{
            return redirect()->back()->with("failed","Gagal Mengupdate List Inspeksi!");
        }
    }

    public function inspeksiFormCreateSubPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $in = DB::table("hse.form_inspeksi_sub")
                ->where("idSub",hex2bin($request->idSub_Form))
                ->update([
                    "idForm"=>hex2bin($request->uid),
                    "numSub"=>$request->numSub,
                    "nameSub"=>$request->nameSub
                ]);
        if($in){
            return redirect()->back()->with("success","Berhasil Mengupdate Sub!");
        }else{
            return redirect()->back()->with("failed","Gagal Mengupdate Sub!");
        }

    }
    public function androidInspeksiForm(Request $request)
    {
        $androidInspeksiForm = DB::table("hse.form_inspeksi")->paginate(10);
        return $androidInspeksiForm;
    }
    public function androidInspeksiList(Request $request)
    {
        $androidInspeksi = DB::table("hse.form_inspeksi_header as a")
                            ->leftJoin("hse.form_inspeksi_validasi as b","b.idInspeksi","a.idInspeksi")
                            ->leftJoin("hse.form_inspeksi_pika as c","c.idInspeksi","a.idInspeksi")
                            ->paginate(10);
        return $androidInspeksi;
    }
    public function androidInspeksiNew(Request $request)
    {
        $androidSubInspeksi = DB::table("hse.form_inspeksi_sub as a")->where("idForm",$request->idForm)->get();

        if(count($androidSubInspeksi)>0){
            foreach ($androidSubInspeksi as $key => $value) {
             $itemInspeksi[] = ["nameSub"=>$value->nameSub,"numSub"=>$value->numSub,"items"=>DB::table("hse.form_inspeksi_list")->where("idSub",$value->idSub)->get()];
            }
        }else{
            $itemInspeksi[] = ["nameSub"=>null,"numSub"=>null,"items"=>DB::table("hse.form_inspeksi_list")->where("idForm",$request->idForm)->get()];
        }
        return ["itemInspeksi"=>$itemInspeksi];
    }
    public function androidInspeksiItemTemp(Request $request)
    {
        $check = DB::table("hse.form_inspeksi_temp")->where([
                        ["idTemp",$request->idTemp],
                        ["idItem",$request->idItem]
                    ])->count();
        if($check>0){
        $temp = DB::table("hse.form_inspeksi_temp")
                ->where([
                        ["idTemp",$request->idTemp],
                        ["idItem",$request->idItem]
                    ])
                ->update([
                    "answer"=>$request->answer
                ]);
        }else{
        $temp = DB::table("hse.form_inspeksi_temp")
                ->insert([
                    "idTemp"=>$request->idTemp,
                    "tglTemp"=>date("Y-m-d"),
                    "idForm"=>$request->idForm,
                    "idItem"=>$request->idItem,
                    "answer"=>$request->answer,
                    "user_create"=>$request->user_create
                ]);
        }

        if($temp>=0){
            return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function androidInspeksiListTeamTemp(Request $request){
        $check = DB::table("hse.team_inspeksi_temp as a")
        ->join("user_login as b","b.nik","a.nikTeam")
        ->join("section as c","c.id_sect","b.section")
        ->join("department as d","d.id_dept","b.department")
        ->where("a.idTemp",$request->idTemp)
        ->groupBy("b.nik")
        ->get();
        return ["teamInspeksiTemp"=>$check];
    }

    public function androidInspeksiPicaTemp(Request $request){
        $pica = DB::table("hse.pica_temp")
        ->where("idTemp",$request->idTemp)
        ->get();
        return ["inspeksiPica"=>$pica];
    }
    public function androidInspeksiAddPicaTemp(Request $request)
    {
        $buktiTemuan = $request->file("buktiTemuan");
        $dir = "bukti_inspeksi/sebelum/";
        $fileName = $buktiTemuan->getClientOriginalName();
        if($buktiTemuan->move($dir,$fileName)){
        $temp = DB::table("hse.pica_temp")
        ->insert([
            "idTemp"=>$request->idTemp,
            "idForm"=>$request->idForm,
            "temuan"=>$request->temuan,
            "buktiTemuan"=>$fileName,
            "nikPJ"=>$request->nikPJ,
            "namaPJ"=>$request->namaPJ,
            "status"=>$request->status,
            "tglTenggat"=>date("Y-m-d",strtotime($request->tglTenggat))
        ]);
        if($temp>0){
            return array("success"=>true);
        }else{
            return array("success"=>false);
        }
        }else{
            return array("success"=>false);
        }
    }
    public function androidInspeksiAddTeamTemp(Request $request)
    {
        $check = DB::table("hse.team_inspeksi_temp")->where([
                        ["idTemp",$request->idTemp],
                        ["nikTeam",$request->nikTeam]
                    ])->count();
        if($check>0){
        $temp = DB::table("hse.team_inspeksi_temp")
                ->where([
                        ["idTemp",$request->idTemp],
                        ["nikTeam",$request->nikTeam]
                    ])
                ->update([
                    "nikTeam"=>$request->nikTeam
                ]);
        }else{
        $temp = DB::table("hse.team_inspeksi_temp")
                ->insert([
                    "idTemp"=>$request->idTemp,
                    "idForm"=>$request->idForm,
                    "nikTeam"=>$request->nikTeam
                ]);
        }

        if($temp){
            return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function androidInspeksiDeleteTemp(Request $request)
    {
        $items = DB::table("hse.form_inspeksi_temp")
                ->where("idTemp",$request->idTemp)->delete();
        $team = DB::table("hse.team_inspeksi_temp")
                ->where("idTemp",$request->idTemp)->delete();
        $pica = DB::table("hse.pica_temp")
                ->where("idTemp",$request->idTemp)->delete();

        if($items || $team || $pica){
            return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }
    public function androidInspeksiItems(Request $request)
        {
            $androidItemsInspeksi = DB::table("hse.form_inspeksi_list as a")->where("idSub",$request->idSub)->get();
            return array("dataItems"=>$androidItemsInspeksi);
        }
    // LOKASI
    public function hseMasterLokasi(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
        $lokasi = DB::table("hse.lokasi")
                    ->paginate(10);
        return view('hse.lokasi',["lokasi"=>$lokasi,"getUser"=>$this->user]);
    }

    public function hseMasterLokasiNew(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
          $lokasi = DB::table("hse.lokasi")
                    ->where("idLok",hex2bin($request->uid))
                    ->first();
        return view('hse.form.lokasi',["editLokasi"=>$lokasi,"getUser"=>$this->user]);
    }
    public function hseMasterLokasiPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $lokasi = DB::table("hse.lokasi")
                    ->insert([
                        "lokasi"=>$request->lokasi,
                        "des_lokasi"=>$request->des_lokasi,
                        "user_input"=>$_SESSION['username'],
                        "tgl_input"=>date("Y-m-d")]);
        if($lokasi){
            return redirect()->back()->with("success","Lokasi Berhasil Ditambahkan!");
        }else{
            return redirect()->back()->with("failed","Lokasi Gagal Ditambah!");
        }
    }
    public function hseMasterLokasiPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $lokasi = DB::table("hse.lokasi")
                    ->where("idLok",$request->uidMaster)
                    ->update([
                        "lokasi"=>$request->lokasi,
                        "des_lokasi"=>$request->des_lokasi,
                        "user_input"=>$_SESSION['username'],
                        "tgl_input"=>date("Y-m-d")]);
        if($lokasi>=0){
            return redirect()->back()->with("success","Lokasi Berhasil Diperbaharui!");
        }else{
            return redirect()->back()->with("failed","Lokasi Gagal Diperbaharui!");
        }
    }
    // RISK
    public function hseMasterRisk(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.risk_category")
                    ->paginate(10);
          $editRisk = DB::table("hse.risk_category")
                    ->where("idRisk",hex2bin($request->uid))
                    ->first();
        return view('hse.risk',["editRisk"=>$editRisk,"risk"=>$risk,"getUser"=>$this->user]);
    }

    public function hseMasterRiskUbah(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.risk_category")
                    ->where("idRisk",hex2bin($request->uid))
                    ->update([
                        "desc_risk"=>$request->desc_risk,
                        "bgColor"=>$request->bgColor,
                        "txtColor"=>$request->txtColor,
                        "user_input"=>$_SESSION['username'],
                        "tgl_input"=>date("Y-m-d")
                    ]);
        if($risk>=0){
            return redirect()->back()->with("success","Risk Berhasil Diperbaharui!");
        }else{
            return redirect()->back()->with("failed","Risk Gagal Diperbaharui!");
        }
    }

    public function hseMasterRiskPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.risk_category")
                    ->insert([
                        "risk"=>$request->risk,
                        "desc_risk"=>$request->desc_risk,
                        "bgColor"=>$request->bgColor,
                        "txtColor"=>$request->txtColor,
                        "user_input"=>$_SESSION['username'],
                        "tgl_input"=>date("Y-m-d")
                    ]);
        if($risk>=0){
            return redirect()->back()->with("success","Risk Berhasil Ditambah!");
        }else{
            return redirect()->back()->with("failed","Risk Gagal Ditambah!");
        }
    }
    // SUMBER BAHAYA
    public function hseMasterSumberBahaya(Request $request)
    {

        if(!isset($_SESSION['username'])) return redirect('/');
          $sumber_bahaya = DB::table("hse.sumber_bahaya")
                    ->paginate(10);
          $editBahaya = DB::table("hse.sumber_bahaya")
                    ->where("idBahaya",hex2bin($request->uid))
                    ->first();
        return view('hse.sumber_bahaya',["editBahaya"=>$editBahaya,"sumber_bahaya"=>$sumber_bahaya,"getUser"=>$this->user]);
    }
    public function hseMasterSumberBahayaPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.sumber_bahaya")
                    ->insert([
                        "bahaya"=>$request->bahaya,
                        "user_input"=>$_SESSION['username'],
                        "time_input"=>date("Y-m-d H:i:s")
                    ]);
        if($risk){
            return redirect()->back()->with("success","Risk Berhasil Ditambah!");
        }else{
            return redirect()->back()->with("failed","Risk Gagal Ditambah!");
        }
    }
    public function hseMasterSumberBahayaPUT(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.sumber_bahaya")
                    ->where("idBahaya",hex2bin($request->uid))
                    ->update([
                        "bahaya"=>$request->bahaya,
                        "user_input"=>$_SESSION['username'],
                        "time_input"=>date("Y-m-d H:i:s")
                    ]);
        if($risk>=0){
            return redirect()->back()->with("success","Risk Berhasil Diperbaharui!");
        }else{
            return redirect()->back()->with("failed","Risk Gagal Diperbaharui!");
        }
    }
    // hasilMatrikResiko
    public function hasilMatrikResiko(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $ketResiko = DB::table("hse.metrik_resiko")
                    ->paginate(10);
          $editKetResiko = DB::table("hse.metrik_resiko")
                    ->where("idResiko",hex2bin($request->uid))
                    ->first();
        return view('hse.hasilResiko',["editKetResiko"=>$editKetResiko,"ketResiko"=>$ketResiko,"getUser"=>$this->user]);
    }
    public function hasilMatrikResikoPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.metrik_resiko")
                    ->insert([
                        "kodeBahaya"=>$request->kodeBahaya,
                        "min"=>$request->min,
                        "max"=>$request->max,
                        "kategori"=>$request->kategori,
                        "tindakan"=>$request->tindakan,
                        "bgColor"=>$request->bgColor,
                        "txtColor"=>$request->txtColor
                    ]);
        if($risk){
            return redirect()->back()->with("success","Matrik Resiko Berhasil Ditambah!");
        }else{
            return redirect()->back()->with("failed","Matrik Resiko Gagal Ditambah!");
        }
    }
    public function hasilMatrikResikoPut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $risk = DB::table("hse.metrik_resiko")
                    ->where("idResiko",hex2bin($request->uid))
                    ->update([
                        "kodeBahaya"=>$request->kodeBahaya,
                        "min"=>$request->min,
                        "max"=>$request->max,
                        "kategori"=>$request->kategori,
                        "tindakan"=>$request->tindakan,
                        "bgColor"=>$request->bgColor,
                        "txtColor"=>$request->txtColor
                    ]);
        if($risk>=0){
            return redirect()->back()->with("success","Matrik Resiko Berhasil Diperbaharui!");
        }else{
            return redirect()->back()->with("failed","Matrik Resiko Gagal Diperbaharui!");
        }
    }
    public function kemungkinanMatrikResiko(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $kmResiko = DB::table("hse.metrik_resiko_kemungkinan")
                    ->paginate(10);
          $editKmResiko = DB::table("hse.metrik_resiko_kemungkinan")
                    ->where("idKemungkinan",hex2bin($request->uid))
                    ->first();
        return view('hse.kemungkinanResiko',["editKmResiko"=>$editKmResiko,"kmResiko"=>$kmResiko,"getUser"=>$this->user]);
    }
    public function kemungkinanMatrikResikoPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $kmResiko = DB::table("hse.metrik_resiko_kemungkinan")
                    ->insert([
                        "kemungkinan"=>$request->kemungkinan,
                        "nilai"=>$request->nilai
                    ]);
        if($kmResiko){
            return redirect()->back()->with("success","Matrik Resiko Berhasil Ditambah!");
        }else{
            return redirect()->back()->with("failed","Matrik Resiko Gagal Ditambah!");
        }
    }
    public function kemungkinanMatrikResikoPut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $kmResiko = DB::table("hse.metrik_resiko_kemungkinan")
                    ->where("idKemungkinan",hex2bin($request->uid))
                    ->update([
                        "kemungkinan"=>$request->kemungkinan,
                        "nilai"=>$request->nilai
                    ]);
        if($kmResiko>=0){
            return redirect('/hse/admin/matrik/kemungkinan')->with("success","Matrik Resiko Berhasil Diperbaharui!");
        }else{
            return redirect()->back()->with("failed","Matrik Resiko Gagal Diperbaharui!");
        }
    }

    public function keparahanMatrikResiko(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $kpResiko = DB::table("hse.metrik_resiko_keparahan")
                    ->paginate(10);
          $editKpResiko = DB::table("hse.metrik_resiko_keparahan")
                    ->where("idKeparahan",hex2bin($request->uid))
                    ->first();
        return view('hse.keparahanResiko',["editKpResiko"=>$editKpResiko,"kpResiko"=>$kpResiko,"getUser"=>$this->user]);
    }
    public function keparahanMatrikResikoPost(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $keparahan = htmlspecialchars($request->keparahan,ENT_QUOTES);
          $kpResiko = DB::table("hse.metrik_resiko_keparahan")
                    ->insert([
                        "keparahan"=>$request->keparahan,
                        "nilai"=>$request->nilai
                    ]);
        if($kpResiko){
            return redirect()->back()->with("success","Matrik Resiko Berhasil Ditambah!");
        }else{
            return redirect()->back()->with("failed","Matrik Resiko Gagal Ditambah!");
        }
    }
    public function keparahanMatrikResikoPut(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
        $keparahan = htmlspecialchars($request->keparahan,ENT_QUOTES);
          $kpResiko = DB::table("hse.metrik_resiko_keparahan")
                    ->where("idKeparahan",hex2bin($request->uid))
                    ->update([
                        "keparahan"=>$keparahan,
                        "nilai"=>$request->nilai
                    ]);
        if($kpResiko>=0){
            return redirect('/hse/admin/matrik/keparahan')->with("success","Matrik Resiko Berhasil Diperbaharui!");
        }else{
            return redirect()->back()->with("failed","Matrik Resiko Gagal Diperbaharui!");
        }
    }

    public function tbMatrikResiko(Request $request)
    {
        if(!isset($_SESSION['username'])) return redirect('/');
          $hsResiko = DB::table("hse.metrik_resiko");
          $kmResiko = DB::table("hse.metrik_resiko_kemungkinan")
                    ->get();
          $kpResiko = DB::table("hse.metrik_resiko_keparahan")
                    ->get();
        return view('hse.tableMatrikResiko',["hsResiko"=>$hsResiko,"kmResiko"=>$kmResiko,"kpResiko"=>$kpResiko,"getUser"=>$this->user]);
    }
    public function tbMatrikResikoWebView(Request $request)
    {
          $hsResiko = DB::table("hse.metrik_resiko");
          $kmResiko = DB::table("hse.metrik_resiko_kemungkinan")
                    ->get();
          $kpResiko = DB::table("hse.metrik_resiko_keparahan")
                    ->get();
        return view('hse.tableMatrikResikoView',["hsResiko"=>$hsResiko,"kmResiko"=>$kmResiko,"kpResiko"=>$kpResiko]);
    }

    // hasilMatrikResiko
    // resikoKemungkinan
    public function resikoKemungkinan(Request $request)
    {
       $kemungkinan = DB::table("hse.metrik_resiko_kemungkinan")->orderBy("idKemungkinan","asc")->get();
       return ["kemungkinan"=>$kemungkinan];
    }
    // resikoKemungkinan
    // resikoKeparahan
    public function resikoKeparahan(Request $request)
    {
       $keparahan = DB::table("hse.metrik_resiko_keparahan")->orderBy("nilai","asc")->get();
       return ["keparahan"=>$keparahan];
    }
    public function resikoKeparahanFull(Request $request)
    {
       $keparahan = DB::table("hse.metrik_resiko_keparahan")->orderBy("nilai","asc")->get();
       return ["keparahan"=>$keparahan];
    }
    public function resikoKeparahanDet(Request $request)
    {
       $det_keparahan = DB::table("hse.det_keparahan")->get();
       return ["det_keparahan"=>$det_keparahan];
    }

    // resikoKeparahan
    // hirarkiPengendalian
    public function hirarkiPengendalian(Request $request)
    {
       $hirarki = DB::table("hse.hirarki_pengendalian")->get();
       return ["hirarki"=>$hirarki];
    }
    public function detailPengendalian(Request $request)
    {
       $detHirarki = DB::table("hse.ket_pengendalian")->get();
       return ["detHirarki"=>$detHirarki];
    }
    // hirarkiPengendalian

    public function infoMessage(Request $request)
    {
        $info = DB::table("hse.message_info")->get();
        return ["message_info"=>$info];
    }
}
