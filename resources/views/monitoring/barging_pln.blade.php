@extends('layout.master')
@section('title')
ABP-system | BARGING PLN
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

<!-- page content -->

<div class="right_col" role="main">
  <div class="col-lg-12">
    <br>
    <a href="{{url('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    <a href="{{url('/barging')}}">BARGING PLN</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Monitoring BARGING PLN</h2>
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
  $min=0;
  $step=1;

if(!isset($bulanan)){
  foreach ($data as $key => $value) {
    $tgl[] = date("d F Y",strtotime($value->tgl));
    $plan[] = (float) $value->plan_daily;
    $actual[] = (float) $value->actual_daily;
  }
  $min = 5000;
  $step = 5000;
}else{
  foreach ($data as $key => $value) {
    $tgl[] = date("F",strtotime($value->tgl));
    $plan[] = (float) $value->mtd_plan;
    $actual[] = (float) $value->mtd_actual;
  }
  $min = 100000;
  $step = 100000;
}

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
<?php
$total = count($tgl);
if($total<6){
  $tinggi = 120*($total*2);
}else{
  $tinggi = (120*$total);
}
?>
<div class="row">
  <div id="mainb" style="height: <?php echo $tinggi;?>px;"></div>
</div>
@if(isset($ach))
<div class="row">
  <div id="mainbar" style="height: <?php echo $tinggi;?>px;"></div>
</div>
@endif



                </div>
              </div>
            </div>
</div>
</div>



@include('layout.footer')


    <!-- compose -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
<div id="konten_modal"></div>
  </div>
</div>

</div>
@endsection

@section('js')
<!-- Datatables -->
    <!-- FastClick -->


@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <script src="{{asset('/numeral/min/numeral.min.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
   <!--highchart-->
<script src="{{asset('highchart/code/highcharts.js')}}"></script>
<script src="{{asset('highchart/code/modules/exporting.js')}}"></script>
<script src="{{asset('highchart/code/modules/export-data.js')}}"></script>
<script src="{{asset('highchart/code/modules/series-label.js')}}"></script>

<script>
Highcharts.setOptions({
    lang: {
      decimalPoint: ',',
      thousandsSep: '.'
    }
});
Highcharts.chart('mainb', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Grafik Monitoring Produksi',
        style:{
          //fontSize:'50px'
        }
    },
    xAxis: {
        categories: <?php echo (json_encode($tgl));?>,
        title: {
            text: null,

        },
        labels:{
          style: {
                //fontSize: '48px'
            }
        }
    },
    yAxis: {
        min: <?php echo $min;?>,
        labels: {
            overflow: 'justify',
            style: {
                //fontSize: '30px'
            },
            formatter: function () {
                if(this.value>0){
                    return this.value/1000 + 'K MT';
                }else{
                    return this.value/1000 + ' MT';
                }
            }
        },
        tickInterval:<?php echo $step;?>,
        title: {
            text: 'Kumulatif MT',
            style: {
                            //fontSize: '38px'
                        }
        }

    },
    tooltip: {
        valueSuffix: ' MT',
        style:{
          //fontSize:'30px'
        }
    },
    plotOptions: {
        bar: {
            borderRadius: 10,
            dataLabels: {
                enabled: true,
                style: {
                          //fontSize: '30px'
                        },
                formatter: function() {
                   return this.y.toFixed(3).toString().replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')+" MT";
                }
            },
            enableMouseTracking: false
        }
    },
    legend: {
        //layout: 'vertical',
        //align: 'top',
        verticalAlign: 'top',
      //  x: -90,
       // y: 250,
        floating: false,
        //padding: 45,
        //itemMarginTop: 5,
        //itemMarginBottom: 5,
        //fontSize:'30px',
       // borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
       // shadow: true,
        //width: '8%',
        //height: '10%',
        itemStyle: {
            fontSize:'17px',
            verticalAlign: 'middle',
            lineHeight: '14px'
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'PLAN',
        data: <?php echo (json_encode($plan));?>,
        color:'#E8CE61'
    }, {
        name: 'ACTUAL',
        data: <?php echo (json_encode($actual));?>,
        color:'#53A63E'
    }]
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
