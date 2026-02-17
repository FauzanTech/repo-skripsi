<?= $this->extend('layout/layoutAdmin'); ?>
<?= $this->section('content'); ?>
  <div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Jenis Referensi
			</h3>
		</div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body" style="overflow: auto">
            <h4 class="card-title">Jenis Referensi</h4>
            <table class="table table-hover" style="max-width: 100%;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Jenis Referensi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach($referenceType as $row) { ?>
                <tr>
                  <td width="5%"><?= $no; ?></span></td>
                  <td><?= $row['reference_type_name']; ?></td>
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
<?= $this->endSection('content'); ?>