@extends('layout.master')
@section('title')
ABP-system | Form Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
 <!-- Datatables -->
    <link href="{{asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/dropzone.css')}}">
    <style>
      .dz-preview{
      z-index: 1!important;
    }
    .centered:hover,.centered i:hover{
      cursor: pointer!important;
      cursor: hand!important;
    }
    .labeling{
      position: absolute;
      top: 10px;
      height: 30px;
      line-height: 30px;
      border: dotted thin #000333;
      background-color: white;
      right: 50px;
      padding-left: 15px;
      padding-right: 15px;

    }
  .table th, .table td {
    text-align: center!important;
  }
  center{
    padding-bottom: 15px;
  }
  .table th{
    background-color: rgba(29,25,255,0.6);
    color: #fff; 
  }
  img{
      height: 100%;
    }
#delete_img{
    position: absolute;
    right: -5px;
    top: -5px;
    opacity: 0;
  }
  #select_img{
    position: absolute;
    left: -15px;
    top: -5px;
    width: 40px;
    padding: 0!important;
    margin: 0!important;
    text-align: center;
    opacity: 0;
  }
   .actived{
    position: absolute;
    left: -15px;
    top: -5px;
    width: 40px;
    padding: 0!important;
    margin: 0!important;
    text-align: center;
    opacity: 1;
    z-index: 100;
  }
  .actived .tooltip-inner,#select_img .tooltip-inner,div[data-hover=img] .tooltip-inner{
    z-index: 10;
    width: 500px;
  }
  .count_img{
    position: absolute;
    right: 25px;
    top: -5px;
    height: 30px;
    padding: 10px!important;
    margin: 0!important;
    text-align: center;
    vertical-align: middle;
    line-height: 10px;
    border: dotted thin #333;
    background-color: white;
  }
  div[data-hover=img]{
    margin-right: 15px; 
  }
  div[data-hover=img]:hover #delete_img,div[data-hover=img]:hover #select_img{
    opacity: 1;
    transition: all  0.5s;
  }

    .dropzone {
      margin-top: 10px!important;
      margin-bottom: 20px!important;
      border: dotted thin #000333;
    }

    .dz-preview{
      z-index: 1!important;
    }
</style>
@endsection
@section('content')

<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav')
@include('layout.top')
<div class="right_col" role="main">
  <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Form <small>Rencana Kebutuhan Barang</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div class="col-xs-12 text-center">
                     <div class="row"> 
<form class="form-horizontal form-label-left" method="post" name="rkb_form" id="rkb_form" enctype="multipart/form-data" action="/v1/rkb/update" >
  {{csrf_field()}}
                      <table class="table">
                        <thead>
                          <tr>
                            <th width="250px">Part Name</th>
                            <th width="200px">Part Number</th>
                            <th width="200px">Quantity</th>
                            <th width="130px">Due Date</th>
                            <th width="250px">Remark</th>
                            <th width="100px">Sample</th>
                            <th width="50px">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                         <tr id="new_row" class="row_hide">
                            <td><span class="form-control-static" id="total"></span></td>
                            <td colspan="4"></td>
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
                            <td colspan="6"></td>
                            <td>
                              <button type="submit" name="submit" class="btn btn-default pull-right">Submit</button>
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
</div>

@include('layout.footer')


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
<div id="konten_modal"></div>
  </div>
</div>
@endsection
@section('js')
@include('layout.js')
<script src="{{asset('/js/dropzone.js')}}"></script>
<script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>   
<?php $op = Illuminate\Support\Facades\DB::table('satuan')->get(); ?>
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
  $(window).on("beforeunload", function(e){
    
    if(z==true){
      fast_delete(arr_tmp_id);
      z=false; 
    }
    return 'Apakah anda ingin meninggalkan halaman ini?';     
});
*/
$(document).ready(function() {
  $("select").selectpicker();
});
$("form").submit(function(){
  $(window).unbind('beforeunload');
});
  $('#myModal').on('hidden.bs.modal', function (e) { 
    rowLen = $(".table tbody tr").length;
    if(rowLen==2){
      $('#myModal').modal('show');
    }
    z=true;
  });

