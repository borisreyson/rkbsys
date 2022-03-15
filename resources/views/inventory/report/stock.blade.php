
<?php
header("Content-type: application/vnd-ms-excel");
 
//Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Stock.xls");
?>
<table cellpadding="4" cellspacing="0" >
	<thead >
	<tr>
		<th style="border:0px!important;" colspan="5">Stock Opname</th>
	</tr>
		<tr>
			<th style="background-color: blue; color: white;border:1px #000 solid;">Item</th>
			<th style="background-color: blue; color: white;border:1px #000 solid;">Description</th>
			<th style="background-color: blue; color: white;border:1px #000 solid;">Category</th>
			<th style="background-color: blue; color: white;border:1px #000 solid;">Total Stock</th>
			<th style="background-color: blue; color: white;border:1px #000 solid;">Timelog</th>
		</tr>
	</thead>
	<tbody>
@if(count($totalStock)>0)
		@foreach($totalStock as $key => $value)
		<tr>
			<td style="border:1px #000 solid;">{{ucwords($value->item)}}</td>
			<td style="border:1px #000 solid;">{{ucwords($value->item_desc)}}</td>
			<td style="border:1px #000 solid;">{{strtoupper($value->code_category)}} ( {{ucwords($value->desc_category)}} )</td>
			<td style="border:1px #000 solid;">{{ucwords($value->stock_total)}} {{strtoupper($value->satuan)}}</td>
			<td style="border:1px #000 solid;"><span>{{date("H:i:s , d F Y", strtotime($value->timelog))}}</span></td>
		</tr>
		@endforeach
	@endif
	</tbody>
</table>
<script>
	//window.close();
</script>