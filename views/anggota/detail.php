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
                      <i class="fas fa-user" style="margin-right: 10px;"></i>Detail Anggota
                    </h2>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=anggota&action=edit&id=<?= $anggota['id_anggota'] ?>"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-edit" style="margin-right: 8px;"></i>Edit
                    </a>
                    <a href="index.php?controller=anggota&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Detail Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06); padding: 40px;">
                <!-- Informasi Utama -->
                <div style="text-align: center; margin-bottom: 40px;">
                  <div style="margin-bottom: 20px;">
                    <i class="fas fa-user-circle" style="font-size: 80px; color: #2d5a47;"></i>
                  </div>
                  <h2 style="font-size: 32px; font-weight: 600; color: #1a1a1a; margin: 0 0 15px 0;">
                    <?= htmlspecialchars($anggota['nama_lengkap']) ?>
                  </h2>
                  <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 15px;">
                    <span style="background: #1a4a6a; color: white; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                      <?= htmlspecialchars($anggota['no_anggota']) ?>
                    </span>
                    <?php if ($anggota['status_aktif'] === 'Aktif'): ?>
                      <span style="background: #2d5a47; color: white; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                        Aktif
                      </span>
                    <?php else: ?>
                      <span style="background: #2a2a2a; color: white; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                        Non-Aktif
                      </span>
                    <?php endif; ?>
                  </div>
                  <p style="margin: 0; font-size: 14px;">
                    <span style="color: #808080;">Terdaftar sejak </span>
                    <strong style="color: #1a1a1a;"><?= DashboardModel::formatDateIndo($anggota['tanggal_daftar']) ?></strong>
                  </p>
                </div>

                <div style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;"></div>

                <!-- Informasi Pribadi -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px;">
                  <div>
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 20px; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                      <i class="fas fa-user" style="margin-right: 8px; color: #1e3a2f;"></i>Informasi Pribadi
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 40%;">NIK</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 2%;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($anggota['nik']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Nama Lengkap</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($anggota['nama_lengkap']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Jenis Kelamin</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;">
                          <?php if ($anggota['jenis_kelamin'] === 'L'): ?>
                            <i class="fas fa-male" style="margin-right: 5px;"></i> Laki-laki
                          <?php else: ?>
                            <i class="fas fa-female" style="margin-right: 5px;"></i> Perempuan
                          <?php endif; ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Tempat Lahir</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($anggota['tempat_lahir']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Tanggal Lahir</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;"><?= DashboardModel::formatDateIndo($anggota['tanggal_lahir']) ?></td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Pekerjaan</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($anggota['pekerjaan'] ?: '-') ?></td>
                      </tr>
                    </table>
                  </div>

                  <div>
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 20px; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                      <i class="fas fa-address-book" style="margin-right: 8px; color: #1e3a2f;"></i>Kontak & Alamat
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 40%;">No. HP</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 2%;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">
                          <i class="fas fa-phone" style="margin-right: 5px;"></i>
                          <?= htmlspecialchars($anggota['no_hp']) ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Alamat</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($anggota['alamat']) ?></td>
                      </tr>
                    </table>

                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin: 30px 0 20px 0; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                      <i class="fas fa-lock" style="margin-right: 8px; color: #1e3a2f;"></i>Akun Login
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 40%;">Username</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px; width: 2%;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">
                          <i class="fas fa-user" style="margin-right: 5px;"></i>
                          <?= htmlspecialchars($anggota['username']) ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">Password</td>
                        <td style="padding: 10px 0; color: #808080; font-size: 14px;">:</td>
                        <td style="padding: 10px 0; font-size: 14px; color: #1a1a1a;">
                          <!-- Info Status Password -->
                          <div style="margin-bottom: 8px;">
                            <?php if ($passwordInfo['is_readable']): ?>
                              <span style="background: #e3f2fd; color: #1565c0; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: 500; border: 1px solid #bbdefb;">
                                <i class="fas fa-lock-open" style="margin-right: 5px;"></i>
                                Password dalam bentuk teks asli
                              </span>
                            <?php else: ?>
                        
                            <?php endif; ?>
                          </div>

                          <!-- Password Display -->
                          <div style="display: flex; align-items: center; gap: 10px;">
                            <?php if ($passwordInfo['is_readable']): ?>
                              <!-- Password dalam bentuk teks asli -->
                              <span style="font-family: 'Arial', sans-serif; font-size: 16px; font-weight: 600; color: #1a1a1a; letter-spacing: 0.5px; background: #f5f5f5; padding: 8px 12px; border-radius: 4px; border: 1px solid #e0e0e0;">
                                <?= htmlspecialchars($passwordInfo['password']) ?>
                              </span>
                            <?php else: ?>
                              <!-- Password terenkripsi (hidden) -->
                              <span style="font-family: 'Courier New', monospace; font-size: 14px; color: #808080; letter-spacing: 2px;">
                                ********
                              </span>
                              <?php if ($passwordInfo['type'] === 'md5_hash' || $passwordInfo['type'] === 'bcrypt_hash'): ?>
                                <button type="button" onclick="showEncryptedInfo()" style="background: none; border: none; color: #1e3a2f; cursor: pointer; font-size: 14px; padding: 5px;" title="Lihat Hash">
                                  <i class="fas fa-info-circle"></i>
                                </button>
                              <?php endif; ?>
                            <?php endif; ?>
                          </div>

                          <!-- Info Box (untuk password terenkripsi) -->
                          <?php if (!$passwordInfo['is_readable'] && isset($passwordInfo['hash_value'])): ?>
                            <div id="encryptedInfo" style="display: none; margin-top: 10px;">
                              <div style="background: #fff8e1; border-left: 4px solid #ffc107; padding: 12px; border-radius: 4px;">
                                <div style="font-size: 12px; color: #333; margin-bottom: 8px;">
                                  <i class="fas fa-exclamation-triangle" style="color: #ffc107; margin-right: 5px;"></i>
                                  <strong>Informasi:</strong>
                                </div>
                                <div style="font-size: 11px; color: #666; margin-bottom: 8px;">
                                  Password disimpan dalam bentuk terenkripsi (hash) untuk keamanan.
                                  Hash <?= strtoupper($passwordInfo['type']) ?> tidak dapat dikembalikan ke teks asli.
                                </div>
                                <div style="margin-top: 8px;">
                                  <span style="font-size: 11px; color: #666;">Hash value:</span>
                                  <code style="display: block; background: white; padding: 8px; border-radius: 3px; color: #1a1a1a; word-break: break-all; font-size: 10px; border: 1px solid #e0e0e0; margin-top: 4px;">
                                    <?= htmlspecialchars($passwordInfo['hash_value']) ?>
                                  </code>
                                </div>
                              </div>
                            </div>
                          <?php endif; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0; padding-top: 30px;">
                  <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="index.php?controller=anggota&action=edit&id=<?= $anggota['id_anggota'] ?>"
                       style="padding: 14px 28px; background: #8b6914; color: white; border-radius: 4px; text-decoration: none; font-size: 16px; font-weight: 500; transition: all 0.3s ease;">
                      <i class="fas fa-edit" style="margin-right: 8px;"></i>Edit Data
                    </a>
                    <button onclick="confirmDelete(<?= $anggota['id_anggota'] ?>, '<?= htmlspecialchars($anggota['nama_lengkap']) ?>')"
                            style="padding: 14px 28px; background: #8b2a2a; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                      <i class="fas fa-trash" style="margin-right: 8px;"></i>Hapus Anggota
                    </button>
                    <a href="index.php?controller=anggota&action=index"
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
    // Show encrypted info
    function showEncryptedInfo() {
      const encryptedInfo = document.getElementById('encryptedInfo');

      if (encryptedInfo.style.display === 'none') {
        encryptedInfo.style.display = 'block';
      } else {
        encryptedInfo.style.display = 'none';
      }
    }

    // Confirm delete
    function confirmDelete(id, nama) {
      if (confirm('Hapus Anggota?\n\nAnda akan menghapus anggota ' + nama + '\n\nData akan dihapus secara permanen.\n\nLanjutkan?')) {
        window.location.href = 'index.php?controller=anggota&action=delete&id=' + id;
      }
    }
  </script>

  <style>
    /* Button hover effects */
    button[onclick]:hover {
      background: #6b1a1a !important;
    }

    a[href*="anggota&action=edit"]:hover {
      background: #6b4f0f !important;
    }

    a[href*="anggota&action=index"]:hover {
      background: #1a1a1a !important;
    }

    /* Toggle password button */
    button[onclick="togglePassword()"]:hover {
      color: #0f1f17 !important;
      transform: scale(1.1);
    }

    button[onclick="togglePassword()"] {
      transition: all 0.3s ease;
    }
  </style>
</body>

</html>
