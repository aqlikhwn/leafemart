<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Leafé Mart') - Mahallah Bilal Online Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFFFF;
            min-height: 100vh;
        }

        :root {
            --primary: #4A90D9;
            --primary-light: #E8F0FE;
            --primary-dark: #1E3A5F;
            --sidebar-bg: #FFFFFF;
            --white: #FFFFFF;
            --gray-100: #F8FAFC;
            --gray-200: #E2E8F0;
            --gray-400: #94A3B8;
            --gray-600: #475569;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Sidebar - Wider with text labels */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            padding: 20px 15px;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid var(--gray-200);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.03);
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-200);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Sidebar Logo */
        .sidebar-logo {
            text-align: center;
            margin-bottom: 15px;
            padding: 5px;
        }

        .sidebar-logo a {
            display: block;
        }

        .logo-img {
            width: 100%;
            max-width: 100px;
            height: auto;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        /* User greeting at top */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .sidebar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #C5D4FF;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .sidebar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-greeting {
            font-size: 13px;
            color: var(--primary-dark);
        }

        .sidebar-greeting strong {
            display: block;
            font-size: 15px;
        }

        /* Decorative Separator */
        .sidebar-separator {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 10px;
            margin-bottom: 10px;
        }

        .separator-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            border-radius: 2px;
        }

        .separator-icon {
            color: var(--primary);
            font-size: 12px;
            opacity: 0.8;
        }

        /* Navigation */
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: var(--primary-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            overflow: visible;
        }

        .sidebar-link .badge {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-divider {
            flex: 1;
        }

        .sidebar-footer {
            display: flex;
            flex-direction: column;
            gap: 5px;
            border-top: 1px solid rgba(0,0,0,0.1);
            padding-top: 15px;
            margin-top: 15px;
        }

        .sidebar-link.logout {
            color: var(--danger);
            background: transparent;
        }

        .sidebar-link.logout:hover {
            background: #FEE2E2;
            color: #DC2626;
        }

        .sidebar-link.login {
            color: var(--primary);
            background: transparent;
        }

        .sidebar-link.login:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Admin Submenu Styles */
        .sidebar-submenu-container {
            display: flex;
            flex-direction: column;
        }

        .sidebar-link.has-submenu {
            cursor: pointer;
        }

        .sidebar-link.has-submenu .submenu-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .sidebar-link.has-submenu.expanded .submenu-arrow {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: var(--gray-100);
            border-radius: 8px;
            margin-top: 5px;
        }

        .sidebar-submenu.expanded {
            max-height: 250px;
        }

        .sidebar-submenu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px 10px 38px;
            color: var(--primary-dark);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 2px 5px;
        }

        .sidebar-submenu-link:hover,
        .sidebar-submenu-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-submenu-link i {
            width: 16px;
            text-align: center;
            font-size: 13px;
        }

        /* Mobile Admin Submenu */
        .mobile-submenu-container {
            display: flex;
            flex-direction: column;
        }

        .mobile-nav-link.has-submenu .submenu-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .mobile-nav-link.has-submenu.expanded .submenu-arrow {
            transform: rotate(180deg);
        }

        .mobile-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            margin-top: 5px;
        }

        .mobile-submenu.expanded {
            max-height: 250px;
        }

        .mobile-submenu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px 12px 45px;
            color: var(--primary-dark);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .mobile-submenu-link:hover,
        .mobile-submenu-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .mobile-submenu-link i {
            width: 16px;
            text-align: center;
            font-size: 13px;
        }

        /* Tablet mode - hide submenu text */
        @media (max-width: 768px) {
            .sidebar-submenu {
                display: none;
            }
        }

                /* Footer Styles */
        .site-footer {
            background: #071a30ff;
            color: white;
            padding: 25px 40px 15px;
            margin-top: auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 15px;
        }

        .footer-section h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: white;
        }

        .footer-section p,
        .footer-section a {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 15px;
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .footer-social a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background: #C5D4FF;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            .site-footer {
                padding: 30px 20px 15px;
            }
        }
