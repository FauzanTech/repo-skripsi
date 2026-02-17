<?= $this->extend('layout/layoutAdmin'); ?>
<?= $this->section('content'); ?>
  <div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Daftar Admin
			</h3>
			<nav aria-label="breadcrumb">
				<ul class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
					</li>
				</ul>
			</nav>
		</div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body" style="overflow: auto">
            <h4 class="card-title">Daftar Anggota</h4>
            <div style="float: right">
              <div class="d-flex">
                <button type="submit" class="btn btn-gradient-primary" style="margin-right: 1rem;" data-toggle="modal" data-target="#exampleModalCenter">Member Baru</button>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-import">Import Member</button>  
              </div>
            </div>
            <table class="table table-hover" style="max-width: 100%;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Jabatan</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach($member as $row) { ?>
                <tr>
                  <td width="5%"><?= $no; ?></span></td>
                  <td><?= $row['fullname']; ?></td>
                  <td><?= $row['email']; ?></td>
                  <td><?= $row['role']; ?></td>
                  <td><?= $row['status']; ?></td>
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #fff">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Admin Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="padding: 0 2rem;">
          <div class="form-group">
            <label for="fullname">Nama Lengkap (Tanpa Gelar)</label>
            <input type="text" class="form-control" id="fullname" placeholder="Nama Lengkap" style="outline:none">
            <p class="error" id="fullname_error"></p>
          </div>
          <div class="form-group">
            <label for="identity_number">NIP</label>
            <input type="text" class="form-control" id="identity_number" placeholder="NIP" style="outline:none">
            <p class="error" id="identity_number_error"></p>
          </div>
          <div class="form-group">
            <label for="email">Email </label>
            <input type="email" class="form-control" id="email" placeholder="Email" style="outline:none">
            <p class="error" id="email_error"></p>
          </div>
          <div class="form-group">
            <label for="email">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" style="outline:none">
            <p class="error" id="email_error"></p>
          </div>
          <div class="form-group">
            <label for="email">Konfirmasi Password</label>
            <input type="password" class="form-control" id="confirmed-password" placeholder="Konfirmasi Password" style="outline:none">
            <p class="error" id="email_error"></p>
          </div>
          <div class="form-group">
            <label for="role">Jabatan </label>
            <select class="form-select" id="role" name="role">
              <option value="admin">Admin Repository</option>
            </select>
            <p class="error" id="role_error"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="submit-new-member">Simpan</button>
        </div>
    </div>
  </div>
</div>

<form action="<?=site_url('import-member')?>" method="POST" enctype="multipart/form-data" >
  <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="background: #fff">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Import Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="padding: 0 2rem;">
          <div class="form-group" style="margin-top:2.5rem;">
            <label for="fileExcel">Masukkan File</label>
            <input type="file" name="fileExcel" class="form-control" id="fileExcel" placeholder="Required Excel" style="outline:none" accept=".xls, .xlsx">
            <p class="error" id="fullname_error"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-preview" >Preview</button>
        </div>
      </div>
    </div>
  </div>
</form>

<script src="assets/js/form-validation.js"></script>
<script>
  $(document).ready(function() {
    $('#submit-new-member').click(function() {
      const prodi_id = $('#prodi_id').val();
      const fullname = $('#fullname').val();
      const identityNumber = $('#identity_number').val();
      const email = $('#email').val();
      const role = $('#role').val();
      const rules = [
        { id: 'prodi_id_error', rule: 'required', error_message: 'Prodi wajib diisi', value: prodiId },
        { id: 'fullname_error', rule: 'required', error_message: 'Nama Lengkap (tanpa gelar) wajib diisi', value: fullname},
        { id: 'identity_number_error', rule: 'required', error_message: 'NIM / NIDN wajib diisi', value: identityNumber },
        { id: 'email_error', rule: 'required', error_message: 'Email wajib diisi', value: email},
        { id: 'role_error', rule: 'required', error_message: 'Jabatan wajib diisi', value: role}
      ];

      const valid = validation(rules);
      if (valid) {
        $.post('/insert-new-member', {
          prodi_id,
          fullname,
          identity_number: identityNumber,
          email,
          role,
          phone_number
        }, function(res) {
          result = JSON.parse(res);
          alert('Data berhasil disimpan!');
        });
      }
    });
  

  });
</script>
<?= $this->endSection('content'); ?>