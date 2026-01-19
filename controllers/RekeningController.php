<?php
/**
 * RekeningController - Controller untuk kelola data rekening anggota
 * Handle: Create, Read, Update, Delete
 * Access: Admin only
 */
require_once __DIR__ . '/../models/RekeningModel.php';

class RekeningController
{
    private $rekeningModel;

    public function __construct()
    {
        $this->rekeningModel = new RekeningModel();
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
     * Index - List semua rekening
     * URL: index.php?controller=rekening&action=index
     */
    public function index(): void
    {
        $this->checkAdminAccess();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $result = $this->rekeningModel->getAllRekening($page, $perPage, $search);

        if ($result['status']) {
            $rekening = $result['data'];
            $pagination = [
                'total' => $result['total'],
                'page' => $result['page'],
                'perPage' => $result['perPage'],
                'totalPages' => $result['totalPages']
            ];
        } else {
            $rekening = [];
            $pagination = [
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0
            ];
        }

        // Ambil statistik
        $statsResult = $this->rekeningModel->getStatistics();
        $statistics = $statsResult['status'] ? $statsResult['data'] : [];

        $page_title = 'Kelola Rekening Anggota - Koperasi Syariah';
        require_once __DIR__ . '/../views/rekening/index.php';
    }

    /**
     * Create - Tampilkan form tambah rekening
     * URL: index.php?controller=rekening&action=create
     */
    public function create(): void
    {
        $this->checkAdminAccess();

        // Ambil daftar anggota aktif
        $anggotaResult = $this->rekeningModel->getDaftarAnggota();
        $daftarAnggota = $anggotaResult['status'] ? $anggotaResult['data'] : [];

        // Ambil daftar jenis simpanan
        $jenisResult = $this->rekeningModel->getDaftarJenisSimpanan();
        $daftarJenis = $jenisResult['status'] ? $jenisResult['data'] : [];

        // Generate automatic no_rekening
        $noRekening = $this->rekeningModel->generateNoRekening();

        $page_title = 'Buat Rekening Baru - Koperasi Syariah';
        $formMode = 'create';
        $data = [];

        require_once __DIR__ . '/../views/rekening/form.php';
    }

    /**
     * Store - Simpan rekening baru
     * URL: index.php?controller=rekening&action=store
     */
    public function store(): void
    {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('rekening', 'index');
            return;
        }

        $data = [
            'no_rekening' => trim($_POST['no_rekening'] ?? ''),
            'id_anggota' => $_POST['id_anggota'] ?? '',
            'id_jenis' => $_POST['id_jenis'] ?? ''
        ];

        // Validasi data
        $validation = $this->validateData($data);
        if (!$validation['valid']) {
            $this->setFlash('error', $validation['message']);
            $this->redirect('rekening', 'create');
            return;
        }

        // Create rekening
        $result = $this->rekeningModel->createRekening($data);

        if ($result['status']) {
            $this->setFlash('success', 'Rekening berhasil dibuat! No. Rekening: ' . $result['no_rekening']);
            $this->redirect('rekening', 'index');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('rekening', 'create');
        }
    }

    /**
     * Edit - Tampilkan form edit rekening
     * URL: index.php?controller=rekening&action=edit&no=REK-20250118-0001
     */
    public function edit(): void
    {
        $this->checkAdminAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            $this->setFlash('error', 'No. Rekening tidak valid');
            $this->redirect('rekening', 'index');
            return;
        }

        $result = $this->rekeningModel->getRekeningByNo($noRekening);

        if (!$result['status']) {
            $this->setFlash('error', $result['message']);
            $this->redirect('rekening', 'index');
            return;
        }

        // Ambil daftar anggota aktif
        $anggotaResult = $this->rekeningModel->getDaftarAnggota();
        $daftarAnggota = $anggotaResult['status'] ? $anggotaResult['data'] : [];

        // Ambil daftar jenis simpanan
        $jenisResult = $this->rekeningModel->getDaftarJenisSimpanan();
        $daftarJenis = $jenisResult['status'] ? $jenisResult['data'] : [];

