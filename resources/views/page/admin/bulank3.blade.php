<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bulan K3 Nasional 2022</title>
    @include('layout.css')
</head>
<body>
    <div class="container">
        <div class="col-lg-12">
            <font alignment="center">
            <h2>Klik / TAP Untuk Mendownload Gambar!</h2>
            </font>
        </div>
        <br>
        <div class="col-lg-12">
            <font alignment="center">
            <h2><a href="{{url('/bulank3.zip')}}">Klik / TAP Untuk Mendownload Semua Gambar!</a></h2>
            </font>
        </div>
        <br>
            <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row">
            <center>
<?php
    $dirname = "Bulan_K3/";
    $images = glob($dirname."*.jpg");

    foreach($images as $image) {
        ?>
        <div class="col-sm-6 col-md-3 col-lg-3" style="vertical-align: center;">
            <a href="{{$image}}" class="col-lg-12" style="background-color: white; padding:2px;margin:5px;" download="">
            <img class="thumbnail" src="{{$image}}" alt="{{$image}}">
        </a>
        </div>
        <?php
    }
    ?>
            </center>
        </div>
        </div>
    </div>
    <!-- JavaScript Bundle with Popper -->
@include('layout.js')
</body>
</html>
