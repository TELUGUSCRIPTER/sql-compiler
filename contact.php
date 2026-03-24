<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | SQL Playground</title>
    <meta name="description" content="Get in touch with the SQL Playground team.">
    <!-- Ad Script -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { background-color: #0a0f1a; color: #fff; font-family: 'Space Grotesk', sans-serif; }</style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="flex flex-col min-h-screen">
    <nav class="border-b border-white/5 bg-black/20 backdrop-blur-sm">
        <div class="max-w-4xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="index.php" class="font-bold tracking-tight text-white">SQL<span class="text-cyan-400">Playground</span></a>
            <a href="index.php" class="text-sm text-slate-400 hover:text-white">Home</a>
        </div>
    </nav>

    <main class="flex-1 py-12 px-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 text-center">Contact Us</h1>
            
            <div class="bg-[#0f1623] border border-white/5 rounded-2xl p-8 shadow-xl">
                <p class="text-slate-400 mb-8 text-center">
                    Have questions, feedback, or need support? We're here to help. <br>
                    Fill out the form below or email us directly.
                </p>

                <div class="flex items-center justify-center gap-4 mb-8">
                     <a href="mailto:support@sqlcompiler.shop" class="flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 rounded-lg text-cyan-400 transition border border-cyan-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        support@sqlcompiler.shop
                     </a>
                </div>

                <form class="space-y-6" onsubmit="event.preventDefault(); alert('Thank you! We will get back to you shortly.');">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Name</label>
                        <input type="text" required class="w-full bg-black/20 border border-slate-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" required class="w-full bg-black/20 border border-slate-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Message</label>
                        <textarea rows="4" required class="w-full bg-black/20 border border-slate-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 rounded-lg transition">Send Message</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 border-t border-white/5 text-center text-slate-600 text-sm">
        &copy; <?= date('Y') ?> SQL Playground. <a href="privacy.php" class="hover:text-cyan-400">Privacy</a>
    </footer>
</body>
</html>
