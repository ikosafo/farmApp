<?php
session_start(); 

if (empty($_SESSION['csrf_token'])) {
    // Generate a new CSRF token if not set
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>FarmApp - Login</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="assets/css/dataTables.dataTables.min.css" />
  <link rel="stylesheet" href="assets/css/template.css" />
  <link rel="stylesheet" href="assets/css/jquery-confirm.min.css" />
  <link rel="stylesheet" href="assets/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/print.min.css" />
  <link rel="stylesheet" href="assets/css/flatpickr.min.css" />
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/print.min.js"></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    .main-content,
    .page-header,
    .container,
    .row {
      height: 100vh;
      overflow: hidden;
    }
  </style>
</head>

<body>
  <main class="main-content mt-0">
    <section>
      <div class="page-header">
        <div class="container py-5">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-4">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient">Farm App</h3>
                  <p class="mb-0">Sign in with your username and password</p>
                </div>
                <div class="card-body">
                  <form id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <label>Username</label>
                    <div class="mb-3">
                      <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" checked>
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="button" id="loginBtn" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Forgot credentials?
                    <a href="forgotPassword" class="text-info text-gradient font-weight-bold">Click here</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                     style="background-image:url('assets/img/curved-images/curved6.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>document.write(new Date().getFullYear())</script>
            Powered By FarmApp.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- JS Libraries -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
  <script src="assets/js/jquery.blockUI.js"></script>
  <script src="assets/js/notify.js"></script>
  <script src="includes/scripts.js"></script>

  <script>
    $(document).ready(function () {
      // Prevent back navigation
      history.pushState(null, null, location.href);
      window.onpopstate = function () {
        history.go(1);
      };

      $('#loginBtn').click(function (e) {
        e.preventDefault();

        let username = $('#username').val().trim();
        let password = $('#password').val().trim();

        if (username === '') {
          $.notify("Username is required", { className: "error", position: "top center" });
          $('#username').focus();
          return;
        }

        if (password === '') {
          $.notify("Password is required", { className: "error", position: "top center" });
          $('#password').focus();
          return;
        }

        let formData = $('#loginForm').serialize();

        $.ajax({
          type: 'POST',
          url: 'ajaxscripts/queries/login.php',
          data: formData,
          beforeSend: function () {
            $.blockUI({ message: '<h4>Please wait...</h4>' });
          },
          success: function (response) {
            //alert(response.message);
            $.unblockUI();
            try {
             
              if (response.success) {
                $.notify("Login successful", { className: "success", position: "top center" });
                setTimeout(() => {
                  window.location.href = response.redirect || "index.php";
                }, 1000);
              } else {
                $.notify(response.message, { className: "error", position: "top center" });
              }
            } catch (err) {
              $.notify(response.message, { className: "error", position: "top center" });
              console.error("JSON Parse Error:", err, "Response:", response);
            }
          },
          error: function (xhr, status, error) {
            $.unblockUI();
            $.notify("Network error. Please try again.", { className: "error", position: "top center" });
            console.error("AJAX Error:", status, error);
          }
        });
      });
    });
  </script>
</body>

</html>