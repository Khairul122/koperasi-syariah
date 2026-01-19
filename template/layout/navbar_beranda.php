<?php
// Include required model files
require_once 'models/ProfileModel.php';
require_once 'models/LayananInformasiModel.php';
require_once 'models/InformasiPublikModel.php';
require_once 'models/TataKelolaModel.php';

// Initialize database connection
global $database;
$db = $database->getConnection();

// Fetch profile menu data
$profileModel = new ProfileModel($db);
$profile_menu = $profileModel->getProfilesForNavbar();

// Fetch layanan informasi menu data
$layananModel = new LayananInformasiModel($db);
$allLayanan = $layananModel->getAllLayanan();

// Group layanan by nama_layanan with 3-level support
$layanan_menu = [];
foreach ($allLayanan as $layanan) {
    $nama = $layanan['nama_layanan'];
    $sub = $layanan['sub_layanan'];
    $sub_2 = $layanan['sub_layanan_2'];

    if (!isset($layanan_menu[$nama])) {
        $layanan_menu[$nama] = [];
    }

    if (!empty($sub)) {
        // Ada sub_layanan
        if (!empty($sub_2)) {
            // Ada sub_layanan_2 (3 level)
            if (!isset($layanan_menu[$nama][$sub])) {
                $layanan_menu[$nama][$sub] = [];
            }
            $layanan_menu[$nama][$sub][] = [
                'id' => $layanan['id_layanan'],
                'sub_layanan_2' => $sub_2
            ];
        } else {
            // Hanya sub_layanan (2 level)
            if (!isset($layanan_menu[$nama][$sub])) {
                $layanan_menu[$nama][$sub] = ['_direct_id' => $layanan['id_layanan']];
            }
        }
    } else {
        // Tidak ada sub_layanan (1 level - direct link)
        $layanan_menu[$nama]['_direct_id'] = $layanan['id_layanan'];
    }
}

// Fetch informasi publik menu data
$informasiModel = new InformasiPublikModel($db);
$allInformasi = $informasiModel->getAllInformasi();

// Group informasi by nama_informasi_publik
$informasi_menu = [];
foreach ($allInformasi as $informasi) {
    $nama = $informasi['nama_informasi_publik'];
    if (!isset($informasi_menu[$nama])) {
        $informasi_menu[$nama] = [];
    }
    if (!empty($informasi['sub_informasi_publik'])) {
        $informasi_menu[$nama][] = [
            'id' => $informasi['id_informasi_publik'],
            'sub_informasi_publik' => $informasi['sub_informasi_publik']
        ];
    } else {
        // Store ID for direct link if no sub_informasi_publik exists
        $informasi_menu[$nama]['_direct_id'] = $informasi['id_informasi_publik'];
    }
}

