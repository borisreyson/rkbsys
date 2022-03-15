@extends('layout.master')
@section('title')
ABP-system | Roster
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/timepicker/jquery.timepicker.css')}}">
    <!-- Bootstrap Colorpicker -->
    <link href="{{asset('/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" src="{{url('bootstrap-table-master/src/extensions/fixed-columns/bootstrap-table-fixed-columns.css')}}">
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
table tr .headcol {
  position: absolute;
  width: 20em;
  left: 0;
  top: auto;
  border-collapse: separate!important;
  border:solid 1px #DDEFEF !important;
  background-color: white;
}
.table-utama{
  margin-left: 19.1em;

  overflow-x: scroll;
  overflow-y: visible;
  padding: 0;
}
.long{
  width: 10em!important;
}
.input-group .bootstrap-select.form-control {
        z-index: inherit;
}
</style>
@endsection
@section('content')
<?php
function hari_ini($date){
  $hari = date ("D",$date);
 
  switch($hari){
    case 'Sun':
      $hari_ini = "Minggu";
    break;
 
    case 'Mon':     
      $hari_ini = "Senin";
    break;
 
    case 'Tue':
      $hari_ini = "Selasa";
    break;
 
    case 'Wed':
      $hari_ini = "Rabu";
    break;
 
    case 'Thu':
      $hari_ini = "Kamis";
    break;
 
    case 'Fri':
      $hari_ini = "Jumat";
    break;
 
    case 'Sat':
      $hari_ini = "Sabtu";
    break;
    
    default:
      $hari_ini = "Tidak di ketahui";   
    break;
  }
 
  return $hari_ini;
 
}
?>  
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
                  <h2>Roster</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
  <div class="row col-lg-12">
   
<div class="col-lg-12 row">
  <div class="col-lg-12">

    <form action="" method="get" class="form-horizontal col-lg-10 row">
      <div class="form-group">
    <label class="control-label col-lg-3">Sub Bagian</label>
    <div class="col-lg-6 clearfix">
      <select name="id_sub" id="id_sub"  class="form-control" required="required" data-live-search="true">
        <option value="">--Pilih--</option>
        
        @foreach($sub_bagian as $kk => $vv)  
        <optgroup label="{{$vv->dept}}">
        <?php
          $sub = Illuminate\Support\Facades\DB::table("db_karyawan.sub_bagian")
                  ->where("id_dept",$vv->id_dept)->get();
        ?>      
          @foreach($sub as $kSub => $vSub)
            @if(isset($_GET['id_sub']))
            @if($_GET['id_sub']==$vSub->id_sub)
            <option value="{{$vSub->id_sub}}" selected="selected">{{$vSub->bagian}}</option>  
            @else               
            <option value="{{$vSub->id_sub}}">{{$vSub->bagian}}</option> 
            @endif
            @else            
            <option value="{{$vSub->id_sub}}">{{$vSub->bagian}}</option> 
            @endif    
          @endforeach      
        </optgroup>
        @endforeach
      </select>     
    </div>

    <div class="col-lg-3">
      <button type="button" id="new_sub" name="new_sub"  data-toggle="modal" data-target="#myModal" class="btn btn-primary">Buat Sub Bagian</button>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-lg-3">Tahun</label>
    <div class="col-lg-2">
      @if(isset($_GET['tahun']))
      @if($_GET['tahun'])
      <input type="number" name="tahun" class="form-control" min="2016" required="required" value="{{$_GET['tahun']}}">
      @else
      <input type="number" name="tahun" class="form-control" min="2016" required="required" value="{{date('Y')}}"> 
      @endif 
      @else
      <input type="number" name="tahun" class="form-control" min="2016" required="required" value="{{date('Y')}}"> 
      @endif
    </div>
  </div>
<div class="form-group">
    <label class="control-label col-lg-3">Bulan</label>
    <div class="col-lg-2">
      <select name="bulan" id="bulan"  class="form-control" required="required" data-live-search="true">
        <option value="">--Pilih--</option>
        <?php        
        $start = strtotime(date("Y-01-01"));
        $end = strtotime(date("Y-12-t"));
        while($start <= $end)
              {
            ?>       
          @if(isset($_GET['bulan']))
          @if($_GET['bulan']==date('m',$start))
          <option value="{{date('m',$start)}}" selected="sxelected">{{date('F',$start)}}</option>  
          @else               
          <option value="{{date('m',$start)}}">{{date('F',$start)}}</option> 
          @endif
          @else            
          <option value="{{date('m',$start)}}">{{date('F',$start)}}</option> 
          @endif
           <?php
                $start = strtotime("+1 month",$start);
                } ?>
      </select>     
    </div>
  </div>

