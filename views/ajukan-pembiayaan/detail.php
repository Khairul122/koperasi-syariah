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
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Detail Pembiayaan
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=ajukanpembiayaan&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Informasi Pembiayaan -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr) gap: 20px; margin-bottom: 20px;">
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">No. Akad</span>
                    <div style="font-size: 18px; font-weight: 700; color: #dc2626; margin-top: 5px; font-family: 'Courier New', monospace;">
                      <?= htmlspecialchars($pembiayaan['no_akad']) ?>
                    </div>
                  </div>
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Tanggal Pengajuan</span>
                    <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-top: 5px;">
                      <?= DashboardModel::formatDateIndo($pembiayaan['tanggal_pengajuan']) ?>
                    </div>
                  </div>
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Jenis Akad</span>
                    <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-top: 5px;">
                      <span style="background: #dc2626; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: 500;">
                        <?= htmlspecialchars($pembiayaan['jenis_akad']) ?>
                      </span>
                    </div>
                  </div>
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Status</span>
                    <div style="margin-top: 5px;">
                      <?php
                      $statusColors = [
                          'Pending' => '#f59e0b',
                          'Disetujui' => '#10b981',
                          'Ditolak' => '#ef4444',
                          'Lunas' => '#059669'
                      ];
                      $statusColor = $statusColors[$pembiayaan['status']] ?? '#6b7280';
                      ?>
                      <span style="background: <?= $statusColor ?>; color: white; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 600;">
                        <?= htmlspecialchars($pembiayaan['status']) ?>
                      </span>
                    </div>
                  </div>
                </div>

                <div style="padding: 20px; background: #f9fafb; border-radius: 6px; margin-bottom: 20px;">
                  <div style="display: grid; grid-template-columns: 2fr 3fr; gap: 30px;">
                    <div>
                      <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1a1a1a;">Keperluan</h4>
                      <p style="margin: 0; font-size: 14px; color: #1a1a1a; line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($pembiayaan['keperluan'])) ?>
                      </p>
                    </div>
                    <div>
                      <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1a1a1a;">Rincian Pembiayaan</h4>
                      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                          <span style="font-size: 12px; color: #808080;">Jumlah Pokok</span>
                          <div style="font-size: 18px; font-weight: 700; color: #1a1a1a;">
                            Rp <?= number_format($pembiayaan['jumlah_pokok'], 0, ',', '.') ?>
                          </div>
                        </div>
                        <div>
                          <span style="font-size: 12px; color: #808080;">Margin Koperasi</span>
                          <div style="font-size: 18px; font-weight: 700; color: #dc2626;">
                            Rp <?= number_format($pembiayaan['margin_koperasi'], 0, ',', '.') ?>
                          </div>
                        </div>
                        <div>
                          <span style="font-size: 12px; color: #808080;">Total Pembayaran</span>
                          <div style="font-size: 18px; font-weight: 700; color: #dc2626;">
                            Rp <?= number_format($pembiayaan['total_bayar'], 0, ',', '.') ?>
                          </div>
                        </div>
                        <div>
                          <span style="font-size: 12px; color: #808080;">Tenor</span>
                          <div style="font-size: 18px; font-weight: 700; color: #1a1a1a;">
                            <?= $pembiayaan['tenor_bulan'] ?> bulan
                          </div>
                        </div>
                        <div>
                          <span style="font-size: 12px; color: #808080;">Cicilan per Bulan</span>
                          <div style="font-size: 18px; font-weight: 700; color: #1a1a1a;">
                            Rp <?= number_format($pembiayaan['cicilan_per_bulan'], 0, ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <?php if ($pembiayaan['status'] === 'Pending' && $pembiayaan['catatan_bendahara']): ?>
                  <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 6px; padding: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <i class="fas fa-info-circle" style="font-size: 20px; color: #92400e;"></i>
                      <div>
                        <strong style="color: #92400e; font-size: 14px;">Catatan Admin:</strong>
                        <p style="margin: 5px 0 0 0; font-size: 13px; color: #78350f;">
                          <?= htmlspecialchars($pembiayaan['catatan_bendahara']) ?>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Progress Angsuran -->
              <?php if (in_array($pembiayaan['status'], ['Disetujui', 'Lunas'])): ?>
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">
                    <i class="fas fa-chart-line" style="margin-right: 10px; color: #dc2626;"></i>Progress Angsuran
                  </h3>

                  <!-- Summary Cards -->
                  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
                    <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 15px;">
                      <div style="font-size: 12px; color: #166534; margin-bottom: 5px;">Total Angsuran</div>
                      <div style="font-size: 20px; font-weight: 700; color: #15803d;">
                        <?= $totalAngsuran['total_angsuran'] ?? 0 ?> / <?= $pembiayaan['tenor_bulan'] ?>
                      </div>
                    </div>
                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 6px; padding: 15px;">
                      <div style="font-size: 12px; color: #14532d; margin-bottom: 5px;">Sudah Dibayar</div>
                      <div style="font-size: 20px; font-weight: 700; color: #15803d;">
                        Rp <?= number_format($totalAngsuran['total_dibayar'] ?? 0, 0, ',', '.') ?>
                      </div>
                    </div>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 15px;">
                      <div style="font-size: 12px; color: #991b1b; margin-bottom: 5px;">Sisa Tagihan</div>
                      <div style="font-size: 20px; font-weight: 700; color: #dc2626;">
                        Rp <?= number_format(($pembiayaan['total_bayar'] - ($totalAngsuran['total_dibayar'] ?? 0)), 0, ',', '.') ?>
                      </div>
                    </div>
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 15px;">
                      <div style="font-size: 12px; color: #1e40af; margin-bottom: 5px;">Progress</div>
                      <div style="font-size: 20px; font-weight: 700; color: #1e40af;">
                        <?= round((($totalAngsuran['total_angsuran'] ?? 0) / $pembiayaan['tenor_bulan'] * 100), 1) ?>%
                      </div>
                    </div>
                  </div>

                  <!-- Progress Bar -->
                  <div style="margin-bottom: 20px;">
                    <div style="width: 100%; background: #e5e7eb; border-radius: 10px; height: 20px; overflow: hidden;">
                      <div style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); height: 100%; border-radius: 10px; transition: width 0.5s ease; width: <?= round((($totalAngsuran['total_angsuran'] ?? 0) / $pembiayaan['tenor_bulan'] * 100), 1) ?>%; display: flex; align-items: center; justify-content: flex-end; padding-right: 10px;">
                        <span style="color: white; font-size: 11px; font-weight: 600;">
                          <?= $totalAngsuran['total_angsuran'] ?? 0 ?> / <?= $pembiayaan['tenor_bulan'] ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Tabel Riwayat Angsuran -->
                  <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                      <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                          <th style="padding: 12px 15px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a;">Ke-</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a;">Tanggal Bayar</th>
                          <th style="padding: 12px 15px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a;">Jumlah Bayar</th>
                          <th style="padding: 12px 15px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a;">Denda</th>
                          <th style="padding: 12px 15px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a;">Total Bayar</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a;">Petugas</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (empty($riwayatAngsuran)): ?>
                          <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: #808080;">
                              <i class="fas fa-calendar-times" style="font-size: 32px; margin-bottom: 10px; color: #b0b0b0;"></i>
                              <p style="margin: 0; font-size: 13px;">Belum ada riwayat angsuran</p>
                            </td>
                          </tr>
                        <?php else: ?>
                          <?php foreach ($riwayatAngsuran as $angsuran): ?>
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                              <td style="padding: 12px 15px;">
                                <span style="background: #dc2626; color: white; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;">
                                  <?= $angsuran['angsuran_ke'] ?>
                                </span>
                              </td>
                              <td style="padding: 12px 15px; font-size: 14px; color: #1a1a1a;">
                                <?= DashboardModel::formatDateIndo($angsuran['tanggal_bayar']) ?>
                                <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                  <?= date('H:i', strtotime($angsuran['tanggal_bayar'])) ?> WIB
                                </div>
                              </td>
                              <td style="padding: 12px 15px; text-align: right; font-size: 14px; color: #1a1a1a; font-weight: 600;">
                                Rp <?= number_format($angsuran['jumlah_bayar'], 0, ',', '.') ?>
                              </td>
                              <td style="padding: 12px 15px; text-align: right; font-size: 14px; color: #dc2626;">
                                <?= $angsuran['denda'] > 0 ? 'Rp ' . number_format($angsuran['denda'], 0, ',', '.') : '-' ?>
                              </td>
                              <td style="padding: 12px 15px; text-align: right; font-size: 14px; color: #15803d; font-weight: 600;">
                                Rp <?= number_format($angsuran['jumlah_bayar'] + $angsuran['denda'], 0, ',', '.') ?>
                              </td>
                              <td style="padding: 12px 15px; font-size: 13px; color: #808080;">
                                <?= htmlspecialchars($angsuran['nama_petugas'] ?? '-') ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              <?php else: ?>
                <!-- Informasi untuk status Pending/Ditolak -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <?php if ($pembiayaan['status'] === 'Pending'): ?>
                    <div style="text-align: center; padding: 40px 20px;">
                      <i class="fas fa-hourglass-half" style="font-size: 48px; margin-bottom: 15px; color: #f59e0b;"></i>
                      <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: #f59e0b;">Menunggu Persetujuan</h3>
                      <p style="margin: 0; font-size: 14px; color: #808080;">Pengajuan Anda sedang diproses oleh admin. Silakan cek secara berkala.</p>
                    </div>
                  <?php elseif ($pembiayaan['status'] === 'Ditolak'): ?>
                    <div style="text-align: center; padding: 40px 20px;">
                      <i class="fas fa-times-circle" style="font-size: 48px; margin-bottom: 15px; color: #ef4444;"></i>
                      <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: #ef4444;">Pengajuan Ditolak</h3>
                      <p style="margin: 0; font-size: 14px; color: #808080;">Maaf, pengajuan Anda tidak disetujui. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                      <?php if ($pembiayaan['catatan_bendahara']): ?>
                        <div style="margin-top: 20px; padding: 15px; background: #fef2f2; border-radius: 6px; display: inline-block; text-align: left;">
                          <strong style="color: #991b1b;">Alasan:</strong>
                          <p style="margin: 5px 0 0 0; font-size: 14px; color: #991b1b;">
                            <?= htmlspecialchars($pembiayaan['catatan_bendahara']) ?>
                          </p>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

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
    .table th {
      border-top: none;
    }

    .table td {
      vertical-align: middle;
    }
  </style>
</body>

</html>
