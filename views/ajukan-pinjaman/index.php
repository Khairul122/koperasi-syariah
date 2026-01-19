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
              <div style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-hand-holding-usd" style="margin-right: 10px;"></i>Pengajuan Pinjaman Saya
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                     
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=ajukanpinjaman&action=create"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-plus" style="margin-right: 8px;"></i>Ajukan Pinjaman Baru
                    </a>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Total Pengajuan -->
                <?php
                $totalPengajuan = count($pembiayaan);
                $totalPending = 0;
                $totalDisetujui = 0;
                $totalDitolak = 0;
                $totalLunas = 0;

                foreach ($pembiayaan as $p) {
                    if ($p['status'] === 'Pending') $totalPending++;
                    elseif ($p['status'] === 'Disetujui') $totalDisetujui++;
                    elseif ($p['status'] === 'Ditolak') $totalDitolak++;
                    elseif ($p['status'] === 'Lunas') $totalLunas++;
                }
                ?>
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Pengajuan</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #1e3a8a;">
                        <?= $totalPengajuan ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-file-alt" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Menunggu Persetujuan -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Menunggu Persetujuan</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #f59e0b;">
                        <?= $totalPending ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-clock" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Disetujui -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Disetujui</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #10b981;">
                        <?= $totalDisetujui ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-check-circle" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Lunas -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Lunas</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #059669;">
                        <?= $totalLunas ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #059669; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-handshake" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Table -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Akad</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal Pengajuan</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keperluan</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tenor</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Status</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($pembiayaan)): ?>
                        <tr>
                          <td colspan="8" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                            <p style="margin: 0; font-size: 14px;">Belum ada pengajuan pinjaman</p>
                            <p style="margin: 5px 0 0 0; font-size: 12px;">
                              <a href="index.php?controller=ajukanpinjaman&action=create" style="color: #1e3a8a; font-weight: 500;">Ajukan pinjaman sekarang</a>
                            </p>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($pembiayaan as $index => $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #1e3a8a; font-size: 14px;">
                                <?= $index + 1 ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #1e3a8a; font-size: 13px;">
                                <?= htmlspecialchars($row['no_akad']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 14px; color: #1a1a1a;">
                              <?= DashboardModel::formatDateIndo($row['tanggal_pengajuan']) ?>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-size: 14px; color: #1a1a1a; max-width: 200px;">
                                <?= htmlspecialchars(substr($row['keperluan'], 0, 50)) ?><?= strlen($row['keperluan']) > 50 ? '...' : '' ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">
                                Rp <?= number_format($row['jumlah_pokok'], 0, ',', '.') ?>
                              </div>
                              <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                Cicilan: Rp <?= number_format($row['cicilan_per_bulan'], 0, ',', '.') ?>/bln
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 14px; color: #1a1a1a;">
                              <?= $row['tenor_bulan'] ?> bulan
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <?php
                              $statusColor = [
                                  'Pending' => '#f59e0b',
                                  'Disetujui' => '#10b981',
                                  'Ditolak' => '#ef4444',
                                  'Lunas' => '#059669'
                              ];
                              $color = $statusColor[$row['status']] ?? '#6b7280';
                              ?>
                              <span style="background: <?= $color ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <?= htmlspecialchars($row['status']) ?>
                              </span>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <a href="index.php?controller=ajukanpinjaman&action=view&id=<?= $row['id_pembiayaan'] ?>"
                                 style="padding: 6px 12px; background: #1e3a8a; color: white; border-radius: 3px; text-decoration: none; font-size: 12px; transition: all 0.3s ease;"
                                 title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
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
  </script>

  <style>
    /* Table row hover */
    tbody tr:hover {
      background: #f9f9f9 !important;
    }

    a[href*="ajukanpinjaman&action=create"]:hover {
      background: rgba(30, 58, 138, 0.3) !important;
    }

    a[href*="ajukanpinjaman&action=view"]:hover {
      background: #1e40af !important;
    }
  </style>
</body>

</html>
