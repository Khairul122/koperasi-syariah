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
              <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-history" style="margin-right: 10px;"></i>Riwayat Approval
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                     
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=approvalpembiayaan&action=index"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-clipboard-list" style="margin-right: 8px;"></i>Pending
                    </a>
                  </div>
                </div>
              </div>

              <!-- Search & Filter -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <form method="GET" action="index.php">
                  <input type="hidden" name="controller" value="approvalpembiayaan">
                  <input type="hidden" name="action" value="history">
                  <div style="display: flex; gap: 15px; align-items: center;">
                    <div style="flex: 1;">
                      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari No. Akad, Nama Anggota, Keperluan..." style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                    </div>
                    <button type="submit" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; transition: all 0.3s ease;">
                      <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                      <a href="index.php?controller=approvalpembiayaan&action=history" style="padding: 10px 20px; background: #9ca3af; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; display: inline-block;">
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
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keperluan</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Diproses Oleh</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Status</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Catatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($historyList)): ?>
                        <tr>
                          <td colspan="9" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; color: #b0b0b0;"></i>
                            <p style="margin: 0; font-size: 14px;">Belum ada riwayat approval</p>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php
                        $no = ($pagination['page'] - 1) * $pagination['perPage'] + 1;
                        foreach ($historyList as $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #6b7280; font-size: 14px;">
                                <?= $no++ ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #dc2626; font-size: 12px;">
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
                            <td style="padding: 15px 20px;">
                              <div style="font-size: 13px; color: #1a1a1a; max-width: 200px;">
                                <?= htmlspecialchars(substr($row['keperluan'], 0, 40)) ?><?= strlen($row['keperluan']) > 40 ? '...' : '' ?>
                              </div>
                              <div style="font-size: 10px; color: #808080; margin-top: 2px;">
                                <?= htmlspecialchars($row['jenis_akad']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #1a1a1a; font-size: 14px;">
                                <?= ApprovalPembiayaanModel::formatRupiah($row['jumlah_pokok']) ?>
                              </div>
                              <div style="font-size: 10px; color: #808080; margin-top: 2px;">
                                Total: <?= ApprovalPembiayaanModel::formatRupiah($row['total_bayar']) ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 13px; color: #1a1a1a;">
                              <?= ApprovalPembiayaanModel::formatTanggalIndo($row['tanggal_pengajuan']) ?>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-size: 13px; color: #1a1a1a;">
                                <strong><?= htmlspecialchars($row['nama_petugas_acc'] ?? '-') ?></strong>
                              </div>
                              <div style="font-size: 10px; color: #808080; margin-top: 2px;">
                                <?= htmlspecialchars($row['username_petugas_acc'] ?? '-') ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <?php
                              $statusColor = [
                                  'Disetujui' => '#10b981',
                                  'Ditolak' => '#ef4444',
                                  'Lunas' => '#059669'
                              ];
                              $color = $statusColor[$row['status']] ?? '#6b7280';
                              ?>
                              <span style="background: <?= $color ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">
                                <?= htmlspecialchars($row['status']) ?>
                              </span>
                            </td>
                            <td style="padding: 15px 20px; font-size: 12px; color: #6b7280; max-width: 200px;">
                              <?= htmlspecialchars(substr($row['catatan_bendahara'] ?? '', 0, 100)) ?>
                              <?= strlen($row['catatan_bendahara'] ?? '') > 100 ? '...' : '' ?>
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
                      dari <?= $pagination['total'] ?> riwayat
                    </div>
                    <div style="display: flex; gap: 5px;">
                      <?php if ($pagination['page'] > 1): ?>
                        <a href="?controller=approvalpembiayaan&action=history&page=1<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="?controller=approvalpembiayaan&action=history&page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
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
                        <a href="?controller=approvalpembiayaan&action=history&page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: <?= $isActive ? '#6b7280' : 'white' ?>; border: 1px solid <?= $isActive ? '#6b7280' : '#d1d5db' ?>; color: <?= $isActive ? 'white' : '#374151' ?>; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <?= $i ?>
                        </a>
                      <?php endfor; ?>

                      <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                        <a href="?controller=approvalpembiayaan&action=history&page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                           style="padding: 6px 12px; background: white; border: 1px solid #d1d5db; color: #374151; text-decoration: none; border-radius: 4px; font-size: 12px;">
                          <i class="fas fa-angle-right"></i>
                        </a>
                        <a href="?controller=approvalpembiayaan&action=history&page=<?= $pagination['totalPages'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
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
  </style>
</body>

</html>
