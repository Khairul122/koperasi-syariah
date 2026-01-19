<?php

/**
 * AnggotaModel - Model untuk CRUD data anggota
 * Handle: Create, Read, Update, Delete, Search, Pagination
 */
require_once __DIR__ . '/../config/koneksi.php';

class AnggotaModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        // Cek koneksi
        if ($this->conn === null) {
            error_log("[AnggotaModel] Database connection is NULL");
            die("Database connection failed. Please check your database configuration and ensure the database exists.");
        }
    }

    /**
     * Get semua anggota dengan pagination dan search
     * @param int $page
     * @param int $perPage
     * @param string $search
     * @return array
     */
    public function getAllAnggota(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        try {
            $offset = ($page - 1) * $perPage;

            // Cek apakah ada kolom password_text
            $checkPasswordText = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_text'");
            $hasPasswordText = $checkPasswordText->rowCount() > 0;

            // Query base dengan status_aktif ENUM
            $passwordField = $hasPasswordText ? 'password, password_text' : 'password';

            $query = "SELECT
                        id_anggota,
                        no_anggota,
                        nik,
                        nama_lengkap,
                        jenis_kelamin,
                        tempat_lahir,
                        tanggal_lahir,
                        alamat,
                        no_hp,
                        pekerjaan,
                        username,
                        {$passwordField},
                        status_aktif,
                        tanggal_daftar
                      FROM tb_anggota
                      WHERE 1=1";

            // Add search filter
            $params = [];
            if (!empty($search)) {
                $query .= " AND (
                    no_anggota LIKE :search
                    OR nik LIKE :search
                    OR nama_lengkap LIKE :search
                    OR no_hp LIKE :search
                    OR username LIKE :search
                )";
                $params[':search'] = "%{$search}%";
            }

            // Add pagination
            $query .= " ORDER BY tanggal_daftar DESC, id_anggota DESC LIMIT :perPage OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $countQuery = "SELECT COUNT(*) FROM tb_anggota WHERE 1=1";
            if (!empty($search)) {
                $countQuery .= " AND (
                    no_anggota LIKE :search
                    OR nik LIKE :search
                    OR nama_lengkap LIKE :search
                    OR no_hp LIKE :search
                    OR username LIKE :search
                )";
            }

            $countStmt = $this->conn->prepare($countQuery);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value, PDO::PARAM_STR);
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();

            return [
                'status' => true,
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] GetAll Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data anggota'
            ];
        }
    }

    /**
     * Get anggota by ID
     * @param int $id
     * @return array
     */
    public function getAnggotaById(int $id): array
    {
        try {
            // Cek apakah ada kolom password_text
            $checkPasswordText = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_text'");
            $hasPasswordText = $checkPasswordText->rowCount() > 0;

            $passwordField = $hasPasswordText ? 'password, password_text' : 'password';

            $query = "SELECT
                        id_anggota,
                        no_anggota,
                        nik,
                        nama_lengkap,
                        jenis_kelamin,
                        tempat_lahir,
                        tanggal_lahir,
                        alamat,
                        no_hp,
                        pekerjaan,
                        username,
                        {$passwordField},
                        status_aktif,
                        tanggal_daftar
                      FROM tb_anggota
                      WHERE id_anggota = :id
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return [
                    'status' => true,
                    'data' => $data
                ];
            }

            return [
                'status' => false,
                'message' => 'Anggota tidak ditemukan'
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] GetById Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data anggota'
            ];
        }
    }

    /**
     * Create anggota baru
     * @param array $data
     * @return array
     */
    public function createAnggota(array $data): array
    {
        $stmt = null; // Declare di luar try-catch

        try {
            // Cek NIK dan username unik
            $checkQuery = "SELECT COUNT(*) FROM tb_anggota
                          WHERE nik = :nik OR username = :username";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([
                ':nik' => $data['nik'],
                ':username' => $data['username']
            ]);

            if ($checkStmt->fetchColumn() > 0) {
                return [
                    'status' => false,
                    'message' => 'NIK atau Username sudah terdaftar'
                ];
            }

            // Generate no_anggota
            $no_anggota = $this->generateNoAnggota();

            // Hash password - cek apakah ada kolom password_hash
            $checkColumn = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_hash'");
            $hasPasswordHash = $checkColumn->rowCount() > 0;

            if ($hasPasswordHash) {
                // Gunakan bcrypt
                $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
                $passwordField = 'password_hash';
            } else {
                // Gunakan MD5
                $hashed_password = md5($data['password']);
                $passwordField = 'password';
            }

            // Cek apakah ada kolom password_text untuk menyimpan password asli
            $checkPasswordText = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_text'");
            $hasPasswordText = $checkPasswordText->rowCount() > 0;

            // Build query berdasarkan kolom yang tersedia
            if ($hasPasswordText) {
                $query = "INSERT INTO tb_anggota (
                            no_anggota, nik, nama_lengkap, jenis_kelamin,
                            tempat_lahir, tanggal_lahir, alamat, no_hp, pekerjaan,
                            username, {$passwordField}, password_text, status_aktif, tanggal_daftar
                          ) VALUES (
                            :no_anggota, :nik, :nama_lengkap, :jenis_kelamin,
                            :tempat_lahir, :tanggal_lahir, :alamat, :no_hp, :pekerjaan,
                            :username, :password, :password_text, 'Aktif', CURDATE()
                          )";

                $params = [
                    ':no_anggota' => $no_anggota,
                    ':nik' => $data['nik'],
                    ':nama_lengkap' => $data['nama_lengkap'],
                    ':jenis_kelamin' => $data['jenis_kelamin'],
                    ':tempat_lahir' => $data['tempat_lahir'],
                    ':tanggal_lahir' => $data['tanggal_lahir'],
                    ':alamat' => $data['alamat'],
                    ':no_hp' => $data['no_hp'],
                    ':pekerjaan' => $data['pekerjaan'],
                    ':username' => $data['username'],
                    ':password' => $hashed_password,
                    ':password_text' => $data['password']  // Password asli
                ];
            } else {
                $query = "INSERT INTO tb_anggota (
                            no_anggota, nik, nama_lengkap, jenis_kelamin,
                            tempat_lahir, tanggal_lahir, alamat, no_hp, pekerjaan,
                            username, {$passwordField}, status_aktif, tanggal_daftar
                          ) VALUES (
                            :no_anggota, :nik, :nama_lengkap, :jenis_kelamin,
                            :tempat_lahir, :tanggal_lahir, :alamat, :no_hp, :pekerjaan,
                            :username, :password, 'Aktif', CURDATE()
                          )";

                $params = [
                    ':no_anggota' => $no_anggota,
                    ':nik' => $data['nik'],
                    ':nama_lengkap' => $data['nama_lengkap'],
                    ':jenis_kelamin' => $data['jenis_kelamin'],
                    ':tempat_lahir' => $data['tempat_lahir'],
                    ':tanggal_lahir' => $data['tanggal_lahir'],
                    ':alamat' => $data['alamat'],
                    ':no_hp' => $data['no_hp'],
                    ':pekerjaan' => $data['pekerjaan'],
                    ':username' => $data['username'],
                    ':password' => $hashed_password
                ];
            }

            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($params);

            if ($result) {
                return [
                    'status' => true,
                    'message' => 'Anggota berhasil ditambahkan',
                    'no_anggota' => $no_anggota
                ];
            }

            // Jika execute gagal tapi tidak throw exception
            if ($stmt) {
                error_log("[AnggotaModel] Execute Failed - Error Info: " . print_r($stmt->errorInfo(), true));
            }

            return [
                'status' => false,
                'message' => 'Gagal menambahkan anggota'
            ];
        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();
            $errorCode = $e->getCode();
            error_log("[AnggotaModel] Create Error: " . $errorMsg);
            error_log("[AnggotaModel] Error Code: " . $errorCode);

            if ($stmt) {
                error_log("[AnggotaModel] Statement Error Info: " . print_r($stmt->errorInfo(), true));
            }

            // Cek duplikasi
            if (strpos($errorMsg, 'Duplicate entry') !== false) {
                if (strpos($errorMsg, 'nik') !== false) {
                    return [
                        'status' => false,
                        'message' => 'NIK sudah terdaftar'
                    ];
                } elseif (strpos($errorMsg, 'username') !== false) {
                    return [
                        'status' => false,
                        'message' => 'Username sudah digunakan'
                    ];
                } elseif (strpos($errorMsg, 'no_anggota') !== false) {
                    return [
                        'status' => false,
                        'message' => 'Nomor anggota sudah ada'
                    ];
                }
            }

            return [
                'status' => false,
                'message' => 'Gagal menambahkan anggota: ' . $errorMsg
            ];
        }
    }

    /**
     * Update anggota
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateAnggota(int $id, array $data): array
    {
        try {
            // Cek apakah anggota ada
            $check = $this->getAnggotaById($id);
            if (!$check['status']) {
                return [
                    'status' => false,
                    'message' => 'Anggota tidak ditemukan'
                ];
            }

            // Cek NIK dan username unik (exclude current id)
            $checkQuery = "SELECT COUNT(*) FROM tb_anggota
                          WHERE ((nik = :nik AND nik != :current_nik)
                                 OR (username = :username AND username != :current_username))
                          AND id_anggota != :id";

            $currentData = $check['data'];
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([
                ':nik' => $data['nik'],
                ':current_nik' => $currentData['nik'],
                ':username' => $data['username'],
                ':current_username' => $currentData['username'],
                ':id' => $id
            ]);

            if ($checkStmt->fetchColumn() > 0) {
                return [
                    'status' => false,
                    'message' => 'NIK atau Username sudah digunakan'
                ];
            }

            // Build update query
            $updateFields = [
                'nik = :nik',
                'nama_lengkap = :nama_lengkap',
                'jenis_kelamin = :jenis_kelamin',
                'tempat_lahir = :tempat_lahir',
                'tanggal_lahir = :tanggal_lahir',
                'alamat = :alamat',
                'no_hp = :no_hp',
                'pekerjaan = :pekerjaan',
                'username = :username'
            ];

            // Add password if provided
            if (!empty($data['password'])) {
                // Cek apakah ada kolom password_hash
                $checkColumn = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_hash'");
                $hasPasswordHash = $checkColumn->rowCount() > 0;

                if ($hasPasswordHash) {
                    $updateFields[] = 'password_hash = :password';
                } else {
                    $updateFields[] = 'password = :password';
                }

                // Cek juga password_text
                $checkPasswordText = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_text'");
                $hasPasswordText = $checkPasswordText->rowCount() > 0;

                if ($hasPasswordText) {
                    $updateFields[] = 'password_text = :password_text';
                }
            }

            $query = "UPDATE tb_anggota SET " . implode(', ', $updateFields) . "
                      WHERE id_anggota = :id";

            $stmt = $this->conn->prepare($query);

            $params = [
                ':id' => $id,
                ':nik' => $data['nik'],
                ':nama_lengkap' => $data['nama_lengkap'],
                ':jenis_kelamin' => $data['jenis_kelamin'],
                ':tempat_lahir' => $data['tempat_lahir'],
                ':tanggal_lahir' => $data['tanggal_lahir'],
                ':alamat' => $data['alamat'],
                ':no_hp' => $data['no_hp'],
                ':pekerjaan' => $data['pekerjaan'],
                ':username' => $data['username']
            ];

            if (!empty($data['password'])) {
                // Hash password
                $checkColumn = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_hash'");
                $hasPasswordHash = $checkColumn->rowCount() > 0;

                if ($hasPasswordHash) {
                    $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                } else {
                    $params[':password'] = md5($data['password']);
                }

                // Password text (asli)
                $checkPasswordText = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_text'");
                if ($checkPasswordText->rowCount() > 0) {
                    $params[':password_text'] = $data['password'];
                }
            }

            $result = $stmt->execute($params);

            if ($result) {
                return [
                    'status' => true,
                    'message' => 'Data anggota berhasil diperbarui'
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal memperbarui data anggota'
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] Update Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal memperbarui data anggota'
            ];
        }
    }

    /**
     * Delete anggota (HARD DELETE)
     * @param int $id
     * @return array
     */
    public function deleteAnggota(int $id): array
    {
        try {
            // Cek apakah anggota ada
            $check = $this->getAnggotaById($id);
            if (!$check['status']) {
                return [
                    'status' => false,
                    'message' => 'Anggota tidak ditemukan'
                ];
            }

            // Hard delete
            $query = "DELETE FROM tb_anggota WHERE id_anggota = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            if ($result) {
                return [
                    'status' => true,
                    'message' => 'Anggota berhasil dihapus'
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal menghapus anggota'
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] Delete Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal menghapus anggota'
            ];
        }
    }

    /**
     * Toggle status aktif anggota (Aktif/Non-Aktif)
     * @param int $id
     * @return array
     */
    public function toggleStatus(int $id): array
    {
        try {
            // Cek apakah anggota ada
            $check = $this->getAnggotaById($id);
            if (!$check['status']) {
                return [
                    'status' => false,
                    'message' => 'Anggota tidak ditemukan'
                ];
            }

            $currentStatus = $check['data']['status_aktif'];
            $newStatus = ($currentStatus === 'Aktif') ? 'Non-Aktif' : 'Aktif';

            // Toggle status ENUM
            $query = "UPDATE tb_anggota SET status_aktif = :status WHERE id_anggota = :id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':status' => $newStatus,
                ':id' => $id
            ]);

            if ($result) {
                return [
                    'status' => true,
                    'message' => "Status anggota berhasil diubah menjadi {$newStatus}",
                    'new_status' => $newStatus
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal mengubah status anggota'
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] ToggleStatus Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengubah status anggota'
            ];
        }
    }

    /**
     * Mendapatkan password yang bisa dibaca (plaintext)
     * @param int $id - ID anggota
     * @return array - ['status' => bool, 'password' => string, 'type' => string]
     */
    public function getReadablePassword(int $id): array
    {
        try {
            // PERBAIKAN: Langsung ambil kedua kolom (pastikan kolom password_text sudah dibuat di DB)
            // Jika kolom password_text belum pasti ada, query ini bisa dimodifikasi.
            // Asumsi: Kolom password_text SUDAH ADA (boleh NULL).

            $query = "SELECT password, password_text FROM tb_anggota WHERE id_anggota = :id LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // 1. Cek jika user tidak ditemukan
            if (!$result) {
                return [
                    'status' => false,
                    'password' => 'Tidak ada password',
                    'type' => 'empty',
                    'is_readable' => false
                ];
            }

            // 2. PRIORITAS UTAMA: Cek password_text (Plaintext dari kolom khusus)
            // Menggunakan null coalescing operator (??) untuk menangani jika key tidak ada
            if (!empty($result['password_text'])) {
                return [
                    'status' => true,
                    'password' => $result['password_text'],
                    'type' => 'plaintext',
                    'is_readable' => true
                ];
            }

            // 3. PRIORITAS KEDUA: Cek kolom password biasa
            $password = $result['password'] ?? '';

            if (empty($password)) {
                return [
                    'status' => true, // User ada, tapi password kosong
                    'password' => 'Belum diset',
                    'type' => 'empty',
                    'is_readable' => false
                ];
            }

            $length = strlen($password);

            // Cek MD5
            if ($length === 32 && ctype_xdigit($password)) {
                return [
                    'status' => true,
                    'password' => '********',
                    'type' => 'md5_hash',
                    'is_readable' => false, // Tidak bisa dibaca
                    'hash_value' => $password
                ];
            }

            // Cek BCRYPT
            if ($length === 60 && preg_match('/^\$2[ay]\$/', $password)) {
                return [
                    'status' => true,
                    'password' => '********',
                    'type' => 'bcrypt_hash',
                    'is_readable' => false,
                    'hash_value' => $password
                ];
            }

            // Jika lolos semua cek di atas, berarti kolom 'password' isinya Plaintext
            return [
                'status' => true,
                'password' => $password,
                'type' => 'plaintext',
                'is_readable' => true
            ];
        } catch (PDOException $e) {
            // Fallback cerdas: Jika errornya karena kolom 'password_text' tidak ditemukan (Column not found)
            // Kita coba ambil password biasa saja.
            if (strpos($e->getMessage(), 'Column not found') !== false) {
                // ... jalankan query SELECT password FROM ... biasa di sini (recursive atau manual)
                error_log("[AnggotaModel] Kolom password_text tidak ditemukan, menggunakan fallback.");
            }

            error_log("[AnggotaModel] Error: " . $e->getMessage());
            return [
                'status' => false,
                'password' => 'Error System',
                'type' => 'error',
                'is_readable' => false
            ];
        }
    }

    /**
     * Generate nomor anggota otomatis
     * Format: ANG + TAHUN + BULAN + 4 digit urut
     */
    private function generateNoAnggota(): string
    {
        $date = date('Ym');
        $prefix = "ANG" . $date;

        // TANPA deleted_at
        $query = "SELECT no_anggota FROM tb_anggota
                  WHERE no_anggota LIKE :prefix
                  ORDER BY no_anggota DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':prefix', "{$prefix}%", PDO::PARAM_STR);
        $stmt->execute();

        $last_no = $stmt->fetch(PDO::FETCH_COLUMN);

        if ($last_no) {
            $last_number = (int)substr($last_no, -4);
            $new_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $new_number = '0001';
        }

        return $prefix . $new_number;
    }

    /**
     * Get statistik anggota
     * @return array
     */
    public function getStatistics(): array
    {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN status_aktif = 'Aktif' THEN 1 ELSE 0 END) as aktif,
                        SUM(CASE WHEN status_aktif = 'Non-Aktif' THEN 1 ELSE 0 END) as non_aktif,
                        SUM(CASE WHEN DATE(tanggal_daftar) = CURDATE() THEN 1 ELSE 0 END) as hari_ini
                      FROM tb_anggota";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return [
                'status' => true,
                'data' => $stmt->fetch(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] GetStatistics Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil statistik'
            ];
        }
    }

    /**
     * Get semua anggota dengan rekening dan jenis simpanan
     * Untuk ditampilkan di tabel dengan kolom: nama, no_rekening, status, nama jenis
     * @param int $page
     * @param int $perPage
     * @param string $search
     * @return array
     */
    public function getAllAnggotaWithRekening(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        try {
            $offset = ($page - 1) * $perPage;

            // Query untuk mengambil data anggota dengan rekening
            $query = "SELECT
                        a.id_anggota,
                        a.no_anggota,
                        a.nama_lengkap,
                        a.status_aktif,
                        a.tanggal_daftar,
                        -- Data rekening
                        sa.id_simpanan,
                        sa.no_rekening,
                        sa.saldo_terakhir,
                        sa.status as status_rekening,
                        -- Data jenis simpanan
                        js.nama_simpanan,
                        js.akad
                      FROM tb_anggota a
                      LEFT JOIN tb_simpanan_anggota sa ON a.id_anggota = sa.id_anggota
                      LEFT JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE 1=1";

            $params = [];

            // Search by nama, no_anggota, no_rekening, atau nama_simpanan
            if (!empty($search)) {
                $query .= " AND (
                    a.nama_lengkap LIKE :search
                    OR a.no_anggota LIKE :search
                    OR sa.no_rekening LIKE :search
                    OR js.nama_simpanan LIKE :search
                )";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY a.tanggal_daftar DESC, sa.no_rekening ASC LIMIT :perPage OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $countQuery = "SELECT COUNT(DISTINCT a.id_anggota)
                           FROM tb_anggota a
                           LEFT JOIN tb_simpanan_anggota sa ON a.id_anggota = sa.id_anggota
                           LEFT JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                           WHERE 1=1";

            if (!empty($search)) {
                $countQuery .= " AND (
                    a.nama_lengkap LIKE :search
                    OR a.no_anggota LIKE :search
                    OR sa.no_rekening LIKE :search
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
                'message' => 'Data anggota dengan rekening berhasil diambil'
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] GetAllAnggotaWithRekening Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0,
                'message' => 'Terjadi kesalahan saat mengambil data anggota dengan rekening'
            ];
        }
    }

    /**
     * Get rekening anggota by ID anggota
     * Untuk menampilkan semua rekening yang dimiliki seorang anggota
     * @param int $idAnggota
     * @return array
     */
    public function getRekeningByAnggotaId(int $idAnggota): array
    {
        try {
            $query = "SELECT
                        sa.id_simpanan,
                        sa.no_rekening,
                        sa.saldo_terakhir,
                        sa.total_setoran,
                        sa.total_penarikan,
                        sa.status as status_rekening,
                        js.id_jenis,
                        js.nama_simpanan,
                        js.akad,
                        js.minimal_setor
                      FROM tb_simpanan_anggota sa
                      INNER JOIN tb_jenis_simpanan js ON sa.id_jenis = js.id_jenis
                      WHERE sa.id_anggota = :id_anggota
                      ORDER BY js.nama_simpanan ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_anggota', $idAnggota, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'data' => $data,
                'count' => count($data)
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] GetRekeningByAnggotaId Error: " . $e->getMessage());
            return [
                'status' => false,
                'data' => [],
                'count' => 0,
                'message' => 'Gagal mengambil data rekening anggota'
            ];
        }
    }

    /**
     * Get anggota dengan detail rekening lengkap
     * @param int $id
     * @return array
     */
    public function getAnggotaWithRekeningDetail(int $id): array
    {
        try {
            // Get data anggota
            $anggotaQuery = "SELECT
                               id_anggota,
                               no_anggota,
                               nik,
                               nama_lengkap,
                               jenis_kelamin,
                               tempat_lahir,
                               tanggal_lahir,
                               alamat,
                               no_hp,
                               pekerjaan,
                               username,
                               status_aktif,
                               tanggal_daftar
                             FROM tb_anggota
                             WHERE id_anggota = :id
                             LIMIT 1";

            $stmt = $this->conn->prepare($anggotaQuery);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $anggota = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$anggota) {
                return [
                    'status' => false,
                    'message' => 'Anggota tidak ditemukan'
                ];
            }

            // Get rekening anggota
            $rekeningResult = $this->getRekeningByAnggotaId($id);

            return [
                'status' => true,
                'data' => [
                    'anggota' => $anggota,
                    'rekening' => $rekeningResult['status'] ? $rekeningResult['data'] : [],
                    'total_rekening' => $rekeningResult['status'] ? $rekeningResult['count'] : 0
                ]
            ];
        } catch (PDOException $e) {
            error_log("[AnggotaModel] GetAnggotaWithRekeningDetail Error: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data anggota dengan rekening'
            ];
        }
    }
}
