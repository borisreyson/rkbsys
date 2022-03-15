<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Sarana</h4>
      </div>
<form class="form-horizontal form-label-left" action="" method="post" novalidate>
<div class="modal-body">
{{csrf_field()}}
@if(isset($edit))
<input type="hidden" name="_method" value="PUT" >
<input type="hidden" name="data_id" value="{{$data_id}}" >
@endif
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pol">No Polisi <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
        <input id="no_pol" class="form-control col-md-12 col-xs-12" data-validate-length-range="2" name="no_pol" placeholder="No Polisi" required="required" type="text" @if(isset($edit)) value="{{$edit->no_pol}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_lv">No LV <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="input-group">
         <span id="satuan" class="input-group-addon">LV</span>
         <input id="no_lv" class="form-control" style="width: 55px" data-inputmask="'mask' : '999'" data-validate-length-range="2" name="no_lv" placeholder="001" required="required" type="text" @if(isset($edit)) value="{{$edit->no_lv}}" @endif>
         </div>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="driver">Driver <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="driver" class="form-control col-md-12 col-xs-12" data-live-search="true" data-validate-length-range="2" name="driver" required="required" type="text" @if(isset($edit)) value="{{$edit->driver}}" @endif>
          <option value="">--PILIH--</option>
          @foreach($driver as $k => $v)
          @if(isset($edit))
          @if($v->no==$edit->driver)
          <option value="{{$v->no}}" selected="selected">{{ucwords($v->nama)}}</option>
          @endif
          <option value="{{$v->no}}">{{ucwords($v->nama)}}</option>
          @else
          <option value="{{$v->no}}">{{ucwords($v->nama)}}</option>
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pic_lv">PIC <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="pic_lv" class="form-control col-md-12 col-xs-12" data-live-search="true" data-validate-length-range="2" name="pic_lv" required="required" type="text" @if(isset($edit)) value="{{$edit->pic_lv}}" @endif>
          <option value="">--PILIH--</option>
          @foreach($karyawan as $k => $v)
          @if(isset($edit))
          @if($v->nik==$edit->pic_lv)
          <option value="{{$v->nik}}" selected="selected">{{ucwords($v->nama)}}</option>
          @endif
          <option value="{{$v->nik}}">{{ucwords($v->nama)}}</option>
          @else
          <option value="{{$v->nik}}">{{ucwords($v->nama)}}</option>
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="merek_type">Merek Type <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="merek_type" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="merek_type" placeholder="Merek Type" required="required" type="text" @if(isset($edit)) value="{{$edit->merek_type}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis">Jenis <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="jenis" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="jenis" placeholder="Jenis" required="required" type="text" @if(isset($edit)) value="{{$edit->jenis}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="model">Model <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="model" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="model" placeholder="Model" required="required" @if(isset($edit)) value="{{$edit->model}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tahun_pembuatan">Tahun Pembuatan <span class="required">*</span>
      </label>
      <div class="col-md-1 col-sm-6 col-xs-12">
        <input id="tahun_pembuatan" class="form-control col-md-12 col-xs-12" data-validate-length-range="2" name="tahun_pembuatan" placeholder="{{date('Y')}}" required="required" type="text" data-inputmask="'mask' : '9999'" @if(isset($edit)) value="{{$edit->thn_pembuatan}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="isi_slinder">Isi Slinder <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="isi_slinder" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="isi_slinder" placeholder="Isi Slinder" required="required" max="4" type="text" @if(isset($edit)) value="{{$edit->isi_slinder}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warna_kb">Warna KB <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="warna_kb" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="warna_kb" placeholder="Warna KB" required="required" type="text" @if(isset($edit)) value="{{$edit->warna_kb}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warna_tnkb">Warna TNKB <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="warna_tnkb" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="warna_tnkb" placeholder="Warna TNKB" required="required" type="text" @if(isset($edit)) value="{{$edit->warna_tnkb}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_p">Pemilik <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="nama_p" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="nama_p" placeholder="Pemilik" required="required" type="text" @if(isset($edit)) value="{{$edit->nama_p}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_p">Alamat Pemilik <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="alamat_p" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="alamat_p" placeholder="Alamat Pemilik" required="required" type="text" @if(isset($edit)) value="{{$edit->alamat_p}}" @endif>
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
    <!-- jquery.inputmask -->
    <script src="{{asset('/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js')}}"></script>

    <script>
      $("select").selectpicker();

    /* INPUT MASK */
      
    function init_InputMask() {
      
      if( typeof ($.fn.inputmask) === 'undefined'){ return; }
      console.log('init_InputMask');
      
        $(":input").inputmask();
        
    };
    init_InputMask();
    setTimeout(function(){
      $("input[id=no_pol]").focus();
    },300);

    $("input").keyup(function(){
      this.value = this.value.toLocaleUpperCase();
    });
    /* VALIDATOR */

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
     
     
    </script>