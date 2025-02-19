<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body class="bg-body-tertiary">
    <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container-fluid pt-5 mt-4">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="card border-0 bg-body shadow-sm mt-5">
            <div class="card-body p-md-4 p-xl-5 text-body">
              <h4 class="card-title">Welcome back, <?php echo $this->session->userdata('fullname'); ?>!</h4>
              <h5 class="card-title">Your Permissions</h5>
              <ul class="list-group">
                <?php
                  $permissions = $this->session->userdata('permissions');
                  if (!empty($permissions)):
                    foreach ($permissions as $permission_id):
                      $permission_name = $this->Permission_model->get_permission_name($permission_id);
                    ?>
                      <li class="list-group-item"><span class="badge text-bg-primary"><?php echo $permission_name; ?></span> | <?php echo $permission_id; ?></li>
                    <?php
                      endforeach;
                      else:
                    ?>
                  <li class="list-group-item">You have no permissions.</li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="card border-0 bg-body shadow-sm mt-5">
            <div class="card-body p-md-4 p-xl-5 text-body">
              <h5 class="card-title">Logged In Users</h5>
              <ul class="list-group">
                <?php foreach ($logged_in_users as $user): ?>
                  <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                      <?php
                        $fullname = $user->fullname;
                        $initials = '';
                        if ($fullname) {
                          $names = explode(' ', $fullname);
                          foreach ($names as $name) {
                            $initials .= strtoupper($name[0]);
                          }
                        }
                      ?>
                      <div class="rounded-circle bg-body-secondary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <?php echo $initials; ?>
                      </div>
                      <?php echo $user->username; ?> (<?php echo $user->fullname; ?>)
                    </div>
                    <!-- Tombol Force Logout -->
                    <a href="<?php echo site_url('admin/force_logout/' . $user->user_id); ?>" class="btn btn-danger btn-sm">Force Logout</a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
  </body>
</html>