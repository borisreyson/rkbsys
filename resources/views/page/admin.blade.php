@extends('layout.master')
@section('title')
ABP-system | Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
<link href="{{asset('/vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
<style>
  thead tr th,tbody tr td{
    text-align: center;
  }    
  .dropdown-menu{
    box-shadow: 1px 1px 5px 5px rgba(0,0,0,0.2);
  }
  .dropdown-menu .print_prev{
    background-color: rgba(242,214,125,0.9);

    color: rgba(0,0,0,0.7);
  }
  .dropdown-menu .details{
    background-color: rgba(245,148,28,0.9);

    color: #fff;
  }
  .dropdown-menu .cancel{
    background-color: rgba(191,17,46,0.9);
    color: #fff;
  }
  .dropdown-menu .po{
    background-color: rgba(56,146,166,0.9);
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
                    <h2>Report <small>Rencana Kebutuhan Barang {{session("success")}}</small></h2>
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

<div class="col-md-7">
  <div class="row">
<div class="col-md-12">
  <div class="btn-group">
  <a data-toggle="collapse" id="filter" data-parent="#accordion" href="#collapseOne" class="btn btn-info"><i class="fa fa-filter"></i> Filter</a>
@if(!($_SESSION['section']=="KABAG" || $_SESSION['section']=="KTT" || $_SESSION['section']=="PURCHASING" ))
  <a href="{{url('/form_rkb')}}" class="btn btn-default">Create New</a>
@endif
@if(isset($_GET['expired'])!="")
  <a href="{{url('rkb')}}" class="btn btn-default">RKB</a>
@else
  <a href="{{url('rkb?expired=notnull')}}" class="btn btn-danger"> RKB EXPIRED</a>
@endif
@if(isset($_GET['close_rkb'])!="")
<a href="{{url('rkb')}}" class="btn btn-default">RKB</a>
@else
<a href="{{url('/rkb/close?close_rkb=all')}}" class="btn btn-default">RKB Close</a>
@endif
</div>
</div>
</div>

</div>
<div class="col-md-5">
  <div class="row">
  <form action="" method="post" class="form-horizontal">
    {{csrf_field()}}
      <div class="form-group">
        <label class="control-label col-md-6"> Search</label>
        <div class=" col-md-6">
          <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search for..." autocomplete="off" value="{{$_POST['search'] or ''}}" required="required">
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


<div class="col-md-12">
  <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                              <form action="" method="get">
                      <div class="panel">
                        <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
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

@if($_SESSION['section']=="KABAG"||$_SESSION['section']=="KTT"||$_SESSION['section']=="PURCHASING")
    <tr>
      @if($_SESSION['section']=="KTT" || $_SESSION['section']=="PURCHASING")
      <th><label>Department</label></th>
      <td>
          <?php
$dep = Illuminate\Support\Facades\DB::table('department')->groupBy("dept")->get();
          ?>
        <select class="form-control" name="dep" id="dep">
          <option value="" selected="selected">--PILIH--</option>
          @foreach($dep as $kd => $vd)
          @if($vd->dept!="ALL")
          @if(isset($_GET['dep']))
          @if($_GET['dep']==$vd->dept)
            <option selected="selected" value="{{$vd->dept}}">{{$vd->dept}}</option>
          @else
            <option value="{{$vd->dept}}">{{$vd->dept}}</option>
          @endif
          @else
            <option value="{{$vd->dept}}">{{$vd->dept}}</option>

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
$section = Illuminate\Support\Facades\DB::table('department')->where("dept",$_SESSION['department'])->get();
}else{
$section = Illuminate\Support\Facades\DB::table('department')->get();  
}
          ?>
        <select class="form-control" name="seksi" id="seksi" disabled="disabled">
          <option value="" selected="selected">--PILIH--</option>
          @foreach($section as $k => $v)
            @if($v->sect!="KABAG" && $v->sect!="KTT" && $v->sect!="PURCHASING")
            @if(isset($_GET['seksi']))
            @if($v->sect==$_GET['seksi'])
            <option selected="selected" value="{{$v->sect}}">{{$v->sect}}</option>
            @else
            <option value="{{$v->sect}}">{{$v->sect}}</option>
            @endif
            @else
            <option value="{{$v->sect}}">{{$v->sect}}</option>
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
                              </form>
                    </div>
