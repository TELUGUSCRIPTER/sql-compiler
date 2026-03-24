<?php
$pageTitle = 'SQL Commands Cheat Sheet - Quick Reference Guide';
$metaDescription = 'Complete SQL cheat sheet with all essential commands, syntax, and examples. Quick reference for SELECT, INSERT, UPDATE, DELETE, JOIN, and more SQL operations.';
$metaKeywords = 'SQL cheat sheet, SQL commands, SQL reference, SQL syntax, SQL quick guide';
$canonicalUrl = 'https://sqlcompiler.shop/learn/sql-cheat-sheet';
$breadcrumbs = [
    ['title' => 'Learn', 'url' => '/learn/sql-basics-tutorial'],
    ['title' => 'SQL Cheat Sheet']
];

require_once __DIR__ . '/includes/learn_header.php';
require_once __DIR__ . '/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">SQL Cheat Sheet: Complete Command Reference</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            This comprehensive SQL cheat sheet provides quick access to the most commonly used SQL commands and syntax.  Whether you're a beginner learning SQL or an experienced developer needing a quick reference, this guide covers all essential SQL operations with clear examples.
        </p>

        <h2>Data Query Language (DQL)</h2>
        
        <h3>SELECT - Retrieve Data</h3>
        <pre><code class="language-sql">-- Basic SELECT
SELECT column1, column2 FROM table_name;

-- SELECT all columns
SELECT * FROM table_name;

-- SELECT with WHERE condition
SELECT * FROM users WHERE age > 25;

-- SELECT with multiple conditions
SELECT * FROM users WHERE age > 25 AND city = 'New York';

-- SELECT DISTINCT values
SELECT DISTINCT department FROM employees;

-- SELECT with alias
SELECT first_name AS name, salary AS income FROM employees;

-- SELECT with ORDER BY
SELECT * FROM products ORDER BY price DESC;

-- SELECT with LIMIT
SELECT * FROM customers LIMIT 10;

-- SELECT with OFFSET (pagination)
SELECT * FROM products LIMIT 10 OFFSET 20;
</code></pre>

        <h3>WHERE Clause Operators</h3>
        <table>
            <thead>
                <tr><th>Operator</th><th>Description</th><th>Example</th></tr>
            </thead>
            <tbody>
                <tr><td>=</td><td>Equal</td><td>WHERE age = 30</td></tr>
                <tr><td>!=</td><td>Not equal</td><td>WHERE status != 'active'</td></tr>
                <tr><td>&gt;</td><td>Greater than</td><td>WHERE salary &gt; 50000</td></tr>
                <tr><td>&lt;</td><td>Less than</td><td>WHERE price &lt; 100</td></tr>
                <tr><td>&gt;=</td><td>Greater or equal</td><td>WHERE age &gt;= 18</td></tr>
                <tr><td>&lt;=</td><td>Less or equal</td><td>WHERE stock &lt;= 10</td></tr>
                <tr><td>BETWEEN</td><td>Between range</td><td>WHERE age BETWEEN 25 AND 35</td></tr>
                <tr><td>LIKE</td><td>Pattern matching</td><td>WHERE name LIKE 'A%'</td></tr>
                <tr><td>IN</td><td>In list</td><td>WHERE city IN ('NY', 'LA')</td></tr>
                <tr><td>IS NULL</td><td>Is null</td><td>WHERE email IS NULL</td></tr>
                <tr><td>IS NOT NULL</td><td>Is not null</td><td>WHERE phone IS NOT NULL</td></tr>
            </tbody>
        </table>

        <h2>Data Manipulation Language (DML)</h2>

        <h3>INSERT - Add Data</h3>
        <pre><code class="language-sql">-- Insert single row
INSERT INTO users (name, email, age)
VALUES ('John Doe', 'john@email.com', 30);

-- Insert multiple rows
INSERT INTO users (name, email, age)
VALUES 
    ('Jane Smith', 'jane@email.com', 25),
    ('Bob Johnson', 'bob@email.com', 35);

--Insert from SELECT
INSERT INTO archived_users
SELECT * FROM users WHERE last_login < '2020-01-01';
</code></pre>

        <h3>UPDATE - Modify Data</h3>
        <pre><code class="language-sql">-- Update single column
UPDATE users SET email = 'newemail@example.com' WHERE id = 1;

-- Update multiple columns
UPDATE employees 
SET salary = 60000, department = 'Sales'
WHERE id = 5;

-- Update with calculation
UPDATE products SET price = price * 1.10 WHERE category = 'Electronics';