        $page_title = 'Edit Rekening - Koperasi Syariah';
        $formMode = 'edit';
        $data = $result['data'];

        require_once __DIR__ . '/../views/rekening/form.php';
    }

    /**
     * Update - Update data rekening
     * URL: index.php?controller=rekening&action=update&no=REK-20250118-0001
     */
    public function update(): void
    {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('rekening', 'index');
            return;
        }

        $noRekening = isset($_POST['old_no_rekening']) ? trim($_POST['old_no_rekening']) : '';

        if (empty($noRekening)) {
            $this->setFlash('error', 'No. Rekening tidak valid');
            $this->redirect('rekening', 'index');
            return;
        }

        $data = [
            'no_rekening' => trim($_POST['no_rekening'] ?? ''),
            'id_anggota' => $_POST['id_anggota'] ?? '',
            'id_jenis' => $_POST['id_jenis'] ?? ''
        ];

        // Validasi data
        $validation = $this->validateData($data);
        if (!$validation['valid']) {
            $this->setFlash('error', $validation['message']);
            $this->redirect('rekening', 'edit&no=' . urlencode($noRekening));
            return;
        }

        // Update rekening
        $result = $this->rekeningModel->updateRekening($noRekening, $data);

        if ($result['status']) {
            $this->setFlash('success', $result['message']);
            $this->redirect('rekening', 'index');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('rekening', 'edit&no=' . urlencode($noRekening));
        }
    }

    /**
     * Delete - Hapus rekening
     * URL: index.php?controller=rekening&action=delete&no=REK-20250118-0001
     */
    public function delete(): void
    {
        $this->checkAdminAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            $this->setFlash('error', 'No. Rekening tidak valid');
            $this->redirect('rekening', 'index');
            return;
        }

        $result = $this->rekeningModel->deleteRekening($noRekening);

        if ($result['status']) {
            $this->setFlash('success', $result['message']);
        } else {
            $this->setFlash('error', $result['message']);
        }

        $this->redirect('rekening', 'index');
    }

    /**
     * View - Lihat detail rekening
     * URL: index.php?controller=rekening&action=view&no=REK-20250118-0001
     */
    public function view(): void
    {
        $this->checkAdminAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            $this->setFlash('error', 'No. Rekening tidak valid');
            $this->redirect('rekening', 'index');
            return;
        }

        $result = $this->rekeningModel->getRekeningByNo($noRekening);

        if (!$result['status']) {
            $this->setFlash('error', $result['message']);
            $this->redirect('rekening', 'index');
            return;
        }

        $page_title = 'Detail Rekening - Koperasi Syariah';
        $rekening = $result['data'];

        require_once __DIR__ . '/../views/rekening/detail.php';
    }

    /**
     * Validasi data rekening
     * @param array $data
     * @return array
     */
    private function validateData(array $data): array
    {
        // Validasi required fields
        if (empty($data['no_rekening'])) {
            return ['valid' => false, 'message' => 'No. Rekening harus diisi'];
        }

        if (empty($data['id_anggota'])) {
            return ['valid' => false, 'message' => 'Anggota harus dipilih'];
        }

        if (empty($data['id_jenis'])) {
            return ['valid' => false, 'message' => 'Jenis Simpanan harus dipilih'];
        }

        // Validasi format no_rekening
        if (!preg_match('/^REK-\d{8}-\d{4}$/', $data['no_rekening'])) {
            return ['valid' => false, 'message' => 'Format No. Rekening tidak valid (REK-YYYYMMDD-XXXX)'];
        }

        // Validasi id_anggota dan id_jenis adalah integer
        if (!is_numeric($data['id_anggota']) || $data['id_anggota'] <= 0) {
            return ['valid' => false, 'message' => 'ID Anggota tidak valid'];
        }

        if (!is_numeric($data['id_jenis']) || $data['id_jenis'] <= 0) {
            return ['valid' => false, 'message' => 'ID Jenis Simpanan tidak valid'];
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
