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
                  <i class="fas fa-chart-line menu-icon"></i>
                  Laporan Keuangan
                </h3>
              </div>

              <!-- Flash Messages -->
              <?php
              $flash_error = $_SESSION['flash_error'] ?? null;
              $flash_success = $_SESSION['flash_success'] ?? null;
              unset($_SESSION['flash_error'], $_SESSION['flash_success']);
              ?>

              <!-- Summary Cards -->
              <div class="row">
                <!-- Total Aset -->
                <div class="col-md-4 stretch-card grid-margin">
                  <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                      <h4 class="font-weight-normal mb-3">Total Aset</h4>
                      <h2 class="mb-4"><?= KeuanganModel::formatRupiah($ringkasan['total_aset']) ?></h2>
                      <p class="mb-0 text-small">Total saldo simpanan anggota</p>
                    </div>
                  </div>
                </div>

                <!-- Total Hutang Anggota -->
                <div class="col-md-4 stretch-card grid-margin">
                  <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                      <h4 class="font-weight-normal mb-3">Total Pembiayaan</h4>
                      <h2 class="mb-4"><?= KeuanganModel::formatRupiah($ringkasan['total_hutang_anggota']) ?></h2>
                      <p class="mb-0 text-small"><?= $ringkasan['jumlah_pembiayaan'] ?> pembiayaan aktif</p>
                    </div>
                  </div>
                </div>

                <!-- Margin Koperasi -->
                <div class="col-md-4 stretch-card grid-margin">
                  <div class="card bg-gradient-warning card-img-holder text-white">
                    <div class="card-body">
                      <h4 class="font-weight-normal mb-3">Margin Koperasi</h4>
                      <h2 class="mb-4"><?= KeuanganModel::formatRupiah($ringkasan['total_margin']) ?></h2>
                      <p class="mb-0 text-small">Total pendapatan koperasi</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Rasio Keuangan -->
              <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-calculator menu-icon"></i>
                        Rasio Keuangan
                      </h4>
                      <div class="row mt-4">
                        <div class="col-md-4">
                          <div class="text-center">
                            <p class="text-muted mb-2">Rasio Hutang terhadap Aset</p>
                            <h3 class="font-weight-bold"><?= number_format($ringkasan['rasio_hutang_aset'], 2) ?>%</h3>
                            <div class="progress mt-3">
                              <div class="progress-bar bg-success" role="progressbar" style="width: <?= min($ringkasan['rasio_hutang_aset'], 100) ?>%" aria-valuenow="<?= $ringkasan['rasio_hutang_aset'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="text-center">
                            <p class="text-muted mb-2">Margin dari Simpanan</p>
                            <h3 class="font-weight-bold text-success"><?= KeuanganModel::formatRupiah($ringkasan['margin_simpanan']) ?></h3>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="text-center">
                            <p class="text-muted mb-2">Margin dari Pembiayaan</p>
                            <h3 class="font-weight-bold text-info"><?= KeuanganModel::formatRupiah($ringkasan['margin_pembiayaan']) ?></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Charts and Details -->
              <div class="row">
                <!-- Arus Kas Chart -->
                <div class="col-lg-8 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-chart-area menu-icon"></i>
                        Grafik Arus Kas 6 Bulan Terakhir
                      </h4>
                      <canvas id="arusKasChart" style="max-height: 350px;"></canvas>
                    </div>
                  </div>
                </div>

                <!-- Distribusi Aset -->
                <div class="col-lg-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        <i class="fas fa-pie-chart menu-icon"></i>
                        Distribusi Aset
                      </h4>
                      <div class="mt-4" style="max-height: 300px; overflow-y: auto;">
                        <?php if (!empty($distribusiAset)): ?>
                          <?php foreach ($distribusiAset as $item): ?>
                            <div class="mb-3 pb-2" style="border-bottom: 1px solid #f0f0f0;">
                              <div class="d-flex justify-content-between mb-2">
                                <span class="font-weight-bold"><?= htmlspecialchars($item['jenis_simpanan']) ?></span>
                                <span class="font-weight-bold text-success"><?= number_format($item['persentase'], 1) ?>%</span>
                              </div>
                              <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= min($item['persentase'], 100) ?>%"></div>
                              </div>
                              <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted"><?= $item['jumlah_akun'] ?> akun</small>
                                <small class="text-muted"><?= KeuanganModel::formatRupiah($item['total_saldo']) ?></small>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <p class="text-muted text-center py-4">Belum ada data</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Transaksi Terakhir -->
              <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">
                          <i class="fas fa-exchange-alt menu-icon"></i>
                          Transaksi Terakhir
                        </h4>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Tanggal</th>
                              <th>Anggota</th>
                              <th>Jenis Transaksi</th>
                              <th>Jumlah</th>
                              <th>Jenis Simpanan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($transaksiTerakhir)): ?>
                              <?php foreach ($transaksiTerakhir as $trx): ?>
                                <tr>
                                  <td><?= KeuanganModel::formatTanggalIndo($trx['tanggal_transaksi']) ?></td>
                                  <td>
                                    <div class="font-weight-bold"><?= htmlspecialchars($trx['nama_lengkap']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($trx['no_anggota']) ?></small>
                                  </td>
                                  <td>
                                    <?php if ($trx['jenis_transaksi'] === 'Setor'): ?>
                                      <span class="badge badge-success">
                                        <i class="fas fa-arrow-down"></i> <?= htmlspecialchars($trx['jenis_transaksi']) ?>
                                      </span>
                                    <?php else: ?>
                                      <span class="badge badge-danger">
                                        <i class="fas fa-arrow-up"></i> <?= htmlspecialchars($trx['jenis_transaksi']) ?>
                                      </span>
                                    <?php endif; ?>
                                  </td>
                                  <td class="font-weight-bold <?= $trx['jenis_transaksi'] === 'Setor' ? 'text-success' : 'text-danger' ?>">
                                    <?= KeuanganModel::formatRupiah($trx['jumlah']) ?>
                                  </td>
                                  <td><?= htmlspecialchars($trx['nama_simpanan']) ?></td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                      <div class="text-center mt-3">
                        <a href="index.php?controller=keuangan&action=detail" class="btn btn-success">
                          <i class="fas fa-list-alt"></i> Lihat Semua Transaksi
                        </a>
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

<script>
// Show flash messages
document.addEventListener('DOMContentLoaded', function() {
  <?php if ($flash_error): ?>
    alert('GAGAL!\n\n<?= addslashes($flash_error) ?>');
  <?php endif; ?>

  <?php if ($flash_success): ?>
    alert('BERHASIL!\n\n<?= addslashes($flash_success) ?>');
  <?php endif; ?>
});

// Chart Arus Kas
const arusKasCtx = document.getElementById('arusKasChart').getContext('2d');

const chartData = <?= json_encode($grafikArusKas) ?>;

const labels = chartData.map(item => {
  const date = new Date(item.bulan + '-01');
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  return months[date.getMonth()] + ' ' + date.getFullYear();
});

const setoranData = chartData.map(item => parseFloat(item.total_setor) || 0);
const tarikData = chartData.map(item => parseFloat(item.total_tarik) || 0);

new Chart(arusKasCtx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
        label: 'Setoran',
        data: setoranData,
        borderColor: '#1cc88a',
        backgroundColor: 'rgba(28, 200, 138, 0.1)',
        borderWidth: 3,
        tension: 0.4,
        pointRadius: 5,
        pointHoverRadius: 7,
        pointBackgroundColor: '#1cc88a',
        pointBorderColor: '#fff',
        pointBorderWidth: 3,
        fill: true
      },
      {
        label: 'Penarikan',
        data: tarikData,
        borderColor: '#e74a3b',
        backgroundColor: 'rgba(231, 74, 59, 0.1)',
        borderWidth: 3,
        tension: 0.4,
        pointRadius: 5,
        pointHoverRadius: 7,
        pointBackgroundColor: '#e74a3b',
        pointBorderColor: '#fff',
        pointBorderWidth: 3,
        fill: true
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        position: 'top',
        align: 'end'
      },
      tooltip: {
        backgroundColor: 'rgba(30, 41, 59, 0.95)',
        padding: 12,
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            if (label) {
              label += ': ';
            }
            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
            return label;
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'Rp ' + (value / 1000).toFixed(0) + 'K';
          }
        }
      }
    }
  }
});
</script>

</body>
</html>
