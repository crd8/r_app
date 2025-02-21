<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-icons.min.css'); ?>" rel="stylesheet">
  </head>
  <body class="bg-body-tertiary">
    <div class="d-flex justify-content-end py-3">
      <i id="darkModeToggle" class="bi bi-moon-stars-fill ms-auto me-4" style="cursor: pointer;"></i>
    </div>
    <section class="py-3 py-md-5">
      <div class="container">
        <?php if ($this->session->flashdata('error')): ?>
          <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex">
                <div class="toast-body">
                  <?php echo $this->session->flashdata('error'); ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="d-flex justify-content-center">
          <div class="card text-center col-10 col-sm-10 col-lg-4 border-0 bg-body shadow-sm mt-5">
            <div class="card-body p-4 p-md-4 p-xl-5 text-body-secondary">
              <h3 class="fw-bold fst-italic mt-3 text-body">RAPINDO</h3>
              <p class="text-body-secondary fw-normal mb-4">Sign in to your account</p>
              <form method="post" action="<?php echo site_url('users/authenticate'); ?>" id="loginForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="floatingInputUsername" name="username" placeholder="username" value="<?php echo $this->session->flashdata('username') ?: get_cookie('remember_username'); ?>">
                  <label for="floatingInputUsername">Username</label>
                </div>
                <div class="form-floating mb-3 position-relative">
                  <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                  <label for="floatingPassword">Password</label>
                  <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php echo get_cookie('remember_username') ? 'checked' : ''; ?>>
                    <label class="form-check-label text-body-secondary" for="remember">
                      Remember me
                    </label>
                  </div>
                  <a href="" class="link-primary text-decoration-none">Forgot password?</a>
                </div>
                <div class="d-grid gap-2 mb-3">
                  <button type="submit" class="btn btn-lg fw-semibold btn-primary" id="loginButton">
                    <span id="loginButtonText">Log in</span>
                    <span id="loginButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>      
      </div>
    </section>

    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var errorToast = document.getElementById('errorToast');
        if (errorToast) {
          var toast = new bootstrap.Toast(errorToast);
          toast.show();
        }

        var togglePassword = document.getElementById('togglePassword');
        var passwordInput = document.getElementById('floatingPassword');
        togglePassword.addEventListener('click', function () {
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.classList.remove('bi-eye');
            togglePassword.classList.add('bi-eye-slash');
          } else {
            passwordInput.type = 'password';
            togglePassword.classList.remove('bi-eye-slash');
            togglePassword.classList.add('bi-eye');
          }
        });

        var loginForm = document.getElementById('loginForm');
        var loginButton = document.getElementById('loginButton');
        var loginButtonText = document.getElementById('loginButtonText');
        var loginButtonSpinner = document.getElementById('loginButtonSpinner');

        loginForm.addEventListener('submit', function () {
          loginButton.disabled = true;
          loginButtonText.classList.add('d-none');
          loginButtonSpinner.classList.remove('d-none');
        });
      });
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var darkModeToggle = document.getElementById('darkModeToggle');
        var htmlElement = document.documentElement;

        // Muat preferensi mode dari localStorage
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