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

              <!-- Page Header Formal -->
              <div style="background: linear-gradient(135deg, #1e3a2f 0%, #0f1f17 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">Kelola Data Anggota</h2>
                    <nav style="margin: 0;">
                     
                    </nav>
                  </div>
                  <a href="index.php?controller=anggota&action=create"
                     style="background: rgba(255,255,255,0.15); color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 500; border: 1px solid rgba(255,255,255,0.3); display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                    <i class="fas fa-plus"></i> Tambah Anggota
                  </a>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                  <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                      <p style="font-size: 13px; color: #808080; margin: 0 0 8px 0; font-weight: 500;">TOTAL ANGGOTA</p>
                      <h3 style="font-size: 32px; font-weight: 700; margin: 0; color: #1a1a1a;"><?= number_format($statistics['total'] ?? 0) ?></h3>
                    </div>
                    <i class="fas fa-users" style="font-size: 36px; color: #1e3a2f; opacity: 0.3;"></i>
                  </div>
                </div>

                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                  <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                      <p style="font-size: 13px; color: #808080; margin: 0 0 8px 0; font-weight: 500;">ANGGOTA AKTIF</p>
                      <h3 style="font-size: 32px; font-weight: 700; margin: 0; color: #2d5a47;"><?= number_format($statistics['aktif'] ?? 0) ?></h3>
                    </div>
                    <i class="fas fa-user-check" style="font-size: 36px; color: #2d5a47; opacity: 0.3;"></i>
                  </div>
                </div>

                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                  <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                      <p style="font-size: 13px; color: #808080; margin: 0 0 8px 0; font-weight: 500;">NON-AKTIF</p>
                      <h3 style="font-size: 32px; font-weight: 700; margin: 0; color: #8b6914;"><?= number_format($statistics['non_aktif'] ?? 0) ?></h3>
                    </div>
                    <i class="fas fa-user-times" style="font-size: 36px; color: #8b6914; opacity: 0.3;"></i>
                  </div>
                </div>

                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                  <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                      <p style="font-size: 13px; color: #808080; margin: 0 0 8px 0; font-weight: 500;">DAFTAR HARI INI</p>
                      <h3 style="font-size: 32px; font-weight: 700; margin: 0; color: #1a4a6a;"><?= number_format($statistics['hari_ini'] ?? 0) ?></h3>
                    </div>
                    <i class="fas fa-user-plus" style="font-size: 36px; color: #1a4a6a; opacity: 0.3;"></i>
                  </div>
                </div>
              </div>

              <!-- Search & Filter Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                <form method="GET" action="index.php">
                  <input type="hidden" name="controller" value="anggota">
                  <input type="hidden" name="action" value="index">
                  <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; align-items: end;">
                    <div>
                      <label style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">Pencarian</label>
                      <div style="display: flex; gap: 10px;">
                        <input type="text" name="search"
                               style="flex: 1; padding: 8px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px;"
                               placeholder="No. Anggota, NIK, Nama, No. HP" value="<?= htmlspecialchars($search) ?>">
                        <button type="submit"
                                style="background: #1e3a2f; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                    <div>
                      <label style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">Tampilkan</label>
                      <select name="per_page"
                              style="width: 100%; padding: 8px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px;"
                              onchange="this.form.submit()">
                        <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 per halaman</option>
                        <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25 per halaman</option>
                        <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 per halaman</option>
                        <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 per halaman</option>
                      </select>
                    </div>
                    <div>
                      <?php if (!empty($search)): ?>
                        <a href="index.php?controller=anggota&action=index"
                           style="display: inline-block; padding: 8px 16px; background: #2a2a2a; color: white; border: 1px solid #808080; border-radius: 4px; text-decoration: none; font-size: 14px;">
                          <i class="fas fa-times"></i> Reset
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Data Table Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                <div style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background: #1a1a1a;">
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">No. Anggota</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">NIK</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">Nama Lengkap</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">Jenis Kelamin</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">No. HP</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">Tgl Daftar</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">Status</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1e3a2f;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($anggota)): ?>
                        <?php $no = ($pagination['page'] - 1) * $pagination['perPage'] + 1; ?>
                        <?php foreach ($anggota as $row): ?>
                          <tr style="border-bottom: 1px solid #e0e0e0; transition: background 0.2s;">
                            <td style="padding: 12px 16px;">
                              <span style="background: #1e3a2f; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;"><?= htmlspecialchars($row['no_anggota']) ?></span>
                            </td>
                            <td style="padding: 12px 16px;"><?= htmlspecialchars($row['nik']) ?></td>
                            <td style="padding: 12px 16px;">
                              <div style="font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                              <small style="color: #808080; font-size: 12px;">@<?= htmlspecialchars($row['username']) ?></small>
                            </td>
                            <td style="padding: 12px 16px;"><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td style="padding: 12px 16px;"><?= htmlspecialchars($row['no_hp']) ?></td>
                            <td style="padding: 12px 16px;"><?= DashboardModel::formatDateIndo($row['tanggal_daftar']) ?></td>
                            <td style="padding: 12px 16px;">
                              <?php if ($row['status_aktif'] === 'Aktif'): ?>
                                <span style="background: #2d5a47; color: white; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">Aktif</span>
                              <?php else: ?>
                                <span style="background: #2a2a2a; color: #b0b0b0; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500;">Non-Aktif</span>
                              <?php endif; ?>
                            </td>
                            <td style="padding: 12px 16px;">
                              <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="index.php?controller=anggota&action=view&id=<?= $row['id_anggota'] ?>"
                                   style="padding: 6px 10px; background: #1a4a6a; color: white; border-radius: 3px; text-decoration: none; font-size: 12px;"
                                   title="Lihat Detail">
                                  <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?controller=anggota&action=edit&id=<?= $row['id_anggota'] ?>"
                                   style="padding: 6px 10px; background: #8b6914; color: white; border-radius: 3px; text-decoration: none; font-size: 12px;"
                                   title="Edit">
                                  <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $row['id_anggota'] ?>, '<?= htmlspecialchars($row['nama_lengkap']) ?>')"
                                        style="padding: 6px 10px; background: #8b2a2a; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;"
                                        title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                          <?php $no++; ?>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-users" style="font-size: 48px; color: #b0b0b0; margin-bottom: 20px;"></i>
                            <p style="color: #808080; font-size: 14px; margin: 0;">
                              <?php if (!empty($search)): ?>
                                Tidak ditemukan data dengan kata kunci "<?= htmlspecialchars($search) ?>"
                              <?php else: ?>
                                Belum ada data anggota
                              <?php endif; ?>
                            </p>
                            <?php if (empty($search)): ?>
                              <div style="margin-top: 20px;">
                                <a href="index.php?controller=anggota&action=create"
                                   style="display: inline-block; padding: 10px 20px; background: #1e3a2f; color: white; border-radius: 4px; text-decoration: none;">
                                  <i class="fas fa-plus"></i> Tambah Anggota
                                </a>
                              </div>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['totalPages'] > 1): ?>
                  <div style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="font-size: 13px; color: #808080;">
                      Menampilkan <strong><?= count($anggota) ?></strong> dari <strong><?= $pagination['total'] ?></strong> data
                      (Halaman <?= $pagination['page'] ?> dari <?= $pagination['totalPages'] ?>)
                    </div>

                    <nav>
                      <ul style="display: flex; gap: 5px; list-style: none; margin: 0; padding: 0;">
                        <?php if ($pagination['page'] > 1): ?>
                          <li>
                            <a href="?controller=anggota&action=index&page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>"
                               style="padding: 6px 12px; background: white; border: 1px solid #b0b0b0; border-radius: 3px; text-decoration: none; color: #1a1a1a; font-size: 13px;">
                              <i class="fas fa-chevron-left"></i>
                            </a>
                          </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $pagination['page'] - 2);
                        $end = min($pagination['totalPages'], $pagination['page'] + 2);
                        ?>

                        <?php for ($i = $start; $i <= $end; $i++): ?>
                          <li>
                            <a href="?controller=anggota&action=index&page=<?= $i ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>"
                               style="padding: 6px 12px; <?= $i == $pagination['page'] ? 'background: #1e3a2f; color: white; border-color: #1e3a2f;' : 'background: white; border: 1px solid #b0b0b0; color: #1a1a1a;' ?> border-radius: 3px; text-decoration: none; font-size: 13px;">
                              <?= $i ?>
                            </a>
                          </li>
                        <?php endfor; ?>

                        <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                          <li>
                            <a href="?controller=anggota&action=index&page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>"
                               style="padding: 6px 12px; background: white; border: 1px solid #b0b0b0; border-radius: 3px; text-decoration: none; color: #1a1a1a; font-size: 13px;">
                              <i class="fas fa-chevron-right"></i>
                            </a>
                          </li>
                        <?php endif; ?>
                      </ul>
                    </nav>
                  </div>
                <?php endif; ?>
              </div>

            </div>
          </div>
        </div>

        <!-- Footer -->
        <footer style="background: white; border-top: 1px solid #e0e0e0; padding: 20px; text-align: center; margin-top: 30px;">
          <p style="font-size: 13px; color: #808080; margin: 0;">
            Copyright © <?= date('Y') ?> Koperasi Syariah. All rights reserved.
          </p>
        </footer>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    // Show notifications on page load (Browser Alert)
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($flash_error): ?>
        alert('GAGAL!\n\n<?= addslashes($flash_error) ?>');
      <?php endif; ?>

      <?php if ($flash_success): ?>
        alert('BERHASIL!\n\n<?= addslashes($flash_success) ?>');
      <?php endif; ?>
    });

    // Confirm delete
    function confirmDelete(id, nama) {
      if (confirm('Hapus Anggota?\n\nAnda akan menghapus anggota ' + nama + '\n\nData akan dihapus secara permanen.\n\nLanjutkan?')) {
        window.location.href = 'index.php?controller=anggota&action=delete&id=' + id;
      }
    }

    // Toggle status
    function toggleStatus(id, button) {
      const icon = button.querySelector('i');
      const isActivating = icon.classList.contains('fa-ban');

      const message = isActivating
        ? 'Non-aktifkan Anggota?\n\nAnggota tidak akan bisa login setelah dinon-aktifkan.\n\nLanjutkan?'
        : 'Aktifkan Anggota?\n\nAnggota akan bisa login kembali.\n\nLanjutkan?';

      if (confirm(message)) {
        window.location.href = 'index.php?controller=anggota&action=toggleStatus&id=' + id;
      }
    }
  </script>
</body>
</html>
