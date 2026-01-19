<?php
/**
 * RekeningModel - Model untuk kelola data rekening anggota
 * Table: tb_simpanan_anggota
 * Fields: no_rekening, id_anggota, id_jenis
 */
require_once __DIR__ . '/../config/koneksi.php';

class RekeningModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get all rekening with pagination and search
     * @param int $page
     * @param int $perPage
     * @param string $search
     * @return array
     */
    public function getAllRekening(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        try {
            $offset = ($page - 1) * $perPage;

            // Query sesuai dengan database aktual (tanpa bunga_default)
            $query = "SELECT
                        sa.no_rekening,
                        sa.id_anggota,
                        sa.id_jenis,
                        a.no_anggota,
                        a.nama_lengkap,
                        a.status_aktif,
                        js.nama_simpanan,
                        js.akad,
                        js.minimal_setor
                      FROM tb_simpanan_anggota sa
                      INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                      INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE 1=1";

            $params = [];

            // Search by no_rekening, nama anggota, or nama simpanan
            if (!empty($search)) {
                $query .= " AND (
                    sa.no_rekening LIKE :search
                    OR a.nama_lengkap LIKE :search
                    OR a.no_anggota LIKE :search
                    OR js.nama_simpanan LIKE :search
                )";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY sa.no_rekening ASC LIMIT :perPage OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $countQuery = "SELECT COUNT(*) as total
                           FROM tb_simpanan_anggota sa
                           INNER JOIN tb_anggota a ON sa.id_anggota = a.id_anggota
                           INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                           WHERE 1=1";

            if (!empty($search)) {
                $countQuery .= " AND (
                    sa.no_rekening LIKE :search
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
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            $totalPages = ceil($total / $perPage);

            return [
                'status' => true,
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages,
                'message' => 'Data rekening berhasil diambil'
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] GetAll Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0,
                'message' => 'Terjadi kesalahan saat mengambil data rekening'
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
            // Query sesuai dengan database aktual (tanpa bunga_default)
            $query = "SELECT
                        sa.no_rekening,
                        sa.id_anggota,
                        sa.id_jenis,
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
                    'message' => 'Data rekening ditemukan'
                ];
            }

            return [
                'status' => false,
                'message' => 'Rekening tidak ditemukan'
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] GetByNo Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data rekening'
            ];
        }
    }

    /**
     * Check if no_rekening exists
     * @param string $noRekening
     * @param string $excludeNoRekening (for update)
     * @return array
     */
    public function checkNoRekeningExists(string $noRekening, string $excludeNoRekening = ''): array
    {
        try {
            $query = "SELECT no_rekening FROM tb_simpanan_anggota WHERE no_rekening = :no_rekening";

            if (!empty($excludeNoRekening)) {
                $query .= " AND no_rekening != :exclude";
            }

            $query .= " LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':no_rekening', $noRekening, PDO::PARAM_STR);

            if (!empty($excludeNoRekening)) {
                $stmt->bindParam(':exclude', $excludeNoRekening, PDO::PARAM_STR);
            }

            $stmt->execute();

            $exists = $stmt->fetch(PDO::FETCH_ASSOC) !== false;

            return [
                'status' => true,
                'exists' => $exists,
                'message' => $exists ? 'No. Rekening sudah digunakan' : 'No. Rekening tersedia'
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] CheckNoRekening Error: " . $e->getMessage());
            return [
                'status' => false,
                'exists' => false,
                'message' => 'Terjadi kesalahan saat mengecek no. rekening'
            ];
        }
    }

    /**
     * Create new rekening
     * @param array $data
     * @return array
     */
    public function createRekening(array $data): array
    {
        try {
            // Validate required fields
            if (empty($data['no_rekening']) || empty($data['id_anggota'] || empty($data['id_jenis']))) {
                return [
                    'status' => false,
                    'message' => 'No. Rekening, Anggota, dan Jenis Simpanan wajib diisi'
                ];
            }

            // Check if no_rekening exists
            $check = $this->checkNoRekeningExists($data['no_rekening']);
            if (!$check['status']) {
                return $check;
            }
            if ($check['exists']) {
                return [
                    'status' => false,
                    'message' => 'No. Rekening sudah digunakan'
                ];
            }

            $query = "INSERT INTO tb_simpanan_anggota (no_rekening, id_anggota, id_jenis)
                      VALUES (:no_rekening, :id_anggota, :id_jenis)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':no_rekening', $data['no_rekening'], PDO::PARAM_STR);
            $stmt->bindParam(':id_anggota', $data['id_anggota'], PDO::PARAM_INT);
            $stmt->bindParam(':id_jenis', $data['id_jenis'], PDO::PARAM_INT);

            $result = $stmt->execute();

            if ($result) {
                return [
                    'status' => true,
                    'message' => 'Rekening berhasil dibuat',
                    'no_rekening' => $data['no_rekening']
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal membuat rekening'
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] Create Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat membuat rekening'
            ];
        }
    }

    /**
     * Update rekening
     * @param string $noRekening
     * @param array $data
     * @return array
     */
    public function updateRekening(string $noRekening, array $data): array
    {
        try {
            // Check if rekening exists
            $check = $this->getRekeningByNo($noRekening);
            if (!$check['status']) {
                return [
                    'status' => false,
                    'message' => 'Rekening tidak ditemukan'
                ];
            }

            // Check if new no_rekening exists (if changed)
            if (!empty($data['no_rekening']) && $data['no_rekening'] !== $noRekening) {
                $check = $this->checkNoRekeningExists($data['no_rekening'], $noRekening);
                if (!$check['status']) {
                    return $check;
                }
                if ($check['exists']) {
                    return [
                        'status' => false,
                        'message' => 'No. Rekening baru sudah digunakan'
                    ];
                }
            }

            $query = "UPDATE tb_simpanan_anggota
                      SET id_anggota = :id_anggota,
                          id_jenis = :id_jenis";

            if (!empty($data['no_rekening']) && $data['no_rekening'] !== $noRekening) {
                $query .= ", no_rekening = :no_rekening";
            }

            $query .= " WHERE no_rekening = :old_no_rekening";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $data['id_anggota'], PDO::PARAM_INT);
            $stmt->bindParam(':id_jenis', $data['id_jenis'], PDO::PARAM_INT);
            $stmt->bindParam(':old_no_rekening', $noRekening, PDO::PARAM_STR);

            if (!empty($data['no_rekening']) && $data['no_rekening'] !== $noRekening) {
                $stmt->bindParam(':no_rekening', $data['no_rekening'], PDO::PARAM_STR);
            }

            $result = $stmt->execute();

            if ($result) {
                $newNoRekening = !empty($data['no_rekening']) && $data['no_rekening'] !== $noRekening
                    ? $data['no_rekening']
                    : $noRekening;

                return [
                    'status' => true,
                    'message' => 'Rekening berhasil diperbarui',
                    'no_rekening' => $newNoRekening
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal memperbarui rekening'
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] Update Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui rekening'
            ];
        }
    }

    /**
     * Delete rekening
     * @param string $noRekening
     * @return array
     */
    public function deleteRekening(string $noRekening): array
    {
        try {
            // Check if rekening exists
            $check = $this->getRekeningByNo($noRekening);
            if (!$check['status']) {
                return [
                    'status' => false,
                    'message' => 'Rekening tidak ditemukan'
                ];
            }

            // Check if has transactions
            $transQuery = "SELECT COUNT(*) as total FROM tb_transaksi_simpanan WHERE no_rekening = :no_rekening";
            $transStmt = $this->conn->prepare($transQuery);
            $transStmt->bindParam(':no_rekening', $noRekening, PDO::PARAM_STR);
            $transStmt->execute();
            $hasTransactions = $transStmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;

            if ($hasTransactions) {
                return [
                    'status' => false,
                    'message' => 'Rekening tidak dapat dihapus karena sudah memiliki transaksi'
                ];
            }

            $query = "DELETE FROM tb_simpanan_anggota WHERE no_rekening = :no_rekening";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':no_rekening', $noRekening, PDO::PARAM_STR);
            $result = $stmt->execute();

            if ($result) {
                return [
                    'status' => true,
                    'message' => 'Rekening berhasil dihapus'
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal menghapus rekening'
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] Delete Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus rekening'
            ];
        }
    }

    /**
     * Get daftar anggota untuk dropdown
     * @return array
     */
    public function getDaftarAnggota(): array
    {
        try {
            $query = "SELECT id_anggota, no_anggota, nama_lengkap, status_aktif
                      FROM tb_anggota
                      WHERE status_aktif = 'Aktif'
                      ORDER BY nama_lengkap ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] GetDaftarAnggota Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => []
            ];
        }
    }

    /**
     * Get daftar jenis simpanan untuk dropdown
     * @return array
     */
    public function getDaftarJenisSimpanan(): array
    {
        try {
            // Query sesuai dengan database aktual (tanpa bunga_default)
            $query = "SELECT id_jenis, nama_simpanan, akad, minimal_setor
                      FROM tb_jenis_simpanan
                      ORDER BY nama_simpanan ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] GetDaftarJenisSimpanan Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => []
            ];
        }
    }

    /**
     * Generate automatic no_rekening
     * Format: REK-YYYYMMDD-XXXX
     * @return string
     */
    public function generateNoRekening(): string
    {
        $date = date('Ymd');
        $prefix = "REK-{$date}-";

        // Get last no_rekening for today
        $query = "SELECT no_rekening FROM tb_simpanan_anggota
                  WHERE no_rekening LIKE :prefix
                  ORDER BY no_rekening DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $prefixParam = "{$prefix}%";
        $stmt->bindParam(':prefix', $prefixParam, PDO::PARAM_STR);
        $stmt->execute();

        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last) {
            // Extract number from last no_rekening
            $lastNo = (int)substr($last['no_rekening'], -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        return $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get statistics
     * @return array
     */
    public function getStatistics(): array
    {
        try {
            // Total rekening
            $query = "SELECT COUNT(*) as total FROM tb_simpanan_anggota";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $totalRekening = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Rekening per jenis simpanan
            $query = "SELECT
                        js.id_jenis,
                        js.nama_simpanan,
                        js.akad,
                        COUNT(sa.no_rekening) as total_rekening
                      FROM tb_jenis_simpanan js
                      LEFT JOIN tb_simpanan_anggota sa ON js.id_jenis = sa.id_jenis
                      GROUP BY js.id_jenis, js.nama_simpanan, js.akad
                      ORDER BY total_rekening DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $rekeningPerJenis = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => [
                    'total_rekening' => $totalRekening,
                    'rekening_per_jenis' => $rekeningPerJenis
                ]
            ];
        } catch (PDOException $e) {
            error_log("[RekeningModel] GetStatistics Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [
                    'total_rekening' => 0,
                    'rekening_per_jenis' => []
                ]
            ];
        }
    }
}
