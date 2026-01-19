<?php

require_once __DIR__ . '/../models/AngsuranModel.php';

class AngsuranController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new AngsuranModel($pdo);
    }

    /**
     * Halaman index - daftar pembayaran angsuran
     */
    public function index(): void
    {
        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $angsuranList = $this->model->getAngsuranList($page, $perPage, $search);
        $total = $this->model->getTotalAngsuran($search);
        $stats = $this->model->getStatistics();

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/angsuran/index.php';
    }

    /**
     * Halaman form tambah pembayaran angsuran
     */
    public function form(): void
    {
        // Get ID pembiayaan from parameter
        $idPembiayaan = (int)($_GET['id'] ?? 0);

        $pembiayaan = null;
        $riwayatAngsuran = [];
        $nextAngsuranKe = 1;

        if ($idPembiayaan > 0) {
            $pembiayaan = $this->model->getPembiayaanDetail($idPembiayaan);
            if ($pembiayaan) {
                $riwayatAngsuran = $this->model->getRiwayatAngsuran($idPembiayaan);
                $nextAngsuranKe = $this->model->getNextAngsuranKe($idPembiayaan);
            }
        }

        // Get list pembiayaan aktif for dropdown
        $pembiayaanList = $this->model->getPembiayaanAktif();

        // Load view
        require_once __DIR__ . '/../views/angsuran/form.php';
    }

    /**
     * Halaman detail angsuran
     */
    public function detail(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID angsuran tidak valid!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        $angsuran = $this->model->getAngsuranById($id);

        if (!$angsuran) {
            $_SESSION['flash_error'] = 'Data angsuran tidak ditemukan!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        // Get riwayat angsuran lain dari pembiayaan yang sama
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($angsuran['id_pembiayaan']);

        // Load view
        require_once __DIR__ . '/../views/angsuran/detail.php';
    }

    /**
     * Halaman history pembayaran angsuran
     */
    public function history(): void
    {
        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $angsuranList = $this->model->getAngsuranList($page, $perPage, $search);
        $total = $this->model->getTotalAngsuran($search);

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/angsuran/history.php';
    }

    /**
     * AJAX get pembiayaan detail untuk form
     */
    public function getPembiayaanDetail(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
            exit;
        }

        $pembiayaan = $this->model->getPembiayaanDetail($id);

        if (!$pembiayaan) {
            echo json_encode(['status' => false, 'message' => 'Pembiayaan tidak ditemukan']);
            exit;
        }

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($id);

        // Get total yang sudah dibayar
        $totalDibayar = $this->model->getTotalDibayar($id);

        // Calculate next angsuran ke
        $nextAngsuranKe = $this->model->getNextAngsuranKe($id);

        echo json_encode([
            'status' => true,
            'data' => [
                'pembiayaan' => $pembiayaan,
                'riwayat' => $riwayatAngsuran,
                'total_dibayar' => $totalDibayar,
                'next_angsuran_ke' => $nextAngsuranKe
            ]
        ]);
        exit;
    }

    /**
     * Proses simpan pembayaran angsuran
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        $idPembiayaan = (int)($_POST['id_pembiayaan'] ?? 0);
        $jumlahBayar = (float)($_POST['jumlah_bayar'] ?? 0);
        $denda = (float)($_POST['denda'] ?? 0);
        $idPetugas = (int)($_SESSION['user_id'] ?? 0);

        if ($idPembiayaan <= 0) {
            $_SESSION['flash_error'] = 'Pilih pembiayaan terlebih dahulu!';
            header('Location: index.php?controller=angsuran&action=form');
            exit;
        }

        if ($jumlahBayar <= 0) {
            $_SESSION['flash_error'] = 'Jumlah bayar harus lebih dari 0!';
            header('Location: index.php?controller=angsuran&action=form&id=' . $idPembiayaan);
            exit;
        }

        if ($idPetugas <= 0) {
            $_SESSION['flash_error'] = 'Sesi Anda telah berakhir. Silakan login kembali!';
            header('Location: index.php?controller=auth&action=logout');
            exit;
        }

        // Get detail pembiayaan
        $pembiayaan = $this->model->getPembiayaanDetail($idPembiayaan);
        if (!$pembiayaan) {
            $_SESSION['flash_error'] = 'Pembiayaan tidak ditemukan atau status tidak aktif!';
            header('Location: index.php?controller=angsuran&action=form');
            exit;
        }

        // Calculate
        $nextAngsuranKe = $this->model->getNextAngsuranKe($idPembiayaan);
        $totalDibayar = $this->model->getTotalDibayar($idPembiayaan);
        $totalDibayarSebelumnya = (float)($totalDibayar['total_dibayar'] ?? 0);
        $sisaTagihan = $pembiayaan['total_bayar'] - ($totalDibayarSebelumnya + $jumlahBayar);

        // Generate no kwitansi
        $noKwitansi = $this->model->generateNoKwitansi();

        // Prepare data
        $data = [
            'no_kwitansi' => $noKwitansi,
            'tanggal_bayar' => date('Y-m-d H:i:s'),
            'angsuran_ke' => $nextAngsuranKe,
            'jumlah_bayar' => $jumlahBayar,
            'sisa_tagihan' => max(0, $sisaTagihan),
            'denda' => $denda,
            'id_pembiayaan' => $idPembiayaan,
            'id_petugas' => $idPetugas
        ];

        // Save
        if ($this->model->createAngsuran($data)) {
            $_SESSION['flash_success'] = "Pembayaran angsuran berhasil dicatat! No. Kwitansi: {$noKwitansi}";
            header('Location: index.php?controller=angsuran&action=index');
        } else {
            $_SESSION['flash_error'] = 'Gagal mencatat pembayaran angsuran!';
            header('Location: index.php?controller=angsuran&action=form&id=' . $idPembiayaan);
        }
        exit;
    }

    /**
     * Proses update angsuran
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        $id = (int)($_POST['id_angsuran'] ?? 0);
        $jumlahBayar = (float)($_POST['jumlah_bayar'] ?? 0);
        $denda = (float)($_POST['denda'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID angsuran tidak valid!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        if ($jumlahBayar <= 0) {
            $_SESSION['flash_error'] = 'Jumlah bayar harus lebih dari 0!';
            header('Location: index.php?controller=angsuran&action=detail&id=' . $id);
            exit;
        }

        // Get angsuran data
        $angsuran = $this->model->getAngsuranById($id);
        if (!$angsuran) {
            $_SESSION['flash_error'] = 'Data angsuran tidak ditemukan!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        // Calculate sisa tagihan
        $totalDibayar = $this->model->getTotalDibayar($angsuran['id_pembiayaan']);
        $totalDibayarLain = (float)($totalDibayar['total_dibayar'] ?? 0) - (float)$angsuran['jumlah_bayar'];
        $sisaTagihan = $angsuran['total_bayar'] - ($totalDibayarLain + $jumlahBayar);

        // Prepare data
        $data = [
            'jumlah_bayar' => $jumlahBayar,
            'denda' => $denda,
            'sisa_tagihan' => max(0, $sisaTagihan)
        ];

        // Update
        if ($this->model->updateAngsuran($id, $data)) {
            $_SESSION['flash_success'] = 'Data angsuran berhasil diperbarui!';
            header('Location: index.php?controller=angsuran&action=detail&id=' . $id);
        } else {
            $_SESSION['flash_error'] = 'Gagal memperbarui data angsuran!';
            header('Location: index.php?controller=angsuran&action=detail&id=' . $id);
        }
        exit;
    }

    /**
     * Proses delete angsuran
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID angsuran tidak valid!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        // Delete
        if ($this->model->deleteAngsuran($id)) {
            $_SESSION['flash_success'] = 'Data angsuran berhasil dihapus!';
            header('Location: index.php?controller=angsuran&action=index');
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus data angsuran!';
            header('Location: index.php?controller=angsuran&action=detail&id=' . $id);
        }
        exit;
    }

    /**
     * Cetak kwitansi angsuran
     */
    public function cetakKwitansi(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID angsuran tidak valid!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        // Get data angsuran
        $angsuran = $this->model->getAngsuranById($id);

        if (!$angsuran) {
            $_SESSION['flash_error'] = 'Data angsuran tidak ditemukan!';
            header('Location: index.php?controller=angsuran&action=index');
            exit;
        }

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($angsuran['id_pembiayaan']);

        // Load view untuk cetak kwitansi
        require_once __DIR__ . '/../views/angsuran/kwitansi.php';
    }
}
