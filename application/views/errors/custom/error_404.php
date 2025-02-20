<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
    <style>
      html, body {
        height: 100%;
      }
      .error-container {
        min-height: 100vh;
      }
    </style>
    <script>
      (function() {
        var theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
      })();
    </script>
  </head>
  <body class="bg-body-tertiary">
    <div class="container error-container d-flex flex-column justify-content-center align-items-center">
      <div class="card text-center bg-body shadow-sm border-0">
        <div class="card-body p-md-4 p-xl-5">
          <div class="mb-4">
            <i class="bi bi-emoji-frown-fill text-warning" style="font-size: 4rem;"></i>
          </div>
          <h1 class="card-title">404 Not Found</h1>
          <p class="card-text lead">Sorry, the page you are looking for cannot be found.</p>
          <p class="card-text">
            This page may have been moved or deleted. Please verify the URL you entered or return to the homepage.
          </p>
          <a href="javascript:history.back()" class="btn btn-lg btn-primary mt-3">
            <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali
          </a>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
          darkModeToggle.addEventListener('click', function () {
            var currentTheme = document.documentElement.getAttribute('data-bs-theme');
            var newTheme = (currentTheme === 'dark') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            // Ubah ikon sesuai tema
            darkModeToggle.classList.toggle('bi-sun-fill');
            darkModeToggle.classList.toggle('bi-moon-stars-fill');
          });
        }
      });
    </script>
  </body>
</html>
