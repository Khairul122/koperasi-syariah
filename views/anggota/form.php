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
              <div style="background: linear-gradient(135deg, #1e3a2f 0%, #0f1f17 100%); color: white; padding: 30px 40px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <h2 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0; color: white;">
                      <?php if ($formMode === 'create'): ?>
                        <i class="fas fa-user-plus" style="margin-right: 10px;"></i>Tambah Anggota Baru
                      <?php else: ?>
                        <i class="fas fa-user-edit" style="margin-right: 10px;"></i>Edit Data Anggota
                      <?php endif; ?>
                    </h2>
                  </div>
                  <a href="index.php?controller=anggota&action=index"
                     style="padding: 10px 20px; background: rgba(255, 255, 255, 0.15); color: white; border-radius: 4px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">
                    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                  </a>
                </div>
              </div>

              <!-- Form Card -->
              <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06); padding: 30px;">
                <form method="POST" action="<?php
                  echo $formMode === 'create'
                    ? 'index.php?controller=anggota&action=store'
                    : 'index.php?controller=anggota&action=update&id=' . ($data['id_anggota'] ?? 0)
                  ?>" id="anggotaForm" novalidate>

                  <?php if ($formMode === 'edit'): ?>
                    <input type="hidden" name="id" value="<?= $data['id_anggota'] ?? '' ?>">
                  <?php endif; ?>

                  <!-- Informasi Pribadi -->
                  <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 20px; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                    <i class="fas fa-user" style="margin-right: 8px; color: #1e3a2f;"></i>Informasi Pribadi
                  </h3>

                  <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div>
                      <label for="nik" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        NIK (Nomor Induk Kependudukan) <span style="color: #8b2a2a;">*</span>
                      </label>
                      <input type="text" id="nik" name="nik"
                             placeholder="16 digit NIK" maxlength="16" required
                             pattern="[0-9]{16}"
                             value="<?= htmlspecialchars($data['nik'] ?? '') ?>"
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                      <small style="display: block; margin-top: 6px; font-size: 12px; color: #808080;">Masukkan 16 digit NIK yang valid</small>
                    </div>

                    <div>
                      <label for="nama_lengkap" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Nama Lengkap <span style="color: #8b2a2a;">*</span>
                      </label>
                      <input type="text" id="nama_lengkap" name="nama_lengkap"
                             placeholder="Nama lengkap sesuai KTP" required
                             value="<?= htmlspecialchars($data['nama_lengkap'] ?? '') ?>"
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                    </div>
                  </div>

                  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div>
                      <label for="jenis_kelamin" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Jenis Kelamin <span style="color: #8b2a2a;">*</span>
                      </label>
                      <select id="jenis_kelamin" name="jenis_kelamin" required
                              style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white;">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" <?= (isset($data['jenis_kelamin']) && $data['jenis_kelamin'] === 'L') ? 'selected' : '' ?>>
                          Laki-laki
                        </option>
                        <option value="P" <?= (isset($data['jenis_kelamin']) && $data['jenis_kelamin'] === 'P') ? 'selected' : '' ?>>
                          Perempuan
                        </option>
                      </select>
                    </div>

                    <div>
                      <label for="tempat_lahir" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Tempat Lahir <span style="color: #8b2a2a;">*</span>
                      </label>
                      <input type="text" id="tempat_lahir" name="tempat_lahir"
                             placeholder="Kota kelahiran" required
                             value="<?= htmlspecialchars($data['tempat_lahir'] ?? '') ?>"
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                    </div>

                    <div>
                      <label for="tanggal_lahir" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Tanggal Lahir <span style="color: #8b2a2a;">*</span>
                      </label>
                      <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                             value="<?= $data['tanggal_lahir'] ?? '' ?>"
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                    </div>
                  </div>

                  <!-- Kontak & Alamat -->
                  <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin: 30px 0 20px 0; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                    <i class="fas fa-address-book" style="margin-right: 8px; color: #1e3a2f;"></i>Kontak & Alamat
                  </h3>

                  <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div>
                      <label for="no_hp" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        No. Handphone <span style="color: #8b2a2a;">*</span>
                      </label>
                      <div style="display: flex;">
                        <span style="padding: 10px 12px; background: #2a2a2a; color: white; border: 1px solid #b0b0b0; border-right: none; border-radius: 4px 0 0 4px; font-size: 14px;">+62</span>
                        <input type="tel" id="no_hp" name="no_hp"
                               placeholder="8xxxxxxxxxx" required pattern="[0-9]{9,14}"
                               value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>"
                               style="flex: 1; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 0 4px 4px 0; font-size: 14px; color: #1a1a1a; background-color: white;">
                      </div>
                      <small style="display: block; margin-top: 6px; font-size: 12px; color: #808080;">Contoh: 81234567890</small>
                    </div>

                    <div>
                      <label for="pekerjaan" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Pekerjaan
                      </label>
                      <input type="text" id="pekerjaan" name="pekerjaan"
                             placeholder="Pekerjaan saat ini"
                             value="<?= htmlspecialchars($data['pekerjaan'] ?? '') ?>"
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                    </div>
                  </div>

                  <div style="margin-bottom: 20px;">
                    <label for="alamat" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                      Alamat Lengkap <span style="color: #8b2a2a;">*</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="3" required
                              placeholder="Alamat domisili saat ini"
                              style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; resize: vertical; transition: all 0.3s ease;"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
                  </div>

                  <!-- Akun Login -->
                  <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin: 30px 0 20px 0; border-bottom: 2px solid #1e3a2f; padding-bottom: 10px;">
                    <i class="fas fa-lock" style="margin-right: 8px; color: #1e3a2f;"></i>Akun Login
                  </h3>

                  <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div>
                      <label for="username" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Username <span style="color: #8b2a2a;">*</span>
                      </label>
                      <input type="text" id="username" name="username"
                             placeholder="Minimal 4 karakter alphanumeric" required
                             pattern="[a-zA-Z0-9]{4,}"
                             value="<?= htmlspecialchars($data['username'] ?? '') ?>"
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                      <small style="display: block; margin-top: 6px; font-size: 12px; color: #808080;">Contoh: ahmad123, siti_aja (tanpa spasi)</small>
                    </div>

                    <div>
                      <label for="password" style="display: block; font-size: 13px; font-weight: 500; color: #1a1a1a; margin-bottom: 6px;">
                        Password
                        <?php if ($formMode === 'create'): ?>
                          <span style="color: #8b2a2a;">*</span>
                        <?php else: ?>
                          <small style="color: #808080;">(kosongkan jika tidak diubah)</small>
                        <?php endif; ?>
                      </label>
                      <input type="password" id="password" name="password"
                             placeholder="Minimal 6 karakter"
                             <?= $formMode === 'create' ? 'required minlength="6"' : '' ?>
                             style="width: 100%; padding: 10px 12px; border: 1px solid #b0b0b0; border-radius: 4px; font-size: 14px; color: #1a1a1a; background-color: white; transition: all 0.3s ease;">
                      <?php if ($formMode === 'create'): ?>
                        <small style="display: block; margin-top: 6px; font-size: 12px; color: #808080;">Minimal 6 karakter</small>
                      <?php else: ?>
                        <small style="display: block; margin-top: 6px; font-size: 12px; color: #808080;">Biarkan kosong jika tidak ingin mengubah password</small>
                      <?php endif; ?>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; gap: 10px;">
                    <a href="index.php?controller=anggota&action=index"
                       style="padding: 12px 24px; background: #2a2a2a; color: white; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;">
                      <i class="fas fa-times" style="margin-right: 8px;"></i>Batal
                    </a>
                    <button type="submit" id="submitBtn"
                            style="padding: 12px 24px; background: #1e3a2f; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                      <i class="fas fa-save" style="margin-right: 8px;"></i>
                      <?php if ($formMode === 'create'): ?>
                        Simpan Data
                      <?php else: ?>
                        Update Data
                      <?php endif; ?>
                    </button>
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
    // Show notifications on page load (Browser Alert)
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($flash_error): ?>
        alert('GAGAL!\n\n<?= addslashes($flash_error) ?>');
      <?php endif; ?>

      <?php if ($flash_success): ?>
        alert('BERHASIL!\n\n<?= addslashes($flash_success) ?>');
      <?php endif; ?>
    });

    // Form validation & submission
    document.getElementById('anggotaForm').addEventListener('submit', function(e) {
      let isValid = true;
      let errorMessage = '';

      // Validate NIK
      const nik = document.getElementById('nik');
      if (!/^[0-9]{16}$/.test(nik.value)) {
        isValid = false;
        errorMessage = 'NIK harus 16 digit angka!';
        nik.classList.add('is-invalid');
      } else {
        nik.classList.remove('is-invalid');
      }

      // Validate No HP
      const noHp = document.getElementById('no_hp');
      if (!/^[0-9]{9,14}$/.test(noHp.value)) {
        isValid = false;
        errorMessage = 'No. HP tidak valid (9-14 digit)!';
        noHp.classList.add('is-invalid');
      } else {
        noHp.classList.remove('is-invalid');
      }

      // Validate Username
      const username = document.getElementById('username');
      if (!/^[a-zA-Z0-9]{4,}$/.test(username.value)) {
        isValid = false;
        errorMessage = 'Username minimal 4 karakter alphanumeric (tanpa spasi)!';
        username.classList.add('is-invalid');
      } else {
        username.classList.remove('is-invalid');
      }

      // Validate Password (create mode)
      const password = document.getElementById('password');
      const isCreateMode = '<?= $formMode === 'create' ? 'true' : 'false' ?>' === 'true';
      if (isCreateMode && password.value.length < 6) {
        isValid = false;
        errorMessage = 'Password minimal 6 karakter!';
        password.classList.add('is-invalid');
      } else {
        password.classList.remove('is-invalid');
      }

      if (!isValid) {
        e.preventDefault();
        alert('VALIDASI GAGAL!\n\n' + errorMessage);
      } else {
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        submitBtn.disabled = true;
      }
    });

    // Prevent spaces in username
    document.getElementById('username').addEventListener('input', function() {
      this.value = this.value.replace(/\s/g, '');
    });

    // Format no HP (numeric only)
    document.getElementById('no_hp').addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });

    // NIK numeric only
    document.getElementById('nik').addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  </script>

  <style>
    /* Form focus states */
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #1e3a2f !important;
      box-shadow: 0 0 0 3px rgba(30, 58, 47, 0.1) !important;
    }

    input.is-invalid, select.is-invalid, textarea.is-invalid {
      border-color: #8b2a2a !important;
    }

    /* Button hover effects */
    a:hover[href*="anggota&action=index"],
    a:hover[href*="dashboard&action=admin"] {
      background: #1a1a1a !important;
    }

    button#submitBtn:hover {
      background: #0f1f17 !important;
    }
  </style>
</body>

</html>
