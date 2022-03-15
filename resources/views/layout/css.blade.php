
    <!-- Jequery UI -->
    <link href="{{asset('jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{asset('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{asset('vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.min.css')}}" rel="stylesheet">

    <!-- PNotify -->
    <link href="{{asset('/vendors/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    @yield('css')
    <style>
        .headings{
            background: linear-gradient(#46A48C, #408E76), #5390A5!important;
            color: #f8f8f8!important;

        }
/*
 .dropright{
    position: absolute;
    z-index: 1px;
  }

 .dropright .dropdown-menu{
    position: absolute!important;
    z-index: 2px!important;
  }
  */
  .dropdown-menu .close_rkb{
    background-color: rgba(8,163,47,0.9);
    color: #fff;
  }

  .box-height{
    box-shadow: 1px 1px 4px 4px rgba(0,0,0,0.25);
    -webkit-transition: box-shadow .2s;
  }
  .box-height:hover{
    box-shadow: 2px 1px 5px 5px rgba(0,0,0,0.30);
  }

  .tile-stats{
    box-shadow: 1px 1px 4px 4px rgba(0,0,0,0.25);

  }

.garis-link{
    border-radius: 5px;
    box-shadow: 1px 1px 4px 4px rgba(0,0,0,0.08);
    padding: 1px;

  }
  .garis-link h4{
    padding-left: 25px;
  }
  div#page .pagination{
    border-radius: 5px;
    box-shadow: 1px 1px 4px 4px rgba(0,0,0,0.3);
    padding: 1px;
  }
  .box-height{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
  }

  #details{
    width: 100%!important;
  }

    </style>
