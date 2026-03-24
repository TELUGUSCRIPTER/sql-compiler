<?php
$pageTitle = "Top 50 SQL Queries for Practice - With Examples and Output (2026)";
$metaDescription = "Top 50 SQL queries for practice with examples and expected output. Covers SELECT, WHERE, JOINs, GROUP BY, subqueries, and window functions. Perfect for SQL interview prep.";
$metaKeywords = "top 50 SQL queries for practice, sql practice questions with answers, SQL query examples, common SQL queries, SQL hands-on practice, SQL problems with solutions";
$canonicalUrl = "https://sqlcompiler.shop/learn/top-50-sql-queries";
include("includes/learn_header.php");
?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<nav class="text-sm text-gray-500 mb-6">
  <ol class="flex items-center space-x-2">
    <li><a href="/" class="hover:text-purple-600">Home</a></li>
    <li><span class="mx-2">/</span></li>
    <li><a href="/learn/index" class="hover:text-purple-600">Learn SQL</a></li>
    <li><span class="mx-2">/</span></li>
    <li class="text-gray-900 font-medium">Top 50 SQL Queries</li>
  </ol>
</nav>

<article class="content-body">
  <div class="mb-4 flex items-center gap-3 flex-wrap">
    <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">REFERENCE GUIDE</span>
    <span class="text-xs text-gray-400">Last Updated: March 2026 &bull; 50 Queries</span>
    <span class="text-xs text-gray-400">By SQL Compiler Team</span>
  </div>

  <h1>Top 50 SQL Queries for Practice (with Examples and Output)</h1>

  <p>This reference guide lists the <strong>50 most important SQL queries</strong> that every developer, analyst, and data professional should know. Each query includes the exact SQL syntax, an explanation of what it does, and in many cases, the expected output. Run any query directly in our <a href="../editor.php" class="text-purple-600 font-medium">free online SQL compiler</a>.</p>

  <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 my-6">
    <h3 class="font-bold text-gray-800 mb-3">Quick Setup — Run This First</h3>
    <p class="text-sm text-gray-600 mb-3">To run the examples below, set up this sample database first (copy-paste into our compiler):</p>
    <pre><code class="language-sql">CREATE TABLE employees (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), dept VARCHAR(50), salary INT, city VARCHAR(50), hire_date DATE);
INSERT INTO employees (name,dept,salary,city,hire_date) VALUES ('Alice','Engineering',85000,'New York','2020-03-15'),('Bob','Marketing',62000,'Chicago','2019-07-22'),('Carol','Engineering',91000,'New York','2018-01-10'),('David','HR',55000,'Houston','2021-05-30'),('Eva','Marketing',70000,'Chicago','2020-11-01'),('Frank','Engineering',98000,'San Francisco','2017-09-14'),('Grace','HR',57000,'New York','2022-02-28'),('Henry','Finance',80000,'Chicago','2019-04-12');
CREATE TABLE orders (id INT AUTO_INCREMENT PRIMARY KEY, emp_id INT, product VARCHAR(100), amount DECIMAL(10,2), order_date DATE);
INSERT INTO orders (emp_id,product,amount,order_date) VALUES (1,'Laptop',1200,'2025-01-10'),(2,'Phone',800,'2025-01-15'),(1,'Mouse',25,'2025-02-01'),(3,'Monitor',450,'2025-02-10'),(5,'Keyboard',75,'2025-03-01'),(2,'Laptop',1200,'2025-03-15');</code></pre>
  </div>

  <h2>1-10: Basic SELECT Queries</h2>
  <pre><code class="language-sql">-- 1. Select all rows and columns
SELECT * FROM employees;

-- 2. Select specific columns only
SELECT name, dept, salary FROM employees;

-- 3. Alias columns for cleaner output
SELECT name AS "Employee", salary AS "Annual Pay" FROM employees;

-- 4. Get distinct values (no duplicates)
SELECT DISTINCT dept FROM employees;

-- 5. Count total number of rows
SELECT COUNT(*) AS total_employees FROM employees;

-- 6. Filter with WHERE
SELECT name, salary FROM employees WHERE dept = 'Engineering';

-- 7. Filter with multiple conditions (AND)
SELECT name FROM employees WHERE dept = 'Engineering' AND salary > 90000;

-- 8. Filter with OR using IN
SELECT name, city FROM employees WHERE city IN ('New York', 'Chicago');

