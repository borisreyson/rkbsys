<link rel="stylesheet" type="text/css" href="http://rkb.it/css/app.css">
<script type="text/javascript" src="http://rkb.it/js/app.js"></script>
<style>
	/*
	#konten{
		display: flex;
		flex-wrap: wrap;
    	justify-content: space-around;
	}
	img{
		border:1px solid black;
		width: 15%;
		height: 150px;
		margin-bottom: 25px;
	}
	*/
</style>
<div id="konten">
<?php
/*
$dir = "images/";

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
    	?>
      <img class="thumbnail" src="/images/<?=$file;?>" >
      <?php
    }
    closedir($dh);
  }
}*/
?>
</div>
<ul id="teks">
</ul>
<script>
	$(document).ready(function() {

var dataRes;
		$.ajax({
			type:"GET",
			url:"http://rkb.it/test/datajson",
			success:function(res){
				$.each(res ,function(k,v){
					$("#teks").append("<li>"+v.username+"</li>");
					//console.log(v.username);
				});
			}
		});
	});
</script>