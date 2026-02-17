<?php 
  $session = session();
  
  echo $this->extend('layout/layout');
  echo $this->section('content');

?>
 <!-- main-panel starts -->
<div class="main-panel-full">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
          <i class="mdi mdi-home"></i>
        </span> <?= ($method == 'new') ? 'Referensi Baru' : 'Edit Referensi'; ?>
      </h3>
    </div> 
    <div class="row justify-content-center">
      <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 ">
        <div class="card">
          <div class="card-body">
            <form class="forms-sample" method="POST" action="">
              <input type="hidden" value="<?= (isset($reference) && $reference['reference_id']) ? $reference['reference_id'] : ''; ?>" id="reference_id"/>
              <input type="hidden" value="<?= $method; ?>" id="method" />
              <div class="form-group">
                <h5 class="card-title">Judul dan Bentuk Koleksi</h5>
                <label for="reference_type" class="required" >Bentuk Koleksi</label>
                <select class="form-select" id="reference-type" name="reference_type">
                  <option value="" selected disabled>Bentuk Koleksi</option>  
                  <?php foreach($referenceType as $row) {
                    if (isset($reference) && $reference['reference_type_id'] == $row['reference_type_id']) { ?>
                      <option value="<?= $row['reference_type_id']; ?>" selected><?= $row['reference_type_name']; ?></option>
                    <?php } else { ?>
                      <option value="<?= $row['reference_type_id']; ?>"><?= $row['reference_type_name']; ?></option>
                  <?php }
                  }?>
                </select>
                <p class="error" id="reference_type_error"></p>
              </div>
              <div class="form-group">
                <label for="Disciplines" class="required">Disiplin Ilmu</label>
                <select class="form-select" id="disciplines" name="disciplines">
                  <option value="" selected disabled>Disiplin Ilmu</option>  
                  <?php foreach($disciplines as $row) {
                    if (isset($reference) && $reference['disciplines_id'] == $row['disciplines_id']) { ?>
                     <option value="<?= $row['disciplines_id']; ?>" selected><?= $row['disciplines_name']; ?></option>
                    <?php } else { ?>
                      <option value="<?= $row['disciplines_id']; ?>"><?= $row['disciplines_name']; ?></option>
                  <?php }
                } ?>
                </select>
                <p class="error" id="disciplines_error"></p>
              </div>
              <div class="form-group">
                <label for="reference_title" class="required">Judul Referensi</label>
                <input type="text" class="form-control" id="reference_title" placeholder="Judul Referensi" style="outline:none" value="<?= (isset($reference) && $reference['title']) ? $reference['title'] : ''; ?>">
                <p class="error" id="reference_title_error"></p>
              </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
              <div class="row">
                <h5 class="card-title" >Penulis</h5>
                <div id="author-container">
                  <?php if (isset($reference) && isset($authors)) {
                    foreach ($authors as $row) { ?> 
                    <div id="author-1" class="d-flex justify-content-between">
                      <div class="form group col-md-6 col-sm-12 col-xs-12">
                        <label for="affiliation">Afiliasi Penulis</label>
                        <select class="form-select" id="affiliation-1" name="affiliation[]" class="affiliation" onchange="changeAffiliation(1)">
                          <?php if ($row['external_author_affiliation'] == 0) { ?>
                            <option value="1" selected>Institut Teknologi Bacharuddin Jusuf Habibie</option>
                            <option value="2">Lainnya</option>
                          <?php } else { ?>
                          <option value="1">Institut Teknologi Bacharuddin Jusuf Habibie</option>
                          <option value="2" selected>Lainnya</option>
                        <?php } ?>
                        </select>
                        <p class="error" id='affiliation-error-1'></p>
                      </div>
                      <div class="form-group col-md-3 col-sm-6 col-xs-6" id="form-author-1">
                        <input type="text" class="form-control" placeholder="NUPTK Dosen/NIM Mahasiswa" />
                        <p class="error" id='author-error-1'></p>
                      </div>
                      <div class="form-group col-md-3 col-sm-6 col-xs-6" id="form-author-1">
                        <label for="author-name">Nama Penulis</label>
                        <?php if (isset($reference) && isset($authors)) {
                          if ($row['external_author_affiliation'] == 0) { ?>
                        <select class="js-example-basic-single form-select" id="author-name-1" name="author-name[]" data-live-search="true">
                          <?php foreach ($members as $rowMember) {
                            if ($row['user_id'] == $rowMember['user_id']) { ?>
                            <option data-tokens="<?= $rowMember['fullname'] ?>" value="<?= $rowMember['user_id'] ?>" selected><?= $rowMember['fullname'] ?></option>
                          <?php } else { ?>
                            <option data-tokens="<?= $rowMember['fullname'] ?>" value="<?= $rowMember['user_id'] ?>"><?= $rowMember['fullname'] ?></option>
                          <?php }} ?>
                          </select>
                          <?php } else { ?>
                          <input type="text" class="form-control" id="author-name-1" name="author-name[]" value="<?= $row['external_author_name']; ?>">
                          <?php }} ?> 
                        <p class="error" id='author-error-1'></p>
                      </div>
                    </div>
                    <?php }
                      } else { ?>
                      <div class="d-flex justify-content-between">
                        
                        <div id="author-1" class="col-md-6 col-sm-12 col-xs-12">
                          <div class="form-group">
                            <label for="affiliation" class="required">Afiliasi Penulis</label>
                            <select class="form-select" id="affiliation-1" name="affiliation[]" class="affiliation" onchange="changeAffiliation(1)">
                              <option value="1" selected>Institut Teknologi Bacharuddin Jusuf Habibie</option>
                              <option value="2">Lainnya</option>
                            </select>
                            <p class="error" id='affiliation-error-1'></p>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6" id="form-author-1">
                          <div class="form-group">
                            <label for="author-name" class="required">NUPTK Dosen/NIM Mahasiswa</label>
                            <input type="text" class="form-control" placeholder="NUPTK Dosen/NIM Mahasiswa">
                            <p class="error" id='author-error-1'></p>
                          </div>
                          <!-- <input type="text" class="form-select" id="author-name-1" name="author-name"> -->
                          
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6" id="form-author-1">
                          <div class="form-group">
                            <label for="author-name" class="required">Nama Penulis</label>
                            <select class="form-select" id="author-name-1" name="author-name[]" data-live-search="true">
                              <?php foreach ($members as $row) {
                                if ($session->get('user_id') == $row['user_id']) { ?>
                                  <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>" selected><?= $row['fullname'] ?></option>
                                <?php } ?>
                                <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>"><?= $row['fullname'] ?></option>
                              <?php }?>
                            </select>
                            <p class="error" id='author-error-1'></p>
                          </div>
                          <!-- <input type="text" class="form-select" id="author-name-1" name="author-name"> -->
                          
                        </div>
                      </div>
                    <?php } ?>
                  
                </div>
                <input type="hidden" id="number_author" value="1" />
                <div id="more_authors" style="cursor:pointer"><p><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Tambah Penulis</p></div>
              </div>
              <div class="form-group" id="supervisors">
                <h5 class="card-title">Dosen Pembimbing</h5>
                <div class="form-group" id="supervisor-1">
                  <label for="author-name" class="required">Dosen Pembimbing 1</label>
                  <select class="selectpicker form-select" id="first-supervisor" name="first-supervisor" data-live-search="true">
                     <option value="" selected disabled>Pilih dosen pembimbing 1</option> 
                    <?php foreach ($supervisors as $row) {
                      if ($row['user_id'] ==  $first_supervisor) {?>
                        <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>" selected><?= $row['fullname'] ?></option>
                        <?php } else { ?>
                      <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>"><?= $row['fullname'] ?></option>
                    <?php } }?>
                    </select>
                  <!-- <input type="text" class="form-select" id="author-name-1" name="author-name"> -->
                  <p class="error" id='first-supervisor-error'></p>
                </div>
                <div class="form-group" id="supervisor-2">
                  <label for="author-name" class="required">Dosen Pembimbing 2</label>
                  <select class="selectpicker form-select" id="second-supervisor" name="second-supervisor" data-live-search="true">
                    <option value="" selected disabled>Pilih dosen pembimbing 2</option> 
                    <?php foreach ($supervisors as $row) {
                      if ($row['user_id'] == $second_supervisor) {?>
                        <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>" selected><?= $row['fullname'] ?></option>
                      <?php } else { ?>
                        <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>"><?= $row['fullname'] ?></option>
                    <?php } }?>
                    </select>
                  <!-- <input type="text" class="form-select" id="author-name-1" name="author-name"> -->
                  <p class="error" id='second-supervisor-error'></p>
                </div>
              </div>
              <label class="required">Status Publikasi</label>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" id="published" value="1" name="published"
                  <?= (isset($reference) && $reference['published_external'] =='1') ? 'checked' : ''; ?>>Telah dipublikasikan </label>
                  
              </div>
               <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" id="not-published" value="0" name="published"
                  <?= (isset($reference) && $reference['published_external'] =='0') ? 'checked' : ''; ?>>Tidak pernah dipublikasikan </label>
              </div>
              <p class="error" id='published_error'></p>
                <button type="button" id="back" class="btn btn-outline-primary">Kembali</button>
                <button type="button" id="next" class="btn btn-primary me-2">Selanjutnya</button> 
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<script src="/assets/js/form-validation.js"></script>
<script>
function changeAffiliation(number) {
    const affiliation = $('#affiliation-'+number).val();
    if (affiliation == 1 || affiliation == '1') {
      $('#form-author-'+number).html(`
        <label for="author-name" class="required">Nama Penulis</label>
          <select class="selectpicker form-select" id="author-name-`+number+`" name="author-name[]" data-live-search="true">
            <?php foreach ($members as $row) { ?>
              <option data-tokens="<?= $row['fullname'] ?>" value="<?= $row['user_id'] ?>"><?= $row['fullname'] ?></option>
            <?php }?>
          </select>
          <p class="error" id='author-error-`+number+`'></p>
      `);
    } else {
      $('#form-author-'+number).html(`
        <label for="author-name" class="required">Nama Penulis</label>
        <input type="text" class="form-control" id="author-name-`+number+`" name="author-name[]" placeholder="Nama Penulis" style="outline:none">
        <p class="error" id='author-error-`+number+`'></p>
      `);
    }
  }
