<?php
// learn/example.php

// Define Dictionary of Topics (Layer 2: Programmatic Data Source)
$topics = [
    'select' => [
        'title' => 'SQL SELECT Example',
        'desc' => 'A simple, copy-paste example of the SQL SELECT statement. Run it instantly.',
        'h1' => 'SQL SELECT Example',
        'content' => 'The `SELECT` statement is the most common command in SQL. It allows you to retrieve specific columns from a table.',
        'code' => "SELECT first_name, last_name \nFROM users \nWHERE active = 1;",
        'related' => ['sql-where', 'sql-limit']
    ],
    'where' => [
        'title' => 'SQL WHERE Clause Example',
        'desc' => 'How to filter data with SQL WHERE. Copy this example code.',
        'h1' => 'SQL WHERE Example',
        'content' => 'Use the `WHERE` clause to filter records that meet a certain condition.',
        'code' => "SELECT * \nFROM orders \nWHERE status = 'Completed';",
        'related' => ['sql-select', 'sql-and-or']
    ],
    'join' => [
        'title' => 'SQL JOIN Example',
        'desc' => 'Inner Join example code for SQL. Combine two tables instantly.',
        'h1' => 'SQL INNER JOIN Example',
        'content' => 'A `JOIN` clause is used to combine rows from two or more tables, based on a related column between them.',
        'code' => "SELECT users.name, orders.date \nFROM users \nINNER JOIN orders ON users.id = orders.user_id;",
        'related' => ['sql-left-join', 'sql-group-by']
    ],
    'group-by' => [
        'title' => 'SQL GROUP BY Example',
        'desc' => 'Group rows and calculate aggregates like COUNT and SUM.',
        'h1' => 'SQL GROUP BY Example',
        'content' => 'The `GROUP BY` statement groups rows that have the same values into summary rows.',
        'code' => "SELECT country, COUNT(*) as user_count \nFROM users \nGROUP BY country;",
        'related' => ['sql-having', 'sql-count']
    ],
    'insert' => [
        'title' => 'SQL INSERT INTO Example',
        'desc' => 'How to insert new records in SQL. Simple INSERT INTO syntax example.',
        'h1' => 'SQL INSERT INTO Example',
        'content' => 'The `INSERT INTO` statement is used to add new records to a table.',
        'code' => "INSERT INTO users (first_name, email) \nVALUES ('John', 'john@example.com');",
        'related' => ['sql-update', 'sql-select']
    ],
    'update' => [
        'title' => 'SQL UPDATE Example',
        'desc' => 'How to modify existing records in SQL. UPDATE statement example.',
        'h1' => 'SQL UPDATE Example',
        'content' => 'The `UPDATE` statement is used to modify the existing records in a table. Be careful to use WHERE!',
        'code' => "UPDATE users \nSET active = 1 \nWHERE id = 5;",
        'related' => ['sql-insert', 'sql-delete']
    ],
    'delete' => [
        'title' => 'SQL DELETE Example',
        'desc' => 'How to remove records in SQL. DELETE statement example.',
        'h1' => 'SQL DELETE Example',
        'content' => 'The `DELETE` statement is used to delete existing records in a table.',
        'code' => "DELETE FROM users \nWHERE active = 0;",
        'related' => ['sql-insert', 'sql-truncate']
    ],
    'order-by' => [
        'title' => 'SQL ORDER BY Example',
        'desc' => 'Sort your SQL results with ORDER BY. ASC and DESC examples.',
        'h1' => 'SQL ORDER BY Example',
        'content' => 'The `ORDER BY` keyword is used to sort the result-set in ascending or descending order.',
        'code' => "SELECT * FROM products \nORDER BY price DESC;",
        'related' => ['sql-select', 'sql-limit']
    ],
    'count' => [
        'title' => 'SQL COUNT() Example',
        'desc' => 'Count rows in SQL. Simple COUNT(*) example code.',
        'h1' => 'SQL COUNT() Function Example',
        'content' => 'The `COUNT()` function returns the number of rows that match a specified criterion.',
        'code' => "SELECT COUNT(*) \nFROM orders;",
        'related' => ['sql-sum', 'sql-avg']
    ],
    'sum' => [
        'title' => 'SQL SUM() Example',
        'desc' => 'Calculate total sum of a column in SQL.',
        'h1' => 'SQL SUM() Function Example',
        'content' => 'The `SUM()` function returns the total sum of a numeric column.',
        'code' => "SELECT SUM(quantity) \nFROM order_details;",
        'related' => ['sql-count', 'sql-avg']
    ],
    'avg' => [
        'title' => 'SQL AVG() Example',
        'desc' => 'Calculate average value of a column in SQL.',
        'h1' => 'SQL AVG() Function Example',
        'content' => 'The `AVG()` function returns the average value of a numeric column.',
        'code' => "SELECT AVG(price) \nFROM products;",
        'related' => ['sql-count', 'sql-sum']
    ],
    'like' => [
        'title' => 'SQL LIKE Operator Example',
        'desc' => 'Pattern matching in SQL with LIKE. Wildcard examples.',
        'h1' => 'SQL LIKE Operator Example',
        'content' => 'The `LIKE` operator is used in a WHERE clause to search for a specified pattern in a column.',
        'code' => "SELECT * FROM users \nWHERE email LIKE '%@gmail.com';",
        'related' => ['sql-where', 'sql-in']
    ],
    'in' => [
        'title' => 'SQL IN Operator Example',
        'desc' => 'Filter by multiple values using SQL IN operator.',
        'h1' => 'SQL IN Operator Example',
        'content' => 'The `IN` operator allows you to specify multiple values in a WHERE clause.',
        'code' => "SELECT * FROM users \nWHERE country IN ('USA', 'Canada', 'UK');",
        'related' => ['sql-between', 'sql-where']
    ],
    'distinct' => [
        'title' => 'SQL DISTINCT Example',
        'desc' => 'Select only unique values with SQL DISTINCT.',
        'h1' => 'SQL SELECT DISTINCT Example',
        'content' => 'The `SELECT DISTINCT` statement is used to return only distinct (different) values.',
        'code' => "SELECT DISTINCT country \nFROM users;",
        'related' => ['sql-select', 'sql-group-by']
    ],
    'limit' => [
        'title' => 'SQL LIMIT Clause Example',
        'desc' => 'Limit the number of rows returned in SQL.',
        'h1' => 'SQL LIMIT Example',
        'content' => 'The `LIMIT` clause is used to specify the number of records to return.',
        'code' => "SELECT * FROM users \nLIMIT 5;",
        'related' => ['sql-select', 'sql-order-by']
    ],
    'alias' => [
        'title' => 'SQL Aliases Example',
        'desc' => 'How to use "AS" to rename columns or tables in SQL.',
        'h1' => 'SQL Aliases (AS) Example',
        'content' => 'SQL aliases are used to give a table, or a column in a table, a temporary name.',
        'code' => "SELECT first_name AS fname \nFROM users;",
        'related' => ['sql-select', 'sql-join']
    ]
];

