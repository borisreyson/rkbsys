@extends('layout.master')
@section('title')
ABP-system | HSE - Form Inspeksi {{$createForm->namaForm or ""}}
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
                  <h2>Master From Inspeksi {{$createForm->namaForm or ""}}</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

<div class="row col-lg-10 col-lg-offset-2">
<form id="formInspeksi" action="" data-parsley-validate class="form-horizontal form-label-left" method="post">
{{csrf_field()}}
@if(isset($inspeksiListEdit))
<input type="hidden" name="idListPut" value="{{bin2hex($inspeksiListEdit->idList)}}">
<input type="hidden" name="_method" value="PUT">        
@endif
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="idSub">Sub Form <span class="required">*</span>
  </label>
  <div class="col-md-5 col-sm-6 col-xs-12">
    <select class="form-control" name="idSub" id="idSub">
      <option value="0">--Pilih--</option>
      @foreach($dataSub as $k => $v)
      @if(isset($_GET['idSub']))
      @if(hex2bin($_GET['idSub'])==$v->idSub)
      <option value="{{$v->idSub}}" selected="selected">({{$v->numSub}}) {{$v->nameSub}}</option> 
      @else
      <option value="{{$v->idSub}}">({{$v->numSub}}) {{$v->nameSub}}</option> 
      @endif
      @else
      <option value="{{$v->idSub}}">({{$v->numSub}}) {{$v->nameSub}}</option> 
      @endif
      @endforeach
    </select>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">  
  <a href="{{url('/hse/admin/inspeksi/form/create/sub?uid='.$_GET['uid'])}}" target="_blank" class="btn btn-primary"><i class="fa fa-plus"></i></a>
</div>  
</div>  
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="listInspeksi">HAL YANG DI PERIKSA <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <textarea type="text" id="listInspeksi" required="required" name="listInspeksi" class="form-control col-md-7 col-xs-12" placeholder="HAL YANG DI PERIKSA">{{$inspeksiListEdit->listInspeksi or ''}}</textarea>
  </div>
</div>
<div class="form-group">
  <div class="col-md-6 col-md-offset-3 col-sm-offset-6 col-sm-6 col-xs-12">
  <input type="submit" name="submit" class="btn btn-primary " value="Submit">
  <a href="{{url('/hse/admin/inspeksi/form/create?uid='.$_GET['uid'])}}" name="reset" class="btn btn-danger">Reset</a>
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
      <th class="text-center" width="80">NOMOR</th>
      <th class="text-center">HAL YANG DI PERIKSA</th>
      <th class="text-center">YES</th>
      <th class="text-center">NO</th>
      <th class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @if(count($subData)>0)
    @foreach($subData as $kk => $vv)
    <?php $z =1; ?>
    <tr>
      <td style="font-weight: bolder;" class="text-center">{{$vv->numSub}}</td>
      <td style="font-weight: bolder;" colspan="3">{{$vv->nameSub}}</td>
    </tr>
    <?php 
        $dataList = Illuminate\Support\Facades\DB::table("hse.form_inspeksi_list")
        ->where("idForm",($vv->idForm))
        ->where("idSub",($vv->idSub))
        ->get();
    ?>
    @foreach($dataList as $k => $v)
    <tr>
      <td class="text-center">{{$z}}</td>
      <td>{{$v->listInspeksi}}</td>
      <td class="text-center">
        <label class="checkbox-inline">
        <input type="radio" name="plilihan{{$k}}" value="yes"> 
        </label>
      </td>
      <td class="text-center">
        <label class="checkbox-inline">
        <input type="radio" name="plilihan{{$k}}" value="no"></label>
      </td>
      <td>
        <a href="{{'/hse/admin/inspeksi/form/create?uid='.$_GET['uid'].'&idSub='.bin2hex($v->idSub).'&idList='.bin2hex($v->idList)}}" class="btn btn-xs btn-warning">Edit</a></td>
    </tr>
    <?php $z++; ?>
    @endforeach
    @endforeach
    @else
    <?php $z =1;
        $dataList = Illuminate\Support\Facades\DB::table("hse.form_inspeksi_list")
        ->where("idForm",($createForm->idForm))
        ->get();
    ?>
    @foreach($dataList as $k => $v)
    <tr>
      <td class="text-center">{{$z}}</td>
      <td>{{$v->listInspeksi}}</td>
      <td class="text-center">
        <label class="checkbox-inline">
        <input type="radio" name="plilihan{{$k}}" value="yes"> 
        </label>
      </td>
      <td class="text-center">
        <label class="checkbox-inline">
        <input type="radio" name="plilihan{{$k}}" value="no"></label>
      </td>
      <td>
        <a href="{{'/hse/admin/inspeksi/form/create?uid='.$_GET['uid'].'&idSub='.bin2hex($v->idSub).'&idList='.bin2hex($v->idList)}}" class="btn btn-xs btn-warning">Edit</a></td>
    </tr>
    <?php $z++; ?>
    @endforeach
    @endif
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