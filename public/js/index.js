$(document).ready(function() {
  
  var animating = false,
      submitPhase1 = 1100,
      submitPhase2 = 400,
      logoutPhase1 = 800,
      $login = $(".login"),
      $app = $(".app");
  
  function ripple(elem, e) {
    $(".ripple").remove();
    var elTop = elem.offset().top,
        elLeft = elem.offset().left,
        x = e.pageX - elLeft,
        y = e.pageY - elTop;
    var $ripple = $("<div class='ripple'></div>");
    $ripple.css({top: y, left: x});
    elem.append($ripple);
  };
  
  $(document).on("submit", ".login_form_submit", function(e) {
    var cur_url = document.location.href;
    $(".error_msg").hide();
    $(".success_msg").hide();
      username = $("input[name=username]").val();
      password = $("input[name=password]").val();
    if (animating) return;
    animating = true;
    var that = $(".login__submit");
    ripple($(that), e);
    $(that).addClass("processing");
    setTimeout(function() {
      $(that).addClass("success");
      setTimeout(function() {
        $app.show();
        $app.css("top");
        $app.addClass("active");
      }, submitPhase2 - 70);
      setTimeout(function() {
       // $login.hide();
        //$login.addClass("inactive");
        $.ajax({
          type:"POST",
          url:cur_url+"login",
          data:{username:username,password:password},
          beforeSend:function(){
            $(".login__submit").attr("disabled","disabled");
          },
          success:function(result){
            if(result=="OK"){              
             $login.fadeOut();
              setTimeout(function(){
              $(".success_msg").slideToggle();
              setTimeout(function(){
                $(".success_msg").slideToggle();
                $(".redirect_msg").slideToggle();
                setTimeout(function(){
                  window.location.reload();
                },500);
              },500);
              },500);

            }else{    
              $(".error_msg").slideToggle();
              animating = false;
              $(that).removeClass("success processing");
              $(".login__submit").removeAttr("disabled");
            }
          }
        });
      }, submitPhase2);
    }, submitPhase1);
    return false;
  });
  
  $(document).on("click", ".app__logout", function(e) {
    if (animating) return;
    $(".ripple").remove();
    animating = true;
    var that = this;
    $(that).addClass("clicked");
    setTimeout(function() {
      $app.removeClass("active");
      $login.show();
      $login.css("top");
      $login.removeClass("inactive");
    }, logoutPhase1 - 120);
    setTimeout(function() {
      $app.hide();
      animating = false;
      $(that).removeClass("clicked");
    }, logoutPhase1);
  });
  
});