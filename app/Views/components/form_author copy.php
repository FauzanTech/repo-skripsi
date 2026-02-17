<h5 class="card-title" >Penulis</h5>
<div id="author-container">
<?php if (isset($reference) && isset($authors)) {
	foreach ($authors as $row) { ?> 
	<div id="author-1" class="d-flex justify-content-between">
	<div class="form group col-md-6 col-sm-12 col-xs-12">
		<label for="affiliation">Afiliasi Penulis</label>
		<select class="form-select" id="affiliation-1" name="affiliation[]" class="affiliation" onchange="changeAffiliation(1)">
		<?php if ($row['external_author_affiliation'] == 0) { ?>
			<option value="1" selected>Institut Teknologi Bacharuddin Jusuf Habibie</option>
			<option value="2">Lainnya</option>
		<?php } else { ?>
			<option value="1">Institut Teknologi Bacharuddin Jusuf Habibie</option>
			<option value="2" selected>Lainnya</option>
		<?php } ?>
		</select>
		<p class="error" id='affiliation-error-1'></p>
	</div>
	<div class="form-group col-md-3 col-sm-6 col-xs-6" id="form-author-1">
		<input type="text" class="form-control" placeholder="NUPTK Dosen/NIM Mahasiswa" />
		<p class="error" id='author-error-1'></p>
	</div>
    <div class="form-group col-md-3 col-sm-6 col-xs-6" id="form-author-1">
			<label for="author-name">Nama Penulis</label>
			<?php if (isset($reference) && isset($authors)) {
			if ($row['external_author_affiliation'] == 0) { ?>
			<select class="js-example-basic-single form-select" id="author_name_1" name="author-name[]" data-live-search="true">
			<?php foreach ($members as $rowMember) {
				if ($row['user_id'] == $rowMember['user_id']) { ?>
					<option data-tokens="<?= $rowMember['fullname'] ?>" value="<?= $rowMember['user_id'] ?>" selected><?= $rowMember['fullname'] ?></option>
			<?php } else { ?>
					<option data-tokens="<?= $rowMember['fullname'] ?>" value="<?= $rowMember['user_id'] ?>"><?= $rowMember['fullname'] ?></option>
			<?php }} ?>
			</select>
			<?php } else { ?>
				<input type="text" class="form-control" id="author_name_1" name="author-name[]" value="<?= $row['external_author_name']; ?>">
			<?php }} ?> 
			<p class="error" id='author-error-1'></p>
    </div>
    </div>
    <?php }
    } else { ?>
    <div id="thesis-author" class="d-none">
        <div class="row d-flex">
        <div class="form-group col-md-6 col-sm-12 col-xs-12" id="form-author-1">
            <label for="author-name" class="required">NIM Mahasiswa</label>
            <input type="hidden" name="affiliation[]" value="1" />
            <input type="text" name="identity_number" class="form-control" id="author-identity-number-1" placeholder="NIM Mahasiswa" value="<?= $session->get('user_id') ? $session->get('identity_number') : '' ?>">
            <p class="error" id='author-identity-number-error-1'></p>
        </div>
            <!-- <input type="text" class="form-select" id="author_name_1" name="author-name"> -->
        <div class="form-group col-md-6 col-sm-12 col-xs-12" id="form-author-1">
            <label for="author-name" class="required">Nama Mahasiswa</label>
            <input type="hidden" id="author_id_1" value="<?= $session->get('user_id') ? $session->get('user_id')  : '' ?> " />
            <input type="text" class="form-control" placeholder="Nama Penulis" id="author_name_1" value="<?= $session->get('user_id') ? $session->get('fullname') : '' ?>" disabled />
            <p class="error" id='author-error-1'></p>
        </div>
        
            <!-- <input type="text" class="form-select" id="author_name_1" name="author-name"> -->

        </div>
        <h5 class="card-title">Dosen Pembimbing</h5>
        <div class="row">
        <div class="form-group col-6" id="supervisor-1">
            <label for="author-name" class="required">NUPTK Dosen Pembimbing 1</label>
            <input type="hidden" name="affiliation[]" value="1" />
            <input type="text" name="identity_number" id="nuptk-supervisor-1" class="form-control" placeholder="NUPT Dosen Pembimbing 1" onkeyup="find_author_name(this)" />
            <p class="error" id='nuptk-supervisor-1-error'></p>
        </div>
        <div class="form-group col-6" id="supervisor-1">
            <label for="author-name" class="required">Nama Dosen Pembimbing 1</label>
            <input type="hidden" id="first_supervisor_id" />
            <input type="text" class="form-control" name="first-supervisor" id="first_supervisor"  disabled>
            <p class="error" id='first_supervisor_error'></p>
        </div>
    
        <div class="form-group col-6">
            <label for="author-name" class="required">NUPTK Dosen Pembimbing 2</label>
            <input type="hidden" name="affiliation[]" value="1" />
            <input type="text" name="identity_number" id="nuptk_supervisor_2" class="form-control" placeholder="NUPT Dosen Pembimbing 2" onkeyup="find_author_name(this)" />
            <p class="error" id='nuptk_supervisor_2-error'></p>
        </div>
        <div class="form-group col-6" id="supervisor-2">
            <label for="author-name" class="required">Nama Dosen Pembimbing 2</label>
            <input type="hidden" id="second_supervisor_id" />
            <input type="text" class="form-control" name="second_supervisor" id="second_supervisor" value="<?= $second_supervisor ? $second_supervisor : "" ?>" disabled>
            <p class="error" id='second_supervisor_error'></p>
        </div>
        </div>

    </div>

    <div id="other-author" class="d-none">
        <input type="hidden" id="number_author" value="1" />
        <button type="button" class="btn btn-sm btn-danger" id="more_authors">Tambah Penulis</button>
        <div id="author-1" class="d-flex">
        <div class="form-group col-3">
            <label for="affiliation" class="required">Afiliasi</label>
            <select class="form-select affiliation" id="affiliation-1" name="affiliation[]" onchange="change_affiliation('1')">
            <option value="1" selected>Internal</option>
            <option value="2">Eksternal</option>
            </select>
            <p class="error" id='affiliation-error-1'></p>
        </div>
        <div class="form-group col-3" id="form-author-1">
            <label for="identity-number-1" class="required">NUPTK Dosen/NIM Mahasiswa</label>
            
            <input type="text" class="form-control" name="identity_number" id="identity-number-1" placeholder="NUPTK Dosen/NIM Penulis" style="outline:none" onkeyup="find_author_name(this)">
            <p class="error" id="author_error_1"></p>
        </div>   
        <div class="form-group col-3" id="form-author-1">
            <label for="author_name_1" class="required">Nama Penulis</label>
            <input type="hidden" name="form_author_id[]" id="form_author_id_1" />
            <input type="text" class="form-control" id="author_name_1" name="author-name[]" placeholder="Nama Penulis" style="outline:none">
            <p class="error" id="author_error_1"></p>
        </div>
        <div class="form-group col-2">
            <label for="author-desc-1" class="required">Keterangan</label>
            <select class="form-select" id="author-desc-1" name="author-desc[]">
            <option value="<?= MyConstants::FIRST_AUTHOR; ?>" selected>Penulis 1</option>
            <option value="<?= MyConstants::CORRESPONDING_AUTHOR; ?>">Koresponden</option>
            <option value="<?= MyConstants::CO_AUTHOR; ?>">Anggota</option>
            </select>
            <p class="error" id='author-desc-error-1'></p>
        </div>
        </div>
    </div>
    
    <?php } ?>

</div>

<div style="float: right">
<button type="button" class="btn btn-outline-primary" id="prevBtn" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Sebelumnya</button>
<button type="button" class="btn btn-primary me-2" id="next_btn_author" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Selanjutnya</button>
</div>