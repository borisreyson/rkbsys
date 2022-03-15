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
                      @if(isset($inspeksiFieldEdit))
                      <input type="hidden" name="uid" value="{{$inspeksiFieldEdit->idField}}">
                      <input type="hidden" name="_method" value="PUT">        
                      @endif
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nameField">Tipe <span class="required">*</span>
                          </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="btn-group ">
                              @if(isset($_GET['tipe']))
                            <a href="?uid={{$_GET['uid']}}&tipe=text" class="btn btn-default {{($_GET['tipe']=='text')? 'active': ''}}">Text Field</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=textarea" class="btn btn-default {{($_GET['tipe']=='textarea')? 'active': ''}}">Textarea</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=checkbox" class="btn btn-default {{($_GET['tipe']=='checkbox')? 'active': ''}}">Checkbox Group </a>
                            <a href="?uid={{$_GET['uid']}}&tipe=radio" class="btn btn-default {{($_GET['tipe']=='radio')? 'active': ''}}">Radio Group</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=select" class="btn btn-default {{($_GET['tipe']=='select')? 'active': ''}}">Select</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=button" class="btn btn-default {{($_GET['tipe']=='button')? 'active': ''}}">Button</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=file" class="btn btn-default {{($_GET['tipe']=='file')? 'active': ''}}">File</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=date" class="btn btn-default {{($_GET['tipe']=='date')? 'active': ''}}">Date</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=number" class="btn btn-default {{($_GET['tipe']=='number')? 'active': ''}}">Number</a>
                            @else
                            <a href="?uid={{$_GET['uid']}}&tipe=text" class="btn btn-default">Text Field</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=textarea" class="btn btn-default">Textarea</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=checkbox" class="btn btn-default">Checkbox Group </a>
                            <a href="?uid={{$_GET['uid']}}&tipe=radio" class="btn btn-default ">Radio Group</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=select" class="btn btn-default ">Select</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=button" class="btn btn-default ">Button</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=file" class="btn btn-default">File</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=date" class="btn btn-default ">Date</a>
                            <a href="?uid={{$_GET['uid']}}&tipe=number" class="btn btn-default ">Number</a>
                            @endif
                            </div>
                        </div>
                      </div>
                      @if(isset($_GET['tipe']))
                      <input type="text" name="tipe" value="{{isset($_GET['tipe'])?$_GET['tipe']:''}}">
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nameField">Required
                          </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class="checkbox">
                            <label class="col-md-12 col-xs-12"><input type="checkbox" id="required" name="required" value="{{$inspeksiFieldEdit->required or ''}}"></label>
                          </div>
                        </div>
                      </div>  

                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nameField">Name <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="nameField" required="required" name="nameField" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->nameField or ''}}" placeholder="Nama (Tidak Boleh Pakai Spasi)">
                        </div>
                      </div>                      
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textLabel">Label
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="textLabel" name="textLabel" class="form-control col-md-7 col-xs-12" placeholder="Label Text" value="{{$inspeksiFieldEdit->textLabel or ''}}">
                        </div>
                      </div>      

                      @if(isset($_GET['tipe']))  
                      @if($_GET['tipe']=="text" || $_GET['tipe']=="textarea" || $_GET['tipe']=="checkbox" || $_GET['tipe']=="radio" || $_GET['tipe']=="select" || $_GET['tipe']=="number" )                
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="helperText">Help Text
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="helperText" name="helperText" class="form-control col-md-7 col-xs-12" placeholder="Tag Field" value="{{$inspeksiFieldEdit->helperText or ''}}">
                        </div>
                      </div>                    
                      @endif
                      @if($_GET['tipe']=="text" || $_GET['tipe']=="textarea" || $_GET['tipe']=="select" || $_GET['tipe']=="number" )
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="placeholder">Placeholder
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="placeholder" name="placeholder" class="form-control col-md-7 col-xs-12" placeholder="Placeholder" value="{{$inspeksiFieldEdit->placeholder or ''}}">
                        </div>
                      </div>    
                      @endif                
                      @endif                

                      @if($_GET['tipe']=="text" || $_GET['tipe']=="button" || $_GET['tipe']=="number")     
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valueField">Value 
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="valueField" name="valueField" class="form-control col-md-7 col-xs-12" placeholder="Value" value="{{$inspeksiFieldEdit->valueField or ''}}">
                        </div>
                      </div> 
                      @endif
                      @if(isset($_GET['tipe']))  
                      @if($_GET['tipe']=="text" || $_GET['tipe']=="button")                 
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sizeField">Tipe <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="tipeField" type="text" name="tipeField" class="form-control col-md-7 col-xs-12" placeholder="Tipe">

                              @if(isset($inspeksiFieldEdit))
                      @if($_GET['tipe']=="text")      
