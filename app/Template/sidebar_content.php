<ul class="nav flex-column">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link <?= isMenuActive('dashboard') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/dashboard">
            <i class="fas fa-th-large fa-fw"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <?php if ($userRole === 'admin'): ?>

    <!-- Konten -->
    <span class="nav-category">Konten</span>

    <li class="nav-item">
        <a class="nav-link <?= isMenuActive('banner') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/banner">
            <i class="fas fa-images fa-fw"></i>
            <span>Banner</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= isMenuActive('portofolio') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/portofolio">
            <i class="fas fa-briefcase fa-fw"></i>
            <span>Portofolio</span>
        </a>
    </li>

    <!-- Data -->
    <span class="nav-category">Data</span>

    <li class="nav-item">
        <a class="nav-link <?= isMenuActive('bank') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/bank">
            <i class="fas fa-university fa-fw"></i>
            <span>Bank</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= isMenuActive('contactPerson') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/contact-person">
            <i class="fas fa-address-book fa-fw"></i>
            <span>Kontak</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= isMenuActive('socialMedia') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/social-media">
            <i class="fas fa-share-alt fa-fw"></i>
            <span>Media Sosial</span>
        </a>
    </li>

    <?php endif; ?>

    <!-- Akun -->
    <span class="nav-category">Akun</span>

    <li class="nav-item">
        <a class="nav-link logout-link" href="<?= BASE_URL ?>/logout">
            <i class="fas fa-sign-out-alt fa-fw"></i>
            <span>Keluar</span>
        </a>
    </li>

</ul>
