<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create User</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body class="bg-body-tertiary">
    <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container pt-5 mt-4">
      <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if (! empty($errorMessage)): ?>
          <div class="toast align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
              <div class="toast-body">
                <?php echo html_escape($errorMessage); ?>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="d-flex justify-content-center">
        <div class="card col-md-10 col-lg-10 border-0 bg-body shadow-sm mt-5">
          <div class="card-body p-md-4 p-xl-5">
            <a href="<?php echo site_url('users/list'); ?>" class="btn btn-sm btn-secondary fw-semibold mb-3">
              <i class="bi bi-arrow-left"></i> Back
            </a>
            <form method="post" action="<?php echo site_url('users/store'); ?>" id="createUserForm">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"  value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <h5 class="card-title">
                <i class="bi bi-person-fill-add text-primary"></i> Create a New User
              </h5>
              <h6 class="card-subtitle mb-4 text-body-secondary">
                Fill in the details below to create a new user account
              </h6>
              <div class="row mb-3">
                <div class="col-md-4 mb-3">
                  <label for="fullname" class="form-label">Fullname</label>
                  <input type="text" class="form-control <?php echo form_error('fullname') ? 'is-invalid' : ''; ?>" id="fullname" name="fullname" value="<?php echo html_escape(set_value('fullname')); ?>" required>
                  <?php echo form_error('fullname','<div class="invalid-feedback">','</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo html_escape(set_value('email')); ?>" required>
                  <?php echo form_error('email','<div class="invalid-feedback">','</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo html_escape(set_value('username')); ?>" required>
                  <?php echo form_error('username','<div class="invalid-feedback">','</div>'); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="department" class="form-label">Department</label>
                  <select class="form-control <?php echo form_error('department') ? 'is-invalid' : ''; ?>" id="department" name="department">
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $department): ?>
                      <option value="<?php echo html_escape($department->id); ?>"
                        <?php echo set_value('department') == $department->id ? 'selected' : ''; ?>>
                        <?php echo html_escape($department->name); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="password" class="form-label">
                    Password <small class="text-muted">(min. 6 characters)</small>
                  </label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" id="password" name="password" minlength="6">
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                  <?php echo form_error('password', '<small class="text-danger">','</small>') ?: ''; ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo form_error('confirm_password') ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                  <?php echo form_error('confirm_password', '<small class="text-danger">','</small>') ?: ''; ?>
                </div>
              </div>
              <div class="mb-3">
                <h6>Permissions Access</h6>
                <?php
                  $grouped_permissions = [];

                  foreach ($all_permissions as $permission) {
                    if (stripos($permission->name, 'user') !== false) {
                      $grouped_permissions['User'][] = $permission;
                    } elseif (stripos($permission->name, 'permission') !== false) {
                      $grouped_permissions['Permission'][] = $permission;
                    } elseif (stripos($permission->name, 'department') !== false) {
                      $grouped_permissions['Department'][] = $permission;
                    } else {
                      $grouped_permissions['Other'][] = $permission;
                    }
                  }

                  foreach ($grouped_permissions as $group => $permissions) {
                    usort($grouped_permissions[$group], function($a, $b) {
                      return strcmp($a->name, $b->name);
                    });
                  }
                ?>
                <div class="row">
                  <?php foreach ($grouped_permissions as $group => $permissions): ?>
                    <div class="col-md-4">
                      <div class="mb-3 bg-body-tertiary p-3 rounded-2">
                        <h6 class="fw-semibold text-body-secondary"><?php echo html_escape($group); ?></h6>
                        <?php foreach ($permissions as $permission): ?>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="permission-<?php echo $permission->id; ?>" name="permissions[]" value="<?php echo $permission->id; ?>" <?php echo (in_array($permission->id, $user_permissions)) ? 'checked' : ''; ?>>
                            <label class="form-check-label text-body-secondary" for="permission-<?php echo $permission->id; ?>">
                              <?php echo html_escape($permission->name); ?>
                            </label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold mt-3" id="createButton">
                  <span id="createUserButtonText">Create</span>
                  <output id="createUserButtonSpinner" class="spinner-border spinner-border-sm d-none" aria-live="polite" aria-hidden="true"></output>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.toast').forEach(function(toastEl){
        new bootstrap.Toast(toastEl).show();
        });
        
        document.querySelectorAll('.toggle-password').forEach(function (icon) {
          icon.addEventListener('click', function () {
            var input = icon.previousElementSibling;
            if (input.type === 'password') {
              input.type = 'text';
              icon.classList.remove('bi-eye');
              icon.classList.add('bi-eye-slash');
            } else {
              input.type = 'password';
              icon.classList.remove('bi-eye-slash');
              icon.classList.add('bi-eye');
            }
          });
        });

        var createUserForm = document.getElementById('createUserForm');
        var createButton = document.getElementById('createButton');
        var createUserButtonText = document.getElementById('createUserButtonText');
        var createUserButtonSpinner = document.getElementById('createUserButtonSpinner');
        createUserForm.addEventListener('submit', function () {
          createButton.disabled = true;
          createUserButtonText.classList.add('d-none');
          createUserButtonSpinner.classList.remove('d-none');
        });
      });
    </script>
  </body>
</html>
