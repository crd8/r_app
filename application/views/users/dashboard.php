<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <h1>Welcome, <?php echo $this->session->userdata('username'); ?>!</h1>
      <a href="<?php echo site_url('users/edit_profile'); ?>" class="btn btn-primary">Edit Profile</a>
      <a href="<?php echo site_url('users/logout'); ?>" class="btn btn-danger">Logout</a>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
  </body>
</html>