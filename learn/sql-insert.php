<?php
$pageTitle = "SQL INSERT INTO | Adding Data | SQL Playground";
$pageDesc = "Learn how to insert new records into a table using the INSERT INTO statement. Single row and bulk insertion examples.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
        <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
            <h1 class="text-4xl font-bold text-white mb-6">The SQL INSERT INTO Statement: A Complete Guide</h1>
            
            <p class="text-slate-300 text-xl leading-relaxed">
                The <code>INSERT INTO</code> statement is used to add new records (rows) to a table. Whether you are signing up a new user, logging a transaction, or saving a comment, this is the command that makes data persistent.
            </p>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Basic Syntax</h2>
            <p>There are two primary ways to write an INSERT statement.</p>
            
            <h3 class="text-xl font-bold text-cyan-400 mt-6 mb-2">1. Specifying Columns (Recommended)</h3>
            <p>
                Best practice is to explicitly list the columns you are populating. This makes your code readable and prevents errors if the table structure changes later.
            </p>
            <pre><code class="language-sql">INSERT INTO table_name (column1, column2)
VALUES (value1, value2);</code></pre>

            <h3 class="text-xl font-bold text-cyan-400 mt-6 mb-2">2. Implicit Columns</h3>
            <p>
                If you provide a value for <em>every</em> column in the exact order they were defined, you can omit the column names.
            </p>
            <pre><code class="language-sql">-- Risky: If table columns change order, this breaks!
INSERT INTO table_name
VALUES (value1, value2);</code></pre>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Handling Default Values and NULLs</h2>
            <p>
                You don't always have data for every column.
            </p>
            <ul class="list-disc pl-5 space-y-2 text-slate-300">
                <li><strong>Auto Increment:</strong> Columns like `id` usually auto-generate. You should skip them in your INSERT list.</li>
                <li><strong>Default Values:</strong> If a column has a `DEFAULT` (e.g., `created_at TIMESTAMP DEFAULT NOW()`), you can skip it, and the database will fill it in.</li>
                <li><strong>NULL:</strong> If a column allows NULLs and you skip it, it becomes NULL.</li>
            </ul>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Advanced Techniques</h2>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">1. Bulk Insert (High Performance)</h3>
            <p>
                Inserting one row at a time is slow due to network overhead. You can insert hundreds of rows in a single query.
            </p>
            <pre><code class="language-sql">INSERT INTO users (name, email) VALUES 
('Alice', 'alice@example.com'),
('Bob', 'bob@example.com'),
('Charlie', 'charlie@example.com');</code></pre>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">2. INSERT INTO ... SELECT</h3>
            <p>
                You can copy data from one table to another. This is great for archiving or backups.
            </p>
            <pre><code class="language-sql">-- Copy all USA users to a specific archive table
INSERT INTO archive_users (id, name, email)
SELECT id, name, email 
FROM users 
WHERE country = 'USA';</code></pre>

            <h3 class="text-xl font-bold text-white mt-6 mb-2">3. Upsert (On Duplicate Key Update)</h3>
            <p>
                Sometimes you want to insert a record, but if it already exists (based on Primary Key or Unique Index), you want to update it instead. This is called an "Upsert".
            </p>
            <pre><code class="language-sql">INSERT INTO products (id, stock) 
VALUES (1, 50)
ON DUPLICATE KEY UPDATE stock = stock + 50;</code></pre>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Performance Tips</h2>
            <div class="space-y-4">
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <h4 class="font-bold text-white">Use Transactions</h4>
                    <p class="text-sm text-slate-400">If inserting 1,000 rows individually, wrap them in a <code>START TRANSACTION</code> and <code>COMMIT</code>. This reduces disk I/O significantly.</p>
                </div>
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <h4 class="font-bold text-white">Disable Indexes (Rare)</h4>
                    <p class="text-sm text-slate-400">For massive data loads (millions of rows), it can be faster to disable indexes, insert data, and then re-enable indexes.</p>
                </div>
            </div>

            <div class="my-12 p-8 bg-yellow-900/20 border border-yellow-500/30 rounded-2xl">
                <h3 class="text-xl font-bold text-yellow-400 mb-2">Practice Time!</h3>
                <p class="mb-4 text-sm text-slate-400">Try bulk inserting data into your own table.</p>
                <div class="flex gap-4">
                    <a href="../editor.php" class="px-6 py-3 bg-yellow-400 hover:bg-yellow-300 text-black font-bold rounded-lg transition shadow-lg shadow-yellow-500/20">Open Compiler &rarr;</a>
                    <a href="sql-update.php" class="px-6 py-3 border border-white/20 hover:border-white text-white font-medium rounded-lg transition">Next: SQL UPDATE Data</a>
                </div>
            </div>

        </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
