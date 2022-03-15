<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Surat Keluar | Sarana Prasarana</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
.top_header{
	text-align: center;
	padding-left: 120px;
	height: 90px;
}
.top_header img{
	position: absolute;
	top: 10;
	left: 25px;
}
	table, td, th {    
    border: none;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 100%;
}
.no_rkb{
	background-color: #333;
	color: #f8f8f8;
}
.header th{
	background-color: rgba(0,0,0,0.1);
	color: #333;
}
th, td {
    padding: 10px;
}
</style>

</head>
<body>
Dengan Hormat, <br><br>
&nbsp;&nbsp;&nbsp;
Surat Keluar Sarana
<br>
<br>
<table>
	<thead>
		<tr>
			<th colspan="4" class="no_rkb">
			No Keluar : {{$sarpras->nomor}}
		</th>
		</tr>
		<tr>
			<th width="110">Keperluan  </th>
			<th width="5"> : </th>
			<th colspan="">{{$sarpras->keperluan}}  </th>
		</tr>
		<tr>
			<th colspan="">Pemohon  </th>
			<th colspan="">: </th>
			<th colspan=""> {{ucwords($sarpras->nama_p)}} </th>
		</tr>
		<tr>
			@if($sarpras->no_lv=="motor" || $sarpras->no_lv=="mobil")
			<th colspan="">Jenis Kendaraan</th>
			<th colspan=""> : </th>
			<th colspan=""> {{ucwords($sarpras->no_lv)}} @if($sarpras->no_pol) ({{ucwords($sarpras->no_pol)}}) @endif</th>
			@else
			<th colspan="">No LV</th>
			<th colspan=""> : </th>
			<th colspan=""> {{$sarpras->no_lv}}</th>
			@endif
			
		</tr>
		<tr class="">
			@if($sarpras->no_lv=="motor" || $sarpras->no_lv=="mobil")
			<th>Merk Kendaraan</th>
			<th colspan=""> : </th>
			<th colspan="">{{ucwords($sarpras->driver)}}</th>
			@else
			<th>Driver</th>
			<th colspan=""> : </th>
			<th colspan="">{{ucwords($sarpras->nama_d)}}</th>
			@endif
		</tr>
		<tr class="">
			<th>Tanggal Keluar</th>
			<th colspan=""> : </th>
			<th colspan="">{{date("d F Y",strtotime($sarpras->tgl_out))}}</th>
		</tr>
		<tr class="">
			<th>Jam Keluar</th>
			<th colspan=""> : </th>
			<th colspan="">{{date("H:i:s",strtotime($sarpras->jam_out))}}</th>
		</tr>
		<tr class="">
			<th>Tanggal Kembali</th>
			<th colspan=""> : </th>
			<th colspan="">@if(isset($sarpras->tgl_in)){{date("d F Y",strtotime($sarpras->tgl_in))}}@else - @endif</th>
		</tr>
		<tr class="">
			<th>Jam Kembali</th>
			<th colspan=""> : </th>
			<th colspan="">@if(isset($sarpras->jam_in)){{date("H:i:s",strtotime($sarpras->jam_in))}}@else - @endif</th>
		</tr>
		<tr class="">
			<th>Penumpang</th>
			<th colspan=""> : </th>
			<th colspan="">
				@php
				$penum = explode(",",$sarpras->penumpang_out);
				foreach($penum as $k => $v){
				$r = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")->where("nik",$v)->first();
				@endphp
				@if(count($penum)>1)
				{{ucwords($r->nama)." , "}}
				@else
				{{ucwords($r->nama)}}
				@endif
				@php } @endphp
			</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<br><br>
Silahkan <a href="{{$urlPDF}}">Klik Disini</a> atau <a href="{{$urlPDF}}">{{$urlPDF}}</a> untuk melihat dokumen yang sudah di approve
<br>
<br>
--<br>
<font color="blue"><b>Automatic send by sistem.</b></font>
<br>
By System Abp~System<br>
<a href="{{url('/')}}" style="text-decoration: none;color: #000;font-weight: bolder;">Abpjobsite.com</a><br>
</body>
</html>