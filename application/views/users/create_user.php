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
        <div class="card col-md-6 col-lg-5 border-0 bg-body shadow-sm mt-5">
          <div class="card-body p-md-4 p-xl-5">
            <a href="<?php echo site_url('users/list'); ?>" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back</a>
            <form method="post" action="<?php echo site_url('users/store'); ?>">
              <h5 class="card-title"><i class="bi bi-person-fill-add text-primary"></i> Create a New User</h5>
              <h6 class="card-subtitle mb-2 text-body-secondary">Fill in the details below to create a new user account</h6>
              <div class="mb-3 mt-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo set_value('fullname'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="position-relative">
                  <input type="password" class="form-control" id="password" name="password" minlength="6" required>
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
      var toastElements = document.querySelectorAll('.toast');
      toastElements.forEach(function (toastElement) {
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
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
    </script>
  </body>
</html>