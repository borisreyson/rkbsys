@extends('layout.master')
@section('title')
ABP-system | Monitoring Unit Rental @if(isset($shift)) Shift {{$shift}} @endif
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;

            overflow-y: auto;
            /* prevent horizontal scrollbar */
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

</style>
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])
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
  $hm_awal=[];
  $hm_akhir=[];
  $abp1=[];
  $mtk1=[];
  $stb1=[];
  $bd1=[];
  $abp2=[];
  $mtk2=[];
  $stb2=[];
  $bd2=[];
  $total_hm1=[];
  $total_hm2=[];
  $tot_stb1=[];
  $tot_stb2=[];
  $tot_hm=[];
  $tot_bd=[];
?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Monitoring Unit Rental @if(isset($shift)) Shift {{$shift}} @endif</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">
<div class="col-lg-12 row">
  <div class="col-lg-12">  
<div class="row">
  <div class="col-xs-12 text-center">
    @foreach($Y as $k => $v)
    @if(isset($_GET['year']))
    @if($v==$_GET['year'])
    @if(isset($_GET['unit']))
    <a href="?year={{$v}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$v}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @endif
    @else    
    @if(isset($_GET['unit']))
    <a href="?year={{$v}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @else
    <a href="?year={{$v}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @else
    @if($v==date("Y"))
    @if(isset($_GET['unit']))
    <a href="?year={{$v}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$v}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @endif
    @else
    @if(isset($_GET['unit']))
    <a href="?year={{$v}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @else
    <a href="?year={{$v}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @endif
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
    @if(isset($_GET['unit']))
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @endif
    @else
    @if(isset($_GET['unit']))
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @else
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @else
    @if($k==0)
    @if(isset($_GET['unit']))
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @endif
    @else
    @if(isset($_GET['unit']))
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @else
    <a href="?year={{$_GET['year']}}&m={{$M[$k]}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @endif
    @else
    @if($M[$k]==date('m'))
    @if(isset($_GET['unit']))
    <a href="?year={{date('Y')}}&m={{$M[$k]}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @else
    <a href="?year={{date('Y')}}&m={{$M[$k]}}" class="btn btn-xs btn-default active">{{$v}}</a>
    @endif
    @else
    @if(isset($_GET['unit']))
    <a href="?year={{date('Y')}}&m={{$M[$k]}}&unit={{$_GET['unit']}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @else
    <a href="?year={{date('Y')}}&m={{$M[$k]}}" class="btn btn-xs btn-primary">{{$v}}</a>
    @endif
    @endif
    @endif
    @endforeach
  </div>
  @endif
</div>
  </div>
</div>
<div class="col-lg-12">
  <hr>
</div>
<div class="col-lg-12 col-md-12 col-xs-12">
<div class="row">
@if(isset($unit))
<form class="form-horizontal" method="get" action="">

  <div class="form-group">
    <label class="control-label col-lg-1 col-md-3 col-xs-12">Unit</label>
    <div class="col-lg-2 col-md-6 col-xs-12">
      <select name="unit" class="form-control" data-live-search="true">
      @foreach($unit as $k =>$v)
      @if(isset($_GET['unit']))
      @if($_GET['unit']==$v->id_unit)
      <option value="{{$v->id_unit}}" selected="selected">{{$v->nama_unit}}</option>
      @else
      <option value="{{$v->id_unit}}">{{$v->nama_unit}}</option>
      @endif
      @else
      <option value="{{$v->id_unit}}">{{$v->nama_unit}}</option>
      @endif
      @endforeach
    </select>
    </div>
    <div class="col-lg-2 col-md-3 col-xs-12">
      <button type="submit" class="btn btn-primary">Kirim</button>
    </div>
  </div>
</form>
@endif
</div>
</div>
<div class="col-lg-12">
  <hr>
