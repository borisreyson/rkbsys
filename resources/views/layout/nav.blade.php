<!-- default background #2A3F54-->
<style>
  @font-face {
    font-family: colonna_mt;
    src: url("{{asset('fonts/colonna_mt.ttf')}}");
}

body {
    background: rgba(0,0,0,0.9)!important;
  }
.e_rkb{
 font-family: colonna_mt;
 font-size: 35px;
}
.e_font{
 font-family: colonna_mt;
 font-size: 50px;
}
.left_col {
  background: rgba(0,0,0,0)!important;

}
.nav_title {
  background-color: rgba(0,0,0,0)!important;
}

.nav.side-menu>li.active>a {
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6)), rgba(0,0,0,0.1)!important;
    color: #EBA40E!important;
}
.nav-sm ul.nav.child_menu {
    background: rgba(0,0,0,1)!important;
  }
.sidebar-footer{
    background: rgba(0,0,0,1)!important;
}
.sidebar-footer a{
  color: rgba(255,255,255,0.9)!important;
    background: rgba(0,0,0,0.5)!important;
}
.sidebar-footer a:hover{
  background: rgba(78,94,92,0.5)!important;

  color: rgba(255,255,255,1)!important;
}
.menu_section {
  z-index: 1!important;
}
</style>

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
<div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{url('https://bit.ly/2VWCDfb')}}" class="site_title">
               <span class="e_font">ABP</span><span class="e_rkb"> System</span></a>

            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{asset('/abp_100x97.png')}}" alt="..." class="img-circle profile_img" >

              </div>
              <div class="profile_info">
                <span>Selamat Datang,</span>
                <h2>{{$getUser->nama_lengkap or $getUser->username}} </h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
