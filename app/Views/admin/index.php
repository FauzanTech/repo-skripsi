<?php 
use Config\MyConstants;
echo $this->extend('layout/layoutAdmin');
echo $this->section('content');
?>
 <div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white me-2">
					<i class="mdi mdi-home"></i>
				</span> Daftar Referensi
			</h3>
			<!-- <nav aria-label="breadcrumb">
				<ul class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
					</li>
				</ul>
			</nav> -->
		</div>
		<div class="row">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body" style="overflow: auto">
						<table class="table table-hover" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Karya Tulis</th>
									<th>Publikasi</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
            <?php
							$no = ($page-1) * 3 + 1;
							foreach ($reference as $row) { ?>
								<tr>
									<td width="5%"><span class="number"><?= $no++; ?></span></td>
									<td width="75%">
										<div class="article-title"><a href="/reference-status?id=<?= $row['reference_id']?>"><?= $row['title'] ?></a></div>
										<div class="d-flex article-author">
											<div><i class="fa fa-user-o"></i> 
												<?php
													$db = db_connect();
													$query = $db->query('select t1.*, t2.fullname from authors as t1 left join user as t2 
													on t1.user_id = t2.user_id where t1.reference_id = "'.$row['reference_id'].'"');
													$result = $query->getResultArray();
													foreach ($result as $author) {
														if ($author['external_author_name']) {
															echo $author['external_author_name'].', ';
														} else {
															echo $author['fullname'].', ';
														}
													}
												?>
										</div>
											
										</div>
										<p><i class="fa fa-calendar" ></i> <?= $row['created_at'] ?></p>
									</td>
									<td><?= $row['published_external'] ? 'Eksternal' : 'Internal' ?></td>
									<td width="10%">
										<label class="<?= $statusClass ?>"><?= $status ?>
									</label></td>
									
								</tr>
              <?php } ?>
							</tbody>
						</table>
						<?= $this->include('components/pagination', ['totalCount' => $totalCount, 'page' => $page, 'pageUrl' => $pageUrl]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
  $(document).ready(function() {
    $('#next-link').click(function(event) {
      const next = <?php echo json_encode($nextPage); ?>;
      if (!next) {
        event.preventDefault();
      }
    });
    $('#previous-link').click(function(event) {
      const previous = <?php echo json_encode($previousPage); ?>;
      if (!previous) {
        event.preventDefault();
      }
    })
  })
</script>
<?= $this->endSection('content'); ?>