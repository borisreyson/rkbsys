@extends('layout.master')
@section('title')
ABP-system | Category
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
                  <h2>Inventory Category</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-6">
<div class="col-lg-12 row">
  <button class="btn btn-primary" id="new_cat" data-toggle="modal" data-target="#myModal">New Category Item</button>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr class="bg-primary">
      <th>Code</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @if(count($category)>0)
    @foreach($category as $k => $v)
    <tr>
      <td>{{$v->code_category}}</td>
      <td>{{$v->desc_category}}</td>
      <td>
        @if($v->status=='0')
        <a href="{{url('/admin/inventory/category/status-'.bin2hex($v->code_category).'-enable')}}" class="btn btn-xs btn-success">Enable</a>
        @elseif($v->status=='1')
        <a href="{{url('/admin/inventory/category/status-'.bin2hex($v->code_category).'-disable')}}" class="btn btn-xs btn-danger">Disable</a>
        @endif
      </td>
      <td>
        <button type="button" class="btn btn-xs btn-warning" data-id="{{bin2hex($v->code_category)}}" id="editCat" data-toggle="modal" data-target="#myModal">Edit</button>
        <a href="{{url('/admin/inventory/category/del-'.bin2hex($v->code_category))}}" class="btn btn-xs btn-danger" id="delCat">Delete</a>
      </td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="4">
       <b>Total Record : {{count($category)}}</b>
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
    {{$category->links()}}
</div>
</div>
<div class="col-lg-6">  
<div class="col-lg-12 row">
  <button class="btn btn-primary" id="new_catVendor" data-toggle="modal" data-target="#myModal">New Category Vendor</button>
</div>
<table class="table table-bordered table-striped">
  <thead>
    <tr class="bg-primary">
      <th>Code</th>
      <th>Descripton</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($cat_vendor as $k => $v)
    <tr>
      <td>{{$v->kodeCat}}</td>
      <td>{{$v->CategoryVendor}}</td>
      @if($v->status==1)
      <td>       
        <a href="{{url('/admin/inventory/category/vendor/status-'.bin2hex($v->kodeCat).'-disable')}}" class="btn btn-xs btn-danger">Disable</a>
      </td>
      @else
      <td> 
        <a href="{{url('/admin/inventory/category/vendor/status-'.bin2hex($v->kodeCat).'-enable')}}" class="btn btn-xs btn-success">Enable</a>
      </td>
      @endif
      <td>
        
        <button type="button" class="btn btn-xs btn-warning" data-id="{{bin2hex($v->kodeCat)}}" id="editCatVendor" data-toggle="modal" data-target="#myModal">Edit</button>
        <a href="{{url('/admin/inventory/category/vendor/del-'.bin2hex($v->kodeCat))}}" class="btn btn-xs btn-danger" id="delCat">Delete</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>


<div class="col-lg-12 row">
  <button class="btn btn-primary" id="new_catUsingItem" data-toggle="modal" data-target="#myModal">New Category Using Item</button>
</div>
<table class="table table-bordered table-striped">
  <thead>
    <tr class="bg-primary">
      <th>Code</th>
      <th>Descripton</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

    @foreach($cat_item as $k => $v)
    <tr>
      <td>{{$v->CodeCat}}</td>
      <td>{{$v->DeskCat}}</td>
      @if($v->status==1)
      <td>       
        <a href="{{url('/admin/inventory/category/item/status-'.bin2hex($v->CodeCat).'-disable')}}" class="btn btn-xs btn-danger">Disable</a>
      </td>
      @else
      <td> 
        <a href="{{url('/admin/inventory/category/item/status-'.bin2hex($v->CodeCat).'-enable')}}" class="btn btn-xs btn-success">Enable</a>
      </td>
      @endif
      <td>
        
        <button type="button" class="btn btn-xs btn-warning" data-id="{{bin2hex($v->CodeCat)}}" id="editCatItem" data-toggle="modal" data-target="#myModal">Edit</button>
        <a href="{{url('/admin/inventory/category/item/del-'.bin2hex($v->CodeCat))}}" class="btn btn-xs btn-danger" id="delCat">Delete</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
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
<!-- Datatables -->
    <!-- FastClick -->


@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
<script>
  //NEW CATEGORY
  $(document).on("click","button[id=new_cat]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/category/new')}}",
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });
  $(document).on("click","button[id=new_catVendor]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/category/new/vendor')}}",
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });
  $(document).on("click","button[id=new_catUsingItem]",function(){
    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/category/new/item')}}",
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });

  


  //EDIT CATEGORY
  $(document).on("click","button[id=editCat]",function(){
    eq = $("button[id=editCat]").index(this);
    data_id = $("button[id=editCat]").eq(eq).attr("data-id");
    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/category/edit')}}",
      data:{data_id:data_id},
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });

  $(document).on("click","button[id=editCatVendor]",function(){
    eq = $("button[id=editCatVendor]").index(this);
    data_id = $("button[id=editCatVendor]").eq(eq).attr("data-id");

    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/category/edit/vendor')}}",
      data:{data_id:data_id},
      success:function(result){
        $("div[id=konten_modal]").html(result);
      }
    });
  });

  $(document).on("click","button[id=editCatItem]",function(){
    eq = $("button[id=editCatItem]").index(this);
    data_id = $("button[id=editCatItem]").eq(eq).attr("data-id");

    $.ajax({
      type:"GET",
      url:"{{url('/admin/inventory/category/edit/item')}}",
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