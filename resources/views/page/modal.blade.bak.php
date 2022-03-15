<!---MODAL DETAIL-->
@if(isset($detail_rkb)=="OPEN")
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">
      	<div class="table-responsive">
              <table width="100%" class="table table-striped jambo_table bulk_action  " id="" >
                <thead>
                  <tr class="headings">
                    <th class="column-title" width="80px">No Entry </th>
                    <th class="column-title">Part Name </th>
                    <th class="column-title">Part Number </th>
                    <th class="column-title">Quantity</th>
                    <th class="column-title">Due Date </th>
                    <th class="column-title">Remarks </th>
                    <th class="column-title" >Action </th>
                    </th>
                    <th class="bulk-actions" colspan="6">
                      <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                    </th>
                  </tr>
                </thead>
                <tbody>
                	@foreach($rkb_det as $k => $v)
                	<tr class="even pointer">
                            <td class=" ">{{$k+1}} </td>
                            <td class=" ">{{$v->part_name}}
                            </td>
                            <td class=" ">{{$v->part_number}} </td>
                            <td class=" ">{{$v->quantity}} {{$v->satuan}}</td>
                            <td class=" ">{{date("d F Y",strtotime($v->due_date))}}</td>
                            <td class=" ">
<?php
$cek = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
->where([
['no_rkb',$v->no_rkb],
['part_name',$v->part_name]
])
->first();
if($cek!=null){
?>
<font color="red">Item Cancel By {{strtoupper($cek->cancel_by)}} Remark : {{$cek->remarks}}</font>
<?php }else{?>
{{$v->remarks or "-"}}
<?php } ?>

                            </td>
                            <td class="cancel_action">

<div class="btn-group dropright">
<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
        
<ul id="check_li" role="menu" class="dropdown-menu pull-right ">

                              @php
                              $data=0;
                              $count_pic = Illuminate\Support\Facades\DB::table('e_rkb_pictures')
                                           ->where([
                                            ["no_rkb",$v->no_rkb],
                                            ["part_name",$v->part_name]
                                          ])->count();
                              @endphp
                              @if($count_pic>0)
                              @php $data = 1; @endphp
                              <li><a href="#" class="" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" id="pictures_btn">Contoh Gambar <i class="fa fa-file-o"></i></a></li>
                              @endif
                              @php
                              $count_pen = Illuminate\Support\Facades\DB::table('e_rkb_penawaran')
                                           ->where([
                                            ["no_rkb",$v->no_rkb],
                                            ["part_name",$v->part_name]
                                          ])->count();
                              @endphp
                              @if($count_pen>0)
                              @php $data = 1; @endphp
                              <li><a href="#" class="" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" id="penawaran_btn">Penawaran <i class="fa fa-file-o"></i></a></li>
<?php
      $cek = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
             ->where([
              ['no_rkb',$v->no_rkb],
              ['part_name',$v->part_name]
            ])
             ->first();
             if($cek!=null){
              echo "<li style=\"background-color:rgba(232,44,12,0.6);color:#fff;\"><a href=\"\" style=\"background-color:rgba(232,44,12,0.6);color:#fff;\" onclick=\"return false;\">".strtoupper($cek->cancel_by)." : \" ".$cek->remarks." \"</a>";
             }else{?>

                  @if($_SESSION['section']=="KABAG" || $_SESSION['section']=="KTT")
@php
  $approve = Illuminate\Support\Facades\DB::table('e_rkb_approve')
              ->where("no_rkb",$v->no_rkb)
              ->first();
@endphp
@if($approve->disetujui == 0)
                              @php $data = 1; @endphp
                              @if(count($rkb_det)>1)
                              @if($approve->cancel_user=='')
              <li><a href="#" class="" id="Cancel_item" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" >Cancel</a></li>
                              @endif
              @endif
@else
@if($approve->diketahui == 0)
              @if($_SESSION['section']=="KTT")
@if($approve->cancel_user == null)
                              @php $data = 1; @endphp
                              @if(count($rkb_det)>1)
             @if($approve->cancel_user=='')
              <li><a href="#" class="" id="Cancel_item" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" >Cancel</a></li>
                              @endif
                               @endif
@endif              
@else
              @endif
@endif
@endif
                  @endif
             <?php
             }
?>
                              @else
