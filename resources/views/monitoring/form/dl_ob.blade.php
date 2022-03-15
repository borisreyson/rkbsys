@extends('layout.master')
@section('title')
ABP-system | FORM Delay Ob
@endsection
@section('css')
		<!-- bootstrap-wysiwyg -->
 @include('layout.css')
		<link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
		<link href="{{asset('/css/timepicker.min.css')}}" rel="stylesheet">
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
 input[type=time]{
 	height: 100%!important;margin: 0px!important;padding-top: 0px!important;padding-bottom: 0px!important;
 }

.active { z-index: 1!important; } 
</style>
@php
	$z;
@endphp
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
		<a href="{{url('/monitoring/form/boat')}}">Form Delay Ob</a>
		<br>
		<br>
	</div>
	<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Form Delay Ob</h2>                  
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
			<input type="text" name="tgl" disabled class="form-control" required="required" value="{{date('d F Y',strtotime($HLDelay->tgl))}}">
			@else
			<input type="text" name="tgl" class="form-control" required="required" value="{{date('d F Y')}}">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Shift</label>
		<div class="col-lg-3">
			@if(isset($edit) == 'true')
			<div id="shift" class="btn-group" style="margin-bottom: 5px;" data-toggle="buttons">
				@if($HLDelay->shift==1)
	            <label class="btn btn-default active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	              <input type="radio" name="shift" value="1" checked="checked"> &nbsp; Shift I &nbsp;
	            </label>
	            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	              <input type="radio" name="shift" value="2"> Shift II
	            </label>
	            @elseif($HLDelay->shift==2)
	            <label class="btn btn-default " data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	              <input type="radio" name="shift" value="1"> &nbsp; Shift I &nbsp;
	            </label>
	            <label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	              <input type="radio" name="shift" value="2" checked=""> Shift II
	            </label>
	            @endif
	          </div>
			@else
			<div id="shift" class="btn-group" style="margin-bottom: 5px;" data-toggle="buttons">
	            <label class="btn btn-default active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	              <input type="radio" name="shift" value="1" checked="checked"> &nbsp; Shift I &nbsp;
	            </label>
	            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	              <input type="radio" name="shift" value="2"> Shift II
	            </label>
	          </div>
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Type Delay</label>
		<div class="col-lg-5	" style="margin-bottom:5px; ">
			@if(isset($edit) == 'true')
			<div id="delayType" class="btn-group" data-toggle="buttons">
				@foreach($typeDelay as $k => $type_delay)
				@if($type_delay->code==$HLDelay->type_delay)
                    <label class="btn btn-default active" id="type_delay" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="type_delay" value="{{$type_delay->code}}" checked="checked"> &nbsp; {{$type_delay->desk}}	&nbsp;
                    </label>
                    @php 
                    $z=$k;
                    @endphp
                @else
                    <label class="btn btn-default" id="type_delay" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="type_delay" value="{{$type_delay->code}}"> &nbsp; {{$type_delay->desk}} &nbsp;
                    </label>
                @endif
                 @endforeach
             </div>
			@else

			<div id="delayType" class="btn-group" data-toggle="buttons">
				@foreach($typeDelay as $k => $v)
				@if($k==0)
                    <label class="btn btn-default active" id="type_delay" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="type_delay" value="{{$v->code}}" checked="checked"> &nbsp; {{$v->desk}} &nbsp;
                    </label>
                    @else
                    <label class="btn btn-default" id="type_delay" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="type_delay" value="{{$v->code}}"> &nbsp; {{$v->desk}} &nbsp;
                    </label>
                    @endif
                 @endforeach
             </div>
			@endif
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3"> Delay</label>
		<div class="col-lg-1">
			@if(isset($edit) == 'true')
			<input type="text" name="delay" class="form-control" id="delay" required="required" placeholder=" Delay" value="{{$HLDelay->delay}}">
			@else
			<input type="text" name="delay" class="form-control" id="delay" required="required"  placeholder=" Delay">
			@endif
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">Keterangan</label>
		<div class="col-lg-3">
			@if(isset($edit) == 'true')
			<textarea name="keterangan" class="form-control" required="required" placeholder="Keterangan">{{$HLDelay->keterangan}}</textarea>
			@else
			<textarea name="keterangan" class="form-control" required="required" placeholder="Keterangan"></textarea>
			@endif
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-3 col-lg-3">
			<button class="btn btn-primary" type="submit">Simpan</button>  
			<a href="{{url('/monitoring/form/delay/ob')}}" class="btn btn-danger">Batal</a>  
		</div>
	</div>
