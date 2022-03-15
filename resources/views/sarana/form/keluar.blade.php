<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Sarana Keluar</h4>
      </div>
<form class="form-horizontal form-label-left" action="" method="post">
<div class="modal-body">
{{csrf_field()}}
@if(isset($edit))
<input type="hidden" name="_method" value="PUT" >
<input type="hidden" name="data_id" value="{{$data_id}}" >
@endif
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_lv">No Lambung <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
        <select id="no_lv" class="form-control col-md-12 col-xs-12" data-validate-length-range="2" name="no_lv" placeholder="No Polisi" required="required" type="text" data-live-search="true">
          <option value="">--PILIH--</option>
          @foreach($unit as $k =>$v)
          <option value="{{$v->no_lv}}">{{$v->no_lv}} ({{$v->merek_type}} {{$v->jenis}}) [ {{$v->no_pol}} ]</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pol">No Polisi <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="form-control-static" id="no_pol_html"></div>
        <input id="no_pol" class="form-control col-md-12 col-xs-12" data-validate-length-range="2" name="no_pol" required="required" type="hidden">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_keluar">Tanggal Keluar <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12">
        <input id="tgl_keluar" class="form-control col-md-12 col-xs-12 datepicker" data-live-search="true" data-validate-length-range="2" name="tgl_keluar" required="required" placeholder="Tanggal Keluar" type="text" @if(isset($edit)) value="{{$edit->nama_p}}" @else value="{{date('d F Y')}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jam_keluar">Jam Keluar <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12" style="width: 80px;">
        <input id="jam_keluar" class="form-control col-md-12 col-xs-12" data-live-search="true" data-validate-length-range="2" name="jam_keluar" required="required" placeholder="Jam Keluar" data-inputmask="'mask' : '**:**'" type="text" @if(isset($edit)) value="{{$edit->nama_p}}" @else value="{{date('H:i')}}" @endif>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_masuk">Waktu Masuk <span class="required">*</span>
      </label>
      <div class="col-md-2 col-sm-6 col-xs-12" style="width: 80px;">
        <div class="form-control-static"><input id="waktu_masuk" class="row" name="waktu_masuk" type="checkbox"></div>
      </div>
    </div>

    <div class="masuk"></div>
    
    
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea id="keterangan" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="keterangan" placeholder="Keterangan" required="required" >@if(isset($edit)){{$edit->alamat_p}}@endif</textarea>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="driver">Driver <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="driver" class="form-control col-md-12 col-xs-12" data-live-search="true" data-validate-length-range="2" name="driver" required="required" placeholder="Driver" type="text" @if(isset($edit)) value="{{$edit->nama_p}}" @endif>
        <option value="">--PILIH--</option>
        @foreach($karyawan as $kD => $vD)
        @if(isset($edit))
        @if($edit->nik==$vD->nik)
        <option value="{{$vD->nik}}">({{$vD->nik}}) {{ucwords($vD->nama)}} [ {{ucwords($vD->jabatan)}} ]</option>
        @else
        <option value="{{$vD->nik}}">({{$vD->nik}}) {{ucwords($vD->nama)}} [ {{ucwords($vD->jabatan)}} ]</option>
        @endif
        @else
        <option value="{{$vD->nik}}">({{$vD->nik}}) {{ucwords($vD->nama)}} [ {{ucwords($vD->jabatan)}} ]</option>
        @endif
        @endforeach
      </select>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemohon">Pemohon <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="pemohon" class="form-control col-md-12 col-xs-12" data-live-search="true" data-validate-length-range="2" name="pemohon" required="required" placeholder="Pemohon" type="hidden" @if(isset($pemohon)) value="{{$pemohon->nik}}" @endif>
        <div class="form-control-static">@if(isset($pemohon)) {{ucwords($pemohon->nama)}} @endif</div>
      </div>
    </div>
    <div class="item row">
      <label class="col-md-12 col-sm-3 col-xs-12 text-center" for="nama">Penumpang<span class="required">*</span></label>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table table-striped" id="table_penumpang">
          <thead>
            <tr class="bg-primary">
              <th width="45%">Penumpang</th>
              <th width="45%">Departemen / Devisi</th>
              <th width="10%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
                  <td>
                  <div class="item">
                  <select name="penumpang[]" id="penumpang"  data-live-search="true" class="form-control" required="required">
                  <option value="">--PILIH--</option>
                  @foreach($karyawan as $kK => $vK)
                  @if(isset($pemohon))
                  @if($pemohon->nik==$vK->nik)
                  <option value="{{$vK->nik}}" selected="selected">({{$vK->nik}}) <b>{{ucwords($vK->nama)}}</b> [ {{ucwords($vK->jabatan)}} ]</option>
                  @else
                  <option value="{{$vK->nik}}">({{$vK->nik}}) <b>{{ucwords($vK->nama)}}</b> [ {{ucwords($vK->jabatan)}} ]</option>
                  @endif
                  @else
                  <option value="{{$vK->nik}}">({{$vK->nik}}) <b>{{ucwords($vK->nama)}}</b> [ {{ucwords($vK->jabatan)}} ]</option>
                  @endif
                  @endforeach
                  </select>
                  </div>
                  </td>
                  <td>
                  <div class="form-control-static" id="depSect">@if(isset($pemohon)) {{ucwords($pemohon->dept)}} @if($pemohon->devisi!=null) / {{ucwords($pemohon->devisi)}} @endif @endif</div>
                  </td>
                  <td>              
                  <button class="btn btn-xs btn-danger" name="deleteRow" type="button"><i class="fa fa-times"></i></button>
                  </td>
                  </tr>
            <tr class="last_item">
              <td colspan="3">
               <div class="container-fluid">
                <div class="row">
