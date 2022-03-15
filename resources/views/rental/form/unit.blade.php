@extends('layout.master')
@section('title')
ABP-system | Data Unit
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
.myButtonDiss{
  background-color: transparent;
  border: 0px;
  position: absolute;
  z-index: 999;
  font-size: 25px;
  right: 50px;
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
                  <h2>Data Unit</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

  <div class="row col-lg-12">


<div class="col-lg-6">
  <div class="col-lg-12">
    <p><b>Jumlah : {{count($unit)}}</b> </p>
  </div>
  <table class="table table-bordered text-center">
    <thead>
      <tr class="bg-success">
        <th class="text-center">No</th>
        <th class="text-center">Unit</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($unit as $k => $v)
      <tr @if($v->flag!=1) style="background-color: red;color: white;" @endif>
        <td class="text-center">{{$v->id_unit}}</td>
        <td class="text-center">{{$v->nama_unit}}</td>
        <td class="text-center">
          <a class="btn btn-warning btn-xs" href="{{url('/mon/unit/rental/form/unit-'.bin2hex($v->id_unit))}}" id="edit"><i class="fa fa-pencil"></i></a>
          @if($v->flag==1)
          <a class="btn btn-danger btn-xs" href="{{url('/mon/unit/rental/unit-del'.bin2hex($v->id_unit))}}" id="edit"><i class="fa fa-trash"></i></a>
          @else
          <a class="btn btn-success btn-xs" href="{{url('/mon/unit/rental/unit-undo'.bin2hex($v->id_unit))}}" id="edit"><i class="fa fa-undo"></i></a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="col-lg-12">
    <p><b>Jumlah : {{count($unit)}}</b> </p>
  </div>

<div class="col-lg-12 text-center">
  <!---PAGINATION-->
  {{$unit->links()}}
  <!---PAGINATION-->
</div>
</div>


<div class="col-lg-6">
  <form class="form-horizontal" method="post" action="">
  {{csrf_field()}}
  <div class="form-group">
    <label class="control-label col-lg-4">Nama Unit</label>
    <div class="col-lg-6">
      @if(isset($edit))
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="unit_old" class="form-control" required="required" value="{{$edit->nama_unit}}">
      <input type="text" name="unit" class="form-control" required="required" value="{{$edit->nama_unit}}">
      @else
      <input type="text" name="unit" class="form-control" required="required" placeholder="Data Unit">
      @endif
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-6 col-lg-4">
      <button class="btn btn-primary" id="Simpan" name="Simpan" type="submit">Simpan</button>  
      <a href="{{url('/mon/unit/rental/form/unit')}}" class="btn btn-danger">Batal</a>  
    </div>
  </div>
</form>
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
    @if(isset($edit))
      <script>
        $("button[name=Simpan]").addClass("disabled").attr("disabled","disabled");
        $("input[name=unit]").keyup(function(){
          unit_old = $("input[name=unit_old]").val();
          Unit     = $("input[name=unit]").val();
          if(unit_old==Unit){
            $("button[name=Simpan]").addClass("disabled").attr("disabled","disabled");
          }else{
            $("button[name=Simpan]").removeClass("disabled").removeAttr("disabled");
          }
        });
      </script>
    @endif
<script>
  $(".myButtonDiss").click(function(){
      document.location= "{{url('/sarpras/data/driver')}}";
    });
  $("button[id=newDriver]").click(function(){
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/driver/form')}}",
      success:function(res){
        $("div[id=konten_modal]").html(res);
      }
    });
  });
  $("button[id=editDriver]").click(function(){
    eq = $("button[id=editDriver]").index(this);
    data_id = $("button[id=editDriver]").eq(eq).attr('data-id');
    $.ajax({
      type:"GET",
      url :"{{url('/sarpras/data/driver/edit')}}",
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