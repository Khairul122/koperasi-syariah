<?php
// Load required models
require_once __DIR__ . '/../../models/DashboardModel.php';

// Determine mode
$isEdit = !empty($data['id_jenis']);
$formTitle = $isEdit ? 'Edit Jenis Simpanan' : 'Tambah Jenis Simpanan';
$formAction = $isEdit ? 'index.php?controller=jenissimpanan&action=update' : 'index.php?controller=jenissimpanan&action=store';

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
              <div style="background: linear-gradient(135deg, #8b6914 0%, #5a4a0f 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?>" style="margin-right: 10px;"></i><?= $formTitle ?>
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                      <ol style="list-style: none; padding: 0; margin: 0; display: flex; gap: 8px; font-size: 13px;">
                        <li><a href="index.php?controller=dashboard&action=admin" style="color: rgba(255, 255, 255, 0.8); text-decoration: none;">Dashboard</a></li>
                        <li style="color: rgba(255, 255, 255, 0.6);">/</li>
                        <li><a href="index.php?controller=jenissimpanan&action=index" style="color: rgba(255, 255, 255, 0.8); text-decoration: none;">Jenis Simpanan</a></li>
                        <li style="color: rgba(255, 255, 255, 0.6);">/</li>
                        <li style="color: white;"><?= $isEdit ? 'Edit' : 'Tambah' ?></li>
                      </ol>
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=jenissimpanan&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Form Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <form method="POST" action="<?= $formAction ?>" id="jenisForm">
                  <?php if ($isEdit): ?>
                    <input type="hidden" name="id_jenis" value="<?= $data['id_jenis'] ?>">
                  <?php endif; ?>

                  <!-- Nama Simpanan -->
                  <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      Nama Simpanan <span style="color: #8b2a2a;">*</span>
                    </label>
                    <input type="text"
                           name="nama_simpanan"
                           id="nama_simpanan"
                           value="<?= htmlspecialchars($data['nama_simpanan']) ?>"
                           placeholder="Contoh: Simpanan Pokok, Simpanan Wajib, Simpanan Sukarela"
                           required
                           style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; transition: border-color 0.3s ease;">
                    <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                      Nama jenis simpanan yang akan ditawarkan kepada anggota
                    </div>
                  </div>

                  <!-- Akad -->
                  <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      Akad <span style="color: #8b2a2a;">*</span>
                    </label>
                    <select name="akad"
                            id="akad"
                            required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; background-color: white; transition: border-color 0.3s ease;">
                      <option value="">-- Pilih Akad --</option>
                      <option value="Wadiah" <?= $data['akad'] === 'Wadiah' ? 'selected' : '' ?>>Wadiah</option>
                      <option value="Mudharabah" <?= $data['akad'] === 'Mudharabah' ? 'selected' : '' ?>>Mudharabah</option>
                      <option value="Murabahah" <?= $data['akad'] === 'Murabahah' ? 'selected' : '' ?>>Murabahah</option>
                    </select>
                    <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                      Jenis akad syariah untuk produk simpanan ini
                    </div>
                  </div>

                  <!-- Minimal Setor -->
                  <div style="margin-bottom: 30px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      Minimal Setor Awal (Rp) <span style="color: #8b2a2a;">*</span>
                    </label>
                    <div style="position: relative;">
                      <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-weight: 600; color: #808080;">Rp</span>
                      <input type="text"
                             name="minimal_setor"
                             id="minimal_setor"
                             value="<?= $data['minimal_setor'] ? number_format($data['minimal_setor'], 0, ',', '.') : '' ?>"
                             placeholder="0"
                             required
                             style="width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; transition: border-color 0.3s ease;">
                    </div>
                    <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                      Jumlah minimal setoran awal untuk membuka rekening jenis ini
                    </div>
                  </div>

                  <!-- Form Actions -->
                  <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                    <a href="index.php?controller=jenissimpanan&action=index"
                       style="padding: 12px 28px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;">
                      <i class="fas fa-times" style="margin-right: 8px;"></i>Batal
                    </a>
                    <button type="submit"
                            id="submitBtn"
                            style="padding: 12px 28px; background: #8b6914; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                      <i class="fas fa-save" style="margin-right: 8px;"></i>Simpan
                    </button>
                  </div>
                </form>
              </div>

              <!-- Information Card -->
              <div style="background: #fff9e6; border: 1px solid #e0d4a8; border-radius: 6px; padding: 20px; margin-top: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <div style="display: flex; align-items: start; gap: 15px;">
                  <div style="width: 40px; height: 40px; background: #8b6914; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-info" style="font-size: 18px; color: white;"></i>
                  </div>
                  <div>
                    <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #8b6914;">Informasi Penting</h4>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #1a1a1a; line-height: 1.6;">
                      <li style="margin-bottom: 5px;">Pastikan nama simpanan unik dan belum digunakan sebelumnya</li>
                      <li style="margin-bottom: 5px;">Akad Wadiah adalah akad simpanan yang paling umum digunakan dalam koperasi syariah</li>
                      <li style="margin-bottom: 5px;">Minimal setor akan divalidasi saat anggota membuka rekening</li>
                      <li>Jenis simpanan yang sudah memiliki rekening aktif tidak dapat dihapus</li>
                    </ul>
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
    // Show flash messages
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($flash_error): ?>
        alert('GAGAL!\n\n<?= addslashes($flash_error) ?>');
      <?php endif; ?>

      <?php if ($flash_success): ?>
        alert('BERHASIL!\n\n<?= addslashes($flash_success) ?>');
      <?php endif; ?>
    });

    // Format minimal_setor input
    const minimalSetorInput = document.getElementById('minimal_setor');

    minimalSetorInput.addEventListener('input', function(e) {
      // Remove all non-digit characters
      let value = e.target.value.replace(/\D/g, '');

      // Format with thousand separator
      if (value) {
        value = parseInt(value).toLocaleString('id-ID');
      }

      e.target.value = value;
    });

    // Remove formatting on form submit
    document.getElementById('jenisForm').addEventListener('submit', function(e) {
      const rawValue = minimalSetorInput.value.replace(/\./g, '').replace(/,/g, '');
      minimalSetorInput.value = rawValue;

      // Validate minimal setor
      if (parseInt(rawValue) < 0) {
        e.preventDefault();
        alert('Minimal setor tidak boleh negatif!');
        return false;
      }
    });

    // Input focus effects
    const inputs = document.querySelectorAll('input[type="text"], select');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.style.borderColor = '#8b6914';
        this.style.boxShadow = '0 0 0 3px rgba(139, 105, 20, 0.1)';
      });

      input.addEventListener('blur', function() {
        this.style.borderColor = '#d0d0d0';
        this.style.boxShadow = 'none';
      });
    });

    // Button hover effects
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.addEventListener('mouseenter', function() {
      this.style.background = '#6b4a0a';
    });

    submitBtn.addEventListener('mouseleave', function() {
      this.style.background = '#8b6914';
    });
  </script>

  <style>
    /* Input focus styles */
    input:focus, select:focus {
      outline: none !important;
    }

    /* Back button hover */
    a[href*="jenissimpanan&action=index"]:hover {
      background: rgba(42, 42, 42, 0.8) !important;
    }
  </style>
</body>

</html>