<div class="form-group pull-right">
<div class=" col-md-8 col-sm-12 col-xs-12 pull-right">
<div class="input-group">
<input type="number" class="form-control" name="nRow" autocomplete="off" min="1" value="1" required="required">
<span class="input-group-addon"> Rows </span>
<span class="input-group-btn">
<button class="btn btn-primary" type="button" name="addRow">Add <i class="fa fa-plus"></i></button>
</span>
              </div>
              </div>
              </div> 
              </div>
              </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
</div>
      <div class="modal-footer">

        <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
  </div>

<!---validator-->
    <script src="{{asset('/vendors/validator/validator.js')}}"></script>
    <!-- jQuery Tags Input -->
    <script src="{{asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>
    <!-- jQuery autocomplete -->
    <script src="{{asset('/js-auto/dist/jquery.autocomplete.min.js')}}"></script>
    <!-- jquery.inputmask -->
    <script src="{{asset('/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js')}}"></script>

    <script>
      $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });
      function init_InputMask() {
      
      if( typeof ($.fn.inputmask) === 'undefined'){ return; }
      console.log('init_InputMask');
      
        $(":input").inputmask();
        
    };
      var masuk = '<div class="item form-group">'+
                  '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_masuk">Tanggal Masuk <span class="required">*</span>'+
                  '</label>'+
                  '<div class="col-md-2 col-sm-6 col-xs-12">'+
                    '<input id="tgl_masuk" class="form-control col-md-12 col-xs-12 datepicker" data-live-search="true" data-validate-length-range="2" name="tgl_masuk" required="required" placeholder="Tanggal Masuk" type="text" @if(isset($edit)) value="{{$edit->jam_masuk}}" @else value="{{date('d F Y')}}" @endif>'+
                  '</div>'+
                '</div>'+
                '<div class="item form-group">'+
                '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jam_masuk">Jam Masuk <span class="required">*</span>'+
                '</label>'+
                '<div class="col-md-2 col-sm-6 col-xs-12" style="width: 80px;">'+
                  '<input id="jam_masuk" class="form-control col-md-12 col-xs-12" data-live-search="true" data-validate-length-range="2" name="jam_masuk" required="required" placeholder="Jam Masuk" data-inputmask="\'mask\' : \'**:**\'" type="text" @if(isset($edit)) value="{{$edit->jam_masuk}}" @else value="{{date('H:i')}}" @endif>'+
                '</div>'+
              '</div>';
      $("#table_penumpang").on("click","button[name=deleteRow]",function(){
        eq = $("button[name=deleteRow]").index(this);
        $("button[name=deleteRow]").eq(eq).parent().parent().remove();
      });
      $("input[id=waktu_masuk]").click(function(){
        if($('input[id=waktu_masuk]').prop('checked')) {
            $("div[class=masuk]").html(masuk);
            $(".datepicker").datepicker({ dateFormat: 'dd MM yy' });
            init_InputMask();
        } else {
            $("div[class=masuk]").html("");
        }
      });
      $("select").selectpicker();
      
      var temp =  `<tr>
                  <td>
                  <div class="item">
                  <select name="penumpang[]" id="penumpang"  data-live-search="true" class="form-control" required="required">
                  <option value="">--PILIH--</option>
                  @foreach($karyawan as $kK => $vK)
                  @if(isset($edit))
                  @if($edit->nik==$vK->nik)
                  <option value="{{$vK->nik}}">({{$vK->nik}}) {{ucwords($vK->nama)}} [ {{$vK->jabatan}} ]</option>
                  @else
                  <option value="{{$vK->nik}}">{{"(".$vK->nik.") ".ucwords($vK->nama)}} [ {{$vK->jabatan}} ]</option>
                  @endif
                  @else
                  <option value="{{$vK->nik}}">{{"(".$vK->nik.") ".ucwords($vK->nama)}}
                  [ {{$vK->jabatan}} ]
                  </option>
                  @endif
                  @endforeach
                  </select>
                  </div>
                  </td>
                  <td>
                  <div class="form-control-static" id="depSect"></div>
                  </td>
                  <td>          
                  <button class="btn btn-xs btn-danger" name="deleteRow" type="button"><i class="fa fa-times"></i></button>
                  </td>
                  </tr>`;
      $("button[name=addRow]").click(function(){
          nRow = parseInt($("input[name=nRow]").val());
          //alert(nRow);
          for(i=0; i<nRow; i++){
          $("tr[class=last_item]").before(temp);
          }
          $("select").selectpicker('refresh');
      });

      
    /* INPUT MASK */
      $("select[id=no_lv]").change(function(){
        no_lv = $("select[id=no_lv]").val();
        if(no_lv!=""){
        $.ajax({
          type:"POST",
          url:"{{url('/sarpras/unit/check-no-lv')}}",
          data:{_token:"{{csrf_token()}}",no_lv:no_lv},
          success:function(res){
            dRes = JSON.parse(res);
            //alert(dRes.no_pol);
            $("input[id=no_pol]").val(dRes.no_pol);
            $("div[id=no_pol_html]").html(dRes.no_pol);
            $("select[id=driver] option").removeAttr("selected");
            $("select[id=driver] option[value="+dRes.nik+"]").attr('selected', 'selected'); 
            $("select").selectpicker('refresh');
          }
        });
        }else{
            $("input[id=no_pol]").val("");
            $("div[id=no_pol_html]").html("");
            $("select[id=driver] option").removeAttr("selected");
            $("select[id=driver] option[value='']").attr('selected', 'selected');
            $("select").selectpicker('refresh'); 
        }
      });
      $("#table_penumpang").on("change","select[id=penumpang]",function(){
        //alert();
        eq = $("select[id=penumpang]").index(this);
        nik = $("select[id=penumpang]").eq(eq).val();
        $.ajax({
          type:"POST",
          url:"{{url('/sarpras/karyawan/check-nik')}}",
          data:{_token:"{{csrf_token()}}",nik:nik},
          success:function(res){
            dRes = JSON.parse(res);
            //alert(dRes.no_pol);
            if(dRes.sect!=null){
            $("div[id=depSect]").eq(eq).html(dRes.dept+" / "+dRes.sect);
          }else{
            $("div[id=depSect]").eq(eq).html(dRes.dept);
          }

          }
        });
      });
    
    init_InputMask();
    setTimeout(function(){
      $("input[id=no_pol]").focus();
    },300);

    $("input").keyup(function(){
      this.value = this.value.toLocaleUpperCase();
    });
    $("textarea").keyup(function(){
      this.value = this.value.toLocaleUpperCase();
    });
    /* VALIDATOR */

    function init_validator() {
     
    if( typeof (validator) === 'undefined'){ return; }    
    // initialize the validator function
      validator.message.date = 'not a real date';

      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
      $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

      $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;
        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
    });
    
    };

    //init_validator();
     
    $('form').submit(function(){
        $("button[id=submit]").attr("disabled","disabled");
    });
    </script>