</form>
	</div>
</div>
</div>
</div>
<div class="x_panel">
							 <div class="x_title">
									<h2>Delay ob Last Week</h2>                  
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped">
			<thead>
				<tr class="info">
					<th width="150px">Tanggal</th>
					<th>Type Delay</th>
					<th>Shift</th>
					<th>Delay</th>
					<th>Keterangan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$daily =  mysqli_query($konek,"select * from ob_delay_daily order by date(tgl) desc limit 7");
				if($daily){
				if(mysqli_num_rows($daily)>0){
				while($row = mysqli_fetch_object($daily)){
				?>
				
				@if($row->flag=='2')
				<tr style="background-color: red;color: white;">
					<td>{{date("d F Y",strtotime($row->tgl))}}</td>
					<td>{{($row->type_delay)}}</td>
					<td>
						@if($row->shift==1)
							Shift I
						@elseif($row->shift==2)
							Shift II
						@endif
					</td>
					<td>{{$row->delay}}
					
					<td>{{$row->keterangan}}</td>
					<td><a style="color: white;" href="{{url('/monitoring/form/delay/ob/q-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a><a style="color: white;" href="{{url('/monitoring/form/delay/ob/undo-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-undo"></i></a></td>
				</tr>
				@else
				<tr>
					<td>{{date("d F Y",strtotime($row->tgl))}}</td>
					<td>{{($row->type_delay)}}</td>
					<td>
						@if($row->shift==1)
							Shift I
						@elseif($row->shift==2)
							Shift II
						@endif
					</td>
					<td>{{($row->delay)}}</td>
					<td>{{$row->keterangan}}</td>
					<td><a href="{{url('/monitoring/form/delay/ob/q-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a><a href="{{url('/monitoring/form/delay/ob/delete-'.bin2hex($row->no))}}" class="btn btn-xs"><i class="fa fa-trash"></i></a></td>
				</tr>

				@endif
				<?php } 
			}else{ ?>
					<tr>
						<td colspan="7" style="text-align: center;">No Record Data</td>
					</tr>
			<?php }
			} ?>
			</tbody>
		</table>
	</div>
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
		<script src="{{asset('/js/timepicker.min.js')}}"></script>



<script>
$("input[name=tgl]").datepicker({ dateFormat: 'dd MM yy' });
var pikerID = ['dl_daily','dl_mtd'];
var timepicker = new TimePicker(pikerID, {
  lang: 'en',
  theme: 'blue-grey'
});
timepicker.on('change', function(evt) {
  
  var value = (evt.hour || '00') + ':' + (evt.minute || '00');
  evt.element.value = value;
  /* if (evt.element.id === 'dl_daily') {
    evt.element.value = value;
  } else {
    evt.element.value = value;
  }
  */
});
$("label[id=type_delay]").click(function(){
	eq = $("label[id=type_delay]").index(this);
	type_delay = $("input[name=type_delay]").eq(eq).val();
	if(eq == 0){
		$(".myLabel").fadeOut();
		$("div[id=dynamicForm]").fadeOut();
		$("input[name=dynamicField]").val(" ");
	}else if(eq == 1){
		$("div[id=dynamicForm]").hide();
		$("div[id=dynamicForm]").fadeIn();
		$(".myLabel").hide();
		$(".myLabel").eq(eq-1).fadeIn();
	}else if(eq == 2){
		$("div[id=dynamicForm]").hide();
		$("div[id=dynamicForm]").fadeIn();
		$(".myLabel").hide();
		$(".myLabel").eq(eq-1).fadeIn();

	}
});

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