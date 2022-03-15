@extends('layout.master')
@section('title')
ABP-system | Master User
@endsection
@section('css')
@include('layout.css')
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
                    <h2>Master<small>User</small></h2>
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
    <a href="{{url('/form_user')}}" class="btn btn-default col-md-4 col-xs-12">Buat User Baru</a>
    </div>
    <div class=" col-md-6 col-xs-12">
    <div class="row">
      <form action="" class="col-md-offset-6 col-md-6 col-xs-12" method="GET">
        <div class="input-group">
          <input type="text" name="cari" class="form-control" placeholder="Search for...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
      </form>
    </div>  
  </div>
</div>
  		<br/>
<div class="row">
<div class="col-md-12 col-xs-12">
                    <div class="table-responsive">
                      <table id="" class="table table-striped ">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">No </th>
                            <th class="column-title">Username</th>
                            <th class="column-title">Nama Lengkap</th>
                            <th class="column-title">Email</th>
                            <th class="column-title">Department</th>
                            <th class="column-title">Section</th>
                            <th class="column-title">Level</th>
                            <th class="column-title">NIK</th>
                            <th class="column-title">Status</th>
                            <th class="column-title no-link last" align="right"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="5">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
@if(count($user)>0)
@foreach($user as $k => $v)
@if($k%2)
                          <tr class="even pointer">
                            <td class=" ">{{$k+1}}</td>
                            <td class=" ">{{$v->username}}</td>
                            <td class=" ">{{$v->nama_lengkap}}</td>
                            <td class=" ">{{$v->email}}</td>
                            <td class=" ">{{$v->dept}}</td>
                            <td class=" ">{{$v->sect}}</td>
                            <td class=" ">{{$v->desk_lvl or '-'}}</td>
                            <td class=" ">{{$v->nik}}</td>
                            <td class=" ">@if($v->status==0) Enable @else <font style="color: red">Disable</font> @endif</td>
                            <td class=" " width="100px">
<div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
<ul role="menu" class="dropdown-menu pull-right ">
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.password')}}">Change Password</a></li>
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.html')}}">Edit</a></li>
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.email')}}">Email</a></li>
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.nik')}}">NIK</a></li>
@if($v->status==0)
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.disable')}}">Disable</a></li>
@else
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.enable')}}">Enable</a></li>
@endif
<li><a href="{{url('/api/'.bin2hex($v->id_user).'.del')}}" class="">Delete</a></li>
</ul>
</div>                        
                            </td>
                          </tr>
@else
                          <tr class="odd pointer">
                            <td class=" ">{{$k+1}}</td>
                            <td class=" ">{{$v->username}}</td>
                            <td class=" ">{{$v->nama_lengkap}}</td>
                            <td class=" ">{{$v->email}}</td>
                            <td class=" ">{{$v->dept}}</td>
                            <td class=" ">{{$v->sect}}</td>
                            <td class=" ">{{$v->desk_lvl or '-'}}</td>
                            <td class=" ">{{$v->nik}}</td>
                            <td class=" ">@if($v->status==0) Enable @else <font style="color: red">Disable</font> @endif</td>
                            <td class=" " width="100px">
<div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
<ul role="menu" class="dropdown-menu pull-right ">
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.password')}}">Change Password</a></li>
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.html')}}">Edit</a></li>
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.email')}}">Email</a></li>
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.nik')}}">NIK</a></li>
@if($v->status==0)
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.disable')}}">Disable</a></li>
@else
<li><a href="{{url('/form_user/'.bin2hex($v->username).'.enable')}}">Enable</a></li>
@endif
<li><a href="{{url('/api/'.bin2hex($v->id_user).'.del')}}" class="">Delete</a></li>
</ul>
</div>                        
                            </td>
                          </tr>
@endif
@endforeach
@else
                          <tr class="even pointer">
                            <td class=" " colspan="10" align="center">Not have recored yet!</td>
                          </tr>
@endif
                        </tbody>
                    </table>
                </div>
                <div class="text-center">{{$user->links()}}</div>
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
  $("#datatables").dataTable();
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
