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
              <div style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-file-invoice-dollar" style="margin-right: 10px;"></i>Detail Pembiayaan
                    </h2>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=ajukanpembiayaan&action=adminIndex"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Status Badge -->
              <div style="margin-bottom: 20px;">
                <?php
                $statusColor = [
                    'Pending' => '#f59e0b',
                    'Disetujui' => '#10b981',
                    'Ditolak' => '#ef4444',
                    'Lunas' => '#059669'
                ];
                $color = $statusColor[$pembiayaan['status']] ?? '#6b7280';
                ?>
                <span style="background: <?= $color ?>; color: white; padding: 8px 16px; border-radius: 4px; font-size: 14px; font-weight: 500; display: inline-block;">
                  Status: <?= htmlspecialchars($pembiayaan['status']) ?>
                </span>
              </div>

              <!-- Informasi Anggota -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                  <i class="fas fa-user" style="color: #1e3a8a; margin-right: 8px;"></i>Informasi Anggota
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Nama Lengkap</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['nama_lengkap']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. Anggota</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['no_anggota']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">NIK</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['nik']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. HP</label>
                    <div style="font-size: 15px; font-weight: 600; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['no_hp']) ?></div>
                  </div>
                  <div style="grid-column: 1 / -1;">
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Alamat</label>
                    <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['alamat']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Pekerjaan</label>
                    <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['pekerjaan']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Status Anggota</label>
                    <span style="background: <?= $pembiayaan['status_anggota'] === 'Aktif' ? '#10b981' : '#ef4444' ?>; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: 500;">
                      <?= htmlspecialchars($pembiayaan['status_anggota']) ?>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Informasi Pembiayaan -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                  <i class="fas fa-file-contract" style="color: #1e3a8a; margin-right: 8px;"></i>Informasi Pembiayaan
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">No. Akad</label>
                    <div style="font-family: 'Courier New', monospace; font-size: 15px; font-weight: 600; color: #1e3a8a;"><?= htmlspecialchars($pembiayaan['no_akad']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Tanggal Pengajuan</label>
                    <div style="font-size: 14px; color: #1a1a1a;"><?= DashboardModel::formatDateIndo($pembiayaan['tanggal_pengajuan']) ?></div>
                  </div>
                  <div>
                    <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jenis Akad</label>
                    <div style="font-size: 14px; color: #1a1a1a;"><?= htmlspecialchars($pembiayaan['jenis_akad']) ?></div>
                  </div>
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                  <label style="font-size: 12px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 10px;">Keperluan</label>
                  <div style="font-size: 15px; color: #1a1a1a; line-height: 1.6; background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #1e3a8a;">
                    <?= nl2br(htmlspecialchars($pembiayaan['keperluan'])) ?>
                  </div>
                </div>
              </div>

              <!-- Rincian Biaya -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                  <i class="fas fa-calculator" style="color: #1e3a8a; margin-right: 8px;"></i>Rincian Biaya
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #1e3a8a;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Jumlah Pokok</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">Rp <?= number_format($pembiayaan['jumlah_pokok'], 0, ',', '.') ?></div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #f59e0b;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Margin Koperasi</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;">Rp <?= number_format($pembiayaan['margin_koperasi'], 0, ',', '.') ?></div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #10b981;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Total Bayar</label>
                    <div style="font-size: 18px; font-weight: 600; color: #059669;">Rp <?= number_format($pembiayaan['total_bayar'], 0, ',', '.') ?></div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #6366f1;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Tenor</label>
                    <div style="font-size: 18px; font-weight: 600; color: #1a1a1a;"><?= $pembiayaan['tenor_bulan'] ?> Bulan</div>
                  </div>
                  <div style="background: #f9fafb; padding: 15px; border-radius: 4px; border-left: 4px solid #dc2626;">
                    <label style="font-size: 11px; color: #808080; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px;">Cicilan / Bulan</label>
                    <div style="font-size: 18px; font-weight: 600; color: #dc2626;">Rp <?= number_format($pembiayaan['cicilan_per_bulan'], 0, ',', '.') ?></div>
                  </div>
                </div>
              </div>

              <!-- Catatan Bendahara (jika ada) -->
              <?php if (!empty($pembiayaan['catatan_bendahara'])): ?>
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                    <i class="fas fa-sticky-note" style="color: #1e3a8a; margin-right: 8px;"></i>Catatan Bendahara
                  </h3>
                  <div style="background: #fef3c7; padding: 15px; border-radius: 4px; border-left: 4px solid #f59e0b;">
                    <div style="font-size: 14px; color: #92400e; line-height: 1.6;">
                      <?= nl2br(htmlspecialchars($pembiayaan['catatan_bendahara'])) ?>
                    </div>
                    <?php if (!empty($pembiayaan['nama_petugas_acc'])): ?>
                      <div style="margin-top: 10px; font-size: 12px; color: #78716c; font-weight: 500;">
                        <i class="fas fa-user-check"></i> <?= htmlspecialchars($pembiayaan['nama_petugas_acc']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Progress Angsuran (hanya jika status Disetujui atau Lunas) -->
              <?php if (in_array($pembiayaan['status'], ['Disetujui', 'Lunas'])): ?>
                <?php
                $totalAngsuranDibayar = $totalAngsuran['total_angsuran'] ?? 0;
                $sisaAngsuran = $pembiayaan['tenor_bulan'] - $totalAngsuranDibayar;
                $totalDibayar = $totalAngsuran['total_dibayar'] ?? 0;
                $sisaTagihan = $pembiayaan['total_bayar'] - $totalDibayar;
                $progressPersen = ($totalAngsuranDibayar / $pembiayaan['tenor_bulan']) * 100;
                ?>

                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                    <i class="fas fa-chart-line" style="color: #1e3a8a; margin-right: 8px;"></i>Progress Angsuran
                  </h3>

                  <!-- Summary Cards -->
                  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 20px; border-radius: 6px;">
                      <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Total Angsuran</label>
                      <div style="font-size: 24px; font-weight: 600;"><?= $pembiayaan['tenor_bulan'] ?> Kali</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border-radius: 6px;">
                      <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Sudah Dibayar</label>
                      <div style="font-size: 24px; font-weight: 600;"><?= $totalAngsuranDibayar ?> Kali</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px; border-radius: 6px;">
                      <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Sisa Tagihan</label>
                      <div style="font-size: 24px; font-weight: 600;">Rp <?= number_format($sisaTagihan, 0, ',', '.') ?></div>
                    </div>
                    <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 20px; border-radius: 6px;">
                      <label style="font-size: 11px; text-transform: uppercase; font-weight: 500; display: block; margin-bottom: 5px; opacity: 0.9;">Progress</label>
                      <div style="font-size: 24px; font-weight: 600;"><?= round($progressPersen, 1) ?>%</div>
                    </div>
                  </div>

                  <!-- Progress Bar -->
                  <div style="margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                      <span style="font-size: 13px; color: #6b7280;">Progress Pembayaran</span>
                      <span style="font-size: 13px; font-weight: 600; color: #1e3a8a;"><?= $totalAngsuranDibayar ?> / <?= $pembiayaan['tenor_bulan'] ?> angsuran</span>
                    </div>
                    <div style="width: 100%; background: #e5e7eb; border-radius: 10px; height: 20px; overflow: hidden;">
                      <div style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); height: 100%; width: <?= round($progressPersen, 1) ?>%; transition: width 0.5s ease;"></div>
                    </div>
                  </div>

                  <!-- Tabel Riwayat Angsuran -->
                  <?php if (!empty($riwayatAngsuran)): ?>
                    <div style="overflow-x: auto;">
                      <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                          <tr style="background: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                            <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Ke-</th>
                            <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Tanggal Bayar</th>
                            <th style="padding: 12px 15px; text-align: right; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Jumlah Bayar</th>
                            <th style="padding: 12px 15px; text-align: right; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Denda</th>
                            <th style="padding: 12px 15px; text-align: right; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Total Bayar</th>
                            <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #1a1a1a; text-transform: uppercase;">Petugas</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($riwayatAngsuran as $angsuran): ?>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                              <td style="padding: 12px 15px;">
                                <span style="background: #1e3a8a; color: white; padding: 3px 8px; border-radius: 3px; font-weight: 600; font-size: 11px;">
                                  <?= $angsuran['angsuran_ke'] ?>
                                </span>
                              </td>
                              <td style="padding: 12px 15px; color: #1a1a1a;">
                                <?= DashboardModel::formatDateIndo($angsuran['tanggal_bayar']) ?>
                              </td>
                              <td style="padding: 12px 15px; text-align: right; color: #1a1a1a;">
                                Rp <?= number_format($angsuran['jumlah_bayar'], 0, ',', '.') ?>
                              </td>
                              <td style="padding: 12px 15px; text-align: right; color: #ef4444;">
                                <?php if ($angsuran['denda'] > 0): ?>
                                  <span style="color: #ef4444; font-weight: 500;">+Rp <?= number_format($angsuran['denda'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                  <span style="color: #10b981;">-</span>
                                <?php endif; ?>
                              </td>
                              <td style="padding: 12px 15px; text-align: right; font-weight: 600; color: #059669;">
                                Rp <?= number_format($angsuran['jumlah_bayar'] + $angsuran['denda'], 0, ',', '.') ?>
                              </td>
                              <td style="padding: 12px 15px; color: #6b7280;">
                                <?= htmlspecialchars($angsuran['nama_petugas'] ?? '-') ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php else: ?>
                    <div style="text-align: center; padding: 30px; color: #808080;">
                      <i class="fas fa-inbox" style="font-size: 36px; margin-bottom: 10px; color: #b0b0b0;"></i>
                      <p style="margin: 0; font-size: 14px;">Belum ada riwayat angsuran</p>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <!-- Form Approval (hanya jika status Pending) -->
              <?php if ($pembiayaan['status'] === 'Pending'): ?>
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                    <i class="fas fa-clipboard-check" style="color: #1e3a8a; margin-right: 8px;"></i>Verifikasi Pengajuan
                  </h3>

                  <!-- Info Box -->
                  <div style="background: #eff6ff; padding: 15px; border-radius: 4px; border-left: 4px solid #1e3a8a; margin-bottom: 20px;">
                    <div style="font-size: 14px; color: #1e3a8a; line-height: 1.6;">
                      <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                      Silakan review data pembiayaan di atas. Jika semua data sudah benar, Anda dapat menyetujui atau menolak pengajuan ini.
                    </div>
                  </div>

                  <form method="POST" action="index.php?controller=ajukanpembiayaan&action=updateStatus" onsubmit="return confirmSubmit();">
                    <input type="hidden" name="id_pembiayaan" value="<?= $pembiayaan['id_pembiayaan'] ?>">

                    <div style="margin-bottom: 20px;">
                      <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Keputusan <span style="color: #ef4444;">*</span></label>
                      <div style="display: flex; gap: 15px;">
                        <label style="display: flex; align-items: center; cursor: pointer; padding: 12px 20px; background: #f9fafb; border: 2px solid #d1d5db; border-radius: 6px; transition: all 0.3s ease; flex: 1;">
                          <input type="radio" name="status" value="Disetujui" required style="margin-right: 10px;">
                          <span style="font-weight: 500; color: #10b981;"><i class="fas fa-check-circle" style="margin-right: 5px;"></i>Setujui</span>
                        </label>
                        <label style="display: flex; align-items: center; cursor: pointer; padding: 12px 20px; background: #f9fafb; border: 2px solid #d1d5db; border-radius: 6px; transition: all 0.3s ease; flex: 1;">
                          <input type="radio" name="status" value="Ditolak" required style="margin-right: 10px;">
                          <span style="font-weight: 500; color: #ef4444;"><i class="fas fa-times-circle" style="margin-right: 5px;"></i>Tolak</span>
                        </label>
                      </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                      <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Catatan (Opsional)</label>
                      <textarea name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..." style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
                      <div style="font-size: 11px; color: #808080; margin-top: 5px;">
                        <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Catatan akan ditampilkan ke anggota jika pengajuan ditolak
                      </div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                      <button type="submit" style="padding: 12px 30px; background: #1e3a8a; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center;">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>Simpan Keputusan
                      </button>
                      <a href="index.php?controller=ajukanpembiayaan&action=adminIndex" style="padding: 12px 30px; background: #6b7280; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                        <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Batal
                      </a>
                    </div>
                  </form>
                </div>

                <style>
                  input[type="radio"]:checked + span {
                    font-weight: 600 !important;
                  }
                  input[type="radio"]:checked + span::before {
                    content: '✓ ';
                  }
                  label:has(input[type="radio"]:checked) {
                    border-color: #1e3a8a !important;
                    background: #eff6ff !important;
                  }
                  label:has(input[type="radio"][value="Disetujui"]:checked) {
                    border-color: #10b981 !important;
                    background: #d1fae5 !important;
                  }
                  label:has(input[type="radio"][value="Ditolak"]:checked) {
                    border-color: #ef4444 !important;
                    background: #fee2e2 !important;
                  }
                </style>

                <script>
                  function confirmSubmit() {
                    const status = document.querySelector('input[name="status"]:checked');
                    if (!status) {
                      alert('Pilih keputusan terlebih dahulu!');
                      return false;
                    }

                    const message = status.value === 'Disetujui'
                      ? 'Apakah Anda yakin ingin MENYETUJUI pengajuan pembiayaan ini?'
                      : 'Apakah Anda yakin ingin MENOLAK pengajuan pembiayaan ini?';

                    return confirm(message);
                  }
                </script>
              <?php endif; ?>

              <!-- Info Message untuk status selain Pending -->
              <?php if ($pembiayaan['status'] !== 'Pending' && !empty($pembiayaan['nama_petugas_acc'])): ?>
                <div style="background: #f9fafb; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
                  <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: <?= $pembiayaan['status'] === 'Disetujui' || $pembiayaan['status'] === 'Lunas' ? '#10b981' : '#ef4444' ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                      <i class="fas <?= $pembiayaan['status'] === 'Disetujui' || $pembiayaan['status'] === 'Lunas' ? 'fa-check' : 'fa-times' ?>" style="font-size: 24px; color: white;"></i>
                    </div>
                    <div style="flex: 1;">
                      <div style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 5px;">
                        Pengajuan telah <?= strtolower($pembiayaan['status']) ?> oleh <?= htmlspecialchars($pembiayaan['nama_petugas_acc']) ?>
                      </div>
                      <div style="font-size: 13px; color: #6b7280;">
                        Username: <?= htmlspecialchars($pembiayaan['username_petugas_acc'] ?? '-') ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

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
  </script>

  <style>
    a[href*="adminIndex"]:hover {
      background: rgba(30, 64, 175, 0.3) !important;
    }
  </style>
</body>

</html>
