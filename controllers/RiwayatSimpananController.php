<?php

require_once __DIR__ . '/../models/RiwayatSimpananModel.php';

class RiwayatSimpananController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new RiwayatSimpananModel($pdo);
    }


    /**
     * Halaman utama riwayat simpanan
     * Menampilkan daftar semua rekening dengan statistik
     */
    public function index(): void
    {

        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $rekening = $this->model->getAllRekeningWithStats($page, $perPage, $search);
        $total = $this->model->getTotalRekening($search);
        $statistics = $this->model->getStatistics();
        $topSaldo = $this->model->getTopSaldo(5);
        $topTransaksi = $this->model->getTopTransaksi(5);

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/riwayat/index.php';
    }

    /**
     * Halaman detail riwayat transaksi rekening
     */
    public function detail(): void
    {

        $noRekening = $_GET['no'] ?? '';

        if (empty($noRekening)) {
            $_SESSION['flash_error'] = 'Nomor rekening tidak ditemukan!';
            header('Location: index.php?controller=riwayatsimpanan&action=index');
            exit;
        }

        // Get detail rekening
        $rekening = $this->model->getDetailRekening($noRekening);

        if (!$rekening) {
            $_SESSION['flash_error'] = 'Rekening tidak ditemukan!';
            header('Location: index.php?controller=riwayatsimpanan&action=index');
            exit;
        }

        // Get riwayat transaksi
        $riwayat = $this->model->getRiwayatTransaksi($noRekening);

        // Load view
        require_once __DIR__ . '/../views/riwayat/detail.php';
    }

    /**
     * API endpoint untuk get info rekening via AJAX
     */
    public function getInfoRekening(): void
    {

        header('Content-Type: application/json');

        $noRekening = $_GET['no'] ?? '';

        if (empty($noRekening)) {
            echo json_encode(['success' => false, 'message' => 'Nomor rekening tidak ditemukan']);
            exit;
        }

        $rekening = $this->model->getDetailRekening($noRekening);

        if (!$rekening) {
            echo json_encode(['success' => false, 'message' => 'Rekening tidak ditemukan']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $rekening
        ]);
        exit;
    }

    /**
     * Export riwayat transaksi ke CSV
     */
    public function export(): void
    {

        $noRekening = $_GET['no'] ?? '';

        if (empty($noRekening)) {
            $_SESSION['flash_error'] = 'Nomor rekening tidak ditemukan!';
            header('Location: index.php?controller=riwayatsimpanan&action=index');
            exit;
        }

        // Get detail rekening
        $rekening = $this->model->getDetailRekening($noRekening);

        if (!$rekening) {
            $_SESSION['flash_error'] = 'Rekening tidak ditemukan!';
            header('Location: index.php?controller=riwayatsimpanan&action=index');
            exit;
        }

        // Get riwayat transaksi
        $riwayat = $this->model->getRiwayatTransaksi($noRekening);

        // Set headers untuk download CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="riwayat_' . $noRekening . '_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add BOM untuk UTF-8
        fprintf($output, "\xEF\xBB\xBF");

        // Header CSV
        fputcsv($output, [
            'No. Transaksi',
            'Tanggal',
            'Jenis',
            'Jumlah',
            'Saldo',
            'Keterangan',
            'Petugas'
        ]);

        // Data rows
        foreach ($riwayat as $row) {
            fputcsv($output, [
                $row['no_transaksi'],
                $row['tanggal_transaksi'],
                $row['jenis_transaksi'],
                $row['jumlah'],
                $row['saldo_terakhir'],
                $row['keterangan'],
                $row['nama_petugas']
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Filter rekening berdasarkan jenis simpanan
     */
    public function filter(): void
    {

        $idJenis = $_GET['jenis'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Jika filter jenis simpanan aktif, tambahkan ke parameter
        // Implementasi filter bisa ditambahkan di model

        // Redirect ke index dengan parameter filter
        $params = [
            'controller' => 'riwayatsimpanan',
            'action' => 'index',
            'page' => $page
        ];

        if (!empty($search)) {
            $params['search'] = $search;
        }

        if (!empty($idJenis)) {
            $params['jenis'] = $idJenis;
        }

        $queryString = http_build_query($params);
        header('Location: index.php?' . $queryString);
        exit;
    }
}
