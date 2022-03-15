@extends('layout.master')
@section('title')
Token Expired
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
              <h1 class="error-number">Expired</h1>
              <h2>Maaf Token Reset Password Anda Kadaluarsa</h2>
              <p>Harap Mengajukan Ulang Lagi
              </p>
              <div class="mid_center">
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