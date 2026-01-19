<?php
/**
 * SetorController - Controller untuk transaksi setoran simpanan
 * Handle: Create transaksi setor, View riwayat
 * Access: Admin & Bendahara
 */
require_once __DIR__ . '/../models/SetorModel.php';

class SetorController
{
    private $setorModel;

    public function __construct()
    {
        $this->setorModel = new SetorModel();
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
     * Index - List semua transaksi setoran
     * URL: index.php?controller=setor&action=index
     */
    public function index(): void
    {
        $this->checkAccess();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $result = $this->setorModel->getAllTransaksi($page, $perPage, $search);

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
        $statsResult = $this->setorModel->getStatistics();
        $statistics = $statsResult['status'] ? $statsResult['data'] : [];

        $page_title = 'Transaksi Setoran - Koperasi Syariah';
        require_once __DIR__ . '/../views/setor/index.php';
    }

    /**
     * Create - Tampilkan form setoran baru
     * URL: index.php?controller=setor&action=create
     */
    public function create(): void
    {
        $this->checkAccess();

        // Ambil daftar rekening aktif
        $rekeningResult = $this->setorModel->getDaftarRekening();
        $daftarRekening = $rekeningResult['status'] ? $rekeningResult['data'] : [];

        $page_title = 'Setor Tunai - Koperasi Syariah';
        $formMode = 'create';
        $data = [];

        require_once __DIR__ . '/../views/setor/form.php';
    }

    /**
     * Store - Simpan transaksi setoran
     * URL: index.php?controller=setor&action=store
     */
    public function store(): void
    {
        $this->checkAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('setor', 'index');
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
            $this->redirect('setor', 'create');
            return;
        }

        // Create setoran
        $result = $this->setorModel->createSetoran($data);

        if ($result['status']) {
            $this->setFlash('success', $result['message']);
            $this->redirect('setor', 'index');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('setor', 'create');
        }
    }

    /**
     * View - Lihat detail transaksi
     * URL: index.php?controller=setor&action=view&id=1
     */
    public function view(): void
    {
        $this->checkAccess();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === 0) {
            $this->setFlash('error', 'ID transaksi tidak valid');
            $this->redirect('setor', 'index');
            return;
        }

        $result = $this->setorModel->getTransaksiById($id);

        if (!$result['status']) {
            $this->setFlash('error', $result['message']);
            $this->redirect('setor', 'index');
            return;
        }

        $page_title = 'Detail Transaksi - Koperasi Syariah';
        $transaksi = $result['data'];

        require_once __DIR__ . '/../views/setor/detail.php';
    }

    /**
     * Get Info Rekening - AJAX endpoint untuk form
     * URL: index.php?controller=setor&action=getInfoRekening&no=REK-001
     */
    public function getInfoRekening(): void
    {
        $this->checkAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            echo json_encode(['status' => false, 'message' => 'No. Rekening tidak valid']);
            return;
        }

        $result = $this->setorModel->getRekeningByNo($noRekening);
        echo json_encode($result);
        exit;
    }

    /**
     * Riwayat - Lihat riwayat transaksi rekening
     * URL: index.php?controller=setor&action=riwayat&no=REK-001
     */
    public function riwayat(): void
    {
        $this->checkAccess();

        $noRekening = isset($_GET['no']) ? trim($_GET['no']) : '';

        if (empty($noRekening)) {
            $this->setFlash('error', 'No. Rekening tidak valid');
            $this->redirect('setor', 'index');
            return;
        }

        // Get info rekening
        $rekeningResult = $this->setorModel->getRekeningByNo($noRekening);

        if (!$rekeningResult['status']) {
            $this->setFlash('error', $rekeningResult['message']);
            $this->redirect('setor', 'index');
            return;
        }

        // Get riwayat transaksi
        $riwayatResult = $this->setorModel->getRiwayatByRekening($noRekening);

        $page_title = 'Riwayat Transaksi - Koperasi Syariah';
        $rekening = $rekeningResult['data'];
        $riwayat = $riwayatResult['status'] ? $riwayatResult['data'] : [];

        require_once __DIR__ . '/../views/setor/riwayat.php';
    }

    /**
     * Validasi data setoran
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
            return ['valid' => false, 'message' => 'Jumlah setoran harus diisi'];
        }

        if (!is_numeric($data['jumlah'])) {
            return ['valid' => false, 'message' => 'Jumlah setoran harus berupa angka'];
        }

        $jumlah = (float)$data['jumlah'];
        if ($jumlah <= 0) {
            return ['valid' => false, 'message' => 'Jumlah setoran harus lebih dari 0'];
        }

        if ($jumlah > 1000000000) { // Max 1 miliar
            return ['valid' => false, 'message' => 'Jumlah setoran terlalu besar (maksimal 1 Miliar)'];
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
