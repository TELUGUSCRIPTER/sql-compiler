<?php
$pageTitle = 'SQL Functions Complete Guide - Aggregate, String, Date Functions';
$metaDescription = 'Master SQL functions with this comprehensive guide. Learn aggregate functions (COUNT, SUM, AVG), string functions (CONCAT, SUBSTRING), date functions, and more with examples.';
$metaKeywords = 'SQL functions, aggregate functions, string functions, date functions, COUNT, SUM, AVG, CONCAT';
$canonicalUrl = 'https://sqlcompiler.shop/learn/sql-functions';
$breadcrumbs = [
    ['title' => 'Learn', 'url' => '/learn/sql-basics-tutorial'],
    ['title' => 'SQL Functions Guide']
];

require_once __DIR__ . '/includes/learn_header.php';
require_once __DIR__ . '/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">SQL Functions Complete Guide</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            SQL functions are built-in operations that perform calculations, manipulate data, and transform results. Understanding SQL functions is essential for writing powerful queries that can aggregate data, format output, and perform complex calculations. This comprehensive guide covers all major SQL function categories with practical examples.
        </p>

        <div class="bg-purple-50 border-l-4 border-purple-600 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">📑 Table of Contents</h2>
            <ul class="space-y-2">
                <li><a href="#aggregate" class="text-purple-600 hover:text-purple-800">Aggregate Functions</a></li>
                <li><a href="#string" class="text-purple-600 hover:text-purple-800">String Functions</a></li>
                <li><a href="#date" class="text-purple-600 hover:text-purple-800">Date & Time Functions</a></li>
                <li><a href="#numeric" class="text-purple-600 hover:text-purple-800">Numeric Functions</a></li>
                <li><a href="#conversion" class="text-purple-600 hover:text-purple-800">Conversion Functions</a></li>
            </ul>
        </div>

        <h2 id="aggregate">Aggregate Functions</h2>
        <p>
            Aggregate functions perform calculations on a set of values and return a single value. They are commonly used with GROUP BY clause to group results.
        </p>

        <h3>COUNT() - Count Rows</h3>
        <pre><code class="language-sql">-- Count all rows
SELECT COUNT(*) FROM employees;

-- Count non-NULL values
SELECT COUNT(email) FROM employees;

-- Count distinct values
SELECT COUNT(DISTINCT department) FROM employees;

-- Count with condition
SELECT COUNT(*) FROM orders WHERE status = 'completed';
</code></pre>

        <h3>SUM() - Calculate Total</h3>
        <pre><code class="language-sql">-- Total sales
SELECT SUM(amount) FROM orders;

-- Sum by group
SELECT department, SUM(salary) as total_salary
FROM employees
GROUP BY department;

-- Sum with condition
SELECT SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as paid_total
FROM invoices;
</code></pre>

        <h3>AVG() - Calculate Average</h3>
        <pre><code class="language-sql">-- Average salary
SELECT AVG(salary) FROM employees;

-- Average by department
SELECT department, AVG(salary) as avg_salary
FROM employees
GROUP BY department;

-- Round average to 2 decimals
SELECT ROUND(AVG(price), 2) as avg_price FROM products;
</code></pre>

        <h3>MAX() and MIN() - Find Extremes</h3>
        <pre><code class="language-sql">-- Highest and lowest salary
SELECT MAX(salary) as highest, MIN(salary) as lowest
FROM employees;

-- Latest order date
SELECT MAX(order_date) as latest_order FROM orders;

-- Oldest employee
SELECT name, MIN(hire_date) as first_hired
FROM employees;
</code></pre>

        <h3>GROUP_CONCAT() - Concatenate Group Values</h3>
        <pre><code class="language-sql">-- List all employees per department (MySQL)
SELECT department, GROUP_CONCAT(name) as employees
FROM employees
GROUP BY department;

-- With custom separator
SELECT department, GROUP_CONCAT(name SEPARATOR '; ') as employees
FROM employees
GROUP BY department;
</code></pre>

        <h2 id="string">String Functions</h2>

        <h3>CONCAT() - Combine Strings</h3>
        <pre><code class="language-sql">-- Concatenate first and last name
SELECT CONCAT(first_name, ' ', last_name) as full_name
FROM employees;

-- Concatenate with literal
SELECT CONCAT('Employee: ', name, ' (', department, ')') as info
FROM employees;

-- CONCAT_WS (with separator)
SELECT CONCAT_WS('-', year, month, day) as date_string
FROM dates;
</code></pre>

        <h3>SUBSTRING() - Extract Substring</h3>
        <pre><code class="language-sql">-- Extract first 3 characters
