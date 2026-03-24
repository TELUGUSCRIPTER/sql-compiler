<?php
$pageTitle = "SQL Constraints: PRIMARY KEY, FOREIGN KEY, NOT NULL";
$pageDesc = "Understand SQL constraints and how to use them to ensure data integrity. Learn about Primary Key, Foreign Key, Unique, Not Null, and Check constraints.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>

<main class="max-w-4xl mx-auto px-6 py-24">
    
    <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
        <a href="/" class="hover:text-cyan-400 transition">Home</a>
        <span class="mx-2">/</span>
        <a href="/learn" class="hover:text-cyan-400 transition">Learn SQL</a>
        <span class="mx-2">/</span>
        <span class="text-white">SQL Constraints</span>
    </nav>

    <article class="prose prose-invert prose-lg max-w-none">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">SQL Constraints Explained</h1>
        
        <p class="lead text-xl text-slate-300">
            <strong>SQL Constraints</strong> are rules enforced on data columns to ensure the accuracy and reliability of the data in the database. They prevent invalid data from being entered.
        </p>

        <h2 id="common-constraints" class="scroll-mt-24 group">
            Most Common Constraints
            <a href="#common-constraints" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>

        <div class="space-y-8 my-8">
            
            <!-- NOT NULL -->
            <div class="bg-white/5 p-6 rounded-xl border border-white/5 transform hover:scale-[1.01] transition duration-300">
                <h3 class="text-cyan-400 font-bold text-xl mb-2 mt-0">1. NOT NULL</h3>
                <p class="text-slate-400 mb-4">Ensures that a column cannot have a `NULL` value.</p>
                <pre><code class="language-sql text-sm">CREATE TABLE users (
    id INT,
    username VARCHAR(50) NOT NULL
);</code></pre>
            </div>

            <!-- UNIQUE -->
            <div class="bg-white/5 p-6 rounded-xl border border-white/5 transform hover:scale-[1.01] transition duration-300">
                <h3 class="text-purple-400 font-bold text-xl mb-2 mt-0">2. UNIQUE</h3>
                <p class="text-slate-400 mb-4">Ensures that all values in a column are different.</p>
                <pre><code class="language-sql text-sm">CREATE TABLE users (
    email VARCHAR(100) UNIQUE
);</code></pre>
            </div>

            <!-- PRIMARY KEY -->
            <div class="bg-white/5 p-6 rounded-xl border border-white/5 transform hover:scale-[1.01] transition duration-300">
                <h3 class="text-green-400 font-bold text-xl mb-2 mt-0">3. PRIMARY KEY</h3>
                <p class="text-slate-400 mb-4">A combination of `NOT NULL` and `UNIQUE`. Uniquely identifies each record in a table.</p>
                <pre><code class="language-sql text-sm">CREATE TABLE users (
    user_id INT PRIMARY KEY,
    name VARCHAR(50)
);</code></pre>
            </div>

        </div>

        <h2 id="foreign-key" class="scroll-mt-24 group">
            Foreign Key Constraint
            <a href="#foreign-key" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            A <strong>FOREIGN KEY</strong> is a field (or collection of fields) in one table that refers to the <strong>PRIMARY KEY</strong> in another table. It is used to prevent actions that would destroy links between tables.
        </p>

        <pre><code class="language-sql text-cyan-300">CREATE TABLE orders (
    order_id INT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);</code></pre>

        <h2 id="check" class="scroll-mt-24 group">
            CHECK Constraint
            <a href="#check" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>The `CHECK` constraint ensures that the values in a column satisfy a specific condition.</p>
        <pre><code class="language-sql text-cyan-300">CREATE TABLE users (
    age INT,
    CHECK (age >= 18)
);</code></pre>

        <div class="bg-cyan-900/20 border border-cyan-500/20 p-6 rounded-xl my-8">
            <h3 class="text-cyan-400 font-bold mb-2 text-lg">Try It Yourself</h3>
            <p class="text-sm text-slate-300 mb-4">
                You can test these constraints in our online compiler. Try creating a table with a `CHECK` constraint and see what happens when you insert invalid data!
            </p>
            <a href="../" class="inline-block px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded transition">Open Compiler</a>
        </div>

    </article>
</main>

<?php include('../includes/footer.php'); ?>
