@extends('layout.master')
@section('title')
ABP-system | Vendor Sarana
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
                  <h2>Data Vendor Sarana</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">
<div class="col-lg-12 row">
  <button class="btn btn-primary" id="newVendor" data-toggle="modal" data-target="#myModal">Tambah Data Vendor Sarana</button>
</div>

<div class="col-lg-12 row">
  <table class="table table-bordered text-center">
    <thead>
      <tr class="bg-success">
        <th class="text-center">No Polisi</th>
        <th class="text-center">Nama</th>
        <th class="text-center">Alamat</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @if(count($vendor)>0)
      @foreach($vendor as $k => $v)
      @if($v->flag_p==0)
      <tr>
        <td>{{$v->no_pol}}</td>
        <td>{{$v->nama_p}}</td>
        <td>{{$v->alamat_p}}</td>
        <td>
          <button class="btn btn-xs btn-warning" id="editVendor" name="editVendor" data-id="{{bin2hex($v->no_pol)}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
          <a href="{{url('/sarpras/data/vendor/delete-')}}{{bin2hex($v->no_pol)}}" class="btn btn-xs btn-danger" id="deleteVendor" name="deleteVendor"><i class="fa fa-trash"></i></a>
        </td>
      </tr>
      @elseif($v->flag_p==1)
      <tr class="btn-danger">
        <td>{{$v->no_pol}}</td>
        <td>{{$v->nama_p}}</td>
        <td>{{$v->alamat_p}}</td>
        <td>
          <button class="btn btn-xs btn-warning" id="editVendor" name="editVendor" data-id="{{bin2hex($v->no_pol)}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
          <a href="{{url('/sarpras/data/vendor/restore-')}}{{bin2hex($v->no_pol)}}" class="btn btn-xs btn-warning" id="undoVendor" name="undoVendor" title="Restore"><i class="fa fa-undo"></i></a>
        </td>
      </tr>

      @endif
      @endforeach
      @else
      <tr>
        <td colspan="4" style="background-color: rgba(0,0,0,0.5);color:#fff;" class="text-center">No Have Record!</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
<div class="col-lg-12 text-center">
  <!---PAGINATION-->
  {{$vendor->links()}}
  <!---PAGINATION-->
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
<!-- Datatables -->
    <!-- FastClick -->


@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
<script>
  $("button[id=newVendor]").click(function(){
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/vendor/form')}}",
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=editVendor]").click(function(){
    data_id = $("button[id=editVendor]").attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/vendor/edit')}}",
      data:{_token:"{{csrf_token()}}",data_id:data_id},
      success:function(res){
        $("div[id=konten_modal]").html(res);
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