<?php
      $cek = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
             ->where([
              ['no_rkb',$v->no_rkb],
              ['part_name',$v->part_name]
            ])
             ->first();
             if($cek!=null){
              $data = 1;
              echo "<li class=\"\"><a href=\"javascript:void(0)\" >Cancel By ".strtoupper($cek->cancel_by)." : \" ".$cek->remarks." \"</a></li>";
             }else{?>
              @php
              $cek_approve = Illuminate\Support\Facades\DB::table('e_rkb_approve')
               ->where([
                ['no_rkb',$v->no_rkb],
                ["cancel_user","!=",null]
              ])
             ->first();
              @endphp
             @if($cek_approve==null)
                  @if($_SESSION['section']=="KABAG" || $_SESSION['section']=="KTT")
@php
  $approve = Illuminate\Support\Facades\DB::table('e_rkb_approve')
              ->where("no_rkb",$v->no_rkb)
              ->first();
@endphp
@if($approve->disetujui == 0)
                              @php $data = 1; @endphp
                              @if(count($rkb_det)>1)
             @if($approve->cancel_user=='')
              <li><a href="#" class="" id="Cancel_item" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" >Cancel</a></li>
                              @endif
                               @endif
@else
@if($approve->diketahui == 0)
@if($_SESSION['section']=="KTT")       
@if($approve->cancel_user == null)
                              @php $data = 1; @endphp
                              @if(count($rkb_det)>1)
             @if($approve->cancel_user=='')
              <li><a href="#" class="" id="Cancel_item" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" >Cancel</a></li>
                              @endif
                               @endif
@endif    

@else

@endif

@endif

@endif

                  @endif
              @else
              
              @endif
<?php
}
?>
@endif

@if($_SESSION['section']=="PURCHASING") 
@php
  $app = Illuminate\Support\Facades\DB::table('e_rkb_approve')
              ->where("no_rkb",$v->no_rkb)
              ->first();
if($app->diketahui=='0'){
@endphp
 @php $data = 1; @endphp
                 <li><a href="{{url('/purchasing/upload-penawaran/'.bin2hex($v->no_rkb).'/'.bin2hex($v->part_name))}}" target="_blank" id="upload_penawaran">Upload Penawaran</a></li>
@php
}
@endphp
@endif
@if($data!=1)
<li><a href="javascript:void(0)" onclick="return false;">NO Action</a></li>
@endif
</ul>
</div>
                            </td>
                          </tr>
                	@endforeach
                </tbody>
            </table>
        </div>
@php
  $app = Illuminate\Support\Facades\DB::table('e_rkb_approve')
              ->join("user_login","user_login.username","e_rkb_approve.cancel_user")
              ->select("user_login.*","e_rkb_approve.*")
              ->where("no_rkb",$v->no_rkb)
              ->first();
@endphp
@if(isset($app))
@if($app->cancel_user!=null)
<div class="container" style="background-color:#AD1805;color: white;padding-top: 15px;"> 
<div class="col-md-6 col-xs-12">
  <div class="col-lg-12 col-xs-12 row"><h4>User Cancel</h4></div>
  <div class="col-lg-12 col-xs-12 row">{{$app->nama_lengkap}}</div>
</div>
<div class="col-md-6 col-xs-12">
  <div class="col-lg-12 col-xs-12 row"><h4>Cancel Remarks</h4></div>
  <div class="col-lg-12 col-xs-12 row">{{$app->remark_cancel}}</div>
</div>
</div>
@endif
@endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- Datatables -->
    <script src="{{asset('/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $("#datatable_new").dataTable({"searching": false});
        $("a[id=Cancel_item]").on("click",function(){
          eq = $("a[id=Cancel_item]").index(this);

          no_rkb = $("a[id=Cancel_item]").eq(eq).attr("no_rkb");
          part_name = $("a[id=Cancel_item]").eq(eq).attr("part_name");
          $.ajax({
            type:"POST",
            url:"/rkb/cancel/item/setRemarks",
            data:{no_rkb:no_rkb,part_name:part_name},
            beforeSend:function(){
              $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
            },
            success:function(result){
              $("#konten_modal").html(result).fadeIn();
            }
          });
        });
        $("a[id=penawaran_btn]").click(function(){
            eq = $("a[id=penawaran_btn]").index(this);
            no_rkb = $("a[id=penawaran_btn]").eq(eq).attr("no_rkb");
            part_name = $("a[id=penawaran_btn]").eq(eq).attr("part_name");
            $.ajax({
              type:"POST",
              url:"{{url('/rkb/detail/files')}}",
              data:{no_rkb:no_rkb,part_name:part_name},
              beforeSend:function(){
                $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
              },
              success:function(result){
                $("#konten_modal").html(result).fadeIn();
              }
            });
        });

        $("a[id=pictures_btn]").click(function(){
            eq = $("a[id=pictures_btn]").index(this);
            no_rkb = $("a[id=pictures_btn]").eq(eq).attr("no_rkb");
            part_name = $("a[id=pictures_btn]").eq(eq).attr("part_name");
            $.ajax({
              type:"POST",
              url:"{{url('/rkb/detail/pictures')}}",
              data:{no_rkb:no_rkb,part_name:part_name},
              beforeSend:function(){
                $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
              },
              success:function(result){
                $("#konten_modal").html(result).fadeIn();
              }
            });
        });
    </script>
