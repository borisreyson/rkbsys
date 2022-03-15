@extends('layout.master')
@section('title')
ABP-system | Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
 <style>
  thead tr th,tbody tr td{
    text-align: center;
  }  
 </style>
<link rel="stylesheet" type="text/css" href="/table/buttons.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="/table/dataTables.bootstrap.min.css"/>
 
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
                    <h2>Report <small>Rencana Kebutuhan Barang </small></h2>


                       <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <br>
                    <div class="col-sm-12">
                    <!--date range-->
                    <form class="form-horizontal form-label-left col-xs-12" style="margin: 0px!important;" action="" method="get">
                      <div class="col-md-6 col-xs-12">
                      <div class="form-group" >
                        <label class="control-label col-md-2 col-xs-12" for="USER">User </label>
                        <div class="col-md-4  col-xs-12">
                          <select type="text" id="USER" name="USER" data-live-search="true" class="form-control select-status">
                            <option value="">--Pilih--</option>
                            <option value="KABAG">KABAG</option>
                            <option value="KTT">KTT</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group" >
                        <label class="control-label col-md-2 col-xs-12" for="ktt">STATUS </label>
                        <div class="col-md-4  col-xs-12">
                          <select type="text" id="STATUS" name="STATUS" data-live-search="true" class="form-control select-status">
                            <option value="">--Pilih--</option>
                            <option value="1">Approve</option>
                            <option value="0">Pending</option>
                            <option value="cancel">Cancel</option>
                          </select>
                        </div>
                      </div>
                      </div>
                      <div class="col-md-6  col-xs-12">
                      <div class="form-group">
                        <div class="col-sm-12 " style="margin: 0px!important;">
                          <div class="row">
                          <div class="input-group" style="margin: 0px!important;">
                            <label class="input-group-addon" for="startDate">Start</label>
                            <input type="text" class="form-control input-sm datepicker" id="startDate" name="startDate" value="{{$_GET['startDate'] or date('d F Y')}}">
                            <label class="input-group-addon" style="margin-right: 0px!important;">End
                            </label>
                            <input type="text" class="form-control input-sm datepicker" id="endDate" name="endDate" value="{{$_GET['endtDate'] or date('d F Y')}}">
                            <span class="input-group-btn" style="margin-right: 0px!important; ">
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 0px!important; ">Submit</button>
                            </span>
                            <span class="input-group-btn" style="margin-right: 0px!important;">
                              <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Costume Date
                                <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu pull-right">
                                <li><a href="?startDate={{urlencode(date('d F Y',strtotime('-1day')))}}&endDate={{urlencode(date('d F Y'))}}">One Day</a></li>
                                <li><a href="?startDate={{urlencode(date('d F Y',strtotime('-1week')))}}&endDate={{urlencode(date('d F Y'))}}">One Week</a></li>
                                <li><a href="?startDate={{urlencode(date('d F Y',strtotime('-1month')))}}&endDate={{urlencode(date('d F Y'))}}">One Month</a></li>
                                <li><a href="?startDate={{urlencode(date('d F Y',strtotime('-1year')))}}&endDate={{urlencode(date('d F Y'))}}">One Year</a></li>
                              </ul>
                            </span>
                          </div>
                          </div>
                        </div>
                      </div>
                      </div>
                    </form>
                    </div>
                    <!--date range-->
<div class="row">
<div class="col-md-12 col-xs-12">
<div class="row">
                    <div class="table-responsive">
                      <table id="datatables" class="table table-striped  bulk_action table-bordered " >
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Nomor Rkb </th>
                            <th class="column-title">Department - Section </th>
                            <th class="column-title">Tanggal Rkb </th>
                            <th class="column-title">Ka. Bag. </th>
                            <th class="column-title">KTT </th>
                            <th class="column-title">Part Name </th>
                            <th class="column-title">Part Number </th>
                            <th class="column-title">Quantity</th>
                            <th class="column-title">Remarks</th>
                            <th class="column-title">Cancel Remark </th>
                            <th class="column-title">Status</th>
                            <th class="bulk-actions" colspan="5">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
@if(count($rkb)>0)
@foreach($rkb as $key => $value)
<tr class="even pointer" >
                            <td class=" "  style="text-align: left!important;">{{$value->no_rkb}}
                              @if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
                              <label class="label label-danger" style="float: right!important;cursor: pointer;cursor: hand;" data-toggle="tooltip" title="Remarks : {{$value->remark_cancel}}">Cancel By {{$value->cancel_user}} </label>
                              @endif
                            </td>
                            <td class=" ">{{$value->dept}} - {{$value->det_sect}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} 
                            </td>
                            <td class=" ">