// Get topic from URL
$topicKey = $_GET['topic'] ?? '';
$data = $topics[$topicKey] ?? null;

// 404 if topic not found
if (!$data) {
    header("HTTP/1.0 404 Not Found");
    include('../404.php'); // Assuming you have a 404, or just die
    echo "<h1>404 - Example Not Found</h1>";
    exit;
}

$pageTitle = $data['title'];
$pageDesc = $data['desc'];
$customTitle = $pageTitle . " | SQLCompiler.shop"; // Override header title
include('../includes/header.php');
include('../includes/schema_article.php');
?>

<main class="max-w-4xl mx-auto px-6 py-24">
    <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
        <a href="/" class="hover:text-cyan-400 transition">Home</a>
        <span class="mx-2">/</span>
        <a href="/learn" class="hover:text-cyan-400 transition">Learn</a>
        <span class="mx-2">/</span>
        <span class="text-white">Example: <?= htmlspecialchars($topicKey) ?></span>
    </nav>

    <article class="prose prose-invert prose-lg max-w-none">
        <h1 class="text-4xl font-bold mb-6"><?= htmlspecialchars($data['h1']) ?></h1>
        
        <p class="text-xl text-slate-300 mb-8">
            <?= htmlspecialchars($data['content']) ?>
        </p>

        <div class="bg-black/30 border border-white/10 rounded-xl p-6 mb-8 relative group">
            <div class="absolute top-0 left-0 bg-white/5 px-3 py-1 text-xs text-slate-400 rounded-br-lg border-r border-b border-white/5">SQL</div>
            <pre class="mt-4"><code class="language-sql text-cyan-300"><?= htmlspecialchars($data['code']) ?></code></pre>
            
            <a href="../?query=<?= urlencode($data['code']) ?>" target="_blank" class="absolute top-4 right-4 px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-black font-bold text-sm rounded transition shadow-lg shadow-cyan-500/20">
                Run this Code &rarr;
            </a>
        </div>

        <div class="bg-blue-900/10 border border-blue-500/20 p-6 rounded-xl">
            <h3 class="text-blue-400 font-bold mb-2">Why this works</h3>
            <ul class="list-disc pl-5 text-slate-400 text-sm space-y-2">
                <li>Simple syntax compliant with ANSI SQL.</li>
                <li>Works in MySQL, PostgreSQL, and SQL Server.</li>
                <li>You can test it immediately in our sandbox.</li>
            </ul>
        </div>

    </article>

    <!-- Internal Linking (Layer 2) -->
    <div class="mt-16 pt-8 border-t border-white/10">
        <h3 class="text-lg font-bold text-white mb-4">More Examples</h3>
        <div class="flex flex-wrap gap-3">
            <?php foreach($topics as $key => $val): ?>
                <?php if($key !== $topicKey): ?>
                <a href="/sql-examples/<?= $key ?>" class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-full border border-white/5 text-sm transition">
                    <?= htmlspecialchars($val['title']) ?>
                </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</main>



<?php include('../includes/footer.php'); ?>
