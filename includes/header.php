<?php
// Determine the current page and map to sidebar titles
$current_page = basename($_SERVER['PHP_SELF']);
$page_titles = [
    'index.php' => 'Dashboard',
    'payment.php' => 'Payments',
    'receipt.php' => 'Receipts',
    'cashbook.php' => 'Cash Book',
    'orders.php' => 'Deliveries/Supplies',
    'trialbalance.php' => 'Trial Balance',
    'reportings.php' => 'Reporting',
    'settings.php' => 'Settings',
    'logout.php' => 'Logout'
];

// Set the title to the corresponding page title, default to 'Dashboard' if not found
$header_title = isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Dashboard';
?>

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 mt-3" id="navbarBlur">
    <div class="container-fluid py-1 px-2">
        <!-- Dynamic Brand/Title -->
        <a class="navbar-brand" href="/"><?php echo htmlspecialchars($header_title); ?></a>

        <!-- Mobile Menu Toggler -->
        <a href="javascript:;" class="nav-link text-body p-0 d-xl-none" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
                <i data-feather="menu" class="feather-icon"></i>
            </div>
        </a>

        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <!-- Search Bar -->
            <div class="ms-md-auto pe-md-2 d-flex align-items-center">
                <div class="input-group search-bar">
                    <span class="input-group-text"><i data-feather="search" class="feather-icon"></i></span>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </div>
            <!-- Navbar Items -->
            <ul class="navbar-nav justify-content-end align-items-center">
                <!-- Welcome User -->
                <li class="nav-item d-flex align-items-center">
                    <div class="user-profile">
                        <span class="welcome-text">
                            <?php
                                echo isset($_SESSION['username']) ? 'Welcome ' . htmlspecialchars(ucfirst($_SESSION['username'])) : 'Welcome Guest';
                            ?>
                        </span>
                    </div>
                </li>
                <!-- Log Out Link -->
                <li class="nav-item d-flex align-items-center">
                    <a href="/logout" class="nav-link logout-btn">
                        <i data-feather="log-out" class="feather-icon"></i>
                        <span class="d-sm-inline d-none text-dark">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Navbar Styling */
    .navbar-main {
        background: linear-gradient(90deg, #2d6a4f 0%, #40916c 100%);
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 0.5rem 0.75rem;
        height: 52px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        margin-bottom: 1rem; /* Default margin */
    }

    .navbar-main .navbar-brand {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.2s ease;
        flex-grow: 1;
        text-align: left;
    }

    .navbar-main .navbar-brand:hover {
        color: #e1e8d8;
    }

    .navbar-main .nav-link {
        color: #ffffff !important;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .navbar-main .nav-link:hover {
        color: #e1e8d8 !important;
        transform: translateY(-1px);
    }

    /* Search Bar Styling */
    .search-bar .input-group {
        max-width: 200px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .search-bar .input-group:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: scale(1.02);
    }

    .search-bar .input-group-text {
        background: transparent;
        border: none;
        color: #ffffff;
        padding: 0.4rem 0.6rem;
    }

    .search-bar .form-control {
        background: transparent;
        border: none;
        color: #ffffff;
        box-shadow: none;
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }

    .search-bar .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
        font-style: italic;
    }

    .search-bar .form-control:focus {
        background: transparent;
        color: #ffffff;
    }

    /* Feather Icons */
    .feather-icon {
        width: 18px !important;
        height: 18px !important;
        color: #ffffff;
    }

    .feather-icon:hover {
        color: #e1e8d8;
    }

    /* User Profile Styling */
    .user-profile {
        display: flex;
        align-items: center;
        padding: 6px 10px;
        margin-right: 0.8rem;
        transition: all 0.3s ease;
    }

    .welcome-text {
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Logout Button */
    .logout-btn {
        background: linear-gradient(90deg, #d4e4c3, #e1e8d8);
        padding: 6px 10px;
        border-radius: 20px;
        color: #2d6a4f !important;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .logout-btn:hover {
        background: linear-gradient(90deg, #c3d4a3, #d1e8c8);
        color: #1f2937 !important;
        transform: translateY(-1px);
    }

    /* Mobile Toggler */
    .sidenav-toggler-inner {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .sidenav-toggler-inner .feather-icon {
        width: 24px !important;
        height: 24px !important;
    }


    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .navbar-main {
            height: 44px;
            padding: 0.4rem 0.5rem;
            margin: 0.5rem 0.5rem 2rem 0.5rem; /* Increased bottom margin */
            border-radius: 8px;
        }

        .navbar-main .navbar-brand {
            font-size: 1.1rem;
            letter-spacing: 0.3px;
            line-height: 1.2;
            max-width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .search-bar .input-group {
            max-width: 100px;
        }

        .search-bar .input-group-text {
            padding: 0.3rem 0.5rem;
        }

        .search-bar .form-control {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
        }

        .user-profile {
            padding: 4px 6px;
            margin-right: 0.5rem;
            max-width: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .welcome-text {
            font-size: 0.75rem;
        }

        .logout-btn {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .feather-icon {
            width: 16px !important;
            height: 16px !important;
        }

        .navbar-collapse {
            background: transparent; /* Ensure collapse doesn't affect sidebar */
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .navbar-nav {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-item {
            width: 100%;
            margin: 0.2rem 0;
        }

        .sidenav-toggler-inner {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
        }

        .sidenav-toggler-inner .feather-icon {
            width: 20px !important;
            height: 20px !important;
        }
    }

    @media (max-width: 576px) {
        .search-bar .input-group {
            max-width: 80px;
        }

        .navbar-main .navbar-brand {
            font-size: 1rem;
            max-width: 45%;
        }

        .user-profile {
            max-width: 60px;
        }

        .sidenav-toggler-inner {
            width: 24px;
            height: 24px;
        }

        .sidenav-toggler-inner .feather-icon {
            width: 18px !important;
            height: 18px !important;
        }
    }

    /* Ensure sidebar retains original background */
    .sidenav {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid #d4e4c3 !important;
    }

    .logout-btn .feather-icon {
        stroke: #1f2937 !important; 
        color: #1f2937 !important; 
    }
</style>

<script>
    // Initialize Feather icons
    feather.replace();
</script>