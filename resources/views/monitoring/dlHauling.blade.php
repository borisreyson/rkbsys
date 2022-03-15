@extends('layout.master')
@section('title')
ABP-system | Delay Hauling
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
    <a href="{{url('/boat')}}">Delay Hauling</a>
    <br>
    <br>
  </div>
  <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Monitoring Delay Hauling</h2>                  
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
        <td style="text-align: center; vertical-align: middle;font-size: 16px;"><b>{{date("d F Y",strtotime($v->tgl))}}</b></td>
        <td>
          <table class="table table-bordered">
            <thead>
                <tr>
                <th colspan="2" style="text-align: center;">Type Delay</th>
                <th width="150px">Shift</th>
                <th width="100px">Start</th>
                <th width="100px">Finish</th>
                <th width="150px">Total</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
            @php
              $list = Illuminate\Support\Facades\DB::table("monitoring_produksi.hl_delay_daily")
                      ->where('tgl',$v->tgl)
                      ->get();
            @endphp
            @foreach($list as $zk => $zV)
            <tr>
              <td>{{($zV->type_delay )}}</td>
              <td>
                @if($zV->dynamicField=="")
                -
                @else
                * {{($zV->dynamicField )}}
                @endif
              </td>
              <td>
                @if($zV->shift==1)
                  Shift I
                @elseif($zV->shift==2)
                  Shift II
                @endif
              </td>
              <td>{{($zV->start)}}</td>
              <td>{{($zV->finish)}}</td>
              <td>                
                @php
                  $origin   = strtotime('00:00:00');
                  $start =strtotime($zV->start);
                  $finish = strtotime($zV->finish);
                  $tot = date("H:i",(($finish-$start)+$origin));
                  $arrTot[$k][] = strtotime($tot);
                  echo $tot;

                  $arrK[$k][$zV->type_delay][] =  strtotime($tot);
                  
                @endphp
              </td>
              <td>{{($zV->keterangan )}}</td>
            </tr>
            @endforeach
@php 
$zx=0;

 @endphp
@if(isset($arrK))
@foreach($arrK[$k] as $kT => $vT)
            <tr style="font-weight: bold!important">
@if($zx==0)
@if(count($arrK[$k])>1)
              <td colspan="3" rowspan="2"></td>
      @else

              <td colspan="3" rowspan=""></td>
      @endif
@endif
              <td colspan="2"  style="text-align: right;text-transform: capitalize!important;font-weight: bold!important; ">
              {{('TOTAL '.$kT." : ")}}
            </td>
              <td colspan="" style="font-weight: bold!important">
              @php
              $sumT2=0;
              $sumT = strtotime('00:00:00');
                foreach($vT as $vTSum)
                {
                  $sumT1 = $vTSum-$sumT;

                  $sumT2 = $sumT2+$sumT1;

                }
                $sumT3 = $sumT+$sumT2;
                echo date("H:i",$sumT3);
                
              @endphp
            </td>
            </tr>
            @php $zx++; @endphp

@endforeach
@endif
            <tr style="font-weight: bold!important">
              <td colspan="3" rowspan="2">&nbsp;</td>
              <td colspan="2" style="text-align: right;font-weight: bold!important"> Total : </td>
              <td>
              @php
              $sum2=0;
              $sum = strtotime('00:00:00');
              if(isset($arrTot)){
                foreach($arrTot[$k] as $vSum)
                {
                  $sum1 = $vSum-$sum;

                  $sum2 = $sum2+$sum1;

                }
                $sum3 = $sum+$sum2;
                $HH = date("H",$sum3);
                $MM = date("i",$sum3);

                $HM = $MM/60+$HH;
                $timeSum[] = number_format($HM,2);
                echo date("H:i",$sum3);
                }
              @endphp
              </td>
              <td rowspan="2">&nbsp;</td>
            </tr>
            <tr style="font-weight: bold!important">
              <td colspan="2" style="text-align: right;">Total Komulatif : </td>
              <td>@if(isset($HM)){{number_format($HM,2)}}@endif</td>
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
    if(isset($timeSum)){
        echo "Total Komulatif : ".number_format(array_sum($timeSum),2);
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