$(document).ready(function() {
    $('#more_authors').click(function() {
      const current_author = $('#number_author').val();
      const next = parseInt(current_author) + 1
      $('#number_author').val(next);
        $('#author-container').append(`
          <div id="author-`+next+`" class="d-flex justify-content-between">
            <div class="form-group" style="width:49%;">
              <label for="affiliation" class="required">Afiliasi</label>
              <select class="form-select" id="affiliation-`+next+`" name="affiliation[]" class="affiliation" onchange="changeAffiliation(`+next+`)">
                <option value="" selected disabled>Pilih Afiliasi</option>
                <option value="1">Institut Teknologi Bacharuddin Jusuf Habibie</option>
                <option value="2">Lainnya</option>
              </select>
              <p class="error" id='affiliation-error-`+next+`'></p>
            </div>
            <div class="form-group" style="width:49%;" id="form-author-`+next+`">
              <label for="author-name-`+next+`" class="required">Nama Penulis</label>
              <input type="text" class="form-control" id="author-name-`+next+`" name="author-name[]" placeholder="Nama Penulis" style="outline:none">
              <p class="error" id='id='author_error_`+next+`''></p>
            </div>    
          </div>
        `);
    });
    // // Handle form submission
    $('#next').click(function(e) {
        e.preventDefault();
        let authors = [];
        const number_author = $('#number_author').val();
        for (i = 1; i<= parseInt(number_author); i++) {
          authors.push($('#author-name-'+i).val());
        }

        const formData = {
            reference_type: $('#reference-type').val(),
            disciplines: $('#disciplines').val(),
            reference_title: $('#reference_title').val(),
            published: $('input[type="radio"]:checked').val(),
            affiliations: $("select[name='affiliation[]']").map(function(){return $(this).val();}).get(),
            authors,
            reference_id: $('#reference_id').val(),
            first_supervisor: $('#first-supervisor').val(),
            second_supervisor: $('#second-supervisor').val(),
            method: $('#method').val() // new or edit
        };
        const forms = [
            { id: 'reference_type_error', value: formData.reference_type, rule: 'required', error_message: 'Bentuk Referensi harus dipilih' },
            { id: 'disciplines_error', value: formData.disciplines, rule: 'required', error_message: 'Rumpun Ilmu harus dipilih' },
            { id: 'reference_title_error', value: formData.reference_title, rule: 'required', error_message: 'Judul Referensi harus diisi' },
            { id: 'published_error', value: formData.published, rule: 'required', error_message: 'Status publikasi harus dipilih'}
        ];
        
        for (i = 1; i <= parseInt(number_author); i++) {
          forms.push(
            { id: 'affiliation-error-'+i, value: formData.affiliations[i-1], rule: 'required', error_message: 'Afiliasi Penulis harus dipilih' },
            { id: 'author-error-'+i, value: formData.authors[i-1], rule: 'required', error_message: 'Nama Penulis harus diisi' },
          )
        }
        if (formData.reference_type === '3' || formData.reference_type === '4' || formData.reference_type === '5') {
          forms.push(
            {id: 'first-supervisor-error', value: formData.first_supervisor, rule: 'required', error_message: 'Dosen pembimbing 1 wajib diisi'},
            {id: 'second-supervisor-error', value: formData.second_supervisor, rule: 'required', error_message: 'Dosen pembimbing 2 wajib diisi'}
          );

        }
        if (validation(forms)) {
          localStorage.setItem('formReference', JSON.stringify(formData));
          $.post('/create-reference-form', formData, function(res) {
            window.location.href = 'new-reference-form';
          });
        }
    });
    $('#reference-type').change(function() {
      type = $('#reference-type').val();
      if (type === '3' || type === '4' || type === '5') {
        $('#supervisors').show();
      } else {
         $('#supervisors').hide();
      }
    });
    
});
</script>

<?= $this->endSection('content'); ?>

