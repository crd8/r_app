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
      <div class="d-flex justify-content-center">
        <div class="card bg-body col-12 border-0 shadow-sm mt-5">
          <div class="card-body text-body p-md-4 p-xl-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h5 class="card-title"><i class="bi bi-people-fill text-primary"></i> List of users account</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">List of active users account in system</h6>
              </div>
              <a href="<?php echo site_url('users/create'); ?>" class="btn btn-secondary"><i class="bi bi-person-add"></i> Create User</a>
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
                      <div><?php echo $user->fullname; ?></div>
                      <div class="text-body-secondary" style="font-size: smaller;"><?php echo $user->email; ?></div>
                    </td>
                    <td><?php echo $user->username; ?></td>
                    <td><?php echo date('d F Y, H:i:s', strtotime($user->created_at)); ?></td>
                    <td><?php echo date('d F Y, H:i:s', strtotime($user->updated_at)); ?></td>
                    <td>
                      <a href="" class="link-primary text-decoration-none me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                        <i class="bi bi-pencil-square text-warning-emphasis"></i>
                      </a>
                      <a href="" class="link-primary text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                        <i class="bi bi-trash text-danger-emphasis"></i>
                      </a>
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
    </script>
  </body>
</html>