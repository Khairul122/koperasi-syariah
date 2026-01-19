<?php

class AjukanPembiayaanModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua pembiayaan untuk anggota tertentu
     */
    public function getPembiayaanByAnggota(int $idAnggota): array
    {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, a.nik, a.no_hp, pt.nama_lengkap as nama_petugas_acc
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas pt ON p.id_petugas_acc = pt.id_petugas
                  WHERE p.id_anggota = :id_anggota
                  ORDER BY p.tanggal_pengajuan DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get pembiayaan dengan pagination dan search
     */
    public function getPembiayaanPaginated(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, pt.nama_lengkap as nama_petugas_acc
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas pt ON p.id_petugas_acc = pt.id_petugas
                  WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                p.no_akad LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search OR
                p.keperluan LIKE :search OR
                p.jenis_akad LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY p.tanggal_pengajuan DESC LIMIT :perPage OFFSET :offset";

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
     * Get total pembiayaan untuk pagination
     */
    public function getTotalPembiayaan(string $search = ''): int
    {
        $query = "SELECT COUNT(DISTINCT p.id_pembiayaan) as total
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                p.no_akad LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search OR
                p.keperluan LIKE :search OR
                p.jenis_akad LIKE :search
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
     * Get pembiayaan by ID
     */
    public function getPembiayaanById(int $id): array|false
    {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, a.nik, a.no_hp, a.alamat,
                  a.pekerjaan, a.status_aktif as status_anggota,
                  pt.nama_lengkap as nama_petugas_acc, pt.username as username_petugas_acc
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas pt ON p.id_petugas_acc = pt.id_petugas
                  WHERE p.id_pembiayaan = :id
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get pembiayaan by no_akad
     */
    public function getPembiayaanByNoAkad(string $noAkad): array|false
    {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, pt.nama_lengkap as nama_petugas_acc
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas pt ON p.id_petugas_acc = pt.id_petugas
                  WHERE p.no_akad = :no_akad
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':no_akad', $noAkad, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create pengajuan pembiayaan baru
     */
    public function createPembiayaan(array $data): bool
    {
        $query = "INSERT INTO tb_pembiayaan
                  (no_akad, tanggal_pengajuan, keperluan, jenis_akad, jumlah_pokok,
                   margin_koperasi, total_bayar, tenor_bulan, cicilan_per_bulan, status, id_anggota)
                  VALUES
                  (:no_akad, :tanggal_pengajuan, :keperluan, :jenis_akad, :jumlah_pokok,
                   :margin_koperasi, :total_bayar, :tenor_bulan, :cicilan_per_bulan, 'Pending', :id_anggota)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':no_akad', $data['no_akad'], PDO::PARAM_STR);
        $stmt->bindValue(':tanggal_pengajuan', $data['tanggal_pengajuan'], PDO::PARAM_STR);
        $stmt->bindValue(':keperluan', $data['keperluan'], PDO::PARAM_STR);
        $stmt->bindValue(':jenis_akad', $data['jenis_akad'], PDO::PARAM_STR);
        $stmt->bindValue(':jumlah_pokok', $data['jumlah_pokok'], PDO::PARAM_STR);
        $stmt->bindValue(':margin_koperasi', $data['margin_koperasi'], PDO::PARAM_STR);
        $stmt->bindValue(':total_bayar', $data['total_bayar'], PDO::PARAM_STR);
        $stmt->bindValue(':tenor_bulan', $data['tenor_bulan'], PDO::PARAM_INT);
        $stmt->bindValue(':cicilan_per_bulan', $data['cicilan_per_bulan'], PDO::PARAM_STR);
        $stmt->bindValue(':id_anggota', $data['id_anggota'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Update status pembiayaan (approval/rejection)
     */
    public function updateStatusPembiayaan(int $id, string $status, int $idPetugas, ?string $catatan = null): bool
    {
        $query = "UPDATE tb_pembiayaan
                  SET status = :status,
                      id_petugas_acc = :id_petugas_acc";

        $params = [
            ':status' => $status,
            ':id_petugas_acc' => $idPetugas,
            ':id' => $id
        ];

        if ($catatan !== null) {
            $query .= ", catatan_bendahara = :catatan";
            $params[':catatan'] = $catatan;
        }

        $query .= " WHERE id_pembiayaan = :id";

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            if ($key === ':id') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        return $stmt->execute();
    }

    /**
     * Cek apakah no_akad sudah ada
     */
    public function isNoAkadExists(string $noAkad, int $excludeId = 0): bool
    {
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE no_akad = :no_akad";
        $params = [':no_akad' => $noAkad];

        if ($excludeId > 0) {
            $query .= " AND id_pembiayaan != :id";
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, $key === ':id' ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['total'] ?? 0) > 0;
    }

    /**
     * Generate no_akad otomatis
     */
    public function generateNoAkad(): string
    {
        $date = date('Ymd');
        $prefix = "AKD-{$date}-";

        // Cari nomor urut terakhir hari ini
        $query = "SELECT no_akad FROM tb_pembiayaan WHERE no_akad LIKE :prefix ORDER BY id_pembiayaan DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':prefix', "{$prefix}%", PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Extract nomor urut dari no_akad terakhir
            $lastNoAkad = $result['no_akad'];
            $lastNumber = (int)substr($lastNoAkad, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get statistik pembiayaan
     */
    public function getStatistics(): array
    {
        $statistics = [];

        // Total pengajuan
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_pengajuan'] = (int)($result['total'] ?? 0);

        // Total per status
        $query = "SELECT status, COUNT(*) as total FROM tb_pembiayaan GROUP BY status";
        $stmt = $this->db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $statistics['per_status'] = [];

        foreach ($results as $row) {
            $statistics['per_status'][$row['status']] = (int)$row['total'];
        }

        // Total pokok yang disetujui
        $query = "SELECT SUM(jumlah_pokok) as total FROM tb_pembiayaan WHERE status = 'Disetujui'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_pokok_disetujui'] = (float)($result['total'] ?? 0);

        // Total yang sudah lunas
        $query = "SELECT SUM(jumlah_pokok) as total FROM tb_pembiayaan WHERE status = 'Lunas'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_lunas'] = (float)($result['total'] ?? 0);

        // Pending yang perlu diproses
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE status = 'Pending'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_pending'] = (int)($result['total'] ?? 0);

        // Total tagihan aktif
        $query = "SELECT SUM(total_bayar) as total FROM tb_pembiayaan WHERE status = 'Disetujui'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_tagihan_aktif'] = (float)($result['total'] ?? 0);

        return $statistics;
    }

    /**
     * Get riwayat angsuran untuk pembiayaan tertentu
     */
    public function getRiwayatAngsuran(int $idPembiayaan): array
    {
        $query = "SELECT a.*, pt.nama_lengkap as nama_petugas
                  FROM tb_angsuran a
                  LEFT JOIN tb_petugas pt ON a.id_petugas = pt.id_petugas
                  WHERE a.id_pembiayaan = :id_pembiayaan
                  ORDER BY a.angsuran_ke ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total angsuran yang sudah dibayar
     */
    public function getTotalAngsuran(int $idPembiayaan): array
    {
        $query = "SELECT
                    COUNT(*) as total_angsuran,
                    SUM(jumlah_bayar) as total_dibayar,
                    MAX(angsuran_ke) as angsuran_terakhir,
                    COALESCE(SUM(denda), 0) as total_denda
                  FROM tb_angsuran
                  WHERE id_pembiayaan = :id_pembiayaan";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cek apakah anggota memiliki simpanan yang cukup
     */
    public function cekSimpananAnggota(int $idAnggota, float $minimalSimpanan = 100000): bool
    {
        $query = "SELECT COALESCE(SUM(saldo_terakhir), 0) as total_simpanan
                  FROM tb_simpanan_anggota
                  WHERE id_anggota = :id_anggota
                  AND status = 'Aktif'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalSimpanan = (float)($result['total_simpanan'] ?? 0);
        return $totalSimpanan >= $minimalSimpanan;
    }

    /**
     * Hitung margin dan cicilan
     */
    public static function hitungCicilan(float $jumlahPokok, float $marginPersen, int $tenorBulan): array
    {
        // Validasi tenor untuk mencegah division by zero
        if ($tenorBulan <= 0) {
            $tenorBulan = 1;
        }

        $marginKoperasi = ($jumlahPokok * $marginPersen) / 100;
        $totalBayar = $jumlahPokok + $marginKoperasi;
        $cicilanPerBulan = $totalBayar / $tenorBulan;

        return [
            'jumlah_pokok' => $jumlahPokok,
            'margin_koperasi' => $marginKoperasi,
            'total_bayar' => $totalBayar,
            'cicilan_per_bulan' => $cicilanPerBulan
        ];
    }
}
