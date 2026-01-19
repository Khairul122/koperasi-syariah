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
              <!-- Dashboard Header -->
              <div class="home-header">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h3 class="font-weight-bold mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Anggota') ?>!</h3>
                    <p class="mb-0">Berikut adalah ringkasan simpanan dan pembiayaan Anda</p>
                    <small >No. Anggota: <?= htmlspecialchars($_SESSION['no_anggota'] ?? '-') ?></small>
                  </div>
                  <div class="text-right">
                    <p class="mb-0 text-muted"><?= date('l, d F Y') ?></p>
                    <p class="mb-0 font-weight-bold text-primary" id="currentTime"></p>
                  </div>
                </div>
              </div>

              <!-- Statistik Cards -->
              <div class="row mt-4">
                <!-- Saldo Simpanan -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Total Saldo Simpanan</p>
                          <h4 class="font-weight-bold mb-0"><?= DashboardModel::formatRupiah($stats['saldo_simpanan'] ?? 0) ?></h4>
                          <small class="text-success"><i class="fas fa-piggy-bank"></i> Simpanan Anda</small>
                        </div>                    
                          <i class="fas fa-wallet text-success fa-2x"></i>            
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Rekening -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Total Rekening</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['total_rekening'] ?? 0) ?></h4>
                          <small class="text-info"><i class="fas fa-book"></i> Rekening Aktif</small>
                        </div>
                          <i class="fas fa-book text-info fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Pembiayaan Aktif -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Pembiayaan Aktif</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['pembiayaan_aktif']['total'] ?? 0) ?></h4>
                          <small class="text-warning"><i class="fas fa-hand-holding-usd"></i> Pinjaman Berjalan</small>
                        </div>
                          <i class="fas fa-file-invoice-dollar text-warning fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Tagihan -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Total Tagihan Aktif</p>
                          <h4 class="font-weight-bold mb-0"><?= DashboardModel::formatRupiah($stats['pembiayaan_aktif']['total_tagihan'] ?? 0) ?></h4>
                          <small class="text-danger"><i class="fas fa-money-bill-wave"></i> Sisa Pinjaman</small>
                        </div>
                          <i class="fas fa-calculator text-danger fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Daftar Rekening Simpanan -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">💰 Daftar Rekening Simpanan Anda</h4>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>No. Rekening</th>
                              <th>Jenis Simpanan</th>
                              <th>Akad</th>
                              <th>Total Setoran</th>
                              <th>Total Penarikan</th>
                              <th>Saldo</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($stats['daftar_rekening'])): ?>
                              <?php foreach ($stats['daftar_rekening'] as $rekening): ?>
                                <tr>
                                  <td>
                                    <span class="badge badge-light" style="font-family: 'Courier New', monospace; font-weight: 600;">
                                      <?= htmlspecialchars($rekening['no_rekening']) ?>
                                    </span>
                                  </td>
                                  <td><?= htmlspecialchars($rekening['nama_simpanan']) ?></td>
                                  <td>
                                    <span class="badge badge-info"><?= htmlspecialchars($rekening['akad']) ?></span>
                                  </td>
                                  <td class="text-success">
                                    <?= DashboardModel::formatRupiah($rekening['total_setoran']) ?>
                                  </td>
                                  <td class="text-danger">
                                    <?= DashboardModel::formatRupiah($rekening['total_penarikan']) ?>
                                  </td>
                                  <td>
                                    <strong class="text-primary">
                                      <?= DashboardModel::formatRupiah($rekening['saldo_terakhir']) ?>
                                    </strong>
                                  </td>
                                  <td>
                                    <?php if ($rekening['status'] === 'Aktif'): ?>
                                      <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                      <span class="badge badge-secondary"><?= htmlspecialchars($rekening['status']) ?></span>
                                    <?php endif; ?>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="7" class="text-center py-4">
                                  <i class="fas fa-wallet fa-2x text-muted mb-2"></i>
                                  <p class="text-muted mb-0">Belum ada rekening simpanan</p>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Chart & Summary -->
              <div class="row mt-4">
                <!-- Chart Riwayat Simpanan -->
                <div class="col-lg-8 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Riwayat Simpanan (6 Bulan Terakhir)</h4>
                      <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="simpananChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Summary Angsuran -->
                <div class="col-lg-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Ringkasan Angsuran</h4>
                      <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e0e0e0;">
                          <div>
                            <p class="mb-0 text-muted">Total Dibayar</p>
                            <h5 class="mb-0 text-success"><?= DashboardModel::formatRupiah($stats['total_angsuran_dibayar']['total_dibayar'] ?? 0) ?></h5>
                          </div>
                            <i class="fas fa-check-circle text-success"></i>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <p class="mb-0 text-muted">Jumlah Cicilan</p>
                            <h5 class="mb-0"><?= number_format($stats['total_angsuran_dibayar']['total_cicilan'] ?? 0) ?> Kali</h5>
                          </div>
                            <i class="fas fa-list-ol text-info"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Quick Actions -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-4">Akses Cepat</h4>
                      <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                          <a href="index.php?controller=ajukanpinjaman&action=index" class="btn btn-success btn-block">
                            <i class="fas fa-hand-holding-usd mr-2"></i>Ajukan Pinjaman
                          </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                          <a href="index.php?controller=riwayat&action=index" class="btn btn-primary btn-block">
                            <i class="fas fa-list mr-2"></i>Riwayat Transaksi
                          </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                          <a href="index.php?controller=jenissimpanan&action=index" class="btn btn-info btn-block">
                            <i class="fas fa-folder-open mr-2"></i>Jenis Simpanan
                          </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                          <a href="index.php?controller=auth&action=logout" class="btn btn-secondary btn-block">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Daftar Pembiayaan -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">📊 Daftar Pembiayaan Anda</h4>
                        <a href="index.php?controller=ajukanpinjaman&action=create" class="btn btn-primary btn-sm">
                          <i class="fas fa-plus mr-1"></i>Ajukan Baru
                        </a>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>No. Akad</th>
                              <th>Keperluan</th>
                              <th>Total Pinjaman</th>
                              <th>Tenor</th>
                              <th>Sudah Dibayar</th>
                              <th>Cicilan Ke-</th>
                              <th>Sisa Tagihan</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($stats['daftar_pembiayaan'])): ?>
                              <?php foreach ($stats['daftar_pembiayaan'] as $pembiayaan): ?>
                                <tr>
                                  <td>
                                    <span class="badge badge-light" style="font-family: 'Courier New', monospace; font-weight: 600;">
                                      <?= htmlspecialchars($pembiayaan['no_akad']) ?>
                                    </span>
                                  </td>
                                  <td><?= htmlspecialchars(substr($pembiayaan['keperluan'], 0, 50)) ?><?= strlen($pembiayaan['keperluan']) > 50 ? '...' : '' ?></td>
                                  <td><strong><?= DashboardModel::formatRupiah($pembiayaan['total_bayar']) ?></strong></td>
                                  <td><?= $pembiayaan['tenor_bulan'] ?> bln</td>
                                  <td class="text-success">
                                    <?= DashboardModel::formatRupiah($pembiayaan['sudah_dibayar']) ?>
                                  </td>
                                  <td>
                                    <span class="badge badge-info"><?= $pembiayaan['angsuran_ke'] ?> / <?= $pembiayaan['tenor_bulan'] ?></span>
                                  </td>
                                  <td class="text-danger">
                                    <strong><?= DashboardModel::formatRupiah($pembiayaan['sisa_tagihan']) ?></strong>
                                  </td>
                                  <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($pembiayaan['status']) {
                                      case 'Disetujui':
                                        $statusClass = 'badge-warning';
                                        break;
                                      case 'Lunas':
                                        $statusClass = 'badge-success';
                                        break;
                                      default:
                                        $statusClass = 'badge-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                      <?= htmlspecialchars($pembiayaan['status']) ?>
                                    </span>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="8" class="text-center py-4">
                                  <i class="fas fa-hand-holding-usd fa-2x text-muted mb-2"></i>
                                  <p class="text-muted mb-0">Belum ada pembiayaan</p>
                                  <a href="index.php?controller=ajukanpinjaman&action=create" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus mr-1"></i>Ajukan Pembiayaan
                                  </a>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Riwayat Transaksi Terakhir -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">🕐 Riwayat Transaksi Terakhir</h4>
                        <a href="index.php?controller=riwayat&action=index" class="btn btn-outline-primary btn-sm">
                          Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th>Tanggal</th>
                              <th>Jenis Simpanan</th>
                              <th>Jenis Transaksi</th>
                              <th>Jumlah</th>
                              <th>Keterangan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($stats['riwayat_transaksi'])): ?>
                              <?php foreach ($stats['riwayat_transaksi'] as $riwayat): ?>
                                <tr>
                                  <td>
                                    <div><?= DashboardModel::formatDateIndo($riwayat['tanggal_transaksi']) ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($riwayat['tanggal_transaksi'])) ?></small>
                                  </td>
                                  <td>
                                    <small class="text-muted"><?= htmlspecialchars($riwayat['nama_simpanan']) ?></small>
                                  </td>
                                  <td>
                                    <?php if ($riwayat['jenis_transaksi'] === 'Setor'): ?>
                                      <span class="badge badge-success">
                                        <i class="fas fa-arrow-down mr-1"></i>Setor
                                      </span>
                                    <?php else: ?>
                                      <span class="badge badge-danger">
                                        <i class="fas fa-arrow-up mr-1"></i>Tarik
                                      </span>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <strong class="<?= $riwayat['jenis_transaksi'] === 'Setor' ? 'text-success' : 'text-danger' ?>">
                                      <?= $riwayat['jenis_transaksi'] === 'Setor' ? '+' : '-' ?>
                                      <?= DashboardModel::formatRupiah($riwayat['jumlah']) ?>
                                    </strong>
                                  </td>
                                  <td>
                                    <small class="text-muted"><?= htmlspecialchars($riwayat['keterangan'] ?? '-') ?></small>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="5" class="text-center py-4">
                                  <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                  <p class="text-muted mb-0">Belum ada riwayat transaksi</p>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

  <script>
    // Real-time Clock
    function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      document.getElementById('currentTime').textContent = timeString;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Simpanan Chart
    const ctx = document.getElementById('simpananChart').getContext('2d');
    const chartData = <?= json_encode($chartData ?? []) ?>;

    if (chartData && Array.isArray(chartData) && chartData.length > 0) {
      const labels = chartData.map(item => {
        const date = new Date(item.bulan + '-01');
        return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
      });

      const setoranData = chartData.map(item => parseFloat(item.total_setor) || 0);
      const penarikanData = chartData.map(item => parseFloat(item.total_tarik) || 0);
      const saldoData = chartData.map(item => (parseFloat(item.total_setor) || 0) - (parseFloat(item.total_tarik) || 0));

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Saldo Simpanan',
              data: saldoData,
              borderColor: '#059669',
              backgroundColor: 'rgba(5, 150, 105, 0.1)',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              pointRadius: 5,
              pointBackgroundColor: '#059669',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              yAxisID: 'y'
            },
            {
              label: 'Setoran',
              data: setoranData,
              borderColor: '#10b981',
              backgroundColor: 'transparent',
              borderWidth: 2,
              tension: 0.4,
              pointRadius: 3,
              pointBackgroundColor: '#10b981',
              yAxisID: 'y1'
            },
            {
              label: 'Penarikan',
              data: penarikanData,
              borderColor: '#f59e0b',
              backgroundColor: 'transparent',
              borderWidth: 2,
              tension: 0.4,
              pointRadius: 3,
              pointBackgroundColor: '#f59e0b',
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false,
          },
          plugins: {
            legend: {
              position: 'top',
              labels: {
                usePointStyle: true,
                padding: 15,
                font: {
                  size: 12
                }
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              padding: 12,
              titleFont: {
                size: 13
              },
              bodyFont: {
                size: 12
              },
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.raw !== null && context.raw !== undefined) {
                    label += 'Rp ' + parseInt(context.raw).toLocaleString('id-ID');
                  } else {
                    label += 'Rp 0';
                  }
                  return label;
                }
              }
            }
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              beginAtZero: true,
              grid: {
                color: 'rgba(0, 0, 0, 0.05)'
              },
              ticks: {
                callback: function(value) {
                  return 'Rp ' + (value / 1000) + 'k';
                },
                font: {
                  size: 11
                }
              }
            },
            y1: {
              type: 'linear',
              display: false,
              position: 'right',
              grid: {
                drawOnChartArea: false,
              },
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                font: {
                  size: 11
                }
              }
            }
          }
        }
      });
    } else {
      // Tampilkan pesan jika tidak ada data
      ctx.font = '14px Arial';
      ctx.fillStyle = '#6b7280';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText('Belum ada data simpanan untuk ditampilkan', ctx.canvas.width / 2, ctx.canvas.height / 2);
    }
  </script>

  <style>
    .icon-box {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .home-header {
      background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
      color: white;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    .home-header h3 {
      color: white;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      border: none;
    }

    .card-body {
      padding: 25px;
    }

    .btn {
      border-radius: 10px;
      padding: 12px 20px;
      font-weight: 500;
    }

    .table th {
      border-top: none;
      font-weight: 600;
      color: #1a1a1a;
      border-bottom: 2px solid #e0e0e0;
    }

    .table td {
      vertical-align: middle;
    }

    .badge {
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: 500;
    }
  </style>
</body>

</html>
