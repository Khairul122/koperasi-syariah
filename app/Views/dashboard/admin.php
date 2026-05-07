<?php require_once BASE_PATH . 'app/Template/header.php'; ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php require_once BASE_PATH . 'app/Template/navbar.php'; ?>
    
    <div class="container-fluid page-body-wrapper">
      <?php require_once BASE_PATH . 'app/Template/sidebar.php'; ?>
      
      <div class="main-panel">
        <div class="content-wrapper">
          
          <!-- Welcome Banner -->
          <div class="neo-card p-5 mb-5" style="background: var(--neo-primary);">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h1 class="fw-black mb-2" style="font-weight: 900; font-size: 3rem; letter-spacing: -2px; text-transform: uppercase;">
                  Welcome, <?= htmlspecialchars($userName) ?>!
                </h1>
                <p class="fw-bold mb-0 text-dark" style="font-size: 1.2rem; text-transform: uppercase;">
                  System Administrator / Synectra Core Panel
                </p>
              </div>
              <div class="col-md-4 text-end d-none d-md-block">
                <i class="fas fa-bolt" style="font-size: 6rem; filter: drop-shadow(4px 4px 0px #000);"></i>
              </div>
            </div>
          </div>

          <div class="row g-4 mb-5">
            <!-- Stats Cards -->
            <div class="col-md-3">
              <div class="neo-card p-4 text-center h-100" style="background: var(--neo-secondary);">
                <div class="brand-icon mb-3 mx-auto" style="background: #fff; border: 3px solid #000; box-shadow: 3px 3px 0px #000; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                </div>
                <h2 class="fw-black mb-1" style="font-weight: 900;"><?= number_format($data['users']['total_users'] ?? 0) ?></h2>
                <p class="fw-bold small mb-0 text-uppercase">Total Users</p>
              </div>
            </div>
            
            <div class="col-md-3">
              <div class="neo-card p-4 text-center h-100" style="background: var(--neo-accent);">
                <div class="brand-icon mb-3 mx-auto" style="background: #fff; border: 3px solid #000; box-shadow: 3px 3px 0px #000; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-user-shield" style="font-size: 1.5rem;"></i>
                </div>
                <h2 class="fw-black mb-1 text-white" style="font-weight: 900;"><?= number_format($data['users']['admin_count'] ?? 0) ?></h2>
                <p class="fw-bold small mb-0 text-white text-uppercase">Admins</p>
              </div>
            </div>

            <div class="col-md-3">
              <div class="neo-card p-4 text-center h-100" style="background: #fff;">
                <div class="brand-icon mb-3 mx-auto" style="background: var(--neo-primary); border: 3px solid #000; box-shadow: 3px 3px 0px #000; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-user" style="font-size: 1.5rem;"></i>
                </div>
                <h2 class="fw-black mb-1" style="font-weight: 900;"><?= number_format($data['users']['client_count'] ?? 0) ?></h2>
                <p class="fw-bold small mb-0 text-uppercase">Clients</p>
              </div>
            </div>

            <div class="col-md-3">
              <div class="neo-card p-4 text-center h-100" style="background: #000;">
                <div class="brand-icon mb-3 mx-auto" style="background: #fff; border: 3px solid #000; box-shadow: 3px 3px 0px var(--neo-secondary); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-chart-line text-dark" style="font-size: 1.5rem;"></i>
                </div>
                <h2 class="fw-black mb-1 text-white" style="font-weight: 900;"><?= number_format($data['users']['new_this_month'] ?? 0) ?></h2>
                <p class="fw-bold small mb-0 text-white text-uppercase">New Growth</p>
              </div>
            </div>
          </div>

          <div class="row g-4">
            <div class="col-lg-8">
              <div class="neo-card h-100">
                <div class="p-4 border-bottom border-3 border-dark d-flex justify-content-between align-items-center" style="background: #fff;">
                  <h4 class="fw-black mb-0 text-uppercase">Recent Personnel</h4>
                  <a href="<?= BASE_URL ?>/users" class="btn neo-btn neo-btn-primary btn-sm px-3">View All</a>
                </div>
                <div class="p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead style="background: var(--neo-bg);">
                        <tr class="border-bottom border-3 border-dark">
                          <th class="p-3 fw-black text-uppercase small">Name</th>
                          <th class="p-3 fw-black text-uppercase small">Email</th>
                          <th class="p-3 fw-black text-uppercase small">Role</th>
                          <th class="p-3 fw-black text-uppercase small">Joined</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($data['recent_users'])): ?>
                          <?php foreach ($data['recent_users'] as $user): ?>
                            <tr class="border-bottom border-2 border-dark">
                              <td class="p-3 fw-bold"><?= htmlspecialchars($user['name']) ?></td>
                              <td class="p-3 fw-bold"><?= htmlspecialchars($user['email']) ?></td>
                              <td class="p-3">
                                <span class="neo-badge <?= $user['role'] === 'admin' ? '' : 'bg-dark' ?>">
                                  <?= strtoupper($user['role']) ?>
                                </span>
                              </td>
                              <td class="p-3 fw-bold"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="4" class="text-center py-5 fw-bold">NO DATA RECORDED</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="neo-card h-100" style="background: #fff;">
                <div class="p-4 border-bottom border-3 border-dark" style="background: var(--neo-bg);">
                  <h4 class="fw-black mb-0 text-uppercase">System Status</h4>
                </div>
                <div class="p-4">
                  <div class="mb-4">
                    <label class="fw-black text-uppercase small d-block mb-1">PHP Environment</label>
                    <div class="neo-card p-3 fw-bold" style="background: var(--neo-secondary);">
                      <?= htmlspecialchars($data['system']['php_version'] ?? 'N/A') ?>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label class="fw-black text-uppercase small d-block mb-1">Server Software</label>
                    <div class="neo-card p-3 fw-bold">
                      <?= htmlspecialchars($data['system']['server_software'] ?? 'N/A') ?>
                    </div>
                  </div>
                  <div class="mb-0">
                    <label class="fw-black text-uppercase small d-block mb-1">System Clock</label>
                    <div class="neo-card p-3 fw-bold" style="background: var(--neo-primary);">
                      <?= htmlspecialchars($data['system']['server_time'] ?? 'N/A') ?>
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

  <?php require_once BASE_PATH . 'app/Template/script.php'; ?>
</body>
</html>
