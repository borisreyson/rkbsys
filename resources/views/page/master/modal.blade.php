@if(isset($modal)=="true")

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Rule</h4>
      </div>

<form id="form_cancel" action="{{url('/rule/user')}}" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
      <div class="modal-body">

{{csrf_field()}}
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Username <span class="required">*</span>
</label>
<div class="col-md-3 col-sm-9 col-xs-12">
<input type="hidden" id="idUser" required="required" name="idUser" class="form-control col-md-4 col-xs-3" value="{{bin2hex($data->id_user)}}">
<input type="text" id="username" required="required" disabled="disabled" name="username" class="form-control col-md-4 col-xs-3" value="{{$data->username}}">
</div>
</div>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="userRule">Rule <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-6 col-xs-12">
<textarea id="userRule" name="userRule" class="form-control col-md-7 col-xs-12">{{$data->rule}}</textarea>
</div>
</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
      </div>
</form>
    </div>

    <!-- jQuery Tags Input -->
    <script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>
    <script>
      $("#userRule").tagsInput();
    </script>
@endif