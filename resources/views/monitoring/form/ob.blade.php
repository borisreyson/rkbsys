@extends('layout.master')
@section('title')
ABP-system | Form OB
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
 table thead tr th, table tbody tr td {
  text-align: center;
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
    <a href="{{url('/monitoring/form/ob')}}">Form OB</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Form Monitoring OB</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
<div class="row">
  <div class="col-xs-6">
<form class="form-horizontal" method="post" action="">
  {{csrf_field()}}
  <div class="form-group">
    <label class="control-label col-lg-3">Tanggal</label>
    <div class="col-lg-4">
      @if(isset($edit) == 'true')
      <input type="text" name="tgl" disabled class="form-control" required="required" value="{{date('d F Y',strtotime($daily->tgl))}}">
      @else
      <input type="text" name="tgl" class="form-control" required="required" value="{{date('d F Y')}}">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Plan Daily</label>
    <div class="col-lg-5">
      @if(isset($edit) == 'true')
      <input type="text" name="plan_daily" class="form-control" required="required" placeholder="Plan Daily" value="{{number_format($daily->plan_daily,3)}}">
      @else
      <input type="text" name="plan_daily" class="form-control" required="required" placeholder="Plan Daily" value="{{number_format($daily->ob_daily_planing,3)}}">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Actual Daily</label>
    <div class="col-lg-5">
      @if(isset($edit) == 'true')
      <input type="text" name="actual_daily" class="form-control" required="required" placeholder="Actual Daily" value="{{number_format($daily->actual_daily,3)}}">
      @else
      <input type="text" name="actual_daily" class="form-control" required="required" placeholder="Actual Daily">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Mtd Plan</label>
    <div class="col-lg-5">
      @if(isset($edit) == 'true')
      <input type="text" name="mtd_plan" class="form-control" required="required" placeholder="Mtd Plan" value="{{number_format($daily->mtd_plan,3)}}">
      @else
      <input type="text" name="mtd_plan" class="form-control" required="required" placeholder="Mtd Plan" value="{{number_format($daily->ob_mtd_planing,3)}}">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Mtd Actual</label>
    <div class="col-lg-5">
      @if(isset($edit) == "true")
      <input type="text" name="mtd_actual" class="form-control" required="required" placeholder="Mtd Actual" value="{{number_format($daily->mtd_actual,3)}}">
      @else
      <input type="text" name="mtd_actual" class="form-control" required="required" placeholder="Mtd Actual">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Keterangan</label>
    <div class="col-lg-5">
      @if(isset($edit) == 'true')
      <input type="text" name="keterangan" class="form-control" required="required" placeholder="Keterangan" value="{{$daily->remark}}">
      @else
      <input type="text" name="keterangan" class="form-control" required="required" placeholder="Keterangan">
      @endif
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
      <button class="btn btn-primary" type="submit">Simpan</button>  
      <a href="{{url('/monitoring/form/ob')}}" class="btn btn-danger">Batal</a>  
    </div>
  </div>
</form>
  </div>

<div class="col-xs-6">
  <div class="row text-center">
    <h5>
      Import Data From Excel
      <hr>
    </h5>
  </div>
<form class="form-horizontal" method="post" action="{{url('/import/abp/ob')}}" enctype="multipart/form-data">
  {{csrf_field()}}
  <div class="form-group">
    <label class="control-label col-lg-3">Input File Excel</label>
    <div class="col-lg-5">
     <input name="fileExcel" class="form-control-static" type="file" required="required"> 
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
      <button class="btn btn-primary" type="submit">Proses Data</button>  
      <a href="{{url('/monitoring/form/ob')}}" class="btn btn-danger">Batal</a>  
    </div>
  </div>
</form>
</div>
</div>
</div>
</div>
<div class="x_panel">
               <div class="x_title">
                  <h2>OB Last Week</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
<div class="row">
  <div class="col-lg-12">
    <table class="table table-bordered table-striped">
      <thead>
        <tr class="info">
          <th width="150px">Tanggal</th>
          <th>Plan Daily</th>
          <th>Actual Daily</th>
          <th>Mtd Daily</th>
          <th>Mtd Daily</th>
          <th>ACH</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $daily =  Illuminate\Support\Facades\DB::table("monitoring_produksi.ob")->orderBy("tgl","desc")->paginate(7);
        foreach($daily as $k => $row){ 
        ?>
        @if($row->flag=='2')
        <tr style="background-color: red;color: white;">
          <td>{{date("d F Y",strtotime($row->tgl))}}</td>
          <td>{{number_format($row->plan_daily,3)}}</td>
          <td>{{number_format($row->actual_daily,3)}}</td>
          <td>{{number_format($row->mtd_plan,3)}}</td>
          <td>{{number_format($row->mtd_actual,3)}}</td>
          <td>
            @if($row->mtd_actual>0)
            @if($row->mtd_plan==0)
              100%
            @else
            {{number_format($row->mtd_actual/$row->mtd_plan,2)*100}} %
            @endif
            @else
            {{number_format(0)}} %
            @endif
          </td>
          <td>
            @if(isset($_GET['page']))
            <a style="color: white;" href="{{url('/monitoring/form/ob/q-'.bin2hex($row->tgl))}}?page={{$_GET['page']}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @else
            <a style="color: white;" href="{{url('/monitoring/form/ob/q-'.bin2hex($row->tgl))}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @endif
            <a style="color: white;" href="{{url('/monitoring/form/ob/undo-'.bin2hex($row->tgl))}}" class="btn btn-xs"><i class="fa fa-undo"></i></a></td>
        </tr>
        @else
        <tr>
          <td>{{date("d F Y",strtotime($row->tgl))}}</td>
          <td>{{number_format($row->plan_daily,3)}}</td>
          <td>{{number_format($row->actual_daily,3)}}</td>
          <td>{{number_format($row->mtd_plan,3)}}</td>
          <td>{{number_format($row->mtd_actual,3)}}</td>
          <td>
            @if($row->mtd_actual>0)
            @if($row->mtd_plan==0)
              100%
            @else
            {{number_format($row->mtd_actual/$row->mtd_plan,2)*100}} %
            @endif
            @else
            {{number_format(0)}} %
            @endif
          </td>
          <td>            
            @if(isset($_GET['page']))
            <a style="" href="{{url('/monitoring/form/ob/q-'.bin2hex($row->tgl))}}?page={{$_GET['page']}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @else
            <a style="" href="{{url('/monitoring/form/ob/q-'.bin2hex($row->tgl))}}" class="btn btn-xs"><i class="fa fa-pencil"></i></a>
            @endif
            <a href="{{url('/monitoring/form/ob/delete-'.bin2hex($row->tgl))}}" class="btn btn-xs"><i class="fa fa-trash"></i></a></td>
        </tr>

        @endif
      <?php } ?>
      </tbody>
    </table>
  </div>
<div class="col-lg-12 text-center">
  {{$daily->links()}}
</div>
</div>
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

<script>
$("input[name=tgl]").datepicker({ dateFormat: 'dd MM yy' });
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