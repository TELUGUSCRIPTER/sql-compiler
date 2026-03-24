<?php
$pageTitle = 'Common SQL Mistakes and How to Avoid Them';
$metaDescription = 'Learn about the most common SQL mistakes developers make and how to avoid them. Fix errors, prevent bugs, and write better SQL queries.';
$metaKeywords = 'SQL mistakes, SQL errors, SQL debugging, SQL antipatterns, SQL problems';
$canonicalUrl = 'https://sqlcompiler.shop/learn/common-sql-mistakes';
$breadcrumbs = [
    ['title' => 'Learn', 'url' => '/learn/sql-basics-tutorial'],
    ['title' => 'Common SQL Mistakes']
];

require_once __DIR__ . '/includes/learn_header.php';
require_once __DIR__ . '/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">Common SQL Mistakes and How to Avoid Them</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            Even experienced developers make SQL mistakes that lead to bugs, security vulnerabilities, and performance problems. This guide covers the 20 most common SQL errors and anti-patterns, with clear examples of what not to do and how to fix them.
        </p>

        <h2>1. Not Using Prepared Statements (SQL Injection)</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-php">$query = "SELECT * FROM users WHERE email = '$_POST[email]'";
$result = mysqli_query($conn, $query);
</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-php">$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$_POST['email']]);
</code></pre>

        <p><strong>Why:</strong> First version is vulnerable to SQL injection attacks. Always use prepared statements!</p>

        <h2>2. Using SELECT * in Production</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">SELECT * FROM employees;</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">SELECT id, name, email, department FROM employees;</code></pre>

        <p><strong>Why:</strong> SELECT * wastes bandwidth, breaks when columns are added/removed, and hurts performance.</p>

        <h2>3. Not Using Indexes on JOIN/WHERE Columns</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- No index on customer_id
SELECT * FROM orders WHERE customer_id = 123;</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">CREATE INDEX idx_customer_id ON orders(customer_id);
SELECT * FROM orders WHERE customer_id = 123;</code></pre>

        <p><strong>Why:</strong> Without indexes, database performs full table scans (slow!).</p>

        <h2>4. Using Functions on Indexed Columns</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">SELECT * FROM orders WHERE YEAR(created_at) = 2024;</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">SELECT * FROM orders 
WHERE created_at >= '2024-01-01' 
  AND created_at < '2025-01-01';</code></pre>

        <p><strong>Why:</strong> Functions prevent index usage, forcing full table scan.</p>

        <h2>5. Forgetting WHERE in UPDATE/DELETE</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">DELETE FROM users;  -- Deletes EVERYTHING!</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">DELETE FROM users WHERE id = 123;</code></pre>

        <p><strong>Why:</strong> Without WHERE, all rows are affected. Always double-check DELETE/UPDATE!</p>

        <h2>6. Not Handling NULL Properly</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">SELECT * FROM users WHERE phone = NULL;  -- Returns nothing!</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">SELECT * FROM users WHERE phone IS NULL;</code></pre>

        <p><strong>Why:</strong> NULL requires IS NULL or IS NOT NULL, not = or !=.</p>

        <h2>7. Using FLOAT for Money</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">CREATE TABLE products (price FLOAT);</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">CREATE TABLE products (price DECIMAL(10,2));</code></pre>

        <p><strong>Why:</strong> FLOAT has precision errors. Use DECIMAL for currency!</p>

        <h2>8. Creating Too Many Indexes</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Index every column "just in case"
CREATE INDEX idx1 ON users(id);
CREATE INDEX idx2 ON users(email);
CREATE INDEX idx3 ON users(name);
CREATE INDEX idx4 ON users(created_at);
-- etc...</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">-- Only index columns actually used in WHERE/JOIN
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_created_at ON users(created_at);</code></pre>

        <p><strong>Why:</strong> Each index slows INSERT/UPDATE. Only index what you query!</p>

        <h2>9. Not Using Transactions</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">UPDATE accounts SET balance = balance - 100 WHERE id = 1;
UPDATE accounts SET balance = balance + 100 WHERE id = 2;
-- What if second UPDATE fails?</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">BEGIN;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
UPDATE accounts SET balance = balance + 100 WHERE id = 2;
COMMIT;</code></pre>

        <p><strong>Why:</strong> Transactions ensure all-or-nothing execution.</p>

        <h2>10. Using DISTINCT as a Band-Aid</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Adding DISTINCT to hide duplicate bug
SELECT DISTINCT customer_id FROM orders;</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">-- Fix the root cause (bad JOIN, missing GROUP BY, etc.)
SELECT customer_id FROM  orders GROUP BY customer_id;</code></pre>

        <p><strong>Why:</strong> DISTINCT hides problems. Find and fix the actual cause!</p>

        <h2>11. Inefficient Pagination</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Very slow for large offsets
