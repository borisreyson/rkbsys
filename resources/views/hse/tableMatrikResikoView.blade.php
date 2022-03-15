<!DOCTYPE html>
<html>
<head>
<style type="text/css">
  
table,th , td{
  text-align: center;
  border: 1px #333333 solid!important;
  white-space: nowrap;
}
table{
  border-collapse: collapse;
}
</style>
  <title>Matrik Resiko</title>
</head>
<body style="background-color: #FFFFFF;">
<!-- page content -->
<div style="overflow-x:auto;">
<table cellspacing="0" cellpadding="10" class="table table-striped table-bordered" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" colspan="2" style="vertical-align: middle;font-weight: bolder;">Kemungkinan \ Keparahan</th>
      @foreach($kpResiko as $k => $v)
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->keparahan}}</th>
      @endforeach
    </tr>
    <tr>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">&nbsp;</th>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">Nilai</th>

    @foreach($kpResiko as $k => $v)
      <th class="text-center nowrap" style="vertical-align: middle;">{{$v->nilai}}</th>
    @endforeach
    </tr>
  </thead>
  <?php
    $resiko = $hsResiko;
  ?>
  <tbody>
    @foreach($kmResiko as $k => $v)
    <tr>
      <td class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->kemungkinan}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->nilai}}</td>
    @foreach($kpResiko as $j => $w)
    <?php
      $hasil = $v->nilai*$w->nilai;
          $hsResiko = Illuminate\Support\Facades\DB::table("hse.metrik_resiko")->where("max",">=",$hasil)->where("min","<=",$hasil)->first();

    ?>
      <td class="text-center nowrap" style="vertical-align: middle;background-color: {{$hsResiko->bgColor}};color: {{$hsResiko->txtColor}};font-weight: bolder;"><b>{{$hsResiko->kodeBahaya}}</b> <small style="font-size: 10px;font-weight: bold;">{{$hasil}}</small></td>
    @endforeach
    </tr>
    @endforeach
  </tbody>
</table>
<br>
<br>
<br>
<!-- KET MATRIK -->
<table cellspacing="0" cellpadding="10" class="table table-striped table-bordered" style="width: 100%!important;">
  <thead>
    <tr class="bg-primary">
      <th class="text-center nowrap" colspan="4" style="vertical-align: middle;font-weight: bolder;">Matrik Resiko</th>
    </tr>
    <tr>
      <th class="text-center nowrap" colspan="2" rowspan="2" style="vertical-align: middle;font-weight: bolder;"></th>
      <th class="text-center nowrap" colspan="3" style="vertical-align: middle;font-weight: bolder;">Nilai Resiko = Kemungkinan x Keparahan</th>
    </tr>
    <tr>
      <th class="text-center nowrap" colspan="3" style="vertical-align: middle;font-weight: bolder;">Ket. Hasil Matrik</th>
    </tr>
    <tr>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">Kode Bahaya</th>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">Nilai</th>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">Kategori Resiko</th>
      <th class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">Tindakan yang harus dilakukan</th>
    </tr>
  </thead>
<?php $hsResiko = Illuminate\Support\Facades\DB::table("hse.metrik_resiko")->get(); ?>

  <tbody>
    @foreach($hsResiko as $k => $v)
    <tr>
      <td class="text-center nowrap" style="vertical-align: middle;font-weight: bolder; background-color: {{$v->bgColor}};color: {{$v->txtColor}};">{{$v->kodeBahaya}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;font-weight: bolder;">{{$v->min}} - {{$v->max}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;font-style: italic;">{{$v->kategori}}</td>
      <td class="text-center nowrap" style="vertical-align: middle;">{{$v->tindakan}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
</body>
</html>