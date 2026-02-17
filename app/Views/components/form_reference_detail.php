<h4 class="card-title">Data Koleksi</h4>
<input type="hidden" id="is_external_published" value="<?= isset($reference) && $reference['published_external'] ? $reference['published_external'] : null ?>" />
<div class="form-group">
	<label for="title" class="required">Judul</label>
	<input type="text" class="form-control" id="title" placeholder="Judul" style="outline:none" value="<?= (isset($reference) && $reference['title']) ? $reference['title'] : ''; ?>">
	<p class="error" id="title_error"></p>
</div>
<div id="journal-detail" class="<?= (isset($reference) && $reference['reference_type_id'] == 1 && $reference['published_external'] == 0) ? '' : 'd-none' ?>">
	<div class="form-group">
		<label for="journal_name" class="required">Nama Jurnal</label>
		<input
			type="text"
			name="journal_name"
			class="form-control <?= ($validation && $validation->hasError('journal_name')) ? 'is-invalid' : ''; ?>"
			id="journal_name"
			placeholder="Nama Jurnal"
			value="<?= (isset($reference) && $reference['journal_name']) ? $reference['journal_name'] : ''; ?>"
		>
		<p class="error"><?= ($validation && $validation->getError('journal_name')) ? $validation->getError('journal_name'): '' ?></p>
	</div>
	<p class="error" id="journal-name-error"></p>
	<div class="d-flex justify-content-between">
		<div class="col-5">
			<div class="form-group">
				<label for="volume">Volume</label>
				<input type="number"
					name="volume"
					class="form-control"
					id="volume"
					placeholder="Volume"
					value="<?= (isset($reference) && $reference['volume']) ?  $reference['volume'] : ''; ?>"
				>
			</div>
			<p class="error" id="volume_error"></p>
		</div>
		<div class="col-3">
			<div class="form-group">
				<label for="start_page">Dari Halaman</label>
				<div class="d-flex">
					<input type="number"
						name="start-page"
						class="form-control"
						id="start_page"
						placeholder="Dari Halaman"
						value="<?= (isset($reference) && $reference['start_page']) ? $reference['start_page'] :''; ?>"
					>
				</div>
				<p class="error" id="start-page-error"></p>
			</div>
		</div>
		<div class="col-3">
			<div class="form-group">
				<label for="end_page">Sampai Halaman</label>
				<div class="d-flex">
					<input type="number"
						name="end_page"
						class="form-control"
						id="end_page" placeholder="Sampai Halaman"
						value="<?= (isset($reference) && $reference['end_page']) ? $reference['end_page'] :''; ?>"
						>
				</div>
				<p class="error" id="end-page-error"></p>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="reference_link" class="required">Tautan Artikel Publikasi</label>
		<input
			type="text"
			name="reference_link"
			id="reference_link"
			class="form-control <?=($validation && $validation->hasError('reference_link')) ? 'is-invalid' : '' ?>"
			value="<?= (isset($reference) && $reference['reference_link']) ? $reference['reference_link'] : ''; ?>"
		>
		<p class="error" id="reference_link_error"><?php echo ($validation && $validation->getError('reference_link')) ? $validation->getError('reference_link') : '' ; ?></p>
	</div>
	<div class="form-group">
		<label for="doi">DOI</label>
		<input
			type="text"
			id="doi"
			name="doi"
			class="form-control <?= ($validation && $validation->hasError('doi')) ? 'is-invalid': '' ?>"
			value="<?= (isset($reference) && $reference['doi']) ? $reference['doi'] : ''; ?>"
		>
		<p class="error" id="doi_error"><?php echo ($validation && $validation->getError('doi')) ? $validation->getError('doi') : '' ; ?></p>
	</div>
</div>

<div id="proceeding-detail" class="<?= (isset($reference) && $reference['reference_type_id'] == 2) ? '' : 'd-none' ?>">
	<p class="error" id="tanggal-terbit-error"></p>
	<div class="form-group">
		<label for="proceedings-title" class="required">Nama Prosiding</label>
		<input
			type="text"
			class="form-control <?= ($validation && $validation->hasError('proceedings-title')) ? 'is-invalid' : ''; ?>"
			id="proceedings_title"
			name="proceeding_title"
			placeholder="Nama Prosiding"
			value="<?= (isset($reference) && $reference['proceedings_title']) ? $reference['proceedings_title'] : ''; ?>"
		>
		<p class="error" id="proceedings_title_error"> <?= ($validation && $validation->getError('proceedings-title')) ? $validation->getError('proceedings-title') : '' ?> </p>
	</div>

	<div class="form-group">
		<label for="city" class="required">Kota</label>
		<input
			type="text"
			name="city"
			class="form-control"
			id="city"
			placeholder="Kota, Negara"
			value="<?= (isset($reference) && $reference['proceedings_city']) ? $reference['proceedings_city'] : ''; ?>"
		>
	</div>
	<p class="error" id="procising_city_error"></p>

	<div class="form-group">
		<label for="doi">DOI</label>
		<input
			type="text"
			id="doi"
			name="doi"
			class="form-control <?= ($validation && $validation->hasError('doi')) ? 'is-invalid': '' ?>"
			value="<?= (isset($reference) && $reference['doi']) ? $reference['doi'] : ''; ?>"
		>
		<p class="error" id="doi_error"><?php echo ($validation && $validation->getError('doi')) ? $validation->getError('doi') : '' ; ?></p>
	</div>
