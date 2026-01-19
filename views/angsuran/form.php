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
              <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <i class="fas fa-money-bill-wave" style="margin-right: 10px;"></i>Bayar Angsuran
                    </h2>
                    <nav style="background: transparent; padding: 0;">
                    
                    </nav>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <a href="index.php?controller=angsuran&action=index"
                       style="padding: 12px 24px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center;">
                      <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <!-- Form Section -->
                <div>
                  <!-- Pilih Pembiayaan -->
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-hand-holding-usd" style="color: #3b82f6; margin-right: 8px;"></i>Pilih Pembiayaan
                    </h3>

                    <div style="margin-bottom: 20px;">
                      <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Pilih Pembiayaan <span style="color: #ef4444;">*</span></label>
                      <select id="id_pembiayaan" name="id_pembiayaan" required
                              style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                        <option value="">-- Pilih Pembiayaan --</option>
                        <?php foreach ($pembiayaanList as $p): ?>
                          <option value="<?= $p['id_pembiayaan'] ?>" <?= ($pembiayaan && $pembiayaan['id_pembiayaan'] == $p['id_pembiayaan']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['no_akad']) ?> - <?= htmlspecialchars($p['nama_lengkap']) ?> (<?= AngsuranModel::formatRupiah($p['cicilan_per_bulan']) ?>/bulan)
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <div style="font-size: 11px; color: #808080; margin-top: 6px;">
                        <i class="fas fa-info-circle"></i> Pilih pembiayaan yang akan dibayarkan angsurannya
                      </div>
                    </div>
                  </div>

                  <!-- Detail Pembiayaan (Hidden by default) -->
                  <div id="pembiayaan-detail" style="display: none;">
                    <!-- Informasi Anggota & Pembiayaan -->
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                      <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                        <i class="fas fa-info-circle" style="color: #3b82f6; margin-right: 8px;"></i>Informasi Pembiayaan
                      </h3>

                      <div id="pembiayaan-info"></div>
                    </div>

                    <!-- Riwayat Angsuran -->
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                      <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                        <i class="fas fa-history" style="color: #3b82f6; margin-right: 8px;"></i>Riwayat Pembayaran
                      </h3>

                      <div id="riwayat-angsuran"></div>
                    </div>

                    <!-- Form Pembayaran -->
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);">
                      <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                        <i class="fas fa-edit" style="color: #3b82f6; margin-right: 8px;"></i>Input Pembayaran
                      </h3>

                      <form method="POST" action="index.php?controller=angsuran&action=save" onsubmit="return validateForm();">
                        <input type="hidden" id="input_id_pembiayaan" name="id_pembiayaan">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                          <div>
                            <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Angsuran Ke</label>
                            <input type="text" id="angsuran_ke_display" readonly placeholder="-"
                                   style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; background: #f9fafb;">
                          </div>
                          <div>
                            <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Tanggal Bayar</label>
                            <input type="text" value="<?= date('d M Y H:i') ?>" readonly
                                   style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; background: #f9fafb;">
                          </div>
                        </div>

                        <div style="margin-top: 20px;">
                          <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Jumlah Bayar <span style="color: #ef4444;">*</span></label>
                          <input type="number" id="jumlah_bayar" name="jumlah_bayar" required min="1" step="0.01"
                                 placeholder="Masukkan jumlah yang dibayar"
                                 style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                          <div style="font-size: 11px; color: #808080; margin-top: 6px;">
                            <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Cicilan normal: <strong id="cicilan-normal">-</strong>
                          </div>
                        </div>

                        <div style="margin-top: 20px;">
                          <label style="font-size: 13px; font-weight: 600; color: #1a1a1a; display: block; margin-bottom: 8px;">Denda (Opsional)</label>
                          <input type="number" id="denda" name="denda" min="0" step="0.01" value="0"
                                 placeholder="Denda keterlambatan (jika ada)"
                                 style="width: 100%; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                          <div style="font-size: 11px; color: #808080; margin-top: 6px;">
                            <i class="fas fa-info-circle"></i> Isi 0 jika tidak ada denda
                          </div>
                        </div>

                        <div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                          <div style="display: flex; justify-content: space-between; font-size: 13px;">
                            <span style="color: #6b7280;">Total yang harus dibayar:</span>
                            <strong style="color: #1a1a1a;" id="total-harus-dibayar">-</strong>
                          </div>
                          <div style="display: flex; justify-content: space-between; font-size: 13px; margin-top: 8px;">
                            <span style="color: #6b7280;">Estimasi sisa setelah bayar:</span>
                            <strong style="color: #10b981;" id="sisa-setelah-bayar">-</strong>
                          </div>
                        </div>

                        <div style="margin-top: 25px; display: flex; gap: 12px;">
                          <button type="submit" style="padding: 14px 35px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);">
                            <i class="fas fa-save" style="margin-right: 10px;"></i>Simpan Pembayaran
                          </button>
                          <a href="index.php?controller=angsuran&action=index" style="padding: 14px 35px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: 600; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                            <i class="fas fa-times" style="margin-right: 10px;"></i>Batal
                          </a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Summary Sidebar -->
                <div>
                  <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06); position: sticky; top: 20px;">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 20px 0; color: #1a1a1a; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                      <i class="fas fa-calculator" style="color: #3b82f6; margin-right: 8px;"></i>Ringkasan
                    </h3>

                    <div id="ringkasan-info">
                      <div style="text-align: center; padding: 30px 0; color: #808080;">
                        <i class="fas fa-hand-pointer" style="font-size: 36px; margin-bottom: 15px; display: block;"></i>
                        <p style="font-size: 13px; margin: 0;">Pilih pembiayaan untuk melihat ringkasan</p>
                      </div>
                    </div>
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

    // Load pembiayaan detail saat dipilih
    document.getElementById('id_pembiayaan').addEventListener('change', function() {
      const idPembiayaan = this.value;

      if (!idPembiayaan) {
        // Reset field angsuran_ke_display
        const angsuranKeDisplay = document.getElementById('angsuran_ke_display');
        if (angsuranKeDisplay) {
          angsuranKeDisplay.value = '';
        }

        document.getElementById('pembiayaan-detail').style.display = 'none';
        return;
      }

      // Update hidden input
      document.getElementById('input_id_pembiayaan').value = idPembiayaan;

      // Fetch data via AJAX
      fetch('index.php?controller=angsuran&action=getPembiayaanDetail&id=' + idPembiayaan)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (!data.status) {
            alert('Gagal memuat data pembiayaan: ' + (data.message || 'Unknown error'));
            return;
          }

          const pembiayaan = data.data.pembiayaan;
          const riwayat = data.data.riwayat;
          const totalDibayar = data.data.total_dibayar;
          const nextAngsuranKe = data.data.next_angsuran_ke;

          // Validasi data
          if (!pembiayaan) {
            alert('Data pembiayaan tidak valid!');
            return;
          }

          // Display pembiayaan info
          displayPembiayaanInfo(pembiayaan, totalDibayar, nextAngsuranKe);

          // Display riwayat
          displayRiwayat(riwayat);

          // Show detail section
          document.getElementById('pembiayaan-detail').style.display = 'block';

          // Update cicilan normal hint
          document.getElementById('cicilan-normal').textContent = formatRupiah(pembiayaan.cicilan_per_bulan);

          // Calculate and update total yang harus dibayar
          updateTotalHarusDibayar(pembiayaan, totalDibayar.total_dibayar);
        })
        .catch(error => {
          console.error('Error:', error);
          // Hanya log error, jangan tampilkan alert jika data tetap muncul
          // alert('Terjadi kesalahan saat memuat data!');
        });
    });

    // Format Rupiah
    function formatRupiah(amount) {
      return 'Rp ' + parseFloat(amount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Display pembiayaan info
    function displayPembiayaanInfo(pembiayaan, totalDibayar, nextAngsuranKe) {
      // Validasi dan default values
      const totalDibayarValue = totalDibayar?.total_dibayar || 0;
      const totalBayar = parseFloat(pembiayaan?.total_bayar) || 0;
      const cicilanPerBulan = parseFloat(pembiayaan?.cicilan_per_bulan) || 0;
      const tenorBulan = parseInt(pembiayaan?.tenor_bulan) || 0;
      const sisaTagihan = totalBayar - totalDibayarValue;
      const progress = totalBayar > 0 ? (totalDibayarValue / totalBayar) * 100 : 0;

      document.getElementById('pembiayaan-info').innerHTML = `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
          <div>
            <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">No. Akad</div>
            <div style="font-family: 'Courier New', monospace; font-weight: 600; color: #dc2626; font-size: 13px;">${pembiayaan.no_akad}</div>
          </div>
          <div>
            <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Tanggal Pengajuan</div>
            <div style="font-size: 13px; color: #1a1a1a;">${pembiayaan.tanggal_pengajuan}</div>
          </div>
          <div>
            <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">Nama Anggota</div>
            <div style="font-size: 13px; font-weight: 600; color: #1a1a1a;">${pembiayaan.nama_lengkap}</div>
          </div>
          <div>
            <div style="font-size: 11px; color: #808080; margin-bottom: 2px;">No. Anggota</div>
            <div style="font-size: 13px; color: #1a1a1a;">${pembiayaan.no_anggota}</div>
          </div>
        </div>

        <div style="padding: 15px; background: #f9fafb; border-radius: 4px; margin-bottom: 15px;">
          <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px;">
            <span style="color: #6b7280;">Jenis Akad:</span>
            <strong style="color: #1a1a1a;">${pembiayaan.jenis_akad || '-'}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px;">
            <span style="color: #6b7280;">Tenor:</span>
            <strong style="color: #1a1a1a;">${tenorBulan} bulan</strong>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px;">
            <span style="color: #6b7280;">Cicilan per bulan:</span>
            <strong style="color: #1a1a1a;">${formatRupiah(cicilanPerBulan)}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px;">
            <span style="color: #6b7280;">Total tagihan:</span>
            <strong style="color: #dc2626;">${formatRupiah(totalBayar)}</strong>
          </div>
        </div>

        <!-- Progress Bar -->
        <div style="margin-bottom: 10px;">
          <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px;">
            <span style="color: #6b7280;">Progres Pembayaran:</span>
            <strong style="color: #10b981;">${progress.toFixed(1)}%</strong>
          </div>
          <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
            <div style="width: ${progress}%; height: 100%; background: linear-gradient(90deg, #10b981 0%, #059669 100%); transition: width 0.3s ease;"></div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
          <div style="padding: 12px; background: #d1fae5; border-left: 3px solid #10b981; border-radius: 4px;">
            <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px;">Total Dibayar</div>
            <div style="font-weight: 600; color: #10b981; font-size: 15px;">${formatRupiah(totalDibayarValue)}</div>
          </div>
          <div style="padding: 12px; background: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 4px;">
            <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px;">Sisa Tagihan</div>
            <div style="font-weight: 600; color: #f59e0b; font-size: 15px;">${formatRupiah(sisaTagihan)}</div>
          </div>
        </div>

        <input type="hidden" id="next_angsuran_ke" value="${nextAngsuranKe}">
        <input type="hidden" id="total_bayar" value="${totalBayar}">
        <input type="hidden" id="total_dibayar_sebelumnya" value="${totalDibayarValue}">
      `;

      // Set field angsuran_ke_display di luar template literal
      // Validasi dan set field dengan aman
      const nextAngsuranKeValue = parseInt(nextAngsuranKe) || 0;
      const angsuranKeDisplayElement = document.getElementById('angsuran_ke_display');

      if (angsuranKeDisplayElement) {
        angsuranKeDisplayElement.value = nextAngsuranKeValue;
        console.log('Setting angsuran_ke_display to:', nextAngsuranKeValue);
      } else {
        console.error('Element angsuran_ke_display not found!');
      }

      // Update ringkasan
      updateRingkasan(pembiayaan, totalDibayar, nextAngsuranKe);
    }

    // Display riwayat angsuran
    function displayRiwayat(riwayat) {
      if (!riwayat || riwayat.length === 0) {
        document.getElementById('riwayat-angsuran').innerHTML = `
          <div style="text-align: center; padding: 30px 0; color: #808080;">
            <i class="fas fa-inbox" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
            <p style="font-size: 13px; margin: 0;">Belum ada riwayat pembayaran</p>
          </div>
        `;
        return;
      }

      let html = '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse;">';
      html += '<thead><tr style="background: #f5f5f5;">';
      html += '<th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Ke</th>';
      html += '<th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Tanggal</th>';
      html += '<th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Jumlah</th>';
      html += '<th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">Denda</th>';
      html += '</tr></thead><tbody>';

      riwayat.forEach(item => {
        html += '<tr style="border-bottom: 1px solid #f0f0f0;">';
        html += '<td style="padding: 10px; font-size: 12px;">' + item.angsuran_ke + '</td>';
        html += '<td style="padding: 10px; font-size: 12px;">' + item.tanggal_bayar + '</td>';
        html += '<td style="padding: 10px; font-size: 12px; font-weight: 600; color: #10b981;">' + formatRupiah(item.jumlah_bayar) + '</td>';
        html += '<td style="padding: 10px; font-size: 12px; color: ' + (item.denda > 0 ? '#ef4444' : '#808080') + ';">' + (item.denda > 0 ? formatRupiah(item.denda) : '-') + '</td>';
        html += '</tr>';
      });

      html += '</tbody></table></div>';
      document.getElementById('riwayat-angsuran').innerHTML = html;
    }

    // Update ringkasan sidebar
    function updateRingkasan(pembiayaan, totalDibayar, nextAngsuranKe) {
      // Validasi dan default values
      const totalDibayarValue = totalDibayar?.total_dibayar || 0;
      const totalBayar = parseFloat(pembiayaan?.total_bayar) || 0;
      const cicilanPerBulan = parseFloat(pembiayaan?.cicilan_per_bulan) || 0;
      const tenorBulan = parseInt(pembiayaan?.tenor_bulan) || 0;
      const sisaTagihan = totalBayar - totalDibayarValue;
      const sisaBulan = tenorBulan - (parseInt(nextAngsuranKe) - 1);

      document.getElementById('ringkasan-info').innerHTML = `
        <div style="margin-bottom: 15px;">
          <div style="font-size: 11px; color: #808080; margin-bottom: 3px;">Angsuran Ke</div>
          <div style="font-size: 20px; font-weight: 600; color: #3b82f6;">${nextAngsuranKe}</div>
          <div style="font-size: 11px; color: #6b7280;">dari ${tenorBulan} bulan</div>
        </div>

        <div style="padding: 12px; background: #f0f9ff; border-radius: 4px; margin-bottom: 15px;">
          <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px;">Cicilan Normal</div>
          <div style="font-weight: 600; color: #3b82f6; font-size: 16px;">${formatRupiah(cicilanPerBulan)}</div>
        </div>

        <div style="padding: 12px; background: #fef3c7; border-radius: 4px; margin-bottom: 15px;">
          <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px;">Sisa Tagihan</div>
          <div style="font-weight: 600; color: #f59e0b; font-size: 16px;">${formatRupiah(sisaTagihan)}</div>
          <div style="font-size: 10px; color: #6b7280; margin-top: 3px;">${sisaBulan > 0 ? sisaBulan + ' bulan lagi' : 'Sudah lunas'}</div>
        </div>
      `;
    }

    // Update total yang harus dibayar saat input berubah
    document.getElementById('jumlah_bayar').addEventListener('input', updateTotalHarusDibayar);
    document.getElementById('denda').addEventListener('input', updateTotalHarusDibayar);

    function updateTotalHarusDibayar(pembiayaan, totalDibayarValue) {
      let sisaTagihan = 0;

      if (!pembiayaan) {
        const totalBayar = parseFloat(document.getElementById('total_bayar')?.value || 0);
        const totalDibayarSebelumnya = parseFloat(document.getElementById('total_dibayar_sebelumnya')?.value || 0);
        sisaTagihan = totalBayar - totalDibayarSebelumnya;
      } else {
        const totalBayar = parseFloat(pembiayaan?.total_bayar) || 0;
        sisaTagihan = totalBayar - totalDibayarValue;
      }

      document.getElementById('total-harus-dibayar').textContent = formatRupiah(sisaTagihan);

      // Calculate sisa setelah bayar
      const jumlahBayar = parseFloat(document.getElementById('jumlah_bayar').value || 0);
      const denda = parseFloat(document.getElementById('denda').value || 0);

      if (sisaTagihan >= 0) {
        const sisaSetelahBayar = Math.max(0, sisaTagihan - jumlahBayar);
        document.getElementById('sisa-setelah-bayar').textContent = formatRupiah(sisaSetelahBayar);
      }
    }

    // Validate form
    function validateForm() {
      const idPembiayaan = document.getElementById('input_id_pembiayaan').value;
      const jumlahBayar = document.getElementById('jumlah_bayar').value;

      if (!idPembiayaan) {
        alert('Pilih pembiayaan terlebih dahulu!');
        return false;
      }

      if (!jumlahBayar || jumlahBayar <= 0) {
        alert('Masukkan jumlah yang dibayar!');
        return false;
      }

      return true;
    }
  </script>
</body>

</html>