-- Update all rows (CAREFUL!)
UPDATE settings SET value = 'default';
</code></pre>

        <h3>DELETE - Remove Data</h3>
        <pre><code class="language-sql">-- Delete specific rows
DELETE FROM users WHERE id = 10;

-- Delete with condition
DELETE FROM orders WHERE order_date < '2020-01-01';

-- Delete all rows (DANGEROUS!)
DELETE FROM temp_data;

-- TRUNCATE (faster than DELETE for all rows)
TRUNCATE TABLE temp_data;
</code></pre>

        <h2>Data Definition Language (DDL)</h2>

        <h3>CREATE - Create Objects</h3>
        <pre><code class="language-sql">-- Create database
CREATE DATABASE my_database;

-- Create table
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    salary DECIMAL(10,2),
    hire_date DATE,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Create index
CREATE INDEX idx_email ON users(email);

-- Create unique index
CREATE UNIQUE INDEX idx_username ON users(username);
</code></pre>

        <h3>ALTER - Modify Objects</h3>
        <pre><code class="language-sql">-- Add column
ALTER TABLE users ADD COLUMN phone VARCHAR(20);

-- Modify column
ALTER TABLE users MODIFY COLUMN phone VARCHAR(30);

-- Drop column
ALTER TABLE users DROP COLUMN phone;

-- Rename table
ALTER TABLE old_name RENAME TO new_name;

-- Add constraint
ALTER TABLE orders ADD CONSTRAINT fk_customer 
FOREIGN KEY (customer_id) REFERENCES customers(id);
</code></pre>

        <h3>DROP - Delete Objects</h3>
        <pre><code class="language-sql">-- Drop database
DROP DATABASE database_name;

-- Drop table
DROP TABLE table_name;

