<?php
$arrRULE = [];
  if(isset($getUser)){
    $arrRULE = explode(',',$getUser->rule);    
  }else{
    ?>
<script>
  window.location="{{url('/logout')}}";
</script>
    <?php } ?>
@if(in_array('hse_system',$arrRULE))
  <li><a><i class="fa fa-plus"></i>HSE <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
      <li><a href="{{url('/hse/admin/hazard/report')}}">Hazard Report</a></li>
      <li><a></i>Inspection <span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
        <li><a href="{{url('/hse/admin/inspeksi/report')}}">Inspection Report</a></li>
        <li><a href="{{url('/hse/admin/inspeksi/form')}}">Inspection Form</a></li>
      </ul>
      <li><a></i>Matrik Resiko <span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
        <li><a href="{{url('/hse/admin/matrik/hasil')}}">Hasil Matrik Resiko</a></li>
        <li><a href="{{url('/hse/admin/matrik/kemungkinan')}}">Kemungkin Matrik Resiko</a></li>
        <li><a href="{{url('/hse/admin/matrik/keparahan')}}">Keparahan Matrik Resiko</a></li>
        <li><a href="{{url('/hse/admin/matrik/table')}}">Tabel Matrik Resiko</a></li>
      </ul>
      <li><a href="{{url('/hse/admin/master/lokasi')}}">Master Lokasi</a></li>
      <!-- <li><a href="{{url('/hse/admin/master/risk')}}">Master Risk</a></li> -->
      <!-- <li><a href="{{url('/hse/admin/master/sumber/bahaya')}}">Master Sumber Bahaya</a></li> -->
    </ul>
  </li>
@endif