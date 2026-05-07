<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Synectra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .register-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }

        /* Left Side - Branding */
        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            overflow: hidden;
            min-height: 100vh;
        }

        /* Animated Background Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .shape-1 {
            width: 500px;
            height: 500px;
            top: -250px;
            left: -250px;
            background: rgba(255, 255, 255, 0.1);
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
        }

        .brand-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 40px;
            opacity: 0; /* Hidden initially, will be animated by anime.js */
        }

        .brand-logo {
            font-size: 64px;
            font-weight: 800;
            margin-bottom: 20px;
            color: #ffffff;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .brand-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            opacity: 0.95;
        }

        .brand-subtitle {
            font-size: 16px;
            opacity: 0.85;
            line-height: 1.6;
            max-width: 400px;
            margin: 0 auto;
        }

        .brand-features {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            opacity: 0;
        }

        .feature-item:nth-child(1) { opacity: 0; }
        .feature-item:nth-child(2) { opacity: 0; }
        .feature-item:nth-child(3) { opacity: 0; }

        .feature-item i {
            font-size: 24px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .feature-item span {
            font-size: 14px;
            font-weight: 500;
        }

        /* Floating Icons */
        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .floating-icon {
            position: absolute;
            font-size: 24px;
            opacity: 0;
            color: white;
        }

        .floating-icon:nth-child(1) { top: 20%; left: 15%; }
        .floating-icon:nth-child(2) { top: 40%; left: 25%; }
        .floating-icon:nth-child(3) { top: 60%; left: 10%; }
        .floating-icon:nth-child(4) { top: 30%; left: 40%; }
        .floating-icon:nth-child(5) { top: 70%; left: 30%; }
        .floating-icon:nth-child(6) { top: 50%; left: 20%; }

        /* Right Side - Register Form */
        .form-section {
            flex: 1;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            min-height: 100vh;
            overflow-y: auto;
        }

        .form-wrapper {
            width: 100%;
            max-width: 450px;
            opacity: 0; /* Hidden initially, will be animated by anime.js */
        }

        .form-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .form-header p {
            font-size: 14px;
            color: #718096;
        }

        .form-header a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .form-header a:hover {
            color: #764ba2;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.icon-left {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 18px;
            transition: all 0.3s;
            pointer-events: none;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #2d3748;
            background: #f7fafc;
            transition: all 0.3s;
            outline: none;
        }

        /* Password field needs extra padding on right for toggle button */
        .form-control.has-toggle {
            padding-right: 50px;
        }

        .form-control:focus {
            border-color: #1e3a8a;
            background: white;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        .form-control:focus + i.icon-left {
            color: #1e3a8a;
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            padding: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .password-toggle:hover {
            color: #1e3a8a;
        }

        /* Terms Checkbox */
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
        }

        .terms-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #1e3a8a;
        }

        .terms-checkbox label {
            font-size: 13px;
            color: #4a5568;
            line-height: 1.5;
            cursor: pointer;
            margin: 0;
        }

        .terms-checkbox a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-register {
            width: 100%;
            padding: 16px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-register:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert/Toast */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: none;
            opacity: 0;
            transform: translateY(-10px);
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .brand-section {
                display: none;
            }

            .form-section {
                flex: 1;
            }

            .form-wrapper {
                max-width: 500px;
            }
        }

        @media (max-width: 480px) {
            body {
                height: auto;
                min-height: 100vh;
            }

            .register-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }

            .form-section {
                padding: 24px 20px;
                min-height: auto;
                display: block; /* Changed from flex to block for proper scrolling */
                overflow-y: visible;
                padding-top: 30px;
            }

            .form-wrapper {
                max-width: 100%;
                width: 100%;
                padding: 0;
            }

            .form-header h1 {
                font-size: 24px;
                margin-bottom: 8px;
            }

            .form-header p {
                font-size: 13px;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-group label {
                font-size: 13px;
                margin-bottom: 6px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .form-control {
                padding: 14px 16px 14px 44px;
                font-size: 14px;
            }

            .input-wrapper i.icon-left {
                font-size: 16px;
                left: 14px;
            }

            .password-toggle {
                right: 14px;
                font-size: 16px;
                width: 22px;
                height: 22px;
            }

            .btn-register {
                padding: 14px;
                font-size: 15px;
            }

            .terms-checkbox {
                margin-bottom: 20px;
            }

            .terms-checkbox label {
                font-size: 12px;
            }

            .alert {
                padding: 12px 14px;
                font-size: 13px;
                margin-bottom: 20px;
            }
        }

        /* Extra small devices (phones, less than 360px) */
        @media (max-width: 360px) {
            .form-header h1 {
                font-size: 22px;
            }

            .form-control {
                padding: 12px 14px 12px 40px;
                font-size: 14px;
            }

            .input-wrapper i.icon-left {
                left: 12px;
                font-size: 14px;
            }

            .btn-register {
                padding: 12px;
                font-size: 14px;
            }

            .form-header {
                margin-bottom: 24px;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .form-control {
                min-height: 48px; /* Apple recommendation */
            }

            .btn-register {
                min-height: 48px;
            }

            .password-toggle {
                min-width: 44px;
                min-height: 44px;
            }
        }

        /* Mobile-only header (visible only on small screens) */
        .mobile-header {
            display: none;
        }

        @media (max-width: 968px) {
            .mobile-header {
                display: block;
                text-align: center;
                padding: 30px 20px 20px;
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                color: white;
                position: relative;
                z-index: 10;
            }

            .mobile-header .logo {
                font-size: 32px;
                font-weight: 800;
                margin-bottom: 8px;
            }

            .mobile-header .tagline {
                font-size: 14px;
                opacity: 0.9;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Mobile Header (Visible Only on Small Screens) -->
    <div class="mobile-header">
        <div class="logo">
            <i class="fas fa-cube"></i> Synectra
        </div>
        <div class="tagline">Mulai Perjalanan Digital Anda</div>
    </div>

    <div class="register-container">
        <!-- Brand Section -->
        <div class="brand-section">
            <!-- Animated Shapes -->
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>

            <div class="floating-icons">
                <i class="fas fa-rocket floating-icon"></i>
                <i class="fas fa-lightbulb floating-icon"></i>
                <i class="fas fa-chart-line floating-icon"></i>
                <i class="fas fa-shield-alt floating-icon"></i>
                <i class="fas fa-users floating-icon"></i>
                <i class="fas fa-cog floating-icon"></i>
            </div>
            <div class="brand-content">
                <div class="brand-logo">
                    <i class="fas fa-cube"></i> Synectra
                </div>
                <h1 class="brand-title">Mulai Perjalanan Digital Anda</h1>
                <p class="brand-subtitle">
                    Daftar sekarang dan nikmati kemudahan mengelola bisnis dengan platform modern dan terintegrasi.
                </p>
                <div class="brand-features">
                    <div class="feature-item">
                        <i class="fas fa-bolt"></i>
                        <span>Proses registrasi cepat dan mudah</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-lock"></i>
                        <span>Keamanan data terjamin dengan enkripsi</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <span>Dukungan teknis 24/7 siap membantu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h1>Buat Akun Baru 🚀</h1>
                    <p>Lengkapi formulir di bawah untuk mendaftar</p>
                </div>

                <div id="alert" class="alert"></div>

                <form id="registerForm">
                    <input type="hidden" id="role" name="role" value="client">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <div class="input-wrapper">
                                <input
                                    type="text"
                                    id="name"
                                    class="form-control"
                                    placeholder="John Doe"
                                    required
                                >
                                <i class="fas fa-user icon-left"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Nomor Telepon</label>
                            <div class="input-wrapper">
                                <input
                                    type="tel"
                                    id="phone_number"
                                    class="form-control"
                                    placeholder="08123456789"
                                >
                                <i class="fas fa-phone icon-left"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                placeholder="nama@email.com"
                                required
                            >
                            <i class="fas fa-envelope icon-left"></i>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-wrapper">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control has-toggle"
                                    placeholder="Min. 6 karakter"
                                    required
                                    minlength="6"
                                >
                                <i class="fas fa-lock icon-left"></i>
                                <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <div class="input-wrapper">
                                <input
                                    type="password"
                                    id="confirm_password"
                                    class="form-control has-toggle"
                                    placeholder="Ulangi password"
                                    required
                                    minlength="6"
                                >
                                <i class="fas fa-lock icon-left"></i>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">
                            Saya menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> yang berlaku
                        </label>
                    </div>

                    <button type="submit" class="btn-register" id="registerBtn">
                        <span id="registerText">Daftar Sekarang</span>
                        <span id="registerSpinner" class="spinner" style="display: none;"></span>
                    </button>
                </form>

                <p style="text-align: center; margin-top: 30px; font-size: 14px; color: #718096;">
                    Sudah punya akun?
                    <a href="<?= BASE_URL ?>/login" style="color: #1e3a8a; font-weight: 600; text-decoration: none;">
                        Masuk Sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // ==================== ANIMATIONS WITH ANIME.JS ====================

        // Initial Page Load Animations
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Animate background shapes
            anime({
                targets: '.shape-1',
                translateX: [
                    { value: 30, duration: 10000 },
                    { value: -20, duration: 10000 }
                ],
                translateY: [
                    { value: -30, duration: 10000 },
                    { value: 20, duration: 10000 }
                ],
                rotate: {
                    value: '1turn',
                    duration: 20000,
                    easing: 'linear'
                },
                loop: true,
                direction: 'alternate',
                easing: 'easeInOutSine'
            });

            anime({
                targets: '.shape-2',
                translateX: [
                    { value: -25, duration: 8000 },
                    { value: 20, duration: 8000 }
                ],
                translateY: [
                    { value: 25, duration: 8000 },
                    { value: -15, duration: 8000 }
                ],
                rotate: {
                    value: '-1turn',
                    duration: 15000,
                    easing: 'linear'
                },
                loop: true,
                direction: 'alternate',
                easing: 'easeInOutQuad'
            });

            // 2. Animate floating icons with stagger
            anime({
                targets: '.floating-icon',
                opacity: [0, 0.2],
                translateY: [
                    { value: -20, duration: 3000 },
                    { value: 0, duration: 3000 }
                ],
                rotate: {
                    value: '1turn',
                    duration: 10000,
                    easing: 'linear'
                },
                delay: anime.stagger(1000, {start: 500}),
                loop: true,
                direction: 'alternate',
                easing: 'easeInOutSine'
            });

            // 3. Animate brand content
            anime({
                targets: '.brand-content',
                opacity: [0, 1],
                translateY: [30, 0],
                duration: 1200,
                easing: 'easeOutExpo'
            });

            // 4. Animate feature items with stagger
            anime({
                targets: '.feature-item',
                opacity: [0, 1],
                translateX: [-30, 0],
                delay: anime.stagger(200, {start: 800}),
                duration: 800,
                easing: 'easeOutExpo'
            });

            // 5. Animate form wrapper
            anime({
                targets: '.form-wrapper',
                opacity: [0, 1],
                translateX: [50, 0],
                duration: 1000,
                delay: 400,
                easing: 'easeOutExpo'
            });

            // 6. Stagger animation for form elements
            anime({
                targets: '.form-header, .form-group, .form-row, .terms-checkbox, .btn-register, .form-wrapper p',
                opacity: [0, 1],
                translateY: [20, 0],
                delay: anime.stagger(100, {start: 600}),
                duration: 800,
                easing: 'easeOutQuad'
            });
        });

        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const password = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Show Alert with Animation
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = `alert alert-${type}`;
            alert.style.display = 'block';

            // Animate in
            anime({
                targets: '#alert',
                opacity: [0, 1],
                translateY: [-10, 0],
                duration: 400,
                easing: 'easeOutExpo'
            });

            // Auto hide after 5 seconds
            setTimeout(() => {
                anime({
                    targets: '#alert',
                    opacity: 0,
                    translateY: -10,
                    duration: 300,
                    easing: 'easeInExpo',
                    complete: function() {
                        alert.style.display = 'none';
                    }
                });
            }, 5000);
        }

        // Set Loading State with Animation
        let spinnerAnimation = null;

        function setLoading(isLoading) {
            const registerBtn = document.getElementById('registerBtn');
            const registerText = document.getElementById('registerText');
            const registerSpinner = document.getElementById('registerSpinner');

            if (isLoading) {
                registerBtn.disabled = true;
                registerText.style.display = 'none';
                registerSpinner.style.display = 'inline-block';

                // Animate spinner with anime.js
                spinnerAnimation = anime({
                    targets: '#registerSpinner',
                    rotate: '1turn',
                    duration: 800,
                    loop: true,
                    easing: 'linear'
                });

                // Button scale animation
                anime({
                    targets: '#registerBtn',
                    scale: 0.98,
                    duration: 200,
                    easing: 'easeOutQuad'
                });
            } else {
                registerBtn.disabled = false;
                registerText.style.display = 'inline';
                registerSpinner.style.display = 'none';

                // Stop spinner animation
                if (spinnerAnimation) {
                    spinnerAnimation.pause();
                }

                // Button scale back
                anime({
                    targets: '#registerBtn',
                    scale: 1,
                    duration: 200,
                    easing: 'easeOutQuad'
                });
            }
        }

        // Input Focus Animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                anime({
                    targets: this.parentElement,
                    scale: 1.02,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });

            input.addEventListener('blur', function() {
                anime({
                    targets: this.parentElement,
                    scale: 1,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
        });

        // Button Hover Animation
        document.getElementById('registerBtn').addEventListener('mouseenter', function() {
            anime({
                targets: this,
                translateY: -2,
                duration: 300,
                easing: 'easeOutQuad'
            });
        });

        document.getElementById('registerBtn').addEventListener('mouseleave', function() {
            anime({
                targets: this,
                translateY: 0,
                duration: 300,
                easing: 'easeOutQuad'
            });
        });

        // Validate Email Format
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Real-time password validation with animation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            if (confirmPassword && password !== confirmPassword) {
                anime({
                    targets: this,
                    borderColor: '#fc8181',
                    duration: 300,
                    easing: 'easeOutQuad'
                });
                this.style.borderColor = '#fc8181';
            } else {
                anime({
                    targets: this,
                    borderColor: '#e2e8f0',
                    duration: 300,
                    easing: 'easeOutQuad'
                });
                this.style.borderColor = '#e2e8f0';
            }
        });

        // Handle Form Submit
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const phoneNumber = document.getElementById('phone_number').value.trim();
            const role = 'client'; // Fixed role for registration
            const terms = document.getElementById('terms').checked;

            // Validation
            if (!name || !email || !password || !confirmPassword) {
                showAlert('Semua field wajib diisi', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showAlert('Format email tidak valid', 'error');
                return;
            }

            if (password.length < 6) {
                showAlert('Password minimal 6 karakter', 'error');
                return;
            }

            if (password !== confirmPassword) {
                showAlert('Konfirmasi password tidak cocok', 'error');
                return;
            }

            if (!terms) {
                showAlert('Silakan setujui syarat & ketentuan', 'error');
                return;
            }

            setLoading(true);

            try {
                const response = await fetch('<?= BASE_URL ?>/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        confirm_password: confirmPassword,
                        role: role,
                        phone_number: phoneNumber
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message + '! Mengalihkan ke halaman login...', 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    showAlert(result.message, 'error');
                    setLoading(false);

                    // Shake animation on error
                    anime({
                        targets: '#registerForm',
                        translateX: [
                            { value: -10, duration: 100 },
                            { value: 10, duration: 100 },
                            { value: -10, duration: 100 },
                            { value: 10, duration: 100 },
                            { value: 0, duration: 100 }
                        ],
                        easing: 'easeInOutQuad'
                    });
                }
            } catch (error) {
                showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                setLoading(false);
            }
        });
    </script>
</body>
</html>
