@extends('layout.master')
@section('title')
ABP-system | Stock
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;
  overflow-y: auto;
  /* prevent horizontal scrollbar */
  overflow-x: hidden;
  }  

.ck-editor__editable {
    min-height: 90px;
}
 .modal-xl{
  position: relative!important;
  left: 0!important;
  right: 0!important;
  margin-top: 50px;
 }

 .myButtonDiss{
  background-color: transparent;
  border: 0px;
  position: absolute;
  z-index: 999;
  font-size: 25px;
  right: 50px;
 }
</style>
@endsection
@section('content')
@php
function rupiah($angka){
  
  $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
  return $hasil_rupiah;
 
}

@endphp
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<!-- page content -->

<div class="right_col" role="main">
  <div class="col-lg-12">
    <br>
    <a href="{{url('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    <a href="{{url('/masteritem/request')}}">Form Request Master Item</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>From Request Master Item</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">          
  <div class="row">
    <div class="col-xs-12">

  <form class="form-horizontal form-label-left" action="" method="post" id="rkb_form" >
    {{csrf_field()}}
<table class="table table-striped table-bordered" id="modalKONTEN">
  <thead>
    <tr class="text-center">
      <th class="text-center" width="200px">
        Part Name
      </th>
      <th class="text-center" width="200px">
        Part Number
      </th>
      <th class="text-center" width="110px">
        Satuan
      </th>
      <th class="text-center" width="110px">
        Kategori
      </th>
      <th class="text-center" width="90px">
        Stock Minimum
      </th>
      <th class="text-center" width="60px">
        Aksi
      </th>
    </tr>
  </thead>
  <tbody>
    <tr id="new_row" class="row_hide">
      <td><span class="form-control-static" id="total"></span></td>
      <td colspan="3"></td>
      <td colspan="2">
        <div class="input-group">
        <span class="staticParent"><input type="number" name="row_add" class="form-control child row_add" value="1" min="1"></span>
        <span class="input-group-addon">Rows</span>
        <div class="input-group-btn">
          <button type="button" id="btn_row" class="btn btn-primary">Add</button>
        </div>
        </div>
      </td>
    </tr>
    <tr class="row_hide">
      <td colspan="6">
        <button type="submit" name="submit" id="kirimData" class="btn btn-primary pull-right">Submit</button>
        <a href="{{url('/masteritem/request/detail')}}" name="cancel" id="cancel" class="btn btn-danger pull-right">Cancel</a>
      </td>
    </tr>
  </tbody>
</table>

</form>
    </div>
  </div>

    </div>
    </div>
    </div>
    </div>
    </div>



@include('layout.footer')
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
<div id="konten_modal"></div>
  </div>
</div>

</div>
@endsection

@section('js')
<!-- Datatables -->
    <!-- FastClick -->


@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
<script>
  var curDate = "<?php echo date('d F Y');?>";
  var i=0;
  var arr_tmp_id = [];
  var arr_name = [];
  var rowLen;
  var z= false;
  var y = false;
  var def_img;
  var mybool = 0;
  var lok=null;
