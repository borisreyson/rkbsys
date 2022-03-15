@extends('layout.master')
@section('title')
ABP-system | Monitoring SR (Stripping Ratio)
@endsection
@section('css')
@include('layout.css')
<link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { 
  position: absolute; cursor: default;z-index:9999 !important;height: 100px;
  overflow-y: auto;
  overflow-x: hidden;
}
.ck-editor__editable {
    min-height: 90px;
}
.modal-xl{
  width: 90%!important;
  margin-top: 50px;
}
</style>
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<div class="right_col" role="main">
  <div class="col-lg-12">
    <br>
    <a href="{{url('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    <a href="{{url('/boat')}}">Monitoring SR (Stripping Ratio)</a>
    <br>
    <br>
  </div>
  <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Monitoring SR (Stripping Ratio)</h2>                  
    <div class="clearfix"></div>
  </div>
<div class="x_content">
<?php
  $tgl = [];
  $plan = [];
  $actual = [];
  $Y = [];
  $M = [];
  $F = [];


  function Getangka($nilai)  { return number_format($nilai, 3, ",","."); }
  foreach($dataY as $k => $v)
  {
    $Y[] = date("Y",strtotime($v->tgl));
  }
  foreach($montH as $k => $v)
  {
    $F[] = date("F",strtotime($v->tgl));
    $M[] = date("m",strtotime($v->tgl));
  }
?>
<div class="row">
  <div class="col-xs-12 text-center">
    @foreach($Y as $k => $v)
    @if(isset($_GET['year']))
    @if($v==$_GET['year'])
    <a href="?year={{$v}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$v}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @else
    @if($v==date("Y"))
    <a href="?year={{$v}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$v}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @endforeach
  </div>
  @if(!isset($bulanan))
  <div class="col-xs-12 text-center">
    @foreach($F as $k => $v)
    @if(isset($_GET['year']))
    @if(isset($_GET['m']))
    @if($M[$k]==$_GET['m'])
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @else
    @if($k==0)
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @else
    @if($M[$k]==date('m'))
    <a href="?year={{date('Y')}}&m={{$M[$k]}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{date('Y')}}&m={{$M[$k]}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @endforeach
  </div>
  @endif
</div>
<div class="row">
  <table class="table table-striped">
    <thead>
<tr>
  <th colspan ="6" style="border-top: 3px solid #000;color: #000;font-weight: bolder;background-color: white;">
    Total Rows : {{count($data)}}
  </th>
</tr>
      <tr style="background-color: #333;color: #f8f8f8;margin-top: 3px;">
        <th>Tanggal</th>
        <th>SR (Stripping Ratio)</th>
        <th>SR (Stripping Ratio) Expose</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $rataSR = [];
      $arrOB=[];
      $arrHL=[];
      $Expose=0;
      $arrExpose=[];
      ?>
@foreach($data as $k => $v)
      <tr>
        <td>{{date("d F Y",strtotime($v->tgl))}}</td>
        <?php
        $ob = Illuminate\Support\Facades\DB::table("monitoring_produksi.ob")->where("tgl",$v->tgl)->first();
        if($v->tgl==$ob->tgl){
        if($v->actual_daily>0){
        $sr = ($ob->actual_daily/$v->actual_daily);
        }else{
          $sr=0;
        }
              array_push($rataSR,$sr);
              array_push($arrOB, $ob->actual_daily);
              array_push($arrHL, $v->actual_daily);
                
        ?>
        @if($ob->actual_daily>0)
        <td>{{number_format($sr,2)}}</td>
        @else
        <td>{{number_format(0,2)}}</td>
        @endif
      <?php }else{ ?>
        <td>-</td>
      <?php } ?>
      <?php
        $stripping_ratio=Illuminate\Support\Facades\DB::table("monitoring_produksi.stripping_ratio")->where("tgl",$v->tgl)->first();
        if(isset($stripping_ratio->tgl)==$v->tgl && $v->tgl==$ob->tgl){
        if($v->actual_daily>0){
        $sr_expose = ($ob->actual_daily/($stripping_ratio->inventory+$v->actual_daily));
        }else{
          $sr_expose=0;
        }
        if($k==0){
              $Expose=$stripping_ratio->inventory;
        }
                
              array_push($arrExpose,$sr_expose);
        ?>
        @if(isset($stripping_ratio->inventory)>0)
        <td>{{number_format($sr_expose,2)}}</td>
        @else
        <td>{{number_format(0,2)}}</td>
        @endif
      <?php }else{ ?>
        <td>-</td>
      <?php } ?>
      </tr>
@endforeach
<tr>
  <td colspan ="" style="border-top: 3px solid #000;color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid #000;">
    Total Rows : {{count($data)}}
    <span style="float: right;">Rata - Rata :</span>
  </td>
  <td style="border-top: 3px solid #000;color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid #000;">
    @if(count($data)>0) 
    {{number_format(array_sum($rataSR)/count($data),2)}}
    @endif
  </td>
  <td style="border-top: 3px solid #000;color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid #000;">
    @if(count($data)>0)
    {{number_format(array_sum($arrExpose)/count($data),2)}}
    @endif
  </td>
</tr>
 @if(count($data)>0)
<tr>
  <td align="right">SR Month To Date : </td>
  <td style="font-weight: bolder;color: #000;"><?php  
  if(array_sum($arrHL)>0){
    $nilaiSR = (array_sum($arrOB)/array_sum($arrHL));
  }else{
    $nilaiSR=0;
  }
  echo number_format($nilaiSR,2);
  ?></td>
  <td style="font-weight: bolder;color: #000;"><?php  
  if($Expose>0){
    $nilaiSRExpose = (array_sum($arrOB)/(array_sum($arrHL) +$Expose) );
  }else{
    $nilaiSRExpose=0;
  }
  echo number_format($nilaiSRExpose,2);
  ?></td>
</tr>
@endif
    </tbody>
  </table>
</div>
      </div>
    </div>
  </div>
</div>
</div>
@include('layout.footer')
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
<div id="konten_modal"></div>
  </div>
</div>
</div>
@endsection
@section('js')
@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <script src="{{asset('/numeral/min/numeral.min.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
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