@extends('layout.master')
@section('title')
ABP-system | Unit Sarana
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
                  <h2>Data Unit Sarana</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">
<div class="col-lg-12 row">
  <button class="btn btn-primary" id="newUnit" data-toggle="modal" data-target="#myModal">Tambah Data Unit Sarana</button>
  <div class=" col-lg-3 pull-right">
    <form method="get" action="" class="row col-lg-12 input-group pull-right">
    <span>
    <input type="text" name="cari" value="<?php if(isset($_GET['cari'])){ echo $_GET['cari']; } ?>" placeholder="Cari" required class="form-control">
    <?php if(isset($_GET['cari'])){ ?>
    <button class="myButtonDiss" type="button">&times;</button>
  <?php } ?>
    </span>
    <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
  </form>
</div>
</div>

<div class="col-lg-12 row">
  <div class="col-lg-12">
    <p><b>Jumlah :</b> {{$j_unit}}</p>
  </div>
  <table class="table table-bordered text-center">
    <thead>
      <tr class="bg-success">
        <th class="text-center">No Polisi</th>
        <th class="text-center">No LV</th>
        <th class="text-center">Driver</th>
        <th class="text-center">Pic</th>
        <th class="text-center">Merek Type</th>
        <th class="text-center">Jenis</th>
        <th class="text-center">Model</th>
        <th class="text-center">Tahun Pembuatan</th>
        <th class="text-center">Isi Slinder</th>
        <th class="text-center">Warna KB</th>
        <th class="text-center">Warna TNKB</th>
        <th class="text-center">Pemilik</th>
        <th class="text-center">Alamat Pemilik</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @if(count($unit)>0)
      @foreach($unit as $k => $v)
      @if($v->flag_h==0)
      <tr>
        <td>{{$v->no_pol}}</td>
        <td>{{$v->no_lv}}</td>
        <td>{{ucwords($v->nama_d)}}</td>
        <td>{{ucwords($v->nama_k)}}</td>
        <td>{{ucwords($v->merek_type)}}</td>
        <td>{{ucwords($v->jenis)}}</td>
        <td>{{ucwords($v->model)}}</td>
        <td>{{ucwords($v->thn_pembuatan)}}</td>
        <td>{{ucwords($v->isi_slinder)}}</td>
        <td>{{ucwords($v->warna_kb)}}</td>
        <td>{{ucwords($v->warna_tnkb)}}</td>
        <td>{{ucwords($v->nama_p)}}</td>
        <td>{{ucwords($v->alamat_p)}}</td>
        <td>
          <button class="btn btn-xs btn-warning" id="editUnit" name="editUnit" data-id="{{bin2hex($v->no_pol)}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
          <a href="{{url('/sarpras/data/sarana/delete-')}}{{bin2hex($v->no_pol)}}" class="btn btn-xs btn-danger" id="deleteUnit" name="deleteUnit"><i class="fa fa-trash"></i></a>
        </td>
      </tr>
      @else
      <tr class="btn-danger" style="text-decoration: line-through;">
        <td>{{$v->no_pol}}</td>
        <td>{{$v->no_lv}}</td>
        <td>{{$v->driver}}</td>
        <td>{{$v->pic_lv}}</td>
        <td>{{ucwords($v->merek_type)}}</td>
        <td>{{ucwords($v->jenis)}}</td>
        <td>{{ucwords($v->model)}}</td>
        <td>{{ucwords($v->thn_pembuatan)}}</td>
        <td>{{ucwords($v->isi_slinder)}}</td>
        <td>{{ucwords($v->warna_kb)}}</td>
        <td>{{ucwords($v->warna_tnkb)}}</td>
        <td>{{ucwords($v->nama_p)}}</td>
        <td>{{ucwords($v->alamat_p)}}</td>
        <td style="text-decoration: none!important;">
          <button class="btn btn-xs btn-warning" id="editUnit" name="editUnit" data-id="{{bin2hex($v->no_pol)}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
          <a href="{{url('/sarpras/data/sarana/restore-')}}{{bin2hex($v->no_pol)}}" class="btn btn-xs btn-warning" id="undoUnit" name="undoUnit" title="Restore"><i class="fa fa-undo"></i></a>
        </td>
      </tr>

      @endif
      @endforeach
      @else
      <tr>
        <td colspan="14" style="background-color: rgba(0,0,0,0.5);color:#fff;" class="text-center">No Have Record!</td>
      </tr>
      @endif
    </tbody>
  </table>
  <div class="col-lg-12">
    <p><b>Jumlah :</b> {{$j_unit}}</p>
  </div>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
  {{$unit->links()}}
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
      document.location= "{{url('/sarpras/data/sarana')}}";
    });
  $("button[id=newUnit]").click(function(){
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/sarana/form')}}",
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=editUnit]").click(function(){
    eq = $("button[id=editUnit]").index(this);

    data_id = $("button[id=editUnit]").eq(eq).attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/sarana/edit')}}",
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