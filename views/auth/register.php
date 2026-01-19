<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?? 'Daftar Anggota - Koperasi Syariah' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #059669;
            --primary-dark: #047857;
            --secondary-color: #fbbf24;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .register-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 50px 60px;
            text-align: center;
            color: white;
            position: relative;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 50%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .header-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .header-icon i {
            font-size: 45px;
        }

        .register-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .register-header p {
            font-size: 16px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        .register-body {
            padding: 50px 60px;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 12px;
            color: var(--primary-color);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control, .form-select {
            height: 48px;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            font-size: 13px;
            color: #ef4444;
            margin-top: 6px;
        }

        textarea.form-control {
            height: auto;
            min-height: 100px;
            resize: vertical;
        }

        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }

        .strength-fill.weak { width: 33%; background: #ef4444; }
        .strength-fill.medium { width: 66%; background: #f59e0b; }
        .strength-fill.strong { width: 100%; background: var(--primary-color); }

        .strength-text {
            font-size: 12px;
            margin-top: 4px;
            color: var(--text-light);
        }

        .btn-register {
            width: 100%;
            height: 54px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(5, 150, 105, 0.3);
        }

        .btn-register:disabled {
            background: var(--text-light);
            cursor: not-allowed;
            transform: none;
        }

        .form-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: var(--text-light);
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #1e40af;
        }

        .info-box i {
            margin-right: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            .register-header {
                padding: 40px 30px;
            }

            .register-header h1 {
                font-size: 26px;
            }

            .register-body {
                padding: 35px 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .form-section-title {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .register-header {
                padding: 30px 20px;
            }

            .register-body {
                padding: 25px 15px;
            }

            .form-control, .form-select {
                height: 46px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Header -->
        <div class="register-header">
            <div class="header-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Pendaftaran Anggota</h1>
            <p>Bergabunglah bersama Koperasi Syariah untuk masa depan finansial yang lebih baik</p>
        </div>

        <!-- Form Body -->
        <div class="register-body">
            <!-- Flash messages -->
            <?php
            $flash_error = $_SESSION['flash_error'] ?? null;
            $flash_success = $_SESSION['flash_success'] ?? null;
            unset($_SESSION['flash_error'], $_SESSION['flash_success']);
            ?>

            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                Isi data diri Anda dengan lengkap dan benar. No. Anggota akan dibuatkan secara otomatis.
            </div>

            <form method="POST" action="index.php?controller=auth&action=register" id="registerForm" novalidate>
                <!-- Informasi Pribadi -->
                <div class="form-section-title">
                    <i class="fas fa-user"></i>Informasi Pribadi
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="required">*</span></label>
                        <input type="text" class="form-control" id="nik" name="nik"
                               placeholder="16 digit NIK" maxlength="16" required
                               pattern="[0-9]{16}">
                        <div class="invalid-feedback">NIK harus 16 digit angka</div>
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                               placeholder="Nama lengkap sesuai KTP" required>
                        <div class="invalid-feedback">Nama lengkap harus diisi</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="required">*</span></label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <div class="invalid-feedback">Pilih jenis kelamin</div>
                    </div>

                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="required">*</span></label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                               placeholder="Kota kelahiran" required>
                        <div class="invalid-feedback">Tempat lahir harus diisi</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        <div class="invalid-feedback">Tanggal lahir harus diisi</div>
                    </div>

                    <div class="form-group">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                               placeholder="Pekerjaan saat ini">
                    </div>
                </div>

                <!-- Kontak & Alamat -->
                <div class="form-section-title" style="margin-top: 30px;">
                    <i class="fas fa-address-book"></i>Kontak & Alamat
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="no_hp" class="form-label">No. Handphone <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp"
                                   placeholder="8xxxxxxxxxx" required pattern="[0-9]{9,14}">
                        </div>
                        <div class="invalid-feedback">No. HP tidak valid (9-14 digit)</div>
                    </div>

                    <div class="form-group">
                        <label for="alamat" class="form-label">Alamat Lengkap <span class="required">*</span></label>
                        <input type="text" class="form-control" id="alamat" name="alamat"
                               placeholder="Alamat domisili saat ini" required>
                        <div class="invalid-feedback">Alamat harus diisi</div>
                    </div>
                </div>

                <!-- Akun Login -->
                <div class="form-section-title" style="margin-top: 30px;">
                    <i class="fas fa-lock"></i>Akun Login
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username" class="form-label">Username <span class="required">*</span></label>
                        <input type="text" class="form-control" id="username" name="username"
                               placeholder="Minimal 4 karakter alphanumeric" required
                               pattern="[a-zA-Z0-9]{4,}">
                        <div class="invalid-feedback">Username minimal 4 karakter alphanumeric</div>
                        <small class="text-muted" style="font-size: 12px;">Contoh: ahmad123, siti_aja</small>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Minimal 6 karakter" required minlength="6">
                        <div class="invalid-feedback">Password minimal 6 karakter</div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Kekuatan password</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                               placeholder="Ulangi password" required>
                        <div class="invalid-feedback">Password tidak cocok</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register" id="submitBtn">
                    <i class="fas fa-user-check me-2"></i>Daftar Sekarang
                </button>

                <!-- Footer -->
                <div class="form-footer">
                    Sudah punya akun?
                    <a href="index.php?controller=auth&action=login">Login di sini</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show notifications on page load (Browser Alert)
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flash_error): ?>
                alert('PENDAFTARAN GAGAL!\n\n<?= addslashes($flash_error) ?>');
            <?php endif; ?>

            <?php if ($flash_success): ?>
                alert('PENDAFTARAN BERHASIL!\n\n<?= addslashes($flash_success) ?>\n\nAnda akan dialihkan ke halaman login...');
                // Redirect to login after success
                setTimeout(function() {
                    window.location.href = 'index.php?controller=auth&action=login';
                }, 2000);
            <?php endif; ?>
        });

        // Password Strength Checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthFill.className = 'strength-fill';

            if (strength < 2) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Lemah';
            } else if (strength < 4) {
                strengthFill.classList.add('medium');
                strengthText.textContent = 'Sedang';
            } else {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Kuat';
            }
        });

        // Form Validation with SweetAlert2
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Validate NIK
            const nik = document.getElementById('nik');
            if (!/^[0-9]{16}$/.test(nik.value)) {
                nik.classList.add('is-invalid');
                isValid = false;
                errorMessage = 'NIK harus 16 digit angka!';
            } else {
                nik.classList.remove('is-invalid');
            }

            // Validate Password Confirmation
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');

            if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('is-invalid');
                isValid = false;
                errorMessage = 'Konfirmasi password tidak cocok!';
            } else {
                passwordConfirm.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
                alert('VALIDASI GAGAL!\n\n' + errorMessage);
            } else {
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                submitBtn.disabled = true;
            }
        });

        // Real-time validation
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                }
            });
        });

        // Prevent spaces in username
        document.getElementById('username').addEventListener('input', function() {
            this.value = this.value.replace(/\s/g, '');
        });

        // Format no HP
        document.getElementById('no_hp').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // NIK numeric only
        document.getElementById('nik').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