</div>
                    <div class="">
                      <table id="" class="table table-striped  bulk_action table-bordered table-responsive">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">RKB Number </th>
                            <th class="column-title">Department - Section </th>
                            <th class="column-title">RKB Date </th>
                            <th class="column-title">User</th>
                            <th class="column-title">Ka. Bag. </th>
                            <th class="column-title" width="150px">KTT </th>
                            @if(isset($_GET['expired']))
                            <th class="column-title">Expired Remarks </th>
                            @endif
                            @if(isset($_GET['close_rkb']))
                            <th class="column-title">NM PO </th>
                            <th class="column-title" width="150px">Close Remarks </th>
                            @endif
                            <th class="column-title no-link last" width="150px" align="center" ><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="5">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
@if(count($rkb)>0)
@foreach($rkb as $key => $value)
@if($key%2)
                          <tr class="odd pointer">
                            <td class=" "  style="text-align: left!important;">                          
<span class="text{{$key}}">{{$value->no_rkb}} </span> <br>
<a href="#" class="btn btn-xs btn-primary" dept="hrga" id="PURCHASING" no-rkb="{{bin2hex($value->no_rkb)}}" section="PURCHASING" tmp-view="ktt">Send To PURCHASING</a>
<button id="clipboard_btn" class="btn btn-xs pull-right" data-tooltip="tooltip" title="Copy" data-clipboard-action="copy" data-clipboard-target=".text{{$key}}"><i class="fa fa-clipboard"></i></button>

@if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
<label class="label label-danger" style="float: right!important;">Cancel By {{$value->cancel_user}} - {{$value->cancel_section}}</label>
@endif

                            </td>
                            <td class=" ">{{$value->dept}} <br> {{$value->section}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} 
                            </td>
                            <td class=" ">
                              {{$value->nama_lengkap}} 
                            </td>
                            <td class=" ">
                              @if($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Disetujui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app)
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
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
                              <br>
                              <a href="#" class="btn btn-xs btn-primary" dept="{{$value->department}}" id="KABAG" no-rkb="{{bin2hex($value->no_rkb)}}"  tmp-view="new" section="KABAG">Send To Kabag</a>

                              <a href="#" class="btn btn-xs btn-primary" dept="{{$value->department}}" id="KABAG" no-rkb="{{bin2hex($value->no_rkb)}}"  tmp-view="new" section="SECTION_HEAD">Send To Section Head</a>
                            </td>
                            <td class=" ">
                              @if($value->diketahui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_diketahui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Diketahui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app)
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_section==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @else
                              
                              @endif
                              @endif
                              @endif
                              <br>
                              <a href="#" class="btn btn-xs btn-primary" dept="ALL" id="KTT" no-rkb="{{bin2hex($value->no_rkb)}}" section="KTT"  tmp-view="kabag">Send To KTT</a>
                            </td>
@if(isset($_GET['expired']))
                            <td class=" " style="background-color: rgba(255,0,0,0.09);color: rgba(0,0,0,0.7);">
                              {{$value->expired_remarks}}
                            </td>
@endif
@if(isset($_GET['close_rkb']))
                            <td class=" " style="background-color: rgba(0,255,80,0.4);color: rgba(0,0,0,0.7);">
                              {{$value->no_po or "-"}}
                            </td>
                            <td class=" " style="background-color: rgba(0,255,80,0.4);color: rgba(0,0,0,0.7);">
                              {{$value->myStatus}}
                            </td>
@endif
                            <td class=" last" align="center">

                    <div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
        
<ul role="menu" class="dropdown-menu pull-right ">
  <!--
                              @if($value->disetujui!=0 || $value->diketahui!=0)
                              @if($value->cancel_user==null)
                              <li><a href="{{url('/convert/'.bin2hex($value->no_rkb).'.PO')}}" class="po">Convert To PO</a></li>
                              @endif
                              @endif
  -->
@if($_SESSION['level']=="administrator")

                              <li><a href="#" class="qty" data-id="{{$value->no_rkb}}" id="qty" data-toggle="modal" data-target="#myModal">Edit Quantity</a></li>
@else
                              @if($value->cancel_section==null)
                              @if($value->diketahui==1)
                              <li><a href="#" class="qty" data-id="{{$value->no_rkb}}" id="qty" data-toggle="modal" data-target="#myModal">Edit Quantity</a></li>
                              @endif
                              @endif
@endif
                              <li><a href="#" class="details" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">Details</a></li>
                              <li><a href="{{url('print-preview-'.bin2hex($value->no_rkb))}}" class="print_prev" target="_blank">Print Preview </a></li>
