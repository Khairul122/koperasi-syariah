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
                      <i class="fas fa-hand-holding-usd" style="margin-right: 10px;"></i>Transaksi Setoran
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=setor&action=create"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-plus" style="margin-right: 8px;"></i>Setor Baru
                    </a>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Total Transaksi Hari Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Setoran Hari Ini</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #1a1a1a;">
                        <?= number_format($statistics['hari_ini']['total_transaksi'] ?? 0) ?>
                      </h3>
                      <p style="margin: 4px 0 0 0; font-size: 12px; color: #808080;">
                        Rp <?= number_format($statistics['hari_ini']['total_setor'] ?? 0, 0, ',', '.') ?>
                      </p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1e3a2f; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-calendar-day" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Setoran Bulan Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Setoran Bulan Ini</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #1a1a1a;">
                        <?= number_format($statistics['bulan_ini']['total_transaksi'] ?? 0) ?>
                      </h3>
                      <p style="margin: 4px 0 0 0; font-size: 12px; color: #808080;">
                        Rp <?= number_format($statistics['bulan_ini']['total_setor'] ?? 0, 0, ',', '.') ?>
                      </p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #2a2a2a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-calendar-alt" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Saldo Rekening -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Saldo Rekening</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #1a1a1a;">
                        <?= number_format($statistics['saldo']['total_saldo'] ?? 0, 0, ',', '.') ?>
                      </h3>
                      <p style="margin: 4px 0 0 0; font-size: 12px; color: #808080;">
                        <?= number_format($statistics['saldo']['total_rekening'] ?? 0) ?> rekening aktif
                      </p>
                    </div>
                    <div style="width: 50px; height: 50px; background: #8b6914; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-wallet" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Search & Filter -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <form method="GET" action="index.php" style="display: flex; gap: 15px; align-items: center;">
                  <input type="hidden" name="controller" value="setor">
                  <input type="hidden" name="action" value="index">

                  <div style="flex: 1;">
                    <input type="text" name="search" placeholder="Cari No. Transaksi, No. Rekening, Nama Anggota..."
                           value="<?= htmlspecialchars($search) ?>"
                           style="width: 100%; padding: 10px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px;">
                  </div>

                  <button type="submit" style="padding: 10px 24px; background: #1e3a2f; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-search" style="margin-right: 8px;"></i>Cari
                  </button>

                  <?php if (!empty($search)): ?>
                    <a href="index.php?controller=setor&action=index"
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
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Transaksi</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Anggota</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Rekening</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jenis</th>
                        <th style="padding: 15px 20px; text-align: right; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($transaksi)): ?>
                        <tr>
                          <td colspan="7" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                            <p style="margin: 0; font-size: 14px;">Tidak ada transaksi</p>
                            <?php if (!empty($search)): ?>
                              <p style="margin: 5px 0 0 0; font-size: 12px;">Coba kata kunci pencarian lain</p>
                            <?php else: ?>
                              <p style="margin: 5px 0 0 0; font-size: 12px;">
                                <a href="index.php?controller=setor&action=create" style="color: #1e3a2f; font-weight: 500;">Buat transaksi setoran baru</a>
                              </p>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($transaksi as $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #1e3a2f; font-size: 14px;">
                                <?= htmlspecialchars($row['no_transaksi']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 14px; color: #1a1a1a;">
                              <?= DashboardModel::formatDateIndo($row['tanggal_transaksi']) ?>
                              <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                <?= date('H:i', strtotime($row['tanggal_transaksi'])) ?>
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
                              <div style="font-family: 'Courier New', monospace; font-weight: 500; color: #1a1a1a; font-size: 13px;">
                                <?= htmlspecialchars($row['no_rekening']) ?>
                              </div>
                              <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                <?= htmlspecialchars($row['nama_simpanan']) ?>
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
                            <td style="padding: 15px 20px; text-align: right;">
                              <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">
                                Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                              </div>
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

                <!-- Pagination -->
                <?php if ($pagination['totalPages'] > 1): ?>
                  <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 13px; color: #808080;">
                      Menampilkan <?= min(($pagination['page'] - 1) * $pagination['perPage'] + 1, $pagination['total']) ?>
                      sampai <?= min($pagination['page'] * $pagination['perPage'], $pagination['total']) ?>
                      dari <?= number_format($pagination['total']) ?> data
                    </div>

                    <div style="display: flex; gap: 5px;">
                      <?php if ($pagination['page'] > 1): ?>
                        <a href="?controller=setor&action=index&page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 8px 16px; background: white; border: 1px solid #d0d0d0; color: #1a1a1a; border-radius: 4px; text-decoration: none; font-size: 13px;">
                          <i class="fas fa-chevron-left" style="margin-right: 5px;"></i>Sebelumnya
                        </a>
                      <?php endif; ?>

                      <?php
                      $startPage = max(1, $pagination['page'] - 2);
                      $endPage = min($pagination['totalPages'], $pagination['page'] + 2);

                      for ($i = $startPage; $i <= $endPage; $i++):
                      ?>
                        <a href="?controller=setor&action=index&page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 8px 16px; background: <?= $i === $pagination['page'] ? '#1e3a2f' : 'white' ?>; border: 1px solid <?= $i === $pagination['page'] ? '#1e3a2f' : '#d0d0d0' ?>; color: <?= $i === $pagination['page'] ? 'white' : '#1a1a1a' ?>; border-radius: 4px; text-decoration: none; font-size: 13px;">
                          <?= $i ?>
                        </a>
                      <?php endfor; ?>

                      <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                        <a href="?controller=setor&action=index&page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
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

    a[href*="setor&action=create"]:hover {
      background: rgba(45, 90, 71, 0.3) !important;
    }

    a[href*="setor&action=view"]:hover {
      background: #0f2f3a !important;
    }
  </style>
</body>

</html>
