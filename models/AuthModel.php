<?php
/**
 * AuthModel - Model untuk otentikasi user
 * Handle: Login, Register, Logout, Session Management
 */
require_once __DIR__ . '/../config/koneksi.php';

class AuthModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Login user (Petugas atau Anggota)
     *
     * @param string $username
     * @param string $password
     * @param string $role 'petugas' atau 'anggota'
     * @return array
     */
    public function login(string $username, string $password, string $role = 'petugas'): array
    {
        try {
            // Cek dulu apakah ada kolom password_hash
            $checkColumn = $role === 'petugas'
                ? "SHOW COLUMNS FROM tb_petugas LIKE 'password_hash'"
                : "SHOW COLUMNS FROM tb_anggota LIKE 'password_hash'";
            $columnStmt = $this->conn->query($checkColumn);
            $hasPasswordHash = $columnStmt->rowCount() > 0;

            // Query dengan memilih password atau password_hash
            $passwordField = $hasPasswordHash ? 'password_hash' : 'password';

            $query = $role === 'petugas'
                ? "SELECT id_petugas AS id, username, {$passwordField} as password, nama_lengkap, level, 'petugas' AS role, 'active' AS status
                   FROM tb_petugas
                   WHERE username = :username
                   LIMIT 1"
                : "SELECT id_anggota AS id, username, {$passwordField} as password, nama_lengkap, no_anggota, 'anggota' AS role,
                          CASE WHEN status_aktif = 'Aktif' THEN 'active' ELSE 'inactive' END AS status
                   FROM tb_anggota
                   WHERE username = :username
                   LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return $this->response(false, 'Username tidak ditemukan');
            }

            if ($user['status'] !== 'active') {
                return $this->response(false, 'Akun Anda tidak aktif. Hubungi admin.');
            }

            // Verify password - support both MD5 (legacy) and BCRYPT
            $passwordValid = false;

            if ($hasPasswordHash) {
                // Gunakan password_verify untuk bcrypt
                $passwordValid = password_verify($password, $user['password']);
            } else {
                // Legacy MD5 - cek dulu dengan MD5, jika cocok migrasi ke bcrypt
                if (md5($password) === $user['password']) {
                    $passwordValid = true;
                    // Migrasi ke bcrypt
                    $this->migratePassword($user['id'], $password, $role);
                }
            }

            if (!$passwordValid) {
                return $this->response(false, 'Password salah');
            }

            // Update last login
            $this->updateLastLogin($user['id'], $role);

            unset($user['password']);
            return $this->response(true, 'Login berhasil', $user);

        } catch (PDOException $e) {
            error_log("[AuthModel] Login Error: " . $e->getMessage());
            return $this->response(false, 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Migrasi password dari MD5 ke BCRYPT
     */
    private function migratePassword(int $userId, string $password, string $role): void
    {
        try {
            $table = $role === 'petugas' ? 'tb_petugas' : 'tb_anggota';
            $idField = $role === 'petugas' ? 'id_petugas' : 'id_anggota';

            // Cek apakah kolom password_hash ada
            $checkColumn = $this->conn->query("SHOW COLUMNS FROM {$table} LIKE 'password_hash'");
            if ($checkColumn->rowCount() === 0) {
                return; // Kolom belum ada, skip migrasi
            }

            // Hash password dengan bcrypt
            $newHash = password_hash($password, PASSWORD_BCRYPT);

            // Update password_hash
            $query = "UPDATE {$table} SET password_hash = :hash WHERE {$idField} = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':hash' => $newHash,
                ':id' => $userId
            ]);

            error_log("[AuthModel] Password migrated successfully for user ID: {$userId}");
        } catch (PDOException $e) {
            error_log("[AuthModel] Migrate Password Error: " . $e->getMessage());
        }
    }

    /**
     * Register anggota baru
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        try {
            // Validasi NIK dan username unik
            if ($this->isUserExists($data['username'], $data['nik'])) {
                return $this->response(false, 'Username atau NIK sudah terdaftar');
            }

            $no_anggota = $this->generateNoAnggota();

            // Hash password - cek apakah ada kolom password_hash
            $checkColumn = $this->conn->query("SHOW COLUMNS FROM tb_anggota LIKE 'password_hash'");
            $hasPasswordHash = $checkColumn->rowCount() > 0;

            if ($hasPasswordHash) {
                // Gunakan bcrypt
                $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
                $passwordField = 'password_hash';
            } else {
                // Legacy MD5
                $hashed_password = md5($data['password']);
                $passwordField = 'password';
            }

            $query = "INSERT INTO tb_anggota (
                        no_anggota, nik, nama_lengkap, jenis_kelamin,
                        tempat_lahir, tanggal_lahir, alamat, no_hp, pekerjaan,
                        username, {$passwordField}, tanggal_daftar, status_aktif
                      ) VALUES (
                        :no_anggota, :nik, :nama_lengkap, :jenis_kelamin,
                        :tempat_lahir, :tanggal_lahir, :alamat, :no_hp, :pekerjaan,
                        :username, :password, CURDATE(), 1
                      )";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
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
            ]);

            return $this->response(true, 'Pendaftaran berhasil', ['no_anggota' => $no_anggota]);

        } catch (PDOException $e) {
            error_log("[AuthModel] Register Error: " . $e->getMessage());
            return $this->response(false, 'Gagal mendaftar. Silakan coba lagi.');
        }
    }

    /**
     * Generate nomor anggota otomatis
     * Format: ANG + YYYYMM + 4 digit sequence
     */
    private function generateNoAnggota(): string
    {
        $date = date('Ym');
        $prefix = "ANG{$date}";

        $query = "SELECT no_anggota FROM tb_anggota
                  WHERE no_anggota LIKE :prefix
                  ORDER BY no_anggota DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':prefix', "{$prefix}%", PDO::PARAM_STR);
        $stmt->execute();

        $last_no = $stmt->fetch(PDO::FETCH_COLUMN);
        $sequence = $last_no ? (int)substr($last_no, -4) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Cek apakah user sudah ada
     */
    private function isUserExists(string $username, string $nik): bool
    {
        $query = "SELECT COUNT(*) FROM tb_anggota WHERE username = :username OR nik = :nik";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $username, ':nik' => $nik]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Update last login timestamp
     */
    private function updateLastLogin(int $userId, string $role): void
    {
        try {
            $table = $role === 'petugas' ? 'tb_petugas' : 'tb_anggota';
            $field = $role === 'petugas' ? 'id_petugas' : 'id_anggota';

            // Cek apakah kolom last_login ada
            $checkColumn = $this->conn->query("SHOW COLUMNS FROM {$table} LIKE 'last_login'");
            if ($checkColumn->rowCount() === 0) {
                return; // Kolom belum ada, skip update
            }

            // Update last_login
            $query = "UPDATE {$table} SET last_login = NOW() WHERE {$field} = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $userId]);

        } catch (PDOException $e) {
            error_log("[AuthModel] UpdateLastLogin Error: " . $e->getMessage());
        }
    }

    /**
     * Cek session user
     */
    public function checkSession(): array
    {
        if (isset($_SESSION['user_id'], $_SESSION['role'], $_SESSION['nama_lengkap'])) {
            return [
                'authenticated' => true,
                'user_id' => $_SESSION['user_id'],
                'role' => $_SESSION['role'],
                'nama_lengkap' => $_SESSION['nama_lengkap'],
                'level' => $_SESSION['level'] ?? null
            ];
        }

        return ['authenticated' => false];
    }

    /**
     * Logout user
     */
    public function logout(): array
    {
        session_unset();
        session_destroy();

        return $this->response(true, 'Berhasil logout');
    }

    /**
     * Format response standar
     */
    private function response(bool $status, string $message, $data = null): array
    {
        $response = [
            'status' => $status,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return $response;
    }
}
