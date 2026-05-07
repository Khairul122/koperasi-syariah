<?php

namespace App\Controllers;

use App\Models\AuthModel;

class AuthController
{
    private $authModel;

    public function __construct($database)
    {
        $this->authModel = new AuthModel($database);
    }

    /**
     * Display login page
     */
    public function index()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirectBasedOnRole();
            return;
        }

        require_once BASE_PATH . 'app/Views/auth/login.php';
    }

    /**
     * Display register page
     */
    public function register()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirectBasedOnRole();
            return;
        }

        require_once BASE_PATH . 'app/Views/auth/register.php';
    }

    /**
     * Handle login form submission
     */
    public function login()
    {
        header('Content-Type: application/json');

        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate input
        if (empty($input['email']) || empty($input['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Email dan password wajib diisi'
            ]);
            return;
        }

        // Sanitize email
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $password = $input['password'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Format email tidak valid'
            ]);
            return;
        }

        // Attempt login
        $user = $this->authModel->login($email, $password);

        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_phone'] = $user['phone_number'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();

            // Update last login
            $this->authModel->updateLastLogin($user['id']);

            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => $this->getRedirectUrl($user['role'])
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Email atau password salah'
            ]);
        }
    }

    /**
     * Handle registration form submission
     */
    public function doRegister()
    {
        header('Content-Type: application/json');

        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        $required_fields = ['name', 'email', 'password', 'confirm_password', 'role'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                echo json_encode([
                    'success' => false,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi'
                ]);
                return;
            }
        }

        // Sanitize inputs
        $name = htmlspecialchars(strip_tags($input['name']));
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $password = $input['password'];
        $confirmPassword = $input['confirm_password'];
        $role = $input['role'];
        $phoneNumber = !empty($input['phone_number']) ? htmlspecialchars(strip_tags($input['phone_number'])) : null;

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Format email tidak valid'
            ]);
            return;
        }

        // Validate password strength
        if (strlen($password) < 6) {
            echo json_encode([
                'success' => false,
                'message' => 'Password minimal 6 karakter'
            ]);
            return;
        }

        // Validate password confirmation
        if ($password !== $confirmPassword) {
            echo json_encode([
                'success' => false,
                'message' => 'Konfirmasi password tidak cocok'
            ]);
            return;
        }

        // Validate role
        if (!in_array($role, ['admin', 'client'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Role tidak valid'
            ]);
            return;
        }

        // Check if email already exists
        if ($this->authModel->emailExists($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Email sudah terdaftar'
            ]);
            return;
        }

        // Register user
        $userId = $this->authModel->register([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'phone_number' => $phoneNumber
        ]);

        if ($userId) {
            echo json_encode([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login',
                'redirect' => BASE_URL . '/login'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        // Unset all session variables
        $_SESSION = [];

        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        // Destroy session
        session_destroy();

        // Redirect to login page
        header('Location: ' . BASE_URL . '/login');
        exit();
    }

    /**
     * Check if user is logged in
     */
    private function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole()
    {
        $role = $_SESSION['user_role'] ?? 'client';
        $redirectUrl = $this->getRedirectUrl($role);
        header('Location: ' . $redirectUrl);
        exit();
    }

    /**
     * Get redirect URL based on role
     */
    private function getRedirectUrl($role)
    {
        switch ($role) {
            case 'admin':
                return BASE_URL . '/dashboard';
            case 'client':
                return BASE_URL . '/dashboard';
            default:
                return BASE_URL;
        }
    }

    /**
     * Get current logged in user
     */
    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role'],
                'phone_number' => $_SESSION['user_phone'] ?? null
            ];
        }
        return null;
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    public function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    /**
     * Require specific role - redirect if user doesn't have required role
     */
    public function requireRole($allowedRoles)
    {
        $this->requireAuth();

        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            // Redirect to unauthorized page or dashboard
            header('Location: ' . BASE_URL . '/dashboard?error=unauthorized');
            exit();
        }
    }
}
