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

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 mt-3" id="navbarBlur">
    <div class="container-fluid py-1 px-3">
        <!-- Dynamic Brand/Title -->
        <a class="navbar-brand" href="#"><?php echo htmlspecialchars($header_title); ?></a>

        <!-- Mobile Menu Toggler (Moved Here) -->
        <a href="javascript:;" class="nav-link text-body p-0 d-xl-none mr-4" id="iconNavbarSidenav" 
        style="margin-left: auto;">
            <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
            </div>
        </a>

        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <!-- Search Bar -->
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group search-bar">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
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
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="d-sm-inline d-none">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Navbar Styling */
    .navbar-main {
        background: linear-gradient(90deg, #1a2a44 0%, #2c3e50 100%);
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 0.75rem 1rem;
        height: 60px; /* Reduced height for a sleeker look */
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .navbar-main .navbar-brand {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .navbar-main .nav-link {
        color: #ffffff !important;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .navbar-main .nav-link:hover {
        color: #a3bffa !important;
        transform: translateY(-1px);
    }

    /* Search Bar Styling */
    .search-bar .input-group {
        max-width: 220px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .search-bar .input-group:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .search-bar .input-group-text {
        background: transparent;
        border: none;
        color: #ffffff;
        padding: 0.5rem 0.75rem;
    }

    .search-bar .form-control {
        background: transparent;
        border: none;
        color: #ffffff;
        box-shadow: none;
        padding: 0.5rem;
        font-size: 0.9rem;
    }

    .search-bar .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
        font-style: italic;
    }

    .search-bar .form-control:focus {
        background: transparent;
        color: #ffffff;
    }

    /* User Profile Styling */
    .user-profile {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 6px 12px;
        margin-right: 1rem;
        transition: all 0.3s ease;
    }

    .user-profile:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.02);
    }

    .welcome-text {
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Logout Button */
    .logout-btn {
        background: #e74c3c;
        padding: 6px 12px;
        border-radius: 20px;
        color: #ffffff !important;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: #c0392b;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .logout-btn i {
        font-size: 1.1rem;
    }

    /* Mobile Toggler */
    .sidenav-toggler-inner {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 8px;
        margin: 0 10px 10px 0;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
    }

    .sidenav-toggler-line {
        width: 24px;
        height: 3px;
        background: #ffffff;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .navbar-main {
            height: 50px;
            padding: 0.5rem;
        }

        .navbar-main .navbar-brand {
            font-size: 1.2rem;
        }

        .search-bar .input-group {
            max-width: 180px;
        }

        .user-profile {
            padding: 4px 8px;
            margin-right: 0.5rem;
        }

        .welcome-text {
            font-size: 0.85rem;
        }

        .logout-btn {
            padding: 4px 8px;
            font-size: 0.85rem;
        }
    }
</style>