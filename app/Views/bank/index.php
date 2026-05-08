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
            <h3><i class="fas fa-university me-2"></i>Manajemen Bank</h3>
            <div class="neo-breadcrumb">Panel &rsaquo; Data &rsaquo; Bank</div>
          </div>
          <a href="<?= BASE_URL ?>/bank/add" class="neo-btn neo-btn-primary">
            <i class="fas fa-plus"></i> Tambah Bank
          </a>
        </div>

        <!-- Bank Table -->
        <div class="neo-table-wrap">
          <?php if (!empty($banks)): ?>
            <div class="table-responsive">
              <table class="neo-table">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="13%">Logo</th>
                    <th width="20%">Nama Bank</th>
                    <th width="18%">No. Rekening</th>
                    <th width="20%">Pemilik</th>
                    <th width="10%">Status</th>
                    <th width="14%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($banks as $bank): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <?php if ($bank['bank_logo']): ?>
                        <img src="<?= BASE_URL ?>/uploads/bank/<?= htmlspecialchars($bank['bank_logo']) ?>"
                             alt="<?= htmlspecialchars($bank['bank_name']) ?>"
                             class="neo-thumb" width="60" height="60">
                      <?php else: ?>
                        <div class="neo-thumb-placeholder" style="width:60px;height:60px;">
                          <i class="fas fa-university text-muted"></i>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($bank['bank_name']) ?></strong></td>
                    <td><code><?= htmlspecialchars($bank['account_number']) ?></code></td>
                    <td><?= htmlspecialchars($bank['account_holder']) ?></td>
                    <td>
                      <?php if ($bank['is_active']): ?>
                        <span class="neo-badge neo-badge-active">Aktif</span>
                      <?php else: ?>
                        <span class="neo-badge neo-badge-inactive">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="neo-action-group">
                        <button class="neo-btn neo-btn-sm neo-btn-primary" title="Toggle Status"
                                onclick="confirmToggleStatus(<?= $bank['id'] ?>, '<?= addslashes($bank['bank_name']) ?>', <?= $bank['is_active'] ?>)">
                          <i class="fas fa-power-off"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/bank/edit?id=<?= $bank['id'] ?>"
                           class="neo-btn neo-btn-sm neo-btn-dark" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="neo-btn neo-btn-sm neo-btn-accent" title="Hapus"
                                onclick="confirmDelete(<?= $bank['id'] ?>, '<?= addslashes($bank['bank_name']) ?>')">
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
              <i class="fas fa-university empty-icon"></i>
              <h5>Belum Ada Data Bank</h5>
              <p>Tambahkan rekening bank pertama Anda untuk memulai.</p>
              <a href="<?= BASE_URL ?>/bank/add" class="neo-btn neo-btn-primary">
                <i class="fas fa-plus"></i> Tambah Bank
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

function confirmToggleStatus(id, bankName, currentStatus) {
  const statusText = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
  SynAlert.confirm({
    title       : 'Ubah Status Bank',
    message     : `Yakin ingin ${statusText} bank "${bankName}"?`,
    type        : 'warning',
    confirmText : `Ya, ${statusText}`,
    onConfirm   : () => window.location.href = `<?= BASE_URL ?>/bank/toggleActive?id=${id}`
  });
}

function confirmDelete(id, bankName) {
  SynAlert.confirm({
    title       : 'Hapus Bank',
    message     : `Yakin ingin menghapus bank "${bankName}"? Tindakan ini tidak dapat dibatalkan.`,
    type        : 'error',
    confirmText : 'Ya, Hapus',
    onConfirm   : () => window.location.href = `<?= BASE_URL ?>/bank/delete?id=${id}`
  });
}
</script>
</body>
</html>