-- Drop table if exists (no error if doesn't exist)
DROP TABLE IF EXISTS table_name;

-- Drop index
DROP INDEX index_name ON table_name;
</code></pre>

        <h2>Aggregate Functions</h2>

        <pre><code class="language-sql">-- COUNT - Count rows
SELECT COUNT(*) FROM users;
SELECT COUNT(DISTINCT city) FROM customers;

-- SUM - Sum values
SELECT SUM(salary) FROM employees;

-- AVG - Average value
SELECT AVG(price) FROM products;

-- MAX - Maximum value
SELECT MAX(salary) FROM employees;

-- MIN - Minimum value
SELECT MIN(price) FROM products;

-- GROUP BY with aggregates
SELECT department, COUNT(*), AVG(salary)
FROM employees
GROUP BY department;

-- HAVING - Filter groups
SELECT department, AVG(salary) as avg_sal
FROM employees
GROUP BY department
HAVING avg_sal > 50000;
</code></pre>

        <h2>JOIN Operations</h2>

        <pre><code class="language-sql">-- INNER JOIN
SELECT customers.name, orders.product
FROM customers
INNER JOIN orders ON customers.id = orders.customer_id;

-- LEFT JOIN
SELECT customers.name, orders.product
FROM customers
LEFT JOIN orders ON customers.id = orders.customer_id;

-- RIGHT JOIN
SELECT customers.name, orders.product
FROM customers
RIGHT JOIN orders ON customers.id = orders.customer_id;

-- Multiple JOINs
SELECT c.name, o.product, p.price
FROM customers c
JOIN orders o ON c.id = o.customer_id
JOIN products p ON o.product_id = p.id;
</code></pre>

        <h2>Subqueries</h2>

        <pre><code class="language-sql">-- Subquery in WHERE
SELECT name FROM employees
WHERE salary > (SELECT AVG(salary) FROM employees);

-- Subquery in FROM
SELECT dept, avg_salary
FROM (
    SELECT department as dept, AVG(salary) as avg_salary
    FROM employees
    GROUP BY department
) AS dept_stats
WHERE avg_salary > 50000;

-- EXISTS
SELECT name FROM customers c
WHERE EXISTS (
    SELECT 1 FROM orders o WHERE o.customer_id = c.id
);
</code></pre>

        <h2>String Functions</h2>

        <pre><code class="language-sql">-- CONCAT - Concatenate strings
SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM users;

-- UPPER / LOWER - Change case
SELECT UPPER(email), LOWER(name) FROM users;

-- SUBSTRING - Extract substring
SELECT SUBSTRING(email, 1, 5) FROM users;

-- LENGTH - String length
SELECT name, LENGTH(name) FROM products;

-- TRIM - Remove whitespace
SELECT TRIM(name) FROM users;

-- REPLACE - Replace text
SELECT REPLACE(description, 'old', 'new') FROM products;
</code></pre>

        <h2>Date Functions</h2>

        <pre><code class="language-sql">-- NOW() - Current date and time
SELECT NOW();

-- CURDATE() - Current date
SELECT CURDATE();

-- DATE - Extract date
SELECT DATE(created_at) FROM orders;

-- YEAR, MONTH, DAY
SELECT YEAR(hire_date), MONTH(hire_date), DAY(hire_date) FROM employees;

-- DATE_ADD / DATE_SUB
SELECT DATE_ADD(CURDATE(), INTERVAL 7 DAY);
SELECT DATE_SUB(order_date, INTERVAL 1 MONTH) FROM orders;

-- DATEDIFF - Difference between dates
SELECT DATEDIFF(end_date, start_date) FROM projects;
</code></pre>

        <h2>Conditional Logic</h2>

        <pre><code class="language-sql">-- CASE statement
SELECT name, salary,
    CASE 
        WHEN salary < 40000 THEN 'Low'
        WHEN salary BETWEEN 40000 AND 70000 THEN 'Medium'
        ELSE 'High'
    END AS salary_level
FROM employees;

-- IF statement (MySQL)
SELECT name, IF(status='active', 'Active User', 'Inactive') AS user_status
FROM users;

-- COALESCE - Return first non-null
SELECT name, COALESCE(phone, email, 'No contact') AS contact FROM users;
</code></pre>

        <h2>Constraints</h2>

        <table>
            <thead>
                <tr><th>Constraint</th><th>Description</th></tr>
            </thead>
            <tbody>
                <tr><td>PRIMARY KEY</td><td>Unique identifier for each row</td></tr>
                <tr><td>FOREIGN KEY</td><td>Links to primary key in another table</td></tr>
                <tr><td>UNIQUE</td><td>All values must be unique</td></tr>
                <tr><td>NOT NULL</td><td>Column cannot be null</td></tr>
                <tr><td>CHECK</td><td>Values must meet condition</td></tr>
                <tr><td>DEFAULT</td><td>Default value if none provided</td></tr>
                <tr><td>AUTO_INCREMENT</td><td>Automatically increment value</td></tr>
            </tbody>
        </table>

        <h2>Data Types (MySQL)</h2>

        <h3>Numeric Types</h3>
        <ul>
            <li><code>INT</code> - Integer</li>
            <li><code>BIGINT</code> - Large integer</li>
            <li><code>DECIMAL(M,D)</code> - Fixed-point number</li>
            <li><code>FLOAT</code> - Floating-point number</li>
            <li><code>DOUBLE</code> - Double-precision float</li>
        </ul>

        <h3>String Types</h3>
        <ul>
            <li><code>VARCHAR(n)</code> - Variable-length string (up to n)</li>
            <li><code>CHAR(n)</code> - Fixed-length string</li>
            <li><code>TEXT</code> - Long text</li>
            <li><code>ENUM('a','b','c')</code> - One of listed values</li>
        </ul>

        <h3>Date/Time Types</h3>
        <ul>
            <li><code>DATE</code> - Date (YYYY-MM-DD)</li>
            <li><code>DATETIME</code> - Date and time</li>
            <li><code>TIMESTAMP</code> - Timestamp</li>
            <li><code>TIME</code> - Time (HH:MM:SS)</li>
            <li><code>YEAR</code> - Year</li>
        </ul>

        <div class="try-it-box">
            <h3 class="text-xl font-bold mb-2">🚀 Try These Commands!</h3>
            <p class="mb-4">Practice all these SQL commands in our interactive online compiler. See results instantly!</p>
            <a href="/#compiler" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Open SQL Compiler →
            </a>
        </div>

        <h2>Tips & Best Practices</h2>
        <ul>
            <li>Always backup data before running UPDATE or DELETE</li>
            <li>Use WHERE clauses with UPDATE/DELETE to avoid modifying all rows</li>
            <li>Test queries on small datasets first</li>
            <li>Use EXPLAIN to analyze query performance</li>
            <li>Create indexes on frequently searched columns</li>
            <li>Use transactions for multiple related operations</li>
            <li>Avoid SELECT * in production code</li>
            <li>Use prepared statements to prevent SQL injection</li>
        </ul>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-basics-tutorial" class="text-purple-600 hover:text-purple-800">→ SQL Basics Tutorial</a>
                <a href="/learn/sql-data-types" class="text-purple-600 hover:text-purple-800">→ SQL Data Types Reference</a>
                <a href="/learn/sql-functions" class="text-purple-600 hover:text-purple-800">→  SQL Functions Guide</a>
                <a href="/learn/sql-best-practices" class="text-purple-600 hover:text-purple-800">→ SQL Best Practices</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/includes/learn_footer.php'; ?>
