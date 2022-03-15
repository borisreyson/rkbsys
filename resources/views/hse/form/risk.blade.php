
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Form Master Risk</h4>
  </div>
  <form id="form_risk" action="" data-parsley-validate class="form-horizontal form-label-left" method="post">
  <div class="modal-body">
                      {{csrf_field()}}
                      @if($_GET['uid'])
                      <input type="hidden" name="uidMaster" value="{{$editLokasi->idLok}}">
                      <input type="hidden" name="_method" value="PUT">        
                      @endif
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="risk">Risk <span class="required">*</span>
                          </label>
                          <div class="col-md-3 col-sm-6 col-xs-12">
                            <input type="text" id="risk" required="required" name="risk" class="form-control col-md-7 col-xs-12" value="{{$editLokasi->risk or ''}}" placeholder="Lokasi"   >
                        </div>
                      </div>                      
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc_risk">Deskripsi Risk <span class="required">*</span>
                          </label>
                          <div class="col-md-3 col-sm-6 col-xs-12">
                            <textarea id="desc_risk" required="required" name="desc_risk" class="form-control col-md-7 col-xs-12" placeholder="Deskripsi Lokasi">{{$editLokasi->desc_risk or ''}}</textarea>
                        </div>
                      </div>     
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bgColor">Background Color <span class="required">*</span>
                          </label>
                          <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="input-group demo1" id="demo1">
                              <input type="text" readonly="readonly" name="bgColor" id="bgColor"  value="{{$editLokasi->bgColor or '#FFFFFF'}}" class="form-control" />
                              <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                      </div>   
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtColor">Text Color <span class="required">*</span>
                          </label>
                          <div class="col-md-3 col-sm-6 col-xs-12">
                            <input type="text" id="txtColor" required="required" name="txtColor" class="form-control col-md-7 col-xs-12" value="{{$editLokasi->txtColor or ''}}" placeholder="Lokasi"   >
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

<!-- Bootstrap Colorpicker -->
    <script src="{{asset('/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <script>
      /* COLOR PICKER */
       
    function init_ColorPicker() {
      
      if( typeof ($.fn.colorpicker) === 'undefined'){ return; }
      console.log('init_ColorPicker');
      
        $("div[id='div']").colorpicker();
      
    }; 
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
init_ColorPicker();
    </script>