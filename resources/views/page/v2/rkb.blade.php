@extends('layout.master')
@section('title')
ABP-system | Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
 <!-- Datatables -->
 <!--
    <link href="{{asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
-->
<link rel="stylesheet" type="text/css" href="{{asset('/DataTables/datatables.min.css')}}"/>
<link href="{{asset('/vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
<style>
  thead tr th,tbody tr td{
    text-align: center!important;
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
</style>
@endsection
@section('content')

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
<div class="col-md-6 col-xs-12">



<div class="col-md-7 col-xs-12">
  <div class="row">
  <div class="btn-group">
  <a data-toggle="collapse" id="filter" data-parent="#accordion" href="#collapseOne" class="btn btn-info "><i class="fa fa-filter"></i> Filter</a>
@if(!($_SESSION['section']=="KABAG" || $_SESSION['section']=="KTT" || $_SESSION['section']=="PURCHASING" ))
  <a href="{{url('/form_rkb')}}" class="btn btn-default">Create New</a>
@endif
@if(isset($_GET['expired'])!="")
  <a href="{{url('rkb')}}" class="btn btn-default">RKB</a>
@else
  <!--<a href="{{url('rkb/expired?expired=notnull')}}" class="btn btn-danger"> RKB EXPIRED</a>-->
@endif
</div>

</div>
</div>

</div>
<div class="col-md-5 col-xs-12">
  <div class="row">
  <form action="" method="post" class="form-horizontal">
    {{csrf_field()}}
      <div class="form-group">
        <div class=" col-md-6 col-md-offset-6  col-xs-12">
          <div class="input-group">
<input type="search" class="form-control" name="search" placeholder="Search for..." autocomplete="off" value="{{$_POST['search'] or ''}}" required="required">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                    </span>
          </div>
        </div>
      </div>
  </form>
  </div>
</div>

                    
</div>
<div class="col-md-12 col-xs-12">
                            <div class="row">
  <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                              <form action="" method="get">
                      <div class="panel">
                        <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <div class="row">
                            <div class="row">
<div class="table-responsive">

</div>
                          </div>
                        </div>
                          </div>
                        </div>
                      </div>
                              </form>
                    </div>
                    </div>
</div>

<div class="row">
<div class="col-md-12 col-xs-12">
<div class="row">
                    <div class="table-responsive">
                      <table id="" class="table table-striped table-bordered" >
                        <thead>
                          <tr class="headings">
                            <th class="column-title">
                            RKB Number</th>
                            <th class="column-title">Department - Section </th>
                            <th class="column-title">RKB Date </th>
                            <th class="column-title">User </th>
                            <th class="column-title">Ka. Bag. </th>
                            <th class="column-title">KTT </th>
                            @if(isset($_GET['expired']))
                            <th class="column-title">Expired Remarks </th>
                            @endif
                            <th class="column-title no-link last" align="right"><span class="nobr">Action</span>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
@if(count($rkb)>0)
@foreach($rkb as $key => $value)
@if($key%2)
                          <tr class="even pointer">
                            <td class=" "  style="text-align: left!important;">
<span class="text{{$key}}">{{$value->no_rkb}}</span> 
<button id="clipboard_btn" class="btn btn-xs pull-right" data-tooltip="tooltip" title="Copy" data-clipboard-action="copy" data-clipboard-target=".text{{$key}}"><i class="fa fa-clipboard"></i></button>
                              @if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
                              <label class="label label-danger" style="float: right!important;cursor: pointer;cursor: hand;" data-toggle="tooltip" title="Remarks : {{$value->remark_cancel}}">Cancel By {{$value->cancel_user}} </label>
                              @endif
                            </td>
                            <td class=" ">{{$value->dept}} - {{$value->sect}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} 
                            </td>
                            <td class=" ">
                              {{$value->nama_lengkap}} 
                            </td>
                            <td class=" ">
                              @if($value->disetujui==0)
                              @if($_SESSION['section']=="KABAG")

                              @if($value->cancel_user==null)
                              <a href="{{asset('/approve/rkb/'.bin2hex($value->no_rkb))}}" id="approve_rkb" class="btn btn-success btn-xs" >Approve Rkb</a>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")->where([
                                          ["no_rkb",$value->no_rkb],
                                          ["desk","Disetujui"]
                                          ])
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->disetujui==2)
                              Cancel
                              @endif
                            </td>
                            <td class=" ">
                              @if($value->diketahui==0)
                              @if($_SESSION['section']=="KTT")
                              @if($value->cancel_user==null)
                              <a  no-rkb="{{bin2hex($value->no_rkb)}}" id="approve_rkb" class="btn btn-success btn-xs" <?php if(!$value->disetujui>0){?> href="#" onclick="return false;" disabled="disabled" <?php }else{?> href="{{asset('/approve/rkb/ktt/'.bin2hex($value->no_rkb))}}" <?php } ?>  >Approve Rkb</a>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->diketahui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_diketahui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")->where([
                                          ["no_rkb",$value->no_rkb],
                                          ["desk","Diketahui"]
                                          ])
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->diketahui==2)
                              Cancel
                              @endif
                            </td>

@if(isset($_GET['expired']))

                            <td class=" " style="background-color: rgba(255,0,0,0.09);color: rgba(0,0,0,0.7);">
                              {{$value->expired_remarks}}
                            </td>
@endif
                            <td class="" align="left" width="150px">