-- 9. Pattern matching with LIKE
SELECT name FROM employees WHERE name LIKE '%a%';  -- Name contains 'a'

-- 10. Range filter with BETWEEN
SELECT name, salary FROM employees WHERE salary BETWEEN 60000 AND 85000;</code></pre>

  <h2>11-20: Sorting, Limiting, and Math</h2>
  <pre><code class="language-sql">-- 11. Sort descending then ascending tiebreak
SELECT name, salary FROM employees ORDER BY salary DESC, name ASC;

-- 12. Top 3 highest-paid employees
SELECT name, salary FROM employees ORDER BY salary DESC LIMIT 3;

-- 13. Pagination: skip first 5 rows, get next 3
SELECT name, salary FROM employees ORDER BY salary DESC LIMIT 3 OFFSET 5;

-- 14. Calculate derived column (salary after 10% raise)
SELECT name, salary, salary * 1.10 AS new_salary FROM employees;

-- 15. Conditional logic with CASE
SELECT name, salary,
  CASE
    WHEN salary >= 90000 THEN 'Senior'
    WHEN salary >= 70000 THEN 'Mid-Level'
    ELSE 'Junior'
  END AS level
FROM employees;

-- 16. NULL handling with COALESCE
SELECT name, COALESCE(city, 'Unknown') AS city FROM employees;

-- 17. String concatenation
SELECT CONCAT(name, ' - ', dept) AS full_label FROM employees;

-- 18. String functions
SELECT UPPER(name), LOWER(dept), LENGTH(name) FROM employees;

-- 19. Date functions
SELECT name, hire_date, YEAR(hire_date) AS year_hired, DATEDIFF(NOW(), hire_date) AS days_at_company FROM employees;

-- 20. Round numeric values
SELECT name, ROUND(salary / 12, 2) AS monthly_salary FROM employees;</code></pre>

  <h2>21-30: GROUP BY and Aggregate Functions</h2>
  <pre><code class="language-sql">-- 21. Count employees per department
SELECT dept, COUNT(*) AS headcount FROM employees GROUP BY dept ORDER BY headcount DESC;

-- 22. Average salary per department
SELECT dept, ROUND(AVG(salary), 0) AS avg_salary FROM employees GROUP BY dept;

-- 23. Total payroll per department
SELECT dept, SUM(salary) AS total_payroll FROM employees GROUP BY dept;

-- 24. Min and Max salary per department
SELECT dept, MIN(salary) AS lowest, MAX(salary) AS highest FROM employees GROUP BY dept;

-- 25. Departments with more than 2 employees (HAVING)
SELECT dept, COUNT(*) AS cnt FROM employees GROUP BY dept HAVING COUNT(*) > 2;

-- 26. Departments where average salary exceeds 70,000
SELECT dept, ROUND(AVG(salary),0) AS avg FROM employees GROUP BY dept HAVING AVG(salary) > 70000;

-- 27. Employees hired per year
SELECT YEAR(hire_date) AS year, COUNT(*) AS hired FROM employees GROUP BY YEAR(hire_date) ORDER BY year;

-- 28. Group with WHERE (filter before grouping) and HAVING (filter after)
SELECT dept, COUNT(*) AS cnt
FROM employees
WHERE city != 'Houston'
GROUP BY dept
HAVING COUNT(*) >= 2;

-- 29. Salary statistics company-wide
SELECT COUNT(*) AS total, MIN(salary) AS min, MAX(salary) AS max, ROUND(AVG(salary),0) AS avg, SUM(salary) AS total_payroll FROM employees;

-- 30. Find departments where total orders exceed 1000
SELECT e.dept, SUM(o.amount) AS total_orders
FROM employees e
JOIN orders o ON e.id = o.emp_id
GROUP BY e.dept
HAVING SUM(o.amount) > 1000;</code></pre>

  <h2>31-40: SQL JOINs</h2>
  <pre><code class="language-sql">-- 31. INNER JOIN - employees with their orders
SELECT e.name, o.product, o.amount
FROM employees e
INNER JOIN orders o ON e.id = o.emp_id;

-- 32. LEFT JOIN - all employees, even those with no orders
SELECT e.name, o.product
FROM employees e
LEFT JOIN orders o ON e.id = o.emp_id
ORDER BY e.name;

-- 33. Find employees who have NEVER placed an order (anti-join)
SELECT e.name
FROM employees e
LEFT JOIN orders o ON e.id = o.emp_id
WHERE o.id IS NULL;

