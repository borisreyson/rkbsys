@if(isset($StokOut))
<style>
  .modal-xl{
    width: 100%!important;
    position: relative!important;
    top: 0!important;
    left: 0!important;
    right: 0!important;
  }
   #tglOut {
       /* has to be larger than 1050 */
    }
</style>
@php
$dept = Illuminate\Support\Facades\DB::table('department')->orderBy("id_dept")->get();
$data_karyawan = Illuminate\Support\Facades\DB::table('db_karyawan.data_karyawan')->orderBy("nik")->get();
$user_login = Illuminate\Support\Facades\DB::table('user_login')->where("username",$_SESSION['username'])->first();
@endphp

<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Stock Out</h4>
      </div>
<form class="form-horizontal form-label-left" action="" id="form_stockout" method="post" novalidate>
<div class="modal-body">
{{csrf_field()}}
<input type="hidden" name="_method" value="PUT" >
<div class="container">
  <table class="table table-striped table-bordered" id="stokOUT">
    <thead>
      <tr>
        <th colspan="4" class="text-right item">
          <div class="col-md-3 form-control-static">Nama Penerima</div>
          <div class="col-md-5 row">
        <select id="penerima" class="form-control col-md-7 col-xs-12" data-live-search="true" name="penerima" placeholder="LOCATION" required="required">
          <option value="">--PILIH--</option>
          @foreach($data_karyawan as $k_kar => $v_kar)
          <option value="{{$v_kar->nik}}">({{$v_kar->nik}}) {{$v_kar->nama}}</option>
          @endforeach
        </select>
      </div>
      </th>
      <th colspan="6" class="text-right item">
        <div class="col-md-4 form-control-static">Diterima Dari</div>
          <div class="col-md-5 row">
        <select id="dari" type="text" class="form-control col-md-7 col-xs-12" name="dari" data-live-search="true"  placeholder="Diterima Dari" required="required">
          <option value="" nik="">--PILIH--</option>
          @foreach($data_karyawan as $k_kar => $v_kar)     
          <option nik="{{$v_kar->nik}}" value="{{$v_kar->nama}}">{{$v_kar->nama}}</option>
          @endforeach
        </select>
      </div>
      </th>
      </tr>
      <tr>
      <tr>
        <th colspan="4" class="text-right item">
          <div class="col-md-3 form-control-static">Jabatan</div>
          <div class="col-md-5 row">
        <input id="jabatan" type="text" class="form-control col-md-7 col-xs-12" name="jabatan" placeholder="Jabatan" required="required" >
      </div>
      </th>
      <th  colspan="6" class="text-right item">
          <div class="col-md-4 form-control-static">Jabatan</div>
          <div class="col-md-5 row">
        <input id="jabatan_a" type="text" class="form-control col-md-7 col-xs-12" name="jabatan_a" placeholder="Jabatan" required="required" >
      </div>
      </th>
      </tr>
      <tr>
        <th colspan="4" class="text-right item">
          <div class="col-md-3 form-control-static">Departemen</div>
          <div class="col-md-3 row">
        <select id="dept" class="form-control col-md-7 col-xs-12" data-live-search="true" name="dept" placeholder="Department" required="required" >
          <option value="">--PILIH--</option>
          @foreach($dept as $k => $v)
          @if($v->id_dept!="ALL")
          <option value="{{$v->id_dept}}">{{strtoupper($v->dept)}}</option>
          @endif
          @endforeach
        </select>
      </div>
      </th>

        <th colspan="6" class="text-right item">
          <div class="col-md-4 form-control-static">Tanggal Dikeluarkan</div>
          <div class="col-md-5 row">
        <input id="tglOut" type="text" class="form-control col-md-7 col-xs-12 datepicker" name="tglOut" placeholder="Tanggal Dikeluarkan" required="required" value="{{date('d F Y')}}" readonly="readonly">
      </div>
      </th> 
      </tr>
      <tr>
        <th colspan="4" class="text-right item">
          <div class="col-md-3 form-control-static">Devisi</div>
          <div class="col-md-3 row">
        <input type="text" id="section" class="form-control col-md-7 col-xs-12" name="section" placeholder="Section" >
      </div>
      </th>
        <th colspan="6" class="text-right item">
          <div class="col-md-4 form-control-static">Lokasi </div>
          <div class="col-md-5 row">
        <select id="lokasi" class="form-control col-md-7 col-xs-12" data-live-search="true" name="lokasi" placeholder="LOCATION" required="required">
          <option value="">--PILIH--</option>          
          @foreach($lokasi as $k => $v)
          <option value="{{$v->code_loc}}">{{ucwords($v->location)}}</option>
          @endforeach
        </select>
      </div>
      </th>
      </tr>
      
      
    </thead>
    <thead>
      <tr>
        <th colspan="2" class="text-center" width="300">Item</th>
        <th colspan="2" class="text-center" width="150px">Stock Out</th>
        <th colspan="2" class="text-center" width="120px">Avaliable</th>
        <th colspan="2" class="text-center" width="150">Remarks</th>
        <th colspan="2" class="text-center" width="50">Action</th>
      </tr>
    </thead>
    <tbody>

         <tr id="last_column" class="row_hide">
            <td colspan="6"><span class="form-control-static" id="total"></span></td>
            <td colspan="4">
              <div class="input-group col-md-9 pull-right">
              <span class="staticParent "><input type="number" name="row_add" class="form-control child row_add" value="1" min="1"></span>
              <span class="input-group-addon">Rows</span>
              <div class="input-group-btn">
                <button type="button" id="btn_row" class="btn btn-primary">Add</button>
              </div>
              </div>
            </td>
          </tr>
    </tbody>
  </table>
