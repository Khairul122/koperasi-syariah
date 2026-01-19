<?php

require_once __DIR__ . '/../models/JenisSimpananModel.php';

class JenisSimpananController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new JenisSimpananModel($pdo);
    }

    /**
     * Access control check
     */


    /**
     * Halaman index - daftar jenis simpanan
     */
    public function index(): void
    {

        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $jenisSimpanan = $this->model->getJenisSimpananPaginated($page, $perPage, $search);
        $total = $this->model->getTotalJenisSimpanan($search);
        $statistics = $this->model->getStatistics();

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/jenis-simpanan/index.php';
    }

    /**
     * Halaman create - form tambah jenis simpanan
     */
    public function create(): void
    {
        $data = [
            'id_jenis' => '',
            'nama_simpanan' => '',
            'akad' => '',
            'minimal_setor' => ''
        ];

        require_once __DIR__ . '/../views/jenis-simpanan/form.php';
    }

    /**
     * Store data - simpan jenis simpanan baru
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        // Validate input
        $nama_simpanan = trim($_POST['nama_simpanan'] ?? '');
        $akad = trim($_POST['akad'] ?? '');
        $minimal_setor = trim($_POST['minimal_setor'] ?? '0');

        // Validasi required fields
        if (empty($nama_simpanan)) {
            $_SESSION['flash_error'] = 'Nama simpanan wajib diisi!';
            header('Location: index.php?controller=jenissimpanan&action=create');
            exit;
        }

        if (empty($akad)) {
            $_SESSION['flash_error'] = 'Akad wajib dipilih!';
            header('Location: index.php?controller=jenissimpanan&action=create');
            exit;
        }

        // Validasi minimal_setor
        $minimal_setor = str_replace(['.', ','], '', $minimal_setor);
        if (!is_numeric($minimal_setor) || $minimal_setor < 0) {
            $_SESSION['flash_error'] = 'Minimal setor harus berupa angka yang valid!';
            header('Location: index.php?controller=jenissimpanan&action=create');
            exit;
        }

        // Cek duplikasi nama
        if ($this->model->isNamaExists($nama_simpanan)) {
            $_SESSION['flash_error'] = 'Nama simpanan sudah ada! Gunakan nama lain.';
            header('Location: index.php?controller=jenissimpanan&action=create');
            exit;
        }

        // Prepare data
        $data = [
            'nama_simpanan' => $nama_simpanan,
            'akad' => $akad,
            'minimal_setor' => $minimal_setor
        ];

        // Insert
        if ($this->model->createJenisSimpanan($data)) {
            $_SESSION['flash_success'] = 'Jenis simpanan berhasil ditambahkan!';
            header('Location: index.php?controller=jenissimpanan&action=index');
        } else {
            $_SESSION['flash_error'] = 'Gagal menambahkan jenis simpanan!';
            header('Location: index.php?controller=jenissimpanan&action=create');
        }
        exit;
    }

    /**
     * Halaman edit - form edit jenis simpanan
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID jenis simpanan tidak valid!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        $data = $this->model->getJenisSimpananById($id);

        if (!$data) {
            $_SESSION['flash_error'] = 'Jenis simpanan tidak ditemukan!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        require_once __DIR__ . '/../views/jenis-simpanan/form.php';
    }

    /**
     * Update data - simpan perubahan
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        $id = (int)($_POST['id_jenis'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID jenis simpanan tidak valid!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        // Validate input
        $nama_simpanan = trim($_POST['nama_simpanan'] ?? '');
        $akad = trim($_POST['akad'] ?? '');
        $minimal_setor = trim($_POST['minimal_setor'] ?? '0');

        // Validasi required fields
        if (empty($nama_simpanan)) {
            $_SESSION['flash_error'] = 'Nama simpanan wajib diisi!';
            header("Location: index.php?controller=jenissimpanan&action=edit&id={$id}");
            exit;
        }

        if (empty($akad)) {
            $_SESSION['flash_error'] = 'Akad wajib dipilih!';
            header("Location: index.php?controller=jenissimpanan&action=edit&id={$id}");
            exit;
        }

        // Validasi minimal_setor
        $minimal_setor = str_replace(['.', ','], '', $minimal_setor);
        if (!is_numeric($minimal_setor) || $minimal_setor < 0) {
            $_SESSION['flash_error'] = 'Minimal setor harus berupa angka yang valid!';
            header("Location: index.php?controller=jenissimpanan&action=edit&id={$id}");
            exit;
        }

        // Cek duplikasi nama (exclude current ID)
        if ($this->model->isNamaExists($nama_simpanan, $id)) {
            $_SESSION['flash_error'] = 'Nama simpanan sudah ada! Gunakan nama lain.';
            header("Location: index.php?controller=jenissimpanan&action=edit&id={$id}");
            exit;
        }

        // Prepare data
        $data = [
            'nama_simpanan' => $nama_simpanan,
            'akad' => $akad,
            'minimal_setor' => $minimal_setor
        ];

        // Update
        if ($this->model->updateJenisSimpanan($id, $data)) {
            $_SESSION['flash_success'] = 'Jenis simpanan berhasil diperbarui!';
            header('Location: index.php?controller=jenissimpanan&action=index');
        } else {
            $_SESSION['flash_error'] = 'Gagal memperbarui jenis simpanan!';
            header("Location: index.php?controller=jenissimpanan&action=edit&id={$id}");
        }
        exit;
    }

    /**
     * Delete data - hapus jenis simpanan
     */
    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID jenis simpanan tidak valid!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        // Cek apakah data ada
        $jenis = $this->model->getJenisSimpananById($id);

        if (!$jenis) {
            $_SESSION['flash_error'] = 'Jenis simpanan tidak ditemukan!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        // Delete
        $result = $this->model->deleteJenisSimpanan($id);

        if ($result) {
            $_SESSION['flash_success'] = 'Jenis simpanan berhasil dihapus!';
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus! Jenis simpanan masih digunakan oleh anggota.';
        }

        header('Location: index.php?controller=jenissimpanan&action=index');
        exit;
    }

    /**
     * View detail jenis simpanan
     */
    public function view(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID jenis simpanan tidak valid!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        $jenis = $this->model->getJenisSimpananById($id);

        if (!$jenis) {
            $_SESSION['flash_error'] = 'Jenis simpanan tidak ditemukan!';
            header('Location: index.php?controller=jenissimpanan&action=index');
            exit;
        }

        // Get statistik untuk jenis ini
        $query = "SELECT COUNT(*) as total_rekening,
                          SUM(saldo_terakhir) as total_saldo,
                          SUM(total_setoran) as total_setoran,
                          SUM(total_penarikan) as total_penarikan
                  FROM tb_simpanan_anggota
                  WHERE id_jenis = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $statistik = $stmt->fetch(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/jenis-simpanan/detail.php';
    }
}
