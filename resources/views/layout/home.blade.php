<li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="{{url('/')}}">Main Page</a></li>
                      
                      @if($_SESSION['section']=="KABAG" || $_SESSION['section']=="SECTION_HEAD")
                      @if(isset($id_pesan))
                      <li><a href="{{url('/kabag/inbox/'.$id_pesan.'.message')}}">INBOX</a></li>
                      @else
                      <li><a href="{{url('/kabag/inbox')}}">INBOX</a></li>
                      @endif
                      @elseif($_SESSION['section']=="KTT")
                      @if(isset($id_pesan))
                      <li><a href="{{url('/ktt/inbox/'.$id_pesan.'.message')}}">INBOX</a></li>
                      @else
                      <li><a href="{{url('/ktt/inbox')}}">INBOX</a></li>
                      @endif
                      @elseif($_SESSION['section']=="PURCHASING")
                      @if(isset($id_pesan))
                      <li><a href="{{url('/logistic/inbox/'.$id_pesan.'.message')}}">INBOX</a></li>
                      @else
                      <li><a href="{{url('/logistic/inbox')}}">INBOX</a></li>
                      @endif
                      @elseif($_SESSION['level']=="administrator")
                      @if(isset($id_pesan))
                      <li><a href="{{url('/admin/inbox/'.$id_pesan.'.message')}}">INBOX</a></li>
                      @else
                      <li><a href="{{url('/admin/inbox')}}">INBOX</a></li>
                      <li><a href="{{url('/admin/all/inbox')}}">ALL INBOX</a></li>
                      @endif
                      @else
                      @if(isset($id_pesan))
                      <li><a href="{{url('/inbox/'.$id_pesan.'.message')}}">INBOX</a></li>
                      <li><a href="{{url('/sent/'.$id_pesan.'.message')}}">SENT</a></li>
                      @else
                      <li><a href="{{url('/inbox')}}">INBOX</a></li>
                      <li><a href="{{url('/sent')}}">SENT</a></li>
                      @endif
                      @endif
                    </ul>
                  </li>