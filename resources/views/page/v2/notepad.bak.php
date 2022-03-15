
                        <div class="mail_view">
                        <div class="inbox-body">
                          <div class="mail_heading row">
                            <div class="col-md-8">
                              <div class="btn-group">
                                <button class="btn btn-sm btn-primary" type="button" id="reply"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                              </div>
                            </div>
                            <div class="col-md-4 text-right">
                              <p class="date">{{date("h:i A d F Y",strtotime($pesan->timelog))}} </p>
                            </div>
                            <div class="col-md-12"><br>
                              <h4> {{$pesan->subjek or ""}}</h4>
                            </div>
                          </div>
                          <div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>{{$pesan->nama_lengkap or ""}}

                                </strong>
                                To
                                <strong>@if($pesan->user_to==$_SESSION['username']) me @endif</strong>
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="view-mail">
                          <div class="col-md-12">                         
                              <h4>{{$pesan->no_rkb or ""}}</h4>
                              <h5>{{$pesan->part_name or ""}}</h5>
                             <div class="col-md-12"><?php echo $pesan->pesan_teks;?></div>
                           </div>   
                          </div>
                        </div>

                      </div>