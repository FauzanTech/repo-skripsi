<?php
use Config\MyConstants;
echo $this->extend('layout/layoutAdmin');
echo $this->section('content');
?>
  <div id="alert"></div>
  <div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Dashboard
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
                <button type="submit" class="btn btn-gradient-primary" style="margin-right: 1rem;" data-bs-toggle="modal" data-bs-target="#myModal" data-mode="<?= MyConstants::MODE_INSERT ?>">Member Baru</button>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-import">Import Member</button>  
              </div>
            </div>
            <table class="table table-hover" style="max-width: 100%;">

              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Program Studi</th>
                  <th><?= $memberType == MyConstants::MAHASISWA ? 'NIM' : 'NUPTK' ?></th>
                  <th>Email</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach($member as $row) { ?>
                <tr>
                  <td width="5%"><?= $no; ?></span></td>
                  <td><?= $row['fullname']; ?></td>
                  <td><?= $row['prodi_name']; ?></td>
                  <td><?= $row['identity_number']; ?></td>
                  <td><?= $row['email']; ?></td>
                  <td><?= MyConstants::MEMBER_STATUS[$row['status']]; ?></td>
                  <td>
                    <i class="fa fa-pencil"
                      data-bs-target="#myModal"
                      data-bs-toggle="modal"
                      data-fullname="<?= $row['fullname']; ?>"
                      data-identity_number="<?= $row['identity_number']; ?>"
                      data-email="<?= $row['email']; ?>"
                      data-role="<?= $row['role']; ?>"
                      data-user_id="<?= $row['user_id']; ?>"
                      data-status="<?= $row['status']; ?>"
                      data-prodi_id="<?= $row['prodi_id']; ?>"
                      data-prefix_title="<?= $row['prefix_title'] ?>"
                      data-suffix_title="<?= $row['suffix_title'] ?>"
                      data-mode="<?= MyConstants::MODE_UPDATE ?>"
                    >
                    </i>
                    <?php if ($row['status'] == 0) { ?>
                      <i class="fa fa-trash"
                        data-bs-toggle="modal"
                        data-bs-target="#myModalDelete"
                        data-fullname="<?= $row['fullname']; ?>"
                        data-user_id="<?= $row['user_id']; ?>">
                      </i>
                    <?php } ?>
                    
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #fff">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Anggota Baru</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="padding: 0 2rem;">
          <div class="form-group" style="margin-top: 2.5rem;">
            <label for="prodi_id">Program Studi</label>
            <select class="form-select" id="prodi_id" name="prodi_id">
              <option value='' selected>Pilih Program Studi</option>
              <?php foreach($prodi as $row) { ?>
                <option value="<?= $row['prodi_id']; ?>"><?= $row['prodi_strata'].' - '.$row['prodi_name']; ?></option>
              <?php } ?>
            </select>
            <p class="error" id="prodi_id_error"></p>
          </div>
          <div class="form-group">
            <label for="fullname">Nama Lengkap (Tanpa Gelar)</label>
            <input type="text" class="form-control" id="fullname" placeholder="Nama Lengkap" style="outline:none">
            <p class="error" id="fullname_error"></p>
          </div>
          <?php if ($memberType === MyConstants::DOSEN) { ?>
          <div class="form-group">
            <label for="fullname">Gelar Depan</label>
            <input type="text" class="form-control" id="prefix_title" placeholder="Gelar depan (contoh: Prof. Dr.)" style="outline:none">
          </div>
          <div class="form-group">
            <label for="fullname">Gelar Belakang</label>
            <input type="text" class="form-control" id="suffix_title" placeholder="Contoh depan (contoh: ST., M.Kom.)" style="outline:none">
          </div>
          <?php } ?>
          <div class="form-group">
            <label for="identity_number"><?= $memberType == MyConstants::MAHASISWA ? 'NIM' : 'NUPTK' ?></label>
            <input type="text" class="form-control" id="identity_number" placeholder="<?= $memberType == MyConstants::MAHASISWA ? 'NIM' : 'NUPTK' ?>" style="outline:none">
            <p class="error" id="identity_number_error"></p>
          </div>
          <div class="form-group">
            <label for="email">Email </label>
            <input type="email" class="form-control" id="email" placeholder="Email" style="outline:none">
            <p class="error" id="email_error"></p>
          </div>
          <input type="hidden" id="mode" />
          <!-- <div class="form-group">
            <label for="role">Jabatan </label>
            <select class="form-select" id="role" name="role">
              <option value='' selected>Pilih Jabatan</option>
              <option value="Dosen">Dosen</option>
              <option value="Mahasiswa">Mahasiswa</option>
            </select>
            <p class="error" id="role_error"></p>
          </div> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="submit-new-member">Simpan</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #fff">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="text-align:center" id="delete-confirmation">
          
        </div>
        <input type="hidden" id="user_id_delete" />
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="delete-member">Hapus</button>
        </div>
    </div>
  </div>
</div>

<form action="<?=site_url('import-member')?>" method="POST" enctype="multipart/form-data" >
  <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="background: #fff">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Import Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" style="padding: 0 2rem;">
          <div class="form-group" style="margin-top:1rem;">
            <label for="fileExcel">Masukkan File</label>
            <input type="file" name="fileExcel" class="form-control" id="fileExcel" placeholder="Required Excel" style="outline:none" accept=".xls, .xlsx">
            <p class="error" id="fullname_error"></p>
          </div>
          <label><a href="/assets/template/Template Import Member Perpustakaan.xlsx">Download Template Member </a></label>
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
    const url = window.location.href.split('/');
    const currentUrl = url[url.length - 1];
    $('#submit-new-member').click(function() {
      const prodi_id = $('#prodi_id').val();
      const fullname = $('#fullname').val();
      const identityNumber = $('#identity_number').val();
      const email = $('#email').val();
      const role = currentUrl === 'dosen' ? 'Dosen' : 'Mahasiswa';
      const mode = $('#mode').val();
      const status = $('#status').val();
      const rules = [
        { id: 'prodi_id_error', rule: 'required', error_message: 'Prodi wajib diisi', value: prodi_id },
        { id: 'fullname_error', rule: 'required', error_message: 'Nama Lengkap (tanpa gelar) wajib diisi', value: fullname},
        { id: 'identity_number_error', rule: 'required', error_message: 'NIM / NUPTK wajib diisi', value: identityNumber },
        { id: 'email_error', rule: 'required', error_message: 'Email wajib diisi', value: email},
        { id: 'role_error', rule: 'required', error_message: 'Jabatan wajib diisi', value: role}
      ];

      const valid = validation(rules);
      if (valid) {
        $.post('/insert-new-member', {
          prodi_id,
          fullname,
          prefix_title: $('#prefix_title').val(),
          suffix_title: $('#suffix_title').val(),
          identity_number: identityNumber,
          email,
          role,
          mode,
          status
        }, function(res) {
          $('.close').click();
          result = JSON.parse(res);
            $.get('/alert?status='+result.status+'&message='+result.message, function(res) {
              $('#alert').html(res);
              $('#close').click();
            });
        });
      }
    });

    $('#delete-member').click(function(){
      const user_id = $('#user_id_delete').val();
      $.post('/delete-member', {user_id}, function(res) {
        $('#user_id_delete').val();
        $('.close').click();
          result = JSON.parse(res);
            $.get('/alert?status='+result.status+'&message='+result.message, function(res) {
              $('#alert').html(res);
              $('#close').click();
            });
      });
    })

    $('#myModalDelete').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const user_id = button.data('user_id');
      const fullname = button.data('fullname');
      $('#user_id_delete').val(user_id);
      $('#delete-confirmation').append('<p>Apakah anda yakin akan menghapus <strong>' + fullname + '?</strong></p>');
    });
  
    $('#myModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const fullname = button.data('fullname');
      const identity_number = button.data('identity_number');
      const email = button.data('email');
      const status = button.data('status');
      const user_id = button.data('user_id');
      const prodi_id = button.data('prodi_id');
      const prefix_title = button.data('prefix_title');
      const suffix_title = button.data('suffix_title');
      const mode = button.data('mode');

      $('#fullname').val(fullname);
      $('#identity_number').val(identity_number);
      $('#email').val(email);
      $('#user_id').val(user_id);
      $('#prodi_id').val(prodi_id);
      $('#prefix_title').val(prefix_title);
      $('#suffix_title').val(suffix_title);

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
  });
</script>
<?= $this->endSection('content'); ?>