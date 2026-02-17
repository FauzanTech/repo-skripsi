function change_affiliation(number) {
  const affiliation = $('#affiliation-'+number).val();
  const identity_number_id = $('#identity-number-'+number);
  const author_name_id = $('#author_name_'+number);
  if (affiliation === '1') {
    identity_number_id.prop('disabled', false);
    author_name_id.prop('disabled', true);
  } else {
    identity_number_id.prop('disabled', true);
    author_name_id.prop('disabled', false);
  }
}

function delete_author(number) {
  $("#author-"+number).remove();
}

function find_author_name(el) {
  let current_id = $(el).attr('id');
  identity_number = $('#'+current_id).val();
  const child = $(el).parent().next().find("input");
  const form_error_id = $(el).parent().find('p').attr('id');
  const form_author_id = child.eq(0).attr('id');
  const form_author_name = child.eq(1).attr('id');
  
  if (identity_number.length > 5) {
    $.get('/get-author-name?identity_number='+identity_number, function(res) {
      res = JSON.parse(res);
      if (res.status === 'success') {
        $('#'+form_author_name).prop('disabled', false);
        $('#'+form_author_name).val(res.author_name);
        $('#'+form_author_name).prop('disabled', true);
        $('#'+form_author_id).val(res.author_id);
        $('#'+current_id).removeClass('is-invalid');
        $('#'+form_error_id).text('');
      } else {
        $('#'+form_error_id).text(res.message);
        $('#'+form_author_name).val('');
        $('#'+form_author_id).val('');
        $('#'+current_id).addClass('is-invalid');
      }
    });
  }
}
$(document).ready(function() {
    $('#more_authors').click(function() {
      const current_author = $('#number_author').val();
      
      const next = parseInt(current_author) + 1;
      $('#number_author').val(next);
      
      $('#other-author').append(`
        <div id="author-`+next+`" class="d-flex">
          <div class="form-group col-3">
            <label for="affiliation" class="required">Afiliasi</label>
            <select class="form-select affiliation" id="affiliation-`+next+`" name="affiliation[]" onchange="change_affiliation('`+next+`')">
              <option value="1" selected>Internal</option>
              <option value="2">Eksternal</option>
            </select>
            <p class="error" id='affiliation-error-`+next+`'></p>
          </div>
          <div class="form-group col-3" id="form-author-`+next+`">
            <label for="author_name_1" class="required">NUPTK Dosen/NIM Mahasiswa</label>
            <input type="text" class="form-control" name="identity_number" placeholder="NUPT/NIM Penulis" style="outline:none" id="identity-number-`+next+`" onkeyup="find_author_name(this)">
            <p class="error" id="author_error_`+next+`"></p>
          </div>   
          <div class="form-group col-3" id="form-author-`+next+`">
            <label for="author_name_1" class="required">Nama Penulis</label>
            <input type="hidden" id="author_id_`+next+`" value="" />
            <input type="text" class="form-control" id="author_name_`+next+`" name="author-name[]" placeholder="Nama Penulis" style="outline:none" disabled>
            <p class="error" id="author_error_1"></p>
          </div>
          <div class="form-group col-2">
            <label for="affiliation" class="required">Keterangan</label>
            <select class="form-select" id="author-desc-`+next+`" name="author-desc[]">
              <option value="FIRST AUTHOR" >Penulis 1</option>
              <option value="CORRESPONDING AUTHOR">Koresponden</option>
              <option value="CO-AUTHOR" selected>Anggota</option>
            </select>
            <p class="error" id='affiliation-error-`+next+`'></p>
          </div>
          <div class="form-group col-1">
            <button type="button" class="btn btn-inverse-danger btn-icon" onclick="delete_author(`+next+`)"><i class="fa fa-trash"></i></button>
          </div>
        </div>
      `);
      });

    });