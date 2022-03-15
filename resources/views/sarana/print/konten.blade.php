<!DOCTYPE html>
<html>
<head>
	<title>Surat Keluar Sarana</title>
	<style>
		.pull-right{
			position: absolute;
			top: 20;
			right: 0;
			line-height: line-height: 10px	!important;
			font-size: 12px;
			width: 175px;
		}
		table tbody tr,table tbody tr td{
		line-height: line-height: 3px!important;
		padding: 5px;
		font-size: 12px;
		}
	</style>
</head>
<body>
	<div style="width: 100%!important;text-align: center;padding-top: 0!important;margin-top: 0!important;text-decoration: underline;">
		<h3>Surat Keluar Sarana</h3>
	</div>

	<div class="pull-right" style="font-weight: bolder;">Tanggal , {{date("d F Y",strtotime($konten->tgl_out))}}</div>
<table style="border:none!important;">
	<tr>
		<td>
			<b>No</b>
		</td>
		<td>
			<b>:</b>
		</td>
		<td>
			{{$konten->nomor}}
		</td>
	</tr>
	<tr>
		<td>Keperluan</td>
		<td>:</td>
		<td>{{ucwords(strtolower($konten->keperluan))}}</td>
	</tr>
	<tr>
		<td>Pemohon</td>
		<td>:</td>
		<td>{{ucwords(strtolower($konten->nama_p))}}</td>
	</tr>
	<tr>
		@if($konten->no_lv=="motor" || $konten->no_lv=="mobil")
		<td>Jenis Kendaraan</td>
		<td>:</td>
		<td>{{ucwords($konten->no_lv)}} @if($konten->no_pol!=null) {{$konten->no_pol}} @endif</td>
		@else
		<td>No LV</td>
		<td>:</td>
		<td>{{$konten->no_lv}}</td>
		@endif
	</tr>
	@if($konten->no_lv=="motor" || $konten->no_lv=="mobil")
	<tr>
		<td>Merk Kendaraan</td>
		<td>:</td>
		<td>{{ucwords(($konten->driver))}}</td>
	</tr>
	@else
	<tr>
		<td>Driver</td>
		<td>:</td>
		<td>{{ucwords(strtolower($konten->nama_d))}}</td>
	</tr>
	@endif
	<tr>
		<td width="100px">Tanggal Keluar</td>
		<td>:</td>
		<td>{{date("d F Y",strtotime($konten->tgl_out))}}</td>
	</tr>
	<tr>
		<td>Jam Keluar</td>
		<td>:</td>
		<td>{{date("H:i:s",strtotime($konten->jam_out))}}</td>
	</tr>
	<tr>
		<td>Tanggal Kembali</td>
		<td>:</td>
		<td>@if(isset($konten->tgl_in)){{date("d F Y",strtotime($konten->tgl_in))}}@else - @endif</td>
	</tr>
	<tr>
		<td>Jam Kembali</td>
		<td>:</td>
		<td>@if(isset($konten->jam_in)){{date("H:i:s",strtotime($konten->jam_in))}}@else - @endif</td>
	</tr>
	<tr>
		<td>Penumpang</td>
		<td>:</td>
		<td>
			@php
			$penum = explode(",",$konten->penumpang_out);
			@endphp
			@foreach($penum as $kk => $vv)
			@php
			$n_penum = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")->where("nik",$vv)->first();
			@endphp
			{{ucwords(strtolower($n_penum->nama))}},
			@endforeach
		</td>
	</tr>
</table>
</body>
</html>