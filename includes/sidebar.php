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
    <title>Farm App</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" crossorigin="anonymous"></script>
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
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
</head>

<body class="g-sidenav-show  bg-gray-100">


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
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="home" width="12px" height="12px"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/payment.php" ? "active" : ""); ?>" href="payment">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="credit-card" width="12px" height="12px"></i>
                        </div>
                        <span class="nav-link-text ms-1">Payments</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/receipt.php" ? "active" : ""); ?>" href="receipt">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="file-text" width="12px" height="12px"></i>
                        </div>
                        <span class="nav-link-text ms-1">Receipts</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/cashbook.php" ? "active" : ""); ?>" href="cashbook">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="book" width="12px" height="12px"></i>
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
                            <i data-feather="truck" width="12px" height="12px"></i>
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
                            <i data-feather="bar-chart-2" width="12px" height="12px"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reporting</span>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/categoryreport.php" ? "active" : ""); ?>" href="categoryreport">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="pie-chart" width="12px" height="12px"></i>
                        </div>
                        <span class="nav-link-text ms-1">Category Report</span>
                    </a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/settings.php" ? "active" : ""); ?>" href="settings">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i data-feather="settings" width="12px" height="12px"></i>
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
    </aside>
