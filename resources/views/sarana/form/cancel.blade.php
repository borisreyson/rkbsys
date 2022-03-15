@if(isset($user))
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<link rel="stylesheet" type="text/css" href="{{asset('/css/app.css')}}">
  <meta name="csrf-token" content="{{csrf_token()}}">
@include('layout.css')
  <title>Form Edit Sarana Keluar</title>
</head>
<body>
@endif
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancel Form Sarana Keluar</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-lg-12">

      		<form method="post" class="form-horizontal" action="">
      			{{csrf_field()}}
				<input type="hidden" name="_method" value="PUT" >
				<input type="hidden" name="data_id" value="{{$data_id}}">
      			<div class="form-group">
      				<label class="control-label col-lg-2">Keterangan</label>
      				<div class="col-lg-10">
      				<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
      				</div>
      			</div>
      			<div class="form-group">
      				<div class="pull-right">
      					<button class="btn btn-primary" name="submit" type="submit">Submit</button>
      				</div>
      			</div>

      		</form>
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" id="close_modal" 
@if(isset($user)) onclick="window.close()" @endif data-dismiss="modal">Close</button>
      </div>
  	</div>
@if(isset($user))
@if(session('success'))
<script>
  window.opener.location.reload();
  window.close();
</script>
@endif
</body>
</html>
@endif