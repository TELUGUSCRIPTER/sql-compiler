<?php
$pageTitle = "SQL CREATE TABLE | Syntax & Data Types | SQL Playground";
$pageDesc = "Learn how to structure your database using the CREATE TABLE statement. Understand data types, primary keys, and constraints.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
            <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-500">SQL CREATE TABLE: Designing Your Database</h1>
            
            <p class="lead text-xl text-slate-300">
                The <code>CREATE TABLE</code> statement is the blueprint logic of your database. Before you can store any data, you must define the structure: what columns exist, what data they hold, and how they relate to each other.
            </p>

            <h2 id="syntax" class="scroll-mt-24 group">
                Basic Syntax
                <a href="#syntax" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
            </h2>
            
            <pre><code class="language-sql text-cyan-300">CREATE TABLE table_name (
    column1 datatype constraints,
    column2 datatype constraints,
    PRIMARY KEY (column1)
);</code></pre>

            <h2 id="datatypes" class="scroll-mt-24 group">
                Choosing the Right Data Types
                <a href="#datatypes" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
            </h2>
            <p>
                Choosing the correct data type is critical for performance and data integrity.
            </p>
            
            <div class="grid md:grid-cols-2 gap-6 my-6 not-prose">
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <h4 class="font-bold text-white mb-2">Integers</h4>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li><code>INT</code>: Standard number (-2B to +2B).</li>
                        <li><code>BIGINT</code>: Huge numbers (YouTube views).</li>
                        <li><code>TINYINT</code>: Small numbers (0-255). Good for flags.</li>
                    </ul>
                </div>
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <h4 class="font-bold text-white mb-2">Strings</h4>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li><code>VARCHAR(255)</code>: Variable length (Efficient).</li>
                        <li><code>CHAR(2)</code>: Fixed length (Country codes).</li>
                        <li><code>TEXT</code>: Long articles or comments.</li>
                    </ul>
                </div>
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <h4 class="font-bold text-white mb-2">Dates</h4>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li><code>DATE</code>: YYYY-MM-DD.</li>
                        <li><code>DATETIME</code>: Date + Time.</li>
                        <li><code>TIMESTAMP</code>: Timezone aware (Best for events).</li>
                    </ul>
                </div>
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <h4 class="font-bold text-white mb-2">Modern Types</h4>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li><code>JSON</code>: Store flexible objects.</li>
                        <li><code>BOOLEAN</code>: True/False (Stored as 1/0).</li>
                        <li><code>DECIMAL(10,2)</code>: Money (Prevention of rounding errors).</li>
                    </ul>
                </div>
            </div>

            <h2 id="constraints" class="scroll-mt-24 group">
                Constraints: Enforcing Rules
                <a href="#constraints" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
            </h2>
            <p>Databases can self-police your data quality using constraints.</p>

            <h3 class="text-white mt-6 mb-2">1. NOT NULL</h3>
            <p>Prevents a column from having no value.</p>
            <pre><code class="language-sql">email VARCHAR(100) NOT NULL</code></pre>

            <h3 class="text-white mt-6 mb-2">2. UNIQUE</h3>
            <p>Ensures no two rows have the same value (e.g., Email or Username).</p>
            <pre><code class="language-sql">email VARCHAR(100) UNIQUE</code></pre>

            <h3 class="text-white mt-6 mb-2">3. PRIMARY KEY</h3>
            <p>Uniquely identifies the record. Usually an auto-incrementing integer (ID).</p>
            <pre><code class="language-sql">id INT AUTO_INCREMENT PRIMARY KEY</code></pre>

            <h3 class="text-white mt-6 mb-2">4. CHECK</h3>
            <p>Validates data against a logical condition.</p>
            <pre><code class="language-sql">age INT CHECK (age >= 18)</code></pre>

            <h2 id="example" class="scroll-mt-24 group">
                Complete Example
                <a href="#example" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
            </h2>
            <div class="relative group">
                <pre><code class="language-sql text-cyan-300">CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_email CHECK (email LIKE '%@%')
);</code></pre>
                <a href="../editor.php?query=CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE);" class="absolute top-2 right-2 px-3 py-1 bg-cyan-500 hover:bg-cyan-400 text-black text-xs font-bold rounded transition">Run Code &rarr;</a>
            </div>

            <h2 id="ctas" class="scroll-mt-24 group">
                Advanced: CREATE TABLE AS SELECT (CTAS)
                <a href="#ctas" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
            </h2>
            <p>
                You can create a table by copying the structure and data from another table.
            </p>
            <pre><code class="language-sql">-- Clone the structure and data
CREATE TABLE archived_users AS
SELECT * FROM users WHERE active = 0;</code></pre>

            <div class="mt-12 pt-8 border-t border-white/10">
                <h3 class="text-xl font-bold text-white mb-4">Design Your Schema</h3>
                <p class="text-slate-400 mb-6">Experiment with creating complex tables with multiple constraints in our compiler.</p>
                <div class="flex gap-4">
                    <a href="../editor.php" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg transition">Open Compiler</a>
                    <a href="sql-constraints.php" class="px-6 py-3 border border-white/20 hover:border-white text-white rounded-lg transition">Next: SQL Constraints &rarr;</a>
                </div>
            </div>

        </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
