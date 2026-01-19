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
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h3 class="page-title">
                      <i class="fas fa-chart-bar menu-icon"></i>
                      Detail Laporan Keuangan
                    </h3>
                  </div>
                  <a href="index.php?controller=keuangan&action=index" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                  </a>
                </div>
              </div>

              <!-- Summary Cards -->
              <div class="row">
                <div class="col-md-3 stretch-card grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-title text-muted text-uppercase font-weight-medium mb-2">Total Aset</p>
                      <h3 class="text-success"><?= KeuanganModel::formatRupiah($ringkasan['total_aset']) ?></h3>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 stretch-card grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-title text-muted text-uppercase font-weight-medium mb-2">Total Pembiayaan</p>
                      <h3 class="text-info"><?= KeuanganModel::formatRupiah($ringkasan['total_hutang_anggota']) ?></h3>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 stretch-card grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-title text-muted text-uppercase font-weight-medium mb-2">Margin</p>
                      <h3 class="text-warning"><?= KeuanganModel::formatRupiah($ringkasan['total_margin']) ?></h3>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 stretch-card grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-title text-muted text-uppercase font-weight-medium mb-2">Rasio H/A</p>
                      <h3 class="text-primary"><?= number_format($ringkasan['rasio_hutang_aset'], 1) ?>%</h3>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statistik Simpanan & Pembiayaan -->
              <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-piggy-bank menu-icon text-success"></i>
                        Statistik Simpanan
                      </h4>
                      <div class="row mt-4">
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded mb-3">
                            <p class="text-muted mb-1 small">Total Simpanan Aktif</p>
                            <h5 class="mb-0"><?= number_format($statistikSimpanan['total_simpanan']) ?></h5>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded mb-3">
                            <p class="text-muted mb-1 small">Total Saldo</p>
                            <h5 class="mb-0 text-success"><?= KeuanganModel::formatRupiah($statistikSimpanan['total_saldo']) ?></h5>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded">
                            <p class="text-muted mb-1 small">Setor Bulan Ini</p>
                            <h5 class="mb-0 text-success"><?= KeuanganModel::formatRupiah($statistikSimpanan['setor_bulan_ini']) ?></h5>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded">
                            <p class="text-muted mb-1 small">Tarik Bulan Ini</p>
                            <h5 class="mb-0 text-danger"><?= KeuanganModel::formatRupiah($statistikSimpanan['tarik_bulan_ini']) ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-hand-holding-usd menu-icon text-info"></i>
                        Statistik Pembiayaan
                      </h4>
                      <div class="row mt-4">
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded mb-3">
                            <p class="text-muted mb-1 small">Total Pembiayaan Aktif</p>
                            <h5 class="mb-0"><?= number_format($statistikPembiayaan['total_pembiayaan']) ?></h5>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded mb-3">
                            <p class="text-muted mb-1 small">Total Plafond</p>
                            <h5 class="mb-0 text-info"><?= KeuanganModel::formatRupiah($statistikPembiayaan['total_plafond']) ?></h5>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded">
                            <p class="text-muted mb-1 small">Total Dibayar</p>
                            <h5 class="mb-0 text-success"><?= KeuanganModel::formatRupiah($statistikPembiayaan['total_dibayar']) ?></h5>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center p-3 bg-light rounded">
                            <p class="text-muted mb-1 small">Sisa Tagihan</p>
                            <h5 class="mb-0 text-warning"><?= KeuanganModel::formatRupiah($statistikPembiayaan['sisa_tagihan']) ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Distribusi Pembiayaan -->
              <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-chart-pie menu-icon text-info"></i>
                        Distribusi Pembiayaan per Jenis Akad
                      </h4>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Jenis Akad</th>
                              <th class="text-center">Jumlah</th>
                              <th>Total Plafond</th>
                              <th>Total Tagihan</th>
                              <th>Total Dibayar</th>
                              <th>Sisa</th>
                              <th>Persentase Bayar</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($distribusiPembiayaan)): ?>
                              <?php foreach ($distribusiPembiayaan as $item): ?>
                                <?php
                                $persenBayar = $item['total_tagihan'] > 0
                                  ? ($item['total_dibayar'] / $item['total_tagihan']) * 100
                                  : 0;
                                ?>
                                <tr>
                                  <td>
                                    <span class="badge badge-info"><?= htmlspecialchars($item['jenis_akad']) ?></span>
                                  </td>
                                  <td class="text-center font-weight-bold"><?= number_format($item['jumlah_akad']) ?></td>
                                  <td><?= KeuanganModel::formatRupiah($item['total_plafond']) ?></td>
                                  <td><?= KeuanganModel::formatRupiah($item['total_tagihan']) ?></td>
                                  <td class="text-success font-weight-bold"><?= KeuanganModel::formatRupiah($item['total_dibayar']) ?></td>
                                  <td class="text-warning font-weight-bold">
                                    <?= KeuanganModel::formatRupiah($item['total_tagihan'] - $item['total_dibayar']) ?>
                                  </td>
                                  <td>
                                    <div class="d-flex align-items-center">
                                      <div class="progress flex-grow-1 mr-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= min($persenBayar, 100) ?>%"></div>
                                      </div>
                                      <span class="font-weight-bold text-muted" style="min-width: 50px;"><?= number_format($persenBayar, 1) ?>%</span>
                                    </div>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data pembiayaan</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Transaksi & Angsuran Terakhir -->
              <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-exchange-alt menu-icon text-success"></i>
                        20 Transaksi Simpanan Terakhir
                      </h4>
                      <div style="max-height: 500px; overflow-y: auto;">
                        <?php if (!empty($transaksiTerakhir)): ?>
                          <?php foreach ($transaksiTerakhir as $trx): ?>
                            <div class="d-flex justify-content-between align-items-start py-2" style="border-bottom: 1px solid #f0f0f0;">
                              <div>
                                <div class="font-weight-bold"><?= htmlspecialchars($trx['nama_lengkap']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($trx['no_anggota']) ?> • <?= htmlspecialchars($trx['nama_simpanan']) ?></small>
                                <div class="small text-muted"><?= KeuanganModel::formatTanggalIndo($trx['tanggal_transaksi']) ?></div>
                              </div>
                              <div class="text-right">
                                <div class="font-weight-bold <?= $trx['jenis_transaksi'] === 'Setor' ? 'text-success' : 'text-danger' ?>">
                                  <?= KeuanganModel::formatRupiah($trx['jumlah']) ?>
                                </div>
                                <?php if ($trx['jenis_transaksi'] === 'Setor'): ?>
                                  <span class="badge badge-success">Setor</span>
                                <?php else: ?>
                                  <span class="badge badge-danger">Tarik</span>
                                <?php endif; ?>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <p class="text-muted text-center py-4">Belum ada transaksi</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-money-bill-wave menu-icon text-info"></i>
                        20 Pembayaran Angsuran Terakhir
                      </h4>
                      <div style="max-height: 500px; overflow-y: auto;">
                        <?php if (!empty($angsuranTerakhir)): ?>
                          <?php foreach ($angsuranTerakhir as $angsuran): ?>
                            <div class="d-flex justify-content-between align-items-start py-2" style="border-bottom: 1px solid #f0f0f0;">
                              <div>
                                <div class="font-weight-bold"><?= htmlspecialchars($angsuran['nama_lengkap']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($angsuran['no_anggota']) ?> • <?= htmlspecialchars($angsuran['no_akad']) ?></small>
                                <div class="small text-muted">
                                  Angsuran ke-<?= $angsuran['angsuran_ke'] ?> • <?= KeuanganModel::formatTanggalIndo($angsuran['tanggal_bayar']) ?>
                                </div>
                              </div>
                              <div class="text-right">
                                <div class="font-weight-bold text-success">
                                  <?= KeuanganModel::formatRupiah($angsuran['jumlah_bayar']) ?>
                                </div>
                                <span class="badge badge-success">Lunas</span>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <p class="text-muted text-center py-4">Belum ada angsuran</p>
                        <?php endif; ?>
                      </div>
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

</body>
</html>
