<?php
// register.php
// Temporary: Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/db_control.php';
require_once __DIR__ . '/config/auth_middleware.php';
require_once __DIR__ . '/config/telegram_helper.php';
require_once __DIR__ . '/config/security_helpers.php';

// reCAPTCHA Configuration
define('RECAPTCHA_SITE_KEY', '6LcD5mcsAAAAABIqim8rYOr0uzWIopMDKXp8eGAk');
define('RECAPTCHA_SECRET_KEY', '6LcD5mcsAAAAALjFZ6mglau3B-In8GDDjrmlQQ5_');

// RATE LIMITING (Token Bucket / Fixed Window)
if (session_status() === PHP_SESSION_NONE) session_start();

$limit = 5; // 5 attempts
$window = 300; // per 5 minutes

if (!isset($_SESSION['reg_rate_limit'])) {
    $_SESSION['reg_rate_limit'] = ['count' => 0, 'start_time' => time()];
}

if (time() - $_SESSION['reg_rate_limit']['start_time'] > $window) {
    $_SESSION['reg_rate_limit'] = ['count' => 0, 'start_time' => time()];
}

if ($_SESSION['reg_rate_limit']['count'] >= $limit) {
    die("Too many registration attempts. Please wait 5 minutes.");
}

$_SESSION['reg_rate_limit']['count']++;

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $recaptchaToken = $_POST['recaptcha_token'] ?? '';
    $deviceFingerprint = $_POST['device_fingerprint'] ?? '';
    
    // Get user IP
    $userIP = getUserIP();
    
    // SECURITY CHECK 1: Honeypot field (bot detection)
    if (!empty($_POST['website'])) {
        // Bot detected - silently fail
        try {
            logRegistrationAttempt(getControlDB(), $userIP, $email, $deviceFingerprint, false, 'Honeypot triggered');
        } catch (\Exception $e) {
            error_log("Logging failed: " . $e->getMessage());
        }
        sleep(2); // Delay to waste bot's time
        $error = "Registration failed. Please try again.";
    }
    // SECURITY CHECK 2: CSRF Check
    elseif (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Form expired (CSRF). Please reload.";
    }
    // SECURITY CHECK 3: reCAPTCHA Verification
    elseif (empty($recaptchaToken)) {
        $error = "Security verification failed. Please refresh and try again.";
    }
    else {
        $captchaResult = verifyCaptcha($recaptchaToken, RECAPTCHA_SECRET_KEY);
        if (!$captchaResult['success']) {
            $score = $captchaResult['score'] ?? 0;
            try {
                logRegistrationAttempt(getControlDB(), $userIP, $email, $deviceFingerprint, false, "reCAPTCHA failed (score: $score)");
            } catch (\Exception $e) {
                error_log("Logging failed: " . $e->getMessage());
            }
            $error = "Security verification failed. Please try again. If you're human, contact support.";
        }
        
        // SECURITY CHECK 6: Disposable email detection
        if (empty($error) && isDisposableEmail($email)) {
            try {
                logRegistrationAttempt(getControlDB(), $userIP, $email, $deviceFingerprint, false, 'Disposable email detected');
            } catch (\Exception $e) {
                error_log("Logging failed: " . $e->getMessage());
            }
            $error = "Temporary email addresses are not allowed. Please use a permanent email address.";
        }
        

        
        // VALIDATION CHECK 8: Server-side email validation
        if (empty($error) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } 
        // VALIDATION CHECK 9: Password strength
        elseif (empty($error) && (strlen($password) < 8 || !preg_match('/[0-9!@#$%^&*]/', $password))) {
            $error = "Password must be 8+ chars and include a number or symbol.";
        } 
        
        if (empty($error)) {
            $pdo = getControlDB();
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Registration failed. Please try a different email or contact support.";
                try {
                    logRegistrationAttempt($pdo, $userIP, $email, $deviceFingerprint, false, 'Email already exists');
                } catch (\Exception $e) {
                    error_log("Logging failed: " . $e->getMessage());
                }
            } else {
                // Check Approval Setting (Safe Mode)
                $manualApproval = false;
                try {
                    $stmtSet = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'manual_approval'");
                    $stmtSet->execute();
                    $manualApproval = $stmtSet->fetchColumn() === '1';
                } catch (\Exception $e) {
                    // Table doesn't exist yet? Default to auto-approve.
                }
                $initialStatus = $manualApproval ? 'pending' : 'active';

                try {
                    // 1. Create User (Transaction for atomicity of user insert)
                    $pdo->beginTransaction();
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Check if new security columns exist
                    $hasSecurityColumns = false;
                    try {
                        $checkStmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'device_fingerprint'");
                        $hasSecurityColumns = $checkStmt->rowCount() > 0;
                    } catch (\Exception $e) {
                        // If check fails, assume columns don't exist
                        $hasSecurityColumns = false;
                    }
                    
                    // Insert user with or without security columns
                    if ($hasSecurityColumns) {
                        $stmt = $pdo->prepare("
                            INSERT INTO users 
                            (name, email, password_hash, status, registration_ip, device_fingerprint) 
                            VALUES (?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$name, $email, $hash, $initialStatus, $userIP, $deviceFingerprint]);
                    } else {
                        // Fallback for existing database schema
                        $stmt = $pdo->prepare("
                            INSERT INTO users 
                            (name, email, password_hash, status, registration_ip) 
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$name, $email, $hash, $initialStatus, $userIP]);
                    }
                    
                    $uid = $pdo->lastInsertId();
                    $pdo->commit(); // Commit user first
                    
                    // Log successful registration
                    try {
                        logRegistrationAttempt($pdo, $userIP, $email, $deviceFingerprint, true, null);
                    } catch (\Exception $e) {
                        // Table doesn't exist yet - skip logging
                        error_log("Registration logging skipped: " . $e->getMessage());
                    }

                    if (!$manualApproval) {
                         // Single DB Architecture: No checking/creating DB.
                         // Just link user to the main DB (conceptually).
                         // We still add a record to user_databases for compatibility 
                         // or to store the "virtual" db name if we want one.
                         // But for now, we just use the main DB.
                         
                         // Compatibility: Insert the main DB name so logic that checks user_databases still works
                         $dbName = DB_CONTROL_NAME; 
                         $stmt = $pdo->prepare("INSERT INTO user_databases (user_id, db_name) VALUES (?, ?)");
                         $stmt->execute([$uid, $dbName]);
                    }
                    
                    // Send Telegram Notification (Non-blocking - don't fail registration if it fails)
                    try {
                        sendTelegramRegistrationAlert([
                            'user_id' => $uid,
                            'name' => $name,
                            'email' => $email,
                            'status' => $initialStatus,
                            'ip' => $userIP,
                            'captcha_score' => $captchaResult['score'] ?? 'N/A'
                        ]);
                    } catch (Exception $e) {
                        // Silent fail - don't block registration if notification fails
                        error_log("Telegram notification failed: " . $e->getMessage());
                    }
                    
                    // Logic for Auto-Login or Pending Redirect
                    $redirectUrl = 'login.php';
                    $successMsg = 'Registration Successful!';
                    $subMsg = 'Redirecting to login...';

                    if ($manualApproval) {
                         $redirectUrl = 'login.php?msg=pending_approval';
                         $successMsg = 'Account Created (Pending Approval)';
                         $subMsg = 'Please wait for admin verification.';
                    } else {
                         // Auto-Login
                         // session_start(); // Handled by middleware
                         $_SESSION['user_id'] = $uid;
                         $_SESSION['name'] = $name;
                         $_SESSION['role'] = 'user';
                         $_SESSION['db_name'] = $dbName;
                         // Update Last Login
                         $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$uid]);
                         
                         $redirectUrl = 'editor.php';
                         $subMsg = 'Redirecting to your dashboard...';
                    }
                    ?>
                <!DOCTYPE html>
                <html lang="en" class="h-full">
                <head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Registration Successful - SQL Compiler</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <style>
                        @keyframes progress {
                            0% { width: 0%; }
                            100% { width: 100%; }
                        }
                        body {
                            background: linear-gradient(135deg, #0a0f1a 0%, #1a1f2e 100%);
                        }
                    </style>
                <script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
                <body class="h-full flex items-center justify-center">
                    <div class="text-center space-y-6 animate-[fadeIn_0.5s_ease-in]">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-xl shadow-green-500/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($successMsg) ?></h1>
                        <p class="text-slate-400"><?= htmlspecialchars($subMsg) ?></p>
                        <div class="w-64 h-1 bg-gray-800 rounded mx-auto mt-6 overflow-hidden">
                            <div class="h-full bg-purple-500 animate-[progress_2s_ease-in-out_forwards]" style="width: 0%"></div>
                        </div>
                    </div>
                    <script>
                        setTimeout(() => {
                            localStorage.removeItem('sidebar-expanded'); // Reset sidebar
                            window.location.href = '<?= $redirectUrl ?>';
                        }, 2000);
                    </script>
                </body>
                </html>
                <?php
                exit();
            } catch (\Exception $e) {
                // Only rollback if we are actually in a transaction (failed step 1)
                if ($pdo->inTransaction()) { $pdo->rollBack(); }
                $error = "System Error: " . $e->getMessage();
            }
        }
    }
    }
}
require_once 'includes/header.php';
?>

