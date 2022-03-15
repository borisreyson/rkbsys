<?php
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Stock-Item-".hex2bin($item)."-Out.xls");
//dd($det);
//die();
?>
<table border="1" cellpadding="5" style="border-color: #000!important;" cellspacing="0">
  <thead>
    <tr class="bg-primary">
      <th style="background-color: blue; color: white;">Item</th>
      <th style="background-color: blue; color: white;">Stock Out</th>
      <th style="background-color: blue; color: white;">User Reciever</th>
      <th style="background-color: blue; color: white;">Department</th>
      <th style="background-color: blue; color: white;">Section</th>
      <th style="background-color: blue; color: white;">Location</th>
      <th style="background-color: blue; color: white;">Remarks</th>
      <th style="background-color: blue; color: white;">User Entry</th>
      <th style="background-color: blue; color: white;">Date In</th>
    </tr>
  </thead>
  <tbody>
    @if(count($det)>0)
    @foreach($det as $k => $v)
    <tr>
      <td>({{ucwords($v->item)}}) {{ucwords($v->item_desc)}}</td>
      <td>{{ucwords($v->stock_out)}} {{$v->satuan}}</td>
      <td>{{ucwords($v->user_reciever)}}</td>
      <td>{{ucwords($v->department)}}</td>
      <td>{{ucwords($v->sect)}}</td>
      <td>{{ucwords($v->location)}}</td>
      <td>{{ucwords($v->remark)}}</td>
      <td>{{ucwords($v->user_entry)}}</td>
      <td>{{strval(date(" H:i:s d F Y",strtotime($v->date_entry)))}}</td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="9">
       <b>Total Record : {{count($det)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="9" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<script>
  //window.close();
</script>