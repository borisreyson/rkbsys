@if(isset($StokIn))
<style>
  .modal-xl{
    width: 100%;
    position: relative!important;
    left: 0!important;
    right: 0!important;
  }
  input{
    z-index: 999;
  }
  @media (max-width: 767px) {
    .table-responsive .dropdown-menu {
        position: static !important;
    }
}
@media (min-width: 768px) {
    .table-responsive {
        overflow: visible;
    }
}
</style>
<div class="modal-content" id="modalKONTEN">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Stock In 
          &nbsp;&nbsp;&nbsp;&nbsp;
          <button class="btn btn-sm btn-default" name="scanqr">Scan QrCode</button></h4>        
      </div>
<form class="form-horizontal form-label-left" action="{{url('/inventory/stok/store')}}" method="post">
<div class="modal-body">
{{csrf_field()}}
@if(isset($editSuplier))
<input type="hidden" name="_method" value="PUT" >
<input type="hidden" name="data_id" value="{{$data_id}}" >
@endif
<div class="table-responsive">
      <table class="table table-bordered table-striped myTable" >
      <thead>
        <tr>
          <th class="text-center" width="150px">Kode Barang</th>
          <th class="text-center" width="150px">No RKB</th>
          <th class="text-center" width="150px">Part Name</th>
          <th class="text-center" width="150px">Part Number</th>
          <th class="text-center" width="200">Stok Masuk</th>
          <th class="text-center" width="150px">Lokasi</th>
          <th class="text-center" width="150px">Kondisi</th>
          <th class="text-center" width="150px">Vendor</th>
          <th class="text-center" width="150px">Keterangan</th>
          <th class="text-center" width="150px">Tgl Diterima</th>
          <th class="text-center" width="50">Aksi</th>
        </tr>
      </thead>
      <tbody>
         <tr id="last_column" class="row_hide">
            <td><span class="form-control-static" id="total"></span></td>
            
            <td colspan="11">
                <button type="button" id="btn_row" class="btn btn-primary pull-right">Add</button>
                <div style="width: 80px!important;padding-right: 10px;" class=" pull-right" ><input type="number" name="rowTot" class="form-control " value="1" min="1"></div>
            </td>
          </tr>
      </tbody>
    </table>
    </div>
</div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
        <button type="button" class="btn btn-default " id="close" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>

<!---validator-->
<script src="{{asset('/vendors/validator/validator.js')}}"></script>
    <script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>
    <!-- jQuery Tags Input -->
    <!--<script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>-->
    <script>
      function childFunc(val){
        $("select[id=item] option:selected").val(val);
        console.log(val);
        $("select").selectpicker("refresh");
      }
      $("button[name=scanqr]").click(function(){
        window.open("{{url('/QRSCAN/')}}", "MsgWindow", "width=300,height=300,top=300,left=600");
      });
      $(document).ready(function() {
        $("tr[id=last_column]").before(SetTemp());
         $("select").selectpicker();
      });
      $(document).on("click","#close",function(){
        $("konten_modal").html("");
      });
      /*
      $(document).on("focus",'input[id=item]',function(){
            eq = $("input[id=item]").index(this);
            $("input[id=item]").eq(eq).devbridgeAutocomplete({ 
              serviceUrl: "{{url('/inventory/get/master/item')}}",
              onSelect: function (suggestion) {
                //$("input[id=location_hide]").eq(eq).val(suggestion.data);

                $("span[id=satuan]").eq(eq).html(suggestion.satuan);
                $("input[id=part_name]").eq(eq).val(suggestion.data);
              },
              autoSelectFirst:true,
              zIndex:999
            });
          });
          */
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
          console.log(e);
          e.preventDefault();
        }

    });
    
    };
        function SetTemp(){
          //init_validator();
        return '<tr class="dynamic_item">'+
          '<td><select name="item[]" id="item" class="form-control"  data-live-search="true" required>'+
          '<option value="">--PILIH--</option>'+
          '@foreach($master as $kk => $vv)'+
          '<option value="{{$vv->item}}">({{$vv->item}}) {{$vv->item_desc}} {{$vv->part_number}}</option>'+
          '@endforeach'+
          '</select></td>'+
          '<td><select name="no_rkb[]" id="no_rkb" class="form-control"  data-live-search="true" required>'+
          '<option value="">--PILIH--</option>'+
          '<td>'+
          '  <input type="text" name="part_name[]" id="part_name" style="height:41px;" class="form-control" required readonly>'+
          '</td>'+
          '<td>'+
          ' <input type="text" name="part_number[]" id="part_number" style="height:41px;" class="form-control" required readonly>'+
          '</td>'+
          '<td>'+
          '<span class="col-md-12 row">'+
          "<div class=\"input-group col-md-12 \" role=\"group\">"+
          '<input type="number" name="stok[]" id="stok" min="1" value="0" style="height:41px;width:100%!important;" class="form-control child" required>'+
           '<span id="satuan" class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
          "</div>"+
          '</span>'+
          '</td>'+
          '<td>'+
          '<select name="lokasi[]" id="lokasi" class="form-control" data-live-search="true" required>'+
          '<option value="">--PILIH--</option>'+
          '@foreach($lokasi as $kL => $vL)'+
          '<option value="{{$vL->code_loc}}">{{strtoupper($vL->location)}}</option>'+
          '@endforeach'+
          '</select>'+
          '</td>'+
          '<td>'+
          '<select name="kondisi[]" id="kondisi" class="form-control" data-live-search="true" required>'+
          '<option value="">--PILIH--</option>'+
          '@foreach($kondisi as $kK => $vK)'+
          '<option value="{{$vK->code}}">{{$vK->code_desc}}</option>'+
          '@endforeach'+
          '</td>'+
          '<td>'+
          '<select name="vendor[]" id="vendor" class="form-control" data-live-search="true">'+
          '<option value="">--PILIH--</option>'+
          '@foreach($vendor as $kV => $vV)'+
          '<option value="{{$vV->nama_supplier}}">{{strtoupper($vV->nama_supplier)}}</option>'+
          '@endforeach'+
          '</td>'+
          '<td><textarea name="desk[]" id="desk" class="form-control"></textarea></td>'+
          '<td><input type="text" name="tgl[]" class="form-control datepicker" value="{{date("d F Y")}}"></td>'+
          '<td class="text-center" style="vertical-align: middle;"><button type="button" name="removeRow" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>'+
          '</tr>'}

