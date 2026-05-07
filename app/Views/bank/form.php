<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: <?= BASE_URL ?>/login');
    exit();
}

// Check if user is admin
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: <?= BASE_URL ?>/dashboard/client');
    exit();
}

$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

$bankName = $oldInput['bank_name'] ?? ($bank['bank_name'] ?? '');
$accountNumber = $oldInput['account_number'] ?? ($bank['account_number'] ?? '');
$accountHolder = $oldInput['account_holder'] ?? ($bank['account_holder'] ?? '');
$isActive = $oldInput['is_active'] ?? ($bank['is_active'] ?? 1);
?>

<?php include(BASE_PATH . 'app/Template/header.php'); ?>
<?php include(BASE_PATH . 'app/Template/ui_components.php'); ?>

<style>
  /* Scoped overrides for form page only */
  .logo-preview-wrapper { display: inline-block; position: relative; margin-top: 1rem; }
  .logo-preview-wrapper img { border: 3px solid #000; box-shadow: 3px 3px 0 #000; max-width: 200px; max-height: 200px; display: block; }
  .btn-remove-logo {
    position: absolute; top: -10px; right: -10px;
    width: 30px; height: 30px;
    background: #FF4081; color: #fff;
    border: 3px solid #000; box-shadow: 2px 2px 0 #000;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 0.8rem;
  }
  .current-logo-container img { border: 3px solid #000; box-shadow: 3px 3px 0 #000; max-width: 200px; display: block; }
</style>


<body>
  <div class="container-scroller">
    <?php include(BASE_PATH . 'app/Template/navbar.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include(BASE_PATH . 'app/Template/setting_panel.php'); ?>
      <?php include(BASE_PATH . 'app/Template/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Page Header -->
              <div class="neo-page-header mb-4">
                <div class="neo-page-title">
                  <h3><i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus-circle'; ?> me-2"></i><?php echo $isEdit ? 'Edit Bank' : 'Tambah Bank Baru'; ?></h3>
                  <div class="neo-breadcrumb">Panel &rsaquo; Bank &rsaquo; <?= $isEdit ? 'Edit' : 'Tambah' ?></div>
                </div>
                <a href="<?= BASE_URL ?>/bank" class="neo-btn neo-btn-white">
                  <i class="fas fa-arrow-left"></i> Kembali
                </a>
              </div>

              <div class="container-fluid px-0">
                <!-- Error Messages (Hidden, will be shown with SweetAlert2) -->
                <?php
                $errors = $_SESSION['errors'] ?? null;
                unset($_SESSION['errors']);
                ?>

                <!-- Form -->
                <form method="POST" action="<?php echo $isEdit ? 'index.php?controller=bank&action=update' : 'index.php?controller=bank&action=store'; ?>"
                      enctype="multipart/form-data" id="bankForm">

                  <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($bank['id']); ?>">
                  <?php endif; ?>

                  <!-- Section 1: Informasi Bank -->
                  <div class="neo-form-section">
                    <div class="neo-form-section-header">
                      <i class="fas fa-university"></i>
                      <h6>Informasi Bank</h6>
                    </div>
                    <div class="neo-form-section-body">
                      <!-- Bank Name -->
                      <div class="neo-input-wrap">
                        <label for="bank_name" class="form-label">
                          <i class="fas fa-landmark me-1"></i>
                          Nama Bank
                          <span class="required">*</span>
                        </label>
                        <i class="fas fa-building-columns input-icon"></i>
                        <input type="text"
                               class="form-control"
                               id="bank_name"
                               name="bank_name"
                               value="<?php echo htmlspecialchars($bankName); ?>"
                               placeholder="Contoh: Bank Central Asia"
                               required>
                        <div class="form-text">
                          <i class="fas fa-info-circle"></i>
                          Masukkan nama bank lengkap
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 2: Informasi Rekening -->
                  <div class="neo-form-section">
                    <div class="neo-form-section-header">
                      <i class="fas fa-credit-card"></i>
                      <h6>Informasi Rekening</h6>
                    </div>
                    <div class="neo-form-section-body">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- Account Number -->
                          <div class="neo-input-wrap">
                            <label for="account_number" class="form-label">
                              <i class="fas fa-hashtag me-1"></i>
                              Nomor Rekening
                              <span class="required">*</span>
                            </label>
                            <i class="fas fa-key input-icon"></i>
                            <input type="text"
                                   class="form-control"
                                   id="account_number"
                                   name="account_number"
                                   value="<?php echo htmlspecialchars($accountNumber); ?>"
                                   placeholder="Contoh: 1234567890"
                                   required>
                            <div class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Nomor rekening harus unik
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <!-- Account Holder -->
                          <div class="neo-input-wrap">
                            <label for="account_holder" class="form-label">
                              <i class="fas fa-user me-1"></i>
                              Nama Pemilik Rekening
                              <span class="required">*</span>
                            </label>
                            <i class="fas fa-user-tie input-icon"></i>
                            <input type="text"
                                   class="form-control"
                                   id="account_holder"
                                   name="account_holder"
                                   value="<?php echo htmlspecialchars($accountHolder); ?>"
                                   placeholder="Contoh: PT Synectra Indonesia"
                                   required>
                            <div class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Nama pemilik rekening sesuai buku tabungan
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 3: Logo Bank -->
                  <div class="neo-form-section">
                    <div class="neo-form-section-header">
                      <i class="fas fa-image"></i>
                      <h6>Logo Bank</h6>
                    </div>
                    <div class="neo-form-section-body">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- Logo Upload -->
                          <label for="bank_logo" class="form-label">
                            <i class="fas fa-upload me-1"></i>
                            Upload Logo
                          </label>
                          <div class="neo-upload-area" onclick="document.getElementById('bank_logo').click()">
                            <input type="file"
                                   class="form-control"
                                   id="bank_logo"
                                   name="bank_logo"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   style="display: none;"
                                   onchange="previewLogo(this)">
                            <i class="fas fa-cloud-upload-alt logo-upload-icon"></i>
                            <div class="logo-upload-text">Klik untuk upload logo</div>
                            <div class="logo-upload-hint">JPG, PNG, GIF, WEBP (Maksimal 2MB)</div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <!-- Logo Preview -->
                          <div id="logoPreviewContainer" style="display: none;">
                            <label class="form-label">
                              <i class="fas fa-eye me-1"></i>
                              Preview Logo
                            </label>
                            <div class="logo-preview-wrapper">
                              <img id="logoPreviewImage" src="" alt="Logo Preview" style="max-width: 200px; max-height: 200px;">
                              <button type="button" class="btn-remove-logo" onclick="removeLogo()" title="Hapus Logo">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>

                          <!-- Current Logo (Edit Mode) -->
                          <?php if ($isEdit && $bank['bank_logo']): ?>
                            <label class="form-label">
                              <i class="fas fa-image me-1"></i>
                              Logo Saat Ini
                            </label>
                            <div class="current-logo-container">
                              <img src="<?= BASE_URL ?>/uploads/bank/<?php echo htmlspecialchars($bank['bank_logo']); ?>"
                                   alt="Current Logo"
                                   style="max-width: 200px; max-height: 200px;">
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 4: Pengaturan -->
                  <div class="neo-form-section">
                    <div class="neo-form-section-header">
                      <i class="fas fa-cog"></i>
                      <h6>Pengaturan</h6>
                    </div>
                    <div class="neo-form-section-body">
                      <!-- Active Status Toggle -->
                      <div class="form-group">
                        <div class="form-switch-info">
                          <h6>
                            <i class="fas fa-toggle-on"></i>
                            Status Aktif
                          </h6>
                          <p>Aktifkan bank ini agar ditampilkan dalam daftar pilihan bank</p>
                        </div>
                        <div>
                          <input class="form-check-input"
                                 type="checkbox"
                                 id="is_active"
                                 name="is_active"
                                 value="1"
                                 <?php echo $isActive ? 'checked' : ''; ?>>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Form Actions -->
                  <div class="neo-form-section">
                    <div class="neo-form-section-body">
                      <div class="neo-form-actions">
                        <button type="button" class="neo-btn neo-btn-white" onclick="confirmCancel()">
                          <i class="fas fa-times"></i>
                          Batal
                        </button>
                        <button type="submit" class="neo-btn neo-btn-dark">
                          <i class="fas fa-save"></i>
                          <?php echo $isEdit ? 'Simpan Perubahan' : 'Simpan Bank'; ?>
                        </button>
                      </div>
                    </div>
                  </div>

                </form>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include(BASE_PATH . 'app/Template/script.php'); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($errors && is_array($errors)): ?>
        SynAlert.show({
          type  : 'error',
          title : 'Terjadi Kesalahan!',
          html  : '<ul><?php foreach ($errors as $error): ?><li><?= addslashes(htmlspecialchars($error)) ?></li><?php endforeach; ?></ul>'
        });
      <?php endif; ?>
    });

    // Preview logo before upload
    function previewLogo(input) {
      const previewContainer = document.getElementById('logoPreviewContainer');
      const previewImage = document.getElementById('logoPreviewImage');

      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
          previewImage.src = e.target.result;
          previewContainer.style.display = 'block';

          // Animate preview with scale effect
          previewContainer.style.opacity = '0';
          previewContainer.style.transform = 'scale(0.9)';
          setTimeout(() => {
            previewContainer.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            previewContainer.style.opacity = '1';
            previewContainer.style.transform = 'scale(1)';
          }, 10);

          // Add success animation to upload area
          const uploadArea = document.querySelector('.logo-upload-area');
          uploadArea.style.borderColor = '#22c55e';
          uploadArea.style.background = '#f0fdf4';
          setTimeout(() => {
            uploadArea.style.borderColor = '#cbd5e1';
            uploadArea.style.background = '#f8fafc';
          }, 2000);
        }

        reader.readAsDataURL(input.files[0]);
      } else {
        previewContainer.style.display = 'none';
      }
    }

    // Remove logo selection
    function removeLogo() {
      document.getElementById('bank_logo').value = '';
      const previewContainer = document.getElementById('logoPreviewContainer');

      // Animate out with scale effect
      previewContainer.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
      previewContainer.style.opacity = '0';
      previewContainer.style.transform = 'scale(0.9)';

      setTimeout(() => {
        previewContainer.style.display = 'none';
        previewContainer.style.transform = 'scale(1)';
      }, 300);
    }

    function confirmCancel() {
      SynAlert.confirm({
        title       : 'Batalkan Perubahan?',
        message     : 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar?',
        type        : 'warning',
        confirmText : 'Ya, Batalkan',
        cancelText  : 'Tidak, Lanjutkan',
        onConfirm   : () => window.location.href = '<?= BASE_URL ?>/bank'
      });
    }

    // Client-side validation with SweetAlert2
    document.getElementById('bankForm').addEventListener('submit', function(e) {
      const bankName = document.getElementById('bank_name');
      const accountNumber = document.getElementById('account_number');
      const accountHolder = document.getElementById('account_holder');
      let isValid = true;
      let errors = [];

      // Reset validation
      [bankName, accountNumber, accountHolder].forEach(el => {
        el.classList.remove('is-invalid');
        el.closest('.input-group-wrapper').style.borderColor = '#e2e8f0';
      });

      // Validate required fields
      if (!bankName.value.trim()) {
        bankName.classList.add('is-invalid');
        bankName.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        errors.push('Nama bank wajib diisi');
        isValid = false;
      }

      if (!accountNumber.value.trim()) {
        accountNumber.classList.add('is-invalid');
        accountNumber.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        errors.push('Nomor rekening wajib diisi');
        isValid = false;
      }

      if (!accountHolder.value.trim()) {
        accountHolder.classList.add('is-invalid');
        accountHolder.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        errors.push('Nama pemilik rekening wajib diisi');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();

        // Build errors HTML with ES5 compatible code
        var errorsHtml = '<div style="text-align: left; padding: 1rem 0;">' +
                        '<ul style="list-style: none; padding: 0; margin: 0;">';

        for (var i = 0; i < errors.length; i++) {
          errorsHtml += '<li style="padding: 0.5rem; margin-bottom: 0.5rem; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px;">' +
                      '<i class="fas fa-times-circle" style="color: #dc2626; margin-right: 0.5rem;"></i>' +
                      errors[i] +
                      '</li>';
        }

        errorsHtml += '</ul></div>';

        SynAlert.show({ type: 'error', title: 'Validasi Gagal', html: errorsHtml });

        // Scroll to first error with animation
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
          firstError.closest('.form-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(() => {
            firstError.focus();
            firstError.closest('.input-group-wrapper').style.animation = 'shake 0.5s';
          }, 500);
        }
      }
    });

    // Animate form sections on scroll/load
    document.addEventListener('DOMContentLoaded', function() {
      const sections = document.querySelectorAll('.form-section');
      sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        setTimeout(() => {
          section.style.transition = 'all 0.5s ease';
          section.style.opacity = '1';
          section.style.transform = 'translateY(0)';
        }, index * 100);
      });
    });

    // Add focus animations to form inputs
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        const wrapper = this.closest('.input-group-wrapper');
        if (wrapper) {
          wrapper.style.transform = 'translateY(-2px)';
          wrapper.style.transition = 'transform 0.3s ease';
        }
      });

      input.addEventListener('blur', function() {
        const wrapper = this.closest('.input-group-wrapper');
        if (wrapper) {
          wrapper.style.transform = 'translateY(0)';
        }
      });
    });

    // Logo upload area hover effects
    const logoUploadArea = document.querySelector('.logo-upload-area');
    if (logoUploadArea) {
      logoUploadArea.addEventListener('mouseenter', function() {
        this.querySelector('.logo-upload-icon').style.transform = 'scale(1.1) translateY(-5px)';
      });

      logoUploadArea.addEventListener('mouseleave', function() {
        this.querySelector('.logo-upload-icon').style.transform = 'scale(1) translateY(0)';
      });
    }

    // Toggle switch animation
    const toggleSwitch = document.getElementById('is_active');
    if (toggleSwitch) {
      toggleSwitch.addEventListener('change', function() {
        const wrapper = this.closest('.form-switch-wrapper');
        if (this.checked) {
          wrapper.style.background = 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)';
          wrapper.style.borderColor = '#22c55e';
        } else {
          wrapper.style.background = 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)';
          wrapper.style.borderColor = '#cbd5e1';
        }
      });
    }
  </script>
</body>

</html>


