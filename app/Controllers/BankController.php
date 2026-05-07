<?php

namespace App\Controllers;

use App\Models\BankModel;

class BankController
{
    private $bankModel;

    public function __construct($database)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->bankModel = new BankModel($database);
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
     * Display all bank accounts
     */
    public function index()
    {
        $this->requireAdmin();

        $banks = $this->bankModel->getAll();

        // Prepare variables for view
        $pageTitle = 'Manajemen Data Bank';
        $banks = $banks ?: [];

        require_once BASE_PATH . 'app/Views/bank/index.php';
    }

    /**
     * Show create bank form
     */
    public function create()
    {
        $this->requireAdmin();

        $pageTitle = 'Tambah Bank Baru';
        $bank = null;
        $isEdit = false;

        require_once BASE_PATH . 'app/Views/bank/form.php';
    }

    /**
     * Store new bank account
     */
    public function store()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        // Validate input
        $bankName = trim($_POST['bank_name'] ?? '');
        $accountNumber = trim($_POST['account_number'] ?? '');
        $accountHolder = trim($_POST['account_holder'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($bankName)) {
            $errors[] = 'Nama bank wajib diisi';
        }

        if (empty($accountNumber)) {
            $errors[] = 'Nomor rekening wajib diisi';
        } elseif ($this->bankModel->accountNumberExists($accountNumber)) {
            $errors[] = 'Nomor rekening sudah terdaftar';
        }

        if (empty($accountHolder)) {
            $errors[] = 'Nama pemilik rekening wajib diisi';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/bank/add');
            exit();
        }

        // Handle logo upload
        $bankLogo = null;
        if (isset($_FILES['bank_logo']) && $_FILES['bank_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/bank/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = pathinfo($_FILES['bank_logo']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                $fileName = uniqid('bank_') . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['bank_logo']['tmp_name'], $filePath)) {
                    $bankLogo = $fileName;
                }
            }
        }

        // Create bank account
        $data = [
            'bank_name' => $bankName,
            'account_number' => $accountNumber,
            'account_holder' => $accountHolder,
            'bank_logo' => $bankLogo,
            'is_active' => $isActive
        ];

        if ($this->bankModel->create($data)) {
            $_SESSION['success_message'] = 'Bank berhasil ditambahkan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal menambahkan bank';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/bank/add');
            exit();
        }
    }

    /**
     * Show edit bank form
     */
    public function edit()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID bank tidak ditemukan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        $bank = $this->bankModel->getById($id);

        if (!$bank) {
            $_SESSION['error_message'] = 'Bank tidak ditemukan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        $pageTitle = 'Edit Bank';
        $isEdit = true;

        require_once BASE_PATH . 'app/Views/bank/form.php';
    }

    /**
     * Update bank account
     */
    public function update()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID bank tidak ditemukan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        // Validate input
        $bankName = trim($_POST['bank_name'] ?? '');
        $accountNumber = trim($_POST['account_number'] ?? '');
        $accountHolder = trim($_POST['account_holder'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];

        if (empty($bankName)) {
            $errors[] = 'Nama bank wajib diisi';
        }

        if (empty($accountNumber)) {
            $errors[] = 'Nomor rekening wajib diisi';
        } elseif ($this->bankModel->accountNumberExists($accountNumber, $id)) {
            $errors[] = 'Nomor rekening sudah digunakan oleh bank lain';
        }

        if (empty($accountHolder)) {
            $errors[] = 'Nama pemilik rekening wajib diisi';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/bank/edit?id=' . $id);
            exit();
        }

        // Get existing bank data
        $existingBank = $this->bankModel->getById($id);
        if (!$existingBank) {
            $_SESSION['error_message'] = 'Bank tidak ditemukan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        // Handle logo upload
        $bankLogo = $existingBank['bank_logo']; // Keep existing logo by default
        if (isset($_FILES['bank_logo']) && $_FILES['bank_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/bank/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = pathinfo($_FILES['bank_logo']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                // Delete old logo if exists
                if ($existingBank['bank_logo'] && file_exists($uploadDir . $existingBank['bank_logo'])) {
                    unlink($uploadDir . $existingBank['bank_logo']);
                }

                $fileName = uniqid('bank_') . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['bank_logo']['tmp_name'], $filePath)) {
                    $bankLogo = $fileName;
                }
            }
        }

        // Update bank account
        $data = [
            'bank_name' => $bankName,
            'account_number' => $accountNumber,
            'account_holder' => $accountHolder,
            'bank_logo' => $bankLogo,
            'is_active' => $isActive
        ];

        if ($this->bankModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Bank berhasil diperbarui';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui bank';
            header('Location: ' . BASE_URL . '/bank/edit?id=' . $id);
            exit();
        }
    }

    /**
     * Delete bank account
     */
    public function delete()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID bank tidak ditemukan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        // Get bank data for logo deletion
        $bank = $this->bankModel->getById($id);
        if ($bank) {
            // Delete logo file if exists
            if ($bank['bank_logo'] && file_exists('uploads/bank/' . $bank['bank_logo'])) {
                unlink('uploads/bank/' . $bank['bank_logo']);
            }
        }

        if ($this->bankModel->delete($id)) {
            $_SESSION['success_message'] = 'Bank berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus bank';
        }

        header('Location: ' . BASE_URL . '/bank');
        exit();
    }

    /**
     * Toggle bank active status
     */
    public function toggleActive()
    {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = 'ID bank tidak ditemukan';
            header('Location: ' . BASE_URL . '/bank');
            exit();
        }

        if ($this->bankModel->toggleActive($id)) {
            $_SESSION['success_message'] = 'Status bank berhasil diperbarui';
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui status bank';
        }

        header('Location: ' . BASE_URL . '/bank');
        exit();
    }

    /**
     * Get active banks for dropdown (AJAX)
     */
    public function getActiveBanks()
    {
        $this->requireAuth();

        $banks = $this->bankModel->getActiveBanks();

        header('Content-Type: application/json');
        echo json_encode($banks ?: []);
        exit();
    }
}
