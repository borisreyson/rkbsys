@extends('layout.master')
@section('title')
ABP-system | Inbox
@endsection
@section('css')
    <!-- bootstrap-wysiwyg -->
 @include('layout.css')
    <link href="{{asset('/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
<style>
.ui-autocomplete { position: absolute; cursor: default;z-index:9999 !important;height: 100px;

            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            }  

.ck-editor__editable {
    min-height: 90px;
}
</style>
@endsection
@section('content')
<body class="nav-md">
<div class="container body">
<div class="main_container">
@include('layout.nav',["getUser"=>$getUser])
@include('layout.top',["getUser"=>$getUser])

<!-- page content -->

<div class="right_col" role="main">


            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Inbox<small>User Mail</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="row">
                      <div class="col-sm-3">
                        <button id="compose" class="btn btn-sm btn-success btn-block" type="button">COMPOSE</button>
@foreach($inbox as $k => $v)    
                        <a href="{{url('/kabag/inbox/'.bin2hex($v->id_pesan))}}.message">
                          <div class="">
                            <div class="left">
                              @if($v->flag_message==0)
                              <i class="fa fa-envelope"></i> 
                              @else
                              <i class="fa fa-envelope-o"></i> 
                              @endif
                            </div>
                            <div class="right">
                              <h3>{{$v->nama_lengkap}} <small>{{date("h.i A",strtotime($v->timelog))}}</small></h3>
                              <b>{{ucfirst($v->subjek)}}</b>
                              <p>{{$v->no_rkb}}</p>
                              <p>{{$v->part_name}}</p>
                            <?php if(strlen($v->pesan_teks)>100){ ?>
                              {!!substr($v->pesan_teks,0,100)!!} ...
                            <?php }else{ ?> 
                              {!!$v->pesan_teks!!}
                            <?php } ?>
                            </div>
                          </div>
                        </a>
@endforeach
<div>{{$inbox->links()}}</div>
                      </div>
                      <!-- /MAIL LIST -->

                      <!-- CONTENT MAIL -->
                      @if(isset($pesan))
                      <div class="col-sm-6 ">
<input type="hidden" name="user_reply" id="user_reply" value="{{$pesan->nama_lengkap}}">
<input type="hidden" name="username_reply" id="username_reply" value="{{$pesan->user_from}}">
<input type="hidden" name="norkb_reply" id="norkb_reply" value="{{$pesan->no_rkb}}">
<input type="hidden" name="partname_reply" id="partname_reply" value="{{$pesan->part_name}}">
<input type="hidden" name="sub_reply" id="sub_reply" value="{{$pesan->subjek}}">
<input type="hidden" name="message_reply" id="message_reply" value="{{$pesan->pesan_teks}}">
<input type="hidden" name="timelog_reply" id="timelog_reply" value="{{date('D , M d, Y ',strtotime($pesan->timelog)).' at '.date('h:i A ',strtotime($pesan->timelog))}}">
                      @endif
                      <!-- /CONTENT MAIL -->
                      <div class="col-lg-12">                        
                              <div class="btn-group">
                                <button class="btn btn-sm btn-primary" type="button" id="reply"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                              </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>



@include('layout.footer')


    <!-- compose -->
    <div class="compose col-md-6 col-xs-12">
      <div class="compose-header">
        New Message
        <button type="button" class="close compose-close">
          <span>×</span>
        </button>
      </div>
      <div class="compose-body">
<form class="form-horizontal" action="" method="post">
<div id="alerts"></div><br>
<div class="form-group">
  <label class="col-md-2 col-xs-12"  for="user_to">
    <div class="form-control-static " >To</div>
  </label>
  <div class="col-md-10  col-xs-12">
  <input type="text" name="user_to" id="user_to" class="form-control" placeholder="To"  required>
  <input type="hidden" name="username_to" id="username_to" class="form-control" required>
  </div>
</div>
<div class="form-group">
  <label class="col-md-2  col-xs-12"  for="user_to">
    <div class="form-control-static " >No RKB</div>
  </label>
  <div class="col-md-10 col-xs-12">
  <input type="text" name="no_rkb" id="no_rkb" class="form-control" placeholder="Nomor RKB">
  </div>
</div>
<div class="form-group">
  <label class="col-md-2  col-xs-12"  for="user_to">
    <div class="form-control-static " >Part Name</div>
  </label>
  <div class="col-md-10 col-xs-12">
  <input type="text" name="part_name" id="part_name" class="form-control" placeholder="Part Name">
  </div>
