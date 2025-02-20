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
            <form method="post" action="<?php echo site_url('users/update/' . $user->id); ?>">
              <h5 class="card-title"><i class="bi bi-person-fill text-primary"></i> Edit User</h5>
              <h6 class="card-subtitle mb-2 text-body-secondary">Update the details below to edit the user account</h6>
              
              <div class="mb-3 mt-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', $user->username); ?>" required>
              </div>
              <div class="mb-3">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo set_value('fullname', $user->fullname); ?>" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email', $user->email); ?>" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password (leave blank if not changing)</label>
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
              </div>
              
              <!-- Bagian untuk mengatur permission -->
              <div class="mb-3">
                <label class="form-label">Permissions</label>
                <div class="border p-2 rounded">
                  <?php foreach ($all_permissions as $permission): ?>
                    <div class="form-check">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="permission-<?php echo $permission->id; ?>" 
                        name="permissions[]" 
                        value="<?php echo $permission->id; ?>"
                        <?php echo (in_array($permission->id, $user_permissions)) ? 'checked' : ''; ?>
                      >
                      <label class="form-check-label" for="permission-<?php echo $permission->id; ?>">
                        <?php echo $permission->name; ?>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <!-- End Bagian Permissions -->
              
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg mt-3">Update account</button>
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
    </script>
  </body>
</html>
