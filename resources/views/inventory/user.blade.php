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
    <a href="{{url('/inventory/user/stock')}}">Stock</a>
    <br>
    <br>
  </div>
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Stock Inventory</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">          
  <div class="row">
    <div class="col-xs-12">
<!--      <button class="btn btn-info" id="StockIn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Stock</button>
      <button class="btn btn-info" id="StockOut" data-toggle="modal" data-target="#myModal"><i class="fa fa-table"></i> Stock Out</button>
   --> 
   <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button" aria-expanded="false">Export <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                      @if(isset($_GET['end_date']))
                      <li><a href="{{url('/inventory/report/stok-now')}}?end_date={{$_GET['end_date']}}">Stock</a>
                      </li>
                      @else
                      <li><a href="{{url('/inventory/report/stok-now')}}">Stock</a>
                      </li>
                      @endif
                      <li><a href="{{url('/inventory/report/stokformat-get')}}">Stock All Formated</a>
                      </li>
                      <li><a href="{{url('/inventory/report/stokin-get')}}">Export Stock In All</a>
                      </li>
                      <li><a href="{{url('/inventory/report/stokout-get')}}">Export Stock Out All</a>
                      </li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a>
                      </li>
                    </ul>
      </div>
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
    <tr class="bg-primary">
      <th>No Barang</th>
      <th>Part Name</th>
      <th>Part Number</th>
      @if(isset($_GET['end_date']))
      <th width="100px">Stok In <?php echo date("F",strtotime($_GET['end_date']));?></th>
      <th width="100px">Stok Out <?php echo date("F",strtotime($_GET['end_date']));?></th>
      @else
      <th width="100px">Stok In <?php echo date("F",strtotime(date('Y-m-d')));?></th>
      <th width="100px">Stok Out <?php echo date("F",strtotime(date('Y-m-d')));?></th>
      @endif
      <th>Stok In</th>
      <th>Stok Out</th>
      <th>Stok</th>
      <th>Umur Barang</th>
      <th>Last Update</th>
    </tr>
  </thead>
  <tbody>
    @if(count($stock)>0)
    @foreach($stock as $k => $v)
    <?php
if(isset($_GET['end_date']))
{
    $dateLAST = Illuminate\Support\Facades\DB::table("invin_detail")->whereRaw("item ='".$v->item."' and date_entry <='".date("Y-m-d",strtotime($_GET['end_date']))."'" )->orderBy("date_entry","desc")->first();

    $dateLASTout = Illuminate\Support\Facades\DB::table("invout_detail")->whereRaw("item ='".$v->item."' and date_entry <='".date("Y-m-d",strtotime($_GET['end_date']))."'" )->orderBy("date_entry","desc")->first();

$lastMonth = date("Y-m-d",strtotime($_GET['end_date']));
  if(isset($dateLAST)!=null){
   $stokIN = Illuminate\Support\Facades\DB::table("invin_detail")->whereRaw("item ='".$v->item."' and date_entry <='".$dateLAST->date_entry."'")->sum("stock_in");
   $stokOUT = Illuminate\Support\Facades\DB::table("invout_detail")->whereRaw("item ='".$v->item."' and date_entry <='".$dateLAST->date_entry."'")->sum("stock_out");
  }else{
   $stokIN = 0;
   $stokOUT = 0;    
  }
}
else
{
    $dateLAST = Illuminate\Support\Facades\DB::table("invin_detail")->whereRaw("item ='".$v->item."'" )->orderBy("date_entry","desc")->first();

    $dateLASTout = Illuminate\Support\Facades\DB::table("invout_detail")->whereRaw("item ='".$v->item."'" )->orderBy("date_entry","desc")->first();

$lastMonth = date("Y-m-d");
 $stokIN = Illuminate\Support\Facades\DB::table("invin_detail")->where("item",$v->item)->sum("stock_in");
 $stokOUT = Illuminate\Support\Facades\DB::table("invout_detail")->where("item",$v->item)->sum("stock_out");
}


