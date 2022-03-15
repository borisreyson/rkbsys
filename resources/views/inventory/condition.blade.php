@extends('layout.master')
@section('title')
ABP-system | Condition
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
  <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Inventory Condition</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
<table class="table table-striped table-bordered">
  <div class="col-lg-12 row">
    <button class="btn btn-primary" id="new_condition" data-toggle="modal" data-target="#myModal">New Condition</button>
  </div>
  <thead>
    <tr class="bg-primary">
      <th>Code</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @if(count($condition)>0)
    @foreach($condition as $k => $v)
    <tr>
      <td>{{$v->code}}</td>
      <td>{{$v->code_desc}}</td>
      <td>
        @if($v->status=='0')
        <a href="{{url('/admin/inventory/condition/status-'.bin2hex($v->code).'-enable')}}" class="btn btn-xs btn-success">Enable</a>
        @elseif($v->status=='1')
        <a href="{{url('/admin/inventory/condition/status-'.bin2hex($v->code).'-disable')}}" class="btn btn-xs btn-danger">Disable</a>
        @endif
      </td>
      <td>
        <button type="button" class="btn btn-xs btn-warning" data-id="{{bin2hex($v->code)}}" id="editCon" data-toggle="modal" data-target="#myModal">Edit</button>
        <a href="{{url('/admin/inventory/condition/del-'.bin2hex($v->code))}}" class="btn btn-xs btn-danger" id="delCon">Delete</a>
      </td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="4">
       <b>Total Record : {{count($condition)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="4" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<div class="col-lg-12 text-center">
    {{$condition->links()}}
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
  <div class="modal-dialog modal-lg">
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
  
  //NEW CONDITION
  $(document).on("click","button[id=new_condition]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/condition/new')}}",
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });

  //EDIT CATEGORY
  $(document).on("click","button[id=editCon]",function(){
    eq = $("button[id=editCon]").index(this);
    data_id = $("button[id=editCon]").eq(eq).attr("data-id");
    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/condition/edit')}}",
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