</div>
</div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>

<!---validator-->
    <script src="{{asset('/vendors/validator/validator.js')}}"></script>
    <!-- jQuery autocomplete -->
    <script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>
    <!-- jQuery Tags Input -->
    <!--<script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>-->
    <script>
      $("#tglOut").focusin(function(){
        $("#tglOut").css("z-index","9999 !important");
      });
      $("#tglOut").focusout(function(){
        $("#tglOut").css("z-index","1 !important");
      });
      var dipilih;
      $("select[id=penerima]").change(function(){
        penerima = $("select[id=penerima]").val();
        $.ajax({
              type:"GET",
              url:"{{url('/data/karyawan')}}",
              data:{nik:penerima},
              success:function(res){
                Jparse = JSON.parse(res);
                $("input[id=jabatan]").val(Jparse.jabatan);
                $("select[id=dept]").val(Jparse.id_dept);
                $("select[id=dept]").selectpicker("refresh");
              }
            });
      });

      $("select[id=dari]").change(function(){
        dari = $("select[id=dari]").find("option:selected").attr("nik");
        $.ajax({
              type:"GET",
              url:"{{url('/data/karyawan')}}",
              data:{nik:dari},
              success:function(res){
                //alert(res);
                Jparse = JSON.parse(res);
                $("input[id=jabatan_a]").val(Jparse.jabatan);
              }
            });
      });

      $("form[id=form_stockout]").ready(function(){
        
        $("select[id=dari] option").each(function(){
           var id= $(this).attr('nik');
          if(id === "{{$user_login->nik}}"){ 
            $(this).prop('selected',true);
            $.ajax({
              type:"GET",
              url:"{{url('/data/karyawan')}}",
              data:{nik:id},
              success:function(res){
                //alert(res);
                Jparse = JSON.parse(res);
                $("input[id=jabatan_a]").val(Jparse.jabatan);
              }
            });
          }
       })
        
      });
      
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

