
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Create Password</h4>
      </div>

          <form method="post" class="form-horizontal" action="">
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">

            {{csrf_field()}}
        <input type="hidden" name="data_id" value="{{$data_id}}">
            <div class="form-group">
              <label class="control-label col-lg-4">Create Username?</label>
              <div class="col-lg-4">
              <input type="text" name="username" class="form-control" placeholder="Create Username?">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-4">New Password</label>
              <div class="col-lg-4">
              <input type="password" name="pass" class="form-control" placeholder="Password" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-4">Retype Password</label>
              <div class="col-lg-4">
              <input type="password" name="r_pass" class="form-control" placeholder="Retype Password" required="required">
              </div>
              <div class="col-lg-4">
                <div class="progress">
                  <div class="progress-bar progress-bar-danger" data-transitiongoal="0"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
                <button class="btn btn-primary" name="submit" type="submit" disabled="disabled">Submit</button>
        <button type="button" class="btn btn-default" id="close_modal" data-dismiss="modal">Close</button>
      </div>

          </form>
    </div>
<script>
  
$("input[name=r_pass]").on("keyup",function(){
  f_pass = $("input[name=pass]");
  r_pass = $("input[name=r_pass]");
    persen = ((r_pass.val().length/f_pass.val().length)*100);

    $(".progress-bar").attr("data-transitiongoal",persen);
    if ($(".progress .progress-bar")[0]) {
      $('.progress .progress-bar').progressbar();
    }
    if(r_pass.val()==f_pass.val()){
      $(".progress-bar").removeClass("progress-bar-danger").addClass("progress-bar-success");
      $("button[name=submit").removeAttr("disabled");
    }else{
      $(".progress-bar").removeClass("progress-bar-success").addClass("progress-bar-danger");$("button[name=submit").attr("disabled","disabled");

    }
  
});
</script>