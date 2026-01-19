<?php

require_once __DIR__ . '/../models/AjukanPinjamanModel.php';

class AjukanPinjamanController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new AjukanPinjamanModel($pdo);
    }

    /**
     * Halaman index untuk anggota - daftar pengajuan mereka sendiri
     */
    public function index(): void
    {
        // Cek apakah user adalah anggota
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Anggota') {
            $_SESSION['flash_error'] = 'Halaman ini khusus untuk anggota!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        $idAnggota = $_SESSION['id_anggota'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        // Get data pembiayaan anggota
        $pembiayaan = $this->model->getPembiayaanByAnggota($idAnggota);

        // Load view
        require_once __DIR__ . '/../views/ajukan-pinjaman/index.php';
    }

    /**
     * Halaman create - form pengajuan pinjaman
     */
    public function create(): void
    {
        // Cek apakah user adalah anggota
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Anggota') {
            $_SESSION['flash_error'] = 'Halaman ini khusus untuk anggota!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        $idAnggota = $_SESSION['id_anggota'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        $data = [
            'no_akad' => '',
            'tanggal_pengajuan' => date('Y-m-d'),
            'keperluan' => '',
            'jenis_akad' => '',
            'jumlah_pokok' => '',
            'margin_koperasi' => '',
            'tenor_bulan' => ''
        ];

        require_once __DIR__ . '/../views/ajukan-pinjaman/form.php';
    }

    /**
     * Store data - proses pengajuan pinjaman
     */
    public function store(): void
    {
        // Cek apakah user adalah anggota
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Anggota') {
            $_SESSION['flash_error'] = 'Akses ditolak!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        $idAnggota = $_SESSION['id_anggota'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ajukanpinjaman&action=index');
            exit;
        }

        // Validate input
        $keperluan = trim($_POST['keperluan'] ?? '');
        $jenis_akad = trim($_POST['jenis_akad'] ?? '');
        $jumlah_pokok = trim($_POST['jumlah_pokok'] ?? '0');
        $margin_persen = trim($_POST['margin_persen'] ?? '0');
        $tenor_bulan = trim($_POST['tenor_bulan'] ?? '1');

        // Validasi required fields
        if (empty($keperluan)) {
            $_SESSION['flash_error'] = 'Keperluan pinjaman wajib diisi!';
            header('Location: index.php?controller=ajukanpinjaman&action=create');
            exit;
        }

        if (empty($jenis_akad)) {
            $_SESSION['flash_error'] = 'Jenis akad wajib dipilih!';
            header('Location: index.php?controller=ajukanpinjaman&action=create');
            exit;
        }

        // Validasi dan format jumlah_pokok
        $jumlah_pokok = str_replace(['.', ','], '', $jumlah_pokok);
        if (!is_numeric($jumlah_pokok) || $jumlah_pokok <= 0) {
            $_SESSION['flash_error'] = 'Jumlah pokok harus berupa angka yang valid dan lebih dari 0!';
            header('Location: index.php?controller=ajukanpinjaman&action=create');
            exit;
        }

        // Validasi margin_persen
        if (!is_numeric($margin_persen) || $margin_persen < 0 || $margin_persen > 100) {
            $_SESSION['flash_error'] = 'Margin koperasi harus antara 0-100%!';
            header('Location: index.php?controller=ajukanpinjaman&action=create');
            exit;
        }

        // Validasi tenor
        if (!is_numeric($tenor_bulan) || $tenor_bulan <= 0) {
            $_SESSION['flash_error'] = 'Tenor harus berupa angka yang valid dan lebih dari 0!';
            header('Location: index.php?controller=ajukanpinjaman&action=create');
            exit;
        }

        // Hitung margin koperasi dan total bayar
        $margin_koperasi = ($jumlah_pokok * $margin_persen) / 100;
        $total_bayar = $jumlah_pokok + $margin_koperasi;
        $cicilan_per_bulan = $total_bayar / $tenor_bulan;

        // Generate no_akad
        $no_akad = $this->model->generateNoAkad();

        // Prepare data
        $data = [
            'no_akad' => $no_akad,
            'tanggal_pengajuan' => date('Y-m-d'),
            'keperluan' => $keperluan,
            'jenis_akad' => $jenis_akad,
            'jumlah_pokok' => $jumlah_pokok,
            'margin_koperasi' => $margin_koperasi,
            'total_bayar' => $total_bayar,
            'tenor_bulan' => $tenor_bulan,
            'cicilan_per_bulan' => $cicilan_per_bulan,
            'id_anggota' => $idAnggota
        ];

        // Insert
        if ($this->model->createPembiayaan($data)) {
            $_SESSION['flash_success'] = "Pengajuan pinjaman berhasil! No. Akad: {$no_akad}. Silakan tunggu proses verifikasi dari admin.";
            header('Location: index.php?controller=ajukanpinjaman&action=index');
        } else {
            $_SESSION['flash_error'] = 'Gagal mengajukan pinjaman! Silakan coba lagi.';
            header('Location: index.php?controller=ajukanpinjaman&action=create');
        }
        exit;
    }

    /**
     * View detail pengajuan
     */
    public function view(): void
    {
        // Cek apakah user adalah anggota
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Anggota') {
            $_SESSION['flash_error'] = 'Halaman ini khusus untuk anggota!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $idAnggota = $_SESSION['id_anggota'] ?? 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pembiayaan tidak valid!';
            header('Location: index.php?controller=ajukanpinjaman&action=index');
            exit;
        }

        $pembiayaan = $this->model->getPembiayaanById($id);

        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan!';
            header('Location: index.php?controller=ajukanpinjaman&action=index');
            exit;
        }

        // Cek apakah pembiayaan milik anggota ini
        if ($pembiayaan['id_anggota'] != $idAnggota) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke data ini!';
            header('Location: index.php?controller=ajukanpinjaman&action=index');
            exit;
        }

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($id);
        $totalAngsuran = $this->model->getTotalAngsuran($id);

        require_once __DIR__ . '/../views/ajukan-pinjaman/detail.php';
    }

    /**
     * Halaman index untuk Admin - semua pengajuan
     */
    public function adminIndex(): void
    {
        // Cek apakah user adalah Admin atau Bendahara
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Bendahara'])) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke halaman ini!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $pembiayaan = $this->model->getPembiayaanPaginated($page, $perPage, $search);
        $total = $this->model->getTotalPembiayaan($search);
        $statistics = $this->model->getStatistics();

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/ajukan-pinjaman/admin-index.php';
    }

    /**
     * Admin view detail
     */
    public function adminView(): void
    {
        // Cek apakah user adalah Admin atau Bendahara
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Bendahara'])) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke halaman ini!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pembiayaan tidak valid!';
            header('Location: index.php?controller=ajukanpinjaman&action=adminIndex');
            exit;
        }

        $pembiayaan = $this->model->getPembiayaanById($id);

        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan!';
            header('Location: index.php?controller=ajukanpinjaman&action=adminIndex');
            exit;
        }

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($id);
        $totalAngsuran = $this->model->getTotalAngsuran($id);

        require_once __DIR__ . '/../views/ajukan-pinjaman/admin-detail.php';
    }

    /**
     * Update status - approval/rejection
     */
    public function updateStatus(): void
    {
        // Cek apakah user adalah Admin atau Bendahara
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Bendahara'])) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses untuk melakukan aksi ini!';
            header('Location: index.php?controller=login&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ajukanpinjaman&action=adminIndex');
            exit;
        }

        $id = (int)($_POST['id_pembiayaan'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $catatan = trim($_POST['catatan'] ?? '');
        $idPetugas = $_SESSION['id_petugas'] ?? 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pembiayaan tidak valid!';
            header('Location: index.php?controller=ajukanpinjaman&action=adminIndex');
            exit;
        }

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            $_SESSION['flash_error'] = 'Status tidak valid!';
            header('Location: index.php?controller=ajukanpinjaman&action=adminView&id=' . $id);
            exit;
        }

        // Cek pembiayaan ada
        $pembiayaan = $this->model->getPembiayaanById($id);
        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan!';
            header('Location: index.php?controller=ajukanpinjaman&action=adminIndex');
            exit;
        }

        // Update status
        if ($this->model->updateStatusPembiayaan($id, $status, $idPetugas, $catatan)) {
            $message = $status === 'Disetujui' ? 'disetujui' : 'ditolak';
            $_SESSION['flash_success'] = "Pengajuan pinjaman berhasil {$message}!";
        } else {
            $_SESSION['flash_error'] = 'Gagal memperbarui status pembiayaan!';
        }

        header('Location: index.php?controller=ajukanpinjaman&action=adminIndex');
        exit;
    }
}