</div>
<div class="form-group">
  <label class="col-md-2 col-xs-12 "  for="user_to">
    <div class="form-control-static " >Subjek</div>
  </label>
  <div class="col-md-10 col-xs-12">
  <input type="text" name="subjek" id="subjek" class="form-control" placeholder="Subkjek">
  </div>
</div>
<div class="form-group">
  <div class="col-md-12 col-xs-12">
  <textarea type="text" name="message" id="message" rows="3" class="form-control" placeholder="Pesan"></textarea>
  </div>
</div>
{{csrf_field()}}
<input type="hidden" name="tree" id="tree">
<div class="compose-footer" style="">
  <button id="send" class="btn btn-sm btn-success" type="submit">Send</button>
  <button id="reset" class="btn btn-sm btn-default" type="reset">Reset</button>
</div>
</form>
    </div>
</div>
    <!-- /compose -->
</div>
@endsection

@section('js')
<!-- Datatables -->
    <!-- FastClick -->


@include('layout.js')
    <script src="{{asset('/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="{{asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <script src="{{asset('/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
    <script src="{{asset('/vendors/google-code-prettify/src/prettify.js')}}"></script>
    <script src="{{asset('/ckeditor/ckeditor.js')}}"></script>

    <script>
      var editor1 = new CKEDITOR.replace( 'message' );
      $("#pictureBtn").click(function(){
        $("#img_file").click();
      });
      $("#compose").click(function() {
        $("#tree").val("parent");
        
      });
      $("#reply").click(function() {
        reply_to = $("#user_reply").val();
        reply_user = $("#username_reply").val();
        norkb_reply = $("#norkb_reply").val();
        partname_reply = $("#partname_reply").val();
        sub_reply = $("#sub_reply").val();
        message_reply = $("#message_reply").val();
        timelog_reply = $("#timelog_reply").val();

        $("#user_to").val(reply_to);
        $("#username_to").val(reply_user);    
        $("#no_rkb").val(norkb_reply);    
        $("#part_name").val(partname_reply);    
        $("#subjek").val(sub_reply);    
        var html_reply = "On "+timelog_reply+" PM "+reply_to+" wrote:";
        CKEDITOR.instances.message.setData("<p>--</p>"+html_reply+"<br>"+message_reply);
      });
      $(".close").click(function() {
        $("#reset").click();
      });

    function init_reply() {
    
      if( typeof ($.fn.slideToggle) === 'undefined'){ return; }
      console.log('init_compose');
      $('#reply').click(function(){
        $('.compose').slideToggle();
      });
      
    
    };
init_reply();
      $("#user_to").autocomplete({
          source: function( request, response ) {
                  $.ajax( {
                    url: "{{url('/get/username')}}",
                    dataType: "json",
                    data: {
                      term: request.term
                    },
                    success: function( data ) {
                      response( data );
                    }
                  } );
                },
          minLength: 2,
          select: function( event, ui ) {
            $("#username_to").val( ui.item.id);
          }
        });
      $("#no_rkb").autocomplete({
          source: function( request, response ) {
                  $.ajax( {
                    url: "{{url('/get/nomor/rkb')}}",
                    dataType: "json",
                    data: {
                      term: request.term
                    },
                    success: function( data ) {
                      response( data );
                    }
                  } );
                },
          minLength: 2,
          select: function( event, ui ) {
            //$("#username_to").val( ui.item.id);
          }
        });
      $("#part_name").autocomplete({
          source: function( request, response ) {
              no_rkb = $("#no_rkb").val();
                  $.ajax( {
                    url: "{{url('/get/part/name')}}",
                    dataType: "json",
                    data: {
                      term: request.term,
                      no_rkb:no_rkb
                    },
                    success: function( data ) {
                      response( data );
                    }
                  } );
                },
          minLength: 2,
          select: function( event, ui ) {
            //$("#username_to").val( ui.item.id);
          }
        });
      
    </script>
@if(session('success'))
  <script>
    setTimeout(function(){
new PNotify({
          title: 'Success',
          text: "{{session('success')}}",
          type: 'success',
          hide: true,
          styling: 'bootstrap3'
      });
    },500);
  </script>
@endif
@if(session('failed'))
  <script>
    setTimeout(function(){
new PNotify({
          title: 'Failed',
          text: "{{session('failed')}}",
          type: 'error',
          hide: true,
          styling: 'bootstrap3'
      });
    },500);
  </script>
@endif
@endsection