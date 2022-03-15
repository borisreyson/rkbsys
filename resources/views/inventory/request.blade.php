@extends('layout.master')
@section('title')
ABP-system | Request Master Item
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
  position: relative!important;
  left: 0!important;
  right: 0!important;
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
    <a href="{{url('/masteritem/request/detail')}}">Master Request</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Request Master Item</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">          
  <div class="row">
    <div class="col-xs-12">
      @if(in_array('user inventory',$arrRULE))
      <a href="{{url('/masteritem/request')}}" class="btn btn-info" id="StockIn" ><i class="fa fa-plus"></i> New Request Master Item</a>
      @endif
<div class=" col-lg-3 pull-right">
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
    <tr class="bg-primary ">
      <th class="text-center">Part Name</th>
      <th class="text-center">Part Number</th>
      <th class="text-center">Satuan</th>
      <th class="text-center">Minimum</th>
      <th class="text-center">Category</th>
      @if(in_array('admin inventory',$arrRULE))
      <th class="text-center">User Request</th>
      @endif
      <th class="text-center" style="width: 200px;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @if(isset($data))
    @foreach($data as $k => $v)
    <?php
         $cek = Illuminate\Support\Facades\DB::table("invmaster_item")
         ->join("inv_cat_item","inv_cat_item.CodeCat","invmaster_item.item_cat")
         ->whereRaw("item_desc ='".$v->part_name."' and part_number='".$v->part_number."'")->first();
    ?>
    <tr>
      <td>
        {{$v->part_name}}
         @if(in_array('admin inventory',$arrRULE))
         @if(isset($cek))
         <hr>
         <font color="green">{{$cek->item_desc}}</font>
         @endif
         @endif
      </td>
      <td>{{$v->part_number}}
      @if(in_array('admin inventory',$arrRULE))
         @if(isset($cek))
         <hr>
         <font color="green">{{$cek->part_number}}</font>
         @endif
         @endif
       </td>
      <td>{{$v->satuan}}
      @if(in_array('admin inventory',$arrRULE))
         @if(isset($cek))
         <hr>
         <font color="green">{{$cek->satuan}}</font>
         @endif
         @endif
       </td>
      <td>{{$v->minimum}}
      @if(in_array('admin inventory',$arrRULE))
         @if(isset($cek))
         <hr>
         <font color="green">{{$cek->minimum}}</font>
         @endif
         @endif
       </td>
      <td>{{$v->DeskCat}}
      @if(in_array('admin inventory',$arrRULE))
         @if(isset($cek))
         <hr>
         <font color="green">{{$cek->DeskCat}}</font>
         @endif
         @endif
       </td> 
       @if(in_array('admin inventory',$arrRULE))
      <td class="text-center" style="vertical-align: middle;font-weight: bolder;">
     
        <?php
          $userReq = Illuminate\Support\Facades\DB::table('user_login')->where("username",$v->user_request)->first();
        ?>
         <font color="green">@if(isset($userReq)) {{$userReq ->nama_lengkap}} @endif</font>
        
       </td> 
       @endif
      <td class="text-center">
        @if(in_array('user inventory',$arrRULE))
        @if(!isset($cek))
        <a href="#" id="editRequest" class="btn btn-warning btn-xs" data-id="{{bin2hex($v->id)}}"  data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></a>
        @else
        <label class="btn btn-xs btn-success">Master Item Has Created!</label>
        @endif
        @endif

        @if(in_array('admin inventory',$arrRULE))
        @if(!isset($cek))
          <a href="#" id="register" class="btn btn-primary btn-xs" data-id="{{bin2hex($v->id)}}"  data-toggle="modal" data-target="#myModal"><i class="fa fa-database"></i> Create To Master Item</a>
        @else
        <hr>

        <font color="green"><i class="fa fa-arrow-left"></i> Permintaan Udah Di register di Master Item</font>
        @endif
        @endif

      </td>
    </tr>
    @endforeach

    <tr class="info">
      <td colspan="7">
       <b>Total Record : {{count($data)}}</b>
      </td>
    </tr>
   @else
    <tr>
      <td colspan="7" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<div class="col-lg-12 text-center">
   {{$data->links()}}
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
  <div class="modal-dialog modal-md">
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
      document.location= "{{url('/masteritem/request/detail')}}";
    });
    $(document).on("click","a[id=editRequest]",function(){
      eq = $("a[id=editRequest]").index(this);
      data_id = $("a[id=editRequest]").eq(eq).attr("data-id");
      
      $.ajax({
        type:"GET",
        url:"{{url('/masteritem/request/item')}}",
        data:{data_id:data_id},
        success:function(res){
          $("div[id=konten_modal]").html(res);
          $("select").selectpicker();
        }

      });
    });
        $(document).on("click","a[id=register]",function(){
      eq = $("a[id=register]").index(this);
      data_id = $("a[id=register]").eq(eq).attr("data-id");
      
      $.ajax({
        type:"GET",
        url:"{{url('/masteritem/request/create')}}",
        data:{data_id:data_id},
        success:function(res){
          $("div[id=konten_modal]").html(res);
          $("select").selectpicker();
        }

      });
    });
  //NEW LOCATION
  $(document).on("click","button[id=StockIn]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/stok/masuk')}}",
      beforeSend:function(){
        $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse\"");
        $(".modal-dialog").removeClass("modal-md").addClass("modal-xl");
      },
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });
  $(document).on("click","button[id=StockOut]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/inventory/stock/out/new')}}",
      beforeSend:function() {
        $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse\"");
        $(".modal-dialog").removeClass("modal-md").addClass("modal-lg");
      },
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });


  $("button[name=detailItemStock]").click(function(){
    eq =  $("button[name=detailItemStock]").index(this);
    idInvSys = $("button[name=detailItemStock]").eq(eq).attr("data-id");
    $.ajax({
      type:"GET",
      url :"/inventory/modal/item/detail",
      data:{idInvSys:idInvSys},
      beforeSend:function(){
        $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse\"");
        $(".modal-dialog").removeClass("modal-lg").addClass("modal-md");
      },
      success:function(res){
        $("div[id=konten_modal]").html(res);
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