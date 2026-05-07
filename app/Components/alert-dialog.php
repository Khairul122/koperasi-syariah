<?php
/**
 * SynAlert Component Loader
 *
 * Include this partial in any view that needs the neobrutalist alert dialog.
 * The component loads its own CSS + JS, so it is safe to include multiple times
 * (a guard prevents duplicate script injection).
 *
 * Usage in a view:
 *   <?php include BASE_PATH . 'app/Components/alert-dialog.php'; ?>
 *
 * Then call from JavaScript:
 *   SynAlert.success('Berhasil!', 'Data telah disimpan.');
 *   SynAlert.error('Gagal', 'Terjadi kesalahan. Coba lagi.');
 *   SynAlert.warning('Perhatian', 'Aksi ini tidak dapat dibatalkan.');
 *   SynAlert.loading('Menyimpan...', 'Mohon tunggu sebentar.');
 *   SynAlert.info('Info', 'Informasi tambahan.');
 *   SynAlert.close();
 *
 *   // Full options:
 *   SynAlert.show({
 *       type             : 'success',   // success | error | warning | loading | info
 *       title            : 'Judul',
 *       message          : 'Pesan...',
 *       onClose          : () => { ... },   // optional callback
 *       dismissOnOverlay : true,            // optional, default true
 *   });
 */
?>
<?php if (!defined('SYNAERT_LOADED')): define('SYNAERT_LOADED', true); ?>
<link  rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components/alert-dialog.css">
<script src="<?= BASE_URL ?>/assets/js/components/alert-dialog.js"></script>
<?php endif; ?>
