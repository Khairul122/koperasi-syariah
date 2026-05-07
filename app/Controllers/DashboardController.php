<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use PDO;
use PDOException;

class DashboardController
{
    private $dashboardModel;

    public function __construct($database)
    {
        $this->dashboardModel = new DashboardModel($database);
    }

    /**
     * Check if user is logged in
     */
    private function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Get current logged in user
     */
    private function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? ''
            ];
        }
        return null;
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    private function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    /**
     * Main dashboard entry point - redirect based on role
     */
    public function index()
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $role = $user['role'];

        // Redirect based on role
        if ($role === 'admin') {
            header('Location: ' . BASE_URL . '/dashboard/admin');
        } elseif ($role === 'client') {
            header('Location: ' . BASE_URL . '/dashboard/client');
        } else {
            // Default to client dashboard if role is unknown
            header('Location: ' . BASE_URL . '/dashboard/client');
        }
        exit();
    }

    /**
     * Admin Dashboard
     */
    public function admin()
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();

        // Check if user is admin
        if ($user['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman admin';
            header('Location: ' . BASE_URL . '/dashboard/client');
            exit();
        }

        // Get admin dashboard data
        $data = $this->dashboardModel->getAdminSummary();

        // Prepare variables for view
        $userName = $user['name'];
        $userEmail = $user['email'];
        $userRole = $user['role'];

        // Pass data to view
        require_once BASE_PATH . 'app/Views/dashboard/admin.php';
    }

    /**
     * Client Dashboard
     */
    public function client()
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();

        // Get client dashboard data
        $data = $this->dashboardModel->getClientSummary($user['id']);

        // Prepare variables for view
        $userName = $user['name'];
        $userEmail = $user['email'];
        $userRole = $user['role'];
        $userPhone = $_SESSION['user_phone'] ?? '';

        // Pass data to view
        require_once BASE_PATH . 'app/Views/dashboard/client.php';
    }

    /**
     * Unauthorized / Access Denied page
     */
    public function unauthorized()
    {
        $this->requireAuth();
        require_once BASE_PATH . 'app/Views/dashboard/unauthorized.php';
    }

    /**
     * Logout
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
     * Get user session data for use in views
     */
    public function getUserSession()
    {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? '',
            'phone' => $_SESSION['user_phone'] ?? ''
        ];
    }

    /**
     * Require specific role - redirect if user doesn't have required role
     */
    public function requireRole($allowedRoles)
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();

        if (!in_array($user['role'], $allowedRoles)) {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini';
            header('Location: ' . BASE_URL . '/dashboard/unauthorized');
            exit();
        }
    }

    /**
     * Update last login time
     */
    public function updateLoginTime($userId)
    {
        return $this->dashboardModel->updateLastLogin($userId);
    }

    /**
     * Get all users (admin only)
     */
    public function getAllUsers()
    {
        $this->requireRole(['admin']);

        try {
            $stmt = $this->dashboardModel->db->prepare("
                SELECT id, name, email, role, phone_number, created_at, updated_at
                FROM users
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all users: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user statistics
     */
    public function getStats()
    {
        $this->requireRole(['admin']);
        return $this->dashboardModel->getUserStats();
    }
}
