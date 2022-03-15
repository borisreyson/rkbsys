@extends('layout.master')
@section('title')
ABP-system | Master Satuan
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
  
 .myButtonDiss{
  background-color: transparent;
  border: 0px;
  position: absolute;
  z-index: 999;
  font-size: 25px;
  right: 50px;
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
                    <h2>Master<small> Satuan</small></h2>
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
<div class="col-md-5 col-xs-12">
  <div class="row">
                      
    <div class=" col-md-6 col-xs-12">
      <a href="{{url('/form_satuan')}}" class="btn btn-default">Buat Satuan Baru</a>
    </div>
    <div class=" col-md-6 col-xs-12">
    <div class="row">
      <form action="" class=" col-md-12 col-xs-12" method="GET">
        <div class="input-group">
          <span>
    <input type="text" name="cari" value="<?php if(isset($_GET['cari'])){ echo $_GET['cari']; } ?>" placeholder="Cari" required class="form-control">
    <?php if(isset($_GET['cari'])){ ?>
    <button class="myButtonDiss" type="button">&times;</button>
  <?php } ?>
    </span>
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
      </form>
    </div>  
  </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-striped ">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" width="50px">No </th>
                            <th class="column-title">Satuan</th>
@if($_SESSION['section']=="IT")
                            <th class="column-title">User Entry</th>
@endif
                            <th class="column-title no-link last" align="right"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="3">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>

@if(count($satuan)>0)
@php
  $z = $satuan->firstItem();
@endphp
@foreach($satuan as $k => $v)
@if($k%2)
                          <tr class="even pointer">
                            <td class=" ">{{$z}}</td>
                            <td class=" ">{{$v->satuannya}}</td>
@if($_SESSION['section']=="IT")
                            <td class=" ">{{$v->user_entry}}</td>
@endif
                            <td class=" " width="150px">
                              <a href="{{url('/form_satuan/'.$v->no.'.html')}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/form_satuan/'.$v->no.'.del')}}" class="btn btn-danger btn-xs">Delete</a>
                            </td>
                          </tr>
@else
                          <tr class="odd pointer">
                            <td class=" ">{{$satuan->firstItem()}}</td>
                            <td class=" ">{{$v->satuannya}}</td>
@if($_SESSION['section']=="IT")
                            <td class=" ">{{$v->user_entry}}</td>
@endif
                            <td class=" " width="150px">
                              <a href="{{url('/form_satuan/'.$v->no.'.html')}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/form_satuan/'.$v->no.'.del')}}" class="btn btn-danger btn-xs">Delete</a>
                            </td>
                          </tr>
@endif
@php
  $z++;
@endphp
@endforeach
@else
                          <tr class="even pointer">
                            <td class=" " colspan="4" align="center">Not have recored yet!</td>
                          </tr>
@endif
                        </tbody>
                    </table>
                </div>
                  <div class="pull-right">
                  {{$satuan->links()}}
                  </div>
            </div></div>
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
<script>
   $(".myButtonDiss").click(function(){
      document.location= "{{url('/satuan')}}";
    });
</script>
@include('layout.js')
@endsection