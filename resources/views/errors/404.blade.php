@extends('layout.master')
@section('title')
Error Page 404
@endsection
@section('css')
 @include('layout.css')
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
<!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">404</h1>
              <h2>Sorry but we couldn't find this page</h2>
              <p>This page you are looking for does not exist <a href="#">Report this?</a>
              </p>
              <div class="mid_center">
                <h3>Search</h3>
                <form>
                  <div class="col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Search for...">
                      <span class="input-group-btn">
                              <button class="btn btn-default" type="button">Go!</button>
                          </span>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
    </div>
</div>
@endsection


@section('js')

@include('layout.js')
<script type="text/javascript" src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script src="{{asset('/vendors/switchery/dist/switchery.min.js')}}"></script>
   <script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>
@endsection