<div class="min-h-screen grid lg:grid-cols-2">
    <!-- Left Side: Visuals -->
    <div class="hidden lg:flex flex-col justify-center items-center relative overflow-hidden bg-[#050a14]">
        <div class="absolute inset-0 bg-grid opacity-30"></div>
        <div class="absolute w-96 h-96 bg-purple-500/10 rounded-full blur-[100px] top-1/2 right-1/2 transform translate-x-1/2 -translate-y-1/2 animate-pulse-slow"></div>
        
        <div class="relative z-10 text-center space-y-4">
            <div class="w-16 h-16 bg-gradient-to-tr from-purple-500 to-indigo-600 rounded-2xl mx-auto flex items-center justify-center mb-6 shadow-2xl shadow-purple-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <h2 class="text-4xl font-bold font-sans">
                Join the <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">Network.</span>
            </h2>
            <p class="text-slate-500 max-w-sm mx-auto">Instant provisioning. Zero setup. Start building your schema in seconds.</p>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="flex items-center justify-center p-8 bg-[#0a0f1a] relative">
        <a href="index.php" class="absolute top-8 left-8 text-sm text-slate-500 hover:text-white transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Home
        </a>

        <div class="w-full max-w-sm space-y-8 glass-nav p-8 rounded-2xl border border-white/5">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight">Create ID</h2>
                <p class="mt-2 text-sm text-slate-400">Initialize your environment</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs font-mono">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" id="registration_form">
                <?= csrfInput() ?>
                
                <!-- Honeypot field (hidden from users, bots will fill it) -->
                <input type="text" name="website" style="display:none !important; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off" aria-hidden="true">
                
                <!-- Hidden fields for security data -->
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                <input type="hidden" name="device_fingerprint" id="device_fingerprint">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Display Name</label>
                        <div class="relative group">
                            <input type="text" name="name" required 
                                class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all peer">
                            <div class="absolute inset-0 rounded-lg bg-purple-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email</label>
                        <div class="relative group">
                            <input type="email" name="email" required 
                                class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all peer">
                            <div class="absolute inset-0 rounded-lg bg-purple-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" id="password_input" required 
                                class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 pr-12 text-white placeholder-slate-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all peer">
                            <!-- Eye Icon Toggle -->
                            <button type="button" id="toggle_password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors p-1" aria-label="Toggle password visibility">
                                <!-- Eye Icon (Hidden state) -->
                                <svg id="eye_icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye Slash Icon (Visible state) -->
                                <svg id="eye_slash_icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                            <div class="absolute inset-0 rounded-lg bg-purple-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                        </div>
                        <!-- Strength Meter -->
                        <div class="mt-2 h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div id="strength-bar" class="h-full w-0 transition-all duration-300 ease-out bg-red-500"></div>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-[10px] text-slate-500">Min 8 chars + Number/Symbol</p>
                            <p id="strength-text" class="text-[10px] font-bold text-slate-500">Weak</p>
                        </div>
                    </div>
                </div>

                <script>
                    const pwdInput = document.getElementById('password_input');
                    const bar = document.getElementById('strength-bar');
                    const txt = document.getElementById('strength-text');

                    pwdInput.addEventListener('input', function() {
                        const val = this.value;
                        let strength = 0;
                        
                        if (val.length >= 8) strength += 1; // Length
                        if (/[0-9]/.test(val)) strength += 1; // Number
                        if (/[^A-Za-z0-9]/.test(val)) strength += 1; // Special

                        // Logic: 
                        // 0-1 pts: Weak (Red)
                        // 2 pts: Medium (Yellow)
                        // 3 pts: Strong (Green)
                        
                        bar.className = 'h-full transition-all duration-300 ease-out';
                        if (val.length === 0) {
                            bar.style.width = '0%';
                            txt.textContent = '';
                        } else if (strength < 2) {
                            bar.style.width = '33%';
                            bar.classList.add('bg-red-500');
                            txt.textContent = 'Weak';
                            txt.className = 'text-[10px] font-bold text-red-500';
                        } else if (strength === 2) {
                            bar.style.width = '66%';
                            bar.classList.add('bg-yellow-500');
                            txt.textContent = 'Medium';
                            txt.className = 'text-[10px] font-bold text-yellow-500';
                        } else {
                            bar.style.width = '100%';
                            bar.classList.add('bg-green-500');
                            txt.textContent = 'Strong';
                            txt.className = 'text-[10px] font-bold text-green-500';
                        }
                    });

                    // Password Visibility Toggle
                    const toggleBtn = document.getElementById('toggle_password');
                    const eyeIcon = document.getElementById('eye_icon');
                    const eyeSlashIcon = document.getElementById('eye_slash_icon');
                    
                    toggleBtn.addEventListener('click', function() {
                        const type = pwdInput.getAttribute('type');
                        if (type === 'password') {
                            pwdInput.setAttribute('type', 'text');
                            eyeIcon.classList.add('hidden');
                            eyeSlashIcon.classList.remove('hidden');
                        } else {
                            pwdInput.setAttribute('type', 'password');
                            eyeIcon.classList.remove('hidden');
                            eyeSlashIcon.classList.add('hidden');
                        }
                    });
                </script>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 rounded-lg transition-all hover:shadow-[0_0_20px_rgba(147,51,234,0.4)] hover:-translate-y-0.5 relative overflow-hidden group">
                    <span class="relative z-10">Provision Database</span>
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>

                <p class="text-center text-sm text-slate-500">
                    Already initialized? <a href="login.php" class="text-purple-400 hover:text-purple-300 font-medium">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</div>

