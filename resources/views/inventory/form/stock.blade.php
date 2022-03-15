@if(isset($StokIn))
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Stock In</h4>
      </div>
<form class="form-horizontal form-label-left" action="" method="post" novalidate>
<div class="modal-body">
{{csrf_field()}}
@if(isset($editSuplier))
<input type="hidden" name="_method" value="PUT" >
<input type="hidden" name="data_id" value="{{$data_id}}" >
@endif
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="method">Method <span class="required">*</span>
      </label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <select id="method" class="form-control col-md-7 col-xs-12" data-live-search="true" name="method" placeholder="Method" required="required" >
          <option value="">-- PILIH --</option>
          @foreach($method as $k => $v)
          @if(isset($editMaster))
          @if($v->code_methode==$editMaster->method)
          <option value="{{$v->code_methode}}" selected="selected">{{strtoupper($v->code_methode)}} ( {{ucwords($v->code_desc)}} )</option>
          @else          
          <option value="{{$v->code_methode}}">{{strtoupper($v->code_methode)}} ( {{ucwords($v->code_desc)}} )</option>
          @endif
          @else
          <option value="{{$v->code_methode}}">{{strtoupper($v->code_methode)}} ( {{ucwords($v->code_desc)}} )</option>          
          @endif
          @endforeach
        </select>
      </div>
    </div>
      <div id="kontenDrop"></div>
</div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary" id="submit" disabled="disabled">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>