// Fetch dokumen menu data from kategori table
$query_dokumen = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$stmt_dokumen = $db->prepare($query_dokumen);
$stmt_dokumen->execute();
$dokumen_menu = $stmt_dokumen->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    :root {
        --primary-color: #1e3a8a;
        --secondary-color: #f59e0b;
        --accent-color: #fbbf24;
        --text-color: #1f2937;
        --muted-color: #6b7280;
        --light-bg: #f8f9fa;
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--light-bg);
    }

    /* Top Info Bar Styles */
    .top-info-bar {
        background-color: #e5e7eb;
        padding: 8px 0;
        font-size: 13px;
        color: var(--muted-color);
        border-bottom: 1px solid #d1d5db;
    }

    .top-info-bar .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-info-links {
        display: flex;
        gap: 20px;
    }

    .top-info-links a {
        color: var(--muted-color);
        text-decoration: none;
        font-weight: 500;
    }

    .top-info-links a:hover {
        color: #374151;
    }

    .top-info-contact {
        display: flex;
        gap: 25px;
        align-items: center;
    }

    /* Navbar Styles */
    .navbar-custom {
        background: #000000;
        padding: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .main-navbar {
        padding: 12px 0;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        color: white !important;
        font-weight: 600;
        text-decoration: none;
    }

    .logo-img {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        background: white;
        border-radius: 8px;
    }

    .logo-img img {
        width: 35px;
        height: 35px;
    }

    .navbar-nav {
        display: flex;
        flex-direction: row;
        gap: 8px;
        align-items: center;
        margin-left: auto;
    }

    .navbar-nav a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 11px;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .navbar-nav a:hover {
        color: #ddd;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-social {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-left: 15px;
        padding-left: 15px;
        border-left: 1px solid rgba(255, 255, 255, 0.3);
    }

    .nav-social a {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.1);
        font-size: 14px;
        padding: 0;
        color: white;
    }

    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
    }

    /* Dropdown Styles */
    .dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    .nav-link-main {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 10px 15px;
    }

    /* Universal Chevron Styling - Sama untuk semua level */
    .dropdown-icon,
    .kategori-icon,
    .sub-kategori-icon {
        font-size: 10px;
        font-weight: 600;
        opacity: 0.9;
        transition: all 0.3s ease;
    }

    .dropdown-icon {
        margin-left: 6px;
    }

    .kategori-icon,
    .sub-kategori-icon {
        margin-left: auto;
    }

    .dropdown-wrapper:hover .dropdown-icon {
        transform: rotate(180deg);
        opacity: 1;
    }

    /* Level 1 Dropdown (Kategori) */
    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #000000;
        min-width: 250px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .dropdown-wrapper:hover .dropdown-content {
        display: block;
    }

    /* Dropdown Item Wrapper */
    .dropdown-item-wrapper {
        position: relative;
    }

    .dropdown-kategori {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid #333;
        transition: background 0.3s;
    }

    .dropdown-kategori:hover {
        background: #1a1a1a;
        color: white;
    }

    .dropdown-item-wrapper:hover .kategori-icon {
        transform: translateX(4px);
        opacity: 1;
    }

    /* Level 2 Dropdown (Sub Layanan) */
    .dropdown-sub {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        background: #1a1a1a;
        min-width: 250px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .dropdown-item-wrapper:hover .dropdown-sub {
        display: block;
    }

    .dropdown-sub a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid #333;
        transition: all 0.3s;
    }

    .dropdown-sub a:hover {
        background: #2a2a2a;
        padding-left: 30px;
    }

    .dropdown-sub a:last-child {
        border-bottom: none;
    }

    /* Sub Item Wrapper (untuk level 3) */
    .dropdown-sub-item-wrapper {
        position: relative;
    }

    .dropdown-sub-kategori {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid #333;
        transition: background 0.3s;
    }

    .dropdown-sub-kategori:hover {
        background: #2a2a2a;
        color: white;
    }

    .dropdown-sub-item-wrapper:hover .sub-kategori-icon {
        transform: translateX(4px);
        opacity: 1;
    }

    /* Level 3 Dropdown (Sub Layanan 2) */
    .dropdown-sub-2 {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        background: #2a2a2a;
        min-width: 220px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .dropdown-sub-item-wrapper:hover .dropdown-sub-2 {
        display: block;
    }

    .dropdown-sub-2 a {
        display: block;
        padding: 10px 18px;
        color: #ddd;
        text-decoration: none;
        border-bottom: 1px solid #444;
        transition: all 0.3s;
        font-size: 13px;
    }

    .dropdown-sub-2 a:hover {
        background: #3a3a3a;
        padding-left: 28px;
        color: white;
    }

    .dropdown-sub-2 a:last-child {
        border-bottom: none;
    }

    /* Direct Link (Tanpa Sub) */
    .dropdown-kategori-direct {
        display: block;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid #333;
        transition: all 0.3s;
    }

    .dropdown-kategori-direct:hover {
        background: #1a1a1a;
        color: white;
        padding-left: 30px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .dropdown-content {
            position: static;
            box-shadow: none;
            background: #1a1a1a;
            margin-top: 0;
        }

        .dropdown-wrapper.mobile-open .dropdown-content {
            display: block;
        }

        .dropdown-sub {
            position: static;
            box-shadow: none;
            background: #2a2a2a;
            margin-left: 0;
        }

        .dropdown-item-wrapper.mobile-open .dropdown-sub {
            display: block;
        }

        .dropdown-sub-2 {
            position: static;
            box-shadow: none;
            background: #3a3a3a;
            margin-left: 0;
        }

        .dropdown-sub-item-wrapper.mobile-open .dropdown-sub-2 {
            display: block;
        }

        .dropdown-kategori {
            padding-left: 30px;
        }

        .dropdown-sub-kategori {
            padding-left: 50px;
        }

        .dropdown-sub a {
            padding-left: 50px;
        }

        .dropdown-sub a:hover {
            padding-left: 60px;
        }

        .dropdown-sub-2 a {
            padding-left: 70px;
        }

        .dropdown-sub-2 a:hover {
            padding-left: 80px;
        }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .top-info-bar {
            display: none;
        }

        .navbar-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #000000;
            flex-direction: column;
            gap: 0;
            padding: 10px 0;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin-left: 0;
        }

        .navbar-nav.show {
            display: flex;
        }

        .mobile-menu-btn {
            display: block;
        }
    }
</style>

