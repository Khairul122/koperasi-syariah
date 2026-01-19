<?php

require_once __DIR__ . '/../models/AjukanPembiayaanModel.php';

class AjukanPembiayaanController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new AjukanPembiayaanModel($pdo);
    }

    /**
     * Halaman index untuk anggota - daftar pengajuan mereka sendiri
     */
    public function index(): void
    {

        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        // Get data pembiayaan anggota
        $pembiayaan = $this->model->getPembiayaanByAnggota($idAnggota);

        // Get statistik
        $statistics = $this->model->getStatistics();

        // Load view
        require_once __DIR__ . '/../views/ajukan-pembiayaan/index.php';
    }

    /**
     * Halaman create - form pengajuan pembiayaan
     */
    public function create(): void
    {
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        $data = [
            'tanggal_pengajuan' => date('Y-m-d'),
            'keperluan' => '',
            'jenis_akad' => '',
            'jumlah_pokok' => '',
            'margin_persen' => '10',
            'tenor_bulan' => '12'
        ];

        // Cek simpanan anggota
        $simpananCukup = $this->model->cekSimpananAnggota($idAnggota);

        require_once __DIR__ . '/../views/ajukan-pembiayaan/form.php';
    }

    /**
     * Store data - proses pengajuan pembiayaan
     */
    public function store(): void
    {
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ajukanpembiayaan&action=index');
            exit;
        }

        // Validate input
        $keperluan = trim($_POST['keperluan'] ?? '');
        $jenis_akad = trim($_POST['jenis_akad'] ?? '');
        $jumlah_pokok = trim($_POST['jumlah_pokok'] ?? '0');
        $margin_persen = trim($_POST['margin_persen'] ?? '10');
        $tenor_bulan = trim($_POST['tenor_bulan'] ?? '1');

        // Validasi required fields
        if (empty($keperluan)) {
            $_SESSION['flash_error'] = 'Keperluan pembiayaan wajib diisi!';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
            exit;
        }

        if (empty($jenis_akad)) {
            $_SESSION['flash_error'] = 'Jenis akad wajib dipilih!';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
            exit;
        }

        // Validasi dan format jumlah_pokok
        $jumlah_pokok = str_replace(['.', ','], '', $jumlah_pokok);
        if (!is_numeric($jumlah_pokok) || $jumlah_pokok <= 0) {
            $_SESSION['flash_error'] = 'Jumlah pokok harus berupa angka yang valid dan lebih dari 0!';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
            exit;
        }

        // Validasi margin_persen
        if (!is_numeric($margin_persen) || $margin_persen < 0 || $margin_persen > 100) {
            $_SESSION['flash_error'] = 'Margin koperasi harus antara 0-100%!';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
            exit;
        }

        // Validasi tenor
        if (!is_numeric($tenor_bulan) || $tenor_bulan <= 0 || $tenor_bulan > 60) {
            $_SESSION['flash_error'] = 'Tenor harus berupa angka antara 1-60 bulan!';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
            exit;
        }

        // Cek simpanan anggota
        if (!$this->model->cekSimpananAnggota($idAnggota)) {
            $_SESSION['flash_error'] = 'Maaf, Anda belum memiliki simpanan yang cukup (minimal Rp 100.000) untuk mengajukan pembiayaan!';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
            exit;
        }

        // Hitung margin dan cicilan
        $perhitungan = AjukanPembiayaanModel::hitungCicilan((float)$jumlah_pokok, (float)$margin_persen, (int)$tenor_bulan);

        // Generate no_akad
        $no_akad = $this->model->generateNoAkad();

        // Prepare data
        $data = [
            'no_akad' => $no_akad,
            'tanggal_pengajuan' => date('Y-m-d'),
            'keperluan' => $keperluan,
            'jenis_akad' => $jenis_akad,
            'jumlah_pokok' => $perhitungan['jumlah_pokok'],
            'margin_koperasi' => $perhitungan['margin_koperasi'],
            'total_bayar' => $perhitungan['total_bayar'],
            'tenor_bulan' => $perhitungan['tenor_bulan'],
            'cicilan_per_bulan' => $perhitungan['cicilan_per_bulan'],
            'id_anggota' => $idAnggota
        ];

        // Insert
        if ($this->model->createPembiayaan($data)) {
            $_SESSION['flash_success'] = "Pengajuan pembiayaan berhasil! No. Akad: {$no_akad}. Silakan tunggu proses verifikasi dari admin.";
            header('Location: index.php?controller=ajukanpembiayaan&action=index');
        } else {
            $_SESSION['flash_error'] = 'Gagal mengajukan pembiayaan! Silakan coba lagi.';
            header('Location: index.php?controller=ajukanpembiayaan&action=create');
        }
        exit;
    }

    /**
     * View detail pengajuan
     */
    public function view(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pembiayaan tidak valid!';
            header('Location: index.php?controller=ajukanpembiayaan&action=index');
            exit;
        }

        $pembiayaan = $this->model->getPembiayaanById($id);

        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan!';
            header('Location: index.php?controller=ajukanpembiayaan&action=index');
            exit;
        }

        // Cek apakah pembiayaan milik anggota ini
        if ($pembiayaan['id_anggota'] != $idAnggota) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke data ini!';
            header('Location: index.php?controller=ajukanpembiayaan&action=index');
            exit;
        }

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($id);
        $totalAngsuran = $this->model->getTotalAngsuran($id);

        require_once __DIR__ . '/../views/ajukan-pembiayaan/detail.php';
    }

    /**
     * Halaman index untuk Admin/Bendahara - semua pengajuan
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
        require_once __DIR__ . '/../views/ajukan-pembiayaan/admin-index.php';
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
            header('Location: index.php?controller=ajukanpembiayaan&action=adminIndex');
            exit;
        }

        $pembiayaan = $this->model->getPembiayaanById($id);

        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan!';
            header('Location: index.php?controller=ajukanpembiayaan&action=adminIndex');
            exit;
        }

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($id);
        $totalAngsuran = $this->model->getTotalAngsuran($id);

        require_once __DIR__ . '/../views/ajukan-pembiayaan/admin-detail.php';
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
            header('Location: index.php?controller=ajukanpembiayaan&action=adminIndex');
            exit;
        }

        $id = (int)($_POST['id_pembiayaan'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $catatan = trim($_POST['catatan'] ?? '');
        $idPetugas = $_SESSION['id_petugas'] ?? 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pembiayaan tidak valid!';
            header('Location: index.php?controller=ajukanpembiayaan&action=adminIndex');
            exit;
        }

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            $_SESSION['flash_error'] = 'Status tidak valid!';
            header('Location: index.php?controller=ajukanpembiayaan&action=adminView&id=' . $id);
            exit;
        }

        // Cek pembiayaan ada
        $pembiayaan = $this->model->getPembiayaanById($id);
        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan!';
            header('Location: index.php?controller=ajukanpembiayaan&action=adminIndex');
            exit;
        }

        // Update status
        if ($this->model->updateStatusPembiayaan($id, $status, $idPetugas, $catatan)) {
            $message = $status === 'Disetujui' ? 'disetujui' : 'ditolak';
            $_SESSION['flash_success'] = "Pengajuan pembiayaan berhasil {$message}!";
        } else {
            $_SESSION['flash_error'] = 'Gagal memperbarui status pembiayaan!';
        }

        header('Location: index.php?controller=ajukanpembiayaan&action=adminIndex');
        exit;
    }
}
