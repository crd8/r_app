<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body>
    <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container pt-5 mt-4">
      <div class="d-flex justify-content-center">
        <div class="card w-50">
          <div class="card-body text-body-secondary">
            <h4 class="card-title">Welcome back, <?php echo $this->session->userdata('fullname'); ?>!</h4>
            <p class="card-text">Here are your permissions:</p>
            <ul class="list-group">
              <?php
                $permissions = $this->session->userdata('permissions');
                if (!empty($permissions)):
                  foreach ($permissions as $permission_id):
                    $permission_name = $this->Permission_model->get_permission_name($permission_id);
                  ?>
                    <li class="list-group-item"><?php echo $permission_id; ?> / <?php echo $permission_name; ?></li>
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
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
  </body>
</html>