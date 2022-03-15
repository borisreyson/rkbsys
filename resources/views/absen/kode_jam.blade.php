@extends('layout.master')
@section('title')
ABP-system | Kode Jam Roster
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/timepicker/jquery.timepicker.css')}}">
    <!-- Bootstrap Colorpicker -->
    <link href="{{asset('/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;
  overflow-y: auto;
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

.active { z-index: 1!important; }
.border{
  border:1px solid #333;
  margin: 2px!important;
}
.label-success{
  color: white!important;
}
.label-success:hover{
  color: black!important;
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
                  <h2>Absen</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">

<div class="col-lg-12 row">

  <div class="col-lg-6">

    <form @if(isset($_GET['id_kode'])) action="{{url('/absen/user/kode/jam/roster/update')}}" @else action="" @endif method="post" class="form-horizontal col-lg-12 row">
      {{csrf_field()}}
      <?php
      if(isset($_GET['id_kode'])){
        $edit = Illuminate\Support\Facades\DB::table("db_karyawan.kode_jam_masuk")
                ->where("id_kode",$_GET['id_kode'])
                ->first();
              }
      ?>
      @if(isset($_GET['id_kode']))
              <input type="hidden" name="id_kode" value="{{$_GET['id_kode']}}">
      @endif
    <div class="form-group">
    <label class="control-label col-lg-3">Kode Jam</label>
    <div class="col-lg-6">         
      <input type="text" name="kode_jam" id="kode_jam" class="form-control" required="required" @if(isset($edit)) value="{{$edit->kode_jam}}" @endif placeholder="Kode Jam" />
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-lg-3">Deskripsi</label>
    <div class="col-lg-6">
      <textarea name="deskripsi" id="deskripsi" class="form-control" required="required" placeholder="Deskripsi">@if(isset($edit)){{$edit->deskripsi}}@endif</textarea> 
      
    </div>
  </div>  
   <div class="form-group">
    <label class="control-label col-lg-3">Warna Latar</label>
    <div class="col-lg-6">
      <div class="input-group demo1">
        <input type="text" readonly="readonly" name="warna" id="warna"  @if(isset($edit)) value="{{$edit->background}}" @else value="#ffffff" @endif  class="form-control" />
        <span class="input-group-addon"><i></i></span>
      </div>
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-lg-3">Warna Tulisan</label>
    <div class="col-lg-6">
      <div class="input-group demo1">
        <input type="text" readonly="readonly" name="tulisan" id="tulisan"  @if(isset($edit)) value="{{$edit->tulisan}}" @else value="#ffffff" @endif  class="form-control" />
        <span class="input-group-addon"><i></i></span>
      </div>
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-lg-3">Jam Kerja</label>
    <div class="col-lg-6">
      

      <select name="id_jam" id="id_jam" class="form-control" required="required" >
        <option value="">--Pilih--</option>
        @foreach($jamkerja as $k => $v)
      @if(isset($edit))
        @if($edit->id_jam_kerja==$v->no)
        @if($v->no=="33")
        <option value="{{$v->no}}" selected="selected">OFF</option>
        @else
        <option value="{{$v->no}}" selected="selected">{{$v->masuk}} - {{$v->pulang}}</option>
        @endif
        @else
        @if($v->no=="33")
        <option value="{{$v->no}}">OFF</option>
        @else
        <option value="{{$v->no}}">{{$v->masuk}} - {{$v->pulang}}</option>
        @endif
        @endif
      @else
        @if($v->no=="33")
        <option value="{{$v->no}}">OFF</option>
        @else
        <option value="{{$v->no}}">{{$v->masuk}} - {{$v->pulang}}</option>
        @endif
      @endif
      @endforeach
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
      <button class="btn btn-primary" id="kirim" type="submit">
      @if(isset($_GET['id_kode']))Update @else Simpan @endif</button>   
      <a class="btn btn-danger" id="reset" href="{{url($_SERVER['REDIRECT_URL'])}}">Reset</a>   
    </div>
  </div>
    </form>
  </div>
  <div class="col-lg-6">
    <form id="demo-form2" action="{{url('/absen/user/kode/jam/roster/import')}}" data-parsley-validate class="form-vertical form-label-left" method="post"  enctype="multipart/form-data">
                      {{csrf_field()}}
                      <input type="hidden" name="_method" value="PUT">
                      <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12" for="fileExcel">Jam Kerja Karyawan <span class="required">*</span>
                        </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <input type="file" id="fileExcel" required="required" name="fileExcel" class="form-control-static col-md-7 col-xs-12" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <button type="submit" class="btn btn-success">Submit</button>
                          <a href="javascript:history.back()" class="btn btn-danger">Cancel</a>
                        </div>
                      </div>

                    </form>
  </div>
  <div class="col-lg-12">
    <hr>
    <p><b>Jumlah : {{count($kodeJam)}}</b> 
      <a href="{{url('/absen/user/kode/jam/roster/export')}}" class="btn btn-primary pull-right">Export</a></p>    
  </div>
  <div class="col-lg-12">
<hr>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center">Kode Jam</th>
          <th class="text-center">Deskripsi</th>
          <th class="text-center">Jam Kerja</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
    @if(count($kodeJam)>0)
      @foreach($kodeJam as $k => $v)
        <tr>
          <td class="text-center">{{$v->kode_jam}}</td>
          <td class="text-center">{{$v->deskripsi}}</td>
          <td class="text-center"  style="background-color: <?php echo $v->background;?>;color: <?php echo $v->tulisan;?>;">
<?php
    $jamnya = Illuminate\Support\Facades\DB::table("db_karyawan.jam_kerja")->where("no",$v->id_jam_kerja)->first();
?>

        @if($v->id_jam_kerja=="33")
            OFF
          @else
            {{$jamnya->masuk}} - {{$jamnya->pulang}}
        @endif
      </td>
          <td class="text-center">
            <a href="{{url('/absen/user/kode/jam/roster')}}?id_kode={{$v->id_kode}}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
            <button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>
          </td>
        </tr>
      @endforeach
      @else
  <div class="col-lg-12"> No Data!</div>
      @endif

      </tbody>
    </table>
  <hr>
  </div>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
  @if(isset($_GET['nik']))
  {{$kodeJam->appends([
    "nik"=>$_GET['nik'],
    "dari"=>$_GET['dari'],
    "sampai"=>$_GET['sampai'],
    "status"=>$_GET['status']
    ])->links()}}
  @else
  {{$kodeJam->links()}}
  @endif
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
    <script src="{{asset('/timepicker/jquery.timepicker.min.js')}}"></script>
<!-- Bootstrap Colorpicker -->
    <script src="{{asset('/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <script>      
      $("select").selectpicker();

    $("input[name=jam_masuk]").timepicker({
                                timeFormat: 'h:mm p'
                            });
    $("input[name=jam_pulang]").timepicker({
                                 timeFormat: 'h:mm p'
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