<!DOCTYPE html>
<html>
<head>
	<title>Import From Excel</title>
</head>
<body>
<form method="post" enctype="multipart/form-data" action="">
	Pilih File: 
	{{csrf_field()}}
	<input name="fileExcel" type="file" required="required"> 
	<input name="upload" type="submit" value="Import">
</form>
</body>
</html>