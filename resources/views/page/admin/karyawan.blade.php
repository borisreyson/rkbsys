@extends('layout.master')
@section('title')
ABP-system | Data Karyawan
@endsection
@section('css')
 @include('layout.css')
    <!-- bootstrap-progressbar -->
    <link href="{{asset('/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<?php
$arrRULE = [];
  if(isset($getUser)){
    $arrRULE = explode(',',$getUser->rule);    
  }else{
    ?>
<script>
  window.location="{{url('/logout')}}";
</script>
    <?php } ?>
<!-- page content -->

<div class="right_col" role="main">
 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Data<small> Karyawan</small></h2>
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
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <button class="btn btn-default" name="tmh_karyawan"  data-toggle="modal" data-target="#myModal">Tambah data Karyawan</button>
  <div class="col-lg-6 pull-right">
  <div class="row">
    <form method="get" class="col-xs-4 pull-right input-group">
    <input type="text" class="cari form-control" name="cari" placeholder="Cari...">
    <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
          </span>
    </form>
    </div>
  </div>
</div>
<div class="col-lg-12 col-sm-12 col-xs-12">
  @if(isset($_GET['status']))
  @if($_GET['status']=="aktif")
  <a href="?status=aktif" class="btn btn-primary active">Karyawan Aktif : {{$aktiv}}</a> 
  @else
  <a href="?status=aktif" class="btn btn-primary">Karyawan Aktif : {{$aktiv}}</a> 
  @endif
  @else
  <a href="?status=aktif" class="btn btn-primary">Karyawan Aktif : {{$aktiv}}</a> 
  @endif
</div>
<div class="col-lg-12 col-sm-12 col-xs-12">

  @if(isset($_GET['status']))
  @if($_GET['status']=="tidak_aktif")
  <a href="?status=tidak_aktif" class="btn btn-danger active">Karyawan Tidak Aktif : {{$nonaktiv}}</a> 
  @else
  <a href="?status=tidak_aktif" class="btn btn-danger">Karyawan Tidak Aktif : {{$nonaktiv}}</a> 
  @endif
  @else
  <a href="?status=tidak_aktif" class="btn btn-danger">Karyawan Tidak Aktif : {{$nonaktiv}}</a> 
  @endif  
</div>
<div class="col-lg-12 col-sm-12 col-xs-12">
  <hr>
  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr class="text-center bg-info">
                          <td>No</td>
                          <td>Nik</td>
                          <td>Nama</td>
                          <td>Departemen</td>
                          <td>Devisi</td>
                          <td>Jabatan</td>
                          <td>Perusahaan</td>
                          <td>Aksi</td>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($karyawan as $k => $v)
                          @php
                          $sect = Illuminate\Support\Facades\DB::table("section")->where("id_sect",$v->devisi)->first();
                          @endphp
                          @if($v->disableKaryawan==1)
                        <tr class="text-center label-danger" style="color: white;">
                          <td style="text-decoration-line: line-through; ">{{$v->no}}</td>
                          <td style="text-decoration-line: line-through; ">{{$v->nik}}</td>
                          <td style="text-decoration-line: line-through; ">{{$v->nama}}</td>
                          <td style="text-decoration-line: line-through; ">{{$v->dept}}</td>
                          <td style="text-decoration-line: line-through; ">{{$sect?$sect->sect:"-"}}</td>
                          <td style="text-decoration-line: line-through; ">{{$v->jabatan}}</td>
                          @if($v->perusahaan>=0)
                          <td style="text-decoration-line: line-through; ">{{$v->nama_perusahaan}}</td>
                          @else
                          <td style="text-decoration-line: line-through; ">-</td>
                          @endif
                          <td>
                             @if(in_array('password karyawan',$arrRULE))
                            <button class="btn btn-default btn-xs" name="new_password" data-toggle="modal" data-target="#myModal" nik="{{$v->nik}}"><i class="fa fa-key"></i> Create Password </button>
                            @endif
                            @if(in_array('edit karyawan',$arrRULE))
                            <button class="btn btn-warning btn-xs" nik="{{$v->nik}}" name="editKar" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
                            @endif
                            <a href="{{url('/data/karyawan/admin/enable?nik='.$v->nik)}}" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>
                          </td>
                        </tr>
                        @else
                        <tr class="text-center">
                          <td>{{$v->no}}</td>
                          <td>{{$v->nik}}</td>
                          <td>{{$v->nama}}</td>
                          <td>{{$v->dept}}</td>
                          <td>{{$sect?$sect->sect:"-"}}</td>
                          <td>{{$v->jabatan}}</td>
                          @if($v->perusahaan>=0)
                          <td>{{$v->nama_perusahaan}}</td>
                          @else
                          <td>-</td>
                          @endif
                          <td>
                             @if(in_array('password karyawan',$arrRULE))
                            <button class="btn btn-default btn-xs" name="new_password" data-toggle="modal" data-target="#myModal" nik="{{$v->nik}}"><i class="fa fa-key"></i> Create Password </button>
                            @endif
                            @if(in_array('edit karyawan',$arrRULE))
                            <button class="btn btn-warning btn-xs" nik="{{$v->nik}}" name="editKar" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
                            @endif
                            <a href="{{url('/data/karyawan/admin/disable?nik='.$v->nik)}}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                        @endif
                          @endforeach
                      </tbody>
                    </table>
                  </div>
</div>
<div class="col-lg-12 text-center">

@if(isset($_GET['status']))
  @if(isset($_GET['cari']))
  {{$karyawan->appends(['status' => $_GET['status'],'cari' => $_GET['cari']])->links()}}
  @else
  {{$karyawan->appends(['status' => $_GET['status']])->links()}}
  @endif
  @else
  {{$karyawan->links()}}
  @endif
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
  <div class="modal-dialog modal-md" id="modal_dialog">
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
    <!-- jQuery autocomplete -->
    <script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{asset('/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>

@include('layout.js')
<script>
    $("button[name=tmh_karyawan]").click(function(){
      eq = $("button[name=tmh_karyawan]").index(this);
      nik = $("button[name=tmh_karyawan]").eq(eq).attr("nik");
      $.ajax({
        type:"GET",
        url:"{{url('/absen/form/karyawan')}}",
        // data:{nik:nik},
        success:function(res){
          $("div[id=konten_modal]").html(res);
        }
      });
    });

    $("button[name=editKar]").click(function(){
      eq = $("button[name=editKar]").index(this);
      nik = $("button[name=editKar]").eq(eq).attr("nik");
      $.ajax({
        type:"GET",
        url:"{{url('/absen/edit/karyawan')}}",
        data:{nik:nik},
        success:function(res){
          $("div[id=konten_modal]").html(res);
        }
      });
    });


    $("button[name=new_password]").click(function(){
      eq = $("button[name=new_password]").index(this);
      nik = $("button[name=new_password]").eq(eq).attr("nik");
      $.ajax({
        type:"GET",
        url:"{{url('/karyawan/createpassword')}}",
        data:{nik:nik},
        success:function(res){
          $("div[id=konten_modal]").html(res);
        }
      });
    });
    $("select").selectpicker();
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