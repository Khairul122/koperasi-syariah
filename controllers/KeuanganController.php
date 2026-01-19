<?php

require_once __DIR__ . '/../models/KeuanganModel.php';

class KeuanganController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new KeuanganModel($pdo);
    }

    /**
     * Halaman index - Dashboard Keuangan
     */
    public function index(): void
    {
        // Get data ringkasan keuangan
        $ringkasan = $this->model->getRingkasanKeuangan();

        // Get statistik simpanan
        $statistikSimpanan = $this->model->getStatistikSimpanan();

        // Get statistik pembiayaan
        $statistikPembiayaan = $this->model->getStatistikPembiayaan();

        // Get grafik arus kas
        $grafikArusKas = $this->model->getGrafikArusKas(6);

        // Get transaksi terakhir
        $transaksiTerakhir = $this->model->getTransaksiTerakhir(5);

        // Get angsuran terakhir
        $angsuranTerakhir = $this->model->getAngsuranTerakhir(5);

        // Get distribusi aset
        $distribusiAset = $this->model->getDistribusiAset();

        // Get distribusi pembiayaan
        $distribusiPembiayaan = $this->model->getDistribusiPembiayaan();

        // Load view
        require_once __DIR__ . '/../views/keuangan/index.php';
    }

    /**
     * Halaman detail keuangan
     */
    public function detail(): void
    {
        // Get semua data keuangan detail
        $ringkasan = $this->model->getRingkasanKeuangan();
        $statistikSimpanan = $this->model->getStatistikSimpanan();
        $statistikPembiayaan = $this->model->getStatistikPembiayaan();

        // Get data untuk grafik yang lebih panjang
        $grafikArusKas = $this->model->getGrafikArusKas(12);

        // Get transaksi terakhir lebih banyak
        $transaksiTerakhir = $this->model->getTransaksiTerakhir(20);

        // Get angsuran terakhir lebih banyak
        $angsuranTerakhir = $this->model->getAngsuranTerakhir(20);

        // Get distribusi
        $distribusiAset = $this->model->getDistribusiAset();
        $distribusiPembiayaan = $this->model->getDistribusiPembiayaan();

        // Load view
        require_once __DIR__ . '/../views/keuangan/detail.php';
    }

    /**
     * API untuk refresh data keuangan (AJAX)
     */
    public function refreshData(): void
    {
        header('Content-Type: application/json');

        try {
            $ringkasan = $this->model->getRingkasanKeuangan();
            $statistikSimpanan = $this->model->getStatistikSimpanan();
            $statistikPembiayaan = $this->model->getStatistikPembiayaan();
            $grafikArusKas = $this->model->getGrafikArusKas(6);

            echo json_encode([
                'status' => true,
                'data' => [
                    'ringkasan' => $ringkasan,
                    'statistik_simpanan' => $statistikSimpanan,
                    'statistik_pembiayaan' => $statistikPembiayaan,
                    'grafik_arus_kas' => $grafikArusKas
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
