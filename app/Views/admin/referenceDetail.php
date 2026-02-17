<?= $this->extend('layout/layoutAdmin'); ?>
<?= $this->section('content'); ?>
<style>
  .modal-content {

  }

</style>
 <div class="content-wrapper">
  <div id="alert" style="display:none">
    <div class="notification">
      <div class="notification-content">
        <div class="form-group">
          <h5>Dokumen Tidak Valid</h5>
          <p>Beritahu penulis alasan penolakan ini</p>
          <input type="hidden" id="doc-name" />
          <textarea  name="rejection-message" id="rejection-message" class="form-control" placeholder="Ketik alasan berkas tidak valid"></textarea>
        </div>
        <div class="notif-footer">
          <button type="button" class="btn btn-outline-primary btn-sm" id="close-rejection">Batal</button>
          <button type="button" class="btn btn-outline-primary btn-sm" id="send-rejection">Kirim</button>
        </div>
      </div>
    </div>
  </div>
		<div class="row">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card-body">
            <div class="publisher d-flex">
                  <?php
                if($referenceDetail['reference_type_id'] == 1) { ?>
                    <div class="badge badge-success"><?=$referenceDetail['reference_type_name']?></div>
                     <?php if($referenceDetail['published_external'] == 1) { ?>
                      <div style="margin-left:0.3rem;"><?=$referenceDetail['journal_name'] ?></div>
                    <?php } ?>
                <?php } 
                else if ($referenceDetail['reference_type_id'] == 2) { ?>
                  <div class="badge badge-info"><?=$referenceDetail['reference_type_name']?></div>
                  <div style="margin-left:0.3rem;"><?=$referenceDetail['proceedings_title'] ?></div>
                <?php } else if ($referenceDetail['reference_type_id'] == 3) { ?>
                  <div class="badge badge-warning"><?=$referenceDetail['reference_type_name']?></div>
                <?php } else if ($referenceDetail['reference_type_id'] == 4) { ?>
                  <div class="badge badge-danger"><?=$referenceDetail['reference_type_name']?></div>
                <?php }
              ?> 
            </div>
            <h1><?= $referenceDetail['title']; ?></h1>
            <div class="author-detail">
              <?php foreach ($authors as $author) {
                if ($author['external_author_name']) {
                  echo $author['external_author_name'].' | ';
                } else {
                  echo $author['fullname'].' | ';
                }
              } ?>
            </div>
            <?= $referenceDetail['abstract']; ?>
              <p>
            <div class="keywords"><strong>Keywords&ensp;: </strong><?= $referenceDetail['keywords']; ?></div>
            <?php if ($firstSupervisor !== '')  {?>
              <div class="keywords"><strong>Pembimbing 1&ensp;: </strong>&ensp;<?= $firstSupervisor; ?></div>
              <div class="keywords"><strong>Pembimbing 2&ensp;: </strong>&ensp;<?= $secondSupervisor; ?></div>
            <?php } ?>
            <?php if ($referenceDetail['doi']) {?><div class="keywords"><strong>Doi&ensp;: </strong>&ensp;<a href="<?= $referenceDetail['doi']; ?>" target="_blank" and rel="noopener noreferrer"><?= $referenceDetail['doi']; ?></a></div> <?php } ?>
            <?php if ($referenceDetail['publication_date']) {?><div class="keywords"><strong>Tanggal Terbit&ensp;: </strong>&ensp;<?= $referenceDetail['publication_date']; ?></div> <?php } ?>
            <?php if ($referenceDetail['proceedings_city']) {?><div class="keywords"><strong>Lokasi Prosiding&ensp;: </strong>&ensp;<?= $referenceDetail['proceedings_city']; ?></div> <?php } ?>
            </p>
            </p>
              <table class="table table-hover" style="max-width: 100%;">
                <thead>
                  <tr>
                    <th>Jenis Dokumen</th>
                    <th>Tindakan</th>
                    <th style="text-align:center">Hasil Pemeriksaan</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
							  <tbody>
                  <tr>
                    <td>Dokumen Referensi </td>
                    <td><button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#reference-modal" id="checkReference"><i class="fa fa-eye" aria-hidden="true"></i> Periksa</button></td>
                    <td style="text-align:center; font-size:24px" id="reference-check" >
                      <?= ($referenceDetail['reference_validation'] == 'VALID') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>'; ?>
                    </td>
                    <td><?= $referenceDetail['message_reference'] ?></td>
                  </tr>
                  <?php if(!$referenceDetail['published_external']) { ?>
                    <tr>
                      <td>Surat Pernyataan Penulis </td>
                      <td><button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#statement-letter-modal" id="checkStatement'"><i class="fa fa-eye" aria-hidden="true"></i> Periksa</button></td>
                      <td style="text-align:center; font-size:24px" id="statement-letter-check" >
                        <?= ($referenceDetail['statement_letter_validation'] == 'VALID') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>'; ?>
                      </td>
                      <td><?= $referenceDetail['message_statement_letter'] ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            </div>
            <div class="d-flex" style="margin-top:1rem">
              <form>
                <input type="hidden" name="statement-letter-validation" />
                <input type="hidden" name="reference-validation" />
                <button
                  type="button"
                  id="accept-btn"
                  class="btn btn-primary btn-fw"
                  data-toggle="modal"
                  data-target="#approval-modal"
                  data-body="Apakah anda yakin menyetujui referensi ini?"
                 >
                    Terima
                </button>
                <button
                  type="button"
                  id="reject-btn"
                  class="btn btn-info btn-fw"
                  style="margin-left:1rem"
                  data-toggle="modal"
                  data-target="#approval-modal"
                  data-body="Apakah anda yakin menolak referensi ini?">
                    Tolak
                </button>
                <a href="/admin" >
                  <button type="button" class="btn btn-outline-primary btn-fw" style="margin-left:1rem">Kembali</button>
                </a>
              </form>
            </div>
            
          </div>
      </div>
		</div>
	</div>
</div>

<!-- Modal -->
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="approval-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="approval" id="approval" />
      <div class="modal-body" style="text-align:center">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="approval-btn" style="display:none">Ya</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="statement-letter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Surat Pernyataan Penulis</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding:1rem; text-align:center">
        <iframe src="/show-statement-letter/<?= $referenceDetail['statement_letter']; ?>" style="width: 100%;" height="500"></iframe> 
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-sm btn-primary" id="valid-statement-letter">Valid</button>
        <button type="button" class="btn btn-sm btn-info" id="invalid-statement-letter" data-toggle="modal" data-target="#rejeced-modal">Tidak Valid</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="reference-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Dokumen Referensi</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe src="/show-reference-file/<?= $referenceDetail['reference_file']; ?>" style="width: 100%;" height="500"></iframe> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-sm btn-primary" id="valid-reference">Valid</button>
        <button type="button" class="btn btn-sm btn-info" id="invalid-reference">Tidak Valid</button>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    $('#valid-reference').click(function() {
      $.post('/validate-document',
        { refId: '<?= $referenceDetail['reference_id'] ?>', docName: 'reference', docvalidation: 'VALID' },
        function(res) {
          $('#reference-check').html('<i class="fa fa-check"></i>');
          $('#reference-modal .close').click();
      });
    })

    $('#invalid-reference').click(function() {
      $('#reference-modal .close').click();
      $('#alert').css('display', 'flex');
      $('#doc-name').val('reference');
      // $.post('/validate-document',
      //   { refId: "", docName: "reference", docvalidation: "INVALID" },
      //   function(res) {
      //     $('#reference-check').html('<i class="fa fa-close"></i>');
      //     $('#reference-modal .close').click();
      // });
    })

    $('#valid-statement-letter').click(function() {
      $.post('/validate-document',
        { refId: '<?= $referenceDetail['reference_id'] ?>', docName: 'statement_letter', docvalidation: 'VALID' },
        function(res) {
          $('#statement-letter-check').html('<i class="fa fa-check"></i>');
          $('#statement-letter-modal .close').click();
      });
    });
    $('#invalid-statement-letter').click(function() {
      $('#statement-letter-modal .close').click();
      $('#alert').css('display', 'flex');
      $('#doc-name').val('statement_letter');
    });
    $('#accept-btn').click(function (event) {
        $.post('/check-document-validation', {refId: '<?= $referenceDetail['reference_id'] ?>'}, function(res) {
          const data = JSON.parse(res);
          const { document_status, reference_validation, statement_letter_validation, published_external } = data;
          if (document_status === 'ACCEPTED') {
            $('#approval-modal .modal-body').text("Anda telah menyetujui referensi ini");
          } else if ((!published_external && reference_validation === 'VALID' && statement_letter_validation === 'VALID')
            || (published_external && reference_validation === 'VALID')) {
            $('#approval-modal .modal-body').text("Apakah anda yakin menyetujui referensi ini?");
            $('#approval-modal #approval').val("ACCEPTED");
            $('#approval-btn').removeAttr('style');
          } else {
            $('#approval-modal .modal-body').text("Referensi ini belum divalidasi. Mohon validasi referensi sebelum menyetujui/menolak referensi");
          }    
        });
    });
    $('#reject-btn').click(function (event) {
      $.post('/check-document-validation', {refId: '<?= $referenceDetail['reference_id'] ?>'}, function(res) {
          const data = JSON.parse(res);
          const { document_status, reference_validation, statement_letter_validation, published_external } = data;
          if ((!published_external && !reference_validation && !statement_letter_validation)
            || (published_external && !reference_validation)) {
            $('#approval-modal .modal-body').text("Referensi ini belum divalidasi. Mohon validasi referensi sebelum menyetujui/menolak referensi");
          } else if ((!published_external && (reference_validation === 'INVALID' || statement_letter_validation === 'INVALID')) ||
          (published_external && reference_validation === 'INVALID')) {
            $('#approval-modal .modal-body').text("Apakah anda yakin menolak referensi ini?");
            $('#approval-modal #approval').val("REJECTED");
            $('#approval-btn').removeAttr('style');
          } else {
            $('#approval-modal .modal-body').text("Anda tidak dapat menolak referensi ini karena dokumen persyaratan valid");
          }   
        });
    });

    $('#approval-btn').click(function() {
      $.post('/update-reference-status', { refId: '<?= $referenceDetail['reference_id'] ?>', status: $('#approval').val() }, function(res) {
        $('#approval-modal .close').click();
      });
    });

    $('#close-rejection').click(function() {
      $('#alert').css('display', 'none');
    });
    $('#send-rejection').click(function() {
      rejectionMessage = $('#rejection-message').val();
      docName = $('#doc-name').val();
      $.post('/validate-document',
        { refId: '<?= $referenceDetail['reference_id'] ?>', docName, docvalidation: 'INVALID', rejectionMessage },
          function(res) {
            if (docName === 'statement_letter') {
              $('#statement-letter-check').html('<i class="fa fa-close"></i>');
            } else {
              $('#reference-check').html('<i class="fa fa-close"></i>');
            }
            $('#alert').css('display', 'none');
            $('#rejection-message').val();
        });
    });
  });
</script>
<?= $this->endSection('content'); ?>