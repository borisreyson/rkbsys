@extends('layout.master')
@section('title')
ABP-system | FORM BOAT
@endsection
@section('css')
		<!-- bootstrap-wysiwyg -->
 @include('layout.css')
		<link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;

						overflow-y: auto;
						/* prevent horizontal scrollbar */
						overflow-x: hidden;
						}  

.ck-editor__editable {
		min-height: 90px;
}
 .modal-xl{
	width: 90%!important;
	margin-top: 50px;
 }
 table thead tr th, table tbody tr td {
	text-align: center;
 }
</style>
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<!-- page content -->

<div class="right_col" role="main">
	<div class="col-lg-12">
		<br>
		<a href="{{url('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
		<a href="{{url('/monitoring/form/boat')}}">Form BOAT</a>
		<br>
		<br>
	</div>
	<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Form Monitoring BOAT</h2>                  
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
<div class="row">
	<div class="col-xs-12">
<form class="form-horizontal" method="post" action="">
	{{csrf_field()}}
	<div class="form-group">
		<label class="control-label col-lg-3">Tanggal</label>
		<div class="col-lg-2">
			@if(isset($edit) == 'true')
			<input type="text" name="tgl" disabled class="form-control" required="required" value="{{date('d F Y',strtotime($daily->tgl))}}">
			@else
			<input type="text" name="tgl" class="form-control" required="required" value="{{date('d F Y')}}">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Tug Boat</label>
		<div class="col-lg-3">
			@if(isset($edit) == 'true')
			<input type="text" name="boat" class="form-control" required="required" placeholder="Tug Boat" value="{{$daily->tugboat}}">
			@else
			<input type="text" name="boat" class="form-control" required="required" placeholder="Tug Boat">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Barge</label>
		<div class="col-lg-3">
			@if(isset($edit) == 'true')
			<input type="text" name="barge" class="form-control" required="required" placeholder="Barge" value="{{$daily->barge}}">
			@else
			<input type="text" name="barge" class="form-control" required="required" placeholder="Barge">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Time</label>
		<div class="col-lg-3">
			@if(isset($edit) == 'true')
			<input type="text" name="time_board" class="form-control" required="required" placeholder="Time" value="{{($daily->time_board)}}">
			@else
			<input type="text" name="time_board" class="form-control" required="required" placeholder="Time">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Tonase</label>
		<div class="col-lg-3">
			@if(isset($edit) == "true")
			<input type="text" name="tonase" class="form-control" required="required" placeholder="Tonase" value="{{number_format($daily->tonase,3)}}">
			@else
			<input type="text" name="tonase" class="form-control" required="required" placeholder="Tonase">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Keterangan</label>
		<div class="col-lg-3">
			@if(isset($edit) == 'true')
			<input type="text" name="keterangan" class="form-control" required="required" placeholder="Keterangan" value="{{$daily->status}}">
			@else
			<input type="text" name="keterangan" class="form-control" required="required" placeholder="Keterangan">
			@endif
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-3 col-lg-3">
			<button class="btn btn-primary" type="submit">Simpan</button>  
			<a href="{{url('/monitoring/form/boat')}}" class="btn btn-danger">Batal</a>  
		</div>
	</div>
</form>
	</div>
</div>
</div>
</div>
<div class="x_panel">
							 <div class="x_title">
									<h2>Boat Last Week</h2>                  
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped">
			<thead>
				<tr class="info">
					<th width="150px">Tanggal</th>
					<th>Tug Boat</th>
					<th>Barge</th>
					<th>Time</th>
					<th>Tonase</th>
					<th>Keterangan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
			$daily = Illuminate\Support\Facades\DB::table("monitoring_mhu.barge_boat")->orderBy("tgl","desc")->paginate(7);
        foreach($daily as $k =>$row){
				?>
				@if($row->flag=='2')
				<tr style="background-color: red;color: white;">
					<td>{{date("d F Y",strtotime($row->tgl))}}</td>
					<td>{{($row->tugboat)}}</td>
					<td>{{($row->barge)}}</td>
					<td>{{($row->time_board)}}</td>
					<td>{{number_format($row->tonase,3)}}</td>
					<td>{{$row->status}}</td>
					<td>
            @if(isset($_GET['page']))
            <a style="color: white;" href="{{url('/mhu/form/boat/q-'.bin2hex($row->no))}}?page={{$_GET['page']}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @else
            <a style="color: white;" href="{{url('/mhu/form/boat/q-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @endif
            <a style="color: white;" href="{{url('/mhu/form/boat/undo-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-undo"></i></a></td>
				</tr>
				@else
				<tr>
					<td>{{date("d F Y",strtotime($row->tgl))}}</td>
					<td>{{($row->tugboat)}}</td>
					<td>{{($row->barge)}}</td>
					<td>{{($row->time_board)}}</td>
					<td>{{number_format($row->tonase,3)}}</td>
					<td>{{$row->status}}</td>
					<td>
            @if(isset($_GET['page']))
            <a href="{{url('/mhu/form/boat/q-'.bin2hex($row->no))}}?page={{$_GET['page']}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @else
            <a href="{{url('/mhu/form/boat/q-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @endif
            <a href="{{url('/mhu/form/boat/delete-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-trash"></i></a></td>
				</tr>

				@endif
			<?php } ?>
			</tbody>
		</table>
	</div>
  <div class="col-lg-12 text-center">{{$daily->links()}}</div>
</div>
								</div>
							</div>
						</div>
</div>
</div>



@include('layout.footer')


		<!-- compose -->
		
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
<div id="konten_modal"></div>
	</div>
</div>

</div>
@endsection

@section('js')
<!-- Datatables -->
		<!-- FastClick -->


@include('layout.js')
		<script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
		<script src="{{asset('/numeral/min/numeral.min.js')}}"></script>
		<!-- bootstrap-wysiwyg -->
		<script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
		<script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
		<script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>

<script>
$("input[name=tgl]").datepicker({ dateFormat: 'dd MM yy' });
</script>



@if(session('success'))
	<script>
		setTimeout(function(){
new PNotify({
					title: 'Success',
					text: "{{session('success')}}",
					type: 'success',
					hide: true,
					styling: 'bootstrap3'
			});
		},500);
	</script>
@endif
@if(session('failed'))
	<script>
		setTimeout(function(){
new PNotify({
					title: 'Failed',
					text: "{{session('failed')}}",
					type: 'error',
					hide: true,
					styling: 'bootstrap3'
			});
		},500);
	</script>
@endif
@endsection