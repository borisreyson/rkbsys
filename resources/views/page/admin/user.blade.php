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
    <style>
      .disable, li[class=disable] a{
        background-color: #F24949;
        color: #f8f8f8;
      }
      .enable, li[class=enable] a{
        background-color:#5EBFAD;
        color: #f8f8f8;
      }
      #users{
        border:1px solid rgba(0,0,0,0.5);
        border-radius: 5px;
        height: 140px;
        width: 100%;
        padding: 5px;
        margin: 15px;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.25);
      }
      #users:hover{
        background-color: #fff;
        cursor: pointer;
        cursor: hand;
        color: #000;
      }
      table tr td {
        padding: 5px;
      }
      .melayang{
        position: absolute!important;
        top: 0;
        right: 0;
        margin-top: 20px;
        border-radius: 25px;
      }
      .btn-menus{
        border-radius: 25px;
        background-color: #F5BD42;
        color: #3E5902;
      }
      .btn-menus:hover,.btn-menus:active,.btn-menus:link,.btn-menus:visited{
        background-color: #f8f8f8;
        border:1px #333 solid;
      }
    </style>
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
  <div class="row" style="font-weight: bolder;">
    @foreach($user as $k => $v)
    @if($v->status==0)
 <div class="col-lg-4 col-md-6 col-xs-12" >
  <div id="users" class="enable">
    <table>
      <tr>
        <td>Username</td>
        <td>:</td>
        <td>{{$v->username}}</td>
      </tr>
      <tr>
        <td>Nama</td>
        <td>:</td>
        <td>{{$v->nama_lengkap}}</td>
      </tr>
      <tr>
        <td>Section</td>
        <td>:</td>
        <td>{{$v->sect}}</td>
      </tr>
      <tr>
        <td>Department</td>
        <td>:</td>
        <td>{{$v->dept}}</td>
      </tr>
    </table>
    <div class="btn-group dropright melayang">
    <button class="btn btn-xs btn-menus dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-ellipsis-h"></i>
    </button>
    <ul role="menu" class="dropdown-menu pull-right ">
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.password')}}">Change Password</a></li>
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.html')}}">Edit</a></li>
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.email')}}">Email</a></li>
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.nik')}}">NIK</a></li>
    <li><a href="#" id_user="{{bin2hex($v->id_user)}}" name="editRule" id="editRule" data-toggle="modal" data-target="#myModal">Rule</a></li>
    @if($v->status==0)
    <li class="disable"><a href="{{url('/form_user/'.bin2hex($v->username).'.disable')}}">Disable</a></li>
    @else
    <li class="enable"><a href="{{url('/form_user/'.bin2hex($v->username).'.enable')}}">Enable</a></li>
    @endif
    <li><a href="{{url('/api/'.bin2hex($v->id_user).'.del')}}" class="">Delete</a></li>
    </ul>
    </div>
 </div>
 </div>
 @else
 <div class="col-lg-4 col-md-6 col-xs-12" >
  <div id="users" class="disable">
    <table>
      <tr>
        <td>Username</td>
        <td>:</td>
        <td>{{$v->username}}</td>
      </tr>
      <tr>
        <td>Nama</td>
        <td>:</td>
        <td>{{$v->nama_lengkap}}</td>
      </tr>
      <tr>
        <td>Section</td>
        <td>:</td>
        <td>{{$v->sect}}</td>
      </tr>
      <tr>
        <td>Department</td>
        <td>:</td>
        <td>{{$v->dept}}</td>
      </tr>
    </table>
    <div class="btn-group dropright melayang">
    <button class="btn btn-xs btn-menus dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-ellipsis-h"></i>
    </button>
    <ul role="menu" class="dropdown-menu pull-right ">
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.password')}}">Change Password</a></li>
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.html')}}">Edit</a></li>
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.email')}}">Email</a></li>
    <li><a href="{{url('/form_user/'.bin2hex($v->username).'.nik')}}">NIK</a></li>
    <li><a href="#" id_user="{{bin2hex($v->id_user)}}" name="editRule" id="editRule" data-toggle="modal" data-target="#myModal">Rule</a></li>
    @if($v->status==0)
    <li class="disable"><a href="{{url('/form_user/'.bin2hex($v->username).'.disable')}}">Disable</a></li>
    @else
    <li class="enable"><a href="{{url('/form_user/'.bin2hex($v->username).'.enable')}}">Enable</a></li>
    @endif
    <li><a href="{{url('/api/'.bin2hex($v->id_user).'.del')}}" class="">Delete</a></li>
    </ul>
    </div>
 </div>
 </div>
 @endif
 @endforeach            
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
<div class="modalKonten"></div>
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
  //tags input 
    
  $("a[id=editRule]").click(function(){
    eq      = $("a[id=editRule]").index(this);
    id_user = $("a[id=editRule]").eq(eq).attr("id_user");
    $.ajax({
      type:"POST",
      url :"{{url('/rule/user/edit')}}",
      data:{id_user:id_user,_token:"{{csrf_token()}}"},
      success:function(res){
        $("div[class=modalKonten]").html(res);
        
      }

    });
  });
 $(".myButtonDiss").click(function(){
      document.location= "{{url('rule/user')}}";
    });
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
