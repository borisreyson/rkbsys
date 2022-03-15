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
		<table class="table-bordered" width="100%">
			<tr>
				<td style="text-align: right;padding-right: 10px;">Cari</td>
				<td>
					<div class="cari-group">
				@if(isset($_GET['cari']))
				<input type="text" class="form-control" name="cari" value="{{$_GET['cari']}}">
				@if($_GET['cari']!="")
				<button type="button" name="btnClear"  class="btn-times">x</button>
				@endif
				@else
				<input type="text" class="form-control" name="cari" value="">
				@endif
					</div>
				</td>
				<td><input type="submit" name="kirim" class="btn btn-primary" value="Cari"></td>
			</tr>
		</table>
</div>
	</form>
<table id="tableSaya" class="table-bordered table" width="100%">
	<thead>
		<tr>
			<th class="text-center">#</th>
			<th class="text-center">No Rkb</th>
			<th class="text-center">Part Name</th>
			<th class="text-center">Part Number</th>
			<th class="text-center" colspan="2">Department - Section</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($detail as $k => $v){ ?>

		<tr>
			<td><button class="btn btn-xs btn-default" name="dipilih">
				<i class="fa fa-check"></i>
			</button></td>
			<input type="hidden" name="no_rkb" value="{{($v->no_rkb)}}">
			<input type="hidden" name="part_name" value="{{($v->part_name)}}">
			<input type="hidden" name="part_number" value="{{($v->part_number)}}">
			<input type="hidden" name="quantity" value="{{($v->quantity)}}">
			<td>{{$v->no_rkb}}</td>
			<td>{{$v->part_name}}</td>
			<td>{{$v->part_number}}</td>
			<td>{{$v->dept}}</td>
			<td>{{$v->sect}}</td>
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
		no_rkb = $("input[name=no_rkb]").eq(eq).val();
		part_name = $("input[name=part_name]").eq(eq).val();
		part_number = $("input[name=part_number]").eq(eq).val();
		quantity = $("input[name=quantity]").eq(eq).val();

		parentData = window.opener.setRKBdet(no_rkb,part_name,part_number,quantity,"{{$eq}}");
		window.close();
	})
</script>