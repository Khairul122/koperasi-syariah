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
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Detail Rekening
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                     
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=saldo&action=index"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                    <?php if ($rekening['status'] === 'Aktif'): ?>
                      <a href="index.php?controller=transaksi&action=create&id=<?= $rekening['id_simpanan'] ?>"
                         style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                        <i class="fas fa-exchange-alt" style="margin-right: 8px;"></i>Transaksi Baru
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <!-- Status Badge -->
              <div style="margin-bottom: 20px;">
                <span style="background: <?= $rekening['status'] === 'Aktif' ? '#10b981' : '#ef4444' ?>; color: white; padding: 8px 16px; border-radius: 4px; font-size: 14px; font-weight: 500; display: inline-block;">
                  Status: <?= htmlspecialchars($rekening['status']) ?>
                </span>
              </div>

              <!-- Informasi Rekening -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #059669; padding-bottom: 10px;">
                  <i class="fas fa-credit-card" style="color: #059669; margin-right: 8px;"></i>Informasi Rekening
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. Rekening</label>
                    <div style="font-family: 'Courier New', monospace; font-size: 16px; font-weight: 600; color: #1e3a8a;">
                      <?= htmlspecialchars($rekening['no_rekening']) ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jenis Simpanan</label>
                    <div style="font-size: 16px; font-weight: 600; color: #1a1a1a;">
                      <?= htmlspecialchars($rekening['nama_simpanan']) ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Akad</label>
                    <div style="font-size: 14px; color: #1a1a1a;">
                      <span style="background: #1e3a8a; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: 500;">
                        <?= htmlspecialchars($rekening['akad']) ?>
                      </span>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Status</label>
                    <span style="background: <?= $rekening['status'] === 'Aktif' ? '#10b981' : '#ef4444' ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: 500;">
                      <?= htmlspecialchars($rekening['status']) ?>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Ringkasan Saldo -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #059669; padding-bottom: 10px;">
                  <i class="fas fa-chart-pie" style="color: #059669; margin-right: 8px;"></i>Ringkasan Saldo
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                  <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Saldo Terakhir</label>
                    <div style="font-size: 26px; font-weight: 600;">
                      <?= SaldoModel::formatRupiah($rekening['saldo_terakhir']) ?>
                    </div>
                  </div>
                  <div style="background: white; border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Setoran</label>
                    <div style="font-size: 22px; font-weight: 600; color: #10b981;">
                      <?= SaldoModel::formatRupiah($rekening['total_setoran']) ?>
                    </div>
                  </div>
                  <div style="background: white; border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Penarikan</label>
                    <div style="font-size: 22px; font-weight: 600; color: #ef4444;">
                      <?= SaldoModel::formatRupiah($rekening['total_penarikan']) ?>
                    </div>
                  </div>
                  <div style="background: white; border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Transaksi</label>
                    <div style="font-size: 24px; font-weight: 600; color: #1e3a8a;">
                      <?= $pagination['total'] ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Ringkasan Transaksi per Jenis -->
              <?php if (!empty($ringkasan)): ?>
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #059669; padding-bottom: 10px;">
                    <i class="fas fa-chart-bar" style="color: #059669; margin-right: 8px;"></i>Ringkasan Transaksi
                  </h3>

                  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px;">
                    <?php foreach ($ringkasan as $jenis => $data): ?>
                      <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid <?= $jenis === 'Setor' ? '#10b981' : ($jenis === 'Tarik' ? '#ef4444' : '#1e3a8a') ?>;">
                        <div style="font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                          <?= $jenis ?>
                        </div>
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">
                          <?= $data['total'] ?> transaksi
                        </div>
                        <div style="font-size: 16px; font-weight: 600; color: <?= $jenis === 'Setor' ? '#10b981' : ($jenis === 'Tarik' ? '#ef4444' : '#1e3a8a') ?>;">
                          <?= SaldoModel::formatRupiah($data['jumlah']) ?>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Riwayat Transaksi -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #059669; padding-bottom: 10px;">
                  <i class="fas fa-history" style="color: #059669; margin-right: 8px;"></i>Riwayat Transaksi
                </h3>

                <?php if (empty($transaksi)): ?>
                  <div style="text-align: center; padding: 40px; color: #808080;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                    <p style="margin: 0; font-size: 14px;">Belum ada transaksi</p>
                  </div>
                <?php else: ?>
                  <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                      <thead>
                        <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                          <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jenis Transaksi</th>
                          <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                          <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $no = ($pagination['page'] - 1) * $pagination['perPage'] + 1;
                        foreach ($transaksi as $t): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 12px 15px; text-align: center;">
                              <div style="font-weight: 600; color: #059669; font-size: 13px;">
                                <?= $no++ ?>
                              </div>
                            </td>
                            <td style="padding: 12px 15px; font-size: 13px; color: #1a1a1a;">
                              <?= SaldoModel::formatTanggalIndo($t['tanggal_transaksi']) ?>
                            </td>
                            <td style="padding: 12px 15px;">
                              <?php
                              $jenisColor = [
                                  'Setor' => '#10b981',
                                  'Tarik' => '#ef4444',
                                  'Transfer' => '#1e3a8a'
                              ];
                              $jenisIcon = [
                                  'Setor' => 'fa-arrow-down',
                                  'Tarik' => 'fa-arrow-up',
                                  'Transfer' => 'fa-exchange-alt'
                              ];
                              $color = $jenisColor[$t['jenis_transaksi']] ?? '#6b7280';
                              $icon = $jenisIcon[$t['jenis_transaksi']] ?? 'fa-circle';
                              ?>
                              <span style="background: <?= $color ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <i class="fas <?= $icon ?>" style="margin-right: 4px;"></i><?= htmlspecialchars($t['jenis_transaksi']) ?>
                              </span>
                            </td>
                            <td style="padding: 12px 15px; text-align: right;">
                              <div style="font-weight: 600; font-size: 15px; color: <?= $t['jenis_transaksi'] === 'Setor' ? '#10b981' : '#ef4444' ?>;">
                                <?= $t['jenis_transaksi'] === 'Setor' ? '+' : '-' ?> <?= SaldoModel::formatRupiah($t['jumlah']) ?>
                              </div>
                            </td>
                            <td style="padding: 12px 15px; font-size: 13px; color: #6b7280; max-width: 300px;">
                              <?= htmlspecialchars($t['keterangan'] ?? '-') ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination -->
                  <?php if ($pagination['totalPages'] > 1): ?>
                    <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                      <div style="font-size: 13px; color: #6b7280;">
                        Menampilkan <?= ($pagination['page'] - 1) * $pagination['perPage'] + 1 ?> -
                        <?= min($pagination['page'] * $pagination['perPage'], $pagination['total']) ?>
                        dari <?= $pagination['total'] ?> transaksi
                      </div>
                      <div style="display: flex; gap: 5px;">
                        <?php if ($pagination['page'] > 1): ?>
                          <a href="?controller=saldo&action=detail&id=<?= $rekening['id_simpanan'] ?>&page=1"
                             style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-angle-double-left"></i>
                          </a>
                          <a href="?controller=saldo&action=detail&id=<?= $rekening['id_simpanan'] ?>&page=<?= $pagination['page'] - 1 ?>"
                             style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-angle-left"></i>
                          </a>
                        <?php endif; ?>

                        <?php
                        $startPage = max(1, $pagination['page'] - 2);
                        $endPage = min($pagination['totalPages'], $pagination['page'] + 2);

                        for ($i = $startPage; $i <= $endPage; $i++):
                          $isActive = $i == $pagination['page'];
                        ?>
                          <a href="?controller=saldo&action=detail&id=<?= $rekening['id_simpanan'] ?>&page=<?= $i ?>"
                             style="padding: 6px 12px; background: <?= $isActive ? '#059669' : 'white' ?>; border: 1px solid <?= $isActive ? '#059669' : '#d1d5db' ?>; color: <?= $isActive ? 'white' : '#374151' ?>; text-decoration: none; border-radius: 4px; font-size: 12px;">
                            <?= $i ?>
                          </a>
                        <?php endfor; ?>

                        <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                          <a href="?controller=saldo&action=detail&id=<?= $rekening['id_simpanan'] ?>&page=<?= $pagination['page'] + 1 ?>"
                             style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-angle-right"></i>
                          </a>
                          <a href="?controller=saldo&action=detail&id=<?= $rekening['id_simpanan'] ?>&page=<?= $pagination['totalPages'] ?>"
                             style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-angle-double-right"></i>
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endif; ?>
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

    a[href*="saldo&action=index"]:hover {
      background: rgba(5, 150, 105, 0.3) !important;
    }

    a[href*="transaksi&action=create"]:hover {
      background: rgba(30, 64, 175, 0.3) !important;
    }
  </style>
</body>

</html>
