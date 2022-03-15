@extends('layout.master')
@section('title')
ABP-system | Stock
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

<!-- page content -->

<div class="right_col" role="main">
  <div class="col-lg-12">
    <br>
    <a href="{{url('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    <a href="{{url('/inventory/stock')}}">Stock</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Stock Inventory Out</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
  <div class="col-lg-12 row">
<form action="" method="get" class="col-lg-6">
    <div class="col-lg-10 input-group">
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Start</span>
          <?php if(isset($_GET['startDate'])){?>
          <input style="background-color: white;" type="text" value="{{$_GET['startDate']}}" class="form-control" name="startDate" placeholder="Start Date" aria-describedby="basic-addon1" readonly>
        <?php }else{?>
          <input style="background-color: white;" type="text" value="{{date('d F Y',strtotime('-1month'))}}" class="form-control" name="startDate" placeholder="Start Date" aria-describedby="basic-addon1" readonly>
        <?php } ?>
          <span class="input-group-addon" id="basic-addon1">End</span>
           <?php if(isset($_GET['endDate'])){?>
          <input style="background-color: white;" type="text" value="{{$_GET['endDate']}}" class="form-control" name="endDate" placeholder="Start Date" aria-describedby="basic-addon1" readonly>
        <?php }else{?>
          <input style="background-color: white;" type="text" value="{{date('d F Y')}}" class="form-control" name="endDate" placeholder="Start Date" aria-describedby="basic-addon1" readonly>
        <?php } ?>
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
    </div>
</form>
    <div class="row col-lg-3  pull-right">
    <form method="get" action="" class="row input-group">
    <span>
    <input type="text" name="cari" value="<?php if(isset($_GET['cari'])){ echo $_GET['cari']; } ?>" placeholder="Cari" required class="form-control">
    <?php if(isset($_GET['cari'])){ ?>
    <button class="myButtonDiss" type="button">&times;</button>
  <?php } ?>
    </span>
    <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
  </form>
    </div>
  </div>
  <div class="row">
<div class="col-lg-12 row">
</div>
</div>
<table class="table table-striped">
  <thead>
    <tr class="bg-primary">
      <th width="150px">No</th>
      <th>User Reciever</th>
      <th>From</th>
      <th>Date Stock Out</th>
      <th>List</th>
    </tr>
  </thead>
  <tbody>
    @if(count($cek)>0)
    @foreach($cek as $k => $v)
    <?php
      $user = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")
                ->leftJoin("department","department.id_dept","db_karyawan.data_karyawan.departemen")
                ->where("nik",$v->user_reciever)
                ->first();

    ?>
    <tr>
      <td><a href="{{url('/check/stock/out-'.bin2hex($v->noid_out))}}" target="_blank"> {{$v->noid_out}}</a> <a href="{{url('/check/stock/out-'.bin2hex($v->noid_out))}}" target="_blank" class="btn btn-xs btn-warning pull-right">Print</a>
      </td>
      <td>{{$user->nama or "-"}} 
        <br>
        {{$user->dept or "-"}} - {{$v->section or "-"}}
        <br>
        ({{ucwords($v->jabatan)}})        
         </td>
      <td>{{$v->diterima_dari or "-"}} ({{$v->jabatan_a or "-"}})</td>
      <td>{{date("d F Y", strtotime($v->tglOut))}}
      </td>
      <td>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Kode Barang</th>
              <th>Part Name</th>
              <th>Part Number</th>
              <th>Quantity</th>
              <th>Lokasi</th>
              <th>Remark</th>
            </tr>
          </thead>
          <tbody>
<?php $detail = Illuminate\Support\Facades\DB::table("invout_detail")
                ->where("noid_out",$v->noid_out)
                ->join('invmaster_item',"invmaster_item.item","invout_detail.item")
                ->get();
      foreach($detail as $kD => $vD){
            ?>
            <tr>
              <td>{{ucwords($vD->item)}}</td>
              <td>{{ucwords($vD->item_desc)}}</td>
              <td>{{ucwords($vD->part_number)}}</td>
              <td>{{$vD->stock_out}} {{ucwords($vD->satuan)}}</td>
              <td>{{$vD->code_loc}}</td>
              <td>{{$vD->remark}}</td>
            </tr>
<?php } ?>
          </tbody>
        </table>
      </td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="8">
       <b>Total Record : {{count($cek)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="8" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<div class="col-lg-12 text-center">
  @if(isset($_GET['category']))
    {{$cek->appends(['close_rkb' => 'all' ])->links()}}
  @else
    {{$cek->links()}}
  @endif

    
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
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
<script>
  
    $("select").selectpicker();
    $("input[name=startDate]").datepicker({ dateFormat: 'dd MM yy' });
    $("input[name=endDate]").datepicker({ dateFormat: 'dd MM yy' });

    $(".myButtonDiss").click(function(){
      document.location= "{{url('/check/stock/out')}}";
    });
    
  //NEW LOCATION
  $(document).on("click","button[id=StockIn]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/stock/new')}}",
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });
  $(document).on("click","button[id=StockOut]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/stock/out/new')}}",
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });



  //EDIT LOCATION
  $(document).on("click","button[id=editSuplier]",function(){
    eq = $("button[id=editSuplier]").index(this);
    data_id = $("button[id=editSuplier]").eq(eq).attr("data-id");
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/suplier/edit')}}",
      data:{data_id:data_id},
      success:function(result){
        $("div[id=konten_modal]").html(result);
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