$lastDM = date('Y-m-t', strtotime("$lastMonth"));
$FirstDM = date('Y-m-01', strtotime("$lastMonth"));

 $stokIN_L = Illuminate\Support\Facades\DB::table("invin_detail")->whereRaw("item ='".$v->item."' and (date_entry between '".$FirstDM."' and '".$lastDM."')")->sum("stock_in");
 $stokOUT_L = Illuminate\Support\Facades\DB::table("invout_detail")->whereRaw("item='".$v->item."' and (date_entry between '".$FirstDM."' and '".$lastDM."')")->sum("stock_out");

    ?>
    <tr>
      <td><button class="btn btn-xs btn-info pull-right"  name="detailItemStock" data-id="{{bin2hex($v->item)}}" data-toggle="modal" data-target="#myModal">Detail</button> {{strtoupper($v->item)}}</td>
      <td>{{strtoupper($v->item_desc)}}</td>
      <td>{{strtoupper($v->part_number)}} </td>

      <td>
       {{$stokIN_L}} {{$v->satuan}}</td>
      <td>
       {{$stokOUT_L}} {{$v->satuan}}</td>
      <td>{{$stokIN}} {{$v->satuan}}  
        <a href="{{url('/inventory/stock-'.bin2hex($v->item).'.in')}}" target="_blank" class="btn btn-warning btn-xs pull-right">Detail</a>
    </td>
      <td>
        {{$stokOUT}} {{$v->satuan}}        
      <a href="{{url('/inventory/stock-'.bin2hex($v->item).'.out')}}" target="_blank" class="btn btn-warning btn-xs pull-right">Detail</a>
    </td>
@php
$stok = $stokIN-$stokOUT;
@endphp
  @if($stok<=0)
      <td style="background-color: red;color:white;">
<?php
 
 echo $stok." ".$v->satuan;
?>
       </td>
@else
      <td>
<?php
 echo $stok." ".$v->satuan;
?>
       </td>

@endif
      <td>
@php
if(isset($dateLAST)!=null){
if(strtotime($dateLAST->date_entry) > strtotime($dateLASTout->date_entry)){
   $date = new DateTime($dateLAST->date_entry);
}else{
  $date = new DateTime($dateLASTout->date_entry);  
}
 $now = new DateTime();
 $interval = $now->diff($date);
 if($interval->y>0) 
 {
  echo $interval->y." Tahun ,";
 }
 if($interval->m>0){
 echo $interval->m." Bulan ,";
  }
 echo $interval->d." Hari "; 
}else{
  echo "-";
}
@endphp
</td>
      <td>
@if(isset($dateLAST)!=null)
  @if(strtotime($dateLAST->date_entry) > strtotime($dateLASTout->date_entry))
    {{date("d F Y",strtotime($dateLAST->date_entry))}}
  @else
    {{date("d F Y",strtotime($dateLASTout->date_entry))}}
  @endif
@else
  -
@endif
      </td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="3">
       <b>Total Record : {{count($stock)}}</b>
      </td>
      <td><a href="{{url('/inventory/stockAll.in')}}" target="_blank" class="btn btn-warning btn-xs pull-right">Detail Stock In</a></td>
      <td><a href="{{url('/check/stock/out')}}" target="_blank" class="btn btn-warning btn-xs pull-right">Detail Stock Out</a></td>
      <td colspan="3"></td>
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
  {{$stock->appends(['close_rkb' => 'all' ])->links()}}
@elseif(isset($_GET['cari']))
  {{$stock->appends(['cari' => $_GET['cari'] ])->links()}}
@elseif(isset($_GET['end_date']))
@if(isset($_GET['cari']))
  {{$stock->appends(['cari' => $_GET['cari'],'end_date' => $_GET['end_date'] ])->links()}}
@else
  {{$stock->appends(['end_date' => $_GET['end_date'] ])->links()}}
@endif
@else
  {{$stock->links()}}
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
      document.location= "{{url('inventory/user/stock')}}";
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