<option value="text" {{($inspeksiFieldEdit=='text')? 'selected="selected"':''}}>Text</option>
<option value="password" {{($inspeksiFieldEdit=='password')? 'selected="selected"':''}}>Password</option>
<option value="email" {{($inspeksiFieldEdit=='email')? 'selected="selected"':''}}>Email</option>
<option value="color" {{($inspeksiFieldEdit=='color')? 'selected="selected"':''}}>Color</option>
<option value="tel" {{($inspeksiFieldEdit=='tel')? 'selected="selected"':''}}>Telp</option>
                              @elseif($_GET['tipe']=="button")
<option value="button" {{($inspeksiFieldEdit=='button')? 'selected="selected"':''}}>Button</option>
<option value="submit" {{($inspeksiFieldEdit=='submit')? 'selected="selected"':''}}>Submit</option>
<option value="reset" {{($inspeksiFieldEdit=='reset')? 'selected="selected"':''}}>Reset</option>
                              @endif
                                @else
                                @if($_GET['tipe']=="text") 
                                <option value="Text" >Text</option>
                                <option value="Password" >Password</option>
                                <option value="Email" >Email</option>
                                <option value="Color" >Color</option>
                                <option value="Telp" >Telp</option>
                                @elseif($_GET['tipe']=="button")
                                <option value="button" >Button</option>
                                <option value="submit" >Submit</option>
                                <option value="reset" >Reset</option>
                              @endif
                              @endif
                            </select>
                        </div>
                      </div>                
                      @endif
                      @endif

                      @if(isset($_GET['tipe']))  
                      @if($_GET['tipe']=="text" || $_GET['tipe']=="textarea")       
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="maxLength">Max Length
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="maxLength" type="number" name="maxLength" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->maxLength or ''}}">
                        </div>
                      </div>    
                      @endif
                      @endif
                      @if(isset($_GET['tipe']))
                      @if($_GET['tipe']=="button")
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="style">Style
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="style" name="style" class="form-control">
                              <option value="default">Default</option>
                              <option value="danger">Danger</option>
                              <option value="info">Info</option>
                              <option value="primary">Primary</option>
                              <option value="success">Success</option>
                              <option value="warning">Warning</option>
                            </select>
                        </div>
                      </div> 
                      @endif
                      @endif

                      @if(isset($_GET['tipe']))  
                      @if($_GET['tipe']=="checkbox")     
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="maxLength">Options
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table table-bordered form-control-static text-center">
                              <tr>
                                <td><input type="checkbox"></td>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td><input type="checkbox"></td>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td><input type="checkbox"></td>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td colspan="4" class="text-right"><button type="button" class="btn btn-xs btn-danger">Add Options <i class="fa fa-plus"></i></button></td>
                              </tr>
                            </table>
                        </div>
                      </div>
                      @endif
                      @if($_GET['tipe']=="radio")  
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="maxLength">Options
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table table-bordered form-control-static text-center">
                              <tr>
                                <td><input type="radio"></td>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td><input type="radio"></td>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td><input type="radio"></td>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td colspan="4" class="text-right"><button type="button" class="btn btn-xs btn-danger">Add Options <i class="fa fa-plus"></i></button></td>
                              </tr>
                            </table>
                        </div>
                      </div>
                      @endif
                      @if($_GET['tipe']=="select")  
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="maxLength">Options
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table table-bordered form-control-static text-center">
                              <tr>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td><input type="text" name="optionLabel" id="optionLabel" placeholder="Label" class="form-control"></td>
                                <td><input type="text" name="optionValue" id="optionValue" placeholder="Value" class="form-control"></td>
                                <td><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                              <tr>
                                <td colspan="4" class="text-right"><button type="button" class="btn btn-xs btn-danger">Add Options <i class="fa fa-plus"></i></button></td>
                              </tr>
                            </table>
                        </div>
                      </div>
                      @endif
                      @endif
                      @if(isset($_GET['tipe']))  
                      @if($_GET['tipe']=="number")  
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="min">Min
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="min" type="number" name="min" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->min or ''}}">
                        </div>
                      </div> 
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max">Max
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="max" type="number" name="max" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->max or ''}}">
                        </div>
                      </div> 

                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="step">Step
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="step" type="number" name="step" class="form-control col-md-7 col-xs-12" value="{{$inspeksiFieldEdit->step or ''}}">
                        </div>
                      </div> 
                      @endif
                      @endif
                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-3 col-sm-offset-6 col-sm-6 col-xs-12">
                            <a href="{{url('hse/admin/inspeksi/form/create?uid='.bin2hex($createForm->idForm))}}" class="btn btn-danger pull-right">Reset</a>
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </div>
                      </div>  
                      @endif
</form>
<br>
<hr>
  </div>

  <div class="row col-lg-12">
    <div class="table-responsive" style="width: 100%!important;">
<table class="table table-striped" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" style="vertical-align: middle;">Template</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Status</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table></div>
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