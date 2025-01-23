<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
  </head>
  <body>
    <section class="bg-light py-3 py-md-5">
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
          <div class="card text-center col-lg-4 mt-5 border border-light-subtle rounded-3 shadow-sm">
            <div class="card-body p-md-4 p-xl-5">
              <h3 class="fw-bold fst-italic mt-3">RAPINDO</h3>
              <p class="fs-6 text-secondary fw-normal mb-4">Sign in to your account</ class="text-secondary">
              <form method="post" action="<?php echo site_url('users/authenticate'); ?>">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="floatingInputUsername" name="username" placeholder="username" value="<?php echo $this->session->flashdata('username') ?: get_cookie('remember_username'); ?>">
                  <label for="floatingInputUsername">Username</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                  <label for="floatingPassword">Password</label>
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php echo get_cookie('remember_username') ? 'checked' : ''; ?>>
                    <label class="form-check-label text-secondary" for="remember">
                      Remember me
                    </label>
                  </div>
                  <a href="" class="link-primary text-decoration-none">Forgot password?</a>
                </div>
                <div class="d-grid gap-2 mb-3">
                  <button type="submit" class="btn btn-lg fw-semibold btn-primary">Log in</button>
                </div>
              </form>
            </div>
          </div>
        </div>      
      </div>
    </section>

    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- For Toast Message -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var errorToast = document.getElementById('errorToast');
        if (errorToast) {
          var toast = new bootstrap.Toast(errorToast);
          toast.show();
        }
      });
    </script>
  </body>
</html>