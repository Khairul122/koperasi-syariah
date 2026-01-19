<?php

/**
 * Sidebar Navigation - Koperasi Syariah
 * Navigasi berdasarkan role: Admin, Bendahara, Anggota
 */

// Fungsi untuk memeriksa apakah menu aktif
function isActive($controller, $action = null)
{
  $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
  $currentAction = isset($_GET['action']) ? $_GET['action'] : '';

  if ($action === null) {
    return $currentController === $controller;
  }
  return $currentController === $controller && $currentAction === $action;
}

// Fungsi untuk memeriksa apakah dropdown aktif
function isDropdownActive($controllers)
{
  $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
  return is_array($controllers) ? in_array($currentController, $controllers) : $currentController === $controllers;
}

// Fungsi untuk memeriksa peran pengguna
function hasRole($allowedRoles)
{
  $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
  $userLevel = isset($_SESSION['level']) ? $_SESSION['level'] : '';

  if ($allowedRoles === 'all') return true;

  // Cek role dan level
  foreach ($allowedRoles as $role) {
    if ($role === 'petugas' && $userRole === 'petugas') return true;
    if ($role === 'anggota' && $userRole === 'anggota') return true;
    if ($role === 'admin' && $userRole === 'petugas' && $userLevel === 'Admin') return true;
    if ($role === 'bendahara' && $userRole === 'petugas' && $userLevel === 'Bendahara') return true;
  }

  return false;
}
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- ==========================================
         MENU DASHBOARD (SEMUA ROLE)
         ========================================== -->
    <?php if (hasRole(['admin', 'bendahara', 'anggota'])): ?>
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('dashboard') ? 'active' : ''; ?>"
          href="<?php
                echo ($_SESSION['level'] ?? '') === 'Admin'
                  ? 'index.php?controller=dashboard&action=admin'
                  : (($_SESSION['level'] ?? '') === 'Bendahara'
                    ? 'index.php?controller=dashboard&action=bendahara'
                    : 'index.php?controller=dashboard&action=anggota')
                ?>">
          <i class="fas fa-home menu-icon fa-sm"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- ==========================================
         MENU KHUSUS ADMIN (Petugas LEVEL: Admin)
         ========================================== -->
    <?php if (hasRole(['admin'])): ?>

      <!-- Dropdown: Master Data -->
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['anggota', 'jenisSimpanan']) ? 'active' : ''; ?>"
          data-bs-toggle="collapse" href="#masterDataDropdown"
          aria-expanded="<?php echo isDropdownActive(['anggota', 'jenisSimpanan']) ? 'true' : 'false'; ?>"
          aria-controls="masterDataDropdown">
          <i class="fas fa-database menu-icon fa-sm"></i>
          <span class="menu-title">Master Data</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['anggota', 'jenisSimpanan']) ? 'show' : ''; ?>" id="masterDataDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('anggota') ? 'active' : ''; ?>"
                href="index.php?controller=anggota&action=index">
                <i class="fas fa-users fa-sm me-2"></i>
                Anggota
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('jenisSimpanan') ? 'active' : ''; ?>"
                href="index.php?controller=jenisSimpanan&action=index">
                <i class="fas fa-wallet fa-sm me-2"></i>
                Jenis Simpanan
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- Dropdown: Transaksi Admin -->
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['rekening', 'tarik', 'setor', 'angsuran']) ? 'active' : ''; ?>"
          data-bs-toggle="collapse" href="#transaksiAdminDropdown"
          aria-expanded="<?php echo isDropdownActive(['rekening', 'tarik', 'setor', 'angsuran']) ? 'true' : 'false'; ?>"
          aria-controls="transaksiAdminDropdown">
          <i class="fas fa-exchange-alt menu-icon fa-sm"></i>
          <span class="menu-title">Transaksi</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['rekening', 'tarik', 'setor', 'angsuran']) ? 'show' : ''; ?>" id="transaksiAdminDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('rekening') ? 'active' : ''; ?>"
                href="index.php?controller=rekening&action=index">
                <i class="fas fa-plus-circle fa-sm me-2"></i>
                Buka Rekening
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('tarik') ? 'active' : ''; ?>"
                href="index.php?controller=tarik&action=index">
                <i class="fas fa-money-bill-wave fa-sm me-2"></i>
                Tarik Tunai
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('setor') ? 'active' : ''; ?>"
                href="index.php?controller=setor&action=index">
                <i class="fas fa-hand-holding-usd fa-sm me-2"></i>
                Setor Tunai
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('angsuran') ? 'active' : ''; ?>"
                href="index.php?controller=angsuran&action=index">
                <i class="fas fa-credit-card fa-sm me-2"></i>
                Input Cicilan
              </a>
            </li>
          </ul>
        </div>
      </li>

    <?php endif; ?>

    <!-- ==========================================
         MENU KHUSUS BENDAHARA (Petugas LEVEL: Bendahara)
         ========================================== -->
    <?php if (hasRole(['bendahara'])): ?>

      <!-- Dropdown: Transaksi Bendahara -->
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['approvalPembiayaan']) ? 'active' : ''; ?>"
          data-bs-toggle="collapse" href="#transaksiBendaharaDropdown"
          aria-expanded="<?php echo isDropdownActive(['approvalPembiayaan']) ? 'true' : 'false'; ?>"
          aria-controls="transaksiBendaharaDropdown">
          <i class="fas fa-exchange-alt menu-icon fa-sm"></i>
          <span class="menu-title">Transaksi</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['approvalPembiayaan']) ? 'show' : ''; ?>" id="transaksiBendaharaDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('approvalPembiayaan') ? 'active' : ''; ?>"
                href="index.php?controller=approvalPembiayaan&action=index">
                <i class="fas fa-check-circle fa-sm me-2"></i>
                Approval Pembiayaan
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- Menu: Monitoring Keuangan -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('keuangan') ? 'active' : ''; ?>"
          href="index.php?controller=keuangan&action=index">
          <i class="fas fa-chart-line menu-icon fa-sm"></i>
          <span class="menu-title">Monitoring Keuangan</span>
        </a>
      </li>

      <!-- Menu: Laporan -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('laporan') ? 'active' : ''; ?>"
          href="index.php?controller=laporan&action=index">
          <i class="fas fa-file-pdf menu-icon fa-sm"></i>
          <span class="menu-title">Laporan</span>
        </a>
      </li>

    <?php endif; ?>

    <!-- ==========================================
         MENU KHUSUS ANGGOTA
         ========================================== -->
    <?php if (hasRole(['anggota'])): ?>

      <!-- Menu: Ajukan Pembiayaan -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('ajukanPembiayaan') ? 'active' : ''; ?>"
          href="index.php?controller=ajukanPembiayaan&action=index">
          <i class="fas fa-hand-holding-usd menu-icon fa-sm"></i>
          <span class="menu-title">Ajukan Pembiayaan</span>
        </a>
      </li>

      <!-- Menu: Cek Saldo -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('saldo') ? 'active' : ''; ?>"
          href="index.php?controller=saldo&action=index">
          <i class="fas fa-wallet menu-icon fa-sm"></i>
          <span class="menu-title">Cek Saldo</span>
        </a>
      </li>

      <!-- Menu: Cek Tagihan -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('tagihan') ? 'active' : ''; ?>"
          href="index.php?controller=tagihan&action=index">
          <i class="fas fa-file-invoice-dollar menu-icon fa-sm"></i>
          <span class="menu-title">Cek Tagihan</span>
        </a>
      </li>

    <?php endif; ?>

    <!-- ==========================================
         MENU LOGOUT (SEMUA ROLE)
         ========================================== -->
    <?php if (hasRole(['admin', 'bendahara', 'anggota'])): ?>
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('auth', 'logout') ? 'active' : ''; ?>"
          href="index.php?controller=auth&action=logout">
          <i class="fas fa-sign-out-alt menu-icon fa-sm"></i>
          <span class="menu-title">Logout</span>
        </a>
      </li>
    <?php endif; ?>

  </ul>
