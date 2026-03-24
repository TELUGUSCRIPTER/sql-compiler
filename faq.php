<?php
$pageTitle = 'Frequently Asked Questions (FAQ) - SQL Compiler';
$metaDescription = 'Find answers to common questions about SQL Compiler. Learn how to use our online SQL editor, database features, account management, and troubleshooting tips.';
$metaKeywords = 'SQL compiler FAQ, SQL questions, online SQL help, SQL compiler support';
$canonicalUrl = 'https://sqlcompiler.shop/faq';
$breadcrumbs = [
    ['title' => 'FAQ']
];

require_once __DIR__ . '/learn/includes/learn_header.php';
require_once __DIR__ . '/learn/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            Find answers to the most common questions about SQL Compiler, our online SQL editor, and learning resources.
        </p>

        <h2>Getting Started</h2>

        <h3>What is SQL Compiler?</h3>
        <p>
            SQL Compiler is a free, online SQL editor and learning platform that allows you to write, execute, and learn SQL directly in your web browser. No installation required - just open your browser and start coding!
        </p>

        <h3>Do I need to install anything?</h3>
        <p>
            No! SQL Compiler runs entirely in your browser. You don't need to install MySQL, PostgreSQL, or any database software. Just create an account and start writing SQL queries immediately.
        </p>

        <h3>Is SQL Compiler really free?</h3>
        <p>
            Yes! SQL Compiler is completely free to use. We provide free database hosting, query execution, and access to all our learning tutorials. We're supported by ads and donations from users who find our service valuable.
        </p>

        <h3>What SQL database does SQL Compiler use?</h3>
        <p>
            We use MySQL as our primary database engine. MySQL is one of the most popular open-source relational database systems and is widely used in web development and data-driven applications.
        </p>

        <h2>Account & Registration</h2>

        <h3>How do I create an account?</h3>
        <p>
            Click the "Sign Up" or "Register" button on the homepage. You'll need to provide your name, email address, and password. We also use Google reCAPTCHA to prevent spam registrations and ensure platform security.
        </p>

        <h3>Is my data safe?</h3>
        <p>
            Yes. We take security seriously:
        </p>
        <ul>
            <li>Passwords are hashed using bcrypt</li>
            <li>Each user gets an isolated database</li>
            <li>We don't share your personal information</li>
            <li>SSL/TLS encryption for all connections</li>
            <li>IP-based rate limiting to prevent abuse</li>
        </ul>

        <h3>Can I reset my password?</h3>
        <p>
            Yes. Click "Forgot Password" on the login page, enter your email, and we'll send you a password reset link.
        </p>

        <h3>Can I delete my account?</h3>
        <p>
            Yes. Contact us at support@sqlcompiler.shop to request account deletion. This will permanently delete your account and all associated data.
        </p>

        <h2>Using the SQL Compiler</h2>

        <h3>How do I execute a query?</h3>
        <p>
            Type your SQL query in the editor and click the "Run Query" button or press Ctrl+Enter (Cmd+Enter on Mac). Results will appear below the editor.
        </p>

        <h3>Can I save my queries?</h3>
        <p>
            Yes! All your queries are automatically saved in your personal database. You can access your query history from your dashboard.
        </p>

        <h3>What SQL commands are supported?</h3>
        <p>
            We support all standard MySQL commands including:
        </p>
        <ul>
            <li>Data Query Language (DQL): SELECT</li>
            <li>Data Manipulation Language (DML): INSERT, UPDATE, DELETE</li>
            <li>Data Definition Language (DDL): CREATE, ALTER, DROP</li>
            <li>Joins, Subqueries, Aggregate Functions</li>
            <li>Views, Indexes, and Triggers</li>
        </ul>

        <h3>Are there any query limitations?</h3>
        <p>
            To ensure fair usage for all users:
        </p>
        <ul>
            <li>Query execution timeout: 10 seconds</li>
            <li>Maximum result rows: 1000</li>
            <li>Database size limit: 50MB per user</li>
            <li>Rate limiting: Maximum 100 queries per minute</li>
        </ul>

        <h3>Can I upload my own database?</h3>
        <p>
            Currently, we don't support direct database uploads. However, you can copy and paste CREATE TABLE and INSERT statements to recreate your database structure and data.
        </p>

        <h2>Learning SQL</h2>

        <h3>I'm a complete beginner. Where should I start?</h3>
        <p>
            Start with our <a href="/learn/sql-basics-tutorial" class="text-purple-600 hover:text-purple-800 font-medium">SQL Basics Tutorial</a>. It covers all the fundamentals in an easy-to-follow format with examples you can try immediately in the compiler.
        </p>

        <h3>Do you offer practice exercises?</h3>
        <p>
            Yes! Check out our <a href="/learn/sql-practice-exercises" class="text-purple-600 hover:text-purple-800 font-medium">50 SQL Practice Exercises</a> with solutions. They range from beginner to advanced levels.
        </p>

        <h3>Are there video tutorials?</h3>
        <p>
            Currently, we offer text-based tutorials with code examples. Video tutorials are planned  for the future!
        </p>

        <h3>Can I get a certificate?</h3>
        <p>
            Certificate programs are on our roadmap! For now, focus on building real skills by practicing  with our compiler and completing exercises.
        </p>

        <h2>Troubleshooting</h2>

        <h3>My query is returning an error. What should I do?</h3>
        <p>
            Common solutions:
        </p>
        <ul>
            <li>Check for syntax errors (missing semicolons, quotes, etc.)</li>
            <li>Ensure table and column names are spelled correctly</li>
            <li>Verify the table exists (use SHOW TABLES;)</li>
            <li>Check our <a href="/learn/common-sql-mistakes" class="text-purple-600 hover:text-purple-800 font-medium">Common SQL Mistakes</a> guide</li>
        </ul>

        <h3>The compiler is slow. What can I do?</h3>
        <p>
            Performance tips:
        </p>
        <ul>
            <li>Add indexes to frequently queried columns</li>
            <li>Use WHERE clauses to filter data early</li>
            <li>Avoid SELECT * when you only need specific columns</li>
            <li>Limit result size with LIMIT clause</li>
        </ul>

        <h3>I found a bug. How do I report it?</h3>
        <p>
            Please email us at bugs@sqlcompiler.shop with:
        </p>
        <ul>
            <li>Description of the issue</li>
            <li>Steps to reproduce</li>
            <li>Your browser and operating system</li>
            <li>Any error messages</li>
        </ul>

        <h2>Features & Functionality</h2>

        <h3>Can I share my queries with others?</h3>
        <p>
            Query sharing is currently in development! Soon you'll be able to generate shareable links for your SQL queries.
        </p>

        <h3>Is there a mobile app?</h3>
        <p>
            Not yet, but our website is fully mobile-responsive. You can use SQL Compiler on your phone or tablet through any modern web browser.
        </p>

        <h3>Can I collaborate with team members?</h3>
        <p>
            Team collaboration features are planned for future releases. Currently, each account has an individual database.
        </p>

        <h3>Do you offer an API?</h3>
        <p>
            API access is not currently available but is on our roadmap for pro users.
        </p>

        <h2>Privacy & Security</h2>

        <h3>What data do you collect?</h3>
        <p>
            We collect:
        </p>
        <ul>
            <li>Account information (name, email)</li>
            <li>Query history and execution logs</li>
            <li>IP addresses for security purposes</li>
            <li>Usage analytics to improve our service</li>
        </ul>
        <p>
            See our <a href="/privacy.php" class="text-purple-600 hover:text-purple-800 font-medium">Privacy Policy</a> for complete details.
        </p>

        <h3>Do you sell my data?</h3>
        <p>
            Never. We do not sell, rent, or share your personal information with third parties except as required by law.
        </p>

        <h3>How is my database secured?</h3>
        <p>
            Each user gets an isolated MySQL database that only they can access. Databases are:
        </p>
        <ul>
            <li>Isolated from other users</li>
            <li>Protected by username-based access control</li>
            <li>Backed up daily</li>
            <li>Encrypted in transit</li>
        </ul>

        <h2>Billing & Subscriptions</h2>

        <h3>Will SQL Compiler always be free?</h3>
        <p>
            Our core features will always remain free. We may introduce optional premium features in the future for advanced users, but the basic compiler and learning resources will stay free forever.
        </p>

        <h3>How do you make money?</h3>
        <p>
            We're currently supported by:
        </p>
        <ul>
            <li>Display advertising</li>
            <li>Optional donations from users</li>
            <li>Future premium features (planned)</li>
        </ul>

        <h2>Contact & Support</h2>

        <h3>How can I contact support?</h3>
        <p>
            Email us at: support@sqlcompiler.shop
        </p>
        <p>
            We typically respond within 24-48 hours.
        </p>

        <h3>Do you have social media?</h3>
        <p>
            Follow us for SQL tips, updates, and news:
        </p>
        <ul>
            <li>Twitter: @SQLCompiler</li>
            <li>Facebook: /SQLCompiler</li>
            <li>LinkedIn: SQL Compiler</li>
        </ul>

        <h3>Can I contribute to SQL Compiler?</h3>
        <p>
            We welcome contributions! If you'd like to:
        </p>
        <ul>
            <li>Write tutorials or guides</li>
            <li>Report bugs or suggest features</li>
            <li>Translate content to other languages</li>
            <li>Donate to support our hosting costs</li>
        </ul>
        <p>
            Please contact us at contribute@sqlcompiler.shop
        </p>

        <div class="bg-purple-50 border-l-4 border-purple-600 p-6 mt-8">
            <h3 class="text-xl font-semibold mb-2">Still have questions?</h3>
            <p class="mb-4">Can't find what you're looking for? We're here to help!</p>
            <a href="mailto:support@sqlcompiler.shop" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                Contact Support →
            </a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Helpful Resources</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-basics-tutorial" class="text-purple-600 hover:text-purple-800">→ SQL Basics Tutorial</a>
                <a href="/learn/sql-cheat-sheet" class="text-purple-600 hover:text-purple-800">→ SQL Cheat Sheet</a>
                <a href="/learn/common-sql-mistakes" class="text-purple-600 hover:text-purple-800">→ Common SQL Mistakes</a>
                <a href="/#compiler" class="text-purple-600 hover:text-purple-800">→ Try SQL Compiler</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/learn/includes/learn_footer.php'; ?>