/*
  $("form").submit(function(){
  $(window).unbind('beforeunload');
});

$("form[id=rkb_form]").submit(function(){
  //$("button[id=kirimData]").attr("disabled","disabled");
});
*/
$(window).on('load',function(){
        //$("tr[id=new_row]").hide();
        $(".row_hide").hide();
        KONTENT_MODAL();
        $('#myModal').modal('show');
    });
  $('#myModal').on('hidden.bs.modal', function (e) { 
    rowLen = $(".table tbody tr").length;

    if(rowLen==2){
      $('#myModal').modal('show');
    }
    z=true;
  });
    $(document).on("click","button[id=btn_row]",function(){
  eq = $("button[id=btn_row]").index(this);
  
  
  var newRows = $("input[name=row_add]").eq(eq).val();
  for(i=0; i<newRows; i++){
  var id_rand = Math.random().toString(36).substr(2, 9);
  arr_tmp_id.push(id_rand);
  $("tr[id=new_row]").before(SetTemp());
  }
  $("select").selectpicker();

   rowLen = $(".table tbody tr").length; 
   //alert(rowLen);
   if(i==newRows){
      $("span[id=total]").html("Total "+(rowLen-2)+" Rows");
      $('#myModal').modal('hide');
      $("input[name=row_add]").eq(eq).val('1');
        //$("tr[id=new_row]").fadeIn();
        $(".row_hide").fadeIn();
    //notif();
    // $('#myModal').modal('show');
    }
});
$(document).on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[190,46,8,9,27,13,110,188])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
});
$(document).on("focus",".datepicker",function(e){
        $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });  
    });
  $(document).on("click","button[id=remove]",function(){
      eq = $("button[id=remove]").index(this);
      $(".table tbody tr").eq(eq).remove();
      rowLen = $(".table tbody tr").length;
      $("span[id=total]").html("Total "+(rowLen-2)+" Rows");
      if(rowLen==2){
        $(".row_hide").hide();      
        KONTENT_MODAL();  
        $('#myModal').modal('show');
      }
    });
    $('.quantity').keyup(function () {     
      this.value = this.value.replace(/[^1-9\.]/g,'');
    });

    function KONTENT_MODAL() {

      $(".modal-dialog").removeClass('modal-lg').addClass('modal-sm');
      $("#konten_modal").html('<div class="modal-content">'+
                                '<div class="modal-header">'+
                                '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
                                '<h4 class="modal-title">Number of rows</h4>'+
                                '</div>'+
                                '<div class="modal-body">'+
                                '<div class="row">'+
                                '<div class="col-sm-4">'+
                                '<span class="staticParent"><input type="number" name="row_add" class="form-control child row_add first_focus" value="1" min="1" autofocus></span>'+
                                '</div>'+
                                '<div class="col-sm-2">'+
                                '<div class="form-control-static">Rows</div>'+
                                '</div>'+
                                '<div class="col-sm-6">'+
                                '<button type="button" id="btn_row" class="btn btn-primary pull-right">Add</button>'+

                                '</div>'+
                                '<div class="row>">'+
                                '<div class="col-sm-12 text-center">'+
                                "<a href=\"{{url('/masteritem/request/detail')}}\" name=\"cancel\" id=\"cancel\" class=\"btn btn-danger\">Cancel</a>"+
                                '</div>'+
                                '</div>'+
                                '</div>'+
                                '</div>');
    }

    function SetTemp(){
          //init_validator();
        return '<tr class="dynamic_item">'+
          '<td><input type="text" name="part_name[]" style="height:41px;" id="part_name" class="form-control" required="required"/>'+ 
          '</td>'+
          '<td><input type="text" style="height:41px;" name="part_number[]" id="part_number" class="form-control" required="required"/>'+
          '<td>'+
          '<select name="satuan[]" id="satuan" class="form-control" data-live-search="true" required="required">'+
          '<option value="">--PILIH--</option>'+
          '@foreach($satuan as $kL => $vL)'+
          '<option value="{{$vL->satuannya}}">{{strtoupper($vL->satuannya)}}</option>'+
          '@endforeach'+
          '</select>'+
          '</td>'+
          '<td>'+
          '<select name="kategori[]" id="kategori" class="form-control" data-live-search="true" required="required">'+
          '<option value="">--PILIH--</option>'+
          '@foreach($kategori as $kL => $vL)'+
          '<option value="{{$vL->CodeCat}}">{{strtoupper($vL->DeskCat)}}</option>'+
          '@endforeach'+
          '</select>'+
          '</td>'+
          '<td>'+
          "<span class=\"staticParent\">"+
          '<input type="number" name="stok[]" id="stok" min="1" value="0" style="height:41px;" class="form-control child" required="required"/></span>'+
          '</td>'+
          '<td class="text-center" style="vertical-align: middle;"><button type="button" name="removeRow" id="remove" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>'+
          '</tr>'}

</script>
@if(session('success'))
  <script>
    setTimeout(function(){
new PNotify({
          title: 'Success',
          text: "{{session('success')}}",
          type: 'success',
          hide: true,
          styling: 'bootstrap3'
      });
    },500);
  </script>
@endif
@if(session('failed'))
@foreach(session('failed') as $k )

  <script>
    setTimeout(function(){
new PNotify({
          title: 'Failed',
          text: "{{$k->part_name}} {{$k->part_number}} : {{$k->status}}",
          type: 'error',
          hide: true,
          styling: 'bootstrap3'
      });
    },500);
  </script>
  @endforeach

@endif
@endsection