<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
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
            <a href="<?php echo site_url('users/list'); ?>" class="btn btn-sm btn-secondary mb-3 fw-semibold">
              <i class="bi bi-arrow-left"></i> Back
            </a>
            <form method="post" action="<?php echo site_url('users/update/' . $user->id); ?>" id="editUserForm">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <h5 class="card-title">
                <i class="bi bi-person-fill text-primary"></i> Edit User
              </h5>
              <h6 class="card-subtitle mb-4 text-body-secondary">
                Update the details below to edit the user account
              </h6>
              <div class="row mb-3">
                <div class="col-md-4 mb-3">
                  <label for="fullname" class="form-label">Fullname</label>
                  <input type="text" class="form-control <?php echo form_error('fullname') ? 'is-invalid' : ''; ?>" id="fullname" name="fullname" value="<?php echo html_escape(set_value('fullname', $user->fullname)); ?>" required>
                  <?php echo form_error('fullname','<div class="invalid-feedback">','</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo html_escape(set_value('email', $user->email)); ?>" required>
                  <?php echo form_error('email','<div class="invalid-feedback">','</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo html_escape(set_value('username', $user->username)); ?>" required>
                  <?php echo form_error('username','<div class="invalid-feedback">','</div>'); ?>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4 mb-3">
                  <label for="department" class="form-label">Department</label>
                  <select class="form-control <?php echo form_error('department') ? 'is-invalid' : ''; ?>" id="department" name="department">
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $dept): ?>
                      <option value="<?php echo html_escape($dept->id); ?>" <?php echo set_value('department', $user->department_id) == $dept->id ? 'selected' : ''; ?>>
                        <?php echo html_escape($dept->name); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('department','<div class="invalid-feedback">','</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="password" class="form-label">
                    Password <small class="text-muted">(leave blank if not changing)</small>
                  </label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" id="password" name="password">
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password"style="cursor: pointer;"></i>
                  </div>
                  <?php echo form_error('password','<div class="invalid-feedback">','</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo form_error('confirm_password') ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password"style="cursor: pointer;"></i>
                  </div>
                  <?php echo form_error('confirm_password', '<small class="text-danger">','</small>') ?: ''; ?>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Permissions</label>
                <?php
                  $grouped = [];
                  foreach ($all_permissions as $p) {
                    if (stripos($p->name,'user')!==false) {
                      $grouped['User'][] = $p;
                    } elseif (stripos($p->name,'permission')!==false) {
                      $grouped['Permission'][] = $p;
                    } elseif (stripos($p->name,'department')!==false) {
                      $grouped['Department'][] = $p;
                    } else {
                      $grouped['Other'][] = $p;
                    }
                  }
                  foreach ($grouped as $g=>$_) {
                    usort($grouped[$g], fn($a,$b)=>strcmp($a->name,$b->name));
                  }
                ?>
                <div class="row">
                  <?php foreach ($grouped as $grp=>$perms): ?>
                    <div class="col-md-4">
                      <h6 class="mt-3"><?php echo html_escape($grp); ?></h6>
                      <div class="mb-3 bg-body-tertiary p-3 rounded-2">
                        <?php foreach ($perms as $perm): ?>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="permission-<?php echo $perm->id; ?>" name="permissions[]" value="<?php echo $perm->id; ?>" <?php echo set_checkbox('permissions[]', $perm->id, in_array($perm->id, $user_permissions)); ?>>
                            <label class="form-check-label text-body-secondary" for="permission-<?php echo $perm->id; ?>">
                              <?php echo html_escape($perm->name); ?>
                            </label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold mt-3" id="updateButton">
                  <span id="UpdateUserButtonText">Create</span>
                  <span id="updateUserButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.querySelectorAll('.toast').forEach(function(toastEl){
        new bootstrap.Toast(toastEl).show();
      });
      document.querySelectorAll('.toggle-password').forEach(icon=>{
        icon.addEventListener('click',()=>{
          const inp = icon.previousElementSibling;
          if (inp.type==='password') {
            inp.type='text'; icon.classList.replace('bi-eye','bi-eye-slash');
          } else {
            inp.type='password'; icon.classList.replace('bi-eye-slash','bi-eye');
          }
        });
      });

      var editUserForm = document.getElementById('editUserForm');
      var updateButton = document.getElementById('updateButton');
      var UpdateUserButtonText = document.getElementById('UpdateUserButtonText');
      var updateUserButtonSpinner = document.getElementById('updateUserButtonSpinner');
      editUserForm.addEventListener('submit', function () {
        updateButton.disabled = true;
        UpdateUserButtonText.classList.add('d-none');
        updateUserButtonSpinner.classList.remove('d-none');
      });
    </script>
  </body>
</html>
