<?php
$pageTitle = "SQL Practice Problems Intermediate - 15 Exercises with Answers (JOINs, GROUP BY)";
$metaDescription = "Intermediate SQL practice problems with answers. 15 exercises covering SQL JOINs, GROUP BY, HAVING, subqueries, and aggregate functions. Practice SQL online free.";
$metaKeywords = "SQL practice problems intermediate, SQL join example with sample tables, SQL group by example with output, SQL aggregate functions example, SQL subquery practice, SQL exercises intermediate";
$canonicalUrl = "https://sqlcompiler.shop/learn/sql-practice-intermediate";
include("includes/learn_header.php");
?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
  <ol class="flex items-center space-x-2">
    <li><a href="/" class="hover:text-purple-600">Home</a></li>
    <li><span class="mx-2">/</span></li>
    <li><a href="/learn/index" class="hover:text-purple-600">Learn SQL</a></li>
    <li><span class="mx-2">/</span></li>
    <li class="text-gray-900 font-medium">Intermediate Practice</li>
  </ol>
</nav>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
<aside class="hidden lg:block">
  <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-24">
    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs mb-4">Practice Levels</h3>
    <ul class="space-y-2 text-sm">
      <li><a href="sql-practice-beginner" class="text-gray-600 hover:text-purple-600 block pl-1">Beginner</a></li>
      <li><a href="sql-practice-intermediate" class="text-purple-600 font-bold border-l-2 border-purple-500 pl-3 -ml-3 block">Intermediate (This Page)</a></li>
      <li><a href="sql-practice-advanced" class="text-gray-600 hover:text-purple-600 block pl-1">Advanced</a></li>
    </ul>
    <hr class="my-4">
    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs mb-4">Topics Covered</h3>
    <ul class="space-y-2 text-sm text-gray-600">
      <li><a href="sql-joins-tutorial" class="hover:text-purple-600">SQL JOINs</a></li>
      <li><a href="sql-group-by" class="hover:text-purple-600">SQL GROUP BY</a></li>
      <li><a href="sql-having" class="hover:text-purple-600">SQL HAVING</a></li>
      <li><a href="sql-subqueries" class="hover:text-purple-600">SQL Subqueries</a></li>
      <li><a href="sql-functions" class="hover:text-purple-600">SQL Functions</a></li>
    </ul>
  </div>
</aside>
<main class="lg:col-span-3 content-body">
  <div class="mb-4">
    <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">INTERMEDIATE LEVEL</span>
    <span class="ml-2 text-xs text-gray-400">Last Updated: March 2026 &bull; 15 Exercises</span>
  </div>
  <h1 class="text-4xl font-bold text-gray-900 mb-4">SQL Practice Problems: Intermediate Level (With Answers)</h1>
  <p class="text-xl text-gray-600 mb-6 leading-relaxed">These <strong>intermediate SQL practice problems</strong> cover the most important topics for data analyst and backend developer job interviews: <strong>SQL JOINs</strong>, <strong>GROUP BY with aggregations</strong>, <strong>HAVING clause</strong>, and <strong>subqueries</strong>. Each exercise uses realistic sample data and provides full answer explanations. If you have not completed the <a href="sql-practice-beginner" class="text-purple-600 font-medium">beginner exercises</a>, start there first.</p>

  <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-8">
    <h3 class="font-bold text-yellow-800 mb-2">Prerequisites</h3>
    <p class="text-yellow-700 text-sm">You should already be comfortable with SELECT, WHERE, ORDER BY, and basic data manipulation (INSERT/UPDATE/DELETE) before tackling these exercises. If not, complete the <a href="sql-practice-beginner" class="underline font-medium">beginner practice set</a> first.</p>
  </div>

  <h2>Sample Database (Same Tables + More Rows)</h2>
  <p>Run these SQL statements in our <a href="../editor.php" class="text-purple-600 font-medium">free online SQL compiler</a> to set up your practice environment:</p>
  <pre><code class="language-sql">CREATE TABLE employees (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100), department VARCHAR(50),
  salary DECIMAL(10,2), hire_date DATE, city VARCHAR(50)
);
INSERT INTO employees (name,department,salary,hire_date,city) VALUES
  ('Alice Johnson','Engineering',85000,'2020-03-15','New York'),
  ('Bob Smith','Marketing',62000,'2019-07-22','Chicago'),
  ('Carol White','Engineering',91000,'2018-01-10','New York'),
  ('David Brown','HR',55000,'2021-05-30','Houston'),
  ('Eva Green','Marketing',70000,'2020-11-01','Chicago'),
  ('Frank Lee','Engineering',98000,'2017-09-14','San Francisco'),
  ('Grace Kim','HR',57000,'2022-02-28','New York'),
  ('Henry Davis','Finance',80000,'2019-04-12','Chicago'),
  ('Iris Chen','Finance',75000,'2021-08-19','San Francisco'),
  ('Jake Wilson','Marketing',60000,'2023-01-05','Houston');
