<?php
session_start();
include('config.php');

// Initialize error message
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate or verify CSRF token
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid CSRF token.';
    } else {
        $username = filter_var($_POST['username'] ?? '', FILTER_SANITIZE_STRING);
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['rememberMe']);

        // Validate inputs
        if (empty($username) || empty($password)) {
            $error = 'Both username and password are required.';
        } else {
            // Query user
            $query = "SELECT userId, username, userPassword FROM users WHERE username = ? AND userStatus = 1";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Verify password
            if ($user && password_verify($password, $user['userPassword'])) {
                // Set session
                $_SESSION['userId'] = $user['userId'];
                $_SESSION['username'] = $user['username'];

                // Handle "Remember Me" with a cookie (e.g., 30 days)
                if ($rememberMe) {
                    $token = bin2hex(random_bytes(16));
                    setcookie('remember_token', $token, time() + 30 * 24 * 3600, '/', '', true, true);
                    // Store token in database (assumes a user_tokens table)
                    $tokenQuery = "INSERT INTO user_tokens (userId, token) VALUES (?, ?)";
                    $tokenStmt = $mysqli->prepare($tokenQuery);
                    $tokenStmt->bind_param('is', $user['userId'], $token);
                    $tokenStmt->execute();
                }

                // Redirect to dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
            $stmt->close();
        }
    }
}

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    FarmApp - Login
  </title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
</head>

<body class="">
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
                  <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger text-white" role="alert">
                      <?php echo htmlspecialchars($error); ?>
                    </div>
                  <?php } ?>
                  <form role="form" method="POST" action="login.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <label>Username</label>
                    <div class="mb-3">
                      <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="username-addon" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" checked>
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Forgot credentials?
                    <a href="forgot-password.php" class="text-info text-gradient font-weight-bold">Click here</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- Footer -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> Powered By FarmApp.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>