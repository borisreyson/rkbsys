@extends('layout.master')
@section('title')
ABP-system | HSE - Inspeksi Report
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
                  <h2>Inspeksi Report</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
<div class="row col-lg-12">
      <div class="btn-group">
        <form method="get" class="col-xs-11 input-group">
      <span class="input-group-addon">
        Dari
      </span>
      <input type="text" class="form-control" value="{{$_GET['dari'] or date('d F Y')}}" name="dari" id="dari" placeholder="Dari Tanggal" aria-label="Dari Tanggal" aria-describedby="basic-addon1">
     <span class="input-group-addon">
        Sampai
      </span>
      <input type="text" class="form-control" id="sampai" value="{{$_GET['sampai'] or date('d F Y')}}" name="sampai" placeholder="Sampai Tanggal" aria-label="Sampai Tanggal" aria-describedby="basic-addon1">
     <span class="input-group-btn">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{url('/hse/admin/inspeksi/report')}}" class="btn btn-warning"><i class="fa fa-refresh"></i></a>
        @if(count($inspeksiHeader)>0)
        @if(isset($_GET['dari']))
        <a href="{{url('/hse/admin/inspeksi/report/export/all?dari='.$_GET['dari'].'&sampai='.$_GET['sampai'])}}" class="btn btn-danger">Export To Excel</a>
        @else
        <a href="{{url('/hse/admin/inspeksi/report/export/all')}}" class="btn btn-danger">Export To Excel</a>
        @endif
        @endif
      </span>
    </form>
</div>
</div>
  <div class="row col-lg-12">
    <div class="table-responsive" style="width: 100%!important;">