<div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
<ul role="menu" class="dropdown-menu pull-right ">
<li><a href="#" class="details" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">Details</a></li>
                              @if($value->user_expired=="")

                              @if($value->disetujui==0)
                              @if($value->cancel_user==null)
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="cancel" data-toggle="modal" data-target="#myModal">Cancel</a></li>
                              @endif
                              @else
                              @if($_SESSION['section']=="KTT")
                              @if($value->cancel_user==null)
                              @if($value->diketahui==0)
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="cancel" data-toggle="modal" data-target="#myModal">Cancel</a></li>
                              @endif
                              @endif
                              @endif
                              @endif
                              @endif
</ul>
</div>
                            </td>
                          </tr>
 @else
                          <tr class="odd pointer">
                            <td class=" "  style="text-align: left!important;">                              
<span class="text{{$key}}">{{$value->no_rkb}}</span> 
<button id="clipboard_btn" class="btn btn-xs pull-right" data-tooltip="tooltip" title="Copy" data-clipboard-action="copy" data-clipboard-target=".text{{$key}}"><i class="fa fa-clipboard"></i></button>
                              @if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
                              <label class="label label-danger" style="float: right!important;cursor: pointer;cursor: hand;" data-toggle="tooltip" title="Remarks : {{$value->remark_cancel}}">Cancel By {{$value->cancel_user}}</label>
                              @endif
                            </td>
                            <td class=" ">{{$value->dept}} - {{$value->sect}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}}
                               </td>

                            <td class=" ">
                              {{$value->nama_lengkap}} 
                            </td>
                            <td class=" ">
                              @if($value->disetujui==0)
                              @if($_SESSION['section']=="KABAG")
                              @if($value->cancel_user==null)
                              <a href="{{asset('/approve/rkb/'.bin2hex($value->no_rkb))}}" id="approve_rkb" class="btn btn-success btn-xs" >Approve Rkb</a>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s , d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")->where([
                                          ["no_rkb",$value->no_rkb],
                                          ["desk","Disetujui"]
                                          ])
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->disetujui==2)
                              Cancel
                              @endif
                               </td>
                            <td class=" ">                              
                              @if($value->diketahui==0)
                              @if($_SESSION['section']=="KTT")
                              @if($value->cancel_user==null)
                              <a  no-rkb="{{bin2hex($value->no_rkb)}}" id="approve_rkb" class="btn btn-success btn-xs" <?php if(!$value->disetujui>0){?> href="#" onclick="return false;" disabled="disabled" <?php }else{?> href="{{asset('/approve/rkb/ktt/'.bin2hex($value->no_rkb))}}" <?php } ?>  >Approve Rkb</a>
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              <label class="label label-danger">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->diketahui==1)
                              <label class="label label-success">{{date("H:i:s , d F Y",strtotime($value->tgl_diketahui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Diketahui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->diketahui==2)
                              Cancel
                              @endif</td>
@if(isset($_GET['expired']))
                            <td class=" " style="background-color: rgba(255,0,0,0.09);color: rgba(0,0,0,0.7);">
                              {{$value->expired_remarks}}
                            </td>
@endif
                            <td class="" align="left" width="150px">
<div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
<ul role="menu" class="dropdown-menu pull-right ">
                              <li><a href="#" class="details" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">Details</a></li>
                              @if($value->user_expired=="")
                              @if($value->disetujui==0)
                              @if($value->cancel_user==null)
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="cancel" data-toggle="modal" data-target="#myModal">Cancel</a></li>
                              @endif
                              @else
                              @if($_SESSION['section']=="KTT")
                              @if($value->cancel_user==null)
                              @if($value->diketahui==0)
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="cancel" data-toggle="modal" data-target="#myModal">Cancel</a></li>
                              @endif
                              @endif
                              @endif
                              @endif
                              @endif
</ul></div>
                            </td>
                          </tr>
@endif
@endforeach
@else
						<tr class="odd pointer">
                            <td class="a-center" align="center"  colspan="7">
                            	Not have recored yet!
                            </td>
                         </tr>
@endif

                        </tbody>
                      </table>
                    </div>
                    </div>
                    </div>
                    </div>
              <div class="pages">
                {{$rkb->links()}}
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
@endsection

@section('js')

@include('layout.js')
<!-- Datatables -->
 
<script type="text/javascript" src="{{asset('/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>
<script src="{{asset('/vendors/switchery/dist/switchery.min.js')}}"></script>

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
  var clipboard = new ClipboardJS('button[id=clipboard_btn]');
  $("button[id=clipboard_btn]").on("click",function(){
    eq = $("button[id=clipboard_btn]").index(this);
    //console.log(eq);
    $("button[id=clipboard_btn]").eq(eq).attr("title","Copied");
    $("button[id=clipboard_btn]").eq(eq).mouseleave(function(){
      $("button[id=clipboard_btn]").eq(eq).attr("title","Copy");
    });
  
});

  $("[data-tooltip=tooltip]").tooltip();
  $("#datatables").dataTable({
    "order": [[ 0 , "desc" ]]
  });
  $("a[id=details]").on("click",function(){
      eq = $("a[id=details]").index(this);
      data_id = $("a[id=details]").eq(eq).attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:data_id},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-md').addClass('modal-lg');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        },
        error:function (request, status, error) {
          console.log(request.responseText);
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


      function init_DataTables() {
        
        if( typeof ($.fn.DataTable) === 'undefined'){ return; }
        console.log('init_DataTables');
        
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
          $("#datatable-buttons").DataTable({
          'order': [[ 2, 'desc' ]],
            responsive: true
          });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
          init: function() {
            handleDataTableButtons();
          }
          };
        }();

        TableManageButtons.init();
        
      };
      init_DataTables();
</script>
@endsection