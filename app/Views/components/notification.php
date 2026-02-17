
<style>
  .notification {
    position: fixed; /* Stay in place */
    z-index: 100; /* Sit on top */
    left: 0%;
    top: 0%;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }
  .notification-content {
    background: #ffffff;
    width: 23rem;
    height: 16rem;
    border-radius: 10px;
    margin: 30% auto;
    padding: 1.5rem;
    text-align: center;
  }
  .notif-footer {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
  }
  .message {
    font:12px;
    margin-top: 0.5rem;
  }
  .fa-success {
    color: #08a30d;
  }
  .fa-failed {
    color: #db121c;
  }
</style>
<div class="notification">
  <div class="notification-content">
    <h3><?php echo ($_GET['status'] == 'success') ?  "Berhasil" : "Gagal";
    ?></h3>
    <i
      class="<?php echo ($_GET['status'] == 'success') ? "fa fa-success fa-check" : "fa fa-failed fa-times" ?>"
      style="font-size:94px">
    </i>
    <div class="message"><?php echo $_GET['message']; ?></div>
    <div class="notif-footer">
      <button type="button" class="btn btn-outline-primary btn-sm" id="close">Oke</button>
    </div>
    <input type="hidden" value="<?= (isset($_GET['next_location'])) ? $_GET['next_location'] : ''; ?>" id="next_location" />
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#close').click(function() {
      $('.notification').attr("style", "display:none");
      
      const next_location = $('#next_location').val();
      if (next_location !== '') {
        window.location.href = next_location;
      } else {
        location.reload();
      }
    });

  });
</script>
