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
              <div style="background: linear-gradient(135deg, #1e3a2f 0%, #0f1f17 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-receipt" style="margin-right: 10px;"></i>Detail Transaksi
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=setor&action=riwayat&no=<?= urlencode($transaksi['no_rekening']) ?>"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-history" style="margin-right: 8px;"></i>Riwayat Rekening
                    </a>
                    <a href="index.php?controller=setor&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Detail Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06); padding: 40px;">

                <!-- Header Transaksi -->
                <div style="text-align: center; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e0e0e0;">
                  <div style="margin-bottom: 15px;">
                    <i class="fas fa-file-invoice-dollar" style="font-size: 60px; color: #1e3a2f;"></i>
                  </div>
                  <h2 style="font-size: 32px; font-weight: 600; color: #1a1a1a; margin: 0 0 10px 0;">
                    <?= htmlspecialchars($transaksi['no_transaksi']) ?>
                  </h2>
                  <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 15px;">
                    <?php if ($transaksi['jenis_transaksi'] === 'Setor'): ?>
                      <span style="background: #2d5a47; color: white; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                        <i class="fas fa-arrow-down" style="margin-right: 5px;"></i>SETORAN
                      </span>
                    <?php else: ?>
                      <span style="background: #8b2a2a; color: white; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                        <i class="fas fa-arrow-up" style="margin-right: 5px;"></i>PENARIKAN
                      </span>
                    <?php endif; ?>
                    <span style="background: #1a4a6a; color: white; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                      <i class="fas fa-check-circle" style="margin-right: 5px;"></i>BERHASIL
                    </span>
                  </div>
                  <p style="margin: 0; font-size: 14px; color: #808080;">
                    <i class="fas fa-calendar" style="margin-right: 5px;"></i>
                    <?= DashboardModel::formatDateIndo($transaksi['tanggal_transaksi']) ?>
                    <span style="margin: 0 8px;">|</span>
                    <i class="fas fa-clock" style="margin-right: 5px;"></i>
                    <?= date('H:i:s', strtotime($transaksi['tanggal_transaksi'])) ?> WIB
                  </p>
                </div>

                <!-- Informasi Anggota -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-bottom: 40px;">
                  <div>
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 20px; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                      <i class="fas fa-user" style="margin-right: 8px; color: #1e3a2f;"></i>Informasi Anggota
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 40%;">Nama Lengkap</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 2%;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($transaksi['nama_lengkap']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">No. Anggota</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($transaksi['no_anggota']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">NIK</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($transaksi['nik']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">No. HP</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;">
                          <i class="fas fa-phone" style="margin-right: 5px;"></i>
                          <?= htmlspecialchars($transaksi['no_hp']) ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Alamat</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($transaksi['alamat']) ?></td>
                      </tr>
                    </table>
                  </div>

                  <div>
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 20px; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                      <i class="fas fa-book" style="margin-right: 8px; color: #1e3a2f;"></i>Informasi Rekening
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 40%;">No. Rekening</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 2%;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a; font-family: 'Courier New', monospace;">
                          <?= htmlspecialchars($transaksi['no_rekening']) ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Jenis Simpanan</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($transaksi['nama_simpanan']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Akad</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;">
                          <span style="background: #1e3a2f; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                            <?= htmlspecialchars($transaksi['akad']) ?>
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Saldo Setelah Transaksi</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 16px; font-weight: 700; color: #1e3a2f;">
                          Rp <?= number_format($transaksi['saldo_terakhir'], 0, ',', '.') ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Status Rekening</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;">
                          <?php if ($transaksi['status_rekening'] === 'Aktif'): ?>
                            <span style="background: #2d5a47; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                              Aktif
                            </span>
                          <?php else: ?>
                            <span style="background: #2a2a2a; color: #b0b0b0; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                              Tutup
                            </span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <!-- Detail Transaksi -->
                <div style="background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%); border: 1px solid #e0e0e0; border-radius: 8px; padding: 30px; margin-bottom: 30px;">
                  <h3 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0 0 25px 0; text-align: center;">
                    <i class="fas fa-money-bill-wave" style="margin-right: 8px; color: #1e3a2f;"></i>
                    Rincian Transaksi
                  </h3>

                  <div style="max-width: 500px; margin: 0 auto;">
                    <!-- Jenis Transaksi -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
                      <span style="font-size: 14px; color: #808080;">Jenis Transaksi</span>
                      <span style="font-size: 15px; font-weight: 600; color: #1a1a1a;">
                        <?= htmlspecialchars($transaksi['jenis_transaksi']) ?>
                      </span>
                    </div>

                    <!-- Keterangan -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
                      <span style="font-size: 14px; color: #808080;">Keterangan</span>
                      <span style="font-size: 15px; font-weight: 600; color: #1a1a1a; text-align: right; max-width: 300px;">
                        <?= htmlspecialchars($transaksi['keterangan'] ?: '-') ?>
                      </span>
                    </div>

                    <!-- Petugas -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
                      <span style="font-size: 14px; color: #808080;">Petugas</span>
                      <span style="font-size: 15px; font-weight: 600; color: #1a1a1a;">
                        <i class="fas fa-user-tie" style="margin-right: 5px;"></i>
                        <?= htmlspecialchars($transaksi['nama_petugas']) ?>
                      </span>
                    </div>

                    <!-- Jumlah -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid #d0d0d0;">
                      <span style="font-size: 16px; font-weight: 600; color: #1a1a1a;">Jumlah</span>
                      <span style="font-size: 28px; font-weight: 700; color: #1e3a2f;">
                        <?= $transaksi['jenis_transaksi'] === 'Setor' ? '+' : '-' ?>
                        Rp <?= number_format($transaksi['jumlah'], 0, ',', '.') ?>
                      </span>
                    </div>

                    <!-- Total -->
                    <div style="background: #1e3a2f; color: white; padding: 20px; border-radius: 6px; margin-top: 20px;">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; font-weight: 500;">Saldo Akhir</span>
                        <span style="font-size: 32px; font-weight: 700;">
                          Rp <?= number_format($transaksi['saldo_terakhir'], 0, ',', '.') ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div style="border-top: 1px solid #e0e0e0; margin: 30px 0; padding-top: 30px;">
                  <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="index.php?controller=setor&action=riwayat&no=<?= urlencode($transaksi['no_rekening']) ?>"
                       style="padding: 14px 28px; background: #1a4a6a; color: white; border-radius: 4px; text-decoration: none; font-size: 16px; font-weight: 500; transition: all 0.3s ease;">
                      <i class="fas fa-history" style="margin-right: 8px;"></i>Lihat Riwayat Rekening
                    </a>
                    <a href="index.php?controller=setor&action=create"
                       style="padding: 14px 28px; background: #8b6914; color: white; border-radius: 4px; text-decoration: none; font-size: 16px; font-weight: 500; transition: all 0.3s ease;">
                      <i class="fas fa-plus" style="margin-right: 8px;"></i>Setor Lagi
                    </a>
                    <a href="index.php?controller=setor&action=index"
                       style="padding: 14px 28px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 16px; font-weight: 500; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
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
    // Print functionality
    function printTransaksi() {
      window.print();
    }
  </script>

  <style>
    a[href*="setor&action=riwayat"]:hover {
      background: #0f2f3a !important;
    }

    a[href*="setor&action=create"]:hover {
      background: #6b4f0f !important;
    }

    a[href*="setor&action=index"]:hover {
      background: #1a1a1a !important;
    }

    @media print {
      .container-scroller .navbar,
      .container-scroller .sidebar,
      .footer,
      button[onclick] {
        display: none !important;
      }

      .main-panel {
        margin: 0 !important;
      }

      a[href*="setor"] {
        display: none !important;
      }
    }
  </style>
</body>

</html>
