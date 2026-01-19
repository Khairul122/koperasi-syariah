<?php
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
                      <i class="fas fa-book" style="margin-right: 10px;"></i>
                      <?= $formMode === 'create' ? 'Buat Rekening Baru' : 'Edit Rekening' ?>
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                   
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=rekening&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Form Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 40px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <?php if ($flash_error): ?>
                  <div style="background: #fee; border-left: 4px solid #c33; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <i class="fas fa-exclamation-circle" style="color: #c33; font-size: 18px;"></i>
                      <span style="color: #300; font-size: 14px;"><?= htmlspecialchars($flash_error) ?></span>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($flash_success): ?>
                  <div style="background: #efe; border-left: 4px solid #3c3; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <i class="fas fa-check-circle" style="color: #3c3; font-size: 18px;"></i>
                      <span style="color: #030; font-size: 14px;"><?= htmlspecialchars($flash_success) ?></span>
                    </div>
                  </div>
                <?php endif; ?>

                <form method="POST" action="<?= $formMode === 'create' ? 'index.php?controller=rekening&action=store' : 'index.php?controller=rekening&action=update&no=' . urlencode($data['no_rekening']) ?>"
                      id="rekeningForm"
                      novalidate>
                  <?php if ($formMode === 'edit'): ?>
                    <input type="hidden" name="old_no_rekening" value="<?= htmlspecialchars($data['no_rekening']) ?>">
                  <?php endif; ?>

                  <!-- No. Rekening -->
                  <div style="margin-bottom: 25px;">
                    <label for="no_rekening" style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      <i class="fas fa-hashtag" style="margin-right: 5px; color: #1e3a2f;"></i>
                      No. Rekening <span style="color: #8b2a2a;">*</span>
                    </label>
                    <input type="text"
                           id="no_rekening"
                           name="no_rekening"
                           value="<?= $formMode === 'create' ? htmlspecialchars($noRekening) : htmlspecialchars($data['no_rekening'] ?? '') ?>"
                           required
                           pattern="^REK-\d{8}-\d{4}$"
                           placeholder="REK-YYYYMMDD-XXXX"
                           style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; font-family: 'Courier New', monospace; <?= $formMode === 'create' ? 'background: #f9f9f9;' : '' ?>">
                    <?php if ($formMode === 'create'): ?>
                      <div style="margin-top: 8px; font-size: 12px; color: #808080;">
                        <i class="fas fa-info-circle" style="margin-right: 4px;"></i>
                        No. Rekening digenerate otomatis (tidak dapat diubah)
                      </div>
                    <?php else: ?>
                      <div style="margin-top: 8px; font-size: 12px; color: #808080;">
                        <i class="fas fa-info-circle" style="margin-right: 4px;"></i>
                        Format: REK-YYYYMMDD-XXXX (contoh: REK-20250118-0001)
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Anggota -->
                  <div style="margin-bottom: 25px;">
                    <label for="id_anggota" style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      <i class="fas fa-user" style="margin-right: 5px; color: #1e3a2f;"></i>
                      Anggota <span style="color: #8b2a2a;">*</span>
                    </label>
                    <select id="id_anggota"
                            name="id_anggota"
                            required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; background: white;">
                      <option value="">-- Pilih Anggota --</option>
                      <?php if (!empty($daftarAnggota)): ?>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                          <option value="<?= $anggota['id_anggota'] ?>"
                                  <?= ($formMode === 'edit' && ($data['id_anggota'] ?? '') == $anggota['id_anggota']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($anggota['no_anggota']) ?> - <?= htmlspecialchars($anggota['nama_lengkap']) ?>
                          </option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                    <?php if (empty($daftarAnggota)): ?>
                      <div style="margin-top: 8px; font-size: 12px; color: #8b2a2a;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i>
                        Tidak ada anggota aktif. Silakan tambah anggota terlebih dahulu.
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Jenis Simpanan -->
                  <div style="margin-bottom: 30px;">
                    <label for="id_jenis" style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      <i class="fas fa-piggy-bank" style="margin-right: 5px; color: #1e3a2f;"></i>
                      Jenis Simpanan <span style="color: #8b2a2a;">*</span>
                    </label>
                    <select id="id_jenis"
                            name="id_jenis"
                            required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; background: white;">
                      <option value="">-- Pilih Jenis Simpanan --</option>
                      <?php if (!empty($daftarJenis)): ?>
                        <?php foreach ($daftarJenis as $jenis): ?>
                          <option value="<?= $jenis['id_jenis'] ?>"
                                  data-akad="<?= htmlspecialchars($jenis['akad']) ?>"
                                  data-minimal="<?= htmlspecialchars($jenis['minimal_setor']) ?>"
                                  <?= ($formMode === 'edit' && ($data['id_jenis'] ?? '') == $jenis['id_jenis']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($jenis['nama_simpanan']) ?>
                          </option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>

                    <!-- Info Jenis Simpanan -->
                    <div id="infoJenis" style="margin-top: 12px; padding: 12px; background: #f9f9f9; border-radius: 4px; border-left: 3px solid #1e3a2f; display: none;">
                      <div style="font-size: 13px; color: #1a1a1a;">
                        <strong><i class="fas fa-file-contract" style="margin-right: 5px;"></i>Akad:</strong>
                        <span id="infoAkad">-</span>
                      </div>
                      <div style="font-size: 13px; color: #1a1a1a; margin-top: 5px;">
                        <strong><i class="fas fa-coins" style="margin-right: 5px;"></i>Minimal Setor:</strong>
                        <span id="infoMinimal">Rp 0</span>
                      </div>
                    </div>
                  </div>

                  <!-- Form Actions -->
                  <div style="border-top: 1px solid #e0e0e0; padding-top: 25px; margin-top: 30px;">
                    <div style="display: flex; gap: 15px; justify-content: flex-end;">
                      <a href="index.php?controller=rekening&action=index"
                         style="padding: 12px 28px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 15px; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-times" style="margin-right: 8px;"></i>Batal
                      </a>
                      <button type="submit"
                              style="padding: 12px 28px; background: #1e3a2f; color: white; border: none; border-radius: 4px; font-size: 15px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>
                        <?= $formMode === 'create' ? 'Simpan Rekening' : 'Perbarui Rekening' ?>
                      </button>
                    </div>
                  </div>
                </form>
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
    // Update info jenis simpanan saat dipilih
    document.getElementById('id_jenis').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const infoJenis = document.getElementById('infoJenis');
      const infoAkad = document.getElementById('infoAkad');
      const infoMinimal = document.getElementById('infoMinimal');

      if (this.value) {
        const akad = selectedOption.getAttribute('data-akad');
        const minimal = selectedOption.getAttribute('data-minimal');

        infoAkad.textContent = akad || '-';
        if (infoMinimal && minimal) {
          // Format minimal_setor sebagai Rupiah
          const minimalNum = parseFloat(minimal);
          infoMinimal.textContent = 'Rp ' + minimalNum.toLocaleString('id-ID');
        }

        infoJenis.style.display = 'block';
      } else {
        infoJenis.style.display = 'none';
      }
    });

    // Show info on page load if editing
    <?php if ($formMode === 'edit' && !empty($data['akad'])): ?>
      document.addEventListener('DOMContentLoaded', function() {
        const infoJenis = document.getElementById('infoJenis');
        infoJenis.style.display = 'block';
        // Set minimal value
        const minimal = '<?= $data['minimal_setor'] ?? 0 ?>';
        if (minimal) {
          document.getElementById('infoMinimal').textContent = 'Rp ' + parseFloat(minimal).toLocaleString('id-ID');
        }
      });
    <?php endif; ?>

    // Form validation
    document.getElementById('rekeningForm').addEventListener('submit', function(e) {
      const noRekening = document.getElementById('no_rekening').value.trim();
      const idAnggota = document.getElementById('id_anggota').value;
      const idJenis = document.getElementById('id_jenis').value;

      // Validasi No. Rekening
      if (!noRekening) {
        e.preventDefault();
        alert('No. Rekening harus diisi');
        document.getElementById('no_rekening').focus();
        return false;
      }

      if (!/^REK-\d{8}-\d{4}$/.test(noRekening)) {
        e.preventDefault();
        alert('Format No. Rekening tidak valid\\n\\nFormat yang benar: REK-YYYYMMDD-XXXX\\nContoh: REK-20250118-0001');
        document.getElementById('no_rekening').focus();
        return false;
      }

      // Validasi Anggota
      if (!idAnggota) {
        e.preventDefault();
        alert('Anggota harus dipilih');
        document.getElementById('id_anggota').focus();
        return false;
      }

      // Validasi Jenis Simpanan
      if (!idJenis) {
        e.preventDefault();
        alert('Jenis Simpanan harus dipilih');
        document.getElementById('id_jenis').focus();
        return false;
      }

      return true;
    });

    <?php if ($formMode === 'create'): ?>
    // Disable no_rekening input in create mode
    document.getElementById('no_rekening').readOnly = true;
    document.getElementById('no_rekening').style.cursor = 'not-allowed';
    <?php endif; ?>
  </script>

  <style>
    /* Button hover effects */
    button[type="submit"]:hover {
      background: #0f1f17 !important;
    }

    a[href*="rekening&action=index"]:hover {
      background: #1a1a1a !important;
    }

    /* Select focus */
    select:focus,
    input:focus {
      outline: none;
      border-color: #1e3a2f !important;
      box-shadow: 0 0 0 3px rgba(30, 58, 47, 0.1);
    }
  </style>
</body>

</html>
