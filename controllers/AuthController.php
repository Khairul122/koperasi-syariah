<?php
/**
 * AuthController - Controller untuk otentikasi
 * Handle: Login, Register, Logout
 */
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    /**
     * Halaman Index Auth (Default)
     * URL: index.php?controller=auth
     *
     * Akan redirect user berdasarkan status login:
     * - Sudah login -> Redirect ke dashboard sesuai role
     * - Belum login -> Redirect ke halaman login
     */
    public function index(): void
    {
        if ($this->isLoggedIn()) {
            // User sudah login, arahkan ke dashboard
            $this->redirectToDashboard();
        } else {
            // User belum login, arahkan ke halaman login
            $this->redirect('auth', 'login');
        }
    }

    /**
     * Halaman Login
     * URL: index.php?controller=auth&action=login
     */
    public function login(): void
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->showLoginForm();
        }
    }

    /**
     * Handle Login POST
     */
    private function handleLogin(): void
    {
        $username = $this->sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'petugas';
        $remember = isset($_POST['remember']);

        // Validasi input
        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Username dan password harus diisi');
            $this->redirect('auth', 'login');
            return;
        }

        // Cek login untuk petugas atau anggota
        $result = $this->authModel->login($username, $password, $role);

        if ($result['status']) {
            // Set session
            $user = $result['data'];
            $this->setUserSession($user);

            // Set remember me cookie (7 hari)
            if ($remember) {
                $this->setRememberMe($user['id'], $role);
            }

            // Log activity
            $this->logActivity('login', $user['id'], $role);

            $this->setFlash('success', "Selamat datang, {$user['nama_lengkap']}!");
            $this->redirectToDashboard();

        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('auth', 'login');
        }
    }

    /**
     * Set user session
     */
    private function setUserSession(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'petugas') {
            $_SESSION['level'] = $user['level'];
        } else {
            $_SESSION['no_anggota'] = $user['no_anggota'];
        }

        $_SESSION['login_time'] = time();
    }

    /**
     * Halaman Register (untuk anggota baru)
     * URL: index.php?controller=auth&action=register
     */
    public function register(): void
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
        } else {
            $this->showRegisterForm();
        }
    }

    /**
     * Handle Register POST
     */
    private function handleRegister(): void
    {
        $data = [
            'nik' => $this->sanitizeInput($_POST['nik'] ?? ''),
            'nama_lengkap' => $this->sanitizeInput($_POST['nama_lengkap'] ?? ''),
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
            'tempat_lahir' => $this->sanitizeInput($_POST['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '',
            'alamat' => $this->sanitizeInput($_POST['alamat'] ?? ''),
            'no_hp' => $this->sanitizeInput($_POST['no_hp'] ?? ''),
            'pekerjaan' => $this->sanitizeInput($_POST['pekerjaan'] ?? ''),
            'username' => $this->sanitizeInput($_POST['username'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? ''
        ];

        // Validasi input
        $validation = $this->validateRegisterData($data);
        if (!$validation['valid']) {
            $this->setFlash('error', $validation['message']);
            $this->redirect('auth', 'register');
            return;
        }

        // Register user
        unset($data['password_confirm']);
        $result = $this->authModel->register($data);

        if ($result['status']) {
            $this->setFlash('success', 'Pendaftaran berhasil! Silakan login dengan No. Anggota: ' . $result['data']['no_anggota']);
            $this->redirect('auth', 'login');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('auth', 'register');
        }
    }

    /**
     * Validasi data register
     */
    private function validateRegisterData(array $data): array
    {
        // Validasi required fields
        $required = ['nik', 'nama_lengkap', 'jenis_kelamin', 'username', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['valid' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' harus diisi'];
            }
        }

        // Validasi NIK (16 digit)
        if (!preg_match('/^[0-9]{16}$/', $data['nik'])) {
            return ['valid' => false, 'message' => 'NIK harus 16 digit angka'];
        }

        // Validasi username (alphanumeric, min 4 karakter)
        if (!preg_match('/^[a-zA-Z0-9]{4,}$/', $data['username'])) {
            return ['valid' => false, 'message' => 'Username minimal 4 karakter alphanumeric'];
        }

        // Validasi password (min 6 karakter)
        if (strlen($data['password']) < 6) {
            return ['valid' => false, 'message' => 'Password minimal 6 karakter'];
        }

        // Validasi password confirmation
        if ($data['password'] !== $data['password_confirm']) {
            return ['valid' => false, 'message' => 'Konfirmasi password tidak cocok'];
        }

        // Validasi no HP (optional, jika diisi harus valid)
        if (!empty($data['no_hp']) && !preg_match('/^[0-9]{10,15}$/', $data['no_hp'])) {
            return ['valid' => false, 'message' => 'No. HP tidak valid (10-15 digit)'];
        }

        return ['valid' => true];
    }

    /**
     * Logout
     * URL: index.php?controller=auth&action=logout
     */
    public function logout(): void
    {
        if ($this->isLoggedIn()) {
            $this->logActivity('logout', $_SESSION['user_id'], $_SESSION['role']);
            $this->authModel->logout();
        }

        // Hapus remember me cookie
        $this->clearRememberMe();

        $this->setFlash('success', 'Berhasil logout');
        $this->redirect('auth', 'login');
    }

    /**
     * Tampilkan form login
     */
    private function showLoginForm(): void
    {
        $page_title = 'Login - Koperasi Syariah';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Tampilkan form register
     */
    private function showRegisterForm(): void
    {
        $page_title = 'Daftar Anggota - Koperasi Syariah';
        require_once __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Cek apakah user sudah login
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    /**
     * Redirect ke dashboard berdasarkan role
     * - Petugas level Admin -> dashboard/admin
     * - Petugas level Bendahara -> dashboard/bendahara
     * - Anggota -> dashboard/anggota
     */
    private function redirectToDashboard(): void
    {
        $role = $_SESSION['role'] ?? '';

        switch ($role) {
            case 'petugas':
                $level = $_SESSION['level'] ?? '';
                if ($level === 'Admin') {
                    $this->redirect('dashboard', 'admin');
                } else {
                    $this->redirect('dashboard', 'bendahara');
                }
                break;

            case 'anggota':
                $this->redirect('dashboard', 'anggota');
                break;

            default:
                $this->redirect('auth', 'login');
                break;
        }
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMe(int $userId, string $role): void
    {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (86400 * 7); // 7 hari

        $cookieData = json_encode([
            'user_id' => $userId,
            'role' => $role,
            'token' => $token
        ]);

        setcookie('remember_me', $cookieData, $expiry, '/', '', false, true);
    }

    /**
     * Clear remember me cookie
     */
    private function clearRememberMe(): void
    {
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        }
    }

    /**
     * Log activity
     */
    private function logActivity(string $action, int $userId, string $role): void
    {
        // Implementasi logging aktivitas user
        // Bisa disimpan ke tabel tb_audit_log
        $logFile = __DIR__ . '/../logs/auth.log';
        $logDir = dirname($logFile);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$action} | User ID: {$userId} | Role: {$role} | IP: {$_SERVER['REMOTE_ADDR']}\n";

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Set flash message
     */
    private function setFlash(string $type, string $message): void
    {
        $_SESSION["flash_{$type}"] = $message;
    }

    /**
     * Sanitize input
     */
    private function sanitizeInput(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Redirect ke controller/action tertentu
     */
    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