</div>
<div class="form-group d-none" id="form-reference-link">
	<label for="reference_link" class="required">Tautan Publikasi</label>
	<input
		type="text"
		name="reference_link"
		id="reference_link"
		class="form-control <?=($validation && $validation->hasError('reference_link')) ? 'is-invalid' : '' ?>"
		value="<?= (isset($reference) && $reference['reference_link']) ? $reference['reference_link'] : ''; ?>"
	>
	<p class="error" id="reference_link_error"><?php echo ($validation && $validation->getError('reference_link')) ? $validation->getError('reference_link') : '' ; ?></p>
</div>
<div class="form-group" id="form-publication-date">
	<label for="publication-date" class="required">Tanggal Terbit</label>
	<input
		type="date"
		class="form-control <?= ($validation && $validation->hasError('publication_date')) ? 'is-invalid' : ''; ?>"
		name="publication_date"
		id="publication_date"
		placeholder="Tanggal Terbit"
		value="<?= (isset($reference) && $reference['publication_date']) ? $reference['publication_date'] : ''; ?>"
		>
	<p class="error" id="publication_date_error"> <?= ($validation && $validation->getError('publication_date')) ? $validation->getError('publication_date') : '' ?></p>
</div>
<div class="form-group">
	<label for="reference_file" class="required">Dokumen Karya</label>
	<input
			type="file"
			name="reference_file"
			id="reference_file"
			class="file-upload-default"
			accept=".pdf"
		>
	<div class="input-group col-xs-12">
		<input
			type="text"
			id="reference_filename"
			class="form-control file-upload-info <?= (isset($validation) && $validation->hasError('reference_file')) ? 'is-invalid' : ''; ?>"
			placeholder="Upload dokumen karya"
			value="<?= (isset($reference) && $reference['reference_file']) ? $reference['reference_file'] : ''; ?>"
			disabled>
			<span class="input-group-append">
				<button class="file-upload-browse btn btn-gradient-primary py-3" type="button" id="upload_reference_file">Upload</button>
			</span>
	</div>
	<p class="error" id="reference_file_error"><?= (isset($validation) && $validation->getError('reference_file')) ? $validation->getError('reference_file') : ''; ?> </p>
</div>
<div class="form-group d-none" id="form-abstract-in">
	<label for="abstract_in" class="required">Abstrak (Bahasa Indonesia)</label>
	<textarea
		name="abstract_in"
		class="form-control <?= ($validation && $validation->hasError('abstract_in')) ? 'is-invalid' : '' ?>"
		id="abstract_in" rows="9"
		placeholder="Abstrak"
	><?= (isset($reference) && $reference['abstract_in']) ? $reference['abstract_in'] : ''; ?></textarea>
	<p class="error" id="abstract_in_error"><?php echo ($validation && $validation->getError('abstract_in')) ? $validation->getError('abstract_in') : '' ; ?></p>
</div>
<div class="form-group d-none" id="form-abstract-en">
	<label for="abstract_en" class="required">Abstract (English Language)</label>
	<textarea
		name="abstract_en"
		class="form-control <?= ($validation && $validation->hasError('abstract_en')) ? 'is-invalid' : '' ?>"
		id="abstract_en" rows="9"
		placeholder="Abstrak"
	><?= (isset($reference) && $reference['abstract_en']) ? $reference['abstract_en'] : ''; ?></textarea>
	<p class="error" id="abstract_en_error"><?php echo ($validation && $validation->getError('abstract_en')) ? $validation->getError('abstract') : '' ; ?></p>
</div>
<div class="form-group d-none" id="form-keywords">
	<label for="keywords" class="required">Kata Kunci</label>
	<input
		type="text"
		name="keywords"
		id="keywords"
		class="form-control <?= ($validation && $validation->hasError('keywords')) ? 'is-invalid' : '' ?>"
		placeholder="Kata Kunci"
		value="<?= (isset($reference) && $reference['keywords']) ? $reference['keywords'] : ''; ?>"
	>
	<p class="error" id="keywords_error"><?php echo ($validation && $validation->getError('keywords')) ? $validation->getError('keywords') : ''; ?></p>
</div>
<div class="form-group d-none" id="form-isbn">
	<label for="sbn" class="required">Nomor ISBN/ISSN/E-ISSN</label>
	<input
		type="text"
		name="isbn"
		id="isbn"
		class="form-control"
		placeholder="Nomor ISBN/ISSN/E-ISSN"
		value="<?= (isset($reference) && $reference['isbn']) ? $reference['isbn'] : ''; ?>"
	>
	<p class="error" id="isbn_error"><?php echo ($validation && $validation->getError('keywords')) ? $validation->getError('keywords') : ''; ?></p>
