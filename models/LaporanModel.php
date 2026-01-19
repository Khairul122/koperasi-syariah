<?php

class LaporanModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get Laporan Simpanan Harian
     */
    public function getLaporanSimpanHarian($tanggal): array
    {
        $query = "SELECT
                    ts.id_transaksi,
                    ts.no_transaksi,
                    ts.tanggal_transaksi,
                    ts.jenis_transaksi,
                    ts.jumlah,
                    ts.keterangan,
                    sa.no_rekening,
                    js.nama_simpanan,
                    a.nama_lengkap,
                    a.no_anggota,
                    p.username as nama_petugas
                  FROM tb_transaksi_simpanan ts
                  LEFT JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  LEFT JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  LEFT JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                  WHERE DATE(ts.tanggal_transaksi) = :tanggal
                  ORDER BY ts.tanggal_transaksi ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tanggal', $tanggal, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Laporan Simpanan Bulanan
     */
    public function getLaporanSimpanBulanan($bulan, $tahun): array
    {
        $query = "SELECT
                    ts.id_transaksi,
                    ts.no_transaksi,
                    ts.tanggal_transaksi,
                    ts.jenis_transaksi,
                    ts.jumlah,
                    ts.keterangan,
                    sa.no_rekening,
                    js.nama_simpanan,
                    a.nama_lengkap,
                    a.no_anggota,
                    p.username as nama_petugas
                  FROM tb_transaksi_simpanan ts
                  LEFT JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  LEFT JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  LEFT JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                  WHERE MONTH(ts.tanggal_transaksi) = :bulan
                  AND YEAR(ts.tanggal_transaksi) = :tahun
                  ORDER BY ts.tanggal_transaksi ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':bulan', $bulan, PDO::PARAM_INT);
        $stmt->bindValue(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Laporan Simpanan Tahunan
     */
    public function getLaporanSimpanTahunan($tahun): array
    {
        $query = "SELECT
                    ts.id_transaksi,
                    ts.no_transaksi,
                    ts.tanggal_transaksi,
                    ts.jenis_transaksi,
                    ts.jumlah,
                    ts.keterangan,
                    sa.no_rekening,
                    js.nama_simpanan,
                    a.nama_lengkap,
                    a.no_anggota,
                    p.username as nama_petugas
                  FROM tb_transaksi_simpanan ts
                  LEFT JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                  LEFT JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                  LEFT JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                  WHERE YEAR(ts.tanggal_transaksi) = :tahun
                  ORDER BY ts.tanggal_transaksi ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Laporan Pembiayaan Harian
     */
    public function getLaporanPinjamHarian($tanggal): array
    {
        $query = "SELECT
                    pem.id_pembiayaan,
                    pem.no_akad,
                    pem.tanggal_pengajuan,
                    pem.keperluan,
                    pem.jenis_akad,
                    pem.jumlah_pokok,
                    pem.margin_koperasi,
                    pem.total_bayar,
                    pem.tenor_bulan,
                    pem.cicilan_per_bulan,
                    pem.status,
                    a.nama_lengkap,
                    a.no_anggota,
                    p.username as nama_petugas
                  FROM tb_pembiayaan pem
                  LEFT JOIN tb_anggota a ON pem.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas p ON pem.id_petugas_acc = p.id_petugas
                  WHERE DATE(pem.tanggal_pengajuan) = :tanggal
                  ORDER BY pem.tanggal_pengajuan ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tanggal', $tanggal, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Laporan Pembiayaan Bulanan
     */
    public function getLaporanPinjamBulanan($bulan, $tahun): array
    {
        $query = "SELECT
                    pem.id_pembiayaan,
                    pem.no_akad,
                    pem.tanggal_pengajuan,
                    pem.keperluan,
                    pem.jenis_akad,
                    pem.jumlah_pokok,
                    pem.margin_koperasi,
                    pem.total_bayar,
                    pem.tenor_bulan,
                    pem.cicilan_per_bulan,
                    pem.status,
                    a.nama_lengkap,
                    a.no_anggota,
                    p.username as nama_petugas
                  FROM tb_pembiayaan pem
                  LEFT JOIN tb_anggota a ON pem.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas p ON pem.id_petugas_acc = p.id_petugas
                  WHERE MONTH(pem.tanggal_pengajuan) = :bulan
                  AND YEAR(pem.tanggal_pengajuan) = :tahun
                  ORDER BY pem.tanggal_pengajuan ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':bulan', $bulan, PDO::PARAM_INT);
        $stmt->bindValue(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Laporan Pembiayaan Tahunan
     */
    public function getLaporanPinjamTahunan($tahun): array
    {
        $query = "SELECT
                    pem.id_pembiayaan,
                    pem.no_akad,
                    pem.tanggal_pengajuan,
                    pem.keperluan,
                    pem.jenis_akad,
                    pem.jumlah_pokok,
                    pem.margin_koperasi,
                    pem.total_bayar,
                    pem.tenor_bulan,
                    pem.cicilan_per_bulan,
                    pem.status,
                    a.nama_lengkap,
                    a.no_anggota,
                    p.username as nama_petugas
                  FROM tb_pembiayaan pem
                  LEFT JOIN tb_anggota a ON pem.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas p ON pem.id_petugas_acc = p.id_petugas
                  WHERE YEAR(pem.tanggal_pengajuan) = :tahun
                  ORDER BY pem.tanggal_pengajuan ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Helper: Format tanggal ke Indonesia
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
        return date('d', $date) . ' ' . $bulan[(int)date('m', $date)] . ' ' . date('Y', $date);
    }

    /**
     * Helper: Format angka ke Rupiah
     */
    public static function formatRupiah($angka): string
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    /**
     * Helper: Format datetime
     */
    public static function formatDateTime($datetime): string
    {
        if (empty($datetime)) {
            return '-';
        }

        $date = strtotime($datetime);
        return date('d/m/Y H:i', $date);
    }
}
