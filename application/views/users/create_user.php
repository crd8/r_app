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
        <?php
        $errorHtml = $this->session->flashdata('error');
        if ($errorHtml) {
          preg_match_all('/<p>(.*?)<\/p>/', $errorHtml, $matches);
          $errorMessages = $matches[1];
          foreach ($errorMessages as $error) {
            if (trim($error) !== '') {
              ?>
              <div class="toast align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="d-flex">
                  <div class="toast-body">
                    <?php echo html_escape($error); ?>
                  </div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
              </div>
              <?php
            }
          }
        }
        ?>
      </div>
      <div class="d-flex justify-content-center">
        <div class="card col-md-10 col-lg-10 border-0 bg-body shadow-sm mt-5">
          <div class="card-body p-md-4 p-xl-5">
            <a href="<?php echo site_url('users/list'); ?>" class="btn btn-sm btn-secondary mb-3">
              <i class="bi bi-arrow-left"></i> Back
            </a>
            <form method="post" action="<?php echo site_url('users/store'); ?>">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"  value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <h5 class="card-title">
                <i class="bi bi-person-fill-add text-primary"></i> Create a New User
              </h5>
              <h6 class="card-subtitle mb-4 text-body-secondary">
                Fill in the details below to create a new user account
              </h6>
              <div class="row mb-3">
                <div class="col-md-4 mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo html_escape(set_value('username')); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="fullname" class="form-label">Fullname</label>
                  <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo html_escape(set_value('fullname')); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo html_escape(set_value('email')); ?>" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="department" class="form-label">Department</label>
                  <select class="form-control" id="department" name="department">
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
                    <input type="password" class="form-control" id="password" name="password" minlength="6">
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Permissions</label>
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
                      <h6 class="mt-3"><?php echo html_escape($group); ?></h6>
                      <div class="mb-3 bg-body-tertiary p-3 rounded-2">
                        <?php foreach ($permissions as $permission): ?>
                          <div class="form-check">
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              id="permission-<?php echo $permission->id; ?>" 
                              name="permissions[]" 
                              value="<?php echo $permission->id; ?>"
                              <?php echo (in_array($permission->id, $user_permissions)) ? 'checked' : ''; ?>>
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
                <button type="submit" class="btn btn-primary btn-lg mt-3">Create account</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.toast').forEach(function (toastElement) {
          var toast = new bootstrap.Toast(toastElement);
          toast.show();
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
      });
    </script>
  </body>
</html>
