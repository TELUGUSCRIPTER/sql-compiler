<?php
// app/admin/includes/admin_nav.php
// Reusable admin navigation component with mobile responsiveness

if (session_status() === PHP_SESSION_NONE) session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
$adminName = $_SESSION['name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - SQL Compiler</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        /* Mobile menu animation */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        /* Overlay */
        .menu-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }
        
        .menu-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #4a4a4a;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #666;
        }
    </style>
    
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full bg-gray-50">
    
    <!-- Mobile Menu Overlay -->
    <div id="menuOverlay" class="menu-overlay fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden" onclick="toggleMobileMenu()"></div>
    
    <!-- Mobile Slide-out Menu -->
    <div id="mobileMenu" class="mobile-menu fixed left-0 top-0 bottom-0 w-64 bg-gray-900 z-50 md:hidden overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-white">Admin Panel</h2>
                <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 rounded-lg <?= $currentPage === 'dashboard.php' ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                
                <a href="users.php" class="flex items-center px-4 py-3 rounded-lg <?= $currentPage === 'users.php' ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users
                </a>
                
                <a href="logs.php" class="flex items-center px-4 py-3 rounded-lg <?= $currentPage === 'logs.php' ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Query Logs
                </a>
                
                <a href="security.php" class="flex items-center px-4 py-3 rounded-lg <?= $currentPage === 'security.php' ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Security
                </a>
            </nav>
            
            <div class="mt-8 pt-8 border-t border-gray-700">
                <div class="px-4 py-2 text-sm text-gray-400">Signed in as</div>
                <div class="px-4 py-2 text-white font-semibold"><?= htmlspecialchars($adminName) ?></div>
                <a href="/logout.php" class="mt-4 flex items-center px-4 py-3 rounded-lg text-red-400 hover:bg-red-900 hover:bg-opacity-20">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Desktop & Mobile Header -->
    <nav class="bg-gray-900 shadow-lg sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Left: Logo & Mobile Menu Button -->
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button onclick="toggleMobileMenu()" class="md:hidden text-gray-400 hover:text-white mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <div class="flex items-center">
                        <span class="text-white font-bold text-lg">Admin Console</span>
                    </div>
                    
                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:flex md:ml-10 md:space-x-8">
                        <a href="dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'border-b-2 border-purple-500 text-white' : 'text-gray-300 hover:text-white' ?> px-3 py-2 text-sm font-medium transition">
                            Dashboard
                        </a>
                        <a href="users.php" class="<?= $currentPage === 'users.php' ? 'border-b-2 border-purple-500 text-white' : 'text-gray-300 hover:text-white' ?> px-3 py-2 text-sm font-medium transition">
                            Users
                        </a>
                        <a href="logs.php" class="<?= $currentPage === 'logs.php' ? 'border-b-2 border-purple-500 text-white' : 'text-gray-300 hover:text-white' ?> px-3 py-2 text-sm font-medium transition">
                            Logs
                        </a>
                        <a href="security.php" class="<?= $currentPage === 'security.php' ? 'border-b-2 border-purple-500 text-white' : 'text-gray-300 hover:text-white' ?> px-3 py-2 text-sm font-medium transition">
                            Security
                        </a>
                    </div>
                </div>
                
                <!-- Right: User Info & Logout (Desktop Only) -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <span class="text-gray-400 text-sm">Welcome, <span class="text-white font-semibold"><?= htmlspecialchars($adminName) ?></span></span>
                    <a href="/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('menuOverlay');
            menu.classList.toggle('open');
            overlay.classList.toggle('open');
        }
        
        // Close menu when clicking outside
        document.getElementById('menuOverlay').addEventListener('click', toggleMobileMenu);
    </script>
    
    <!-- Main Content Wrapper -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
