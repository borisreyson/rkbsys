@if(isset($editReq))
@if($editReq=="OK")
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Master Item</h4>
      </div>
<form class="form-horizontal form-label-left" action="{{url('/masteritem/request/detail')}}" method="post" novalidate>
{{csrf_field()}}
{{ method_field('PUT') }} 
<input type="hidden" name="reqKode" value="{{$edit->kode}}">
<input type="hidden" name="user_request" value="{{$edit->user_request}}">
<div class="modal-body">
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode Barang<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="kode_barang" class="form-control col-md-7 col-xs-12" name="kode_barang" placeholder="Kode Barang" required="required" type="text" value="{{$INVNumb}}">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Part Name <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="part_name" class="form-control col-md-7 col-xs-12" name="part_name" placeholder="Code Category" required="required" type="text" @if(isset($edit)) value="{{$edit->part_name}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Part Number <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="part_number" class="form-control col-md-7 col-xs-12" name="part_number" placeholder="Code Category" required="required" type="text" @if(isset($edit)) value="{{$edit->part_number}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Satuan <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="satuan" class="form-control col-md-7 col-xs-12" data-live-search="true" name="satuan" placeholder="Code Category" required="required" >
          <option value="">--PILIH--</option>
          @foreach($satuan as $v)
          @if($edit->satuan==$v->satuannya)
          <option value="{{$v->satuannya}}" selected="selected">{{strtoupper($v->satuannya)}}</option>
          @else
          <option value="{{$v->satuannya}}">{{strtoupper($v->satuannya)}}</option>
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Minimum <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
        <input id="minimum" class="form-control col-md-7 col-xs-12" min="1" name="minimum" placeholder="Code Category" required="required" type="number" @if(isset($edit)) value="{{$edit->minimum}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Category <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="kategori" class="form-control col-md-7 col-xs-12 " data-live-search="true" name="kategori" placeholder="Code Category" required="required">
          <option value="">--PILIH--</option>
          @foreach($kategori as $v)
          @if($edit->item_cat==$v->CodeCat)
          <option value="{{$v->CodeCat}}" selected="selected">{{strtoupper($v->DeskCat)}}</option>
          @else
          <option value="{{$v->CodeCat}}">{{strtoupper($v->DeskCat)}}</option>
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Label <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="label" class="form-control col-md-7 col-xs-12 " data-live-search="true" name="label" placeholder="Code Category" required="required">
          <option value="">--PILIH--</option>
          @foreach($label as $v)
          <option value="{{$v->code_category}}">{{strtoupper($v->code_category)}} ({{strtoupper($v->desc_category)}})</option>
          @endforeach
        </select>
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
  @if(!isset($editMaster))
<script>
  $(".modal-content").ready(function(){
      $.ajax({
        type:"GET",
        url:"{{url('/inventory/cek/master')}}",
        success:function(res) {
          $("input[name=kode_barang]").val(res);
        }
      });

    });
</script>
    @endif
    <script>
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

@endif
@endif