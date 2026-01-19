<?php

class TagihanModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua pembiayaan dengan tagihan aktif untuk anggota tertentu
     */
    public function getTagihanByAnggota(int $idAnggota): array
    {
        $query = "SELECT p.*,
                  CASE
                    WHEN p.status = 'Lunas' THEN 'Lunas'
                    ELSE 'Aktif'
                  END as status_tagihan,
                  COALESCE((SELECT COUNT(*) FROM tb_angsuran a WHERE a.id_pembiayaan = p.id_pembiayaan), 0) as total_dibayar,
                  COALESCE((SELECT SUM(jumlah_bayar) FROM tb_angsuran a WHERE a.id_pembiayaan = p.id_pembiayaan), 0) as nominal_dibayar
                  FROM tb_pembiayaan p
                  WHERE p.id_anggota = :id_anggota
                  AND p.status IN ('Disetujui', 'Lunas')
                  ORDER BY p.tanggal_pengajuan DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get detail tagihan berdasarkan id_pembiayaan
     */
    public function getTagihanById(int $idPembiayaan): array|false
    {
        $query = "SELECT p.*,
                  a.nama_lengkap, a.no_anggota, a.no_hp,
                  COALESCE(pt.nama_lengkap, 'Belum disetujui') as nama_petugas_acc,
                  CASE
                    WHEN p.status = 'Lunas' THEN 'Lunas'
                    ELSE 'Aktif'
                  END as status_tagihan,
                  COALESCE((SELECT COUNT(*) FROM tb_angsuran ang WHERE ang.id_pembiayaan = p.id_pembiayaan), 0) as total_dibayar,
                  COALESCE((SELECT SUM(jumlah_bayar) FROM tb_angsuran ang WHERE ang.id_pembiayaan = p.id_pembiayaan), 0) as nominal_dibayar
                  FROM tb_pembiayaan p
                  INNER JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                  LEFT JOIN tb_petugas pt ON p.id_petugas_acc = pt.id_petugas
                  WHERE p.id_pembiayaan = :id_pembiayaan
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pembiayaan', $idPembiayaan, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika tenor_bulan kosong/NULL, hitung dari cicilan_per_bulan dan total_bayar
        if ($result && empty($result['tenor_bulan'])) {
            $cicilan = (float)($result['cicilan_per_bulan'] ?? 0);
            $total = (float)($result['total_bayar'] ?? 0);

            if ($cicilan > 0) {
                $result['tenor_bulan'] = round($total / $cicilan);
            } else {
                $result['tenor_bulan'] = 12; // Default
            }
        }

        return $result;
    }

    /**
     * Get riwayat angsuran yang sudah dibayar
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
     * Get jadwal angsuran lengkap (1 sampai tenor)
     */
    public function getJadwalAngsuran(int $idPembiayaan): array
    {
        // Ambil data pembiayaan
        $tagihan = $this->getTagihanById($idPembiayaan);
        if (!$tagihan) {
            return [];
        }

        // Handle tenor_bulan yang NULL atau 0
        $tenor = (int)($tagihan['tenor_bulan'] ?? 0);
        $cicilan = (float)($tagihan['cicilan_per_bulan'] ?? 0);
        $totalBayar = (float)($tagihan['total_bayar'] ?? 0);
        $tanggalPengajuan = $tagihan['tanggal_pengajuan'] ?? date('Y-m-d');

        // Jika tenor kosong/0, hitung dari total_bayar / cicilan_per_bulan
        if ($tenor <= 0 && $cicilan > 0) {
            $tenor = (int)round($totalBayar / $cicilan);
        }

        // Default ke 12 jika masih kosong
        if ($tenor <= 0) {
            $tenor = 12;
        }

        // Ambil angsuran yang sudah dibayar
        $riwayatAngsuran = $this->getRiwayatAngsuran($idPembiayaan);
        $angsuranDibayar = [];
        foreach ($riwayatAngsuran as $angsuran) {
            $angsuranDibayar[$angsuran['angsuran_ke']] = $angsuran;
        }

        // Generate jadwal lengkap
        $jadwal = [];
        for ($i = 1; $i <= $tenor; $i++) {
            $tanggalJatuhTempo = date('Y-m-d', strtotime($tanggalPengajuan . ' +' . $i . ' months'));

            $jumlahBayar = $angsuranDibayar[$i]['jumlah_bayar'] ?? 0;
            $denda = $angsuranDibayar[$i]['denda'] ?? 0;

            $jadwal[] = [
                'angsuran_ke' => $i,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'jumlah' => $cicilan,
                'status' => isset($angsuranDibayar[$i]) ? 'Lunas' : 'Belum Bayar',
                'tanggal_bayar' => $angsuranDibayar[$i]['tanggal_bayar'] ?? null,
                'jumlah_bayar' => $jumlahBayar,
                'denda' => $denda,
                'total_bayar' => $jumlahBayar + $denda,
                'petugas' => $angsuranDibayar[$i]['nama_petugas'] ?? null
            ];
        }

        return $jadwal;
    }

    /**
     * Get statistik tagihan anggota
     */
    public function getStatistikTagihan(int $idAnggota): array
    {
        $statistics = [];
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Total tagihan aktif
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan
                  WHERE id_anggota = :id_anggota AND status = 'Disetujui'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_tagihan_aktif'] = (int)($result['total'] ?? 0);

        // Total sudah lunas
        $query = "SELECT COUNT(*) as total FROM tb_pembiayaan
                  WHERE id_anggota = :id_anggota AND status = 'Lunas'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_lunas'] = (int)($result['total'] ?? 0);

        // Total sisa tagihan (yang masih aktif)
        $query = "SELECT SUM(p.total_bayar - COALESCE((SELECT SUM(jumlah_bayar) FROM tb_angsuran a WHERE a.id_pembiayaan = p.id_pembiayaan), 0)) as sisa
                  FROM tb_pembiayaan p
                  WHERE p.id_anggota = :id_anggota AND p.status = 'Disetujui'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['sisa_tagihan'] = (float)($result['sisa'] ?? 0);

        // Total angsuran bulan ini
        $query = "SELECT COUNT(*) as total, SUM(jumlah_bayar) as nominal
                  FROM tb_angsuran a
                  INNER JOIN tb_pembiayaan p ON a.id_pembiayaan = p.id_pembiayaan
                  WHERE p.id_anggota = :id_anggota
                  AND MONTH(a.tanggal_bayar) = :month
                  AND YEAR(a.tanggal_bayar) = :year";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_anggota', $idAnggota, PDO::PARAM_INT);
        $stmt->bindValue(':month', $currentMonth, PDO::PARAM_STR);
        $stmt->bindValue(':year', $currentYear, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['angsuran_bulan_ini'] = (int)($result['total'] ?? 0);
        $statistics['nominal_bulan_ini'] = (float)($result['nominal'] ?? 0);

        return $statistics;
    }

    /**
     * Get tagihan jatuh tempo dalam 30 hari
     */
    public function getTagihanJatuhTempo(int $idAnggota, int $days = 30): array
    {
        $query = "SELECT p.*,
                  COALESCE((SELECT COUNT(*) FROM tb_angsuran a WHERE a.id_pembiayaan = p.id_pembiayaan), 0) as total_dibayar
                  FROM tb_pembiayaan p
                  WHERE p.id_anggota = :id_anggota
                  AND p.status = 'Disetujui'
                  AND DATEDATE_ADD(DATE(p.tanggal_pengajuan), INTERVAL (p.tenor_bulan - COALESCE((SELECT COUNT(*) FROM tb_angsuran a WHERE a.id_pembiayaan = p.id_pembiayaan), 0)) MONTH) <= DATE_ADD(CURDATE(), INTERVAL :days DAY)
                  ORDER BY DATE_ADD(DATE(p.tanggal_pengajuan), INTERVAL (p.tenor_bulan - COALESCE((SELECT COUNT(*) FROM tb_angsuran a WHERE a.id_pembiayaan = p.id_pembiayaan), 0)) MONTH) ASC";

        // Note: Query ini kompleks, kita gunakan pendekatan lebih sederhana di PHP
        $tagihan = $this->getTagihanByAnggota($idAnggota);
        $tagihanJatuhTempo = [];
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime($today . ' +' . $days . ' days'));

        foreach ($tagihan as $t) {
            if ($t['status'] === 'Lunas') {
                continue;
            }

            $nextAngsuranKe = $t['total_dibayar'] + 1;
            $tanggalJatuhTempo = date('Y-m-d', strtotime($t['tanggal_pengajuan'] . ' +' . $nextAngsuranKe . ' months'));

            if ($tanggalJatuhTempo <= $futureDate) {
                $t['jatuh_tempo'] = $tanggalJatuhTempo;
                $t['angsuran_ke'] = $nextAngsuranKe;
                $tagihanJatuhTempo[] = $t;
            }
        }

        return $tagihanJatuhTempo;
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
