
@foreach($inbox as $k => $v)    
                        <a href="{{url('/kabag/inbox/'.bin2hex($v->id_pesan))}}.message">



                        <button id="compose" class="btn btn-sm btn-success btn-block" type="button">COMPOSE</button>


                        
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