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
      <?php if ($this->session->flashdata('success')): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
          <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
                <?php echo $this->session->flashdata('success'); ?>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="card border-0 bg-body shadow-sm mt-5">
            <div class="card-body p-md-4 p-xl-5 text-body">
              <h4 class="card-title">Welcome back, <?php echo html_escape($this->session->userdata('fullname')); ?>!</h4>
              <h5 class="card-title">Your Permissions</h5>
              <ul class="list-group">
                <?php
                  $session_permissions = $this->session->userdata('permissions');
                  if (!empty($session_permissions)):
                    foreach ($session_permissions as $permission_id):
                      $permission_name = $this->Permission_model->get_permission_name($permission_id);
                    ?>
                      <li class="list-group-item"><span class="badge text-bg-primary"><?php echo html_escape($permission_name); ?></span> | <?php echo html_escape($permission_id); ?></li>
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
                        <?php echo html_escape($initials); ?>
                      </div>
                      <?php echo html_escape($user->username); ?> (<?php echo html_escape($user->fullname); ?>)
                    </div>
                    <?php
                      $session_permissions = $this->session->userdata('permissions');
                      $list_users_permission_id = $this->Permission_model->get_permission_id('force logout');
                      if (in_array($list_users_permission_id, $session_permissions)):
                    ?>
                    
                    <form action="<?php echo site_url('users/force_logout/' . $user->id); ?>" method="post" style="display: inline;" id="forceLogoutForm">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                      <button type="submit" class="btn btn-danger btn-sm fw-semibold" id="forceLogoutButton">
                        <span id="forceLogoutButtonText"><i class="bi bi-box-arrow-left"></i> Force Logout</span>
                        <span id="forceLogoutButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                      </button>
                    </form>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      var toastElements = document.querySelectorAll('.toast');
      toastElements.forEach(function (toastElement) {
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
      });

      var forceLogoutForm = document.getElementById('forceLogoutForm');
      var forceLogoutButton = document.getElementById('forceLogoutButton');
      var forceLogoutButtonText = document.getElementById('forceLogoutButtonText');
      var forceLogoutButtonSpinner = document.getElementById('forceLogoutButtonSpinner');
      forceLogoutForm.addEventListener('submit', function () {
        forceLogoutButton.disabled = true;
        forceLogoutButtonText.classList.add('d-none');
        forceLogoutButtonSpinner.classList.remove('d-none');
      });
    </script>
  </body>
</html>