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

$title = $oldInput['title'] ?? ($banner['title'] ?? '');
$description = $oldInput['description'] ?? ($banner['description'] ?? '');
$linkUrl = $oldInput['link_url'] ?? ($banner['link_url'] ?? '');
$isActive = $oldInput['is_active'] ?? ($banner['is_active'] ?? 1);
?>

<?php include(BASE_PATH . 'app/Template/header.php'); ?>
<?php include(BASE_PATH . 'app/Template/ui_components.php'); ?>


<style>
  /* Page Header */
  .page-header-simple {
    margin-bottom: 2rem;
    padding: 1rem 0;
  }

  .page-header-simple h3 {
    color: #1e3a8a;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .page-header-simple h3 i {
    color: #1e3a8a;
    font-size: 1.5rem;
  }

  /* Form Section Card */
  .form-section {
    background: white;
    border: none;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .form-section:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
  }

  .form-section-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.25rem 1.5rem;
    border-bottom: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .form-section-header i {
    color: #1e3a8a;
    font-size: 1.25rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(30, 58, 138, 0.1);
  }

  .form-section-header h6 {
    margin: 0;
    color: #1e3a8a;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .form-section-body {
    padding: 2rem;
  }

  /* Input Groups with Icons */
  .input-group-wrapper {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .input-group-wrapper .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
    z-index: 10;
    transition: all 0.3s ease;
  }

  .input-group-wrapper .form-control {
    padding-left: 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f8fafc;
  }

  .input-group-wrapper .form-control:focus {
    border-color: #1e3a8a;
    background: white;
    box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.08);
    transform: translateY(-1px);
  }

  .input-group-wrapper:focus-within .input-icon {
    color: #1e3a8a;
    transform: translateY(-50%) scale(1.1);
  }

  .form-label {
    font-weight: 700;
    color: #334155;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-label .required {
    color: #dc2626;
    font-weight: 700;
  }

  .form-text {
    color: #64748b;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-text i {
    color: #94a3b8;
  }

  textarea.form-control {
    min-height: 120px;
    resize: vertical;
  }

  /* Banner Image Upload */
  .banner-upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .banner-upload-area:hover {
    border-color: #1e3a8a;
    background: #f1f5f9;
  }

  .banner-upload-icon {
    font-size: 3rem;
    color: #94a3b8;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
  }

  .banner-upload-area:hover .banner-upload-icon {
    color: #1e3a8a;
    transform: scale(1.1);
  }

  .banner-upload-text {
    color: #475569;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .banner-upload-hint {
    color: #94a3b8;
    font-size: 0.85rem;
  }

  /* Image Preview */
  .image-preview-wrapper {
    display: inline-block;
    position: relative;
    margin-top: 1rem;
  }

  .image-preview-wrapper img {
    border: 3px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    max-width: 100%;
    height: auto;
    max-height: 300px;
  }

  .image-preview-wrapper:hover img {
    border-color: #1e3a8a;
    transform: scale(1.02);
    box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
  }

  .btn-remove-image {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    border: 3px solid white;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
  }

  .btn-remove-image:hover {
    transform: scale(1.15) rotate(90deg);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
  }

  /* Current Image */
  .current-image-container {
    display: inline-block;
    position: relative;
    padding: 10px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
  }

  .current-image-container img {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    max-width: 100%;
    height: auto;
    max-height: 300px;
  }

  /* Toggle Switch */
  .form-switch-wrapper {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #86efac;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    transition: all 0.3s ease;
  }

  .form-switch-wrapper:hover {
    border-color: #22c55e;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
  }

  .form-switch-info h6 {
    margin: 0 0 0.25rem 0;
    color: #166534;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-switch-info p {
    margin: 0;
    color: #15803d;
    font-size: 0.85rem;
  }

  .form-check-input {
    width: 3.5rem;
    height: 2rem;
    border-radius: 2rem;
    background-color: #cbd5e1;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .form-check-input:checked {
    background-color: #22c55e;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='white'/%3e%3c/svg%3e");
  }

  /* Action Buttons */
  .form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 1rem;
    border-top: 2px solid #e2e8f0;
  }

  .btn-submit {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    border: none;
    padding: 0.875rem 2.5rem;
    border-radius: 10px;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(30, 58, 138, 0.35);
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
  }

  .btn-cancel {
    border: 2px solid #e2e8f0;
    padding: 0.875rem 2.5rem;
    border-radius: 10px;
    font-weight: 600;
    color: #64748b;
    background: white;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-cancel:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #475569;
    transform: translateY(-1px);
  }

  /* Alert Messages */
  .alert {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 5px solid;
  }

  .alert-danger {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left-color: #dc2626;
    color: #991b1b;
  }

  .alert-danger ul {
    margin: 0.5rem 0 0 0;
    padding-left: 1.5rem;
  }

  .alert-danger li {
    margin-bottom: 0.25rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .form-section-body {
      padding: 1.5rem;
    }

    .form-actions {
      flex-direction: column;
    }

    .form-actions .btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>

<body>
  <div class="container-scroller">
    <?php include(BASE_PATH . 'app/Template/navbar.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include(BASE_PATH . 'app/Template/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Page Header -->
              <div class="neo-page-header">
                <h3>
                  <i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus-circle'; ?>"></i>
                  <?php echo $isEdit ? 'Edit Banner' : 'Tambah Banner Baru'; ?>
                </h3>
              </div>

              <div class="container-fluid p-0">
                <!-- Error Messages (Hidden, will be shown with SweetAlert2) -->
                <?php
                $errors = $_SESSION['errors'] ?? null;
                unset($_SESSION['errors']);
                ?>

                <!-- Form -->
                <form method="POST" action="<?php echo $isEdit ? 'index.php?controller=banner&action=update' : '<?= BASE_URL ?>/banner/store'; ?>"
                      enctype="multipart/form-data" id="bannerForm">

                  <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($banner['id']); ?>">
                  <?php endif; ?>

                  <!-- Section 1: Informasi Banner -->
                  <div class="neo-card form-section">
                    <div class="form-section-header neo-card-header">
                      <i class="fas fa-info-circle"></i>
                      <h6>Informasi Banner</h6>
                    </div>
                    <div class="form-section-body">
                      <!-- Title -->
                      <div class="input-group-wrapper">
                        <label for="title" class="form-label">
                          <i class="fas fa-heading me-1"></i>
                          Judul Banner
                          <span class="required">*</span>
                        </label>
                        <i class="fas fa-font input-icon"></i>
                        <?php echo ui_input('title', 'text', $title, 'placeholder="Contoh: Promo Spesial" required'); ?>
                        <div class="form-text">
                          <i class="fas fa-info-circle"></i>
                          Judul banner yang akan ditampilkan
                        </div>
                      </div>

                      <!-- Description -->
                      <div class="input-group-wrapper">
                        <label for="description" class="form-label">
                          <i class="fas fa-align-left me-1"></i>
                          Deskripsi
                        </label>
                        <?php echo ui_textarea('description', $description, 'placeholder="Deskripsi singkat tentang banner..."'); ?>
                        <div class="form-text">
                          <i class="fas fa-info-circle"></i>
                          Deskripsi opsional untuk banner
                        </div>
                      </div>

                      <!-- Link URL -->
                      <div class="input-group-wrapper">
                        <label for="link_url" class="form-label">
                          <i class="fas fa-link me-1"></i>
                          Link URL
                        </label>
                        <i class="fas fa-external-link-alt input-icon"></i>
                        <?php echo ui_input('link_url', 'url', $linkUrl, 'placeholder="Contoh: https://example.com/promo"'); ?>
                        <div class="form-text">
                          <i class="fas fa-info-circle"></i>
                          URL tujuan saat banner diklik (opsional)
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 2: Gambar Banner -->
                  <div class="neo-card form-section">
                    <div class="form-section-header neo-card-header">
                      <i class="fas fa-image"></i>
                      <h6>Gambar Banner</h6>
                    </div>
                    <div class="form-section-body">
                      <!-- Banner Upload (Simple) -->
                      <div class="input-group-wrapper">
                        <label for="banner_image" class="form-label">
                          <i class="fas fa-upload me-1"></i>
                          Upload Gambar
                          <?php if (!$isEdit): ?><span class="required">*</span><?php endif; ?>
                        </label>

                        <!-- Current Image (Edit Mode) -->
                        <?php if ($isEdit && $banner['image_path']): ?>
                          <div style="margin-bottom: 15px;">
                            <p class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Gambar saat ini: <strong><?php echo htmlspecialchars($banner['image_path']); ?></strong>
                            </p>
                            <img src="<?php echo htmlspecialchars($banner['image_path']); ?>"
                                 alt="Current Banner"
                                 style="max-width: 300px; border-radius: 8px; border: 2px solid #e2e8f0;">
                          </div>
                        <?php endif; ?>

                        <!-- File Input -->
                        <?php echo ui_input('banner_image', 'file', '', 'accept="image/jpeg,image/png,image/gif,image/webp" ' . (!$isEdit ? 'required' : '')); ?>

                        <div class="form-text">
                          <i class="fas fa-info-circle"></i>
                          <?php if (!$isEdit): ?>
                            Pilih file gambar untuk banner (JPG, PNG, GIF, WEBP - Max 5MB)
                          <?php else: ?>
                            Kosongkan jika tidak ingin mengubah gambar
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 3: Pengaturan -->
                  <div class="neo-card form-section">
                    <div class="form-section-header neo-card-header">
                      <i class="fas fa-cog"></i>
                      <h6>Pengaturan</h6>
                    </div>
                    <div class="form-section-body">
                      <!-- Active Status Toggle -->
                      <div class="form-switch-wrapper">
                        <div class="form-switch-info">
                          <h6>
                            <i class="fas fa-toggle-on"></i>
                            Status Aktif
                          </h6>
                          <p>Aktifkan banner ini agar ditampilkan di halaman depan</p>
                        </div>
                        <div>
                          <?php echo ui_checkbox('is_active', (bool)$isActive); ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Form Actions -->
                  <div class="neo-card form-section">
                    <div class="form-section-body">
                      <div class="form-actions">
                        <button type="button" class="neo-btn neo-btn-white" onclick="confirmCancel()">
                          <i class="fas fa-times"></i>
                          Batal
                        </button>
                        <button type="submit" class="neo-btn neo-btn-primary">
                          <i class="fas fa-save"></i>
                          <?php echo $isEdit ? 'Simpan Perubahan' : 'Simpan Banner'; ?>
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

    function confirmCancel() {
      SynAlert.confirm({
        title       : 'Batalkan Perubahan?',
        message     : 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar?',
        type        : 'warning',
        confirmText : 'Ya, Batalkan',
        cancelText  : 'Tidak, Lanjutkan',
        onConfirm   : () => window.location.href = '<?= BASE_URL ?>/banner'
      });
    }

    // Client-side validation with SweetAlert2
    document.getElementById('bannerForm').addEventListener('submit', function(e) {
      console.log('=== FORM SUBMIT START ==='); // Debug log
      console.log('Form action:', this.action); // Debug log
      console.log('Form method:', this.method); // Debug log

      const title = document.getElementById('title');
      const bannerImage = document.getElementById('banner_image');
      let isValid = true;
      let errors = [];

      // Reset validation
      [title, bannerImage].forEach(el => {
        el.classList.remove('is-invalid');
        if (el.closest('.input-group-wrapper')) {
          el.closest('.input-group-wrapper').style.borderColor = '#e2e8f0';
        }
      });

      // Validate required fields
      if (!title.value.trim()) {
        console.log('Title validation failed'); // Debug log
        title.classList.add('is-invalid');
        if (title.closest('.input-group-wrapper')) {
          title.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        }
        errors.push('Judul banner wajib diisi');
        isValid = false;
      }

      // Validate image only for create mode
      <?php if (!$isEdit): ?>
        if (!bannerImage.files || bannerImage.files.length === 0) {
          console.log('Image validation failed'); // Debug log
          bannerImage.classList.add('is-invalid');
          errors.push('Gambar banner wajib diunggah');
          isValid = false;
        }
      <?php endif; ?>

      console.log('isValid:', isValid, 'errors:', errors); // Debug log

      if (!isValid) {
        console.log('Validation failed, preventing submit'); // Debug log
        e.preventDefault();

        // Build error list HTML manually (no .map() for better compatibility)
        let errorsHtml = '<div style="text-align: left; padding: 1rem 0;">';
        errorsHtml += '<ul style="list-style: none; padding: 0; margin: 0;">';
        for (let i = 0; i < errors.length; i++) {
          errorsHtml += '<li style="padding: 0.5rem; margin-bottom: 0.5rem; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px;">';
          errorsHtml += '<i class="fas fa-times-circle" style="color: #dc2626; margin-right: 0.5rem;"></i>';
          errorsHtml += errors[i];
          errorsHtml += '</li>';
        }
        errorsHtml += '</ul></div>';

        SynAlert.show({ type: 'error', title: 'Validasi Gagal', html: errorsHtml });

        // Scroll to first error with animation
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
          firstError.closest('.form-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(() => {
            firstError.focus();
            if (firstError.closest('.input-group-wrapper')) {
              firstError.closest('.input-group-wrapper').style.animation = 'shake 0.5s';
            }
          }, 500);
        }

        return false; // Explicit return
      }

      console.log('Validation passed, form will submit'); // Debug log
      // Form will submit naturally
    });

    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
      }

      .animated.bounceIn {
        animation: bounceIn 0.5s;
      }

      .animated.shake {
        animation: shake 0.5s;
      }

      @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
      }
    `;
    document.head.appendChild(style);

    // Animate form sections on load
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


