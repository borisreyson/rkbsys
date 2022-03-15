
<!DOCTYPE html>
<html>
<head>
  <title></title>
<style>
  tbody tr td{
    text-align: center;
  }
  div{
    margin: 15px;
  }
</style>
</head>
<body>
<div class="table-responsive">
                      <table id="datatables" border="1" cellpadding="5" cellspacing="0" class="table table-striped  table-bordered ">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Nomor Rkb </th>
                            <th class="column-title">Department - Section </th>
                            <th class="column-title">Tanggal Rkb </th>
                            <th class="column-title">Ka. Bag. </th>
                            <th class="column-title">KTT </th>
                            <th class="column-title">Part Name </th>
                            <th class="column-title">Part Number </th>
                            <th class="column-title">Quantity</th>
                            <th class="column-title">Remarks</th>
                            <th class="column-title">Cancel Remark </th>
                            <th class="column-title">Status</th>
                          </tr>
                        </thead>

                        <tbody>

@if(count($rkb)>0)
@foreach($rkb as $key => $value)
<tr class="even pointer" >
                            <td class=" "  style="text-align: left!important;">{{$value->no_rkb}}
                              @if(!($value->cancel_section=="KABAG" || $value->cancel_section=="KTT" || $value->cancel_section==null))
                              <label class="label label-danger" style="float: right!important;cursor: pointer;cursor: hand;" data-toggle="tooltip" title="Remarks : {{$value->remark_cancel}}">Cancel By {{$value->cancel_user}} </label>
                              @endif
                            </td>
                            <td class=" ">{{$value->dept}} - {{$value->det_sect}}</td>
                            <td class=" ">
                              {{date("d F Y",strtotime($value->tgl_order))}} 
                            </td>
                            <td class=" ">
                              @php
  $status = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
            ->where([
            ["no_rkb" , $value->no_rkb],
            ['part_name',$value->part_name]
            ])->first();

@endphp
@if($status==null)
                              @if($value->disetujui==0)
                              @if($_SESSION['section']=="KABAG")

                              @if($value->cancel_user==null)
                              <a href="{{asset('/approve/rkb/'.bin2hex($value->no_rkb))}}" id="approve_rkb" class="btn btn-success btn-xs" >Approve Rkb</a>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning">Waiting</label>
                              @else
                              @if($value->cancel_section=="KABAG")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->disetujui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_disetujui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Disetujui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->disetujui==2)
                              Cancel
                              @endif
                              @endif
                            </td>
                            <td class=" ">
                               @php
  $status = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
            ->where([
            ["no_rkb" , $value->no_rkb],
            ['part_name',$value->part_name]
            ])->first();

@endphp
@if($status==null)
                              @if($value->diketahui==0)
                              @if($_SESSION['section']=="KTT")
                              @if($value->cancel_user==null)
                              <a  no-rkb="{{bin2hex($value->no_rkb)}}" id="approve_rkb" class="btn btn-success btn-xs" <?php if(!$value->disetujui>0){?> href="#" onclick="return false;" disabled="disabled" <?php }else{?> href="{{asset('/approve/rkb/ktt/'.bin2hex($value->no_rkb))}}" <?php } ?>  >Approve Rkb</a>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @else
                              @if($value->cancel_user==null)
                              <label class="label label-warning"> Waiting</label>
                              @else
                              @if($value->cancel_section=="KTT")
                              <label class="label label-danger" style="cursor: pointer;cursor: hand;" title="Remarks: {{$value->remark_cancel}}" data-toggle="tooltip">Cancel By {{$value->cancel_user}}</label>
                              @endif
                              @endif
                              @endif
                              @elseif($value->diketahui==1)
                              <label class="label label-success">{{date("H:i:s ,d F Y",strtotime($value->tgl_diketahui))}}</label>
                              @php
                              $user_app = Illuminate\Support\Facades\DB::table("user_approve")
                                          ->leftjoin("user_login","user_login.username","user_approve.username")
                                          ->where([
                                          ["user_approve.no_rkb",$value->no_rkb],
                                          ["user_approve.desk","Diketahui"]
                                          ])
                                          ->first();
                              @endphp
                              @if($user_app->level=="PLT")
                              <br>
                              <label class="label label-default">{{$user_app->nama_lengkap}}</label>
                              @endif
                              @elseif($value->diketahui==2)
                              Cancel
                              @endif
                              
                              @endif

                            </td>

                            <td>{{$value->part_name}}</td>
                            <td>{{$value->part_number}}</td>
                            <td>{{$value->quantity}} {{$value->satuan}}</td>
                            <td>{{$value->remarks}}</td>
                            <td>{{$value->remark_cancel}}</td>
                            <td>
@php
  $status = Illuminate\Support\Facades\DB::table('e_rkb_cancel')
            ->where([
            ["no_rkb" , $value->no_rkb],
            ['part_name',$value->part_name]
            ])->first();
  if($status!=null){
  echo "Cancel By ".strtoupper($status->cancel_by)." Remarks : ".$status->remarks;
}
@endphp

                            </td>
@endforeach

 @else
            <tr class="odd pointer">
                            <td class="a-center" align="center"  colspan="11">
                              Not have recored yet!
                            </td>
                         </tr>
@endif
                        </tbody>
                      </table>
                      </div>
                  <script>window.print();
                window.close();</script>
</body>
</html>
                    