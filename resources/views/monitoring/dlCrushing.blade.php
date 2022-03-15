@extends('layout.master')
@section('title')
ABP-system | Delay Crushing
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
    <a href="{{url('/boat')}}">Delay Crushing</a>
    <br>
    <br>
  </div>
  <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Monitoring Delay Crushing</h2>                  
    <div class="clearfix"></div>
  </div>
<div class="x_content">
<?php
  $fl;
  $tgl = [];
  $plan = [];
  $actual = [];
  $Y = [];
  $M = [];
  $F = [];
  $Tdaily=[]; 
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
  <table class="table table-bordered">
    <thead>
<tr>
  <th colspan ="6" style="border-top: 3px solid rgba(0,0,0,0.6);color: #000;font-weight: bolder;background-color: white;">
    Total Rows : {{count($data)}}
  </th>
</tr>
      <tr style="background-color: rgba(0,0,0,0.6); color: #f8f8f8;margin-top: 3px;">
        <th style="border-left-color: rgba(0,0,0,0.1);border-right-color: rgba(0,0,0,0.1);">Tanggal</th>
        <th colspan="2" style="border-left-color: rgba(0,0,0,0.1);border-right-color: rgba(0,0,0,0.1);"></th>
      </tr>
    </thead>
    <tbody>

@foreach($data as $k => $v)
<?php
  
$sum = Illuminate\Support\Facades\DB::table("monitoring_produksi.cr_delay_daily")
      ->whereRaW("tgL = '".$v->tgl."' and flag ='1'")
      ->groupby("shift")
      ->get();
      
?>  
@if(count($sum)>0)
      <tr>
        <td style="text-align: center; vertical-align: middle;font-size: 16px;"><b>{{date("d F Y",strtotime($v->tgl))}}</b></td>
        <td colspan="2">
          <table id="myTable"  class="table table-bordered" style="margin: 0px!important;">
      
@php
$groupCP = Illuminate\Support\Facades\DB::table("monitoring_produksi.cr_delay_daily")
      ->where('tgl',$v->tgl)
      ->groupby("shift")
      ->get();


@endphp
@foreach($groupCP as $kk => $vv)
@php
$groupCPflag = Illuminate\Support\Facades\DB::table("monitoring_produksi.cr_delay_daily")
      ->where([
                ['tgl',$v->tgl],
                ['shift',$vv->shift],
                ['flag',1]
              ])
      ->get();
      
@endphp
@if(count($groupCPflag)>0)
      <tr>
        <td style="vertical-align: middle;text-align: center;">
          @if($vv->shift==1)
            <span style="font-family: Times New Roman;font-size: 18px;">Shift I</span>
          @elseif($vv->shift==2)
            <span style="font-family: Times New Roman;font-size: 18px;">Shift II</span>
          @endif
        </td>
        <td colspan="3">
@php
$typeDL = Illuminate\Support\Facades\DB::table("monitoring_produksi.cr_delay_daily")
      ->where([
      ['tgl',$v->tgl],
      ['shift',$vv->shift]
      ])
      ->leftjoin("monitoring_produksi.type_delay","monitoring_produksi.type_delay.code","monitoring_produksi.cr_delay_daily.type_delay")
      ->groupby("type_delay")
      ->get();

@endphp
@foreach($typeDL as $kkk => $vvv)
@php
$tCR = Illuminate\Support\Facades\DB::table("monitoring_produksi.cr_delay_daily")
      ->where([
      ['tgl',$v->tgl],
      ['shift',$vv->shift],
      ['type_delay',$vvv->type_delay]
      ])
      ->get();

$tCR_flag = Illuminate\Support\Facades\DB::table("monitoring_produksi.cr_delay_daily")
            ->where([
            ['tgl',$v->tgl],
            ['shift',$vv->shift],
            ['type_delay',$vvv->type_delay],
            ['flag',1]
            ])
          ->get();   
@endphp
@if(count($tCR_flag)>0)
<table class="table table-bordered" style="margin:0px!important;">
  <tr align="center">
    <td  style="vertical-align: middle;">
      {{$vvv->desk}}
    </td>
    <td>
<table class="table table-bordered" style="margin:0px!important;">
@foreach($tCR as $kkkk => $vvvv)
  <tr>
    <td>{{$vvvv->typeCR}}</td>
    <td>{{($vvvv->timeCR)}} </td>
    <td>{{($vvvv->remark?$vvvv->remark:"-")}} </td>
  </tr>
  <?php
  $v4arr[$k][$kk][$kkk][] = $vvvv->timeCR;
  ?>
@endforeach
<tr>
  <td style="text-align: right;">
    Total : 
  </td>
  <td>{{array_sum($v4arr[$k][$kk][$kkk])}}</td>
</tr>
<?php $v3sh[$k][$kk][] = array_sum($v4arr[$k][$kk][$kkk]); ?> 
</table>
    </td>
  </tr>
</table>
@endif
@endforeach
        </td>
      </tr>
<tr>
  <td>&nbsp;</td>
  <td style="text-align: right;">
    Total Daily Shift : 
  </td>
  <td width="146px">
    <?php 
    $dlSHIFT = number_format(array_sum($v3sh[$k][$kk]),2);
    $Tdaily[$k][] = $dlSHIFT;
    echo $dlSHIFT;
    ?>
    
  </td>
</tr>
@endif
@endforeach
          </table>
          </td>
      </tr>

<tr>
  <td>&nbsp;</td>
  <td style="text-align: right;">
    Total Daily :
  </td>
  <td width="154px">
    @php
    $dailyT = number_format(array_sum($Tdaily[$k]),2);
    $mtd[] = $dailyT;
    echo $dailyT;    
    @endphp
  </td>
</tr>
@endif
@endforeach
<tr>
  <td colspan="3" style="border-top: 3px solid rgba(0,0,0,0.09);color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid rgba(0,0,0,0.6); font-size: 14px;">
    
    Total MTD : @if(isset($mtd)) {{number_format(array_sum($mtd),2)}} @else 0 @endif

  </td>
</tr>
<tr>
  <td colspan ="3" style="border-top: 3px solid rgba(0,0,0,0.6);color: #000;font-weight: bolder;background-color: white;border-bottom:3px solid rgba(0,0,0,0.6);">
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