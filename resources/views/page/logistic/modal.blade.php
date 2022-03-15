<style>
  thead tr th,tbody tr td{
    text-align: center;
  }  
</style>
@if(isset($replace))
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$f_name->file}}</h4>
      </div>
      <form id="demo-form2" action="{{url('/purchasing/penawaran/replace')}}" method="post" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
      <div class="modal-body">
{{csrf_field()}}
<input type="hidden" name="file_name" value="{{$f_name->file}}">
<input type="hidden" name="_method" value="PUT">
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select File</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="file" class="form-control-static col-md-7 col-xs-12"  name="penawaran" accept="application/pdf" required>
        </div>
      </div>
      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>
@endif

@if(isset($qty))

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$ID}}</h4>
      </div>
      <form id="demo-form2" action="{{url('/purchasing/update/qty')}}" method="post" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
      <div class="modal-body">
{{csrf_field()}}
<input type="hidden" name="_method" value="PUT">
      <table class="table">
        <thead>
          <tr class="headings">
            <th>Part Name</th>
            <th>Part Number</th>
            <th width="250px">Quantity</th>
            <th>Due Date</th>
            <th>Remarks</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rkb as $k => $v)
          <input type="hidden" name="no_rkb" value="{{$v->no_rkb}}">
          <input type="hidden" name="part_name[]" value="{{$v->part_name}}">
          <input type="hidden" name="part_number[]" value="{{$v->part_number}}">
          <input type="hidden" name="old_qty[]" value="{{$v->quantity}}">
          <input type="hidden" name="old_satuan[]" value="{{$v->satuan}}">
          <input type="hidden" name="due_date[]" value="{{$v->due_date}}">
          <input type="hidden" name="remarks[]" value="{{$v->remarks}}">
          <input type="hidden" name="user_entry[]" value="{{$v->user_entry}}">
          <input type="hidden" name="timelog[]" value="{{$v->timelog}}">
          <tr>
            <td>{{$v->part_name}}</td>
            <td>{{$v->part_number}}</td>
            <td>
            <div class="col-sm-5 staticParent">
              <input type="number" name="qty[]" min="1" value="{{$v->quantity}}" class="form-control child">
            </div>
              <div class="col-sm-7">
              <select name="satuan[]" class="form-control">
                <option value="">--PILIH--</option>
                @foreach($satuan as $ke => $val)
                @if($val->satuannya==$v->satuan)
                <option value="{{$v->satuan}}" selected>{{$v->satuan}}</option>
                @else
                <option value="{{$val->satuannya}}">{{$val->satuannya}}</option>
                @endif
                @endforeach
              </select>
            </div>
              </td>
            <td>{{date("d F Y",strtotime($v->due_date))}}</td>
            <td>{{$v->remarks}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>
  <script>
$(document).on("focus",".child",function() {
  $('.staticParent').on('keydown', '.child', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
});
  </script>
@endif

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
            <p>{{$v->quantity}} {{$v->satuan}}</p>
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
        <div class="col-lg-12 col-xs-12 bg-success" style="font-weight: bolder;">
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
@if($approve->diketahui==0 || $count_pic>0 || $count_pen>0)
        <div class="col-lg-12 col-xs-12 bg-info text-center">
          <div class="col-lg-6 col-xs-12">
            <br>
@if($count_pic>0)
@php $data = 1; @endphp
<a href="#" class="btn btn-xs btn-default" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" id="pictures_btn">Contoh Gambar <i class="fa fa-file-o"></i></a>
            <br>
@endif
@if($count_pen>0)
@php $data = 1; @endphp
<a href="#" class="btn btn-xs btn-default" no_rkb="{{$v->no_rkb}}" part_name="{{$v->part_name}}" id="penawaran_btn">Penawaran <i class="fa fa-file-o"></i></a>
            <br>
@endif
          </div>
          <div class="col-lg-6 col-xs-12">
@if($approve->cancel_user=='')<br>
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
@if($_SESSION['section']=="KABAG")
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
        </div>
        </div>
@endif
        @endif

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
    @if($approve->diketahui>0)
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
    <b>Status Barang</b> : {{$item_status}} {{$v->satuan}} Sudah Datang <br>
    <a href="{{url('/stock.in')}}?no_rkb={{bin2hex($status->no_rkb)}}&item={{bin2hex($v->item)}}" style="color:red;" target="_blank">Check Barang Disini!</a>
  </div>
</div>
   @endif
   @endif
@endif
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
        
        $("button[id=close_item]").on("click",function(){
          eq = $("button[id=close_item]").index(this);

          no_rkb = $("button[id=close_item]").eq(eq).attr("no_rkb");
          part_name = $("button[id=close_item]").eq(eq).attr("part_name");
          part_number = $("button[id=close_item]").eq(eq).attr("part_number");
          quantity = $("button[id=close_item]").eq(eq).attr("quantity");
          satuan = $("button[id=close_item]").eq(eq).attr("satuan");
          remarks = $("button[id=close_item]").eq(eq).attr("remarks");
          timelog = $("button[id=close_item]").eq(eq).attr("timelog");
          $.ajax({
            type:"POST",
            url:"/logistic/close/item",
            data:{no_rkb:no_rkb,part_name:part_name,part_number:part_number,quantity:quantity,satuan:satuan,remarks:remarks,timelog:timelog},
            beforeSend:function(){
              $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
            },
            success:function(result){
              $("#konten_modal").html(result).fadeIn();
            }
          });
        });


        $("button[id=update_status]").on("click",function(){
          eq = $("button[id=update_status]").index(this);

          no_rkb = $("button[id=update_status]").eq(eq).attr("no_rkb");
          part_name = $("button[id=update_status]").eq(eq).attr("part_name");
          part_number = $("button[id=update_status]").eq(eq).attr("part_number");
          quantity = $("button[id=update_status]").eq(eq).attr("quantity");
          satuan = $("button[id=update_status]").eq(eq).attr("satuan");
          remarks = $("button[id=update_status]").eq(eq).attr("remarks");
          timelog = $("button[id=update_status]").eq(eq).attr("timelog");
          close_remark = $("button[id=update_status]").eq(eq).attr("close_remark");
          id_status = $("button[id=update_status]").eq(eq).attr("id_status");

          $.ajax({
            type:"POST",
            url:"/logistic/update/item/status",
            data:{no_rkb:no_rkb,part_name:part_name,part_number:part_number,quantity:quantity,satuan:satuan,remarks:remarks,timelog:timelog,close_remark:close_remark,id_status:id_status},
            beforeSend:function(){
              $("div[id=konten_modal]").html("<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i>").hide();
            },
            success:function(result){
              $("#konten_modal").html(result).fadeIn();
            }
          });
        });


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


@if(isset($close_form))
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$request->no_rkb}}</h4>
      </div>
      <form id="demo-form2" action="{{url('/logistic/close/item')}}" method="post" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
      <div class="modal-body">
{{csrf_field()}}
<input type="hidden" name="_method" value="PUT">
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_name">Part Name</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-7 col-xs-12" name="part_name" value="{{$request->part_name}}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_number">Part Number</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-7 col-xs-12" name="part_number" value="{{$request->part_number}}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="quantity">Quantity</label>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-6 col-xs-12" value="{{$request->quantity}}" name="quantity" readonly>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-6 col-xs-12" value="{{$request->satuan}}" name="satuan" readonly>
        </div>

      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea type="text" class="form-control col-md-7 col-xs-12 " name="remarks" readonly>{{$request->remarks}}</textarea>
          <input type="hidden" class="form-control col-md-7 col-xs-12" name="timelog" value="{{$request->timelog}}" readonly>
          <input type="hidden" class="form-control col-md-7 col-xs-12" name="no_rkb" value="{{$request->no_rkb}}" readonly>
        </div>
      </div>
      <hr>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Close Remarks</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<textarea type="text" class="form-control col-md-7 col-xs-12 " name="close_remarks"></textarea>
        </div>
      </div>
      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>