SELECT * FROM products 
ORDER BY id 
LIMIT 100 OFFSET 100000;  -- Skips 100k rows!</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">-- Use WHERE with last seen ID
SELECT * FROM products 
WHERE id > 100000 
ORDER BY id 
LIMIT 100;</code></pre>

        <p><strong>Why:</strong> Large OFFSET still reads all skipped rows. Use cursor-based pagination!</p>

        <h2>12. COUNT(*) for Existence Check</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">SELECT COUNT(*) FROM users WHERE email = 'test@example.com';</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">SELECT EXISTS(SELECT 1 FROM users WHERE email = 'test@example.com');</code></pre>

        <p><strong>Why:</strong> EXISTS stops at first match. COUNT reads all matching rows!</p>

        <h2>13. Not Validating Data Types</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Storing dates as strings
CREATE TABLE events (event_date VARCHAR(20));</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">CREATE TABLE events (event_date DATE);</code></pre>

        <p><strong>Why:</strong> Proper data types enable validation, indexing, and date math!</p>

        <h2>14. Inefficient Subqueries</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">SELECT * FROM employees 
WHERE id IN (SELECT employee_id FROM salaries WHERE amount > 50000);</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">SELECT e.* FROM employees e
JOIN salaries s ON e.id = s.employee_id
WHERE s.amount > 50000;</code></pre>

        <p><strong>Why:</strong> JOINs are usually faster than subqueries in WHERE.</p>

        <h2>15. Not Using EXPLAIN</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Deploy slow query without testing
SELECT * FROM huge_table WHERE unindexed_column = 'value';</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">-- Check execution plan first
EXPLAIN SELECT * FROM huge_table WHERE unindexed_column = 'value';
-- See type: ALL (full scan) → Add index!</code></pre>

        <p><strong>Why:</strong> EXPLAIN reveals performance problems before they hit production.</p>

        <h2>16. Storing Arrays/JSON in Strings</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Storing as comma-separated string
CREATE TABLE users (tags VARCHAR(255));  -- 'tag1,tag2,tag3'</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">-- Use proper many-to-many relationship
CREATE TABLE user_tags (
    user_id INT,
    tag_id INT,
    PRIMARY KEY (user_id, tag_id)
);</code></pre>

        <p><strong>Why:</strong> Strings can't be properly queried, indexed, or validated.</p>

        <h2>17. Ignoring Character Sets</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">CREATE TABLE posts (content TEXT);  -- Default charset?</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">CREATE TABLE posts (
    content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
);</code></pre>

        <p><strong>Why:</strong> utf8mb4 supports emojis and international characters!</p>

        <h2>18. Not Backing Up Before Major Changes</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">-- Run directly in production
ALTER TABLE users DROP COLUMN email;  -- YOLO!</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">-- 1. Backup first
-- 2. Test in staging
-- 3. Then apply to production with rollback plan</code></pre>

        <p><strong>Why:</strong> Always have a way to undo changes!</p>

        <h2>19. Using OR Instead of IN</h2>
        <h3>❌ Wrong</h3>
        <pre><code class="language-sql">SELECT * FROM users 
WHERE city = 'NYC' OR city = 'LA' OR city = 'Chicago' OR city = 'Boston';</code></pre>

        <h3>✅ Correct</h3>
        <pre><code class="language-sql">SELECT * FROM users WHERE city IN ('NYC', 'LA', 'Chicago', 'Boston');</code></pre>

        <p><strong>Why:</strong> IN is cleaner and can be optimized better.</p>

        <h2>20. Not Monitoring Query Performance</h2>
        <h3>❌ Wrong</h3>
        <p>Deploy and forget. Hope it works!</p>

        <h3>✅ Correct</h3>
        <ul>
            <li>Enable slow query log</li>
            <li>Monitor query execution times</li>
            <li>Set up alerts for slow queries</li>
            <li>Regularly review and optimize</li>
        </ul>

        <div class="try-it-box">
            <h3 class="text-xl font-bold mb-2">🔍 Check Your Queries!</h3>
            <p class="mb-4">Review your existing SQL code for these mistakes. Test fixes in our compiler!</p>
            <a href="/#compiler" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Open SQL Compiler →
            </a>
        </div>

        <h2>Key Takeaways</h2>
        <ul>
            <li>Always use prepared statements (never concatenate user input!)</li>
            <li>Specify columns explicitly (avoid SELECT *)</li>
            <li>Add indexes strategically (WHERE/JOIN columns)</li>
            <li>Use proper data types (DECIMAL for money, DATE for dates)</li>
            <li>Test with EXPLAIN before deploying</li>
            <li>Back up before making changes</li>
            <li>Use transactions for related operations</li>
            <li>Monitor performance in production</li>
        </ul>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-best-practices" class="text-purple-600 hover:text-purple-800">→ SQL Best Practices</a>
                <a href="/learn/sql-interview-questions" class="text-purple-600 hover:text-purple-800">→ Interview Questions</a>
                <a href="/learn/sql-basics-tutorial" class="text-purple-600 hover:text-purple-800">→ SQL Basics Tutorial</a>
                <a href="/learn/sql-practice-exercises" class="text-purple-600 hover:text-purple-800">→ Practice Exercises</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/includes/learn_footer.php'; ?>
