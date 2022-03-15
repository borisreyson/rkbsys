    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tanggal & Jam Masuk</h4>
      </div>
          <form method="post" class="form-horizontal" action="{{url('/sarpras/sarana/keluar-masuk/t_m_in_post')}}">
      <div class="modal-body">
      	<div class="row">
      		<div class="col-lg-12">
      			{{csrf_field()}}
				<input type="hidden" name="noid_out" value="{{$noid_out}}">
            <div class="form-group">
              <label class="control-label col-lg-4">Tanggal Masuk</label>
              <div class="col-lg-4">
              <input type="text" name="tgl_in" class="form-control" value="{{date('d F Y')}}" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-4">Jam Masuk</label>
              <div class="col-lg-2">
              <input type="text" name="jam_in" maxlength="5" class="form-control" value="{{date('H:i')}}" required="required">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-lg-4">Keterangan</label>
              <div class="col-lg-5">
              <textarea name="keterangan" class="form-control" placeholder="Keterangan" required="required"></textarea>
              </div>
            </div>
      		</div>
      	</div>
      </div>
      <div class="modal-footer">

        <button class="btn btn-primary" name="submit" type="submit">Submit</button>
      	<button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>
          </form>
  	</div>
<script>
  $("#konten_modal").on("focus","input[name=tgl_in]",function(){
    $("input[name=tgl_in]").datepicker({dateFormat: 'dd MM yy'});
  });
</script>