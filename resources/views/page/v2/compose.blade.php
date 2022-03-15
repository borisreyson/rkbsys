@extends('layout.master')
@section('title')
ABP-system | Compose
@endsection
@section('css')  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

 @include('layout.css')
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
                    <h2>Compose</h2>
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
<form id="demo-form2" action="/form_dept" data-parsley-validate class="form-horizontal form-label-left" method="post">
                      {{csrf_field()}}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_from">To <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="text" id="user_from" name="user_from" required="required" class="form-control col-md-7 col-xs-12" value="{{hex2bin($user_to)}}" placeholder="To">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_rkb">No RKB <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="text" id="no_rkb" required="required" readonly="readonly" name="no_rkb" class="form-control col-md-7 col-xs-12" value="{{hex2bin($no_rkb)}}" placeholder="No RKB">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_rkb">No RKB <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="text" id="user_to" name="user_to" class="form-control col-md-7 col-xs-12"  placeholder="No RKB">
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                      @if(!isset($edit_dept))
                          <button type="submit" class="btn btn-success">Submit</button>
                          <button class="btn btn-primary" type="reset">Reset</button>
                          @else
                          <button type="submit" class="btn btn-success">Update</button>
                          <a href="{{url('/form_dept')}}" class="btn btn-default">New Entry</a>
                      @endif
                          <a href="javascript:history.back()" class="btn btn-danger">Cancel</a>
                        </div>
                      </div>

                    </form>

            </div>
        </div>
    </div>
</div>
</div>



@include('layout.footer')

</div>
</div>
@endsection

@section('js')
<!-- Datatables -->

@include('layout.js')



<script>
  $("#user_to").autocomplete({
          source: function( request, response ) {
                  $.ajax( {
                    url: "{{url('/get/username')}}",
                    dataType: "json",
                    data: {
                      term: request.term
                    },
                    success: function( data ) {
                      response( data );
                    }
                  } );
                },
          minLength: 2,
          select: function( event, ui ) {
            console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
          }
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