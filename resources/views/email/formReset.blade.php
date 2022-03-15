@extends('layout.master')
@section('title')
Reset Password
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
              <h3 class="error-number">Reset Password</h3>
            </div>
            <div class="col-md-12 ">
             <form class="form-horizontal" method="post" action="">
              {{csrf_field()}}
              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Username : </label>
                <div class="col-sm-10">
                  <p class="form-control-static" >{{$user->username}}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email : </label>
                <div class="col-sm-10">
                  <p class="form-control-static" >{{$user->email}}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Nama : </label>
                <div class="col-sm-10">
                  <p class="form-control-static" >{{$user->nama_lengkap}}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="newPwd">Password : </label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="newPwd" name="newPwd" placeholder="Enter password" required="required">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="rePwd">Retype Password : </label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="rePwd" name="rePwd" placeholder="Retype password" required="required">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default">Submit</button>
                </div>
              </div>
            </form> 
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
    window.opener.location.reload();
    setTimeout(function(){
      window.close();
    },2000);
    
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