$("form[id=rkb_form]").submit(function(){
  $("button[name=submit]").attr("disabled","disabled");
});
$(document).on("click","button[id=btn_row]",function(){
  
  eq = $("button[id=btn_row]").index(this);
  var newRows = $("input[name=row_add]").eq(eq).val();
  for(i=0; i<newRows; i++){
  var id_rand = Math.random().toString(36).substr(2, 9);
  arr_tmp_id.push(id_rand);
  $("tr[id=new_row]").before("<tr>"+
               "<td>"+
               "<input type=\"hidden\" name=\"id_tmp[]\" id=\"id_tmp\" class=\"form-control id_tmp\" value=\""+id_rand+"\" required>"+
               "<select name=\"part_name[]\" data-live-search=\"true\" id=\"part_name\" class=\"form-control\" placeholder=\"Part Name\" required>"+
               "<option value=\"\">--PLILIH--</option>"+
               "@foreach($master as $kM => $vM)"+
               "<option value=\"{{$vM->item}}\">{{$vM->item_desc.' '.$vM->part_number}}</option>"+
               "@endforeach"+
               "</select>"+
               "</td>"+
               "<td><input type=\"text\" name=\"part_number[]\" id=\"part_number\" style=\"height:41px!important;\" class=\"form-control\" placeholder=\"Part Number\" readonly>"+               
               "<input type=\"text\" name=\"item[]\" id=\"item\" class=\"form-control\" placeholder=\"Item\" style=\"height:41px!important;\" value=\"\" readonly>"+
               "</td>"+
               "<td>"+
               "<div class=\"col-xs-6\" role=\"group\">"+
               "<span class=\"staticParent\">"+
               "<input type=\"number\" min=\"1\" step=\"0.1\" style=\"width:65px;height:41px!important;\" name=\"quantity[]\" class=\"form-control child\" value=\"1\"></span>"+
               "</div>"+
               "<div class=\"col-xs-6 row\" style=\"margin:0px!important;padding:0px!important;\">"+
               "<input type=\"text\" name=\"satuan[]\" id=\"satuan\" style=\"height:41px!important;\"  class=\"form-control\" readonly=\"readonly\" required>"+
               "</div>"+
               "</td>"+
               "<td align=\"right\"><input type=\"text\" name=\"due_date[]\" class=\"form-control datepicker\" placeholder=\"Due Date\" readonly=\"readonly\" value=\""+curDate+"\"></td>"+
               "<td><textarea rows=\"1\" name=\"remark[]\" class=\"form-control\" placeholder=\"Remark\"></textarea></td>"+
               "<td><button type=\"button\" id=\"attch_btn\" class=\"btn btn-default\" style=\"border:0px!important;\" title=\"Attach File\" ><i class=\"fa fa-2x fa-paperclip\"></i></button></td>"+
               "<td><div class=\"form-control-static\"><button type=\"button\" id=\"remove\" class=\"btn btn-xs btn-danger remove\"><i class=\"fa fa-times\"></i></button></div></td>"+
               "</tr>");
  $("select").selectpicker("refresh");
  }

   rowLen = $(".table tbody tr").length; 

   if(i==newRows){
      $("span[id=total]").html("Total "+(rowLen-2)+" Rows");
      $('#myModal').modal('hide');
      $("input[name=row_add]").eq(eq).val('1');
        //$("tr[id=new_row]").fadeIn();
        $(".row_hide").fadeIn();
    }

});

