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
                    <h3 class="font-weight-bold mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin') ?>!</h3>
                    <p>Berikut adalah ringkasan aktivitas koperasi hari ini</p>
                  </div>
                  <div class="text-right">
                    <p><?= date('l, d F Y') ?></p>
                    <p class="mb-0 font-weight-bold text-primary" id="currentTime"></p>
                  </div>
                </div>
              </div>

              <!-- Statistik Cards -->
              <div class="row mt-4">
                <!-- Total Anggota -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Total Anggota</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['total_anggota'] ?? 0) ?></h4>
                          <small class="text-success"><i class="fas fa-user-plus"></i> Anggota Aktif</small>
                        </div>
                        <i class="fas fa-users text-primary fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Petugas -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Total Petugas</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['total_petugas'] ?? 0) ?></h4>
                          <small class="text-info"><i class="fas fa-user-tie"></i> Admin & Bendahara</small>
                        </div>
                          <i class="fas fa-user-shield text-info fa-2x"></i>
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
                          <p class="mb-0 text-muted">Total Simpanan</p>
                          <h4 class="font-weight-bold mb-0"><?= DashboardModel::formatRupiah($stats['total_simpanan'] ?? 0) ?></h4>
                          <small class="text-success"><i class="fas fa-piggy-bank"></i> Dana Koperasi</small>
                        </div>
                          <i class="fas fa-wallet text-success fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Pembiayaan -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-muted">Pembiayaan Aktif</p>
                          <h4 class="font-weight-bold mb-0"><?= number_format($stats['total_pembiayaan'] ?? 0) ?></h4>
                          <small class="text-warning"><i class="fas fa-hand-holding-usd"></i> Pinjaman Berjalan</small>
                        </div>
                          <i class="fas fa-money-bill-wave text-warning fa-2x"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Second Row Stats -->
              <div class="row mt-3">
                <!-- Angsuran Bulan Ini -->
                <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                  <div class="card bg-gradient-primary">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-white opacity-75">Total Angsuran Bulan Ini</p>
                          <h3 class="font-weight-bold mb-0 text-white"><?= DashboardModel::formatRupiah($stats['total_angsuran_bulan_ini'] ?? 0) ?></h3>
                          <small class="text-white"><i class="fas fa-chart-line"></i> Pendapatan Bulan Ini</small>
                        </div>
                          <i class="fas fa-coins text-white fa-3x opacity-50"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Pengajuan Pending -->
                <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                  <div class="card bg-gradient-warning">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <p class="mb-0 text-white opacity-75">Pengajuan Menunggu Approval</p>
                          <h3 class="font-weight-bold mb-0 text-white"><?= number_format($stats['pengajuan_pending'] ?? 0) ?> Pengajuan</h3>
                          <small class="text-white"><i class="fas fa-clock"></i> Butuh Persetujuan Bendahara</small>
                        </div>
                          <i class="fas fa-clipboard-list text-white fa-3x opacity-50"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Charts and Activities -->
              <div class="row mt-4">
                <!-- Transaksi Chart -->
                <div class="col-lg-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                          <h4 class="card-title mb-1">Statistik Transaksi</h4>  
                        </div>
                      </div>
                      <div class="chart-wrapper" style="height: 400px; position: relative;">
                        <canvas id="transaksiChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Recent Activities -->
                <div class="col-lg-12 grid-margin stretch-card mt-4">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Aktivitas Terbaru</h4>
                      <div class="activities">
                        <?php if (!empty($recentActivities)): ?>
                          <?php foreach (array_slice($recentActivities, 0, 5) as $activity): ?>
                            <div class="activity d-flex align-items-start border-bottom py-3">
                              <div class="activity-icon bg-primary bg-opacity-10">
                                <i class="fas fa-exchange-alt text-primary"></i>
                              </div>
                              <div class="activity-content ml-3">
                                <p class="mb-1 font-weight-medium"><?= htmlspecialchars($activity['aktivitas']) ?></p>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($activity['detail']) ?></p>
                                <small class="text-muted"><i class="far fa-clock"></i> <?= DashboardModel::formatDateIndo($activity['waktu']) ?></small>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <p class="text-muted text-center py-4">Belum ada aktivitas</p>
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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

  <script>
    // Real-time Clock
    function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
      document.getElementById('currentTime').textContent = timeString;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Transaksi Chart - Hanya render jika ada data
    const ctx = document.getElementById('transaksiChart').getContext('2d');
    const chartData = <?= json_encode($chartData ?? []) ?>;

    // Cek apakah data ada dan tidak kosong
    if (chartData && Array.isArray(chartData) && chartData.length > 0) {
      const labels = chartData.map(item => {
        const date = new Date(item.bulan + '-01');
        return date.toLocaleDateString('id-ID', {
          month: 'short',
          year: '2-digit'
        });
      });

      const setoranData = chartData.map(item => parseFloat(item.total_setor) || 0);
      const penarikanData = chartData.map(item => parseFloat(item.total_tarik) || 0);

      // Format Rupiah
      const formatRupiah = (value) => {
        return 'Rp ' + parseInt(value).toLocaleString('id-ID');
      };

      // Gradient fill untuk setoran
      const gradientFillSetoran = ctx.createLinearGradient(0, 0, 0, 400);
      gradientFillSetoran.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
      gradientFillSetoran.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

      // Gradient fill untuk penarikan
      const gradientFillPenarikan = ctx.createLinearGradient(0, 0, 0, 400);
      gradientFillPenarikan.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
      gradientFillPenarikan.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: 'Setoran',
              data: setoranData,
              borderColor: '#10b981',
              backgroundColor: gradientFillSetoran,
              borderWidth: 4,
              tension: 0.4,
              pointRadius: 6,
              pointHoverRadius: 8,
              pointBackgroundColor: '#10b981',
              pointBorderColor: '#fff',
              pointBorderWidth: 3,
              pointHoverBorderWidth: 4,
              fill: true,
              order: 1
            },
            {
              label: 'Penarikan',
              data: penarikanData,
              borderColor: '#ef4444',
              backgroundColor: gradientFillPenarikan,
              borderWidth: 4,
              tension: 0.4,
              pointRadius: 6,
              pointHoverRadius: 8,
              pointBackgroundColor: '#ef4444',
              pointBorderColor: '#fff',
              pointBorderWidth: 3,
              pointHoverBorderWidth: 4,
              fill: true,
              order: 2
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
          layout: {
            padding: {
              top: 20,
              right: 20,
              bottom: 10,
              left: 10
            }
          },
          plugins: {
            legend: {
              position: 'top',
              align: 'end',
              labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 20,
                font: {
                  family: "'Plus Jakarta Sans', sans-serif",
                  size: 13,
                  weight: '600'
                },
                color: '#64748b'
              }
            },
            tooltip: {
              backgroundColor: 'rgba(30, 41, 59, 0.95)',
              titleColor: '#fff',
              bodyColor: '#fff',
              borderColor: 'rgba(255, 255, 255, 0.1)',
              borderWidth: 1,
              padding: 16,
              cornerRadius: 12,
              titleFont: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 14,
                weight: '700'
              },
              bodyFont: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 13,
                weight: '500'
              },
              displayColors: true,
              boxPadding: 6,
              callbacks: {
                title: function(context) {
                  return context[0].label;
                },
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed.y !== null && context.parsed.y !== undefined) {
                    label += formatRupiah(context.parsed.y);
                  } else {
                    label += 'Rp 0';
                  }
                  return label;
                },
                afterBody: function(context) {
                  const data = context[0];
                  const setoranValue = setoranData[data.dataIndex];
                  const penarikanValue = penarikanData[data.dataIndex];
                  const saldo = setoranValue - penarikanValue;
                  return '\nSaldo Bersih: ' + formatRupiah(saldo);
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false,
                drawBorder: false
              },
              ticks: {
                font: {
                  family: "'Plus Jakarta Sans', sans-serif",
                  size: 11,
                  weight: '500'
                },
                color: '#64748b',
                maxRotation: 45,
                minRotation: 0
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(226, 232, 240, 0.6)',
                drawBorder: false,
                borderDash: [5, 5]
              },
              ticks: {
                font: {
                  family: "'Plus Jakarta Sans', sans-serif",
                  size: 11,
                  weight: '500'
                },
                color: '#64748b',
                padding: 10,
                callback: function(value) {
                  if (value >= 1000000) {
                    return (value / 1000000).toFixed(1) + ' Jt';
                  } else if (value >= 1000) {
                    return (value / 1000).toFixed(0) + ' Rb';
                  }
                  return value;
                }
              }
            }
          },
          animation: {
            duration: 1200,
            easing: 'easeOutQuart',
            delay: (context) => {
              return context.dataIndex * 50;
            }
          }
        }
      });
    } else {
      // Tampilkan pesan jika tidak ada data
      ctx.font = '14px Arial';
      ctx.fillStyle = '#6b7280';
      ctx.textAlign = 'center';
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
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      color: white;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    .home-header h3 {
      color: white;
    }

    .bg-gradient-primary {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .bg-gradient-warning {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

    /* Trend Badge Styles */
    .trend-badge {
      background: linear-gradient(135deg, rgba(5, 150, 105, 0.05) 0%, rgba(5, 150, 105, 0.1) 100%);
      padding: 12px 16px;
      border-radius: 10px;
      border-left: 4px solid #059669;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all 0.3s ease;
    }

    .trend-badge:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
    }

    .trend-badge .text-success {
      color: #059669 !important;
    }

    .trend-badge .text-danger {
      color: #ef4444 !important;
    }

    .trend-badge .text-muted {
      color: #6b7280 !important;
      font-size: 0.9rem;
    }

    .trend-badge .font-weight-bold {
      font-size: 1.1rem;
    }

    /* Chart container improvements */
    .chart-wrapper {
      width: 100%;
      position: relative;
    }

    .chart-wrapper canvas {
      width: 100% !important;
      height: 100% !important;
    }

    .chart-stats {
      background: #f8fafc;
      padding: 12px 20px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
    }

    @media (max-width: 768px) {
      .chart-wrapper {
        height: 300px !important;
      }

      .chart-stats {
        display: none;
      }
    }
  </style>
</body>

</html>