</div>
<div class="container-fluid">
<div class="col-lg-12 col-xs-12 col-sm-12">
  <div class="row">
  <div class="table-responsive">
  <table class="table table-bordered text-center ">
    <thead>
      <tr class="bg-success">
        <th class="text-center">Date</th>
        <th class="text-center">Shift</th>
        <th class="text-center">Unit</th>
        <th class="text-center">Nama</th>
        <th class="text-center">Hm Awal</th>
        <th class="text-center">Hm Akhir</th>
        <th class="text-center">Total HM</th>
        <th class="text-center">ABP</th>
        <th class="text-center">MTK</th>
        <th class="text-center">Standby</th>
        <th class="text-center">Breakdown</th>
        <th class="text-center">Total</th>
        <th class="text-center">PA</th>
        <th class="text-center">UA</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="14" class="bg-danger">&nbsp;</td>
      </tr>
      @if(count($data)>0)
      @foreach($data as $kd => $vd)
      @php
        $rental = Illuminate\Support\Facades\DB::table("monitoring_unit.data_hm_unit")
                ->join("monitoring_unit.unit","monitoring_unit.unit.id_unit","monitoring_unit.data_hm_unit.unit")
                ->whereRaw("tgl='".$vd->tgl."' and unit='".$vd->unit."'")
                ->get();
      @endphp
      @foreach($rental as $k => $v)
      <tr>
        @if($k==0)
        <td rowspan="2" style="vertical-align: middle;">{{date("d F Y",strtotime($v->tgl))}}</td>
        @endif
        <td>
        @if($v->shift==1)
        <?php 
        $hm_awal[$kd]   = $v->hm_awal;
        $abp1[$kd]      = $v->abp;
        $mtk1[$kd]      = $v->mtk;
        $stb1[$kd]      = $v->stb;
        $bd1[$kd]       = $v->bd;
        $total_hm1[]    = ($v->hm_akhir-$v->hm_awal);
        $tot_stb1[]     = $v->stb;
         ?>
              Shift I
            @elseif($v->shift==2)
        <?php 
        $hm_akhir[$kd] = $v->hm_akhir;
        $abp2[$kd]     = $v->abp;
        $mtk2[$kd]     = $v->mtk;
        $stb2[$kd]     = $v->stb;
        $bd2[$kd]      = $v->bd;
        $total_hm2[]    =($v->hm_akhir-$v->hm_awal);
        $tot_stb2[]     = $v->stb;
         ?>
              Shift II
            @endif
        </td>
        @if($k==0)
        <td rowspan="2" style="vertical-align: middle;">{{($v->nama_unit)}}</td>
        @endif
        <td>{{$v->nama}}</td>
        <td>{{number_format($v->hm_awal,1)}}</td>
        <td>{{number_format($v->hm_akhir,1)}}</td>
        <td>{{number_format(($v->hm_akhir-$v->hm_awal),1)}}</td>
        <td>{{number_format($v->abp,1)}}</td>
        <td>{{number_format($v->mtk,1)}}</td>
        <td>{{number_format($v->stb,1)}}</td>
        <td>{{number_format($v->bd,1)}}</td>
        <td>{{number_format((($v->hm_akhir-$v->hm_awal)+$v->stb+$v->bd),1)}}</td>

        @php 
        $tot_hm[]=(($v->hm_akhir-$v->hm_awal)+$v->stb+$v->bd);
        $tot_bd[]=$v->bd;
         @endphp
        <td>
          @if(($v->hm_akhir-$v->hm_awal)>0)
          {{((($v->hm_akhir-$v->hm_awal)+$v->stb)/(($v->hm_akhir-$v->hm_awal)+$v->stb+$v->bd))*100}}%
        @php $tot_pa[]=((($v->hm_akhir-$v->hm_awal)+$v->stb)/(($v->hm_akhir-$v->hm_awal)+$v->stb+$v->bd))*100; @endphp
          @else
          0
          @endif
        </td>
        <td>@if(($v->hm_akhir-$v->hm_awal)>0)
          {{number_format((($v->hm_akhir-$v->hm_awal)/(($v->hm_akhir-$v->hm_awal)+$v->stb))*100,2)}} %
        @else
      0
    @endif</td>
      </tr>
       @if(count($rental)==1)
       <tr>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        </tr>
        @endif
      @endforeach
      <tr>
        <td class="bg-primary">Total</td>
        <td class="bg-primary" colspan="3"></td>
        <td class="bg-primary">@if(isset($hm_awal[$kd]))
                                {{number_format($hm_awal[$kd],1)}}
                              @else 
                                {{number_format(0,1)}}
                              @endif</td>
        <td class="bg-primary">@if(isset($hm_akhir[$kd]))
                                  {{number_format($hm_akhir[$kd],1)}}
                                @else
                              {{number_format(0,1)}}
                              @endif</td>
        <td class="bg-primary">@if(isset($hm_akhir[$kd] ) && isset($hm_awal[$kd]))
                              {{number_format(($hm_akhir[$kd]-$hm_awal[$kd]),1)}}
                            @else
                            {{number_format(0,1)}}
                          @endif</td>
        <td class="bg-primary">@if(isset($abp1[$kd]) && isset($abp2[$kd]))
                                {{number_format(($abp1[$kd]+$abp2[$kd]),1)}}
                              @else
                                {{number_format(0,1)}}
                            @endif</td>
        <td class="bg-primary">@if(isset($mtk1[$kd]) && isset($mtk2[$kd]))
                                {{number_format(($mtk1[$kd]+$mtk2[$kd]),1)}}
                              @else
                              {{number_format(0,1)}}
                            @endif</td>
        <td class="bg-primary">
                          @if(isset($stb1[$kd]) && isset($stb2[$kd]))
                              {{number_format(($stb1[$kd]+$stb2[$kd]),1)}}
                            @else
                            {{number_format(0,1)}}
                          @endif
                        </td>
        <td class="bg-primary">
        @if(isset($bd1[$kd]) && isset($bd2[$kd]))
        {{number_format(($bd1[$kd]+$bd2[$kd]),1)}}
      @else
      {{number_format(0,1)}}
    @endif</td>
        <td class="bg-primary">
          @if(isset($hm_akhir[$kd]) && isset($hm_awal[$kd]) && isset($stb1[$kd]) && isset($stb2[$kd]) && isset($bd2[$kd]))
          {{number_format((($hm_akhir[$kd]-$hm_awal[$kd])+($stb1[$kd]+$stb2[$kd])+($bd2[$kd]+$bd2[$kd])),1)}}
          @else
          {{number_format(0,1)}}
        @endif</td>
        <td class="bg-primary">
          @if(isset($hm_akhir[$kd]) && isset($hm_awal[$kd]) && isset($stb1[$kd]) && isset($stb2[$kd]) && isset($bd2[$kd]))
          {{((($hm_akhir[$kd]-$hm_awal[$kd])+($stb1[$kd]+$stb2[$kd]))/(($hm_akhir[$kd]-$hm_awal[$kd])+($stb1[$kd]+$stb2[$kd])+($bd2[$kd]+$bd2[$kd])))*100}}%
          @else
          {{number_format(0,1)}}
        @endif</td>
        <td class="bg-primary">
        @if(isset($hm_akhir[$kd]) && isset($hm_awal[$kd]) && isset($stb1[$kd]) && isset($stb2[$kd]) && isset($bd2[$kd]))
        {{number_format((($hm_akhir[$kd]-$hm_awal[$kd])/(($hm_akhir[$kd]-$hm_awal[$kd])+($stb1[$kd]+$stb2[$kd])))*100,2)}} %
        @else
        {{number_format(0,1)}}
        @endif
        </td>
      </tr>
      <tr>
        <td colspan="14" class="bg-danger">&nbsp;</td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="14" style="background-color: rgba(0,0,0,0.5);color:#fff;" class="text-center">No Have Record!</td>
      </tr>
      @endif
      @if(count($data)>0)
      <tr>
        <td style="background-color: black;color: white;">Total MTD</td>
        <td colspan="5" style="background-color: black;color: white;"></td>
        <td style="background-color: black;color: white;">{{number_format(array_sum($total_hm1)+array_sum($total_hm2),1)}}</td>
        <td colspan="2" style="background-color: black;color: white;"></td>
        <td style="background-color: black;color: white;">{{number_format(array_sum($tot_stb1)+array_sum($tot_stb2),1)}}</td>
        <td style="background-color: black;color: white;"></td>
        <td style="background-color: black;color: white;">{{array_sum($tot_hm)}}</td>
        <td style="background-color: black;color: white;">
