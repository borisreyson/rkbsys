<?php
  function tgl_indo($tanggal)
  {
      $bulan = array (1 =>   'Januari',
          'Februari',
          'Maret',
          'April',
          'Mei',
          'Juni',
          'Juli',
          'Agustus',
          'September',
          'Oktober',
          'November',
          'Desember'
        );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
  }

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
<style>
	html,body{
		background-color: #fff;
		color:#000;
		margin: 0px;
		padding: 0px;
		line-height: line-height: 2	!important;
		font-size:10px;
	}
	.cover{
		height: 20px!important;
	}
	.logo_right{
		position: absolute;
		top: 10px;
		left: 10px;
		width: 80px;
		height: 80px;
	}
	.rkb_header{
		border:solid 1px #000;
		padding:0px!important;
		margin:0px!important;
	}
	.tanda_tangan{
		position: fixed;
		bottom: 0px!important;
		width: auto;
	}
	.col-lg-12{
		width: 97%;
		padding: 5px;
		margin: 5px;
	}
	.text-center{
		text-align: center;
	}
	tbody tr
    {
        page-break-after: always;
        page-break-inside: avoid;
    }
    table thead {
	display: table-header-group!important;
	}
	table{
	 width: 100%;
	 text-align: center;
	 margin: 0px;
	 border: solid 1px #000;
	 border-collapse: collapse!important;
	}
	table thead tr,table thead tr th{
	 background-color: #fff;
	 color: #000;
 	 padding: 5px;
	 border: solid 1px #000;
	 border-collapse: collapse!important;
	}

	table tbody tr,table tbody tr td{
		background-color: #fff;
		color: #000;
	 	padding: 5px;
	 	font-size: 10px!important;
		line-height: line-height: 20px!important;
	    border: solid 1px #000;
	    border-collapse: collapse!important;
	}
	.pull-left{
		float: left;
	}
	.pull-right{
		float: right;
	}
</style>
	<title>Print Preview RKB</title>
	<link rel="shortcut icon" href="{{asset('abp.jpg')}}" />
</head>
<body>
@php
	$record_rkb = Illuminate\Support\Facades\DB::table('e_rkb_detail')
				  ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_detail.no_rkb")
				  ->leftjoin("e_rkb_cancel",function($join){
				  		$join->on("e_rkb_cancel.no_rkb","e_rkb_detail.no_rkb") 
				  			 ->on("e_rkb_cancel.part_name","e_rkb_detail.part_name");
														})
				  ->select("e_rkb_cancel.*","e_rkb_header.*","e_rkb_detail.*")
				  ->where("e_rkb_detail.no_rkb",$no_rkb)
				  ->get();
$z=1;
@endphp
<div class="cover">
	<br><br>
	<table class="table table-bordered" border="0" cellspacing="0">
		<thead>
			<tr>
				<th width="10px">No.</th>
				<th nowrap>PART NAME</th>
				<th nowrap>PART NUMBER</th>
				<th width="80px">QUANTITY</th>
				<th nowrap>REMARK</th>
				<th nowrap>Due Date</th>
			</tr>
		</thead>
		<tbody>
@foreach($record_rkb as $key => $value)
@if($value->cancel_by==NULL)
	<tr>
		<td>{{$z}}</td>
		<td>{{$value->part_name}}</td>
		<td>{{$value->part_number}}</td>
		<td>{{$value->quantity}} {{$value->satuan}}</td>
		<td>{{$value->remarks}}</td>
		<td nowrap>{{date("d F Y",strtotime($value->due_date))}}</td>
	</tr>
@php $z++; @endphp
	@endif
@endforeach
		</tbody>
	</table>
</div>
</body>
</html>