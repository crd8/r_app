<?php
  $errors = isset($errors) ? $errors : ['fullname'=>'', 'email'=>''];
  $old = isset($old) ? $old : [];
  $password_errors = isset($password_errors) ? $password_errors : ['current_password'=>'','new_password'=>'','confirm_password'=>''];
  $success_password = isset($success_password) ? $success_password : '';
  $toastError = isset($errorToast) ? $errorToast : '';
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body class="bg-body-tertiary">
    <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container pt-5 mt-4">
      <?php $toastError = isset($errorToast) ? $errorToast : ''; ?>
      <?php if ($toastError): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
          <div class="toast text-bg-danger border-0" role="alert" data-bs-delay="5000">
            <div class="d-flex">
              <div class="toast-body"><?= html_escape($toastError) ?></div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="d-flex justify-content-center">
        <div>
          <div class="card col-md-10 col-lg-10 border-0 bg-body shadow-sm mt-5">
            <div class="card-body p-md-4 p-xl-5">
              <form method="post" action="<?php echo site_url('users/update_profile'); ?>" id="profileForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <h5 class="card-title">
                  <i class="bi bi-person-vcard-fill text-info"></i> Profile Information
                </h5>
                <h6 class="card-subtitle mb-4 text-body-secondary">
                  Update your account's profile information and email address.
                </h6>
                <div class="mb-3">
                  <label for="fullname" class="form-label">Fullname</label>
                  <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $this->session->userdata('fullname'); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $this->session->userdata('email'); ?>" required>
                  <?php echo form_error('email', '<small class="text-danger">','</small>') ?: ''; ?> <small class="text-danger text-decoration-underline fw-semibold"><?php echo html_escape(set_value('email')); ?></small>
                </div>
                <div class="d-flex align-items-center mt-3">
                  <button type="submit" class="btn btn-primary fw-semibold" id="updateProfileButton">
                    <span id="updateProfileButtonText">Update profile</span>
                    <output id="updateProfileButtonSpinner" class="spinner-border spinner-border-sm d-none" aria-live="polite" aria-hidden="true"></output>
                  </button>
                  <?php if (! empty($success_profile)): ?>
                    <div id="successProfileMessage" class="ms-3 text-body-secondary small">
                      <?= html_escape($success_profile); ?>
                    </div>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
          <div class="card col-md-10 col-lg-10 border-0 bg-body shadow-sm mt-5">
            <div class="card-body p-md-4 p-xl-5">
              <form method="post" action="<?php echo site_url('users/update_password'); ?>" id="passwordForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <h5 class="card-title">
                  <i class="bi bi-key-fill text-primary"></i> Update Password
                </h5>
                <h6 class="card-subtitle mb-4 text-body-secondary">
                  Ensure your account is using a long, random password to stay secure.
                </h6>
                <div class="mb-3">
                  <label for="current_password" class="form-label">Current Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo $password_errors['current_password'] ? 'is-invalid' : ''; ?>" id="current_password" name="current_password" required>
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                    <?php if ($password_errors['current_password']): ?>
                      <div class="invalid-feedback">
                        <?= strip_tags($password_errors['current_password']); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="new_password" class="form-label">New Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo $password_errors['new_password'] ? 'is-invalid' : ''; ?>" id="new_password" name="new_password" minlength="6" required>
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                    <?php if ($password_errors['new_password']): ?>
                      <div class="invalid-feedback">
                        <?= strip_tags($password_errors['new_password']); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control <?php echo $password_errors['confirm_password'] ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                    <?php if ($password_errors['confirm_password']): ?>
                      <div class="invalid-feedback">
                        <?= strip_tags($password_errors['confirm_password']); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                  <button type="submit" class="btn btn-primary fw-semibold" id="updatePasswordButton">
                    <span id="updatePasswordButtonText">Update password</span>
                    <output id="updatePasswordButtonSpinner" class="spinner-border spinner-border-sm d-none" aria-live="polite" aria-hidden="true"></output>
                  </button>
                  <?php if (! empty($success_password)): ?>
                    <div id="successPasswordMessage" class="ms-3 text-success small">
                      <?= html_escape($success_password); ?>
                    </div>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(function (toastElement) {
          var toast = new bootstrap.Toast(toastElement);
          toast.show();
        });

        var profileForm = document.getElementById('profileForm');
        var updateProfileButton = document.getElementById('updateProfileButton');
        var updateProfileButtonText = document.getElementById('updateProfileButtonText');
        var updateProfileButtonSpinner = document.getElementById('updateProfileButtonSpinner');

        profileForm.addEventListener('submit', function () {
          updateProfileButtonSpinner.classList.remove('d-none');
          updateProfileButtonText.textContent = 'Updating...';
          updateProfileButton.disabled = true;
        });

        var passwordForm = document.getElementById('passwordForm');
        var updatePasswordButton = document.getElementById('updatePasswordButton');
        var updatePasswordButtonText = document.getElementById('updatePasswordButtonText');
        var updatePasswordButtonSpinner = document.getElementById('updatePasswordButtonSpinner');

        passwordForm.addEventListener('submit', function () {
          updatePasswordButtonSpinner.classList.remove('d-none');
          updatePasswordButtonText.textContent = 'Updating...';
          updatePasswordButton.disabled = true;
        });

        var togglePasswordIcons = document.querySelectorAll('.toggle-password');
        togglePasswordIcons.forEach(function (icon) {
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

        var successMsg = document.getElementById('successProfileMessage');
        if (successMsg) {
          setTimeout(function () {
            successMsg.classList.add('d-none');
          }, 5000);
        }

        var successPwdMsg = document.getElementById('successPasswordMessage');
        if (successPwdMsg) {
          setTimeout(function () {
            successPwdMsg.classList.add('d-none');
          }, 5000);
        }
      });
    </script>
  </body>
</html>
