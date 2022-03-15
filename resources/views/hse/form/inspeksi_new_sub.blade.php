@extends('layout.master')
@section('title')
ABP-system | HSE - Form Inspeksi {{$createForm->namaForm or ""}} Sub
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
                  <h2>Master From Inspeksi {{$createForm->namaForm or ""}} Sub</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
<div class="row col-lg-6 col-lg-offset-6">
<form id="formInspeksi" action="" data-parsley-validate class="form-horizontal form-label-left" method="post">
{{csrf_field()}}
@if(isset($inspeksiFieldEdit))
<input type="hidden" name="idSub_Form" value="{{bin2hex($inspeksiFieldEdit->idSub)}}">
<input type="hidden" name="_method" value="PUT">        
@endif  
<input type="hidden" name="idForm" value="{{$createForm->idForm}}">        
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="numSub">Nomor Sub <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" id="numSub" required="required" name="numSub" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->numSub or ''}}" placeholder="Nomor Sub">
  </div>
</div> 
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nameSub">Nama Sub <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" id="nameSub" required="required" name="nameSub" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->nameSub or ''}}" placeholder="Nama Sub">
  </div>
</div>
<div class="form-group">
  <div class="col-md-6 col-md-offset-3 col-sm-offset-6 col-sm-6 col-xs-12">
  <input type="submit" name="submit" class="btn btn-primary " value="Submit">
  <a href="{{url('/hse/admin/inspeksi/form/create/sub?uid='.$_GET['uid'])}}" name="reset" class="btn btn-danger">Reset</a>
  </div>
</div> 
</form>
  </div>
<div class="row">
  <div class="col-md-12">
    <br>
    <br>
    <br>
  </div>
<div class="col-md-12">
<table class="table table-bordered">
  <thead>
    <tr class="bg-primary">
      <th class="text-center" width="100">NOMOR SUB</th>
      <th class="text-center">Nama Sub</th>
      <th class="text-center">STATUS</th>
      <th class="text-center">AKSI</th>
    </tr>
  </thead>
  <tbody>
    @foreach($dataSub as $k => $v)
    <tr>
      <td class="text-center">{{$v->numSub}}</td>
      <td class="text-center">{{$v->nameSub}}</td>
      @if($v->flag==0)
      <td class="text-center">Aktif</td>
      <td class="text-center">
        <a class="btn btn-xs btn-warning" href="{{url('/hse/admin/inspeksi/form/create/sub?uid='.$_GET['uid'].'&idSub='.bin2hex($v->idSub))}}">Edit</a>
        <a class="btn btn-xs btn-danger" href="{{url('/hse/admin/inspeksi/form/create/sub?uid='.$_GET['uid'].'&idSub='.$v->idSub.'&status=disable')}}">Disable</a>
      </td>
      @else
      <td class="text-center">Tidak Aktif</td>
      <td class="text-center">
        <a class="btn btn-xs btn-warning" href="{{url('/hse/admin/inspeksi/form/create/sub?uid='.$_GET['uid'].'&idSub='.bin2hex($v->idSub))}}">Edit</a>
        <a class="btn btn-xs btn-success" href="{{url('/hse/admin/inspeksi/form/create/sub?uid='.$_GET['uid'].'&idSub='.$v->idSub.'&status=enable')}}">Enable</a>
      </td>
      @endif
    </tr>
    @endforeach
  </tbody> 
</table>
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