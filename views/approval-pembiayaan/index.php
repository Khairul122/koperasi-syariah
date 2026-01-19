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
              <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-clipboard-check" style="margin-right: 10px;"></i>Approval Pembiayaan
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                   
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=approvalpembiayaan&action=history"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-history" style="margin-right: 8px;"></i>Riwayat
                    </a>
                  </div>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Total Pending -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Menunggu Approval</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #f59e0b;">
                        <?= $stats['total_pending'] ?? 0 ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-clock" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Disetujui Hari Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Disetujui Hari Ini</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #10b981;">
                        <?= $stats['disetujui_hari_ini'] ?? 0 ?>
                      </h3>
                      <div style="font-size: 11px; color: #808080; margin-top: 4px;">
                        <?= ApprovalPembiayaanModel::formatRupiah($stats['nominal_disetujui'] ?? 0) ?>
                      </div>
                    </div>
                    <div style="width: 50px; height: 50px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-check-circle" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Ditolak Hari Ini -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Ditolak Hari Ini</p>
                      <h3 style="margin: 0; font-size: 32px; font-weight: 600; color: #ef4444;">
                        <?= $stats['ditolak_hari_ini'] ?? 0 ?>
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-times-circle" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>

                <!-- Rata-rata Approval -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="margin: 0 0 8px 0; font-size: 13px; color: #808080; text-transform: uppercase; font-weight: 500;">Rata-rata Waktu</p>
                      <h3 style="margin: 0; font-size: 28px; font-weight: 600; color: #1e3a8a;">
                        <?= $stats['rata_rata_approval'] ?? 0 ?> Hari
                      </h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-hourglass-half" style="font-size: 24px; color: white;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Search & Filter -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <form method="GET" action="index.php">
                  <input type="hidden" name="controller" value="approvalpembiayaan">
                  <input type="hidden" name="action" value="index">
                  <div style="display: flex; gap: 15px; align-items: center;">
                    <div style="flex: 1;">
                      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari No. Akad, Nama Anggota, Keperluan..." style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                    </div>
                    <button type="submit" style="padding: 10px 20px; background: #f59e0b; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; transition: all 0.3s ease;">
                      <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                      <a href="index.php?controller=approvalpembiayaan&action=index" style="padding: 10px 20px; background: #6b7280; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; display: inline-block;">
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
                        <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">
                          <input type="checkbox" id="selectAll" style="transform: scale(1.2); cursor: pointer;">
                        </th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">No. Akad</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Anggota</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Keperluan</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tenor</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Cicilan</th>
                        <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($pendingApprovals)): ?>
                        <tr>
                          <td colspan="10" style="padding: 40px; text-align: center; color: #808080;">
                            <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 15px; color: #10b981;"></i>
                            <p style="margin: 0; font-size: 16px; font-weight: 500;">Tidak ada pengajuan menunggu approval</p>
                            <p style="margin: 5px 0 0 0; font-size: 13px;">Semua pengajuan sudah diproses</p>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php
                        $no = ($pagination['page'] - 1) * $pagination['perPage'] + 1;
                        foreach ($pendingApprovals as $row): ?>
                          <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px 20px; text-align: center;">
                              <input type="checkbox" class="row-checkbox" data-id="<?= $row['id_pembiayaan'] ?>" style="transform: scale(1.2); cursor: pointer;">
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #f59e0b; font-size: 14px;">
                                <?= $no++ ?>
                              </div>
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #dc2626; font-size: 13px;">
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
                              <div style="font-size: 13px; color: #1a1a1a; max-width: 180px;">
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
                              <?= $row['tenor_bulan'] ?> bln
                            </td>
                            <td style="padding: 15px 20px;">
                              <div style="font-weight: 600; color: #dc2626; font-size: 13px;">
                                <?= ApprovalPembiayaanModel::formatRupiah($row['cicilan_per_bulan']) ?>
                              </div>
                              <div style="font-size: 10px; color: #808080; margin-top: 2px;">
                                /bulan
                              </div>
                            </td>
                            <td style="padding: 15px 20px; font-size: 13px; color: #1a1a1a;">
                              <?= ApprovalPembiayaanModel::formatTanggalIndo($row['tanggal_pengajuan']) ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                              <a href="index.php?controller=approvalpembiayaan&action=detail&id=<?= $row['id_pembiayaan'] ?>"
                                 style="padding: 8px 16px; background: #f59e0b; color: white; border-radius: 4px; text-decoration: none; font-size: 12px; transition: all 0.3s ease; display: inline-block;"
                                 title="Review & Approve">
                                <i class="fas fa-clipboard-check"></i> Review
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Batch Actions & Pagination -->
                <?php if (!empty($pendingApprovals)): ?>
                  <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                      <div id="batchActions" style="display: none; gap: 10px;">
                        <button onclick="batchApprove('Disetujui')" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 4px; font-size: 13px; cursor: pointer; transition: all 0.3s ease;">
                          <i class="fas fa-check-circle"></i> Setujui Terpilih
                        </button>
                        <button onclick="batchApprove('Ditolak')" style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 4px; font-size: 13px; cursor: pointer; transition: all 0.3s ease;">
                          <i class="fas fa-times-circle"></i> Tolak Terpilih
                        </button>
                      </div>
                      <span id="selectedCount" style="font-size: 13px; color: #6b7280;"></span>
                    </div>

                    <div style="display: flex; gap: 5px;">
                      <span style="font-size: 13px; color: #6b7280;">
                        Menampilkan <?= ($pagination['page'] - 1) * $pagination['perPage'] + 1 ?> -
                        <?= min($pagination['page'] * $pagination['perPage'], $pagination['total']) ?>
                        dari <?= $pagination['total'] ?> pengajuan
                      </span>
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

    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const batchActions = document.getElementById('batchActions');
    const selectedCount = document.getElementById('selectedCount');

    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
        });
        updateBatchActions();
      });
    }

    rowCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        updateBatchActions();
      });
    });

    function updateBatchActions() {
      const selected = document.querySelectorAll('.row-checkbox:checked');
      const count = selected.length;

      if (count > 0) {
        batchActions.style.display = 'flex';
        selectedCount.textContent = count + ' pengajuan dipilih';
      } else {
        batchActions.style.display = 'none';
        selectedCount.textContent = '';
        selectAllCheckbox.checked = false;
      }
    }

    // Batch approve function
    function batchApprove(status) {
      const selected = document.querySelectorAll('.row-checkbox:checked');
      const ids = Array.from(selected).map(cb => cb.dataset.id);

      if (ids.length === 0) {
        alert('Pilih pengajuan terlebih dahulu!');
        return;
      }

      const message = status === 'Disetujui'
        ? 'Apakah Anda yakin ingin MENYETUJUI ' + ids.length + ' pengajuan terpilih?'
        : 'Apakah Anda yakin ingin MENOLAK ' + ids.length + ' pengajuan terpilih?';

      if (!confirm(message)) {
        return;
      }

      // Create form and submit
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'index.php?controller=approvalpembiayaan&action=batchApprove';

      ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
      });

      const statusInput = document.createElement('input');
      statusInput.type = 'hidden';
      statusInput.name = 'status';
      statusInput.value = status;
      form.appendChild(statusInput);

      document.body.appendChild(form);
      form.submit();
    }
  </script>

  <style>
    /* Table row hover */
    tbody tr:hover {
      background: #fef3c7 !important;
    }

    a[href*="approvalpembiayaan&action=detail"]:hover {
      background: #d97706 !important;
    }
  </style>
</body>

</html>
