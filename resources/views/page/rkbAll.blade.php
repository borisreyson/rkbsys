@extends('layout.master')
@section('title')
ABP-system | Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
<link href="{{asset('/vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
<style>
  tbody tr td{
    text-align: center;
  }
  .dropdown-menu{
    box-shadow: 1px 1px 5px 5px rgba(0,0,0,0.2);
  }
  .dropdown-menu .details{
    background-color: rgba(245,148,28,0.9);

    color: #fff;
  }
  .dropdown-menu .cancel{
    background-color: rgba(191,17,46,0.9);
    color: #fff;
  }
  .bottom_padd{
    padding-bottom: 15px;
  }
  .right_padd{
    margin-right: 10px!important;
  }
  .box-height{
    border: solid 0.1em #8CBFAA;
    margin-bottom: 1.5em;
  }
  .rkb_box{
    color: #000;
    font-weight: bold;
  }
  .rkb_box:hover,.rkb_box:active,.rkb_box:visited{
    opacity: 0.8;
    color: #000;
  }

.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;

            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            }

.ck-editor__editable {
    min-height: 90px;
}
@media only screen and (max-width: 600px) {
  .data_box{
    height: 350px!important;
  }
}
@media only screen and (min-width: 650px) {
  .data_box{
    height: 270px!important;
  }
}

#countITEM{
  position: absolute;
  top: 0;
  right: 0;
  height: 40px!important;
  width: 40px;
  text-align: center!important;
}
</style>
@endsection
@section('content')
<?php
if(isset($_GET['submit'])){
  $search = $_GET['search'];
  if(isset($_GET['page'])){
    $page = $_GET['page'];
    header('Location: ?page='.$page.'&search='.$search);
    exit;
  }else{
    header('Location: ?search='.$search);
    exit;
  }
}
?>
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<div class="right_col" role="main">
 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Report <small>Rencana Kebutuhan Barang</small></h2>
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
                    <br/>
  <div class="row">
<div class="col-md-6 col-sm-12 col-xs-12">

  <div id="btn_group" class="btn-group  col-xs-12">
  <a data-toggle="collapse" id="filter" data-parent="#accordion" href="#collapseOne" class="btn btn-info"><i class="fa fa-filter"></i> Filter</a>
@if(!($_SESSION['section']=="KABAG" || $_SESSION['section']=="KTT" || $_SESSION['section']=="PURCHASING" ))
  <a href="{{url('/form_rkb')}}" class="btn btn-default">Create New</a>
@endif
@if(isset($_GET['expired'])!="")
  <a href="{{url('/kabag/alldept/rkb')}}" class="btn btn-default">RKB</a>
@else
  <!--<a href="{{url('/kabag/rkb?expired=notnull')}}" class="btn btn-danger"> RKB EXPIRED</a>-->
@endif
@if(isset($_GET['close_rkb'])!="")
<a href="{{url('/kabag/alldept/rkb')}}" class="btn btn-default">RKB</a>
@else
<a href="{{url('/kabag/alldept/rkb?close_rkb=all')}}" class="btn btn-default">RKB Close</a>
@endif
</div>
</div>
<div class="col-md-6 col-sm-12 col-xs-12">
  <div class="">
  <form action="" method="GET" class="form-horizontal">
    {{csrf_field()}}
      <div class="form-group">
        <div class=" col-md-6 col-md-offset-6 col-sm-12 col-xs-12">
          <div class="input-group">
<input type="search" class="form-control" name="search" placeholder="Search for..." autocomplete="off" value="{{$_POST['search'] or ''}}"  required="required">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit" name="submit"><i class="fa fa-search"></i></button>
                    </span>
          </div>
        </div>
      </div>
  </form>
  </div>
</div>
</div>


<div class="col-md-12">
  <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                              <form action="" method="get">
                      <div class="panel">
                        <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
<div class="col-md-12">
<div class="table-responsive">

