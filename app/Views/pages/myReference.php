<?php 
$session = session();
echo $this->extend('layout/layout');
echo $this->section('content');
?>
<style>
  .container-fluid, .page-body-wrapper {
    width: 100vw;
    align-items: center;
    justify-content: center;
  }
  .navbar {
    border-bottom: solid 1px #fcf6f6;
  }
</style>
 <div class="main-panel">
    <div class="col-12 col-md-12 col-sm-12 col-xs-12" style=" margin:1rem auto;">
      <div >
        <div class="user-info d-flex" style="justify-content: space-between">
            <div class="d-flex">
              <div style="padding:0.5rem;">
                <h2><?= $session->get('fullname'); ?></h2>
                <?= $session->get('prodi_name'); ?> <div class="badge badge-danger"><?= count($reference); ?>&nbsp; Artikel</div>
              </div>
            </div>
            <div style="padding: 1rem 0">
              <a href="new-reference">
                <button class="btn btn-primary">Tambah Koleksi</button>
              </a>
            </div>
        </div>
      </div>
      <div>
        <?php foreach($reference as $row) { ?>
          <div class="article-list" onclick="seeDetail('<?= $row['reference_id'] ?>')" style="cursor:pointer">
            <div class="title"><?= $row['title'] ?></div>
            <div class="author">
              <?php
                $db = db_connect();
                $query = $db->query('select t1.*, t2.fullname from authors as t1 left join user as t2 
                  on t1.user_id = t2.user_id where t1.reference_id = "'.$row['reference_id'].'"');
                $result = $query->getResultArray();
                $authorList = array();
                foreach ($result as $author) {
                  if ($author['external_author_name']) {
                    array_push($authorList, $author['external_author_name']);
                  } else {
                    array_push($authorList, $author['fullname']);
                  }
                }
                echo implode(", ", $authorList);
              ?>
              </div>
            <div class="published-date">Tanggal terbit: <?= $row['publication_date']; ?></div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  function seeDetail(referenceId) {
    window.location = '/reference-detail?id=' + referenceId;
  }
</script>
<?= $this->endSection('content'); ?>
   