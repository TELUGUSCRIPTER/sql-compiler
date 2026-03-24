<?php
// reset_password.php
require_once __DIR__ . '/config/db_control.php';
require_once __DIR__ . '/config/csrf.php';

$error = '';
$success = false;
$validToken = false;

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

// Validate token on page load
if ($token && $email) {
    try {
        $pdo = getControlDB();
        $tokenHash = hash('sha256', $token);
        
        // Fetch user with token (don't check expiry in SQL due to timezone issues)
        $stmt = $pdo->prepare(
            "SELECT id, name, reset_token_expires_at FROM users 
             WHERE email = ? 
             AND reset_token_hash = ?"
        );
        $stmt->execute([$email, $tokenHash]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Check expiry in PHP to avoid timezone issues
            $expiresAt = strtotime($user['reset_token_expires_at']);
            $now = time();
            
            if ($now > $expiresAt) {
                $error = "This reset link has expired. Please request a new one.";
            } else {
                $validToken = true;
            }
        } else {
            $error = "Invalid or expired reset link. Please request a new one.";
        }
    } catch (Exception $e) {
        error_log("Token validation error: " . $e->getMessage());
        $error = "An error occurred. Please try again.";
    }
} else {
    $error = "Invalid reset link.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Session expired (CSRF). Please reload.";
    } elseif (strlen($newPassword) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        try {
            $pdo = getControlDB();
            $tokenHash = hash('sha256', $token);
            
            // Verify token again before update (without NOW() to avoid timezone issues)
            $stmt = $pdo->prepare(
                "SELECT id, reset_token_expires_at FROM users 
                 WHERE email = ? 
                 AND reset_token_hash = ?"
            );
            $stmt->execute([$email, $tokenHash]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Check expiry in PHP
                $expiresAt = strtotime($user['reset_token_expires_at']);
                $now = time();
                
                if ($now > $expiresAt) {
                    $error = "This reset link has expired.";
                    $validToken = false;
                } else {
                    // Update password and invalidate token
                    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare(
                        "UPDATE users 
                         SET password_hash = ?, 
                             reset_token_hash = NULL, 
                             reset_token_expires_at = NULL 
                         WHERE id = ?"
                    );
                    $updateStmt->execute([$passwordHash, $user['id']]);
                    
                    $success = true;
                    $validToken = false;
                }
            } else {
                $error = "Invalid or expired reset link.";
                $validToken = false;
            }
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            $error = "An error occurred. Please try again.";
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <h2 class="text-4xl font-bold font-sans">
                Secure Your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Account.</span>
            </h2>
            <p class="text-slate-500 max-w-sm mx-auto">Choose a strong password to protect your database environment.</p>
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
                <h2 class="text-3xl font-bold tracking-tight">Reset Password</h2>
                <p class="mt-2 text-sm text-slate-400">Enter your new password</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-sm">
                    <div class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                    <?php if (!$validToken && !$success): ?>
                    <div class="mt-3 pt-3 border-t border-red-500/20">
                        <a href="forgot_password.php" class="text-cyan-400 hover:text-cyan-300 text-sm font-medium">Request a new reset link</a>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg text-sm space-y-4">
                    <div class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="font-semibold">Password reset successful!</p>
                            <p class="text-green-300/80 text-xs mt-1">You can now sign in with your new password.</p>
                        </div>
                    </div>
                    <div class="pt-3">
                        <a href="login.php" class="block w-full bg-cyan-600 hover:bg-cyan-500 text-white text-center font-bold py-3 rounded-lg transition">
                            Continue to Login
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($validToken && !$success): ?>
            <form method="POST" class="space-y-6">
                <?= csrfInput() ?>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">New Password</label>
                    <div class="relative group">
                        <input type="password" name="password" id="newPassword" required 
                            minlength="8"
                            placeholder="Min. 8 characters"
                            class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 pr-12 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all peer">
                        <button type="button" onclick="togglePassword('newPassword', 'newPasswordEye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition-colors">
                            <svg id="newPasswordEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <div class="absolute inset-0 rounded-lg bg-cyan-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-1.5">Use at least 8 characters</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Confirm Password</label>
                    <div class="relative group">
                        <input type="password" name="confirm_password" id="confirmPassword" required 
                            minlength="8"
                            placeholder="Re-enter password"
                            class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 pr-12 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all peer">
                        <button type="button" onclick="togglePassword('confirmPassword', 'confirmPasswordEye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition-colors">
                            <svg id="confirmPasswordEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <div class="absolute inset-0 rounded-lg bg-cyan-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold py-3 rounded-lg transition-all hover:shadow-[0_0_30px_rgba(8,145,178,0.5)] hover:-translate-y-0.5 relative overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Reset Password
                    </span>
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