<?php
if(array_sum($total_hm1)+array_sum($total_hm2)!=0){
$totPA = ((array_sum($total_hm1)+array_sum($total_hm2)+array_sum($tot_stb1)+array_sum($tot_stb2))/(array_sum($total_hm1)+array_sum($total_hm2)+array_sum($tot_stb1)+array_sum($tot_stb2)+array_sum($tot_bd)))*100;
echo number_format($totPA,2)." %";
}
?>
        </td>
        <td style="background-color: black;color: white;">
<?php
if(array_sum($total_hm1)+array_sum($total_hm2)!=0){
$totUA = ((array_sum($total_hm1)+array_sum($total_hm2))/(array_sum($total_hm1)+array_sum($total_hm2)+array_sum($tot_stb1)+array_sum($tot_stb2)))*100;
echo number_format($totUA,2)." %";
}
?>
        </td>
      </tr>
      @endif
    </tbody>
  </table>
  </div>
  </div>
  </div>
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
    @if(!isset($_GET['unit']))
<script>
  $(window).ready(function(){
    document.location.href="?unit="+$("select").val();
  });
</script>
    @endif
<script>
  $("select[name=unit]").on("change",function(){
    unit = $("select[name=unit]").val();
    if(unit!=""){
      window.location.href="?unit="+unit;
    }
  });
  $("select").selectpicker();
  $(".myButtonDiss").click(function(){
      document.location= "{{url('/mon/unit/rental')}}";
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