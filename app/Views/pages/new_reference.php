<?php 
  use Config\MyConstants;
  $session = session();
  
  echo $this->extend('layout/layout');
  echo $this->section('content');
  $published = false;
  $referenceData = session()->get('referenceData');
  $internalReferenceType = [3, 4, 5];
  if ($referenceData && $referenceData['reference_id'] != '') {

    $db = db_connect();
    $refId = $referenceData['reference_id'];
    $res = $db->query("SELECT * from reference where reference_id='$refId'");
    $reference = $res->getResultArray()[0];
  }
?>
<style>
  .card {
    margin-bottom: 1rem;
  }
  .btn-inverse-danger {
    margin-top: 1.5rem;
  }
</style>
 <!-- main-panel starts -->
<div class="main-panel-full">
  <div id="alert"></div>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
          <i class="mdi mdi-home"></i>
        </span> <?= ($method == 'new') ? 'Referensi Baru' : 'Edit Referensi'; ?>
      </h3>
    </div>
    <form class="forms-sample" id="my_form" method="POST" action="/insert-new-reference" enctype="multipart/form-data">
      <div class="row justify-content-center">
        <div class="col-lg-9 col-md-10 col-sm-12 col-xs-12 ">
          <div class="card">
            <div class="card-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Tipe Koleksi & Status Publikasi</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link disabled" id="author-tab" data-bs-toggle="tab" data-bs-target="#tab-author" type="button" role="tab" aria-controls="profile" aria-selected="false">Penulis</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link disabled" id="reference-detail-tab" data-bs-toggle="tab" data-bs-target="#reference-detail" type="button" role="tab" aria-controls="contact" aria-selected="false">Detail Koleksi</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link disabled" id="discipline-tab" data-bs-toggle="tab" data-bs-target="#tab-discipline" role="tab" aria-controls="tab-discipline" aria-selected="false">Subject</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link disabled" id="deposit-tab" data-bs-toggle="tab" data-bs-target="#tab-deposit" type="button" role="tab" aria-controls="tab-deposit" aria-selected="false">Deposit</button>
                </li>
              </ul>

              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <?= $this->include('components/form_reference_type', ['reference' => $reference ?? [], 'referenceType' => $referenceType, 'reportType' => $reportType, 'reportProgram', $reportProgram]) ?>
                </div>
                <div class="tab-pane fade" id="tab-author" role="tabpanel" aria-labelledby="author-tab">
                  <?= $this->include('components/form_author', ['reference' => $reference ?? [], 'author' => $authors ?? []]) ?>
                </div>
                <div class="tab-pane fade" id="tab-deposit" role="tabpanel" aria-labelledby="deposit-tab">
                  <?= $this->include('/components/form_deposit') ?>
                </div>
    
                <div class="tab-pane fade" id="reference-detail" role="tabpanel" aria-labelledby="reference-detail-tab">
                  <?= $this->include('components/form_reference_detail', ['reference' => $reference ?? []]) ?>
                </div>
                <div class="tab-pane fade" id="tab-discipline" role="tabpanel" aria-labelledby="discipline-tab">
                  <?= $this->include('components/form_subjects', ['reference' => $reference ?? [], 'subjects' => $subjects, 'disciplines' => $disciplines]) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<script>