SELECT SUBSTRING(name, 1, 3) FROM products;

-- Extract from position
SELECT SUBSTRING(email, 1, LOCATE('@', email) - 1) as username
FROM users;

-- RIGHT and LEFT
SELECT LEFT(code, 3) as prefix, RIGHT(code, 4) as suffix
FROM products;
</code></pre>

        <h3>UPPER() and LOWER() - Change Case</h3>
        <pre><code class="language-sql">-- Convert to uppercase
SELECT UPPER(name) FROM customers;

-- Convert to lowercase
SELECT LOWER(email) FROM users;

-- Mixed case
SELECT CONCAT(UPPER(LEFT(name, 1)), LOWER(SUBSTRING(name, 2))) as proper_case
FROM employees;
</code></pre>

        <h3>TRIM(), LTRIM(), RTRIM() - Remove Whitespace</h3>
        <pre><code class="language-sql">-- Remove leading and trailing spaces
SELECT TRIM(name) FROM customers;

-- Remove leading spaces only
SELECT LTRIM(name) FROM customers;

-- Remove trailing spaces only
SELECT RTRIM(name) FROM customers;

-- Remove specific characters
SELECT TRIM('_' FROM code) FROM products;
</code></pre>

        <h3>REPLACE() - Replace Text</h3>
        <pre><code class="language-sql">-- Replace text
SELECT REPLACE(description, 'old', 'new') FROM products;

-- Remove text (replace with empty)
SELECT REPLACE(phone, '-', '') as phone_number FROM contacts;
</code></pre>

        <h3>LENGTH() and CHAR_LENGTH() - String Length</h3>
        <pre><code class="language-sql">-- Character length
SELECT name, CHAR_LENGTH(name) as length FROM products;

-- Byte length (may differ for multi-byte characters)
SELECT LENGTH(description) as bytes FROM products;

-- Find long descriptions
SELECT * FROM products WHERE CHAR_LENGTH(description) > 100;
</code></pre>

        <h2 id="date">Date & Time Functions</h2>

        <h3>NOW(), CURDATE(), CURTIME() - Current Date/Time</h3>
        <pre><code class="language-sql">-- Current timestamp
SELECT NOW();

-- Current date
SELECT CURDATE();

-- Current time
SELECT CURTIME();

-- Insert with current timestamp
INSERT INTO logs (message, created_at) VALUES ('Log entry', NOW());
</code></pre>

        <h3>DATE(), TIME(), YEAR(), MONTH(), DAY()</h3>
        <pre><code class="language-sql">-- Extract date from datetime
SELECT DATE(created_at) FROM orders;

-- Extract time
SELECT TIME(created_at) FROM logs;

-- Extract year, month, day
SELECT YEAR(hire_date) as year, 
       MONTH(hire_date) as month,
       DAY(hire_date) as day
FROM employees;
</code></pre>

        <h3>DATE_ADD() and DATE_SUB() - Date Arithmetic</h3>
        <pre><code class="language-sql">-- Add days
SELECT DATE_ADD(CURDATE(), INTERVAL 7 DAY) as next_week;

-- Add months
SELECT DATE_ADD(start_date, INTERVAL 3 MONTH) as renewal_date
FROM subscriptions;

-- Subtract days
SELECT DATE_SUB(CURDATE(), INTERVAL 30 DAY) as last_month;

-- Add hours and minutes
SELECT DATE_ADD(NOW(), INTERVAL 2 HOUR) as two_hours_later;
</code></pre>

        <h3>DATEDIFF() - Date Difference</h3>
        <pre><code class="language-sql">-- Days between dates
SELECT DATEDIFF(end_date, start_date) as days
FROM projects;

-- Days since hire
SELECT name, DATEDIFF(CURDATE(), hire_date) as days_employed
FROM employees;

-- Age calculation
SELECT name, FLOOR(DATEDIFF(CURDATE(), birth_date) / 365) as age
FROM users;
</code></pre>

        <h3>DATE_FORMAT() - Format Dates</h3>
        <pre><code class="language-sql">-- Format date
SELECT DATE_FORMAT(order_date, '%Y-%m-%d') FROM orders;

-- Full date format
SELECT DATE_FORMAT(created_at, '%W, %M %d, %Y') as formatted_date
FROM posts;

