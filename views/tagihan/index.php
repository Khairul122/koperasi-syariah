<?php
// Load required models
require_once __DIR__ . '/../../models/DashboardModel.php';

// Flash messages
$flash_error = $_SESSION['flash_error'] ?? null;
$flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>

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
              <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Tagihan Angsuran
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                   
                    </nav>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Tagihan Aktif -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Tagihan Aktif</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #dc2626;">
                        <?= $statistik['total_tagihan_aktif'] ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #dc2626; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-clock" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Sudah Lunas -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Sudah Lunas</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #10b981;">
                        <?= $statistik['total_lunas'] ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-check-circle" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Sisa Tagihan -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Sisa Tagihan</p>
                      <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #f59e0b;">
                        <?= TagihanModel::formatRupiah($statistik['sisa_tagihan']) ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-money-bill-wave" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Angsuran Bulan Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Bulan Ini</p>
                      <h3 style="margin: 0; font-size: 20px; font-weight: 600; color: #1e3a8a;">
                        <?= $statistik['angsuran_bulan_ini'] ?>x
                      </h3>
                      <div style="font-size: 12px; color: #808080; margin-top: 4px;">
                        <?= TagihanModel::formatRupiah($statistik['nominal_bulan_ini']) ?>
                      </div>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-calendar-alt" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tagihan Jatuh Tempo -->
              <?php if (!empty($tagihanJatuhTempo)): ?>
                <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 20px; margin-bottom: 30px;">
                  <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-exclamation-triangle" style="font-size: 20px; color: white;"></i>
                    </div>
                    <div style="flex: 1;">
                      <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #92400e;">Tagihan Jatuh Tempo (30 Hari Kedepan)</h3>
                      <p style="margin: 5px 0 0 0; font-size: 13px; color: #78716c;">Ada <?= count($tagihanJatuhTempo) ?> tagihan yang akan jatuh tempo. Segera lakukan pembayaran.</p>
                    </div>
                  </div>
                  <div style="display: grid; gap: 10px;">
                    <?php foreach ($tagihanJatuhTempo as $tjt): ?>
                      <div style="background: white; padding: 12px 15px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                          <div style="font-size: 13px; font-weight: 600; color: #1a1a1a;">
                            <?= htmlspecialchars($tjt['no_akad']) ?> - <?= htmlspecialchars($tjt['keperluan']) ?>
                          </div>
                          <div style="font-size: 12px; color: #6b7280;">
                            Angsuran ke-<?= $tjt['angsuran_ke'] ?>: <?= TagihanModel::formatTanggalIndo($tjt['jatuh_tempo']) ?>
                          </div>
                        </div>
                        <div style="text-align: right;">
                          <div style="font-size: 14px; font-weight: 600; color: #dc2626;">
                            <?= TagihanModel::formatRupiah($tjt['cicilan_per_bulan']) ?>
                          </div>
                          <a href="index.php?controller=tagihan&action=detail&id=<?= $tjt['id_pembiayaan'] ?>"
                             style="font-size: 11px; color: #1e3a8a; text-decoration: none; font-weight: 500;">
                            Lihat Detail
                          </a>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Daftar Tagihan -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">
                  <i class="fas fa-list" style="color: #dc2626; margin-right: 8px;"></i>Daftar Tagihan
                </h3>

                <?php if (empty($tagihan)): ?>
                  <div style="text-align: center; padding: 40px; color: #808080;">
                    <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 15px; color: #10b981;"></i>
                    <p style="margin: 0; font-size: 16px; font-weight: 500;">Tidak ada tagihan</p>
                    <p style="margin: 5px 0 0 0; font-size: 13px;">Anda tidak memiliki pembiayaan yang sedang berjalan</p>
                  </div>
                <?php else: ?>
                  <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                      <thead>
                        <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Akad</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keperluan</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tenor</th>
                          <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Progress</th>
                          <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Sisa Tagihan</th>
                          <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Status</th>
                          <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($tagihan as $t):
                          $sisaAngsuran = $t['tenor_bulan'] - $t['total_dibayar'];
                          $sisaTagihan = $t['total_bayar'] - $t['nominal_dibayar'];
                          // Cegah division by zero
                          $progressPersen = ($t['tenor_bulan'] > 0) ? ($t['total_dibayar'] / $t['tenor_bulan']) * 100 : 0;
                        ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 12px 15px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #dc2626; font-size: 12px;">
                                <?= htmlspecialchars($t['no_akad']) ?>
                              </div>
                            </td>
                            <td style="padding: 12px 15px;">
                              <div style="font-size: 13px; font-weight: 500; color: #1a1a1a; max-width: 200px;">
                                <?= htmlspecialchars(substr($t['keperluan'], 0, 50)) ?><?= strlen($t['keperluan']) > 50 ? '...' : '' ?>
                              </div>
                              <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                <?= htmlspecialchars($t['jenis_akad']) ?>
                              </div>
                            </td>
                            <td style="padding: 12px 15px; font-size: 13px; color: #1a1a1a;">
                              <?= $t['tenor_bulan'] ?> bln
                            </td>
                            <td style="padding: 12px 15px;">
                              <div style="text-align: center;">
                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">
                                  <?= $t['total_dibayar'] ?> / <?= $t['tenor_bulan'] ?> angsuran
                                </div>
                                <div style="width: 100%; background: #e5e7eb; border-radius: 10px; height: 8px;">
                                  <div style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); height: 100%; width: <?= round($progressPersen, 1) ?>%; border-radius: 10px;"></div>
                                </div>
                                <div style="font-size: 11px; font-weight: 600; color: #dc2626; margin-top: 4px;">
                                  <?= round($progressPersen, 1) ?>%
                                </div>
                              </div>
                            </td>
                            <td style="padding: 12px 15px; text-align: right;">
                              <div style="font-size: 14px; font-weight: 600; color: #f59e0b;">
                                <?= TagihanModel::formatRupiah($sisaTagihan) ?>
                              </div>
                              <div style="font-size: 11px; color: #6b7280;">
                                <?= $sisaAngsuran ?> x angsuran
                              </div>
                            </td>
                            <td style="padding: 12px 15px; text-align: center;">
                              <?php if ($t['status'] === 'Lunas'): ?>
                                <span style="background: #059669; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                  Lunas
                                </span>
                              <?php else: ?>
                                <span style="background: #dc2626; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                  Aktif
                                </span>
                              <?php endif; ?>
                            </td>
                            <td style="padding: 12px 15px; text-align: center;">
                              <a href="index.php?controller=tagihan&action=detail&id=<?= $t['id_pembiayaan'] ?>"
                                 style="padding: 6px 12px; background: #1e3a8a; color: white; border-radius: 3px; text-decoration: none; font-size: 11px; transition: all 0.3s ease; display: inline-block;">
                                <i class="fas fa-eye"></i> Detail
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
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
  </script>

  <style>
    /* Table row hover */
    tbody tr:hover {
      background: #f9f9f9 !important;
    }

    a[href*="tagihan&action=detail"]:hover {
      background: #1e40af !important;
    }
  </style>
</body>

</html>