$(document).on("click","button[id=attch_btn]",function(){
  arr_name = [];
  eq = $("button[id=attch_btn]").index(this);
  tmp_id = $("input[id=id_tmp]").eq(eq).val();
  $(".modal-dialog").removeClass('modal-sm').addClass('modal-lg');
  $("#myModal").modal("show");
  $("#konten_modal").html('<div class="modal-content">'+
           '<div class="modal-header">'+
            '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
            '<h4 class="modal-title">Sample</h4>'+
            '</div>'+
            '<div class="modal-body">'+
            "<form action=\"{{asset('/v1/file-upload')}}\" class=\"form-horizontal dropzone\" id=\"myAwesomeDropzone\" data-tooltip=\"tooltip\" title=\"Maksimal yang di unggah adalah 9\">'"+
            '<span class="labeling"><font color="red" id="curent_img">0</font>/9 File</span>'+
            '{{csrf_field()}}'+
            '<input type="hidden" name="oriname" value="'+tmp_id+'">'+
            '<div class="fallback">'+
            '<input name="file" type="file" multiple />'+
            '</div>'+
            '</form>'+
            '</div>'+
            '</div>');
  
  Dropzone.options.myAwesomeDropzone = {
        maxFilesize: 10,
        maxFiles: 9 ,
        dictDefaultMessage: "<button type=\"button\" class=\"btn btn-sm btn-success\">Pilih</button> <label>atau tarik dan letakkan di sini untuk mengupload</label>\n <br/><br/>\n <ul class=\"ul_message\" style=\"text-align:left; vertical-align:middle;\"> <li> Sampai 9 File, masing-masing maksimal 10 MB.</li>\n </ul>",
        previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n     </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n \n <center>&nbsp;<a href=\"javascript:void(0)\" class=\"btn btn-xs btn-danger centered\" id=\"deleted\" data-dz-remove > <i class=\"fa fa-trash\"></i></a></center>\n          </div>",
       // acceptedFiles:"*",
         init: function() {
          thisDropzone = this;

$.getJSON("/v1/recent-image-"+tmp_id, function(dataku) {
$.each(dataku, function(key,value)
                    {
                      var mockFile = { name: value.name, size: value.size, customName:value.name};
                      thisDropzone.files.push(mockFile);
                      thisDropzone.emit("addedfile",mockFile);
                      thisDropzone.emit("thumbnail",mockFile,value.url);
                      //thisDropzone.createThumbnailFromUrl(mockFile,value.url);
                      arr_name.push(value.name);
                      thisDropzone.emit("complete", mockFile);
                      });

var existingFileCount  = dataku.length;
thisDropzone.options.maxFiles = thisDropzone.options.maxFiles - existingFileCount;
$("font[id=curent_img]").text(arr_name.length);
                              
});

                            this.on("success", function(file,respone) {
                              results = JSON.parse(respone);
                              arr_name.push(results.name);
                              file.customName = results.name;
                              z=true;
                              
                              $("font[id=curent_img]").text(arr_name.length);
                            });
                            this.on("removedfile", function(file,respone) { 
                              newarray = [file.customName];
                              arr_name = arr_name.filter(function(item) { 
                                  return item !== file.customName
                              });
                              soft_delete(newarray);
                              $("font[id=curent_img]").html(arr_name.length);

                              z=true;
                            });
                            this.on("maxfilesexceeded", function(file,respone) { 
                              this.removeFile(file);
                            });
/*                            this.on("queuecomplete", function() {
                              if(arr_name.length > 0){
                                $("form[id=produk]").slideToggle();
                                $("div[id=label_produk]").fadeIn();
                                                      }
                            });*/
         }
       };
       $("#myAwesomeDropzone").dropzone();
});
function soft_delete(vars){
        $.ajax({
                type:"POST",
                data:{_token:"{{csrf_token()}}",token_:vars},
                url:"{{asset('/v1/soft-delete')}}",
                success:function(result){
                }
               });
}
function fast_delete(varID){
  $.ajax({
    type:"POST",
    data:{_token:"{{csrf_token()}}",keyID_:varID},
    url:"{{url('/v1/fast-delete')}}",
    success:function(result){
      console.log(result);
    }
  });
}
$(document).on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
});
    $(document).on("focus",".datepicker",function(e){
        $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });  
    });
    
    $(document).on("click","button[id=remove]",function(){
      eq = $("button[id=remove]").index(this);
      id_tmp = $("input[id=id_tmp]").eq(eq).val();
      part_name = $(".part_name").eq(eq).val();
      if(part_name!=""){
      $.post("{{url('/api/delete/entry')}}",{_token:"{{csrf_token()}}",id_rkb:id_tmp},function(result){
        if(result=="OK"){
          $(".table tbody tr").eq(eq).remove();
          rowLen = $(".table tbody tr").length;
          $("span[id=total]").html("Total "+(rowLen-2)+" Rows");
          if(rowLen==2){
            $(".row_hide").hide();      
            KONTENT_MODAL();  
            $('#myModal').modal('show');
          }
           notif("success","Success","Delete Row Success!");
        }else{
           notif("error","Failed","Delete Row Failed!");
        }
      });
      }else{
        $(".table tbody tr").eq(eq).remove();
          rowLen = $(".table tbody tr").length;
          $("span[id=total]").html("Total "+(rowLen-2)+" Rows");
          if(rowLen==2){
            $(".row_hide").hide();      
            KONTENT_MODAL();  
            $('#myModal').modal('show');
          }
      }
      
    });