@endif
<!---MODAL DETAIL-->
@if(isset($setRemarks))
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">

<form id="form_cancel" action="#" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">No RKB <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-4 col-xs-3">
<input type="text" id="id_rkb" required="required" disabled="disabled" name="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$no_rkb}}">
</div>
</div>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Part Name <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-4 col-xs-3">
<input type="text" id="id_rkb" required="required" disabled="disabled" name="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$part_name}}">
</div>
</div>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Cancel Remark <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-4 col-xs-3">
  <textarea class="form-control col-md-4 col-xs-3" id="remarks" name="remarks"  required="required"></textarea>
</div>
</div>

<div class="form-group">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
<button type="submit" class="btn btn-success">Submit</button>
<button class="btn btn-primary" type="reset">Reset</button>
<button type="button" class="btn btn-danger" id="cancelRemarks" data-id="{{$no_rkb}}">Cancel</button>
</div></div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
      </div>
    </div>

<script>

  $("form[id=form_cancel]").submit(function(){
    remarks = $("#remarks").val();
    
    $.ajax({
      type:"POST",
      url:"{{url('/rkb/cancel/item/')}}",
      data:{no_rkb:"{{$no_rkb}}",part_name:"{{$part_name}}",remarks:remarks},
      beforeSend:function(){
            //$("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
      },
      success:function(result){
        $("button[id=cancelRemarks]").click();
      }
    });
    
    return false;
  });
  $("button[id=cancelRemarks]").on("click",function(){
      eq = $("button[id=cancelRemarks]").index(this);
      data_id = $("button[id=cancelRemarks]").eq(eq).attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:data_id},
        beforeSend:function(){
            $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
        },
        success:function(result){
          $("div[id=konten_modal]").html(result).fadeIn();
        }
      });
  });
</script>
@endif


@if(isset($itemFiles))
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">
            <div class="clearfix"></div>
        <div class="container-fluid"> 
    <div class="row">
<div class="col-lg-12 profile_details" style="padding: 0px!important;margin:0px!important;width: 100%!important;">
  <div class="well profile_view col-lg-12">
    <div class="col-sm-12">
      <h4 class="brief"><i>{{$part_name}}</i></h4>
      <div class="left col-xs-12">
        <h2>File Penawaran</h2>
      </div>
      <div class="right col-xs-12 text-center">
@foreach($penawaran as $k => $v)
@php
 $imgExt =  explode('.',$v->file);
 $Ext = end($imgExt);
@endphp
@if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.bin2hex($v->file))}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <img src="{{url('/rkb/detail/files/penawaran/view-'.bin2hex($v->file))}}" style="width: 45px;height: 40px;" class="img-responsive">
          </div>
        </a>
        </div>
@elseif($Ext=="ppt"||$Ext=="pptx")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-powerpoint-o fa-3x"></i>
          </div>
          </a>
        </div>      
@elseif($Ext=="zip"||$Ext=="rar")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-archive-o fa-3x"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="xls"||$Ext=="xlsx")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-excel-o fa-3x"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="doc"||$Ext=="docx")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-word-o fa-3x"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="pdf")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.bin2hex($v->file))}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-pdf-o fa-3x"></i>
          </div>
        </a>
        </div>

@else
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-o fa-3x"></i>
          </div>
        </a>
        </div>
@endif
@endforeach
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="back" data-id="{{$no_rkb}}">Back</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <script>
      $("button[id=back]").on("click",function(){
        data_id = $("button[id=back]").attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:data_id},
        beforeSend:function(){
            $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
        },
        success:function(result){
          $("div[id=konten_modal]").html(result).fadeIn();
        }
      });
      });
    </script>
@endif

@if(isset($replace_file))

@php
 $imgExt =  explode('.',$file->file);
 $Ext = end($imgExt);
@endphp
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ganti File</h4>
      </div>
      <div class="modal-body">
<div class="row">
@if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <img src="{{url('/rkb/detail/files/view-'.$file->file)}}" style="width:100%;height: 100%;" class="img-responsive">
          </div>
        </a>
        </div>
