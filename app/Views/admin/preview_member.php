<?= $this->extend('layout/layoutAdmin'); ?>
<?= $this->section('content'); ?>
  <div class="content-wrapper">
    <div id="alert"></div>
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
            <form id="myForm">
              <h4 class="card-title">Preview Data Anggota</h4>
              <div style="float: right">
                <div class="d-flex">
                  <button type="submit" id="submit" class="btn btn-gradient-primary" style="margin-right: 1rem;">Simpan</button>
                  <a href="/member"><button type="button" class="btn btn-outline-primary">Batal</button> </a>
                </div>
              </div>
              <table class="table table-hover" style="max-width: 100%;">
                <thead>
                  <tr>
                    <?php foreach ($data[1] as $header): ?>
                      <th><?= $header ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (array_slice($data, 1) as $row): ?>
                    <input type="hidden" value="<?=$row['A'] ?> " name="identity_number[]" />
                    <input type="hidden" value="<?=$row['B'] ?> " name="name[]" />
                    <input type="hidden" value="<?=$row['C'] ?> " name="email[]" />
                    <input type="hidden" value="<?=$row['D'] ?> " name="role[]" />
                    <input type="hidden" value="<?=$row['E'] ?> " name="prodi[]" />
                    <tr>
                      <?php foreach ($row as $cell): ?>
                        <td><?= $cell ?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
      
    </div>
  
</div>
</div>
<script>
  $(document).ready(function() {
    $("#myForm").submit(function(e) {
      e.preventDefault();
      const data = {
        name: $("input[name='name[]']").map(function(){return $(this).val();}).get(),
        email: $("input[name='email[]']").map(function(){return $(this).val();}).get(),
        identity_number: $("input[name='identity_number[]']").map(function(){return $(this).val();}).get(),
        prodi: $("input[name='prodi[]']").map(function(){return $(this).val();}).get(),
        role: $("input[name='role[]']").map(function(){return $(this).val();}).get(),
      };
      $.post('/insert-batch-member', data, function(res) {
        res = JSON.parse(res);
        if (res.status == 'success') {
          $.get('/alert?status=success&message=Data Berhasil Disimpan', function(res) {
            $('#alert').html(res);
          });
        } else {
          if (res.status == 'success') {
          $.get('/alert?status=failed&message=Terjadi Kesalahan Sistem', function(res) {
            $('#alert').html(res);
          });
        }
        }
      });
    });
  });
</script>
<?= $this->endSection('content'); ?>