@if($_SESSION['section']!="BOD")
<!--bukan BOD-->

                  @include('layout.home',["getUser"=>$getUser])

                  @if(!($_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD" || $_SESSION['section']=="KTT"))
@if(in_array('form',$arrRULE))
                  <li><a><i class="fa fa-edit"></i> Forms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">

@if(in_array('rkb',$arrRULE))
                      <li><a href="{{url('/v1/form_rkb')}}">RKB</a></li>
@endif

@if(in_array('master request',$arrRULE))
                      <li><a href="{{url('/masteritem/request')}}">Master Item Request</a></li>
@endif


@if(in_array('monitoring',$arrRULE))
<li><a>Monitoring Produksi<span class="fa fa-chevron-down"></span></a>
  <ul class="nav child_menu">
@if(in_array('admin abp',$arrRULE))
      @include('layout.abp',["getUser"=>$getUser,"arrRULE"=>$arrRULE])
@endif
@if(in_array('admin mhu1',$arrRULE))
    <li><a>Produksi PT. MHU<span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
      @include('layout.mhu',["getUser"=>$getUser])
      </ul>
    </li>
@endif


@if(in_array('unit rental',$arrRULE))
      @include('layout.rental',["getUser"=>$getUser])
@endif


  </ul>
</li>
                      @endif
                    </ul>
                  </li>
@endif
                  @endif
                  <li><a><i class="fa fa-clone"></i>RKB <span class="fa fa-chevron-down"></span></a>

                    <ul class="nav child_menu">
                      @if($_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
                      <li><a href="{{url('/kabag/rkb')}}">Rkb</a></li>
                      @if($_SESSION['department']=="hrga")
                      <li><a href="{{url('/kabag/alldept/rkb')}}">Rkb All Dept</a></li>
                      @elseif($_SESSION['department']=="enp" && $_SESSION['section']=="KABAG")
                      <!-- <li><a href="{{url('/kabag/mtk/rkb')}}">Rkb MTK</a></li> -->
                      @endif
                      <li><a href="{{url('/kabag/rkbPrint')}}">Print Rkb</a></li>
                      @elseif($_SESSION['section']=="KTT")
                      <li><a href="{{url('/ktt/rkb')}}">Rkb</a></li>
                      <li><a href="{{url('/ktt/rkbPrint')}}">Print Rkb</a></li>
                      @elseif($_SESSION['section']=="PURCHASING")
                        @if($_SESSION['perusahaan']=='0')
                        <li><a href="{{url('/logistic/rkb')}}">Rkb</a></li>
                        <li><a href="{{url('/logistic/rkbPrint')}}">Print Rkb</a></li>
                        @else
                        <li><a href="{{url('/mtk/rkb')}}">Rkb</a></li>
                        <li><a href="{{url('/mtk/rkbPrint')}}">Print Rkb</a></li>
                        @endif
                      @elseif($_SESSION['level']=="administrator")
                      <li><a href="{{url('/admin/rkb')}}">Rkb</a></li>
                      @if($_SESSION['department']=="hrga")
                      <li><a href="{{url('/kabag/alldept/rkb')}}">Rkb All Dept</a></li>
                      @endif
                       @if($_SESSION['level']=="administrator")
                      <li><a href="{{url('/admin/printRkb')}}">Print Rkb</a></li>
                      @else
                      <li><a href="{{url('/printRkb')}}">Print Rkb</a></li>
                      @endif
                      @else

                      @if(in_array('gudang logistic',$arrRULE))
                      <li><a href="{{url('/kabag/alldept/rkb')}}">Rkb All Dept</a></li>
                      @endif
                      <li><a href="{{url('/v3/rkb')}}">Rkb</a></li>
                      <li><a href="{{url('/printRkb')}}">Print Rkb</a></li>
                      @endif
                    </ul>
                  </li>
                  @if($_SESSION['section']=="IT" || $_SESSION['section']=="PURCHASING")
                  <li><a><i class="fa fa-desktop"></i>Master <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      @if($_SESSION['section']=="IT")
                      <li><a href="{{url('/tulis/pesan')}}">Tulis Pesan</a></li>
                      <li><a href="{{url('/dept')}}">Department</a></li>
                      <li><a href="{{url('/sect')}}">Section</a></li>
                      <li><a href="{{url('/user')}}">User</a></li>
                      <li><a href="{{url('/manage/users')}}">Users</a></li>
                      <li><a href="{{url('/level/user')}}">Level User</a></li>
                      <li><a href="{{url('/rule/user')}}">Rule User</a></li>
                      <li><a href="{{url('/data/karyawan/admin')}}">Data Karyawan</a></li>
                      <li><a href="{{url('/import/abp/data/karyawan')}}">Form Data Karyawan</a></li>
                      @endif
                      <li><a href="{{url('/satuan')}}">Satuan</a></li>
                    </ul>
                  </li>
                      @endif

@if(in_array('menu sarpras',$arrRULE))
@include('layout.sarana',["getUser"=>$getUser])
@endif
@if(in_array('menu inventory',$arrRULE))
                  <li><a><i class="fa fa-database"></i>Inventory <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
@if(in_array('mtk_master_item',$arrRULE))
                      <li><a href="{{url('/inventory/master')}}">Master Item</a></li>
@endif
@if(in_array('admin inventory',$arrRULE))
                      <li><a href="{{url('/admin/inventory/category')}}">Category</a></li>
                      <li><a href="{{url('/admin/inventory/condition')}}">Condition</a></li>
                      <li><a href="{{url('/admin/inventory/location')}}">Location</a></li>
                      <li><a href="{{url('/admin/inventory/method')}}">Method</a></li>
                      <li><a href="{{url('/inventory/suplier')}}">Vendor</a></li>
                      <li><a href="{{url('/inventory/master')}}">Master Item</a></li>
                      <li><a href="{{url('/inventory/stock')}}">Stock</a></li>
                      <li><a href="{{url('/check/stock/out')}}">Stock Out</a></li>
                      <li><a href="{{url('/satuan')}}">Satuan</a></li>
                      <li><a href="{{url('/masteritem/request/detail/log')}}">Request Master Item</a></li>
@if(in_array('filter inventory',$arrRULE))
                      <li><a href="{{url('/logistic/stock/adjust')}}">Adjust Stock</a></li>
                      @endif
@endif

@if(in_array('user inventory',$arrRULE))
                      <li><a href="{{url('/inventory/user/stock')}}">Stock</a></li>

@endif
@if(in_array('req table inventory',$arrRULE))
<li><a href="{{url('/masteritem/request/detail')}}">Request Master Item</a></li>
@endif
@if(in_array('dept inventory',$arrRULE))
                      <li><a href="{{url('/inventory/user/stock')}}">Stock</a></li>
@endif
                    </ul>
                  </li>
                  @endif
@else

                  <li>
                    <a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu">
                      <li><a href="{{url('https://bit.ly/2VWCDfb')}}">Main Page</a></li>
                    </ul>
                  </li>
@endif
<!--bukan BOD-->
@if($_SESSION['department']!="mtk")
<li><a><i class="fa fa-table"></i>Monitoring Produksi<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
                      @include('layout.monitoring',["getUser"=>$getUser])
                  @if(in_array('adminIT',$arrRULE))
                  <li><a><i class="fa fa-table"></i>Produksi PT. MHU<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      @include('layout.mrMHU',["getUser"=>$getUser])
                    </ul>
                  </li>
                  @endif
                  @include('layout.mrRental',["getUser"=>$getUser])
</ul>
</li>
@endif
@if(in_array('menu absen',$arrRULE))
@include('layout.absen',["getUser"=>$getUser])
@endif
@include('layout.hse',["getUser"=>$getUser])
@if(in_array('pesan informasi',$arrRULE))
<li><a href="{{url('/tulis/pesan')}}"><i class="fa fa-paper-plane"></i>Kirim Informasi</a></li>
@endif
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" class="pull-right" data-placement="top" title="Logout" href="{{asset('/logout')}}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>
