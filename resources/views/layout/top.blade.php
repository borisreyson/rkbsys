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
 <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{asset('/abp_100x97.png')}}" alt="">
                {{$getUser->nama_lengkap or $getUser->username}}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="{{url('/form_user/'.bin2hex($_SESSION['username']).'.html')}}"> Edit Account</a></li>
                    <li><a href="{{url('/form_user/'.bin2hex($_SESSION['username']).'.password')}}"> Change Password</a></li>
                    @if($_SESSION['section']=="KTT"||$_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
                    @if($_SESSION['level']!="PLT")
                    <li><a href="{{url('/form_user/'.bin2hex($_SESSION['username']).'.plt')}}"> User PLT</a></li>
                    @endif
                    @endif
<!--
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
-->
                    <li><a href="{{asset('/logout')}}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <!-- pesan -->
                @php
                $message = Illuminate\Support\Facades\DB::table('notification')
                            ->leftJoin("user_login","user_login.username","notification.user_send")
                            ->where([
                                      ["user_notif",$_SESSION['username']],
                                      ["flag",0]
                                      ])
                            ->orderBy("timelog","desc")
                            ->limit(5)
                            ->get();
                $bell = Illuminate\Support\Facades\DB::table('status_inv')
                            ->where([
                                      ["user_notif",$_SESSION['username']],
                                      ["flag",0]
                                      ])
                            ->orderBy("timelog","desc")
                            ->limit(5)
                            ->get();
                @endphp

@if($_SESSION['section']!="BOD")

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" id="notifOpen" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o "></i>
                    <span class="badge bg-green" id="numberNotif">@if(count($message)>0){{count($message)}}@endif</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
@if(count($message)>0)
                @foreach($message as $k => $v)
@if($_SESSION['section']=="KTT")
                    <li id="notifList">
                      <a href="{{url('/ktt/inbox/'.bin2hex($v->idNotif))}}.message">
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>{{$v->nama_lengkap}}</span>
                          <span class="time">{{date("h:i A, M d",strtotime($v->timelog))}}</span>
                        </span>
                        <span class="message">
                          <input type="hidden" id="idNotif" name="idNotif[]" value="{{$v->idNotif}}">
                            <?php if(strlen($v->notif)>100){ ?>
                              <?php echo strip_tags(substr($v->notif,0,100),'<br>');?> ...
                            <?php }else{ ?> 
                              {!!$v->notif!!}
                            <?php } ?>
                        </span>
                      </a>
                    </li>
@elseif($_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
                    <li id="notifList">
                      <a href="{{url('/kabag/inbox/'.bin2hex($v->idNotif))}}.message">
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>{{$v->nama_lengkap}}</span>
                          <span class="time">{{date("h:i A, M d",strtotime($v->timelog))}}</span>
                        </span>
                        <span class="message">
                          <input type="hidden" id="idNotif" name="idNotif[]" value="{{$v->idNotif}}">
                            <?php if(strlen($v->notif)>100){ ?>
                              <?php echo strip_tags(substr($v->notif,0,100),'<br>');?> ...
                            <?php }else{ ?> 
                              {!!$v->notif!!}
                            <?php } ?>
                        </span>
                      </a>
                    </li>
@elseif($_SESSION['level']=="administrator")
                    <li id="notifList">
                      <a href="{{url('/admin/inbox/'.bin2hex($v->idNotif))}}.message">
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>{{$v->nama_lengkap}}</span>
                          <span class="time">{{date("h:i A, M d",strtotime($v->timelog))}}</span>
                        </span>
                        <span class="message">
                          <input type="hidden" id="idNotif" name="idNotif[]" value="{{$v->idNotif}}">
                            <?php if(strlen($v->notif)>100){ ?>
                              <?php echo strip_tags(substr($v->notif,0,100),'<br>');?> ...
                            <?php }else{ ?> 
                              {!!$v->notif!!}
                            <?php } ?>
                        </span>
                      </a>
                    </li>
@elseif($_SESSION['section']=="PURCHASING")
                    <li id="notifList">
                      <a href="{{url('/logistic/inbox/'.bin2hex($v->idNotif))}}.message">
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>{{$v->nama_lengkap}}</span>
                          <span class="time">{{date("h:i A, M d",strtotime($v->timelog))}}</span>
                        </span>
                        <span class="message">
                          <input type="hidden" id="idNotif" name="idNotif[]" value="{{$v->idNotif}}">
                            <?php if(strlen($v->notif)>100){ ?>
                              <?php echo strip_tags(substr($v->notif,0,100),'<br>');?> ...
                            <?php }else{ ?> 
                              {!!$v->notif!!}
                            <?php } ?>
                        </span>
                      </a>
                    </li>
@else
                    <li id="notifList">
                      <a href="{{url('/inbox/'.bin2hex($v->idNotif))}}.message">
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>{{$v->nama_lengkap}}</span>
                          <span class="time">{{date("h:i A, M d",strtotime($v->timelog))}}</span>
                        </span>
                        <span class="message">
                          <input type="hidden" id="idNotif" name="idNotif[]" value="{{$v->idNotif}}">
                            <?php if(strlen($v->notif)>100){ ?>
                              <?php echo strip_tags(substr($v->notif,0,100),'<br>');?> ...
                            <?php }else{ ?> 
                              {!!$v->notif!!}
                            <?php } ?>
                        </span>
                      </a>
                    </li>
@endif
@endforeach
@else
                    <li id="nothing">
                        <span class="message ">
                          <center>
                          Nothing!
                          </center>
                        </span>
                    </li>
@endif
                    <li>
                      <div class="text-center">

                        @if($_SESSION['section']=="KTT")
                        <a href="{{url('/ktt/inbox')}}">
                          <strong>See All Inbox</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                        @elseif($_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
                        <a href="{{url('/kabag/inbox')}}">
                          <strong>See All Inbox</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                        @elseif($_SESSION['section']=="PURCHASING")
                        <a href="{{url('/logistic/inbox')}}">
                          <strong>See All Inbox</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                        @elseif($_SESSION['level']=="administrator")
                        <a href="{{url('/admin/inbox')}}">
                          <strong>See All Inbox</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                        @else

                        <a href="{{url('/inbox')}}">
                          <strong>See All Inbox</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                        @endif
                      </div>
                    </li>
                  </ul>
                </li>
                @endif
                <!-- pesan -->
                @if(in_array('notif inventory',$arrRULE))   
    <li role="presentation" class="dropdown">
        <a href="javascript:;" id="" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell-o "></i>
                    <span class="badge bg-green" id="numberNotif">@if(count($bell)>0){{count($bell)}}@endif</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    @if(count($bell)>0)
                @foreach($bell as $k => $v)
                    <li id="notifList">
                      @if($v->item!="")
                      <a href="{{url('/inventory/stock?cari='.($v->item).'&notif=open')}}">
                      @else
                      <a href="{{url('/masteritem/request/detail/log')}}?token={{$v->idStatus}}">
                      @endif
                        <span>
                          <span>{{$v->user_send}}</span>
                          <span class="time">{{date("h:i A, M d",strtotime($v->timelog))}}</span>
                        </span>
                        <span class="message">
                          <input type="hidden" id="idNotif" name="idNotif[]" value="{{$v->idStatus}}">
                            <?php if(strlen($v->notif)>100){ ?>
                              <?php echo strip_tags(substr($v->notif,0,100),'<br>');?> ...
                            <?php }else{ ?> 
                              {!!$v->notif!!}
                            <?php } ?>
            </span>
         </a>
      </li>
@endforeach
@else
                    <li id="nothing">
                        <span class="message ">
                          <center>
                          Nothing!
                          </center>
                        </span>
                    </li>
<li> 
<div class="text-center">
  <a href="{{url('/inbox')}}">
    <strong>See All Notification</strong>
    <i class="fa fa-angle-right"></i>
  </a>
</div>
</li>
                    </ul>
                  </li>
     @endif
@endif
              </ul>
            </nav>
          </div>
        </div>
