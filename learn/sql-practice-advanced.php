<?php
$pageTitle = "Advanced SQL Practice Questions - Window Functions, CTEs, Optimization";
$metaDescription = "Advanced SQL practice problems with answers. 15 exercises covering Window Functions, CTEs, query optimization, and complex subqueries. SQL interview practice for seniors.";
$metaKeywords = "advanced SQL practice questions, SQL window functions examples, SQL CTE practice, advanced SQL interview questions, SQL query optimization, SQL scenario based questions, complex SQL queries";
$canonicalUrl = "https://sqlcompiler.shop/learn/sql-practice-advanced";
include("includes/learn_header.php");
?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<nav class="text-sm text-gray-500 mb-6">
  <ol class="flex items-center space-x-2">
    <li><a href="/" class="hover:text-purple-600">Home</a></li>
    <li><span class="mx-2">/</span></li>
    <li><a href="/learn/index" class="hover:text-purple-600">Learn SQL</a></li>
    <li><span class="mx-2">/</span></li>
    <li class="text-gray-900 font-medium">Advanced Practice</li>
  </ol>
</nav>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
<aside class="hidden lg:block">
  <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-24">
    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs mb-4">Practice Levels</h3>
    <ul class="space-y-2 text-sm">
      <li><a href="sql-practice-beginner" class="text-gray-600 hover:text-purple-600 block pl-1">Beginner</a></li>
      <li><a href="sql-practice-intermediate" class="text-gray-600 hover:text-purple-600 block pl-1">Intermediate</a></li>
      <li><a href="sql-practice-advanced" class="text-purple-600 font-bold border-l-2 border-purple-500 pl-3 -ml-3 block">Advanced (This Page)</a></li>
    </ul>
    <hr class="my-4">
    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs mb-4">Topics Covered</h3>
    <ul class="space-y-2 text-sm text-gray-600">
      <li>Window Functions</li>
      <li>CTEs (WITH clause)</li>
      <li>Recursive Queries</li>
      <li>Query Optimization</li>
      <li>Pivoting Data</li>
      <li><a href="sql-interview-questions" class="hover:text-purple-600">SQL Interview Q&amp;A</a></li>
    </ul>
  </div>
</aside>
<main class="lg:col-span-3 content-body">
  <div class="mb-4">
    <span class="inline-block bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">ADVANCED LEVEL</span>
    <span class="ml-2 text-xs text-gray-400">Last Updated: March 2026 &bull; 15 Exercises</span>
  </div>
  <h1 class="text-4xl font-bold text-gray-900 mb-4">Advanced SQL Practice Questions (Window Functions, CTEs, Optimization)</h1>
  <p class="text-xl text-gray-600 mb-6 leading-relaxed">These <strong>advanced SQL practice problems</strong> are designed for developers and analysts preparing for senior-level technical interviews. Topics include <strong>Window Functions</strong> (RANK, ROW_NUMBER, LAG/LEAD), <strong>CTEs</strong>, recursive queries, and query optimization strategies. Complete <a href="sql-practice-intermediate" class="text-purple-600">intermediate exercises</a> first before attempting these.</p>

  <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-8">
    <h3 class="font-bold text-red-800 mb-2">Difficulty Warning</h3>
    <p class="text-red-700 text-sm">These questions appear in senior backend developer, data engineer, and data scientist interviews. They require a solid understanding of SQL JOINs, GROUP BY, and subqueries. Take your time with each one — understanding the concept is more important than memorizing the syntax.</p>
  </div>

  <h2>Setup: Run This SQL First</h2>
  <pre><code class="language-sql">CREATE TABLE employees (
  id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(100),
  department VARCHAR(50), salary DECIMAL(10,2),
  hire_date DATE, city VARCHAR(50), manager_id INT
);
INSERT INTO employees (name,department,salary,hire_date,city,manager_id) VALUES
  ('Alice Johnson','Engineering',85000,'2020-03-15','New York',6),
  ('Bob Smith','Marketing',62000,'2019-07-22','Chicago',5),
  ('Carol White','Engineering',91000,'2018-01-10','New York',6),
  ('David Brown','HR',55000,'2021-05-30','Houston',NULL),
  ('Eva Green','Marketing',70000,'2020-11-01','Chicago',NULL),
  ('Frank Lee','Engineering',98000,'2017-09-14','San Francisco',NULL),
  ('Grace Kim','HR',57000,'2022-02-28','New York',4),
  ('Henry Davis','Finance',80000,'2019-04-12','Chicago',NULL),
  ('Iris Chen','Finance',75000,'2021-08-19','San Francisco',8),
  ('Jake Wilson','Marketing',60000,'2023-01-05','Houston',5);
