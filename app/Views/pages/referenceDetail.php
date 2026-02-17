<?php
use Config\MyConstants;
$session = session(); 
$userId = $session->get('user_id');

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
  <div>
    <div class="row">
      <div class="col-12" style="padding: 2rem 2rem 3rem">
        <div>
            <?php if($referenceDetail['created_by'] == $userId && $referenceDetail['status'] == MyConstants::REJECTED) { ?>
              <div class="alert-danger" style="margin-bottom:2rem; margin-top: 1rem;">
                Karya tulis ini harus diperbaiki dengan informasi berikut ini!
               <?php
               if (count($referenceDetail['message']) > 0) { 
                echo "<ul>";
                foreach($referenceDetail['message'] as $row) {
                  echo "<li>";
                    if ($row['doc_name'] == MyConstants::REFERENCE_FILE) {
                       echo "Karya Tulis : ";
                    } else {
                      echo "Surat pernyataan : ";
                    }
                    echo $row['message']."</li>";
                }
                echo "</ul>";

               }
               ?>
               </div>
            <?php } else if($referenceDetail['created_by'] == $userId && $referenceDetail['status'] == MyConstants::UNDER_REVIEW) { ?>
              <div class="alert-danger" style="margin-bottom:1rem">Karya tulis ini sedang direview. Silahkan hubungi admin untuk informasi lebih lanjut!
                 <?php
               if (count($referenceDetail['message']) > 0) { 
                echo "<ul>";
                foreach($referenceDetail['message'] as $row) {
                  echo "<li>";
                    if ($row['doc_name'] == MyConstants::REFERENCE_FILE) {
                       echo "Karya Tulis : ";
                    } else {
                      echo "Surat pernyataan : ";
                    }
                    echo $row['message']."</li>";
                }
                echo "</ul>";

               }
               ?>
              </div>
            <?php }
            ?>
            <div class="publisher d-flex">
                  <?php
                if($referenceDetail['reference_type_id'] == 1) { ?>
                    <div class="badge badge-success"><?=$referenceDetail['reference_type_name']?></div>
                    <div style="margin-left:0.3rem;"><?=$referenceDetail['journal_name'] ?></div>
                <?php } 
                else if ($referenceDetail['reference_type_id'] == 2) { ?>
                <div class="badge badge-info"><?=$referenceDetail['reference_type_name']?></div>
                  <div style="margin-left:0.3rem;"><?=$referenceDetail['proceedings_title'] ?></div>
                <?php } else if ($referenceDetail['reference_type_id'] == 3 || $referenceDetail['reference_type_id'] == 4 || $referenceDetail['reference_type_id'] == 5) {

                if ($referenceDetail['reference_type_id'] == 3) { ?>
                  <div class="badge badge-warning"><?=$referenceDetail['reference_type_name']?></div>
                <?php } else if ($referenceDetail['reference_type_id'] == 4) { ?>
                    <div class="badge badge-danger"><?=$referenceDetail['reference_type_name']?></div>
                  <?php } ?>
                  <div style="margin-left:0.3rem;">Program Studi <?=$referenceDetail['prodi_name'] ?></div>
              <?php } ?> 
            </div>
            <h1><?= $referenceDetail['title']; ?></h1>
            <div class="author-detail">
              <?php 
                $authorName = implode(",", $authors);
                echo $authorName;
              ?>
            </div> 
            <?php if ($referenceDetail['reference_type_id'] == 3 || $referenceDetail['reference_type_id'] == 4 || $referenceDetail['reference_type_id'] == 5) { ?>
              <h5>Dosen Pembimbing</h5>
              <?php if ($firstSupervisor !== '')  {?><div class="keywords">1.&ensp; &ensp;<?= $firstSupervisor; ?></div> <?php } ?>
              <?php if ($secondSupervisor !== '')  {?><div class="keywords">2.&ensp; &ensp;<?= $secondSupervisor; ?></div> <?php } 
            }?>
            <br/>
            <?php if (!empty($referenceDetail['abstract_in'])) { ?> 
            <h5>Abstrak (Bahasa Indonesia)</h5>
            <?= $referenceDetail['abstract_in'] ; ?>
            <?php } if (!empty($referenceDetail['abstract_en'])) { ?>
              <h5>Abstract (English)</h5>
              <?= $referenceDetail['abstract_en']; ?>
            <?php } if (!empty($referenceDetail['summary'])) { ?> <p>
               <h5>Ringkasan</h5>
              <?= $referenceDetail['summary']; ?>
             <?php } if (!empty($referenceDetail['keywords'])) { ?>
              <h5>Keywords</h5>
              <?= $referenceDetail['keywords']; ?>
            <?php } ?>
            <h5>Tentang Koleksi</h5>
            
            <?php if ($referenceDetail['doi']) {?><div class="keywords">Doi: <?= $referenceDetail['doi']; ?></div> <?php } ?>
            <?php if ($referenceDetail['publication_date']) {
              $date = new DateTime($referenceDetail['publication_date']);
            ?>
              <div class="keywords">Tanggal Terbit: <?= $date->format("d F Y"); ?></div>
            <?php } ?>
            </p>
            </p>
            <div class="d-flex" style="margin-top:1rem">
                  <button type="button" class="btn btn-outline-primary btn-fw" id="btn-back" style="margin-right:1rem">Kembali</button>
                <?php
                
                if ($referenceDetail['created_by'] == $userId && ($referenceDetail['status'] == MyConstants::REJECTED || $referenceDetail['status'] == MyConstants::DRAFT)) {?>
                  <button type="button" class="btn btn-primary btn-fw" id="btn-edit" onclick="edit('<?= $referenceDetail['reference_id']; ?>')"><i class="fa fa-pencil" aria-hidden="true" style="color:#fff"></i>&nbsp;Edit</button>
                <?php } else if ($referenceDetail['reference_link']) { ?>
                  <a href="<?=$referenceDetail['reference_link']; ?>"><button type="button" class="btn btn-primary btn-fw">
                    <i class="fa fa-file-pdf-o" aria-hidden="true" style="color:#fff"></i>Download</button></a>
                <?php } else { ?>
                   <a href="/download-reference/<?= $referenceDetail['reference_file'] ?>">
                      <button type="button" class="btn btn-primary btn-fw"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:#fff"></i>&nbsp;Download</button>
                    </a>
                <?php } ?>
                
            </div>
          </div>
        </div>
      </div>
    </div>
    

  </div>
</div>
<script>
 function edit(refId) {
  // window.location.href = '/edit-reference/'+refId;
   window.location.href = '/new-reference?refId='+refId;
 }

<script>
  $(function(){
    // Back button behavior:
    // - If page was opened with window.opener (i.e., from our list via window.open), close the tab so the user returns to the list tab.
    // - Else if there is a referrer from same origin, navigate back in history.
    // - Otherwise, fallback to the main reference list page.
    $('#btn-back').on('click', function(e){
      e.preventDefault();
      try {
        if (window.opener && !window.opener.closed) {
          window.close();
          return;
        }
      } catch (err) {
        // ignore
      }
      if (document.referrer && document.referrer.indexOf(window.location.origin) === 0) {
        window.history.back();
        return;
      }
      // fallback to list page
      window.location.href = '/list';
    });
  });

 function edit(refId) {
  // window.location.href = '/edit-reference/'+refId;
   window.location.href = '/new-reference?refId='+refId;
 }
</script>
<?= $this->endSection('content'); ?>
