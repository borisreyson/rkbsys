@extends('layout.master')
@section('title')
ABP-system | Master User
@endsection
@section('css')
@include('layout.css')
    <link href="{{asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
    <style>
      .disable, li[class=disable] a{
        background-color: #F24949;
        color: #f8f8f8;
      }
      .enable, li[class=enable] a{
        background-color:#5EBFAD;
        color: #f8f8f8;
      }
      #users{
        border:1px solid rgba(0,0,0,0.5);
        border-radius: 5px;
        height: 140px;
        width: 100%;
        padding: 5px;
        margin: 15px;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.25);
      }
      #users:hover{
        background-color: #fff;
        cursor: pointer;
        cursor: hand;
        color: #000;
      }
      table tr td {
        padding: 5px;
      }
      .melayang{
        position: absolute!important;
        top: 0;
        right: 0;
        margin-top: 20px;
        border-radius: 25px;
      }
      .btn-menus{
        border-radius: 25px;
        background-color: #F5BD42;
        color: #3E5902;
      }
      .btn-menus:hover,.btn-menus:active,.btn-menus:link,.btn-menus:visited{
        background-color: #f8f8f8;
        border:1px #333 solid;
      }
    </style>
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
<!-- page content -->

<div class="right_col" role="main">
 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Master<small>User</small></h2>
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
                    <br/>
<div class="row">
  <div class="col-md-6 col-xs-12">
    <a href="{{url('/form_user')}}" class="btn btn-default col-md-4 col-xs-12">Buat User Baru</a>
    </div>
    <div class=" col-md-6 col-xs-12">
    <div class="row">
      <form action="" class="col-md-offset-6 col-md-6 col-xs-12" method="GET">
        <div class="input-group">
          <input type="text" name="cari" class="form-control" placeholder="Search for...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
      </form>
    </div>  
  </div>
</div>
  		<br/>
<div class="row">
<div class="col-md-12 col-xs-12">
  <div class="row" style="font-weight: bolder;">
 <div class="col-lg-4 col-md-6 col-xs-12" >
  <div class="input-group input-daterange">
    <input type="text" class="form-control" value="2012-04-05">
    <div class="input-group-addon">to</div>
    <input type="text" class="form-control" value="2012-04-19">
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
<div class="modalKonten"></div>
</div>
  </div>
@endsection

@section('js')
<!-- Datatables -->
    <script src="{{asset('/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{asset('/vendors/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{asset('/vendors/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('/vendors/pdfmake/build/vfs_fonts.js')}}"></script>

    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script><script>
  $('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});
</script>
@include('layout.js')
<script>
  //tags input 
    
  $("a[id=editRule]").click(function(){
    eq      = $("a[id=editRule]").index(this);
    id_user = $("a[id=editRule]").eq(eq).attr("id_user");
    $.ajax({
      type:"POST",
      url :"{{url('/rule/user/edit')}}",
      data:{id_user:id_user,_token:"{{csrf_token()}}"},
      success:function(res){
        $("div[class=modalKonten]").html(res);
        
      }

    });
  });
 $(".myButtonDiss").click(function(){
      document.location= "{{url('rule/user')}}";
    });
  $("#datatables").dataTable();
          function init_DataTables() {
        
        if( typeof ($.fn.DataTable) === 'undefined'){ return; }
        console.log('init_DataTables');
        
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
          $("#datatable-buttons").DataTable({
          'order': [[ 2, 'desc' ]],
            responsive: true
          });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
          init: function() {
            handleDataTableButtons();
          }
          };
        }();

        TableManageButtons.init();
        
      };
      init_DataTables();
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
