<?php
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Stock-In.xls");
?>
<table border="1" cellpadding="4" cellspacing="0" >
	<thead >
		<tr>
			<th style="background-color: blue; color: white;">No RKb</th>
			<th style="background-color: blue; color: white;">Metode</th>
			<th style="background-color: blue; color: white;">No PO</th>
			<th style="background-color: blue; color: white;">No Surat</th>
			<th style="background-color: blue; color: white;">Part Name - Part Number</th>
			<th style="background-color: blue; color: white;">Item</th>
			<th style="background-color: blue; color: white;">Description</th>
			<th style="background-color: blue; color: white;">Category</th>
			<th style="background-color: blue; color: white;">Total Stock</th>
			<th style="background-color: blue; color: white;">Supplier</th>
			<th style="background-color: blue; color: white;">Condition</th>
			<th style="background-color: blue; color: white;">Item Location</th>
			<th style="background-color: blue; color: white;">User Entry</th>
			<th style="background-color: blue; color: white;">Date Entry</th>
		</tr>
	</thead>
	<tbody>
@if(count($totalStock)>0)
		@foreach($totalStock as $key => $value)
		<tr>
			<td>{{ucwords($value->no_rkb)}}</td>
			<td>{{ucwords($value->code_desc)}}</td>
			<td>{{ucwords($value->no_po)}}</td>
			<td>{{ucwords($value->no_surat)}}</td>
			<td>{{ucwords($value->part_name)}} - {{ucwords($value->part_number)}}</td>
			<td>{{ucwords($value->item)}}</td>
			<td>{{ucwords($value->item_desc)}}</td>
			<td>{{strtoupper($value->code_category)}} ( {{ucwords($value->desc_category)}} )</td>
			<td>{{ucwords($value->stock_in)}} {{strtoupper($value->satuan)}}</td>
			<td>{{ucwords($value->supplier)}}</td>
			<td>{{ucwords($value->condition)}}</td>
			<td>{{ucwords($value->location)}}</td>
			<td>{{ucwords($value->user_entry)}}</td>
			<td><span>{{date("H:i:s , d F Y",strtotime($value->date_entry))}}</span></td>
		</tr>
		@endforeach
	@endif
	</tbody>
</table>
<script>
	window.close();
</script>