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
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Manajemen Pembiayaan
                    </h2>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Total Pengajuan -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Pengajuan</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #1e3a8a;">
                        <?= $statistics['total_pengajuan'] ?? 0 ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-file-alt" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Pending -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Pending</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #f59e0b;">
                        <?= $statistics['total_pending'] ?? 0 ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-clock" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Disetujui -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Disetujui</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #10b981;">
                        <?= $statistics['per_status']['Disetujui'] ?? 0 ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-check-circle" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Total Pokok Disetujui -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Total Pokok</p>
                      <h3 style="margin: 0; font-size: 20px; font-weight: 600; color: #059669;">
                        Rp <?= number_format($statistics['total_pokok_disetujui'] ?? 0, 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #059669; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-money-bill-wave" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Lunas -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500;">Lunas</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #059669;">
                        <?= $statistics['per_status']['Lunas'] ?? 0 ?>
                      </h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: #059669; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-handshake" style="font-size: 20px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Search & Filter -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <form method="GET" action="index.php">
                  <input type="hidden" name="controller" value="ajukanpembiayaan">
                  <input type="hidden" name="action" value="adminIndex">
                  <div style="display: flex; gap: 15px; align-items: center;">
                    <div style="flex: 1;">
                      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari No. Akad, Nama Anggota, Keperluan..." style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                    </div>
                    <button type="submit" style="padding: 10px 20px; background: #1e3a8a; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; transition: all 0.3s ease;">
                      <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                      <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" style="padding: 10px 20px; background: #6b7280; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; display: inline-block;">
                        <i class="fas fa-times"></i> Reset
                      </a>
                    <?php endif; ?>
                  </div>
                </form>
              </div>

              <!-- Table -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Akad</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Anggota</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keperluan</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tenor</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Status</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($pembiayaan)): ?>
                        <tr>
                          <td colspan="9" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                            <p style="margin: 0; font-size: 14px;">Tidak ada data pembiayaan</p>
                            <?php if (!empty($search)): ?>
                              <p style="margin: 5px 0 0 0; font-size: 12px;">
                                <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" style="color: #1e3a8a; font-weight: 500;">Hapus pencarian</a>
                              </p>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php
                        $no = ($pagination['page'] - 1) * $pagination['perPage'] + 1;
                        foreach ($pembiayaan as $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #1e3a8a; font-size: 13px;">
                                <?= $no++ ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #1e3a8a; font-size: 12px;">
                                <?= htmlspecialchars($row['no_akad']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-size: 13px; color: #1a1a1a;">
                                <strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong>
                              </div>
                              <div style="font-size: 11px; color: #808080; margin-top: 2px;">
                                <?= htmlspecialchars($row['no_anggota']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 13px; color: #1a1a1a;">
                              <?= DashboardModel::formatDateIndo($row['tanggal_pengajuan']) ?>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-size: 13px; color: #1a1a1a; max-width: 180px;">
                                <?= htmlspecialchars(substr($row['keperluan'], 0, 40)) ?><?= strlen($row['keperluan']) > 40 ? '...' : '' ?>
                              </div>
                              <div style="font-size: 10px; color: #808080; margin-top: 2px;">
                                <?= htmlspecialchars($row['jenis_akad']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #1a1a1a; font-size: 14px;">
                                Rp <?= number_format($row['jumlah_pokok'], 0, ',', '.') ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 13px; color: #1a1a1a;">
                              <?= $row['tenor_bulan'] ?> bln
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
                              <span style="background: <?= $color ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 10px; font-weight: 500;">
                                <?= htmlspecialchars($row['status']) ?>
                              </span>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <a href="index.php?controller=ajukanpembiayaan&action=adminView&id=<?= $row['id_pembiayaan'] ?>"
                                 style="padding: 6px 12px; background: #1e3a8a; color: white; border-radius: 3px; text-decoration: none; font-size: 11px; transition: all 0.3s ease; display: inline-block;"
                                 title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
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
                  <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div style="font-size: 13px; color: #6b7280;">
                      Menampilkan <?= ($pagination['page'] - 1) * $pagination['perPage'] + 1 ?> -
                      <?= min($pagination['page'] * $pagination['perPage'], $pagination['total']) ?>
                      dari <?= $pagination['total'] ?> data
                    </div>
                    <div style="display: flex; gap: 5px;">
                      <?php if ($pagination['page'] > 1): ?>
                        <a href="?controller=ajukanpembiayaan&action=adminIndex&page=1<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="?controller=ajukanpembiayaan&action=adminIndex&page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
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
                        <a href="?controller=ajukanpembiayaan&action=adminIndex&page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: <?= $isActive ? '#1e3a8a' : 'white' ?>; border: 1px solid <?= $isActive ? '#1e3a8a' : '#d1d5db' ?>; color: <?= $isActive ? 'white' : '#374151' ?>; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <?= $i ?>
                        </a>
                      <?php endfor; ?>

                      <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                        <a href="?controller=ajukanpembiayaan&action=adminIndex&page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <i class="fas fa-angle-right"></i>
                        </a>
                        <a href="?controller=ajukanpembiayaan&action=adminIndex&page=<?= $pagination['totalPages'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <i class="fas fa-angle-double-right"></i>
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

    a[href*="adminView"]:hover {
      background: #1e40af !important;
    }
  </style>
</body>

</html>
