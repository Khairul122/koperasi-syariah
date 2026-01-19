<?php
/**
 * SetorModel - Model untuk transaksi setoran simpanan anggota
 * Table: tb_transaksi_simpanan, tb_simpanan_anggota
 * Handle: Create transaksi setor, update saldo rekening
 */
require_once __DIR__ . '/../config/koneksi.php';

class SetorModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get semua transaksi setoran dengan pagination dan search
     * @param int $page
     * @param int $perPage
     * @param string $search
     * @return array
     */
    public function getAllTransaksi(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        try {
            $offset = ($page - 1) * $perPage;

            $query = "SELECT
                        ts.id_transaksi,
                        ts.no_transaksi,
                        ts.tanggal_transaksi,
                        ts.jenis_transaksi,
                        ts.jumlah,
                        ts.keterangan,
                        ts.id_simpanan,
                        ts.id_petugas,
                        sa.no_rekening,
                        sa.saldo_terakhir,
                        a.id_anggota,
                        a.no_anggota,
                        a.nama_lengkap,
                        js.nama_simpanan,
                        js.akad,
                        p.nama_lengkap as nama_petugas
                      FROM tb_transaksi_simpanan ts
                      INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      INNER JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                      WHERE 1=1";

            $params = [];

            // Search by no_transaksi, no_rekening, nama anggota, nama simpanan
            if (!empty($search)) {
                $query .= " AND (
                    ts.no_transaksi LIKE :search
                    OR sa.no_rekening LIKE :search
                    OR a.nama_lengkap LIKE :search
                    OR a.no_anggota LIKE :search
                    OR js.nama_simpanan LIKE :search
                )";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY ts.tanggal_transaksi DESC LIMIT :perPage OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $countQuery = "SELECT COUNT(*)
                           FROM tb_transaksi_simpanan ts
                           INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                           INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                           INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                           WHERE 1=1";

            if (!empty($search)) {
                $countQuery .= " AND (
                    ts.no_transaksi LIKE :search
                    OR sa.no_rekening LIKE :search
                    OR a.nama_lengkap LIKE :search
                    OR a.no_anggota LIKE :search
                    OR js.nama_simpanan LIKE :search
                )";
            }

            $countStmt = $this->conn->prepare($countQuery);

            if (!empty($search)) {
                $countStmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
            }

            $countStmt->execute();
            $total = $countStmt->fetchColumn();

            $totalPages = ceil($total / $perPage);

            return [
                'status' => true,
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages,
                'message' => 'Data transaksi berhasil diambil'
            ];
        } catch (PDOException $e) {
            error_log("[SetorModel] GetAllTransaksi Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0,
                'message' => 'Terjadi kesalahan saat mengambil data transaksi'
            ];
        }
    }

    /**
     * Get transaksi by ID
     * @param int $id
     * @return array
     */
    public function getTransaksiById(int $id): array
    {
        try {
            $query = "SELECT
                        ts.id_transaksi,
                        ts.no_transaksi,
                        ts.tanggal_transaksi,
                        ts.jenis_transaksi,
                        ts.jumlah,
                        ts.keterangan,
                        ts.id_simpanan,
                        ts.id_petugas,
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
                        js.id_jenis,
                        js.nama_simpanan,
                        js.akad,
                        js.minimal_setor,
                        p.nama_lengkap as nama_petugas
                      FROM tb_transaksi_simpanan ts
                      INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      INNER JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                      WHERE ts.id_transaksi = :id
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Data transaksi ditemukan'
                ];
            }

            return [
                'status' => false,
                'message' => 'Transaksi tidak ditemukan'
            ];
        } catch (PDOException $e) {
            error_log("[SetorModel] GetTransaksiById Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data transaksi'
            ];
        }
    }

    /**
     * Get rekening by no_rekening
     * @param string $noRekening
     * @return array
     */
    public function getRekeningByNo(string $noRekening): array
    {
        try {
            $query = "SELECT
                        sa.id_simpanan,
                        sa.no_rekening,
                        sa.id_anggota,
                        sa.id_jenis,
                        sa.saldo_terakhir,
                        sa.total_setoran,
                        sa.total_penarikan,
                        sa.status as status_rekening,
                        a.no_anggota,
                        a.nama_lengkap,
                        a.nik,
                        a.no_hp,
                        a.alamat,
                        js.nama_simpanan,
                        js.akad,
                        js.minimal_setor
                      FROM tb_simpanan_anggota sa
                      INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE sa.no_rekening = :no_rekening
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':no_rekening', $noRekening, PDO::PARAM_STR);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Rekening ditemukan'
                ];
            }

            return [
                'status' => false,
                'message' => 'Rekening tidak ditemukan'
            ];
        } catch (PDOException $e) {
            error_log("[SetorModel] GetRekeningByNo Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data rekening'
            ];
        }
    }

    /**
     * Get daftar rekening aktif untuk dropdown
     * @return array
     */
    public function getDaftarRekening(): array
    {
        try {
            $query = "SELECT
                        sa.id_simpanan,
                        sa.no_rekening,
                        sa.saldo_terakhir,
                        a.id_anggota,
                        a.no_anggota,
                        a.nama_lengkap,
                        js.nama_simpanan
                      FROM tb_simpanan_anggota sa
                      INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE sa.status = 'Aktif'
                      ORDER BY a.nama_lengkap ASC, js.nama_simpanan ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            error_log("[SetorModel] GetDaftarRekening Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => []
            ];
        }
    }

    /**
     * Create transaksi setoran
     * @param array $data
     * @return array
     */
    public function createSetoran(array $data): array
    {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // 1. Validate rekening
            $rekening = $this->getRekeningByNo($data['no_rekening']);
            if (!$rekening['status']) {
                $this->conn->rollBack();
                return [
                    'status' => false,
                    'message' => 'Rekening tidak ditemukan'
                ];
            }

            $rekeningData = $rekening['data'];

            // 2. Check if rekening is active
            if ($rekeningData['status_rekening'] !== 'Aktif') {
                $this->conn->rollBack();
                return [
                    'status' => false,
                    'message' => 'Rekening tidak aktif. Tidak dapat melakukan setoran.'
                ];
            }

            // 3. Validate jumlah (minimal setoran)
            $minimalSetor = (float)$rekeningData['minimal_setor'];
            $jumlahSetor = (float)$data['jumlah'];

            if ($jumlahSetor < $minimalSetor) {
                $this->conn->rollBack();
                return [
                    'status' => false,
                    'message' => "Minimal setoran untuk {$rekeningData['nama_simpanan']} adalah Rp " . number_format($minimalSetor, 0, ',', '.')
                ];
            }

            // 4. Generate no_transaksi
            $noTransaksi = $this->generateNoTransaksi();

            // 5. Insert transaksi
            $queryTransaksi = "INSERT INTO tb_transaksi_simpanan (
                                no_transaksi,
                                tanggal_transaksi,
                                jenis_transaksi,
                                jumlah,
                                keterangan,
                                id_simpanan,
                                id_petugas
                              ) VALUES (
                                :no_transaksi,
                                NOW(),
                                'Setor',
                                :jumlah,
                                :keterangan,
                                :id_simpanan,
                                :id_petugas
                              )";

            $stmt = $this->conn->prepare($queryTransaksi);
            $result = $stmt->execute([
                ':no_transaksi' => $noTransaksi,
                ':jumlah' => $jumlahSetor,
                ':keterangan' => $data['keterangan'] ?? 'Setoran Tunai',
                ':id_simpanan' => $rekeningData['id_simpanan'],
                ':id_petugas' => $data['id_petugas']
            ]);

            if (!$result) {
                $this->conn->rollBack();
                return [
                    'status' => false,
                    'message' => 'Gagal menyimpan transaksi'
                ];
            }

            // 6. Update saldo di tb_simpanan_anggota
            $saldoBaru = (float)$rekeningData['saldo_terakhir'] + $jumlahSetor;
            $totalSetorBaru = (float)$rekeningData['total_setoran'] + $jumlahSetor;

            $queryUpdateSaldo = "UPDATE tb_simpanan_anggota
                                  SET saldo_terakhir = :saldo,
                                      total_setoran = :total_setor
                                  WHERE id_simpanan = :id_simpanan";

            $stmt = $this->conn->prepare($queryUpdateSaldo);
            $result = $stmt->execute([
                ':saldo' => $saldoBaru,
                ':total_setor' => $totalSetorBaru,
                ':id_simpanan' => $rekeningData['id_simpanan']
            ]);

            if (!$result) {
                $this->conn->rollBack();
                return [
                    'status' => false,
                    'message' => 'Gagal mengupdate saldo rekening'
                ];
            }

            // Commit transaction
            $this->conn->commit();

            return [
                'status' => true,
                'message' => 'Setoran berhasil!',
                'no_transaksi' => $noTransaksi,
                'jumlah' => $jumlahSetor,
                'saldo_baru' => $saldoBaru
            ];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[SetorModel] CreateSetoran Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat melakukan setoran'
            ];
        }
    }

    /**
     * Generate nomor transaksi otomatis
     * Format: TRX-YYYYMMDD-XXXX
     * @return string
     */
    private function generateNoTransaksi(): string
    {
        $date = date('Ymd');
        $prefix = "TRX-{$date}-";

        $query = "SELECT no_transaksi FROM tb_transaksi_simpanan
                  WHERE no_transaksi LIKE :prefix
                  ORDER BY no_transaksi DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $prefixParam = "{$prefix}%";
        $stmt->bindParam(':prefix', $prefixParam, PDO::PARAM_STR);
        $stmt->execute();

        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last) {
            $lastNo = (int)substr($last['no_transaksi'], -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        return $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get statistik setoran
     * @return array
     */
    public function getStatistics(): array
    {
        try {
            // Total transaksi hari ini
            $query = "SELECT
                        COUNT(*) as total_transaksi,
                        COALESCE(SUM(CASE WHEN jenis_transaksi = 'Setor' THEN jumlah ELSE 0 END), 0) as total_setor,
                        COALESCE(SUM(CASE WHEN jenis_transaksi = 'Tarik' THEN jumlah ELSE 0 END), 0) as total_tarik
                      FROM tb_transaksi_simpanan
                      WHERE DATE(tanggal_transaksi) = CURDATE()";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $hariIni = $stmt->fetch(PDO::FETCH_ASSOC);

            // Total setoran bulan ini
            $query = "SELECT
                        COUNT(*) as total_transaksi,
                        COALESCE(SUM(jumlah), 0) as total_setor
                      FROM tb_transaksi_simpanan
                      WHERE jenis_transaksi = 'Setor'
                      AND MONTH(tanggal_transaksi) = MONTH(CURDATE())
                      AND YEAR(tanggal_transaksi) = YEAR(CURDATE())";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $bulanIni = $stmt->fetch(PDO::FETCH_ASSOC);

            // Total saldo semua rekening
            $query = "SELECT
                        COUNT(*) as total_rekening,
                        COALESCE(SUM(saldo_terakhir), 0) as total_saldo
                      FROM tb_simpanan_anggota
                      WHERE status = 'Aktif'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $saldo = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => [
                    'hari_ini' => $hariIni,
                    'bulan_ini' => $bulanIni,
                    'saldo' => $saldo
                ]
            ];
        } catch (PDOException $e) {
            error_log("[SetorModel] GetStatistics Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [
                    'hari_ini' => ['total_transaksi' => 0, 'total_setor' => 0, 'total_tarik' => 0],
                    'bulan_ini' => ['total_transaksi' => 0, 'total_setor' => 0],
                    'saldo' => ['total_rekening' => 0, 'total_saldo' => 0]
                ]
            ];
        }
    }

    /**
     * Get riwayat transaksi by rekening
     * @param string $noRekening
     * @return array
     */
    public function getRiwayatByRekening(string $noRekening): array
    {
        try {
            $query = "SELECT
                        ts.id_transaksi,
                        ts.no_transaksi,
                        ts.tanggal_transaksi,
                        ts.jenis_transaksi,
                        ts.jumlah,
                        ts.keterangan,
                        sa.saldo_terakhir,
                        p.nama_lengkap as nama_petugas
                      FROM tb_transaksi_simpanan ts
                      INNER JOIN tb_simpanan_anggota sa ON ts.id_simpanan = sa.id_simpanan
                      INNER JOIN tb_petugas p ON ts.id_petugas = p.id_petugas
                      WHERE sa.no_rekening = :no_rekening
                      ORDER BY ts.tanggal_transaksi DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':no_rekening', $noRekening, PDO::PARAM_STR);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $data,
                'count' => count($data)
            ];
        } catch (PDOException $e) {
            error_log("[SetorModel] GetRiwayatByRekening Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [],
                'count' => 0,
                'message' => 'Gagal mengambil riwayat transaksi'
            ];
        }
    }
}
