<!---MODAL DETAIL-->
@if(isset($detail_rkb)=="OPEN")
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$no_rkb}}</h4>
      </div>
      <div class="modal-body">
<div class="row">
  <div class="col-lg-12">
    @foreach($rkb_det as $k => $v)
<?php
$cek = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
->where([
['no_rkb',$v->no_rkb],
['part_name',$v->part_name]
])
->first();
?>
@php
if($cek==null)
{
$color = "#08A32F";
}else{
$color = "#930C00";
}
@endphp
    <div class="col-lg-12  col-xs-12">
      <div class="row" style="border: solid thin <?php echo $color;?>;margin-right: 2px;margin-left: 2px;margin-bottom: 10px;">
        <div class="col-lg-6 col-xs-12 ">
          <label class="col-lg-3 col-xs-12 row">Part Name</label>
          <div class="col-lg-9 col-xs-12">
            <p>{{$v->part_name}}</p>
          </div>
        </div>
        <div class="col-lg-6 col-xs-12 ">
          <label class="col-lg-3 col-xs-12 row">Part Number</label>
          <div class="col-lg-9 col-xs-12">
            <p>{{$v->part_number or "-"}}</p>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 ">
          <label class="col-lg-3 col-xs-12 row">Quantity</label>
          <div class="col-lg-9 col-xs-12">
            <div class="col-xs-12 row">
            @php
           $koma = explode(".",$v->quantity);
            @endphp
            @if(isset($koma[1]))
            <p>{{number_format($v->quantity,1)}} {{$v->satuan}}</p>
            @else
            <p>{{number_format($v->quantity)}} {{$v->satuan}}</p>
            @endif
            </div>

          </div>
        </div>
        <div class="col-lg-6 col-xs-12 ">
          <label class="col-lg-3 col-xs-12 row">Remarks</label>
          <div class="col-lg-9 col-xs-12">
            <p>{{$v->remarks or "-"}}</p>
          </div>
        </div>

        <?php
        $noPO = Illuminate\Support\Facades\DB::table('e_rkb_po')->where([
                                                                  ["no_rkb",$no_rkb],
                                                                  ["item",$v->item]
                                                                ])->first();
        ?>
        @if(isset($noPO))
        <div class="col-lg-12 col-xs-12 btn-success" style="font-weight: bolder;">
          <div class="row">
          <div class="col-lg-2 col-xs-12">Nomor PO</div>
          <div class="col-lg-9 col-xs-12">
            {{$noPO?$noPO->no_po:""}}
          </div>
          <div class="col-lg-2 col-xs-12">Remark</div>
          <div class="col-lg-9 col-xs-12">
            {{$noPO?$noPO->keterangan:""}}
          </div>
        </div>
        </div>
        @endif

        @if($cek!=null)

@php
  $cancel_by = Illuminate\Support\Facades\DB::table('user_login')
              ->where("username",$cek->cancel_by)
              ->first();
@endphp
        <div class="col-lg-12 col-xs-12 bg-danger">
          <label class="col-lg-3 col-xs-12">Item Cancel Remark</label>
          <div class="col-lg-9 col-xs-12">
          <p>
            Item Cancel By {{strtoupper($cancel_by->nama_lengkap)}}
            <br> 
            Remark : {{$cek->remarks}}
          </p>
        </div>
        </div>
        @else
@php
$approve = Illuminate\Support\Facades\DB::table('e_rkb_approve')
      ->where("no_rkb",$v->no_rkb)
      ->first();

$data=0;
$count_pic = Illuminate\Support\Facades\DB::table('e_rkb_pictures')
->where([
["no_rkb",$v->no_rkb],
["part_name",$v->part_name]
])->count();

$count_pen = Illuminate\Support\Facades\DB::table('e_rkb_penawaran')
->where([
["no_rkb",$v->no_rkb],
["part_name",$v->part_name]
])->count();

@endphp
        <div class="col-lg-12 col-xs-12 bg-info text-center" style="padding-top: 5.5px;">
<div class="col-xs-6">
  <div class="row text-left">
    <label class="control-label" >User Due Date : {{date("d F Y",strtotime($v->due_date))}}</label>
  </div>
</div>
<div class="col-xs-6">
  <div class=" text-left">
    <!--<label class="control-label">Logistic Due Date : </label>-->
  </div>
</div>
@if($approve->diketahui==0 || $count_pic>0 || $count_pen>0)
          <div class="col-lg-6 col-xs-6">
