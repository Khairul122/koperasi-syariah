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
<body>
<div class="container-scroller">
  <?php include(BASE_PATH . 'app/Template/navbar.php'); ?>
  <div class="container-fluid page-body-wrapper">
 ?>
    <?php include(BASE_PATH . 'app/Template/sidebar.php'); ?>
    <div class="main-panel">
      <div class="content-wrapper">

        <!-- Page Header -->
        <div class="neo-page-header">
          <div class="neo-page-title">
            <h3><i class="fas fa-briefcase me-2"></i>Manajemen Portofolio</h3>
            <div class="neo-breadcrumb">Panel &rsaquo; Konten &rsaquo; Portofolio</div>
          </div>
          <a href="<?= BASE_URL ?>/portofolio/add" class="neo-btn neo-btn-primary">
            <i class="fas fa-plus"></i> Tambah Portofolio
          </a>
        </div>

        <!-- Table -->
        <div class="neo-table-wrap">
          <?php if (!empty($portfolios)): ?>
            <div class="table-responsive">
              <table class="neo-table">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="13%">Gambar</th>
                    <th width="28%">Judul &amp; Deskripsi</th>
                    <th width="15%">Kategori</th>
                    <th width="15%">Tanggal</th>
                    <th width="10%">Status</th>
                    <th width="14%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($portfolios as $portfolio): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <?php if (!empty($portfolio['image_path']) && file_exists($portfolio['image_path'])): ?>
                        <img src="<?= htmlspecialchars($portfolio['image_path']) ?>"
                             alt="<?= htmlspecialchars($portfolio['title']) ?>"
                             class="neo-thumb" width="80" height="80">
                      <?php else: ?>
                        <div class="neo-thumb-placeholder" style="width:80px;height:80px;">
                          <i class="fas fa-image text-muted"></i>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <strong class="d-block"><?= htmlspecialchars($portfolio['title']) ?></strong>
                      <small class="text-muted"><?= htmlspecialchars(substr($portfolio['description'] ?? '-', 0, 80)) ?><?= strlen($portfolio['description'] ?? '') > 80 ? '...' : '' ?></small>
                    </td>
                    <td>
                      <?php if (!empty($portfolio['category'])): ?>
                        <span class="neo-badge neo-badge-info"><?= htmlspecialchars($portfolio['category']) ?></span>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <small class="fw-bold">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <?= date('d M Y', strtotime($portfolio['created_at'])) ?>
                      </small>
                    </td>
                    <td>
                      <?php if (!empty($portfolio['is_active'])): ?>
                        <span class="neo-badge neo-badge-active">Aktif</span>
                      <?php else: ?>
                        <span class="neo-badge neo-badge-inactive">Draft</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="neo-action-group">
                        <a href="index.php?controller=portofolio&action=edit&id=<?= $portfolio['id'] ?>"
                           class="neo-btn neo-btn-sm neo-btn-dark" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="neo-btn neo-btn-sm neo-btn-accent" title="Hapus"
                                onclick="confirmDelete(<?= $portfolio['id'] ?>, '<?= addslashes($portfolio['title']) ?>')">
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
              <i class="fas fa-briefcase empty-icon"></i>
              <h5>Belum Ada Portofolio</h5>
              <p>Tambahkan portofolio pertama Anda untuk memulai.</p>
              <a href="<?= BASE_URL ?>/portofolio/add" class="neo-btn neo-btn-primary">
                <i class="fas fa-plus"></i> Tambah Portofolio
              </a>
            </div>
          <?php endif; ?>
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

function confirmDelete(id, title) {
  SynAlert.confirm({
    title       : 'Hapus Portofolio',
    message     : `Yakin ingin menghapus portofolio "${title}"? Tindakan ini tidak dapat dibatalkan.`,
    type        : 'error',
    confirmText : 'Ya, Hapus',
    onConfirm   : () => window.location.href = `index.php?controller=portofolio&action=delete&id=${id}`
  });
}
</script>
</body>
</html>