/* Main content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 0;
            background: #C5D4FF;
            border-radius: 30px 0 0 30px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content .content-area {
            flex: 1;
        }


        /* Top Header with Cart */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 40px;
            border-bottom: 1px solid var(--gray-200);
        }

        .site-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .header-cart {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--primary-dark);
            font-weight: 500;
            padding: 12px 20px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            background: var(--white);
            transition: all 0.3s ease;
        }

        .header-cart:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        .header-cart i {
            font-size: 20px;
        }

        .cart-badge {
            background: var(--danger);
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
            position: relative;
            top: -8px;
            left: -8px;
        }

        /* Hamburger Menu Button */
        .hamburger-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 40px;
            height: 40px;
            padding: 8px;
            background: var(--primary-light);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hamburger-btn:hover {
            background: #C5D4FF;
        }

        .hamburger-btn:hover .hamburger-line {
            background: white;
        }

        .hamburger-line {
            width: 100%;
            height: 3px;
            background: #C5D4FF;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-menu-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Mobile Slide Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100vh;
            background: var(--white);
            z-index: 1001;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .mobile-menu.active {
            left: 0;
        }

        .mobile-menu-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--gray-200);
        }

        .mobile-menu-close {
            margin-left: auto;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FEE2E2;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            color: #DC2626;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .mobile-menu-close:hover {
            background: var(--danger);
            color: white;
        }

        .mobile-nav {
            padding: 15px;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--primary-dark);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .mobile-nav-link i {
            width: 20px;
            text-align: center;
        }

        .mobile-nav-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 10px 0;
        }

        /* Mobile Cart Button (icon only) */
        .mobile-cart-btn {
            display: none;
            position: relative;
            width: 40px;
            min-width: 40px;
            max-width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            border-radius: 10px;
            color: var(--primary);
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .mobile-cart-btn:hover {
            background: #C5D4FF;
            color: white;
        }

        .mobile-cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            .hamburger-btn {
                display: flex;
                order: 3;
                width: 40px;
                height: 40px;
            }
            .mobile-menu {
                display: block;
            }
            .mobile-cart-btn {
                display: flex;
                order: 2;
                width: 40px;
                height: 40px;
            }
            .top-header > a {
                flex: 1;
                order: 1;
            }
            .top-header {
                gap: 8px;
                padding: 15px 20px;
            }
            .header-cart {
                display: none;
            }
        }

        /* Content Area */
        .content-area {
            padding: 30px 40px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            overflow: hidden;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-weight: 500;
            color: var(--primary-dark);
        }

        /* Cards */
        .card {
            background: var(--white);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--gray-200);
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 144, 217, 0.4);
        }

        .btn-secondary {
            background: var(--primary-light);
            color: var(--primary);
        }

        .btn-secondary:hover {
            background: #FFFFFF;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        /* Form inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.1);
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 24px;
        }

        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-5 { grid-template-columns: repeat(5, 1fr); }

        @media (max-width: 1200px) {
            .grid-4 { grid-template-columns: repeat(3, 1fr); }
            .grid-5 { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 992px) {
            .grid-3, .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-5 { grid-template-columns: repeat(2, 1fr); }
        }

        /* Tablet Styles */
        @media (max-width: 768px) {
            .sidebar { 
                width: 70px; 
                padding: 15px 10px; 
            }
            .sidebar-logo { display: none; }
            .sidebar-separator { display: none; }
            .sidebar-link span { display: none; }
            .sidebar-link { 
                justify-content: center;
                padding: 12px;
            }
            .sidebar-link i { margin: 0; }
            .sidebar-user { flex-direction: column; gap: 5px; }
            .sidebar-greeting { display: none; }
            .main-content { margin-left: 70px; }
            .top-header, .content-area { padding: 20px; }
            .site-title { font-size: 22px; }
            .header-cart { padding: 10px 15px; font-size: 14px; }
            .page-title { font-size: 20px; }
            .card { padding: 20px; }
            .btn { padding: 10px 18px; font-size: 13px; }
            .form-control { padding: 12px 14px; }
        }

        /* Mobile Styles */
        @media (max-width: 600px) {
            .sidebar {
                display: none !important; /* Hide sidebar on mobile, use hamburger menu instead */
            }
            
            .main-content { 
                margin-left: 0 !important; 
                margin-bottom: 0 !important;
                border-radius: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .top-header { 
                padding: 15px !important; 
                flex-wrap: nowrap !important;
                gap: 10px !important;
                width: 100% !important;
            }
            .site-title { 
                font-size: 20px !important; 
                flex: 1 !important;
            }
            .header-cart { 
                display: none !important; /* Hide cart button, it's in the mobile menu */
            }
            .hamburger-btn {
                display: flex !important;
                order: 2; /* Put hamburger on right side */
            }
            .content-area { 
                padding: 10px !important; 
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .page-header { 
                flex-direction: column !important; 
                align-items: flex-start !important;
                gap: 15px !important;
            }
            .page-title { font-size: 18px !important; }
            
            .grid-2, .grid-3, .grid-4, .grid-5 { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 10px !important; 
            }
            
            .card { 
                padding: 12px !important; 
                border-radius: 12px !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .product-card { 
                border-radius: 10px; 
            }
            .product-image { 
                height: 130px; 
                width: calc(100% - 12px);
                margin: 6px; 
                border-radius: 8px;
                overflow: hidden; 
            }
            .product-info { padding: 8px 10px 12px; }
            .product-name { font-size: 13px; line-height: 1.3; }
            .product-price { font-size: 14px; }
            
            .btn { 
                padding: 10px 16px; 
                font-size: 13px;
                border-radius: 8px;
            }
            .form-control { 
                padding: 12px; 
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .table { font-size: 13px; }
            .table th, .table td { padding: 10px 8px; }
            
            /* Make tables horizontally scrollable on mobile */
            .card {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table {
                min-width: 100%;
                width: max-content;
                white-space: nowrap;
            }
            
            .quantity-controls { gap: 6px; }
            .quantity-btn { width: 32px; height: 32px; }
            
            .badge { padding: 3px 8px; font-size: 11px; }
            
            .alert { padding: 12px 15px; font-size: 13px; }
        }

        /* Small Mobile Styles */
        @media (max-width: 480px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; gap: 12px; }
            
            .top-header {
                justify-content: center;
            }
            .site-title { 
                width: 100%; 
                text-align: center;
            }
            .header-cart {
                width: 100%;
                justify-content: center;
            }
            
            .product-image { height: 180px; overflow: hidden; }
            .product-info { padding: 15px; }
            .product-name { font-size: 15px; }
            .product-price { font-size: 18px; }
            
            .form-group { margin-bottom: 15px; }
            
            .category-pills { 
                gap: 8px; 
                padding: 8px 0;
            }
            .category-pill { 
                padding: 8px 14px; 
                font-size: 13px;
            }
        }

        /* Touch-friendly enhancements */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover { transform: none; }
            .product-card:hover { transform: none; }
            .sidebar-link:hover { transform: none; }
            
            .sidebar-link:active,
            .btn:active,
            .product-card:active {
                opacity: 0.8;
                transform: scale(0.98);
            }
        }

        /* Landscape phone */
        @media (max-width: 900px) and (orientation: landscape) {
            .sidebar {
                width: 60px;
            }
            .main-content {
                margin-left: 60px;
            }
            .grid-3, .grid-4 { grid-template-columns: repeat(3, 1fr); }
        }

        /* Product Detail Page */
        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            .product-detail-grid .product-image {
                height: 280px !important;
            }
        }

        @media (max-width: 480px) {
            .product-detail-grid {
                gap: 20px;
            }
            .product-detail-grid .product-image {
                height: 220px !important;
            }
        }

        /* Cart Page Grid */
        .cart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 900px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Generic Responsive Two-Column Grid */
        .responsive-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .responsive-grid-2-1 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .responsive-grid-2,
            .responsive-grid-2-1 {
                grid-template-columns: 1fr;
            }
            .responsive-grid-2 > *,
            .responsive-grid-2-1 > * {
                min-width: 0;
            }
        }

        /* Responsive Tables */
        @media (max-width: 600px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table-responsive table {
                min-width: 500px;
            }
        }

        /* Product Card */
        .product-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-200);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .product-image {
            position: relative;
            width: calc(100% - 20px);
            height: 180px;
            background: linear-gradient(135deg, var(--primary-light), #e8efff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            margin: 10px;
            overflow: hidden;
        }

        .product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 16px;
        }

        .product-name {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        /* Category Pills */
        .category-pills {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 15px 0;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .category-pill {
            padding: 10px 22px;
            background: var(--white);
            border-radius: 50px;
            border: 2px solid var(--gray-200);
            color: var(--gray-600);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
        }

        .category-pill:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .category-pill.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        /* Badge */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: #D1FAE5; color: #059669; }
        .badge-warning { background: #FEF3C7; color: #D97706; }
        .badge-danger { background: #FEE2E2; color: #DC2626; }
        .badge-info { background: #DBEAFE; color: #2563EB; }
        .badge-primary { background: var(--primary-light); color: var(--primary); }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success { background: #D1FAE5; color: #059669; }
        .alert-danger { background: #FEE2E2; color: #DC2626; }
        .alert-warning { background: #FEF3C7; color: #D97706; }
        .alert-info { background: #DBEAFE; color: #2563EB; }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }

        .table th {
            font-weight: 600;
            color: var(--gray-600);
            font-size: 13px;
            text-transform: uppercase;
        }

        .table tr:hover {
            background: var(--gray-100);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-400);
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
        }

        /* Quantity Controls */
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 2px solid var(--gray-200);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .quantity-value {
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        /* Pagination Styles */
        nav[role="navigation"] {
            display: flex;
            justify-content: center;
        }

        nav[role="navigation"] > div {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        nav[role="navigation"] > div:first-child {
            display: none;
        }

        nav[role="navigation"] svg {
            width: 20px;
            height: 20px;
        }

        nav[role="navigation"] a,
        nav[role="navigation"] span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        nav[role="navigation"] a {
            background: var(--white);
            color: var(--primary-dark);
            border: 1px solid var(--gray-200);
        }

        nav[role="navigation"] a:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background: #C5D4FF;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
        }

        nav[role="navigation"] span[aria-disabled="true"] {
            background: var(--gray-100);
            color: var(--gray-400);
            cursor: not-allowed;
        }

        /* Toast Notification Styles */
        .toast-notification {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 16px 45px 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            overflow: hidden;
            border: 1px solid transparent;
        }

        .toast-notification.hiding {
            animation: slideOut 0.3s ease-in forwards;
        }

        @keyframes slideIn {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        .toast-success {
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
            border-color: #10B981;
        }

        .toast-success .toast-icon {
            color: #059669;
        }

        .toast-error {
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            border-color: #EF4444;
        }

        .toast-error .toast-icon {
            color: #DC2626;
        }

        .toast-warning {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-color: #F59E0B;
        }

        .toast-warning .toast-icon {
            color: #D97706;
        }

        .toast-info {
            background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
            border-color: #3B82F6;
        }

        .toast-info .toast-icon {
            color: #2563EB;
        }

        .toast-icon {
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .toast-content {
            flex: 1;
            min-width: 0;
        }

        .toast-title {
            font-weight: 700;
            font-size: 15px;
            color: var(--primary-dark);
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.5;
        }

        .toast-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            transition: all 0.3s ease;
        }

        .toast-close:hover {
            background: rgba(255, 255, 255, 1);
            color: var(--danger);
            transform: scale(1.1);
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: #10B981;
            animation: progressShrink 5s linear forwards;
            border-radius: 0 0 0 12px;
        }

        .toast-progress-error {
            background: #EF4444;
        }

        .toast-progress-warning {
            background: #F59E0B;
        }

        .toast-progress-info {
            background: #3B82F6;
        }

        @keyframes progressShrink {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        /* Mobile toast styles */
        @media (max-width: 600px) {
            .toast-notification {
                padding: 14px 40px 14px 16px;
                gap: 12px;
                border-radius: 10px;
                margin-bottom: 15px;
            }

            .toast-icon {
                font-size: 20px;
            }

            .toast-title {
                font-size: 14px;
            }

            .toast-message {
                font-size: 13px;
            }

            .toast-close {
                width: 24px;
                height: 24px;
                top: 10px;
                right: 10px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Logo -->
            <div class="sidebar-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Leafé Mart" class="logo-img">
                </a>
            </div>
            
            <!-- User Greeting -->
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    @auth
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    @else
                        <i class="fas fa-user" style="font-size: 14px;"></i>
                    @endauth
                </div>
                <div class="sidebar-greeting">
                    Assalam'alaykum,
                    <strong>@auth {{ auth()->user()->name }} @else Guest @endauth!</strong>
                </div>
            </div>
            
            <!-- Decorative Separator -->
            <div class="sidebar-separator">
                <div class="separator-line"></div>
                <i class="fas fa-leaf separator-icon"></i>
                <div class="separator-line"></div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('home') }}" class="sidebar-link {{ request()->routeIs('home') || (request()->routeIs('product.*') && request('from') == 'home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('browse') }}" class="sidebar-link {{ request()->routeIs('browse*') || request()->routeIs('search*') || (request()->routeIs('product.*') && request('from') != 'home') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Browse</span>
                </a>
                @auth
                <a href="{{ route('orders.history') }}" class="sidebar-link {{ request()->routeIs('orders*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>My Orders</span>
                </a>
                @livewire('notification-badge')

                @endauth

                <div class="sidebar-divider"></div>

                <div class="sidebar-divider"></div>
            </nav>
            
            <div class="sidebar-footer">
                <a href="{{ route('about') }}" class="sidebar-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>About Us</span>
                </a>
                <a href="{{ route('faq') }}" class="sidebar-link {{ request()->routeIs('faq') || request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
                @auth
                <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') || request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
                @if(auth()->user()->isAdmin())
                <div class="sidebar-submenu-container">
                    <div class="sidebar-link has-submenu {{ request()->routeIs('admin.*') && !request()->routeIs('admin.messages*') && !request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit; flex: 1;">
                            <i class="fas fa-user-shield"></i>
                            <span>Admin Dashboard</span>
                        </a>
                        <i class="fas fa-chevron-down submenu-arrow" onclick="toggleAdminSubmenu(event)" style="cursor: pointer; padding: 5px;"></i>
                    </div>
                    <div class="sidebar-submenu" id="adminSubmenu">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>Manage Products</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span>Manage Categories</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>Manage Orders</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="sidebar-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                    @php
                        $unreadMsgCount = \App\Models\Message::unread()->count();
                    @endphp
                    @if($unreadMsgCount > 0)
                        <span class="badge badge-danger">{{ $unreadMsgCount }}</span>
                    @endif
                </a>
                @else
                <a href="{{ route('messages.index') }}" class="sidebar-link {{ request()->routeIs('messages.index') ? 'active' : '' }}">
                    <i class="fas fa-envelope-open-text"></i>
                    <span>My Messages</span>
                    @php
                        $unreadReplies = \App\Models\Message::where('user_id', auth()->id())
                            ->whereNotNull('admin_reply')
                            ->where('reply_read', false)
                            ->count();
                    @endphp
                    @if($unreadReplies > 0)
                        <span class="badge badge-danger">{{ $unreadReplies }}</span>
                    @endif
                </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link logout" style="width: 100%; border: none; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="sidebar-link login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header with Cart -->
            <div class="top-header">
                <!-- Hamburger Menu Button (Mobile Only) -->
                <button class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                
                <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Leafé Mart" style="width: 45px; height: 45px; border-radius: 10px;">
                    <h1 class="site-title">Leafé Mart</h1>
                </a>
                
                @auth
                <!-- Mobile Cart Icon (Mobile Only) -->
                <a href="{{ route('cart.index') }}" class="mobile-cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    @livewire('cart-counter')
                </a>
                
                <!-- Desktop Cart Button -->
                <a href="{{ route('cart.index') }}" class="header-cart">
                    Your Cart
                    <i class="fas fa-shopping-cart"></i>
                    @livewire('cart-counter')
                </a>


                @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endauth
            </div>

            <!-- Mobile Menu Overlay -->
            <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>
            
            <!-- Mobile Slide Menu -->
            <div class="mobile-menu" id="mobileMenu">
                <div class="mobile-menu-header">
                    <div class="sidebar-avatar" style="width: 50px; height: 50px; font-size: 20px;">
                        @auth
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="">
                            @else
                                {{ substr(auth()->user()->name, 0, 1) }}
                            @endif
                        @else
                            <i class="fas fa-user" style="font-size: 18px;"></i>
                        @endauth
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--gray-400);">Assalam'alaykum,</div>
                        <strong style="color: var(--primary-dark);">@auth {{ auth()->user()->name }} @else Guest @endauth!</strong>
                    </div>
                    <button class="mobile-menu-close" onclick="toggleMobileMenu()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <nav class="mobile-nav">
                    <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') || (request()->routeIs('product.*') && request('from') == 'home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <a href="{{ route('browse') }}" class="mobile-nav-link {{ request()->routeIs('browse*') || request()->routeIs('search*') || (request()->routeIs('product.*') && request('from') != 'home') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Browse
                    </a>
                    @auth
                    <a href="{{ route('orders.history') }}" class="mobile-nav-link {{ request()->routeIs('orders*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i> My Orders
                    </a>
                    <a href="{{ route('notifications') }}" class="mobile-nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i> Notifications
                        @php
                            $mobileUnreadNotifCount = \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count();
                        @endphp
                        @if($mobileUnreadNotifCount > 0)
                            <span class="badge badge-danger" style="margin-left: auto;">{{ $mobileUnreadNotifCount }}</span>
                        @endif
                    </a>
                    
                    <div class="mobile-nav-divider"></div>
                    
                    <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i> About Us
                    </a>
                    <a href="{{ route('faq') }}" class="mobile-nav-link {{ request()->routeIs('faq') || request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i> FAQ
                    </a>
                    <a href="{{ route('profile') }}" class="mobile-nav-link {{ request()->routeIs('profile') || request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    @if(auth()->user()->isAdmin())
                    <div class="mobile-submenu-container">
                        <div class="mobile-nav-link has-submenu {{ request()->routeIs('admin.*') && !request()->routeIs('admin.messages*') && !request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit; flex: 1;">
                                <i class="fas fa-user-shield"></i> Admin Dashboard
                            </a>
                            <i class="fas fa-chevron-down submenu-arrow" onclick="toggleMobileAdminSubmenu(event)" style="cursor: pointer; padding: 5px;"></i>
                        </div>
                        <div class="mobile-submenu" id="mobileAdminSubmenu">
                            <a href="{{ route('admin.dashboard') }}" class="mobile-submenu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="mobile-submenu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="fas fa-box"></i> Manage Products
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="mobile-submenu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i> Manage Categories
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="mobile-submenu-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="fas fa-receipt"></i> Manage Orders
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="mobile-submenu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('admin.messages.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i> Messages
                        @php
                            $mobileUnreadMsgCount = \App\Models\Message::unread()->count();
                        @endphp
                        @if($mobileUnreadMsgCount > 0)
                            <span class="badge badge-danger" style="margin-left: auto;">{{ $mobileUnreadMsgCount }}</span>
                        @endif
                    </a>
                    @else
                    <a href="{{ route('messages.index') }}" class="mobile-nav-link {{ request()->routeIs('messages.index') ? 'active' : '' }}">
                        <i class="fas fa-envelope-open-text"></i> My Messages
                        @php
                            $mobileUnreadReplies = \App\Models\Message::where('user_id', auth()->id())
                                ->whereNotNull('admin_reply')
                                ->where('reply_read', false)
                                ->count();
                        @endphp
                        @if($mobileUnreadReplies > 0)
                            <span class="badge badge-danger" style="margin-left: auto;">{{ $mobileUnreadReplies }}</span>
                        @endif
                    </a>
                    @endif
                    
                    <div class="mobile-nav-divider"></div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="mobile-nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: var(--danger);">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </button>
                    </form>
                    @else
                    <div class="mobile-nav-divider"></div>
                    <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i> About Us
                    </a>
                    <a href="{{ route('faq') }}" class="mobile-nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i> FAQ
                    </a>
                    <div class="mobile-nav-divider"></div>
                    <a href="{{ route('login') }}" class="mobile-nav-link" style="color: var(--primary);">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                    @endauth
                </nav>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @if(session('success'))
                <div class="toast-notification toast-success" id="toastSuccess">
                    <div class="toast-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">Success!</div>
                        <div class="toast-message">{{ session('success') }}</div>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastSuccess')">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="toast-progress"></div>
                </div>
                @endif

                @if(session('error'))
                <div class="toast-notification toast-error" id="toastError">
                    <div class="toast-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">Error</div>
                        <div class="toast-message">{{ session('error') }}</div>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastError')">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="toast-progress toast-progress-error"></div>
                </div>
                @endif

                @if(session('warning'))
                <div class="toast-notification toast-warning" id="toastWarning">
                    <div class="toast-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">Warning</div>
                        <div class="toast-message">{{ session('warning') }}</div>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastWarning')">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="toast-progress toast-progress-warning"></div>
                </div>
                @endif

                @if(session('info'))
                <div class="toast-notification toast-info" id="toastInfo">
                    <div class="toast-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">Info</div>
                        <div class="toast-message">{{ session('info') }}</div>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastInfo')">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="toast-progress toast-progress-info"></div>
                </div>
                @endif

                @if($errors->any())
                <div class="toast-notification toast-error" id="toastValidation">
                    <div class="toast-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">Validation Error</div>
                        <div class="toast-message">
                            <ul style="margin: 0; padding-left: 15px; font-size: 13px;">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastValidation')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @yield('content')
            </div>
            <!-- Footer -->
            <footer class="site-footer">
                <div class="footer-content">
                    <div class="footer-section">
                        <h4>About Leafe Mart</h4>
                        <p>Your trusted online store at Mahallah Bilal. We provide quality products with convenient delivery to your doorstep.</p>
                        <div class="footer-social">
                            <a href="#"><i class="fab fa-tiktok"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <div class="footer-links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('browse') }}">Browse Products</a>
                            <a href="{{ route('about') }}">About Us</a>
                            <a href="{{ route('faq') }}">FAQ</a>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h4>Contact Us</h4>
                        <p><i class="fas fa-map-marker-alt"></i> Mahallah Bilal, IIUM Gombak</p>
                        <p><i class="fas fa-envelope"></i> leafemart@iium.edu.my</p>
                        <p><i class="fas fa-phone"></i> +60 12-345 6789</p>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} Leafe Mart. All rights reserved.</p>
                </div>
            </footer>
        </main>
    </div>

    <!-- Mobile Menu JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');
            
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (menu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const menu = document.getElementById('mobileMenu');
                if (menu && menu.classList.contains('active')) {
                    toggleMobileMenu();
                }
            }
        });
    </script>

    <!-- Admin Submenu Toggle JavaScript -->
    <script>
        function toggleAdminSubmenu(event) {
            event.preventDefault();
            event.stopPropagation();
            const container = event.currentTarget.closest('.sidebar-submenu-container');
            const link = container.querySelector('.sidebar-link.has-submenu');
            const submenu = document.getElementById('adminSubmenu');
            
            link.classList.toggle('expanded');
            submenu.classList.toggle('expanded');
        }

        function toggleMobileAdminSubmenu(event) {
            event.preventDefault();
            event.stopPropagation();
            const container = event.currentTarget.closest('.mobile-submenu-container');
            const link = container.querySelector('.mobile-nav-link.has-submenu');
            const submenu = document.getElementById('mobileAdminSubmenu');
            
            link.classList.toggle('expanded');
            submenu.classList.toggle('expanded');
        }

        // Auto-expand submenu if on admin page
        document.addEventListener('DOMContentLoaded', function() {
            const isAdminPage = window.location.pathname.startsWith('/admin');
            if (isAdminPage) {
                const adminSubmenu = document.getElementById('adminSubmenu');
                const adminLink = document.querySelector('.sidebar-link.has-submenu');
                const mobileAdminSubmenu = document.getElementById('mobileAdminSubmenu');
                const mobileAdminLink = document.querySelector('.mobile-nav-link.has-submenu');
                
                if (adminSubmenu && adminLink) {
                    adminSubmenu.classList.add('expanded');
                    adminLink.classList.add('expanded');
                }
                if (mobileAdminSubmenu && mobileAdminLink) {
                    mobileAdminSubmenu.classList.add('expanded');
                    mobileAdminLink.classList.add('expanded');
                }
            }
        });
    </script>

    <!-- Toast Notification JavaScript -->
    <script>
        // Close toast with animation
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('hiding');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Auto-dismiss toasts after 5 seconds (except validation errors)
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification:not(#toastValidation)');
            toasts.forEach(toast => {
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.classList.add('hiding');
                        setTimeout(() => {
                            toast.remove();
                        }, 300);
                    }
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
    @livewireScripts
</body>

</html>

























