<!doctype html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Permission</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
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
            <a href="<?php echo site_url('permissions/list'); ?>" class="btn btn-sm btn-secondary mb-3">
              <i class="bi bi-arrow-left"></i> Back
            </a>
            <form method="post" action="<?php echo site_url('permissions/store'); ?>">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <h5 class="card-title"><i class="bi bi-shield-shaded text-primary"></i></i> Create a new permission</h5>
              <h6 class="card-subtitle mb-2 text-body-secondary">Fill in the details below to create a new permission access.</h6>
              <div class="mb-3 mt-3">
                <label for="name" class="form-label">Permission Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description'); ?></textarea>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg mt-3">Create Permission</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
      var toastElements = document.querySelectorAll('.toast');
      toastElements.forEach(function (toastElement) {
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
      });
    </script>
  </body>
</html>