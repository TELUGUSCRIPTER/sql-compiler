<?php
$pageTitle = 'SQL Best Practices - Performance, Security & Code Quality';
$metaDescription = 'Learn SQL best practices for writing efficient, secure, and maintainable queries. Tips on performance optimization, security, indexing, and code organization.';
$metaKeywords = 'SQL best practices, SQL optimization, SQL performance, SQL security, query optimization';
$canonicalUrl = 'https://sqlcompiler.shop/learn/sql-best-practices';$breadcrumbs = [
    ['title' => 'Learn', 'url' => '/learn/sql-basics-tutorial'],
    ['title' => 'SQL Best Practices']
];

require_once __DIR__ . '/includes/learn_header.php';
require_once __DIR__ . '/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">SQL Best Practices for Performance & Security</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            Writing SQL queries is easy. Writing GOOD SQL queries takes skill and knowledge of best practices. This guide covers essential principles for writing efficient, secure, and maintainable SQL code that performs well at scale.
        </p>

        <h2>Performance Best Practices</h2>

        <h3>1. Use Indexes Wisely</h3>
        <pre><code class="language-sql">-- Create indexes on frequently queried columns
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_created_at ON orders(created_at);

-- Composite index for multiple columns used together
CREATE INDEX idx_name_dept ON employees(last_name, department);
</code></pre>
        <blockquote>⚠️ Over-indexing slows INSERT/UPDATE operations. Index strategically!</blockquote>

        <h3>2. Avoid SELECT *</h3>
        <pre><code class="language-sql">-- Bad
SELECT * FROM employees;

-- Good - specify only needed columns
SELECT id, name, email FROM employees;
</code></pre>

        <h3>3. Use EXISTS Instead of COUNT</h3>
        <pre><code class="language-sql">-- Slower
SELECT COUNT(*) FROM orders WHERE customer_id = 123;

-- Faster - stops after finding first match
SELECT EXISTS(SELECT 1 FROM orders WHERE customer_id = 123);
</code></pre>

        <h3>4. Filter Early in JOINs</h3>
        <pre><code class="language-sql">-- Apply WHERE before JOIN
SELECT e.name, d.name
FROM (SELECT * FROM employees WHERE active = 1) e
JOIN departments d ON e.dept_id = d.id;
</code></pre>

        <h3>5. Use LIMIT for Large Results</h3>
        <pre><code class="language-sql">-- Always limit when testing
SELECT * FROM logs ORDER BY created_at DESC LIMIT 100;
</code></pre>

        <h2>Security Best Practices</h2>

        <h3>1. Always Use Prepared Statements</h3>
        <pre><code class="language-php">// Bad - SQL Injection vulnerable
$sql = "SELECT * FROM users WHERE email = '$email'";

// Good - Prepared statement
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
</code></pre>

        <h3>2. Principle of Least Privilege</h3>
        <pre><code class="language-sql">-- Grant only necessary permissions
GRANT SELECT, INSERT ON products TO 'app_user'@'localhost';

-- Don't give unnecessary admin rights
-- GRANT ALL PRIVILEGES ON *.* -- DON'T DO THIS!
</code></pre>

        <h3>3. Sanitize User Input</h3>
        <ul>
            <li>Never trust user input</li>
            <li>Validate data types and formats</li>
            <li>Use parameterized queries</li>
            <li>Escape special characters</li>
        </ul>

        <h2>Query Design Best Practices</h2>

        <h3>1. Use JOINs Over Subqueries</h3>
        <pre><code class="language-sql">-- Less efficient - subquery
SELECT name FROM employees
WHERE dept_id IN (SELECT id FROM departments WHERE  budget > 100000);

-- More efficient - JOIN
SELECT e.name
FROM employees e
JOIN departments d ON e.dept_id = d.id
WHERE d.budget > 100000;
</code></pre>

        <h3>2. Avoid Functions on Indexed Columns</h3>
        <pre><code class="language-sql">-- Bad - can't use index
SELECT * FROM orders WHERE YEAR(created_at) = 2024;

-- Good - index can be used
SELECT * FROM orders 
WHERE created_at >= '2024-01-01' AND created_at < '2025-01-01';
</code></pre>

        <h3>3. Use UNION ALL Instead of UNION</h3>
        <pre><code class="language-sql">-- Slower - removes duplicates
SELECT name FROM customers UNION SELECT name FROM suppliers;

-- Faster - if duplicates are OK
SELECT name FROM customers UNION ALL SELECT name FROM suppliers;
</code></pre>

        <h2>Code Quality Best Practices</h2>

        <h3>1. Use Consistent Formatting</h3>
        <pre><code class="language-sql">-- Good formatting
SELECT 
    e.id,
    e.name,
    e.email,
    d.name AS department
FROM employees e
JOIN departments d ON e.dept_id = d.id
WHERE e.active = 1
ORDER BY e.name;
</code></pre>

        <h3>2. Use Meaningful Aliases</h3>
        <pre><code class="language-sql">-- Bad
