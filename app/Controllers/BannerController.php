<?php

namespace App\Controllers;

use App\Models\BannerModel;
use Exception;

class BannerController
{
    private $bannerModel;
    private $dbConnection;

    public function __construct($dbConnection)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Debug: Log connection type
        error_log("BannerController initialized with: " . (is_object($dbConnection) ? get_class($dbConnection) : gettype($dbConnection)));

        // Store database connection for debugging
        $this->dbConnection = $dbConnection;

        $this->bannerModel = new BannerModel($dbConnection);
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
     * Display all banners
     */
    public function index()
    {
        $this->requireAdmin();

        $banners = $this->bannerModel->getAll();

        // Prepare variables for view
        $pageTitle = 'Manajemen Banner';
        $banners = $banners ?: [];

        require_once BASE_PATH . 'app/Views/banner/index.php';
    }

    /**
     * Show create banner form
     */
    public function create()
    {
        $this->requireAdmin();

        $pageTitle = 'Tambah Banner Baru';
        $banner = null;
        $isEdit = false;

        require_once BASE_PATH . 'app/Views/banner/form.php';
    }

    /**
     * Store new banner
     */
    public function store()
    {
        try {
            $this->requireAdmin();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/banner');
                exit();
            }

            // 🔴 DEBUG: Log semua data yang masuk
            error_log("=== BANNER CREATE DEBUG START ===");
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));

        // Validate input
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $linkUrl = trim($_POST['link_url'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        error_log("Validated data - title: $title, isActive: $isActive");

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Judul banner wajib diisi';
            error_log("Error: Title is empty");
        }

        if (!empty($errors)) {
            error_log("Validation failed: " . print_r($errors, true));
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/banner/add');
            exit();
        }

        // Handle image upload
        $imagePath = null;

        // Debug: Log upload info
        error_log("Banner upload - FILES: " . print_r($_FILES, true));

        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/banners/';

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    $errors[] = 'Gagal membuat folder upload. Hubungi administrator.';
                    $_SESSION['errors'] = $errors;
                    $_SESSION['old_input'] = $_POST;
                    header('Location: ' . BASE_URL . '/banner/add');
                    exit();
                }
            }

            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                $errors[] = 'Folder upload tidak writable. Hubungi administrator.';
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . BASE_URL . '/banner/add');
                exit();
            }

            $fileExtension = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = uniqid('banner_') . '_' . time() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                error_log("Attempting to upload to: " . $filePath);

                if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $filePath)) {
                    $imagePath = $filePath; // ✅ FIX: Simpan full path, bukan hanya nama file
                    error_log("Upload success: " . $imagePath);
                } else {
                    $errors[] = 'Gagal memindahkan file yang diupload.';
                    error_log("move_uploaded_file failed for: " . $filePath);
                    $_SESSION['errors'] = $errors;
                    $_SESSION['old_input'] = $_POST;
                    header('Location: ' . BASE_URL . '/banner/add');
                    exit();
                }
            } else {
                $errors[] = 'Format gambar tidak valid. Gunakan JPG, PNG, GIF, atau WEBP';
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . BASE_URL . '/banner/add');
                exit();
            }
        } else {
            // Cek error upload
            $uploadError = $_FILES['banner_image']['error'] ?? null;
            error_log("Banner upload error code: " . $uploadError);

            if ($uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE) {
                $errors[] = 'Ukuran file terlalu besar. Maksimal 5MB.';
            } elseif ($uploadError === UPLOAD_ERR_NO_FILE) {
                $errors[] = 'Gambar banner wajib diunggah';
            } else {
                $errors[] = 'Gagal mengupload gambar. Error code: ' . $uploadError;
            }

            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/banner/add');
            exit();
        }

        // Create banner
        $data = [
            'title' => $title,
            'description' => $description ?: null,
            'image_path' => $imagePath,
            'link_url' => $linkUrl ?: null,
            'is_active' => $isActive
        ];

        error_log("Banner data to save: " . print_r($data, true));

        if ($this->bannerModel->create($data)) {
            $_SESSION['success_message'] = 'Banner berhasil ditambahkan';
            error_log("Banner saved successfully, redirecting to index...");
            session_write_close(); // Ensure session is saved before redirect
            header('Location: ' . BASE_URL . '/banner');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal menambahkan banner. Silakan cek error log untuk detail.';
            error_log("Banner creation failed. Data: " . print_r($data, true));
            $_SESSION['old_input'] = $_POST;
            session_write_close(); // Ensure session is saved before redirect
            header('Location: ' . BASE_URL . '/banner/add');
            exit();
        }
        } catch (Exception $e) {
            // Catch any unexpected exceptions
            error_log("=== EXCEPTION IN BANNER STORE ===");
            error_log("Exception Message: " . $e->getMessage());
            error_log("Exception Code: " . $e->getCode());
            error_log("Exception File: " . $e->getFile() . " Line: " . $e->getLine());
            error_log("Exception Trace: " . $e->getTraceAsString());

            $_SESSION['error_message'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST ?? [];
            header('Location: ' . BASE_URL . '/banner/add');
            exit();
        }
    }

    /**
     * Show edit banner form
     */
    public function edit()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID banner tidak ditemukan';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        $banner = $this->bannerModel->getById($id);

        if (!$banner) {
            $_SESSION['error_message'] = 'Banner tidak ditemukan';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        $pageTitle = 'Edit Banner';
        $isEdit = true;

        require_once BASE_PATH . 'app/Views/banner/form.php';
    }

    /**
     * Update banner
     */
    public function update()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID banner tidak ditemukan';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        // Validate input
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $linkUrl = trim($_POST['link_url'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Judul banner wajib diisi';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/banner/edit?id=' . $id);
            exit();
        }

        // Get existing banner data
        $existingBanner = $this->bannerModel->getById($id);
        if (!$existingBanner) {
            $_SESSION['error_message'] = 'Banner tidak ditemukan';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        // Handle image upload
        $imagePath = $existingBanner['image_path']; // Keep existing image by default

        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/banners/';

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                // Delete old image if exists
                if (!empty($existingBanner['image_path']) && file_exists($existingBanner['image_path'])) {
                    unlink($existingBanner['image_path']);
                }

                $fileName = uniqid('banner_') . '_' . time() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $filePath)) {
                    $imagePath = $filePath; // ✅ FIX: Simpan full path
                }
            }
        }

        // Update banner
        $data = [
            'title' => $title,
            'description' => $description,
            'image_path' => $imagePath,
            'link_url' => $linkUrl,
            'is_active' => $isActive
        ];

        if ($this->bannerModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Banner berhasil diperbarui';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui banner';
            header('Location: ' . BASE_URL . '/banner/edit?id=' . $id);
            exit();
        }
    }

    /**
     * Delete banner
     */
    public function delete()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID banner tidak ditemukan';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        // Get banner data for image deletion
        $banner = $this->bannerModel->getById($id);
        if ($banner) {
            // Delete image file if exists
            if (!empty($banner['image_path']) && file_exists($banner['image_path'])) {
                unlink($banner['image_path']); // ✅ FIX: Path sudah lengkap dari database
            }
        }

        if ($this->bannerModel->delete($id)) {
            $_SESSION['success_message'] = 'Banner berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus banner';
        }

        header('Location: ' . BASE_URL . '/banner');
        exit();
    }

    /**
     * Toggle banner active status
     */
    public function toggleActive()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID banner tidak ditemukan';
            header('Location: ' . BASE_URL . '/banner');
            exit();
        }

        if ($this->bannerModel->toggleActive($id)) {
            $_SESSION['success_message'] = 'Status banner berhasil diperbarui';
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui status banner';
        }

        header('Location: ' . BASE_URL . '/banner');
        exit();
    }

    /**
     * Get active banners for dropdown (AJAX)
     */
    public function getActiveBanners()
    {
        $this->requireAuth();

        $banners = $this->bannerModel->getActiveBanners();

        header('Content-Type: application/json');
        echo json_encode($banners ?: []);
        exit();
    }
}
