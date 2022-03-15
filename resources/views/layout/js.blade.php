    <script src="{{url('/')}}:3000/socket.io/socket.io.js"></script>
    <!-- Jequery UI -->
    <script src="{{asset('jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{asset('vendors/nprogress/nprogress.js')}}"></script>
    <!-- Chart.js -->
    <script src="{{asset('vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- gauge.js -->
    <script src="{{asset('vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{asset('vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
    <!-- Skycons -->
    <script src="{{asset('vendors/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script src="{{asset('vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.resize.js')}}"></script>
    <!-- Flot plugins -->
    <script src="{{asset('vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script src="{{asset('vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{asset('vendors/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script src="{{asset('vendors/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script src="{{asset('vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{asset('vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{asset('vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <!--<script src="{{asset('/js/custom.min.js')}}"></script>-->
    <script src="{{asset('/js/custom.js')}}"></script>
    <!--bootstrap-notify-->
    <script src="{{asset('/notify/bootstrap-notify.min.js')}}"></script>
    <!-- PNotify -->
    <script src="{{asset('/vendors/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('/vendors/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('/vendors/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <!-- ECharts -->
    <script src="{{asset('/vendors/echarts/dist/echarts.min.js')}}"></script>
    <script src="{{asset('/vendors/echarts/map/js/world.js')}}"></script>

<script>
var oldToken = "{{csrf_token()}}";
var cur_url = window.location.hostname;
console.log(cur_url);
var socket = io.connect(cur_url+":3000");
var d = new Date();
</script>
@if(!isset($_SESSION['username']))
<script>
  
</script>
@else
<script>
  $(window).ready(function(){

//      socket.emit('updateUser' ,"{{$_SESSION['username']}}");
      socket.emit('RegisterUser' ,"{{$_SESSION['username']}}","{{date('H:i')}}","{{date('d')}}",document.location.href);

  });
</script>
@endif

@if(session('success'))
<script>
  /*
  socket.on('updateUser',function(msg){
          $("#show-fade").append("<article class=\"media event\">"+
                                "<a class=\"pull-left date\">"+
                                "<p class=\"month\">"+d.getHours()+":"+d.getMinutes()+"</p>"+
                                "<p class=\"day\">"+d.getDate()+"</p>"+
                                "</a>"+
                                "<div class=\"media-body\">"+
                                "<a class=\"title\" href=\"#\">User Login : "+msg+"</a>"+
                                "<p>"+document.location+"</p>"+
                                "</div>"+
                                "</article>");
  });
  */
</script>
@else
<script>
  /*
  socket.on('updateUser',function(msg){
          $("#show-fade").append("<article class=\"media event\">"+
                                "<a class=\"pull-left date\">"+
                                "<p class=\"month\">"+d.getHours()+":"+d.getMinutes()+"</p>"+
                                "<p class=\"day\">"+d.getDate()+"</p>"+
                                "</a>"+
                                "<div class=\"media-body\">"+
                                "<a class=\"title\" href=\"#\">User "+msg+" Conected</a>"+
                                "<p>"+document.location+"</p>"+
                                "</div>"+
                                "</article>");
  });
  */
</script>
@endif
<script >
  socket.on('disconnect',function(msg){
  });

$('.table-responsive').on('show.bs.dropdown', function () {
     $('.table-responsive').css( "overflow", "inherit" );
});
$('.table-responsive').on('hide.bs.dropdown', function () {
     $('.table-responsive').css( "overflow", "auto" );
})

$('#datatables').on('show.bs.dropdown', function () {
     $('.table-responsive').css( "overflow", "inherit" );
});
$('#datatables').on('hide.bs.dropdown', function () {
     $('.table-responsive').css( "overflow", "auto" );
})

socket.on('RegisterRespond',function(status){
  if(status==false){
  }else{


  }
});

setInterval(function(){
socket.emit("getCPU","OK");
},1000);


socket.on("updateUser",function(dataUsersArr){
 $(dataUsersArr).each(function(key,val){
    $("#show-fade").append("<article class=\"media event\">"+
                                "<a class=\"pull-left date\">"+
                                "<p class=\"month\">"+val.time+"</p>"+
                                "<p class=\"day\">"+val.date+"</p>"+
                                "</a>"+
                                "<div class=\"media-body\">"+
                                "<a class=\"title\" href=\"#\">"+val.user_id+" </a>"+
                                "<p>"+val.halaman+"</p>"+
                                "</div>"+
                                "</article>");
 });
});
socket.on("userOnline",function(usernames){
  $("#show-fade").empty();
console.log(usernames);
  for(var i=0; i<usernames.length; i++){
/*
    $("#show-fade").append("<article class=\"media event\">"+
                                "<a class=\"pull-left date\">"+
                                "<p class=\"month\">"+d.getHours()+":"+d.getMinutes()+"</p>"+
                                "<p class=\"day\">"+d.getDate()+"</p>"+
                                "</a>"+
                                "<div class=\"media-body\">"+
                                "<a class=\"title\" href=\"#\">"+usernames[i]+" </a>"+
                                "<p>"+document.location+"</p>"+
                                "</div>"+
                                "</article>");

*/
}
});
</script>
<style>
    .table-responsive {
  overflow-y: visible !important;
}
</style>
<script>
  $("#notifOpen").on("click",function(){
  idNotif=[];
  z=0;
  $(".dropdown").on("show.bs.dropdown",function(evt){

    $('input[name^="idNotif"]').each(function() {
    idNotif.push($(this).val());
    })
    if(idNotif.length>0)
{
    $.ajax({
        type:"POST",
        url:"{{url('/notif/open')}}",
        data:{idNotif:idNotif},
        success:function(result){
            if(result=="OK"){
                $("span[id=numberNotif]").text("");
            }
        }
    });
}
  });

  });

  $(".dropdown").on("hide.bs.dropdown",function(evt){
        $("#idNotif").remove();
      });
</script>
<script>
  $(window).ready(function(){
  //    socket.emit('updateUser' ,document.location.href);
        setInterval(function(){
          $.ajax({
              type:"GET",
              url:"{{url('/refresh-csrf')}}",
              success:function(result){
                      if(result.csrf_token!=oldToken){
                        $("input[name=_token]").val(result.csrf_token);
                      }
                      
              }
          });
        },200000);
  });
  
</script>
@yield('js')
