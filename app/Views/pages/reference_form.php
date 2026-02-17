
<?php
echo $this->extend('layout/layout');
echo $this->section('content');
// $validation = session()->get('validation');

$referenceData = session()->get('referenceData');

if ($referenceData && $referenceData['reference_id'] != '') {

  $db = db_connect();
  $refId = $referenceData['reference_id'];
  $res = $db->query("SELECT * from reference where reference_id='$refId'");
  $reference = $res->getResultArray()[0];
}

?>
<div class="main-panel-full">
  <div id="alert"></div>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
          <i class="mdi mdi-home"></i>
        </span> Dashboard
      </h3>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Referensi Baru</h4>
            <input type="hidden" id="is_external_published" value="<?= $published; ?>" />
            <form class="forms-sample" method="POST" action="/insert-new-reference" enctype="multipart/form-data">
              <?php
              if ($referenceType == 1 && $published === true) { ?>
                <!-- Form Fields for Reference Type 1 and Published -->
                <div class="form-group">
                  <label for="journal-name" class="required">Nama Jurnal</label>
                  <input
                    type="text"
                    name="journal_name"
                    class="form-control <?= ($validation && $validation->hasError('journal_name')) ? 'is-invalid' : ''; ?>"
                    id="journal-name"
                    placeholder="Nama Jurnal"
                    value="<?= (isset($reference) && $reference['journal_name']) ? $reference['journal_name'] : ''; ?>"
                  >
                  <p class="error"><?= ($validation && $validation->getError('journal_name')) ? $validation->getError('journal_name'): '' ?></p>
                </div>
                <p class="error" id="journal-name-error"></p>
                <div class="form-group">
                  <label for="publication-date" class="required">Tanggal Terbit</label>
                  <input
                    type="date"
                    class="form-control <?= ($validation && $validation->hasError('publication_date')) ? 'is-invalid' : ''; ?>"
                    name="publication_date" id="publication_date"
                    placeholder="Tanggal Terbit"
                    value="<?= (isset($reference) && $reference['publication_date']) ? $reference['publication_date'] : ''; ?>"

                    >
                  <p class="error"> <?= ($validation && $validation->getError('publication_date')) ? $validation->getError('publication_date') : '' ?></p>
                </div>
                
                <div class="d-flex justify-content-between">
                  <div class="col-5">
                    <div class="form-group">
                      <label for="volume">Volume</label>
                      <input type="number"
                        name="volume"
                        class="form-control"
                        id="volume"
                        placeholder="Volume"
                        value="<?= (isset($reference) && $reference['volume']) ?  $reference['volume'] : ''; ?>"
                      >
                    </div>
                    <p class="error" id="volume_error"></p>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="start-page">Dari Halaman</label>
                      <div class="d-flex">
                        <input type="number"
                          name="start-page"
                          class="form-control"
                          id="start-page"
                          placeholder="Dari Halaman"
                          value="<?= (isset($reference) && $reference['start_page']) ? $reference['start_page'] :''; ?>"
                        >
                      </div>
                      <p class="error" id="start-page-error"></p>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="end-page">Sampai Halaman</label>
                      <div class="d-flex">
                        <input type="number"
                          name="end-page"
                          class="form-control"
                          id="end-page" placeholder="Sampai Halaman"
                          value="<?= (isset($reference) && $reference['end_page']) ? $reference['end_page'] :''; ?>"
                          >
                      </div>
                      <p class="error" id="end-page-error"></p>
                    </div>
                  </div>
                </div>
              
              <?php } elseif ($referenceType == 2 && $published === true) { ?>
                <!-- Form Fields for Reference Type 2 and Published -->
                <div class="form-group">
                  <label for="tanggal-terbit">Tanggal Terbit</label>
                  <input type="date" class="form-control <?= ($validation && $validation->hasError('publication-date')) ? 'is-invalid' : ''; ?>" name="publication_date" id="publication_date" placeholder="Tanggal Terbit">
                  <p class="error" id="publication_date"> <?= ($validation && $validation->getError('publication_date')) ? $validation->getError('publication_date') : '' ?></p>
                </div>
                <p class="error" id="tanggal-terbit-error"></p>
                <div class="form-group">
                  <label for="proceedings-title" class="required">Nama Prosiding</label>
                  <input
                    type="text"
                    class="form-control <?= ($validation && $validation->hasError('proceedings-title')) ? 'is-invalid' : ''; ?>"
                    id="proceedings_title"
                    name="proceeding_title"
                    placeholder="Nama Prosiding"
                    value="<?= (isset($reference) && $reference['proceedings_title']) ? $reference['proceedings_title'] : ''; ?>"
                  >
                  <p class="error" id="proceedings_title_error"> <?= ($validation && $validation->getError('proceedings-title')) ? $validation->getError('proceedings-title') : '' ?> </p>
                </div>
                
                <div class="form-group">
                  <label for="city" class="required">Kota</label>
                  <input
                    type="text"
                    name="city"
                    class="form-control"
                    id="city"
                    placeholder="Kota, Negara"
                    value="<?= (isset($reference) && $reference['proceedings_city']) ? $reference['proceedings_city'] : ''; ?>"
                  >
                </div>
                <p class="error" id="procising_city_error"></p>
              
              <?php } elseif (!$published) { ?>
                <!-- Form Fields for Other Cases -->
                <div class="form-group">
                <label for="statement_letter" class="required">Dokumen Surat Pernyataan (pdf)</label>
                    <input type="file" class="file-upload-default" id="statement_letter" name="statement_letter" accept=".pdf" />
                    <div class="input-group col-xs-12">
                      <input
                        type="text"
                        class="form-control file-upload-info <?= ($validation && $validation->hasError('statement_letter')) ? 'is-invalid': '' ?>"
                        readonly
                        placeholder="Upload dokumen surat pernyataan"
                        id="statement_letter_name"
                        value="<?= (isset($reference) && $reference['statement_letter']) ? $reference['statement_letter'] : ''; ?>"
                      >
                      <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-primary py-3" type="button" id="upload_statement_letter">Upload</button>
                      </span>
                    </div>
                    <p class="error" id="statement_letter_error"><?= ($validation && $validation->getError('statement_letter')) ? $validation->getError('statement_letter') : ''; ?></p>
                    <span>
                      <a href="<?= site_url('StatementFile'); ?>" style="font-size: 12px; color:orange">
                        Download Surat Pernyataan disini
                      </a>
                      </span>
                  </div>
                <div class="form-group">
                  <label for="reference_file" class="required">Dokumen Artikel (pdf)</label>
                  <input
                      type="file"
                      name="reference_file"
                      id="reference_file"
                      class="file-upload-default"
                      accept=".pdf"
                    >
                  <div class="input-group col-xs-12">
                    <input
                      type="text"
                      id="reference_filename"
                      class="form-control file-upload-info <?= (isset($validation) && $validation->hasError('reference_file')) ? 'is-invalid' : ''; ?>"
                      placeholder="Upload dokumen artikel (pdf)"
                      value="<?= (isset($reference) && $reference['reference_file']) ? $reference['reference_file'] : ''; ?>"
                      disabled>
                      <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-primary py-3" type="button" id="upload_reference_file">Upload</button>
                      </span>
                  </div>
                  <p class="error" id="reference_file_error"><?= (isset($validation) && $validation->getError('reference_file')) ? $validation->getError('reference_file') : ''; ?> </p>
                </div> 
              <?php } ?>
              
              <?php if ($published === true) { ?>
                <!-- Common Fields for Published -->
                <div class="form-group">
                  <label for="reference_link" class="required">Tautan Artikel Publikasi</label>
                  <input
                    type="text"
                    name="reference_link"
                    id="reference_link"
                    class="form-control <?=($validation && $validation->hasError('reference_link')) ? 'is-invalid' : '' ?>"
                    value="<?= (isset($reference) && $reference['reference_link']) ? $reference['reference_link'] : ''; ?>"
                  >
                  <p class="error" id="reference_link_error"><?php echo ($validation && $validation->getError('reference_link')) ? $validation->getError('reference_link') : '' ; ?></p>
                </div>
                <div class="form-group">
                  <label for="doi">DOI</label>
                  <input
                    type="text"
                    id="doi"
                    name="doi"
                    class="form-control <?= ($validation && $validation->hasError('doi')) ? 'is-invalid': '' ?>"
                    value="<?= (isset($reference) && $reference['doi']) ? $reference['doi'] : ''; ?>"
                  >
                  <p class="error" id="doi_error"><?php echo ($validation && $validation->getError('doi')) ? $validation->getError('doi') : '' ; ?></p>
                </div>
                <!-- <p class="error" id="doi-error"></p> -->
              <?php } ?>
              <!-- Common Fields -->
              <div class="form-group">
                <label for="abstract" class="required">Abstrak</label>
                <textarea
                  name="abstract"
                  class="form-control <?= ($validation && $validation->hasError('abstract')) ? 'is-invalid' : '' ?>"
                  id="abstract" rows="9"
                  placeholder="Abstrak"
                ><?= (isset($reference) && $reference['abstract']) ? $reference['abstract'] : ''; ?></textarea>
                <p class="error" id="abstract_error"><?php echo ($validation && $validation->getError('abstract')) ? $validation->getError('abstract') : '' ; ?></p>
              </div>
              <div class="form-group">
                <label for="keywords" class="required">Kata Kunci</label>
                <input
                  type="text"
                  name="keywords"
                  id="keywords"
                  class="form-control <?= ($validation && $validation->hasError('keywords')) ? 'is-invalid' : '' ?>"
                  placeholder="Kata Kunci"
                  value="<?= (isset($reference) && $reference['keywords']) ? $reference['keywords'] : ''; ?>"
                >
                <p class="error" id="keywords_error"><?php echo ($validation && $validation->getError('keywords')) ? $validation->getError('keywords') : ''; ?></p>
              </div>
              <a href="my-reference">
                <button type="button" id="batal" class="btn btn-outline-primary">Batal</button>
              </a>
              <button type="submit" id="simpan" class="btn btn-primary me-2">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<script src="assets/js/form-validation.js"></script>
