<?php
use Config\MyConstants;
echo $this->extend('layout/layoutAdmin');
echo $this->section('content');
?>
<?php $validation = session()->get('validation'); ?>

  <div class="content-wrapper">
    <div id="alert"></div>
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
            <h4 class="card-title">Daftar Admin</h4>
            <div style="float: right">
              <div class="d-flex">
                <button type="button" class="btn btn-gradient-primary" style="margin-right: 1rem;" data-toggle="modal" data-target="#myModal">Admin Baru</button>
              </div>
            </div>
            <table class="table table-hover" style="max-width: 100%;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>NIP</th>
                  <th>Email</th>
                  <th>Jabatan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                if (count($admin) == 0) { ?>
                  <tr>
                    <td colspan="5" style="text-align:center">Belum ada admin terdaftar</td>
                  </tr>
                <?php } else {
                foreach($admin as $row) { ?>
                  <tr>
                    <td width="5%"><?= $no; ?></span></td>
                    <td><?= $row['fullname']; ?></td>
                    <td><?= $row['identity_number']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['role']; ?></td>
                    <td><?= $row['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                    <td style="cusor:pointer">
                      <i class="fa fa-pencil"
                        aria-hidden="true"
                        data-toggle="modal"
                        data-target="#myModal"
                        data-fullname="<?= $row['fullname']; ?>"
                        data-identity_number="<?= $row['identity_number']; ?>"
                        data-email="<?= $row['email']; ?>"
                        data-role="<?= $row['role']; ?>"
                        data-user_id="<?= $row['user_id']; ?>"
                        data-status="<?= $row['status']; ?>"
                        data-mode="<?= MyConstants::MODE_UPDATE?>"
                        >
                      </i>
                      
                    </td>
                  </tr>
                  <?php $no++; 
                } } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #fff">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Admin Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form>
        <input type="hidden" id="user_id" />
        <div class="modal-body" style="padding: 2rem;">
          <div class="form-group">
            <label for="fullname">Nama Lengkap (Tanpa Gelar)</label>
            <input type="text" class="form-control" id="fullname" placeholder="Nama Lengkap" style="outline:none" name="fullname">
            <p class="error" id="fullname_error"></p>
          </div>
          <div class="form-group">
            <label for="identity_number">NIP</label>
            <input type="text" class="form-control" id="identity_number" placeholder="NIP" style="outline:none" name="identity_number">
            <p class="error" id="identity_number_error"></p>
          </div>
          <div class="form-group">
            <label for="email">Email </label>
            <input type="email" class="form-control <?php echo ($validation && $validation->getError('email')) ? 'is-invalid': ''; ?>" id="email" placeholder="Email" style="outline:none" name="email">
            <p class="error" id="email_error"><?php echo ($validation && $validation->getError('email')) ? $validation->getError('email'): ''; ?></p>
          </div>
          <div class="form-group">
            <label for="email">Kata Sandi</label>
            <input type="password" class="form-control" id="password" placeholder="Kata Sandi" style="outline:none" name="password">
            <p class="error" id="password_error"></p>
          </div>
          <div class="form-group">
            <label for="email">Konfirmasi Kata Sandi</label>
            <input type="password" class="form-control" id="confirmed_password" placeholder="Konfirmasi Kata Sandi" style="outline:none" name="confirmed_password">
            <p class="error" id="confirmed_password_error"></p>
          </div>
          <inpu type="hidden" id="mode" />
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="submit-new-member">Simpan</button>
        </div>
            </form>
    </div>
  </div>
</div>

<script src="assets/js/form-validation.js"></script>
<script>
  $(document).ready(function() {
   $('#submit-new-member').click(function() {
      const fullname = $('#fullname').val();
      const identityNumber = $('#identity_number').val();
      const email = $('#email').val();
      const password = $('#password').val();
      const confirmed_password = $('#confirmed_password').val();
      const status = $('#status').val();
      const user_id = $('#user_id').val();
      const mode = $('#mode').val();
      const rules = {
        fullname: 'fullname_error',
        email: 'email_error',
        password: 'password_error',
        confirmed_password: 'confirmed_password_error',
        identity_number: 'identity_number_error'
      };
      // fungsi clearErrors untuk menghapus pesan error yang sudah disubmit sebelunya
      clearErrors(rules);
        $.post('/insert-new-admin', {
          fullname,
          identity_number: identityNumber,
          email,
          password,
          confirmed_password,
          status,
          user_id,
          mode,
        }, function(res) {
          result = JSON.parse(res);
          const { status, message, errors} = result;
          // if (status === 'failed') {
          //   if (errors) {
          //     setErrors(rules, errors);
          //   } else if (message) {
          //     $.get('/alert?status='+status+'&message=Data Gagal Disimpan', function(res) {
          //     $('#alert').html(res);
          //   });
          //   $('#close').click();
          //   }
          // } else {
          //   $.get('/alert?status='+status+'&message='+message, function(res) {
          //     $('#alert').html(res);
          //   });
          //   $('#close').click();
          // }
           $.get('/alert?status='+status+'&message='+message, function(res) {
              $('#alert').html(res);
              $('#close').click();
            });
          $('#user_id').val('');
        }).fail(function() {
          // Handle HTTP-level errors
          $.get('/alert?status=failed&message=Maaf, terjadi kesalahan sistem!', function(res) {
              $('#alert').html(res);
              $('#close').click();
            });
            $('#user_id').val('');
        });
      // }
    });
 
    });
    $('#myModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const fullname = button.data('fullname');
      const identity_number = button.data('identity_number');
      const email = button.data('email');
      const role = button.data('role');
      const user_id = button.data('user_id');
      const mode = button.data('mode');
      $('#fullname').val(fullname);
      $('#identity_number').val(identity_number);
      $('#email').val(email);
      $('#user_id').val(user_id);
      $('#mode').val(mode);
      if (mode === 'update') {
        $('.modal-body').append(
        '<div class="form-group" id="status-container"><label for="status">Status </label><select class="form-control" id="status" name="status">' +
          '<option value="" selected>Pilih Status</option><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select> ' +
          '<p class="error" id="status_error"></p></div>');
      } else {
        $('.modal-body #status-container').remove();
      }

  });
</script>
<?= $this->endSection('content'); ?>