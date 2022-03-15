@extends('layout.master')
@section('title')
ABP-system | Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
 <!-- Datatables -->
    <link href="{{asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
<style>
  tbody tr td{
    text-align: center;
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
                    <h2>Rencana Kebutuhan Barang {{hex2bin($no_rkb)}}</h2>
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
                    <div class="table-responsive">
                      <table id="datatables" class="table table-striped  bulk_action table-bordered" style="width: 100%;">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Nomor Rkb </th>
                            <th class="column-title">Department - Section </th>
                            <th class="column-title">Tanggal Rkb </th>
                            <th class="column-title">Ka. Bag. </th>
                            <th class="column-title" width="150px">KTT </th>
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
                          <tr class="even pointer">
                            <td class=" "  style="text-align: left!important;">{{$value->no_rkb}}
                              @if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
                              <label class="label label-danger" style="float: right!important;">Cancel By {{$value->cancel_user}} {{$value->cancel_section}}</label>
                              @endif
                            </td>
                            <td class=" ">{{$value->dept}} - {{$value->section}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} 
                            </td>
                            <td class=" ">
                              @if($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @else
                              @if($value->cancel_user==null)                              
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_user!="ktt")
                              <label class="label label-danger">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                            </td>
                            <td class=" ">
                              @if($value->diketahui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_diketahui))}}</label>
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_user=="ktt")
                              <label class="label label-danger">Cancel By {{$value->cancel_user}}</label>
                              @else
                              -
                              @endif
                              @endif
                              @endif
                            </td>
                            <td class=" last" align="center">
                              @if($value->cancel_user==null)
                              <a href="{{url('/convert/'.bin2hex($value->no_rkb).'.PO')}}" class="btn btn-default btn-xs">Convert To PO</a>
                              @endif
                            	<a href="#" class="btn btn-primary btn-xs" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">Details</a>
                            </td>
                          </tr>
 @else
                          <tr class="odd pointer">
                            <td class=" " style="text-align: left!important;">{{$value->no_rkb}}</td>
                            <td class=" ">{{$value->dept}} - {{$value->section}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} </td>
                            <td class=" ">
                              @if($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s , d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_user!="ktt")
                              <label class="label label-danger">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                               </td>
                            <td class=" ">
                              @if($value->diketahui==1)
                              <label class="label label-success">{{date("H:i:s , d F Y",strtotime($value->tgl_diketahui))}}</label>
                              @else
                              @if($value->cancel_user==null)
                              
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_user=="ktt")
                              <label class="label label-danger">Cancel By {{$value->cancel_user}}</label>
                              @else
                              -
                              @endif
                              @endif
                              
                              @endif</td>
                            <td class=" last" align="center">
                              @if($value->cancel_user==null)
                              <a href="{{url('/convert/'.bin2hex($value->no_rkb).'.PO')}}" class="btn btn-default btn-xs">Convert To PO</a>
                              @endif
                              <a href="#" class="btn btn-primary btn-xs" data-id="{{$value->no_rkb}}" id="details" data-toggle="modal" data-target="#myModal">Details</a>
                            </td>
                          </tr>
@endif
@endforeach
@else
						<tr class="odd pointer">
                            <td class="a-center" align="center"  colspan="6">
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

<!-- Datatables -->
    <script src="{{asset('/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{asset('/vendors/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{asset('/vendors/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('/vendors/pdfmake/build/vfs_fonts.js')}}"></script>


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
  $("#datatables").dataTable({
    "order": [[ 1, "desc" ]]
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