<table class="table">
  <tbody>
    <tr>
      <th><label for="disetujui">Approved by Kabag</label></th>
      <td><input type="checkbox" class="js-switch grouped" <?php if(isset($_GET['disetujui'])==1){ echo "checked=\"checked\""; }?> value="1" name="disetujui" id="disetujui"></td>

      <th><label for="diketahui">Approved by KTT</label></th>
      <td><input type="checkbox" <?php if(isset($_GET['diketahui'])==1){ echo "checked=\"checked\""; }?> class="js-switch grouped" name="diketahui" value="1" id="diketahui"></td>

      <th><label for="approve">Waiting</label></th>
      <td><input type="checkbox" <?php if(isset($_GET['approve'])==1){ echo "checked=\"checked\""; }?> class="js-switch grouped" name="approve" value="1" id="approve"></td>


      <th><label for="cancel">Cancel</label></th>
      <td><input type="checkbox" <?php if(isset($_GET['cancel'])==1){ echo "checked=\"checked\""; }?> class="js-switch ungroup" name="cancel" value="1" id="cancel"></td>
    </tr>

@if($_SESSION['section']=="KABAG"||$_SESSION['section']=="KTT")
    <tr>
      @if($_SESSION['section']=="KTT")
      <th><label>Department</label></th>
      <td>
          <?php
$dep = Illuminate\Support\Facades\DB::table('department')->groupBy("dept")->get();
          ?>
        <select class="form-control" name="dep" id="dep">
          <option value="" selected="selected">--PILIH--</option>
          @foreach($dep as $kd => $vd)
          @if($vd->id_dept!="ALL")
          @if(isset($_GET['dep']))
          @if($_GET['dep']==$vd->id_dept)
            <option selected="selected" value="{{$vd->id_dept}}">{{$vd->dept}}</option>
          @else
            <option value="{{$vd->id_dept}}">{{$vd->dept}}</option>
          @endif
          @else
            <option value="{{$vd->id_dept}}">{{$vd->dept}}</option>
          @endif
          @endif
          @endforeach

        </select>
      </td>
      @endif
      <th><label>Section</label></th>
      <td>
          <?php

if($_SESSION['department']!="ALL"){
$section = Illuminate\Support\Facades\DB::table('section')
          ->leftjoin("department","department.id_dept","section.id_dept")
          ->select("department.*","section.*")
          ->where("department.id_dept",$_SESSION['department'])
          ->get();
}else{
$section = Illuminate\Support\Facades\DB::table('section')->get();
}
          ?>
        <select class="form-control" name="seksi" id="seksi" disabled="disabled">
          <option value="" selected="selected">--PILIH--</option>
          @foreach($section as $k => $v)
            @if($v->id_sect!="KABAG" && $v->sect!="KTT" && $v->id_sect!="PURCHASING")
            @if(isset($_GET['seksi']))
            @if($v->id_sect==$_GET['seksi'])
            <option selected="selected" value="{{$v->id_sect}}">{{$v->sect}}</option>
            @else
            <option value="{{$v->id_sect}}">{{$v->sect}}</option>
            @endif
            @else
            <option value="{{$v->id_sect}}">{{$v->sect}}</option>
            @endif

            @endif
          @endforeach

        </select>
      </td>
      <td colspan="6"></td>
    </tr>
@endif
    <tr>
      <td colspan="7"> </td>
      <td align="right">
        <button type="submit" class="btn btn-primary">submit</button>
      </td>
    </tr>
  </tbody>
</table>

</div>
</div>
                          </div>
                        </div>
                      </div>
                              </form>
                    </div>
</div>
<div class="row">
<div class="col-md-12 col-xs-12">
  @foreach($rkb as $key => $value)
@php
  if($value->cancel_user==null)
  {
    if($value->diketahui>0){

    $color = "#08A32F";
    }else{

    $color = "#917A07";
    }
  }else{
    $color = "#930C00";
  }
@endphp

<a href="javascript:void(0)" class="rkb_box">
<div class="col-lg-4 col-sm-6 col-xs-12 data_box" title="Click To Detail" data-placement="top"  data-tooltip="tooltip" >
<div class="row box-height right_padd"><a href="#" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">
  <div class="col-xs-12 " style="background-color: <?php echo $color;?>;color: #fff;font-weight: bold; ">
  <div class="col-lg-12 col-xs-12 no_rkb row text-center"><h4 class="text-center">
<span class="text{{$key}} text-center">{{$value->no_rkb}}</span>
  </h4>
  <?php $totalItem = Illuminate\Support\Facades\DB::table("e_rkb_detail")->where("no_rkb",$value->no_rkb)->count();
  if($totalItem>1){ ?>
  <span id="countITEM" style="font-size:13px;"> {{$totalItem}} Items</span>
<?php }else{?>
<span id="countITEM" style="font-size:13px;"> {{$totalItem}}<br>Item</span>
  <?php } ?>
