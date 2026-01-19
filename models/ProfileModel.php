<?php

class ProfileModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Get profil anggota berdasarkan ID
     */
    public function getProfilAnggota($id_anggota): array
    {
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
                    email,
                    pekerjaan,
                    username,
                    status_aktif,
                    tanggal_daftar
                  FROM tb_anggota
                  WHERE id_anggota = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id_anggota, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get profil anggota berdasarkan username
     */
    public function getProfilByUsername($username): array
    {
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
                    email,
                    pekerjaan,
                    username,
                    status_aktif,
                    tanggal_daftar
                  FROM tb_anggota
                  WHERE username = :username";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Update profil anggota
     */
    public function updateProfilAnggota($data, $id_anggota): bool
    {
        $query = "UPDATE tb_anggota SET
                    nama_lengkap = :nama_lengkap,
                    jenis_kelamin = :jenis_kelamin,
                    tempat_lahir = :tempat_lahir,
                    tanggal_lahir = :tanggal_lahir,
                    alamat = :alamat,
                    no_hp = :no_hp,
                    pekerjaan = :pekerjaan
                  WHERE id_anggota = :id";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nama_lengkap', $data['nama_lengkap'], PDO::PARAM_STR);
        $stmt->bindValue(':jenis_kelamin', $data['jenis_kelamin'], PDO::PARAM_STR);
        $stmt->bindValue(':tempat_lahir', $data['tempat_lahir'], PDO::PARAM_STR);
        $stmt->bindValue(':tanggal_lahir', $data['tanggal_lahir'], PDO::PARAM_STR);
        $stmt->bindValue(':alamat', $data['alamat'], PDO::PARAM_STR);
        $stmt->bindValue(':no_hp', $data['no_hp'], PDO::PARAM_STR);
        $stmt->bindValue(':pekerjaan', $data['pekerjaan'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $id_anggota, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Update password anggota
     */
    public function updatePasswordAnggota($password_baru, $id_anggota): bool
    {
        $query = "UPDATE tb_anggota SET password = :password WHERE id_anggota = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':password', md5($password_baru), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id_anggota, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Verifikasi password lama
     */
    public function verifyPassword($password_lama, $id_anggota): bool
    {
        $query = "SELECT password FROM tb_anggota WHERE id_anggota = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id_anggota, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['password'] === md5($password_lama)) {
            return true;
        }

        return false;
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
        return date('d', $date) . ' ' . $bulan[(int)date('m', $date)] . ' ' . date('Y', $date);
    }
}
