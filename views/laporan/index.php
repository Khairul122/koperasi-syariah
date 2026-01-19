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
                  <i class="fas fa-file-pdf menu-icon text-danger"></i>
                  Laporan Koperasi
                </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?controller=dashboard&action=admin">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                  </ol>
                </nav>
              </div>

              <!-- Flash Messages -->
              <?php
              $flash_error = $_SESSION['flash_error'] ?? null;
              $flash_success = $_SESSION['flash_success'] ?? null;
              unset($_SESSION['flash_error'], $_SESSION['flash_success']);
              ?>

              <!-- Laporan Simpanan -->
              <div class="row">
                <div class="col-md-12">
                  <h4 class="mb-3">
                    <i class="fas fa-piggy-bank text-success"></i>
                    Laporan Simpanan
                  </h4>
                </div>
              </div>

              <div class="row">
                <!-- Laporan Simpanan Harian -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fas fa-calendar-day text-primary"></i>
                        Laporan Simpanan Harian
                      </h5>
                      <p class="card-text text-muted">Cetak laporan transaksi simpanan per tanggal</p>

                      <form action="index.php?controller=laporan&action=simpanHarian" method="GET" target="_blank">
                        <input type="hidden" name="controller" value="laporan">
                        <input type="hidden" name="action" value="simpanHarian">

                        <div class="form-group">
                          <label>Pilih Tanggal</label>
                          <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                          <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Laporan Simpanan Bulanan -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fas fa-calendar-alt text-info"></i>
                        Laporan Simpanan Bulanan
                      </h5>
                      <p class="card-text text-muted">Cetak laporan transaksi simpanan per bulan</p>

                      <form action="index.php?controller=laporan&action=simpanBulanan" method="GET" target="_blank">
                        <input type="hidden" name="controller" value="laporan">
                        <input type="hidden" name="action" value="simpanBulanan">

                        <div class="form-group">
                          <label>Pilih Bulan</label>
                          <select name="bulan" class="form-control" required>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                              <option value="<?= $i ?>" <?= $i == date('m') ? 'selected' : '' ?>>
                                <?= [
                                  1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                  5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                  9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ][$i] ?>
                              </option>
                            <?php endfor; ?>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Pilih Tahun</label>
                          <select name="tahun" class="form-control" required>
                            <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                              <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                          </select>
                        </div>

                        <button type="submit" class="btn btn-info btn-block">
                          <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Laporan Simpanan Tahunan -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fas fa-calendar text-success"></i>
                        Laporan Simpanan Tahunan
                      </h5>
                      <p class="card-text text-muted">Cetak laporan transaksi simpanan per tahun</p>

                      <form action="index.php?controller=laporan&action=simpanTahunan" method="GET" target="_blank">
                        <input type="hidden" name="controller" value="laporan">
                        <input type="hidden" name="action" value="simpanTahunan">

                        <div class="form-group">
                          <label>Pilih Tahun</label>
                          <select name="tahun" class="form-control" required>
                            <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                              <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                          </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                          <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Laporan Pembiayaan -->
              <div class="row mt-4">
                <div class="col-md-12">
                  <h4 class="mb-3">
                    <i class="fas fa-hand-holding-usd text-warning"></i>
                    Laporan Pembiayaan
                  </h4>
                </div>
              </div>

              <div class="row">
                <!-- Laporan Pembiayaan Harian -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fas fa-calendar-day text-primary"></i>
                        Laporan Pembiayaan Harian
                      </h5>
                      <p class="card-text text-muted">Cetak laporan pembiayaan per tanggal</p>

                      <form action="index.php?controller=laporan&action=pinjamHarian" method="GET" target="_blank">
                        <input type="hidden" name="controller" value="laporan">
                        <input type="hidden" name="action" value="pinjamHarian">

                        <div class="form-group">
                          <label>Pilih Tanggal</label>
                          <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                          <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Laporan Pembiayaan Bulanan -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fas fa-calendar-alt text-info"></i>
                        Laporan Pembiayaan Bulanan
                      </h5>
                      <p class="card-text text-muted">Cetak laporan pembiayaan per bulan</p>

                      <form action="index.php?controller=laporan&action=pinjamBulanan" method="GET" target="_blank">
                        <input type="hidden" name="controller" value="laporan">
                        <input type="hidden" name="action" value="pinjamBulanan">

                        <div class="form-group">
                          <label>Pilih Bulan</label>
                          <select name="bulan" class="form-control" required>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                              <option value="<?= $i ?>" <?= $i == date('m') ? 'selected' : '' ?>>
                                <?= [
                                  1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                  5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                  9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ][$i] ?>
                              </option>
                            <?php endfor; ?>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Pilih Tahun</label>
                          <select name="tahun" class="form-control" required>
                            <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                              <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                          </select>
                        </div>

                        <button type="submit" class="btn btn-info btn-block">
                          <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Laporan Pembiayaan Tahunan -->
                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fas fa-calendar text-success"></i>
                        Laporan Pembiayaan Tahunan
                      </h5>
                      <p class="card-text text-muted">Cetak laporan pembiayaan per tahun</p>

                      <form action="index.php?controller=laporan&action=pinjamTahunan" method="GET" target="_blank">
                        <input type="hidden" name="controller" value="laporan">
                        <input type="hidden" name="action" value="pinjamTahunan">

                        <div class="form-group">
                          <label>Pilih Tahun</label>
                          <select name="tahun" class="form-control" required>
                            <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                              <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                          </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                          <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
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