CREATE TABLE sales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  employee_id INT, sale_date DATE,
  amount DECIMAL(10,2), product VARCHAR(50)
);
INSERT INTO sales (employee_id,sale_date,amount,product) VALUES
  (2,'2025-01-15',1200,'Analytics Pro'),
  (5,'2025-01-20',3500,'Enterprise Suite'),
  (2,'2025-02-10',900,'Analytics Pro'),
  (10,'2025-02-15',1800,'Data Tools'),
  (5,'2025-02-28',4200,'Enterprise Suite'),
  (10,'2025-03-05',2100,'Data Tools'),
  (2,'2025-03-10',1500,'Analytics Pro'),
  (5,'2025-03-22',3800,'Enterprise Suite');</code></pre>

  <h2>Window Functions (ROW_NUMBER, RANK, LAG/LEAD)</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q1: Rank employees by salary within each department (highest first). Show ties as equal rank.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, department, salary,
  RANK() OVER (PARTITION BY department ORDER BY salary DESC) AS dept_rank
FROM employees
ORDER BY department, dept_rank;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> RANK() is a window function. PARTITION BY department resets the rank counter for each department. ORDER BY salary DESC makes highest salary rank 1. Unlike GROUP BY, window functions do NOT collapse rows — you keep all original rows plus the calculated rank column. Ties receive the same rank, and the next rank is skipped (1,1,3 not 1,1,2).</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q2: Assign a unique sequential row number to employees, ordered by hire date (oldest first).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT ROW_NUMBER() OVER (ORDER BY hire_date ASC) AS row_num,
  name, hire_date, department
FROM employees;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>ROW_NUMBER vs RANK vs DENSE_RANK:</strong> ROW_NUMBER() always gives unique numbers even for ties. RANK() skips numbers after ties (1,1,3). DENSE_RANK() never skips (1,1,2). Use ROW_NUMBER() for pagination in applications.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q3: For each employee, show their salary and the salary of the next higher-paid employee in the same department (LAG/LEAD).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, department, salary,
  LAG(salary) OVER (PARTITION BY department ORDER BY salary) AS prev_lower_salary,
  LEAD(salary) OVER (PARTITION BY department ORDER BY salary) AS next_higher_salary,
  salary - LAG(salary, 1, salary) OVER (PARTITION BY department ORDER BY salary) AS salary_gap
FROM employees
ORDER BY department, salary;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> LAG() fetches the value from the previous row (lower salary). LEAD() fetches from the next row (higher salary). The third argument (salary) is the default value when there is no previous/next row. This is used for calculating month-over-month changes, salary gaps, and time-series analysis.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q4: Find the top-1 highest paid employee in each department WITHOUT using a subquery (use ROW_NUMBER).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, department, salary
FROM (
  SELECT name, department, salary,
    ROW_NUMBER() OVER (PARTITION BY department ORDER BY salary DESC) AS rn
  FROM employees
) ranked
WHERE rn = 1;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Why this is better than a correlated subquery:</strong> The window function approach evaluates the entire dataset once. The correlated subquery re-executes for each row. On large tables (millions of rows), the window function approach can be 10-100x faster. This is a classic interview question at Google, Amazon, and Meta.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q5: Calculate a running total of sales amounts, ordered by sale date.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT s.sale_date, e.name, s.amount,
  SUM(s.amount) OVER (ORDER BY s.sale_date ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS running_total
FROM sales s
JOIN employees e ON s.employee_id = e.id
ORDER BY s.sale_date;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> SUM() as a window function with ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW computes a cumulative (running) total. This is far simpler than the self-join approach previously required. Running totals are essential in financial reporting.</p>
    </div></details>
  </div>

  <h2>CTEs (Common Table Expressions)</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q6: Use a CTE to find departments where the average salary is above the company-wide average.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">WITH dept_averages AS (
  SELECT department, AVG(salary) AS avg_salary
  FROM employees
  GROUP BY department
),
company_average AS (
  SELECT AVG(salary) AS company_avg FROM employees
)
SELECT d.department, ROUND(d.avg_salary, 0) AS dept_avg,
       ROUND(c.company_avg, 0) AS company_avg
