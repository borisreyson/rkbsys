<?php
	require_once 'Classes/PHPExcel.php';

$excel = new PHPExcel;
 
$excel->getProperties()->setCreator("Bimosaurus");
$excel->getProperties()->setLastModifiedBy("Bimosaurus");
$excel->getProperties()->setTitle("Coba");
$excel->removeSheetByIndex(0);
 
 
$sheet = $excel->createSheet();
$sheet->setTitle('sheet_1');
 $sheet->setCellValue("A1", "Kolom Satu");
 $sheet->setCellValue("B1", "Kolom Dua");
 $sheet->setCellValue("C1", "Kolom Tiga");
 $sheet->setCellValue("D1", "Kolom Empat");
 $sheet->setCellValue("E1", "Kolom Lima");
 $sheet->setCellValue("F1", "Kolom Enam");
 
 $sheet->setCellValue("A2", "Isi Satu");
 $sheet->setCellValue("B2", "Isi Dua");  
 $sheet->setCellValue("C2", "Isi Tiga");
 $sheet->setCellValue("D2", "Isi Empat"); 
 $sheet->setCellValue("E2", "Isi Lima"); 
 $sheet->setCellValue("F2", "Isi Enam");
 
 //$writer = new PHPExcel_Writer_Excel2007($excel);
 //$writer->save("excel.xlsx");
 return redirect('/excel.xlsx');
?>