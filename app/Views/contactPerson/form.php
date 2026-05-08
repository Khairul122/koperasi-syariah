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

$platform = $oldInput['platform'] ?? ($contactPerson['platform'] ?? '');
$contactInfo = $oldInput['contact_info'] ?? ($contactPerson['contact_info'] ?? '');
$linkUrl = $oldInput['link_url'] ?? ($contactPerson['link_url'] ?? '');
$icon = $oldInput['icon'] ?? ($contactPerson['icon'] ?? '');
$isActive = $oldInput['is_active'] ?? ($contactPerson['is_active'] ?? 1);
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

  /* Icon Selection Grid */
  .icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
  }

  .icon-option {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
  }

  .icon-option:hover {
    border-color: #1e3a8a;
    background: #f1f5f9;
    transform: translateY(-2px);
  }

  .icon-option.selected {
    border-color: #1e3a8a;
    background: #dbeafe;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
  }

  .icon-option i {
    font-size: 1.5rem;
    color: #64748b;
    margin-bottom: 0.5rem;
  }

  .icon-option.selected i {
    color: #1e3a8a;
  }

  .icon-option span {
    display: block;
    font-size: 0.7rem;
    color: #94a3b8;
    font-weight: 600;
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

    .icon-grid {
      grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
      gap: 0.75rem;
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
 ?>
      <?php include(BASE_PATH . 'app/Template/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Page Header -->
              <div class="page-header-simple">
                <h3>
                  <i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus-circle'; ?>"></i>
                  <?php echo $isEdit ? 'Edit Kontak Person' : 'Tambah Kontak Person Baru'; ?>
                </h3>
              </div>

              <div class="container-fluid p-0">
                <!-- Error Messages (Hidden, will be shown with SweetAlert2) -->
                <?php
                $errors = $_SESSION['errors'] ?? null;
                unset($_SESSION['errors']);
                ?>

                <!-- Form -->
                <form method="POST" action="<?php echo $isEdit ? '<?= BASE_URL ?>/contact-person/update' : '<?= BASE_URL ?>/contact-person/store'; ?>"
                      id="contactPersonForm">

                  <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($contactPerson['id']); ?>">
                  <?php endif; ?>

                  <!-- Section 1: Informasi Platform -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-share-alt"></i>
                      <h6>Informasi Platform</h6>
                    </div>
                    <div class="form-section-body">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- Platform Name -->
                          <div class="input-group-wrapper">
                            <label for="platform" class="form-label">
                              <i class="fas fa-tag me-1"></i>
                              Nama Platform
                              <span class="required">*</span>
                            </label>
                            <i class="fas fa-hashtag input-icon"></i>
                            <input type="text"
                                   class="form-control"
                                   id="platform"
                                   name="platform"
                                   value="<?php echo htmlspecialchars($platform); ?>"
                                   placeholder="Contoh: WhatsApp, Instagram"
                                   required>
                            <div class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Nama platform media sosial atau kontak
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <!-- Contact Info -->
                          <div class="input-group-wrapper">
                            <label for="contact_info" class="form-label">
                              <i class="fas fa-id-card me-1"></i>
                              Info Kontak
                              <span class="required">*</span>
                            </label>
                            <i class="fas fa-info input-icon"></i>
                            <input type="text"
                                   class="form-control"
                                   id="contact_info"
                                   name="contact_info"
                                   value="<?php echo htmlspecialchars($contactInfo); ?>"
                                   placeholder="Contoh: +62 812-3456-7890"
                                   required>
                            <div class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Nomor atau username yang ditampilkan
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 2: Icon Platform -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-icons"></i>
                      <h6>Pilih Icon</h6>
                    </div>
                    <div class="form-section-body">
                      <label class="form-label">
                        <i class="fas fa-image me-1"></i>
                        Icon Platform
                      </label>
                      <input type="hidden" id="icon" name="icon" value="<?php echo htmlspecialchars($icon); ?>">

                      <div class="icon-grid" id="iconGrid">
                        <div class="icon-option <?php echo $icon === 'fa-whatsapp' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-whatsapp')">
                          <i class="fab fa-whatsapp"></i>
                          <span>WhatsApp</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-instagram' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-instagram')">
                          <i class="fab fa-instagram"></i>
                          <span>Instagram</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-facebook' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-facebook')">
                          <i class="fab fa-facebook"></i>
                          <span>Facebook</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-facebook-messenger' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-facebook-messenger')">
                          <i class="fab fa-facebook-messenger"></i>
                          <span>Messenger</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-twitter' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-twitter')">
                          <i class="fab fa-twitter"></i>
                          <span>Twitter</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-linkedin' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-linkedin')">
                          <i class="fab fa-linkedin"></i>
                          <span>LinkedIn</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-youtube' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-youtube')">
                          <i class="fab fa-youtube"></i>
                          <span>YouTube</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-tiktok' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-tiktok')">
                          <i class="fab fa-tiktok"></i>
                          <span>TikTok</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-telegram' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-telegram')">
                          <i class="fab fa-telegram"></i>
                          <span>Telegram</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-envelope' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-envelope')">
                          <i class="fas fa-envelope"></i>
                          <span>Email</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-phone' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-phone')">
                          <i class="fas fa-phone"></i>
                          <span>Telepon</span>
                        </div>
                        <div class="icon-option <?php echo $icon === 'fa-globe' ? 'selected' : ''; ?>"
                             onclick="selectIcon('fa-globe')">
                          <i class="fas fa-globe"></i>
                          <span>Website</span>
                        </div>
                      </div>
                      <div class="form-text">
                        <i class="fas fa-hand-pointer"></i>
                        Pilih icon yang sesuai dengan platform (opsional)
                      </div>
                    </div>
                  </div>

                  <!-- Section 3: Link URL -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-link"></i>
                      <h6>Link URL</h6>
                    </div>
                    <div class="form-section-body">
                      <div class="input-group-wrapper">
                        <label for="link_url" class="form-label">
                          <i class="fas fa-external-link-alt me-1"></i>
                          URL Lengkap
                          <span class="required">*</span>
                        </label>
                        <i class="fas fa-link input-icon"></i>
                        <input type="url"
                               class="form-control"
                               id="link_url"
                               name="link_url"
                               value="<?php echo htmlspecialchars($linkUrl); ?>"
                               placeholder="Contoh: https://wa.me/6281234567890"
                               required>
                        <div class="form-text">
                          <i class="fas fa-info-circle"></i>
                          URL lengkap untuk menuju ke kontak (https://...)
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 4: Pengaturan -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-cog"></i>
                      <h6>Pengaturan</h6>
                    </div>
                    <div class="form-section-body">
                      <!-- Active Status Toggle -->
                      <div class="neo-toggle-wrap">
                        <div class="neo-toggle-info" style="font-family: inherit;">
                          <h6>
                            <i class="fas fa-toggle-on"></i>
                            Status Aktif
                          </h6>
                          <p>Aktifkan kontak ini agar ditampilkan di halaman depan</p>
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
                  <div class="form-section">
                    <div class="form-section-body">
                      <div class="form-actions">
                        <button type="button" class="btn btn-cancel" onclick="confirmCancel()">
                          <i class="fas fa-times"></i>
                          Batal
                        </button>
                        <button type="submit" class="btn btn-submit">
                          <i class="fas fa-save"></i>
                          <?php echo $isEdit ? 'Simpan Perubahan' : 'Simpan Kontak'; ?>
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

    // Select icon from grid
    function selectIcon(iconName) {
      // Update hidden input
      document.getElementById('icon').value = iconName;

      // Remove selected class from all icons
      document.querySelectorAll('.icon-option').forEach(option => {
        option.classList.remove('selected');
      });

      // Add selected class to clicked icon
      event.currentTarget.classList.add('selected');
    }

    function confirmCancel() {
      SynAlert.confirm({
        title       : 'Batalkan Perubahan?',
        message     : 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar?',
        type        : 'warning',
        confirmText : 'Ya, Batalkan',
        cancelText  : 'Tidak, Lanjutkan',
        onConfirm   : () => window.location.href = '<?= BASE_URL ?>/contact-person'
      });
    }

    // Client-side validation with SweetAlert2
    document.getElementById('contactPersonForm').addEventListener('submit', function(e) {
      const platform = document.getElementById('platform');
      const contactInfo = document.getElementById('contact_info');
      const linkUrl = document.getElementById('link_url');
      let isValid = true;
      let errors = [];

      // Reset validation
      [platform, contactInfo, linkUrl].forEach(el => {
        el.classList.remove('is-invalid');
        if (el.closest('.input-group-wrapper')) {
          el.closest('.input-group-wrapper').style.borderColor = '#e2e8f0';
        }
      });

      // Validate required fields
      if (!platform.value.trim()) {
        platform.classList.add('is-invalid');
        if (platform.closest('.input-group-wrapper')) {
          platform.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        }
        errors.push('Nama platform wajib diisi');
        isValid = false;
      }

      if (!contactInfo.value.trim()) {
        contactInfo.classList.add('is-invalid');
        if (contactInfo.closest('.input-group-wrapper')) {
          contactInfo.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        }
        errors.push('Info kontak wajib diisi');
        isValid = false;
      }

      if (!linkUrl.value.trim()) {
        linkUrl.classList.add('is-invalid');
        if (linkUrl.closest('.input-group-wrapper')) {
          linkUrl.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        }
        errors.push('Link URL wajib diisi');
        isValid = false;
      }

      // Validate URL format
      if (linkUrl.value.trim()) {
        try {
          new URL(linkUrl.value);
        } catch (_) {
          linkUrl.classList.add('is-invalid');
          if (linkUrl.closest('.input-group-wrapper')) {
            linkUrl.closest('.input-group-wrapper').style.borderColor = '#dc2626';
          }
          errors.push('Format URL tidak valid (harus dimulai dengan http:// atau https://)');
          isValid = false;
        }
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
            if (firstError.closest('.input-group-wrapper')) {
              firstError.closest('.input-group-wrapper').style.animation = 'shake 0.5s';
            }
          }, 500);
        }
      }
    });

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

    // Icon selection hover effects
    document.querySelectorAll('.icon-option').forEach(option => {
      option.addEventListener('mouseenter', function() {
        if (!this.classList.contains('selected')) {
          this.style.transform = 'translateY(-2px) scale(1.05)';
        }
      });

      option.addEventListener('mouseleave', function() {
        if (!this.classList.contains('selected')) {
          this.style.transform = 'translateY(0) scale(1)';
        }
      });
    });

    
    </script>
</body>

</html>