CREATE TABLE departments (
  dept_id INT PRIMARY KEY AUTO_INCREMENT,
  dept_name VARCHAR(50), manager VARCHAR(100), budget DECIMAL(15,2)
);
INSERT INTO departments (dept_name,manager,budget) VALUES
  ('Engineering','Frank Lee',500000),
  ('Marketing','Eva Green',200000),
  ('HR','David Brown',150000),
  ('Finance','Henry Davis',300000);
CREATE TABLE projects (
  id INT PRIMARY KEY AUTO_INCREMENT,
  project_name VARCHAR(100), department VARCHAR(50),
  budget DECIMAL(15,2), status VARCHAR(20)
);
INSERT INTO projects (project_name,department,budget,status) VALUES
  ('Cloud Migration','Engineering',120000,'Active'),
  ('Brand Refresh','Marketing',45000,'Active'),
  ('HR Portal','HR',30000,'Completed'),
  ('Financial Dashboard','Finance',80000,'Active'),
  ('API Redesign','Engineering',95000,'Active'),
  ('Social Campaign','Marketing',25000,'Completed');</code></pre>

  <h2>Exercises 1-5: SQL JOINs</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q1: List all employees with their department budget using INNER JOIN.</h3>
    <p class="text-gray-600 text-sm mb-3">Combine the employees and departments tables to show each employee alongside their department budget.</p>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT e.name, e.department, d.budget
FROM employees e
INNER JOIN departments d ON e.department = d.dept_name
ORDER BY e.department, e.name;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> INNER JOIN returns only rows where the join condition is met in BOTH tables. We use table aliases (e and d) for readability. The ON clause defines the relationship between the tables.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q2: Show all departments and their employees. Include departments even if they have no employees (LEFT JOIN).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT d.dept_name, d.manager, e.name AS employee_name
FROM departments d
LEFT JOIN employees e ON d.dept_name = e.department
ORDER BY d.dept_name;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> LEFT JOIN returns ALL rows from the left table (departments) even if there is no matching row in the right table (employees). If no match exists, employee columns will be NULL. This is crucial for finding "departments with no employees".</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q3: Find employees who work on active projects. Show employee name, project name, and project budget.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT e.name, p.project_name, p.budget AS project_budget
FROM employees e
INNER JOIN projects p ON e.department = p.department
WHERE p.status = 'Active'
ORDER BY e.name;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> You can combine JOIN with WHERE to filter after joining. This query matches employees to projects based on department, then filters for only Active projects. In real scenarios, a many-to-many relationship would use a bridge table.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q4: Difference between INNER JOIN and LEFT JOIN — when does LEFT JOIN return extra rows?</h3>
    <p class="text-gray-600 text-sm mb-3">Write a query to show which departments have NO employees assigned.</p>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT d.dept_name, d.manager
FROM departments d
LEFT JOIN employees e ON d.dept_name = e.department
WHERE e.id IS NULL;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> This is a classic "anti-join" pattern. After a LEFT JOIN, rows with no match will have NULL in all right-table columns. The WHERE e.id IS NULL filter finds exactly those unmatched rows — departments with no employees. With the current sample data, all departments have employees, so this returns 0 rows.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q5: Join 3 tables — show employee names, their department manager, and their current active project.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT e.name AS employee, d.manager, p.project_name
FROM employees e
INNER JOIN departments d ON e.department = d.dept_name
LEFT JOIN projects p ON e.department = p.department AND p.status = 'Active'
ORDER BY e.department, e.name;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Joining 3 tables is a chain of JOIN operations. We use LEFT JOIN for projects so employees without active projects still appear in the result. Notice how you can add conditions directly in the ON clause.</p>
    </div></details>
  </div>

  <h2>Exercises 6-10: GROUP BY and Aggregate Functions</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q6: Count the number of employees in each department.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT department, COUNT(*) AS employee_count
