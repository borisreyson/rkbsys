@extends('layout.master')
@section('title')
ABP-system | Unit Rental
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

.active { z-index: 1!important; }
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
                  <h2>Unit Rental</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">
  <form class="form-horizontal" method="post" action="">
<div class="col-lg-4 row">
  {{csrf_field()}}

        @if(isset($edit))
        <input type="hidden" name="_method" value="PUT">
        @endif
  <div class="form-group">
    <label class="control-label col-lg-3">Tanggal</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="tgl" disabled class="form-control" required="required" value="{{date('d F Y',strtotime($edit->tgl))}}">
      @else
      <input type="text" name="tgl" class="form-control" required="required" value="{{date('d F Y',strtotime('-1 day'))}}">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Shift</label>
    <div class="col-lg-6">      
      @if(isset($edit))
      <div id="shift" class="btn-group" style="margin-bottom: 5px;" data-toggle="buttons">
        @if($edit->shift==1)
              <label class="btn btn-warning active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                <input type="radio" name="shift" value="1" checked="checked"> &nbsp; Shift I &nbsp;
              </label>
              <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-primary">
                <input type="radio" name="shift" value="2"> Shift II
              </label>
              @elseif($edit->shift==2)
              <label class="btn btn-warning " data-toggle-class="btn-primary" data-toggle-passive-class="btn-warning">
                <input type="radio" name="shift" value="1"> &nbsp; Shift I &nbsp;
              </label>
              <label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-primary">
                <input type="radio" name="shift" value="2" checked=""> Shift II
              </label>
              @endif
            </div>
      @else
      <div id="shift" class="btn-group" style="margin-bottom: 5px;" data-toggle="buttons">
              <label class="btn btn-default active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                <input type="radio" name="shift" value="1" checked="checked"> &nbsp; Shift I &nbsp;
              </label>
              <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                <input type="radio" name="shift" value="2"> Shift II
              </label>
            </div>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Unit</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <select name="unit" id="unit" class="form-control" required="required" data-live-search="true">
        <option value="">--PILIH--</option>
        @foreach($unit as $k => $v)
        @if($v->id_unit==$edit->unit)        
        <option value="{{$v->id_unit}}" selected="selected">{{$v->nama_unit}}</option>
        @else
        <option value="{{$v->id_unit}}">{{$v->nama_unit}}</option>
        @endif
        @endforeach
      </select>
      @else
      <select name="unit" id="unit" class="form-control" required="required" data-live-search="true">
        <option value="">--PILIH--</option>
        @foreach($unit as $k => $v)
        @if(isset($edit))
        @if($edit->id_unit==$v->id_unit)        
        <option value="{{$v->id_unit}}">{{$v->nama_unit}}</option>
        @else
        <option value="{{$v->id_unit}}" selected="selected">{{$v->nama_unit}}</option>
        @endif
        @else
        <option value="{{$v->id_unit}}">{{$v->nama_unit}}1</option>
        @endif
        @endforeach
      </select>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">Nama</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="nama" class="form-control" required="required" placeholder="Nama" value="{{$edit->nama}}">
      @else
      <input type="text" name="nama" class="form-control" required="required" placeholder="Nama" value="">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">HM Awal</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="hm_awal" class="form-control" required="required" placeholder="HM Awal" value="{{number_format($edit->hm_awal,1)}}">
      @else
      <input type="text" name="hm_awal" class="form-control" required="required" placeholder="HM Awal">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">HM Akhir</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="hm_akhir" class="form-control" required="required" placeholder="HM Akhir" value="{{number_format($edit->hm_akhir,1)}}">
      @else
      <input type="text" name="hm_akhir" class="form-control" required="required" placeholder="HM Akhir" value="">
      @endif
    </div>
  </div>
</div>

<div class="col-lg-3 row">
  <div class="form-group">
    <label class="control-label col-lg-3">MTK</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="mtk" class="form-control" required="required" placeholder="Mtd Actual" value="{{number_format($edit->mtk,1)}}">
      @else
      <input type="text" name="mtk" class="form-control" required="required" placeholder="Mtd Actual" value="0">
      @endif
    </div>
  </div>  
  <div class="form-group">
    <label class="control-label col-lg-3">ABP</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="abp" class="form-control" required="required" placeholder="Mtd Actual" value="{{number_format($edit->abp,1)}}">
      @else
      <input type="text" name="abp" class="form-control" required="required" placeholder="Mtd Actual" value="0">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">BD</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="bd" class="form-control" required="required" placeholder="Mtd Actual" value="{{number_format($edit->bd,1)}}">
      @else
      <input type="text" name="bd" class="form-control" required="required" placeholder="Mtd Actual" value="0">
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3">STB</label>
    <div class="col-lg-5">
      @if(isset($edit))
      <input type="text" name="stb" class="form-control" required="required" placeholder="Mtd Actual" value="{{number_format($edit->stb,1)}}">
      @else
      <input type="text" name="stb" class="form-control" required="required" placeholder="Mtd Actual" value="0">
      @endif
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-8" align="right">
      <button class="btn btn-primary" type="submit">Simpan</button>  
      <a href="{{url('/mon/unit/rental/form/hm')}}" class="btn btn-danger">Batal</a>  
    </div>
  </div>
