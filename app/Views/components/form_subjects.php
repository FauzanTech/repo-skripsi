<div style="margin-left:1rem">
  <h6>Subjek Terpilih</h6>
  <div id="selected_subjects" class="d-flex" style="flex-wrap: wrap;max-width:100%;">
  <?php if (isset($reference) && $reference['subject_ids']) {
    $subjectIds = json_decode($reference['subject_ids']);
    $filteredSubjects = array_filter($subjects, function($item) use ($subjectIds) {
        return in_array($item['subject_id'], $subjectIds);
      });
    if (count($filteredSubjects) > 0) {
      foreach ($filteredSubjects as $row) {
        echo '<div class="badge badge-gradient-success" style="margin-right:0.7rem;margin-bottom:0.5rem" id="'.$row['subject_id'].'">'.$row['subject_name'].' <i class="fa fa-close" onclick="remove_subject('.$row['subject_id'].')"></i></div>';
      }
    }
    
  } ?>
  </div>
  <div id="subjects_error" class="alert-danger d-none"></div>
</div>

<div class="nav" style="display:block">
  <?php
    foreach($disciplines as $row) { ?>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#<?= $row['disciplines_id']; ?>" aria-expanded="false" aria-controls="<?= $row['disciplines_id']; ?>">
            <span class="menu-title"><?= $row['disciplines_name'] ?></span>
            <i class="menu-arrow fa fa-chevron-down"></i>
        </a>
        <div  id="<?= $row['disciplines_id']; ?>" style="margin-left:1.5rem" class="collapse">
          <ul class="nav flex-column sub-menu">
            <?php if (count($subjects) > 0) {
              foreach ($subjects as $sub) { 
                if ($row['disciplines_id'] == $sub['disciplines_id']) { ?>
                <li class="nav-item">
                  <div class="form-check form-check-flat form-check-primary">
                  <label class="form-check-label" for="<?= $sub['subject_id'] ?>">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    name="subject_id[]"
                    value="<?= $sub['subject_id'] ?>"
                    id="<?= $sub['subject_id'] ?>"
                    <?= (isset($reference) && isset($subjectIds) && in_array($sub['subject_id'], $subjectIds)) ? 'checked' : '' ?>
                  ><?= $sub['subject_name'] ?></label>
                  </div>
                </li>
            <?php }}
            }
            ?>
          </ul>
        </div>
      </li>
    <?php } ?>
</div>

<div style="float: right">
  <a href="my-reference">
    <button type="button" class="btn btn-outline-primary" id="prevBtn2" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Sebelumnya</button>
  </a>
  <button type="button" class="btn btn-primary me-2" id="next_btn_discipline" >Selanjutnya</button>
</div>
<script>
  function remove_subject(subject) {
    $('input[name="subject_id[]"][value="'+subject+'"]').prop('checked', false);
    $('#'+subject).remove();
  }
  $(document).ready(function() {
    $('input[name="subject_id[]"]').change(function() {
      if (this.checked) {
        $('#subjects_error').addClass('d-none');
        var label = $("label[for='" + this.id + "']").text();
        $('#selected_subjects').append('<div class="badge badge-gradient-success" style="margin-right:0.7rem;margin-bottom:0.5rem" id="'+this.value+'">'+label+' <i class="fa fa-close" onclick="remove_subject('+this.value+')"></i></div>');

      } else {
        $('#'+this.value).remove();
      } 
    });
  });
</script>