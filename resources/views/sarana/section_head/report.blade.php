@extends('layout.master')
@section('title')
ABP-system | Report Keluar - Masuk Sarana
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
.event a {
    background-color: #42B373 !important;
    background-image :none !important;
    color: #ffffff !important;
}
</style>
@endsection
@section('content')
<?php
//dd(date("Y-m-d H:i:s",strtotime("-1 Days")));
$arrRULE = [];
  if(isset($getUser)){
    $arrRULE = explode(',',$getUser->rule);    
  }else{
    ?>
<script>
  window.location="{{url('/logout')}}";
</script>
    <?php } ?>
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
                  <h2>Keluar - Masuk Sarana</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">

<div class="row">
<div class="col-lg-12 ">
<!-- start accordion -->
<div class="accordion" id="accordion" role="tablist" aria-multiselectable="false">
@foreach($K_M as $k => $v)
<?php
  $pemohon = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")->where("nik",$v->nik)->first();

?>
@if(isset($pemohon))
<div class="panel" style="border:1px solid #333;vertical-align: middle!important;">
  <div style="cursor: pointer;cursor: hand; " class="panel-heading collapsed " role="tab" id="heading{{$k}}" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$k}}" aria-expanded="false" aria-controls="collapseOne">
  <div class="row" id="my_id">
    
    <h4 class="panel-title">
<div class="col-lg-8 col-md-12 col-sm-12">
  <div class="row">
    <div class="col-lg-6 col-xs-12">
      <i class="fa fa-bars" id="menus_animate"></i> No : <font color="blue" style="font-weight: bolder;"> {{$v->nomor}}</font> 
      | 
      Pemohon
      <font color="blue" style="font-weight: bolder;"> {{ucwords($pemohon->nama)}}</font>
      <br><br>
    </div>
    <div class="col-lg-6 col-xs-12">
      <span>Tanggal : {{date("d F Y",strtotime($v->tgl_out))}}</span>
      <span>Jam Keluar : {{date("H:i:s",strtotime($v->jam_out))}}</span>
      <br><br>
      </div>
      </div>