<!---validator-->
    <script src="{{asset('/vendors/validator/validator.js')}}"></script>
    <!-- jQuery Tags Input -->
    <!--<script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>-->
    <script>
      var dipilih;
    $("select").selectpicker();
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
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }
        if(submit){
          return true;
        }else{
          e.preventDefault();
        }

    });
    
    };
    $("select[id=method]").change(function() {
      dipilih = $("select[id=method]").val();
      $.ajax({
        type:"POST",        
        url:"{{url('/inventory/stock/fade')}}",
        data:{metode:dipilih},
        success:function(result){
          if(dipilih==""){
          $("div[id=kontenDrop]").hide();
          $("button[id=submit]").attr("disabled","disabled");
          }else{
            
          $("div[id=kontenDrop]").hide().html(result).slideToggle();
          $("button[id=submit]").removeAttr("disabled");
          }
        }
      });
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


@if(isset($StokInFade))
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_rkb">Nomor Rkb <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="input-group">
        <input id="no_rkb" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="no_rkb" placeholder="Nomor RKB"type="text" >
        <span class="input-group-btn">
          <button class="btn btn-default" name="addRKB" type="button"><i class="fa fa-search-plus"></i></button>
        </span>
      </div>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_po">PO Number <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="no_po" type="text" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="no_po" placeholder="PO Number" >
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_surat">Nomor Surat <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="no_surat" type="text" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="no_surat" placeholder="Nomor Surat">
      </div>
    </div>

    <table class="table table-striped myTable" >
      <thead>
        <tr>
          <th class="text-center">Part Name</th>
          <th class="text-center">Item</th>
          <th class="text-center" width="125">Stock In</th>
          <th class="text-center">Location</th>
          <th class="text-center">Condition</th>
          <th class="text-center">Suplier</th>
          <th class="text-center">Remarks</th>
          <th class="text-center">Tgl Diterima</th>
          <th class="text-center" width="100">Action</th>
        </tr>
      </thead>
      <tbody>

         <tr id="last_column" class="row_hide">
            <td><span class="form-control-static" id="total"></span></td>
            <td colspan="6"></td>
            <td colspan="2">
                <button type="button" id="btn_row" class="btn btn-primary pull-right">Add</button>
            </td>
          </tr>
      </tbody>
    </table>
    <!-- jQuery autocomplete -->
    <script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>
    <script>

          var arrItem = [];
      function SetTemp(partName){
      return '<tr class="dynamic_item">'+
          '<td>'+
            '<div class="item form-group">'+
              '<input type="text" name="part_name[]" id="part_name" class="form-control" required="required" value="'+partName+'">'+
              '<input type="hidden" name="part_name_c[]" id="part_name_c" class="form-control" value="'+partName+'">'+
            '</div>'+
          '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<input type="text" name="item[]" id="item" class="form-control" required="required">'+
              '<input type="hidden" name="item_hide[]" id="item_hide" class="form-control">'+
            '</div>'+
          '</td>'+
          '<td>'+
            '<div class="item form-group">'+
            '<div class="input-group">'+
              '<span class="staticParent "><input type="number" name="stock_in[]" id="stock_in" class="form-control child stock_in" value="1" min="1"  required="required"></span>'+
              '<span id="satuan" class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
            '</div>'+
            '</div>'+
        '</td>'+
          '<td>'+
           '<div class="item form-group">'+
              '<input type="hidden" name="location_hide[]" id="location_hide" class="form-control">'+
              '<input type="text" name="location[]" id="location" class="form-control" required="required">'+
            '</div>'+
        '</td>'+
          '<td>'+
           '<div class="item form-group">'+
              '<input type="hidden" name="condition_hide[]" id="condition_hide" class="form-control">'+
              '<input type="text" name="condition[]" id="condition" class="form-control" required="required">'+
            '</div>'+
        '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<input type="hidden" name="suplier_hide[]" id="suplier_hide" class="form-control">'+
              '<input type="text" name="suplier[]" id="suplier" class="form-control" required="required">'+
            '</div>'+
        '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<textarea name="remarks[]" id="remarks" class="form-control" rows="1" required="required"></textarea>'+
            '</div>'+
        '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<input type="text" name="wkt_datang[]" id="wkt_datang" class="form-control datepicker" >'+
            '</div>'+
        '</td>'+
          '<td>'+
            '<div class="text-center">'+
              '<button type="button" class="btn btn-xs btn-danger" name="removeRow" id="removeRow"><i class="fa fa-times"></i></button>'+
            '</div>'+
        '</td>'+
        '</tr>';
      }
      function setData(value,no_rkb){
        myNewData=value;
        if(no_rkb!=""){
          $("input[name=no_rkb]").val(no_rkb);
        }
         $(myNewData).each(function(i){
            $("tr[id=last_column]").before(SetTemp(myNewData[i]));     
                });
         if(myNewData>0){
          $("button[id=btn_row]").attr("disabled","disabled").addClass("disabled");
         }else{
            $("button[id=btn_row]").removeAttr("disabled").removeClass("disabled");
         }
      }
        $(document).ready(function(){
          //$("tr[id=last_column]").before(template);
          
          if(dipilih=="PO"){
              $("input[id=no_rkb]").attr("required","required");
              $("input[id=part_name]").attr("required","required");
              $("input[id=part_number]").attr("required","required");
              $("input[id=no_po]").attr("required","required");
            }else{
              $("input[id=no_rkb]").removeAttr("required");
              $("input[id=part_name]").removeAttr("required");
              $("input[id=part_number]").removeAttr("required");
              $("input[id=no_po]").removeAttr("required");
            }

          $(document).on("focus",'input[id=item]',function(){
            eq = $("input[id=item]").index(this);
            $('input[id=item]').eq(eq).devbridgeAutocomplete({ 
              serviceUrl: "{{url('/inventory/get/master/item')}}",
              onInvalidateSelection: function() {
                  //console.log('on invalidate');
              },
              onSelect: function (suggestion) {
                $("span[id=satuan]").eq(eq).html(suggestion.satuan);
                $("input[id=item_hide]").eq(eq).val(suggestion.data);

              },
              autoSelectFirst:true,
              onHide: function () {
                  //console.log("on hide")
              }
            });
          });

          $(document).on("focus",'input[id=location]',function(){
            eq = $("input[id=location]").index(this);
            $("input[id=location]").eq(eq).devbridgeAutocomplete({ 
              serviceUrl: "{{url('/inventory/get/master/location')}}",
              onSelect: function (suggestion) {
                $("input[id=location_hide]").eq(eq).val(suggestion.data);
              },
              autoSelectFirst:true
            });
          });

          $(document).on("focus",'input[id=suplier]',function(){
            eq = $("input[id=suplier]").index(this);
            $("input[id=suplier]").eq(eq).devbridgeAutocomplete({ 
              serviceUrl: "{{url('/inventory/get/master/suplier')}}",
              onSelect: function (suggestion) {
                $("input[id=suplier_hide]").eq(eq).val(suggestion.data);
              },
              autoSelectFirst:true
            });
          });

          $(document).on("focus",'input[id=category]',function(){
            eq = $("input[id=category]").index(this);
           $("input[id=category]").eq(eq).devbridgeAutocomplete({ 
              serviceUrl: "{{url('/inventory/get/master/category')}}",
              onSelect: function (suggestion) {
                $("input[id=cat_hide]").eq(eq).val(suggestion.data);
              },
              autoSelectFirst:true
            });
          });

          $(document).on("focus",'input[id=condition]',function(){
            eq = $("input[id=condition]").index(this);
           $("input[id=condition]").eq(eq).devbridgeAutocomplete({ 
              serviceUrl: "{{url('/inventory/get/master/condition')}}",
              onSelect: function (suggestion) {
                $("input[id=condition_hide]").eq(eq).val(suggestion.data);
              },
              autoSelectFirst:true
            });
          });

         

        });



$(document).on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
});




        $("button[id=btn_row]").click(function() {
          no_rkb = $("input[name=no_rkb]").val();
          var values = $("input[name='part_name[]']")
              .map(function(){return $(this).val();}).get();
          //console.log(values);
          //for(i=0; i<len; i++){
            //$("tr[id=last_column]").before(SetTemp(" "));
          //}
          if(no_rkb==""){
         var myWindow = window.open("{{url('/get/rkb/details.popup-html')}}?no_rkb="+no_rkb+"&part_name="+values,"", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=500,width=600,height=400");
          }else{
         var myWindow = window.open("{{url('/get/rkb/details.popup')}}?no_rkb="+no_rkb+"&part_name="+values,"", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=500,width=600,height=400");
          }
        });

        $(".myTable").on("click","button[name=removeRow]",function() {
          eq = $("button[name=removeRow]").index(this);
          $(".table tbody .dynamic_item").eq(eq).remove();
        });

$("button[name=addRKB]").click(function(){ 
  var myWindow = window.open("{{url('/inventory/popup/detRKB')}}","", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=500,width=600,height=400");
});

    function init_autocomplete() {      
      if( typeof ($.fn.autocomplete) === 'undefined'){ return; }
      $('#no_rkb').devbridgeAutocomplete({ 
        serviceUrl: "{{url('/get/nomor/rkb')}}",
        onSelect: function (suggestion) {
                $.ajax({
                    type:"POST",
                    data:{noRKB:suggestion.value},
                    url:"{{url('/get/rkb/details.complate')}}",
                    beforeSend:function(){
                      $(".table tbody .dynamic_item").remove();
                    },
                    success:function(result){
                      $(result).each(function(i){
            $("tr[id=last_column]").before(SetTemp(result[i].part_name));  
                      });
                    }
                });
              },
              onHide:function(){
              },
              autoSelectFirst:true
      });

      $(document).on("focus",'input[id=part_name]',function(){
          var _norkb = $('input[id=no_rkb]').val();
      $('input[id=part_name]').devbridgeAutocomplete({ 
        serviceUrl: "{{url('/get/part/name')}}?no_rkb="+_norkb,
              autoSelectFirst:true
      });
      });

      $(document).on("focus",'input[id=part_number]',function(){
          var _norkb = $('input[id=no_rkb]').val();
          var _part_name = $('input[id=part_name]').val();
      $('input[id=part_number]').devbridgeAutocomplete({
        serviceUrl: "{{url('/get/part/number')}}?no_rkb="+_norkb+"&part_name="+_part_name,
              autoSelectFirst:true
      });
      });

       $(document).on("focus",".datepicker",function(){
              $('.datepicker').datepicker({ dateFormat: 'dd MM yy' });  
});

    };




    init_autocomplete();
    </script>
    @endif