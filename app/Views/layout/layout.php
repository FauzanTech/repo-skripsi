<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Repository ITH</title>
    <script src="assets/js/jquery-3.7.1.js"></script>
   
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- plugins:css -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/Logo Clean.png" />
    <!-- <script src="assets/js/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  </head>
  <style>
    .form-check {
      margin-top: 0 !important;
    }
    .navbar {
      margin-top: 0!important;
      padding-top: 0!important;
    }
    @media only screen and (max-width: 600px) {
      .btn-login {
        display: none;
      }
    }
  </style>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <a class="navbar-brand brand-logo d-flex" href="/list"><img class="logo-ith" src="assets/images/Logo Clean.png" alt="logo" />
          <div class="d-flex align-self-center ith-text" style="width: 100px;"><strong>Repositori ITH</strong></div>
        </a>
         
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <!-- <div class="d-flex align-self-center" style="width:65%">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
          </div> -->
          <?php 
            $session = session(); 
            if ($session->get('fullname')) {  ?>
              <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item nav-profile dropdown">
                  <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="nav-profile-img">
                      <img  src="assets/images/faces/user.jpeg" alt="image">
                    </div>
                    <div class="nav-profile-text">
                      <p class="mb-1" style="color:#F8F6F9;"><?php echo $session->get('fullname'); 
                      ?></p>
                    </div>
                  </a>
                  <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown" style="width:15rem">
                    <a class="dropdown-item" href="/my-reference">
                      <i class="mdi mdi-cached me-2 text-success"></i>Tulisan Saya </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/change-password">
                      <i class="mdi mdi-logout me-2 text-primary"></i>Ganti Password </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/logout">
                      <i class="mdi mdi-logout me-2 text-primary"></i>Keluar </a>
                  </div>
                </li>

              </ul>
            <?php } else { ?>
              <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item nav-profile dropdown">
                  <button class="btn-login" style="background:#0c5fa6">Login</otton>
                </li>
              </ul>
           <?php }?>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <?php if (!isset($hideSideMenu)) { ?>
          <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <span class="menu-title">Sejak Tahun</span>
                    <i class="menu-arrow"></i>
                </a>
                <div id="ui-basic">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="year" value="<?php echo date("Y") - 1; ?>"><?php echo date("Y") - 1; ?></label>
                      </div>
                    </li>
                    <li class="nav-item">
                      <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="year" value="<?php echo date("Y") - 2; ?>"><?php echo date("Y") - 2; ?></label>
                      </div>
                    </li>
                    <li class="nav-item">
                      <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="year" value="<?php echo date("Y") - 3; ?>"><?php echo date("Y") - 3; ?></label>
                      </div>
                    </li>
                    <li class="nav-item">
                      <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="year" value="<?php echo date("Y") - 4; ?>"><?php echo date("Y") - 4; ?></label>
                      </div>
                    </li>
                    <li class="nav-item">
                      <input type="text" class="form-control" name="since-year" placeholder="Masukkan Tahun"/>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#reference_type" aria-expanded="false" aria-controls="icons">
                  <span class="menu-title">Jenis Koleksi</span>
                  <i class="menu-arrow fa fa-chevron-down"></i>
                </a>
                <div  id="reference_type">
                  <ul class="nav flex-column sub-menu">
                    <?php if (count($referenceType) > 0) {
                      foreach ($referenceType as $row) { ?>
                        <li class="nav-item">
                          <div class="form-check form-check-flat form-check-primary">
                          <label class="form-check-label">
                          <input type="checkbox" class="form-check-input" name="reference-type[]" value="<?= $row['reference_type_id'] ?>"><?= $row['reference_type_name'] ?></label>
                          </div>
                        </li>
                     <?php }
                    }
                    ?>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#dicipline" aria-expanded="false" aria-controls="icons">
                  <span class="menu-title">Disiplin Ilmu</span>
                  <i class="menu-arrow"></i>
                </a>
                <div  id="dicipline">
                  <ul class="nav flex-column sub-menu">
                    <?php foreach ($disciplines as $row) { ?>
                      <li class="nav-item">
                        <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="disciplines[]" value="<?= $row['disciplines_id'] ?>"><?= $row['disciplines_name'] ?></label>
                        </div>
                      </li>
                    <?php } ?>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/">Scholar</a>
              </li>
            </ul>
          </nav>
        <?php } ?>
          <!-- partial:partials/_sidebar.html -->
        <!-- partial -->
       
            <?= $this->renderSection('content'); ?>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          </div>
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023 <a href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>. All rights reserved.</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
            </div>
          </footer>
          <!-- partial -->
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/chart.umd.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="assets/js/jquery.cookie.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
     <script src="/assets/js/form-validation.js"></script>
      <script src="/assets/js/form-upload.js"></script>
      <script src="/assets/js/form-author.js"></script>
    <script>
      $(document).ready(function() {
        $('.btn-login').click(function() {
          window.location= '/auth';
        })
      });  
    </script>
  </body>
</html>