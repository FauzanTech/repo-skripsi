<?php 
use Config\MyConstants;
echo $this->extend('layout/layoutAdmin');
echo $this->section('content');
?>

  <div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Dashboard
      </h3> 
      <div id="alert"></div>
		</div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body" style="overflow: auto">
            <h4 class="card-title">Program Studi</h4>
            <div style="float: right">
              <button type="button" class="btn btn-primary" style="margin-right: 1rem;" data-bs-toggle="modal" data-bs-target="#myModal" data-mode="<?= MyConstants::MODE_INSERT; ?>">Prodi Baru</button>
            </div>
            <table class="table table-hover" style="max-width: 100%;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Program Studi</th>
                  <th>Jenjang Program Studi</th>
                  <th>Nama Program Studi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = $rowStart;
                foreach($prodi as $row) { ?>
                <tr>
                  <td width="5%"><?= $no; ?></span></td>
                  <td><?= $row['prodi_code']; ?></td>
                  <td><?= $row['prodi_strata']; ?></td>
                  <td><?= $row['prodi_name']; ?></td>
                  <td>
                    <i class="fa fa-pencil"
                        aria-hidden="true"
                        data-bs-toggle="modal"
                        data-bs-target="#myModal"
                        data-prodi_code="<?= $row['prodi_code']; ?>"
                        data-prodi_name="<?= $row['prodi_name']; ?>"
                        data-prodi_strata="<?= $row['prodi_strata']; ?>"
                        data-prodi_id="<?= $row['prodi_id']; ?>"
                        data-mode="<?= MyConstants::MODE_UPDATE; ?>"
                        >
                      </i>
                  </td>
                </tr>
                <?php $no++; 
              } ?>
              </tbody>
            </table>
            <?= $this->include('components/pagination', ['totalCount' => $totalCount, 'page' => $page, 'pageUrl' => $pageUrl]) ?>
          </div>
        </div>
      </div>
      
    </div>
  
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #fff">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Prodi Baru</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="padding: 1rem;">
          <div class="form-group">
            <label for="code">Kode Program Studi</label>
            <input type="text" class="form-control" id="code" placeholder="Kode Program Studi" style="outline:none">
            <p class="error" id="code_error"></p>
          </div>
          <div class="form-group">
            <label for="name">Nama Program Studi</label>
            <input type="text" class="form-control" id="name" placeholder="Nama Program Studi" style="outline:none">
            <p class="error" id="name_error"></p>
          </div>
        </div>
        <input type="hidden" id="prodi_id" />
        <input type="hidden" id="mode" />
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close">Batal</button>
          <button type="button" class="btn btn-primary" id="submit-new-prodi">Simpan</button>
        </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {

    $('#submit-new-prodi').click(function() {
      $.post('/insert-new-prodi',
        {
          name: $('#name').val(),
          code: $('#code').val(),
          prodi_id: $('#prodi_id').val(),
          mode: $('#mode').val()
        },
        function(res) {
          data = JSON.parse(res);
          $.get('/alert?status='+data.status+'&message='+data.message, function(res) {
              $('#alert').html(res);
            });
          $('#close').click();
          $('#prodi_id').val();
        });
    });

    $('#myModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const prodi_name = button.data('prodi_name');
      const prodi_id = button.data('prodi_id');
      const prodi_strata = button.data('prodi_strata');
      const prodi_code = button.data('prodi_code');
      const mode = button.data('mode');
      $('#name').val(prodi_name);
      $('#code').val(prodi_code);
      $('#prodi_id').val(prodi_id);
      $('#mode').val(mode);
      mode === 'insert' ? $('.modal-title').text('Prodi Baru') : $('.modal-title').text('Edit Baru')
    });
  });
</script>
<?= $this->endSection('content'); ?>