FROM dept_averages d, company_average c
WHERE d.avg_salary > c.company_avg
ORDER BY d.avg_salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> A CTE (WITH clause) defines named temporary result sets. You can define multiple CTEs in one query by separating them with commas. CTEs make complex queries more readable and maintainable versus deeply nested subqueries. They are also essential for recursive queries (see Q9).</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q7: Using a CTE, find every employee's salary percentile rank within the entire company.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">WITH ranked AS (
  SELECT name, salary,
    PERCENT_RANK() OVER (ORDER BY salary) AS percentile_rank
  FROM employees
)
SELECT name, salary,
  ROUND(percentile_rank * 100, 1) AS percentile
FROM ranked
ORDER BY salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> PERCENT_RANK() returns a value between 0 and 1, where 0 is the minimum and 1 is the maximum. Multiply by 100 for a conventional percentile. The CTE keeps the main SELECT clean and easy to read.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q8: Analyze month-over-month sales growth using a CTE and LAG().</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">WITH monthly_sales AS (
  SELECT DATE_FORMAT(sale_date, '%Y-%m') AS month,
         SUM(amount) AS total_sales
  FROM sales
  GROUP BY DATE_FORMAT(sale_date, '%Y-%m')
)
SELECT month, total_sales,
  LAG(total_sales) OVER (ORDER BY month) AS prev_month_sales,
  ROUND(((total_sales - LAG(total_sales) OVER (ORDER BY month))
    / LAG(total_sales) OVER (ORDER BY month)) * 100, 1) AS growth_pct
FROM monthly_sales
ORDER BY month;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> DATE_FORMAT aggregates by month. The CTE computes monthly totals, then the outer query uses LAG() to compare with the previous month. This MoM growth calculation is one of the most common analytics queries in business intelligence.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q9 (Hard): Write a recursive CTE to build the org chart hierarchy starting from top-level managers (no manager_id).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">WITH RECURSIVE org_chart AS (
  -- Anchor: top-level employees (no manager)
  SELECT id, name, department, manager_id, 0 AS level,
         CAST(name AS CHAR(500)) AS hierarchy_path
  FROM employees
  WHERE manager_id IS NULL

  UNION ALL

  -- Recursive: employees who report to the previous level
  SELECT e.id, e.name, e.department, e.manager_id, oc.level + 1,
         CONCAT(oc.hierarchy_path, ' > ', e.name)
  FROM employees e
  INNER JOIN org_chart oc ON e.manager_id = oc.id
)
SELECT REPEAT('  ', level) AS indent, name, department,
       level AS org_level, hierarchy_path
FROM org_chart
ORDER BY hierarchy_path;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Recursive CTEs have two parts: the anchor (starting rows) and the recursive member (rows added each iteration). UNION ALL combines them. MySQL requires the WITH RECURSIVE keyword. The REPEAT() function indents rows visually based on their depth. This is used for org charts, file systems, product categories, and any parent-child relationship.</p>
    </div></details>
  </div>

  <h2>Query Optimization</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q10: How would you optimize this slow query? Explain what EXPLAIN shows.</h3>
    <pre><code class="language-sql">-- Slow query (assumes millions of rows in employees)
SELECT * FROM employees WHERE city = 'New York' AND salary > 80000;</code></pre>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">-- Step 1: Analyze with EXPLAIN
EXPLAIN SELECT * FROM employees WHERE city = 'New York' AND salary > 80000;

-- Step 2: Create a composite index
CREATE INDEX idx_city_salary ON employees (city, salary);

-- Step 3: Re-run EXPLAIN to compare
EXPLAIN SELECT * FROM employees WHERE city = 'New York' AND salary > 80000;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> EXPLAIN shows the query execution plan. Key columns to watch: 'type' (should be 'ref' or 'range', not 'ALL'), 'rows' (estimated rows scanned), and 'Extra' (should show 'Using index'). A composite index on (city, salary) allows the database to first narrow to New York employees, then find those over 80k — both operations within the index, which is extremely fast. Index column order matters: put the equality condition (city =) before the range condition (salary >).</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q11: Rewrite this correlated subquery as a JOIN for better performance.</h3>
    <pre><code class="language-sql">-- Slow: Runs inner query once per outer row