<div class="form-group">
    <label class="control-label col-lg-3">Jumlah Karyawan</label>
    <div class="col-lg-2">
      <input type="number" min="1" name="jumKar" id="jumKar"  class="form-control" required="required" @if(isset($_GET['jumKar'])) value="{{$_GET['jumKar']}}" @else value="1" @endif />   
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
      <button class="btn btn-primary" id="import" type="submit" name="import" value="true">Import</button>  
      <button class="btn btn-primary" id="kirim" type="submit">Buat Roster</button>   
      <a class="btn btn-danger" id="reset" href="{{url($_SERVER['REDIRECT_URL'])}}">Reset</a>   
      <a class="btn btn-default" id="reset" href="{{url('/import/import_roster.xlsx')}}">Download Template Excel</a>   
    </div>
  </div>
    </form>
  </div>
  <div class="col-lg-12">
    
  </div>
  <form method="post" @if(isset($_GET['import']))  enctype="multipart/form-data" action="{{url('absen/roster/karyawan/import')}}" class="form-horizontal" @else  action="" @endif name="formRoster">
    {{csrf_field()}}
  @if(isset($_GET['id_sub']))
    <input type="hidden" name="id_sub" value="{{$_GET['id_sub']}}">
    <input type="hidden" name="tahun" value="{{$_GET['tahun']}}">
    <input type="hidden" name="bulan" value="{{$_GET['bulan']}}">
    @if(isset($_GET['import']))
  <div class="col-lg-12">
    <hr>
    
    <div class="col-lg-12">
      <div class="form-group">
        <h2 class="col-lg-3 col-lg-offset-2">Jam Kerja</h2>
        
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3">Pilih Jam Kerja</label>
        <div class="col-lg-4">
          <?php
          $jamKerjaLoop = Illuminate\Support\Facades\DB::table("db_karyawan.kode_jam_masuk")
                          ->join("db_karyawan.jam_kerja","db_karyawan.jam_kerja.no","db_karyawan.kode_jam_masuk.id_jam_kerja")
                          ->where("db_karyawan.kode_jam_masuk.flag",'1')
                          ->get();
        ?> 
        @foreach($jamKerjaLoop as $k =>$v)
          <div class="checkbox" style="background-color: <?php echo $v->background;?>; color: <?php echo $v->tulisan;?>; border: solid 0.01px #575E58;">
              <label >
                <input type="checkbox"  value="{{$v->id_kode}}" class="flat"  name="jam[]" id="jam"> <b> {{$v->kode_jam}}</b> [ {{$v->deskripsi}} ] | ( {{$v->masuk}} - {{$v->pulang}})
              </label>
            </div>
        @endforeach
        </div>
      </div>
      <div class="form-group">
        <h2 class="col-lg-3 col-lg-offset-2">Form Import From Excel File</h2>
        
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="fileRoster">Pilih File</label>
        <div class="col-lg-6">
          <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="fileRoster" id="fileRoster"  class="form-control-static" required="required" />   
        </div>
      </div>
      <div class="form-group">
        <div class=" col-lg-1 col-lg-offset-3">
          <button class="btn btn-primary" type="submit">Proses</button>  
        </div>
      </div>
      
    </div>
    <hr>
  </div>
    @else
  <div class="col-lg-12">
    <hr>
    <div class="table-responsive table-utama">
    <table class="table table-bordered" >
      <thead>
        <tr>
          <th class="text-center headcol">Karyawan</th>
          <?php    
        $jumKar = $_GET['jumKar'];
        $m = $_GET['bulan'];    
        $start = strtotime(date("Y-".$m."-01"));
        $end = strtotime(date("Y-".$m."-t"));
        while($start <= $end)
              {
            ?>
          <th class="text-center long" >{{date("d/m/Y",$start)}}</th>          
      <?php
        $start = strtotime("+1 day",$start);
        } ?>
        </tr>
        <tr>
          <th class="headcol">&nbsp;</th>

          <?php    
        $m = $_GET['bulan'];    
        $start = strtotime(date("Y-".$m."-01"));
        $end = strtotime(date("Y-".$m."-t"));
        while($start <= $end)
              {
            ?>
          <th class="text-center long" >{{hari_ini($start)}}</th>          
      <?php
        $start = strtotime("+1 day",$start);
        } ?>
        </tr>
      </thead>
      <tbody>

        @for($i=0; $i<$jumKar; $i++)
        <?php $z=$jumKar;?>
        <tr>
          <td class="headcol id_index{{$z}}">
            <select name="nik[]" id="nik"  class="form-control nik" required="required" data-live-search="true">
            <option value="">--Pilih--</option>
            @foreach($karyawan as $k => $v)
              <option value="{{$v->nik}}">{{$v->nama}} ({{$v->dept}})</option>              
            @endforeach
      </select>
          </td>
        <?php    
        $m = $_GET['bulan'];    
        $start = strtotime(date("Y-".$m."-01"));
        $end = strtotime(date("Y-".$m."-t"));
        $z=0;
        while($start <= $end)
              {
            ?>
          <td class="">
            <input type="hidden" name="tgl[{{$i}}][{{$z}}]" id="tgl" value="{{$start}}">
            <select name="jam_kerja[{{$i}}][{{$z}}]" id="jam_kerja"  class="form-control long" required="required" data-live-search="true">
            <option value="">--Pilih--</option>
            @foreach($jamKerja as $kJ => $vJ)
            <option value="{{$vJ->id_kode}}">{{$vJ->deskripsi}} = {{$vJ->masuk}} - {{$vJ->pulang}}</option>
            @endforeach
      </select>
          </td>
      <?php
      $z++;
        $start = strtotime("+1 day",$start);
        } ?>
        </tr>
<?php $z--; ?>
        @endfor
      </tbody>
    </table>
    </div>
  <hr>
  <div class="col-lg-12">
      <button class="btn btn-primary pull-right" type="submit">Proses</button>
  </div>
  </div>
  @endif
  @endif
  </form>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
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
  <div id="modals" class="modal-dialog modal-lg">
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
    <script src="{{url('bootstrap-table-master/src/extensions/fixed-columns/bootstrap-table-fixed-columns.js')}}"></script>

    <script>
      $("#datepicker").datepicker({
          format: "yyyy",
          viewMode: "years", 
          minViewMode: "years"
      });
      $("select[id=nik]").selectpicker("refresh");
      $("button[name=new_sub]").click(function(){
        $.ajax({
          "type":"GET",
          "url":"{{url('/absen/new/sub')}}",
          beforeSend:function(){
            $("div[id=modals]").removeClass("modal-lg").addClass("modal-md ");
          },
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