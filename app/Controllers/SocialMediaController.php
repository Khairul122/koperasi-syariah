<?php

namespace App\Controllers;

use App\Models\SocialMediaModel;

class SocialMediaController
{
    private $socialMediaModel;

    public function __construct($database)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->socialMediaModel = new SocialMediaModel($database);
    }

    /**
     * Check if user is logged in
     */
    private function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    private function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['error_message'] = 'Silakan login terlebih dahulu';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    /**
     * Require admin role
     */
    private function requireAdmin()
    {
        $this->requireAuth();

        if ($_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman ini';
            header('Location: ' . BASE_URL . '/dashboard');
            exit();
        }
    }

    /**
     * Display all social media accounts
     */
    public function index()
    {
        $this->requireAdmin();

        $socialMedias = $this->socialMediaModel->getAll();

        // Debug: log hasil query
        if ($socialMedias === false) {
            error_log("SocialMedia getAll() returned false");
        } else {
            error_log("SocialMedia getAll() returned " . count($socialMedias) . " records");
        }

        // Prepare variables for view
        $pageTitle = 'Manajemen Social Media';
        $socialMedias = $socialMedias ?: [];

        require_once BASE_PATH . 'app/Views/social-media/index.php';
    }

    /**
     * Show create social media form
     */
    public function create()
    {
        $this->requireAdmin();

        $pageTitle = 'Tambah Social Media Baru';
        $socialMedia = null;
        $isEdit = false;

        require_once BASE_PATH . 'app/Views/social-media/form.php';
    }

    /**
     * Store new social media account
     */
    public function store()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        // Validate input
        $platformName = trim($_POST['platform_name'] ?? '');
        $accountName = trim($_POST['account_name'] ?? '');
        $profileUrl = trim($_POST['profile_url'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($platformName)) {
            $errors[] = 'Nama platform wajib diisi';
        } elseif ($this->socialMediaModel->platformExists($platformName)) {
            $errors[] = 'Platform sudah terdaftar';
        }

        if (empty($accountName)) {
            $errors[] = 'Nama akun wajib diisi';
        }

        if (empty($profileUrl)) {
            $errors[] = 'URL profil wajib diisi';
        } elseif (!filter_var($profileUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Format URL tidak valid';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/social-media/add');
            exit();
        }

        // Create social media
        $data = [
            'platform_name' => $platformName,
            'account_name' => $accountName,
            'profile_url' => $profileUrl,
            'icon' => $icon ?: null,
            'is_active' => $isActive
        ];

        if ($this->socialMediaModel->create($data)) {
            $_SESSION['success_message'] = 'Social media berhasil ditambahkan';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal menambahkan social media';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/social-media/add');
            exit();
        }
    }

    /**
     * Show edit social media form
     */
    public function edit()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID social media tidak ditemukan';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        $socialMedia = $this->socialMediaModel->getById($id);

        if (!$socialMedia) {
            $_SESSION['error_message'] = 'Social media tidak ditemukan';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        $pageTitle = 'Edit Social Media';
        $isEdit = true;

        require_once BASE_PATH . 'app/Views/social-media/form.php';
    }

    /**
     * Update social media account
     */
    public function update()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID social media tidak ditemukan';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        // Validate input
        $platformName = trim($_POST['platform_name'] ?? '');
        $accountName = trim($_POST['account_name'] ?? '');
        $profileUrl = trim($_POST['profile_url'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($platformName)) {
            $errors[] = 'Nama platform wajib diisi';
        } elseif ($this->socialMediaModel->platformExists($platformName, $id)) {
            $errors[] = 'Platform sudah digunakan oleh akun lain';
        }

        if (empty($accountName)) {
            $errors[] = 'Nama akun wajib diisi';
        }

        if (empty($profileUrl)) {
            $errors[] = 'URL profil wajib diisi';
        } elseif (!filter_var($profileUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Format URL tidak valid';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/social-media/edit?id=' . $id);
            exit();
        }

        // Update social media
        $data = [
            'platform_name' => $platformName,
            'account_name' => $accountName,
            'profile_url' => $profileUrl,
            'icon' => $icon ?: null,
            'is_active' => $isActive
        ];

        if ($this->socialMediaModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Social media berhasil diperbarui';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui social media';
            header('Location: ' . BASE_URL . '/social-media/edit?id=' . $id);
            exit();
        }
    }

    /**
     * Delete social media account
     */
    public function delete()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID social media tidak ditemukan';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        if ($this->socialMediaModel->delete($id)) {
            $_SESSION['success_message'] = 'Social media berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus social media';
        }

        header('Location: ' . BASE_URL . '/social-media');
        exit();
    }

    /**
     * Toggle social media active status
     */
    public function toggleActive()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID social media tidak ditemukan';
            header('Location: ' . BASE_URL . '/social-media');
            exit();
        }

        if ($this->socialMediaModel->toggleActive($id)) {
            $_SESSION['success_message'] = 'Status social media berhasil diperbarui';
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui status social media';
        }

        header('Location: ' . BASE_URL . '/social-media');
        exit();
    }

    /**
     * Get active social media accounts for dropdown (AJAX)
     */
    public function getActiveSocialMedia()
    {
        $this->requireAuth();

        $socialMedias = $this->socialMediaModel->getActiveSocialMedia();

        header('Content-Type: application/json');
        echo json_encode($socialMedias ?: []);
        exit();
    }
}
