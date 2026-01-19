<?php
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
              <div style="background: linear-gradient(135deg, #8b2a2a 0%, #5a1a1a 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-money-bill-wave" style="margin-right: 10px;"></i>Tarik Tunai
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=tarik&action=index"
                       style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Form Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 40px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                <?php if ($flash_error): ?>
                  <div style="background: #fee; border-left: 4px solid #c33; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <i class="fas fa-exclamation-circle" style="color: #c33; font-size: 18px;"></i>
                      <span style="color: #300; font-size: 14px;"><?= htmlspecialchars($flash_error) ?></span>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($flash_success): ?>
                  <div style="background: #efe; border-left: 4px solid #3c3; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <i class="fas fa-check-circle" style="color: #3c3; font-size: 18px;"></i>
                      <span style="color: #030; font-size: 14px;"><?= htmlspecialchars($flash_success) ?></span>
                    </div>
                  </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=tarik&action=store"
                      id="tarikForm"
                      novalidate>

                  <!-- Pilih Rekening -->
                  <div style="margin-bottom: 30px;">
                    <label for="no_rekening" style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      <i class="fas fa-book" style="margin-right: 5px; color: #8b2a2a;"></i>
                      Pilih Rekening <span style="color: #8b2a2a;">*</span>
                    </label>
                    <select id="no_rekening"
                            name="no_rekening"
                            required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; background: white;">
                      <option value="">-- Pilih Rekening --</option>
                      <?php if (!empty($daftarRekening)): ?>
                        <?php foreach ($daftarRekening as $rek): ?>
                          <option value="<?= htmlspecialchars($rek['no_rekening'] ?? '') ?>"
                                  data-saldo="<?= htmlspecialchars($rek['saldo_terakhir'] ?? '0') ?>"
                                  data-jenis="<?= htmlspecialchars($rek['nama_simpanan'] ?? '') ?>"
                                  data-anggota="<?= htmlspecialchars($rek['nama_lengkap'] ?? '') ?>">
                            <?= htmlspecialchars($rek['no_rekening'] ?? '') ?> -
                            <?= htmlspecialchars($rek['nama_lengkap'] ?? '') ?> -
                            <?= htmlspecialchars($rek['nama_simpanan'] ?? '') ?>
                          </option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                    <?php if (empty($daftarRekening)): ?>
                      <div style="margin-top: 8px; font-size: 12px; color: #8b2a2a;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i>
                        Tidak ada rekening aktif dengan saldo. Rekening harus memiliki saldo > 0 untuk dapat ditarik.
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Info Rekening -->
                  <div id="infoRekening" style="display: none; background: #fff3e0; border-left: 3px solid #8b2a2a; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
                    <h4 style="margin: 0 0 12px 0; font-size: 15px; color: #1a1a1a;">
                      <i class="fas fa-info-circle" style="margin-right: 5px; color: #8b2a2a;"></i>
                      Informasi Rekening
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                      <div>
                        <span style="font-size: 12px; color: #808080;">Nama Anggota:</span>
                        <div id="infoNama" style="font-weight: 600; color: #1a1a1a; font-size: 14px;">-</div>
                      </div>
                      <div>
                        <span style="font-size: 12px; color: #808080;">Jenis Simpanan:</span>
                        <div id="infoJenis" style="font-weight: 600; color: #1a1a1a; font-size: 14px;">-</div>
                      </div>
                      <div>
                        <span style="font-size: 12px; color: #808080;">Saldo Tersedia:</span>
                        <div id="infoSaldo" style="font-weight: 700; color: #8b2a2a; font-size: 18px;">Rp 0</div>
                      </div>
                      <div>
                        <span style="font-size: 12px; color: #808080;">Saldo Minimum (harus tersisa):</span>
                        <div style="font-weight: 600; color: #2a2a2a; font-size: 14px;">Rp 10.000</div>
                      </div>
                    </div>
                  </div>

                  <!-- Jumlah Penarikan -->
                  <div style="margin-bottom: 25px;">
                    <label for="jumlah" style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      <i class="fas fa-money-bill-wave" style="margin-right: 5px; color: #8b2a2a;"></i>
                      Jumlah Penarikan <span style="color: #8b2a2a;">*</span>
                    </label>
                    <div style="position: relative;">
                      <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-weight: 600; color: #8b2a2a; font-size: 15px;">Rp</span>
                      <input type="text"
                             id="jumlah"
                             name="jumlah"
                             placeholder="0"
                             required
                             style="width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 18px; font-weight: 600; color: #8b2a2a; text-align: right;">
                    </div>
                    <small style="display: block; margin-top: 8px; font-size: 12px; color: #808080;">
                      <i class="fas fa-info-circle" style="margin-right: 4px;"></i>
      Masukkan nominal penarikan (tanpa titik atau koma)
                    </small>
                  </div>

                  <!-- Estimasi Saldo Setelah Penarikan -->
                  <div id="estimasiSaldo" style="display: none; background: #ffebee; border-left: 3px solid #8b2a2a; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                      <div>
                        <span style="font-size: 13px; color: #1a1a1a;">Estimasi Saldo Setelah Penarikan:</span>
                      </div>
                      <div id="estimasiNilai" style="font-weight: 700; color: #8b2a2a; font-size: 20px;">Rp 0</div>
                    </div>
                    <div id="warningSaldo" style="display: none; margin-top: 10px; padding: 8px; background: #fff; border-radius: 4px; font-size: 11px; color: #8b2a2a;">
                      <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                      <span>Saldo mendekati minimum!</span>
                    </div>
                  </div>

                  <!-- Keterangan -->
                  <div style="margin-bottom: 30px;">
                    <label for="keterangan" style="display: block; font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">
                      <i class="fas fa-sticky-note" style="margin-right: 5px; color: #8b2a2a;"></i>
                      Keterangan
                    </label>
                    <textarea id="keterangan"
                              name="keterangan"
                              rows="3"
                              placeholder="Keterangan penarikan (opsional)"
                              style="width: 100%; padding: 12px 15px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; color: #1a1a1a; resize: vertical;">Penarikan Tunai</textarea>
                  </div>

                  <!-- Form Actions -->
                  <div style="border-top: 1px solid #e0e0e0; padding-top: 25px; margin-top: 30px;">
                    <div style="display: flex; gap: 15px; justify-content: flex-end;">
                      <a href="index.php?controller=tarik&action=index"
                         style="padding: 12px 28px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 15px; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-times" style="margin-right: 8px;"></i>Batal
                      </a>
                      <button type="submit"
                              style="padding: 12px 28px; background: #8b2a2a; color: white; border: none; border-radius: 4px; font-size: 15px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-check" style="margin-right: 8px;"></i>Proses Penarikan
                      </button>
                    </div>
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
    const noRekeningSelect = document.getElementById('no_rekening');
    const jumlahInput = document.getElementById('jumlah');
    const infoRekening = document.getElementById('infoRekening');
    const estimasiSaldo = document.getElementById('estimasiSaldo');
    const warningSaldo = document.getElementById('warningSaldo');

    // Update info rekening saat memilih rekening
    noRekeningSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];

      if (this.value) {
        const saldo = parseFloat(selectedOption.getAttribute('data-saldo')) || 0;
        const jenis = selectedOption.getAttribute('data-jenis');
        const anggota = selectedOption.getAttribute('data-anggota');

        // Update info rekening
        document.getElementById('infoNama').textContent = anggota;
        document.getElementById('infoJenis').textContent = jenis;
        document.getElementById('infoSaldo').textContent = 'Rp ' + saldo.toLocaleString('id-ID');

        infoRekening.style.display = 'block';

        // Update estimasi saldo
        updateEstimasiSaldo();
      } else {
        infoRekening.style.display = 'none';
        estimasiSaldo.style.display = 'none';
      }
    });

    // Update estimasi saat mengubah jumlah
    jumlahInput.addEventListener('input', function() {
      updateEstimasiSaldo();
    });

    function updateEstimasiSaldo() {
      if (!noRekeningSelect.value) {
        estimasiSaldo.style.display = 'none';
        return;
      }

      const selectedOption = noRekeningSelect.options[noRekeningSelect.selectedIndex];
      const saldo = parseFloat(selectedOption.getAttribute('data-saldo')) || 0;
      const jumlah = parseFloat(jumlahInput.value.replace(/[^0-9]/g, '')) || 0;

      if (jumlah > 0) {
        const saldoBaru = saldo - jumlah;
        document.getElementById('estimasiNilai').textContent = 'Rp ' + saldoBaru.toLocaleString('id-ID');
        estimasiSaldo.style.display = 'block';

        // Show warning jika saldo mendekati minimum (< 20000)
        if (saldoBaru < 20000) {
          warningSaldo.style.display = 'block';
        } else {
          warningSaldo.style.display = 'none';
        }
      } else {
        estimasiSaldo.style.display = 'none';
      }
    }

    // Format jumlah input
    jumlahInput.addEventListener('input', function() {
      // Hanya angka
      this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Form validation
    document.getElementById('tarikForm').addEventListener('submit', function(e) {
      const noRekening = noRekeningSelect.value;
      const jumlah = parseFloat(jumlahInput.value);

      // Validasi rekening
      if (!noRekening) {
        e.preventDefault();
        alert('Rekening harus dipilih');
        noRekeningSelect.focus();
        return false;
      }

      // Validasi jumlah
      if (!jumlah || jumlah <= 0) {
        e.preventDefault();
        alert('Jumlah penarikan harus diisi dengan benar');
        jumlahInput.focus();
        return false;
      }

      // Validasi saldo mencukupi
      const selectedOption = noRekeningSelect.options[noRekeningSelect.selectedIndex];
      const saldo = parseFloat(selectedOption.getAttribute('data-saldo')) || 0;

      if (jumlah > saldo) {
        e.preventDefault();
        alert('Saldo tidak mencukupi!\n\nSaldo saat ini: Rp ' + saldo.toLocaleString('id-ID') + '\nJumlah penarikan: Rp ' + jumlah.toLocaleString('id-ID'));
        jumlahInput.focus();
        return false;
      }

      // Validasi saldo minimum
      const minimalSisa = 10000;
      if ((saldo - jumlah) < minimalSisa) {
        e.preventDefault();
        alert('Penarikan gagal!\n\nSaldo minimum yang harus tersisa adalah Rp ' + minimalSisa.toLocaleString('id-ID') + '\n\nSaldo saat ini: Rp ' + saldo.toLocaleString('id-ID') + '\nMaksimal penarikan: Rp ' + (saldo - minimalSisa).toLocaleString('id-ID'));
        jumlahInput.focus();
        return false;
      }

      return true;
    });
  </script>

  <style>
    select:focus,
    input:focus,
    textarea:focus {
      outline: none;
      border-color: #8b2a2a !important;
      box-shadow: 0 0 0 3px rgba(139, 42, 42, 0.1) !important;
    }

    a[href*="tarik&action=index"]:hover {
      background: #1a1a1a !important;
    }

    button[type="submit"]:hover {
      background: #6b1a1a !important;
    }
  </style>
</body>

</html>