FROM employees
GROUP BY department
ORDER BY employee_count DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Expected Output:</strong></p>
    <table class="w-full text-xs border-collapse mt-2">
      <tr class="bg-gray-100"><th class="p-2 text-left border">department</th><th class="p-2 text-left border">employee_count</th></tr>
      <tr><td class="p-2 border">Engineering</td><td class="p-2 border">3</td></tr>
      <tr class="bg-gray-50"><td class="p-2 border">Marketing</td><td class="p-2 border">3</td></tr>
      <tr><td class="p-2 border">Finance</td><td class="p-2 border">2</td></tr>
      <tr class="bg-gray-50"><td class="p-2 border">HR</td><td class="p-2 border">2</td></tr>
    </table>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q7: Find the average salary per department, rounded to 2 decimal places.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT department,
       ROUND(AVG(salary), 2) AS avg_salary,
       MIN(salary) AS min_salary,
       MAX(salary) AS max_salary
FROM employees
GROUP BY department
ORDER BY avg_salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> AVG(), MIN(), and MAX() are aggregate functions that work on grouped rows. ROUND(value, 2) limits the decimal places. You can use multiple aggregate functions in a single GROUP BY query.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q8: Find departments where the total salary bill exceeds $150,000 (using HAVING).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT department, SUM(salary) AS total_salary_cost
FROM employees
GROUP BY department
HAVING SUM(salary) > 150000
ORDER BY total_salary_cost DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Key Rule:</strong> Use WHERE to filter individual rows BEFORE grouping. Use HAVING to filter groups AFTER grouping. You cannot use aggregate functions (SUM, COUNT, AVG) in a WHERE clause — they must go in HAVING.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q9: Show the number of employees hired each year, sorted by year.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT YEAR(hire_date) AS hire_year,
       COUNT(*) AS employees_hired
FROM employees
GROUP BY YEAR(hire_date)
ORDER BY hire_year;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> YEAR() extracts the year from a DATE column. You can GROUP BY a function call. This type of query is extremely common in business reporting ("How many users signed up per month?").</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q10: List departments with more than 2 employees, showing count and total payroll.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT department,
       COUNT(*) AS headcount,
       SUM(salary) AS total_payroll
FROM employees
GROUP BY department
HAVING COUNT(*) > 2
ORDER BY total_payroll DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> HAVING COUNT(*) > 2 filters after grouping. This is the correct way to filter by aggregated values. Returns only Engineering and Marketing (3 employees each).</p>
    </div></details>
  </div>

  <h2>Exercises 11-15: Subqueries</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q11: Find employees who earn more than the company average salary.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary
FROM employees
WHERE salary > (SELECT AVG(salary) FROM employees)
ORDER BY salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> The inner query (SELECT AVG(salary)) runs first and returns a single value (~73,300). The outer query then compares each employee's salary against that value. This is a scalar subquery — one that returns exactly one value.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q12: Find the highest-paid employee in each department using a subquery.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, department, salary
FROM employees e
WHERE salary = (
  SELECT MAX(salary)
  FROM employees
  WHERE department = e.department
)
ORDER BY salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> This is a correlated subquery — it references the outer query's table alias (e). For each row in the outer query, the inner query runs once using that row's department value. This is a classic pattern for finding "top record per group".</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q13: Find employees who work in the same department as 'Frank Lee'.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, department
FROM employees
WHERE department = (
  SELECT department FROM employees WHERE name = 'Frank Lee'
)
AND name != 'Frank Lee';</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> The subquery finds Frank Lee's department (Engineering). The outer query then finds all employees in that department excluding Frank. Returns: Alice Johnson, Carol White.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q14: Find employees whose salary is in the top 30% of all salaries.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary
FROM employees
WHERE salary >= (
  SELECT MAX(salary) * 0.70 FROM employees
)
ORDER BY salary DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Alternative approach:</strong> Using LIMIT to get the exact top 3 (30% of 10):</p>
    <pre><code class="language-sql">SELECT name, salary FROM employees
ORDER BY salary DESC LIMIT 3;</code></pre>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q15: Using a subquery in FROM — calculate each department's payroll as a percentage of total company payroll.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT
  dept_stats.department,
  dept_stats.dept_total,
  ROUND((dept_stats.dept_total / total.overall) * 100, 1) AS percentage
