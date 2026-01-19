<?php

class ApprovalPembiayaanModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua pengajuan yang menunggu approval
     */
    public function getPendingApprovals(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, a.nik, a.no_hp, a.alamat,
                  a.pekerjaan, a.status_aktif as status_anggota
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.status = 'Pending'";

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

        $query .= " ORDER BY p.tanggal_pengajuan ASC LIMIT :perPage OFFSET :offset";

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
     * Get total pending approvals
     */
    public function getTotalPending(string $search = ''): int
    {
        $query = "SELECT COUNT(DISTINCT p.id_pembiayaan) as total
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.status = 'Pending'";

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
     * Get detail pengajuan untuk approval
     */
    public function getPendingDetail(int $id): array|false
    {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, a.nik, a.no_hp, a.alamat,
                  a.pekerjaan, a.status_aktif as status_anggota,
                  COALESCE(SUM(sa.saldo_terakhir), 0) as total_simpanan_anggota,
                  COUNT(DISTINCT sa.id_simpanan) as jumlah_rekening
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_simpanan_anggota sa ON a.id_anggota = sa.id_anggota AND sa.status = 'Aktif'
                  WHERE p.id_pembiayaan = :id
                  AND p.status = 'Pending'
                  GROUP BY p.id_pembiayaan
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get riwayat approval yang sudah diproses
     */
    public function getApprovalHistory(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota,
                  pt.nama_lengkap as nama_petugas_acc, pt.username as username_petugas_acc
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas pt ON p.id_petugas_acc = pt.id_petugas
                  WHERE p.status IN ('Disetujui', 'Ditolak')";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                p.no_akad LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search OR
                p.keperluan LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY p.id_petugas_acc IS NOT NULL DESC, p.tanggal_pengajuan DESC LIMIT :perPage OFFSET :offset";

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
     * Get total approval history
     */
    public function getTotalHistory(string $search = ''): int
    {
        $query = "SELECT COUNT(DISTINCT p.id_pembiayaan) as total
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.status IN ('Disetujui', 'Ditolak')";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                p.no_akad LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search OR
                p.keperluan LIKE :search
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
     * Update status approval (Approve/Reject)
     */
    public function updateApproval(int $id, string $status, int $idPetugas, ?string $catatan = null): bool
    {
        try {
            // Log parameter untuk debugging
            error_log("[ApprovalPembiayaanModel] updateApproval called - ID: {$id}, Status: {$status}, ID Petugas: {$idPetugas}, Catatan: " . ($catatan ?? 'null'));

            // Update status pembiayaan
            $query = "UPDATE tb_pembiayaan
                      SET status = :status,
                          id_petugas_acc = :id_petugas_acc";

            $params = [
                ':status' => $status,
                ':id_petugas_acc' => $idPetugas,
                ':id' => $id
            ];

            if (!empty($catatan)) {
                $query .= ", catatan_bendahara = :catatan";
                $params[':catatan'] = $catatan;
            }

            $query .= " WHERE id_pembiayaan = :id";

            error_log("[ApprovalPembiayaanModel] Query: " . $query);

            $stmt = $this->db->prepare($query);

            // Bind parameters secara eksplisit
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':id_petugas_acc', $idPetugas, PDO::PARAM_INT);

            if (!empty($catatan)) {
                $stmt->bindValue(':catatan', $catatan, PDO::PARAM_STR);
            }

            $result = $stmt->execute();

            error_log("[ApprovalPembiayaanModel] Execute result: " . ($result ? 'true' : 'false'));

            if ($result) {
                // Check if row was actually updated
                $rowCount = $stmt->rowCount();
                error_log("[ApprovalPembiayaanModel] Row count: {$rowCount}");

                if ($rowCount > 0) {
                    error_log("[ApprovalPembiayaanModel] Update successful for id_pembiayaan: {$id}");
                    return true;
                } else {
                    error_log("[ApprovalPembiayaanModel] WARNING: No rows updated for id_pembiayaan: {$id}. Possible reasons: record not found or already has same values.");
                    return false;
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';
                error_log("[ApprovalPembiayaanModel] SQL Error - Code: {$errorInfo[1]}, Message: {$errorMessage}");
                error_log("[ApprovalPembiayaanModel] Full ErrorInfo: " . print_r($errorInfo, true));
                return false;
            }

        } catch (PDOException $e) {
            error_log("[ApprovalPembiayaanModel] PDOException - Code: " . $e->getCode() . ", Message: " . $e->getMessage());
            error_log("[ApprovalPembiayaanModel] Stack trace: " . $e->getTraceAsString());
            return false;
        } catch (Exception $e) {
            error_log("[ApprovalPembiayaanModel] Exception: " . $e->getMessage());
            error_log("[ApprovalPembiayaanModel] Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Get statistik approval
     */
    public function getApprovalStats(): array
    {
        $stats = [];

        // Total pending
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE status = 'Pending'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_pending'] = (int)($result['total'] ?? 0);

        // Total disetujui hari ini
        $query = "SELECT COUNT(*) as total, SUM(total_bayar) as nominal
                  FROM tb_pembiayaan
                  WHERE status = 'Disetujui'
                  AND id_petugas_acc IS NOT NULL
                  AND DATE(DATE_ADD(tanggal_pengajuan, INTERVAL 1 DAY)) = CURDATE()";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['disetujui_hari_ini'] = (int)($result['total'] ?? 0);
        $stats['nominal_disetujui'] = (float)($result['nominal'] ?? 0);

        // Total ditolak hari ini
        $query = "SELECT COUNT(*) as total
                  FROM tb_pembiayaan
                  WHERE status = 'Ditolak'
                  AND DATE(DATE_ADD(tanggal_pengajuan, INTERVAL 1 DAY)) = CURDATE()";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['ditolak_hari_ini'] = (int)($result['total'] ?? 0);

        // Rata-rata waktu approval (dalam hari)
        $query = "SELECT AVG(DATEDIFF(DATE_ADD(tanggal_pengajuan, INTERVAL 1 DAY), CURDATE())) as avg_days
                  FROM tb_pembiayaan
                  WHERE status IN ('Disetujui', 'Ditolak')
                  AND id_petugas_acc IS NOT NULL
                  AND DATE(DATE_ADD(tanggal_pengajuan, INTERVAL 1 DAY)) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['rata_rata_approval'] = abs((int)($result['avg_days'] ?? 0));

        return $stats;
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
     * Get rekomendasi approval berdasarkan kriteria
     */
    public function getApprovalRecommendation(int $idPembiayaan): array
    {
        $pembiayaan = $this->getPendingDetail($idPembiayaan);
        if (!$pembiayaan) {
            return ['recommendation' => 'unknown', 'reason' => 'Data tidak ditemukan'];
        }

        $recommendation = 'approve';
        $reasons = [];

        // Cek 1: Status anggota aktif
        if ($pembiayaan['status_anggota'] !== 'Aktif') {
            $recommendation = 'reject';
            $reasons[] = 'Status anggota tidak aktif';
        }

        // Cek 2: Simpanan minimal
        if (!$this->cekSimpananAnggota($pembiayaan['id_anggota'])) {
            $recommendation = 'reject';
            $reasons[] = 'Simpanan kurang dari Rp 100.000';
        } else {
            $reasons[] = 'Memiliki simpanan yang cukup';
        }

        // Cek 3: Jumlah pembiayaan wajar (maksimal 10x simpanan)
        $maxPembiayaan = $pembiayaan['total_simpanan_anggota'] * 10;
        if ($pembiayaan['total_bayar'] > $maxPembiayaan && $pembiayaan['total_simpanan_anggota'] > 0) {
            $recommendation = 'review';
            $reasons[] = 'Jumlah pembiayaan melebihi 10x simpanan (perlu review)';
        }

        // Cek 4: Tenor wajar
        if ($pembiayaan['tenor_bulan'] > 60) {
            $recommendation = 'review';
            $reasons[] = 'Tenor lebih dari 60 bulan';
        }

        return [
            'recommendation' => $recommendation,
            'reasons' => $reasons
        ];
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