function notif(tipe,title,val){
new PNotify({
      title: title,
      text: val,
      type: tipe,
      hide: true,
      styling: 'bootstrap3'
  });
}
    $('.quantity').keyup(function () {     
      this.value = this.value.replace(/[^1-9\.]/g,'');
    });

    $(window).on('load',function(){

        //$("tr[id=new_row]").hide();
        //$(".row_hide").hide();
        //KONTENT_MODAL();
        //$('#myModal').modal('show');
        loadData();
          rowLen = $(".table tbody tr").length;
          $("span[id=total]").html("Total "+(rowLen-2)+" Rows");
    });
    function loadData() {

      <?php 
        foreach($tmp_rkb as $k => $v){
      ?>
      //console.log("{{$v->quantity}}");
      $("tr[id=new_row]").before("<tr>"+
               "<td>"+
               "<input type=\"hidden\" name=\"id_tmp[]\" id=\"id_tmp\" class=\"form-control id_tmp\" value=\"{{$v->id_rkb}}\" required>"+
               "<select name=\"part_name[]\" data-live-search=\"true\" id=\"part_name\" class=\"form-control\" placeholder=\"Part Name\" required>"+
               "<option value=\"\">--PILIH--</option>"+
               "@foreach($master as $kk =>$vv)"+
               "@if($v->item==$vv->item)"+
               "<option value=\"{{$vv->item}}\" selected=\"selected\">{{($vv->item_desc)}} {{$vv->part_number}}</option>"+
               "@else"+
               "<option value=\"{{$vv->item}}\">{{($vv->item_desc)}} {{($vv->part_number)}}</option>"+
               "@endif"+
               "@endforeach"+
               "</select>"+
               "</td>"+
               "<td><input type=\"text\" name=\"part_number[]\" id=\"part_number\" style=\"height:41px!important;\" class=\"form-control\" placeholder=\"Part Number\" value=\"{{($v->part_number)}}\" readonly>"+
               "<input type=\"text\" name=\"item[]\" id=\"item\" class=\"form-control\" placeholder=\"Item\" style=\"height:41px!important;\" value=\"{{($v->part_name)}}\" readonly>"+
               "</td>"+
               "<td>"+
               "<div class=\"col-xs-6\" role=\"group\">"+
               "<span class=\"staticParent\">"+
               "<input type=\"number\" min=\"1\" step=\"0.1\" style=\"width:65px;height:41px!important;\" name=\"quantity[]\" class=\"form-control child\"  value=\"{{($v->quantity)}}\"></span>"+
               "</div>"+
               "<div class=\"col-xs-6 row\" style=\"margin:0px!important;padding:0px!important;\">"+
               "<input type=\"text\" name=\"satuan[]\" id=\"satuan\" style=\"height:41px!important;\"  class=\"form-control\" readonly=\"readonly\"  value=\"{{($v->satuan)}}\" required>"+
               "</div>"+
               "</td>"+
               "<td align=\"right\"><input type=\"text\" name=\"due_date[]\" class=\"form-control datepicker\" placeholder=\"Due Date\" readonly=\"readonly\" value=\"{{(date('d F Y ',strtotime($v->due_date)))}}\"></td>"+
               "<td><textarea rows=\"1\" name=\"remark[]\" class=\"form-control\" placeholder=\"Remark\">{{($v->remarks)}}</textarea></td>"+
               "<td><button type=\"button\" id=\"attch_btn\" class=\"btn btn-default\" style=\"border:0px!important;\" title=\"Attach File\" ><i class=\"fa fa-2x fa-paperclip\"></i></button></td>"+
               "<td><div class=\"form-control-static\"><button type=\"button\" id=\"remove\" class=\"btn btn-xs btn-danger remove\"><i class=\"fa fa-times\"></i></button></div></td>"+
               "</tr>");
      <?php } ?>
      $('select').selectpicker();
    }
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
                                '</div>'+
                                '</div>');
    }

    $(document).on("keydown",".row_add",function(e){
      eq = $(".row_add").index(this);
                if(e.keyCode==13){
                  $("button[id=btn_row]").eq(eq).click();
                  return false;
                }else{
                  return true;
                }

              });

    $("button[id=edit]").on("click",function(){
      eq = $("button[id=edit]").index(this);
      img_name =$("button[id=edit]").eq(eq).attr("img-name");
      $.ajax({
        type:"POST",
        url:"{{url('/form_rkb/img-edit.py')}}",
        data:{img_name:img_name},
        beforeSend:function(){
          $(".modal-dialog").removeClass("modal-lg").addClass("modal-md");
        },
        success:function(result){ 
          $("div[id=konten_modal]").html(result);
        }
      });
    });
$(document).on("change","select[id=part_name]",function(){
    eq = $("select[id=part_name]").index(this);
    part_name =  $("select[id=part_name]").eq(eq).val();
    //alert(part_name);
    $.ajax({
      type:"GET",
      url:"{{url('/rkb/get/master/part_number')}}",
      data:{part_name:part_name},
      beforeSend:function(){

      $("input[id=part_number]").eq(eq).val("");
      $("input[id=item]").eq(eq).val("");
      $("span[id=avaliable]").eq(eq).html("");
      },
      success:function(res){
      $("input[id=part_number]").eq(eq).val(res.part_number);
      $("input[id=item]").eq(eq).val(res.part_name);
      if(res.stok>0){
        $("div[id=avaliable]").eq(eq).html("<font color='green'>"+res.stok+" "+res.satuanA+"</font>");
      }else{
        $("div[id=avaliable]").eq(eq).html("<font color='red'>"+res.stok+" "+res.satuanA+"</font>");
      }
      
      $("input[id=satuan]").eq(eq).val(res.satuanA);
      $("select").selectpicker('refresh');
      }
    });
 });
</script>
<script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>
@if(!$tmp_rkb)
  <script>
  </script>
@endif

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
  <script>
    setTimeout(function(){
new PNotify({
          title: 'Failed',
          text: "{{session('failed')}}",
          type: 'error',
          hide: true,
          styling: 'bootstrap3'
      });
    },500);
  </script>
@endif
@endsection
