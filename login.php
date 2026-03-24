<?php
// login.php
require_once __DIR__ . '/config/db_control.php';
require_once __DIR__ . '/config/auth_middleware.php';

if (isLoggedIn()) { header("Location: dashboard.php"); exit(); }

$error = '';
$msg = $_GET['msg'] ?? '';
if ($msg === 'pending_approval') {
    $error = "Account created. Waiting for Admin Approval.";
} elseif ($msg === 'session_expired') {
    $error = "Session expired. Please log in again.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Server-side email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif($email && $password) {
        // CSRF Check
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = "Session expired (CSRF). Please reload.";
        } else {
            $pdo = getControlDB();
            $stmt = $pdo->prepare("SELECT id, name, password_hash, role, status FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['status'] === 'banned') { $error = "Account banned."; }
                elseif ($user['status'] === 'pending') { $error = "Account pending approval. Contact Admin."; }
                else {
                    // Security: Prevent Session Fixation
                    session_regenerate_id(true);

                    $stmtDb = $pdo->prepare("SELECT db_name FROM user_databases WHERE user_id = ?");
                    $stmtDb->execute([$user['id']]);
                    $userDb = $stmtDb->fetch();
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['db_name'] = $userDb['db_name'] ?? null;
                    
                    // Track Last Login
                    $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
                    
                    header("Location: editor.php");
                    exit();
                }
            } else { $error = "Invalid credentials."; }
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
                Welcome to the <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Grid.</span>
            </h2>
            <p class="text-slate-500 max-w-sm mx-auto">Access your isolated database environment and start executing queries in milliseconds.</p>
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
                <h2 class="text-3xl font-bold tracking-tight">Sign In</h2>
                <p class="mt-2 text-sm text-slate-400">Continue your SQL journey</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-<?= strpos($error, 'approval') !== false ? 'blue' : 'red' ?>-500/10 border border-<?= strpos($error, 'approval') !== false ? 'blue' : 'red' ?>-500/20 text-<?= strpos($error, 'approval') !== false ? 'blue' : 'red' ?>-400 px-4 py-3 rounded-lg text-xs font-mono">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <?= csrfInput() ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email</label>
                        <div class="relative group">
                            <input type="email" name="email" required 
                                class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all peer">
                            <div class="absolute inset-0 rounded-lg bg-cyan-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" id="loginPassword" required 
                                class="w-full bg-[#0f1623] border border-slate-800 rounded-lg px-4 py-3 pr-12 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all peer">
                            <button type="button" onclick="togglePassword('loginPassword', 'loginEye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition-colors">
                                <svg id="loginEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <div class="absolute inset-0 rounded-lg bg-cyan-500/20 blur opacity-0 peer-focus:opacity-100 transition duration-500 -z-10"></div>
                        </div>
                        <div class="text-right mt-2">
                            <a href="forgot_password.php" class="text-xs text-cyan-400 hover:text-cyan-300 font-medium transition-colors hover:underline">
                                Forgot Password?
                            </a>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 rounded-lg transition-all hover:shadow-[0_0_20px_rgba(8,145,178,0.4)] hover:-translate-y-0.5 relative overflow-hidden group">
                    <span class="relative z-10">Authenticate</span>
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>

                <p class="text-center text-sm text-slate-500">
                    Not registered? <a href="register.php" class="text-cyan-400 hover:text-cyan-300 font-medium">Create ID</a>
                </p>
            </form>
        </div>
    </div>
</div>

<!-- SEO Content Block: Why create an account? -->
<section class="max-w-3xl mx-auto px-4 mt-16 mb-12 text-center">
    <h2 class="text-2xl font-bold text-white mb-4">Why Create a Free SQL Practice Account?</h2>
    <p class="text-slate-400 mb-8">Your <strong class="text-slate-200">free SQL practice login</strong> unlocks a smarter, more structured way to learn SQL online. Here is what you get when you sign in:</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left mb-10">
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <div class="text-2xl mb-3">💾</div>
            <h3 class="font-bold text-white mb-2">Save SQL Queries Online</h3>
            <p class="text-slate-400 text-sm">Every query you write can be saved to your personal library. Come back tomorrow and pick up exactly where you left off. No more losing work — your SQL stays safe in the cloud.</p>
        </div>
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <div class="text-2xl mb-3">📈</div>
            <h3 class="font-bold text-white mb-2">Track SQL Practice Progress</h3>
            <p class="text-slate-400 text-sm">Your practice history is automatically tracked. See which exercises you have completed, which topics need more work, and how your SQL skills have improved over time.</p>
        </div>
        <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-5">
            <div class="text-2xl mb-3">🎯</div>
            <h3 class="font-bold text-white mb-2">Structured Learning Path</h3>
            <p class="text-slate-400 text-sm">Access a curated SQL learning path — from <a href="/learn/sql-practice-beginner" class="text-cyan-400 hover:underline">beginner exercises</a> to <a href="/learn/sql-practice-advanced" class="text-cyan-400 hover:underline">advanced window functions</a>. Your progress syncs across all devices.</p>
        </div>
    </div>

    <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl p-6 text-left max-w-2xl mx-auto">
        <h3 class="font-bold text-white mb-3 text-lg">Who Should Create a Free SQL Account?</h3>
        <ul class="space-y-2 text-slate-400 text-sm">
            <li class="flex items-start gap-2"><span class="text-cyan-400 mt-0.5">✓</span> <span>Students preparing for data analytics or computer science exams</span></li>
            <li class="flex items-start gap-2"><span class="text-cyan-400 mt-0.5">✓</span> <span>Job seekers preparing for <a href="/learn/sql-interview-questions" class="text-cyan-400 hover:underline">SQL technical interviews</a> at tech companies</span></li>
            <li class="flex items-start gap-2"><span class="text-cyan-400 mt-0.5">✓</span> <span>Developers who want to save and organize their SQL query snippets</span></li>
            <li class="flex items-start gap-2"><span class="text-cyan-400 mt-0.5">✓</span> <span>Analysts who need a fast, reliable <strong class="text-slate-200">online SQL learning account</strong> without installing MySQL</span></li>
            <li class="flex items-start gap-2"><span class="text-cyan-400 mt-0.5">✓</span> <span>Anyone who wants to <a href="/learn/best-way-to-learn-sql" class="text-cyan-400 hover:underline">learn SQL fast</a> with structured, trackable practice</span></li>
        </ul>
    </div>

    <p class="text-slate-500 text-sm mt-6">Registration is 100% free. No credit card required. No spam.</p>
</section>

<script>
    // Force sidebar to be collapsed when starting a new session
    localStorage.removeItem('sidebar-expanded');
    
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