$("#modalKONTEN").on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[190,46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
});

    $(".myTable").on("click","button[name=removeRow]",function() {
          eq = $("button[name=removeRow]").index(this);
          $(".table tbody .dynamic_item").eq(eq).remove();
        });
    $("select").selectpicker();
    $("button[id=btn_row]").click(function(){
      rowTot = $("input[name=rowTot]").val();
      for(z=0; z<rowTot; z++){
        $("tr[id=last_column]").before(SetTemp()); 
      }
      $("select").selectpicker("refresh");
    });
    $("#modalKONTEN").on("focus",".datepicker",function(e){
        $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });  
    });

    var dipilih;

    
    /* VALIDATOR */


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
$("#modalKONTEN").on("click","input[id=part_name]",function(){
  eq = $("input[id=part_name]").index(this);
  $("input[id=no_rkb]").eq(eq).click();
});
$("#modalKONTEN").on("click","input[id=part_number]",function(){
  eq = $("input[id=part_number]").index(this);
  $("input[id=no_rkb]").eq(eq).click();
});
$("#modalKONTEN").on("click","input[id=no_rkb]",function(){
  eq = $("input[id=no_rkb]").index(this);
  var myWindow = window.open("{{url('/inventory/stok/masuk/list-')}}"+eq,"", "toolbar=no,scrollbars=yes,resizable=no,width="+(window.screen.availWidth)+",height="+(window.screen.availHeight));
});
$("#modalKONTEN").on("change","select[id=item]",function(){
  eq = $("select[id=item]").index(this);
  item = $("select[id=item]").eq(eq).val();
  $.ajax({
    type:"GET",
    url:"{{url('/inventory/master/item/list-')}}"+eq+"?item="+item,
    success:function(res){
      $("input[id=part_name]").eq(eq).val(res.part_name);
      $("input[id=part_number]").eq(eq).val(res.part_number);
      var listItems = '<option value="">--PILIH--</option>';
      $.each( res.stok, function( key, value ) {        
        listItems += "<option value='" + value.no_rkb + "' quantity='"+value.quantity+"' satuan='"+value.satuan+"'>" + value.no_rkb + " ("+value.quantity+") "+value.satuan+"</option>";
      });
      $("select[id=no_rkb]").eq(eq).html(listItems);
      $("select").selectpicker("refresh");

    }
  });


});
$("#modalKONTEN").on("change","select[id=no_rkb]",function(){
eq = $("select[id=no_rkb]").index(this);
  item = $("select[id=no_rkb]").eq(eq).find(":selected").attr('quantity');
  no_rkb = $("select[id=no_rkb]").eq(eq).val();
  element = $("select[id=no_rkb]").eq(eq).find('option:selected');
  satuan = element.attr("satuan");
  part_name = $("input[id=part_name]").eq(eq).val();
  console.log(satuan);
  $.ajax({
    type:"GET",
    url :"{{url('/inventory/compare/quantity')}}",
    data:{quantity:item,no_rkb:no_rkb,part_name:part_name},
    success:function(res){
      $("input[id=stok]").eq(eq).attr("max",res).val("0");
      
      $("span[id=satuan]").eq(eq).html(satuan);
    }
  });
    
});
/*
$("#modalKONTEN").on("click","input[id=item]",function(){
  eq = $("input[id=item]").index(this);
  var myWindow = window.open("{{url('/inventory/master/item/list-')}}"+eq,"", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=350,width=800,height=400");
});
*/
$("#modalKONTEN").on("click","input[id=lokasi]",function(){
  eq = $("input[id=lokasi]").index(this);
  var myWindow = window.open("{{url('/inventory/master/lokasi/list-')}}"+eq,"", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=350,width=800,height=400");
});
$("#modalKONTEN").on("click","input[id=vendor]",function(){
  eq = $("input[id=vendor]").index(this);
  var myWindow = window.open("{{url('/inventory/master/vendor/list-')}}"+eq,"", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=350,width=800,height=400");
});
$("#modalKONTEN").on("click","input[id=kondisi]",function(){
  eq = $("input[id=kondisi]").index(this);
  var myWindow = window.open("{{url('/inventory/master/kondisi/list-')}}"+eq,"", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=350,width=800,height=400");
});


function setItem(item,satuan,eqParent){
  $("input[id=item]").eq(eqParent).val(item);
  $("span[id=satuan]").eq(eqParent).text(satuan);
}
function setRKBdet(norkb,partname,partnumber,quantity,eqParent){
  $("input[id=no_rkb]").eq(eqParent).val(norkb);
  $("input[id=part_name]").eq(eqParent).val(partname);
  $("input[id=part_number]").eq(eqParent).val(partnumber);
  $("input[id=quantity]").eq(eqParent).val(quantity);
}
function setLokasi(lokasi,eqParent){
  $("input[id=lokasi]").eq(eqParent).val(lokasi);
}

function setVendor(Vendor,eqParent){
  $("input[id=vendor]").eq(eqParent).val(Vendor);
}
function setKondisi(kondisi,eqParent){
  $("input[id=kondisi]").eq(eqParent).val(kondisi);
}


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

//    
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
                <button type="button" id="btn_new" class="btn btn-primary pull-right">Add</button>
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
              '<input type="text" name="part_name[]" id="part_name" class="form-control" required value="'+partName+'">'+
              '<input type="hidden" name="part_name_c[]" id="part_name_c" class="form-control" value="'+partName+'">'+
            '</div>'+
          '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<input type="text" name="item[]" id="item" class="form-control" required>'+
              '<input type="hidden" name="item_hide[]" id="item_hide" class="form-control">'+
            '</div>'+
          '</td>'+
          '<td>'+
            '<div class="item form-group">'+
            '<div class="input-group">'+
              '<span class="staticParent "><input type="number" name="stock_in[]" id="stock_in" class="form-control child stock_in" value="1" min="1"  required></span>'+
              '<span id="satuan" class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
            '</div>'+
            '</div>'+
        '</td>'+
          '<td>'+
           '<div class="item form-group">'+
              '<input type="hidden" name="location_hide[]" id="location_hide" class="form-control">'+
              '<input type="text" name="location[]" id="location" class="form-control" required>'+
            '</div>'+
        '</td>'+
          '<td>'+
           '<div class="item form-group">'+
              '<input type="hidden" name="condition_hide[]" id="condition_hide" class="form-control">'+
              '<input type="text" name="condition[]" id="condition" class="form-control" required>'+
            '</div>'+
        '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<input type="hidden" name="suplier_hide[]" id="suplier_hide" class="form-control">'+
              '<input type="text" name="suplier[]" id="suplier" class="form-control" required>'+
            '</div>'+
        '</td>'+
          '<td>'+
            '<div class="item form-group">'+
              '<textarea name="remarks[]" id="remarks" class="form-control" rows="1" required></textarea>'+
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