<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
$isAuth = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | SQL Playground' : 'SQL Playground | Master Databases' ?>
    </title>
    <meta name="description"
        content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'A secure, isolated cloud environment to write, execute, and analyze SQL queries. Instant provisioning. No setup required.' ?>">
    <meta name="keywords"
        content="sql playground, online sql editor, database practice, sql compiler, learn sql, mysql online">
    <meta name="author" content="SQL Playground">
    <?php
    // SEO Logic: Canonical & Noindex
    // Define clean title if not set
    if (!isset($pageTitle)) {
        $pageTitle = "Online SQL Compiler – Run & Practice SQL Queries Instantly";
    }
    // Define clean description if not set
    if (!isset($pageDescription)) {
        $pageDescription = "Run SQL queries online instantly. Free SQL compiler for MySQL & PostgreSQL. No login required. Perfect for practice, testing, and interviews.";
    }

    $protocol = 'https://';
    $domain = 'sqlcompiler.shop';
    $path = $_SERVER['PHP_SELF'];

    // Clean up path (remove .php, index.php becomes root)
    $cleanPath = str_replace('.php', '', $path);
    if ($cleanPath === '/index')
        $cleanPath = '/';

    $canonicalUrl = $protocol . $domain . $cleanPath;

    // Determine if page should be noindexed
    // 1. If it's the editor page (compiler)
    // 2. If there are query parameters (to avoid duplicate content issues)
    // 3. If it's an admin page
    $shouldNoIndex = false;
    if (strpos($path, 'editor.php') !== false || !empty($_GET) || strpos($path, '/admin/') !== false) {
        $shouldNoIndex = true;
    }
    ?>
    <?php if ($shouldNoIndex): ?>
        <meta name="robots" content="noindex, follow">
    <?php else: ?>
        <link rel="canonical" href="<?= $canonicalUrl ?>">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $canonicalUrl ?>">
    <meta property="og:title"
        content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'SQL Playground | Master Databases' ?>">
    <meta property="og:description"
        content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'A secure, isolated cloud environment to write, execute, and analyze SQL queries. Instant provisioning. No setup required.' ?>">
    <meta property="og:image" content="https://sqlcompiler.shop/assets/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://sqlcompiler.shop<?= $_SERVER['PHP_SELF'] ?>">
    <meta property="twitter:title"
        content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'SQL Playground | Master Databases' ?>">
    <meta property="twitter:description"
        content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'A secure, isolated cloud environment to write, execute, and analyze SQL queries. Instant provisioning. No setup required.' ?>">
    <meta property="twitter:image" content="https://sqlcompiler.shop/assets/og-image.jpg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/logo.png">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        cyber: {
                            black: '#0a0f1a',
                            dark: '#0f172a',
                            card: 'rgba(17, 24, 39, 0.7)',
                            primary: '#06b6d4',
                            secondary: '#8b5cf6',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'loader-spin': 'spin 1s linear infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeIn: {
                            'from': { opacity: '0', transform: 'translateY(10px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #0a0f1a;
            color: #ecfeff;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0f1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }

        .glass-sidebar {
            background: rgba(10, 15, 26, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .nav-item-active {
            background: rgba(6, 182, 212, 0.1);
            color: #22d3ee;
            border-left: 3px solid #06b6d4;
        }

        .nav-item-inactive {
            color: #94a3b8;
            border-left: 3px solid transparent;
        }

        .nav-item-inactive:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.03);
        }

        /* Loader */
        #global-loader {
            transition: opacity 0.5s ease-out, visibility 0.5s;
        }
    </style>

    <?php
    // Google Ads - Only on non-admin pages
    $isAdminPage = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
    if (!$isAdminPage):
        ?>

    <?php endif; ?>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>

<body class="h-full antialiased overflow-hidden flex">

    <!-- Global Page Loader -->
    <div id="global-loader" class="fixed inset-0 z-[9999] bg-[#0a0f1a] flex items-center justify-center">
        <div class="flex flex-col items-center gap-4">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 rounded-full border-t-2 border-r-2 border-cyan-500 animate-spin"></div>
                <div class="absolute inset-2 rounded-full border-b-2 border-l-2 border-purple-500 animate-spin"
                    style="animation-direction: reverse;"></div>
            </div>
            <div class="text-xs font-mono text-slate-500 animate-pulse">INITIALIZING...</div>
        </div>
    </div>
    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('global-loader');
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 500);
        });
    </script>

    <?php if ($isAuth): ?>
        <!-- SIDEBAR -->
        <!-- Default to w-20 (minimized) to prevent FOUC since default is minimized -->
        <aside id="sidebar"
            class="w-20 glass-sidebar h-full flex flex-col z-50 hidden md:flex transition-all duration-300 ease-in-out group/sidebar">

            <!-- Logo area -->
            <div class="h-16 flex items-center px-0 justify-center border-b border-white/5 relative">
                <!-- Toggle Button (absolute on right border) -->
                <button id="sidebar-toggle"
                    class="absolute -right-3 top-6 bg-slate-800 border border-slate-700 text-slate-400 rounded-full p-1 shadow-lg hover:text-white transition z-50">
                    <svg id="toggle-icon" class="w-3 h-3 transform rotate-180 transition-transform duration-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                    <img src="assets/logo.png" alt="Logo" class="w-8 h-8 flex-shrink-0 object-contain ml-6">
                    <span
                        class="font-bold tracking-tight text-lg nav-label opacity-0 w-0 transition-all duration-300">SQL<span
                            class="text-cyan-400">IDE</span></span>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 py-6 space-y-2 px-3">
                <a href="dashboard" title="Dashboard"
                    class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors relative overflow-hidden whitespace-nowrap group <?= $currentPage == 'dashboard.php' ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                    <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="ml-3 nav-label opacity-0 w-0 transition-all duration-300">Dashboard</span>
                    <?php if ($currentPage == 'dashboard.php'): ?>
                        <div class="absolute left-0 top-2 bottom-2 w-1 bg-cyan-400 rounded-r-full"></div><?php endif; ?>
                </a>

                <a href="editor" title="SQL Editor"
                    class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors relative overflow-hidden whitespace-nowrap group <?= $currentPage == 'editor.php' ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                    <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <span class="ml-3 nav-label opacity-0 w-0 transition-all duration-300">SQL Editor</span>
                    <?php if ($currentPage == 'editor.php'): ?>
                        <div class="absolute left-0 top-2 bottom-2 w-1 bg-cyan-400 rounded-r-full"></div><?php endif; ?>
                </a>

                <a href="history" title="History"
                    class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors relative overflow-hidden whitespace-nowrap group <?= $currentPage == 'history.php' ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                    <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3 nav-label opacity-0 w-0 transition-all duration-300">History</span>
                    <?php if ($currentPage == 'history.php'): ?>
                        <div class="absolute left-0 top-2 bottom-2 w-1 bg-cyan-400 rounded-r-full"></div><?php endif; ?>
                </a>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/dashboard" title="Admin Panel"
                        class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors relative overflow-hidden whitespace-nowrap group <?= strpos($currentPage, 'admin') !== false ? 'bg-purple-500/10 text-purple-400' : 'text-purple-400/70 hover:bg-purple-500/10 hover:text-purple-400' ?>">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3 nav-label opacity-0 w-0 transition-all duration-300">Admin Panel</span>
                        <?php if (strpos($currentPage, 'admin') !== false): ?>
                            <div class="absolute left-0 top-2 bottom-2 w-1 bg-purple-400 rounded-r-full"></div><?php endif; ?>
                    </a>
                <?php endif; ?>
            </nav>

            <!-- User Info / Logout -->
            <div class="p-4 border-t border-white/5 overflow-hidden">
                <div class="flex items-center gap-3 mb-4 whitespace-nowrap">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-800 flex-shrink-0 flex items-center justify-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white nav-label opacity-0 w-0 transition-all duration-300">
                            <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></div>
                        <div
                            class="text-[10px] text-slate-500 font-mono nav-label opacity-0 w-0 transition-all duration-300">
                            <?= htmlspecialchars($_SESSION['db_name'] ?? '') ?></div>
                    </div>
                </div>
                <a href="logout" title="Sign Out"
                    class="flex items-center justify-center py-2 text-center text-xs text-red-400 hover:bg-red-500/10 border border-red-500/20 rounded transition overflow-hidden whitespace-nowrap">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="ml-2 nav-label opacity-0 w-0 transition-all duration-300">Sign Out</span>
                </a>
            </div>
        </aside>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('sidebar-toggle');
                const toggleIcon = document.getElementById('toggle-icon');
                const labels = document.querySelectorAll('.nav-label');

                // Load State (Default Minimized: true)
                // If 'sidebar-expanded' is set to 'true', we expand. Otherwise minimized.
                let isExpanded = localStorage.getItem('sidebar-expanded') === 'true';

                const updateSidebar = () => {
                    if (isExpanded) {
                        // Expand
                        sidebar.classList.remove('w-20');
                        sidebar.classList.add('w-64');
                        toggleIcon.classList.remove('rotate-180');

                        labels.forEach(l => {
                            l.classList.remove('opacity-0', 'w-0');
                            l.classList.add('opacity-100', 'w-auto');
                        });
                    } else {
                        // Minimize
                        sidebar.classList.remove('w-64');
                        sidebar.classList.add('w-20');
                        toggleIcon.classList.add('rotate-180');

                        labels.forEach(l => {
                            l.classList.remove('opacity-100', 'w-auto');
                            l.classList.add('opacity-0', 'w-0');
                        });
                    }
                };

                // Initial Render
                updateSidebar();

                // Toggle Handler
                toggleBtn.addEventListener('click', () => {
                    isExpanded = !isExpanded;
                    localStorage.setItem('sidebar-expanded', isExpanded);
                    updateSidebar();
                });
            });
        </script>
    <?php endif; ?>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="flex-1 flex flex-col relative overflow-hidden bg-[#0a0f1a]">
        <!-- Top Mobile Header (only visible on mobile) -->
        <?php if ($isAuth): ?>
            <div
                class="md:hidden h-16 border-b border-white/5 flex items-center justify-between px-4 bg-slate-900/50 backdrop-blur">
                <span class="font-bold tracking-tight">SQL<span class="text-cyan-400">IDE</span></span>
                <a href="logout" class="text-xs text-slate-400">Logout</a>
            </div>
        <?php endif; ?>

        <!-- Content Scroll Area -->
        <div class="flex-1 overflow-auto relative">
            <!-- Particles Background -->
            <div class="fixed inset-0 pointer-events-none -z-10">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-500/5 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-[100px]">
                </div>
            </div>