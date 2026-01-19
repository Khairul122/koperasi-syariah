<?php

require_once __DIR__ . '/../models/ApprovalPembiayaanModel.php';

class ApprovalPembiayaanController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new ApprovalPembiayaanModel($pdo);
    }
    /**
     * Halaman index - daftar pengajuan pending
     */
    public function index(): void
    {
        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $pendingApprovals = $this->model->getPendingApprovals($page, $perPage, $search);
        $total = $this->model->getTotalPending($search);
        $stats = $this->model->getApprovalStats();

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/approval-pembiayaan/index.php';
    }

    /**
     * Halaman history - riwayat approval
     */
    public function history(): void
    {

        // Get parameters
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = $_GET['search'] ?? '';

        // Get data
        $historyList = $this->model->getApprovalHistory($page, $perPage, $search);
        $total = $this->model->getTotalHistory($search);
        $stats = $this->model->getApprovalStats();

        // Pagination
        $totalPages = ceil($total / $perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        // Load view
        require_once __DIR__ . '/../views/approval-pembiayaan/history.php';
    }

    /**
     * Halaman detail - form approval
     */
    public function detail(): void
    {

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pengajuan tidak valid!';
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        // Get detail pengajuan
        $pengajuan = $this->model->getPendingDetail($id);

        if (!$pengajuan) {
            $_SESSION['flash_error'] = 'Pengajuan tidak ditemukan atau sudah diproses!';
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        // Get rekomendasi approval
        $recommendation = $this->model->getApprovalRecommendation($id);

        // Load view
        require_once __DIR__ . '/../views/approval-pembiayaan/detail.php';
    }

    /**
     * Proses approval
     */
    public function approve(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        $id = (int)($_POST['id_pembiayaan'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $catatan = trim($_POST['catatan'] ?? '');
        $catatan = empty($catatan) ? null : $catatan; // Convert empty string to null
        $idPetugas = (int)($_SESSION['user_id'] ?? 0); // Gunakan user_id, bukan id_petugas

        // Log untuk debugging
        error_log("[ApprovalPembiayaanController] approve() - ID Pembiayaan: {$id}, Status: {$status}, ID Petugas (from session user_id): {$idPetugas}");

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID pengajuan tidak valid!';
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        if ($idPetugas <= 0) {
            $_SESSION['flash_error'] = 'Sesi Anda telah berakhir. Silakan login kembali!';
            header('Location: index.php?controller=auth&action=logout');
            exit;
        }

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            $_SESSION['flash_error'] = 'Status tidak valid!';
            header('Location: index.php?controller=approvalpembiayaan&action=detail&id=' . $id);
            exit;
        }

        // Cek pengajuan ada
        $pengajuan = $this->model->getPendingDetail($id);
        if (!$pengajuan) {
            $_SESSION['flash_error'] = 'Pengajuan tidak ditemukan atau sudah diproses!';
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        // Update status
        $result = $this->model->updateApproval($id, $status, $idPetugas, $catatan);
        if ($result) {
            $message = $status === 'Disetujui' ? 'disetujui' : 'ditolak';
            $_SESSION['flash_success'] = "Pengajuan pembiayaan berhasil {$message}!";
        } else {
            $_SESSION['flash_error'] = 'Gagal memproses pengajuan pembiayaan!';
            // Debug: Cek error log di server untuk detail lebih lanjut
            // error_log location tergantung konfigurasi PHP (biasanya di /var/log/php_errors.log atau sejenis)
        }

        header('Location: index.php?controller=approvalpembiayaan&action=index');
        exit;
    }

    /**
     * Batch approve - approve multiple
     */
    public function batchApprove(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        $ids = $_POST['ids'] ?? [];
        $status = trim($_POST['status'] ?? '');
        $idPetugas = (int)($_SESSION['user_id'] ?? 0); // Gunakan user_id, bukan id_petugas

        if (empty($ids) || !in_array($status, ['Disetujui', 'Ditolak'])) {
            $_SESSION['flash_error'] = 'Data tidak valid!';
            header('Location: index.php?controller=approvalpembiayaan&action=index');
            exit;
        }

        if ($idPetugas <= 0) {
            $_SESSION['flash_error'] = 'Sesi Anda telah berakhir. Silakan login kembali!';
            header('Location: index.php?controller=auth&action=logout');
            exit;
        }

        $success = 0;
        $failed = 0;

        foreach ($ids as $id) {
            $id = (int)$id;
            $pengajuan = $this->model->getPendingDetail($id);

            if ($pengajuan && $this->model->updateApproval($id, $status, $idPetugas)) {
                $success++;
            } else {
                $failed++;
            }
        }

        if ($success > 0) {
            $message = $status === 'Disetujui' ? 'disetujui' : 'ditolak';
            $_SESSION['flash_success'] = "{$success} pengajuan berhasil {$message}!" . ($failed > 0 ? " {$failed} gagal." : '');
        } else {
            $_SESSION['flash_error'] = 'Gagal memproses pengajuan pembiayaan!';
        }

        header('Location: index.php?controller=approvalpembiayaan&action=index');
        exit;
    }
}
