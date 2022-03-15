@extends('layout.master')
@section('title')
ABP-system | Karyawan
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;

            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            }  

.ck-editor__editable {
    min-height: 90px;
}

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
<?php
//dd(date("Y-m-d H:i:s",strtotime("-1 Days")));
$arrRULE = [];
  if(isset($getUser)){
    $arrRULE = explode(',',$getUser->rule);    
  }else{
    ?>
<script>
  window.location="{{url('/logout')}}";
</script>
    <?php } ?>
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
                  <h2>Data Karyawan</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">
<div class="col-lg-12 row">
<div class="row">
  <div class="col-md-6 col-sm-12 col-xs-12">
  <button class="btn btn-primary" id="newKaryawan" data-toggle="modal" data-target="#myModal">Tambah Data Karyawan</button>
</div>

<div class=" col-lg-3 pull-right">
    <form method="get" action="" class="row col-lg-12 input-group pull-right">
    <span>
    <input type="text" name="search" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; } ?>" placeholder="Cari" required class="form-control">
    <?php if(isset($_GET['search'])){ ?>
    <button class="myButtonDiss" type="button">&times;</button>
  <?php } ?>
    </span>
    <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
  </form>
</div>
</div>  
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <div class="col-lg-12">
    <p><b>Jumlah :</b> {{$jumlah}}</p>
  </div>
<div class=""> 
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <div class="table-responsive">
  <table class="table table-bordered text-center">
    <thead>
      <tr class="bg-success">
        <th class="text-center">NIK</th>
        <th class="text-center">Nama</th>
        <th class="text-center">Departemen</th>
        <th class="text-center">Devisi</th>
        <th class="text-center">Jabatan</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @if(count($karyawan)>0)
      @foreach($karyawan as $k => $v)
      <tr>
        <td>{{$v->nik}}</td>
        <td>{{ucwords($v->nama)}}</td>
        <td>{{$v->dept}}</td>
        <td>{{$v->sect}}</td>
        <td>{{ucwords($v->jabatan)}}</td>
        <td class="text-right" width="200px">

          @if(in_array('admin sarpras',$arrRULE))
          <?php
          $cek_user = Illuminate\Support\Facades\DB::table("user_login")->where("nik",$v->nik)->first();
          if(!isset($cek_user->nik)){
          ?>
          <button class="btn btn-xs btn-default" id="createPass" name="createPass" data-id="{{bin2hex($v->nik)}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-key"></i> Create Password</button>
          <?php } ?>
          @endif
          <button class="btn btn-xs btn-warning" id="editKaryawan" name="editKaryawan" data-id="{{bin2hex($v->nik)}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
          <a href="{{url('/sarpras/data/karyawan/delete-')}}{{bin2hex($v->nik)}}" class="btn btn-xs btn-danger" id="deleteKaryawan" name="deleteKaryawan"><i class="fa fa-trash"></i></a>
        </td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="6" style="background-color: rgba(0,0,0,0.5);color:#fff;" class="text-center">No Have Record!</td>
      </tr>
      @endif
    </tbody>
  </table>
  </div>
  </div>
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p><b>Jumlah :</b> {{$jumlah}}</p>
  </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
  <!---PAGINATION-->
  {{$karyawan->links()}}
  <!---PAGINATION-->
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
@endsection

@section('js')
<!-- Datatables -->
    <!-- FastClick -->


@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
<script>
    $(".myButtonDiss").click(function(){
      document.location= "{{url('/sarpras/data/karyawan')}}";
    });
  $("button[id=newKaryawan]").click(function(){
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/karyawan/form')}}",
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=editKaryawan]").click(function(){
    eq = $("button[id=editKaryawan]").index(this);
    data_id = $("button[id=editKaryawan]").eq(eq).attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/karyawan/edit')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id},
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=createPass]").click(function(){
    eq = $("button[id=createPass]").index(this);
    data_id = $("button[id=createPass]").eq(eq).attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/karyawan/create/pwd')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id},
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
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