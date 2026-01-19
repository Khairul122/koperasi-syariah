<?php
/**
 * TarikController - Controller untuk transaksi penarikan simpanan
 * Handle: Create transaksi tarik, View riwayat
 * Access: Admin & Bendahara
 */
require_once __DIR__ . '/../models/TarikModel.php';

class TarikController
{
    private $tarikModel;

    public function __construct()
    {
        $this->tarikModel = new TarikModel();
    }

    /**
     * Cek apakah user adalah Admin atau Bendahara
     */
    private function isAuthorized(): bool
    {
        return isset($_SESSION['role'], $_SESSION['level'])
            && $_SESSION['role'] === 'petugas'
            && in_array($_SESSION['level'], ['Admin', 'Bendahara']);
    }

    /**
     * Redirect jika tidak authorized
     */
    private function checkAccess(): void
    {
        if (!$this->isAuthorized()) {
            $this->setFlash('error', 'Akses ditolak. Halaman ini khusus Admin dan Bendahara.');
            $this->redirect('dashboard', 'index');
            exit;
        }
    }

    /**
     * Index - List semua transaksi penarikan
     * URL: index.php?controller=tarik&action=index
     */
    public function index(): void
    {
        $this->checkAccess();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $result = $this->tarikModel->getAllTransaksi($page, $perPage, $search);

        if ($result['status']) {
            $transaksi = $result['data'];
            $pagination = [
                'total' => $result['total'],
                'page' => $result['page'],
                'perPage' => $result['perPage'],
                'totalPages' => $result['totalPages']
            ];
        } else {
            $transaksi = [];
            $pagination = [
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0
            ];
        }

        // Ambil statistik
        $statsResult = $this->tarikModel->getStatistics();
        $statistics = $statsResult['status'] ? $statsResult['data'] : [];

        $page_title = 'Transaksi Penarikan - Koperasi Syariah';
        require_once __DIR__ . '/../views/tarik/index.php';
    }

    /**
     * Create - Tampilkan form penarikan baru
     * URL: index.php?controller=tarik&action=create
     */
    public function create(): void
    {
        $this->checkAccess();

        // Ambil daftar rekening aktif dengan saldo > 0
        $rekeningResult = $this->tarikModel->getDaftarRekening();
        $daftarRekening = $rekeningResult['status'] ? $rekeningResult['data'] : [];

        $page_title = 'Tarik Tunai - Koperasi Syariah';
        $formMode = 'create';
        $data = [];

        require_once __DIR__ . '/../views/tarik/form.php';
    }

    /**
     * Store - Simpan transaksi penarikan
     * URL: index.php?controller=tarik&action=store
     */
    public function store(): void
    {
        $this->checkAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tarik', 'index');
            return;
        }

        $data = [
            'no_rekening' => trim($_POST['no_rekening'] ?? ''),
            'jumlah' => $_POST['jumlah'] ?? '',
            'keterangan' => trim($_POST['keterangan'] ?? ''),
            'id_petugas' => $_SESSION['id_petugas'] ?? 1
        ];

        // Validasi data
        $validation = $this->validateData($data);
        if (!$validation['valid']) {
            $this->setFlash('error', $validation['message']);
            $this->redirect('tarik', 'create');
            return;
        }

        // Create penarikan
        $result = $this->tarikModel->createPenarikan($data);

        if ($result['status']) {
            $this->setFlash('success', $result['message']);
            $this->redirect('tarik', 'index');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('tarik', 'create');
        }
    }

    /**
     * View - Lihat detail transaksi
     * URL: index.php?controller=tarik&action=view&id=1
     */
    public function view(): void
    {
        $this->checkAccess();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            $this->setFlash('error', 'ID transaksi tidak valid');
            $this->redirect('tarik', 'index');
            return;
        }

        $result = $this->tarikModel->getTransaksiById($id);

        if (!$result['status']) {
            $this->setFlash('error', $result['message']);
            $this->redirect('tarik', 'index');
            return;
        }

        $page_title = 'Detail Transaksi - Koperasi Syariah';
        $transaksi = $result['data'];

        require_once __DIR__ . '/../views/tarik/detail.php';
    }

    /**
     * Get Info Rekening - AJAX endpoint untuk form
     * URL: index.php?controller=tarik&action=getInfoRekening&no=REK-001
     */
    public function getInfoRekening(): void
    {
        $this->checkAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            echo json_encode(['status' => false, 'message' => 'No. Rekening tidak valid']);
            return;
        }

        $result = $this->tarikModel->getRekeningByNo($noRekening);
        echo json_encode($result);
        exit;
    }

    /**
     * Riwayat - Lihat riwayat transaksi penarikan rekening
     * URL: index.php?controller=tarik&action=riwayat&no=REK-001
     */
    public function riwayat(): void
    {
        $this->checkAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            $this->setFlash('error', 'No. Rekening tidak valid');
            $this->redirect('tarik', 'index');
            return;
        }

        // Get info rekening
        $rekeningResult = $this->tarikModel->getRekeningByNo($noRekening);

        if (!$rekeningResult['status']) {
            $this->setFlash('error', $rekeningResult['message']);
            $this->redirect('tarik', 'index');
            return;
        }

        // Get riwayat transaksi tarik
        $riwayatResult = $this->tarikModel->getRiwayatByRekening($noRekening);

        $page_title = 'Riwayat Penarikan - Koperasi Syariah';
        $rekening = $rekeningResult['data'];
        $riwayat = $riwayatResult['status'] ? $riwayatResult['data'] : [];

        require_once __DIR__ . '/../views/tarik/riwayat.php';
    }

    /**
     * Validasi data penarikan
     * @param array $data
     * @return array
     */
    private function validateData(array $data): array
    {
        // Validasi no_rekening
        if (empty($data['no_rekening'])) {
            return ['valid' => false, 'message' => 'No. Rekening harus dipilih'];
        }

        // Validasi jumlah
        if (empty($data['jumlah'])) {
            return ['valid' => false, 'message' => 'Jumlah penarikan harus diisi'];
        }

        if (!is_numeric($data['jumlah'])) {
            return ['valid' => false, 'message' => 'Jumlah penarikan harus berupa angka'];
        }

        $jumlah = (float)$data['jumlah'];
        if ($jumlah <= 0) {
            return ['valid' => false, 'message' => 'Jumlah penarikan harus lebih dari 0'];
        }

        if ($jumlah > 1000000000) { // Max 1 miliar
            return ['valid' => false, 'message' => 'Jumlah penarikan terlalu besar (maksimal 1 Miliar)'];
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
