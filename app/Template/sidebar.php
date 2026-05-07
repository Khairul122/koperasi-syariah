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

<!-- Desktop Sidebar -->
<aside class="sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-inner flex-grow-1">
        <?php include(BASE_PATH . 'app/Template/sidebar_content.php'); ?>
    </div>
</aside>

<!-- Mobile Sidebar (Offcanvas) -->
<div class="offcanvas offcanvas-start glass-sidebar" tabindex="-1"
     id="sidebarCanvas" aria-labelledby="sidebarCanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-black text-uppercase fst-italic mb-0" id="sidebarCanvasLabel">
            SYNECTRA
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="sidebar-inner">
            <?php include(BASE_PATH . 'app/Template/sidebar_content.php'); ?>
        </div>
    </div>
</div>
