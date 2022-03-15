@extends('layout.master')
@section('title')
ABP-system | Master Form User
@endsection
@section('css')
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
                    <h2>Form<small>Master User</small></h2>
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
<form id="demo-form2" action="/form_user/{{bin2hex($password_user->username)}}.password" data-parsley-validate class="form-horizontal form-label-left" method="post">
                      {{csrf_field()}}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="text" id="username" required="required" name="username" class="form-control col-md-7 col-xs-12" value="{{$password_user->username or ''}}" disabled>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section">Nama Lengkap <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="text" id="last-nama_lengkap" name="nama_lengkap" required="required" class="form-control col-md-7 col-xs-12" value="{{$password_user->nama_lengkap or ''}}" disabled>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dept">Department <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                         <select class="form-control col-md-7 col-xs-12" id="dept" name="dept" required disabled>
                            <option value="">-- Pilih --</option>
                            @foreach($department as $v)
                            @if(isset($password_user))
                            @if($password_user->department == $v->id_dept)
                            <option value="{{$v->id_dept}}" selected="selected">{{$v->dept}}</option>
                            @else
                            <option value="{{$v->id_dept}}">{{$v->dept}}</option>
                            @endif     
                            @else                            
                            <option value="{{$v->id_dept}}">{{$v->dept}}</option>
                            @endif                            
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section">Section <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                        <select class="form-control col-md-7 col-xs-12" id="section" name="section" required disabled>
                            <option value="">-- Pilih --</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">New Password <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="password" id="password" name="password" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="retype_password">Retype New Password <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <input type="password" id="retype_password" name="retype_password" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">Update</button>
                          @if($_SESSION['section']=="IT")
                          <a href="{{url('/user')}}" class="btn btn-danger">Cancel</a>
                          @else
                          <a href="{{url('/')}}" class="btn btn-danger">Cancel</a>
                          @endif
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

@include('layout.js')

@if(isset($password_user))
<script>
  $(document).ready(function(){
    section("{{urlencode($password_user->department)}}","{{urlencode($password_user->section)}}");
  });
</script>
@endif

<script>

  $("select[name=dept]").change(function(){
   dept =  $("select[name=dept]").val();
   section(encodeURI(dept),"");
    return false;
 });
  function section(dept,selected) {
   if(dept==""){
      $("select[name=section]").attr("disabled","disabled");
    }else{
      $.ajax({
        type:"GET",
        url:"/section-from-dept",
        data:{dept:dept,selected:selected},
        beforeSend:function(){
          $("select[id=section]").html("<option value=\"\">-- Pilih --</option>");
        },
        success:function(result){
          $("select[id=section]").html(result);
          //$("select[name=section]").removeAttr("disabled");
        }
      });
    }
  }
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