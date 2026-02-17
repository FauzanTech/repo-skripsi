
<?php 
use Config\MyConstants;

function getBatchClass($referenceType) {
	if ($referenceType == 1) {
		return "badge-success";
	} else if ($referenceType == 2) {
		return "badge-info";
	} else if ($referenceType == 3) {
		return "badge-warning";
	} else if ($referenceType == 6) {
		return "badge-danger";
	}
}

if (count($reference) == 0) {
	?>
		<h5>Maaf, tidak ada data untuk saat ini</h5>
	<?php
} else {
	foreach ($reference as $row) {
	// find authors
	$db = db_connect();
	$query = $db->query('select t1.*, t2.fullname, t3.prodi_name from user as t2 right join AUTHORS as t1 on t1.user_id = t2.user_id join prodi as t3 on t2.prodi_id = t3.prodi_id where t1.reference_id = "'.$row['reference_id'].'"');
	$authors = $query->getResultArray();
	$authorName = [];
	foreach ($authors as $author) {
		if ($author['external_author_name']) {
			$authorName[] = $author['external_author_name'];
		} else {
			$authorName[] = $author['fullname'];
		}
	}
	$authorName = implode(', ', $authorName);
	$prodi_name = '';
	// for skripsi/tesis/disertasi, Find prodi of first author
	if ($row['reference_type_id'] == 3 || $row['reference_type_id'] == 4 || $row['reference_type_id'] == 5) {
		if (count($authors) > 0) {
		$prodi_name = $authors[0]['prodi_name'];
		}
	}

	$publisher = '';
	
	if (!empty($row['journal_name'])) {
		$publisher = $row['journal_name'];
	} else if (!empty($row['proceedings_title'])) {
		$publisher = $row['proceedings_title'];
	} else if (!empty($row['book_publisher'])) {
		$publisher = $row['book_publisher'];
	}

	$abstract = '';
	if (!empty($row['abstract_in'])) {
		$abstract = $row['abstract_in'];
	} else if (!empty($row['abstract_en'])) {
		$abstract = $row['abstract_en'];
	} else if (!empty($row['summary'])) {
		$abstract = $row['summary'];
	}

	$db->close();
?>
	<div class="col-12" onclick="seeDetail('<?= $row['reference_id'] ?>')" style="cursor:pointer;margin-top:1rem;">
		<div class="card">
			<div class="card-body">
				<!-- badge class = badge badge-success, badge-info, badge-warning, badge-danger -->
				<div class="publisher d-flex">
					<div class="badge <?= getBatchClass($row['reference_type_id']) ?>"><?=$row['reference_type_name']?></div>
					<?php if($row['published_external'] == 1) { ?>
						<div style="margin-left:0.3rem;"><?=$row['journal_name'] ?: $row['proceedings_title'] ?: $row['book_publisher'] ?></div>
					<?php } else {  ?>
						<div style="margin-left:0.3rem;"><?= $prodi_name ?></div>
					<?php }?>
				</div>
						
				<h5 class="card-title"><?= $row['title'] ?></h5>
				<div class="author">
					 <?php
						echo $authorName;
					?>
				</div>
				<p>
					<?= substr($row['abstract_in'] ?: $row['abstract_en'] ?: $row['summary'], 0, 200) ?> ...
				</p>
				<div class="keywords">Keywords: <?=$row['keywords'] ?></div>
			</div>
		</div>
	</div>

	
<?php } if ($count > MyConstants::NUMBER_ROW) {
	?>

<nav aria-label="Page navigation example">
      <ul class="pagination">
          <?php if ($page > 1) { ?>
			<li class="page-item">
            <a class="page-link" href="/list?page=<?= $page-1 ?>" aria-label="Previous" id="previous-link">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Sebelumnya</span>
            </a>
          </li>
          <?php }
		  if ($count > MyConstants::NUMBER_ROW) {
			if ($page > 1) {?>
          <li class="page-item"><a class="page-link" href="/list?page=<?= $page-1 ?>"><?= $page-1 ?></a></li>
		  <?php } ?>
          <li class="page-item"><a class="page-link active" href="/list?page=<?= $page ?>"><?= $page ?></a></li>
          <li class="page-item"><a class="page-link" href="/list?page=<?= $page+1 ?>"><?= $page+1 ?></a></li> 
        <?php } else { ?>
			<li class="page-item"><a class="page-link active" href="/list?page=<?= $page ?>"><?= $page ?></a></li>
		<?php }
		// buatkan kondisi jika bukan halaman terakhir maka code dibawah muncul
		if (($page * MyConstants::NUMBER_ROW) < $count) {
        ?>
          <li class="page-item">
            <a class="page-link" href="/list?page=<?= $page+1 ?>" aria-label="Next" id="next-link">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Berikutnya</span>
            </a>
          </li>
		<?php } ?>
      </ul>
    </nav>
<?php }
} ?>
