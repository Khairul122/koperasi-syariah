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
                    <h3 class="font-weight-bold mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Bendahara') ?>!</h3>
                    <p>Berikut adalah ringkasan aktivitas keuangan koperasi hari ini</p>
                  </div>
                  <div class="text-right">
                    <p class="mb-0 text-muted"><?= date('l, d F Y') ?></p>
                    <p class="mb-0 font-weight-bold text-primary" id="currentTime"></p>
                  </div>
                </div>
              </div>

              <!-- Statistik Cards Row 1 -->
              <div class="row mt-4">
                <!-- Total Setoran Hari Ini -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Setoran Hari Ini</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['transaksi_hari_ini']['total_setor'] ?? 0) ?></h4>
                          <small class="text-success"><i class="fas fa-arrow-down"></i> <?= DashboardModel::formatRupiah($stats['transaksi_hari_ini']['total_setoran'] ?? 0) ?></small>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10">
                          <i class="fas fa-arrow-circle-down text-success fa-2x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Penarikan Hari Ini -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Penarikan Hari Ini</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['transaksi_hari_ini']['total_tarik'] ?? 0) ?></h4>
                          <small class="text-warning"><i class="fas fa-arrow-up"></i> <?= DashboardModel::formatRupiah($stats['transaksi_hari_ini']['total_penarikan'] ?? 0) ?></small>
                        </div>
                          <i class="fas fa-arrow-circle-up text-warning fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Simpanan -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Total Dana Simpanan</p>
                          <h4 class="font-weight-bold mb-0"><?= DashboardModel::formatRupiah($stats['total_simpanan'] ?? 0) ?></h4>
                          <small class="text-primary"><i class="fas fa-piggy-bank"></i> Saldo Koperasi</small>
                        </div>
                          <i class="fas fa-wallet text-primary fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Transaksi Hari Ini -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Transaksi Hari Ini</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format(($stats['transaksi_hari_ini']['total_setor'] ?? 0) + ($stats['transaksi_hari_ini']['total_tarik'] ?? 0)) ?></h4>
                          <small class="text-info"><i class="fas fa-exchange-alt"></i> Setor & Tarik</small>
                        </div>
                          <i class="fas fa-list-alt text-info fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statistik Cards Row 2 -->
              <div class="row mt-3">
                <!-- Saldo Bersih -->
                <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                  <div class="card bg-gradient-success">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-white opacity-75">Saldo Bersih Hari Ini</p>
                          <h3 class="font-weight-bold mb-0 text-white"><?= DashboardModel::formatRupiah(($stats['transaksi_hari_ini']['total_setoran'] ?? 0) - ($stats['transaksi_hari_ini']['total_penarikan'] ?? 0)) ?></h3>
                          <small class="text-white"><i class="fas fa-balance-scale"></i> Total Setor - Total Tarik</small>
                        </div>
                          <i class="fas fa-coins text-white fa-3x opacity-50"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Anggota -->
                <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                  <div class="card bg-gradient-info">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-white opacity-75">Total Anggota Aktif</p>
                          <h3 class="font-weight-bold mb-0 text-white"><?= number_format($stats['total_anggota'] ?? 0) ?></h3>
                          <small class="text-white"><i class="fas fa-users"></i> Semua Anggota Koperasi</small>
                        </div>
                          <i class="fas fa-users text-white fa-3x opacity-50"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Angsuran Bulan Ini -->
                <div class="col-xl-4 col-sm-12 grid-margin stretch-card">
                  <div class="card bg-gradient-danger">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-white opacity-75">Angsuran Bulan Ini</p>
                          <h3 class="font-weight-bold mb-0 text-white"><?= DashboardModel::formatRupiah($stats['angsuran_bulan_ini']['total_jumlah'] ?? 0) ?></h3>
                          <small class="text-white"><i class="fas fa-chart-line"></i> <?= number_format($stats['angsuran_bulan_ini']['total_angsuran'] ?? 0) ?> Transaksi</small>
                        </div>
                          <i class="fas fa-money-bill-wave text-white fa-3x opacity-50"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statistik Pembiayaan -->
              <div class="row mt-3">
                <!-- Pengajuan Pending -->
                <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                  <div class="card border-left border-warning" style="border-left: 4px solid #f59e0b !important;">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Pengajuan Menunggu Approval</p>
                          <h3 class="font-weight-bold mb-0 text-warning"><?= number_format($stats['pending_approval']['total'] ?? 0) ?> Pengajuan</h3>
                          <small class="text-muted">Total Nominal: <?= DashboardModel::formatRupiah($stats['pending_approval']['total_nominal'] ?? 0) ?></small>
                        </div>
                          <i class="fas fa-clock text-warning fa-2x"></i>
                      </div>
                      <?php if (($stats['pending_approval']['total'] ?? 0) > 0): ?>
                        <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" class="btn btn-warning btn-sm mt-2">
                          <i class="fas fa-check-circle"></i> Proses Sekarang
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <!-- Pembiayaan Aktif -->
                <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                  <div class="card border-left border-success" style="border-left: 4px solid #10b981 !important;">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Pembiayaan Aktif</p>
                          <h3 class="font-weight-bold mb-0 text-success"><?= number_format($stats['pembiayaan_aktif']['total_pembiayaan'] ?? 0) ?> Pembiayaan</h3>
                          <small class="text-muted">Total Nominal: <?= DashboardModel::formatRupiah($stats['pembiayaan_aktif']['total_nominal'] ?? 0) ?></small>
                        </div>
                          <i class="fas fa-file-invoice-dollar text-success fa-2x"></i>
                      </div>
                      <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" class="btn btn-success btn-sm mt-2">
                        <i class="fas fa-list"></i> Lihat Semua
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Charts and Activities -->
              <div class="row mt-4">
                <!-- Transaksi Chart -->
                <div class="col-lg-8 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Statistik Setoran & Penarikan (6 Bulan Terakhir)</h4>
                      <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="transaksiChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Recent Activities -->
                <div class="col-lg-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Transaksi Terbaru</h4>
                      <div class="activities">
                        <?php if (!empty($recentTransactions)): ?>
                          <?php foreach (array_slice($recentTransactions, 0, 5) as $trx): ?>
                            <div class="activity d-flex align-items-start border-bottom py-3">
                              <div class="activity-icon <?= $trx['jenis_transaksi'] === 'Setor' ? 'bg-success' : 'bg-warning' ?> bg-opacity-10">
                                <i class="fas fa-<?= $trx['jenis_transaksi'] === 'Setor' ? 'arrow-down' : 'arrow-up' ?> text-<?= $trx['jenis_transaksi'] === 'Setor' ? 'success' : 'warning' ?>"></i>
                              </div>
                              <div class="activity-content ml-3">
                                <p class="mb-1 font-weight-medium"><?= htmlspecialchars($trx['nama_lengkap']) ?></p>
                                <p class="text-muted small mb-0">
                                  <?= $trx['jenis_transaksi'] === 'Setor' ? 'Setor' : 'Tarik' ?>
                                  Rp <?= number_format($trx['jumlah'], 0, ',', '.') ?>
                                </p>
                                <small class="text-muted"><i class="far fa-clock"></i> <?= DashboardModel::formatDateIndo($trx['tanggal_transaksi']) ?></small>
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
              </div>

              <!-- Quick Actions -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-4">Akses Cepat Bendahara</h4>
                      <div class="row">
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                          <a href="index.php?controller=transaksiSimpanan&action=create" class="btn btn-success btn-block">
                            <i class="fas fa-plus-circle mr-2"></i>Setor/Tarik
                          </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                          <a href="index.php?controller=transaksiSimpanan&action=index" class="btn btn-primary btn-block">
                            <i class="fas fa-list mr-2"></i>Riwayat Transaksi
                          </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                          <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" class="btn btn-danger btn-block">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>Pembiayaan
                          </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                          <a href="index.php?controller=anggota&action=index" class="btn btn-info btn-block">
                            <i class="fas fa-users mr-2"></i>Data Anggota
                          </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                          <a href="index.php?controller=jenisSimpanan&action=index" class="btn btn-warning btn-block">
                            <i class="fas fa-wallet mr-2"></i>Jenis Simpanan
                          </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                          <a href="index.php?controller=angsuran&action=index" class="btn btn-secondary btn-block">
                            <i class="fas fa-hand-holding-usd mr-2"></i>Bayar Angsuran
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Recent Pengajuan Pembiayaan -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">5 Pengajuan Pembiayaan Terbaru</h4>
                        <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" class="btn btn-outline-danger btn-sm">
                          Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>No. Akad</th>
                              <th>Anggota</th>
                              <th>Tanggal</th>
                              <th>Keperluan</th>
                              <th class="text-right">Total</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($recentPengajuan)): ?>
                              <?php foreach ($recentPengajuan as $pengajuan): ?>
                                <tr>
                                  <td>
                                    <span class="font-weight-bold text-danger">
                                      <?= htmlspecialchars($pengajuan['no_akad']) ?>
                                    </span>
                                  </td>
                                  <td>
                                    <strong><?= htmlspecialchars($pengajuan['nama_lengkap']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($pengajuan['no_anggota']) ?></small>
                                  </td>
                                  <td><?= DashboardModel::formatDateIndo($pengajuan['tanggal_pengajuan']) ?></td>
                                  <td>
                                    <?= htmlspecialchars(substr($pengajuan['keperluan'], 0, 30)) ?><?= strlen($pengajuan['keperluan']) > 30 ? '...' : '' ?>
                                  </td>
                                  <td class="text-right">
                                    <strong><?= DashboardModel::formatRupiah($pengajuan['total_bayar']) ?></strong>
                                  </td>
                                  <td class="text-center">
                                    <?php
                                    $statusColor = [
                                        'Pending' => 'warning',
                                        'Disetujui' => 'success',
                                        'Ditolak' => 'danger',
                                        'Lunas' => 'info'
                                    ];
                                    $color = $statusColor[$pengajuan['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $color ?>">
                                      <?= htmlspecialchars($pengajuan['status']) ?>
                                    </span>
                                  </td>
                                  <td class="text-center">
                                    <a href="index.php?controller=ajukanpembiayaan&action=adminView&id=<?= $pengajuan['id_pembiayaan'] ?>"
                                       class="btn btn-sm btn-outline-primary">
                                      <i class="fas fa-eye"></i>
                                    </a>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pengajuan pembiayaan</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Top Anggota -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-4">Top 5 Anggota Penabung Terbesar</h4>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>No. Anggota</th>
                              <th>Nama Lengkap</th>
                              <th class="text-right">Total Simpanan</th>
                              <th class="text-center">Jenis Simpanan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($topAnggota)): ?>
                              <?php foreach ($topAnggota as $anggota): ?>
                                <tr>
                                  <td><?= htmlspecialchars($anggota['no_anggota']) ?></td>
                                  <td>
                                    <strong><?= htmlspecialchars($anggota['nama_lengkap']) ?></strong>
                                  </td>
                                  <td class="text-right">
                                    <strong class="text-success">
                                      Rp <?= number_format($anggota['total_simpanan'], 0, ',', '.') ?>
                                    </strong>
                                  </td>
                                  <td class="text-center">
                                    <span class="badge badge-info"><?= number_format($anggota['jumlah_jenis']) ?> Jenis</span>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data</td>
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

    // Transaksi Chart
    const ctx = document.getElementById('transaksiChart').getContext('2d');
    const chartData = <?= json_encode($chartData ?? []) ?>;

    if (chartData && Array.isArray(chartData) && chartData.length > 0) {
      const labels = chartData.map(item => {
        const date = new Date(item.bulan + '-01');
        return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
      });

      const setoranData = chartData.map(item => parseFloat(item.total_setor) || 0);
      const penarikanData = chartData.map(item => parseFloat(item.total_tarik) || 0);

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Setoran',
              data: setoranData,
              borderColor: '#059669',
              backgroundColor: 'rgba(5, 150, 105, 0.1)',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              pointRadius: 4,
              pointBackgroundColor: '#059669',
              pointBorderColor: '#fff',
              pointBorderWidth: 2
            },
            {
              label: 'Penarikan',
              data: penarikanData,
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.1)',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              pointRadius: 4,
              pointBackgroundColor: '#f59e0b',
              pointBorderColor: '#fff',
              pointBorderWidth: 2
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
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
              beginAtZero: true,
              grid: {
                color: 'rgba(0, 0, 0, 0.05)'
              },
              ticks: {
                callback: function(value) {
                  if (value >= 1000000) {
                    return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                  }
                  return 'Rp ' + (value / 1000) + 'k';
                },
                font: {
                  size: 11
                }
              }
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
          },
          interaction: {
            intersect: false,
            mode: 'index'
          }
        }
      });
    } else {
      // Tampilkan pesan jika tidak ada data
      ctx.font = '14px Arial';
      ctx.fillStyle = '#6b7280';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText('Belum ada data transaksi untuk ditampilkan', ctx.canvas.width / 2, ctx.canvas.height / 2);
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
      background: linear-gradient(135deg, #1e3a2f 0%, #0f1f17 100%);
      color: white;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    .home-header h3 {
      color: white;
    }

    .bg-gradient-success {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .bg-gradient-info {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .bg-gradient-danger {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    .activity {
      max-height: 400px;
      overflow-y: auto;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
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

    .border-left {
      border-left: 4px solid;
    }
  </style>
</body>

</html>
