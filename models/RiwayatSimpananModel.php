<?php

class RiwayatSimpananModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua rekening dengan statistik transaksi
     * Untuk ditampilkan di halaman riwayat simpanan
     */
    public function getAllRekeningWithStats(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;

        // Query utama untuk mendapatkan semua rekening dengan statistik
        $query = "SELECT
                    sa.id_simpanan,
                    sa.no_rekening,
                    sa.saldo_terakhir,
                    sa.total_setoran,
                    sa.total_penarikan,
                    sa.status as status_rekening,
                    a.id_anggota,
                    a.no_anggota,
                    a.nama_lengkap,
                    js.nama_simpanan,
                    js.akad,
                    COUNT(ts.id_transaksi) as jumlah_transaksi,
                    MAX(ts.tanggal_transaksi) as tanggal_transaksi_terakhir
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  LEFT JOIN tb_transaksi_simpanan ts ON sa.id_simpanan = ts.id_simpanan
                  WHERE 1=1";

        $params = [];

        // Search filter
        if (!empty($search)) {
            $query .= " AND (
                sa.no_rekening LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search OR
                js.nama_simpanan LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $query .= " GROUP BY sa.id_simpanan
                    ORDER BY sa.no_rekening ASC
                    LIMIT :perPage OFFSET :offset";

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total rekening untuk pagination
     */
    public function getTotalRekening(string $search = ''): int
    {
        $query = "SELECT COUNT(DISTINCT sa.id_simpanan) as total
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                sa.no_rekening LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search OR
                js.nama_simpanan LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    /**
     * Get detail rekening dengan semua informasi anggota dan rekening
     */
    public function getDetailRekening(string $noRekening): array|false
    {
        $query = "SELECT
                    sa.id_simpanan,
                    sa.no_rekening,
                    sa.saldo_terakhir,
                    sa.total_setoran,
                    sa.total_penarikan,
                    sa.status as status_rekening,
                    a.id_anggota,
                    a.no_anggota,
                    a.nama_lengkap,
                    a.nik,
                    a.no_hp,
                    a.alamat,
                    a.tanggal_daftar,
                    js.nama_simpanan,
                    js.akad,
                    js.minimal_setor
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE sa.no_rekening = :no_rekening
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':no_rekening', $noRekening, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get riwayat transaksi untuk rekening tertentu
     */
    public function getRiwayatTransaksi(string $noRekening): array
    {
        $query = "SELECT
                    ts.id_transaksi,
                    ts.no_transaksi,
                    ts.tanggal_transaksi,
                    ts.jenis_transaksi,
                    ts.jumlah,
                    ts.keterangan,
                    p.nama_lengkap as nama_petugas
                  FROM tb_transaksi_simpanan ts
                  INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  LEFT JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                  WHERE sa.no_rekening = :no_rekening
                  ORDER BY ts.tanggal_transaksi DESC, ts.id_transaksi DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':no_rekening', $noRekening, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get statistik keseluruhan untuk dashboard riwayat
     */
    public function getStatistics(): array
    {
        $statistics = [];

        // Total rekening aktif
        $query = "SELECT COUNT(*) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_rekening_aktif'] = (int)($result['total'] ?? 0);

        // Total saldo semua rekening
        $query = "SELECT SUM(saldo_terakhir) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_saldo'] = (float)($result['total'] ?? 0);

        // Total setoran
        $query = "SELECT SUM(total_setoran) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_setoran'] = (float)($result['total'] ?? 0);

        // Total penarikan
        $query = "SELECT SUM(total_penarikan) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_penarikan'] = (float)($result['total'] ?? 0);

        // Total transaksi hari ini
        $query = "SELECT COUNT(*) as total, SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END) as total_setor,
                  SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END) as total_tarik
                  FROM tb_transaksi_simpanan
                  WHERE DATE(tanggal_transaksi) = CURDATE()";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['hari_ini'] = [
            'total_transaksi' => (int)($result['total'] ?? 0),
            'total_setor' => (float)($result['total_setor'] ?? 0),
            'total_tarik' => (float)($result['total_tarik'] ?? 0)
        ];

        // Total transaksi bulan ini
        $query = "SELECT COUNT(*) as total, SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END) as total_setor,
                  SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END) as total_tarik
                  FROM tb_transaksi_simpanan
                  WHERE MONTH(tanggal_transaksi) = MONTH(CURDATE())
                  AND YEAR(tanggal_transaksi) = YEAR(CURDATE())";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['bulan_ini'] = [
            'total_transaksi' => (int)($result['total'] ?? 0),
            'total_setor' => (float)($result['total_setor'] ?? 0),
            'total_tarik' => (float)($result['total_tarik'] ?? 0)
        ];

        return $statistics;
    }

    /**
     * Get rekening dengan saldo tertinggi
     */
    public function getTopSaldo(int $limit = 5): array
    {
        $query = "SELECT
                    sa.no_rekening,
                    a.nama_lengkap,
                    js.nama_simpanan,
                    sa.saldo_terakhir,
                    sa.total_setoran
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE sa.status = 'Aktif'
                  ORDER BY sa.saldo_terakhir DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get rekening dengan transaksi terbanyak
     */
    public function getTopTransaksi(int $limit = 5): array
    {
        $query = "SELECT
                    sa.no_rekening,
                    a.nama_lengkap,
                    js.nama_simpanan,
                    sa.saldo_terakhir,
                    COUNT(ts.id_transaksi) as jumlah_transaksi
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  LEFT JOIN tb_transaksi_simpanan ts ON sa.id_simpanan = ts.id_simpanan
                  WHERE sa.status = 'Aktif'
                  GROUP BY sa.id_simpanan
                  ORDER BY jumlah_transaksi DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get semua jenis simpanan untuk filter
     */
    public function getAllJenisSimpanan(): array
    {
        $query = "SELECT id_jenis, nama_simpanan FROM tb_jenis_simpanan ORDER BY nama_simpanan ASC";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
