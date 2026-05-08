<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

// Check if user is admin
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/dashboard/client');
    exit();
}

$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

$title = $oldInput['title'] ?? ($portfolio['title'] ?? '');
$description = $oldInput['description'] ?? ($portfolio['description'] ?? '');
$category = $oldInput['category'] ?? ($portfolio['category'] ?? '');
$existingImages = $portfolio['images'] ?? [];
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

  /* Multiple Upload Area */
  .image-upload-wrapper {
    border: 3px dashed #cbd5e1;
    border-radius: 16px;
    padding: 3rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
  }

  .image-upload-wrapper:hover {
    border-color: #1e3a8a;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    transform: translateY(-2px);
  }

  .image-upload-wrapper.dragover {
    border-color: #22c55e;
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    transform: scale(1.02);
  }

  .image-upload-icon {
    font-size: 4rem;
    color: #94a3b8;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
  }

  .image-upload-wrapper:hover .image-upload-icon {
    color: #1e3a8a;
    transform: scale(1.1);
  }

  .image-upload-text {
    color: #64748b;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .image-upload-hint {
    color: #94a3b8;
    font-size: 0.875rem;
  }

  /* Image Gallery Grid */
  .image-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
  }

  .gallery-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: white;
    transition: all 0.3s ease;
  }

  .gallery-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  }

  .gallery-item.primary {
    border: 3px solid #f59e0b;
    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
  }

  .gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
  }

  .gallery-item-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .gallery-item:hover .gallery-item-overlay {
    opacity: 1;
  }

  .gallery-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 10;
  }

  .gallery-btn {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .gallery-btn-primary {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
  }

  .gallery-btn-primary:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
  }

  .gallery-btn-delete {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
  }

  .gallery-btn-delete:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
  }

  /* Category Tags */
  .category-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.5rem;
  }

  .category-tag {
    padding: 0.5rem 1rem;
    background: #f1f5f9;
    border: 2px solid #e2e8f0;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .category-tag:hover {
    border-color: #1e3a8a;
    background: #dbeafe;
    color: #1e3a8a;
    transform: translateY(-2px);
  }

  .category-tag.selected {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    border-color: #1e3a8a;
    color: white;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
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

  /* Preview Container */
  .preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
  }

  .preview-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: white;
  }

  .preview-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
  }

  .preview-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(220, 38, 38, 0.9);
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    transition: all 0.3s ease;
  }

  .preview-remove:hover {
    background: rgba(220, 38, 38, 1);
    transform: scale(1.1);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .form-section-body {
      padding: 1.5rem;
    }

    .category-tags {
      gap: 0.5rem;
    }

    .form-actions {
      flex-direction: column;
    }

    .form-actions .btn {
      width: 100%;
      justify-content: center;
    }

    .image-upload-wrapper {
      padding: 2rem 1rem;
    }

    .image-gallery {
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
                  <?php echo $isEdit ? 'Edit Portofolio' : 'Tambah Portofolio Baru'; ?>
                </h3>
              </div>

              <div class="container-fluid p-0">
                <!-- Error Messages (Hidden, will be shown with SweetAlert2) -->
                <?php
                $errors = $_SESSION['errors'] ?? null;
                unset($_SESSION['errors']);
                ?>

                <!-- Form -->
                <form method="POST"
                      action="<?php echo $isEdit ? BASE_URL . '/portofolio/update' : BASE_URL . '/portofolio/store'; ?>"
                      id="portofolioForm"
                      enctype="multipart/form-data">

                  <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($portfolio['id']); ?>">
                  <?php endif; ?>

                  <!-- Section 1: Informasi Dasar -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-info-circle"></i>
                      <h6>Informasi Dasar</h6>
                    </div>
                    <div class="form-section-body">
                      <div class="row">
                        <div class="col-md-12">
                          <!-- Title -->
                          <div class="input-group-wrapper">
                            <label for="title" class="form-label">
                              <i class="fas fa-heading me-1"></i>
                              Judul Portofolio
                              <span class="required">*</span>
                            </label>
                            <i class="fas fa-heading input-icon"></i>
                            <input type="text"
                                   class="form-control"
                                   id="title"
                                   name="title"
                                   value="<?php echo htmlspecialchars($title); ?>"
                                   placeholder="Contoh: Website E-Commerce, Aplikasi Mobile"
                                   required>
                            <div class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Judul yang menarik untuk portofolio Anda
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <!-- Description -->
                          <div class="input-group-wrapper">
                            <label for="description" class="form-label">
                              <i class="fas fa-align-left me-1"></i>
                              Deskripsi
                            </label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Jelaskan detail tentang portofolio ini..."><?php echo htmlspecialchars($description); ?></textarea>
                            <div class="form-text">
                              <i class="fas fa-info-circle"></i>
                              Deskripsi singkat tentang portofolio (opsional)
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section 2: Kategori -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-tags"></i>
                      <h6>Kategori</h6>
                    </div>
                    <div class="form-section-body">
                      <label class="form-label">
                        <i class="fas fa-folder me-1"></i>
                        Pilih atau Buat Kategori
                      </label>
                      <input type="hidden" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>">

                      <div class="input-group-wrapper">
                        <i class="fas fa-tag input-icon"></i>
                        <input type="text"
                               class="form-control"
                               id="categoryInput"
                               placeholder="Ketik kategori baru atau pilih dari opsi di bawah"
                               value="<?php echo htmlspecialchars($category); ?>">
                      </div>

                      <?php if (!empty($categories)): ?>
                        <div class="category-tags">
                          <span class="category-tag <?php echo empty($category) ? 'selected' : ''; ?>"
                                onclick="selectCategory('')">
                            Semua
                          </span>
                          <?php foreach ($categories as $cat): ?>
                            <span class="category-tag <?php echo $category === $cat ? 'selected' : ''; ?>"
                                  onclick="selectCategory('<?php echo htmlspecialchars($cat); ?>')">
                              <?php echo htmlspecialchars($cat); ?>
                            </span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>

                      <div class="form-text">
                        <i class="fas fa-lightbulb"></i>
                        Contoh: Web Design, Mobile App, Branding, Photography, dll.
                      </div>
                    </div>
                  </div>

                  <!-- Section 3: Upload Multiple Images -->
                  <div class="form-section">
                    <div class="form-section-header">
                      <i class="fas fa-images"></i>
                      <h6>Galeri Gambar</h6>
                    </div>
                    <div class="form-section-body">
                      <?php if ($isEdit && !empty($existingImages)): ?>
                        <!-- Existing Images Gallery -->
                        <label class="form-label">
                          <i class="fas fa-image me-1"></i>
                          Gambar Saat Ini (<?php echo count($existingImages); ?> gambar)
                        </label>

                        <div class="image-gallery">
                          <?php foreach ($existingImages as $img): ?>
                            <div class="gallery-item <?php echo $img['is_primary'] ? 'primary' : ''; ?>">
                              <?php if ($img['is_primary']): ?>
                                <span class="gallery-badge">
                                  <i class="fas fa-star me-1"></i>Utama
                                </span>
                              <?php endif; ?>
                              <img src="<?= BASE_URL ?>/<?php echo htmlspecialchars($img['image_path']); ?>"
                                   alt="Portfolio Image">

                              <div class="gallery-item-overlay">
                                <?php if (!$img['is_primary']): ?>
                                  <a href="<?= BASE_URL ?>/portofolio/setPrimary?image_id=<?php echo $img['id']; ?>&portfolio_id=<?php echo $portfolio['id']; ?>"
                                     class="gallery-btn gallery-btn-primary"
                                     title="Set sebagai Gambar Utama">
                                    <i class="fas fa-star"></i> Jadikan Utama
                                  </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/portofolio/deleteImage?image_id=<?php echo $img['id']; ?>&portfolio_id=<?php echo $portfolio['id']; ?>"
                                   class="gallery-btn gallery-btn-delete"
                                   title="Hapus Gambar"
                                   onclick="return confirmDeleteImage()">
                                  <i class="fas fa-trash"></i> Hapus
                                </a>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>

                        <div class="form-text mt-3">
                          <i class="fas fa-info-circle"></i>
                          Klik "Jadikan Utama" untuk mengatur gambar thumbnail. Klik "Hapus" untuk menghapus gambar.
                        </div>
                      <?php endif; ?>

                      <!-- Upload New Images -->
                      <label class="form-label mt-4">
                        <i class="fas fa-cloud-upload-alt me-1"></i>
                        Upload Gambar Baru
                        <?php echo $isEdit ? '' : '<span class="required">*</span>' ?>
                      </label>

                      <div class="image-upload-wrapper"
                           id="dropZone"
                           onclick="document.getElementById('images').click()">
                        <i class="fas fa-cloud-upload-alt image-upload-icon"></i>
                        <div class="image-upload-text">
                          Klik atau Drag & Drop gambar di sini
                        </div>
                        <div class="image-upload-hint">
                          JPG, PNG, GIF, WEBP (Maks. 5MB per gambar)
                        </div>
                        <input type="file"
                               name="images[]"
                               id="images"
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               multiple
                               style="display: none"
                               <?php echo $isEdit ? '' : 'required'; ?>>
                      </div>

                      <!-- Preview Container -->
                      <div id="previewContainer" class="preview-container"></div>

                      <div class="form-text">
                        <i class="fas fa-lightbulb"></i>
                        Anda dapat memilih multiple gambar sekaligus. Gambar pertama akan otomatis menjadi gambar utama.
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
                          <?php echo $isEdit ? 'Simpan Perubahan' : 'Simpan Portofolio'; ?>
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


    // Select category from tags
    function selectCategory(categoryName) {
      document.getElementById('category').value = categoryName;
      document.getElementById('categoryInput').value = categoryName;

      // Update selected state
      document.querySelectorAll('.category-tag').forEach(tag => {
        tag.classList.remove('selected');
      });
      event.currentTarget.classList.add('selected');
    }

    // Update hidden category input when typing
    document.getElementById('categoryInput').addEventListener('input', function() {
      document.getElementById('category').value = this.value;

      // Remove selected state from all tags
      document.querySelectorAll('.category-tag').forEach(tag => {
        tag.classList.remove('selected');
      });
    });

    // Multiple file upload handling
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('previewContainer');
    const dropZone = document.getElementById('dropZone');
    let selectedFiles = [];

    imageInput.addEventListener('change', function(e) {
      handleFiles(e.target.files);
    });

    // Drag and drop
    dropZone.addEventListener('dragover', function(e) {
      e.preventDefault();
      dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
      e.preventDefault();
      dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
      e.preventDefault();
      dropZone.classList.remove('dragover');

      const files = e.dataTransfer.files;
      handleFiles(files);
    });

    function handleFiles(files) {
      for (let i = 0; i < files.length; i++) {
        const file = files[i];

        if (file.type.startsWith('image/')) {
          selectedFiles.push(file);
          showPreview(file);
        }
      }
    }

    function showPreview(file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        previewItem.innerHTML = `
          <img src="${e.target.result}" alt="Preview">
          <button type="button" class="preview-remove" onclick="removePreview(this)">
            <i class="fas fa-times"></i>
          </button>
        `;
        previewContainer.appendChild(previewItem);
      };
      reader.readAsDataURL(file);
    }

    function removePreview(button) {
      const previewItem = button.closest('.preview-item');
      const index = Array.from(previewContainer.children).indexOf(previewItem);

      if (index > -1) {
        selectedFiles.splice(index, 1);
        previewItem.remove();
      }
    }

    function confirmDeleteImage() {
      return confirm('Apakah Anda yakin ingin menghapus gambar ini?');
    }

    function confirmCancel() {
      SynAlert.confirm({
        title       : 'Batalkan Perubahan?',
        message     : 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar?',
        type        : 'warning',
        confirmText : 'Ya, Batalkan',
        cancelText  : 'Tidak, Lanjutkan',
        onConfirm   : () => window.location.href = '<?= BASE_URL ?>/portofolio'
      });
    }

    // Client-side validation with SweetAlert2
    document.getElementById('portofolioForm').addEventListener('submit', function(e) {
      const title = document.getElementById('title');
      let isValid = true;
      let errors = [];

      // Reset validation
      title.classList.remove('is-invalid');
      if (title.closest('.input-group-wrapper')) {
        title.closest('.input-group-wrapper').style.borderColor = '#e2e8f0';
      }

      // Validate required fields
      if (!title.value.trim()) {
        title.classList.add('is-invalid');
        if (title.closest('.input-group-wrapper')) {
          title.closest('.input-group-wrapper').style.borderColor = '#dc2626';
        }
        errors.push('Judul portofolio wajib diisi');
        isValid = false;
      }

      // Validate file on create
      <?php if (!$isEdit): ?>
      const imageInput = document.getElementById('images');
      if ((!selectedFiles || selectedFiles.length === 0) && (!imageInput.files || imageInput.files.length === 0)) {
        errors.push('Minimal upload 1 gambar portofolio');
        isValid = false;
      }
      <?php endif; ?>

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

        // Scroll to first error
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
          firstError.closest('.form-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(() => firstError.focus(), 500);
        }
      } else {
        // If validation passes, transfer selectedFiles to the file input using FormData
        const imageInput = document.getElementById('images');
        if (selectedFiles && selectedFiles.length > 0) {
          const dataTransfer = new DataTransfer();
          for (let i = 0; i < selectedFiles.length; i++) {
            dataTransfer.items.add(selectedFiles[i]);
          }
          imageInput.files = dataTransfer.files;
        }
        // Allow form to submit naturally by not preventing default
        return true;
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
  </script>
</body>

</html>


