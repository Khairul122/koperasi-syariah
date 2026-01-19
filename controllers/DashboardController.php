<?php
/**
 * DashboardController - Controller untuk dashboard berdasarkan role
 * Handle: Admin, Bendahara, Anggota
 */
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    /**
     * Cek apakah user sudah login
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    /**
     * Cek apakah user memiliki role yang sesuai
     */
    private function hasRole(array $allowedRoles): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $userRole = $_SESSION['role'] ?? '';
        return in_array($userRole, $allowedRoles);
    }

    /**
     * Redirect jika tidak memiliki akses
     */
    private function checkAccess(array $allowedRoles): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth', 'login');
            exit;
        }

        if (!$this->hasRole($allowedRoles)) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('auth', 'login');
            exit;
        }
    }

    /**
     * Index action - Redirect berdasarkan role
     * URL: index.php?controller=dashboard
     */
    public function index(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth', 'login');
            return;
        }

        $role = $_SESSION['role'] ?? '';

        // Redirect berdasarkan role
        switch ($role) {
            case 'petugas':
                $level = $_SESSION['level'] ?? '';
                if ($level === 'Admin') {
                    $this->redirect('dashboard', 'admin');
                } else {
                    $this->redirect('dashboard', 'bendahara');
                }
                break;

            case 'anggota':
                $this->redirect('dashboard', 'anggota');
                break;

            default:
                $this->redirect('auth', 'login');
                break;
        }
    }

    /**
     * Dashboard Admin
     * URL: index.php?controller=dashboard&action=admin
     */
    public function admin(): void
    {
        $this->checkAccess(['petugas']);

        // Pastikan hanya Admin yang bisa akses
        if ($_SESSION['level'] !== 'Admin') {
            $this->setFlash('error', 'Akses ditolak. Halaman ini khusus Admin.');
            $this->redirect('dashboard', 'bendahara');
            return;
        }

        // Ambil statistik
        $statsResult = $this->dashboardModel->getAdminStats();
        $stats = $statsResult['status'] ? $statsResult['data'] : [];

        $chartResult = $this->dashboardModel->getTransaksiChart();
        $chartData = $chartResult['status'] ? $chartResult['data'] : [];

        $activitiesResult = $this->dashboardModel->getRecentActivities(10);
        $recentActivities = $activitiesResult['status'] ? $activitiesResult['data'] : [];

        $page_title = 'Dashboard Admin - Koperasi Syariah';
        require_once __DIR__ . '/../views/dashboard/admin.php';
    }

    /**
     * Dashboard Bendahara
     * URL: index.php?controller=dashboard&action=bendahara
     */
    public function bendahara(): void
    {
        $this->checkAccess(['petugas']);

        // Ambil statistik
        $statsResult = $this->dashboardModel->getBendaharaStats();
        $stats = $statsResult['status'] ? $statsResult['data'] : [];

        // Ambil data chart transaksi 30 hari terakhir
        $chartResult = $this->dashboardModel->getTransaksiChart();
        $chartData = $chartResult['status'] ? $chartResult['data'] : [];

        // Ambil transaksi terbaru
        $recentTransactions = $stats['transaksi_terakhir'] ?? [];

        // Ambil pengajuan pembiayaan terbaru
        $recentPengajuan = $stats['pengajuan_terbaru'] ?? [];

        // Ambil top anggota penabung
        $topAnggotaResult = $this->dashboardModel->getTopAnggota(5);
        $topAnggota = $topAnggotaResult['status'] ? $topAnggotaResult['data'] : [];

        $page_title = 'Dashboard Bendahara - Koperasi Syariah';
        require_once __DIR__ . '/../views/dashboard/bendahara.php';
    }

    /**
     * Dashboard Anggota
     * URL: index.php?controller=dashboard&action=anggota
     */
    public function anggota(): void
    {
        $this->checkAccess(['anggota']);

        $idAnggota = $_SESSION['user_id'] ?? 0;

        // Ambil statistik
        $statsResult = $this->dashboardModel->getAnggotaStats((int)$idAnggota);
        $stats = $statsResult['status'] ? $statsResult['data'] : [];

        // Ambil data chart simpanan anggota
        $chartResult = $this->dashboardModel->getAnggotaTransaksiChart((int)$idAnggota);
        $chartData = $chartResult['status'] ? $chartResult['data'] : [];

        $page_title = 'Dashboard Anggota - Koperasi Syariah';
        require_once __DIR__ . '/../views/dashboard/anggota.php';
    }

    /**
     * Set flash message
     */
    private function setFlash(string $type, string $message): void
    {
        $_SESSION["flash_{$type}"] = $message;
    }

    /**
     * Redirect ke controller/action tertentu
     */
    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
