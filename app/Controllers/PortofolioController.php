<?php

namespace App\Controllers;

use App\Models\PortofolioModel;

class PortofolioController
{
    private $portofolioModel;

    public function __construct($database)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->portofolioModel = new PortofolioModel($database);
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
     * Display all portfolios
     */
    public function index()
    {
        $this->requireAdmin();

        // Get session messages
        $success_message = $_SESSION['success_message'] ?? null;
        $error_message   = $_SESSION['error_message'] ?? null;
        $errors          = $_SESSION['errors'] ?? null;
        
        // Clear session messages after reading
        unset($_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['errors']);

        $portfolios = $this->portofolioModel->getAll();

        // Debug: log hasil query
        if ($portfolios === false) {
            error_log("Portofolio getAll() returned false");
            $portfolios = [];
        } else {
            error_log("Portofolio getAll() returned " . count($portfolios) . " records");
        }

        // Prepare variables for view
        $pageTitle = 'Manajemen Portofolio';
        $portfolios = $portfolios ?: [];

        require_once BASE_PATH . 'app/Views/portofolio/index.php';
    }

    /**
     * Show create portfolio form
     */
    public function create()
    {
        $this->requireAdmin();

        $pageTitle = 'Tambah Portofolio Baru';
        $portfolio = null;
        $isEdit = false;
        $categories = $this->portofolioModel->getCategories();

        require_once BASE_PATH . 'app/Views/portofolio/form.php';
    }

    /**
     * Store new portfolio with multiple images
     */
    public function store()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        // Validate input
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Judul portofolio wajib diisi';
        }

        // Handle multiple file uploads
        $imagePaths = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploadDir = 'uploads/portofolio/';

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $files = $_FILES['images'];
            $fileCount = count($files['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $fileExtension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $errors[] = "File ke-" . ($i + 1) . ": Format tidak valid. Gunakan JPG, PNG, GIF, atau WEBP";
                        continue;
                    }

                    // Generate unique filename
                    $fileName = uniqid('portfolio_') . '_' . time() . '_' . $i . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;

                    // Move uploaded file
                    if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                        $imagePaths[] = $uploadPath;
                    } else {
                        $errors[] = "File ke-" . ($i + 1) . ": Gagal mengupload gambar";
                    }
                }
            }
        }

        if (empty($imagePaths)) {
            $errors[] = 'Minimal upload 1 gambar portofolio';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/portofolio/add');
            exit();
        }

        // Create portfolio
        $data = [
            'title' => $title,
            'description' => $description ?: null,
            'category' => $category ?: null,
            'images' => $imagePaths
        ];

        if ($this->portofolioModel->create($data)) {
            $_SESSION['success_message'] = 'Portofolio berhasil ditambahkan dengan ' . count($imagePaths) . ' gambar';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal menambahkan portofolio';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/portofolio/add');
            exit();
        }
    }

    /**
     * Show edit portfolio form
     */
    public function edit()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        $portfolio = $this->portofolioModel->getById($id);

        if (!$portfolio) {
            $_SESSION['error_message'] = 'Portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        $pageTitle = 'Edit Portofolio';
        $isEdit = true;
        $categories = $this->portofolioModel->getCategories();

        require_once BASE_PATH . 'app/Views/portofolio/form.php';
    }

    /**
     * Update portfolio
     */
    public function update()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        // Validate input
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Judul portofolio wajib diisi';
        }

        // Get existing portfolio
        $existingPortfolio = $this->portofolioModel->getById($id);
        if (!$existingPortfolio) {
            $_SESSION['error_message'] = 'Portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        // Handle additional file uploads
        $newImagePaths = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploadDir = 'uploads/portofolio/';

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $files = $_FILES['images'];
            $fileCount = count($files['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $fileExtension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $errors[] = "File ke-" . ($i + 1) . ": Format tidak valid";
                        continue;
                    }

                    // Generate unique filename
                    $fileName = uniqid('portfolio_') . '_' . time() . '_' . $i . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;

                    // Move uploaded file
                    if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                        $newImagePaths[] = $uploadPath;
                    }
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/portofolio/edit?id=' . $id);
            exit();
        }

        // Update portfolio
        $data = [
            'title' => $title,
            'description' => $description ?: null,
            'category' => $category ?: null
        ];

        // Add new images if any
        if (!empty($newImagePaths)) {
            $data['new_images'] = $newImagePaths;
        }

        if ($this->portofolioModel->update($id, $data)) {
            $message = 'Portofolio berhasil diperbarui';
            if (!empty($newImagePaths)) {
                $message .= ' dengan ' . count($newImagePaths) . ' gambar baru';
            }
            $_SESSION['success_message'] = $message;
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui portofolio';
            header('Location: ' . BASE_URL . '/portofolio/edit?id=' . $id);
            exit();
        }
    }

    /**
     * Delete portfolio
     */
    public function delete()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        if ($this->portofolioModel->delete($id)) {
            $_SESSION['success_message'] = 'Portofolio berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus portofolio';
        }

        header('Location: ' . BASE_URL . '/portofolio');
        exit();
    }

    /**
     * Delete individual image from portfolio
     */
    public function deleteImage()
    {
        $this->requireAdmin();

        $imageId = $_GET['image_id'] ?? null;
        $portfolioId = $_GET['portfolio_id'] ?? null;

        if (!$imageId || !$portfolioId) {
            $_SESSION['error_message'] = 'ID gambar atau portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        if ($this->portofolioModel->deleteImage($imageId)) {
            $_SESSION['success_message'] = 'Gambar berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus gambar';
        }

        header('Location: ' . BASE_URL . '/portofolio/edit?id=' . $portfolioId);
        exit();
    }

    /**
     * Set image as primary
     */
    public function setPrimary()
    {
        $this->requireAdmin();

        $imageId = $_GET['image_id'] ?? null;
        $portfolioId = $_GET['portfolio_id'] ?? null;

        if (!$imageId || !$portfolioId) {
            $_SESSION['error_message'] = 'ID gambar atau portofolio tidak ditemukan';
            header('Location: ' . BASE_URL . '/portofolio');
            exit();
        }

        if ($this->portofolioModel->setPrimaryImage($imageId, $portfolioId)) {
            $_SESSION['success_message'] = 'Gambar utama berhasil diperbarui';
        } else {
            $_SESSION['error_message'] = 'Gagal mengatur gambar utama';
        }

        header('Location: ' . BASE_URL . '/portofolio/edit?id=' . $portfolioId);
        exit();
    }

    /**
     * Update image sort order (AJAX)
     */
    public function updateImageOrder()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }

        $imageId = $_POST['image_id'] ?? null;
        $newOrder = $_POST['new_order'] ?? null;

        if (!$imageId || $newOrder === null) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit();
        }

        if ($this->portofolioModel->updateImageOrder($imageId, $newOrder)) {
            echo json_encode(['success' => true, 'message' => 'Order updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order']);
        }
        exit();
    }

    /**
     * Get portfolios for API (AJAX)
     */
    public function getPortfolios()
    {
        $this->requireAuth();

        $portfolios = $this->portofolioModel->getAll();

        header('Content-Type: application/json');
        echo json_encode($portfolios ?: []);
        exit();
    }

    /**
     * Get portfolios by category
     */
    public function getByCategory()
    {
        $this->requireAuth();

        $category = $_GET['category'] ?? null;

        if (!$category) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }

        $portfolios = $this->portofolioModel->getByCategory($category);

        header('Content-Type: application/json');
        echo json_encode($portfolios ?: []);
        exit();
    }
}
