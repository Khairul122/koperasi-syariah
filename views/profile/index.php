<?php include('template/header.php'); ?>
<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Page Header -->
              <div class="page-header">
                <h3 class="page-title">
                  <i class="fas fa-user-circle menu-icon"></i>
                  Profil Saya
                </h3>
                <nav aria-label="breadcrumb">
                </nav>
              </div>

              <!-- Flash Messages -->
              <?php
              $flash_error = $_SESSION['flash_error'] ?? null;
              $flash_success = $_SESSION['flash_success'] ?? null;
              unset($_SESSION['flash_error'], $_SESSION['flash_success']);
              ?>

              <div class="row">
                <!-- Profile Photo & Info -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body text-center">
                      <div class="mb-3">
                        <div style="width: 150px; height: 150px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 3px solid #059669;">
                          <i class="fas fa-user" style="font-size: 70px; color: white;"></i>
                        </div>
                      </div>
                      <h4 class="mb-1"><?= htmlspecialchars($profil['nama_lengkap'] ?? '-') ?></h4>
                      <p class="text-muted mb-2"><?= htmlspecialchars($profil['no_anggota'] ?? '-') ?></p>
                      <span class="badge badge-success">Anggota Aktif</span>

                      <hr class="my-3">

                      <div class="text-left">
                        <p class="mb-1"><strong>Username:</strong> <?= htmlspecialchars($profil['username'] ?? '-') ?></p>
                        <p class="mb-1"><strong>No. HP:</strong> <?= htmlspecialchars($profil['no_hp'] ?? '-') ?></p>
                        <p class="mb-0"><strong>Tanggal Daftar:</strong> <?= ProfileModel::formatTanggalIndo($profil['tanggal_daftar']) ?></p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Edit Profile Form -->
                <div class="col-md-8 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-edit menu-icon text-primary"></i>
                        Edit Profil
                      </h4>

                      <form action="index.php?controller=profile&action=update" method="POST" class="mt-4">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Nama Lengkap <span class="text-danger">*</span></label>
                              <input type="text" name="nama_lengkap" class="form-control"
                                     value="<?= htmlspecialchars($profil['nama_lengkap'] ?? '') ?>" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>NIK <span class="text-danger">*</span></label>
                              <input type="text" class="form-control"
                                     value="<?= htmlspecialchars($profil['nik'] ?? '') ?>" disabled>
                              <small class="text-muted">NIK tidak dapat diubah</small>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Jenis Kelamin <span class="text-danger">*</span></label>
                              <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" <?= ($profil['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= ($profil['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>No. HP <span class="text-danger">*</span></label>
                              <input type="text" name="no_hp" class="form-control"
                                     value="<?= htmlspecialchars($profil['no_hp'] ?? '') ?>" required>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Tempat Lahir</label>
                              <input type="text" name="tempat_lahir" class="form-control"
                                     value="<?= htmlspecialchars($profil['tempat_lahir'] ?? '') ?>">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Tanggal Lahir</label>
                              <input type="date" name="tanggal_lahir" class="form-control"
                                     value="<?= htmlspecialchars($profil['tanggal_lahir'] ?? '') ?>">
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label>Pekerjaan</label>
                          <input type="text" name="pekerjaan" class="form-control"
                                 value="<?= htmlspecialchars($profil['pekerjaan'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                          <label>Alamat Lengkap</label>
                          <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($profil['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                          </button>
                          <a href="index.php?controller=dashboard&action=anggota" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                          </a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- Footer -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright © <?= date('Y') ?> Koperasi Syariah. All rights reserved.
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  <?php if ($flash_error): ?>
    alert('GAGAL!\n\n<?= addslashes($flash_error) ?>');
  <?php endif; ?>

  <?php if ($flash_success): ?>
    alert('BERHASIL!\n\n<?= addslashes($flash_success) ?>');
  <?php endif; ?>
});
</script>

</body>
</html>
