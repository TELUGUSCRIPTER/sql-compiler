<?php
$pageTitle = "SQL WHERE Clause | Tutorial & Examples | SQL Playground";
$pageDesc = "Master the SQL WHERE clause to filter data. Learn how to use operators like =, >, <, LIKE, and IN.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
        <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
            <h1 class="text-4xl font-bold text-white mb-6">The SQL WHERE Clause: Mastering Data Filtering</h1>
            
            <p class="text-slate-300 text-xl leading-relaxed">
                 The <code>WHERE</code> clause is the gatekeeper of your queries. It allows you to filter records and extract only those that satisfy a specific condition. Without it, you would always retrieve every single row in your database—which is rarely what you want.
            </p>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">How Filtering Works</h2>
            <p>
                Technically, the <code>WHERE</code> clause acts as a boolean check. For every row in the table, the database evaluates the condition.
            </p>
            <ul class="list-disc pl-5 text-slate-300 space-y-2">
                <li>If the condition is <strong>TRUE</strong>, the row is included.</li>
                <li>If <strong>FALSE</strong>, the row is discarded.</li>
                <li>If <strong>UNKNOWN</strong> (NULL), the row is discarded.</li>
            </ul>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Standard Operators</h2>
            <div class="not-prose overflow-hidden rounded-xl border border-white/10 my-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white/5 text-white font-bold">
                        <tr><th class="p-4">Operator</th><th class="p-4">Description</th><th class="p-4">Example</th></tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-400">
                        <tr><td class="p-4 font-mono text-cyan-400">=</td><td class="p-4">Equal</td><td class="p-4 font-mono">age = 18</td></tr>
                        <tr><td class="p-4 font-mono text-cyan-400"><></td><td class="p-4">Not equal</td><td class="p-4 font-mono">status <> 'active'</td></tr>
                        <tr><td class="p-4 font-mono text-cyan-400">></td><td class="p-4">Greater than</td><td class="p-4 font-mono">price > 100</td></tr>
                        <tr><td class="p-4 font-mono text-cyan-400"><</td><td class="p-4">Less than</td><td class="p-4 font-mono">stock < 5</td></tr>
                        <tr><td class="p-4 font-mono text-cyan-400">BETWEEN</td><td class="p-4">Range (Inclusive)</td><td class="p-4 font-mono">price BETWEEN 10 AND 20</td></tr>
                        <tr><td class="p-4 font-mono text-cyan-400">IN</td><td class="p-4">Multiple Values</td><td class="p-4 font-mono">city IN ('Paris', 'London')</td></tr>
                    </tbody>
                </table>
            </div>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Combining Logic: AND, OR, NOT</h2>
            <p>Real-world questions are rarely simple. You often need to combine multiple conditions.</p>

            <h3 class="text-xl font-bold text-cyan-400 mt-8 mb-2">1. The AND Operator</h3>
            <p>Requires <strong>BOTH</strong> conditions to be true.</p>
            <pre><code class="language-sql">-- Find users who are active AND live in USA
SELECT * FROM users 
WHERE status = 'active' AND country = 'USA';</code></pre>

            <h3 class="text-xl font-bold text-cyan-400 mt-8 mb-2">2. The OR Operator</h3>
            <p>Requires <strong>AT LEAST ONE</strong> condition to be true.</p>
            <pre><code class="language-sql">-- Find users who live in USA OR Canada
SELECT * FROM users 
WHERE country = 'USA' OR country = 'Canada';</code></pre>

            <h3 class="text-xl font-bold text-cyan-400 mt-8 mb-2">3. The NOT Operator</h3>
            <p>Reverses the condition.</p>
            <pre><code class="language-sql">-- Find users who are NOT from France
SELECT * FROM users 
WHERE NOT country = 'France';</code></pre>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Advanced Filtering</h2>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">Handling NULL Values</h3>
            <p class="bg-yellow-900/20 border border-yellow-500/20 p-4 rounded text-sm text-yellow-200">
                <strong>Crucial Concept:</strong> In SQL, <code>NULL</code> means "unknown". You cannot check for it using <code>= NULL</code> because "unknown" does not equal "unknown".
            </p>
            <p>You MUST use the <code>IS NULL</code> operator.</p>
            <pre><code class="language-sql">-- WRONG
SELECT * FROM users WHERE phone = NULL;

-- CORRECT
SELECT * FROM users WHERE phone IS NULL;</code></pre>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">Pattern Matching with LIKE</h3>
            <p>The <code>LIKE</code> operator allows wildcard searches.</p>
            <ul class="list-disc pl-5 space-y-2 text-slate-300">
                <li><code>%</code> : Represents zero or more characters.</li>
                <li><code>_</code> : Represents exactly one character.</li>
            </ul>
            <pre><code class="language-sql">-- Find emails ending in @gmail.com
SELECT * FROM users WHERE email LIKE '%@gmail.com';

-- Find names starting with 'A'
SELECT * FROM users WHERE name LIKE 'A%';</code></pre>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Security Warning: SQL Injection</h2>
            <p>
                When using WHERE clauses in web applications (PHP, Python, Node.js), <strong>NEVER</strong> concatenate user input directly into the query string.
            </p>
            <div class="bg-red-900/20 border border-red-500/20 p-6 rounded-lg my-4">
                <code class="text-red-300 block mb-2">-- DANGEROUS CODE</code>
                <pre class="text-xs">"SELECT * FROM users WHERE name = '" + userInput + "'";</pre>
                <p class="text-sm text-red-200 mt-2">
                    If a user enters <code>' OR '1'='1</code>, they can bypass your login logic. Always use <strong>Prepared Statements</strong>.
                </p>
            </div>

            <div class="my-12 p-8 bg-purple-900/20 border border-purple-500/30 rounded-2xl">
                <h3 class="text-xl font-bold text-purple-400 mb-2">Practice Time!</h3>
                <p class="mb-4 text-sm text-slate-400">Try combining AND, OR, and LIKE operators in our secure sandbox.</p>
                <div class="flex gap-4">
                    <a href="../editor.php" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-lg transition shadow-lg shadow-purple-500/20">Open Compiler &rarr;</a>
                    <a href="sql-insert.php" class="px-6 py-3 border border-white/20 hover:border-white text-white font-medium rounded-lg transition">Next: SQL INSERT Data</a>
                </div>
            </div>

        </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