$(document).ready(function() {
  let reference = <?= json_encode($reference ?? []) ?>;

  if (Object.keys(reference).length > 0) {
    const reference_type = parseInt(reference.reference_type_id);
    const published = parseInt(reference.published_external);
    show_all_items(reference_type, published);
  }

    // // Handle form submission
    $('#submit_draft').click(function(e) {
      e.preventDefault();
      const data = {
        ...get_form_data(),
        ...get_all_authors(),
        ...get_all_detail(),
        reference_id: $('#reference_id').val(),
        subject_ids: get_all_subjects(),
        status: '<?= MyConstants::DRAFT ?>',
        method: '<?= isset($reference) && $reference['reference_id'] ? "edit" : "new"?>'
      };
      send_data(data);

    });
    $('#submit').click(function(e) {
      e.preventDefault();
       const data = {
        ...get_form_data(),
        ...get_all_authors(),
        ...get_all_detail(),
        reference_id: $('#reference_id').val(),
        subject_ids: get_all_subjects(),
        status: '<?= MyConstants::UNDER_REVIEW ?>',
        method: '<?= isset($reference) && $reference['reference_id'] ? "edit" : "new"?>'
      };
      send_data(data);
    });

    function send_data(data) {
      const formData = new FormData();
      for (const key in data) {
        if (Array.isArray(data[key])) {
          data[key].forEach(v => formData.append(`${key}[]`, v));
        } else {
          formData.append(key, data[key]);
        }
      }
     
      $.ajax({
        url:'/insert-new-reference',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(res) {
          const { status, errors } = res;
          if (errors) {
          
            setErrors(rules, errors);
          } else if (status === 'success') {
            let message = '';
            if (data.status === '<?= MyConstants::DRAFT ?>' && data.method === 'new') {
              message = 'Data berhasil disimpan sebagai draft'
            } else if (data.status === '<?= MyConstants::DRAFT ?>' && data.method === 'edit') {
              message = 'Draft berhasil diperbarui'
            } else if (data.status === '<?= MyConstants::UNDER_REVIEW ?>' && data.method === 'new') {
              message = 'Data berhasil disimpan disimpan. Data ini akan melalui proses review oleh admin'
            } else {
              message = 'Data berhasil diperbarui. Data ini akan melalui proses review oleh admin'
            }
            $.get('/alert?status='+status+'&message='+message+'&next_location=my-reference', function(res) {
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
    }

    function hide_all_items() {
      $('#thesis-detail, #thesis-author, #other-author, #proceeding-detail, #journal-detail, #form-abstract-in, #form-abstract-en, #form-keywords, #form-summary, #form-book-cover, #form-isbn, #form-report-program-id, #form-report-year, #form-peer-reviewed,  #form-publication-date, #form-book-publisher, #form-city-book-publisher, #form-publisher, #form-reference-link').addClass('d-none');
    }

    function show_all_items(reference_type, published) {
      
      if (reference_type === 1) {
       $('#form-keywords, #other-author, #form-abstract-in, #form-abstract-en').removeClass('d-none');
       if (published === 1) {
        $('#journal-detail, #form-publication-date').removeClass('d-none');
        $('#form-peer-reviewed').addClass('d-none');
       } else {
         $('#journal-detail, #form-publication-date').addClass('d-none');
         $('#form-peer-reviewed').removeClass('d-none');
       }
      } else if (reference_type === 2) {
       $('#proceeding-detail, #form-publication-date, #form-keywords, #other-author, #form-abstract-in, #form-abstract-en').removeClass('d-none');
      } else if ([3, 4, 5].includes(reference_type)) {
        $('#thesis-detail, #thesis-author, #form-abstract-in, #form-abstract-en, #form-keywords').removeClass('d-none');
      } else if([6,7,8].includes(reference_type)) {
        $('#form-summary, #other-author').removeClass('d-none');
        if (published === 1) {
          $('#form-peer-reviewed').addClass('d-none');
          $('#form-book-publisher, #form-city-book-publisher, #form-isbn').removeClass('d-none');
        } else {
          $('#form-peer-reviewed').removeClass('d-none');
           $('#form-book-publisher, #form-city-book-publisher, #form-isbn').addClass('d-none');
        }
      } else if ([ 9, 10, 11, 12].includes(reference_type)){ // tipe laporan, dataset, paten, eksperimen
        $('#form-summary, #other-author, #form-peer-reviewed').removeClass('d-none');
        if (published === 1) {
          $('#form-publisher, #form-reference-link, #form-publication-date, form-reference-link').removeClass('d-none');
        } else {
          $('#form-publisher, #form-reference-link, #form-publication-date').addClass('d-none');
        }

        if (reference_type === 9) { // ini adalah tipe laporan
          $('#form-report-program-id, #form-report-year').removeClass('d-none');
        } else {
          $('#form-report-program-id, #form-report-year').addClass('d-none');
        }

        if (reference_type === 10) { // ini adalah paten
          $('#form-peer-reviewed').addClass('d-none');
        }
      }
    }

    function get_form_data() {
      let reference_type = $('#reference-type').val();
      reference_type = parseInt(reference_type);
      let published = $('input[name="published"]:checked').val();

      const forms = {
        reference_type,
        published,
        visible_to: $('#visible_to').val(),
        file_type: $('#file_type').val(),
        embargo_expiry_date: $('#embargo_expiry_date').val(),
        language: $('#language').val()
      };
  
      return forms;
    }

    function get_selected_subjects() {
      const subjects_ids = $('input[name="subject_id[]"]:checked').map(function () {
        return $(this).val();
      });
      return subjects_ids;
    }

    function get_all_authors() {
      let authors = [];
      const number_author = $('#number_author').val();
      const reference_type = $('#reference_type').val();
      const affiliations = $("select[name='affiliation[]']").map(function(){return $(this).val();}).get();
      
      for (i = 1; i<= parseInt(number_author); i++) {
        if (affiliations[i-1] === '2') {
          authors.push($('#author_name_'+i).val());
        } else {
          authors.push($('#author_id_'+i).val());
        }
      }

      const forms = {
        affiliations,
        authors,
        first_supervisor: $('#first_supervisor_id').val(),
        second_supervisor: $('#second_supervisor_id').val(),
        description: $('select[name="author-desc[]"]').map(function(){return $(this).val();}).get(),
      }

      return forms;
    }

    function get_all_detail() {
      const forms = {
        title: $('#title').val(),
        keywords: $('#keywords').val(),
        abstract_in: $('#abstract_in').val(),
        abstract_en: $('#abstract_en').val(),
        reference_filename: $('#reference_filename').val(),
        reference_file: $('#reference_file')[0] && $('#reference_file')[0].files[0],
        statement_letter: $('#statement_letter')[0] && $('#statement_letter')[0].files[0],
        summary: $('#summary').val(),
        book_cover: $('#book_cover').val(),
        publisher: $('#publisher').val(),
        doi: $('#doi').val(),
        proceedings_title: $('#proceedings_title').val(),
        journal_name: $('#journal_name').val(),
        publication_date: $('#publication_date').val(),
        start_page: $('#start_page').val(),
        end_page: $('#end_page').val(),
        volume: $('#volume').val(),
        isbn: $('#isbn').val(),
        peer_reviewed: $('#peer_reviewed').val(),
        book_publisher: $('#book_publisher').val(),
        city_book_publisher: $('#city_book_publisher').val(),
        report_program_id: $('#report_program_id').val(),
        report_year: $('#report_year').val(),
        publisher: $('#publisher').val(),
        reference_link: $('#reference_link').val(),
      };
      return forms;
    }

    function get_all_subjects () {
      const subjects = $('input[name="subject_id[]"]:checked')
                .map(function() {
                    return $(this).val();
                }).get();
      return subjects;
          
    }

    function select_abstract_language() {
      const lan = $('#language').val();
			const reference_type = $('#reference_type').val();
			const labelin = $('#form-abstract-in').find("label");
			const labelen = $('#form-abstract-en').find("label");
			if (![3,4,5].includes(parseInt(reference_type)) ) {
				if (lan === 'in') {
					labelin.addClass('required');
					labelen.removeClass('required');
				} else {
					labelen.addClass('required');
					labelin.removeClassClass('required');
				}
			}
    }


    $('input[name="published"], #reference-type, #language').on("click change", function() {
      hide_all_items();
      let { reference_type, published } = get_form_data();
      published = parseInt(published);
      show_all_items(reference_type, published);
      select_abstract_language();
    });

    $('#reference-type').change(function() {
      var type = $("#reference-type").val();
      type = parseInt(type);
      if ([1, 2, 3, 4, 5, 6, 7, 8, 9].includes(type)) {
        $('#file_type').prop('disabled', true);
      } else {
        $('#file_type').prop('disabled', false);
      }

      if ([3, 4, 5].includes(type)) {
        $('#visible_to').prop('disabled', true);
        $('#license').prop('disabled', true);
        $('#embargo_expiry_date').prop('disabled', true);
        $('input[name="published"][value="0"]').prop('checked', true);
        $('input[name="published"').prop('disabled', true);
      } else {
        $('#visible_to').prop('disabled', false);
        $('#license').prop('disabled', false);
        $('#embargo_expiry_date').prop('disabled', false);
        $('input[name="published"][value="0"]').prop('checked', false);
        $('input[name="published"').prop('disabled', false);
      }
    });

    $('#next_btn_collection_type').click(function() {
      const { reference_type, published, file_type, visible_to, embargo_expiry_date } = get_form_data();
      const rules = [
        { id: 'reference_type_error', rule: 'required', error_message: 'Bentuk koleksi wajib diisi', value: reference_type, form_id: 'reference-type' },
        { id: 'published_error', rule: 'required', error_message: 'Status publikasi wajib diisi', value: published, form_id: 'is_published'},
        { id: 'file_type_error', rule: 'required', error_message: 'Jenis berkas wajib diisi', value: file_type, form_id: 'file_type'},
        { id: 'language_error', rule: 'required', error_message: 'Bahasa wajib diisi', value: language, form_id: 'language'},
        { id: 'visible_to_error', rule: 'required', error_message: 'Hak akses wajib diisi', value: visible_to, form_id: 'visible_to'},
      ];

      if (visible_to === 'staff-only') {
        rules.push(
          { id: 'embargo_expiry_date_error', rule: 'required', error_message: 'Tanggal berakhirnya embargo wajib diisi', value: embargo_expiry_date, form_id: 'embargo_expiry_date'},
        )
      }
      clearValidation(rules); // remove all error message in the previous click
      const valid = validation(rules);
      if (valid) {
        $('#author-tab').removeClass('disabled');
        nextTab();
      } else {
        $('#author-tab').addClass('disabled');
      }
    });

    $('#next_btn_author').click(function(e) {
      e.preventDefault();
      
      let reference_type = $('#reference-type').val();
      reference_type = parseInt(reference_type);
      const number_author = $('#number_author').val();
      const { first_supervisor, second_supervisor, affiliations, authors, description } = get_all_authors();
      const forms = [];

      if ([3, 4, 5].includes(reference_type)) { // tipe skripsi, tesis, disertasi
        forms.push(
          {id: 'first_supervisor_error', value: first_supervisor, rule: 'required', error_message: 'Dosen pembimbing 1 wajib diisi', form_id: 'first_supervisor'},
          {id: 'second_supervisor_error', value: second_supervisor, rule: 'required', error_message: 'Dosen pembimbing 2 wajib diisi', form_id: 'second_supervisor'}
        );
      } else {
        for (i = 1; i <= parseInt(number_author); i++) {
          forms.push(
            { id: 'affiliation-error-'+i, value: affiliations[i-1], rule: 'required', error_message: 'Afiliasi Penulis harus dipilih' },
            { id: 'author-error-'+i, value: authors[i-1], rule: 'required', error_message: 'Nama Penulis harus diisi' },
          );
        }
      }
      clearValidation(forms); // remove all error message in the previous click
      if (validation(forms)) {
        $('#reference-detail-tab').removeClass('disabled');
        nextTab();
      } else {
        $('#reference-detail-tab').addClass('disabled');
      }
    });

    $("#next_btn_reference-detail").click(function () {
    
      const forms = get_all_detail();
      
      const {
        title, keywords, abstract_in, abstract_en,
        journal_name, volume, publication_date, summary,
        proceedings_name, proceedings_city, peer_reviewed, book_publisher, isbn, city_book_publisher,
        report_program_id, report_year, publisher,
        reference_link,
      } = forms;
      let { reference_type, published } = get_form_data();
      published = parseInt(published);
      const rules = [
        { id: 'title_error', rule: 'required', error_message: 'Judul karya wajib diisi', value: title, form_id: 'title' },
      ];
      if (published === 0) {
        rules.push(
          { id: 'reference_file_error', rule: 'required', error_message: 'Dokumen karya wajib diunggah', value: reference_file, form_id: 'reference_filename' }
        );
      } else {
        if (![6,7,8].includes(reference_type)) { // tidak berlaku untuk buku, buku chapter, dan monograf
          rules.push(
            { id: 'reference_link_error', rule: 'required', error_message: 'Tautan karya atau publikasi wajib diunggah', value: reference_link, form_id: 'reference_link' },
          );
        }
        rules.push(
          { id: 'publication_date_error', rule: 'required', error_message: 'Tanggal publikasi wajib diisi', value: publication_date, form_id: 'publication_date' }
        );
      }

      if ([1,2,3,4,5].includes(reference_type)) {
        rules.push(
          { id: 'keywords_error', rule: 'required', error_message: 'Kata kunci wajib diisi', value: keywords, form_id: 'keywords' },
        );
        if([3,4,5].includes(reference_type)) {
          rules.push(
            { id: 'abstract_en_error', rule: 'required', error_message: 'Abstrak Bahasa Inggris wajib diisi', value: abstract_en, form_id: 'abstract_en' },
            { id: 'abstract_in_error', rule: 'required', error_message: 'Abstrak Bahasa Indonesia wajib diisi', value: abstract_in, form_id: 'abstract_in' },
          );
        }
        if ([1,2].includes(reference_type) && language==='en') {
          rules.push(
            { id: 'abstract_en_error', rule: 'required', error_message: 'Abstrak Bahasa Inggris wajib diisi', value: abstract_en, form_id: 'abstract_en' },
          );
        }
        if ([1,2].includes(reference_type) && language==='in') {
          rules.push(
            { id: 'abstract_in_error', rule: 'required', error_message: 'Abstrak Bahasa Indonesia wajib diisi', value: abstract_in, form_id: 'abstract_in' }
          );
        }
        if (reference_type === 1 && published === 1) {
          rules.push(
            { id: 'journal_name_error', rule: 'required', error_message: 'Nama jurnal wajib diisi', value: journal_name, form_id: 'journal_name' },
            { id: 'volume_error', rule: 'required', error_message: 'Volume wajib diisi', value: volume, form_id: 'volume' }, 
          );
        } else if (reference_type === 2 && published === 1) {
          rules.push(
            { id: 'proceedings_title_error', rule: 'required', error_message: 'Nama Konferensi wajib diisi', value: proceedings_title, form_id: 'proceedings_title' },
            { id: 'publication_date_error', rule: 'required', error_message: 'Tanggal publikasi wajib diisi', value: publication_date, form_id: 'publication_date' }
          );
        }
      } else if ([6,7,8,9,10,11,12].includes(reference_type)) {
        rules.push(
          { id: 'summary_error', rule: 'required', error_message: 'Abstrak Bahasa Indonesia wajib diisi', value: summary, form_id: 'summary' }
        );
        if(published === 0) {
          if (reference_type !== 9 || reference_type !== 10) { // tidak berlaku untuk paten dan laporan
            rules.push(
              { id: 'peer_reviewed_error', rule: 'required', error_message: 'Peer review wajib diisi', value: peer_reviewed, form_id: 'peer_reviewed' }
            );
          }
        } else {
          
          if ([6,7,8].includes(reference_type)) { // tipe buku, buku chapter dan monograf
            rules.push(
             { id: 'isbn_error', rule: 'required', error_message: 'ISBN/ISSN  wajib diisi', value: isbn, form_id: 'isbn' },
             { id: 'book_publisher_error', rule: 'required', error_message: 'Penerbit buku wajib diisi', value: book_publisher, form_id: 'book_publisher' },
             { id: 'city_book_publisher_error', rule: 'required', error_message: 'Kota penerbit buku wajib diisi', value: city_book_publisher, form_id: 'city_book_publisher' },
             { id: 'publication_date_error', rule: 'required', error_message: 'Tanggal publikasi wajib diisi', value: publication_date, form_id: 'publication_date' },
            );
          } else if ([11, 12].includes(reference_type)) { // tipe dataset, eksperimen
            rules.push(
             { id: 'publisher_error', rule: 'required', error_message: 'Nama penerbit  wajib diisi', value: publisher, form_id: 'publisher' },
            );
          }
        }

        if (reference_type === 9) { // tipe laporan
          rules.push(
             { id: 'report_program_id_error', rule: 'required', error_message: 'Nama program wajib diisi', value: report_program_id, form_id: 'report_program_id' },
             { id: 'report_year_error', rule: 'required', error_message: 'Tahun pelaporan wajib diisi', value: report_year, form_id: 'report_year' }
          );
        }
      }

      clearValidation(rules); // clear previous error message
      if (validation(rules)) {
        $('#discipline-tab').removeClass('disabled');
        nextTab();
      } else {
        $('#discipline-tab').addClass('disabled');
      }
    });

    $('#next_btn_discipline').click(function() {
      const subjects = get_all_subjects();

      if (subjects.length === 0) {
        $('#subjects_error').removeClass('d-none');
        $('#subjects_error').text('Subjek belum dipilih. Klik disiplin ilmu di bawah ini dan centang subjek terkait');
      } else {
         $('#subjects_error').addClass('d-none');
        $('#deposit-tab').removeClass('disabled');
        nextTab();
      }
    })



    // coding chatgpt
    function updateButtons() {
      let current = $("#myTab .nav-link.active");

      // Disable Previous jika berada di tab pertama
      if (current.parent().prev().length === 0) {
          $("#prevBtn").prop("disabled", true);
      } else {
          $("#prevBtn").prop("disabled", false);
      }

      // Disable Next jika berada di tab terakhir
      if (current.parent().next().length === 0) {
          $("#nextBtn").prop("disabled", true);
      } else {
          $("#nextBtn").prop("disabled", false);
      }
    }

// Jalankan saat halaman dimuat
    updateButtons();

// Tombol Next
    function nextTab() {
      let current = $("#myTab .nav-link.active");
      let next = current.parent().next().find(".nav-link");

      if (next.length) {
          next.tab("show");
      }

      updateButtons();
    }
    // $("#reference-detail").on("click", function () {
    //   let current = $("#myTab .nav-link.active");
    //   let next = current.parent().next().find(".nav-link");

    //   if (next.length) {
    //       next.tab("show");
    //   }
    //   updateButtons();
    // });

// Tombol Previous
    $("#prevBtn, #prevBtn1, #prevBtn2").on("click", function () {
        let current = $("#myTab .nav-link.active");
        let prev = current.parent().prev().find(".nav-link");

        if (prev.length) {
            prev.tab("show");
        }
        updateButtons();
    });

  // Update tombol ketika tab berubah manual skitnya persaaanku
    $('#myTab .nav-link').on('shown.bs.tab', function () {
        updateButtons();
    });
   
  });
</script>

<?= $this->endSection('content'); ?>