@endif

@if(isset($update_status))

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$request->no_rkb}}</h4>
      </div>
      <form id="demo-form2" action="{{url('/logistic/update/item/status')}}" method="post" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
      <div class="modal-body">
{{csrf_field()}}
<input type="hidden" name="_method" value="PUT">
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_name">Part Name</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-7 col-xs-12" name="part_name" value="{{$request->part_name}}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_number">Part Number</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-7 col-xs-12" name="part_number" value="{{$request->part_number}}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="quantity">Quantity</label>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-6 col-xs-12" value="{{$request->quantity}}" name="quantity" readonly>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <input type="text" class="form-control col-md-6 col-xs-12" value="{{$request->satuan}}" name="satuan" readonly>
        </div>

      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea type="text" class="form-control col-md-7 col-xs-12 " name="remarks" readonly>{{$request->remarks}}</textarea>
          <input type="hidden" class="form-control col-md-7 col-xs-12" name="timelog" value="{{$request->timelog}}" readonly>
          <input type="hidden" class="form-control col-md-7 col-xs-12" name="no_rkb" value="{{$request->no_rkb}}" readonly>
          <input type="hidden" class="form-control col-md-7 col-xs-12" name="id_status" value="{{$request->id_status}}" readonly>
        </div>
      </div>
      <hr>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="old_close_remark">Old Close Remarks</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<textarea type="text" class="form-control col-md-7 col-xs-12 " readonly name="old_close_remark">{{$request->close_remark}}</textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="close_remark">Close Remarks</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<textarea type="text" class="form-control col-md-7 col-xs-12 " name="close_remark"></textarea>
        </div>
      </div>
      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>

@endif