-- 34. Total amount spent per employee (JOIN + GROUP BY)
SELECT e.name, COUNT(o.id) AS orders, SUM(o.amount) AS total_spent
FROM employees e
LEFT JOIN orders o ON e.id = o.emp_id
GROUP BY e.id, e.name
ORDER BY total_spent DESC;

-- 35. Self-join example: find employees in the same city
SELECT a.name AS employee1, b.name AS employee2, a.city
FROM employees a
INNER JOIN employees b ON a.city = b.city AND a.id < b.id
ORDER BY a.city, a.name;

-- 36. INNER JOIN with WHERE filter
SELECT e.name, o.product, o.amount
FROM employees e
JOIN orders o ON e.id = o.emp_id
WHERE o.amount > 500;

-- 37. Join and aggregate: top spender per department
SELECT e.dept, e.name, SUM(o.amount) AS total_spent
FROM employees e
JOIN orders o ON e.id = o.emp_id
GROUP BY e.dept, e.id, e.name
ORDER BY e.dept, total_spent DESC;

-- 38. cross department orders: employees who ordered "Laptop"
SELECT DISTINCT e.name, e.dept
FROM employees e
JOIN orders o ON e.id = o.emp_id
WHERE o.product = 'Laptop';

-- 39. Average order value per department
SELECT e.dept, COUNT(o.id) AS order_count, ROUND(AVG(o.amount),2) AS avg_order
FROM employees e
JOIN orders o ON e.id = o.emp_id
GROUP BY e.dept;

-- 40. Employees who placed more than 1 order
SELECT e.name, COUNT(o.id) AS order_count
FROM employees e
JOIN orders o ON e.id = o.emp_id
GROUP BY e.id, e.name
HAVING COUNT(o.id) > 1;</code></pre>

  <h2>41-50: Advanced Queries (Subqueries & Window Functions)</h2>
  <pre><code class="language-sql">-- 41. Subquery: employees earning above company average
SELECT name, salary
FROM employees
WHERE salary > (SELECT AVG(salary) FROM employees);

-- 42. Subquery: second highest salary
SELECT MAX(salary) AS second_highest
FROM employees
WHERE salary < (SELECT MAX(salary) FROM employees);

-- 43. EXISTS: employees who have placed at least one order
SELECT name FROM employees e
WHERE EXISTS (SELECT 1 FROM orders o WHERE o.emp_id = e.id);

-- 44. NOT IN: employees with no orders
SELECT name FROM employees
WHERE id NOT IN (SELECT DISTINCT emp_id FROM orders);

-- 45. Rank employees by salary (no gaps in ties)
SELECT name, dept, salary,
  DENSE_RANK() OVER (ORDER BY salary DESC) AS rank_overall
FROM employees;

-- 46. Rank employees by salary WITHIN department
SELECT name, dept, salary,
  RANK() OVER (PARTITION BY dept ORDER BY salary DESC) AS dept_rank
FROM employees;

-- 47. ROW_NUMBER: top-1 per department
SELECT name, dept, salary
FROM (
  SELECT name, dept, salary,
    ROW_NUMBER() OVER (PARTITION BY dept ORDER BY salary DESC) AS rn
  FROM employees
) t WHERE rn = 1;

-- 48. Running total of payroll by hire date
SELECT name, hire_date, salary,
  SUM(salary) OVER (ORDER BY hire_date ROWS UNBOUNDED PRECEDING) AS running_payroll
FROM employees;

-- 49. CTE: departments above company average salary
WITH company_avg AS (SELECT AVG(salary) AS avg_sal FROM employees)
SELECT dept, ROUND(AVG(salary),0) AS dept_avg
FROM employees, company_avg
GROUP BY dept
HAVING AVG(salary) > company_avg.avg_sal;

-- 50. CASE inside aggregate: salary breakdown by tier
SELECT
  SUM(CASE WHEN salary >= 90000 THEN 1 ELSE 0 END) AS senior_count,
  SUM(CASE WHEN salary BETWEEN 70000 AND 89999 THEN 1 ELSE 0 END) AS mid_count,
  SUM(CASE WHEN salary < 70000 THEN 1 ELSE 0 END) AS junior_count
