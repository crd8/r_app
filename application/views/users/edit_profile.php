<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
  </head>
  <body>
    <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container pt-5 mt-4">
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
        <div class="card col-lg-6 border border-light-subtle rounded-3 mb-3">
          <div class="card-body p-md-4 p-xl-5">
            <form method="post" action="<?php echo site_url('users/update_profile'); ?>">
              <h4 class="fs-4">Profile Information</h4>
              <p class="text-secondary">Update your account's profile information and email address.</p>
              <div class="mb-3">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $this->session->userdata('fullname'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $this->session->userdata('email'); ?>" required>
              </div>
              <button type="submit" class="btn btn-primary mt-2">Update profile</button>
            </form>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <div class="card col-lg-6 border border-light-subtle rounded-3">
          <div class="card-body p-md-4 p-xl-5">
            <form method="post" action="<?php echo site_url('users/update_password'); ?>">
              <h4 class="fs-4">Update Password</h4>
              <p class="text-secondary">Ensure your account is using a long, random password to stay secure</p>
              <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
              </div>
              <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" minlength="6">
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
              </div>
              <button type="submit" class="btn btn-primary mt-2">Update password</button>
            </form>
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
      });
    </script>
  </body>
</html>