</div>
    <div class="pull-right col-lg-4 col-md-12 col-sm-12 text-right">
      @if($v->no_lv=="motor" || $v->no_lv=="mobil"){{ucwords($v->no_lv)}} |@endif
          Status
           : 
           @if(strtotime($v->entry_keluar) < strtotime(date("Y-m-d")))
            <button class="btn btn-xs btn-danger" id="expr" type="button"><i class="fa fa-times"></i> Expired</button>
           @else
           @if($v->flag_appr==2)
           @if(strtotime($v->entry_keluar) < strtotime(date("Y-m-d")))
            <button class="btn btn-xs btn-danger" id="expr" type="button"><i class="fa fa-times"></i> Expired</button>
           @else           
           <label class="btn btn-xs btn-warning" id="menunggu"><i class="fa fa-spinner fa-spin"></i> Waiting</label> 
           @endif
           @elseif($v->flag_appr==0)
           <label class="btn btn-xs btn-danger" id="dicancel"><i class="fa fa-times"></i> Cancel | {{date("H:i:s d F Y",strtotime($v->tanggal_appr))}}</label>
           @elseif($v->flag_appr==1)
           <label class="btn btn-xs btn-success" id="disetujui"><i class="fa fa-check"></i>Disetujui | {{date("H:i:s d F Y",strtotime($v->tanggal_appr))}}</label> 
           <a href="" class="btn btn-xs btn-primary" data_id="{{$v->noid_out}}" id="lihat"><i class="fa fa-desktop"></i> Lihat</a>
           @endif    
           @endif
           
        </div>
    </h4>
    </div>
  </div>
  <div id="collapse{{$k}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$k}}">
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="col-lg-12">
             @if($v->no_lv=="motor" || $v->no_lv=="mobil")
          <b class="col-lg-2">Jenis Kendaraan </b> <span class="col-lg-9"><b>:</b> {{ucwords($v->no_lv)}} @if(isset($v->no_pol)) ({{ucwords($v->no_pol)}}) @endif</span>
          @else
          <b class="col-lg-2">No LV </b> <span class="col-lg-9"><b>:</b> {{ucwords($v->no_lv)}}</span>
          @endif
          </div>
          <div class="col-lg-12">
            @if($v->no_lv=="motor" || $v->no_lv=="mobil")
            <b class="col-lg-2">Merk Kendaraan </b>  <span class="col-lg-9"><b>:</b> {{ucwords($v->driver)}}</span>
            @else
            <?php
            $driver = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")->where("nik",$v->driver)->first();
            ?>
          <b class="col-lg-2">Driver </b>  <span class="col-lg-9"><b>:</b> {{ucwords($driver->nama)}}</span>
          
          @endif
          </div>
          <div class="col-lg-12">
          <b class="col-lg-1">Keperluan </b>  <span class="col-lg-9"><b>:</b> {{$v->keperluan}}</span>
          </div>
          <div class="col-lg-12">
            <br><br>
          </div>          
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Tanggal Keluar</th>
            <th>Jam Keluar</th>
            <th>Tanggal Masuk</th>
            <th>Jam Masuk</th>
            @if(isset($v->keterangan_in))
            <th>Keterangan Masuk</th>
            @endif
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{date("d F Y",strtotime($v->tgl_out))}}</td>
            <td>{{date("H:i:s",strtotime($v->jam_out))}}</td>
            <td>@if(isset($v->tgl_in)){{date("d F Y",strtotime($v->tgl_in))}}@else - @endif</td>
            <td>@if(isset($v->jam_in)){{date("H:i:s",strtotime($v->jam_in))}}@else - @endif</td>
            @if(isset($v->keterangan_in))
            <td>{{($v->keterangan_in)}}</td>
            @endif
          </tr>
          @if(!isset($v->tgl_in))
          <tr>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">
           @if(in_array('user sarpras',$arrRULE))
              <button class="btn btn-xs btn-primary" id="T_M_IN" noid="{{$v->noid_out}}" type="button" name="T_M_IN" data-toggle="modal" data-target="#myModal">Input Tanggal & Jam Masuk</button>
              @endif
            </td>
          </tr>
          @endif
        </tbody>
      </table>
      </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <h3>Penumpang</h3>
        </div>
        <div class="col-lg-12">
          <table class="table-bordered table">
            <thead>
              <tr>
                <th>Nik</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Dept / Sect</th>
              </tr>
            </thead>
            <tbody>
        <?php
        $semua_penumpang = explode(",",$v->penumpang_out);
        ?>
        @foreach($semua_penumpang as $kP => $vP)
        @php
          $p = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")
                ->join("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                ->join("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                ->where("db_karyawan.data_karyawan.nik",$vP)->first();

        @endphp
        @if(isset($p))
              <tr>
                <td>{{$p->nik}}</td>
                <td>{{ucwords($p->nama)}}</td>
                <td>{{ucwords($p->jabatan)}}</td>
                <td>{{ucwords($p->dept)}} / {{ucwords($p->sect)}}</td>
              </tr>
        @endif
        @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 pull-right">
        @php
        /*
          $appr = Illuminate\Support\Facades\DB::table("vihicle.v_approve")
                ->join("user_login","user_login.username","vihicle.v_approve.user_appr")
                ->where("noid_out",$v->noid_out)->first();
                */
        @endphp
          <!--<label class="control-label" style="color:blue;font-size: 16px;">Disetujui Oleh : </label>-->
        </div>
      </div>
    </div>
  </div>
</div>      
@endif               
@endforeach
</div>
<!-- end of accordion -->
</div>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
  {{$K_M->links()}}
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
  <div class="modal-dialog modal-lg" id="modal_dialog">
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
  $("button[id=T_M_IN]").click(function(){
    eq = $("button[id=T_M_IN]").index(this);
    noid = $("button[id=T_M_IN]").eq(eq).attr("noid");

    $.ajax({
      type:"GET",
      url:"{{url('/sarpras/sarana/keluar-masuk/t_m_in')}}",
      data:{noid_out:noid},
      beforeSend:function(){
        $("div[id=modal_dialog]").removeClass("modal-lg").addClass("modal-md");
      },
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("a[id=lihat]").click(function(){
    eq = $("a[id=lihat]").index(this);
    noid_out = $("a[id=lihat]").eq(eq).attr("data_id");
    window.open("{{url('/sarpras/sarana/keluar-masuk-print-out-')}}"+noid_out,"_blank");
    return false;
  });
  var eventDates = {};
  <?php
    $dateEvent = Illuminate\Support\Facades\DB::table("vihicle.v_out_h")->groupBy("tgl_out")->get();
  foreach($dateEvent as $kEvent => $vEvent){
  ?>
    eventDates[ new Date( "{{date('m/d/Y',strtotime($vEvent->tgl_out))}}" )] = new Date( "{{date('m/d/Y',strtotime($vEvent->tgl_out))}}" );
  <?php
    }
  ?>
  $(".datepicker").datepicker({ 
                                dateFormat: 'dd MM yy', 
                                beforeShowDay: function(date) {
                                var highlight = eventDates[date];
                                if (highlight) {
                                     return [true, "event", "highlight"];
                                } else {
                                     return [true, '', ''];
                                }
                              }  
                            });
  $("button[id=Approve]").click(function(){
    eq = $("button[id=Approve]").index(this);
    data_id = $("button[id=Approve]").eq(eq).attr("data_id");
    //alert(data_id);
    $.ajax({
      type:"POST",
      url:"{{url('/sarpras/sarana/approve')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id},
      success:function(res){
        new PNotify({
          title: 'Info',
          text: res,
          type: 'info',
          hide: true,
          styling: 'bootstrap3'
      });
        setTimeout(function(){
          window.location.reload();
        },500);
      }
    });
    return false;
  });
  $("button[id=cancel]").click(function(){
    eq = $("button[id=cancel]").index(this);
    data_id = $("button[id=cancel]").eq(eq).attr("data_id");
    $.ajax({
      type:"POST",
      url:"{{url('/sarpras/sarana/cancel')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id},
      beforeSend:function(){
        $("div[id=modal_dialog]").removeClass("modal-lg").addClass("modal-md");
      },
      success:function(res){        
        $("div[id=konten_modal]").html(res);
      }
    });
      
  });
  $("button[id=newOutForm]").click(function(){
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/sarana/form-keluar')}}",
      beforeSend:function(){
        $("div[id=modal_dialog]").removeClass("modal-md").addClass("modal-lg");
      },
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=editOutForm]").click(function(){
    data_id = $("button[id=editOutForm]").attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/sarana/edit')}}",
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