</div>
<div class="form-group d-none" id="form-publisher">
	<label for="publisher" class="required">Nama Penerbit</label>
	<input
		type="text"
		name="publisher"
		id="publisher"
		class="form-control"
		placeholder="Penerbit"
		value="<?= (isset($reference) && $reference['publisher']) ? $reference['publisher'] : ''; ?>"
	>
	<p class="error" id="publisher_error"><?php echo ($validation && $validation->getError('keywords')) ? $validation->getError('keywords') : ''; ?></p>
</div>
<div class="form-group d-none" id="form-book-publisher">
	<label for="book_publisher" class="required">Penerbit Buku</label>
	<input
		type="text"
		name="book_publisher"
		id="book_publisher"
		class="form-control"
		placeholder="Penerbit"
		value="<?= (isset($reference) && $reference['book_publisher']) ? $reference['book_publisher'] : ''; ?>"
	>
	<p class="error" id="book_publisher_error"><?php echo ($validation && $validation->getError('keywords')) ? $validation->getError('keywords') : ''; ?></p>
</div>
<div class="form-group d-none" id="form-city-book-publisher">
	<label for="city_book_publisher" class="required">Kota Penerbit</label>
	<input
		type="text"
		name="city_book_publisher"
		id="city_book_publisher"
		class="form-control"
		placeholder="Kota Penerbit"
		value="<?= (isset($reference) && $reference['city_book_publisher']) ? $reference['city_book_publisher'] : ''; ?>"
	>
	<p class="error" id="city_book_publisher_error"><?php echo ($validation && $validation->getError('keywords')) ? $validation->getError('keywords') : ''; ?></p>
</div>

<div class="form-group d-none" id="form-peer-reviewed">
		<label for="peer_reviewed" class="required">Melalui Peer Review</label>
		<select class="form-select" id="peer_reviewed" name="peer_reviewed">
			<option value="" disabled></option>  
			<option value="1">Ya</option>
			<option value="2">Tidak</option>
		</select>
		<p class="error" id='peer_reviewed_error'></p>
	</div>
<div class="form-group d-none" id="form-report-program-id">
		<label for="report_program_id" class="required">Nama Program</label>
		<select class="form-select" id="report_program_id" name="report_program_id">
			<option value="" selected disabled></option>  
			<?php foreach($reportProgram as $row) { ?>
				<option value="<?= $row['program_id'] ?>"><?= $row['program_name'] ?></option>
			<?php } ?>
		</select>
		<p class="error" id='report_program_id_error'></p>
	</div>

<div class="form-group d-none" id="form-report-year">
		<label for="report_year" class="required">Tahun Laporan</label>
		<input
			type="year"
			name="report_year"
			id="report_year"
			class="form-control"
			placeholder="Kota Penerbit"
			value="<?= (isset($reference) && $reference['report_year']) ? $reference['city_book_publisher'] : ''; ?>"
		>
		<p class="error" id='report_year_error'></p>
	</div>

<div class="form-group d-none" id="form-summary">
	<label for="summary" class="required">Ringkasan</label>
	<textarea
		name="summary"
		class="form-control <?= ($validation && $validation->hasError('summary')) ? 'is-invalid' : '' ?>"
		id="summary" rows="9"
		placeholder="Abstrak"
	><?= (isset($reference) && $reference['summary']) ? $reference['summary'] : ''; ?></textarea>
	<p class="error" id="summary_error"><?php echo ($validation && $validation->getError('summary')) ? $validation->getError('abstract') : '' ; ?></p>
</div>
<div class="form-group d-none" id="form-book-cover">
	<label for="book_cover" class="required">Sampul Buku</label>
	<input
			type="file"
			name="book_cover"
			id="book_cover"
			class="file-upload-default"
			accept="*image"
		>
	<div class="input-group col-xs-12">
		<input
			type="text"
			id="book_cover"
			class="form-control file-upload-info <?= (isset($validation) && $validation->hasError('book_cover')) ? 'is-invalid' : ''; ?>"
			placeholder="Upload dokumen artikel (pdf)"
			value="<?= (isset($reference) && $reference['book_cover']) ? $reference['book_cover'] : ''; ?>"
			disabled>
			<span class="input-group-append">
				<button class="file-upload-browse btn btn-gradient-primary py-3" type="button" id="upload_book_cover">Upload</button>
			</span>
	</div>
	<p class="error" id="book_cover_error"><?= (isset($validation) && $validation->getError('book_cover')) ? $validation->getError('book_cover') : ''; ?> </p>
</div>

<div style="float: right">
	<a href="my-reference">
		<button type="button" class="btn btn-outline-primary" id="prevBtn1" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Sebelumnya</button>
	</a>
	<button type="button" class="btn btn-primary me-2" id="next_btn_reference-detail" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Selanjutnya</button>
</div>