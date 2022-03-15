<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Form Master Lokasi</h4>
  </div>
  <form id="form_lokasi" action="" data-parsley-validate class="form-horizontal form-label-left" method="post">
  <div class="modal-body">
                      {{csrf_field()}}
                      @if($editLokasi)
                      <input type="hidden" name="uidMaster" value="{{$editLokasi->idLok}}">
                      <input type="hidden" name="_method" value="PUT">        
                      @endif
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lokasi">Lokasi <span class="required">*</span>
                          </label>
                          <div class="col-md-3 col-sm-6 col-xs-12">
                            <input type="text" id="lokasi" required="required" name="lokasi" class="form-control col-md-7 col-xs-12" value="{{$editLokasi->lokasi or ''}}" placeholder="Lokasi"   >
                        </div>
                      </div>                      
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="des_lokasi">Deskripsi Lokasi <span class="required">*</span>
                          </label>
                          <div class="col-md-3 col-sm-6 col-xs-12">
                            <textarea id="des_lokasi" required="required" name="des_lokasi" class="form-control col-md-7 col-xs-12" placeholder="Deskripsi Lokasi">{{$editLokasi->des_lokasi or ''}}</textarea>
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