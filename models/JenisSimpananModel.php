<?php

class JenisSimpananModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get semua jenis simpanan
     */
    public function getAllJenisSimpanan(): array
    {
        $query = "SELECT * FROM tb_jenis_simpanan ORDER BY nama_simpanan ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get jenis simpanan dengan pagination dan search
     */
    public function getJenisSimpananPaginated(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT * FROM tb_jenis_simpanan WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                nama_simpanan LIKE :search OR
                akad LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY nama_simpanan ASC LIMIT :perPage OFFSET :offset";

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
     * Get total jenis simpanan untuk pagination
     */
    public function getTotalJenisSimpanan(string $search = ''): int
    {
        $query = "SELECT COUNT(*) as total FROM tb_jenis_simpanan WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (
                nama_simpanan LIKE :search OR
                akad LIKE :search
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
     * Get jenis simpanan by ID
     */
    public function getJenisSimpananById(int $id): array|false
    {
        $query = "SELECT * FROM tb_jenis_simpanan WHERE id_jenis = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create jenis simpanan baru
     */
    public function createJenisSimpanan(array $data): bool
    {
        $query = "INSERT INTO tb_jenis_simpanan (nama_simpanan, akad, minimal_setor)
                  VALUES (:nama_simpanan, :akad, :minimal_setor)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nama_simpanan', $data['nama_simpanan'], PDO::PARAM_STR);
        $stmt->bindValue(':akad', $data['akad'], PDO::PARAM_STR);
        $stmt->bindValue(':minimal_setor', $data['minimal_setor'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Update jenis simpanan
     */
    public function updateJenisSimpanan(int $id, array $data): bool
    {
        $query = "UPDATE tb_jenis_simpanan
                  SET nama_simpanan = :nama_simpanan,
                      akad = :akad,
                      minimal_setor = :minimal_setor
                  WHERE id_jenis = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nama_simpanan', $data['nama_simpanan'], PDO::PARAM_STR);
        $stmt->bindValue(':akad', $data['akad'], PDO::PARAM_STR);
        $stmt->bindValue(':minimal_setor', $data['minimal_setor'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Delete jenis simpanan
     */
    public function deleteJenisSimpanan(int $id): bool
    {
        // Cek apakah jenis simpanan masih digunakan
        $query = "SELECT COUNT(*) as total FROM tb_simpanan_anggota WHERE id_jenis = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (($result['total'] ?? 0) > 0) {
            return false; // Masih digunakan, tidak bisa dihapus
        }

        // Hapus jenis simpanan
        $query = "DELETE FROM tb_jenis_simpanan WHERE id_jenis = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Cek apakah nama simpanan sudah ada
     */
    public function isNamaExists(string $nama, int $excludeId = 0): bool
    {
        $query = "SELECT COUNT(*) as total FROM tb_jenis_simpanan WHERE nama_simpanan = :nama";
        $params = [':nama' => $nama];

        if ($excludeId > 0) {
            $query .= " AND id_jenis != :id";
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, $value === $excludeId ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['total'] ?? 0) > 0;
    }

    /**
     * Get statistik jenis simpanan
     */
    public function getStatistics(): array
    {
        $statistics = [];

        // Total jenis simpanan
        $query = "SELECT COUNT(*) as total FROM tb_jenis_simpanan";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $statistics['total_jenis'] = (int)($result['total'] ?? 0);

        // Total rekening per jenis
        $query = "SELECT js.nama_simpanan, COUNT(sa.id_simpanan) as total_rekening,
                          SUM(sa.saldo_terakhir) as total_saldo
                  FROM tb_jenis_simpanan js
                  LEFT JOIN tb_simpanan_anggota sa ON js.id_jenis = sa.id_jenis
                  GROUP BY js.id_jenis, js.nama_simpanan
                  ORDER BY total_rekening DESC";

        $stmt = $this->db->query($query);
        $statistics['per_jenis'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $statistics;
    }
}
