<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List of Users Account</title>
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
        <div class="card bg-body col-12 border-0 shadow-sm mt-5">
          <div class="card-body text-body p-md-4 p-xl-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h5 class="card-title"><i class="bi bi-people-fill text-primary"></i> List of users account</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">List of active users account in system</h6>
              </div>
              <?php
              $session_permissions = $this->session->userdata('permissions');
              $user_create_permission_id = $this->Permission_model->get_permission_id('user create');
              if (in_array($user_create_permission_id, $session_permissions)):
              ?>
              <a href="<?php echo site_url('users/create'); ?>" class="btn btn-sm btn-primary"><i class="bi bi-person-add"></i> Create User</a>
              <?php endif; ?>
            </div>
            <hr>
            <div class="table-responsive bg-body-tertiary p-2 rounded-2">
              <table class="table table-hover align-middle" id="dataTablesUsers">
                <thead>
                  <tr>
                    <th class="text-uppercase" scope="col">Fullname</th>
                    <th class="text-uppercase" scope="col">Username</th>
                    <th class="text-uppercase" scope="col">Created At</th>
                    <th class="text-uppercase" scope="col">Updated At</th>
                    <th class="text-uppercase">Option</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $user): ?>
                  <tr>
                    <td>
                      <div><?php echo html_escape($user->fullname); ?></div>
                      <div class="text-body-secondary" style="font-size: smaller;"><?php echo html_escape($user->email); ?></div>
                    </td>
                    <td><?php echo $user->username; ?></td>
                    <td><?php echo html_escape(date('d F Y, H:i:s', strtotime($user->created_at))); ?></td>
                    <td><?php echo html_escape(date('d F Y, H:i:s', strtotime($user->updated_at))); ?></td>
                    <td>
                      <?php
                      $session_permissions = $this->session->userdata('permissions');
                      $user_edit_permission_id = $this->Permission_model->get_permission_id('user edit');
                      if (in_array($user_edit_permission_id, $session_permissions)):
                      ?>
                      <a href="<?php echo site_url('users/edit/' . $user->id); ?>" class="link-primary text-decoration-none me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                        <i class="bi bi-pencil-square text-warning-emphasis"></i>
                      </a>
                      <?php endif; ?>
                      <?php
                        $session_permissions = $this->session->userdata('permissions');
                        $user_delete_permission_id = $this->Permission_model->get_permission_id('user delete');
                        if (in_array($user_delete_permission_id, $session_permissions)):
                      ?>
                      <a href="#" class="text-danger-emphasis text-decoration-none" data-bs-toggle="modal" data-bs-target="#deleteModal" data-userid="<?php echo $user->id; ?>" data-username="<?php echo $user->username; ?>" title="Delete">
                        <i class="bi bi-trash text-danger-emphasis"></i>
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
      <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-center">Are you sure you want to delete the account with username <strong><span id="usernameToDelete"></span></strong>?</p>
            <p class="text-center text-danger">This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="deleteUserForm" action="" method="post" style="display: inline;">
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
      $(document).ready(function() {
        $('#dataTablesUsers').DataTable();
      });

      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

      var toastElements = document.querySelectorAll('.toast');
      toastElements.forEach(function (toastElement) {
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
      });
      document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        var deleteForm = document.getElementById('deleteUserForm');
        var usernameToDeleteSpan = document.getElementById('usernameToDelete');

        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget;
          var userId = button.getAttribute('data-userid');
          var username = button.getAttribute('data-username');
          usernameToDeleteSpan.textContent = username;
          deleteForm.setAttribute('action', '<?php echo site_url("users/delete/"); ?>' + userId);
        });
      });
    </script>
  </body>
</html>