</div>
  </div>
</a>

  <div class="col-lg-12 col-xs-12 text-center" style="border-top:solid thin <?php echo $color;?>;padding-bottom: 3px;">
    <small>{{date("d F Y",strtotime($value->tgl_order))}}</small>
  </div>
  <div class="col-lg-12 col-xs-12" style="border-bottom:solid thin #000;border-top:solid thin <?php echo $color;?>;padding-bottom: 3px;">
    <div class="col-lg-8 col-xs-6 row">{{ucfirst($value->nama_lengkap)}} </div>
    <div class="col-lg-4 col-xs-6 text-right row">
      <div class="text-right row">
@if($value->cancel_user!=$value->user_entry)
<label class="label label-default">Created</label>
@else
<label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{ucfirst($value->nama_lengkap)}}</label>
@endif
</div>
</div>
    </div>
  <div class="col-lg-12"><small>{{$value->sect}} - {{$value->dept}}</small></div>
  <div class="col-lg-6 col-xs-12">
    <span class="row text-center">
      <div class="col-xs-12 row">Ka. Bag.</div>
      <div class="col-xs-12 row text-center">
    @if($value->disetujui>0)
<label class="label label-success"  title="{{date('H:i:s ,d F Y',strtotime($value->tgl_disetujui))}}" data-placement="top" data-tooltip="tooltip">{{date("H:i:s ,d F Y",strtotime($value->tgl_disetujui))}}</label>
@php
$user_app = Illuminate\Support\Facades\DB::table("user_approve")->where([
["no_rkb",$value->no_rkb],
["desk","Disetujui"]
])
->leftjoin("user_login","user_login.username","user_approve.username")
->first();
@endphp
@if($user_app)
@if($user_app->level=="PLT")
<br>
<label class="label label-default">{{$user_app->nama_lengkap}}</label>
@endif
@else
<br>
@endif
    @else
@if($value->cancel_user==null)
<!-- <a href="{{asset('/approve/rkb/'.bin2hex($value->no_rkb))}}" id="approve_rkb" title="Click To Approve Rkb" data-placement="top"  data-tooltip="tooltip" class="btn btn-success btn-xs" >Approve Rkb</a> -->
@if($value->disetujui==0)
<!-- <a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal" title="Click To Cancel" data-placement="top" data-tooltip="tooltip" >Cancel</a> -->
@endif
<label class="label label-warning" data-placement="top" data-tooltip="tooltip">Waiting</label>
@else
@if($value->cancel_section=="KABAG")
@php
$user_cancel = Illuminate\Support\Facades\DB::table("user_login")->where("username",$value->cancel_user)->first();
@endphp
<label class="label label-danger" type="button" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$user_cancel->nama_lengkap}} </label><br><br>
@else
<label class="label label-danger"><i class="fa fa-times"></i></label>
<br><br>
@endif
@endif
    @endif
      </div>
  </span>
</div>
  <div class="col-lg-6 col-xs-12">
    <span class="row text-center">
      <div class="col-xs-12 row" style="color: #000;font-weight: bold;">KTT</div>
      <div class="col-xs-12 row text-center">
    @if($value->diketahui>0)
<label class="label label-success" title="{{date('H:i:s ,d F Y',strtotime($value->tgl_diketahui))}}" data-placement="top" data-tooltip="tooltip">{{date("H:i:s ,d F Y",strtotime($value->tgl_diketahui))}}</label>
@php
$user_app = Illuminate\Support\Facades\DB::table("user_approve")->where([
["no_rkb",$value->no_rkb],
["desk","Diketahui"]
])
->leftjoin("user_login","user_login.username","user_approve.username")
->first();
@endphp
@if($user_app)
@if($user_app->level=="PLT")
<br>
<label class="label label-default">{{$user_app->nama_lengkap}}</label>
@else
<br>
<br>
@endif
@else
<br>
<br>
@endif
    @else
@if($value->cancel_user==null)
      <label class="label label-warning" title="Waiting" data-placement="top" data-tooltip="tooltip">Waiting</label>
<br>
<br>
@else
@if($value->cancel_section=="KTT")
@php
$user_cancel = Illuminate\Support\Facades\DB::table("user_login")->where("username",$value->cancel_user)->first();
@endphp
<label class="label label-danger" type="button" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$user_cancel->nama_lengkap}} </label><br><br>
@else
<label class="label label-danger"><i class="fa fa-times"></i></label>
@endif
@endif
    @endif
      </div>
  </span>
