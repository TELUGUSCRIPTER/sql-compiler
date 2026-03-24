<?php
// about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | SQL Playground</title>
    <meta name="description" content="Learn about the team behind SQL Playground and our mission to provide free, accessible database tools for everyone.">
    <!-- Ad Script -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { background-color: #0a0f1a; color: #fff; font-family: 'Space Grotesk', sans-serif; }</style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Simple Header -->
    <nav class="border-b border-white/5 bg-black/20 backdrop-blur-sm">
        <div class="max-w-4xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="index.php" class="font-bold tracking-tight text-white">SQL<span class="text-cyan-400">Playground</span></a>
            <a href="index.php" class="text-sm text-slate-400 hover:text-white">Home</a>
        </div>
    </nav>

    <main class="flex-1 py-12 px-6">
        <div class="max-w-3xl mx-auto prose prose-invert prose-lg">
            <h1 class="text-4xl font-bold mb-8">About SQLCompiler.shop</h1>
            
            <p class="text-slate-400 text-lg leading-relaxed">
                Welcome to <strong>sqlcompiler.shop</strong>, a dedicated educational platform designed to help students, developers, and data enthusiasts master Structured Query Language (SQL) in a safe, instant, and accessible environment. We believe that the best way to learn database management is by doing, not just reading.
            </p>

            <h2 class="text-2xl font-bold mt-10 mb-4 text-white">Our Mission</h2>
            <p class="text-slate-400">
                In the world of software development, setting up a local database environment can be a significant barrier to entry. Beginners often struggle with installation, configuration, and permission errors before they even write their first query.
            </p>
            <p class="text-slate-400">
                Our mission is to <strong>democratize database education</strong> by providing a zero-setup, cloud-based SQL internal environment. We aim to bridge the gap between theoretical knowledge and practical application, allowing learners to experiment with code immediately.
            </p>

            <h2 class="text-2xl font-bold mt-10 mb-4 text-white">Who This Tool Is For</h2>
            <ul class="list-disc pl-5 space-y-2 text-slate-400">
                <li><strong>Students & Learners:</strong> If you are taking a database course or learning on your own, our platform offers a hassle-free sandbox to test queries and understand results.</li>
                <li><strong>Interview Candidates:</strong> Technical interviews often require writing SQL on a whiteboard or a simple editor. Our compiler mimics these constraints while providing instant feedback, helping you prepare effectively.</li>
                <li><strong>Educators:</strong> Teachers can use our tool to demonstrate concepts live in the classroom without needing students to install heavy software on their personal laptops.</li>
                <li><strong>Developers:</strong> seasoned professionals can use our tool to quickly prototype schemas or test complex logic without spinning up a local instance.</li>
            </ul>

            <h2 class="text-2xl font-bold mt-10 mb-4 text-white">Educational Focus & Quality</h2>
            <p class="text-slate-400">
                We are committed to providing high-quality educational resources. Beyond the compiler, we offer a growing library of tutorials, interview questions, and concept guides. Our content is written by experienced developers who understand the common pitfalls and questions beginners face.
            </p>

            <h2 class="text-2xl font-bold mt-10 mb-4 text-white">Safety & Sandbox Disclaimer</h2>
            <div class="bg-cyan-900/10 border-l-4 border-cyan-500 p-4 rounded-r-lg">
                <p class="text-slate-300 font-medium">Educational Use Only</p>
                <p class="text-slate-400 text-sm mt-1">
                    Please note that this is a <strong>sandbox environment</strong> intended for educational and testing purposes only. 
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Do not store sensitive, personal, or production-critical data.</li>
                        <li>The database instances are ephemeral and meant for practice.</li>
                        <li>All code execution is isolated to preventing any impact on other users.</li>
                    </ul>
                </p>
            </div>

            <h2 class="text-2xl font-bold mt-10 mb-4 text-white">Contact Us</h2>
            <p class="text-slate-400">
                We value your feedback and suggestions. If you find a bug, have a feature request, or just want to say hello, please visit our <a href="contact.php" class="text-cyan-400 hover:underline">Contact Page</a> or email us directly at <a href="mailto:support@sqlcompiler.shop" class="text-cyan-400 hover:underline">support@sqlcompiler.shop</a>.
            </p>
        </div>
    </main>

    <!-- Ad Unit Bottom -->
    <div class="max-w-4xl mx-auto px-6 mb-12 text-center text-slate-600">
        <div class="bg-white/5 border border-white/5 p-8 rounded-lg">
            <!-- Ad Placeholder -->
            [AdSense Output Area]
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-8 border-t border-white/5 text-center text-slate-600 text-sm">
        &copy; <?= date('Y') ?> SQL Playground. <a href="privacy.php" class="text-slate-500 hover:text-cyan-400">Privacy</a> &bull; <a href="terms.php" class="text-slate-500 hover:text-cyan-400">Terms</a>
    </footer>
</body>
</html>