SELECT name FROM employees e
WHERE (SELECT COUNT(*) FROM sales s WHERE s.employee_id = e.id) > 2;</code></pre>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">-- Fast: JOIN + GROUP BY approach
SELECT e.name
FROM employees e
INNER JOIN sales s ON e.id = s.employee_id
GROUP BY e.id, e.name
HAVING COUNT(s.id) > 2;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Why this is faster:</strong> The correlated subquery re-executes for every row in employees. With 1 million employees, that is 1 million subquery executions. The JOIN approach scans both tables once upfront, then groups. On large tables this can be orders of magnitude faster. Always prefer JOINs over correlated subqueries for EXISTS and COUNT checks.</p>
    </div></details>
  </div>

  <h2>Complex Real-World Scenarios</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q12: Find employees who have never made a sale (using NOT EXISTS).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">-- Method 1: NOT EXISTS (most readable)
SELECT name FROM employees e
WHERE NOT EXISTS (SELECT 1 FROM sales s WHERE s.employee_id = e.id);

-- Method 2: LEFT JOIN anti-pattern
SELECT e.name FROM employees e
LEFT JOIN sales s ON e.id = s.employee_id
WHERE s.id IS NULL;

-- Method 3: NOT IN (avoid with NULLs)
SELECT name FROM employees
WHERE id NOT IN (SELECT DISTINCT employee_id FROM sales);</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Best practice:</strong> Prefer NOT EXISTS over NOT IN when the subquery might return NULLs. NOT IN with a NULL in the subquery returns zero rows (unexpected behavior). NOT EXISTS and LEFT JOIN anti-join handle NULLs correctly.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q13: Pivot the sales data — show total sales per salesperson, broken down by month as separate columns.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT e.name,
  SUM(CASE WHEN DATE_FORMAT(s.sale_date,'%Y-%m') = '2025-01' THEN s.amount ELSE 0 END) AS Jan,
  SUM(CASE WHEN DATE_FORMAT(s.sale_date,'%Y-%m') = '2025-02' THEN s.amount ELSE 0 END) AS Feb,
  SUM(CASE WHEN DATE_FORMAT(s.sale_date,'%Y-%m') = '2025-03' THEN s.amount ELSE 0 END) AS Mar,
  SUM(s.amount) AS Total
FROM employees e
INNER JOIN sales s ON e.id = s.employee_id
GROUP BY e.id, e.name
ORDER BY Total DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> MySQL does not have a native PIVOT operator (unlike SQL Server). We simulate it using conditional aggregation: CASE WHEN inside SUM(). For dynamic pivots with unknown columns, you would need to generate SQL dynamically in application code.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q14: Using NTILE(), divide employees into 4 salary quartiles and identify which quartile each employee belongs to.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary,
  NTILE(4) OVER (ORDER BY salary) AS quartile,
  CASE NTILE(4) OVER (ORDER BY salary)
    WHEN 1 THEN 'Bottom 25%'
    WHEN 2 THEN 'Lower-Middle 25%'
    WHEN 3 THEN 'Upper-Middle 25%'
    WHEN 4 THEN 'Top 25%'
  END AS quartile_label
FROM employees
ORDER BY salary;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> NTILE(n) divides rows into n equal buckets. NTILE(4) creates salary quartiles, NTILE(10) creates deciles, NTILE(100) creates percentiles. This is used extensively in compensation analysis and sales performance reporting.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q15 (Expert): Find all employees whose salary is above average for their city AND above average for their department simultaneously.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">WITH city_avg AS (
  SELECT city, AVG(salary) AS city_avg_salary FROM employees GROUP BY city
),
dept_avg AS (
  SELECT department, AVG(salary) AS dept_avg_salary FROM employees GROUP BY department
)
SELECT e.name, e.department, e.city, e.salary,
  ROUND(ca.city_avg_salary, 0) AS city_avg,
  ROUND(da.dept_avg_salary, 0) AS dept_avg
FROM employees e
JOIN city_avg ca ON e.city = ca.city
JOIN dept_avg da ON e.department = da.department
WHERE e.salary > ca.city_avg_salary
  AND e.salary > da.dept_avg_salary
