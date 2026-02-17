<?php
use Config\MyConstants;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Repository ITH</title>
    <!-- plugins:css -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/Logo Clean.png" />
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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

  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <a class="navbar-brand brand-logo d-flex" href="index.html"><img class="logo-ith" src="assets/images/Logo Clean.png" alt="logo" />
          <div class="d-flex align-self-center ith-text" style="width: 100px;"><strong>Perpustakaan Digital ITH</strong></div>
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
                      <img src="assets/images/faces/face1.jpg" alt="image">
                      <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                      <p class="mb-1" style="color:#F8F6F9;"><?php echo $session->get('fullname'); 
                      ?></p>
                    </div>
                  </a>
                  <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="/change-password">
                      <i class="mdi mdi-logout me-2 text-primary"></i>Ganti Password </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/logout">
                      <i class="mdi mdi-logout me-2 text-primary"></i>Keluar </a>
                  </div>
                </li>

              </ul>
            <?php } ?>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="/dashboard">
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#reference-data" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Koleksi</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="reference-data">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="/published-reference">Telah Terbit</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/unpublished-reference">Referensi Baru</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/rejected-reference">Referensi Ditolak</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#master-data" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Master Data</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="master-data">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="/reference-type">Jenis Dokumen</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/diciplines">Disiplin Ilmu</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/prodi">Program Studi</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#user-data" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manajemen Pengguna</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="user-data">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="/dosen">Dosen</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/mahasiswa">Mahasiswa</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/dosen">Pengguna Umum</a>
                  </li>
                  <?php if ($session->get('role') == MyConstants::SUPERADMIN) { ?>
                  <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                  </li>
                  <?php } ?>
                </ul>
              </div>
            </li>
          </ul>
        </nav>
          <!-- partial:partials/_sidebar.html -->
        <!-- partial -->
        <!-- main-panel starts -->
          <div class="main-panel">
            <?= $this->renderSection('content'); ?>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
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
  </body>
</html>