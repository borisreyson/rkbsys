@extends('layout.master')
@section('title')
ABP-system | HSE - Master Hasil Matrik Resiko
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
                  <h2>Master  Hasil Matrik Resiko</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-6 col-lg-offset-6">
  <form id="form_risk" action="" data-parsley-validate class="form-horizontal form-label-left" method="post">
    {{csrf_field()}}
                      @if($editKetResiko)
                      <input type="hidden" name="uidMaster" value="{{$editKetResiko->idResiko}}">
                      <input type="hidden" name="_method" value="PUT">        
                      @endif
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kodeBahaya">Kode Resiko <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="kodeBahaya" required="required" style="font-weight: bolder;" name="kodeBahaya" class="form-control col-md-7 col-xs-12" value="{{$editKetResiko->kodeBahaya or ''}}" placeholder="Kode Resiko"   >
                        </div>
                      </div>                      
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="min">Nilai Minimal <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="min" min="1" max="25" required="required" name="min" class="form-control col-md-7 col-xs-12" placeholder="Nilai Minimal" value="{{$editKetResiko->min or ''}}">
                        </div>
                      </div>     
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max">Nilai Maksimal <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="number" id="min" min="1" max="25" name="max" id="max"  value="{{$editKetResiko->max or ''}}" placeholder="Nilai Maksimal" class="form-control" />
                        </div>
                      </div>   
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategori">Kategori Resiko <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea name="kategori" id="kategori" placeholder="Kategori Resiko" class="form-control" >{{$editKetResiko->kategori or ''}}</textarea>
                        </div>
                      </div>   
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tindakan">Tindakan Resiko <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea name="tindakan" id="tindakan" placeholder="Tindakan Resiko" class="form-control" >{{$editKetResiko->tindakan or ''}}</textarea>
                        </div>
                      </div>   

                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bgColor">Background Color <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group demo1" id="demo1">
                              <input type="text" readonly="readonly" name="bgColor" id="bgColor"  value="{{$editKetResiko->bgColor or '#FFFFFF'}}" class="form-control" />
                              <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                      </div>   
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtColor">Text Color <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group demo1" id="demo1">
                              <input type="text" readonly="readonly" name="txtColor" id="txtColor"  value="{{$editKetResiko->txtColor or '#FFFFFF'}}" class="form-control" />
                              <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                      </div>  
                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-3 col-sm-offset-6 col-sm-6 col-xs-12">
                            <a href="{{url('/hse/admin/matrik/hasil')}}" class="btn btn-danger pull-right">Reset</a>
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </div>
                      </div>  
</form>
<br>
<hr>
  </div>

  <div class="row col-lg-12">
    <div class="table-responsive" style="width: 100%!important;">
<table class="table table-striped table-bordered" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" style="vertical-align: middle;">No</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Kode Resiko</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Nilai Minimal</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Nilai Maksimal</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Kategori Resiko</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Tindakan Resiko</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @if(count($ketResiko)>0)
    @if(isset($_GET['page']))
    @if($_GET['page']!=1)
      @php $z = ($_GET['page']*count($ketResiko))+1; @endphp
    @else
      @php $z = 1; @endphp
    @endif
    @else
    @php $z = 1; @endphp
    @endif
    @foreach($ketResiko as $k => $v)
    <tr>
      <td class="text-center" style="vertical-align: middle;">{{$z}}</td>
      <td class="text-center" style="vertical-align: middle;background-color: {{$v->bgColor}};color: {{$v->txtColor}}; font-weight: bolder;">{{$v->kodeBahaya}}</td>
      <td class="text-center" style="vertical-align: middle;">{{$v->min}}</td>
      <td class="text-center" style="vertical-align: middle;">{{$v->max}}</td>
      <td class="text-center" style="vertical-align: middle;background-color: {{$v->bgColor}};color: {{$v->txtColor}}; font-weight: bolder;">{{$v->kategori}}</td>
      <td class="text-center" style="vertical-align: middle;">{{$v->tindakan}}</td>
      <td style="vertical-align: middle;text-align:center;">
        @if(isset($_GET['page']))
        <a href="{{url('hse/admin/matrik/hasil?uid='.bin2hex($v->idResiko).'&page='.$_GET['page'])}}" id="edtMatrikResiko" name="edtMatrikResiko" class="btn btn-xs btn-warning" >Edit <i class="fa fa-pencil"></i></a>
      @else
        <a href="{{url('hse/admin/matrik/hasil?uid='.bin2hex($v->idResiko))}}" id="edtMatrikResiko" name="edtMatrikResiko" class="btn btn-xs btn-warning" >Edit <i class="fa fa-pencil"></i></a>
      @endif
    </td>
    </tr>
    @php
      $z++;
    @endphp
    @endforeach
    <tr class="info">
      <td colspan="40">
       <b>Total Record : {{count($ketResiko)}}</b>
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
  @if(isset($_GET['uid']))
    {{$ketResiko->appends(["uid"=>$_GET['uid']])->links()}}
  @else
    {{$ketResiko->links()}}
@endif
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