<?php 
  use Config\MyConstants;
  $internalReferenceType = [3, 4, 5];
?>
<h5 class="card-title">Bentuk Koleksi</h5>
<input type="hidden" value="<?= (isset($reference) && $reference['reference_id']) ? $reference['reference_id'] : ''; ?>" id="reference_id"/>
<input type="hidden" value="<?= $method; ?>" id="method" />
<div class="form-group">
	<label for="reference_type" class="required" >Bentuk Koleksi</label>
	<select class="form-select" id="reference-type" name="reference_type">
		<option value="" selected disabled>Bentuk Koleksi</option>  
		<?php foreach($referenceType as $row) {
			if (isset($reference) && $reference['reference_type_id'] == $row['reference_type_id']) { ?>
				<option value="<?= $row['reference_type_id']; ?>" selected><?= $row['reference_type_name']; ?></option>
			<?php } else { ?>
				<option value="<?= $row['reference_type_id']; ?>"><?= $row['reference_type_name']; ?></option>
		<?php }
		}?>
	</select>
	<p class="error" id="reference_type_error"></p>
</div>
<div class="form-group">
	<label for="language" class="required" >Bahasa</label>
	<select class="form-select" id="language" name="language">
		<?php if (isset($reference) && $reference['language']) { ?>
				<option value="in" <?= ($reference['language'] == 'in') ? 'selected' : '' ?>>Indonesia</option>
			<option value="en" <?= ($reference['language'] == 'en') ? 'selected' : '' ?>>English</option>
		<?php } else { ?> 
		<option value="in" selected>Indonesia</option>
		<option value="en" >English</option>
		<?php } ?>
	</select>
	<p class="error" id="language_error"></p>
</div>
<div class="form-group">
	<label for="publishe" class="required">Status Publikasi</label>
	<div class="form-check form-check-flat form-check-primary">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" id="published" value="1" name="published"
			<?= (isset($reference) && $reference['published_external'] =='1') ? 'checked' : ''; ?>
			<?= (isset($reference) && in_array($reference['reference_type_id'], $internalReferenceType)) ? 'disabled' : ''?> >Telah dipublikasikan </label>
			
	</div>
	<div class="form-check form-check-flat form-check-primary">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" id="not-published" value="0" name="published"
			<?= (isset($reference) && $reference['published_external'] =='0') ? 'checked' : ''; ?>
			<?= (isset($reference) && in_array($reference['reference_type_id'], $internalReferenceType)) ? 'disabled' : ''?>
		>Tidak pernah dipublikasikan </label>
	</div>
	<p class="error" id="published_error"></p>
</div>
<div class="form-group">
	<label for="file_type" class="required" >Jenis Berkas</label>
	<select class="form-select" id="file_type" name="file_type" <?= (isset($reference) && in_array($reference['reference_type_id'], $internalReferenceType)) ? 'disabled' : ''?>>
		<?php
			foreach(MyConstants::FILE_TYPE as $opt) { 
				if ($opt['value'] == 'text' || (isset($reference) && $reference['file_type'] == $opt['value'])) { ?>

					<option value="<?= $opt['value'] ?>" selected><?= $opt['label'] ?></option>
			<?php } else { ?>
			<option value="<?= $opt['value'] ?>" ><?= $opt['label'] ?></option>
		<?php } }
		?>
		
	</select>
	<p class="error" id="file_type_error"></p>
</div>
<div class="form-group">
	<label for="visible_to" class="required" >Dapat diakses oleh</label>
	<select class="form-select" id="visible_to" name="visible_to" <?= (isset($reference) && in_array($reference['reference_type_id'], $internalReferenceType)) ? 'disabled' : ''?>>
		<?php
			foreach(MyConstants::VISIBLE_TO as $opt) { 
				if ($opt['value'] == 'member-only' || (isset($reference) && $reference['visible_to'] == $opt['value'])) { ?>

					<option value="<?= $opt['value'] ?>" selected><?= $opt['label'] ?></option>
			<?php } else { ?>
			<option value="<?= $opt['value'] ?>" ><?= $opt['label'] ?></option>
		<?php } }
		?>
	</select>
	<p class="error" id="visible_to_error"></p>
</div>

<div class="form-group">
	<label for="license" >Lisensi</label>
	<select class="form-select" id="license" name="license" <?= (isset($reference) && in_array($reference['reference_type_id'], $internalReferenceType)) ? 'disabled' : ''?>>
		<?php
			foreach(MyConstants::LICENSE as $opt) { 
				if ($opt['value'] == 'Unspesified' || (isset($reference) && $reference['license'] == $opt['value'])) { ?>

					<option value="<?= $opt['value'] ?>" selected><?= $opt['label'] ?></option>
			<?php } else { ?>
			<option value="<?= $opt['value'] ?>" ><?= $opt['label'] ?></option>
		<?php } } ?>
	</select>
</div>
<div class="form-group">
	<label for="embargo_expiry_date" class="required">Tanggal Berakhirnya Embargo</label>
	<input
		type="date"
		class="form-control"
		id="embargo_expiry_date"
		placeholder="Tanggal Berakhirnya Embargo"
		style="outline:none"
		name="embargo_expiry_date"
		value="<?= (isset($reference) && isset($reference['embargo_expiry_date'])) ?  $reference['embargo_expiry_date'] : '' ?>"
		<?= (!isset($reference) || (isset($reference) && in_array($reference['reference_type_id'], $internalReferenceType))) ? 'disabled' : ''?>
	>
	<p class="error" id="embargo_expiry_date_error"></p>
</div>
<div style="float: right">
	<a href="my-reference">
		<button type="button" class="btn btn-outline-primary">Batal</button>
	</a>
	<button type="button" class="btn btn-primary me-2" id="next_btn_collection_type" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Selanjutnya</button>
</div>

<script>
	$(document).ready(function() {
		$('#visible_to').change(function() {
			if ($('#visible_to').val() == 'staff-only') {
				$('#embargo_expiry_date').prop('disabled', false);
				$('label[for="embargo_expiry_date"]').addClass('required');
			} else {
				$('#embargo_expiry_date').prop('disabled', true);
				$('label[for="embargo_expiry_date"]').removeClass('required');
			}
		});
	});
</script>