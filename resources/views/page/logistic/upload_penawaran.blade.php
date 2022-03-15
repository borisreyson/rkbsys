@extends('layout.master')
@section('title')
ABP-system | Upload Penawaran
@endsection
@section('css')
 @include('layout.css')
 <style>
   #link_penawaran{
    width: 80px;
    height: 80px; 
    z-index: 1;
   }

.middle
{
  position: absolute;
  top: 0px;
  left: 0px;
  right: 0px;
  bottom: 0px;
  width: 99%;
  background-color: rgba(0,0,0,0.25);
  height: 100%;
  border-radius: 10px;
  opacity: 0;
  transition: opacity .35s;
  z-index: 999;
}
#link_penawaran:hover .middle{
  opacity: 1;
}
.zoom_in{
  background-color: transparent;
  border:0;
  width: 100%;
  height: 100%;
}

 </style>
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<div class="right_col" role="main">
 <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Form <small>Upload Penawaran</small></h2>
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
  <div class="row">
    <form id="demo-form2" action="" method="post" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
{{csrf_field()}}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Rkb</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <span class="form-control-static col-md-7 col-xs-12">{{$gdata->no_rkb?$gdata->no_rkb:'-'}}</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Part Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <span class="form-control-static col-md-7 col-xs-12">{{$gdata->part_name?$gdata->part_name:'-'}}</span>
                        </div>
                      </div>
                      <div class="form-group">
                      <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Part Number</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <span class="form-control-static col-md-7 col-xs-12">{{$gdata->part_number?$gdata->part_number:'-'}}</span>
                        </div>
                      </div>
                      <div class="form-group">
                      <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Quantity</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <span class="form-control-static col-md-7 col-xs-12">{{$gdata->quantity?$gdata->quantity:'-'}} {{$gdata?$gdata->satuan:''}}</span>
                        </div>
                      </div>
                      <div class="form-group">
                      <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Remarks</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <span class="form-control-static col-md-7 col-xs-12">{{$gdata->remarks?$gdata->remarks:'-'}}</span>
                        </div>
                      </div>
                      @if($gdata!=null)
                      @php
                        $cek_file = Illuminate\Support\Facades\DB::table('e_rkb_penawaran')->where([
                        ['no_rkb',$gdata->no_rkb],
                        ['part_name',$gdata->part_name]
                        ])->get();
                      @endphp
                      @if(count($cek_file))
                      <div class="form-group">
                      <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">File Penawaran</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @foreach($cek_file as $keys => $values)
                          <span class="form-control-static col-xs-2">
                              <div id="link_penawaran"><img src="{{url('/images/pdf.png')}}" class="image" width="100%">
                              <div class="middle">
                                <div class="pull-left"> &nbsp;
                                <button id="edit" class="btn btn-warning btn-xs penawaran" data-toggle="modal" data-target="#myModal" type="button" f_name="{{bin2hex($values->file)}}" ><i class="fa fa-edit"></i></button>
                              </div>
                                <div class="pull-right">
                                <button id="delete" f_name="{{bin2hex($values->file)}}" class="btn btn-danger btn-xs penawaran" type="button"><i class="fa fa-trash"></i></button>&nbsp;</div>
                                <div class="col-xs-12">
                                  <br>
                                  @php
                                  $url = url("/rkb/detail/files/penawaran/view-".bin2hex($values->file));
                                  @endphp
                                  <button class="col-xs-12 zoom_in" onclick="window.open('{{$url}}');" type="button"><span class="fa fa-search-plus fa-3x "></span></button>
                                </div>
                              </div>
                            </div>
                              <span>Penawaran {{$keys+1}}</span>
                              
                          </span>
                            @endforeach
                        </div>
                      </div>
                      @endif
                      @endif
                      <div class="form-group">
                      <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Upload Penawaran</label>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input type="file" name="penawaran[]" multiple="multiple" accept="application/pdf" class="form-control-static col-md-5 col-xs-12" id="penawaran" required>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-primary" type="button" onclick="window.close()">Close</button>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
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
@include('layout.js')    
<script>
      $("button[id=edit]").click(function(){
      eq = $("button[id=edit]").index(this);
      f_name = $("button[id=edit]").eq(eq).attr('f_name');
      $.ajax({
        type:"post",
        url:"{{url('/purchasing/penawaran/replace')}}",
        data:{f_name:f_name,_token:"{{csrf_token()}}"},
        success:function(result){
          $("div[id=konten_modal]").html(result);
        }
      });
    });
    $("button[id=delete]").click(function(){
      eq = $("button[id=delete]").index(this);
      f_name = $("button[id=delete]").eq(eq).attr('f_name');
      $.ajax({
        type:"post",
        url:"{{url('/purchasing/penawaran/delete')}}",
        data:{f_name:f_name,_token:"{{csrf_token()}}",_method:'DELETE'},
        success:function(result){
          if(result=="OK"){
            localStorage.setItem("notif", "send");
            document.location.reload();
          }else{
            new PNotify({
              title: 'Error',
              text: "Error Delete Penawaran!",
              type: 'failed',
              hide: true,
              styling: 'bootstrap3'
            });
          }
        }
      });
    });
    if(localStorage.getItem("notif") == "send"){
      new PNotify({
              title: 'Success',
              text: "File Deleted!",
              type: 'success',
              hide: true,
              styling: 'bootstrap3'
            });
      localStorage.setItem("notif", "recive");
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
@endsection