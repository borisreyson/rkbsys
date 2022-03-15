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
    	}

    }
  }
}
</script>
<style>
	html,body{
		background-color: transparent!important;
		color:#000;
		margin: 0px!important;
		padding: 0px!important;
		width: 100%!important;

	 	font-size:12px!important;
	}
	

	.tanda_tangan{
		position: fixed;
		bottom: 0px!important;
		width: auto;
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
	.col-lg-12{
		width: 97%;
		padding: 5px;
		margin: 5px;
	}
	.col-lg-6{
		width: 47%;
		padding: 5px;
		margin: 5px;
		float: left!important;
	}

	.row{
		width: 100%;
		padding: 0px;
		margin: 0px;
	}
</style>
	<title>Print Preview RKB</title>
	<link rel="shortcut icon" href="{{asset('abp.jpg')}}" />
</head>
<body onload="subst()">

<div id="head_temp" class="container-fluid" style="margin:0px!important;padding: 0px!important;">
	<img src="{{url('/kopAbp.png')}}" style="width: 100%!important;margin: 0px!important;">
</div>
<br>
<br>
<br>
<br>
</body>
</html>