</div>
@if(isset($_GET['close_rkb']))
<div class="col-xs-12" style="border-top: solid thin #DEAF38;background-color: #DEAF38;">
  <div class="row">
    <div class="col-sm-4">
    <div class="row">
<div class="col-sm-12"><b>Nomor PO</b></div>
<div class="col-sm-12">{{$value->no_po or "-"}}</div>
    </div>

    </div>
    <div class="col-sm-8">
    <div class="row">
<div class="col-sm-12"><b>Close Remarks</b></div>
<div class="col-sm-12">{{$value->myStatus}}</div>
    </div>
  </div>
  </div>
</div>
@endif

<!---->
<div class="col-xs-12" style="border-top: solid #333 thin;padding-top: 5px;">
  <div class="row">
    <div class="col-sm-12">

<a href="#" class="btn btn-xs btn-primary" data-id="{{$value->no_rkb}}" id="details1" data-toggle="modal" data-target="#myModal">Details</a>
    </div>
  </div>
</div>
<!---->
</div>
</div>
</a>
@endforeach
<div id="page" class="row col-lg-12 text-center">
@if(isset($_GET['search']))
{{$rkb->appends(['search' => $_GET['search'] ])->links()}}
@elseif(isset($_GET['close_rkb']))
{{$rkb->appends(['close_rkb' => 'all' ])->links()}}
@elseif(isset($_GET['expired']))
{{$rkb->appends(['expired' => 'notnull' ])->links()}}
@else
{{$rkb->links()}}
@endif
</div>
</div>
</div>
</div>
                  </div>
                  </div>
                </div>
              </div>
</div>


@include('layout.footer')

</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
<div id="konten_modal"></div>
  </div>
</div>


    <!-- compose -->
    <div class="compose col-md-6 col-xs-12">
      <div class="compose-header">
        New Message
        <button type="button" class="close compose-close">
          <span>×</span>
        </button>
      </div>
      <div class="compose-body">
<form class="form-horizontal" action="{{url('/kabag/inbox')}}" method="post">
<div id="alerts"></div><br>
<div class="form-group">
  <label class="col-md-2 col-xs-12"  for="user_to">
    <div class="form-control-static " >To</div>
  </label>
  <div class="col-md-10  col-xs-12">
  <input type="text" name="user_to" id="user_to" class="form-control" placeholder="To"  required>
  <input type="hidden" name="username_to" id="username_to" class="form-control" required>
  </div>
</div>
<div class="form-group">
  <label class="col-md-2  col-xs-12"  for="user_to">
    <div class="form-control-static " >No RKB</div>
  </label>
  <div class="col-md-10 col-xs-12">
  <input type="text" name="no_rkb" id="no_rkb" class="form-control" placeholder="Nomor RKB">
  </div>
</div>
<div class="form-group">
  <label class="col-md-2  col-xs-12"  for="user_to">
    <div class="form-control-static " >Part Name</div>
  </label>
  <div class="col-md-10 col-xs-12">
  <input type="text" name="part_name" id="part_name" class="form-control" placeholder="Part Name">
  </div>
</div>
<div class="form-group">
  <label class="col-md-2 col-xs-12 "  for="user_to">
    <div class="form-control-static " >Subjek</div>
  </label>
  <div class="col-md-10 col-xs-12">
  <input type="text" name="subjek" id="subjek" class="form-control" placeholder="Subkjek">
  </div>
</div>
<div class="form-group">
  <div class="col-md-12 col-xs-12">
  <textarea type="text" name="message" id="message" rows="3" class="form-control" placeholder="Pesan"></textarea>
  </div>
</div>
{{csrf_field()}}
<input type="hidden" name="tree" id="tree">
<div class="compose-footer" style="">
  <button id="send" class="btn btn-sm btn-success" type="submit">Send</button>
  <button id="reset" class="btn btn-sm btn-default" type="reset">Reset</button>
  <button type="button" class="compose-close btn btn-sm btn-danger">
          <span>Close</span>
        </button>
</div>
</form>
    </div>
</div>
    <!-- /compose -->
@endsection

@section('js')


@include('layout.js')

    <script src="{{asset('/vendors/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
    <script src="{{asset('/ckeditor/ckeditor.js')}}"></script>

