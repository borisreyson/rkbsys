@php
	$disetujui = Illuminate\Support\Facades\DB::table("user_approve")
			->join("user_login","user_login.username","user_approve.username")
			->join("department","department.id_dept","user_login.department")
			->select("user_login.*","user_approve.*","department.*")
			->where([
					["user_approve.no_rkb",$no_rkb],
					["desk","Disetujui"]
					])->first();
	$diketahui = Illuminate\Support\Facades\DB::table("user_approve")
			->join("user_login","user_login.username","user_approve.username")
			->join("department","department.id_dept","user_login.department")
			->select("user_login.*","user_approve.*","department.*")
			->where([
					["user_approve.no_rkb",$no_rkb],
					["desk","Diketahui"]
					])->first();

	$usersign = Illuminate\Support\Facades\DB::table("e_rkb_detail")
			->join("user_login","user_login.username","e_rkb_detail.user_entry")
			->select("user_login.*","e_rkb_detail.*")
			->where([
					["e_rkb_detail.no_rkb",$no_rkb]
					])->first();
@endphp
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>

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
    	}else{    		
    		document.getElementById("cover_sign").style.color = "#fff";
    		document.getElementById("cover_sign").style.display = "none";
    	}

    }
  }
}
</script>
	<style>
/* latin-ext */
/* herr-von-muellerhoff-regular - latin */
@font-face {
  font-family: 'Herr Von Muellerhoff';
  font-style: normal;
  src: url('<?php echo $_SERVER['DOCUMENT_ROOT'];?>/fonts/herr-von-muellerhoff-v7-latin-regular.eot'); /* IE9 Compat Modes */
  src: local('Herr Von Muellerhoff Regular'), local('HerrVonMuellerhoff-Regular'),
       url('<?php echo $_SERVER['DOCUMENT_ROOT'];?>/fonts/herr-von-muellerhoff-v7-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('<?php echo $_SERVER['DOCUMENT_ROOT'];?>/fonts/herr-von-muellerhoff-v7-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
       url('<?php echo $_SERVER['DOCUMENT_ROOT'];?>/fonts/herr-von-muellerhoff-v7-latin-regular.woff') format('woff'), /* Modern Browsers */
       url('<?php echo $_SERVER['DOCUMENT_ROOT'];?>/fonts/herr-von-muellerhoff-v7-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
       url('<?php echo $_SERVER['DOCUMENT_ROOT'];?>/fonts/herr-von-muellerhoff-v7-latin-regular.svg#HerrVonMuellerhoff') format('svg'); /* Legacy iOS */
}

	html,body{
		font-size:10px;
	}
		.col-lg-4{
			width: 33.33%;
			float: left;
			text-align: center;
			padding:0px!important;
			margin:0px!important;
		}
		.col-lg-12{
			width: 99%;
			float: left;
			text-align: center;
			padding:0px!important;
			margin:0px!important;
		}
		.padding-top{
			padding-top: 0px!important;
		}
		h3{
		    font-family: 'Herr Von Muellerhoff', cursive !important;
			color: #0D6CD2;
			padding-top: 0px;
			padding-bottom: 0px;
			font-size:50px;
		}
		input[type="checkbox"]{
			border:solid 1px rgba(0,0,0,0.8);
			background-color: #fff;
		}
		h3 small{
			padding: 0px;
			margin: 0px;
		}
		.no_border{
			border:solid 3px #fff;
			margin:3px!important;
		}
		.no_border_null{
			border:solid 3px #fff;
			margin:10px!important;
		}
		small{
		font-size:8px;
		}
	</style>
</head>
<body onload="subst()">

<div class="col-lg-12">
<div class="col-lg-4" id="cover_sign">
	<div><label>Diketahui </label></div>
	@if($Print_prev->diketahui=='1')
	<div class="" style="padding:0px;margin: 0px;">
		<img src="{{url('/ttd/'.$diketahui->ttd)}}" width="200px" height="120px">
	</div>
	@else
	<div class="padding-top">
		<h3 class="no_border_null">&nbsp;&nbsp;<small>&nbsp;&nbsp;</small></h3>
	</div>
	@endif
</div>
<div class="col-lg-4">
	<div><label>Disetujui </label></div>
	@if($Print_prev->disetujui=='1')
	<div class="" style="padding:0px;margin: 0px;">
		<img src="{{url('/ttd/'.$disetujui->ttd)}}" width="200px" height="120px">
	</div>
	@else
	<div class="padding-top">
		<h3 class="no_border_null">&nbsp;&nbsp;<small>&nbsp;&nbsp;</small></h3>
	</div>
	@endif
</div>
<div class="col-lg-4">
	<div class=""><label>Dibuat </label></div>
	<div class="padding-top">
		<br>
		<h3 class="no_border">{{strtok($usersign->nama_lengkap,' ')}}</h3>
	</div>
</div>
</div>

<div class="col-lg-12">
<div class="col-lg-4">
	@if($Print_prev->diketahui=='1')
	<div><b>{{$diketahui->nama_lengkap}}</b></div>
	@endif
	<div><b>Mine Manager</b></div>
	@if($Print_prev->diketahui=='1')
	<div><small>Tanggal {{tgl_indo(date("Y-m-d",strtotime($Print_prev->tgl_diketahui)))}}</small></div>
	@endif
</div>
<div class="col-lg-4">
	@if($Print_prev->disetujui=='1')
	<div><b>{{$disetujui->nama_lengkap}}</b></div>
	@endif
	<div><b>Department Head</b></div>
	@if($Print_prev->disetujui=='1')
	<div><small>Tanggal {{tgl_indo(date("Y-m-d",strtotime($Print_prev->tgl_disetujui)))}}</small></div>
	@endif
</div>
<div class="col-lg-4">
	<div class=""><b>{{$usersign->nama_lengkap}} </b></div>
	<div class=""><b>{{$Print_prev->sect}} </b></div>
	<div class=""><small>Tanggal {{tgl_indo(date("Y-m-d",strtotime($Print_prev->tgl_order)))}}</small></div>
</div>
</div>


</body>
</html>
