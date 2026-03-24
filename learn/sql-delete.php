<?php
$pageTitle = "SQL DELETE Statement: Removing Data Safely";
$pageDesc = "Learn how to use the SQL DELETE command to remove records from a table. Understand the difference between DELETE and TRUNCATE.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>

<main class="max-w-4xl mx-auto px-6 py-24">
    
    <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
        <a href="/" class="hover:text-cyan-400 transition">Home</a>
        <span class="mx-2">/</span>
        <a href="/learn" class="hover:text-cyan-400 transition">Learn SQL</a>
        <span class="mx-2">/</span>
        <span class="text-white">SQL DELETE</span>
    </nav>

    <article class="prose prose-invert prose-lg max-w-none">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-red-400 to-orange-500">SQL DELETE Statement: A Guide to Data Removal</h1>
        
        <p class="lead text-xl text-slate-300">
            The <code>DELETE</code> statement is the trash can of SQL. It allows you to permanently remove records from your database. However, unlike a computer's recycle bin, usually <strong>there is no undo</strong>.
        </p>

        <h2 id="syntax" class="scroll-mt-24 group">
            Basic Syntax
            <a href="#syntax" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        
        <pre><code class="language-sql text-cyan-300">DELETE FROM table_name
WHERE condition;</code></pre>

        <div class="bg-red-900/10 border-l-4 border-red-500 p-4 my-6">
            <h3 class="text-red-400 font-bold mb-2">⚠️ CRITICAL WARNING</h3>
            <p class="text-slate-300 text-sm">
                If you omit the <code>WHERE</code> clause, <strong>ALL</strong> records in the table will be deleted! The table structure will remain, but it will be empty.
            </p>
        </div>

        <h2 id="soft-delete" class="scroll-mt-24 group">
            Advanced Pattern: The "Soft Delete"
            <a href="#soft-delete" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            In professional applications, we rarely run a physical <code>DELETE</code>. Instead, we perform a <strong>Soft Delete</strong>. This means we keep the data but mark it as "hidden".
        </p>
        <p><strong>Why?</strong> Auditing, recovery, and data integrity.</p>
        
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 my-4">
            <h4 class="text-white font-bold mb-4">How to Implement Soft Deletes</h4>
            <ol class="list-decimal pl-5 space-y-4 text-slate-400 text-sm">
                <li>Add a column named <code>is_deleted</code> (BOOLEAN) or <code>deleted_at</code> (TIMESTAMP) to your table.</li>
                <li>Instead of DELETE, use UPDATE:
                    <pre class="mt-2"><code class="language-sql text-cyan-300">UPDATE users SET deleted_at = NOW() WHERE id = 50;</code></pre>
                </li>
                <li>When Selecting, filter them out:
                    <pre class="mt-2"><code class="language-sql text-cyan-300">SELECT * FROM users WHERE deleted_at IS NULL;</code></pre>
                </li>
            </ol>
        </div>

        <h2 id="cascade" class="scroll-mt-24 group">
            Referential Integrity & Cascading Deletes
            <a href="#cascade" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            What happens if you delete a `User`, but they still have `Orders` in the database? This creates "Orphaned Records".
        </p>
        <p>Databases handle this with <strong>Foreign Key Constraints</strong>:</p>
        <ul class="list-disc pl-5 space-y-2 text-slate-300">
            <li><strong>ON DELETE RESTRICT:</strong> Prevents you from deleting the user until all their orders are deleted first. (Safest)</li>
            <li><strong>ON DELETE CASCADE:</strong> Automatically deletes all related orders when the user is deleted. (Convenient but risky)</li>
            <li><strong>ON DELETE SET NULL:</strong> Keeps the orders but sets the <code>user_id</code> to NULL.</li>
        </ul>

        <h2 id="truncate" class="scroll-mt-24 group">
            DELETE vs. TRUNCATE
            <a href="#truncate" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        
        <table class="w-full text-left border-collapse border border-white/10 mt-6">
            <thead>
                <tr class="bg-white/5">
                    <th class="p-4 border border-white/10 text-white">Feature</th>
                    <th class="p-4 border border-white/10 text-cyan-400">DELETE</th>
                    <th class="p-4 border border-white/10 text-purple-400">TRUNCATE</th>
                </tr>
            </thead>
            <tbody class="text-slate-400 text-sm">
                <tr>
                    <td class="p-4 border border-white/10">Type</td>
                    <td class="p-4 border border-white/10">DML (Data Manipulation)</td>
                    <td class="p-4 border border-white/10">DDL (Data Definition)</td>
                </tr>
                <tr>
                    <td class="p-4 border border-white/10">Filtering</td>
                    <td class="p-4 border border-white/10">Can use <code>WHERE</code></td>
                    <td class="p-4 border border-white/10">Cannot filter (all rows)</td>
                </tr>
                <tr>
                    <td class="p-4 border border-white/10">Speed</td>
                    <td class="p-4 border border-white/10">Slower (logs each row)</td>
                    <td class="p-4 border border-white/10">Faster (resets table)</td>
                </tr>
                <tr>
                    <td class="p-4 border border-white/10">Auto-Increment</td>
                    <td class="p-4 border border-white/10">Continues from last ID</td>
                    <td class="p-4 border border-white/10">Resets to 1</td>
                </tr>
            </tbody>
        </table>

        <h2 id="performance" class="scroll-mt-24 group">
             Performance: Deleting Millions of Rows
            <a href="#performance" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            Running <code>DELETE FROM logs WHERE date < '2023-01-01'</code> on a table with 100 million rows will likely crash your database (lock wait timeout or transaction log full).
        </p>
        <p><strong>The Solution: Batching</strong></p>
        <pre><code class="language-sql text-cyan-300">-- Delete in chunks of 1000 to avoid locking the table
DELETE FROM logs 
WHERE date < '2023-01-01' 
LIMIT 1000;</code></pre>
        <p class="text-sm text-slate-400 mt-2">Repeatedly run this query until 0 rows are affected.</p>

        <div class="mt-12 pt-8 border-t border-white/10">
            <h3 class="text-xl font-bold text-white mb-4">Practice Safely</h3>
            <p class="text-slate-400 mb-6">In our online compiler, databases are isolated. You can delete whatever you want without breaking anything!</p>
            <div class="flex gap-4">
                <a href="../editor.php" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg transition">Try DELETE Command</a>
            </div>
        </div>

    </article>
</main>

<?php include('../includes/footer.php'); ?>
