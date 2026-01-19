<?php

require_once __DIR__ . '/../models/TagihanModel.php';

class TagihanController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new TagihanModel($pdo);
    }

    /**
     * Halaman index untuk anggota - daftar semua tagihan aktif
     */
    public function index(): void
    {
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($idAnggota <= 0) {
            $_SESSION['flash_error'] = 'Data anggota tidak valid!';
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        // Get data tagihan
        $tagihan = $this->model->getTagihanByAnggota($idAnggota);

        // Get statistik
        $statistik = $this->model->getStatistikTagihan($idAnggota);

        // Get tagihan jatuh tempo
        $tagihanJatuhTempo = $this->model->getTagihanJatuhTempo($idAnggota, 30);

        // Load view
        require_once __DIR__ . '/../views/tagihan/index.php';
    }

    /**
     * Halaman detail tagihan dengan jadwal angsuran
     */
    public function detail(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $idAnggota = $_SESSION['user_id'] ?? 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID tagihan tidak valid!';
            header('Location: index.php?controller=tagihan&action=index');
            exit;
        }

        // Get data tagihan
        $tagihan = $this->model->getTagihanById($id);

        if (!$tagihan) {
            $_SESSION['flash_error'] = 'Tagihan tidak ditemukan!';
            header('Location: index.php?controller=tagihan&action=index');
            exit;
        }

        // Cek apakah tagihan milik anggota ini
        if ($tagihan['id_anggota'] != $idAnggota) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke tagihan ini!';
            header('Location: index.php?controller=tagihan&action=index');
            exit;
        }

        // Get jadwal angsuran lengkap
        $jadwalAngsuran = $this->model->getJadwalAngsuran($id);

        // Get riwayat angsuran
        $riwayatAngsuran = $this->model->getRiwayatAngsuran($id);

        // Hitung summary
        $totalDibayar = 0;
        $totalNominal = 0;
        $totalDenda = 0;
        $sisaAngsuran = 0;

        foreach ($jadwalAngsuran as $j) {
            if ($j['status'] === 'Lunas') {
                $totalDibayar++;
                $totalNominal += $j['jumlah_bayar'] ?? 0;
                $totalDenda += $j['denda'] ?? 0;
            } else {
                $sisaAngsuran++;
            }
        }

        // Ambil tenor_bulan dengan fallback
        $tenorBulan = (int)($tagihan['tenor_bulan'] ?? 0);
        if ($tenorBulan <= 0 && !empty($jadwalAngsuran)) {
            $tenorBulan = count($jadwalAngsuran);
        }
        if ($tenorBulan <= 0) {
            $tenorBulan = 12; // Default
        }

        $summary = [
            'total_dibayar' => $totalDibayar,
            'total_nominal' => $totalNominal,
            'total_denda' => $totalDenda,
            'sisa_angsuran' => $sisaAngsuran,
            'sisa_tagihan' => ($tagihan['total_bayar'] ?? 0) - $totalNominal,
            'progress_persen' => ($tenorBulan > 0) ? ($totalDibayar / $tenorBulan) * 100 : 0
        ];

        // Load view
        require_once __DIR__ . '/../views/tagihan/detail.php';
    }
}