<table class="table table-striped table-bordered" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" style="vertical-align: middle;">Inspeksi</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Tanggal Inspeksi</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Perusahaan</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Lokasi</th>
      <th style="vertical-align: middle; text-align: center;">Saran</th>
      <th style="vertical-align: middle; text-align: center;">Team Inspeksi</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Foto Temuan</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Temuan</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Tindakan</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Nik Penanggung Jawab</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Nama Penanggug Jawab</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Tanggal Tenggat</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Foto Perbaikan</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Keterangan Perbaikan</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Tanggal Selesai</th>
      <th class="text-center nowrap" style="vertical-align: middle;">Status</th>
    </tr>
  </thead>
  <tbody>
    @if(count($inspeksiHeader)>0)
    @foreach($inspeksiHeader as $k => $value)
    @php
    $pica[$k] = Illuminate\Support\Facades\DB::table("hse.form_inspeksi_pika as a")
                            ->where("a.idInspeksi",$value->idInspeksi)
                            ->get();
    @endphp
    @if(count($pica[$k])>0)
    @foreach($pica[$k] as $kk => $v)
      <tr>
      @if($kk == 0)
      <td class="text-center" rowspan="{{count($pica[$k])}}" style="vertical-align: middle;min-width: 300px;">{{$value->namaForm}} </td>
      <td class="text-center nowrap" rowspan="{{count($pica[$k])}}" style="vertical-align: middle;">{{date("d F Y",strtotime($value->tgl_inspeksi))}}</td>
      <td class="text-center nowrap" rowspan="{{count($pica[$k])}}" style="vertical-align: middle;">{{$value->nama_perusahaan}}</td>
      <td class="text-center nowrap" rowspan="{{count($pica[$k])}}" style="vertical-align: middle;">{{$value->lokasiInspeksi}}</td>
      <td rowspan="{{count($pica[$k])}}" style="text-align: center; vertical-align: middle;min-width: 300px!important;">{{$value->saran or "-"}}</td>
      <td class="text-left nowrap" rowspan="{{count($pica[$k])}}"style="vertical-align:middle;">
        @php
          $teamInspeksi = Illuminate\Support\Facades\DB::table("hse.team_inspeksi as aa")
                          ->leftJoin('user_login as bb',"bb.nik","aa.nikTeam")
                          ->where("aa.idInspeksi",$value->idInspeksi)
                          ->get();
          
          if(count($teamInspeksi)>0){
            foreach($teamInspeksi as $kTeam => $vTeam){
              echo $vTeam->nikTeam." | ".$vTeam->nama_lengkap."<br>";
            }
          }else{
            echo "-";
          }
            
        @endphp
      </td>
      @endif
      <td class="text-center nowrap" style="vertical-align: middle;">
      <a class="img-responsive img-thumbnail" href="{{asset('/bukti_inspeksi/sebelum/'.$v->picaSebelum)}}" target="_blank">
          <img style="object-fit:contain; width:100px; height:100px;" src="{{asset('/bukti_inspeksi/sebelum/'.$v->picaSebelum)}}" >
        </a>
        </td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$v->picaTemuan}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$v->picaTindakan or "-"}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$v->picaNikPJ}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$v->picaNamaPJ}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{date("d F Y",strtotime($v->picaTenggat))}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">
        @if($v->picaSesudah!=null)
      <a class="img-responsive img-thumbnail" href="{{asset('/bukti_inspeksi/sesudah/'.$v->picaSesudah)}}" target="_blank">
          <img style="object-fit:contain; width:100px; height:100px;" src="{{asset('/bukti_inspeksi/sesudah/'.$v->picaSesudah)}}" >
        </a>
        @else
        -
        @endif
      </td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$v->picaPerbaikan or "-"}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">
        @if($v->tgl_selesai!=null)
        {{date("d F Y",strtotime($v->tgl_selesai))}}
        @else
        -
        @endif
      </td>
      @if($v->status=="SELESAI")
      <td class="text-center nowrap" style="vertical-align: middle;background-color:#4CAF50; color: #FFFFFF;">{{$v->status}}</td>
      @elseif($v->status=="BELUM SELESAI")
      <td class="text-center nowrap" style="vertical-align: middle; background-color:#F44336; color: #FFFFFF;">{{$v->status}}</td>
      @elseif($v->status=="BERLANJUT")
      <td class="text-center nowrap" style="vertical-align: middle; background-color:#2196F3; color: #FFFFFF;">{{$v->status}}</td>
      @elseif($v->status=="DALAM PENGERJAAN")
      <td class="text-center nowrap" style="vertical-align: middle; background-color:#2196F3; color: #FFFFFF;">{{$v->status}}</td>
      @endif     
      </tr>

    @endforeach
    @else
    <tr>
      <td class="text-center"  style="vertical-align: middle;min-width: 300px;">{{$value->namaForm or "-"}} </td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{date("d F Y",strtotime($value->tgl_inspeksi))}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$value->nama_perusahaan or "-"}}</td>
      <td class="text-center nowrap"  style="vertical-align: middle;">{{$value->lokasiInspeksi or "-"}}</td>
      <td style="text-align: center; vertical-align: middle;min-width: 300px!important;">{{$value->saran or "-"}}</td>
      <td class="text-left nowrap" style="vertical-align:middle;">
        @php
          $teamInspeksi = Illuminate\Support\Facades\DB::table("hse.team_inspeksi as aa")
                          ->leftJoin('user_login as bb',"bb.nik","aa.nikTeam")
                          ->where("aa.idInspeksi",$value->idInspeksi)
                          ->get();
          
          if(count($teamInspeksi)>0){
            foreach($teamInspeksi as $kTeam => $vTeam){
              echo $vTeam->nikTeam." | ".$vTeam->nama_lengkap."<br>";
            }
          }else{
            echo "-";
          }
            
        @endphp
      </td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">Tidak Ada Temuan</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      <td class="text-center nowrap" style="vertical-align: middle;">-</td>
      </tr>
    @endif
      @endforeach
    @endif
  </tbody>
</table></div>

<div class="col-lg-12 text-center">
  {{$inspeksiHeader->links()}}
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
    <script>
      $("input[name=dari]").datepicker({ dateFormat: 'dd MM yy' });
      $("input[name=sampai]").datepicker({ dateFormat: 'dd MM yy' });
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