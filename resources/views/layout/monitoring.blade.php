<li><a>OB<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
      <li class="sub_menu"><a href="{{url('/ob/daily')}}">DAILY</a>
      </li>
      <li><a href="{{url('/ob/monthly')}}">MONTHLY</a>
      </li>
      <li><a href="{{url('/ob/ach')}}">ACH</a>
      </li>
      <li class="sub_menu"><a href="{{url('/ob/delay')}}">DELAY</a>
      </li>
    </ul>
</li>
<li><a>Hauling <span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
      <li class="sub_menu"><a href="{{url('/hauling/daily')}}">DAILY</a>
      </li>
      <li><a href="{{url('/hauling/monthly')}}">MONTHLY</a>
      </li>
      <li><a href="{{url('/hauling/ach')}}">ACH</a>
      </li>
      <li class="sub_menu"><a href="{{url('/hauling/delay')}}">DELAY</a>
      </li>
    </ul>
</li>
<li><a>Crushing <span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
      <li class="sub_menu"><a href="{{url('/crushing/daily')}}">DAILY</a>
      </li>
      <li><a href="{{url('/crushing/monthly')}}">MONTHLY</a>
      </li>
      <li><a href="{{url('/crushing/ach')}}">ACH</a>
      </li>
      <li class="sub_menu"><a href="{{url('/crushing/delay')}}">DELAY</a>
      </li>
    </ul>
</li>
<li><a>Barging <span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
      <li class="sub_menu"><a href="{{url('/barging/daily')}}">DAILY</a>
      </li>
      <li><a href="{{url('/barging/monthly')}}">MONTHLY</a>
      </li>
      <li><a href="{{url('/barging/ach')}}">ACH</a>
      </li>
      <li class="sub_menu"><a href="{{url('/barging/delay')}}">DELAY</a></li>
    </ul>
</li>
<li><a href="{{url('/boat')}}">Tug Boat</a></li>
<li><a href="{{url('/stockProduct')}}">Stock</a></li>
<li><a href="{{url('/monitoring/sr/daily')}}">SR (Stripping Ratio)</a></li>
@if(isset($_SESSION['admin']))
<li>
  <a>SR<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
      <li class="sub_menu"><a href="{{url('/monitoring/sr/daily')}}">DAILY</a>
      </li>
      <li><a href="{{url('/monitoring/sr/monthly')}}">MONTHLY</a>
      </li>
    </ul>
    @endif