@if($count_pic>0)
@php $data = 1; @endphp
<a href="" class="btn btn-xs btn-primary" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" <?php if(isset($_POST['parent_eq'])){ ?> parent_eq="{{$_POST['parent_eq']}}" <?php } ?> id="pictures_btn"><b>Attachment</b> <i class="fa fa-file-o"></i></a>
            <br>
@endif
@if($count_pen>0)
@php $data = 1; @endphp
<a href="#" class="btn btn-xs btn-default" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" id="penawaran_btn">Penawaran <i class="fa fa-file-o"></i></a>
            <br>
@endif
          </div>
          <div class="col-lg-6 col-xs-6">
@if(($_SESSION['section'])=="KTT" || ($_SESSION['section'])=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
<div class="pull-right">
  <button class="btn btn-xs btn-primary" name="tanyakan_item" id="tanyakan_item" part_name="{{$v->part_name}}"  <?php if(isset($_POST['parent_eq'])){ ?> parent_eq="{{$_POST['parent_eq']}}" <?php } ?> >Tanyakan! </button>
</div>
@if($approve->cancel_user=='')
@php $data = 1;
$count_Cancel = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
->where([
["no_rkb",$v->no_rkb]
])->count();
@endphp
@if(($count_Cancel)<(count($rkb_det)-1))
@php
  $approve = Illuminate\Support\Facades\DB::table('e_rkb_approve')
              ->where("no_rkb",$v->no_rkb)
              ->first();
@endphp
@if($_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
@if($approve->disetujui == 0)
<a href="#" class="btn btn-xs btn-danger" id="Cancel_item" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" >Cancel</a>
@endif
@endif
@if($_SESSION['section']=="KTT")
@if($approve->diketahui == 0)
<a href="#" class="btn btn-xs btn-danger" id="Cancel_item" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" >Cancel</a>
@endif
@endif
            <br>
@endif
@endif
@endif


        </div>

@endif



@if(!($_SESSION['section']=="KTT" || $_SESSION['section']=="KABAG" || $_SESSION['section']=="PURCHASING" || $_SESSION['section']=="SECTION_HEAD"))
@if($approve->cancel_section=='')
<a href="#" no_rkb="{{bin2hex($v->no_rkb)}}" part_name="{{bin2hex($v->part_name)}}" target="_blank" id="upload_file" class="btn btn-xs btn-default pull-right" <?php if(isset($_POST['parent_eq'])) { ?> parent_eq="{{$_POST['parent_eq']}}" <?php } ?>>Upload File</a>
@endif
@endif

        </div>
        @endif
<!---OK-->
    @php 
    $cancel_item = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
                    ->where([
                            ["no_rkb",$v->no_rkb],
                            ["part_name",$v->part_name]
                            ])->first();
    $item_status = Illuminate\Support\Facades\DB::table('item_status')
                    ->where([
                            ["no_rkb",$v->no_rkb],
                            ["part_name",$v->part_name],
                            ["part_number",$v->part_number],
                            ["void",0]
                            ])->sum("quantity");
    @endphp
@if(!$cancel_item)
    @if($item_status<=$v->quantity && $item_status != 0)
    <?php
    $status = Illuminate\Support\Facades\DB::table('item_status')
                    ->where([
                            ["no_rkb",$v->no_rkb],
                            ["part_name",$v->part_name],
                            ["part_number",$v->part_number],
                            ["void",0]
                            ])->first();
                            ?>
<div class="col-lg-12 col-xs-12" style="border-top: 1px solid #000;padding-top: 5px;padding-bottom: 5px;">
  <div class="col-lg-12 col-xs-12">
    <b>Status Barang</b> : {{$item_status}} {{$v->satuan}} Sudah Datang | 
    <a href="{{url('/stock.in')}}?no_rkb={{bin2hex($status->no_rkb)}}&item={{bin2hex($v->item)}}" style="color:red;" target="_blank">Check Barang Disini!</a>
  </div>
</div>
   @endif
@endif
<!--OK-->
      </div>
    </div>
    @endforeach
  </div>
</div>      	

@php
  $app = Illuminate\Support\Facades\DB::table('e_rkb_approve')
              ->join("user_login","user_login.username","e_rkb_approve.cancel_user")
              ->select("user_login.*","e_rkb_approve.*")
              ->where("no_rkb",$no_rkb)
              ->first();
@endphp
@if(isset($app))
@if($app->cancel_user!=null)
<div class="container" style="background-color:#AD1805;color: white;padding-top: 15px;padding-bottom:20px;"> 
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
            parent_eq = $("a[id=pictures_btn]").eq(eq).attr("parent_eq");
            $.ajax({
              type:"POST",
              url:"{{url('/rkb/detail/pictures')}}",
              data:{no_rkb:no_rkb,part_name:part_name,parent_eq:parent_eq},
              beforeSend:function(){
                $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
              },
              success:function(result){
                $("#konten_modal").html(result).fadeIn();
              }
            });
            return false;
        });


        $("a[id=upload_file]").click(function(){
            eq = $("a[id=upload_file]").index(this);
            no_rkb = $("a[id=upload_file]").eq(eq).attr("no_rkb");
            part_name = $("a[id=upload_file]").eq(eq).attr("part_name");
            parent_eq = $("a[id=upload_file]").eq(eq).attr("parent_eq");
            $.ajax({
              type:"POST",
              url:"{{url('/v3/rkb/upload/')}}",
              data:{no_rkb:no_rkb,part_name:part_name,parent_eq:parent_eq},
              beforeSend:function(){
                $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
              },
              success:function(result){
                $("#konten_modal").html(result).fadeIn();
              }
            });
            return false;
        });

    function tanyakan_item() {
    
      if( typeof ($.fn.slideToggle) === 'undefined'){ return; }
      console.log('init_compose');
      $('button[id=tanyakan_item]').click(function(){
        $('.compose').slideToggle();
        return false;
      });
      
    
    };

        
