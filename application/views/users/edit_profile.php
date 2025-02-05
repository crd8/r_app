<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body>
    <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container pt-5 mt-4">
      <?php if ($this->session->flashdata('success')): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
          <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center">
              <div class="toast-body d-flex align-items-center">
                <i class="bi bi-check2-circle fs-3 me-2"></i> <span><?php echo $this->session->flashdata('success'); ?></span>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
          <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
                <?php echo $this->session->flashdata('error'); ?>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="d-flex justify-content-center">
        <div>
          <div class="card mb-3 rounded-4">
            <div class="card-body p-md-4 p-xl-5">
              <form method="post" action="<?php echo site_url('users/update_profile'); ?>" id="profileForm">
                <h4 class="fs-5 text-body">Profile Information</h4>
                <p class="text-body-secondary">Update your account's profile information and email address.</p>
                <div class="mb-3">
                  <label for="fullname" class="form-label">Fullname</label>
                  <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $this->session->userdata('fullname'); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo $this->session->userdata('email'); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2" id="updateProfileButton">
                  <span id="updateProfileButtonText">Update profile</span>
                  <span id="updateProfileButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
              </form>
            </div>
          </div>
          <div class="card rounded-4">
            <div class="card-body p-md-4 p-xl-5">
              <form method="post" action="<?php echo site_url('users/update_password'); ?>" id="passwordForm">
                <h4 class="fs-5 text-body">Update Password</h4>
                <p class="text-body-secondary">Ensure your account is using a long, random password to stay secure</p>
                <div class="mb-3">
                  <label for="current_password" class="form-label">Current Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="new_password" class="form-label">New Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <i class="bi bi-eye position-absolute translate-middle-y top-50 end-0 me-3 toggle-password" style="cursor: pointer;"></i>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2" id="updatePasswordButton">
                  <span id="updatePasswordButtonText">Update password</span>
                  <span id="updatePasswordButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
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
      });
    </script>
  </body>
</html>