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
                      <i class="fas fa-plus" style="margin-right: 10px;"></i>Formulir Pengajuan Pinjaman
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                   
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=ajukanpinjaman&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Form Card -->
                <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                  <form method="POST" action="index.php?controller=ajukanpinjaman&action=store" id="pinjamanForm">
                    <!-- Keperluan -->
                    <div style="margin-bottom: 25px;">
                      <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                        Keperluan Pinjaman <span style="color: #ef4444;">*</span>
                      </label>
                      <textarea name="keperluan"
                                id="keperluan"
                                rows="4"
                                placeholder="Jelaskan keperluan dana pinjaman Anda secara detail..."
                                required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; resize: vertical; transition: border-color 0.3s ease;"><?= htmlspecialchars($data['keperluan']) ?></textarea>
                      <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                        Contoh: Modal usaha warung, renovasi rumah, biaya pendidikan, dll.
                      </div>
                    </div>

                    <!-- Jenis Akad -->
                    <div style="margin-bottom: 25px;">
                      <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                        Jenis Akad <span style="color: #ef4444;">*</span>
                      </label>
                      <select name="jenis_akad"
                              id="jenis_akad"
                              required
                              style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; background-color: white; transition: border-color 0.3s ease;">
                        <option value="">-- Pilih Jenis Akad --</option>
                        <option value="Murabahah" <?= $data['jenis_akad'] === 'Murabahah' ? 'selected' : '' ?>>Murabahah (Jual Beli)</option>
                        <option value="Mudharabah" <?= $data['jenis_akad'] === 'Mudharabah' ? 'selected' : '' ?>>Mudharabah (Bagi Hasil)</option>
                        <option value="Murabahah" <?= $data['jenis_akad'] === 'Musyarakah' ? 'selected' : '' ?>>Musyarakah (Kemitraan)</option>
                        <option value="Ijaroh" <?= $data['jenis_akad'] === 'Ijaroh' ? 'selected' : '' ?>>Ijaroh (Sewa)</option>
                      </select>
                      <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                        Pilih jenis akad pembiayaan yang sesuai dengan kebutuhan Anda
                      </div>
                    </div>

                    <!-- Jumlah Pokok -->
                    <div style="margin-bottom: 25px;">
                      <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                        Jumlah Pinjaman (Rp) <span style="color: #ef4444;">*</span>
                      </label>
                      <div style="position: relative;">
                        <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-weight: 600; color: #808080;">Rp</span>
                        <input type="text"
                               name="jumlah_pokok"
                               id="jumlah_pokok"
                               value="<?= $data['jumlah_pokok'] ? number_format($data['jumlah_pokok'], 0, ',', '.') : '' ?>"
                               placeholder="0"
                               required
                               style="width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; transition: border-color 0.3s ease;">
                      </div>
                      <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                        Minimal: Rp 500.000 | Maksimal: Rp 50.000.000
                      </div>
                    </div>

                    <!-- Margin Koperasi -->
                    <div style="margin-bottom: 25px;">
                      <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                        Margin Koperasi (%) <span style="color: #ef4444;">*</span>
                      </label>
                      <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="number"
                               name="margin_persen"
                               id="margin_persen"
                               value="<?= $data['margin_koperasi'] ?? '10' ?>"
                               min="0"
                               max="100"
                               step="0.1"
                               required
                               style="flex: 1; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; transition: border-color 0.3s ease;">
                        <span style="font-size: 14px; font-weight: 600; color: #808080;">%</span>
                      </div>
                      <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                        Margin bagi hasil untuk koperasi (default 10%)
                      </div>
                    </div>

                    <!-- Tenor -->
                    <div style="margin-bottom: 30px;">
                      <label style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                        Jangka Waktu (Tenor) <span style="color: #ef4444;">*</span>
                      </label>
                      <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="number"
                               name="tenor_bulan"
                               id="tenor_bulan"
                               value="<?= $data['tenor_bulan'] ?? '12' ?>"
                               min="1"
                               max="60"
                               required
                               style="flex: 1; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; transition: border-color 0.3s ease;">
                        <span style="font-size: 14px; font-weight: 600; color: #808080;">bulan</span>
                      </div>
                      <div style="font-size: 12px; color: #808080; margin-top: 5px;">
                        Pilih jangka waktu pengembalian (1-60 bulan)
                      </div>
                    </div>

                    <!-- Calculation Preview -->
                    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; padding: 20px; margin-bottom: 30px;">
                      <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #0369a1;">
                        <i class="fas fa-calculator" style="margin-right: 8px;"></i>Simulasi Angsuran
                      </h4>
                      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px;">
                        <div>
                          <span style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 500;">Jumlah Pokok</span>
                          <div id="preview_pokok" style="font-weight: 600; color: #0f172a; font-size: 16px;">Rp 0</div>
                        </div>
                        <div>
                          <span style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 500;">Margin Koperasi</span>
                          <div id="preview_margin" style="font-weight: 600; color: #dc2626; font-size: 16px;">Rp 0</div>
                        </div>
                        <div>
                          <span style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 500;">Total Pembayaran</span>
                          <div id="preview_total" style="font-weight: 600; color: #0369a1; font-size: 16px;">Rp 0</div>
                        </div>
                        <div>
                          <span style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 500;">Cicilan per Bulan</span>
                          <div id="preview_cicilan" style="font-weight: 600; color: #059669; font-size: 16px;">Rp 0</div>
                        </div>
                      </div>
                    </div>

                    <!-- Form Actions -->
                    <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                      <a href="index.php?controller=ajukanpinjaman&action=index"
                         style="padding: 12px 28px; background: #64748b; color: white; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-times" style="margin-right: 8px;"></i>Batal
                      </a>
                      <button type="submit"
                              id="submitBtn"
                              style="padding: 12px 28px; background: #1e3a8a; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>Ajukan Permohonan
                      </button>
                    </div>
                  </form>
                </div>

                <!-- Information Card -->
                <div>
                  <!-- Syarat & Ketentuan -->
                  <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <div style="display: flex; align-items: start; gap: 15px; margin-bottom: 15px;">
                      <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-info-circle" style="font-size: 18px; color: white;"></i>
                      </div>
                      <div>
                        <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #92400e;">Syarat Pengajuan</h4>
                      </div>
                    </div>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #1a1a1a; line-height: 1.6;">
                      <li style="margin-bottom: 5px;">Status keanggotaan AKTIF</li>
                      <li style="margin-bottom: 5px;">Memiliki simpanan pokok & wajib</li>
                      <li style="margin-bottom: 5px;">Tidak memiliki pinjaman macet</li>
                      <li style="margin-bottom: 5px;">Melampirkan jaminan (jika diperlukan)</li>
                    </ul>
                  </div>

                  <!-- Proses Pengajuan -->
                  <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #15803d;">
                      <i class="fas fa-steps" style="margin-right: 8px;"></i>Proses Pengajuan
                    </h4>
                    <div style="font-size: 13px; color: #1a1a1a; line-height: 1.8;">
                      <div style="margin-bottom: 10px; padding-left: 20px; position: relative;">
                        <span style="position: absolute; left: 0; top: 0; width: 16px; height: 16px; background: #15803d; color: white; border-radius: 50%; font-size: 11px; font-weight: 600; display: flex; align-items: center; justify-content: center;">1</span>
                        <strong>Ajukan Formulir</strong><br>
                        <span style="color: #64748b;">Isi formulir pengajuan</span>
                      </div>
                      <div style="margin-bottom: 10px; padding-left: 20px; position: relative;">
                        <span style="position: absolute; left: 0; top: 0; width: 16px; height: 16px; background: #15803d; color: white; border-radius: 50%; font-size: 11px; font-weight: 600; display: flex; align-items: center; justify-content: center;">2</span>
                        <strong>Verifikasi</strong><br>
                        <span style="color: #64748b;">Admin memverifikasi data (1-3 hari kerja)</span>
                      </div>
                      <div style="margin-bottom: 10px; padding-left: 20px; position: relative;">
                        <span style="position: absolute; left: 0; top: 0; width: 16px; height: 16px; background: #15803d; color: white; border-radius: 50%; font-size: 11px; font-weight: 600; display: flex; align-items: center; justify-content: center;">3</span>
                        <strong>Survey (jika perlu)</strong><br>
                        <span style="color: #64748b;">Petugas melakukan survei lapangan</span>
                      </div>
                      <div style="padding-left: 20px; position: relative;">
                        <span style="position: absolute; left: 0; top: 0; width: 16px; height: 16px; background: #15803d; color: white; border-radius: 50%; font-size: 11px; font-weight: 600; display: flex; align-items: center; justify-content: center;">4</span>
                        <strong>Keputusan</strong><br>
                        <span style="color: #64748b;">Persetujuan atau penolakan</span>
                      </div>
                    </div>
                  </div>

                  <!-- Contact -->
                  <div style="background: #eff6ff; border: 1px solid #93c5fd; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 600; color: #1e40af;">
                      <i class="fas fa-phone-alt" style="margin-right: 8px;"></i>Butuh Bantuan?
                    </h4>
                    <p style="margin: 0; font-size: 13px; color: #1a1a1a; line-height: 1.6;">
                      Hubungi admin koperasi jika Anda memiliki pertanyaan seputar pengajuan pinjaman.
                    </p>
                  </div>
                </div>
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

    // Format jumlah_pokok input
    const jumlahPokokInput = document.getElementById('jumlah_pokok');
    const marginPersenInput = document.getElementById('margin_persen');
    const tenorBulanInput = document.getElementById('tenor_bulan');

    function calculatePreview() {
      // Get values
      let jumlahPokok = parseFloat(jumlahPokokInput.value.replace(/\./g, '').replace(/,/g, '')) || 0;
      let marginPersen = parseFloat(marginPersenInput.value) || 0;
      let tenorBulan = parseInt(tenorBulanInput.value) || 1;

      // Pastikan tenor minimal 1 untuk mencegah division by zero
      if (tenorBulan <= 0) {
        tenorBulan = 1;
      }

      // Calculate
      let marginKoperasi = (jumlahPokok * marginPersen) / 100;
      let totalBayar = jumlahPokok + marginKoperasi;
      let cicilanPerBulan = totalBayar / tenorBulan;

      // Update preview
      document.getElementById('preview_pokok').textContent = 'Rp ' + jumlahPokok.toLocaleString('id-ID');
      document.getElementById('preview_margin').textContent = 'Rp ' + marginKoperasi.toLocaleString('id-ID', {maximumFractionDigits: 0});
      document.getElementById('preview_total').textContent = 'Rp ' + totalBayar.toLocaleString('id-ID', {maximumFractionDigits: 0});
      document.getElementById('preview_cicilan').textContent = 'Rp ' + cicilanPerBulan.toLocaleString('id-ID', {maximumFractionDigits: 0}) + ' /bulan';
    }

    jumlahPokokInput.addEventListener('input', function(e) {
      // Format with thousand separator
      let value = e.target.value.replace(/\D/g, '');
      if (value) {
        value = parseInt(value).toLocaleString('id-ID');
      }
      e.target.value = value;
      calculatePreview();
    });

    marginPersenInput.addEventListener('input', calculatePreview);
    tenorBulanInput.addEventListener('input', calculatePreview);

    // Remove formatting on form submit
    document.getElementById('pinjamanForm').addEventListener('submit', function(e) {
      const rawValue = jumlahPokokInput.value.replace(/\./g, '');
      jumlahPokokInput.value = rawValue;

      // Validasi jumlah
      const jumlah = parseFloat(rawValue);
      if (jumlah < 500000) {
        e.preventDefault();
        alert('Minimal jumlah pinjaman adalah Rp 500.000!');
        return false;
      }
      if (jumlah > 50000000) {
        e.preventDefault();
        alert('Maksimal jumlah pinjaman adalah Rp 50.000.000!');
        return false;
      }
    });

    // Input focus effects
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.style.borderColor = '#1e3a8a';
        this.style.boxShadow = '0 0 0 3px rgba(30, 58, 138, 0.1)';
      });

      input.addEventListener('blur', function() {
        this.style.borderColor = '#d0d0d0';
        this.style.boxShadow = 'none';
      });
    });

    // Initial calculation
    calculatePreview();
  </script>

  <style>
    /* Input focus styles */
    input:focus, textarea:focus, select:focus {
      outline: none !important;
    }
  </style>
</body>

</html>