FROM employees;</code></pre>

  <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl p-8 my-10 text-white">
    <h3 class="text-xl font-bold mb-2">Practice These 50 Queries Live</h3>
    <p class="mb-4 text-green-100">Open our free online SQL compiler and run every query above in your own isolated database environment.</p>
    <div class="flex flex-wrap gap-3">
      <a href="../editor.php" class="bg-white text-green-700 font-bold px-5 py-2 rounded-lg hover:bg-green-50 transition">Open SQL Compiler</a>
      <a href="sql-practice-intermediate" class="border border-white text-white font-medium px-5 py-2 rounded-lg hover:bg-white/10 transition">Practice Exercises</a>
      <a href="sql-interview-questions" class="border border-white text-white font-medium px-5 py-2 rounded-lg hover:bg-white/10 transition">Interview Questions</a>
    </div>
  </div>

  <h2>Frequently Asked Questions</h2>
  <div class="space-y-4">
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What are the most important SQL queries to know?</summary>
      <p class="mt-3 text-gray-600 text-sm">The most essential SQL queries are: SELECT with filtering (WHERE, LIKE, IN, BETWEEN), GROUP BY with aggregates (COUNT, SUM, AVG), all JOIN types (INNER, LEFT, RIGHT), subqueries (correlated and scalar), and window functions (RANK, ROW_NUMBER, LAG). Mastering these 5 categories covers 95% of real-world and interview use cases.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">How do I find the second highest salary in SQL?</summary>
      <p class="mt-3 text-gray-600 text-sm">Use a subquery: SELECT MAX(salary) FROM employees WHERE salary &lt; (SELECT MAX(salary) FROM employees). Alternatively, use DENSE_RANK(): SELECT salary FROM (SELECT salary, DENSE_RANK() OVER (ORDER BY salary DESC) AS rk FROM employees) t WHERE rk = 2 LIMIT 1. The window function approach is more robust and handles duplicates correctly.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What is the SQL query to get records with duplicate values?</summary>
      <p class="mt-3 text-gray-600 text-sm">SELECT column_name, COUNT(*) FROM table_name GROUP BY column_name HAVING COUNT(*) > 1. This groups by the column you want to check for duplicates, then returns only groups with more than one occurrence.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">How do I run SQL queries online without installing anything?</summary>
      <p class="mt-3 text-gray-600 text-sm">Use <a href="/" class="text-purple-600">SQLCompiler.shop</a> — a completely free online SQL editor. No downloads, no registration required for basic use. Create tables, insert data, and run any of the 50 queries above instantly in your browser.</p>
    </details>
  </div>

  <div class="mt-10 bg-gray-50 rounded-xl p-6 border border-gray-200">
    <h3 class="font-bold text-gray-800 mb-4">More SQL Practice &amp; Learning</h3>
    <div class="grid grid-cols-2 gap-3 text-sm">
      <a href="sql-practice-beginner" class="text-purple-600 hover:underline">Beginner SQL Practice</a>
      <a href="sql-practice-intermediate" class="text-purple-600 hover:underline">Intermediate SQL Practice</a>
      <a href="sql-practice-advanced" class="text-purple-600 hover:underline">Advanced SQL Practice</a>
      <a href="sql-interview-questions" class="text-purple-600 hover:underline">SQL Interview Questions</a>
      <a href="how-to-prepare-sql-interviews" class="text-purple-600 hover:underline">Interview Prep Guide</a>
      <a href="sql-cheat-sheet" class="text-purple-600 hover:underline">SQL Cheat Sheet</a>
    </div>
  </div>
</article>
</div>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"What are the most important SQL queries to know?","acceptedAnswer":{"@type":"Answer","text":"The most essential SQL queries are: SELECT with filtering, GROUP BY with aggregates (COUNT, SUM, AVG), all JOIN types (INNER, LEFT, RIGHT), subqueries, and window functions (RANK, ROW_NUMBER, LAG)."}},{"@type":"Question","name":"How do I find the second highest salary in SQL?","acceptedAnswer":{"@type":"Answer","text":"Use: SELECT MAX(salary) FROM employees WHERE salary < (SELECT MAX(salary) FROM employees). Or use DENSE_RANK() window function for a more robust solution."}},{"@type":"Question","name":"How do I run SQL queries online without installing anything?","acceptedAnswer":{"@type":"Answer","text":"Use SQLCompiler.shop - a completely free online SQL editor. No downloads or registration required. Create tables and run queries instantly in your browser."}}]}
</script>
<?php include("includes/learn_footer.php"); ?>