<script>
  $(document).ready(function(){
    $('#upload_statement_letter').click(function(e) {
      e.preventDefault();
      $('#statement_letter').click();
    });
    $('#statement_letter').change(function() {
      const file = $('#statement_letter')[0].files[0].name;
      $('#statement_letter_name').val(file);
    });

    $('#upload_reference_file').click(function(e) {
      e.preventDefault();
      $('#reference_file').click();
    });

    $('#reference_file').change(function(e) {
      const file =  $('#reference_file')[0].files[0].name;
      $('#reference_filename').val(file);
    });

    $('Form').submit(function(e) {
      e.preventDefault();
      const rules = {
        abstract: 'abstract_error',
        keywords: 'keywords_error',
        journal_name: 'journal_name_error',
        volume: 'volume_error',
        doi: 'doi_error',
        publication_date: 'publication_date_error',
        reference_link: 'reference_link_error',
        proceedings_title: 'proceedings_title_error',
        reference_file: 'reference_file_error',
        statement_letter: 'statement_letter_error'
      }
      clearErrors(rules);
      const formReference = JSON.parse(localStorage.getItem('formReference'));
      const { reference_type, published } = formReference;

      const data = new FormData(this);
      $.ajax({
        url: '/insert-new-reference',
        type: 'POST',
        data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(res) {
        // const result = JSON.parse(res);
          const { status, errors } = res;
          if (errors) {
          
            setErrors(rules, errors);
          } else if (status === 'success') {
            $.get('/alert?status='+status+'&message=Data Berhasil Disimpan&next_location=my-reference', function(res) {
              $('#alert').html(res);
            });
          }
        },
        error: function (xhr) {
          $.get('/alert?status='+status+'&message=Terjadi kesalahan sistem!', function(res) {
              $('#alert').html(res);
            });
        }
      });
    });

  });
</script>
<?= $this->endSection('content'); ?>