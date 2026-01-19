<?php

class SaldoModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua rekening simpanan untuk anggota tertentu
     */
    public function getRekeningByAnggota(int $idAnggota): array
    {
        $query = "SELECT sa.*, js.nama_simpanan, js.akad,
                  CASE
                    WHEN sa.status = 'Aktif' THEN 1
                    ELSE 0
                  END as is_active
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE sa.id_anggota = :id_anggota
                  ORDER BY sa.status DESC, js.nama_simpanan ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get detail rekening simpanan berdasarkan id_simpanan
     */
    public function getRekeningById(int $idSimpanan): array|false
    {
        $query = "SELECT sa.*, js.nama_simpanan, js.akad,
                  a.nama_lengkap, a.no_anggota
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  WHERE sa.id_simpanan = :id_simpanan
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_simpanan', $idSimpanan, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get riwayat transaksi untuk rekening tertentu dengan pagination
     */
    public function getTransaksiByRekening(int $idSimpanan, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT ts.*, js.nama_simpanan
                  FROM tb_transaksi_simpanan ts
                  INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE ts.id_simpanan = :id_simpanan
                  ORDER BY ts.tanggal_transaksi DESC, ts.id_transaksi DESC
                  LIMIT :perPage OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_simpanan', $idSimpanan, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total transaksi untuk pagination
     */
    public function getTotalTransaksi(int $idSimpanan): int
    {
        $query = "SELECT COUNT(*) as total
                  FROM tb_transaksi_simpanan
                  WHERE id_simpanan = :id_simpanan";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_simpanan', $idSimpanan, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    /**
     * Get ringkasan transaksi per jenis
     */
    public function getRingkasanTransaksi(int $idSimpanan): array
    {
        $query = "SELECT
                    jenis_transaksi,
                    COUNT(*) as total_transaki,
                    SUM(jumlah) as total_jumlah
                  FROM tb_transaksi_simpanan
                  WHERE id_simpanan = :id_simpanan
                  GROUP BY jenis_transaksi";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_simpanan', $idSimpanan, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ringkasan = [
            'Setor' => ['total' => 0, 'jumlah' => 0],
            'Tarik' => ['total' => 0, 'jumlah' => 0],
            'Transfer' => ['total' => 0, 'jumlah' => 0]
        ];

        foreach ($results as $row) {
            $jenis = $row['jenis_transaksi'];
            if (isset($ringkasan[$jenis])) {
                $ringkasan[$jenis]['total'] = (int)$row['total_transaki'];
                $ringkasan[$jenis]['jumlah'] = (float)$row['total_jumlah'];
            }
        }

        return $ringkasan;
    }

    /**
     * Get transaksi terbaru (limit 5) untuk semua rekening anggota
     */
    public function getTransaksiTerbaru(int $idAnggota, int $limit = 5): array
    {
        $query = "SELECT ts.id_transaksi, ts.tanggal_transaksi, ts.jenis_transaksi,
                  ts.jumlah, ts.keterangan, sa.no_rekening, js.nama_simpanan
                  FROM tb_transaksi_simpanan ts
                  INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE sa.id_anggota = :id_anggota
                  ORDER BY ts.tanggal_transaksi DESC, ts.id_transaksi DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get statistik saldo anggota
     */
    public function getStatistikSaldo(int $idAnggota): array
    {
        $statistics = [];

        // Total saldo semua rekening
        $query = "SELECT COALESCE(SUM(saldo_terakhir), 0) as total_saldo,
                  COALESCE(SUM(total_setoran), 0) as total_setoran,
                  COALESCE(SUM(total_penarikan), 0) as total_penarikan
                  FROM tb_simpanan_anggota
                  WHERE id_anggota = :id_anggota AND status = 'Aktif'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $statistics['total_saldo'] = (float)($result['total_saldo'] ?? 0);
        $statistics['total_setoran'] = (float)($result['total_setoran'] ?? 0);
        $statistics['total_penarikan'] = (float)($result['total_penarikan'] ?? 0);

        // Jumlah rekening aktif
        $query = "SELECT COUNT(*) as total FROM tb_simpanan_anggota
                  WHERE id_anggota = :id_anggota AND status = 'Aktif'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $statistics['total_rekening_aktif'] = (int)($result['total'] ?? 0);

        // Total transaksi bulan ini
        $query = "SELECT COUNT(*) as total, SUM(jumlah) as total_jumlah
                  FROM tb_transaksi_simpanan ts
                  INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  WHERE sa.id_anggota = :id_anggota
                  AND MONTH(ts.tanggal_transaksi) = MONTH(CURDATE())
                  AND YEAR(ts.tanggal_transaksi) = YEAR(CURDATE())";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $statistics['transaksi_bulan_ini'] = (int)($result['total'] ?? 0);
        $statistics['nominal_bulan_ini'] = (float)($result['total_jumlah'] ?? 0);

        return $statistics;
    }

    /**
     * Format angka ke Rupiah
     */
    public static function formatRupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format tanggal ke Indonesia
     */
    public static function formatTanggalIndo($tanggal): string
    {
        if (empty($tanggal) || $tanggal === '0000-00-00') {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $date = strtotime($tanggal);
        $tanggal_indo = date('d', $date) . ' ' . $bulan[(int)date('m', $date)] . ' ' . date('Y', $date);

        return $tanggal_indo;
    }
}