</div>

</form>
<div class="col-lg-12 row">

  <div class="col-lg-12">
    <hr>
  </div>
  <div class="col-lg-12">
    <p><b>Jumlah : {{count($rental)}}</b> </p>
  </div>
  <table class="table table-bordered text-center">
    <thead>
      <tr class="bg-success">
        <th class="text-center">Tgl</th>
        <th class="text-center">Shift</th>
        <th class="text-center">Unit</th>
        <th class="text-center">Nama</th>
        <th class="text-center">HM Awal</th>
        <th class="text-center">HM Akhir</th>
        <th class="text-center">Total HM</th>
        <th class="text-center">ABP</th>
        <th class="text-center">MTK</th>
        <th class="text-center">Standby</th>
        <th class="text-center">Breakdown</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @if(count($rental)>0)
      @foreach($rental as $k => $v)
      <tr @if($v->flag!=1) style="background-color:red;color:white;" @endif>
        <td>{{date("d F Y",strtotime($v->tgl))}}</td>
        @php
          $unit = Illuminate\Support\Facades\DB::table('monitoring_unit.unit')->where("id_unit",$v->unit)->first();
        @endphp

        <td>
           @if($v->shift==1)
              Shift I
            @elseif($v->shift==2)
              Shift II
            @endif
        </td>
        <td>{{$unit->nama_unit}}</td>
        <td>{{$v->nama}}</td>
        <td>{{number_format($v->hm_awal,1)}}</td>
        <td>{{number_format($v->hm_akhir,1)}}</td>
        <td>{{number_format($v->hm_akhir-$v->hm_awal,1)}}</td>
        <td>{{number_format($v->abp,1)}}</td>
        <td>{{number_format($v->mtk,1)}}</td>
        <td>{{number_format($v->stb,1)}}</td>
        <td>{{number_format($v->bd,1)}}</td>
        <td>
          <a class="btn btn-warning btn-xs" href="{{url('/mon/unit/rental/form/hm-'.bin2hex($v->id_hm))}}" id="edit"><i class="fa fa-pencil"></i></a>
          @if($v->flag==1)
          <a class="btn btn-danger btn-xs" href="{{url('/mon/unit/rental/hm-del'.bin2hex($v->id_hm))}}" id="edit"><i class="fa fa-trash"></i></a>
          @else
          <a class="btn btn-success btn-xs" href="{{url('/mon/unit/rental/hm-undo'.bin2hex($v->id_hm))}}" id="edit"><i class="fa fa-undo"></i></a>
          @endif
        </td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="10">Data Kosong</td>
      </tr>

      @endif
    </tbody>
  </table>
  <div class="col-lg-12">
    <p><b>Jumlah : {{count($rental)}}</b> </p>
  </div>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
    {{$rental->links()}}
    <span id="text"></span>
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
<script>
  $("input[name=mtk]").keyup(function(){
    hm_awal = $("input[name=hm_awal]").val();
    hm_akhir= $("input[name=hm_akhir]").val();
    bd      = $("input[name=bd]").val();
    totalHM = (hm_akhir.replace(",","")-hm_awal.replace(",",""));

    mtk = $("input[name=mtk]").val();
    txtABP = totalHM.toFixed(1)-mtk.replace(",","");
    stb = 12 - totalHM.toFixed(1)- bd.replace(",","");
    $("input[name=abp]").val(txtABP.toFixed(1));
    $("input[name=stb]").val(stb.toFixed(1));
  });

  $("input[name=bd]").keyup(function(){
    hm_awal = $("input[name=hm_awal]").val();
    hm_akhir= $("input[name=hm_akhir]").val();
    bd      = $("input[name=bd]").val();
    totalHM = (hm_akhir.replace(",","")-hm_awal.replace(",",""));

    mtk = $("input[name=mtk]").val();
    txtABP = totalHM.toFixed(1)-mtk.replace(",","");
    stb = 12 - totalHM.toFixed(1)- bd.replace(",","");
    $("input[name=abp]").val(txtABP.toFixed(1));
    $("input[name=stb]").val(stb.toFixed(1));
  });
  
  $("input[name=tgl]").datepicker({ dateFormat: 'dd MM yy' });
  $("select").selectpicker();
  $(".myButtonDiss").click(function(){
      document.location= "{{url('/sarpras/data/driver')}}";
    });
  $("button[id=newDriver]").click(function(){
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/driver/form')}}",
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=editDriver]").click(function(){
    eq = $("button[id=editDriver]").index(this);
    data_id = $("button[id=editDriver]").eq(eq).attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/driver/edit')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id},
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