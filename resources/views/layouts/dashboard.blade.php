<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/app-2fd25941.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('build/assets/app-fff57d2d.js') }}" defer></script>

    
    <style>
        :root {
            --teal: #008080;
            --mint-green: #98FF98;
            --light-gray: #F5F5F5;
            --teal-dark: #006666;
            --mint-green-dark: #70DD70;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }
        
        body {
            overflow-x: hidden;
            background-color: var(--light-gray);
            min-height: 100vh;
            position: relative;
        }
        
        /* Sidebar Styles */
        #sidebar-wrapper {
            position: fixed;
            height: 100vh;
            width: var(--sidebar-width);
            left: 0;
            top: 0;
            background-color: var(--teal);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        #sidebar-wrapper::-webkit-scrollbar {
            width: 4px;
        }
        
        #sidebar-wrapper::-webkit-scrollbar-track {
            background: var(--teal-dark);
        }
        
        #sidebar-wrapper::-webkit-scrollbar-thumb {
            background: var(--mint-green);
        }
        
        #sidebar-wrapper.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        #sidebar-wrapper .sidebar-brand {
            padding: 1.5rem 1rem;
            text-align: center;
            color: white;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #sidebar-wrapper.collapsed .sidebar-brand h4 {
            display: none;
        }
        
        #sidebar-wrapper .nav-link {
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 0;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        #sidebar-wrapper .nav-link:hover {
            background-color: var(--teal-dark);
        }
        
        #sidebar-wrapper .nav-link.active {
            background-color: var(--mint-green);
            color: var(--teal-dark);
            font-weight: 600;
        }
        
        #sidebar-wrapper.collapsed .nav-link span {
            display: none;
        }
        
        #sidebar-wrapper.collapsed .nav-link {
            text-align: center;
            padding: 0.8rem 0;
        }
        
        #sidebar-wrapper.collapsed .nav-link i {
            font-size: 1.2rem;
            margin-right: 0;
        }
        
        /* Main Content Styles */
        #page-content-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            background-color: var(--light-gray);
            padding: 20px;
            position: relative;
            width: calc(100% - var(--sidebar-width));
            z-index: 1; /* Ensure content appears above sidebar when needed */
        }
        
        #page-content-wrapper.expanded {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }
        
        /* Ensure proper spacing in the cards */
        .card-body {
            padding: 20px;
            overflow-x: auto; /* Add horizontal scrolling for tables that might overflow */
        }
        
        /* Fix for any potential layout issues in tabs */
        .tab-content {
            width: 100%;
            overflow-x: hidden;
        }
        
        /* Improve filter display on smaller screens */
        @media (max-width: 992px) {
            .filters.d-flex {
                flex-direction: column;
            }
            
            .filters .form-select {
                margin-bottom: 5px;
                width: 100%;
            }
        }
        
        /* Toggle Button */
        #menu-toggle {
            position: fixed;
            top: 10px;
            left: calc(var(--sidebar-width) - 20px);
            z-index: 1010;
            background-color: var(--mint-green);
            color: var(--teal);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        #menu-toggle.collapsed {
            left: calc(var(--sidebar-collapsed-width) - 20px);
        }
        
        #menu-toggle:hover {
            background-color: var(--mint-green-dark);
        }
        
        /* Card and Form Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            background-color: white;
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }
        
        .card-header h6 {
            color: var(--teal);
            font-weight: 700;
            margin: 0;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--mint-green);
            box-shadow: 0 0 0 0.2rem rgba(152, 255, 152, 0.25);
        }
        
        .form-label {
            color: var(--teal);
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .btn-primary {
            background-color: var(--teal);
            border-color: var(--teal);
        }
        
        .btn-primary:hover {
            background-color: var(--teal-dark);
            border-color: var(--teal-dark);
        }
        
        .btn-secondary {
            background-color: #e0e0e0;
            border-color: #e0e0e0;
            color: #333;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            color: var(--teal);
            font-weight: 600;
        }
        
        /* Navbar for mobile */
        .navbar {
            display: none;
        }
        
        /* Tab styling fixes */
        .nav-tabs .nav-link {
            color: var(--teal);
            border-color: #e0e0e0;
            background-color: #f8f9fa;
            border-radius: 10px 10px 0 0;
            margin-right: 5px;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--teal-dark);
            font-weight: 600;
            background-color: white;
            border-bottom-color: white;
        }
        
        /* Media Queries */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                transform: translateX(-100%);
                box-shadow: none;
            }
            
            #sidebar-wrapper.active {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }
            
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
                padding-top: 70px; /* Space for the mobile navbar */
            }
            
            #page-content-wrapper.expanded {
                margin-left: 0;
                width: 100%;
            }
            
            .navbar {
                display: flex;
                background-color: var(--teal);
                padding: 10px 15px;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 999;
            }
            
            .navbar-brand {
                color: white;
                font-weight: 700;
            }
            
            .mobile-menu-toggle {
                color: white;
                background: none;
                border: none;
                font-size: 1.5rem;
            }
            
            #menu-toggle {
                display: none;
            }
        }
        
        /* Bottom Menu Styles */
        .sidebar-bottom-menu {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
        }

        .sidebar-bottom-menu .nav-link {
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 0;
            transition: all 0.2s;
        }

        .sidebar-bottom-menu .nav-link:hover {
            background-color: var(--teal-dark);
        }

        .sidebar-bottom-menu .nav-link.active {
            background-color: var(--mint-green);
            color: var(--teal-dark);
            font-weight: 600;
        }

        /* Sign Out Link Style */
        .nav-link.sign-out {
            background-color: #F75A5A !important;
            color: white !important;
        }

        .nav-link.sign-out:hover {
            background-color: #e54a4a !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <!-- Mobile Navbar -->
    <nav class="navbar d-md-none">
        <button class="mobile-menu-toggle" id="mobile-menu-toggle">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand" href="#">{{ config('app.name', 'HealthCare Management') }}</a>
    </nav>

    <!-- Sidebar Toggle Button -->
    <button class="btn" id="menu-toggle">
        <i class="bi bi-chevron-left"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <div class="sidebar-brand">
            <h4>{{ config('app.name', 'HealthCare Management') }}</h4>
            <div class="sidebar-toggle-icon text-center">
                <i class="bi bi-hospital"></i>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                @if(auth()->user()->isAdmin)
                    <!-- Admin Menu -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="/home">
    <i class="bi bi-speedometer2 me-2"></i> <span>Admin Dashboard</span>
</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/user-activity') ? 'active' : '' }}" href="/admin/user-activity">
                            <i class="bi bi-people me-2"></i> <span>User Activity</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/export-reports') ? 'active' : '' }}" href="{{ route('admin.export-reports') }}">
                            <i class="bi bi-file-earmark-excel me-2"></i> <span>Export Reports</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/family-planning-records') ? 'active' : '' }}" href="{{ route('admin.family-planning-records') }}">
                            <i class="bi bi-card-list me-2"></i> <span>Records Tracking</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('analytics') ? 'active' : '' }}" href="{{ route('analytics') }}">
                            <i class="bi bi-graph-up me-2"></i> <span>Analytics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('family-planning*') && !Request::is('family-planning/create') ? 'active' : '' }}" href="{{ route('family-planning.index') }}">
                            <i class="bi bi-calendar me-2"></i> <span>Family Planning</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('family-planning/create') ? 'active' : '' }}" href="{{ route('family-planning.create') }}">
                            <i class="bi bi-plus-circle me-2"></i> <span>FP Record Form</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('immunization*') && !Request::is('immunization/create') ? 'active' : '' }}" href="{{ route('immunization.index') }}">
                            <i class="bi bi-shield me-2"></i> <span>Immunization Records</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('immunization/create') ? 'active' : '' }}" href="{{ route('immunization.create') }}">
                            <i class="bi bi-plus-circle me-2"></i> <span>Immunization Form</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('bmi-calculator') ? 'active' : '' }}" href="{{ route('bmi.calculator') }}">
                            <i class="bi bi-calculator me-2"></i> <span>BMI Calculator</span>
                        </a>
                    </li>
                @else
                    <!-- Regular User Menu -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-2"></i> <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/export-reports') ? 'active' : '' }}" href="{{ url('user/export-reports') }}">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i> <span>Export Reports</span>
                        </a>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('analytics') ? 'active' : '' }}" href="{{ route('analytics') }}">
                            <i class="bi bi-graph-up me-2"></i> <span>Analytics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('family-planning*') && !Request::is('family-planning/create') ? 'active' : '' }}" href="{{ route('family-planning.index') }}">
                            <i class="bi bi-calendar me-2"></i> <span>Family Planning</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('family-planning/create') ? 'active' : '' }}" href="{{ route('family-planning.create') }}">
                            <i class="bi bi-plus-circle me-2"></i> <span>FP Record Form</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('immunization*') && !Request::is('immunization/create') ? 'active' : '' }}" href="{{ route('immunization.index') }}">
                            <i class="bi bi-shield me-2"></i> <span>Immunization Records</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('immunization/create') ? 'active' : '' }}" href="{{ route('immunization.create') }}">
                            <i class="bi bi-plus-circle me-2"></i> <span>Immunization Form</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('bmi-calculator') ? 'active' : '' }}" href="{{ route('bmi.calculator') }}">
                            <i class="bi bi-calculator me-2"></i> <span>BMI Calculator</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Bottom Menu Items -->
        <div class="sidebar-bottom-menu mt-auto">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('account*') ? 'active' : '' }}" href="{{ route('account.index') }}">
                        <i class="bi bi-person me-2"></i> <span>Account Details</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sign-out" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> <span>Sign Out</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <!-- Just showing the page title once -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
                <h1 class="h2 text-teal">@yield('title')</h1>
            </div>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Initial state adjustment for dashboard tabs if sidebar is collapsed on page load
            if ($("#sidebar-wrapper").hasClass("collapsed") && $("#dashboardTabsContent").length) {
                setTimeout(function() {
                    if (typeof Chart !== 'undefined' && Chart.instances) {
                        Chart.instances.forEach(function(chart) {
                            chart.resize();
                            chart.render();
                        });
                    }
                }, 100);
            }
            
            // Toggle sidebar
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#sidebar-wrapper").toggleClass("collapsed");
                $("#page-content-wrapper").toggleClass("expanded");
                $("#menu-toggle").toggleClass("collapsed");
                
                // Force redraw of charts when sidebar is toggled
                if ($("#dashboardTabsContent").length) {
                    // Force immediate redraw for chart containers
                    $(".card-body canvas").css("width", "100%");
                    
                    // Ensure dashboard container updates properly
                    $("#dashboardContainer").css("width", "100%");
                    
                    // Force chart resize after transition completes
                    setTimeout(function() {
                        if (typeof Chart !== 'undefined' && Chart.instances) {
                            Chart.instances.forEach(function(chart) {
                                chart.resize();
                                chart.render();
                            });
                        }
                    }, 350);
                }
                
                if ($("#sidebar-wrapper").hasClass("collapsed")) {
                    $("#menu-toggle i").removeClass("bi-chevron-left").addClass("bi-chevron-right");
                } else {
                    $("#menu-toggle i").removeClass("bi-chevron-right").addClass("bi-chevron-left");
                }
            });
            
            // Mobile menu toggle
            $("#mobile-menu-toggle").click(function(e) {
                e.preventDefault();
                $("#sidebar-wrapper").toggleClass("active");
            });
            
            // If on home page, listen for tab changes to update URL hash
            const dashboardTabs = document.getElementById('dashboardTabs');
            if (dashboardTabs) {
                const tabTriggers = dashboardTabs.querySelectorAll('[data-bs-toggle="tab"]');
                tabTriggers.forEach(trigger => {
                    trigger.addEventListener('shown.bs.tab', function (event) {
                        const id = event.target.getAttribute('data-bs-target').substring(1);
                        window.location.hash = id;
                    });
                });
                
                // Check for hash in URL to open correct tab
                if (window.location.hash) {
                    const tabId = window.location.hash.substring(1);
                    const tab = document.querySelector(`[data-bs-target="#${tabId}"]`);
                    if (tab) {
                        const bsTab = new bootstrap.Tab(tab);
                        bsTab.show();
                    }
                }
            }
        });
        
        // Function to open BMI calculator tab from sidebar
        function openBmiCalculator(event) {
            event.preventDefault();
            if (window.location.pathname !== '/home') {
                window.location.href = '/home#bmi-calculator';
            } else {
                const tab = document.querySelector('[data-bs-target="#bmi-calculator"]');
                if (tab) {
                    const bsTab = new bootstrap.Tab(tab);
                    bsTab.show();
                }
            }
        }
    </script>
    
    @yield('scripts')
    @stack('scripts')
    @stack('scripts')
</body>
</html> 