<?php

namespace App\Controllers;

use App\Models\ContactPersonModel;

class ContactPersonController
{
    private $contactPersonModel;

    public function __construct($database)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->contactPersonModel = new ContactPersonModel($database);
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
     * Display all contact persons
     */
    public function index()
    {
        $this->requireAdmin();

        $contactPersons = $this->contactPersonModel->getAll();

        // Debug: log hasil query
        if ($contactPersons === false) {
            error_log("ContactPerson getAll() returned false");
        } else {
            error_log("ContactPerson getAll() returned " . count($contactPersons) . " records");
        }

        // Prepare variables for view
        $pageTitle = 'Manajemen Kontak Person';
        $contactPersons = $contactPersons ?: [];

        require_once BASE_PATH . 'app/Views/contactPerson/index.php';
    }

    /**
     * Show create contact person form
     */
    public function create()
    {
        $this->requireAdmin();

        $pageTitle = 'Tambah Kontak Person Baru';
        $contactPerson = null;
        $isEdit = false;

        require_once BASE_PATH . 'app/Views/contactPerson/form.php';
    }

    /**
     * Store new contact person
     */
    public function store()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        // Validate input
        $platform = trim($_POST['platform'] ?? '');
        $contactInfo = trim($_POST['contact_info'] ?? '');
        $linkUrl = trim($_POST['link_url'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($platform)) {
            $errors[] = 'Platform wajib diisi';
        } elseif ($this->contactPersonModel->platformExists($platform)) {
            $errors[] = 'Platform sudah terdaftar';
        }

        if (empty($contactInfo)) {
            $errors[] = 'Info kontak wajib diisi';
        }

        if (empty($linkUrl)) {
            $errors[] = 'Link URL wajib diisi';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/contact-person/add');
            exit();
        }

        // Create contact person
        $data = [
            'platform' => $platform,
            'contact_info' => $contactInfo,
            'link_url' => $linkUrl,
            'icon' => $icon ?: null,
            'is_active' => $isActive
        ];

        if ($this->contactPersonModel->create($data)) {
            $_SESSION['success_message'] = 'Kontak person berhasil ditambahkan';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal menambahkan kontak person';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/contact-person/add');
            exit();
        }
    }

    /**
     * Show edit contact person form
     */
    public function edit()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID kontak person tidak ditemukan';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        $contactPerson = $this->contactPersonModel->getById($id);

        if (!$contactPerson) {
            $_SESSION['error_message'] = 'Kontak person tidak ditemukan';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        $pageTitle = 'Edit Kontak Person';
        $isEdit = true;

        require_once BASE_PATH . 'app/Views/contactPerson/form.php';
    }

    /**
     * Update contact person
     */
    public function update()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID kontak person tidak ditemukan';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        // Validate input
        $platform = trim($_POST['platform'] ?? '');
        $contactInfo = trim($_POST['contact_info'] ?? '');
        $linkUrl = trim($_POST['link_url'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($platform)) {
            $errors[] = 'Platform wajib diisi';
        } elseif ($this->contactPersonModel->platformExists($platform, $id)) {
            $errors[] = 'Platform sudah digunakan oleh kontak lain';
        }

        if (empty($contactInfo)) {
            $errors[] = 'Info kontak wajib diisi';
        }

        if (empty($linkUrl)) {
            $errors[] = 'Link URL wajib diisi';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/contact-person/edit?id=' . $id);
            exit();
        }

        // Update contact person
        $data = [
            'platform' => $platform,
            'contact_info' => $contactInfo,
            'link_url' => $linkUrl,
            'icon' => $icon ?: null,
            'is_active' => $isActive
        ];

        if ($this->contactPersonModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Kontak person berhasil diperbarui';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui kontak person';
            header('Location: ' . BASE_URL . '/contact-person/edit?id=' . $id);
            exit();
        }
    }

    /**
     * Delete contact person
     */
    public function delete()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID kontak person tidak ditemukan';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        if ($this->contactPersonModel->delete($id)) {
            $_SESSION['success_message'] = 'Kontak person berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus kontak person';
        }

        header('Location: ' . BASE_URL . '/contact-person');
        exit();
    }

    /**
     * Toggle contact person active status
     */
    public function toggleActive()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID kontak person tidak ditemukan';
            header('Location: ' . BASE_URL . '/contact-person');
            exit();
        }

        if ($this->contactPersonModel->toggleActive($id)) {
            $_SESSION['success_message'] = 'Status kontak person berhasil diperbarui';
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui status kontak person';
        }

        header('Location: ' . BASE_URL . '/contact-person');
        exit();
    }

    /**
     * Get active contact persons for dropdown (AJAX)
     */
    public function getActiveContactPersons()
    {
        $this->requireAuth();

        $contactPersons = $this->contactPersonModel->getActiveContactPersons();

        header('Content-Type: application/json');
        echo json_encode($contactPersons ?: []);
        exit();
    }
}
