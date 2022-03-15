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
  <li><a><i class="fa fa-users"></i>Absen <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
@if(in_array('ktt absen',$arrRULE))
    <li><a href="{{url('/absen/rekap/karyawan')}}">Rekap Absen</a></li>
    @if(in_array('hge absen',$arrRULE))
    <li><a href="{{url('/absen/user/hge')}}">HCGA DEPT</a></li>
    @endif
     @if(in_array('hse absen',$arrRULE))
      <li><a href="{{url('/absen/user/hse')}}">HSE DEPT</a></li>
    @endif
     @if(in_array('enp absen',$arrRULE))
      <li><a href="{{url('/absen/user/enp')}}">ENP DEPT </a></li>
    @endif
     @if(in_array('management absen',$arrRULE))
      <li><a href="{{url('/absen/user/management')}}">Management</a></li>
    @endif
     @if(in_array('mtk absen',$arrRULE))
      <li><a href="{{url('/absen/user/mtk')}}">MTK</a></li>
    @endif
@endif

@if(in_array('kabag absen',$arrRULE))
    @if(in_array('hge absen',$arrRULE))
    <li><a href="{{url('/absen/kabag/hge')}}">HCGA DEPT</a></li>
    @endif
     @if(in_array('hse absen',$arrRULE))
      <li><a href="{{url('/absen/kabag/hse')}}">HSE DEPT</a></li>
    @endif
     @if(in_array('enp absen',$arrRULE))
      <li><a href="{{url('/absen/kabag/enp')}}">ENP DEPT </a></li>
    @endif
@endif

@if(in_array('user absen',$arrRULE))
@if(in_array('admin absen',$arrRULE))
    <li><a>Karyawan <span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
        <li><a href="{{url('/dept')}}">Department</a></li>
        <li><a href="{{url('/sect')}}">Section</a></li>
        <li><a href="{{url('/data/karyawan/admin')}}">Data Karyawan</a></li>
      </ul>
    </li>
    <li><a>Roster Kerja <span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
      <li><a href="{{url('/absen/user/kode/jam/roster')}}">Kode Jam Kerja</a></li>
      <li><a href="{{url('/absen/roster/karyawan')}}">Buat Roster</a></li>
      <li><a href="{{url('/absen/roster/karyawan/lihat')}}">Lihat Roster</a></li>
    </ul>
  </li>     
  @endif 
  <li><a href="{{url('/absen/rekap/karyawan')}}">Rekap Absen</a></li>
    @if(in_array('hge absen',$arrRULE))
    <li><a href="{{url('/absen/user/hge')}}">HCGA DEPT</a></li>
    @endif
     @if(in_array('hse absen',$arrRULE))
      <li><a href="{{url('/absen/user/hse')}}">HSE DEPT</a></li>
    @endif
     @if(in_array('enp absen',$arrRULE))
      <li><a href="{{url('/absen/user/enp')}}">ENP DEPT </a></li>
    @endif
     @if(in_array('management absen',$arrRULE))
      <li><a href="{{url('/absen/user/management')}}">Management</a></li>
    @endif
     @if(in_array('mtk absen',$arrRULE))
      <li><a href="{{url('/absen/user/mtk')}}">MTK</a></li>
    @endif
      <li><a href="{{url('/absen/user/error')}}">Error</a></li>

@endif
                  </li>
                    </ul>
                  </li>