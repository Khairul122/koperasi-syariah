<?php

require_once __DIR__ . '/../models/SaldoModel.php';

class SaldoController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new SaldoModel($pdo);
    }

    /**
     * Halaman index untuk anggota - daftar semua rekening simpanan
     */
    public function index(): void
    {
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        // Get data rekening anggota
        $rekening = $this->model->getRekeningByAnggota($idAnggota);

        // Get statistik saldo
        $statistik = $this->model->getStatistikSaldo($idAnggota);

        // Get transaksi terbaru
        $transaksiTerbaru = $this->model->getTransaksiTerbaru($idAnggota, 5);

        // Load view
        require_once __DIR__ . '/../views/saldo/index.php';
    }

    /**
     * Halaman detail rekening dengan riwayat transaksi
     */
    public function detail(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID rekening tidak valid!';
            header('Location: index.php?controller=saldo&action=index');
            exit;
        }

        // Get data rekening
        $rekening = $this->model->getRekeningById($id);

        if (!$rekening) {
            $_SESSION['flash_error'] = 'Rekening tidak ditemukan!';
            header('Location: index.php?controller=saldo&action=index');
            exit;
        }

        // Cek apakah rekening milik anggota ini
        if ($rekening['id_anggota'] != $idAnggota) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke rekening ini!';
            header('Location: index.php?controller=saldo&action=index');
            exit;
        }

        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;

        // Get riwayat transaksi
        $transaksi = $this->model->getTransaksiByRekening($id, $page, $perPage);
        $total = $this->model->getTotalTransaksi($id);
        $ringkasan = $this->model->getRingkasanTransaksi($id);

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/saldo/detail.php';
    }
}
