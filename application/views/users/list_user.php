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
  <body>
  <?php $this->load->view('partials/navbar.php'); ?>
    <div class="container pt-5 mt-4">
      <div class="d-flex justify-content-center">
        <div class="card w-100 rounded-4">
          <div class="card-body text-body-secondary p-md-4 p-xl-5">
            <h4 class="card-title"><i class="bi bi-people-fill text-primary"></i> List of users account</h4>
            <p class="card-text">List of active users account in system</p>
            <div class="table-responsive">
              <table class="table table-hover table-bordered align-middle" id="dataTablesUsers">
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
                      <small><a href="" class="fw-bold link-primary text-decoration-none me-2">EDIT</a></small>
                      <small><a href="" class="fw-bold link-primary text-decoration-none">DELETE</a></small>
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
    </script>
  </body>
</html>