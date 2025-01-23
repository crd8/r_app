<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
  </head>
  <body>
    <h1>Hello, world!</h1>
    <form method="post" action="<?php echo site_url('users/store'); ?>">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="fullname">Fullname:</label>
            <input type="text" id="fullname" name="fullname" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit">Create</button>
    </form>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
  </body>
</html>