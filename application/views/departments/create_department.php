<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Department</title>
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
            <a href="<?php echo site_url('departments/list'); ?>" class="btn btn-sm btn-secondary fw-semibold mb-3">
              <i class="bi bi-arrow-left"></i> Back
            </a>
            <form method="post" action="<?php echo site_url('departments/store'); ?>" id="createDepartmentForm">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <h5 class="card-title"><i class="bi bi-people-fill"></i> Create a New Department</h5>
              <h6 class="card-subtitle mb-4 text-body-secondary">Fill in the details below to create a new department</h6>
              <hr>
              <div class="mb-3">
                <label for="name" class="form-label">Department name</label>
                <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo html_escape(set_value('name')); ?>" required>
                <?php echo form_error('name','<div class="invalid-feedback">','</div>'); ?>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo set_value('description'); ?></textarea>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold mt-3" id="createDepartmentButton">
                  <span id="createDepartmentButtonText">Create</span>
                  <output id="createDepartmentButtonSpinner" class="spinner-border spinner-border-sm d-none" aria-live="polite" aria-hidden="true"></output>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.toast').forEach(function(toastEl){
        new bootstrap.Toast(toastEl).show();
        });

        var createDepartmentForm = document.getElementById('createDepartmentForm');
        var createDepartmentButton = document.getElementById('createDepartmentButton');
        var createDepartmentButtonText = document.getElementById('createDepartmentButtonText');
        var createDepartmentButtonSpinner = document.getElementById('createDepartmentButtonSpinner');
        createDepartmentForm.addEventListener('submit', function () {
          createDepartmentButton.disabled = true;
          createDepartmentButtonText.classList.add('d-none');
          createDepartmentButtonSpinner.classList.remove('d-none');
        });
      });
    </script>
  </body>
</html>