<script >
    function init_reply() {

      if( typeof ($.fn.slideToggle) === 'undefined'){ return; }
      console.log('init_compose');
      $('button[id=tanyakan]').click(function(){
        $('.compose').slideToggle();
        return false;
      });


    };


init_reply();

      $("button[id=tanyakan]").click(function() {
        eq = $("button[id=tanyakan]").index(this);
        user_to = $("input[id=userTo]").eq(eq).val();
        usernameTo = $("input[id=usernameTo]").eq(eq).val();
        norkb_to = $("input[id=norkb_to]").eq(eq).val();

        $("#tree").val("parent");
        $("#user_to").val(user_to);
        $("#username_to").val(usernameTo);
        $("#no_rkb").val(norkb_to);
      });
      $(".close").click(function() {
        $("#reset").click();
      });
      $(".compose-close").click(function() {
        $("#reset").click();
      });

      var editor1 = new CKEDITOR.replace( 'message' );
      $("#pictureBtn").click(function(){
        $("#img_file").click();
      });
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
<script>
  $(window).on("load",function(){
    Lebar = $(window).width();
    if(Lebar<720){
      $("#btn_group").addClass("btn-group-vertical").addClass("bottom_padd");
      $(".no_rkb").addClass("text-center");
    }else{
      $("#btn_group").removeClass("btn-group-vertical").removeClass("bottom_padd");
      $(".no_rkb").removeClass("text-center");
    }
  });

  $(window).on("resize",function(){
    Lebar = $(window).width();
    if(Lebar<720){
      $("#btn_group").addClass("btn-group-vertical").addClass("bottom_padd");
      $(".box-height").removeClass("right_padd");
      $(".no_rkb").addClass("text-center");



    }else{
      $("#btn_group").removeClass("btn-group-vertical").removeClass("bottom_padd");
      $(".box-height").addClass("right_padd");
      $(".no_rkb").removeClass("text-center");

    }
  });


  $("select[id=dep]").change(function(){
    isi = $("select[id=dep]").val();
    if(isi==""){
      $("select[id=seksi]").html("<option value=\"\">--PILIH--</option>");
      $("select[id=seksi]").attr("disabled","disabled");
    }else{
      $.ajax({
        type:"POST",
        url:"{{url('/api/department')}}",
        data:{_token:"{{csrf_token()}}",dept:isi},
        success:function(result){
          $("select[id=seksi]").html(result);
        }
      })
      $("select[id=seksi]").removeAttr("disabled");
    }
  });

  var z=0;
  $("a[id=filter]").click(function(){
    eq = $("a[id=filter]").index(this);
    if(z==0){
    $("a[id=filter]").removeClass('btn-info').addClass("btn-default active");
    z=1;
    }else{
    $("a[id=filter]").removeClass('btn-default active').addClass("btn-info");
    z=0;
    }
  });

  var i=0;
  var log_btn = [];
  $(".grouped").change(function(e){
    var eq = $(".grouped").index(this);
    if($('.grouped').eq(eq).is(':checked')){
    var name = $(".grouped").eq(eq).attr("name");
      log_btn.push(name);
    }else{
      log_btn = log_btn.filter(function(item) {
    var name1 = $(".grouped").eq(eq).attr("name");
          return item !== name1
      });
    }
    console.log(log_btn);
  });
  $(".ungroup").change(function(){
    if($('.ungroup').is(':checked')){
           for(i=0; i<=log_btn.length; i++){
            $('input[id='+log_btn[i]+']').click();
           }
    }else{
    }
  });

  $("a[id=details]").on("click",function(){
      eq = $("a[id=details]").index(this);
      data_id = $("a[id=details]").eq(eq).attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:data_id,parent_eq:eq},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-md').addClass('modal-lg');
          $("div[id=konten_modal]").html('<center style="color:#fff;"><li class="fa fa-5x fa-spinner fa-pulse"></li></ceneter>');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
  });

  $("a[id=details1]").on("click",function(){
      eq = $("a[id=details1]").index(this);
      data_id = $("a[id=details1]").eq(eq).attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:data_id,parent_eq:eq},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-md').addClass('modal-lg');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
  });

  $("a[id=cancel_rkb]").on("click",function(){
      eq = $("a[id=cancel_rkb]").index(this);
      no_rkb = $("a[id=cancel_rkb]").eq(eq).attr("no-rkb");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/cancel-rkb.py')}}",
        data:{no_rkb:no_rkb},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-lg').addClass('modal-md');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
  });
</script>
@endsection