</nav>

<style>
  /* Sidebar user actions */
  .sidebar-user-actions {
    margin-top: auto;
    padding: 15px;
    border-top: 1px solid #e5e7eb;
  }

  .user-details {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-radius: 12px;
    padding: 15px;
    color: white;
  }

  .user-details p {
    color: white;
  }

  .user-details .small {
    opacity: 0.9;
  }
</style>

<script>
  // Fungsi untuk menangani aktifasi item menu saat dibuka dari dropdown
  document.addEventListener('DOMContentLoaded', function() {
    // Membuka dropdown otomatis jika ada item aktif didalamnya
    const activeItems = document.querySelectorAll('.sub-menu .nav-link.active');
    activeItems.forEach(function(item) {
      const parentCollapse = item.closest('.collapse');
      if (parentCollapse) {
        parentCollapse.classList.add('show');
        // Update aria-expanded attribute juga
        const correspondingToggle = document.querySelector('[href="#' + parentCollapse.id + '"]');
        if (correspondingToggle) {
          correspondingToggle.setAttribute('aria-expanded', 'true');
        }
      }
    });

    // Menangani klik pada mobile untuk menutup sidebar
    const sidebarLinks = document.querySelectorAll('#sidebar .nav-link:not(.disabled)');
    sidebarLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        // Menutup sidebar di mobile setelah klik
        if (window.innerWidth < 992) {
          document.body.classList.remove('sidebar-icon-only');
        }
      });
    });
  });
</script>
