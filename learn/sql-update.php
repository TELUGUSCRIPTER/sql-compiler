<?php
$pageTitle = "SQL UPDATE Statement: Modifying Database Data";
$pageDesc = "Learn how to use the SQL UPDATE command to modify existing records in a table. Syntax, examples, and safety tips to avoid accidental data loss.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>

<main class="max-w-4xl mx-auto px-6 py-24">
    
    <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
        <a href="/" class="hover:text-cyan-400 transition">Home</a>
        <span class="mx-2">/</span>
        <a href="/learn" class="hover:text-cyan-400 transition">Learn SQL</a>
        <span class="mx-2">/</span>
        <span class="text-white">SQL UPDATE</span>
    </nav>

    <article class="prose prose-invert prose-lg max-w-none">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-500">SQL UPDATE Statement: The Complete Guide</h1>
        
        <p class="lead text-xl text-slate-300">
            The <code>UPDATE</code> statement is used to modify existing records in a table. It is one of the most powerful commands in SQL—and also one of the most dangerous. A single mistake in your <code>WHERE</code> clause can wipe out critical data across your entire application.
        </p>

        <h2 id="syntax" class="scroll-mt-24 group">
            Basic Syntax
            <a href="#syntax" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        
        <pre><code class="language-sql text-cyan-300">UPDATE table_name
SET column1 = value1, column2 = value2, ...
WHERE condition;</code></pre>

        <div class="bg-red-900/10 border-l-4 border-red-500 p-6 my-8 rounded-r-lg">
            <h3 class="text-red-400 font-bold mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                CRITICAL WARNING
            </h3>
            <p class="text-slate-300">
                If you omit the <code>WHERE</code> clause, <strong>ALL</strong> records in the table will be updated! 
                <br><code>UPDATE users SET role = 'admin';</code> -> Congratulations, every user is now an admin.
            </p>
            <p class="mt-4 text-xs text-red-300 uppercase font-bold tracking-widest">Always backup before bulk updates.</p>
        </div>

        <h2 id="scenarios" class="scroll-mt-24 group">
            Real-World Scenarios
            <a href="#scenarios" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>

        <h3 class="text-xl font-bold text-white mt-8 mb-4">1. The "Soft Delete" Pattern</h3>
        <p class="text-slate-400">Instead of actually deleting data, modern apps often just mark it as deleted.</p>
        <pre><code class="language-sql text-cyan-300">UPDATE files 
SET is_deleted = 1, deleted_at = NOW() 
WHERE id = 502;</code></pre>

        <h3 class="text-xl font-bold text-white mt-8 mb-4">2. Calculating New Values</h3>
        <p class="text-slate-400">You can use the existing data to calculate new data. This is great for inventory.</p>
        <pre><code class="language-sql text-cyan-300">-- Reduce stock by 1 for item #99
UPDATE products 
SET stock_quantity = stock_quantity - 1 
WHERE id = 99;</code></pre>

        <h2 id="advanced" class="scroll-mt-24 group">Advanced Updates</h2>

        <h3 class="text-lg font-bold text-purple-400 mt-6 mb-2">Updating with JOIN</h3>
        <p>Sometimes you need to update a table based on data in <em>another</em> table.</p>
        <pre><code class="language-sql">-- Set customers to 'VIP' if they have spent > $1000
UPDATE customers c
JOIN (
    SELECT user_id, SUM(total) as spent 
    FROM orders 
    GROUP BY user_id
) o ON c.id = o.user_id
SET c.status = 'VIP'
WHERE o.spent > 1000;</code></pre>

        <h3 class="text-lg font-bold text-purple-400 mt-6 mb-2">Safe Updates with LIMIT</h3>
        <p>In MySQL, you can limit how many rows are updated. This is a great safety net.</p>
        <pre><code class="language-sql">-- Only update the most recent login
UPDATE users 
SET last_login_ip = '10.0.0.1' 
WHERE id = 5 
ORDER BY last_login DESC 
LIMIT 1;</code></pre>

        <h2 id="best-practices" class="scroll-mt-24 group">Best Practices for Production</h2>
        <ul class="list-disc pl-5 space-y-4 text-slate-300 mt-6">
            <li>
                <strong>Use Transactions:</strong> Wrap your updates in a transaction. If you make a mistake, you can <code>ROLLBACK</code>.
            </li>
            <li>
                <strong>Test with SELECT:</strong> Before running an UPDATE, run a SELECT with the same WHERE clause to see exactly which rows will be affected.
                <pre class="bg-black/30 p-2 mt-2 text-xs rounded">-- Step 1: Check
SELECT * FROM users WHERE active = 0;
-- Step 2: Update
UPDATE users SET status = 'archived' WHERE active = 0;</pre>
            </li>
            <li>
                <strong>Index your Filters:</strong> Ensure the columns in your WHERE clause are indexed. Updating a table with 1 million rows without an index will lock the entire table for seconds (or minutes), freezing your app.
            </li>
        </ul>

        <div class="mt-12 pt-8 border-t border-white/10">
            <h3 class="text-xl font-bold text-white mb-4">Next Lesson</h3>
            <p class="text-slate-400 mb-6">Now that you can modify data, let's learn how to permanently remove it.</p>
            <div class="flex gap-4">
                <a href="../editor.php" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg transition">Open Compiler</a>
                <a href="sql-delete.php" class="px-6 py-3 border border-white/20 hover:border-white text-white rounded-lg transition">Next: SQL DELETE &rarr;</a>
            </div>
        </div>

    </article>
</main>

<?php include('../includes/footer.php'); ?>
