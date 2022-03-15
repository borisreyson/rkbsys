@if(isset($close_rkb_po)=="NO_PO")
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">
{{csrf_field()}}
@php
$itemlist=Illuminate\Support\Facades\DB::table("e_rkb_detail")->where("no_rkb",$no_rkb)->get();
@endphp
<input type="hidden" name="no_rkb" value="{{$no_rkb}}">
<input type="hidden" class="form-control" name="total_item" id="total_item" value="{{count($itemlist)}}" required="required">   
<div class="row">
  <div class="col-xs-12">
    <table class="table table-stripped">
      <thead>
        <tr style="text-align: center!important;">
          <th style="text-align: center!important;">Item</th>
          <th style="text-align: center!important;">Quantity</th>
          <th style="text-align: center!important;">No PO</th>
          <th style="text-align: center!important;">Keterangan</th>
          <th style="text-align: center!important;">Action</th>
        </tr>
      </thead>
      <tbody>
        @php
          
          foreach($itemlist as $k => $v){
          $cek = Illuminate\Support\Facades\DB::table("e_rkb_po")->where([
                  ["no_rkb",$no_rkb],
                  ["item",$v->item]
                  ])->first();
        @endphp
        @if($cek==NULL)
        <tr style="text-align: center!important;">
          <td style="text-align: center!important;">{{$v->part_name}} {{$v->part_number?"(".$v->part_number.")":""}}</td>
          <td style="text-align: center!important;">{{$v->quantity}} </td>
          <td style="text-align: center!important;" id="td_po">
            <span id="span_po"></span>
            <input type="hidden" class="form-control" name="item" id="item" value="{{($v->item)}}" required="required">
            <input type="text" class="form-control" name="no_po" id="no_po" placeholder="Nomor Purchase Order" required="required">
          </td>
          <td style="text-align: center!important;" id="td_keterangan">
            <span id="span_ket"></span>
            <textarea class="form-control" name="keterangan" id="keterangan" required="required" placeholder="Keterangan...."></textarea>
          </td>
          <td style="text-align: center!important;" id="td_kirim">
            <button class="btn btn-xs btn-primary" type="button" id="saveBTN"><i class="fa fa-save"></i></button>
          </td>
        </tr>
        @else
        <tr style="text-align: center!important;">
          <td style="text-align: center!important;">{{$v->part_name}} {{$v->part_number?"(".$v->part_number.")":""}}</td>
          <td style="text-align: center!important;">{{$v->quantity}} </td>
          <td style="text-align: center!important;" id="">
            {{$cek->no_po}}
          </td>
          <td style="text-align: center!important;" id="">
            {{$cek->keterangan}}
          </td>
          <td style="text-align: center!important;" id="td_kirim">
            <i class="fa fa-check"></i>
          </td>
        </tr>
        @endif
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>
</div>
<script>
  var itemTotal = $("input[name=total_item]").val();
  console.log(itemTotal);
  $("button[id=saveBTN]").click(function(){
    eq =  $("button[id=saveBTN]").index(this);   
    no_rkb = $("input[name=no_rkb]").val();
    item = $("input[name=item]").eq(eq).val();    
    no_po = $("input[id=no_po]").eq(eq).val();
    keterangan = $("textarea[id=keterangan]").eq(eq).val();
    if(no_po==""){
      alert("NO PO TIDAK BOLEH KOSONG!");
      $("input[id=no_po]").focus();
    }else if(keterangan==""){
      alert("KETERANGAN TIDAK BOLEH KOSONG!");
      $("textarea[id=keterangan]").focus();
    }else{
    $.ajax({
      type:"POST",
      url:"{{url('/api/rkb/close.rkb')}}",
      data:{_token:"{{csrf_token()}}",no_rkb:no_rkb,no_po:no_po,keterangan:keterangan,_method:"PUT",item:item,total_item:itemTotal},
      success:function(res){
        $("input[id=no_po]").eq(eq).hide();
        $("span[id=span_po]").eq(eq).html(no_po);
        $("textarea[id=keterangan]").eq(eq).hide();
        $("span[id=span_ket]").eq(eq).html(keterangan);
        $("button[id=saveBTN]").eq(eq).attr("disabled","disabled");
        //alert(eq);
        console.log(itemTotal);
        itemTotal--;
        if(res =="refresh"){
          window.location.reload();
        }
      }
    });
  }
  });

</script>
@endif
@if(isset($close_rkb_cancel)=="close_rkb_cancel")
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">
        <p>Apakah Anda Ingin Menutup RKB ini?</p>
      </div>
      <div class="modal-footer">
        <form action="{{url('/api/rkb/close.rkb.cancel')}}" method="post">
          {{csrf_field()}}
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="no_rkb" value="{{$no_rkb}}">
        <button type="submit" class="btn btn-primary" id="submit_status">Yes</button>
        <button type="button" class="btn btn-danger" id="close_modal" data-dismiss="modal">No</button>
        </form>
      </div>
</div>
@endif

@if(isset($upload))
<link rel="stylesheet" type="text/css" href="{{asset('/css/dropzone.css')}}">
<style>
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
  .dropzone{
    height: 300px;
  }
      .dz-preview{
      z-index: 1!important;
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
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">

<form action="{{asset('/v1/file-upload')}}" class="form-horizontal dropzone" id="myAwesomeDropzone" data-tooltip="tooltip" title="Maksimal yang di unggah adalah 9">
            <span class="labeling"><font color="red" id="curent_img">0</font>/9 File</span>
            {{csrf_field()}}
            <input type="hidden" name="oriname" value="{{uniqid()}}">
            <input type="hidden" name="no_rkb" value="{{$no_rkb}}">
            <input type="hidden" name="part_name" value="{{$rkb_det->part_name}}">
            <div class="fallback">
            <input name="file" type="file" multiple />
            </div>
            </form>

</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="back_now">Back</button>
        <button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>
</div>
<script src="{{asset('/js/dropzone.js')}}"></script>
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
  $(document).ready(function(){
tmp_id = $("input[name=oriname]").val();
  Dropzone.options.myAwesomeDropzone = {
        maxFilesize: 10,
        maxFiles: 9 ,
        dictDefaultMessage: "<button type=\"button\" class=\"btn btn-sm btn-success\">Pilih</button> <label>atau tarik dan letakkan di sini untuk mengupload</label>\n <br/><br/>\n <ul class=\"ul_message\" style=\"text-align:left; vertical-align:middle;\"> <li> Sampai 9 File, masing-masing maksimal 10 MB.</li>\n </ul>",
        previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n     </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n \n <center>&nbsp;<a href=\"javascript:void(0)\" class=\"btn btn-xs btn-danger centered\" id=\"deleted\" data-dz-remove > <i class=\"fa fa-trash\"></i></a></center>\n          </div>",
       // acceptedFiles:"*",
         init: function() {
          thisDropzone = this;

$.getJSON("/v1/recent-image-{{bin2hex($no_rkb)}}", function(dataku) {
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

  $("button[id=back_now]").on("click",function(){
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:"{{$no_rkb}}",parent_eq:eq},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-md').addClass('modal-lg');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
  });
</script>
@endif