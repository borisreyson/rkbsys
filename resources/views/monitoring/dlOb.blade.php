@extends('layout.master')
@section('title')
ABP-system | Delay Ob
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
    <a href="{{url('/boat')}}">Delay Ob</a>
    <br>
    <br>
  </div>
  <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Monitoring Delay Ob</h2>                  
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
  $timeSum = [];
  $arrDaily=[];
  $arrTot=null;
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
<div class="row table-responsive">
  <table class="table table-striped">
    <thead>
<tr>
  <th colspan ="6" style="border-top: 3px solid rgba(0,0,0,0.6);color: #000;font-weight: bolder;background-color: white;">
    Total Rows : {{count($data)}}
  </th>
</tr>
      <tr style="background-color: rgba(0,0,0,0.6);color: #f8f8f8;margin-top: 3px;">
        <th>Tanggal</th>
        <th></th>
      </tr>
    </thead>
    <tbody>

@foreach($data as $k => $v)
  @if($v->flag==1)
      <tr>
        <td style="text-align: center; vertical-align: middle;font-size: 16px;">
          <b>
            {{date("D",strtotime($v->tgl))}}
            <br>
            
          {{date("d F Y",strtotime($v->tgl))}}
        </b>
        </td>
        <td>
          <table class="table table-bordered">
            <thead>
                <tr>
                <th width="100px">Type Delay</th>
                <th width="150px">Shift</th>
                <th width="100px">Delay</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
            @php
              $list = Illuminate\Support\Facades\DB::table("monitoring_produksi.ob_delay_daily")
                      ->where('tgl',$v->tgl)
                      ->get();
            @endphp
            @foreach($list as $zk => $zV)
            <tr>
              
              <td>{{($zV->type_delay)}}</td>
              <td>
                @if($zV->shift==1)
                  Shift I
                @elseif($zV->shift==2)
                  Shift II
                @endif
              </td>
              <td>{{($zV->delay)}}</td>
              <td>{{($zV->keterangan )}}</td>
              @php
               $arrTot[$k][] = $zV->delay;
              @endphp
            </tr>
            @endforeach
@php 
$zx=0;
 @endphp

            <tr style="font-weight: bold!important">
              <td colspan="" rowspan="2">&nbsp;</td>
              <td colspan="2" style="text-align: right;font-weight: bold!important"> Total Daily: </td>
              <td>
              @php
              if(is_array($arrTot)){
              $totDaily = number_format(array_sum($arrTot[$k]),2);
              $arrDaily[] = $totDaily;
                echo $totDaily;
              }
              @endphp
              </td>
              <td rowspan="2">&nbsp;</td>
            </tr>
            </tbody>
          </table>
          </td>
      </tr>
            @endif
@endforeach
<tr>
  <td colspan="2" style="border-top: 3px solid rgba(0,0,0,0.6);color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid rgba(0,0,0,0.6); font-size: 14px;">
    @php
        if(!empty($arrDaily)){
        echo "Total MTD : ".number_format(array_sum($arrDaily),2);
      }
    @endphp
  </td>
</tr>
<tr>
  <td colspan ="2" style="border-top: 3px solid rgba(0,0,0,0.6);color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid rgba(0,0,0,0.6);">
    Total Rows : {{count($data)}}
  </td>
</tr>
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