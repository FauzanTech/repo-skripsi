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
            <h4 class="card-title">Disiplin Ilmu</h4>
            <div style="float: right">
              <button type="button" class="btn btn-primary" style="margin-right: 1rem;" data-bs-toggle="modal" data-bs-target="#myModal" data-mode="<?= MyConstants::MODE_INSERT; ?>">Bidang Ilmu Baru</button>
            </div>
            <table class="table table-hover" style="max-width: 100%;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Disiplin Ilmu</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach($disciplines as $row) { ?>
                <tr>
                  <td width="5%"><?= $no; ?></span></td>
                  <td><?= $row['disciplines_name']; ?></td>
                  <td>
                    <i class="fa fa-pencil"
                      aria-hidden="true"
                      data-bs-toggle="modal"
                      data-bs-target="#myModal"
                      data-name="<?= $row['disciplines_name']; ?>"
                      data-id="<?= $row['disciplines_id']; ?>"
                      data-mode="<?= MyConstants::MODE_UPDATE; ?>"
                    >
                    </i>
                  </td>
                </tr>
                <?php $no++; 
              } ?>
              </tbody>
            </table>
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
        <h5 class="modal-title" id="exampleModalLongTitle">Disiplin Ilmu Baru</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="padding: 1rem;">
          <div class="form-group">
            <label for="name">Nama Disiplin Ilmu</label>
            <input type="text" class="form-control" id="name" placeholder="Nama Disiplin Ilmu" style="outline:none">
            <p class="error" id="name_error"></p>
          </div>
          <input type="hidden" id="id" />
          <input type="hidden" id="mode" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close">Batal</button>
          <button type="button" class="btn btn-primary" id="submit-new-diciplines">Simpan</button>
        </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $('#submit-new-diciplines').click(function() {
      $.post('/insert-diciplines',
        { name: $('#name').val(), id: $('#id').val(), mode: $('#mode').val()},
        function(res) {
          data = JSON.parse(res);
          $.get('/alert?status='+data.status+'&message='+data.message, function(res) {
            $('#alert').html(res);
          });
          $('#close').click();
          $('#id').val();
        
        });
    });
    $('#myModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const name = button.data('name');
      const id = button.data('id');
      const mode = button.data('mode');
      $('#name').val(name);
      $('#id').val(id);
      $('#mode').val(mode);
      mode === 'insert' ? $('.modal-title').text('Disiplin Ilmu Baru') : $('.modal-title').text('Ubah Disiplin Ilmu')
    });
  });
</script>
<?= $this->endSection('content'); ?>