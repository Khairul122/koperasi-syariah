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
.neo-platform-icon {
    width: 48px; height: 48px;
    border: 3px solid #000;
    box-shadow: 3px 3px 0 #000;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.neo-platform-icon.whatsapp  { background: #A3E635; color: #000; }
.neo-platform-icon.instagram { background: #FF4081; color: #fff; }
.neo-platform-icon.facebook  { background: #FFD600; color: #000; }
.neo-platform-icon.email     { background: #000;    color: #fff; }
.neo-platform-icon.phone     { background: #A3E635; color: #000; }
.neo-platform-icon.default   { background: #F0F0F0; color: #000; }
</style>

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
            <h3><i class="fas fa-address-book me-2"></i>Manajemen Kontak Person</h3>
            <div class="neo-breadcrumb">Panel &rsaquo; Data &rsaquo; Kontak</div>
          </div>
          <a href="index.php?controller=contactPerson&action=create" class="neo-btn neo-btn-primary">
            <i class="fas fa-plus"></i> Tambah Kontak
          </a>
        </div>

        <!-- Table -->
        <div class="neo-table-wrap">
          <?php if (!empty($contactPersons)): ?>
            <div class="table-responsive">
              <table class="neo-table">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="8%">Icon</th>
                    <th width="18%">Platform</th>
                    <th width="22%">Info Kontak</th>
                    <th width="27%">Link URL</th>
                    <th width="10%">Status</th>
                    <th width="10%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($contactPersons as $cp):
                    $iconClass = 'default';
                    $faIcon    = $cp['icon'] ?? 'fa-link';
                    $pl        = strtolower($cp['platform']);
                    if (str_contains($pl, 'whatsapp') || str_contains($pl, 'wa')) {
                        $iconClass = 'whatsapp'; $faIcon = 'fa-whatsapp';
                    } elseif (str_contains($pl, 'instagram') || str_contains($pl, 'ig')) {
                        $iconClass = 'instagram'; $faIcon = 'fa-instagram';
                    } elseif (str_contains($pl, 'facebook') || str_contains($pl, 'fb')) {
                        $iconClass = 'facebook'; $faIcon = 'fa-facebook';
                    } elseif (str_contains($pl, 'email') || str_contains($pl, 'mail')) {
                        $iconClass = 'email'; $faIcon = 'fa-envelope';
                    } elseif (str_contains($pl, 'phone') || str_contains($pl, 'telepon')) {
                        $iconClass = 'phone'; $faIcon = 'fa-phone';
                    }
                  ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <div class="neo-platform-icon <?= $iconClass ?>">
                        <i class="fab <?= $faIcon ?>"></i>
                      </div>
                    </td>
                    <td><strong><?= htmlspecialchars($cp['platform']) ?></strong></td>
                    <td><code><?= htmlspecialchars($cp['contact_info']) ?></code></td>
                    <td><code><?= htmlspecialchars($cp['link_url']) ?></code></td>
                    <td>
                      <?php if ($cp['is_active']): ?>
                        <span class="neo-badge neo-badge-active">Aktif</span>
                      <?php else: ?>
                        <span class="neo-badge neo-badge-inactive">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="neo-action-group">
                        <button class="neo-btn neo-btn-sm neo-btn-primary" title="Toggle Status"
                                onclick="confirmToggleStatus(<?= $cp['id'] ?>, '<?= addslashes($cp['platform']) ?>', <?= $cp['is_active'] ?>)">
                          <i class="fas fa-power-off"></i>
                        </button>
                        <a href="index.php?controller=contactPerson&action=edit&id=<?= $cp['id'] ?>"
                           class="neo-btn neo-btn-sm neo-btn-dark" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="neo-btn neo-btn-sm neo-btn-accent" title="Hapus"
                                onclick="confirmDelete(<?= $cp['id'] ?>, '<?= addslashes($cp['platform']) ?>')">
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
              <i class="fas fa-address-book empty-icon"></i>
              <h5>Belum Ada Kontak Person</h5>
              <p>Tambahkan kontak person pertama Anda untuk memulai.</p>
              <a href="index.php?controller=contactPerson&action=create" class="neo-btn neo-btn-primary">
                <i class="fas fa-plus"></i> Tambah Kontak
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

function confirmToggleStatus(id, platform, currentStatus) {
  const statusText = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
  SynAlert.confirm({
    title       : 'Ubah Status Kontak',
    message     : `Yakin ingin ${statusText} kontak "${platform}"?`,
    type        : 'warning',
    confirmText : `Ya, ${statusText}`,
    onConfirm   : () => window.location.href = `index.php?controller=contactPerson&action=toggleActive&id=${id}`
  });
}

function confirmDelete(id, platform) {
  SynAlert.confirm({
    title       : 'Hapus Kontak',
    message     : `Yakin ingin menghapus kontak "${platform}"? Tindakan ini tidak dapat dibatalkan.`,
    type        : 'error',
    confirmText : 'Ya, Hapus',
    onConfirm   : () => window.location.href = `index.php?controller=contactPerson&action=delete&id=${id}`
  });
}
</script>
</body>
</html>