@if(!isset($_GET['expired']))
@if($value->cancel_section=="")
@if($value->disetujui=='1' && $value->diketahui=='0')
                              
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="expired_rkb" class="cancel" data-toggle="modal" data-target="#myModal">SET EXPIRED</a></li>
@endif
@endif
@endif
                              @if($value->cancel_section==null)
                              @if($value->diketahui==1)
                              <!--<li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="cancel" data-toggle="modal" data-target="#myModal">Cancel</a></li>-->
                              @endif
                              @endif
                              @if(isset($_GET['close_rkb'])=="")
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="close_rkb" class="close_rkb" data-toggle="modal" data-target="#myModal">Close RKB</a></li>
                              @endif
                            </ul>
                          </div>

                            </td>
                          </tr>
 @else
                          <tr class="event pointer">
                            <td class=" " style="text-align: left!important;">                          
<span class="text{{$key}}">{{$value->no_rkb}}</span><br>
<a href="#" class="btn btn-xs btn-primary" dept="hrga" id="PURCHASING" no-rkb="{{bin2hex($value->no_rkb)}}" section="PURCHASING"  tmp-view="ktt">Send To PURCHASING</a>
<button id="clipboard_btn" class="btn btn-xs pull-right" data-tooltip="tooltip" title="Copy" data-clipboard-action="copy" data-clipboard-target=".text{{$key}}"><i class="fa fa-clipboard"></i></button> 
@if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
<label class="label label-danger" style="float: right!important;">Cancel By {{$value->cancel_user}} - {{$value->cancel_section}}</label>
@endif
</td>
                            <td class=" ">{{$value->dept}} <br> {{$value->section}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} </td>
                            <td class=" ">
                              {{$value->nama_lengkap}} 
                            </td>
                            <td class=" ">
                              @if($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s , d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Disetujui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app)
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_section==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif                              
                              <br>
                              <a href="#" class="btn btn-xs btn-primary" dept="{{$value->department}}" id="KABAG" no-rkb="{{bin2hex($value->no_rkb)}}" tmp-view="new" section="KABAG">Send To Kabag</a>
                              <a href="#" class="btn btn-xs btn-primary" dept="{{$value->department}}" id="KABAG" no-rkb="{{bin2hex($value->no_rkb)}}" tmp-view="new" section="SECTION_HEAD">Send To Section Head</a>
                               </td>
                            <td class=" ">
                              @if($value->diketahui==1)
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
                              @if($user_app)
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_section==null)
                              
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @else
                              @endif
                              @endif
                              @endif
                              <br>
                              <a href="#" class="btn btn-xs btn-primary" dept="ALL" id="KTT" no-rkb="{{bin2hex($value->no_rkb)}}" tmp-view="kabag" section="KTT">Send To KTT</a>
                            </td>
@if(isset($_GET['expired']))
                            <td class=" " style="background-color: rgba(255,0,0,0.09);color: rgba(0,0,0,0.7);">
                              {{$value->expired_remarks}}
                            </td>
@endif
@if(isset($_GET['close_rkb']))
                            <td class=" " style="background-color: rgba(0,255,80,0.4);color: rgba(0,0,0,0.7);">
                              {{$value->no_po or "-"}}
                            </td>
                            <td class=" " style="background-color: rgba(0,255,80,0.4);color: rgba(0,0,0,0.7);">
                              {{$value->myStatus}}
                            </td>
@endif
                            <td class=" last" align="center">
                    <div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
<ul role="menu" class="dropdown-menu pull-right ">
  <!--
                              @if($value->disetujui!=0 || $value->diketahui!=0)
                              @if($value->cancel_user==null)
                              <li><a href="{{url('/convert/'.bin2hex($value->no_rkb).'.PO')}}" class="po">Convert To PO</a></li>
                              @endif
                              @endif
                            -->
  @if($_SESSION['level']=="administrator")
                              <li><a href="#" class="qty" data-id="{{$value->no_rkb}}" id="qty" data-toggle="modal" data-target="#myModal">Edit Quantity</a></li>
 @else
                              @if($value->cancel_section==null)
                              @if($value->diketahui==1)
                              <li><a href="#" class="qty" data-id="{{$value->no_rkb}}" id="qty" data-toggle="modal" data-target="#myModal">Edit Quantity</a></li>
                              @endif
                              @endif
 @endif
                              <li><a href="#" class="details" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">Details</a></li>
                              <li><a href="{{url('print-preview-'.bin2hex($value->no_rkb))}}" class="print_prev" target="_blank">Print Preview </a></li>
