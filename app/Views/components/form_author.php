<?php
use Config\MyConstants;
 // untuk koleksi skripsi, tesis, disertasi
 $nonThesisType = [1, 2, 6, 7, 8, 9, 10, 11, 12, 13];
 $internalReferenceType = [3, 4, 5];

$session = session();
$studentId = '';
$studentName = '';
$studentIndentityNumber = '';
$firstSupervisorId =  '';
$firstSupervisorName = '';
$firstSupervisorIdentityNumber = '';
$secondSupervisorId =  '';
$secondSupervisorName = '';
$secondSupervisorIdentityNumber = '';

$noAuthors = isset($authors) ? count($authors) : 1;
 if (isset($reference) && isset($authors) && in_array($reference['reference_type_id'], $internalReferenceType)) {
	foreach($authors as $row) {
		if ($row['description'] == MyConstants::FIRST_AUTHOR) {
			$studentName = $row['fullname'];
			$studentIndentityNumber = $row['identity_number'];
			$studentId = $row['user_id'];
		} else if ($row['description'] == MyConstants::FIRST_SUPERVISOR) {
			$firstSupervisorId =  $row['user_id'];
			$firstSupervisorName = $row['fullname'];
			$firstSupervisorIdentityNumber = $row['identity_number'];
		} else if ($row['description'] == MyConstants::SECOND_SUPERVISOR) {
			$secondSupervisorId =  $row['user_id'];
			$secondSupervisorName = $row['fullname'];
			$secondSupervisorIdentityNumber = $row['identity_number'];
		}
	}
 }
