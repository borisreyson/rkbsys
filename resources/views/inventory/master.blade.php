<?php
$arrRULE = [];
  if(isset($getUser)){
    $arrRULE = explode(',',$getUser->rule);    
  }else{
    ?>
<script>
  window.location="{{url('/logout')}}";
</script>
    <?php } ?>
@php
function rupiah($angka){
  
  $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
  return $hasil_rupiah;
 
}

@endphp

@extends('layout.master')
@section('title')
ABP-system | Master
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
                  <h2>Inventory Master Item</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                   <div class="col-lg-12 row">
<div class="col-xs-6">
    <button class="btn btn-primary" id="new_master" data-toggle="modal" data-target="#myModal">New Master Item</button>
    <a href="{{url('/export/master/item')}}" class="btn btn-primary" id="export">Export To Excel</a>
</div>
<div class="col-xs-6">
  
<div class=" col-lg-6 pull-right">
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
  </div>
<table class="table table-striped table-bordered">
 
  <thead>
    <tr class="bg-primary">
      <th class="text-center">#</th>
      <th class="text-center">Kode Barang</th>
      <th class="text-center">Part Name</th>
      <th class="text-center">Part Number</th>
      <th class="text-center">Satuan</th>
      <th class="text-center">Label</th>
      <th class="text-center">Kategori</th>
      <th class="text-center">Stok Minimal</th>
      @if(in_array('purchasing',$arrRULE))
      <th>Harga</th>
      @endif
      <th class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @if(count($master)>0)
    @foreach($master as $k => $v)
    <tr>
      <td>
        @if($v->statusMaster=='1')
          <i style="color: green;" class="fa fa-check"></i>
        @else
          <i class="fa fa-times" style="color: red;"></i>
        @endif
      </td>
      <td>
        @if($v->statusMaster=='1')
        <font color="green"><b>{{$v->item}}</b></font>
        @else
        <font color="red"><b>{{$v->item}}</b></font>
        @endif</td>
      <td>{{$v->item_desc}}</td>
      <td>{{$v->part_number}}</td>
      <td>{{$v->satuan}}</td>
      <td>{{strtoupper($v->category)}} ( {{ucwords($v->desc_category)}} )</td>
      <td>
        {{strtoupper($v->DeskCat)}}
      </td>
      <td>
        {{strtoupper($v->minimum)}}
      </td>
      @if(in_array('purchasing',$arrRULE))
      <td class="text-center">
        @if($v->harga==NULL)
        -
        @else
        {{rupiah($v->harga)}}
        @endif
      </td>
      @endif
      <td align="center">
        @if(in_array('purchasing',$arrRULE))
        <button class="btn btn-xs btn-default" data-id="{{bin2hex($v->item)}}" id="updateHarga" data-toggle="modal" data-target="#myModal1">Update Harga</button>
        @endif
        <button class="btn btn-xs btn-info" data-id="{{($v->item)}}" id="qrcode" data-toggle="modal" data-target="#myModal1">QR Code</button>
        @if($v->statusMaster=='0')
        <a href="{{url('/inventory/master/status-'.bin2hex($v->item).'-enable')}}" class="btn btn-xs btn-success">Enable</a>
        @endif
        @if($v->statusMaster=='1')
        <a href="{{url('/inventory/master/status-'.bin2hex($v->item).'-disable')}}" class="btn btn-xs btn-danger">Disable</a>
        @endif
      
        <button type="button" class="btn btn-xs btn-warning" data-id="{{bin2hex($v->item)}}" id="editMaster" data-toggle="modal" data-target="#myModal">Edit</button>
        <a href="{{url('/inventory/master/del-'.bin2hex($v->item))}}" class="btn btn-xs btn-danger" id="delMaster">Delete</a>
      </td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="10">
       <b>Total Record : {{count($master)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="10" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<div class="col-lg-12 text-center">
    {{$master->links()}}
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
  <div class="modal-dialog modal-lg">
<div id="konten_modal"></div>
  </div>
</div>

<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
<div id="konten_modal1"></div>
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
<script type="text/javascript" src="{{asset('/js/jquery.qrcode.min.js')}}"></script>
<script>  
  //NEW LOCATION
  $(document).on("click","button[id=new_master]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/master/new')}}",
      beforeSend:function(){
        $("div[class=modal-dialog]").removeClass("modal-xs").addClass("modal-lg");
      },
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });

  //EDIT LOCATION
  $(document).on("click","button[id=editMaster]",function(){
    eq = $("button[id=editMaster]").index(this);
    data_id = $("button[id=editMaster]").eq(eq).attr("data-id");
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/master/edit')}}",
      data:{data_id:data_id},
      beforeSend:function(){        
        $("div[class=modal-dialog]").removeClass("modal-xs").addClass("modal-lg");
      },
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });
  //BARCODE
  $(document).on("click","button[id=qrcode]",function(){
    eq = $("button[id=qrcode]").index(this);
    data_id = $("button[id=qrcode]").eq(eq).attr("data-id");
    $.ajax({
      type:"POST",
      url:"{{url('/inventory/view/QRcode')}}",
      data:{data_id:data_id,_token:"{{csrf_token()}}"},
      beforeSend:function(){        
        $("div[class=modal-dialog]").removeClass("modal-lg").addClass("modal-xs");
      },
      success:function(result){
        $("div[id=konten_modal1]").html(result);
      }
    });
  });
$(".myButtonDiss").click(function(){
      document.location= "{{url('inventory/master')}}";
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