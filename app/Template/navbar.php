<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? 'Guest';
?>

<nav class="navbar fixed-top px-3 d-flex align-items-center justify-content-between">

    <!-- Left: Hamburger + Brand -->
    <div class="d-flex align-items-center gap-3">
        <button class="neo-hamburger d-lg-none" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#sidebarCanvas" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
        <a class="neo-brand d-lg-none" href="<?= BASE_URL ?>/dashboard">
            SYNECTRA <span>PANEL</span>
        </a>
    </div>

    <!-- Right: User dropdown -->
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
           data-bs-toggle="dropdown" aria-expanded="false" style="color:inherit;">
            <div class="neo-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></div>
            <div class="neo-user-info d-none d-md-block">
                <div class="neo-user-name"><?= htmlspecialchars($userName) ?></div>
                <div class="neo-user-role"><?= ucfirst($userRole) ?></div>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end neo-dropdown-menu mt-2">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                    <i class="far fa-user fa-fw"></i> Profil
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                    <i class="fas fa-cog fa-fw"></i> Pengaturan
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="<?= BASE_URL ?>/logout">
                    <i class="fas fa-power-off fa-fw"></i> Keluar
                </a>
            </li>
        </ul>
    </div>
</nav>
