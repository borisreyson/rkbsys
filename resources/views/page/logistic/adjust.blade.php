@extends('layout.master')
@section('title')
ABP-system | Adjust Stock
@endsection
@section('css')
 @include('layout.css')
<link href="{{asset('/vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
<style>

.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;

            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            }  
  tbody tr td{
    text-align: center;
  }
  .dropdown-menu{
    box-shadow: 1px 1px 5px 5px rgba(0,0,0,0.2);
  }
  .dropdown-menu .details{
    background-color: rgba(245,148,28,0.9);

    color: #fff;
  }
  .dropdown-menu .cancel{
    background-color: rgba(191,17,46,0.9);
    color: #fff;
  }
  .bottom_padd{
    padding-bottom: 15px;
  }
  .right_padd{
    margin-right: 10px!important;
  }
  .box-height{
    border: solid 0.1em #8CBFAA;
    margin-bottom: 1.5em;
  }
  .rkb_box{
    color: #000;
    font-weight: bold;
  }
  .rkb_box:hover,.rkb_box:active,.rkb_box:visited{
    opacity: 0.8;
    color: #000;
  }
@media only screen and (max-width: 600px) {
  .data_box{
    height: 350px!important;
  }
}
@media only screen and (min-width: 650px) {
  .data_box{
    height: 270px!important;
  }
}
</style>
@endsection
@section('content')
<?php
if(isset($_GET['submit'])){
  $search = $_GET['search'];
  if(isset($_GET['page'])){
    $page = $_GET['page'];
    header('Location: ?page='.$page.'&search='.$search);  
    exit;
  }else{
    header('Location: ?search='.$search);  
    exit;
  }
}
?>
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<div class="right_col" role="main">
 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Adjust Stock </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<div class="row">
  <div class="col-lg-12">
    <div class="form-horizontal">
      <div class="form-group">
        <label class="col-lg-3 control-label">Item</label>
        <div class="col-lg-6 input-group">
          <input type="text" name="cari" id="cari" class="form-control" placeholder="Cari">
          <span class="input-group-btn">
            <button class="btn btn-primary" id="appendTo"><i class="fa fa-plus"></i></button>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>                                      
<div class="row">
  <div class="col-lg-12">
    <div class="text-center">
      <h2>Adjust Item</h2>
    </div>
  </div>
    <div class="col-lg-12">
      <div class="form-horizontal">
        <div class="form-group">
          <label class="col-lg-offset-1 col-lg-3 control-label">Item : </label>
          <div class="col-lg-4">
            <input type="hidden" name="itemKey" class="form-control">
            <input type="hidden" name="stockTot" class="form-control">
            <p class="form-control-static"><b id="item"></b></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-offset-1 col-lg-3 control-label">Stock Item : </label>
          <div class="col-lg-4">
            <p class="form-control-static"><b id="stockItem"></b></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-offset-1 col-lg-3 control-label">Adjust : </label>
          <div class="col-lg-1">
            <input type="number" value="0" name="adjustItem" class="form-control" disabled="disabled" >
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-offset-1 col-lg-3 control-label">Stock Now : </label>
          <div class="col-lg-1">
            <input type="text" name="stockNow" class="form-control" disabled="disabled" >
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-offset-1 col-lg-3 control-label">Description </label>
          <div class="col-lg-3">
            <textarea class="form-control" name="desc" placeholder="Description" disabled="disabled"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-offset-4 col-lg-3">
            <button class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </div>
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


@include('layout.js')
    
<script type="text/javascript" src="{{asset('/DataTables/datatables.min.js')}}"></script>
    <script src="{{asset('/vendors/switchery/dist/switchery.min.js')}}"></script>
   <script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>


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

    <!-- jQuery autocomplete -->
    <script src="{{asset('/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js')}}"></script>
<script>
  var dataRecord;
  $("button[id=appendTo]").click(function(){
    cari = $("input[name=cari]").val();
    
    $("input[name=itemKey]").html(dataRecord.item);
    $("b[id=item]").html(dataRecord.value);
    $("b[id=stockItem]").html(dataRecord.stockTot);

    $("input[name=adjustItem]").attr("max",dataRecord.stockTot).removeAttr("disabled");
    $("textarea[name=desc]").removeAttr("disabled");
    $("input[name=stockTot]").val(dataRecord.stockTot);
  });
  
  $("input[name=adjustItem]").change(function(){
    stockTotA = $("input[name=stockTot]").val();
    adjustItemA = $("input[name=adjustItem]").val();

    NowStock = (parseInt(stockTotA)+parseInt(adjustItemA));
    $("input[name=stockNow]").val(NowStock);
  });

   function init_autocomplete() {      
      if( typeof ($.fn.autocomplete) === 'undefined'){ return; }
      // initialize autocomplete with custom appendTo
  
$("input[id=cari]").autocomplete({ 
        serviceUrl: "{{url('/get/item_data')}}",
          onSelect: function (suggestion) {
            dataRecord =suggestion;
          }
      });

      };

      init_autocomplete();

</script>
@endsection