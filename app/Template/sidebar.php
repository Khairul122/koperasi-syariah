<?php
$currentController = $_GET['controller'] ?? 'dashboard';
$currentAction     = $_GET['action']     ?? 'index';
$userRole          = $_SESSION['user_role'] ?? 'guest';

function isMenuActive($controller, $action = null) {
    global $currentController, $currentAction;
    if ($action) {
        return $currentController === $controller && $currentAction === $action;
    }
    return $currentController === $controller;
}
?>

<style>
    .sidebar-brand-block {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-height: var(--navbar-height, 70px);
        padding: 0 1.25rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        box-sizing: border-box;
    }

    .sidebar-brand-block img {
        width: 42px;
        height: 42px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .sidebar-brand-copy {
        min-width: 0;
        line-height: 1.1;
    }

    .sidebar-brand-copy strong {
        display: block;
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #111;
    }

    .sidebar-brand-copy span {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
        margin-top: 0.15rem;
    }

    .sidebar-brand-link {
        color: inherit;
        text-decoration: none;
    }

    .sidebar-brand-link:hover {
        color: inherit;
        text-decoration: none;
    }

    .sidebar-brand-offcanvas {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-brand-offcanvas img {
        width: 34px;
        height: 34px;
        object-fit: contain;
    }
</style>

<!-- Desktop Sidebar -->
<aside class="sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-inner flex-grow-1">
        <a href="<?= BASE_URL ?>/dashboard" class="sidebar-brand-link">
            <div class="sidebar-brand-block">
                <img src="<?= BASE_URL ?>/assets/images/logo.svg" alt="Synectra Logo">
                <div class="sidebar-brand-copy">
                    <strong>Synectra</strong>
                    <span>Panel</span>
                </div>
            </div>
        </a>
        <?php include(BASE_PATH . 'app/Template/sidebar_content.php'); ?>
    </div>
</aside>

<!-- Mobile Sidebar (Offcanvas) -->
<div class="offcanvas offcanvas-start glass-sidebar" tabindex="-1"
     id="sidebarCanvas" aria-labelledby="sidebarCanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-black text-uppercase fst-italic mb-0" id="sidebarCanvasLabel">
            <a href="<?= BASE_URL ?>/dashboard" class="sidebar-brand-link sidebar-brand-offcanvas">
                <img src="<?= BASE_URL ?>/assets/images/logo.svg" alt="Synectra Logo">
                <span>SYNECTRA</span>
            </a>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="sidebar-inner">
            <?php include(BASE_PATH . 'app/Template/sidebar_content.php'); ?>
        </div>
    </div>
</div>
