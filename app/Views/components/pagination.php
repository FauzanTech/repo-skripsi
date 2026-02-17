
<?php
use Config\MyConstants;

if(!isset($numRows)) {
	$numRows = MyConstants::NUMBER_ROW;
}
if ($totalCount > $numRows) { ?>
	<nav aria-label="Page navigation example">
		<ul class="pagination">
			<?php if ($page > 1) { ?>
				<li class="page-item">
					<a class="page-link" href="<?= $pageUrl ?>?page=<?= $page-1 ?>" aria-label="Previous" id="previous-link">
						<span aria-hidden="true">&laquo;</span>
						<span class="sr-only">Sebelumnya</span>
					</a>
				</li>
			<?php }
				if ($page > 1) {?> <li class="page-item"><a class="page-link" href="<?= $pageUrl ?>?page=<?= $page-1 ?>"><?= $page-1 ?></a></li><?php } ?>
				<li class="page-item"><a class="page-link active" href="<?= $pageUrl ?>?page=<?= $page ?>"><?= $page ?></a></li>
				<?php if ($page * $numRows < $totalCount) { ?>
					<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>?page=<?= $page+1 ?>"><?= $page+1 ?></a></li>
				<?php } ?>
			<?php if (($page + 1) * $numRows < $totalCount) { ?>
				<li class="page-item">
					<a class="page-link" href="<?= $pageUrl ?>?page=<?= $page+1 ?>" aria-label="Next" id="next-link">
						<span aria-hidden="true">&raquo;</span>
						<span class="sr-only">Berikutnya</span>
					</a>
				</li>
			<?php } ?>
		</ul>
	</nav>
	<?php } ?>