@if(!isset($_GET['expired']))
@if($value->cancel_section=="")
@if($value->disetujui=='1' && $value->diketahui=='0')
                              
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="expired_rkb" class="cancel" data-toggle="modal" data-target="#myModal">SET EXPIRED</a></li>
@endif
@endif
@endif
                              @if($value->cancel_section==null)
                              @if($value->diketahui==1)
                              <!--<li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="cancel_rkb" class="cancel" data-toggle="modal" data-target="#myModal">Cancel</a></li>-->
                              @endif
                              @endif
                              @if(isset($_GET['close_rkb'])=="")
                              <li><a href="#" no-rkb="{{bin2hex($value->no_rkb)}}" id="close_rkb" class="close_rkb" data-toggle="modal" data-target="#myModal">Close RKB</a></li>
                              @endif
                            </ul>
                          </div>
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
            {{$rkb->links()}}
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
<script type="text/javascript" src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script src="{{asset('/vendors/switchery/dist/switchery.min.js')}}"></script>
   <script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>

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
          type: 'danger',
          hide: true,
          styling: 'bootstrap3'
      });
    },500);
  </script>
@endif
<script>
  $("select[id=dep]").change(function(){
    isi = $("select[id=dep]").val();
    if(isi==""){
      $("select[id=seksi]").html("<option value=\"\">--PILIH--</option>");
      $("select[id=seksi]").attr("disabled","disabled");
    }else{
      $.ajax({
        type:"POST",
        url:"api/department",
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
    "order": [[ 0, "desc" ]]
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
        }
      });
  });
  $("a[id=qty]").on("click",function(){
      eq = $("a[id=qty]").index(this);
      data_id = $("a[id=qty]").eq(eq).attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/purchasing/edit/qty')}}",
        data:{no_rkb:data_id},
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


  $("a[id=close_rkb]").on("click",function(){
      eq = $("a[id=close_rkb]").index(this);
      no_rkb = $("a[id=close_rkb]").eq(eq).attr("no-rkb");
      $.ajax({
        type:"POST",
        url:"{{url('/api/rkb/close.rkb')}}",
        data:{no_rkb:no_rkb},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-lg').addClass('modal-md');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
  });

  $("a[id=expired_rkb]").on("click",function(){
      eq = $("a[id=expired_rkb]").index(this);
      no_rkb = $("a[id=expired_rkb]").eq(eq).attr("no-rkb");
      $.ajax({
        type:"POST",
        url:"{{url('/api/expired')}}",
        data:{no_rkb:no_rkb},
        beforeSend:function(){
          $(".modal-dialog").removeClass('modal-lg').addClass('modal-md');
        },
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
  });

$(document).on("click","a[id=KABAG]",function(){
  eq = $("a[id=KABAG]").index(this);
  dept = $("a[id=KABAG]").eq(eq).attr("dept");
  no_rkb = $("a[id=KABAG]").eq(eq).attr("no-rkb");
  sect = $("a[id=KABAG]").eq(eq).attr("section");  
  tmp_view = $("a[id=KABAG]").eq(eq).attr("tmp-view");
  $.ajax({
    type:"GET",
    url:"{{url('/send/email')}}",
    data:{department:dept,section:sect,nomor_rkb:no_rkb,tmp_view:tmp_view},
    success:function(result){
      console.log(result);
      alert(result);
    }
  });
  return false;
});
$(document).on("click","a[id=KTT]",function(){
  eq = $("a[id=KTT]").index(this);
  dept = $("a[id=KTT]").eq(eq).attr("dept");
  no_rkb = $("a[id=KTT]").eq(eq).attr("no-rkb");
  sect = $("a[id=KTT]").eq(eq).attr("section");
  tmp_view = $("a[id=KTT]").eq(eq).attr("tmp-view");
  $.ajax({
    type:"GET",
    url:"{{url('/send/email')}}",
    data:{department:dept,section:sect,nomor_rkb:no_rkb,tmp_view:tmp_view},
    success:function(result){
      console.log(result);
      alert(result);
    }
  });
  return false;
});

$(document).on("click","a[id=PURCHASING]",function(){
  eq = $("a[id=PURCHASING]").index(this);
  dept = $("a[id=PURCHASING]").eq(eq).attr("dept");
  no_rkb = $("a[id=PURCHASING]").eq(eq).attr("no-rkb");
  sect = $("a[id=PURCHASING]").eq(eq).attr("section");
  tmp_view = $("a[id=PURCHASING]").eq(eq).attr("tmp-view");
  $.ajax({
    type:"GET",
    url:"{{url('/send/email')}}",
    data:{department:dept,section:sect,nomor_rkb:no_rkb,tmp_view:tmp_view},
    success:function(result){
      console.log(result);
      alert(result);
    }
  });
  return false;
});

</script>
@endsection