@extends('layout.master')
@section('title')
ABP-system | Keluar - Masuk Sarana
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
.btn-nama,.btn-nama:hover{
  background-color: #2A334D;
  color:#f8f8f8;
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
<div class="col-lg-12 row">
          @if(in_array('user sarpras',$arrRULE))
  <button class="btn btn-primary" id="newOutForm" data-toggle="modal" data-target="#myModal">Form Sarana Keluar</button>
          @endif
  @if(isset($_GET['dt_expr']))
  <a href="?" class="btn btn-default" id="expr" type="button">Form Sarana Keluar Sekarang</a>
  
  
 @else
  <a href="?dt_expr={{bin2hex(date('Y-m-d',strtotime('-1 Days')))}}" class="btn btn-danger" id="Expr_form">Form Sarana Keluar Kadaluarsa</a>
  @endif
  <div class="col-lg-6 pull-right">
  <div class="row">
    <form method="get" class="col-xs-6 pull-right input-group">
    <input type="text" class="datepicker form-control" name="dt_expr" value="{{date('d F Y',strtotime('-1 days'))}}">
    <span class="input-group-btn">
            <button class="btn btn-default" name="kirim" type="submit">Go!</button>
          </span>
    </form>
    </div>
  </div>
</div>
<div class="row">
<div class="col-lg-12 ">
<!-- start accordion -->
<div class="accordion" id="accordion" role="tablist" aria-multiselectable="false">
  <script>
    var time1=[];
    var minute1 = [];
  </script>
  <?php $now = strtotime(date("Y-m-d H:i:s"));?>
@foreach($K_M as $k => $v)
<?php
  $pemohon = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")->where("nik",$v->nik)->first();

?>
<div class="panel" style="border:1px solid #333;vertical-align: middle!important;">
  <div style="cursor: pointer;cursor: hand; " class="panel-heading collapsed " role="tab" id="heading{{$k}}" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$k}}" aria-expanded="false" aria-controls="collapseOne">
  <div class="row" id="my_id">
    
    <h4 class="panel-title">
<div class="col-lg-8 col-md-12 col-sm-12">
  <div class="row">
    <div class="col-lg-6 col-xs-12">
      @php
        $time[$k] = strtotime($v->out_entry);
        $minute[$k] = strtotime(date("Y-m-d H:i:s",strtotime("+30 minutes",($time[$k]))));
        $time1[$k] = ($minute[$k]-$now);
      @endphp
      <table border="0">
        <tr>
          <td><i class="fa fa-bars" id="menus_animate"></i> No : <font color="blue" style="font-weight: bolder;"> {{$v->nomor}}</font> </td>
          <td rowspan="3">&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td><span>
              Pemohon
              <font color="blue" style="font-weight: bolder;"> {{ucwords($pemohon->nama)}}</font> </span></td>
        </tr>
        <tr>
          <td>Create At </td>
          <td>          
              <span>{{date("d M Y",strtotime($v->out_entry))}}</span>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>          
              <span>{{date("H:i:s",strtotime($v->out_entry))}}</span>
          </td>
        </tr>
      </table>
    </div>
    <div class="col-lg-6 col-xs-12">
      <table align="center" style="text-align: center;">
        <tr>
          <td>
      <span>Tanggal & Jam Keluar : </span>
          </td>
      </tr>
      <tr>
        <td>
      <span>{{date("d F Y",strtotime($v->tgl_out))}}</span>
        </td>
      </tr>
      <tr>
        <td>
      <span>{{date("H:i:s",strtotime($v->jam_out))}}</span>
        </td>
      </tr>
      </table>
      </div>
      </div>
</div>
@if($v->flag==0)
@if($v->flag_appr==2)
<div class="col-lg-2 text-center" style=" color: #173B40;">
  <span>Durasi Approval</span><br><span id="waktu{{$k}}" style="color: #4F8EFF;"><span id="time{{$k}}">00:00</span> Menit</span>
</div>
@endif
@endif
<div class="row">
    <div class="pull-right col-lg-4 col-md-12 col-sm-12 text-right">
      @if($v->no_lv=="motor" || $v->no_lv=="mobil"){{ucwords($v->no_lv)}} |@endif
          Status
           : 
           @if(in_array('approve sarpras',$arrRULE))
           @if($v->flag==0)
           @if($v->flag_appr==2)
           @if(isset($_GET['dt_expr']))
            <button class="btn btn-xs btn-danger" id="expr" type="button"><i class="fa fa-times"></i> Expired</button>
           @else           
           <label class="btn btn-xs btn-warning" id="menunggu"><i class="fa fa-spinner fa-spin"></i> Waiting</label> 
           @endif
           @elseif($v->flag_appr==0)
           <label class="btn btn-xs btn-danger" id="dicancel"><i class="fa fa-times"></i> Cancel | {{date("H:i:s d F Y",strtotime($v->tanggal_appr))}}</label>
           @elseif($v->flag_appr==1)
           <table style="float:right;">
             <tr>
               <td> 
                <label class="btn btn-xs btn-success" id="disetujui"><i class="fa fa-check"></i>Disetujui | {{date("H:i:s d F Y",strtotime($v->tanggal_appr))}}</label>
               </td>
               <td>
                <a href="" class="btn btn-xs btn-primary" data_id="{{$v->noid_out}}" id="lihat"><i class="fa fa-desktop"></i> Lihat</a>
               </td>
             </tr>
             <tr>
               <td>
                <?php
                $user_appr = Illuminate\Support\Facades\DB::table("user_login")->where("username",$v->user_appr)->first();
                ?>
                <label class="btn btn-xs btn-nama" id="">Disetujui Oleh <b>{{$user_appr->nama_lengkap}}</b></label>
              </td>
               <td></td>
             </tr>
           </table>           
           @endif 
           @else           
            <button class="btn btn-xs btn-danger" id="expr" type="button"><i class="fa fa-times"></i> Cancel</button>
           @endif
           @endif
        </div>