@elseif($Ext=="ppt"||$Ext=="pptx")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-powerpoint-o fa-3x" style="font-size: 80px!important;"></i>
          </div>
          </a>
        </div>      
@elseif($Ext=="zip"||$Ext=="rar")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-archive-o fa-3x" style="font-size: 80px!important;"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="xls"||$Ext=="xlsx")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-excel-o fa-3x" style="font-size: 80px!important;"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="doc"||$Ext=="docx")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-word-o fa-3x" style="font-size: 80px!important;"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="pdf")
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-pdf-o fa-5x" style="font-size: 80px!important;"></i>
          </div>
        </a>
        </div>
@else
        <div class="col-xs-2">
          <a href="{{url('/rkb/detail/files/view-'.$file->file)}}" target="_blank">
          <div class="thumbnail" style="height: 100px;padding: 5px;text-align: center;margin-bottom: 0px;">
            <i class="fa fa-file-o fa-5x" style="font-size: 80px!important;"></i>
          </div>
        </a>
        </div>
@endif  
<div class="col-md-10">
<form action="{{url('/form_rkb/img-reupload-'.$file->file)}}" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
{{csrf_field()}}
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="file">File<span class="required">*</span>
</label>
<div class="col-md-6 col-sm-4 col-xs-3">
  <div  class="form-control-static col-md-4 col-xs-3" >
  <input type="file" id="file" required="required" name="file">
</div>
</div>
</div>


<div class="form-group">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
<button type="submit" class="btn btn-success">Submit</button>
</div>
</div>

</form>
</div>

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
      </div>
    </div>
@endif

@if(isset($rkb_cancel))

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Cancel {{$header->no_rkb}}</h4>
  </div>
  <div class="modal-body">

<form action="{{asset('/rkb/cancel-rkb.submit')}}" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
{{csrf_field()}}
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_rkb">No RKB <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-4 col-xs-3">
  <input type="text" name="no_rkb" id="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$header->no_rkb}}" readonly="readonly">
</div>
</div>
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Cancel Remarks <span class="required">*</span>
</label>
<div class="col-md-8 col-sm-6 col-xs-3">
<textarea type="text" name="remarks" id="remarks" class="form-control col-md-4 col-xs-3" required></textarea>
</div>
</div>


<div class="form-group">
<div class="col-md-3 col-sm-6 col-xs-12 col-md-offset-9">
<button type="submit" class="btn btn-success">Submit</button>
</div>
</div>

</form>
</div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
  </div>
</div>

@endif



@if(isset($itemPICTURES))
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">
            <div class="clearfix"></div>
        <div class="container-fluid"> 
    <div class="row">
<div class="col-lg-12 profile_details" style="padding: 0px!important;margin:0px!important;width: 100%!important;">
  <div class="well profile_view col-lg-12">
    <div class="col-sm-12">
      <h4 class="brief"><i>{{$part_name}}</i></h4>
      <div class="left col-xs-12">
        <h2>Contoh Gambar</h2>
      </div>
      <div class="right col-xs-12 text-center">
@foreach($penawaran as $k => $v)
@php
 $imgExt =  explode('.',$v->file);
 $Ext = end($imgExt);
@endphp
@if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <img src="{{url('/rkb/detail/files/view-'.$v->file)}}" style="width: 45px;height: 40px;" class="img-responsive">
          </div>
        </a>
        </div>
@elseif($Ext=="ppt"||$Ext=="pptx")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-powerpoint-o fa-3x"></i>
          </div>
          </a>
        </div>      
@elseif($Ext=="zip"||$Ext=="rar")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-archive-o fa-3x"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="xls"||$Ext=="xlsx")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-excel-o fa-3x"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="doc"||$Ext=="docx")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-word-o fa-3x"></i>
          </div>
        </a>
        </div>

@elseif($Ext=="pdf")
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-pdf-o fa-3x"></i>
          </div>
        </a>
        </div>

@else
        <div class="col-xs-1">
          <a href="{{url('/rkb/detail/files/penawaran/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail" style="height: 50px;">
            <i class="fa fa-file-o fa-3x"></i>
          </div>
        </a>
        </div>
@endif
@endforeach
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="back" data-id="{{$no_rkb}}">Back</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <script>
      $("button[id=back]").on("click",function(){
        data_id = $("button[id=back]").attr("data-id");
      $.ajax({
        type:"POST",
        url:"{{url('/rkb/detail.py')}}",
        data:{no_rkb:data_id},
        beforeSend:function(){
            $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
        },
        success:function(result){
          $("div[id=konten_modal]").html(result).fadeIn();
        }
      });
      });
    </script>
@endif