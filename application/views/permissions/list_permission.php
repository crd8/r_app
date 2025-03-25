<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List of Permissions</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet">
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
      <div class="d-flex justify-content-center">
        <div class="card col-md-12 col-lg-9 border-0 bg-body shadow-sm mt-5">
          <div class="card-body text-body p-md-4 p-xl-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h5 class="card-title"><i class="bi bi-shield-shaded text-primary"></i> List of permissions</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">List of active permissions in system</h6>
              </div>
              <?php
              $session_permissions = $this->session->userdata('permissions');
              $permission_create_permission_id = $this->Permission_model->get_permission_id('permission create');
              if (in_array($permission_create_permission_id, $session_permissions)):
              ?>
              <a href="<?php echo site_url('permissions/create'); ?>" class="btn btn-primary"><i class="bi bi-shield-plus"></i> Create Permission</a>
              <?php endif; ?>
            </div>
            <hr>
            <div class="table-responsive bg-body-tertiary p-2 rounded-2">
              <table class="table table-hover align-middle" id="dataTablesPermissions">
                <thead>
                  <tr>
                    <th class="text-uppercase" scope="col">Permission Name</th>
                    <th class="text-uppercase" scope="col">Created At</th>
                    <th class="text-uppercase" scope="col">Updated At</th>
                    <th class="text-uppercase">Option</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($permissions as $permission): ?>
                  <tr>
                    <td>
                      <div><?php echo html_escape($permission->name); ?></div>
                      <div class="text-body-secondary"><small><?php echo html_escape($permission->description); ?></small></div>
                    </td>
                    <td><?php echo html_escape(date('d F Y, H:i:s', strtotime($permission->created_at))); ?></td>
                    <td><?php echo html_escape(date('d F Y, H:i:s', strtotime($permission->updated_at))); ?></td>
                    <td>
                      <?php
                      $session_permissions = $this->session->userdata('permissions');
                      $permission_edit_permission_id = $this->Permission_model->get_permission_id('permission edit');
                      if (in_array($permission_edit_permission_id, $session_permissions)):
                      ?>
                      <a href="<?php echo site_url('permissions/edit/' . $permission->id); ?>" class="link-primary text-decoration-none me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                        <i class="bi bi-pencil-square text-warning-emphasis"></i>
                      </a>
                      <?php endif; ?>
                      <?php
                        $session_permissions = $this->session->userdata('permissions');
                        $permission_delete_permission_id = $this->Permission_model->get_permission_id('permission delete');
                        if (in_array($permission_delete_permission_id, $session_permissions)):
                      ?>
                      <a href="#" class="text-danger-emphasis text-decoration-none" data-bs-toggle="modal" data-bs-target="#deleteModal" data-permissionid="<?php echo $permission->id; ?>" data-name="<?php echo $permission->name; ?>">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                          <i class="bi bi-trash"></i>
                        </span>
                      </a>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Delete Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-center">Are you sure you want to delete the permission with name <strong><span id="nameToDelete"></span></strong>?</p>
            <p class="text-center text-danger">This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="deletePermissionForm" action="" method="post" style="display: inline;">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal -->
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-3.7.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap5.min.js'); ?>"></script>
    <script>
      $(document).ready(function () {
        var table = $('#dataTablesPermissions').DataTable({
          order: []
        });

        function initTooltips() {
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
          tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
          });
        }
        
        initTooltips();

        table.on('draw.dt', function () {
          initTooltips();
        });
        
        document.querySelectorAll('.toast').forEach(function (toastElement) {
          var toast = new bootstrap.Toast(toastElement);
          toast.show();
        });

        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget;
          var permissionId = button.getAttribute('data-permissionid');
          var name = button.getAttribute('data-name');
          document.getElementById('nameToDelete').textContent = name;
          document.getElementById('deletePermissionForm').setAttribute('action', '<?php echo site_url("permissions/delete/"); ?>' + permissionId);
        });
      });
    </script>
  </body>
</html>