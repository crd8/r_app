<nav class="navbar bg-light border-bottom border-light-subtle fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
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
        <div class="rounded-circle text-bg-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
          <?php echo $initials; ?>
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end text-body-secondary">
        <li class="px-3 py-2 bg-light">
          <p class="mb-0 fw-bold text-primary"><?php echo $this->session->userdata('fullname'); ?></p>
          <p class="mb-0 text-muted text-secondary"><?php echo $this->session->userdata('email'); ?></p>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="<?php echo site_url('users/edit_profile'); ?>">Profile</a></li>
        <li><a class="dropdown-item" href="<?php echo site_url('users/logout'); ?>">Sign out</a></li>
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
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'dashboard') ? 'active' : ''; ?>" aria-current="page" href="<?php echo site_url('users/dashboard'); ?>">Dashboard</a>
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'permissions') ? 'active' : ''; ?>" aria-current="page" href="<?php echo site_url('users/dashboard'); ?>">Permissions</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<!-- <div class="container mt-5 pt-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo site_url('users/dashboard'); ?>">Dashboard</a></li>
      <?php if ($this->uri->segment(2) == 'permissions'): ?>
        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
      <?php elseif ($this->uri->segment(2) == 'edit_profile'): ?>
        <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
      <?php endif; ?>
    </ol>
  </nav>
</div> -->