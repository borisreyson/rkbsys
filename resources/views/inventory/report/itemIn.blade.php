<?php
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Stock-Item-".hex2bin($item)."-In.xls");
?>
<table border="1" cellpadding="5" style="border-color: #000!important;" cellspacing="0">
  <thead>
    <tr class="bg-primary">
      <th style="background-color: blue; color: white;">Item</th>
      <th style="background-color: blue; color: white;">Stock In</th>
      <th style="background-color: blue; color: white;">Suplier</th>
      <th style="background-color: blue; color: white;">Method</th>
      <th style="background-color: blue; color: white;">PO Number</th>
      <th style="background-color: blue; color: white;">RKB Number</th>
      <th style="background-color: blue; color: white;">Nomor Surat</th>
      <th style="background-color: blue; color: white;">Part Name</th>
      <th style="background-color: blue; color: white;">Part Number</th>
      <th style="background-color: blue; color: white;">Condition</th>
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
      <td>{{ucwords($v->stock_in)}} {{$v->satuan}}</td>
      <td>{{ucwords($v->supplier)}}</td>
      <td>{{ucwords($v->methode)}}</td>
      <td>{{ucwords($v->no_po)}}</td>
      <td>{{ucwords($v->no_rkb)}}</td>
      <td>{{ucwords($v->no_surat)}}</td>
      <td>{{ucwords($v->part_name)}}</td>
      <td>{{ucwords($v->part_number)}}</td>
      <td>{{ucwords($v->condition)}}</td>
      <td>{{ucwords($v->location)}}</td>
      <td>{{ucwords($v->remark)}}</td>
      <td>{{ucwords($v->user_entry)}}</td>
      <td>{{date("H:i:s ,d F Y",strtotime($v->date_entry))}}</td>
    </tr>
    @endforeach
    <tr class="info">
      <td colspan="14">
       <b>Total Record : {{count($det)}}</b>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="13" class="text-center">Not Have Record</td>
    </tr>
    @endif
  </tbody>
</table>
<script>
  window.close();
</script>