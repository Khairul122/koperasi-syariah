<?php include(BASE_PATH . 'app/Template/header.php'); ?>

<style>
  /* Base Styles */
  .welcome-banner {
    background: #1e3a8a;
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-bottom: 3px solid #1e40af;
    position: relative;
    overflow: hidden;
    animation: slideDown 0.6s ease-out;
  }

  .welcome-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes float {
    0%, 100% {
      transform: translateY(0) scale(1);
    }
    50% {
      transform: translateY(-20px) scale(1.05);
    }
  }

  .welcome-banner h1 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
    position: relative;
    z-index: 1;
    animation: fadeInLeft 0.8s ease-out 0.2s both;
  }

  .welcome-banner p {
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
    font-size: 0.95rem;
    position: relative;
    z-index: 1;
    animation: fadeInLeft 0.8s ease-out 0.4s both;
  }

  @keyframes fadeInLeft {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .profile-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
  }

  .profile-card:nth-child(1) { animation-delay: 0.1s; }
  .profile-card:nth-child(2) { animation-delay: 0.2s; }
  .profile-card:nth-child(3) { animation-delay: 0.3s; }
  .profile-card:nth-child(4) { animation-delay: 0.4s; }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .profile-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-8px) scale(1.02);
    border-color: #cbd5e1;
  }

  .profile-card .icon-box {
    width: 56px;
    height: 56px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .profile-card .icon-box::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .profile-card:hover .icon-box::before {
    width: 200%;
    height: 200%;
  }

  .profile-card .icon-box i {
    position: relative;
    z-index: 1;
    transition: transform 0.3s ease;
  }

  .profile-card:hover .icon-box i {
    transform: scale(1.1) rotate(5deg);
  }

  .profile-card.primary .icon-box {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
  }

  .profile-card.success .icon-box {
    background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
  }

  .profile-card.info .icon-box {
    background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
  }

  .profile-card.warning .icon-box {
    background: linear-gradient(135deg, #b45309 0%, #92400e 100%);
  }

  .profile-card .card-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    transition: color 0.3s ease;
  }

  .profile-card:hover .card-value {
    color: #1e3a8a;
  }

  .profile-card .card-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
  }

  .info-section {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    background: white;
    opacity: 0;
    animation: fadeInUp 0.6s ease-out 0.5s forwards;
  }

  .info-section .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 8px 8px 0 0 !important;
    padding: 1.25rem 1.5rem;
  }

  .info-section .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #1e293b;
    font-size: 1rem;
  }

  .info-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .info-list li {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .info-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 0;
    background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
    transition: height 0.3s ease;
  }

  .info-list li:hover::before {
    height: 100%;
  }

  .info-list li:hover {
    background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
    padding-left: 2rem;
    transform: translateX(5px);
  }

  .info-list li:last-child {
    border-bottom: none;
  }

  .info-list li .info-icon {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
    color: white;
    flex-shrink: 0;
    transition: all 0.3s ease;
  }

  .info-list li:hover .info-icon {
    transform: scale(1.1) rotate(-5deg);
  }

  .info-list li .info-content {
    flex: 1;
  }

  .info-list li .info-content h6 {
    margin: 0;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: color 0.3s ease;
  }

  .info-list li:hover .info-content h6 {
    color: #1e3a8a;
  }

  .info-list li .info-content p {
    margin: 0;
    font-size: 0.9rem;
    color: #1e293b;
    font-weight: 500;
    transition: color 0.3s ease;
  }

  .info-list li:hover .info-content p {
    color: #0f172a;
  }

  .icon-purple { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); }
  .icon-blue { background: linear-gradient(135deg, #0369a1 0%, #075985 100%); }
  .icon-green { background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); }
  .icon-orange { background: linear-gradient(135deg, #b45309 0%, #92400e 100%); }
  .icon-red { background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%); }

  .quick-actions {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    background: white;
    opacity: 0;
    animation: fadeInUp 0.6s ease-out 0.3s forwards;
  }

  .quick-actions .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 8px 8px 0 0 !important;
    padding: 1.25rem 1.5rem;
  }

  .quick-actions .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #1e293b;
    font-size: 1rem;
  }

  .quick-action-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
  }

  .quick-action-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 0;
    background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
    transition: height 0.3s ease;
  }

  .quick-action-item:hover::before {
    height: 100%;
  }

  .quick-action-item:last-child {
    border-bottom: none;
  }

  .quick-action-item:hover {
    background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
    padding-left: 2rem;
    transform: translateX(5px);
  }

  .quick-action-item .action-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    flex-shrink: 0;
  }

  .quick-action-item:hover .action-icon {
    transform: scale(1.1) rotate(-5deg);
  }

  .quick-action-item .action-text h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    transition: color 0.3s ease;
  }

  .quick-action-item:hover .action-text h6 {
    color: #1e3a8a;
  }

  .quick-action-item .action-text p {
    margin: 0;
    font-size: 0.8rem;
    color: #64748b;
    transition: color 0.3s ease;
  }

  .quick-action-item:hover .action-text p {
    color: #475569;
  }

  .action-purple { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; }
  .action-blue { background: linear-gradient(135deg, #0369a1 0%, #075985 100%); color: white; }
  .action-green { background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); color: white; }
  .action-orange { background: linear-gradient(135deg, #b45309 0%, #92400e 100%); color: white; }

  /* Mobile Responsive */
  @media (max-width: 768px) {
    .welcome-banner {
      padding: 1.5rem 0;
      margin-bottom: 1.5rem;
    }

    .welcome-banner::before {
      display: none;
    }

    .welcome-banner h1 {
      font-size: 1.4rem;
      animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .welcome-banner p {
      font-size: 0.85rem;
      animation: fadeInUp 0.6s ease-out 0.4s both;
    }

    .profile-card {
      margin-bottom: 1rem;
      animation: fadeInUp 0.6s ease-out forwards !important;
    }

    .profile-card .icon-box {
      width: 48px;
      height: 48px;
      font-size: 1.3rem;
    }

    .profile-card .card-value {
      font-size: 1.1rem;
    }

    .profile-card .card-label {
      font-size: 0.7rem;
    }

    .profile-card:hover {
      transform: translateY(-4px) scale(1.01);
    }

    .info-section {
      margin-bottom: 1rem;
    }

    .info-section .card-header {
      padding: 1rem;
    }

    .info-section .card-header h5 {
      font-size: 0.95rem;
    }

    .info-list li {
      padding: 0.875rem 1rem;
    }

    .info-list li:hover {
      padding-left: 1rem;
      transform: none;
    }

    .info-list li .info-icon {
      width: 38px;
      height: 38px;
      font-size: 1rem;
      margin-right: 0.875rem;
    }

    .info-list li .info-content h6 {
      font-size: 0.7rem;
    }

    .info-list li .info-content p {
      font-size: 0.85rem;
    }

    .quick-actions {
      margin-bottom: 1rem;
    }

    .quick-actions .card-header {
      padding: 1rem;
    }

    .quick-actions .card-header h5 {
      font-size: 0.95rem;
    }

    .quick-action-item {
      padding: 0.875rem 1rem;
    }

    .quick-action-item:hover {
      padding-left: 1rem;
      transform: none;
    }

    .quick-action-item .action-icon {
      width: 42px;
      height: 42px;
      font-size: 1.1rem;
      margin-right: 0.875rem;
    }

    .quick-action-item .action-text h6 {
      font-size: 0.85rem;
    }

    .quick-action-item .action-text p {
      font-size: 0.75rem;
    }
  }

  @media (max-width: 480px) {
    .welcome-banner h1 {
      font-size: 1.2rem;
    }

    .welcome-banner p {
      font-size: 0.8rem;
    }

    .profile-card .card-value {
      font-size: 1rem;
    }

    .profile-card .card-label {
      font-size: 0.65rem;
    }

    .info-list li {
      padding: 0.75rem 0.875rem;
    }

    .info-list li .info-icon {
      width: 34px;
      height: 34px;
      font-size: 0.9rem;
    }

    .info-list li .info-content h6 {
      font-size: 0.65rem;
    }

    .info-list li .info-content p {
      font-size: 0.8rem;
    }

    .quick-action-item {
      padding: 0.75rem 0.875rem;
    }

    .quick-action-item .action-icon {
      width: 38px;
      height: 38px;
      font-size: 1rem;
    }

    .quick-action-item .action-text h6 {
      font-size: 0.8rem;
    }

    .quick-action-item .action-text p {
      font-size: 0.7rem;
    }
  }

  /* Touch device optimizations */
  @media (hover: none) and (pointer: coarse) {
    .profile-card:hover {
      transform: none;
    }

    .profile-card:active {
      transform: scale(0.98);
    }

    .info-list li:hover {
      padding-left: 1.5rem;
      transform: none;
    }

    .info-list li:active {
      background: #f1f5f9;
    }

    .quick-action-item:hover {
      padding-left: 1.5rem;
      transform: none;
    }

    .quick-action-item:active {
      background: #f1f5f9;
    }
  }
</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include(BASE_PATH . 'app/Template/navbar.php'); ?>
    <div class="container-fluid page-body-wrapper">
 ?>
      <?php include(BASE_PATH . 'app/Template/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Welcome Banner -->
              <div class="welcome-banner">
                <div class="container-fluid p-0">
                  <h1>Selamat Datang, <?php echo htmlspecialchars($userName); ?>! 👋</h1>
                  <p>Dashboard Klien - Kelola akun Anda dengan mudah</p>
                </div>
              </div>

              <!-- Profile Cards -->
              <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card profile-card primary">
                    <div class="card-body">
                      <div class="icon-box">
                        <i class="fas fa-user"></i>
                      </div>
                      <div class="card-value"><?php echo htmlspecialchars($userName); ?></div>
                      <div class="card-label">Nama Lengkap</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card profile-card success">
                    <div class="card-body">
                      <div class="icon-box">
                        <i class="fas fa-envelope"></i>
                      </div>
                      <div class="card-value text-truncate"><?php echo htmlspecialchars($userEmail); ?></div>
                      <div class="card-label">Email</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card profile-card info">
                    <div class="card-body">
                      <div class="icon-box">
                        <i class="fas fa-phone"></i>
                      </div>
                      <div class="card-value"><?php echo htmlspecialchars($userPhone ?: '-'); ?></div>
                      <div class="card-label">No. Telepon</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card profile-card warning">
                    <div class="card-body">
                      <div class="icon-box">
                        <i class="fas fa-id-badge"></i>
                      </div>
                      <div class="card-value"><?php echo ucfirst($userRole); ?></div>
                      <div class="card-label">Role</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Account Information & Quick Actions -->
              <div class="row">
                <div class="col-lg-8 mb-4">
                  <div class="card info-section">
                    <div class="card-header">
                      <h5>Informasi Akun</h5>
                    </div>
                    <div class="card-body p-0">
                      <ul class="info-list">
                        <li>
                          <div class="info-icon icon-purple">
                            <i class="fas fa-user-circle"></i>
                          </div>
                          <div class="info-content">
                            <h6>Nama Lengkap</h6>
                            <p><?php echo htmlspecialchars($userName); ?></p>
                          </div>
                        </li>
                        <li>
                          <div class="info-icon icon-blue">
                            <i class="fas fa-at"></i>
                          </div>
                          <div class="info-content">
                            <h6>Alamat Email</h6>
                            <p><?php echo htmlspecialchars($userEmail); ?></p>
                          </div>
                        </li>
                        <li>
                          <div class="info-icon icon-green">
                            <i class="fas fa-phone-alt"></i>
                          </div>
                          <div class="info-content">
                            <h6>Nomor Telepon</h6>
                            <p><?php echo htmlspecialchars($userPhone ?: 'Belum diatur'); ?></p>
                          </div>
                        </li>
                        <li>
                          <div class="info-icon icon-orange">
                            <i class="fas fa-user-tag"></i>
                          </div>
                          <div class="info-content">
                            <h6>Tipe Akun</h6>
                            <p><?php echo ucfirst($userRole); ?></p>
                          </div>
                        </li>
                        <?php if (!empty($data['user']['created_at'])): ?>
                        <li>
                          <div class="info-icon icon-red">
                            <i class="fas fa-calendar-alt"></i>
                          </div>
                          <div class="info-content">
                            <h6>Bergabung Sejak</h6>
                            <p>
                              <?php echo date('d F Y', strtotime($data['user']['created_at'])); ?>
                              <?php if (!empty($data['account_age'])): ?>
                                <span class="text-muted" style="font-size: 0.85rem;">
                                  (<?php echo htmlspecialchars($data['account_age']); ?>)
                                </span>
                              <?php endif; ?>
                            </p>
                          </div>
                        </li>
                        <?php endif; ?>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 mb-4">
                  <div class="card quick-actions">
                    <div class="card-header">
                      <h5>Aksi Cepat</h5>
                    </div>
                    <div class="card-body p-0">
                      <a href="<?= BASE_URL ?>/profile" class="quick-action-item">
                        <div class="action-icon action-purple">
                          <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="action-text">
                          <h6>Edit Profil</h6>
                          <p>Update informasi pribadi</p>
                        </div>
                      </a>
                      <a href="<?= BASE_URL ?>/settings" class="quick-action-item">
                        <div class="action-icon action-blue">
                          <i class="fas fa-cog"></i>
                        </div>
                        <div class="action-text">
                          <h6>Pengaturan</h6>
                          <p>Konfigurasi akun</p>
                        </div>
                      </a>
                      <a href="<?= BASE_URL ?>/logout" class="quick-action-item">
                        <div class="action-icon action-orange">
                          <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="action-text">
                          <h6>Logout</h6>
                          <p>Keluar dari sistem</p>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- System Info -->
              <div class="row">
                <div class="col-12 mb-4">
                  <div class="card quick-actions">
                    <div class="card-header">
                      <h5>Informasi Sistem</h5>
                    </div>
                    <div class="card-body p-0">
                      <div class="quick-action-item">
                        <div class="action-icon action-purple">
                          <i class="fas fa-clock"></i>
                        </div>
                        <div class="action-text">
                          <h6>Waktu Server</h6>
                          <p><?php echo htmlspecialchars($data['system']['server_time'] ?? 'Unknown'); ?></p>
                        </div>
                      </div>
                      <?php if (!empty($data['system']['timezone'])): ?>
                      <div class="quick-action-item">
                        <div class="action-icon action-blue">
                          <i class="fas fa-globe"></i>
                        </div>
                        <div class="action-text">
                          <h6>Timezone</h6>
                          <p><?php echo htmlspecialchars($data['system']['timezone']); ?></p>
                        </div>
                      </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include(BASE_PATH . 'app/Template/script.php'); ?>
</body>

</html>

