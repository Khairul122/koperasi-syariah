<style>
    .footer {
        background:black;
        color: white;
        padding: 60px 0 30px;
        margin-top: auto;
        position: relative;
        z-index: 10;
    }

    .footer h5 {
        color: var(--accent-color);
        margin-bottom: 25px;
        font-weight: 600;
        position: relative;
        display: inline-block;
    }

    .footer h5::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    .footer p {
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.6;
    }

    .footer a {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        margin-bottom: 12px;
        display: block;
        transition: all 0.3s ease;
        position: relative;
        padding-left: 15px;
    }

    .footer a::before {
        content: '→';
        position: absolute;
        left: 0;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .footer a:hover {
        color: var(--accent-color);
        transform: translateX(5px);
    }

    .footer a:hover::before {
        opacity: 1;
        color: var(--accent-color);
    }

    .footer .social-links {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }

    .footer .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        margin-bottom: 0;
        padding: 0;
    }

    .footer .social-links a:hover {
        background: var(--accent-color);
        color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(251, 191, 36, 0.3);
    }

    .footer .copyright {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 40px;
        padding-top: 25px;
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
    }

    .footer .footer-logo {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .footer .footer-logo img {
        height: 50px;
        margin-right: 15px;
    }

    .footer .footer-logo h4 {
        margin: 0;
        font-weight: 700;
    }

    .footer .quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .footer .footer-contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .footer .footer-contact-item i {
        margin-right: 12px;
        color: var(--accent-color);
        font-size: 1.1rem;
        margin-top: 4px;
    }

    .footer .footer-contact-item div {
        flex: 1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer {
            padding: 40px 0 20px;
        }

        .footer .social-links {
            justify-content: center;
        }

        .footer .footer-logo {
            justify-content: center;
        }

        .footer .footer-logo img {
            margin-right: 10px;
        }
    }

    @media (max-width: 576px) {
        .footer .footer-logo {
            flex-direction: column;
            text-align: center;
        }

        .footer .footer-logo img {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .footer .social-links {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    /* Wave effect on top of footer */
    .footer::before {
        content: "";
        position: absolute;
        top: -2px;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--accent-color), transparent);
    }
</style>

<footer class="footer" data-aos="fade-up">
    <div class="container">
        <div class="row">
            <!-- Kolom 1: Logo dan Informasi Singkat -->
            <div class="col-lg-4 col-md-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="100">
                <div class="footer-logo">
                    <h4>PPID Madina</h4>
                </div>
                <p style="color: rgba(255, 255, 255, 0.85);">Pejabat Pengelola Informasi dan Dokumentasi Kabupaten Mandailing Natal, mewujudkan tata kelola pemerintahan yang terbuka dan akuntabel.</p>
                
                <!-- Informasi Kontak -->
                <div class="mt-4">
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Alamat:</strong><br>
                            <?= isset($data['kontak']['alamat']) ? htmlspecialchars($data['kontak']['alamat']) : 'Komplek Perkantoran Payaloting, Parbangunan, Panyabungan, Mandailing Natal' ?>
                        </div>
                    </div>
                    
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Telepon:</strong><br>
                            <?= isset($data['kontak']['telepon']) ? htmlspecialchars($data['kontak']['telepon']) : '(0635) 21370' ?>
                        </div>
                    </div>
                    
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email:</strong><br>
                            <?= isset($data['kontak']['email']) ? htmlspecialchars($data['kontak']['email']) : 'diskominfomadina@gmail.com' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Link Terkait -->
            <div class="col-lg-4 col-md-6 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                <h5>Link Terkait</h5>
                <div class="quick-links">
                    <a href="index.php"><i class="fas fa-home me-2"></i>Beranda</a>
                    <a href="index.php?controller=profile&action=public"><i class="fas fa-info-circle me-2"></i>Profil</a>
                    <a href="index.php?controller=layanan&action=public"><i class="fas fa-hand-holding me-2"></i>Layanan Informasi</a>
                    <a href="index.php?controller=informasi&action=public"><i class="fas fa-file-alt me-2"></i>Daftar Informasi Publik</a>
                    <a href="index.php?controller=tata_kelola&action=public"><i class="fas fa-gavel me-2"></i>Tata Kelola</a>
                    <a href="index.php?controller=dokumen&action=index"><i class="fas fa-folder me-2"></i>Dokumen Publik</a>
                    <a href="index.php?controller=berita&action=public"><i class="fas fa-newspaper me-2"></i>Berita</a>
                    <a href="index.php?controller=faq&action=public"><i class="fas fa-question-circle me-2"></i>FAQ</a>
                </div>
            </div>

            <!-- Kolom 3: Media Sosial dan Jam Operasional -->
            <div class="col-lg-4 col-md-12" data-aos="fade-left" data-aos-delay="300">
                <h5>Media Sosial</h5>
                <div class="social-links">
                    <?php 
                    // Ambil data media sosial dari database
                    $socialMedia = [];
                    if (isset($database)) {
                        global $database;
                        $conn = $database->getConnection();
                        if ($conn) {
                            try {
                                $socialQuery = "SELECT * FROM sosial_media ORDER BY id_sosial_media ASC";
                                $socialStmt = $conn->prepare($socialQuery);
                                $socialStmt->execute();
                                $socialResults = $socialStmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach($socialResults as $social) {
                                    $platform = strtolower($social['site']);
                                    $iconClass = '';
                                    
                                    switch($platform) {
                                        case 'facebook':
                                            $iconClass = 'fab fa-facebook-f';
                                            $social['url'] = $social['facebook_link'];
                                            break;
                                        case 'instagram':
                                            $iconClass = 'fab fa-instagram';
                                            $social['url'] = $social['instagram_link'];
                                            break;
                                        case 'youtube':
                                            $iconClass = 'fab fa-youtube';
                                            $social['url'] = $social['site']; // Menggunakan site sebagai url
                                            if (isset($social['instagram_post'])) $social['url'] = $social['instagram_post'];
                                            break;
                                        case 'tiktok':
                                            $iconClass = 'fab fa-tiktok';
                                            $social['url'] = $social['site']; // Menggunakan site sebagai url
                                            break;
                                        default:
                                            $iconClass = 'fas fa-globe';
                                    }
                                    
                                    if (!empty($social['url'])) {
                                        $socialMedia[] = [
                                            'platform' => $social['site'],
                                            'url' => $social['url'],
                                            'icon' => $iconClass
                                        ];
                                    }
                                }
                            } catch (PDOException $e) {
                                // Jika terjadi error, gunakan data default
                                $socialMedia = [
                                    ['platform' => 'Facebook', 'url' => '#', 'icon' => 'fab fa-facebook-f'],
                                    ['platform' => 'Instagram', 'url' => '#', 'icon' => 'fab fa-instagram'],
                                    ['platform' => 'YouTube', 'url' => '#', 'icon' => 'fab fa-youtube'],
                                    ['platform' => 'Twitter', 'url' => '#', 'icon' => 'fab fa-twitter']
                                ];
                            }
                        }
                    }
                    ?>
                    
                    <?php if (!empty($socialMedia)): ?>
                        <?php foreach($socialMedia as $social): ?>
                            <a href="<?= htmlspecialchars($social['url']) ?>" title="<?= htmlspecialchars($social['platform']) ?>" target="_blank">
                                <i class="<?= $social['icon'] ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
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
                    <?php endif; ?>
                </div>

                <!-- Jam Operasional -->
                <div class="mt-4">
                    <h5 class="mb-3">Jam Operasional</h5>
                    <div class="footer-contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Senin - Kamis:</strong><br>
                            <?= isset($data['kontak']['jam_operasional']['senin_kamis']) ? htmlspecialchars($data['kontak']['jam_operasional']['senin_kamis']) : '08.00 - 16.00 WIB' ?>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Jumat:</strong><br>
                            <?= isset($data['kontak']['jam_operasional']['jumat']) ? htmlspecialchars($data['kontak']['jam_operasional']['jumat']) : '08.00 - 16.30 WIB' ?>
                        </div>
                    </div>
                </div>
              
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright mt-5 pt-4 border-top" data-aos="fade-in" data-aos-delay="500">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; <?= date('Y') ?> PPID Mandailing Natal. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk masyarakat Madina</p>
                </div>
            </div>
        </div>
    </div>
</footer>