<!-- SEO Content Block: Create a Free SQL Account -->
<section class="max-w-3xl mx-auto px-4 mt-16 mb-12 text-center">
    <h2 class="text-2xl font-bold text-white mb-4">Create Your Free SQL Practice Account</h2>
    <p class="text-slate-400 mb-8">Your <strong class="text-slate-200">free SQL practice account</strong> on SQLCompiler.shop gives you everything you need to go from beginner to interview-ready — all in one place, with no installation required.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left mb-10">
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <h3 class="font-bold text-white mb-3 text-base flex items-center gap-2"><span class="text-2xl">💾</span> Save SQL Queries Online</h3>
            <p class="text-slate-400 text-sm">Every query you write is automatically saved to your personal library. Build a collection of reusable SQL snippets that follows you across sessions and devices.</p>
        </div>
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <h3 class="font-bold text-white mb-3 text-base flex items-center gap-2"><span class="text-2xl">📊</span> Track SQL Practice Progress</h3>
            <p class="text-slate-400 text-sm">Your practice history is automatically tracked. Review completed exercises, see improvement over time, and know exactly where to focus next on your SQL learning journey.</p>
        </div>
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <h3 class="font-bold text-white mb-3 text-base flex items-center gap-2"><span class="text-2xl">🎯</span> Structured Learning Path</h3>
            <p class="text-slate-400 text-sm">Follow our curated path from <a href="/learn/sql-practice-beginner" class="text-purple-400 hover:underline">beginner SQL exercises</a> through <a href="/learn/sql-practice-intermediate" class="text-purple-400 hover:underline">intermediate JOINs</a> to <a href="/learn/sql-practice-advanced" class="text-purple-400 hover:underline">advanced window functions and CTEs</a>.</p>
        </div>
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <h3 class="font-bold text-white mb-3 text-base flex items-center gap-2"><span class="text-2xl">🚀</span> Interview Preparation</h3>
            <p class="text-slate-400 text-sm">Access our curated <a href="/learn/sql-interview-questions" class="text-purple-400 hover:underline">SQL interview questions</a> and <a href="/learn/how-to-prepare-sql-interviews" class="text-purple-400 hover:underline">interview prep guide</a>. Practice under timed conditions to simulate real job interviews at top tech companies.</p>
        </div>
    </div>

    <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl p-6 text-left max-w-2xl mx-auto mb-6">
        <h3 class="font-bold text-white mb-3">Everything is 100% Free</h3>
        <ul class="space-y-2 text-slate-400 text-sm">
            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Free SQL compiler — no installation, runs in your browser</li>
            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Unlimited query executions — practice as much as you want</li>
            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Full access to all practice exercises (beginner to advanced)</li>
            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> SQL interview questions and prep guides included</li>
            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> No credit card. No spam. Cancel anytime.</li>
        </ul>
    </div>

    <p class="text-slate-500 text-sm">Already have an account? <a href="login.php" class="text-purple-400 hover:underline">Sign in here</a>.</p>