</div>
    </h4>
    </div>
  </div>
  <div id="collapse{{$k}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$k}}">
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-12">
            @if($v->flag>0)
          <div class="col-lg-12" style="color: red;">
          <b class="col-lg-2">Remark Cancel </b> <b class="col-lg-9"><b>:</b> {{$v->flag_note}}</b>
          </div>
            @endif
          <div class="col-lg-12">
           @if($v->no_lv=="motor" || $v->no_lv=="mobil")
          <b class="col-lg-2">Jenis Kendaraan </b> <span class="col-lg-9"><b>:</b> {{ucwords($v->no_lv)}} @if(isset($v->no_pol)) ({{ucwords($v->no_pol)}}) @endif</span>
          @else
          <b class="col-lg-2">No LV </b> <span class="col-lg-9"><b>:</b> {{ucwords($v->no_lv)}}</span>
          @endif
          </div>
          <div class="col-lg-12">
           @if($v->no_lv=="motor" || $v->no_lv=="mobil")
            <b class="col-lg-2">Merk Kendaraan </b>  <span class="col-lg-9"><b>:</b> {{ucwords($v->driver)}} @if(isset($v->no_pol))({{ucwords($v->no_pol)}}@endif</span>
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
                ->leftjoin("section","section.id_sect","db_karyawan.data_karyawan.devisi")
                ->where("nik",$vP)->first();
        @endphp
              <tr>
                <td>{{$p->nik}}</td>
                <td>{{ucwords($p->nama)}}</td>
                <td>{{ucwords($p->jabatan)}}</td>
                <td>{{ucwords($p->dept)}} / {{ucwords($p->sect)}}</td>
              </tr>
        @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12" id="tombol_appr{{$k}}">          
          @if($v->flag==0)
          @if(in_array('approve sarpras',$arrRULE))
           @if($v->tgl_out>=date("Y-m-d"))
          @if($v->flag_appr==2)
           <button class="btn btn-sm btn-success" id="Approve" data_id="{{$v->noid_out}}" type="button"><i class="fa fa-check"></i> Approve</button> 
           <button type="button" class="btn btn-sm btn-danger" data_id="{{$v->noid_out}}" id="cancel" style="z-index: 9999;"  data-toggle="modal" data-target="#myModal"><i class="fa fa-times"></i> Cancel</button> 
           @endif
           @endif
           @endif
           @endif
        </div>
      </div>
    </div>
  </div>
</div>      
<script>
  time1.push('{{$time1[$k]}}');
  minute1.push('{{$minute[$k]}}');
</script>         
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
  console.log(minute1);
  var myInterval;
  function startTimer(duration, display,key) {
    var timer = duration, minutes, seconds;
   var myInterval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            //timer = duration;
            clearInterval(myInterval);
            //$("div[id=tombol_appr"+key+"]").hide();
            $("span[id=waktu"+key+"]").html("<span style='color:red;'><span id='time"+key+"'>00:00 </span>Menit</span>");
        }
    }, 1000);
}

time1.forEach(function(v,k){


if(minute1[k] > parseInt("{{$now}}")){
    var fiveMinutes = v;
    display = document.querySelector("#time"+k);
    startTimer(fiveMinutes, display,k);
}else{
  $("span[id=waktu"+k+"]").html("<span style='color:red;'><span id='time"+k+"'>00:00 </span>Menit</span>");
  //$("div[id=tombol_appr"+k+"]").hide();
}
});
  </script>
<script>

</script>    
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

  $("a[id=edit]").click(function(){
    eq = $("a[id=edit]").index(this);
    noid_out = $("a[id=edit]").eq(eq).attr("data_id");
    window.open("{{url('/sarpras/sarana/keluar-masuk/edit')}}"+noid_out,"_blank","width="+screen.width+",height="+screen.height+",top=50,left=200");
    return false;
  });
  var eventDates = {};
  <?php
    $dateEvent = Illuminate\Support\Facades\DB::table("vihicle.v_out_h")
                  ->join("user_login","user_login.nik","vihicle.v_out_h.nik")
                  ->where("user_login.department",$_SESSION['department'])
                  ->groupBy("tgl_out")->get();
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
    pdfURL = "{{url('/sarpras/sarana/keluar-masuk-print-out-')}}"+data_id;
    //alert(data_id);
    $.ajax({
      type:"POST",
      url:"{{url('/sarpras/sarana/approve')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id,pdfURL:pdfURL},
      success:function(res){
        if(res == "Approve Success!"){
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
      }else{
        new PNotify({
          title: 'Info',
          text: res,
          type: 'info',
          hide: true,
          styling: 'bootstrap3'
        });
      }

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
      url :"{{url('/sarpras/sarana/edit')}}",
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