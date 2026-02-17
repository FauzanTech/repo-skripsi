<?php
$session = session();
echo $this->extend('layout/layout');
echo $this->section('content');
?>
 <!-- main-panel starts -->
<div class="main-panel-full">
  <div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <div class="card">
            
            <div class="card-body">
                <h3>Ubah Kata Sandi</h3>   
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= site_url('change-password-proces') ?>">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label for="old_password">Kata Sandi Lama</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter old password">
                    </div>
                    <?php if (isset($validation) && $validation->getError('old_password')): ?>
                        <small class="text-danger"><?= $validation->getError('old_password') ?></small>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="new_password">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password">
                    </div>
                    <?php if (isset($validation) && $validation->getError('new_password')): ?>
                        <small class="text-danger"><?= $validation->getError('new_password') ?></small>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                    <?php if (isset($validation) && $validation->getError('confirm_password')): ?>
                        <small class="text-danger"><?= $validation->getError('confirm_password') ?></small>
                    <?php endif; ?>
                    <a href="<?= site_url('/') ?>" class="btn btn-outline-primary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    
                </form>
                    </div>
                    </div>
        </div>
    </div>
    </div>
</div>
</div>
<?= $this->endSection('content'); ?>
