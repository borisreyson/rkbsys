          <option value="" selected="selected">--PILIH--</option>
          @foreach($dep as $k => $v)
            @if($v->id_sect!="KABAG" && $v->id_sect!="KTT" && $v->id_sect!="PURCHASING")
            @if(isset($_GET['seksi']))
            @if($v->id_sect==$_GET['seksi'])
            <option selected="selected" value="{{$v->id_sect}}">{{$v->sect}}</option>
            @else
            <option value="{{$v->id_sect}}">{{$v->sect}}</option>
            @endif
            @else
            <option value="{{$v->id_sect}}">{{$v->sect}}</option>
            @endif

            @endif
          @endforeach