@extends('layout.master')
@section('title')
ABP-system | Home
@endsection
@section('css')
 @include('layout.css')
 <style>
 .rkb_success{ 
    background: #40FC22 !important;
    border: 1px solid #40FC22 !important;
    color: #fff

 }
 .tile_stats_count{
    padding-top: 20px!important;
    margin: 5px!important;
    border: 0px!important;     
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
          <!-- top tiles -->
          <div class="row tile_count ">

              <div class="animated flipInY col-lg-4 col-md-3 col-sm-6 col-xs-12 " title="" data-toggle="tooltip">
                <div class="tile-stats bg-blue-sky" style="color: #fff!important;">
                  <div class="icon" style="color: #fff!important;"><i class="fa fa-table"></i></div>
                  <div class="count" style="color: #fff!important;">OB</div>
                  <h3 style="color: #fff!important;">
                    <a href="{{url('/ob/daily')}}" class="btn btn-xs btn-default">Daily</a>
                    <a href="{{url('/ob/monthly')}}" class="btn btn-xs btn-default">Monthly</a>
                    <a href="{{url('/ob/ach')}}" class="btn btn-xs btn-default">ACH</a>
                  </h3>
                </div>
              </div>

              <div class="animated flipInY col-lg-4 col-md-3 col-sm-6 col-xs-12 " title="" data-toggle="tooltip">
                <div class="tile-stats bg-blue-sky" style="color: #fff!important;">
                  <div class="icon" style="color: #fff!important;"><i class="fa fa-table"></i></div>
                  <div class="count" style="color: #fff!important;">Hauling</div>
                  <h3 style="color: #fff!important;">
                    <a href="{{url('/hauling/daily')}}" class="btn btn-xs btn-default">Daily</a>
                    <a href="{{url('/hauling/monthly')}}" class="btn btn-xs btn-default">Monthly</a>
                    <a href="{{url('/hauling/ach')}}" class="btn btn-xs btn-default">ACH</a>
                  </h3>
                </div>
              </div>

              <div class="animated flipInY col-lg-4 col-md-3 col-sm-6 col-xs-12 " title="" data-toggle="tooltip">
                <div class="tile-stats bg-blue-sky" style="color: #fff!important;">
                  <div class="icon" style="color: #fff!important;"><i class="fa fa-table"></i></div>
                  <div class="count" style="color: #fff!important;">Crushing</div>
                  <h3 style="color: #fff!important;">
                    <a href="{{url('/chrusing/daily')}}" class="btn btn-xs btn-default">Daily</a>
                    <a href="{{url('/chrusing/monthly')}}" class="btn btn-xs btn-default">Monthly</a>
                    <a href="{{url('/chrusing/ach')}}" class="btn btn-xs btn-default">ACH</a>
                  </h3>
                </div>
              </div>

              <div class="animated flipInY col-lg-4 col-md-3 col-sm-6 col-xs-12 " title="" data-toggle="tooltip">
                <div class="tile-stats bg-blue-sky" style="color: #fff!important;">
                  <div class="icon" style="color: #fff!important;"><i class="fa fa-table"></i></div>
                  <div class="count" style="color: #fff!important;">Barging</div>
                  <h3 style="color: #fff!important;">
                    <a href="{{url('/barging/daily')}}" class="btn btn-xs btn-default">Daily</a>
                    <a href="{{url('/barging/monthly')}}" class="btn btn-xs btn-default">Monthly</a>
                    <a href="{{url('/barging/ach')}}" class="btn btn-xs btn-default">ACH</a>
                  </h3>
                </div>
              </div>

              <div class="animated flipInY col-lg-4 col-md-3 col-sm-6 col-xs-12 " title="" data-toggle="tooltip">
                <div class="tile-stats bg-blue-sky" style="color: #fff!important;">
                  <div class="icon" style="color: #fff!important;"><i class="fa fa-table"></i></div>
                  <div class="count" style="color: #fff!important;">Tug Boat</div>
                  <h3 style="color: #fff!important;">
                    <a href="{{url('/boat')}}" class="btn btn-xs btn-default">Monthly</a>
                  </h3>
                </div>
              </div>

              <div class="animated flipInY col-lg-4 col-md-3 col-sm-6 col-xs-12 " title="" data-toggle="tooltip">
                <div class="tile-stats bg-blue-sky" style="color: #fff!important;">
                  <div class="icon" style="color: #fff!important;"><i class="fa fa-table"></i></div>
                  <div class="count" style="color: #fff!important;">Stock</div>
                  <h3 style="color: #fff!important;">
                    <a href="{{url('/stockProduct')}}" class="btn btn-xs btn-default">Monthly</a>
                  </h3>
                </div>
              </div>
          </div>

<!--KTT-->


<!--KTT-->
          <br />

            </div>
          </div>
        </div>
        <!-- /page content -->


@include('layout.footer')

</div>
</div>
@endsection

@section('js')
@include('layout.js')
@php
$getCount = $getDay = $vWeek = $approvE = $waiting = $cancel = [];

$tglOrder = $tglSetuju = $tglKetahui = $tglCancel = [];


  $lastweek = Illuminate\Support\Facades\DB::select(DB::Raw("select * from e_rkb_header
  where tgl_order between date_sub(now(),INTERVAL 1 WEEK) and now() group by Date(tgl_order)"));

  $lastweekAction = Illuminate\Support\Facades\DB::select(DB::Raw("select * from e_rkb_header ,e_rkb_approve
  where ((e_rkb_approve.tgl_disetujui between date_sub(now(),INTERVAL 1 WEEK) and now()) and (e_rkb_approve.tgl_disetujui between date_sub(now(),INTERVAL 1 WEEK) and now()) or (e_rkb_approve.tgl_diketahui between date_sub(now(),INTERVAL 1 WEEK) and now()) or (e_rkb_approve.tgl_cancel_user between date_sub(now(),INTERVAL 1 WEEK) and now()))  group by Date(e_rkb_approve.tgl_disetujui) , Date(e_rkb_approve.tgl_cancel_user),Date(e_rkb_approve.tgl_diketahui),Date(e_rkb_header.tgl_order)"));

  foreach($lastweek as $kLast => $vLast){
    $tglOrder[]   = date("Y-m-d",strtotime($vLast->tgl_order));
  }

  foreach($lastweekAction as $kApprove => $vApprove){
    if($vApprove->tgl_disetujui!=null){
        $tglSetuju[]  = date("Y-m-d",strtotime($vApprove->tgl_disetujui));
    }
    if($vApprove->tgl_diketahui!=null){
    $tglKetahui[] = date("Y-m-d",strtotime($vApprove->tgl_diketahui));
    }
    if($vApprove->tgl_cancel_user!=null){
    $tglCancel[]  = date("Y-m-d",strtotime($vApprove->tgl_cancel_user));
    }
  }

  $merge = array_unique(array_merge($tglOrder,$tglSetuju,$tglKetahui,$tglCancel));

  foreach($merge as $kMerge => $vMerge){
  $findDate = date('Y-m-d',strtotime($vMerge));
  $countDay = Illuminate\Support\Facades\DB::table("e_rkb_header")
            ->whereDate("tgl_order",$findDate)
            ->count();  
  $countApprove = Illuminate\Support\Facades\DB::table("e_rkb_approve")
            ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
            ["e_rkb_approve.diketahui",1],
            ["e_rkb_approve.cancel_user",null]
            ])
            ->whereDate("e_rkb_approve.tgl_diketahui",$findDate)
            ->count();
  
  $countWaiting = Illuminate\Support\Facades\DB::table("e_rkb_approve")
            ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->whereRaw(" (e_rkb_approve.diketahui=0 and e_rkb_approve.cancel_user IS NULL)")
            ->whereDate("e_rkb_header.tgl_order",$findDate)
            ->count();
            
  $countCancel = Illuminate\Support\Facades\DB::table("e_rkb_approve")
            ->join("e_rkb_header","e_rkb_header.no_rkb","e_rkb_approve.no_rkb")
            ->where([
            ["e_rkb_approve.cancel_user","!=",null]
            ])
            ->whereDate("e_rkb_approve.tgl_cancel_user",$findDate)
            ->count();
    $getCount[] = $countDay;
    $approvE[] = $countApprove;
    $waiting[] = $countWaiting;
    $cancel[] = $countCancel;
    $getDay[] = date("l",strtotime($vMerge));
  }
  //dd($_SESSION['section']);
@endphp
<script>
    
    /* ECHRTS */    
    function init_echarts() {

        if( typeof (echarts) === 'undefined'){ return; }    
          var theme = {
          color: [
            '#004C97', '#41FF22', '#F7C803', '#F21003',
            '#9B59B6', '#466C95', '#F7C803', '#3E606F'
          ],

          title: {
            itemGap: 8,
            textStyle: {
              fontWeight: 'normal',
              color: '#408829'
            }
          },

          dataRange: {
            color: ['#1f610a', '#97b58d']
          },

          toolbox: {
            color: ['#408829', '#408829', '#408829', '#408829']
          },

          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.5)',
            axisPointer: {
              type: 'line',
              lineStyle: {
                color: '#408829',
                type: 'dashed'
              },
              crossStyle: {
                color: '#408829'
              },
              shadowStyle: {
                color: 'rgba(200,200,200,0.3)'
              }
            }
          },

          dataZoom: {
            dataBackgroundColor: '#eee',
            fillerColor: 'rgba(64,136,41,0.2)',
            handleColor: '#408829'
          },
          grid: {
            borderWidth: 0
          },

          categoryAxis: {
            axisLine: {
              lineStyle: {
                color: '#408829'
              }
            },
            splitLine: {
              lineStyle: {
                color: ['#eee']
              }
            }
          },

          valueAxis: {
            axisLine: {
              lineStyle: {
                color: '#408829'
              }
            },
            splitArea: {
              show: true,
              areaStyle: {
                color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
              }
            },
            splitLine: {
              lineStyle: {
                color: ['#eee']
              }
            }
          },
          timeline: {
            lineStyle: {
              color: '#408829'
            },
            controlStyle: {
              normal: {color: '#408829'},
              emphasis: {color: '#408829'}
            }
          },

          k: {
            itemStyle: {
              normal: {
                color: '#68a54a',
                color0: '#a9cba2',
                lineStyle: {
                  width: 1,
                  color: '#408829',
                  color0: '#86b379'
                }
              }
            }
          },
          map: {
            itemStyle: {
              normal: {
                areaStyle: {
                  color: '#ddd'
                },
                label: {
                  textStyle: {
                    color: '#c12e34'
                  }
                }
              },
              emphasis: {
                areaStyle: {
                  color: '#99d2dd'
                },
                label: {
                  textStyle: {
                    color: '#c12e34'
                  }
                }
              }
            }
          },
          force: {
            itemStyle: {
              normal: {
                linkStyle: {
                  strokeColor: '#408829'
                }
              }
            }
          },
          chord: {
            padding: 4,
            itemStyle: {
              normal: {
                lineStyle: {
                  width: 1,
                  color: 'rgba(128, 128, 128, 0.5)'
                },
                chordStyle: {
                  lineStyle: {
                    width: 1,
                    color: 'rgba(128, 128, 128, 0.5)'
                  }
                }
              },
              emphasis: {
                lineStyle: {
                  width: 1,
                  color: 'rgba(128, 128, 128, 0.5)'
                },
                chordStyle: {
                  lineStyle: {
                    width: 1,
                    color: 'rgba(128, 128, 128, 0.5)'
                  }
                }
              }
            }
          },
          gauge: {
            startAngle: 225,
            endAngle: -45,
            axisLine: {
              show: true,
              lineStyle: {
                color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                width: 8
              }
            },
            axisTick: {
              splitNumber: 10,
              length: 12,
              lineStyle: {
                color: 'auto'
              }
            },
            axisLabel: {
              textStyle: {
                color: 'auto'
              }
            },
            splitLine: {
              length: 18,
              lineStyle: {
                color: 'auto'
              }
            },
            pointer: {
              length: '90%',
              color: 'auto'
            },
            title: {
              textStyle: {
                color: '#333'
              }
            },
            detail: {
              textStyle: {
                color: 'auto'
              }
            }
          },
          textStyle: {
            fontFamily: 'Arial, Verdana, sans-serif'
          }
        };

    title_teks = "Graph RKB";
    text_label = "";
         //echart Line
        
      if ($('#echart_line').length ){ 
        
        var echartLine = echarts.init(document.getElementById('echart_line'), theme);

        echartLine.setOption({
        title: {
          text: title_teks,
          subtext: 'Subtitle'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          x: 100,
          y: 30,
          data: ["Total Rkb "+text_label,"Rkb "+text_label+" Approve","Rkb "+text_label+" Waiting", "Rkb "+text_label+" Cancel"  ]
        },
        toolbox: {
          show: true,
          feature: {
          magicType: {
            show: true,
            title: {
            line: 'Line',
            bar: 'Bar',
            stack: 'Stack',
            tiled: 'Tiled'
            },
            type: ['line', 'bar', 'stack', 'tiled']
          },
          restore: {
            show: true,
            title: "Restore"
          },
          saveAsImage: {
            show: true,
            title: "Save Image"
          }
          }
        },
        calculable: true,
        xAxis: [{
          type: 'category',
          boundaryGap: false,
          data: <?php echo json_encode($getDay); ?>
        }],
        yAxis: [{
          type: 'value'
        }],
        series: [{
          name: "Total Rkb "+text_label,
          type: 'line',
          smooth: true,
          itemStyle: {
          normal: {
            areaStyle: {
            type: 'default'
            }
          }
          },
          data: <?php echo json_encode($getCount); ?>
        }, {
          name: "Rkb "+text_label+" Approve",
          type: 'line',
          smooth: true,
          itemStyle: {
          normal: {
            areaStyle: {
            type: 'default'
            }
          }
          },
          data: <?php echo json_encode($approvE); ?>
        }, {
          name: "Rkb "+text_label+" Waiting",
          type: 'line',
          smooth: true,
          itemStyle: {
          normal: {
            areaStyle: {
            type: 'default'
            }
          }
          },
          data: <?php echo json_encode($waiting); ?>
        }, {
          name: "Rkb "+text_label+" Cancel",
          type: 'line',
          smooth: true,
          itemStyle: {
          normal: {
            areaStyle: {
            type: 'default'
            }
          }
          },
          data: <?php echo json_encode($cancel); ?>
        }]
        });

      }
}
init_echarts();
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