@if($value->cancel_by==null)
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
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Disetujui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->disetujui==2)
                              Cancel
                              @endif
                              @endif
                            </td>
                            <td class=" ">
                            
@if($value->cancel_by==null)
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
                              <label class="label label-warning"> Waiting</label>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->diketahui==1)
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
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->diketahui==2)
                              Cancel
                              @endif                              
                              @endif

                            </td>

                            <td>{{$value->part_name}}</td>
                            <td>{{$value->part_number}}</td>
                            <td>{{$value->quantity}} {{$value->satuan}}</td>
                            <td>{{$value->remarks}}</td>
                            <td style="background-color: rgba(199,64,45,0.07);">{{$value->remark_cancel}}</td>
                            <td>
@php
  
if($value->cancel_by!=null){
  echo "Cancel By ".strtoupper($value->cancel_by)."\n Remarks : ".$value->cancel_remarks;
}
@endphp

                            </td>
                          </tr>
@endforeach

 @else
            <tr class="odd pointer">
                            <td class="a-center" align="center"  colspan="11">
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


</div></div></div></div></div>
@include('layout.footer')

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
<div id="konten_modal"></div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('DataTables/datatables.min.js')}}"></script>

@php
  if(!isset($_GET['startDate'])){
    $_GET['startDate']=" ";
  }
  if(!isset($_GET['endDate'])){
    $_GET['endDate']=" ";
  }

@endphp
@include('layout.js')
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
  $(".select-status").selectpicker();
  $("select[id='USER']").change(function(){
    if($("select[id='USER']").val()==""){
      $("select[id='STATUS']").removeAttr("required");
    }else{
      $("select[id='STATUS']").attr("required","required");
    }
  });
  $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });
  $(document).ready(function(){ 
    if( typeof ($.fn.DataTable) === 'undefined'){ return; }
        console.log('rkbPrint');
    $("#datatables").DataTable({
      "order": [[ 0, "desc" ]],
       dom: "Blfrtip", 
       buttons: [
       {
            extend: 'collection',
            text: 'Export',
            className: "btn-sm btn-export",
               buttons: [
               {
                 extend: "csv",
               },
               {
                 extend: "excel"
               }
             ]
         }
      ],
      "searching": true,
      "paging": false,
      "sort": false
    });
  });
  $(".btn-export").css("margin-bottom","15px");

//$(".dt-buttons").after("<div class=\"col-xs-6 pull-right\" style=\"padding-right:0px!important;\"><div id=\"group_print\" class=\"row \"></div></div>");

//$("#group_print").append("<div class=\"col-xs-6 \"><div id=\"reportrange\" style=\"background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc\"><i class=\"glyphicon glyphicon-calendar fa fa-calendar\"></i><span>December 30, 2014 - January 28, 2015</span> <b class=\"caret\"></b></div></div>");
//$("#group_print").append("<div class=\"col-xs-6  \"><button class=\"btn btn-sm btn-success pull-right\" onclick=\"window.open('/rkbPrint','_blank','top=200,left=250','width=500,height=500')\">Print</button></div>");


  //$(".dt-buttons").after("<div id=\"reportrange\" style=\"width:100px;background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc\"><i class=\"glyphicon glyphicon-calendar fa fa-calendar\"></i><span>December 30, 2014 - January 28, 2015</span> <b class=\"caret\"></b></div>");

  
  //$(".btn-export").css("position","absolute");
</script>
@if(isset($_GET['startDate']) && isset($_GET['endDate']))

<script>
  var url = document.location.href;
  var res = url.split("?");
  startDate = "{{urlencode($_GET['startDate'])}}";
  endDate = "{{urlencode($_GET['endDate'])}}";
 $(".dt-buttons").after("<button class=\"btn-print btn btn-sm btn-success pull-right\" onclick=\"window.open('/rkbPrint?"+res[1]+"','_blank','top=0,left=0,width=1,height=1,toolbar=no,scrollbars=no,resizable=no,location=no,menubar=no,status=no')\">Print</button>");
</script>
@else
<script>
 $(".dt-buttons").after("<button class=\"btn-print btn btn-sm btn-success pull-right\" onclick=\"window.open('/rkbPrint','_blank','top=0,left=0,width=1,height=1,toolbar=no,scrollbars=no,resizable=no,titlebar=no,location=no,menubar=no,status=no')\">Print</button>");
</script>
@endif
@endsection