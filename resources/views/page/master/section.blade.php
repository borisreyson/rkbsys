@extends('layout.master')
@section('title')
ABP-system | Master Department
@endsection
@section('css')
 @include('layout.css')
 <!-- Datatables -->
    <link href="{{asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<!-- page content -->

<div class="right_col" role="main">
 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Master<small>Section</small></h2>
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
                      <a href="{{url('/sect/form')}}" class="btn btn-default">Buat Section Baru</a>
                      <a href="{{url('/form_dept')}}" class="btn btn-default">Buat Departemen Baru</a>
                      @if($_SESSION['level']=="administrator")

                      <a href="{{url('/form_user')}}" class="btn btn-default">Buat User Baru</a>
                      @endif
                      <br/>
                      <br/>
<div class="row">
<div class="col-md-12 col-xs-12">
                    <div class="table-responsive">
                      <table class="table table-striped ">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">No </th>
                            <th class="column-title">Id Section</th>
                            <th class="column-title">Section</th>
                            <th class="column-title">Department</th>
                            <th class="column-title no-link last" align="right"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="5">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
@if(count($sect)>0)
@foreach($sect as $k => $v)
@if($k%2)
                          <tr class="even pointer">
                            <td class=" ">{{$k+1}}</td>
                            <td class=" ">{{strtoupper($v->id_sect)}}</td>
                            <td class=" ">{{ucwords(strtolower($v->sect))}}</td>
                            <td class=" ">{{$v->dept}}</td>
                            <td class=" " width="150px">
                              <a href="{{url('/sect/'.bin2hex($v->id_dept).'-'.bin2hex($v->id_sect).'.edit')}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/sect/'.bin2hex($v->id_dept).'-'.bin2hex($v->id_sect).'.del')}}" class="btn btn-danger btn-xs">Delete</a>
                            </td>
                          </tr>
@else
                          <tr class="odd pointer">
                            <td class=" ">{{$k+1}}</td>
                            <td class=" ">{{strtoupper($v->id_sect)}}</td>
                            <td class=" ">{{ucwords(strtolower($v->sect))}}</td>
                            <td class=" ">{{$v->dept}}</td>
                            <td class=" " width="150px">
                              <a href="{{url('/sect/'.bin2hex($v->id_dept).'-'.bin2hex($v->id_sect).'.edit')}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/sect/'.bin2hex($v->id_dept).'-'.bin2hex($v->id_sect).'.del')}}" class="btn btn-danger btn-xs">Delete</a>
                            </td>
                          </tr>
@endif
@endforeach
@else
                          <tr class="even pointer">
                            <td class=" " colspan="5" align="center">Not have recored yet!</td>
                          </tr>
@endif
                        </tbody>
                    </table>
                </div>
                <div id="paging pull-right">{{$sect->links()}}</div>
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
<script>
  
  $("#datatables").dataTable({
    "order": [[ 1, "asc" ]]
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