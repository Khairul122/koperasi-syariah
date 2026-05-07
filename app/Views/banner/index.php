<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/login'); exit();
}
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/dashboard/client'); exit();
}

$success_message = $_SESSION['success_message'] ?? null;
$error_message   = $_SESSION['error_message']   ?? null;
$errors          = $_SESSION['errors']           ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['errors']);
?>
<?php include(BASE_PATH . 'app/Template/header.php'); ?>
<style>
  /* Ensure full width and correct alignment for content */
  .neo-page-header, .neo-table-wrap { 
    width: 100% !important; 
    max-width: 100% !important; 
    margin-left: 0 !important; 
    margin-right: 0 !important; 
    box-sizing: border-box; 
  }
  .neo-table-wrap { padding: 1rem 1.5rem; }
  .neo-table { width: 100%; table-layout: fixed; }
  .neo-table th, .neo-table td { word-wrap: break-word; overflow: hidden; }
  .neo-thumb { max-width: 140px; height: auto; cursor: pointer; border:3px solid #000; box-shadow:4px 4px 0 #000; }
  @media (max-width: 768px) {
    .neo-table-wrap { padding: 0.5rem; }
    .neo-table thead { display: none; }
    .neo-table tr { display: block; margin-bottom: 0.75rem; border-bottom: 1px dashed #ddd; padding-bottom: 0.5rem; }
    .neo-table td { display: block; text-align: left; }
  }
</style>
<body>
<div class="container-scroller">
  <?php include(BASE_PATH . 'app/Template/navbar.php'); ?>
  <div class="container-fluid page-body-wrapper">
    <?php include(BASE_PATH . 'app/Template/sidebar.php'); ?>
    <div class="main-panel">
      <div class="content-wrapper">

        <!-- Page Header -->
        <div class="neo-page-header">
          <div class="neo-page-title">
            <h3><i class="fas fa-images me-2"></i>Manajemen Banner</h3>
            <div class="neo-breadcrumb">Panel &rsaquo; Konten &rsaquo; Banner</div>
          </div>
          <a href="<?= BASE_URL ?>/banner/add" class="neo-btn neo-btn-primary">
            <i class="fas fa-plus"></i> Tambah Banner
          </a>
        </div>

        <!-- Banner Table -->
        <div class="neo-table-wrap">
          <?php if (!empty($banners)): ?>
            <div class="table-responsive">
              <table class="neo-table">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="14%">Gambar</th>
                    <th width="18%">Judul</th>
                    <th width="22%">Deskripsi</th>
                    <th width="17%">Link URL</th>
                    <th width="10%">Status</th>
                    <th width="14%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($banners as $banner): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <?php if ($banner['image_path']): ?>
                        <img src="<?= htmlspecialchars($banner['image_path']) ?>"
                             alt="<?= htmlspecialchars($banner['title']) ?>"
                             class="neo-thumb" width="120" height="60"
                             onclick="showImagePreview('<?= htmlspecialchars($banner['image_path']) ?>', '<?= addslashes($banner['title']) ?>')"
                             title="Klik untuk preview">
                      <?php else: ?>
                        <div class="neo-thumb-placeholder" style="width:120px;height:60px;">
                          <i class="fas fa-image text-muted"></i>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($banner['title']) ?></strong></td>
                    <td>
                      <small><?= htmlspecialchars(substr($banner['description'] ?? '-', 0, 80)) ?><?= strlen($banner['description'] ?? '') > 80 ? '...' : '' ?></small>
                    </td>
                    <td>
                      <?php if ($banner['link_url']): ?>
                        <code><?= htmlspecialchars($banner['link_url']) ?></code>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($banner['is_active']): ?>
                        <span class="neo-badge neo-badge-active">Aktif</span>
                      <?php else: ?>
                        <span class="neo-badge neo-badge-inactive">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="neo-action-group">
                        <button class="neo-btn neo-btn-sm neo-btn-primary" title="Toggle Status"
                                onclick="confirmToggleStatus(<?= $banner['id'] ?>, '<?= addslashes($banner['title']) ?>', <?= $banner['is_active'] ?>)">
                          <i class="fas fa-power-off"></i>
                        </button>
                        <a href="index.php?controller=banner&action=edit&id=<?= $banner['id'] ?>"
                           class="neo-btn neo-btn-sm neo-btn-dark" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="neo-btn neo-btn-sm neo-btn-accent" title="Hapus"
                                onclick="confirmDelete(<?= $banner['id'] ?>, '<?= addslashes($banner['title']) ?>')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="neo-empty-state">
              <i class="fas fa-images empty-icon"></i>
              <h5>Belum Ada Banner</h5>
              <p>Tambahkan banner pertama Anda untuk memulai.</p>
              <a href="<?= BASE_URL ?>/banner/add" class="neo-btn neo-btn-primary">
                <i class="fas fa-plus"></i> Tambah Banner
              </a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Image Preview Modal -->
        <div id="imgPreviewOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:99998;align-items:center;justify-content:center;padding:1rem;">
          <div style="background:#fff;border:4px solid #000;box-shadow:8px 8px 0 #000;max-width:800px;width:100%;position:relative;">
            <div style="background:#FFD600;border-bottom:3px solid #000;padding:0.75rem 1rem;display:flex;justify-content:space-between;align-items:center;">
              <strong id="imgPreviewTitle" style="font-weight:900;text-transform:uppercase;font-style:italic;"></strong>
              <button onclick="closeImagePreview()" style="background:#000;color:#fff;border:none;width:32px;height:32px;font-weight:900;font-size:1rem;cursor:pointer;">✕</button>
            </div>
            <div style="padding:1rem;">
              <img id="imgPreviewSrc" src="" alt="" style="width:100%;display:block;border:3px solid #000;">
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include(BASE_PATH . 'app/Template/script.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  <?php if ($success_message): ?>
    SynAlert.success('Berhasil!', <?= json_encode($success_message) ?>);
  <?php elseif ($error_message): ?>
    SynAlert.error('Gagal!', <?= json_encode($error_message) ?>);
  <?php elseif ($errors && is_array($errors)): ?>
    SynAlert.show({
      type  : 'error',
      title : 'Terjadi Kesalahan!',
      html  : '<ul><?php foreach ($errors as $e): ?><li><?= addslashes(htmlspecialchars($e)) ?></li><?php endforeach; ?></ul>'
    });
  <?php endif; ?>
});

function showImagePreview(path, title) {
  document.getElementById('imgPreviewSrc').src      = path;
  document.getElementById('imgPreviewTitle').textContent = title;
  const el = document.getElementById('imgPreviewOverlay');
  el.style.display = 'flex';
}

function closeImagePreview() {
  document.getElementById('imgPreviewOverlay').style.display = 'none';
}

document.getElementById('imgPreviewOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeImagePreview();
});

function confirmToggleStatus(id, title, currentStatus) {
  const statusText = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
  SynAlert.confirm({
    title       : 'Ubah Status Banner',
    message     : `Yakin ingin ${statusText} banner "${title}"?`,
    type        : 'warning',
    confirmText : `Ya, ${statusText}`,
    onConfirm   : () => window.location.href = `index.php?controller=banner&action=toggleActive&id=${id}`
  });
}

function confirmDelete(id, title) {
  SynAlert.confirm({
    title       : 'Hapus Banner',
    message     : `Yakin ingin menghapus banner "${title}"? Tindakan ini tidak dapat dibatalkan.`,
    type        : 'error',
    confirmText : 'Ya, Hapus',
    onConfirm   : () => window.location.href = `index.php?controller=banner&action=delete&id=${id}`
  });
}
</script>
</body>
</html>

