<!doctype html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Permission</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
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
        <div class="card col-md-5 rounded-4">
          <div class="card-body p-md-4 p-xl-5 text-body-secondary">
            <form method="post" action="<?php echo site_url('permissions/store'); ?>">
              <h4 class="card-title"><i class="bi bi-shield-lock-fill text-primary"></i></i> Create a permission</h4>
              <p class="card-text">Create a new permission access.</p>
              <div class="mb-3">
                <label for="name" class="form-label">Permission Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description'); ?></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Create Permission</button>
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