-- Custom formats
SELECT DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') as timestamp;
</code></pre>

        <h2 id="numeric">Numeric Functions</h2>

        <h3>ROUND(), CEIL(), FLOOR()</h3>
        <pre><code class="language-sql">-- Round to 2 decimals
SELECT ROUND(price, 2) FROM products;

-- Round up (ceiling)
SELECT CEIL(4.3);  -- Returns 5

-- Round down (floor)
SELECT FLOOR(4.9);  -- Returns 4

-- Round average
SELECT department, ROUND(AVG(salary), 0) as avg_salary
FROM employees
GROUP BY department;
</code></pre>

        <h3>ABS(), POWER(), SQRT()</h3>
        <pre><code class="language-sql">-- Absolute value
SELECT ABS(-10);  -- Returns 10

-- Power
SELECT POWER(2, 3);  -- Returns 8

-- Square root
SELECT SQRT(16);  -- Returns 4

-- Calculate distance
SELECT SQRT(POWER(x2-x1, 2) + POWER(y2-y1, 2)) as distance
FROM coordinates;
</code></pre>

        <h3>MOD() - Modulo</h3>
        <pre><code class="language-sql">-- Find remainder
SELECT MOD(10, 3);  -- Returns 1

-- Find even numbers
SELECT * FROM numbers WHERE MOD(value, 2) = 0;

-- Find odd numbers
SELECT * FROM numbers WHERE MOD(value, 2) = 1;
</code></pre>

        <h2 id="conversion">Conversion Functions</h2>

        <h3>CAST() and CONVERT()</h3>
        <pre><code class="language-sql">-- Convert to integer
SELECT CAST('123' AS SIGNED);

-- Convert to decimal
SELECT CAST(price AS DECIMAL(10,2)) FROM products;

-- Convert to date
SELECT CAST('2024-01-15' AS DATE);

-- CONVERT (MySQL specific)
SELECT CONVERT('123', SIGNED INTEGER);
</code></pre>

        <h3>COALESCE() and IFNULL()</h3>
        <pre><code class="language-sql">-- Return first non-NULL value
SELECT COALESCE(phone, email, 'No contact') as contact FROM users;

-- IFNULL (return alternative if NULL)
SELECT name, IFNULL(middle_name, '') as middle FROM employees;

-- Provide default values
SELECT product, COALESCE(sale_price, regular_price) as final_price
FROM products;
</code></pre>

        <div class="try-it-box">
            <h3 class="text-xl font-bold mb-2">🚀 Practice SQL Functions!</h3>
            <p class="mb-4">Try these function examples in our interactive compiler. Experiment with different functions and see results instantly!</p>
            <a href="/#compiler" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Open SQL Compiler →
            </a>
        </div>

        <h2>Function Categories Summary</h2>
        <table>
            <thead>
                <tr><th>Category</th><th>Common Functions</th><th>Use Case</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>Aggregate</strong></td><td>COUNT, SUM, AVG, MAX, MIN</td><td>Statistical analysis</td></tr>
                <tr><td><strong>String</strong></td><td>CONCAT, SUBSTRING, UPPER, TRIM</td><td>Text manipulation</td></tr>
                <tr><td><strong>Date/Time</strong></td><td>NOW, DATE_ADD, DATEDIFF</td><td>Date calculations</td></tr>
                <tr><td><strong>Numeric</strong></td><td>ROUND, ABS, POWER, MOD</td><td>Math operations</td></tr>
                <tr><td><strong>Conversion</strong></td><td>CAST, CONVERT, COALESCE</td><td>Type conversion</td></tr>
            </tbody>
        </table>

        <h2>Best Practices</h2>
        <ul>
            <li>Use aggregate functions with GROUP BY for meaningful summaries</li>
            <li>Apply functions to columns in SELECT, not in WHERE (for performance)</li>
            <li>ROUND monetary values to 2 decimal places</li>
            <li>Use COALESCE to handle NULL values gracefully</li>
            <li>Combine functions for complex transformations</li>
            <li>Test functions with sample data first</li>
        </ul>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-basics-tutorial" class="text-purple-600 hover:text-purple-800">→ SQL Basics Tutorial</a>
                <a href="/learn/sql-cheat-sheet" class="text-purple-600 hover:text-purple-800">→ SQL Cheat Sheet</a>
                <a href="/learn/sql-subqueries" class="text-purple-600 hover:text-purple-800">→ SQL Subqueries</a>
                <a href="/learn/sql-best-practices" class="text-purple-600 hover:text-purple-800">→ SQL Best Practices</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/includes/learn_footer.php'; ?>
