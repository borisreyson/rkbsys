@extends('layout.master')
@section('title')
ABP-system | HSE - Master Matrik Resiko Keparahan
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
<link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">        
<!-- Bootstrap Colorpicker -->
<link href="{{asset('/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
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
                  <h2>Master Matrik Resiko Keparahan</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
  <div class="row col-lg-12">
    <div class="table-responsive" style="width: 100%!important;">
<table class="table table-striped table-bordered" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" colspan="2" style="vertical-align: middle;font-weight: bolder;">Kemungkinan \ Keparahan</th>
      @foreach($kpResiko as $k => $v)
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->keparahan}}</th>
      @endforeach
    </tr>
    <tr>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">&nbsp;</th>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">Nilai</th>

    @foreach($kpResiko as $k => $v)
      <th class="text-center nowrap" style="vertical-align: middle;">{{$v->nilai}}</th>
    @endforeach
    </tr>
  </thead>
  <?php
    $resiko = $hsResiko;
  ?>
  <tbody>
    @foreach($kmResiko as $k => $v)
    <tr>
      <td class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->kemungkinan}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->nilai}}</td>
    @foreach($kpResiko as $j => $w)
    <?php
      $hasil = $v->nilai*$w->nilai;
          $hsResiko = Illuminate\Support\Facades\DB::table("hse.metrik_resiko")->where("max",">=",$hasil)->where("min","<=",$hasil)->first();

    ?>
      <td class="text-center nowrap" style="vertical-align: middle;background-color: {{$hsResiko->bgColor}};color: {{$hsResiko->txtColor}};font-weight: bolder;"><b>{{$hsResiko->kodeBahaya}}</b> <small style="font-size: 10px;font-weight: bold;">{{$hasil}}</small></td>
    @endforeach
    </tr>
    @endforeach
  </tbody>
</table></div>
<div class="col-lg-12 text-center">
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
<!-- Bootstrap Colorpicker -->
    <script src="{{asset('/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
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