ORDER BY e.salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Multiple CTEs break the problem into logical steps. We join each CTE back to employees to get the comparative averages, then filter with two WHERE conditions simultaneously. This type of multi-dimensional comparison is common in HR analytics and compensation benchmarking.</p>
    </div></details>
  </div>

  <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl p-8 my-10 text-white">
    <h3 class="text-xl font-bold mb-2">Now Test Yourself in a Real Interview Scenario</h3>
    <p class="mb-4 text-red-100">You have completed all advanced exercises. The next step is practicing SQL interview questions under time pressure in our free online compiler.</p>
    <div class="flex flex-wrap gap-3">
      <a href="sql-interview-questions" class="bg-white text-red-700 font-bold px-5 py-2 rounded-lg hover:bg-red-50 transition">SQL Interview Questions</a>
      <a href="../editor.php" class="border border-white text-white font-medium px-5 py-2 rounded-lg hover:bg-white/10 transition">Open SQL Compiler</a>
      <a href="how-to-prepare-sql-interviews" class="border border-white text-white font-medium px-5 py-2 rounded-lg hover:bg-white/10 transition">Interview Prep Guide</a>
    </div>
  </div>

  <h2>Frequently Asked Questions</h2>
  <div class="space-y-4">
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What are SQL Window Functions?</summary>
      <p class="mt-3 text-gray-600 text-sm">Window functions perform calculations across a set of rows related to the current row, without collapsing them like GROUP BY does. Common window functions include RANK(), ROW_NUMBER(), DENSE_RANK(), LAG(), LEAD(), SUM() OVER(), and NTILE(). They use the OVER() clause to define the window (partition and ordering).</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What is a CTE in SQL and when should I use it?</summary>
      <p class="mt-3 text-gray-600 text-sm">A CTE (Common Table Expression) is a named temporary result set defined with the WITH clause. Use CTEs when: (1) you need to reference the same subquery multiple times, (2) the query has too many levels of nesting to be readable, or (3) you need recursion. CTEs improve readability but do not provide performance benefits over equivalent subqueries in most databases.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What is the difference between RANK() and DENSE_RANK()?</summary>
      <p class="mt-3 text-gray-600 text-sm">Both assign ranks, but differ in how they handle ties. RANK() skips the next rank after a tie (e.g., 1, 1, 3, 4). DENSE_RANK() never skips (e.g., 1, 1, 2, 3). Use RANK() for competition rankings (first, second, third). Use DENSE_RANK() when you do not want gaps in rank numbers.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What SQL topics appear most in data engineer interviews?</summary>
      <p class="mt-3 text-gray-600 text-sm">Top topics: (1) Window functions and their use cases, (2) Writing optimal JOINs vs correlated subqueries, (3) Identifying and fixing slow queries using EXPLAIN, (4) Recursive CTEs for hierarchical data, (5) Aggregation with GROUP BY and HAVING, (6) NULL handling and anti-join patterns.</p>
    </details>
  </div>

  <div class="mt-10 bg-gray-50 rounded-xl p-6 border border-gray-200">
    <h3 class="font-bold text-gray-800 mb-4">Advanced Learning Resources</h3>
    <div class="grid grid-cols-2 gap-3 text-sm">
      <a href="sql-interview-questions" class="text-purple-600 hover:underline">SQL Interview Questions</a>
      <a href="sql-joins-tutorial" class="text-purple-600 hover:underline">SQL JOINs In-Depth</a>
      <a href="sql-best-practices" class="text-purple-600 hover:underline">SQL Best Practices</a>
      <a href="sql-functions" class="text-purple-600 hover:underline">SQL Functions Reference</a>
      <a href="how-to-prepare-sql-interviews" class="text-purple-600 hover:underline">How to Prepare for SQL Interviews</a>
      <a href="top-50-sql-queries" class="text-purple-600 hover:underline">Top 50 SQL Queries</a>
    </div>
  </div>
</main>
</div>
</div>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"What are SQL Window Functions?","acceptedAnswer":{"@type":"Answer","text":"Window functions perform calculations across a set of rows related to the current row without collapsing them. Common examples: RANK(), ROW_NUMBER(), LAG(), LEAD(), and SUM() OVER()."}},{"@type":"Question","name":"What is a CTE in SQL?","acceptedAnswer":{"@type":"Answer","text":"A CTE (Common Table Expression) is a named temporary result set defined with the WITH clause, used to make complex queries more readable and to enable recursion."}},{"@type":"Question","name":"What SQL topics appear most in data engineer interviews?","acceptedAnswer":{"@type":"Answer","text":"Top topics: Window functions, optimal JOINs vs correlated subqueries, query optimization with EXPLAIN, recursive CTEs, GROUP BY with HAVING, and NULL handling."}}]}
</script>
<?php include("includes/learn_footer.php"); ?>
