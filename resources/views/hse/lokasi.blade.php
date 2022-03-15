@extends('layout.master')
@section('title')
ABP-system | HSE - Master Lokasi
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
.nowrap{
  white-space: nowrap;
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
                  <h2>Master Lokasi</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">
    <button class="btn btn-primary" id="tmbLokasi" name="tmbLokasi" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Tambah Lokasi</button>
  </div>
  <div class="row col-lg-12">
    <div class="table-responsive" style="width: 100%!important;">
<table class="table table-striped table-bordered" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" style="vertical-align: middle;">No</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Lokasi</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Deskripsi Lokasi</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @if(count($lokasi)>0)
    @if(isset($_GET['page']))
    @php $z = $_GET['page']*count($lokasi); @endphp
    @else
    @php $z = 1; @endphp
    @endif
    @foreach($lokasi as $k => $v)
    <tr>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$z}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{ucwords($v->lokasi)}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{ucwords($v->des_lokasi)}}</td>
      <td style="vertical-align: middle;text-align: center;">
        <button id="edtLokasi" name="edtLokasi" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-warning" uid="{{bin2hex($v->idLok)}}">Edit <i class="fa fa-pencil"></i></button>
      </td>
    </tr>
    @php
      $z++;
    @endphp
    @endforeach
    <tr class="info">
      <td colspan="40">
       <b>Total Record : {{count($lokasi)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="40" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table></div>

<div class="col-lg-12 text-center">
    {{$lokasi->links()}}
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
    $("button[name=tmbLokasi]").click(function(){
      eq = $("button[name=tmbLokasi]").index(this);
      $.ajax({
        type:"GET",
        url:"{{url('/hse/admin/master/lokasi/new')}}",
        // data:{nik:nik},
        success:function(res){
          $("div[id=konten_modal]").html(res);
        }
      });
    });
    $("button[name=edtLokasi]").click(function(){
      eq = $("button[name=edtLokasi]").index(this);
      uid = $("button[name=edtLokasi]").eq(eq).attr("uid");
      $.ajax({
        type:"GET",
        url:"{{url('/hse/admin/master/lokasi/ubah')}}",
        data:{uid:uid},
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