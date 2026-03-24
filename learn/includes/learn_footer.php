    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <!-- About -->
                <div class="md:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">SQL Compiler</h3>
                    <p class="text-gray-400 text-sm mb-3">
                        Free online SQL compiler and practice tool. Learn SQL, run queries, and prepare for interviews — no installation required.
                    </p>
                    <a href="/" class="inline-block text-purple-400 text-sm hover:text-purple-300 font-medium">← Back to Compiler</a>
                </div>

                <!-- Practice SQL -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Practice SQL</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/learn/sql-practice-beginner" class="text-gray-400 hover:text-white transition">⭐ Beginner Exercises</a></li>
                        <li><a href="/learn/sql-practice-intermediate" class="text-gray-400 hover:text-white transition">⭐⭐ Intermediate Exercises</a></li>
                        <li><a href="/learn/sql-practice-advanced" class="text-gray-400 hover:text-white transition">⭐⭐⭐ Advanced Exercises</a></li>
                        <li><a href="/learn/sql-interview-questions" class="text-gray-400 hover:text-white transition">SQL Interview Questions</a></li>
                        <li><a href="/learn/top-50-sql-queries" class="text-gray-400 hover:text-white transition">Top 50 SQL Queries</a></li>
                    </ul>
                </div>

                <!-- Learn SQL -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Learn SQL</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/learn/sql-basics-tutorial" class="text-gray-400 hover:text-white transition">SQL Basics Tutorial</a></li>
                        <li><a href="/learn/sql-joins-tutorial" class="text-gray-400 hover:text-white transition">SQL JOINs (In-Depth)</a></li>
                        <li><a href="/learn/sql-functions" class="text-gray-400 hover:text-white transition">SQL Functions</a></li>
                        <li><a href="/learn/sql-cheat-sheet" class="text-gray-400 hover:text-white transition">SQL Cheat Sheet</a></li>
                        <li><a href="/learn/sql-subqueries" class="text-gray-400 hover:text-white transition">SQL Subqueries</a></li>
                    </ul>
                </div>

                <!-- Guides & Blogs -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Guides &amp; Tips</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/learn/how-to-prepare-sql-interviews" class="text-gray-400 hover:text-white transition">SQL Interview Prep Guide</a></li>
                        <li><a href="/learn/best-way-to-learn-sql" class="text-gray-400 hover:text-white transition">Best Way to Learn SQL</a></li>
                        <li><a href="/learn/common-sql-mistakes" class="text-gray-400 hover:text-white transition">Common SQL Mistakes</a></li>
                        <li><a href="/learn/sql-best-practices" class="text-gray-400 hover:text-white transition">SQL Best Practices</a></li>
                        <li><a href="/learn/sql-vs-mysql-vs-postgresql" class="text-gray-400 hover:text-white transition">SQL vs MySQL vs PostgreSQL</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/about" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="/faq" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                        <li><a href="/privacy" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms" class="text-gray-400 hover:text-white transition">Terms of Service</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
                    <p>&copy; <?= date('Y') ?> SQLCompiler.shop. All rights reserved.</p>
                    <p>Free online SQL compiler &bull; SQL practice questions &bull; SQL interview prep</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
