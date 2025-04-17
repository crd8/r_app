<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Permission</title>
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
        <div class="card col-md-6 col-lg-5 border-0 bg-body shadow-sm mt-5">
          <div class="card-body p-md-4 p-xl-5">
            <a href="<?php echo site_url('permissions/list'); ?>" class="btn btn-sm btn-secondary mb-3 fw-semibold">
              <i class="bi bi-arrow-left"></i> Back
            </a>
            <form method="post" action="<?php echo site_url('permissions/update/' . $permission->id); ?>" id="editPermsissionForm">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <h5 class="card-title">
                <i class="bi bi-person-fill text-primary"></i> Edit Permission
              </h5>
              <h6 class="card-subtitle mb-4 text-body-secondary">
                Update the details below to edit the permission
              </h6>
              <div class="mb-3">
                <label for="name" class="form-label">Permission Name</label>
                <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo set_value('name', $permission->name); ?>" required>
                <?php echo form_error('name','<div class="invalid-feedback">','</div>'); ?>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control <?php echo form_error('description') ? 'is-invalid' : ''; ?>" id="description" name="description" rows="3"><?php echo set_value('description', $permission->description); ?></textarea>
                <?php echo form_error('description','<div class="invalid-feedback">','</div>'); ?>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold mt-3" id="updateButton">
                  <span id="UpdatePermissionButtonText">Update</span>
                  <span id="updatePermissionButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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

      var editPermsissionForm = document.getElementById('editPermsissionForm');
      var updateButton = document.getElementById('updateButton');
      var UpdatePermissionButtonText = document.getElementById('UpdatePermissionButtonText');
      var updatePermissionButtonSpinner = document.getElementById('updatePermissionButtonSpinner');
      editPermsissionForm.addEventListener('submit', function () {
        updateButton.disabled = true;
        UpdatePermissionButtonText.classList.add('d-none');
        updatePermissionButtonSpinner.classList.remove('d-none');
      });
    </script>
  </body>
</html>
