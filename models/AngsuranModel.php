<?php

class AngsuranModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua data angsuran dengan pagination
     */
    public function getAngsuranList(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT a.*, p.no_akad, p.tenor_bulan, p.cicilan_per_bulan,
                  ang.nama_lengkap as nama_anggota, ang.no_anggota,
                  pt.nama_lengkap as nama_petugas
                  FROM tb_angsuran a
                  INNER JOIN tb_pembiayaan p ON a.id_pembiayaan = p.id_pembiayaan
                  INNER JOIN tb_anggota ang ON p.id_anggota = ang.id_anggota
                  LEFT JOIN tb_petugas pt ON a.id_petugas = pt.id_petugas
                  WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                a.no_kwitansi LIKE :search OR
                p.no_akad LIKE :search OR
                ang.nama_lengkap LIKE :search OR
                ang.no_anggota LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY a.tanggal_bayar DESC LIMIT :perPage OFFSET :offset";

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
     * Get total angsuran untuk pagination
     */
    public function getTotalAngsuran(string $search = ''): int
    {
        $query = "SELECT COUNT(*) as total
                  FROM tb_angsuran a
                  INNER JOIN tb_pembiayaan p ON a.id_pembiayaan = p.id_pembiayaan
                  INNER JOIN tb_anggota ang ON p.id_anggota = ang.id_anggota
                  WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                a.no_kwitansi LIKE :search OR
                p.no_akad LIKE :search OR
                ang.nama_lengkap LIKE :search OR
                ang.no_anggota LIKE :search
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
     * Get pembiayaan yang aktif untuk dropdown
     */
    public function getPembiayaanAktif(string $search = ''): array
    {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.status = 'Disetujui'";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                p.no_akad LIKE :search OR
                a.nama_lengkap LIKE :search OR
                a.no_anggota LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY p.tanggal_pengajuan DESC";

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get detail pembiayaan untuk pembayaran angsuran
     */
    public function getPembiayaanDetail(int $id): array|false
    {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, a.alamat, a.no_hp,
                  COALESCE(SUM(ang.jumlah_bayar), 0) as total_dibayar,
                  COUNT(ang.id_angsuran) as jumlah_angsuran
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_angsuran ang ON p.id_pembiayaan = ang.id_pembiayaan
                  WHERE p.id_pembiayaan = :id
                  AND p.status = 'Disetujui'
                  GROUP BY p.id_pembiayaan
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
     * Get angsuran by ID
     */
    public function getAngsuranById(int $id): array|false
    {
        $query = "SELECT a.*, p.no_akad, p.jenis_akad, p.tenor_bulan, p.cicilan_per_bulan, p.total_bayar,
                  ang.nama_lengkap as nama_anggota, ang.no_anggota, ang.alamat, ang.no_hp,
                  pt.nama_lengkap as nama_petugas, pt.username as username_petugas
                  FROM tb_angsuran a
                  INNER JOIN tb_pembiayaan p ON a.id_pembiayaan = p.id_pembiayaan
                  INNER JOIN tb_anggota ang ON p.id_anggota = ang.id_anggota
                  LEFT JOIN tb_petugas pt ON a.id_petugas = pt.id_petugas
                  WHERE a.id_angsuran = :id
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Generate no kwitansi otomatis
     */
    public function generateNoKwitansi(): string
    {
        $date = date('Ymd');
        $prefix = "KW-{$date}-";

        // Cari nomor urut terakhir hari ini
        $query = "SELECT no_kwitansi FROM tb_angsuran WHERE no_kwitansi LIKE :prefix ORDER BY id_angsuran DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':prefix', "{$prefix}%", PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Extract nomor urut dari no_kwitansi terakhir
            $lastNoKwitansi = $result['no_kwitansi'];
            $lastNumber = (int)substr($lastNoKwitansi, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung angsuran ke berikutnya
     */
    public function getNextAngsuranKe(int $idPembiayaan): int
    {
        $query = "SELECT COALESCE(MAX(angsuran_ke), 0) as max_angsuran
                  FROM tb_angsuran
                  WHERE id_pembiayaan = :id_pembiayaan";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['max_angsuran'] ?? 0) + 1;
    }

    /**
     * Hitung total yang sudah dibayar untuk pembiayaan tertentu
     */
    public function getTotalDibayar(int $idPembiayaan): array
    {
        $query = "SELECT
                    COALESCE(SUM(jumlah_bayar), 0) as total_dibayar,
                    COALESCE(SUM(denda), 0) as total_denda,
                    COALESCE(MAX(angsuran_ke), 0) as angsuran_terakhir,
                    COUNT(*) as jumlah_bayar
                  FROM tb_angsuran
                  WHERE id_pembiayaan = :id_pembiayaan";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika tidak ada data angsuran sama sekali, return default array
        if (!$result) {
            return [
                'total_dibayar' => 0,
                'total_denda' => 0,
                'angsuran_terakhir' => 0,
                'jumlah_bayar' => 0
            ];
        }

        // Pastikan semua field ada dan tidak null
        return [
            'total_dibayar' => (float)($result['total_dibayar'] ?? 0),
            'total_denda' => (float)($result['total_denda'] ?? 0),
            'angsuran_terakhir' => (int)($result['angsuran_terakhir'] ?? 0),
            'jumlah_bayar' => (int)($result['jumlah_bayar'] ?? 0)
        ];
    }

    /**
     * Cek apakah pembiayaan sudah lunas
     */
    public function isLunas(int $idPembiayaan): bool
    {
        $query = "SELECT p.total_bayar,
                  COALESCE(SUM(a.jumlah_bayar), 0) as total_dibayar
                  FROM tb_pembiayaan p
                  LEFT JOIN tb_angsuran a ON p.id_pembiayaan = a.id_pembiayaan
                  WHERE p.id_pembiayaan = :id_pembiayaan
                  GROUP BY p.id_pembiayaan";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) return false;

        $totalDibayar = (float)($result['total_dibayar'] ?? 0);
        $totalBayar = (float)($result['total_bayar'] ?? 0);

        return $totalDibayar >= $totalBayar;
    }

    /**
     * Create pembayaran angsuran baru
     */
    public function createAngsuran(array $data): bool
    {
        $this->db->beginTransaction();

        try {
            // Insert ke tb_angsuran
            $query = "INSERT INTO tb_angsuran
                      (no_kwitansi, tanggal_bayar, angsuran_ke, jumlah_bayar, sisa_tagihan, denda, id_pembiayaan, id_petugas)
                      VALUES
                      (:no_kwitansi, :tanggal_bayar, :angsuran_ke, :jumlah_bayar, :sisa_tagihan, :denda, :id_pembiayaan, :id_petugas)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':no_kwitansi', $data['no_kwitansi'], PDO::PARAM_STR);
            $stmt->bindValue(':tanggal_bayar', $data['tanggal_bayar'], PDO::PARAM_STR);
            $stmt->bindValue(':angsuran_ke', $data['angsuran_ke'], PDO::PARAM_INT);
            $stmt->bindValue(':jumlah_bayar', $data['jumlah_bayar'], PDO::PARAM_STR);
            $stmt->bindValue(':sisa_tagihan', $data['sisa_tagihan'], PDO::PARAM_STR);
            $stmt->bindValue(':denda', $data['denda'], PDO::PARAM_STR);
            $stmt->bindValue(':id_pembiayaan', $data['id_pembiayaan'], PDO::PARAM_INT);
            $stmt->bindValue(':id_petugas', $data['id_petugas'], PDO::PARAM_INT);
            $stmt->execute();

            // Cek apakah sudah lunas
            if ($this->isLunas($data['id_pembiayaan'])) {
                // Update status pembiayaan jadi Lunas
                $query = "UPDATE tb_pembiayaan SET status = 'Lunas' WHERE id_pembiayaan = :id_pembiayaan";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':id_pembiayaan', $data['id_pembiayaan'], PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("[AngsuranModel] CreateAngsuran Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update data angsuran
     */
    public function updateAngsuran(int $id, array $data): bool
    {
        $query = "UPDATE tb_angsuran
                  SET jumlah_bayar = :jumlah_bayar,
                      denda = :denda,
                      sisa_tagihan = :sisa_tagihan";

        $params = [
            ':jumlah_bayar' => $data['jumlah_bayar'],
            ':denda' => $data['denda'],
            ':sisa_tagihan' => $data['sisa_tagihan'],
            ':id' => $id
        ];

        $query .= " WHERE id_angsuran = :id";

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
     * Delete angsuran
     */
    public function deleteAngsuran(int $id): bool
    {
        $this->db->beginTransaction();

        try {
            // Get id_pembiayaan before delete
            $query = "SELECT id_pembiayaan FROM tb_angsuran WHERE id_angsuran = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return false;
            }

            $idPembiayaan = $result['id_pembiayaan'];

            // Delete angsuran
            $query = "DELETE FROM tb_angsuran WHERE id_angsuran = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Cek apakah masih lunas setelah delete
            if (!$this->isLunas($idPembiayaan)) {
                // Update status pembiayaan kembali ke Disetujui
                $query = "UPDATE tb_pembiayaan SET status = 'Disetujui' WHERE id_pembiayaan = :id_pembiayaan";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("[AngsuranModel] DeleteAngsuran Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get statistik angsuran
     */
    public function getStatistics(): array
    {
        $stats = [];
        $today = date('Y-m-d');
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Total pembayaran hari ini
        $query = "SELECT COALESCE(SUM(jumlah_bayar), 0) as total, COUNT(*) as jumlah
                  FROM tb_angsuran
                  WHERE DATE(tanggal_bayar) = :today";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['hari_ini'] = [
            'total' => (float)($result['total'] ?? 0),
            'jumlah' => (int)($result['jumlah'] ?? 0)
        ];

        // Total pembayaran bulan ini
        $query = "SELECT COALESCE(SUM(jumlah_bayar), 0) as total, COUNT(*) as jumlah
                  FROM tb_angsuran
                  WHERE YEAR(tanggal_bayar) = :year
                  AND MONTH(tanggal_bayar) = :month";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':year', $currentYear, PDO::PARAM_STR);
        $stmt->bindValue(':month', $currentMonth, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['bulan_ini'] = [
            'total' => (float)($result['total'] ?? 0),
            'jumlah' => (int)($result['jumlah'] ?? 0)
        ];

        // Total denda bulan ini
        $query = "SELECT COALESCE(SUM(denda), 0) as total
                  FROM tb_angsuran
                  WHERE YEAR(tanggal_bayar) = :year
                  AND MONTH(tanggal_bayar) = :month";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':year', $currentYear, PDO::PARAM_STR);
        $stmt->bindValue(':month', $currentMonth, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['denda_bulan_ini'] = (float)($result['total'] ?? 0);

        return $stats;
    }

    /**
     * Format angka ke Rupiah
     */
    public static function formatRupiah($amount): string
    {
        $amount = is_numeric($amount) ? (float)$amount : 0;
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format tanggal ke Indonesia
     */
    public static function formatTanggalIndo($tanggal): string
    {
        if (empty($tanggal) || $tanggal === '0000-00-00' || $tanggal === '0000-00-00 00:00:00') {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $date = strtotime($tanggal);
        $tanggal_indo = date('d', $date) . ' ' . $bulan[(int)date('m', $date)] . ' ' . date('Y', $date);

        if (date('H:i:s', $date) !== '00:00:00') {
            $tanggal_indo .= ', ' . date('H:i', $date);
        }

        return $tanggal_indo;
    }

    /**
     * Terbilang untuk angka dalam bahasa Indonesia
     */
    public static function terbilang($nilai): string
    {
        $nilai = abs((float)$nilai);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";

        if ($nilai < 12) {
            $temp = " " . $huruf[(int)$nilai];
        } else if ($nilai < 20) {
            $temp = self::terbilang($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = self::terbilang((int)($nilai / 10)) . " Puluh" . self::terbilang((int)($nilai % 10));
        } else if ($nilai < 200) {
            $temp = " Seratus" . self::terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::terbilang((int)($nilai / 100)) . " Ratus" . self::terbilang((int)($nilai % 100));
        } else if ($nilai < 2000) {
            $temp = " Seribu" . self::terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::terbilang((int)($nilai / 1000)) . " Ribu" . self::terbilang((int)($nilai % 1000));
        } else if ($nilai < 1000000000) {
            $temp = self::terbilang((int)($nilai / 1000000)) . " Juta" . self::terbilang((int)($nilai % 1000000));
        } else if ($nilai < 1000000000000) {
            $temp = self::terbilang((int)($nilai / 1000000000)) . " Milyar" . self::terbilang(fmod($nilai, 1000000000));
        }

        return trim($temp);
    }
}
