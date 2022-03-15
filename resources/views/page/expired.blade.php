@if(isset($expired))

<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}} Set To Expired</h4>
      </div>

<form id="demo-form2"  action="{{url('/api/expired/send')}}"  class="form-horizontal form-label-left" method="post">
	{{csrf_field()}}
      <div class="modal-body">
<input type="hidden" name="no_rkb" value="{{bin2hex($no_rkb)}}">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-6 col-xs-12" for="id_rkb">No Rkb<span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
	  <input type="text" id="id_rkb" required="required" disabled="disabled" name="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$no_rkb}}">
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-6 col-xs-12" for="id_rkb">Expire Remarks<span class="required">*</span>
	</label>
	<div class="col-md-9 col-sm-6 col-xs-12">
	  <textarea id="expired" required="required" placeholder="Expired Remarks!" name="expired" class="form-control"></textarea>
	</div>
</div>

</div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Submit</button>
        <button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>

</form>
</div>
@endif