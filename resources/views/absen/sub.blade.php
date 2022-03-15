<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="row">
  <div class="col-lg-12">

    <form action="{{url('/absen/new/sub')}}" method="post" class="form-horizontal col-lg-10 row">
      {{csrf_field()}}
      <input type="hidden" name="_method" value="PUT">
      <div class="form-group">
    <label class="control-label col-lg-3">Department</label>
    <div class="col-lg-7">
    <?php
      $dept = Illuminate\Support\Facades\DB::table("department")->get();
    ?>
     <select name="dept" id="dept" class="form-control" required="required">
      <option value="">--PILIH--</option>
      @foreach($dept as $k => $v)
      <option value="{{$v->id_dept}}">{{strtoupper($v->dept)}}</option>
      @endforeach
     </select>
    </div>
    </div>
    <div class="form-group">
    <label class="control-label col-lg-3">Sub Bagian</label>
    <div class="col-lg-7">
     <input type="text" name="sub_bagian" id="sub_bagian" class="form-control" placeholder="Sub Bagian"  required="required">
    </div>
    </div>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-7">
      <button class="btn btn-primary pull-right" id="kirim" type="submit">Simpan</button> 
    </div>
  </div>
    </form>
  </div>
  </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>
</div>
  