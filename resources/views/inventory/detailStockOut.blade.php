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
    <a href="{{url('/inventory/stock')}}">Stock</a> <i class="fa fa-angle-right"></i>
    <a href="">Stock Out</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Stock Out Inventory</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
  <div class="col-lg-12 row">
  <span class="pull-left">
    <?php if(isset($_GET['cari'])){ ?>
  <a href="{{url('inventory/report/stock-'.$item.'-out')}}?cari=<?php echo $_GET['cari'];?>" target="_blank" class="btn btn-default" id="export" >Export To Excel</a>
  <?php }else{ ?>
  <a href="{{url('inventory/report/stock-'.$item.'-out')}}" target="_blank" class="btn btn-default" id="export" >Export To Excel</a>
  <?php } ?>
</span>

<form action="" method="get" class="col-lg-6">
    <div class="col-lg-10 input-group">
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Start</span>
          <?php if(isset($_GET['startDate'])){?>
          <input type="text" value="{{$_GET['startDate']}}" class="form-control" name="startDate" placeholder="Start Date" aria-describedby="basic-addon1">
        <?php }else{?>
          <input type="text" value="{{date('d F Y',strtotime('-1month'))}}" class="form-control" name="startDate" placeholder="Start Date" aria-describedby="basic-addon1">
        <?php } ?>
          <span class="input-group-addon" id="basic-addon1">End</span>
           <?php if(isset($_GET['endDate'])){?>
          <input type="text" value="{{$_GET['endDate']}}" class="form-control" name="endDate" placeholder="Start Date" aria-describedby="basic-addon1">
        <?php }else{?>
          <input type="text" value="{{date('d F Y')}}" class="form-control" name="endDate" placeholder="Start Date" aria-describedby="basic-addon1">
        <?php } ?>
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
    </div>
</form>
<div class="row col-lg-3 pull-right">
    <form method="get" action="" class="row col-lg-12 input-group pull-right">
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
<table class="table table-striped table-bordered">
  <div class="col-lg-12 row">
  </div>
  <thead>
    <tr class="bg-primary">
      <th>Kode Barang</th>
      <th>Part Name</th>
      <th>Part Number</th>
      <th>Stok Keluar</th>
      <th>Lokasi</th>
      <th>Penerima</th>
      <th>Jabatan</th>
      <th>Dept - Devisi</th>
      <th>Diterima Dari</th>
      <th>Jabatan</th>
      <th>Keterangan</th>
      <th>Time Log</th>

    </tr>
  </thead>
  <tbody>
    @if(count($det)>0)
    @foreach($det as $k => $v)
    @php
      $user_penerima = Illuminate\Support\Facades\DB::table("db_karyawan.data_karyawan")->where("nik",$v->user_reciever)->first();
    @endphp
    <tr>
      <td><a href="{{url('/inventory/stock')}}?cari={{$v->item}}" target="_blank">{{ucwords($v->item)}}</a></td>
      <td>{{$v->item_desc}}</td>
      <td>{{$v->part_number}}</td>
      <td>{{$v->stock_out}} {{$v->satuan}}</td>
      <td>{{ucwords($v->location)}}</td>
      @if(isset($user_penerima))
      <td>{{ucwords($user_penerima->nama)}}</td>
      @else
      <td>{{ucwords($v->user_reciever)}}</td>
      @endif
      <td>{{ucwords($v->jabatan)}}</td>
      <td>{{ucwords($v->department)}} - {{ucwords($v->sect)}}</td>
      <td>{{ucwords($v->diterima_dari)}}</td>
      <td>{{ucwords($v->jabatan_a)}}</td>
      <td>{{ucwords($v->remark)}}</td>
      <td>{{date("d F Y",strtotime($v->tglOut))}}</td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="12">
       <b>Total Record : {{count($det)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="12" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<div class="col-lg-12 text-center">
    {{$det->links()}}
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
  
    $(".myButtonDiss").click(function(){
      document.location= "{{url('inventory/stock-'.$item.'.out')}}";
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