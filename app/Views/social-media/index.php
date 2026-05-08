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
.neo-platform-icon.facebook  { background: #FFD600; color: #000; }
.neo-platform-icon.instagram { background: #FF4081; color: #fff; }
.neo-platform-icon.twitter   { background: #000;    color: #fff; }
.neo-platform-icon.linkedin  { background: #FFD600; color: #000; }
.neo-platform-icon.youtube   { background: #FF4081; color: #fff; }
.neo-platform-icon.tiktok    { background: #000;    color: #fff; }
.neo-platform-icon.telegram  { background: #FFD600; color: #000; }
.neo-platform-icon.whatsapp  { background: #A3E635; color: #000; }
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
            <h3><i class="fas fa-share-alt me-2"></i>Manajemen Social Media</h3>
            <div class="neo-breadcrumb">Panel &rsaquo; Data &rsaquo; Social Media</div>
          </div>
          <a href="<?= BASE_URL ?>/social-media/add" class="neo-btn neo-btn-primary">
            <i class="fas fa-plus"></i> Tambah Social Media
          </a>
        </div>

        <!-- Table -->
        <div class="neo-table-wrap">
          <?php if (!empty($socialMedias)): ?>
            <div class="table-responsive">
              <table class="neo-table">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="8%">Icon</th>
                    <th width="18%">Platform</th>
                    <th width="22%">Nama Akun</th>
                    <th width="23%">URL Profil</th>
                    <th width="10%">Status</th>
                    <th width="14%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($socialMedias as $sm):
                    $iconClass  = 'default';
                    $faIcon     = $sm['icon'] ?? 'fa-share-alt';
                    $pl         = strtolower($sm['platform_name']);
                    if (str_contains($pl, 'facebook') || str_contains($pl, 'fb'))       { $iconClass = 'facebook';  $faIcon = 'fa-facebook'; }
                    elseif (str_contains($pl, 'instagram') || str_contains($pl, 'ig')) { $iconClass = 'instagram'; $faIcon = 'fa-instagram'; }
                    elseif (str_contains($pl, 'twitter') || str_contains($pl, 'x.com')){ $iconClass = 'twitter';   $faIcon = 'fa-twitter'; }
                    elseif (str_contains($pl, 'linkedin'))                              { $iconClass = 'linkedin';  $faIcon = 'fa-linkedin'; }
                    elseif (str_contains($pl, 'youtube'))                               { $iconClass = 'youtube';   $faIcon = 'fa-youtube'; }
                    elseif (str_contains($pl, 'tiktok'))                                { $iconClass = 'tiktok';    $faIcon = 'fa-tiktok'; }
                    elseif (str_contains($pl, 'telegram'))                              { $iconClass = 'telegram';  $faIcon = 'fa-telegram'; }
                    elseif (str_contains($pl, 'whatsapp') || str_contains($pl, 'wa'))  { $iconClass = 'whatsapp';  $faIcon = 'fa-whatsapp'; }
                  ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <div class="neo-platform-icon <?= $iconClass ?>">
                        <i class="fab <?= $faIcon ?>"></i>
                      </div>
                    </td>
                    <td><strong><?= htmlspecialchars($sm['platform_name']) ?></strong></td>
                    <td><?= htmlspecialchars($sm['account_name']) ?></td>
                    <td><code><?= htmlspecialchars($sm['profile_url']) ?></code></td>
                    <td>
                      <?php if ($sm['is_active']): ?>
                        <span class="neo-badge neo-badge-active">Aktif</span>
                      <?php else: ?>
                        <span class="neo-badge neo-badge-inactive">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="neo-action-group">
                        <button class="neo-btn neo-btn-sm neo-btn-primary" title="Toggle Status"
                                onclick="confirmToggleStatus(<?= $sm['id'] ?>, '<?= addslashes($sm['platform_name']) ?>', <?= $sm['is_active'] ?>)">
                          <i class="fas fa-power-off"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/social-media/edit?id=<?= $sm['id'] ?>"
                           class="neo-btn neo-btn-sm neo-btn-dark" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="neo-btn neo-btn-sm neo-btn-accent" title="Hapus"
                                onclick="confirmDelete(<?= $sm['id'] ?>, '<?= addslashes($sm['platform_name']) ?>')">
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
              <i class="fas fa-share-alt empty-icon"></i>
              <h5>Belum Ada Social Media</h5>
              <p>Tambahkan akun social media pertama Anda untuk memulai.</p>
              <a href="<?= BASE_URL ?>/social-media/add" class="neo-btn neo-btn-primary">
                <i class="fas fa-plus"></i> Tambah Social Media
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
    title       : 'Ubah Status Social Media',
    message     : `Yakin ingin ${statusText} "${platform}"?`,
    type        : 'warning',
    confirmText : `Ya, ${statusText}`,
    onConfirm   : () => window.location.href = `<?= BASE_URL ?>/social-media/toggleActive?id=${id}`
  });
}

function confirmDelete(id, platform) {
  SynAlert.confirm({
    title       : 'Hapus Social Media',
    message     : `Yakin ingin menghapus "${platform}"? Tindakan ini tidak dapat dibatalkan.`,
    type        : 'error',
    confirmText : 'Ya, Hapus',
    onConfirm   : () => window.location.href = `<?= BASE_URL ?>/social-media/delete?id=${id}`
  });
}
</script>
</body>
</html>