<div class="top-info-bar">
    <div class="container">
        <div class="top-info-links">
            <a href="#">TENTANG PPID</a>
            <a href="#">KONTAK PPID</a>
        </div>
        <div class="top-info-contact">
            <span><i class="fas fa-envelope"></i> ppid@mandaiing.go.id</span>
            <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
        </div>
    </div>
</div>

<nav class="navbar-custom">
    <div class="container main-navbar">
        <div class="d-flex justify-content-between align-items-center w-100">
            <a href="index.php" class="navbar-brand">
                <div class="logo-img">
                    <img src="ppid_assets/logo.jpg" alt="Logo">
                </div>
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-nav" id="navLinks">
                <a href="index.php">BERANDA</a>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        PROFIL <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($profile_menu)): ?>
                            <?php foreach ($profile_menu as $kategori => $items): ?>
                                <div class="dropdown-item-wrapper">
                                    <a href="#" class="dropdown-kategori">
                                        <?php echo htmlspecialchars($kategori); ?>
                                        <i class="fas fa-chevron-right kategori-icon"></i>
                                    </a>
                                    <div class="dropdown-sub">
                                        <?php foreach ($items as $item): ?>
                                            <a href="index.php?controller=profile&action=viewDetail&id=<?php echo $item['id_profile']; ?>">
                                                <?php echo htmlspecialchars($item['keterangan']); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        LAYANAN INFORMASI PUBLIK <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($layanan_menu)): ?>
                            <?php foreach ($layanan_menu as $nama_layanan => $items): ?>
                                <?php
                                // Cek apakah direct link (Level 1)
                                if (isset($items['_direct_id'])) {
                                    $direct_id = $items['_direct_id'];
                                    ?>
                                    <!-- Level 1: Tanpa Sub Layanan - Direct Link -->
                                    <a href="index.php?controller=layananInformasi&action=viewDetail&id=<?php echo $direct_id; ?>" class="dropdown-kategori-direct">
                                        <?php echo htmlspecialchars($nama_layanan); ?>
                                    </a>
                                    <?php
                                } else {
                                    // Ada sub_layanan (Level 2 atau 3)
                                    ?>
                                    <div class="dropdown-item-wrapper">
                                        <a href="#" class="dropdown-kategori">
                                            <?php echo htmlspecialchars($nama_layanan); ?>
                                            <i class="fas fa-chevron-right kategori-icon"></i>
                                        </a>
                                        <div class="dropdown-sub">
                                            <?php foreach ($items as $sub_layanan => $sub_items): ?>
                                                <?php
                                                // Cek apakah sub_layanan punya sub_layanan_2
                                                if (is_array($sub_items) && isset($sub_items['_direct_id'])) {
                                                    // Level 2: Hanya sub_layanan (direct link)
                                                    ?>
                                                    <a href="index.php?controller=layananInformasi&action=viewDetail&id=<?php echo $sub_items['_direct_id']; ?>">
                                                        <?php echo htmlspecialchars($sub_layanan); ?>
                                                    </a>
                                                    <?php
                                                } elseif (is_array($sub_items) && !isset($sub_items['_direct_id'])) {
                                                    // Level 3: Ada sub_layanan_2
                                                    ?>
                                                    <div class="dropdown-sub-item-wrapper">
                                                        <a href="#" class="dropdown-sub-kategori">
                                                            <?php echo htmlspecialchars($sub_layanan); ?>
                                                            <i class="fas fa-chevron-right sub-kategori-icon"></i>
                                                        </a>
                                                        <div class="dropdown-sub-2">
                                                            <?php foreach ($sub_items as $item): ?>
                                                                <?php if (is_array($item) && isset($item['sub_layanan_2'])): ?>
                                                                    <a href="index.php?controller=layananInformasi&action=viewDetail&id=<?php echo $item['id']; ?>">
                                                                        <?php echo htmlspecialchars($item['sub_layanan_2']); ?>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        DAFTAR INFORMASI PUBLIK <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($informasi_menu)): ?>
                            <?php foreach ($informasi_menu as $nama_informasi => $items): ?>
                                <?php
                                // Cek apakah ada sub_informasi_publik atau direct link
                                $has_sub = false;
                                $direct_id = null;

                                if (isset($items['_direct_id'])) {
                                    $direct_id = $items['_direct_id'];
                                } else {
                                    // Filter hanya item yang bukan _direct_id
                                    $sub_items = array_filter($items, function ($key) {
                                        return $key !== '_direct_id';
                                    }, ARRAY_FILTER_USE_KEY);

                                    if (!empty($sub_items)) {
                                        $has_sub = true;
                                    }
                                }
                                ?>

                                <?php if ($has_sub): ?>
                                    <!-- Dengan Sub Informasi - Dropdown Bertingkat -->
                                    <div class="dropdown-item-wrapper">
                                        <a href="#" class="dropdown-kategori">
                                            <?php echo htmlspecialchars($nama_informasi); ?>
                                            <i class="fas fa-chevron-right kategori-icon"></i>
                                        </a>
                                        <div class="dropdown-sub">
                                            <?php foreach ($items as $item): ?>
                                                <?php if (is_array($item) && isset($item['sub_informasi_publik'])): ?>
                                                    <a href="index.php?controller=informasiPublik&action=viewDetail&id=<?php echo $item['id']; ?>">
                                                        <?php echo htmlspecialchars($item['sub_informasi_publik']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Tanpa Sub Informasi - Direct Link -->
                                    <a href="index.php?controller=informasiPublik&action=viewDetail&id=<?php echo $direct_id; ?>" class="dropdown-kategori-direct">
                                        <?php echo htmlspecialchars($nama_informasi); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Dropdown Dokumen -->
                        <div class="dropdown-item-wrapper">
                            <a href="#" class="dropdown-kategori">
                                Dokumen
                                <i class="fas fa-chevron-right kategori-icon"></i>
                            </a>
                            <div class="dropdown-sub">
                                <?php if (!empty($dokumen_menu)): ?>
                                    <?php foreach ($dokumen_menu as $kategori): ?>
                                        <a href="index.php?controller=dokumen&action=index&kategori=<?php echo $kategori['id_kategori']; ?>">
                                            <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        TATA KELOLA <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php
                        // Fetch tata kelola data
                        $tataKelolaModel = new TataKelolaModel($db);
                        $tataKelolaList = $tataKelolaModel->getAllTataKelola();
                        ?>
                        <?php if (!empty($tataKelolaList)): ?>
                            <?php foreach ($tataKelolaList as $tataKelola): ?>
                                <a href="<?php echo !empty($tataKelola['link']) ? htmlspecialchars($tataKelola['link']) : '#'; ?>"
                                    <?php echo !empty($tataKelola['link']) ? 'target="_blank"' : ''; ?>>
                                    <?php echo htmlspecialchars($tataKelola['nama_tata_kelola']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#" class="dropdown-kategori-direct">Tidak ada data</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        INFO <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="index.php?controller=album&action=public&kategori=foto" class="dropdown-kategori-direct">
                            Galeri Foto
                        </a>
                        <a href="index.php?controller=album&action=public&kategori=video" class="dropdown-kategori-direct">
                            Galeri Video
                        </a>
                        <a href="index.php?controller=berita&action=public" class="dropdown-kategori-direct">
                            Berita
                        </a>
                        <a href="index.php?controller=faq&action=public" class="dropdown-kategori-direct">
                            FAQ
                        </a>
                        <a href="index.php?controller=pesanMasuk&action=public" class="dropdown-kategori-direct">
                            Kontak
                        </a>
                    </div>
                </div>
                <a href="index.php?controller=auth&action=login">LOGIN</a>

                <div class="nav-social">
                    <a href="https://www.facebook.com/PemkabMandailingNatal" title="Facebook" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a href="https://www.instagram.com/pemkabmandailingnatal/" title="Instagram" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a href="https://www.youtube.com/@DISKOMINFOMADINA" title="YouTube" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-youtube"></i>
                    </a>

                    <a href="https://www.tiktok.com/@diskominfomadina2" title="TikTok" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <a href="#" title="Search">
                        <i class="fas fa-search"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>



<script>
    // Initialize mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.toggle('show');
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (navLinks && mobileMenuBtn && 
                !navLinks.contains(event.target) && 
                !mobileMenuBtn.contains(event.target)) {
                navLinks.classList.remove('show');
            }
        });

        // Mobile dropdown handling
        // Main dropdown toggle
        const mainLinks = document.querySelectorAll('.nav-link-main');
        mainLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const wrapper = this.closest('.dropdown-wrapper');
                    if (wrapper) {
                        wrapper.classList.toggle('mobile-open');
                    }
                }
            });
        });

        // Category toggle for mobile (Level 2)
        const kategoriLinks = document.querySelectorAll('.dropdown-kategori');
        kategoriLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const wrapper = this.closest('.dropdown-item-wrapper');
                    if (wrapper) {
                        wrapper.classList.toggle('mobile-open');
                    }
                }
            });
        });

        // Sub Category toggle for mobile (Level 3)
        const subKategoriLinks = document.querySelectorAll('.dropdown-sub-kategori');
        subKategoriLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const wrapper = this.closest('.dropdown-sub-item-wrapper');
                    if (wrapper) {
                        wrapper.classList.toggle('mobile-open');
                    }
                }
            });
        });
    });
</script>