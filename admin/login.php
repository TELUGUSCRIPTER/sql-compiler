<?php
// admin/login.php
session_start();
require_once __DIR__ . '/../config/db_control.php';

require_once __DIR__ . '/../config/csrf.php'; // CSRF Protection

// BRUTE FORCE PROTECTION with Exponential Backoff
$limit = 3; // Attempts allowed before lockout
$baseWindow = 300; // Base window: 5 minutes

if (!isset($_SESSION['admin_auth_limit'])) {
    $_SESSION['admin_auth_limit'] = ['count' => 0, 'start_time' => time(), 'lockout_until' => 0];
}

// Check if currently locked out
if ($_SESSION['admin_auth_limit']['lockout_until'] > time()) {
    $remainingTime = ceil(($_SESSION['admin_auth_limit']['lockout_until'] - time()) / 60);
    die("Access blocked. Too many failed attempts. Try again in {$remainingTime} minute(s).");
}

// Reset counter if base window expired
if (time() - $_SESSION['admin_auth_limit']['start_time'] > $baseWindow) {
    $_SESSION['admin_auth_limit'] = ['count' => 0, 'start_time' => time(), 'lockout_until' => 0];
}

// Redirect if already admin
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // CSRF Check
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Session expired or invalid CSRF token. Please reload.";
    } elseif($email && $password) {
        $pdo = getControlDB();
        $stmt = $pdo->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash']) && $user['role'] === 'admin') {
            // Security: Prevent Session Fixation
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['db_name'] = 'control_db'; 
            header("Location: dashboard.php");
            exit();
        } else { 
            $_SESSION['admin_auth_limit']['count']++;
            
            // Exponential backoff: 5min, 15min, 1hr, 24hr
            if ($_SESSION['admin_auth_limit']['count'] >= $limit) {
                $lockoutMultiplier = pow(3, $_SESSION['admin_auth_limit']['count'] - $limit);
                $lockoutDuration = min($baseWindow * $lockoutMultiplier, 86400); // Max 24 hours
                $_SESSION['admin_auth_limit']['lockout_until'] = time() + $lockoutDuration;
            }
            
            $error = "Access Denied: Invalid credentials or insufficient privileges."; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | SQL Playground</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <!-- Ad Script -->

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'], },
                    colors: { cyber: { black: '#0a0f1a', accent: '#8b5cf6', accentGlow: '#a78bfa' } },
                    animation: {
                        'pulse-glow': 'pulseGlow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'scale-in': 'scaleIn 0.3s ease-out forwards',
                        'spin-slow': 'spin 3s linear infinite',
                    },
                    keyframes: {
                        pulseGlow: { '0%, 100%': { opacity: '1', boxShadow: '0 0 20px #8b5cf6' }, '50%': { opacity: '.7', boxShadow: '0 0 10px #8b5cf6' } },
                        scaleIn: { 'from': { opacity: '0', transform: 'scale(0.95)' }, 'to': { opacity: '1', transform: 'scale(1)' } }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0a0f1a; color: #fff; }
        .glass-card-accent {
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(139, 92, 246, 0.2);
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.1);
        }
        .bg-grid {
            background-image: radial-gradient(rgba(139, 92, 246, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full flex items-center justify-center relative overflow-hidden bg-grid">

    <!-- Ambient Background -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[120px] -z-10 animate-pulse-glow"></div>
    
    <!-- Particles (CSS Sim) -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-purple-400 rounded-full animate-bounce"></div>
        <div class="absolute bottom-1/3 right-1/4 w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-duration:3s"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md p-8 animate-scale-in">
        <div class="glass-card-accent rounded-2xl p-8 relative overflow-hidden">
            
            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="w-14 h-14 mx-auto bg-gradient-to-tr from-purple-600 to-indigo-600 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-purple-500/30">
                    <i data-lucide="shield" class="w-7 h-7 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Admin Portal</h1>
                <p class="text-sm text-slate-400 mt-1">Restricted access area</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs font-mono mb-6 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" class="space-y-6 relative z-10" onsubmit="document.getElementById('loader').classList.remove('hidden');">
                <?= csrfInput() ?>
                <div class="space-y-4">
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-purple-400 transition">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" name="email" required placeholder="Administrator Email"
                            class="w-full bg-[#0a0f1a]/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition placeholder-slate-600 backdrop-blur-sm">
                    </div>

                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-purple-400 transition">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="Secure Password"
                            class="w-full bg-[#0a0f1a]/50 border border-white/10 rounded-xl py-3 pl-12 pr-12 text-sm focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition placeholder-slate-600 backdrop-blur-sm">
                        <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="group w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Access Admin Panel</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    <div id="loader" class="hidden w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="../login.php" class="text-xs text-slate-500 hover:text-white transition flex items-center justify-center gap-1 group">
                    <i data-lucide="arrow-left" class="w-3 h-3 group-hover:-translate-x-1 transition-transform"></i>
                    Back to main site
                </a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
