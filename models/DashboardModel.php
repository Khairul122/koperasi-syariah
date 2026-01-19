<?php
/**
 * DashboardModel - Model untuk dashboard statistik dan data
 * Handle: Statistik anggota, simpanan, pembiayaan, angsuran
 */
require_once __DIR__ . '/../config/koneksi.php';

class DashboardModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get dashboard statistics untuk Admin
     * @return array
     */
    public function getAdminStats(): array
    {
        try {
            $stats = [];

            // Total anggota aktif
            $query = "SELECT COUNT(*) as total FROM tb_anggota WHERE status_aktif = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_anggota'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total petugas
            $query = "SELECT COUNT(*) as total FROM tb_petugas";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_petugas'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total simpanan (dari tb_simpanan_anggota)
            $query = "SELECT COALESCE(SUM(saldo_terakhir), 0) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_simpanan'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total jenis simpanan
            $query = "SELECT COUNT(*) as total FROM tb_jenis_simpanan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_jenis_simpanan'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total rekening simpanan aktif
            $query = "SELECT COUNT(*) as total FROM tb_simpanan_anggota WHERE status = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_rekening'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total pembiayaan aktif
            $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE status = 'Disetujui'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_pembiayaan'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total angsuran bulan ini
            $query = "SELECT COALESCE(SUM(jumlah_bayar), 0) as total
                      FROM tb_angsuran
                      WHERE MONTH(tanggal_bayar) = MONTH(CURDATE())
                      AND YEAR(tanggal_bayar) = YEAR(CURDATE())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_angsuran_bulan_ini'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Pengajuan pembiayaan pending
            $query = "SELECT COUNT(*) as total FROM tb_pembiayaan WHERE status = 'Pending'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['pengajuan_pending'] = $stmt->fetch(PDO::FETCH_COLUMN);

            return [
                'status' => true,
                'data' => $stats
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetAdminStats Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil statistik admin'
            ];
        }
    }

    /**
     * Get dashboard statistics untuk Bendahara
     * @return array
     */
    public function getBendaharaStats(): array
    {
        try {
            $stats = [];

            // Transaksi simpanan hari ini
            $query = "SELECT
                        COUNT(CASE WHEN jenis_transaksi = 'Setor' THEN 1 END) as total_setor,
                        COUNT(CASE WHEN jenis_transaksi = 'Tarik' THEN 1 END) as total_tarik,
                        COALESCE(SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END), 0) as total_setoran,
                        COALESCE(SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END), 0) as total_penarikan
                      FROM tb_transaksi_simpanan
                      WHERE DATE(tanggal_transaksi) = CURDATE()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $todayTransactions = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['transaksi_hari_ini'] = $todayTransactions;

            // Total simpanan koperasi (dari tb_simpanan_anggota)
            $query = "SELECT COALESCE(SUM(saldo_terakhir), 0) as total_simpanan
                      FROM tb_simpanan_anggota
                      WHERE status = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_simpanan'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total anggota aktif
            $query = "SELECT COUNT(*) as total FROM tb_anggota WHERE status_aktif = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_anggota'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total angsuran bulan ini
            $query = "SELECT
                        COUNT(*) as total_angsuran,
                        COALESCE(SUM(jumlah_bayar), 0) as total_jumlah,
                        COALESCE(SUM(denda), 0) as total_denda
                      FROM tb_angsuran
                      WHERE MONTH(tanggal_bayar) = MONTH(CURDATE())
                      AND YEAR(tanggal_bayar) = YEAR(CURDATE())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['angsuran_bulan_ini'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Pembiayaan pending approval
            $query = "SELECT
                        COUNT(*) as total,
                        COALESCE(SUM(total_bayar), 0) as total_nominal
                      FROM tb_pembiayaan
                      WHERE status = 'Pending'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['pending_approval'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Pembiayaan aktif
            $query = "SELECT
                        COUNT(*) as total_pembiayaan,
                        COALESCE(SUM(total_bayar), 0) as total_nominal
                      FROM tb_pembiayaan
                      WHERE status = 'Disetujui'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['pembiayaan_aktif'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // 5 Transaksi terakhir
            $query = "SELECT
                        ts.no_transaksi,
                        ts.tanggal_transaksi,
                        ts.jenis_transaksi,
                        ts.jumlah,
                        a.nama_lengkap,
                        a.no_anggota
                      FROM tb_transaksi_simpanan ts
                      JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      ORDER BY ts.tanggal_transaksi DESC
                      LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['transaksi_terakhir'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 5 Pengajuan pembiayaan terbaru
            $query = "SELECT
                        p.id_pembiayaan,
                        p.no_akad,
                        p.tanggal_pengajuan,
                        p.keperluan,
                        p.total_bayar,
                        p.status,
                        a.nama_lengkap,
                        a.no_anggota
                      FROM tb_pembiayaan p
                      JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                      ORDER BY p.tanggal_pengajuan DESC
                      LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['pengajuan_terbaru'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $stats
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetBendaharaStats Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil statistik bendahara'
            ];
        }
    }

    /**
     * Get dashboard statistics untuk Anggota
     * @param int $idAnggota
     * @return array
     */
    public function getAnggotaStats(int $idAnggota): array
    {
        try {
            $stats = [];

            // Saldo simpanan (dari tb_simpanan_anggota)
            $query = "SELECT COALESCE(SUM(saldo_terakhir), 0) as saldo
                      FROM tb_simpanan_anggota
                      WHERE id_anggota = :id_anggota
                      AND status = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $stats['saldo_simpanan'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total rekening simpanan
            $query = "SELECT COUNT(*) as total
                      FROM tb_simpanan_anggota
                      WHERE id_anggota = :id_anggota
                      AND status = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $stats['total_rekening'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // Total pembiayaan aktif
            $query = "SELECT
                        COUNT(*) as total,
                        COALESCE(SUM(total_bayar), 0) as total_tagihan
                      FROM tb_pembiayaan
                      WHERE id_anggota = :id_anggota
                      AND status = 'Disetujui'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $pembiayaan = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['pembiayaan_aktif'] = $pembiayaan;

            // Total angsuran yang sudah dibayar
            $query = "SELECT
                        COUNT(*) as total_cicilan,
                        COALESCE(SUM(jumlah_bayar), 0) as total_dibayar
                      FROM tb_angsuran ang
                      JOIN tb_pembiayaan pem ON ang.id_pembiayaan = pem.id_pembiayaan
                      WHERE pem.id_anggota = :id_anggota";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $stats['total_angsuran_dibayar'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Sisa tagihan semua pembiayaan
            $query = "SELECT
                        pem.id_pembiayaan,
                        pem.no_akad,
                        pem.keperluan,
                        pem.total_bayar,
                        pem.tenor_bulan,
                        pem.cicilan_per_bulan,
                        COALESCE(SUM(ang.jumlah_bayar), 0) as sudah_dibayar,
                        COALESCE(COUNT(ang.id_angsuran), 0) as angsuran_ke,
                        pem.total_bayar - COALESCE(SUM(ang.jumlah_bayar), 0) as sisa_tagihan,
                        pem.status
                      FROM tb_pembiayaan pem
                      LEFT JOIN tb_angsuran ang ON pem.id_pembiayaan = ang.id_pembiayaan
                      WHERE pem.id_anggota = :id_anggota
                      AND pem.status IN ('Disetujui', 'Lunas')
                      GROUP BY pem.id_pembiayaan
                      ORDER BY pem.tanggal_pengajuan DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $stats['daftar_pembiayaan'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Daftar rekening simpanan
            $query = "SELECT
                        sa.no_rekening,
                        sa.saldo_terakhir,
                        sa.total_setoran,
                        sa.total_penarikan,
                        sa.status,
                        js.nama_simpanan,
                        js.akad
                      FROM tb_simpanan_anggota sa
                      JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE sa.id_anggota = :id_anggota
                      ORDER BY js.nama_simpanan ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $stats['daftar_rekening'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Riwayat transaksi terakhir (5 transaksi)
            $query = "SELECT
                        ts.tanggal_transaksi,
                        ts.jenis_transaksi,
                        ts.jumlah,
                        ts.keterangan,
                        js.nama_simpanan
                      FROM tb_transaksi_simpanan ts
                      JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE sa.id_anggota = :id_anggota
                      ORDER BY ts.tanggal_transaksi DESC
                      LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();
            $stats['riwayat_transaksi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $stats
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetAnggotaStats Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil statistik anggota'
            ];
        }
    }

    /**
     * Get chart data untuk admin (transaksi per bulan)
     * @return array
     */
    public function getTransaksiChart(): array
    {
        try {
            $query = "SELECT
                        DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan,
                        SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END) as total_setor,
                        SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END) as total_tarik
                      FROM tb_transaksi_simpanan
                      WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                      GROUP BY DATE_FORMAT(tanggal_transaksi, '%Y-%m')
                      ORDER BY bulan ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return [
                'status' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetTransaksiChart Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data chart'
            ];
        }
    }

    /**
     * Get recent activities untuk log
     * @param int $limit
     * @return array
     */
    public function getRecentActivities(int $limit = 10): array
    {
        try {
            $activities = [];

            // Transaksi simpanan terbaru
            $query = "SELECT
                        'Transaksi Simpanan' as aktivitas,
                        CONCAT(a.nama_lengkap, ' melakukan ', ts.jenis_transaksi, ' sebesar Rp ', FORMAT(ts.jumlah, 0)) as detail,
                        ts.tanggal_transaksi as waktu
                      FROM tb_transaksi_simpanan ts
                      JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      ORDER BY ts.tanggal_transaksi DESC
                      LIMIT :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $activities
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetRecentActivities Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil aktivitas terbaru'
            ];
        }
    }

    /**
     * Get top anggota penabung terbesar
     * @param int $limit
     * @return array
     */
    public function getTopAnggota(int $limit = 5): array
    {
        try {
            $query = "SELECT
                        a.no_anggota,
                        a.nama_lengkap,
                        COALESCE(SUM(sa.saldo_terakhir), 0) as total_simpanan,
                        COUNT(DISTINCT sa.id_jenis) as jumlah_jenis
                      FROM tb_anggota a
                      LEFT JOIN tb_simpanan_anggota sa ON a.id_anggota = sa.id_anggota AND sa.status = 'Aktif'
                      GROUP BY a.id_anggota, a.no_anggota, a.nama_lengkap
                      HAVING total_simpanan > 0
                      ORDER BY total_simpanan DESC
                      LIMIT :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'status' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetTopAnggota Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data top anggota'
            ];
        }
    }

    /**
     * Get chart data transaksi simpanan untuk anggota tertentu
     * @param int $idAnggota
     * @return array
     */
    public function getAnggotaTransaksiChart(int $idAnggota): array
    {
        try {
            $query = "SELECT
                        DATE_FORMAT(ts.tanggal_transaksi, '%Y-%m') as bulan,
                        SUM(CASE WHEN ts.jenis_transaksi = 'Setor' THEN ts.jumlah ELSE 0 END) as total_setor,
                        SUM(CASE WHEN ts.jenis_transaksi = 'Tarik' THEN ts.jumlah ELSE 0 END) as total_tarik
                      FROM tb_transaksi_simpanan ts
                      JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      WHERE sa.id_anggota = :id_anggota
                      AND ts.tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                      GROUP BY DATE_FORMAT(ts.tanggal_transaksi, '%Y-%m')
                      ORDER BY bulan ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'status' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];

        } catch (PDOException $e) {
            error_log("[DashboardModel] GetAnggotaTransaksiChart Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data chart anggota'
            ];
        }
    }

    /**
     * Format angka ke format Indonesia
     * @param float $number
     * @return string
     */
    public static function formatRupiah(float $number): string
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    /**
     * Format tanggal ke format Indonesia
     * @param string $date
     * @return string
     */
    public static function formatDateIndo(string $date): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $timestamp = strtotime($date);
        $tanggal = date('d', $timestamp);
        $bulan_nama = $bulan[date('n', $timestamp)];
        $tahun = date('Y', $timestamp);

        return $tanggal . ' ' . $bulan_nama . ' ' . $tahun;
    }
}
