<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?? 'Login - Koperasi Syariah' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --accent: #f59e0b;
            --dark: #1e293b;
            --gray: #64748b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #22c55e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0fdf4;
            background-image:
                radial-gradient(at 40% 20%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(245, 158, 11, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(245, 158, 11, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 80% 100%, rgba(245, 158, 11, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Shapes */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            z-index: 0;
            animation: float 20s infinite;
        }

        body::before {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            top: -200px;
            right: -200px;
        }

        body::after {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }
            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        .login-wrapper {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.02),
                0 10px 15px -3px rgba(0, 0, 0, 0.04),
                0 20px 25px -5px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border);
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        /* Top Accent Line */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .logo-container::after {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 22px;
            z-index: -1;
            opacity: 0.3;
            filter: blur(10px);
        }

        .logo-container i {
            font-size: 36px;
            color: white;
        }

        .logo-section h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .logo-section p {
            font-size: 15px;
            color: var(--gray);
            margin: 0;
        }

        /* Tab Navigation for Role Selection */
        .role-tabs {
            display: flex;
            background: var(--light);
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 32px;
            gap: 4px;
        }

        .role-tab {
            flex: 1;
            padding: 12px 16px;
            border: none;
            background: transparent;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .role-tab i {
            font-size: 16px;
        }

        .role-tab.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .role-tab:hover:not(.active) {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 18px;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 48px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            color: var(--dark);
            background: white;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-control:focus + i,
        .input-wrapper:focus-within i {
            color: var(--primary);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            padding: 4px;
            font-size: 18px;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Remember Me */
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            margin-left: 10px;
            font-size: 14px;
            color: var(--gray);
            cursor: pointer;
            user-select: none;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer Link */
        .form-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            font-size: 14px;
            color: var(--gray);
        }

        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert-custom {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-error i {
            font-size: 18px;
        }

        /* Loading Spinner */
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Form Validation States */
        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            display: none;
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
        }

        .form-control.is-invalid ~ .error-message {
            display: block;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 32px 24px;
            }

            .logo-section h1 {
                font-size: 24px;
            }

            .form-control {
                padding: 12px 44px;
                font-size: 14px;
            }

            .btn-submit {
                padding: 14px;
            }
        }

        /* Accessibility */
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-container">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
                <h1>Koperasi Syariah</h1>
                <p>Masuk untuk mengelola akun Anda</p>
            </div>

            <!-- Flash Messages -->
            <?php
            $flash_error = $_SESSION['flash_error'] ?? null;
            $flash_success = $_SESSION['flash_success'] ?? null;
            unset($_SESSION['flash_error'], $_SESSION['flash_success']);
            ?>

            <form method="POST" action="index.php?controller=auth&action=login" id="loginForm" novalidate>
                <!-- Role Selection Tabs -->
                <div class="role-tabs">
                    <input type="radio" name="role" value="petugas" id="rolePetugas" class="visually-hidden" checked>
                    <button type="button" class="role-tab active" data-role="petugas" onclick="selectRole('petugas')">
                        <i class="fas fa-user-tie"></i>
                        Petugas
                    </button>

                    <input type="radio" name="role" value="anggota" id="roleAnggota" class="visually-hidden">
                    <button type="button" class="role-tab" data-role="anggota" onclick="selectRole('anggota')">
                        <i class="fas fa-user"></i>
                        Anggota
                    </button>
                </div>

                <!-- Username Field -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <input type="text"
                               class="form-control"
                               id="username"
                               name="username"
                               placeholder="Masukkan username Anda"
                               required
                               autocomplete="username">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="error-message" id="usernameError">Username wajib diisi</div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               placeholder="Masukkan password Anda"
                               required
                               autocomplete="current-password">
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="error-message" id="passwordError">Password wajib diisi</div>
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <span>Masuk Sekarang</span>
                </button>

                <!-- Footer -->
                <div class="form-footer">
                    Belum punya akun?
                    <a href="index.php?controller=auth&action=register">Daftar sebagai Anggota</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Show flash messages on load
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flash_error): ?>
                showAlert('error', '<?= addslashes($flash_error) ?>');
            <?php endif; ?>

            <?php if ($flash_success): ?>
                showAlert('success', '<?= addslashes($flash_success) ?>');
            <?php endif; ?>

            // Set initial focus
            document.getElementById('username').focus();
        });

        // Role Selection
        function selectRole(role) {
            // Update hidden radio inputs
            document.querySelectorAll('input[name="role"]').forEach(input => {
                input.checked = (input.value === role);
            });

            // Update visual tabs
            document.querySelectorAll('.role-tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.dataset.role === role) {
                    tab.classList.add('active');
                }
            });
        }

        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form Validation
        const loginForm = document.getElementById('loginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');

        function validateField(input, errorId) {
            const value = input.value.trim();
            const errorElement = document.getElementById(errorId);

            if (value === '') {
                input.classList.add('is-invalid');
                return false;
            } else {
                input.classList.remove('is-invalid');
                return true;
            }
        }

        // Real-time validation
        usernameInput.addEventListener('blur', () => validateField(usernameInput, 'usernameError'));
        passwordInput.addEventListener('blur', () => validateField(passwordInput, 'passwordError'));

        usernameInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this, 'usernameError');
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this, 'passwordError');
            }
        });

        // Form Submit
        loginForm.addEventListener('submit', function(e) {
            const isUsernameValid = validateField(usernameInput, 'usernameError');
            const isPasswordValid = validateField(passwordInput, 'passwordError');

            if (!isUsernameValid || !isPasswordValid) {
                e.preventDefault();

                // Focus first invalid field
                if (!isUsernameValid) {
                    usernameInput.focus();
                } else if (!isPasswordValid) {
                    passwordInput.focus();
                }

                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="spinner me-2"></div><span>Memproses...</span>';
        });

        // Custom Alert Function
        function showAlert(type, message) {
            const existingAlert = document.querySelector('.alert-custom');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alert = document.createElement('div');
            alert.className = 'alert-custom alert-' + type;
            alert.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'circle-exclamation' : 'circle-check'}"></i>
                <span>${message}</span>
            `;

            const form = document.getElementById('loginForm');
            form.insertBefore(alert, form.firstChild);

            // Auto remove after 5 seconds
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }

        // Enter key navigation
        usernameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                passwordInput.focus();
            }
        });

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
