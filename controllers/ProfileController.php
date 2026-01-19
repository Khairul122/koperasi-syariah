<?php

require_once __DIR__ . '/../models/ProfileModel.php';

class ProfileController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new ProfileModel($pdo);
    }

    /**
     * Halaman profil anggota
     */
    public function index(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'anggota') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Get username dari session
        $username = $_SESSION['username'] ?? '';

        // Get data profil
        $profil = $this->model->getProfilByUsername($username);

        // Load view
        require_once __DIR__ . '/../views/profile/index.php';
    }

    /**
     * Update profil anggota
     */
    public function update(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'anggota') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Cek apakah method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=profile&action=index');
            exit;
        }

        $id_anggota = $_SESSION['id_anggota'] ?? 0;

        try {
            // Siapkan data
            $data = [
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'jenis_kelamin' => $_POST['jenis_kelamin'] ?? 'L',
                'tempat_lahir' => $_POST['tempat_lahir'] ?? '',
                'tanggal_lahir' => $_POST['tanggal_lahir'] ?? null,
                'alamat' => $_POST['alamat'] ?? '',
                'no_hp' => $_POST['no_hp'] ?? '',
                'pekerjaan' => $_POST['pekerjaan'] ?? ''
            ];

            // Update data di session
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];

            // Validasi data wajib
            if (empty($data['nama_lengkap']) || empty($data['no_hp'])) {
                $_SESSION['flash_error'] = 'Nama lengkap dan No. HP wajib diisi!';
                header('Location: index.php?controller=profile&action=index');
                exit;
            }

            // Cek apakah id_anggota valid
            if ($id_anggota <= 0) {
                $_SESSION['flash_error'] = 'ID anggota tidak valid!';
                header('Location: index.php?controller=profile&action=index');
                exit;
            }

            // Update profil
            $result = $this->model->updateProfilAnggota($data, $id_anggota);

            if ($result) {
                $_SESSION['flash_success'] = 'Profil berhasil diperbarui!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui profil. Silakan coba lagi.';
            }

        } catch (PDOException $e) {
            $_SESSION['flash_error'] = 'Database error: ' . $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        header('Location: index.php?controller=profile&action=index');
        exit;
    }
}