SELECT a.x, b.y FROM t1 a JOIN t2 b ON a.id = b.id;

-- Good
SELECT 
    emp.name,
    dept.name
FROM employees emp
JOIN departments dept ON emp.dept_id = dept.id;
</code></pre>

        <h3>3. Comment Complex Queries</h3>
        <pre><code class="language-sql">-- Calculate monthly revenue by region
-- Excludes refunded/cancelled orders
SELECT 
    r.name AS region,
    DATE_FORMAT(o.created_at, '%Y-%m') AS month,
    SUM(o.total) AS revenue
FROM orders o
JOIN customers c ON o.customer_id = c.id
JOIN regions r ON c.region_id = r.id
WHERE o.status NOT IN ('refunded', 'cancelled')
GROUP BY r.name, DATE_FORMAT(o.created_at, '%Y-%m');
</code></pre>

        <h2>Database Design Best Practices</h2>

        <h3>1. Normalize Appropriately</h3>
        <ul>
            <li>Aim for 3rd Normal Form (3NF)</li>
            <li>Denormalize only for performance when needed</li>
            <li>Document denormalization decisions</li>
        </ul>

        <h3>2. Use Appropriate Data Types</h3>
        <table>
            <thead>
                <tr><th>Data</th><th>Use</th><th>Don't Use</th></tr>
            </thead>
            <tbody>
                <tr><td>Emails</td><td>VARCHAR(255)</td><td>TEXT</td></tr>
                <tr><td>Prices</td><td>DECIMAL(10,2)</td><td>FLOAT</td></tr>
                <tr><td>Dates</td><td>DATE</td><td>VARCHAR</td></tr>
                <tr><td>Boolean</td><td>BOOLEAN/TINYINT(1)</td><td>VARCHAR</td></tr>
            </tbody>
        </table>

        <h3>3. Add Constraints</h3>
        <pre><code class="language-sql">CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL CHECK (total >= 0),
    status ENUM('pending', 'paid', 'shipped', 'delivered'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);
</code></pre>

        <h2>Maintenance Best Practices</h2>

        <h3>1. Regular Index Maintenance</h3>
        <pre><code class="language-sql">-- Analyze tables regularly
ANALYZE TABLE employees;

-- Optimize tables
OPTIMIZE TABLE orders;

-- Check for unused indexes
SHOW INDEX FROM table_name;
</code></pre>

        <h3>2. Monitor Query Performance</h3>
        <pre><code class="language-sql">-- Enable slow query log (MySQL)
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;  -- Log queries > 2 seconds

-- Use EXPLAIN to analyze
EXPLAIN SELECT * FROM employees WHERE department = 'IT';
</code></pre>

        <h3>3. Regular Backups</h3>
        <ul>
            <li>Automate daily backups</li>
            <li>Test restore procedures</li>
            <li>Keep multiple backup generations</li>
            <li>Store offsite copies</li>
        </ul>

        <h2>Transaction Best Practices</h2>

        <h3>1. Keep Transactions Short</h3>
        <pre><code class="language-sql">-- Bad - long transaction
BEGIN;
SELECT * FROM large_table FOR UPDATE; -- Locks many rows
-- ... lots of processing ...
UPDATE large_table SET ...;
COMMIT;

-- Good - minimize lock time
BEGIN;
UPDATE specific_rows SET ... WHERE id IN (1,2,3);
COMMIT;
</code></pre>

        <h3>2. Use Appropriate Isolation Levels</h3>
        <pre><code class="language-sql">-- For read-heavy operations
SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

-- For critical financial operations
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
</code></pre>

        <div class="try-it-box">
            <h3 class="text-xl font-bold mb-2">✅ Apply Best Practices!</h3>
            <p class="mb-4">Review your existing queries against these best practices. Optimize and secure them in our compiler!</p>
            <a href="/#compiler" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Try SQL Compiler →
            </a>
        </div>

        <h2>Quick Checklist</h2>
        <ul>
            <li>✅ Used prepared statements for user input?</li>
            <li>✅ Added indexes on WHERE/JOIN columns?</li>
            <li>✅ Avoided SELECT * in production?</li>
            <li>✅ Used appropriate data types?</li>
            <li>✅ Kept transactions short?</li>
            <li>✅ Added foreign key constraints?</li>
            <li>✅ Formatted queries consistently?</li>
            <li>✅ Tested with realistic data volumes?</li>
        </ul>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-functions" class="text-purple-600 hover:text-purple-800">→ SQL Functions Guide</a>
                <a href="/learn/common-sql-mistakes" class="text-purple-600 hover:text-purple-800">→ Common SQL Mistakes</a>
                <a href="/learn/sql-interview-questions" class="text-purple-600 hover:text-purple-800">→ Interview Questions</a>
                <a href="/learn/sql-use-cases" class="text-purple-600 hover:text-purple-800">→ Real-World Use Cases</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/includes/learn_footer.php'; ?>
