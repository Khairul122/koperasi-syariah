<?php

class KeuanganModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get ringkasan keuangan koperasi
     */
    public function getRingkasanKeuangan(): array
    {
        $ringkasan = [];

        // Total Aset = Total Saldo Simpanan Anggota
        $query = "SELECT
                    COALESCE(SUM(saldo_terakhir), 0) as total_saldo_simpanan,
                    COALESCE(SUM(total_setoran), 0) as total_setor,
                    COALESCE(SUM(total_penarikan), 0) as total_penarikan
                  FROM tb_simpanan_anggota
                  WHERE status = 'Aktif'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $ringkasan['total_aset'] = (float)($result['total_saldo_simpanan'] ?? 0);
        $ringkasan['total_setor'] = (float)($result['total_setor'] ?? 0);
        $ringkasan['total_penarikan'] = (float)($result['total_penarikan'] ?? 0);

        // Total Hutang Anggota = Total Pembiayaan yang Aktif
        $query = "SELECT
                    COALESCE(SUM(total_bayar), 0) as total_pembiayaan_aktif,
                    COUNT(*) as jumlah_pembiayaan
                  FROM tb_pembiayaan
                  WHERE status = 'Disetujui'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $ringkasan['total_hutang_anggota'] = (float)($result['total_pembiayaan_aktif'] ?? 0);
        $ringkasan['jumlah_pembiayaan'] = (int)($result['jumlah_pembiayaan'] ?? 0);

        // Margin Koperasi = (Total Pendapatan - Total Pengeluaran)
        // Total Pendapatan dari Bagi Hasil Simpanan (jika ada) dan Margin Pembiayaan
        // Hitung margin dari pembiayaan (selisih antara total_bayar dan jumlah_pokok + margin_koperasi)
        $query = "SELECT
                    COALESCE(SUM(total_bayar - (jumlah_pokok + margin_koperasi)), 0) as total_margin_pembiayaan
                  FROM tb_pembiayaan
                  WHERE status IN ('Disetujui', 'Lunas')";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalMarginPembiayaan = (float)($result['total_margin_pembiayaan'] ?? 0);

        $ringkasan['total_margin'] = $totalMarginPembiayaan;
        $ringkasan['margin_simpanan'] = 0; // Tidak ada bagi hasil simpanan
        $ringkasan['margin_pembiayaan'] = $totalMarginPembiayaan;

        // Rasio Keuangan
        $ringkasan['rasio_hutang_aset'] = $ringkasan['total_aset'] > 0
            ? round(($ringkasan['total_hutang_anggota'] / $ringkasan['total_aset']) * 100, 2)
            : 0;

        return $ringkasan;
    }

    /**
     * Get statistik simpanan
     */
    public function getStatistikSimpanan(): array
    {
        $statistik = [];
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Total simpanan aktif
        $query = "SELECT COUNT(*) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistik['total_simpanan'] = (int)($result['total'] ?? 0);

        // Total saldo simpanan
        $query = "SELECT COALESCE(SUM(saldo_terakhir), 0) as total_saldo FROM tb_simpanan_anggota WHERE status = 'Aktif'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistik['total_saldo'] = (float)($result['total_saldo'] ?? 0);

        // Transaksi bulan ini
        $query = "SELECT
                    COUNT(*) as total_transaksi,
                    SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END) as total_setor_bulan,
                    SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END) as total_tarik_bulan
                  FROM tb_transaksi_simpanan
                  WHERE MONTH(tanggal_transaksi) = :month
                  AND YEAR(tanggal_transaksi) = :year";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':month', $currentMonth, PDO::PARAM_STR);
        $stmt->bindValue(':year', $currentYear, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $statistik['transaksi_bulan_ini'] = (int)($result['total_transaksi'] ?? 0);
        $statistik['setor_bulan_ini'] = (float)($result['total_setor_bulan'] ?? 0);
        $statistik['tarik_bulan_ini'] = (float)($result['total_tarik_bulan'] ?? 0);

        return $statistik;
    }

    /**
     * Get statistik pembiayaan
     */
    public function getStatistikPembiayaan(): array
    {
        $statistik = [];
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Total pembiayaan aktif
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE status = 'Disetujui'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistik['total_pembiayaan'] = (int)($result['total'] ?? 0);

        // Total jumlah pokok yang diberikan
        $query = "SELECT COALESCE(SUM(jumlah_pokok), 0) as total_jumlah_pokok FROM tb_pembiayaan WHERE status = 'Disetujui'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistik['total_plafond'] = (float)($result['total_jumlah_pokok'] ?? 0);

        // Total yang sudah dibayar
        $query = "SELECT
                    COALESCE(SUM(p.total_bayar), 0) as total_tagihan,
                    COALESCE(SUM(a.jumlah_bayar), 0) as total_dibayar
                  FROM tb_pembiayaan p
                  LEFT JOIN tb_angsuran a ON p.id_pembiayaan = a.id_pembiayaan
                  WHERE p.status = 'Disetujui'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $statistik['total_tagihan'] = (float)($result['total_tagihan'] ?? 0);
        $statistik['total_dibayar'] = (float)($result['total_dibayar'] ?? 0);
        $statistik['sisa_tagihan'] = $statistik['total_tagihan'] - $statistik['total_dibayar'];

        // Total pembiayaan lunas (tanpa filter bulan karena tidak ada field updated_at)
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE status = 'Lunas'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $statistik['total_lunas'] = (int)($result['total'] ?? 0);
        $statistik['lunas_bulan_ini'] = 0; // Default 0 karena tidak ada field tanggal update

        return $statistik;
    }

    /**
     * Get grafik arus kas bulanan
     */
    public function getGrafikArusKas(int $bulan = 6): array
    {
        $query = "SELECT
                    DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan,
                    SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END) as total_setor,
                    SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END) as total_tarik
                  FROM tb_transaksi_simpanan
                  WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL :bulan MONTH)
                  GROUP BY DATE_FORMAT(tanggal_transaksi, '%Y-%m')
                  ORDER BY bulan ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':bulan', $bulan, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get detail transaksi simpanan terakhir
     */
    public function getTransaksiTerakhir(int $limit = 10): array
    {
        $query = "SELECT ts.*,
                  js.nama_simpanan,
                  ang.no_anggota,
                  ang.nama_lengkap
                  FROM tb_transaksi_simpanan ts
                  INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  INNER JOIN tb_anggota ang ON sa.id_anggota = ang.id_anggota
                  ORDER BY ts.tanggal_transaksi DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get detail angsuran terakhir
     */
    public function getAngsuranTerakhir(int $limit = 10): array
    {
        $query = "SELECT a.*,
                  p.no_akad,
                  ang.no_anggota,
                  ang.nama_lengkap
                  FROM tb_angsuran a
                  INNER JOIN tb_pembiayaan p ON a.id_pembiayaan = p.id_pembiayaan
                  INNER JOIN tb_anggota ang ON p.id_anggota = ang.id_anggota
                  ORDER BY a.tanggal_bayar DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get distribusi aset per jenis simpanan
     */
    public function getDistribusiAset(): array
    {
        $query = "SELECT
                    js.nama_simpanan as jenis_simpanan,
                    COUNT(*) as jumlah_akun,
                    SUM(sa.saldo_terakhir) as total_saldo,
                    (SUM(sa.saldo_terakhir) / (SELECT SUM(saldo_terakhir) FROM tb_simpanan_anggota WHERE status = 'Aktif') * 100) as persentase
                  FROM tb_simpanan_anggota sa
                  INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  WHERE sa.status = 'Aktif'
                  GROUP BY js.id_jenis, js.nama_simpanan
                  ORDER BY total_saldo DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get distribusi pembiayaan per jenis akad
     */
    public function getDistribusiPembiayaan(): array
    {
        $query = "SELECT
                    p.jenis_akad,
                    COUNT(*) as jumlah_akad,
                    SUM(p.jumlah_pokok) as total_plafond,
                    SUM(p.total_bayar) as total_tagihan,
                    COALESCE(SUM(a.jumlah_bayar), 0) as total_dibayar
                  FROM tb_pembiayaan p
                  LEFT JOIN tb_angsuran a ON p.id_pembiayaan = a.id_pembiayaan
                  WHERE p.status = 'Disetujui'
                  GROUP BY p.jenis_akad
                  ORDER BY total_plafond DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}
