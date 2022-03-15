<?php
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Stock-Out.xls");
?>
<table border="1" cellpadding="4" cellspacing="0" >
	<thead >
		<tr>
			<th style="background-color: blue; color: white;">User Receiver</th>
			<th style="background-color: blue; color: white;">Dept - Section</th>
			<th style="background-color: blue; color: white;">User Entry</th>
			<th style="background-color: blue; color: white;">Item</th>
			<th style="background-color: blue; color: white;">Item Desc</th>
			<th style="background-color: blue; color: white;">Item Out</th>
			<th style="background-color: blue; color: white;">Description</th>
			<th style="background-color: blue; color: white;">Category</th>
			<th style="background-color: blue; color: white;">Item Location</th>
			<th style="background-color: blue; color: white;">Date Entry</th>
		</tr>
	</thead>
	<tbody>
@if(count($totalStock)>0)
		@foreach($totalStock as $key => $value)
		<tr>
			<td>{{ucwords($value->user_reciever)}}</td>
			<td>{{ucwords($value->dept)}} - {{ucwords($value->section)}}</td>
			<td>{{ucwords($value->user_entry)}}</td>
			<td>{{ucwords($value->item)}}</td>
			<td>{{ucwords($value->item_desc)}}</td>
			<td>{{ucwords($value->stock_out)}} {{ucwords($value->satuan)}}   </td>
			<td>{{ucwords($value->remark)}}</td>
			<td>{{ucwords($value->code_category)}} ( {{ucwords($value->desc_category)}} )</td>
			<td>{{ucwords($value->location)}}</td>
			<td>{{date("H:i:s , d F Y",strtotime($value->date_entry))}}</td>
		</tr>
		@endforeach
	@endif
	</tbody>
</table>
<script>
	window.close();
</script>