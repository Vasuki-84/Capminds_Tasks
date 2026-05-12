<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Healthcare Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f8f9fc;
        }
        
        .wrapper {
            display: flex;
            flex: 1;
            min-height: 100vh;
            position: relative;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }
        
        /* Desktop view - sidebar always visible */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }
        
        /* Tablet and Mobile view - sidebar hidden by default */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
                z-index: 1050;
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
            
            /* Overlay when sidebar is open */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.6);
                z-index: 1040;
                display: none;
                cursor: pointer;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.3);
            font-weight: bold;
            border-left: 3px solid #ffd700;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 22px;
            font-size: 1.1rem;
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }
        
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Navbar Styles */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        /* Hamburger Menu Button */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #667eea;
            cursor: pointer;
            margin-right: 12px;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .hamburger-btn:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .hamburger-btn:active {
            transform: scale(0.95);
        }
        
        @media (max-width: 991px) {
            .hamburger-btn {
                display: block;
            }
        }
        
        /* Close button inside sidebar for mobile */
        .sidebar-close {
            display: none;
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .sidebar-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }
        
        @media (max-width: 991px) {
            .sidebar-close {
                display: block;
            }
        }
        
        /* Content Wrapper */
        .content-wrapper {
            flex: 1;
            padding: 20px;
        }
        
        /* Footer Styles */
        .footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 15px;
            text-align: center;
            margin-top: auto;
        }
        
        .footer p {
            margin: 0;
            color: #6c757d;
            font-size: 13px;
        }
        
        .footer p i {
            margin: 0 3px;
        }
        
        /* Card Styles */
        .card-stats {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.07);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            margin-bottom: 15px;
        }
        
        .card-stats:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        /* Responsive Grid */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 12px;
            }
            
            .card-stats {
                margin-bottom: 12px;
            }
            
            .card-stats .card-body {
                padding: 15px;
            }
            
            .card-stats h2 {
                font-size: 1.8rem;
            }
            
            .card-stats h6 {
                font-size: 0.85rem;
            }
            
            .fs-1 {
                font-size: 1.8rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1rem !important;
            }
            
            .navbar-brand i {
                font-size: 0.9rem;
            }
            
            .btn-light {
                padding: 5px 10px;
                font-size: 0.85rem;
            }
            
            .card-stats h2 {
                font-size: 1.5rem;
            }
            
            .card-stats .card-body {
                padding: 12px;
            }
            
            .content-wrapper {
                padding: 10px;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .table td, .table th {
                padding: 8px;
            }
            
            .alert {
                font-size: 0.85rem;
                padding: 10px;
            }
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Button Styles */
        .btn-custom {
            border-radius: 20px;
            padding: 6px 16px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        @media (max-width: 576px) {
            .btn-custom {
                padding: 5px 12px;
                font-size: 0.8rem;
            }
        }
        
        /* Table Responsive */
        .table-responsive {
            border-radius: 12px;
            overflow-x: auto;
        }
        
        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }
        
        @media (max-width: 576px) {
            .table thead th {
                font-size: 0.75rem;
            }
            
            .table td {
                font-size: 0.75rem;
            }
        }
        
        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        
        /* Badge Styles */
        .badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <div class="sidebar p-3" id="sidebar">
            <button class="sidebar-close" id="sidebarCloseBtn">
                <i class="bi bi-x-lg"></i>
            </button>
            <h4 class="text-white text-center mb-4" style="font-size: 1.3rem;">
                <i class="bi bi-hospital"></i> HealthCare
            </h4>
            <nav class="nav flex-column">
                <?php
                // Get current page filename
                $current_page = basename($_SERVER['PHP_SELF']);
                $current_dir = basename(dirname($_SERVER['PHP_SELF']));
                ?>
                
                <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                
                <a class="nav-link <?php echo ($current_dir == 'patients') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/patients/list.php">
                    <i class="bi bi-people"></i> Patients
                </a>
                
                <a class="nav-link <?php echo ($current_dir == 'visits' && $current_page != 'add.php') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/visits/list.php">
                    <i class="bi bi-calendar-check"></i> All Visits
                </a>
                
                <a class="nav-link <?php echo ($current_page == 'add.php' && $current_dir == 'visits') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/visits/add.php">
                    <i class="bi bi-plus-circle"></i> New Visit
                </a>
                
                <hr class="bg-light">
                
                <a class="nav-link <?php echo ($current_page == 'followups.php') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/reports/followups.php">
                    <i class="bi bi-bell"></i> Follow-ups
                </a>
                
                <a class="nav-link <?php echo ($current_page == 'monthly.php') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/reports/monthly.php">
                    <i class="bi bi-graph-up"></i> Monthly Report
                </a>
                
                <a class="nav-link <?php echo ($current_page == 'birthdays.php') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/reports/birthdays.php">
                    <i class="bi bi-gift"></i> Birthdays
                </a>
                
                <a class="nav-link <?php echo ($current_page == 'summary.php') ? 'active' : ''; ?>" 
                   href="/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/reports/summary.php">
                    <i class="bi bi-file-text"></i> Full Summary
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <nav class="navbar navbar-custom">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <button class="hamburger-btn" id="hamburgerBtn">
                            <i class="bi bi-list"></i>
                        </button>
                        <span class="navbar-brand mb-0 h2" style="font-size: 1.2rem;">
                            <i class="bi bi-clipboard-pulse"></i> Patient Visit & Follow-Up Manager
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size: 0.9rem;">
                                <i class="bi bi-person-circle"></i> Admin
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <div class="content-wrapper">