var countable = 1;
        $(document).on("keyup","input[id=stock_out]",function(e){
          eq = $("input[id=stock_out]").index(this);
           var isi = $("input[id=stock_out]").eq(eq).val();
           var myMax = $("input[id=stock_out]").eq(eq).attr("max");
           intISI = parseInt(isi); intmyMax = parseInt(myMax);
           if(intISI>intmyMax){
            $("input[id=stock_out]").eq(eq).val(myMax);
           }else{
            $("input[id=stock_out]").eq(eq).val(isi);
           }
        });

      $('form').submit(function(e) {
        //return false;
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }
        if($("input[id=stock_out]").val()==0){
          submit=false;
          $("input[id=stock_out]").focus();
        }
        if(submit){
          return true;

        }else{
          e.preventDefault();
        }

    });
    
    };
    $("select").selectpicker();
    
    $("#konten_modal").on("focus",".datepicker",function(e){
      
        $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });  
    });
    /* VALIDATOR */
    
    var myTemplate = '<tr class="dynamic_item">'+
        '<td colspan="2" class="item"><select name="item[]" id="item" class="form-control"  data-live-search="true" required>'+
        '<option value="">--PILIH--</option>'+
        '@foreach($item as $kk => $vv)'+
          '<option value="{{$vv->item}}" stok="{{$vv->stock}}" satuan="{{$vv->satuan}}">({{$vv->item}}) {{strtoupper($vv->item_desc)}} {{strtoupper($vv->part_number)}}</option>'+
          '@endforeach'+
          '</select>'+
        '</td>'+
        '<td colspan="2" class="item">'+
          '<div class="input-group"><input type="number" name="stock_out[]" id="stock_out" class="form-control stock_out" value="0" min="1" max="0" required="required">'+
            '<span id="satuan" class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
            '</div>'+
          '</td>'+
        '<td colspan="2" class="item text-center">'+
        '<span id="tersedia">0</span>'+
        '</td>'+
        '<td colspan="2" class="item"><textarea rows="1" class="form-control" name="remark[]" id="remark" placeholder="Remarks" required="required"></textarea></td>'+
        '<td colspan="2" class="text-center">'+
          '<button type="button" class="btn btn-xs btn-danger" name="removeRow" id="removeRow"><i class="fa fa-times"></i></button>'+
        '</td>'+       
      '</tr>';

        $(document).ready(function(){
          $("tr[id=last_column]").before(myTemplate);
          $("select").selectpicker("refresh");
        });

        $("select[id=dept]").on("change",function(){
          isiDept = $("select[id=dept]").val();
          if(isiDept!=""){
            $.ajax({
              type:"POST",
              data: {dept:isiDept},
              url : "{{url('/api/department')}}",
              success:function(result){
               $("select[name=section]").html(result).selectpicker('refresh');

              }

            });
          }else{            
$("select[name=section]").html('<option value="">--PILIH--</option>').selectpicker('refresh');
          }
        });

      var stockMax ;
    $(document).on("focus",'input[id=item]',function(){ 
        eq = $("input[id=item]").index(this);
        $('input[id=item]').eq(eq).devbridgeAutocomplete({ 
          serviceUrl: "{{url('/inventory/get/master/dataStockItem')}}",
          onInvalidateSelection: function() {
              console.log('on invalidate');
          },
          onSelect: function (suggestion) {
            $("span[id=satuan]").eq(eq).html(suggestion.satuan);
            $("input[id=item_hide]").eq(eq).val(suggestion.data);
            $("input[id=stock_out]").eq(eq).attr("max",suggestion.stock);
            $("span[id=tersedia]").eq(eq).text(suggestion.stock);
            
          },
          autoSelectFirst:true,
          onHide: function(suggestion) {
          }
      });
      });


$("#stokOUT").on("change","select[id=item]",function(){
  eq = $("select[id=item]").index(this);
  element = $("select[id=item]").eq(eq).find('option:selected'); 
  myTag = element.attr("stok");
  satuan = element.attr("satuan");
  $("input[id=stock_out]").eq(eq).attr("max",myTag);
  $("span[id=satuan]").eq(eq).html(satuan);
  if(myTag==0){
   $("span[id=tersedia]").eq(eq).html("<font color='red'>"+myTag+"</font>");
  }else{
   $("span[id=tersedia]").eq(eq).html("<font color='green'>"+myTag+"</font>");    
  }
  init_validator();
  
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

        $(document).off("click","button[name=removeRow]");
        $(document).on("click","button[name=removeRow]",function() {
          eq = $("button[name=removeRow]").index(this);
          $(".table tbody .dynamic_item").eq(eq).remove();
        });

    

    init_validator();


        $("button[id=btn_row]").click(function() {
          len = $("input[name=row_add]").val();
          //console.log(len);
          for(i=0; i<len; i++){
            $("tr[id=last_column]").before(myTemplate);
          }
          $("select").selectpicker("refresh");
        });

        
$(document).on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()}); 
});

$("button[id=cariNewTab]").click(function(){
  var myWindow = window.open("{{url('/get/rkb/details.popup-html')}}","", "toolbar=no,scrollbars=yes,resizable=no,top=200,left=500,width=600,height=400");
});

    </script>
@endif