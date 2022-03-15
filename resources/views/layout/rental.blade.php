@if(isset($edit->id_unit))
<li><a href="{{url('/mon/unit/rental/form/unit-'.bin2hex($edit->id_unit))}}">Form Unit Rental</a></li>
@else
<li><a href="{{url('/mon/unit/rental/form/unit')}}">Form Unit Rental</a></li>
@endif
@if(isset($edit->id_hm))
<li><a href="{{url('/mon/unit/rental/form/hm-'.bin2hex($edit->id_hm))}}">Form HM Unit</a></li>
@else
<li><a href="{{url('/mon/unit/rental/form/hm')}}">Form HM Unit</a></li>
@endif