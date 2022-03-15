<!DOCTYPE html>
<html>
<head>
	<title>Tanda Terima Barang</title>
	<style>

	html,body{
		background-color: transparent!important;
		font-size:10px;
		margin: 0px!important;
		padding: 0px!important;

	}
		.table{
			width:100%!important;
		}
		.table-bordered,.table-bordered tr,.table-bordered tr td, .table-bordered thead tr th{
		border:solid 1px #000!important;
		border-spacing: 0px;
		line-height: line-height: 25px !important;
		}
		.table-bordered-bottom tr td{
		border-bottom:solid 1px #000!important;
		border-spacing: 0px;
		line-height: line-height: 25px !important;
		}
		.custom tr td{
			border:0px!important;
		}
		.custom{
			width: auto!important;
			float: left!important;
			font-weight: bolder;
		}
		.right{
			width: auto!important;
			float: right!important;
			font-weight: bolder;
		}
		.col-lg-12{
			width: 99%!important;
			float: left!important;
		} 
		.text-center{
			text-align: center!important;
		}
		.row{
			width: 100%!important;
			float: left!important;
		}
		.pull-right{
			float: right!important;
		}


	</style>
</head>
<body>

	<div style="">
		<div class="row">
			<table class="table table-bordered-bottom">
				<tr>
					<td class="text-center" style="font-size: 16px;padding-bottom: 10px;padding-top: 10px;
		line-height: line-height: 5px !important;"><b>Formulir</b></td>
				</tr>
				<tr>
					<td class="text-center"><b>Tanda Terima Barang</b></td>
				</tr>
			</table>
		</div>
		<div class="row">
			<div class="col-lg-12 text-center" style="font-weight: bold;font-size: 20px;padding-bottom: 10px;padding-top: 10px;">Nomor : {{hex2bin($noid_out)}}<br></div>
				<table class="table custom">
					<?php
						$dr = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan as a")
								->leftJoin("department","department.id_dept","a.departemen")
								->leftJoin("section","section.id_sect","a.devisi")
								->where("a.nama",$stokOut->diterima_dari)
								->first();
// dd($dr);
								// die();
						$terima = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")
						->leftJoin("department","department.id_dept","db_karyawan.data_karyawan.departemen")
						->where("nik",$stokOut->user_reciever)
						->first();
					?>
					<tr>
						<td>Diterima Dari</td>
						<td>:</td>
						<td>{{ucwords($stokOut->diterima_dari)}}</td>
					</tr>
					<tr>
						<td>Department</td>
						<td>:</td>
						<td>{{$dr->dept}}</td>
					</tr>
					<tr>
						<td>Section</td>
						<td>:</td>
						<td>{{$dr->sect}}</td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>{{$stokOut->jabatan_a}}</td>
					</tr>
				</table>
			<!---OK--->
				<table class="table right">
					<tr>
						<td>Penerima</td>
						<td>:</td>
						<td>{{ucwords($terima->nama)}}</td>
					</tr>
					<tr>
						<td>Department</td>
						<td>:</td>
						<td>{{$stokOut->dept}}</td>
					</tr>
					<tr>
						<td>Section</td>
						<td>:</td>
						<td>{{$stokOut->sect}}</td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>{{$stokOut->jabatan}}</td>
					</tr>
				</table>
				<br><br><br><br>
							<table class="table table-bordered" style="margin-top: 100px!important">
								<thead>
								<tr>
									<th width="100px">Kode Barang</th>
									<th>Part Name</th>
									<th>Part Number</th>
									<th>Quantity</th>
									<th>Remarks</th>
								</tr>
								</thead>
<?php  
$det = Illuminate\Support\Facades\DB::table("invout_detail")
		->where("noid_out",$stokOut->noid_out)
		->join('invmaster_item',"invmaster_item.item","invout_detail.item")
		->get();
	foreach($det as $k => $v){
?>
								<tr class="text-center">
									<td>{{$v->noid_out}}</td>
									<td>{{$v->item_desc}}</td>
									<td>{{$v->part_number}}</td>
									<td>{{$v->stock_out}} {{$v->satuan}}</td>
									<td>{{$v->remark}}</td>
								</tr>
	<?php } ?>
							</table>

			<div class="footer">
				<div class="col-lg-12">
					<br><br><br><br>&nbsp;
				</div>
					<div class=" pull-right">
						<div class="pull-right" style="font-weight: bolder;">
							<p>Jembayan , {{date('d F Y')}}
							</p>
						</div>
						<div class="">
						<table class="table table-bordered text-center" style="width: 80mm!important">
							<thead>
								<tr>
									<th class="text-center">Diserahkan Oleh,</th>
									<th class="text-center">Diterima Oleh,</th>
								</tr>
							</thead>
							<tbody>
								<tr style="height: 70px;">
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>
&nbsp;
										<hr style="height: 1px;background-color: #000;padding: 0px;margin-bottom: 0px;">
										Logistik
									</td>
									<td>&nbsp;
										<hr style="height: 1px;background-color: #000;padding: 0px;margin-bottom: 0px;">
										&nbsp;
										</td>
								</tr>
							</tbody>
						</table>
						</div>
					</div>
			</div>
		</div>
	</div>
</body>
</html>