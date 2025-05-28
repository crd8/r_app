<nav class="navbar fixed-top bg-body shadow-sm">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <i id="darkModeToggle" class="bi bi-moon-stars-fill ms-auto me-4" style="cursor: pointer;"></i>
    <div class="dropdown">
      <?php
        $fullname = $this->session->userdata('fullname');
        $initials = '';
        if ($fullname) {
          $names = explode(' ', $fullname);
          foreach ($names as $name) {
            $initials .= strtoupper($name[0]);
          }
        }
      ?>
      <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="rounded-circle bg-body-secondary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
          <?php echo $initials; ?>
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
        <li class="px-3 py-2">
          <p class="mb-0 fw-bold text-primary"><?php echo $this->session->userdata('fullname'); ?></p>
          <p class="mb-0 text-muted text-secondary"><?php echo $this->session->userdata('email'); ?></p>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="<?php echo site_url('profile'); ?>">Profile</a></li>
        <li>
          <form action="<?= site_url('logout'); ?>" method="post" id="logoutForm" style="display: inline;">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            <button type="submit" class="dropdown-item">Sign out</button>
          </form>
        </li>
      </ul>
    </div>
    
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">NAVIGATION</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>" aria-current="page" href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
          </li>
          <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle <?php echo (in_array($this->uri->segment(1), ['users','permissions'])) ? 'active' : ''; ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Administrator
            </button>
            <ul class="dropdown-menu">
              <?php
                $permissions = $this->session->userdata('permissions');
                $list_users_permission_id = $this->PermissionModel->get_permission_id('user list');
                if (in_array($list_users_permission_id, $permissions)):
              ?>
              <li><a class="dropdown-item <?php echo ($this->uri->segment(1) == 'users') ? 'active' : ''; ?>" aria-current="page" href="<?php echo site_url('users/list'); ?>">Users</a></li>
              <?php endif; ?>

              <?php
                $permissions = $this->session->userdata('permissions');
                $permission_list_permission_id = $this->PermissionModel->get_permission_id('permission list');
                if (in_array($permission_list_permission_id, $permissions)):
              ?>
              <li><a class="dropdown-item <?php echo ($this->uri->segment(1) == 'permissions') ? 'active' : ''; ?>" aria-current="page" href="<?php echo site_url('permissions/list'); ?>">Permissions</a></li>
              <?php endif; ?>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(1) == 'departments') ? 'active' : ''; ?>" aria-current="page" href="<?php echo site_url('departments'); ?>">Departments</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var darkModeToggle = document.getElementById('darkModeToggle');
    var htmlElement = document.documentElement;

    if (localStorage.getItem('theme') === 'dark') {
      htmlElement.setAttribute('data-bs-theme', 'dark');
      darkModeToggle.classList.remove('bi-moon-stars-fill');
      darkModeToggle.classList.add('bi-sun-fill');
    } else {
      htmlElement.setAttribute('data-bs-theme', 'light');
      darkModeToggle.classList.remove('bi-sun-fill');
      darkModeToggle.classList.add('bi-moon-stars-fill');
    }

    darkModeToggle.addEventListener('click', function () {
      if (htmlElement.getAttribute('data-bs-theme') === 'dark') {
        htmlElement.setAttribute('data-bs-theme', 'light');
        darkModeToggle.classList.remove('bi-sun-fill');
        darkModeToggle.classList.add('bi-moon-stars-fill');
        localStorage.setItem('theme', 'light');
      } else {
        htmlElement.setAttribute('data-bs-theme', 'dark');
        darkModeToggle.classList.remove('bi-moon-stars-fill');
        darkModeToggle.classList.add('bi-sun-fill');
        localStorage.setItem('theme', 'dark');
      }
    });
  });
</script>
