<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<script>
function subst() {
  var vars={};
  var x=document.location.search.substring(1).split('&');
  for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
  var x=['frompage','topage','page','webpage','section','subsection','subsubsection'];
  for (var i in x) {
    var y = document.getElementsByClassName(x[i]);
    for (var j=0; j<y.length; ++j) {
    	y[j].textContent = vars[x[i]];
    	if(vars[x[2]]==vars[x[1]]){
    		//var a1 = document.getElementById("head_temp").style.color = "#fff";
    		//var a2 = document.getElementByTagName("img").style.display = "none";
    		//var a3 = document.getElementsByClassName("logo_right").style.display = "none";
    		//var a4 = document.getElementsByClassName("rkb_header").style.border = "solid 0px #fff!important";
    		//var a5 = document.getElementById("rkb_header").style.border = "solid 0px #fff!important";
    	}

    }
  }
}
</script>
<style>
	html,body{
		background-color: transparent!important;
		color:#000;
		margin: 0px;
		padding: 0px;
		width: 100%!important;

	 	font-size:12px!important;
	}

	.logo_right{
		position: absolute;
		top: 18px;
		left: 13px;
		width: 70px;
		height: 70px;
	}
	.rkb_header{
		border:solid 1px #000;
		padding: 0px!important;
		margin: 0px!important;
	}
	.tanda_tangan{
		position: fixed;
		bottom: 0px!important;
		width: auto;
	}
	.col-lg-12{
		width: 97%;
		padding: 5px;
		margin: 5px;
	}
	.text-center{
		text-align: center;
	}
	.pull-left{
		float: left;
	}
	.pull-right{
		float: right;
	}
	.alamat{
	 	font-size: 9.5px!important;
	}
	.rkb_header h4{
		padding: 2px!important;
		margin: 2px!important;
	}
	.table-left{
		width: auto!important;
		
	}
	.table-right{
		width: auto!important;
		
		float: right;
	}
	.table-left td,.table-right td{
		padding-right: 10px!important;
	}
</style>
	<title>Print Preview RKB</title>
	<link rel="shortcut icon" href="{{asset('abp.jpg')}}" />
</head>
<body onload="subst()">
<img src="{{url('/kopAbp.png')}}" style="width: 100%!important;margin: 0px!important;padding:0px!important">
<div id="head_temp" class="container-fluid">
	<div class="row">
		<div class="col-lg-12 text-center">
<div class="rkb_header" id="rkb_header">
	<h4><b>RENCANA KEBUTUHAN BARANG</b></h4>
</div>
<div>
	<label>NOMOR : {{($no_rkb)}}</label>
</div>
		</div>
		<div class="col-lg-12">
			<table class="pull-left table-left">
				<tr>
					<td>Tanggal </td>
					<td>:</td>
					<td>{{$tglIndo}}</td>
				</tr>
					<tr>
						<td>Dept </td><td> :</td><td nowrap> {{$Print_prev->dept}}</td>
					</tr>
					<tr>
						<td>Section </td><td> :</td><td nowrap>  {{$Print_prev->sect}}</td>
					</tr>
			</table>
				<table class="table-right" >
					<tr>
						<td>Proyek </td><td> :</td><td nowrap> Site Jembayan</td>
					</tr>
					<tr>
						<td>Halaman </td><td> :</td><td nowrap> <span class="page"></span></td>
					</tr>
				</table>
			
		</div>
	</div>
</div>
<br>
<br>
<br>
<br>
</body>
</html>