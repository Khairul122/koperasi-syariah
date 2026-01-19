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
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Detail Tagihan
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=tagihan&action=index"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Informasi Pembiayaan -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">
                  <i class="fas fa-file-contract" style="color: #dc2626; margin-right: 8px;"></i>Informasi Pembiayaan
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. Akad</label>
                    <div style="font-family: 'Courier New', monospace; font-size: 15px; font-weight: 600; color: #dc2626;">
                      <?= htmlspecialchars($tagihan['no_akad'] ?? '-') ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Tanggal Pengajuan</label>
                    <div style="font-size: 14px; color: #1a1a1a;">
                      <?= TagihanModel::formatTanggalIndo($tagihan['tanggal_pengajuan'] ?? '') ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jenis Akad</label>
                    <div style="font-size: 14px; color: #1a1a1a;">
                      <?= htmlspecialchars($tagihan['jenis_akad'] ?? '-') ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Keperluan</label>
                    <div style="font-size: 14px; color: #1a1a1a; max-width: 250px;">
                      <?= htmlspecialchars(substr($tagihan['keperluan'] ?? '', 0, 50)) ?><?= strlen($tagihan['keperluan'] ?? '') > 50 ? '...' : '' ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Rincian Tagihan -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">
                  <i class="fas fa-calculator" style="color: #dc2626; margin-right: 8px;"></i>Rincian Tagihan
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #1e3a8a;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jumlah Pokok</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">
                      <?= TagihanModel::formatRupiah($tagihan['jumlah_pokok'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #f59e0b;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Margin Koperasi</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">
                      <?= TagihanModel::formatRupiah($tagihan['margin_koperasi'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #10b981;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Bayar</label>
                    <div style="font-size: 18px; font-weight: 600; color: #059669;">
                      <?= TagihanModel::formatRupiah($tagihan['total_bayar'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #6366f1;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Tenor</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">
                      <?= $tagihan['tenor_bulan'] ?? 0 ?> Bulan
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #dc2626;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Cicilan / Bulan</label>
                    <div style="font-size: 18px; font-weight: 600; color: #dc2626;">
                      <?= TagihanModel::formatRupiah($tagihan['cicilan_per_bulan'] ?? 0) ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Summary Progress -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">
                  <i class="fas fa-chart-line" style="color: #dc2626; margin-right: 8px;"></i>Progress Pembayaran
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                  <div style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Total Angsuran</label>
                    <div style="font-size: 26px; font-weight: 600;">
                      <?= $tagihan['tenor_bulan'] ?? 0 ?> Kali
                    </div>
                  </div>
                  <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Sudah Dibayar</label>
                    <div style="font-size: 26px; font-weight: 600;">
                      <?= $summary['total_dibayar'] ?? 0 ?> Kali
                    </div>
                  </div>
                  <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Sisa Tagihan</label>
                    <div style="font-size: 22px; font-weight: 600;">
                      <?= TagihanModel::formatRupiah($summary['sisa_tagihan'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Progress</label>
                    <div style="font-size: 26px; font-weight: 600;">
                      <?= round($summary['progress_persen'] ?? 0, 1) ?>%
                    </div>
                  </div>
                </div>

                <!-- Progress Bar -->
                <div style="margin-bottom: 10px;">
                  <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 13px; color: #6b7280;">Progress Pembayaran</span>
                    <span style="font-size: 13px; font-weight: 600; color: #dc2626;"><?= $summary['total_dibayar'] ?? 0 ?> / <?= $tagihan['tenor_bulan'] ?? 0 ?> angsuran</span>
                  </div>
                  <div style="width: 100%; background: #e5e7eb; border-radius: 10px; height: 25px; overflow: hidden;">
                    <div style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); height: 100%; width: <?= round($summary['progress_persen'] ?? 0, 1) ?>%; transition: width 0.5s ease;"></div>
                  </div>
                </div>
              </div>

              <!-- Jadwal Angsuran -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">
                  <i class="fas fa-calendar-check" style="color: #dc2626; margin-right: 8px;"></i>Jadwal Angsuran
                </h3>

                <div style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Ke-</th>
                        <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jatuh Tempo</th>
                        <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal Bayar</th>
                        <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Denda</th>
                        <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Total Bayar</th>
                        <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Status</th>
                        <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Petugas</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach (($jadwalAngsuran ?? []) as $idx => $jadwal): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0; <?= ($jadwal['status'] ?? '') === 'Belum Bayar' ? 'background: #fef3c7;' : '' ?>">
                          <td style="padding: 12px 15px; text-align: center;">
                            <div style="font-weight: 600; color: #dc2626; font-size: 13px;">
                              <?= $jadwal['angsuran_ke'] ?? 0 ?>
                            </div>
                          </td>
                          <td style="padding: 12px 15px; font-size: 13px; color: #1a1a1a;">
                            <?= TagihanModel::formatTanggalIndo($jadwal['tanggal_jatuh_tempo'] ?? '') ?>
                          </td>
                          <td style="padding: 12px 15px; text-align: right; font-size: 14px; font-weight: 600; color: #1a1a1a;">
                            <?= TagihanModel::formatRupiah($jadwal['jumlah'] ?? 0) ?>
                          </td>
                          <td style="padding: 12px 15px; font-size: 13px; color: #1a1a1a;">
                            <?= ($jadwal['tanggal_bayar'] ?? null) ? TagihanModel::formatTanggalIndo($jadwal['tanggal_bayar']) : '-' ?>
                          </td>
                          <td style="padding: 12px 15px; text-align: right; font-size: 13px; color: #ef4444;">
                            <?php if (($jadwal['denda'] ?? 0) > 0): ?>
                              <span style="color: #ef4444; font-weight: 500;">+<?= TagihanModel::formatRupiah($jadwal['denda']) ?></span>
                            <?php else: ?>
                              <span style="color: #10b981;">-</span>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 12px 15px; text-align: right; font-weight: 600; font-size: 14px; color: #059669;">
                            <?= ($jadwal['status'] ?? '') === 'Lunas' ? TagihanModel::formatRupiah($jadwal['total_bayar'] ?? 0) : '-' ?>
                          </td>
                          <td style="padding: 12px 15px; text-align: center;">
                            <?php if (($jadwal['status'] ?? '') === 'Lunas'): ?>
                              <span style="background: #10b981; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <i class="fas fa-check" style="margin-right: 4px;"></i>Lunas
                              </span>
                            <?php else: ?>
                              <span style="background: #f59e0b; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <i class="fas fa-clock" style="margin-right: 4px;"></i>Belum Bayar
                              </span>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 12px 15px; font-size: 12px; color: #6b7280;">
                            <?= ($jadwal['petugas'] ?? null) ? htmlspecialchars($jadwal['petugas']) : '-' ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Summary Table -->
                <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e0e0e0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                  <div style="background: #f0fdf4; padding: 15px; border-radius: 4px; border-left: 4px solid #10b981;">
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 500; margin-bottom: 5px;">Total Sudah Dibayar</div>
                    <div style="font-size: 18px; font-weight: 600; color: #059669;">
                      <?= TagihanModel::formatRupiah($summary['total_nominal'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: #fef2f2; padding: 15px; border-radius: 4px; border-left: 4px solid #ef4444;">
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 500; margin-bottom: 5px;">Total Denda</div>
                    <div style="font-size: 18px; font-weight: 600; color: #ef4444;">
                      <?= TagihanModel::formatRupiah($summary['total_denda'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: #fffbeb; padding: 15px; border-radius: 4px; border-left: 4px solid #f59e0b;">
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 500; margin-bottom: 5px;">Sisa Tagihan</div>
                    <div style="font-size: 18px; font-weight: 600; color: #f59e0b;">
                      <?= TagihanModel::formatRupiah($summary['sisa_tagihan'] ?? 0) ?>
                    </div>
                  </div>
                  <div style="background: #eff6ff; padding: 15px; border-radius: 4px; border-left: 4px solid #1e3a8a;">
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 500; margin-bottom: 5px;">Sisa Angsuran</div>
                    <div style="font-size: 18px; font-weight: 600; color: #1e3a8a;">
                      <?= $summary['sisa_angsuran'] ?? 0 ?> Kali
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
  </script>

  <style>
    /* Table row hover */
    tbody tr:hover {
      background: #f9f9f9 !important;
    }

    a[href*="tagihan&action=index"]:hover {
      background: rgba(220, 38, 38, 0.3) !important;
    }
  </style>
</body>

</html>
