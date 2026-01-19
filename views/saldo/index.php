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
              <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-wallet" style="margin-right: 10px;"></i>Saldo Simpanan Saya
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=simpanan&action=create"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-plus" style="margin-right: 8px;"></i>Buka Rekening Baru
                    </a>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Total Saldo -->
                <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 6px; padding: 24px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: rgba(255, 255, 255, 0.9); text-transform: uppercase; font-weight: 500;">Total Saldo</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600;">
                        <?= SaldoModel::formatRupiah($statistik['total_saldo']) ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-wallet" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Setoran -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Setoran</p>
                      <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #10b981;">
                        <?= SaldoModel::formatRupiah($statistik['total_setoran']) ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-arrow-down" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Penarikan -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Penarikan</p>
                      <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #ef4444;">
                        <?= SaldoModel::formatRupiah($statistik['total_penarikan']) ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-arrow-up" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Jumlah Rekening -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Rekening Aktif</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #1e3a8a;">
                        <?= $statistik['total_rekening_aktif'] ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-credit-card" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Daftar Rekening -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #059669; padding-bottom: 10px;">
                  <i class="fas fa-list" style="color: #059669; margin-right: 8px;"></i>Daftar Rekening Simpanan
                </h3>

                <?php if (empty($rekening)): ?>
                  <div style="text-align: center; padding: 40px; color: #808080;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                    <p style="margin: 0; font-size: 16px; font-weight: 500;">Belum ada rekening simpanan</p>
                    <p style="margin: 5px 0 0 0; font-size: 13px;">
                      <a href="index.php?controller=simpanan&action=create" style="color: #059669; font-weight: 500;">Buka rekening simpanan sekarang</a>
                    </p>
                  </div>
                <?php else: ?>
                  <div style="display: grid; gap: 20px;">
                    <?php foreach ($rekening as $idx => $rek): ?>
                      <div style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; transition: all 0.3s ease; <?= $rek['status'] === 'Aktif' ? 'background: white;' : 'background: #f9fafb; opacity: 0.7;' ?>">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
                          <div style="flex: 1; min-width: 280px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                              <span style="background: #1e3a8a; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <?= htmlspecialchars($rek['akad']) ?>
                              </span>
                              <span style="background: <?= $rek['status'] === 'Aktif' ? '#10b981' : '#ef4444' ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <?= htmlspecialchars($rek['status']) ?>
                              </span>
                            </div>

                            <h4 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">
                              <?= htmlspecialchars($rek['nama_simpanan']) ?>
                            </h4>

                            <div style="font-family: 'Courier New', monospace; font-size: 13px; color: #6b7280; margin-bottom: 12px;">
                              <i class="fas fa-hashtag" style="margin-right: 5px;"></i><?= htmlspecialchars($rek['no_rekening']) ?>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; font-size: 13px;">
                              <div>
                                <span style="color: #808080;">Saldo:</span>
                                <span style="font-weight: 600; color: #059669; font-size: 15px;"><?= SaldoModel::formatRupiah($rek['saldo_terakhir']) ?></span>
                              </div>
                              <div>
                                <span style="color: #808080;">Total Setor:</span>
                                <span style="font-weight: 500; color: #10b981;"><?= SaldoModel::formatRupiah($rek['total_setoran']) ?></span>
                              </div>
                              <div>
                                <span style="color: #808080;">Total Tarik:</span>
                                <span style="font-weight: 500; color: #ef4444;"><?= SaldoModel::formatRupiah($rek['total_penarikan']) ?></span>
                              </div>
                            </div>
                          </div>

                          <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php if ($rek['status'] === 'Aktif'): ?>
                              <a href="index.php?controller=saldo&action=detail&id=<?= $rek['id_simpanan'] ?>"
                                 style="padding: 10px 20px; background: #059669; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="fas fa-eye" style="margin-right: 6px;"></i>Lihat Detail
                              </a>
                            <?php else: ?>
                              <span style="padding: 10px 20px; background: #6b7280; color: white; border-radius: 4px; font-size: 13px; display: inline-flex; align-items: center;">
                                <i class="fas fa-lock" style="margin-right: 6px;"></i>Rekening Tidak Aktif
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Transaksi Terbaru -->
              <?php if (!empty($transaksiTerbaru)): ?>
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 20px; font-weight: 600; margin: 0; color: #1a1a1a; border-bottom: 2px solid #059669; padding-bottom: 10px;">
                      <i class="fas fa-history" style="color: #059669; margin-right: 8px;"></i>Transaksi Terbaru
                    </h3>
                  </div>

                  <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                      <thead>
                        <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Rekening</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jenis</th>
                          <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($transaksiTerbaru as $trans): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 12px 15px; font-size: 13px; color: #1a1a1a;">
                              <?= SaldoModel::formatTanggalIndo($trans['tanggal_transaksi']) ?>
                            </td>
                            <td style="padding: 12px 15px;">
                              <div style="font-size: 12px; color: #1a1a1a; font-weight: 500;">
                                <?= htmlspecialchars($trans['nama_simpanan']) ?>
                              </div>
                              <div style="font-size: 11px; color: #6b7280;">
                                <?= htmlspecialchars($trans['no_rekening']) ?>
                              </div>
                            </td>
                            <td style="padding: 12px 15px;">
                              <?php
                              $jenisColor = [
                                  'Setor' => '#10b981',
                                  'Tarik' => '#ef4444',
                                  'Transfer' => '#1e3a8a'
                              ];
                              $color = $jenisColor[$trans['jenis_transaksi']] ?? '#6b7280';
                              ?>
                              <span style="background: <?= $color ?>; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <?= htmlspecialchars($trans['jenis_transaksi']) ?>
                              </span>
                            </td>
                            <td style="padding: 12px 15px; text-align: right; font-weight: 600; font-size: 14px; color: <?= $trans['jenis_transaksi'] === 'Setor' ? '#10b981' : '#ef4444' ?>;">
                              <?= $trans['jenis_transaksi'] === 'Setor' ? '+' : '-' ?> <?= SaldoModel::formatRupiah($trans['jumlah']) ?>
                            </td>
                            <td style="padding: 12px 15px; font-size: 12px; color: #6b7280;">
                              <?= htmlspecialchars($trans['keterangan'] ?? '-') ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
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
    /* Card hover effect */
    div[style*="border: 1px solid #e0e0e0"]:hover {
      border-color: #059669 !important;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15) !important;
    }

    a[href*="saldo&action=detail"]:hover {
      background: #047857 !important;
    }

    a[href*="transaksi&action=create"]:hover {
      background: #1e40af !important;
    }
  </style>
</body>

</html>
