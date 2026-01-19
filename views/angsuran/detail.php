<?php
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
              <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Detail Angsuran
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                     
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=angsuran&action=index"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Kwitansi Banner -->
              <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <div style="font-size: 12px; opacity: 0.9; margin-bottom: 5px;">No. Kwitansi</div>
                    <div style="font-family: 'Courier New', monospace; font-size: 28px; font-weight: 600; margin: 0;">
                      <?= htmlspecialchars($angsuran['no_kwitansi'] ?? '-') ?>
                    </div>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-size: 12px; opacity: 0.9; margin-bottom: 5px;">Tanggal Pembayaran</div>
                    <div style="font-size: 18px; font-weight: 600;">
                      <?= AngsuranModel::formatTanggalIndo($angsuran['tanggal_bayar'] ?? '') ?>
                    </div>
                  </div>
                </div>
              </div>

              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <!-- Main Content -->
                <div>
                  <!-- Informasi Anggota -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-user" style="color: #3b82f6; margin-right: 8px;"></i>Informasi Anggota
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Nama Lengkap</div>
                        <div style="font-size: 14px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($angsuran['nama_anggota'] ?? '-') ?></div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">No. Anggota</div>
                        <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($angsuran['no_anggota'] ?? '-') ?></div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Alamat</div>
                        <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($angsuran['alamat'] ?? '-') ?></div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">No. HP</div>
                        <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($angsuran['no_hp'] ?? '-') ?></div>
                      </div>
                    </div>
                  </div>

                  <!-- Informasi Pembiayaan -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-file-contract" style="color: #3b82f6; margin-right: 8px;"></i>Informasi Pembiayaan
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">No. Akad</div>
                        <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #dc2626; font-size: 13px;">
                          <?= htmlspecialchars($angsuran['no_akad'] ?? '-') ?>
                        </div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Jenis Akad</div>
                        <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($angsuran['jenis_akad'] ?? '-') ?></div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Tenor</div>
                        <div style="font-size: 14px; color: #1a1a1a;"><?= $angsuran['tenor_bulan'] ?? 0 ?> bulan</div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Cicilan per Bulan</div>
                        <div style="font-size: 14px; font-weight: 600; color: #1a1a1a;">
                          <?= AngsuranModel::formatRupiah($angsuran['cicilan_per_bulan'] ?? 0) ?>
                        </div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Total Tagihan</div>
                        <div style="font-size: 14px; font-weight: 600; color: #dc2626;">
                          <?= AngsuranModel::formatRupiah($angsuran['total_bayar'] ?? 0) ?>
                        </div>
                      </div>
                      <div>
                        <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Angsuran Ke</div>
                        <div style="font-size: 14px; font-weight: 600; color: #3b82f6;"><?= $angsuran['angsuran_ke'] ?? 0 ?></div>
                      </div>
                    </div>
                  </div>

                  <!-- Rincian Pembayaran -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-money-check-alt" style="color: #3b82f6; margin-right: 8px;"></i>Rincian Pembayaran
                    </h3>

                    <div style="padding: 15px; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 4px; margin-bottom: 15px;">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                          <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">Jumlah Bayar</div>
                          <div style="font-size: 28px; font-weight: 600; color: #10b981;">
                            <?= AngsuranModel::formatRupiah($angsuran['jumlah_bayar'] ?? 0) ?>
                          </div>
                        </div>
                        <div style="text-align: right;">
                          <div style="font-size: 12px; color: #6b7280; margin-bottom: 3px;">Denda</div>
                          <div style="font-size: 20px; font-weight: 600; color: <?php echo ($angsuran['denda'] ?? 0) > 0 ? '#ef4444' : '#808080'; ?>;">
                            <?= ($angsuran['denda'] ?? 0) > 0 ? AngsuranModel::formatRupiah($angsuran['denda']) : '-' ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                      <div style="padding: 12px; background: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 4px;">
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px;">Sisa Tagihan</div>
                        <div style="font-weight: 600; color: #f59e0b; font-size: 15px;">
                          <?= AngsuranModel::formatRupiah($angsuran['sisa_tagihan'] ?? 0) ?>
                        </div>
                      </div>
                      <div style="padding: 12px; background: #dbeafe; border-left: 3px solid #3b82f6; border-radius: 4px;">
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px;">Total Dibayar</div>
                        <div style="font-weight: 600; color: #3b82f6; font-size: 15px;">
                          <?= AngsuranModel::formatRupiah(($angsuran['total_bayar'] ?? 0) - ($angsuran['sisa_tagihan'] ?? 0)) ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Riwayat Angsuran Lainnya -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-history" style="color: #3b82f6; margin-right: 8px;"></i>Riwayat Angsuran Lainnya
                    </h3>

                    <?php if (empty($riwayatAngsuran) || count($riwayatAngsuran) <= 1): ?>
                      <div style="text-align: center; padding: 30px 0; color: #808080;">
                        <i class="fas fa-inbox" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                        <p style="font-size: 13px; margin: 0;">Belum ada riwayat angsuran lain</p>
                      </div>
                    <?php else: ?>
                      <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                          <thead>
                            <tr style="background: #f5f5f5;">
                              <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Ke</th>
                              <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">No. Kwitansi</th>
                              <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Tanggal</th>
                              <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Jumlah</th>
                              <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Denda</th>
                              <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Sisa</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach (($riwayatAngsuran ?? []) as $riwayat): ?>
                              <?php if ($riwayat['id_angsuran'] != $angsuran['id_angsuran']): ?>
                                <tr style="border-bottom: 1px solid #f0f0f0; <?php echo $riwayat['id_angsuran'] == $angsuran['id_angsuran'] ? 'background: #f0f9ff;' : ''; ?>">
                                  <td style="padding: 10px; font-size: 12px;"><?= $riwayat['angsuran_ke'] ?? 0 ?></td>
                                  <td style="padding: 10px; font-size: 12px; font-family: 'Courier New', monospace;"><?= htmlspecialchars($riwayat['no_kwitansi'] ?? '-') ?></td>
                                  <td style="padding: 10px; font-size: 12px;"><?= AngsuranModel::formatTanggalIndo($riwayat['tanggal_bayar'] ?? '') ?></td>
                                  <td style="padding: 10px; font-size: 12px; font-weight: 600; color: #10b981;"><?= AngsuranModel::formatRupiah($riwayat['jumlah_bayar'] ?? 0) ?></td>
                                  <td style="padding: 10px; font-size: 12px; color: <?php echo ($riwayat['denda'] ?? 0) > 0 ? '#ef4444' : '#808080'; ?>;">
                                    <?= ($riwayat['denda'] ?? 0) > 0 ? AngsuranModel::formatRupiah($riwayat['denda']) : '-' ?>
                                  </td>
                                  <td style="padding: 10px; font-size: 12px; color: #6b7280;"><?= AngsuranModel::formatRupiah($riwayat['sisa_tagihan'] ?? 0) ?></td>
                                </tr>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Sidebar -->
                <div>
                  <!-- Petugas -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-user-tie" style="color: #3b82f6; margin-right: 8px;"></i>Diproses Oleh
                    </h3>

                    <div style="text-align: center;">
                      <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                        <i class="fas fa-user" style="font-size: 24px; color: white;"></i>
                      </div>
                      <div style="font-weight: 600; color: #1a1a1a; font-size: 15px; margin-bottom: 3px;">
                        <?= htmlspecialchars($angsuran['nama_petugas'] ?? '-') ?>
                      </div>
                      <div style="font-size: 12px; color: #808080;">
                        <?= htmlspecialchars($angsuran['username_petugas'] ?? '-') ?>
                      </div>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-cogs" style="color: #3b82f6; margin-right: 8px;"></i>Aksi
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                      <a href="index.php?controller=angsuran&action=form&id=<?php echo $angsuran['id_pembiayaan']; ?>"
                         style="padding: 12px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; text-align: center; transition: all 0.3s ease;">
                        <i class="fas fa-plus" style="margin-right: 8px;"></i>Tambah Angsuran
                      </a>
                      <a href="index.php?controller=angsuran&action=cetakKwitansi&id=<?php echo $angsuran['id_angsuran']; ?>"
                         style="padding: 12px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; text-align: center; transition: all 0.3s ease; display: block;"
                         target="_blank">
                        <i class="fas fa-file-pdf" style="margin-right: 8px;"></i>Cetak Kwitansi
                      </a>
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

    /* Print styles */
    @media print {
      .container-scroller,
      .navbar,
      .sidebar,
      .setting-panel,
      .footer,
      button[onclick="window.print()"] {
        display: none !important;
      }
      .main-panel {
        margin: 0 !important;
      }
    }
  </style>
</body>

</html>
