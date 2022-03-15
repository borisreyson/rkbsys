@extends('layout.master')
@section('title')
ABP-system | Absen Abp
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;
  overflow-y: auto;
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
.border{
  border:1px solid #333;
  margin: 2px!important;
}
.label-success{
  color: white!important;
}
.label-success:hover{
  color: black!important;
}
.listPerson .idDel{
  position: absolute!important;
  top: 10px!important;
  right:15px !important;
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
                  <h2>Absen</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">

<div class="col-lg-12 row">

  <div class="col-lg-6">

    <form action="" method="get" class="form-horizontal col-lg-10 row">
    <div class="form-group">
    <label class="control-label col-lg-3">Nik / Nama</label>
    <div class="col-lg-6">
      <select name="nik" id="nik"  class="form-control" required="required" data-live-search="true">
        <option value="">--Pilih--</option>
          @if(isset($_GET['nik']))
          <option value="{{$_GET['nik']}}" selected="selected">Absen Error</option>  
          @else
          <option value="error">Absen Error</option> 
          @endif
      </select>     
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-lg-3">Dari</label>
    <div class="col-lg-3">
      @if(isset($_GET['dari']))
      <input type="text" name="dari" id="dari" class="form-control dateRange" required="required" value="{{date('01 F Y',strtotime($_GET['dari']))}}"  />
      @else
      <input type="text" name="dari" id="dari" class="form-control dateRange" required="required" value="{{date('01 F Y')}}"  />
      @endif
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-lg-3">Sampai</label>
    <div class="col-lg-3">
      @if(isset($_GET['sampai']))
      <input type="text" name="sampai" id="sampai" class="form-control dateRange" required="required" value="{{date('t F Y',strtotime($_GET['sampai']))}}" />
      @else
      <input type="text" name="sampai" id="sampai" class="form-control dateRange" required="required" value="{{date('t F Y')}}" />
      @endif
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-lg-3">Dari</label>
    <div class="col-lg-3">
      <select name="status" id="status"  class="form-control" required="required" data-live-search="true">
          @if(isset($_GET['status']))
          @if(($_GET['status'])==="masuk")
          <option value="all">--All--</option>
          <option value="masuk" selected="selected">Masuk</option>
          <option value="pulang" >Pulang</option>  
          @elseif(($_GET['status'])==="pulang")
          <option value="all">--All--</option>
          <option value="masuk">Masuk</option>
          <option value="pulang" selected="selected">Pulang</option>  
          @else          
          <option value="all" selected="selected">--All--</option>
          <option value="masuk">Masuk</option>   
          <option value="pulang">Pulang</option>    
          @endif
          @else
          <option value="all" selected="selected">--All--</option>
          <option value="masuk">Masuk</option>   
          <option value="pulang">Pulang</option>    

          @endif
      </select>  
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
      <button class="btn btn-primary" id="kirim" type="submit">Kirim</button>   
      <a class="btn btn-danger" id="reset" href="{{url($_SERVER['REDIRECT_URL'])}}">Reset</a>   
    </div>
  </div>
    </form>
  </div>
  
  <div class="col-lg-12">
    <hr>
    <p><b>Jumlah : {{count($dataAbsen)}}</b> </p>
    <hr>
  </div>
  <div class="col-lg-12">
    @if(count($dataAbsen)>0)
    <div class="col-lg-12">
      <a href="{{url('/absen/user/error/export?').$_SERVER['QUERY_STRING'].'&dept='.$dept}}" class="btn btn-primary">Export</a>
    </div>
      @foreach($dataAbsen as $k => $v)
      <?php
        $nik= ($v->nik==null)?"NIK":$v->nik;
      ?>
    <div class="col-lg-6 listPerson">
      <div class="border row">
      <div class="col-lg-12 row">
        <div class="col-lg-3 row">
          <a href="{{url('/face_id').'/'.$nik.'/'.$v->gambar}}" target="_blank" title="Click to Zoom"><img class="thumbnail" style="margin: 0px!important;" src="{{asset(url('/face_id').'/'.$nik.'/'.$v->gambar)}}"></a>
        </div>
        <div class="col-lg-8" style="margin-left: 30px!important;padding: 10px;">
          <div class="col-lg-12 ">
            <div class="col-lg-4">Tanggal</div>
            <div class="col-lg-8">{{date("d F Y",strtotime($v->tanggal))}}</div>
          </div>
          <div class="col-lg-12">
            <div class="col-lg-4">Nik</div>
            <div class="col-lg-8">{{$v->nik}}</div>
          </div>
          <div class="col-lg-12">
            <div class="col-lg-4">Nama Lengkap</div>
            <div class="col-lg-8">{{$v->nama}}</div>
          </div>
          <div class="col-lg-12">
            <div class="col-lg-4">Jam Cek log</div>
            <div class="col-lg-8">{{$v->jam}}</div>
          </div>
          <div class="col-lg-12">
            <div class="col-lg-4">Status</div>
            <div class="col-lg-8">
              @if($v->status=="Masuk")
              <label class="label label-success">
              {{$v->status}}
            </label>
            @elseif($v->status=="Pulang")
            <label class="label label-danger">
              {{$v->status}}
            </label>
            @endif
          </div>
          </div>
        </div>
      </div>
      </div>
      @if($v->flag==0)
      <div class="idDel">
              <button name="hapus" nik="{{$v->nik}}" tgl="{{strtotime($v->tanggal)}}" status="{{$v->status}}" class="btn btn-xs btn-danger" ><i class="fa fa-times"></i></button>
            </div>
            @endif
    </div>

      @endforeach
      @else
  <div class="col-lg-12"> No Data!</div>
      @endif
  <hr>
  </div>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
  @if(isset($_GET['nik']))
  {{$dataAbsen->appends([
    "nik"=>$_GET['nik'],
    "dari"=>$_GET['dari'],
    "sampai"=>$_GET['sampai'],
    "status"=>$_GET['status']
    ])->links()}}
  @else
  {{$dataAbsen->links()}}
  @endif
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
      $("button[name=hapus]").click(function(){
        eq = $("button[name=hapus]").index(this);
        nik = $("button[name=hapus]").eq(eq).attr("nik");
        tgl = $("button[name=hapus]").eq(eq).attr("tgl");
        status = $("button[name=hapus]").eq(eq).attr("status");
        $.ajax({
          type:"POST",
          url:"{{url('/absen/rekap/karyawan/validasi')}}",
          data:{_token:"{{csrf_token()}}",nik:nik,tgl:tgl,status:status},
          success:function(res){
            if(res=="berhasil"){
              window.location.reload();
            }else{
              alert("Gagal Update!");  
            }
          }

        });
      });
      $("select").selectpicker();

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