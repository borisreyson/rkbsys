@if(isset($suplierNew))
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Method</h4>
      </div>
<form class="form-horizontal form-label-left" action="" method="post" novalidate>
<div class="modal-body">
{{csrf_field()}}
@if(isset($editSuplier))
<input type="hidden" name="_method" value="PUT" >
<input type="hidden" name="data_id" value="{{$data_id}}" >
@endif
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="suplier">Nama Suplier <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="suplier" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="suplier" placeholder="Nama Suplier" required="required" type="text" @if(isset($editSuplier)) value="{{$editSuplier->nama_supplier}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_instansi">Nama Instansi <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="nama_instansi" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="nama_instansi" placeholder="Nama Instansi" required="required" type="text" @if(isset($editSuplier)) value="{{$editSuplier->nama_instansi}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea name="alamat" id="alamat" required="required" placeholder="Alamat" class="form-control col-md-7 col-xs-12">@if(isset($editSuplier)){{$editSuplier->alamat}}@endif</textarea>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nmr_contact">Nomor Kontak <span class="required">*</span>
      </label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <input type="tel" id="telephone" name="phone" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12" placeholder="Nomor Kontak" @if(isset($editSuplier)) value="{{$editSuplier->nmr_contact}}" @endif>
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_instansi">Kategori <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="category" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="category" placeholder="Category" required="required" >
          @foreach($catVendor as $kVend => $vVend)
          
          @if(isset($editSuplier))
          @if($vVend->kodeCat==$editSuplier->category_vendor)
          <option value="{{$vVend->kodeCat}}" selected="selected">{{$vVend->CategoryVendor}}</option>
          @else

          <option value="{{$vVend->kodeCat}}">{{$vVend->CategoryVendor}}</option>
          @endif
          @else
          <option value="{{$vVend->kodeCat}}">{{$vVend->CategoryVendor}}</option>
          @endif

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
    <!--<script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>-->
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
/*

        //tags input
      function init_TagsInput() {
          
        if(typeof $.fn.tagsInput !== 'undefined'){  
         
        $('#desc').tagsInput({
          width: 'auto'
        });
        
        }
        
        };
    init_TagsInput();
    */

    init_validator();
    </script>
@endif