FROM (
  SELECT department, SUM(salary) AS dept_total
  FROM employees GROUP BY department
) AS dept_stats,
(
  SELECT SUM(salary) AS overall FROM employees
) AS total
ORDER BY percentage DESC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> A subquery in the FROM clause acts like a temporary table (called a "derived table"). This is a powerful pattern for multi-level aggregations. Engineering department should represent the largest share of payroll.</p>
    </div></details>
  </div>

  <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-8 my-10 text-white">
    <h3 class="text-xl font-bold mb-2">Ready for the Final Challenge?</h3>
    <p class="mb-4 text-yellow-100">You have completed all 15 intermediate SQL practice problems! Move on to advanced topics: Window Functions, CTEs, and query optimization.</p>
    <div class="flex flex-wrap gap-3">
      <a href="sql-practice-advanced" class="bg-white text-orange-700 font-bold px-5 py-2 rounded-lg hover:bg-orange-50 transition">Advanced Practice</a>
      <a href="sql-interview-questions" class="border border-white text-white font-medium px-5 py-2 rounded-lg hover:bg-white/10 transition">SQL Interview Questions</a>
    </div>
  </div>

  <h2>Frequently Asked Questions</h2>
  <div class="space-y-4">
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What is the difference between INNER JOIN and LEFT JOIN?</summary>
      <p class="mt-3 text-gray-600 text-sm">INNER JOIN returns only rows where the condition is met in BOTH tables. LEFT JOIN returns ALL rows from the left table, plus matching rows from the right table (NULL where no match). Use LEFT JOIN when you want to keep all records from the primary table regardless of matches.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">When should I use HAVING instead of WHERE?</summary>
      <p class="mt-3 text-gray-600 text-sm">Use WHERE to filter individual rows before grouping. Use HAVING to filter groups after a GROUP BY operation. Rule: if your condition uses an aggregate function (COUNT, SUM, AVG, MAX, MIN), it must go in HAVING — not WHERE.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">How do I join 3 tables in SQL?</summary>
      <p class="mt-3 text-gray-600 text-sm">Chain multiple JOIN clauses: SELECT ... FROM table1 JOIN table2 ON condition1 JOIN table3 ON condition2. Each JOIN adds a new table to the result set. You can mix INNER JOIN and LEFT JOIN as needed. See Exercise Q5 above for a worked example with sample output.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What is the SQL GROUP BY clause used for?</summary>
      <p class="mt-3 text-gray-600 text-sm">GROUP BY collapses multiple rows that share the same value in a column into a single summary row. It's always used with aggregate functions (COUNT, SUM, AVG, etc.). Example: GROUP BY department collapses all Engineering employees into one row so you can calculate aggregate statistics per department.</p>
    </details>
  </div>

  <div class="mt-10 bg-gray-50 rounded-xl p-6 border border-gray-200">
    <h3 class="font-bold text-gray-800 mb-4">Continue Learning</h3>
    <div class="grid grid-cols-2 gap-3 text-sm">
      <a href="sql-joins-tutorial" class="text-purple-600 hover:underline">SQL JOINs Tutorial (In-Depth)</a>
      <a href="sql-group-by" class="text-purple-600 hover:underline">SQL GROUP BY Guide</a>
      <a href="sql-having" class="text-purple-600 hover:underline">SQL HAVING Clause</a>
      <a href="sql-subqueries" class="text-purple-600 hover:underline">SQL Subqueries Guide</a>
      <a href="sql-interview-questions" class="text-purple-600 hover:underline">SQL Interview Questions</a>
      <a href="sql-practice-advanced" class="text-purple-600 hover:underline">Advanced SQL Practice</a>
    </div>
  </div>
</main>
</div>
</div>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"What is the difference between INNER JOIN and LEFT JOIN?","acceptedAnswer":{"@type":"Answer","text":"INNER JOIN returns only rows where the condition is met in BOTH tables. LEFT JOIN returns ALL rows from the left table, plus matching rows from the right table."}},{"@type":"Question","name":"When should I use HAVING instead of WHERE?","acceptedAnswer":{"@type":"Answer","text":"Use WHERE to filter rows before grouping. Use HAVING to filter groups after GROUP BY. If your condition uses an aggregate function, it must go in HAVING."}},{"@type":"Question","name":"What is the SQL GROUP BY clause used for?","acceptedAnswer":{"@type":"Answer","text":"GROUP BY collapses multiple rows sharing the same value into a single summary row. It is always used with aggregate functions like COUNT, SUM, AVG."}}]}
</script>
<?php include("includes/learn_footer.php"); ?>
