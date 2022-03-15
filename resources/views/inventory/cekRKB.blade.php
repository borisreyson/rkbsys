<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{asset('/js/app.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/css/app.css')}}">
<style>
	.footer{
		position: fixed;
		bottom:0px;
		background-color: rgba(0,0,0,0.5);
		width: 100%!important;
		padding: 10px; 
	}
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<table class="table">
				<tbody>
					@foreach($noRkb as $k => $v)
					<tr>
						<td>
							<input type="checkbox" name="no_rkb" id="no_rkb" value="{{$v}}" rkbID="{{ bin2hex($v) }}">
						</td>
						<td>
							<div>{{$v}}</div>
							<div>
								<table border="0" cellpadding="1" cellspacing="1" class="table">
									@foreach($detRKB as $n => $nn)
									@if($nn==$v)
									<tr>
										<td>
											<input type="checkbox" name="part_name" id="part_name" no_rkb="{{bin2hex($v)}}" value="{{ ($items[$n]) }}">
										</td>
										<td>{{$items[$n]}}</td>
										<td>{{$qty[$n]}}</td>
									</tr>
									@endif
									@endforeach
								</table>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="footer">
	<div class="container-fluid">
		<div class="row col-lg-12">
			<button class="btn btn-primary pull-right" name="submitCheck">Submit</button>
		</div>
	</div>
</div>



<script>

oldVal=null;

$('input[id=part_name]').on('change', function() {
	eq = $('input[id=part_name]').index(this);
	eq = $('input[id=part_name]').index(this);
	
	$("input[no_rkb="+myVal+"]")
});

$('input[id=no_rkb]').on('change', function() {
    $('input[id=no_rkb]').not(this).prop('checked', false); 
    eq = $('input[id=no_rkb]').index(this);

    chk = $('input[id=no_rkb]').not(this).prop("checked");
    if(chk==false){
    	chk1 = $('input[id=no_rkb]').eq(eq).prop("checked");
    	if(chk1==true){

				$("input[id=part_name]").attr('checked', false);
			    myVal = $('input[id=no_rkb]').eq(eq).attr("rkbID");
			    $("input[no_rkb="+myVal+"]").attr('checked', true);
	    	}else{
			    myVal = $('input[id=no_rkb]').eq(eq).attr("rkbID");
			    $("input[no_rkb="+myVal+"]").attr('checked', false);
    	}
	}else if(chk==true){
		$("input[id=part_name]").attr('checked', false);
	    myVal = $('input[id=no_rkb]').eq(eq).attr("rkbID");
	    $("input[no_rkb="+myVal+"]").attr('checked', false);
	}
         
});
function getCheckedCheckboxesFor(checkboxName) {
    var checkboxes = document.querySelectorAll('input[name="' + checkboxName + '"]:checked'), values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
    return values;
}
$("button[name=submitCheck]").click(function(){
	dataku = getCheckedCheckboxesFor("part_name");
	alert(dataku);
});
</script>