?>
<h5 class="card-title" >Penulis</h5>
<div id="author-container">
	<div id="thesis-author" class="d-none">
		<div class="row d-flex">
			<div class="form-group col-md-6 col-sm-12 col-xs-12" id="form-author-1">
				<label for="author-name" class="required">NIM Mahasiswa</label>
				<input type="hidden" name="affiliation[]" value="1" />
				<input
					type="text"
					name="identity_number"
					class="form-control"
					id="author-identity-number-1"
					placeholder="NIM Mahasiswa"
					value="<?= $session->get('user_id') ? $session->get('identity_number') : '' ?>"
				>
				<p class="error" id='author-identity-number-error-1'></p>
			</div>
			<div class="form-group col-md-6 col-sm-12 col-xs-12" id="form-author-1">
				<label for="author-name" class="required">Nama Mahasiswa</label>
				<input type="hidden" id="author_id_1" value="<?= $studentId == '' ? $session->get('user_id') : $studentId ?> " />
				<input type="text" class="form-control" placeholder="Nama Penulis" id="author_name_1" value="<?= $session->get('user_id') ? $session->get('fullname') : '' ?>" disabled />
				<p class="error" id='author-error-1'></p>
			</div>
		</div>
		<h5 class="card-title">Dosen Pembimbing</h5>
		<div class="row">
			<div class="form-group col-6" id="supervisor-1">
					<label for="author-name" class="required">NUPTK Dosen Pembimbing 1</label>
					<input type="hidden" name="affiliation[]" value="1" />
					<input
						type="text"
						name="identity_number"
						id="nuptk-supervisor-1"
						class="form-control"
						placeholder="NUPT Dosen Pembimbing 1"
						onkeyup="find_author_name(this)"
						value="<?=$firstSupervisorIdentityNumber ?>"
					/>
					<p class="error" id='nuptk-supervisor-1-error'></p>
			</div>
			<div class="form-group col-6" id="supervisor-1">
					<label for="author-name" class="required">Nama Dosen Pembimbing 1</label>
					<input type="hidden" id="first_supervisor_id" value="<?=$firstSupervisorId ?>" />
					<input type="text" class="form-control" name="first-supervisor" id="first_supervisor" disabled value="<?=$firstSupervisorName ?>">
					<p class="error" id='first_supervisor_error'></p>
			</div>
	
			<div class="form-group col-6">
					<label for="author-name" class="required">NUPTK Dosen Pembimbing 2</label>
					<input type="hidden" name="affiliation[]" value="1" />
					<input type="text" name="identity_number" id="nuptk_supervisor_2" class="form-control" placeholder="NUPT Dosen Pembimbing 2" onkeyup="find_author_name(this)" value="<?=$secondSupervisorIdentityNumber ?>"/>
					<p class="error" id='nuptk_supervisor_2-error'></p>
			</div>
			<div class="form-group col-6" id="supervisor-2">
					<label for="author-name" class="required">Nama Dosen Pembimbing 2</label>
					<input type="hidden" id="second_supervisor_id" value="<?=$secondSupervisorId ?>" />
					<input type="text" class="form-control" name="second_supervisor" id="second_supervisor" disabled value="<?= $secondSupervisorName ?>" >
					<p class="error" id='second_supervisor_error'></p>
			</div>
			</div>

	</div>

	<div id="other-author" class="<?= (isset($reference) && in_array($reference['reference_type_id'], $nonThesisType)) ? '' : 'd-none' ?>">
		<input type="hidden" id="number_author" value="<?= $noAuthors ?>" />
		<button type="button" class="btn btn-danger" id="more_authors" style="margin: 1rem 0;">Tambah Penulis</button>
		<?php for($i = 1; $i<= $noAuthors; $i++) { ?>
		<div id="author-<?= $i ?>" class="d-flex">
			<div class="form-group col-3">
				<label for="affiliation" class="required">Afiliasi</label>
				<select class="form-select affiliation" id="affiliation-<?= $i ?>" name="affiliation[]" onchange="change_affiliation('1')">
				<option value="1" <?= (!isset($authors) || (isset($authors) && count($authors) > 0 && $authors[$i-1]['external_author_affiliation'] == '0')) ? 'selected' : '' ?>>Internal</option>
				<option value="2"<?= isset($authors) && count($authors) > 0 && $authors[$i-1]['external_author_affiliation'] == '1' ? 'selected' : '' ?> >Eksternal</option>
				</select>
				<p class="error" id='affiliation-error-<?= $i+1 ?>'></p>
			</div>
			<div class="form-group col-3" id="form-author-<?= $i ?>">
				<label for="identity-number-1" class="required">NUPTK Dosen/NIM Mahasiswa</label>
				<input
					type="text"
					class="form-control"
					name="identity_number"
					id="identity-number-<?= $i ?>"
					placeholder="NUPTK Dosen/NIM Penulis"
					style="outline:none"
					onkeyup="find_author_name(this)"
					value="<?= isset($authors) && count($authors) > 0 ? $authors[$i-1]['identity_number'] : $session->get('identity_number')?> "
					<?= (isset($authors) && count($authors) > 0 && $authors[$i-1]['external_author_affiliation'] == '1') ? 'disabled' : '' ?> 
				/>
				<p class="error" id="author_error_<?= $i ?>"></p>
			</div>   
			<div class="form-group col-3" id="form-author-<?= $i ?>">
				<label for="author_name_1" class="required">Nama Penulis</label>
				<input type="hidden" name="form_author_id[]" id="author_id_<?= $i ?>" value="<?= $session->get('user_id')?> " />
				<input
					type="text"
					class="form-control"
					id="author_name_<?= $i ?>"
					name="author-name[]"
					placeholder="Nama Penulis"
					style="outline:none"
					value="<?= isset($authors) && count($authors) > 0 ? $authors[$i-1]['fullname'] ?? $authors[$i-1]['external_author_name'] : $session->get('fullname')?> "
					<?= !isset($authors) || (isset($authors) && count($authors) > 0 && $authors[$i-1]['external_author_affiliation'] == '0')? 'disabled' :'' ?>
				/>
				<p class="error" id="author_error_<?= $i ?>"></p>
			</div>
			<div class="form-group col-2">
				<label for="author-desc-<?= $i ?>" class="required">Keterangan</label>
				<select class="form-select" id="author-desc-<?= $i ?>" name="author-desc[]">
				<option value="<?= MyConstants::FIRST_AUTHOR; ?>" <?= !isset($authors) || isset($authors) && count($authors) > 0 && $authors[$i-1]['description'] == MyConstants::FIRST_AUTHOR ? 'selected' : '' ?>>Penulis 1</option>
				<option value="<?= MyConstants::CORRESPONDING_AUTHOR; ?>" <?= isset($authors) && count($authors) > 0 && $authors[$i-1]['description'] == MyConstants::CORRESPONDING_AUTHOR ? 'selected' : '' ?>>Koresponden</option>
				<option value="<?= MyConstants::CO_AUTHOR; ?>" <?= isset($authors) && count($authors) > 0 && $authors[$i-1]['description'] == MyConstants::CO_AUTHOR ? 'selected' : '' ?>>Anggota</option>
				</select>
				<p class="error" id='author-desc-error-<?= $i ?>'></p>
			</div>
		</div>
		<?php } ?>
	</div>
</div>

<div style="float: right">
<button type="button" class="btn btn-outline-primary" id="prevBtn" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Sebelumnya</button>
<button type="button" class="btn btn-primary me-2" id="next_btn_author" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Selanjutnya</button>
</div>