</section>

<script>
    // Force sidebar to be collapsed when starting registration
    localStorage.removeItem('sidebar-expanded');
</script>

<!-- Google reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>"></script>

<script>
    // ===================================
    // DEVICE FINGERPRINTING
    // ===================================
    function generateDeviceFingerprint() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        ctx.textBaseline = 'top';
        ctx.font = '14px Arial';
        ctx.fillText('fingerprint', 2, 2);
        const canvasData = canvas.toDataURL();
        
        const fingerprint = {
            userAgent: navigator.userAgent,
            language: navigator.language,
            languages: navigator.languages ? navigator.languages.join(',') : '',
            platform: navigator.platform,
            hardwareConcurrency: navigator.hardwareConcurrency || 0,
            deviceMemory: navigator.deviceMemory || 0,
            screenResolution: `${screen.width}x${screen.height}`,
            colorDepth: screen.colorDepth,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timezoneOffset: new Date().getTimezoneOffset(),
            canvas: canvasData.substring(0, 100), // First 100 chars of canvas fingerprint
            touchSupport: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
            webGL: getWebGLFingerprint()
        };
        
        // Create a hash-like string from the fingerprint
        const fpString = JSON.stringify(fingerprint);
        return btoa(fpString).substring(0, 255); // Base64 encode and limit to 255 chars
    }
    
    function getWebGLFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            if (!gl) return 'unsupported';
            
            const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            if (!debugInfo) return 'no-debug-info';
            
            return gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL).substring(0, 50);
        } catch (e) {
            return 'error';
        }
    }
    
    // Set device fingerprint on page load
    document.addEventListener('DOMContentLoaded', function() {
        const deviceFp = generateDeviceFingerprint();
        document.getElementById('device_fingerprint').value = deviceFp;
    });
    
    // ===================================
    // reCAPTCHA v3 INTEGRATION
    // ===================================
    const form = document.getElementById('registration_form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default submission
        
        // Execute reCAPTCHA
        grecaptcha.ready(function() {
            grecaptcha.execute('<?= RECAPTCHA_SITE_KEY ?>', {action: 'register'})
                .then(function(token) {
                    // Set the token in hidden field
                    document.getElementById('recaptcha_token').value = token;
                    
                    // Submit the form
                    form.submit();
                })
                .catch(function(error) {
                    console.error('reCAPTCHA error:', error);
                    alert('Security verification failed. Please refresh the page and try again.');
                });
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
