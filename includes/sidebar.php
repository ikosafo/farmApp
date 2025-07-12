<?php
session_start();

if (!isset($_SESSION['uId']) || empty($_SESSION['uId'])) {
    header("Location: /login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>FarmApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" crossorigin="anonymous"></script>
    <link href="assets/css/nucleo-svg" rel="stylesheet" />
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
    <script src="includes/scripts.js"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #f0f4f3 0%, #e1e8d8 100%);
            font-family: 'Poppins', sans-serif;
        }

        .sidenav {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #d4e4c3;
        }

        .navbar-brand {
            color: #2d6a4f;
        }

        .horizontal.dark {
            border-top: 1px solid #d4e4c3;
        }

        .nav-link {
            color: #1f2937;
        }

        .nav-link.active {
            background: linear-gradient(to right, #2d6a4f, #40916c);
            color: white !important;
        }

        .icon {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #d4e4c3;
            width: 36px !important;
            /* Slightly reduced to balance with larger icons */
            height: 36px !important;
            /* Slightly reduced to balance with larger icons */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-item h6 {
            color: #4a7043;
        }

        .btn.bg-gradient-primary {
            background: linear-gradient(to right, #2d6a4f, #40916c);
            color: white;
        }

        .text-secondary {
            color: #4a7043 !important;
        }

        .navbar-vertical .navbar-nav>.nav-item .nav-link.active .icon {
            background-image: linear-gradient(310deg, #ffffff, #ffffff);
        }

        /* Increase Feather icon size significantly */
        .feather {
            width: 24px !important;
            /* Larger icon size */
            height: 24px !important;
            /* Larger icon size */
        }

        /* Modal styling */
        .maintenance-modal .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to bottom, #f0f4f3 0%, #e1e8d8 100%);
        }

        .maintenance-modal .modal-header {
            border-bottom: none;
            padding-bottom: 0;
            justify-content: center;
            /* Center the title */
        }

        .maintenance-modal .modal-title {
            color: #2d6a4f;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .maintenance-modal .modal-body {
            text-align: center;
            padding-top: 1rem;
            padding-bottom: 2rem;
            color: #1f2937;
            font-size: 1.1rem;
        }

        .maintenance-modal .modal-footer {
            border-top: none;
            justify-content: center;
            padding-top: 0;
        }

        .maintenance-modal .btn-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #888;
            opacity: 1;
            padding: 0;
            margin: 0;
        }

        .maintenance-modal .btn-close:hover {
            color: #555;
        }

        .modal-backdrop.show {
            opacity: 0.6;
            /* Make the backdrop a bit darker */
        }

        /* Ensure modal appears on top */
        .maintenance-modal {
            z-index: 1050 !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        /* FIX: Main content overlap */
        /* This rule pushes the main content area to make space for the fixed sidebar */
        .main-content {
            margin-left: 17rem; /* Default sidebar width for soft-ui-dashboard */
            transition: margin-left .2s ease-in-out;
        }

        /* Responsive adjustment for when the sidebar might collapse or become an overlay */
        @media (max-width: 1200px) { /* This breakpoint corresponds to where the Soft UI dashboard sidebar often changes behavior (e.g., becomes collapsible or overlays) */
            .main-content {
                margin-left: 0 !important; /* On smaller screens, the sidebar often overlays, so main content doesn't need a static margin */
            }
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="/" target="_blank">
                <img src="assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">AgroTrack</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto max-height-vh-100 h-100" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/index.php" ? "active" : ""); ?>" href="/">
                        <div class="icon icon-shape icon-sm shadow border utc-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="home"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/payment.php" ? "active" : ""); ?>" href="payment">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="credit-card"></i>
                        </div>
                        <span class="nav-link-text ms-1">Payments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/receipt.php" ? "active" : ""); ?>" href="receipt">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="file-text"></i>
                        </div>
                        <span class="nav-link-text ms-1">Receipts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/cashbook.php" ? "active" : ""); ?>" href="cashbook">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="book"></i>
                        </div>
                        <span class="nav-link-text ms-1">Cash Book</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Daily Entries</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/orders.php" ? "active" : ""); ?>" href="orders">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="truck"></i>
                        </div>
                        <span class="nav-link-text ms-1">Deliveries / Supplies</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reportings</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/reportings.php" ? "active" : ""); ?>" href="reportings">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="bar-chart-2"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reporting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/settings.php" ? "active" : ""); ?>" href="settings">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="settings"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout">
                        <span class="btn bg-gradient-primary mt-4">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <script>
            // Initialize Feather icons
            feather.replace();

            // JavaScript for the maintenance modal
            document.addEventListener('DOMContentLoaded', function() {
                const navLinks = document.querySelectorAll('.nav-link');
                const dashboardLinkHref = '/'; // Assuming your dashboard is at the root '/'

                navLinks.forEach(link => {
                    // Check if the link's href is not exactly the dashboard link
                    // Also ensure the link is not the logout link, as it has a specific action
                    if (link.getAttribute('href') !== dashboardLinkHref && link.getAttribute('href') !== 'logout') {
                        link.addEventListener('click', function(event) {
                            event.preventDefault(); // Stop the navigation
                            $('#maintenanceModal').modal('show'); // Show the modal using jQuery
                        });
                    }
                });
            });
        </script>
    </aside>

    <div class="modal fade maintenance-modal" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="maintenanceModalLabel">
                        <i data-feather="tool" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 8px;"></i>
                        Maintenance in Progress
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>We're currently performing some important updates and maintenance.</p>
                    <p>Some features are temporarily unavailable to ensure a smooth and improved experience.</p>
                    <p>Thank you for your patience!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-primary" data-bs-dismiss="modal">Got It!</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
