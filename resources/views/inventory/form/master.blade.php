@if(isset($masterNew))
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Master Inventory</h4>
      </div>
<form class="form-horizontal form-label-left" action="" method="post" novalidate>
<div class="modal-body">
{{csrf_field()}}
@if(isset($editMaster))
<input type="hidden" name="_method" value="PUT" >
<input type="hidden" name="data_id" value="{{$data_id}}" >
@endif
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item">Kode Barang <span class="required">*</span>
      </label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <input id="item" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="item" placeholder="Kode Item" required="required" type="text" @if(isset($editMaster)) value="{{$editMaster->item}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Part Name <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="desc" id="desc" rows="4" required="required" placeholder="Part Name" class="form-control col-md-7 col-xs-12" value="@if(isset($editMaster)){{$editMaster->item_desc}}@endif">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Part Number <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="part_number" id="part_number" rows="4" placeholder="Part Number" class="form-control col-md-7 col-xs-12" value="@if(isset($editMaster)){{$editMaster->part_number}}@endif">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan <span class="required">*</span>
      </label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <select id="satuan" class="form-control col-md-7 col-xs-12" data-live-search="true" name="satuan" placeholder="Units" required="required" >
          <option value="">-- PILIH --</option>
          @foreach($satuan as $k => $v)
          @if(isset($editMaster))
          @if($v->satuannya==$editMaster->satuan)
          <option value="{{$v->satuannya}}" selected="selected">{{$v->satuannya}}</option>
          @else          
          <option value="{{$v->satuannya}}">{{$v->satuannya}}</option>
          @endif
          @else
          <option value="{{$v->satuannya}}">{{$v->satuannya}}</option>          
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Label <span class="required">*</span>
      </label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <select id="category" class="form-control col-md-7 col-xs-12" data-live-search="true" name="category" placeholder="Label" required="required" >
          <option value="">-- PILIH --</option>
          @foreach($category as $k => $v)
          @if(isset($editMaster))
          @if($v->code_category==$editMaster->category)
          <option value="{{$v->code_category}}" selected="selected">{{strtoupper($v->code_category)}} ( {{ucwords($v->desc_category)}} )</option>
          @else          
          <option value="{{$v->code_category}}">{{strtoupper($v->code_category)}} ( {{ucwords($v->desc_category)}} )</option>
          @endif
          @else
          <option value="{{$v->code_category}}">{{strtoupper($v->code_category)}} ( {{ucwords($v->desc_category)}} )</option>          
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kategori <span class="required">*</span>
      </label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <select id="catItem" class="form-control col-md-7 col-xs-12" data-live-search="true" name="catItem" placeholder="Category Item" required="required" >
          <option value="">-- PILIH --</option>
          @foreach($catItem as $k1 => $v1)
          @if(isset($editMaster))
          @if($v1->CodeCat==$editMaster->item_cat)
          <option value="{{$v1->CodeCat}}" selected="selected">{{ucwords($v1->DeskCat)}}</option>
          @else          
          <option value="{{$v1->CodeCat}}">{{ucwords($v1->DeskCat)}}</option>
          @endif
          @else
          <option value="{{$v1->CodeCat}}">{{ucwords($v1->DeskCat)}}</option>          
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">

      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Stok Minimal <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
         <span class="staticParent">
        
        <input id="minstok" style="width: 90px!important;" type="number" min="1" class="form-control col-md-2 col-xs-2 child" name="minstok" placeholder="Minimum Stok" required="required"  @if(isset($editMaster)) value="{{$editMaster->minimum}}" @endif>
        
      </span>
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
    @if(!isset($editMaster))
<script>
  $(".modal-content").ready(function(){
      $.ajax({
        type:"GET",
        url:"{{url('/inventory/cek/master')}}",
        success:function(res) {
          $("input[name=item]").val(res);
        }
      });

    });
</script>
    @endif
    <script>
    /* VALIDATOR */

    
    $("select").selectpicker();

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
                $('#item').keydown(function(event) {
              if (event.keyCode == '32') {
                 event.preventDefault();
               }
            });

$(".modal-content").on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[190,46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
});
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