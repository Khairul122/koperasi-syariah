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
                      <i class="fas fa-history" style="margin-right: 10px;"></i>Riwayat Transaksi Setoran
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=setor&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Informasi Rekening -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">No. Rekening</span>
                    <div style="font-size: 18px; font-weight: 700; color: #1e3a2f; margin-top: 5px;">
                      <?= htmlspecialchars($rekening['no_rekening']) ?>
                    </div>
                  </div>
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Nama Anggota</span>
                    <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-top: 5px;">
                      <?= htmlspecialchars($rekening['nama_lengkap']) ?>
                    </div>
                  </div>
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Jenis Simpanan</span>
                    <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-top: 5px;">
                      <?= htmlspecialchars($rekening['nama_simpanan']) ?>
                    </div>
                  </div>
                  <div>
                    <span style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Saldo Saat Ini</span>
                    <div style="font-size: 18px; font-weight: 700; color: #1e3a2f; margin-top: 5px;">
                      Rp <?= number_format($rekening['saldo_terakhir'], 0, ',', '.') ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                <!-- Total Setor -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 5px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Setor</p>
                      <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #1e3a2f;">
                        <?= number_format($rekening['total_setoran'], 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #2d5a47; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-arrow-down" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Tarik -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 5px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Tarik</p>
                      <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #8b2a2a;">
                        <?= number_format($rekening['total_penarikan'], 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #8b2a2a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-arrow-up" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Transaksi -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 5px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Transaksi</p>
                      <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #1a1a1a;">
                        <?= count($riwayat) ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #2a2a2a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-exchange-alt" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Saldo Terakhir -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 5px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Saldo Terakhir</p>
                      <h3 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e3a2f;">
                        Rp <?= number_format($rekening['saldo_terakhir'], 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #8b6914; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-wallet" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Table Riwayat -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Transaksi</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jenis</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keterangan</th>
                        <th style="padding: 15px 20px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Petugas</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($riwayat)): ?>
                        <tr>
                          <td colspan="8" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                            <p style="margin: 0; font-size: 14px;">Tidak ada riwayat transaksi</p>
                            <p style="margin: 5px 0 0 0; font-size: 12px;">
                              Belum ada transaksi setoran untuk rekening ini
                            </p>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($riwayat as $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #1e3a2f; font-size: 13px;">
                                <?= htmlspecialchars($row['no_transaksi']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 14px; color: #1a1a1a;">
                              <?= DashboardModel::formatDateIndo($row['tanggal_transaksi']) ?>
                              <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                <?= date('H:i:s', strtotime($row['tanggal_transaksi'])) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <?php if ($row['jenis_transaksi'] === 'Setor'): ?>
                                <span style="background: #2d5a47; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                  Setor
                                </span>
                              <?php else: ?>
                                <span style="background: #8b2a2a; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                  Tarik
                                </span>
                              <?php endif; ?>
                            </td>
                            <td style="padding: 15px 20px; font-size: 14px; color: #1a1a1a; max-width: 200px;">
                              <?= htmlspecialchars($row['keterangan'] ?: '-') ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                              <?php if ($row['jenis_transaksi'] === 'Setor'): ?>
                                <div style="font-weight: 600; color: #1e3a2f; font-size: 15px;">
                                  + Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                                </div>
                              <?php else: ?>
                                <div style="font-weight: 600; color: #8b2a2a; font-size: 15px;">
                                  - Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                                </div>
                              <?php endif; ?>
                            </td>
                            <td style="padding: 15px 20px; font-size: 13px; color: #808080;">
                              <?= htmlspecialchars($row['nama_petugas']) ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <a href="index.php?controller=setor&action=view&id=<?= $row['id_transaksi'] ?>"
                                 style="padding: 6px 12px; background: #1a4a6a; color: white; border-radius: 3px; text-decoration: none; font-size: 12px; transition: all 0.3s ease;"
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

              <!-- Action Buttons -->
              <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
                <a href="index.php?controller=setor&action=create&no=<?= urlencode($rekening['no_rekening']) ?>"
                   style="padding: 12px 28px; background: #1e3a2f; color: white; border-radius: 4px; text-decoration: none; font-size: 15px; font-weight: 500; transition: all 0.3s ease;">
                  <i class="fas fa-plus" style="margin-right: 8px;"></i>Setor Baru
                </a>
                <a href="index.php?controller=tarik&action=create&no=<?= urlencode($rekening['no_rekening']) ?>"
                   style="padding: 12px 28px; background: #8b2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 15px; font-weight: 500; transition: all 0.3s ease;">
                  <i class="fas fa-minus" style="margin-right: 8px;"></i>Tarik Baru
                </a>
                <a href="index.php?controller=setor&action=index"
                   style="padding: 12px 28px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 15px; font-weight: 500; transition: all 0.3s ease;">
                  <i class="fas fa-list" style="margin-right: 8px;"></i>Kembali ke Daftar
                </a>
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

    a[href*="setor&action=create"]:hover {
      background: #0f1f17 !important;
    }

    a[href*="tarik&action=create"]:hover {
      background: #6b1a1a !important;
    }

    a[href*="setor&action=index"]:hover {
      background: #1a1a1a !important;
    }

    a[href*="setor&action=view"]:hover {
      background: #0f2f3a !important;
    }
  </style>
</body>

</html>
