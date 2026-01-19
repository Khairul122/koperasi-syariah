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
              <div style="background: linear-gradient(135deg, #1a4a6a 0%, #0d2a3a 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-history" style="margin-right: 10px;"></i>Riwayat Simpanan
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Total Rekening Aktif -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Rekening Aktif</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #1a4a6a;">
                        <?= number_format($statistics['total_rekening_aktif'] ?? 0) ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1a4a6a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-wallet" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Saldo -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Saldo</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #1e3a2f;">
                        Rp <?= number_format($statistics['total_saldo'] ?? 0, 0, ',', '.') ?>
                      </h3>
                      <p style="margin: 4px 0 0 0; font-size: 12px; color: #808080;">
                        Semua rekening aktif
                      </p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1e3a2f; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-coins" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Setor -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Setor</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #1e3a2f;">
                        Rp <?= number_format($statistics['total_setoran'] ?? 0, 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #2d5a47; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-arrow-down" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Tarik -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Tarik</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #8b2a2a;">
                        Rp <?= number_format($statistics['total_penarikan'] ?? 0, 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #8b2a2a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-arrow-up" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Transaksi Hari Ini & Bulan Ini -->
              <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
                <!-- Hari Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1a1a1a; display: flex; align-items: center;">
                    <i class="fas fa-calendar-day" style="margin-right: 8px; color: #1a4a6a;"></i>
                    Transaksi Hari Ini
                  </h4>
                  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                    <div>
                      <span style="font-size: 12px; color: #808080;">Total Transaksi</span>
                      <div style="font-size: 24px; font-weight: 600; color: #1a1a1a; margin-top: 5px;">
                        <?= number_format($statistics['hari_ini']['total_transaksi'] ?? 0) ?>
                      </div>
                    </div>
                    <div>
                      <span style="font-size: 12px; color: #808080;">Setor</span>
                      <div style="font-size: 18px; font-weight: 600; color: #1e3a2f; margin-top: 5px;">
                        + Rp <?= number_format($statistics['hari_ini']['total_setor'] ?? 0, 0, ',', '.') ?>
                      </div>
                    </div>
                    <div>
                      <span style="font-size: 12px; color: #808080;">Tarik</span>
                      <div style="font-size: 18px; font-weight: 600; color: #8b2a2a; margin-top: 5px;">
                        - Rp <?= number_format($statistics['hari_ini']['total_tarik'] ?? 0, 0, ',', '.') ?>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Bulan Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1a1a1a; display: flex; align-items: center;">
                    <i class="fas fa-calendar-alt" style="margin-right: 8px; color: #1a4a6a;"></i>
                    Transaksi Bulan Ini
                  </h4>
                  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                    <div>
                      <span style="font-size: 12px; color: #808080;">Total Transaksi</span>
                      <div style="font-size: 24px; font-weight: 600; color: #1a1a1a; margin-top: 5px;">
                        <?= number_format($statistics['bulan_ini']['total_transaksi'] ?? 0) ?>
                      </div>
                    </div>
                    <div>
                      <span style="font-size: 12px; color: #808080;">Setor</span>
                      <div style="font-size: 18px; font-weight: 600; color: #1e3a2f; margin-top: 5px;">
                        + Rp <?= number_format($statistics['bulan_ini']['total_setor'] ?? 0, 0, ',', '.') ?>
                      </div>
                    </div>
                    <div>
                      <span style="font-size: 12px; color: #808080;">Tarik</span>
                      <div style="font-size: 18px; font-weight: 600; color: #8b2a2a; margin-top: 5px;">
                        - Rp <?= number_format($statistics['bulan_ini']['total_tarik'] ?? 0, 0, ',', '.') ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Top Saldo & Top Transaksi -->
              <?php if (!empty($topSaldo) || !empty($topTransaksi)): ?>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
                  <!-- Top 5 Saldo Tertinggi -->
                  <?php if (!empty($topSaldo)): ?>
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                      <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1a1a1a; display: flex; align-items: center;">
                        <i class="fas fa-trophy" style="margin-right: 8px; color: #8b6914;"></i>
                        Top 5 Saldo Tertinggi
                      </h4>
                      <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($topSaldo as $index => $item): ?>
                          <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f9f9f9; border-radius: 4px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                              <div style="width: 30px; height: 30px; background: #1a4a6a; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                <?= $index + 1 ?>
                              </div>
                              <div>
                                <div style="font-weight: 600; color: #1a1a1a; font-size: 14px;"><?= htmlspecialchars($item['nama_lengkap']) ?></div>
                                <div style="font-size: 11px; color: #808080;"><?= htmlspecialchars($item['nama_simpanan']) ?></div>
                              </div>
                            </div>
                            <div style="font-weight: 700; color: #1e3a2f; font-size: 16px;">
                              Rp <?= number_format($item['saldo_terakhir'], 0, ',', '.') ?>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- Top 5 Transaksi Terbanyak -->
                  <?php if (!empty($topTransaksi)): ?>
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                      <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1a1a1a; display: flex; align-items: center;">
                        <i class="fas fa-exchange-alt" style="margin-right: 8px; color: #1a4a6a;"></i>
                        Top 5 Transaksi Terbanyak
                      </h4>
                      <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($topTransaksi as $index => $item): ?>
                          <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f9f9f9; border-radius: 4px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                              <div style="width: 30px; height: 30px; background: #2a2a2a; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                <?= $index + 1 ?>
                              </div>
                              <div>
                                <div style="font-weight: 600; color: #1a1a1a; font-size: 14px;"><?= htmlspecialchars($item['nama_lengkap']) ?></div>
                                <div style="font-size: 11px; color: #808080;"><?= htmlspecialchars($item['nama_simpanan']) ?></div>
                              </div>
                            </div>
                            <div style="font-weight: 700; color: #1a4a6a; font-size: 16px;">
                              <?= number_format($item['jumlah_transaksi']) ?> transaksi
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <!-- Search & Filter -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <form method="GET" action="index.php" style="display: flex; gap: 15px; align-items: center;">
                  <input type="hidden" name="controller" value="riwayatsimpanan">
                  <input type="hidden" name="action" value="index">

                  <div style="flex: 1;">
                    <input type="text" name="search" placeholder="Cari No. Rekening, Nama Anggota, No. Anggota..."
                           value="<?= htmlspecialchars($search) ?>"
                           style="width: 100%; padding: 10px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px;">
                  </div>

                  <button type="submit" style="padding: 10px 24px; background: #1a4a6a; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-search" style="margin-right: 8px;"></i>Cari
                  </button>

                  <?php if (!empty($search)): ?>
                    <a href="index.php?controller=riwayatsimpanan&action=index"
                       style="padding: 10px 20px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 14px;">
                      <i class="fas fa-times" style="margin-right: 8px;"></i>Reset
                    </a>
                  <?php endif; ?>
                </form>
              </div>

              <!-- Table -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Rekening</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Anggota</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jenis Simpanan</th>
                        <th style="padding: 15px 20px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Total Setor</th>
                        <th style="padding: 15px 20px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Total Tarik</th>
                        <th style="padding: 15px 20px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Saldo</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Transaksi</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($rekening)): ?>
                        <tr>
                          <td colspan="8" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                            <p style="margin: 0; font-size: 14px;">Tidak ada rekening ditemukan</p>
                            <?php if (!empty($search)): ?>
                              <p style="margin: 5px 0 0 0; font-size: 12px;">Coba kata kunci pencarian lain</p>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($rekening as $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #1a4a6a; font-size: 14px;">
                                <?= htmlspecialchars($row['no_rekening']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #1a1a1a; font-size: 14px; margin-bottom: 4px;">
                                <?= htmlspecialchars($row['nama_lengkap']) ?>
                              </div>
                              <div style="font-size: 12px; color: #808080;">
                                <?= htmlspecialchars($row['no_anggota']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 500; color: #1a1a1a; font-size: 14px; margin-bottom: 4px;">
                                <?= htmlspecialchars($row['nama_simpanan']) ?>
                              </div>
                              <div style="font-size: 11px; color: #808080;">
                                <?= htmlspecialchars($row['akad']) ?>
                              </div>
                              <?php if ($row['status_rekening'] === 'Aktif'): ?>
                                <span style="background: #2d5a47; color: white; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 500;">
                                  Aktif
                                </span>
                              <?php else: ?>
                                <span style="background: #2a2a2a; color: #b0b0b0; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 500;">
                                  Tutup
                                </span>
                              <?php endif; ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                              <div style="font-weight: 600; color: #1e3a2f; font-size: 14px;">
                                + Rp <?= number_format($row['total_setoran'], 0, ',', '.') ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                              <div style="font-weight: 600; color: #8b2a2a; font-size: 14px;">
                                - Rp <?= number_format($row['total_penarikan'], 0, ',', '.') ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                              <div style="font-weight: 700; color: #1e3a2f; font-size: 16px;">
                                Rp <?= number_format($row['saldo_terakhir'], 0, ',', '.') ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">
                                <?= number_format($row['jumlah_transaksi']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <a href="index.php?controller=riwayatsimpanan&action=detail&no=<?= urlencode($row['no_rekening']) ?>"
                                 style="padding: 6px 12px; background: #1a4a6a; color: white; border-radius: 3px; text-decoration: none; font-size: 12px; transition: all 0.3s ease;"
                                 title="Lihat Riwayat">
                                <i class="fas fa-history"></i> Riwayat
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['totalPages'] > 1): ?>
                  <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 13px; color: #808080;">
                      Menampilkan <?= min(($pagination['page'] - 1) * $pagination['perPage'] + 1, $pagination['total']) ?>
                      sampai <?= min($pagination['page'] * $pagination['perPage'], $pagination['total']) ?>
                      dari <?= number_format($pagination['total']) ?> rekening
                    </div>

                    <div style="display: flex; gap: 5px;">
                      <?php if ($pagination['page'] > 1): ?>
                        <a href="?controller=riwayatsimpanan&action=index&page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 8px 16px; background: white; border: 1px solid #d0d0d0; color: #1a1a1a; border-radius: 4px; text-decoration: none; font-size: 13px;">
                          <i class="fas fa-chevron-left" style="margin-right: 5px;"></i>Sebelumnya
                        </a>
                      <?php endif; ?>

                      <?php
                      $startPage = max(1, $pagination['page'] - 2);
                      $endPage = min($pagination['totalPages'], $pagination['page'] + 2);

                      for ($i = $startPage; $i <= $endPage; $i++):
                      ?>
                        <a href="?controller=riwayatsimpanan&action=index&page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 8px 16px; background: <?= $i === $pagination['page'] ? '#1a4a6a' : 'white' ?>; border: 1px solid <?= $i === $pagination['page'] ? '#1a4a6a' : '#d0d0d0' ?>; color: <?= $i === $pagination['page'] ? 'white' : '#1a1a1a' ?>; border-radius: 4px; text-decoration: none; font-size: 13px;">
                          <?= $i ?>
                        </a>
                      <?php endfor; ?>

                      <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                        <a href="?controller=riwayatsimpanan&action=index&page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 8px 16px; background: white; border: 1px solid #d0d0d0; color: #1a1a1a; border-radius: 4px; text-decoration: none; font-size: 13px;">
                          Selanjutnya<i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                        </a>
                      <?php endif; ?>
                    </div>
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

    a[href*="riwayatsimpanan&action=detail"]:hover {
      background: #0f2f3a !important;
    }
  </style>
</body>

</html>
