<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Form Karyawan</h4>
  </div>

  	<form id="form_cancel" action="{{url('/absen/form/karyawan')}}" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
      {{csrf_field()}}

      @if(isset($getKar))
    <input type="hidden" name="_method" value="PUT">        
    <input type="hidden" id="oldNik" required="required" name="oldNik" class="form-control col-md-4 col-xs-3" value="@if(isset($getKar)) {{$getKar->nik}} @endif">
      @endif
  <div class="modal-body">
<div class="row">
  <div class="col-lg-12">

	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">NIK <span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-9 col-xs-12">
	<input type="text" id="nik" required="required" name="nik" class="form-control col-md-4 col-xs-3" value="@if(isset($getKar)) {{$getKar->nik}} @endif">
	</div>
	</div>

	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Nama <span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-9 col-xs-12">
	<input type="text" id="nama" required="required" name="nama" class="form-control col-md-4 col-xs-3" value="@if(isset($getKar)) {{$getKar->nama}} @endif">
	</div>
	</div>

	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Departemen <span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-9 col-xs-12">
	<select id="department" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="department" placeholder="Departemen" required="required" type="text" data-live-search="true">
          <option value="">--PILIH--</option>
          @foreach($dep as $k =>$v)
          @if(isset($getKar))
          @if(($getKar->departemen)==$v->id_dept)
          <option value="{{$v->id_dept}}" selected="selected">{{$v->dept}}</option>
          @else
          <option value="{{$v->id_dept}}">{{$v->dept}}</option>
          @endif
          @else
          <option value="{{$v->id_dept}}">{{$v->dept}}</option>
          @endif
          @endforeach
        </select>
	</div>
	</div>

	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Devisi <span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-9 col-xs-12">
	<select id="section" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="section" placeholder="Devisi" disabled="disabled">
          <option value="">--PILIH--</option>
        </select>
	</div>
	</div>

  <div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Jabatan <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-9 col-xs-12">
   <input id="jabatan" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="jabatan" placeholder="Jabatan" required="required" type="text" @if(isset($getKar)) value="{{$getKar->jabatan}}" @endif>
  </div>
  </div>
  <div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Perusahaan <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-9 col-xs-12">
   <select id="perusahaan" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="perusahaan" placeholder="Perusahaan" required="required" type="text" data-live-search="true">
          <option value="">--PILIH--</option>
          @foreach($company as $k =>$v)
          @if(isset($getKar))
          @if(($getKar->perusahaan)==$v->id_perusahaan)
          <option value="{{$v->id_perusahaan}}" selected="selected">{{$v->nama_perusahaan}}</option>
          @else
          <option value="{{$v->id_perusahaan}}">{{$v->nama_perusahaan}}</option>
          @endif
          @else
          <option value="{{$v->id_perusahaan}}">{{$v->nama_perusahaan}}</option>
          @endif
          @endforeach
        </select>
  </div>
  </div>

  </div>
</div>
</div>
 <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 	</div>
 	</form>
</div>

<!---validator-->
    <script src="{{asset('/vendors/validator/validator.js')}}"></script>
    <!-- jQuery Tags Input -->
    <script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>
    <!-- jQuery autocomplete -->
    <script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>
@if(isset($getKar))
<script>
  $(document).ready(function(){
    section("{{urlencode($getKar->departemen)}}","{{urlencode($getKar->devisi)}}");
  });
</script>
@endif
    <script>
      $("select").selectpicker();
    /* VALIDATOR */
function section(dept,selected) {
   if(dept==""){
      $("select[id=section]").html("<option value=\"\">-- Pilih --</option>");

            $("select[name=section]").attr("disabled","disabled");
          
    }else{
      $.ajax({
        type:"GET",
        url:"/section-from-dept",
        data:{dept:dept,selected:selected},
        beforeSend:function(){
          $("select[id=section]").html("<option value=\"\">-- Pilih --</option>");
        },
        success:function(result){
          $("select[id=section]").html(result);

          //if("{{$_SESSION['level']}}"=="administrator")
          //{
            $("select[name=section]").removeAttr("disabled");
            $("select").selectpicker('refresh');
          //}
        }
      });
    }
  }
    function init_validator() {
     
    if( typeof (validator) === 'undefined'){ return; }    
    // initialize the validator function
      validator.message.date = 'not a real date';

      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
      $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

      $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;
        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
    });
    
    };

    init_validator();
      $("select[name=department]").change(function(){
   dept =  $("select[name=department]").val();
   section(encodeURI(dept),"");
    return false;
 });
  function section(dept,selected) {
   if(dept==""){
      $("select[id=section]").html("<option value=\"\">-- Pilih --</option>");

            $("select[name=section]").attr("disabled","disabled");
          
    }else{
      $.ajax({
        type:"GET",
        url:"/section-from-dept",
        data:{dept:dept,selected:selected},
        beforeSend:function(){
          $("select[id=section]").html("<option value=\"\">-- Pilih --</option>");
        },
        success:function(result){
          $("select[id=section]").html(result);

          //if("{{$_SESSION['level']}}"=="administrator")
          //{
            $("select[name=section]").removeAttr("disabled");
            $("select").selectpicker('refresh');
          //}
        }
      });
    }
  }
    </script>