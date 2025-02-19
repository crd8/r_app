<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body class="bg-body-tertiary">
    <div class="container pt-5 mt-4">
      <div class="d-flex justify-content-center">
        <div class="card col-10 col-sm-6 col-lg-4 border-0 bg-body shadow-sm mt-5">
          <div class="card-body text-body-secondary text-center p-md-4 p-xl-5">
            <h4 class="card-title"><i class="bi bi-exclamation-triangle-fill text-warning"></i> 404 Not Found</h4>
            <p class="card-text">The page you are looking for could not be found.</p>
            <p class="card-text">Please check the URL or return to the homepage.</p>
            <a href="javascript:history.back()" class="btn btn-primary"><i class="bi bi-arrow-left-circle-fill"></i> Go Back</a>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var darkModeToggle = document.getElementById('darkModeToggle');
        var htmlElement = document.documentElement;

        // light or dark mode
        if (localStorage.getItem('theme') === 'dark') {
          htmlElement.setAttribute('data-bs-theme', 'dark');
          darkModeToggle.classList.remove('bi-moon-stars-fill');
          darkModeToggle.classList.add('bi-sun-fill');
        } else {
          htmlElement.setAttribute('data-bs-theme', 'light');
          darkModeToggle.classList.remove('bi-sun-fill');
          darkModeToggle.classList.add('bi-moon-stars-fill');
        }

        darkModeToggle.addEventListener('click', function () {
          if (htmlElement.getAttribute('data-bs-theme') === 'dark') {
            htmlElement.setAttribute('data-bs-theme', 'light');
            darkModeToggle.classList.remove('bi-sun-fill');
            darkModeToggle.classList.add('bi-moon-stars-fill');
            localStorage.setItem('theme', 'light');
          } else {
            htmlElement.setAttribute('data-bs-theme', 'dark');
            darkModeToggle.classList.remove('bi-moon-stars-fill');
            darkModeToggle.classList.add('bi-sun-fill');
            localStorage.setItem('theme', 'dark');
          }
        });
      });
    </script>
  </body>
</html>