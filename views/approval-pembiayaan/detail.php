<?php
// Load required models
require_once __DIR__ . '/../../models/DashboardModel.php';

// Flash messages
$flash_error = $_SESSION['flash_error'] ?? null;
$flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>

<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <!-- Page Header -->
              <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-clipboard-check" style="margin-right: 10px;"></i>Review Pengajuan
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=approvalpembiayaan&action=index"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Recommendation Banner -->
              <div style="margin-bottom: 20px; padding: 20px; border-radius: 6px; background: <?= $recommendation['recommendation'] === 'approve' ? '#d1fae5' : ($recommendation['recommendation'] === 'reject' ? '#fee2e2' : '#fef3c7') ?>; border-left: 4px solid <?= $recommendation['recommendation'] === 'approve' ? '#10b981' : ($recommendation['recommendation'] === 'reject' ? '#ef4444' : '#f59e0b') ?>;">
                <div style="display: flex; align-items: center; gap: 15px;">
                  <div style="width: 50px; height: 50px; background: <?= $recommendation['recommendation'] === 'approve' ? '#10b981' : ($recommendation['recommendation'] === 'reject' ? '#ef4444' : '#f59e0b') ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-<?= $recommendation['recommendation'] === 'approve' ? 'check' : ($recommendation['recommendation'] === 'reject' ? 'times' : 'exclamation') ?>-circle" style="font-size: 24px; color: white;"></i>
                  </div>
                  <div style="flex: 1;">
                    <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: <?= $recommendation['recommendation'] === 'approve' ? '#065f46' : ($recommendation['recommendation'] === 'reject' ? '#991b1b' : '#92400e') ?>;">
                      Rekomendasi: <?= ucfirst($recommendation['recommendation']) ?>
                    </h4>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: <?= $recommendation['recommendation'] === 'approve' ? '#065f46' : ($recommendation['recommendation'] === 'reject' ? '#991b1b' : '#92400e') ?>;">
                      <?php foreach ($recommendation['reasons'] as $reason): ?>
                        <li><?= htmlspecialchars($reason) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Informasi Anggota -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #f59e0b; padding-bottom: 10px;">
                  <i class="fas fa-user" style="color: #f59e0b; margin-right: 8px;"></i>Informasi Anggota
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Nama Lengkap</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pengajuan['nama_lengkap']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. Anggota</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pengajuan['no_anggota']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">NIK</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pengajuan['nik']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Status Anggota</label>
                    <span style="background: <?= $pengajuan['status_anggota'] === 'Aktif' ? '#10b981' : '#ef4444' ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: 500;">
                      <?= htmlspecialchars($pengajuan['status_anggota']) ?>
                    </span>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Simpanan</label>
                    <div style="font-size: 15px; font-weight: 600; color: #059669;">
                      <?= ApprovalPembiayaanModel::formatRupiah($pengajuan['total_simpanan_anggota']) ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jumlah Rekening</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1e3a8a;">
                      <?= $pengajuan['jumlah_rekening'] ?> Rekening
                    </div>
                  </div>
                </div>
              </div>

              <!-- Informasi Pembiayaan -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #f59e0b; padding-bottom: 10px;">
                  <i class="fas fa-file-contract" style="color: #f59e0b; margin-right: 8px;"></i>Informasi Pembiayaan
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. Akad</label>
                    <div style="font-family: 'Courier New', monospace; font-size: 15px; font-weight: 600; color: #dc2626;">
                      <?= htmlspecialchars($pengajuan['no_akad']) ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Tanggal Pengajuan</label>
                    <div style="font-size: 14px; color: #1a1a1a;">
                      <?= ApprovalPembiayaanModel::formatTanggalIndo($pengajuan['tanggal_pengajuan']) ?>
                    </div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jenis Akad</label>
                    <div style="font-size: 14px; color: #1a1a1a;">
                      <?= htmlspecialchars($pengajuan['jenis_akad']) ?>
                    </div>
                  </div>
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                  <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 10px;">Keperluan</label>
                  <div style="font-size: 15px; color: #1a1a1a; line-height: 1.6; background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #f59e0b;">
                    <?= nl2br(htmlspecialchars($pengajuan['keperluan'])) ?>
                  </div>
                </div>
              </div>

              <!-- Rincian Biaya -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #f59e0b; padding-bottom: 10px;">
                  <i class="fas fa-calculator" style="color: #f59e0b; margin-right: 8px;"></i>Rincian Biaya
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #1e3a8a;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jumlah Pokok</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">
                      <?= ApprovalPembiayaanModel::formatRupiah($pengajuan['jumlah_pokok']) ?>
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #f59e0b;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Margin Koperasi</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">
                      <?= ApprovalPembiayaanModel::formatRupiah($pengajuan['margin_koperasi']) ?>
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #10b981;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Bayar</label>
                    <div style="font-size: 18px; font-weight: 600; color: #059669;">
                      <?= ApprovalPembiayaanModel::formatRupiah($pengajuan['total_bayar']) ?>
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #6366f1;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Tenor</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">
                      <?= $pengajuan['tenor_bulan'] ?> Bulan
                    </div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #dc2626;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Cicilan / Bulan</label>
                    <div style="font-size: 18px; font-weight: 600; color: #dc2626;">
                      <?= ApprovalPembiayaanModel::formatRupiah($pengajuan['cicilan_per_bulan']) ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Form Approval -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #f59e0b; padding-bottom: 10px;">
                  <i class="fas fa-gavel" style="color: #f59e0b; margin-right: 8px;"></i>Keputusan Approval
                </h3>

                <form method="POST" action="index.php?controller=approvalpembiayaan&action=approve" onsubmit="return confirmSubmit();">
                  <input type="hidden" name="id_pembiayaan" value="<?= $pengajuan['id_pembiayaan'] ?>">

                  <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 12px;">Keputusan Anda <span style="color: #ef4444;">*</span></label>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                      <label style="display: flex; align-items: center; cursor: pointer; padding: 20px 30px; background: #f9fafb; border: 3px solid #d1d5db; border-radius: 8px; transition: all 0.3s ease; flex: 1; min-width: 200px;">
                        <input type="radio" name="status" value="Disetujui" required style="margin-right: 12px; transform: scale(1.3);">
                        <div>
                          <span style="font-weight: 600; font-size: 15px; color: #10b981; display: block;">Setujui</span>
                          <span style="font-size: 12px; color: #6b7280; display: block;">Pembiayaan disetujui & dapat dicairkan</span>
                        </div>
                      </label>
                      <label style="display: flex; align-items: center; cursor: pointer; padding: 20px 30px; background: #f9fafb; border: 3px solid #d1d5db; border-radius: 8px; transition: all 0.3s ease; flex: 1; min-width: 200px;">
                        <input type="radio" name="status" value="Ditolak" required style="margin-right: 12px; transform: scale(1.3);">
                        <div>
                          <span style="font-weight: 600; font-size: 15px; color: #ef4444; display: block;">Tolak</span>
                          <span style="font-size: 12px; color: #6b7280; display: block;">Pembiayaan ditolak</span>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div style="margin-bottom: 25px;">
                    <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Tambahkan catatan untuk anggota..." style="width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($pengajuan['catatan_bendahara'] ?? '') ?></textarea>
                    <div style="font-size: 11px; color: #808080; margin-top: 6px;">
                      <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Catatan akan ditampilkan ke anggota (terutama jika ditolak)
                    </div>
                  </div>

                  <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="submit" style="padding: 14px 35px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);">
                      <i class="fas fa-paper-plane" style="margin-right: 10px;"></i>Submit Keputusan
                    </button>
                    <a href="index.php?controller=approvalpembiayaan&action=index" style="padding: 14px 35px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: 600; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 10px;"></i>Batal
                    </a>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright © <?= date('Y') ?> Koperasi Syariah. All rights reserved.
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    // Show flash messages
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($flash_error): ?>
        alert('GAGAL!\n\n<?= addslashes($flash_error) ?>');
      <?php endif; ?>

      <?php if ($flash_success): ?>
        alert('BERHASIL!\n\n<?= addslashes($flash_success) ?>');
      <?php endif; ?>
    });

    // Radio button styling
    const radioInputs = document.querySelectorAll('input[name="status"]');
    radioInputs.forEach(input => {
      input.addEventListener('change', function() {
        const labels = document.querySelectorAll('label:has(input[name="status"])');
        labels.forEach(label => {
          label.style.borderColor = '#d1d5db';
          label.style.background = '#f9fafb';
        });

        const selectedLabel = this.closest('label');
        if (this.value === 'Disetujui') {
          selectedLabel.style.borderColor = '#10b981';
          selectedLabel.style.background = '#d1fae5';
        } else {
          selectedLabel.style.borderColor = '#ef4444';
          selectedLabel.style.background = '#fee2e2';
        }
      });
    });

    function confirmSubmit() {
      const status = document.querySelector('input[name="status"]:checked');
      if (!status) {
        alert('Pilih keputusan terlebih dahulu!');
        return false;
      }

      const message = status.value === 'Disetujui'
        ? 'Apakah Anda yakin ingin MENYETUJUI pengajuan pembiayaan ini?\n\nPembiayaan akan dicairkan ke anggota.'
        : 'Apakah Anda yakin ingin MENOLAK pengajuan pembiayaan ini?\n\nAnggota akan diberitahu melalui catatan.';

      return confirm(message);
    }
  </script>

  <style>
    a[href*="approvalpembiayaan&action=index"]:hover {
      background: rgba(245, 158, 11, 0.3) !important;
    }

    input[type="radio"]:checked + span {
      font-weight: 700 !important;
    }
  </style>
</body>

</html>
