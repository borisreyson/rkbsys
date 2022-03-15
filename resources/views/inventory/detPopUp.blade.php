<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{asset('/js/app.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/css/app.css')}}">
@if(isset($popAll))
<div class="container">
	<br>
	<div class="col-lg-12">
<form method="get" action="">		
<div class="input-group">
<input id="no_rkb" type="text" class="form-control col-md-7 col-xs-12" name="no_rkb" placeholder="Nomor Rkb" required="required" >
<span class="input-group-btn">
                <button type="submit" class="btn btn-primary" id="cariNewTab">Go!</button>
            </span>
</div>
</form>
	</div>
	<hr>
</div>
@endif
<table class="table">
	<thead>
		<tr>
			<th># @if(isset($popAll)) No RKB @endif</th>
			<th>Part Name</th>
			<th>Part Number</th>
			<th>Quantity</th>
			<th>User</th>
		</tr>
	</thead>
	<tbody>
	@if(is_array($GetRKB))
	@foreach($GetRKB as $k => $v)
		<tr>
			<td><input type="checkbox" id="{{$v->part_name}}" name="part_name" part-name="{{$v->part_name}}" NO-RKB="{{$v->no_rkb}}" value="{{$v->part_name}}">
				@if(isset($popAll))
			 <label for="{{$v->part_name}}">{{$v->no_rkb}}</label>
			 @endif
			</td>
			<td>{{$v->part_name}}</td>
			<td>{{$v->part_number}}</td>
			<td>{{$v->quantity}}</td>
			<td>{{$v->user_entry}}</td>
		</tr>
	@endforeach
	@else
		<tr>
			<td colspan="5" style="text-align: center;">No Data Record!</td>
		</tr>
	@endif
	</tbody>
</table>
<hr>
<div class="container">
<div class="pull-right">
<button class="btn btn-primary " name="ok" id="ok" type="button">Ok</button>
<button class="btn btn-default " onclick="window.close()" type="button">Close</button>
</div>
<br>
<br>
</div>

<script>
var n;
	var countChecked = function() {
  		n = $( "input:checked" ).length;
  		if(n<1){
  			$("button[id=ok]").attr("disabled","disabled").addClass("disabled");
  		}else{
  			$("button[id=ok]").removeAttr("disabled").removeClass("disabled");
  		}
	};
countChecked();
 
$( "input[type=checkbox]" ).on( "click", function(){
	countChecked();
					});

	$("button[id=ok]").click(function(){
		var len = $( "input[type=checkbox]" );
		var resString=[];
		var NoRKB;
		if(len.length>0){
			var resStr = len.length + "c checkbox(s) checked <br>";
			len.each(function(){
				resString.push($(this).val());
				NoRKB=$(this).attr("NO-RKB");
			});

			parentData = window.opener.setData(resString,NoRKB);
			window.close();
		}else{
			console.log("No Checked");
			return false;
		}
		
	});
</script>