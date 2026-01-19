<?php
/**
 * AnggotaController - Controller untuk kelola data anggota
 * Handle: Create, Read, Update, Delete, Toggle Status
 * Access: Admin only
 */
require_once __DIR__ . '/../models/AnggotaModel.php';

class AnggotaController
{
    private $anggotaModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
    }

    /**
     * Cek apakah user adalah Admin
     */
    private function isAdmin(): bool
    {
        return isset($_SESSION['role'], $_SESSION['level'])
            && $_SESSION['role'] === 'petugas'
            && $_SESSION['level'] === 'Admin';
    }

    /**
     * Redirect jika bukan Admin
     */
    private function checkAdminAccess(): void
    {
        if (!$this->isAdmin()) {
            $this->setFlash('error', 'Akses ditolak. Halaman ini khusus Admin.');
            $this->redirect('dashboard', 'index');
            exit;
        }
    }

    /**
     * Index - List semua anggota
     * URL: index.php?controller=anggota&action=index
     */
    public function index(): void
    {
        $this->checkAdminAccess();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $result = $this->anggotaModel->getAllAnggota($page, $perPage, $search);

        if ($result['status']) {
            $anggota = $result['data'];
            $pagination = [
                'total' => $result['total'],
                'page' => $result['page'],
                'perPage' => $result['perPage'],
                'totalPages' => $result['totalPages']
            ];
        } else {
            $anggota = [];
            $pagination = [
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0
            ];
        }

        // Ambil statistik
        $statsResult = $this->anggotaModel->getStatistics();
        $statistics = $statsResult['status'] ? $statsResult['data'] : [];

        $page_title = 'Kelola Data Anggota - Koperasi Syariah';
        require_once __DIR__ . '/../views/anggota/index.php';
    }

    /**
     * Create - Tampilkan form tambah anggota
     * URL: index.php?controller=anggota&action=create
     */
    public function create(): void
    {
        $this->checkAdminAccess();

        $page_title = 'Tambah Anggota Baru - Koperasi Syariah';
        $formMode = 'create';
        $data = [];

        require_once __DIR__ . '/../views/anggota/form.php';
    }

    /**
     * Store - Simpan anggota baru
     * URL: index.php?controller=anggota&action=store
     */
    public function store(): void
    {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('anggota', 'index');
            return;
        }

        $data = [
            'nik' => trim($_POST['nik'] ?? ''),
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
            'tempat_lahir' => trim($_POST['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '',
            'alamat' => trim($_POST['alamat'] ?? ''),
            'no_hp' => trim($_POST['no_hp'] ?? ''),
            'pekerjaan' => trim($_POST['pekerjaan'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        // Validasi data
        $validation = $this->validateData($data);
        if (!$validation['valid']) {
            $this->setFlash('error', $validation['message']);
            $this->redirect('anggota', 'create');
            return;
        }

        // Create anggota
        $result = $this->anggotaModel->createAnggota($data);

        if ($result['status']) {
            $this->setFlash('success', 'Anggota berhasil ditambahkan! No. Anggota: ' . $result['no_anggota']);
            $this->redirect('anggota', 'index');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('anggota', 'create');
        }
    }

    /**
     * Edit - Tampilkan form edit anggota
     * URL: index.php?controller=anggota&action=edit&id=1
     */
    public function edit(): void
    {
        $this->checkAdminAccess();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            $this->setFlash('error', 'ID anggota tidak valid');
            $this->redirect('anggota', 'index');
            return;
        }

        $result = $this->anggotaModel->getAnggotaById($id);

        if (!$result['status']) {
            $this->setFlash('error', $result['message']);
            $this->redirect('anggota', 'index');
            return;
        }

        $page_title = 'Edit Data Anggota - Koperasi Syariah';
        $formMode = 'edit';
        $data = $result['data'];

        require_once __DIR__ . '/../views/anggota/form.php';
    }

    /**
     * Update - Update data anggota
     * URL: index.php?controller=anggota&action=update&id=1
     */
    public function update(): void
    {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('anggota', 'index');
            return;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($id === 0) {
            $this->setFlash('error', 'ID anggota tidak valid');
            $this->redirect('anggota', 'index');
            return;
        }

        $data = [
            'nik' => trim($_POST['nik'] ?? ''),
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
            'tempat_lahir' => trim($_POST['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '',
            'alamat' => trim($_POST['alamat'] ?? ''),
            'no_hp' => trim($_POST['no_hp'] ?? ''),
            'pekerjaan' => trim($_POST['pekerjaan'] ?? ''),
            'username' => trim($_POST['username'] ?? '')
        ];

        // Password optional saat update
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Validasi data
        $validation = $this->validateData($data, true);
        if (!$validation['valid']) {
            $this->setFlash('error', $validation['message']);
            $this->redirect('anggota', 'edit&id=' . $id);
            return;
        }

        // Update anggota
        $result = $this->anggotaModel->updateAnggota($id, $data);

        if ($result['status']) {
            $this->setFlash('success', $result['message']);
            $this->redirect('anggota', 'index');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('anggota', 'edit&id=' . $id);
        }
    }

    /**
     * Delete - Hapus anggota (soft delete)
     * URL: index.php?controller=anggota&action=delete&id=1
     */
    public function delete(): void
    {
        $this->checkAdminAccess();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            $this->setFlash('error', 'ID anggota tidak valid');
            $this->redirect('anggota', 'index');
            return;
        }

        $result = $this->anggotaModel->deleteAnggota($id);

        if ($result['status']) {
            $this->setFlash('success', $result['message']);
        } else {
            $this->setFlash('error', $result['message']);
        }

        $this->redirect('anggota', 'index');
    }

    /**
     * Toggle Status - Aktif/Non-aktifkan anggota
     * URL: index.php?controller=anggota&action=toggleStatus&id=1
     */
    public function toggleStatus(): void
    {
        $this->checkAdminAccess();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
            return;
        }

        $result = $this->anggotaModel->toggleStatus($id);

        echo json_encode($result);
        exit;
    }

    /**
     * View - Lihat detail anggota
     * URL: index.php?controller=anggota&action=view&id=1
     */
    public function view(): void
    {
        $this->checkAdminAccess();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            $this->setFlash('error', 'ID anggota tidak valid');
            $this->redirect('anggota', 'index');
            return;
        }

        $result = $this->anggotaModel->getAnggotaById($id);

        if (!$result['status']) {
            $this->setFlash('error', $result['message']);
            $this->redirect('anggota', 'index');
            return;
        }

        // Ambil password yang readable
        $passwordResult = $this->anggotaModel->getReadablePassword($id);

        $page_title = 'Detail Anggota - Koperasi Syariah';
        $anggota = $result['data'];
        $passwordInfo = $passwordResult;

        require_once __DIR__ . '/../views/anggota/detail.php';
    }

    /**
     * Validasi data anggota
     * @param array $data
     * @param bool $isUpdate
     * @return array
     */
    private function validateData(array $data, bool $isUpdate = false): array
    {
        // Validasi required fields
        $required = ['nik', 'nama_lengkap', 'jenis_kelamin', 'username'];
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

        // Validasi password (required saat create, optional saat update)
        if (!$isUpdate) {
            if (empty($data['password'])) {
                return ['valid' => false, 'message' => 'Password harus diisi'];
            }
            if (strlen($data['password']) < 6) {
                return ['valid' => false, 'message' => 'Password minimal 6 karakter'];
            }
        } else {
            // Jika password diisi saat update
            if (!empty($data['password']) && strlen($data['password']) < 6) {
                return ['valid' => false, 'message' => 'Password minimal 6 karakter'];
            }
        }

        // Validasi no HP (9-14 digit sesuai form)
        if (!empty($data['no_hp']) && !preg_match('/^[0-9]{9,14}$/', $data['no_hp'])) {
            return ['valid' => false, 'message' => 'No. HP tidak valid (9-14 digit)'];
        }

        return ['valid' => true];
    }

    /**
     * Set flash message
     */
    private function setFlash(string $type, string $message): void
    {
        $_SESSION["flash_{$type}"] = $message;
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
