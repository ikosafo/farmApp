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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  
  <!-- Existing CSS dependencies -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/dataTables.dataTables.min.css" />
  <link rel="stylesheet" href="assets/css/template.css" />
  <link rel="stylesheet" href="assets/css/jquery-confirm.min.css" />
  <link rel="stylesheet" href="assets/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/print.min.css" />
  <link rel="stylesheet" href="assets/css/flatpickr.min.css" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #f0f4f3 0%, #e1e8d8 100%);
      height: 100vh;
      margin: 0;
      overflow: hidden;
    }

    .login-container {
      background-image: url('assets/img/login.jpg');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 1.5rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      max-width: 400px;
      width: 100%;
      border: 1px solid #d4e4c3;
    }

    .login-card h3 {
      color: #2d6a4f;
      font-weight: 600;
      text-align: center;
    }

    .login-card p {
      color: #4a7043;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-control {
      border: 1px solid #a3bffa;
      border-radius: 0.5rem;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #2d6a4f;
      box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.2);
      outline: none;
    }

    .btn-farm {
      background: linear-gradient(to right, #2d6a4f, #40916c);
      color: white;
      font-weight: 600;
      border-radius: 0.5rem;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }

    .btn-farm:hover {
      background: linear-gradient(to right, #1e453e, #2d6a4f);
      transform: translateY(-2px);
    }

    .form-check-input:checked {
      background-color: #2d6a4f;
      border-color: #2d6a4f;
    }

    .forgot-link {
      color: #40916c;
      font-weight: 500;
    }

    .forgot-link:hover {
      color: #2d6a4f;
      text-decoration: underline;
    }

    .footer {
      background: transparent;
      color: #4a7043;
      position: absolute;
      bottom: 0;
      width: 100%;
      text-align: center;
      padding: 1rem;
    }

    /* Toast Notification Styles */
    .toast-container {
      position: fixed;
      top: 1rem;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10000 !important;
      max-width: 400px;
      width: 90%;
      display: block !important;
    }

    .toast {
      background: rgba(255, 255, 255, 0.95) !important;
      border-radius: 0.5rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      padding: 1rem;
      margin-bottom: 0.5rem;
      display: flex !important;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .toast:hover {
      transform: scale(1.02);
    }

    .toast.success {
      border-left: 4px solid #2d6a4f;
    }

    .toast.error {
      border-left: 4px solid #dc2626;
    }

    .toast .toast-icon {
      margin-right: 0.75rem;
      font-size: 1.25rem;
      color: #2d6a4f; /* Success icon color */
      width: 1.25rem;
      height: 1.25rem;
    }

    .toast.error .toast-icon {
      color: #dc2626; /* Error icon color */
    }

    .toast .toast-message {
      color: #1f2937;
      font-size: 0.875rem;
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .login-card {
        margin: 1rem;
      }
      .toast-container {
        top: 0.5rem;
        width: calc(100% - 1rem);
      }
    }
  </style>
</head>

<body>
  <main class="login-container">
    <div class="login-card">
      <div class="mb-4">
        <h3>FarmApp</h3>
        <p>Sign in to manage your farm</p>
      </div>
      <form id="loginForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="mb-4">
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input type="text" name="username" id="username" class="form-control w-full mt-1" placeholder="Enter your username" required>
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" id="password" class="form-control w-full mt-1" placeholder="Enter your password" required>
        </div>
        <div class="text-center">
          <button type="button" id="loginBtn" class="btn-farm w-full">Sign In</button>
        </div>
      </form>
      <div class="text-center mt-4">
        <p class="text-sm text-gray-600">
          Forgot credentials?
          <a href="forgotPassword" class="forgot-link">Click here</a>
        </p>
      </div>
    </div>
  </main>

  <!-- Toast Notification Container -->
  <div class="toast-container" id="toastContainer"></div>

  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <!-- <p class="mb-0">
            &copy; <script>document.write(new Date().getFullYear())</script>
            Powered By FarmApp.
          </p> -->
        </div>
      </div>
    </div>
  </footer>

  <!-- JS Libraries -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/js/jquery-ui.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/print.min.js"></script>
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
  <script src="assets/js/jquery.blockUI.js"></script>
  <script src="includes/scripts.js"></script>
  <script src="ajaxscripts/scripts/login.js"></script>

</body>

</html>