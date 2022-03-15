@extends('layout.master')
@section('title')
ABP-system | Form Rencana Kebutuhan Barang
@endsection
@section('css')
 @include('layout.css')
 <!-- Datatables -->
    <link href="{{asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('content')

<body class="nav-md" style="display: none;">
<div class="container body">
<div class="main_container">
@include('layout.nav')
@include('layout.top')
<div class="right_col" role="main">
  <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Form Entry <small>Rencana Kebutuhan Barang</small></h2>
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
                    <br />
                    <form id="demo-form2" @if(!isset($edit_rkb)) action="/e_rkb_temp" @else action="/e_rkb_temp/{{$edit_rkb->id_rkb}}" @endif data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
                      @if(isset($edit_rkb))
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">No entry <span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-2 col-xs-3">
                          <input type="text" id="id_rkb" required="required" disabled="disabled" name="id_rkb" class="form-control col-md-4 col-xs-3" value="{{$edit_rkb->id_rkb}}">
                        </div>
                      </div>
                      @endif
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_name">Part Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="part_name" required="required" name="part_name" class="form-control col-md-7 col-xs-12" value="{{$edit_rkb->part_name or ''}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_number">Part Number <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="part_number" name="part_number" required="required" class="form-control col-md-7 col-xs-12" value="{{$edit_rkb->part_number or ''}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="quantity" class="control-label col-md-3 col-sm-3 col-xs-12">Quantity<span class="required">*</span></label>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input id="quantity" class="form-control col-md-7 col-xs-12 quantity" min="1" type="number" name="quantity" value="{{$edit_rkb->quantity or '1'}}"  required="required">
                        </div>
                        <label for="satuan" class="control-label col-md-1 col-sm-3 col-xs-12">Satuan<span class="required">*</span></label>
                        <div class="col-md-2 col-sm-4 col-xs-12">
                          <select class="form-control col-md-7 col-xs-12" name="satuan" id="satuan" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuan as $k => $v)
                            @if(isset($edit_rkb))
                              @if($v->satuannya==$edit_rkb->satuan)
                                <option value="{{$v->satuannya}}" selected="selected">{{$v->satuannya}}</option>
                                @else
                                <option value="{{$v->satuannya}}">{{$v->satuannya}}</option>
                              @endif
                            @else
                                <option value="{{$v->satuannya}}">{{$v->satuannya}}</option>
                            @endif
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="remark" class="control-label col-md-3 col-sm-3 col-xs-12">Due Date</label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
<input type="text" id="due_date" readonly="readonly" class="form-control col-md-12 col-xs-12" value="@php if(isset($edit_rkb)){ echo date('d F Y',strtotime($edit_rkb->due_date)); } else { echo date('d F Y'); } @endphp" name="due_date">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="remark" class="control-label col-md-3 col-sm-3 col-xs-12">Remark</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
<textarea id="remark" class="form-control col-md-7 col-xs-12" name="remark">{{$edit_rkb->remarks or ''}}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sample Image
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label  class="form-control-static col-md-7 col-xs-12">
                          <input type="file" id="files" name="files[]" accept="image/*" multiple>
                          </label>
                        </div>
                      </div>
@if(isset($edit_rkb))
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Gambar
                        </label>
                        <div class="col-md-9">
                          <div class="row">
                          <div class="col-xs-12">
@foreach($penawaran as $k => $v)
@php
 $imgExt =  explode('.',$v->file);
 $Ext = end($imgExt);
@endphp
@if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <img src="{{url('/rkb/detail/files/view-'.$v->file)}}" style="width:100%;height: 100%;" class="img-responsive">
          </div>
        </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/gambar/delete-'.$v->file)}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </center>
        </div>
@elseif($Ext=="ppt"||$Ext=="pptx")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-powerpoint-o fa-3x" style="font-size: 90px!important;"></i>
          </div>
          </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/files/delete-'.$v->file)}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </center>
        </div>      
@elseif($Ext=="zip"||$Ext=="rar")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-archive-o fa-3x" style="font-size: 90px!important;"></i>
          </div>
        </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/files/delete-'.$v->file.'.pic')}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </center>
        </div>

@elseif($Ext=="xls"||$Ext=="xlsx")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-excel-o fa-3x" style="font-size: 90px!important;"></i>
          </div>
        </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/files/delete-'.$v->file)}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </center>
        </div>

@elseif($Ext=="doc"||$Ext=="docx")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-word-o fa-3x" style="font-size: 90px!important;"></i>
          </div>
        </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/files/delete-'.$v->file)}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </center>
        </div>

@elseif($Ext=="pdf")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-pdf-o fa-5x" style="font-size: 90px!important;"></i>
          </div>
        </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/files/delete-'.$v->file)}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </center>
        </div>
@else
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-o fa-5x" style="font-size: 90px!important;"></i>
          </div>
        </a>
        <center>
          <button type="button" class="btn btn-warning btn-xs" img-name="{{$v->file}}" id="edit" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-edit"></i>
          </button>
          <a href="{{url('/rkb/detail/files/delete-'.$v->file)}}" class="btn btn-xs btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </div>
@endif
@endforeach
</div></div>
                        </div>
                      </div>
@endif

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                      @if(!isset($edit_rkb))
                          <button type="submit" class="btn btn-success">Add</button>
                          <button class="btn btn-primary" type="reset">Reset</button>
                          <a href="{{url('/rkb')}}" class="btn btn-danger">Cancel</a>
                          @else
                          <button type="submit" class="btn btn-success">Update</button>
                          <a href="{{url('/form_rkb')}}" class="btn btn-default">New Entry</a>
                          <a href="{{url('/form_rkb')}}" class="btn btn-danger">Cancel</a>
                      @endif
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Form List<small>Rencana Kebutuhan Barang</small></h2>
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
                    <br />
<div class="row">
<div class="col-md-12 col-xs-12">
<div class="row">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th>
                              <input type="checkbox" id="check-all" class="flat">
                            </th>
                            <th class="column-title">No Entry </th>
                            <th class="column-title">Part Name </th>
                            <th class="column-title">Part Number </th>
                            <th class="column-title">Quantity </th>
                            <th class="column-title">Due Date </th>
                            <th class="column-title">Remarks </th>
                            <th class="column-title no-link last" width="115"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="6">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
                          @if(count($tmp_rkb)>0)
                          @foreach($tmp_rkb as $key => $value)
                          @if($key%2)
                          <tr class="even pointer">
                            <td class="a-center ">
                              <input type="checkbox" class="flat" name="table_records" value="{{$value->id_rkb}}">
                            </td>
                            <td class=" ">{{$key+1}}</td>
                            <td class=" ">{{$value->part_name}}</td>
                            <td class=" ">{{$value->part_number}} </td>
                            <td class=" ">{{$value->quantity}} {{$value->satuan}}</td>
                            <td class=" ">{{$value->due_date}}</td>
                            <td class=" ">{{$value->remarks}}</td>
                            <td class="last" align="right">
                              <a href="{{url('/form_rkb/'.$value->id_rkb)}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/form_rkb/'.$value->id_rkb.'/delete')}}" class="btn btn-danger btn-xs">Delete</a>
                            </td>
                          </tr>
                          @else
                          <tr class="odd pointer">
                            <td class="a-center ">
                        @if(isset($edit_rkb))
                        @if($edit_rkb->id_rkb!=$value->id_rkb)
                        <input type="checkbox" class="flat" name="table_records" value="{{$value->id_rkb}}">
                        @endif
                        @else
                        <input type="checkbox" class="flat" name="table_records" value="{{$value->id_rkb}}">
                        @endif
                            </td>
                            <td class=" ">{{$key+1}}</td>
                            <td class=" ">{{$value->part_name}}</td>
                            <td class=" ">{{$value->part_number}} </td>
                            <td class=" ">{{$value->quantity}} {{$value->satuan}}</td>
                            <td class=" ">{{date("d F Y",strtotime($value->due_date))}}</td>
                            <td class=" ">{{$value->remarks}}</td>
                            <td class=" last" align="right">
                        @if(isset($edit_rkb))
                        @if($edit_rkb->id_rkb!=$value->id_rkb)
                              <a href="{{url('/form_rkb/'.$value->id_rkb)}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/form_rkb/'.$value->id_rkb.'/delete')}}" class="btn btn-danger btn-xs">Delete</a>
                              @endif
                              @else
                              <a href="{{url('/form_rkb/'.$value->id_rkb)}}" class="btn btn-warning btn-xs">Edit</a>
                              <a href="{{url('/form_rkb/'.$value->id_rkb.'/delete')}}" class="btn btn-danger btn-xs">Delete</a>
                              @endif
                            </td>
                          </tr>
                          @endif
                          @endforeach
                          @else
                          <tr class="odd pointer">
                            <td class="a-center" align="center"  colspan="8">
                              Not have recored yet!
                            </td>
                         </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                    </div>
                  </div>
                  </div>
                    <div class="pull-right">
                      <button class="btn btn-danger pull-right" id="delete_selected" style="display: none;">Delete Records Selected</button>
                          @if(count($tmp_rkb)>0)
                          <div class="row ">
                      <form method="post" class="col-md-offset-9 col-md-3 col-xs-12" action="/create_rkb">
                          <div class="row ">
                        {{csrf_field()}}

                      @if(!isset($edit_rkb))
                      <button class="btn btn-success pull-right">Submit RKB</button>
                      @endif
                      </div>
                    </form>
                      </div>
                      @endif
                    </div>
            
                  </div>
                  </div>
                </div>
              </div>
            </div>
</div>

<style>
  center{
    padding-bottom: 15px;
  }
</style>
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
  $(window).on("load",function(){
    window.location="{{url('/v1/form_rkb')}}";
  });
  $("input[id=due_date]").datepicker({ dateFormat: 'dd MM yy' });

    $('.quantity').keyup(function () {     
      this.value = this.value.replace(/[^1-9\.]/g,'');
    });
    $("button[id=edit]").on("click",function(){
      eq = $("button[id=edit]").index(this);
      img_name =$("button[id=edit]").eq(eq).attr("img-name");
      $.ajax({
        type:"POST",
        url:"{{url('/form_rkb/img-edit.py')}}",
        data:{img_name:img_name},
        beforeSend:function(){
          $(".modal-dialog").removeClass("modal-lg").addClass("modal-md");
        },
        success:function(result){ 
          $("div[id=konten_modal]").html(result);
        }
      });
    });
</script>
<script src="{{asset('/clipboard/dist/clipboard.min.js')}}"></script>
@if(!$tmp_rkb)
  <script>
  </script>
@endif

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
