<?php
$pageTitle = "SQL Indexes: Improving Query Performance";
$pageDesc = "Learn how SQL Indexes work, when to use them, and how they speed up database queries. A guide to CREATE INDEX and performance optimization.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>

<main class="max-w-4xl mx-auto px-6 py-24">
    
    <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
        <a href="/" class="hover:text-cyan-400 transition">Home</a>
        <span class="mx-2">/</span>
        <a href="/learn" class="hover:text-cyan-400 transition">Learn SQL</a>
        <span class="mx-2">/</span>
        <span class="text-white">SQL Indexes</span>
    </nav>

    <article class="prose prose-invert prose-lg max-w-none">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">SQL Indexes Explained</h1>
        
        <p class="lead text-xl text-slate-300">
            An <strong>Index</strong> in SQL is a data structure that improves the speed of data retrieval operations on a database table. Think of it like the index at the back of a book—it helps you find information quickly without reading every page.
        </p>

        <h2 id="create-index" class="scroll-mt-24 group">
            Creating an Index
            <a href="#create-index" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            You can create an index on one or more columns using the `CREATE INDEX` statement.
        </p>
        
        <div class="relative group my-6">
            <pre><code class="language-sql text-cyan-300">CREATE INDEX idx_lastname
ON users (lastname);</code></pre>
        </div>

        <p>
            After creating this index, searching for users by `lastname` will be significantly faster, especially if the table has millions of rows.
        </p>

        <h2 id="how-it-works" class="scroll-mt-24 group">
            How It Works
            <a href="#how-it-works" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            Without an index, the database must perform a <strong>Full Table Scan</strong>, checking every single row to find a match. With an index, the database uses a structured lookup (usually a B-Tree) to jump directly to the relevant data.
        </p>

        <div class="grid md:grid-cols-2 gap-8 my-8 not-prose">
            <div class="p-6 bg-red-900/10 border border-red-500/20 rounded-xl">
                <h3 class="text-red-400 font-bold mb-2">Without Index</h3>
                <div class="text-sm text-slate-400">
                    <p class="mb-2">• Query: SELECT * FROM users WHERE age = 25;</p>
                    <p>• Action: Checks Row 1, Row 2, ... Row 1,000,000.</p>
                    <p>• Result: <strong>Slow</strong> (O(n))</p>
                </div>
            </div>
            <div class="p-6 bg-green-900/10 border border-green-500/20 rounded-xl">
                <h3 class="text-green-400 font-bold mb-2">With Index</h3>
                <div class="text-sm text-slate-400">
                    <p class="mb-2">• Query: SELECT * FROM users WHERE age = 25;</p>
                    <p>• Action: Jumps to "25" in B-Tree.</p>
                    <p>• Result: <strong>Fast</strong> (O(log n))</p>
                </div>
            </div>
        </div>

        <h2 id="tradeoffs" class="scroll-mt-24 group">
            The Trade-off: Read vs. Write
            <a href="#tradeoffs" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            Indexes speed up <code>SELECT</code> queries, but they slow down <code>INSERT</code>, <code>UPDATE</code>, and <code>DELETE</code> statements. Why? Because every time you change data, the database has to update the indexes as well.
        </p>
        <div class="bg-yellow-900/20 p-4 border-l-4 border-yellow-500 text-slate-300 text-sm italic">
            <strong>Best Practice:</strong> Only create indexes on columns that are frequently used in your <code>WHERE</code> clauses or <code>JOIN</code> conditions.
        </div>

        <h2 id="unique-index" class="scroll-mt-24 group">Unique Index</h2>
        <p>
            A unique index ensures that two rows cannot have the same value in the indexed column.
        </p>
         <pre><code class="language-sql text-cyan-300">CREATE UNIQUE INDEX idx_email
ON users (email);</code></pre>


        <div class="mt-12 pt-8 border-t border-white/10">
            <h3 class="text-xl font-bold text-white mb-4">Related Topics</h3>
            <ul class="flex flex-wrap gap-4">
                <li><a href="sql-select.php" class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-full border border-white/5 bg-slate-400 transition">SQL SELECT</a></li>
                <li><a href="sql-where.php" class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-full border border-white/5 bg-slate-400 transition">SQL WHERE</a></li>
            </ul>
        </div>

    </article>
</main>

<?php include('../includes/footer.php'); ?>