tanyakan_item();

      $("button[id=tanyakan_item]").click(function() {
        eq = $("button[id=tanyakan_item]").index(this);
        parent_eq = $("button[id=tanyakan_item]").eq(eq).attr("parent_eq");
        part_name = $("button[id=tanyakan_item]").eq(eq).attr("part_name");
        userTo = $("input[id=userTo]").eq(parent_eq).val();
        usernameTo = $("input[id=usernameTo]").eq(parent_eq).val();
        norkb_to = $("input[id=norkb_to]").eq(parent_eq).val();
        
        $("#tree").val("parent");
        $("#user_to").val(userTo);
        $("#username_to").val(usernameTo);    
        $("#part_name").val(part_name);    
        $("#no_rkb").val(norkb_to);    
      
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

<form id="form_cancel" action="#" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
      <div class="modal-body">


<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">No RKB <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-9 col-xs-12">
<input type="text" id="id_rkb" required="required" disabled="disabled" name="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$no_rkb}}">
</div>
</div>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Part Name <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-9 col-xs-12">
<input type="text" id="id_rkb" required="required" disabled="disabled" name="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$part_name}}">
</div>
</div>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_rkb">Cancel Remark <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-9 col-xs-12">
  <textarea class="form-control col-md-4 col-xs-3" id="remarks" name="remarks"  required="required"></textarea>
</div>
</div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <button class="btn btn-primary" type="reset">Reset</button>
        <button type="button" class="btn btn-danger" id="cancelRemarks" data-id="{{$no_rkb}}">Cancel</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
      </div>
</form>
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

<form action="{{asset('/rkb/cancel-rkb.submit')}}" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
  <div class="modal-body">

{{csrf_field()}}

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_rkb">No RKB <span class="required">*</span>
</label>
<div class="col-md-6 col-sm-9 col-xs-12">
  <input type="text" name="no_rkb" id="no_rkb" class="form-control col-md-4 col-xs-3" value="{{$header->no_rkb}}" readonly="readonly">
</div>
</div>
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Cancel Remarks <span class="required">*</span>
</label>
<div class="col-md-8 col-sm-9 col-xs-12">
<textarea type="text" name="remarks" id="remarks" class="form-control col-md-4 col-xs-3" required></textarea>
</div>
</div>

</div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-success">Submit</button>
    <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
  </div>
</div>

</form>
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
        <h2>Attachment</h2>
      </div>
      <div class="right col-xs-12 text-center">
@foreach($penawaran as $k => $v)
@php
 $imgExt =  explode('.',$v->file);
 $Ext = end($imgExt);
@endphp
@if($Ext=="jpg"||$Ext=="png"||$Ext=="gif"||$Ext=="jpeg")
        <div class="col-lg-3 col-xs-12 col-md-6">
          <a href="{{url('/rkb/detail/files/view-'.$v->file)}}" target="_blank">
          <div class="thumbnail">
            <div class="image">
              <img style="width: 100%; height: 100%; display: block;size: cover;" src="{{url('/rkb/detail/files/view-'.$v->file)}}" alt="image" />
            </div>

            <div class="caption">
              <p>Image {{$k+1}}</p>
              <p>Part Name {{$v->part_name}}</p>
            </div>
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
        data:{no_rkb:data_id,parent_eq:"{{$parent_eq}}"},
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