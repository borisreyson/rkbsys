<meta name="csrf-token" content="{{csrf_token()}}">
<link rel="stylesheet" type="text/css" href="{{asset('/css/app.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('/font_awesome/css/font-awesome.min.css')}}">
<style>
	#tableSaya{
		margin-bottom: 60px;
	}
	#box_btn{
		position: fixed;
		bottom: 0;
		right: 0;
		left: 0;
		height: 40px;
		background-color: #333;
		border:1px solid #333;
		display: flex;
	}
	#kirimkan:hover{
		background-color: #E8FFC7;
	}
	#kirimkan:active{
		background-color: #E8DFB5;
	}
	#kirimkan{
		position: absolute;
		right: 20;
		top :5;
		height: 30px;
		width: 70px;
		background-color: #f8f8f8;
		color: #333;
		border:0;
		font-weight: bold;
	}
	.cari-group{
		width: 100%;
		position: relative;
	}
	.btn-times{
		margin: 0px;
		padding: 0px;
		background-color: transparent;
		position: absolute;
		top: 0;
		right: 0;
		border:0;
		bottom: 0;
		width: 30px;
		z-index: 999;
	}
</style>
	<form method="get" action="">
<div class="col-12">
		<table style="margin-top: 10px;" class="table-bordered" width="100%">
			<tr>
				<td style="text-align: right;padding-right: 10px;">Cari</td>
				<td>
					<div class="cari-group">
				@if(isset($_GET['cari']))
				<input type="text" class="form-control" name="cari" value="{{$_GET['cari']}}">
				@if($_GET['cari']!="")
				<button type="button" name="btnClear"  class="btn-times"><i class="fa fa-times"></i></button>
				@endif
				@else
				<input type="text" class="form-control" name="cari" placeholder="Cari...">
				@endif
					</div>
				</td>
				<td><input type="submit" class="btn btn-primary" name="kirim" value="Cari"></td>
			</tr>
		</table>
</div>
	</form>
<table class="table table-bordered" width="100%">
	<thead>
		<tr >
			<th class="text-center">#</th>
			<th class="text-center">Barang</th>
			<th class="text-center">Keterangan</th>
			<th class="text-center">Satuan</th>
			<th class="text-center">Kategori</th>
			<th class="text-center">Kategori Pemakaian</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($detail as $k => $v){ ?>

		<tr>
			<td><button name="dipilih" class="btn btn-default btn-xs">
				<i class="fa fa-check"></i>
			</button></td>
			<input type="hidden" name="item" value="{{$v->item}}">
			<input type="hidden" name="satuan" value="{{$v->satuan}}">
			<td>{{$v->item}}</td>
			<td>{{$v->item_desc}}</td>
			<td>{{$v->satuan}}</td>
			<td>{{$v->category}}</td>
			<td>{{$v->item_cat}}</td>
		</tr>
<?php } ?>
</tbody>
</table>

<div id="box_btn">
<button id="kirimkan" name="kirimkan" onclick="window.close()">Close</button>

</div>
<script src="{{asset('js/app.js')}}"></script>
<script>
	$("button[name=btnClear]").click(function(){
		$("button[name=btnClear]").remove();
		window.location = document.location.href.split('?')[0];
	});
	$("button[name=dipilih]").click(function() {
		eq = $("button[name=dipilih]").index(this);
		item = $("input[name=item]").eq(eq).val();
		satuan = $("input[name=satuan]").eq(eq).val();
		parentData = window.opener.setItem(item,satuan,"{{$eq}}");
		window.close();
	})
</script>