<?php
$pageTitle = "SQL Practice Questions for Beginners - 15+ Exercises with Answers";
$metaDescription = "Free SQL practice questions for beginners with answers. 15+ beginner SQL exercises covering SELECT, WHERE, ORDER BY, INSERT, and UPDATE. Practice SQL online free.";
$metaKeywords = "SQL practice questions for beginners, beginner SQL exercises, SQL coding practice, SQL exercises online, practice SQL online free, SQL query practice, SQL problems with solutions";
$canonicalUrl = "https://sqlcompiler.shop/learn/sql-practice-beginner";
include("includes/learn_header.php");
?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
  <ol class="flex items-center space-x-2">
    <li><a href="/" class="hover:text-purple-600">Home</a></li>
    <li><span class="mx-2">/</span></li>
    <li><a href="/learn/index" class="hover:text-purple-600">Learn SQL</a></li>
    <li><span class="mx-2">/</span></li>
    <li class="text-gray-900 font-medium">Beginner Practice</li>
  </ol>
</nav>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
<aside class="hidden lg:block">
  <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-24">
    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs mb-4">Practice Levels</h3>
    <ul class="space-y-2 text-sm">
      <li><a href="sql-practice-beginner" class="text-purple-600 font-bold border-l-2 border-purple-500 pl-3 -ml-3 block">Beginner (This Page)</a></li>
      <li><a href="sql-practice-intermediate" class="text-gray-600 hover:text-purple-600 block pl-1">Intermediate</a></li>
      <li><a href="sql-practice-advanced" class="text-gray-600 hover:text-purple-600 block pl-1">Advanced</a></li>
    </ul>
    <hr class="my-4">
    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs mb-4">Related Topics</h3>
    <ul class="space-y-2 text-sm text-gray-600">
      <li><a href="sql-select" class="hover:text-purple-600">SQL SELECT</a></li>
      <li><a href="sql-where" class="hover:text-purple-600">SQL WHERE</a></li>
      <li><a href="sql-order-by" class="hover:text-purple-600">SQL ORDER BY</a></li>
      <li><a href="sql-insert" class="hover:text-purple-600">SQL INSERT</a></li>
      <li><a href="sql-update" class="hover:text-purple-600">SQL UPDATE</a></li>
      <li><a href="sql-basics-tutorial" class="hover:text-purple-600">SQL Basics Tutorial</a></li>
    </ul>
  </div>
</aside>
<main class="lg:col-span-3 content-body">
  <div class="mb-4">
    <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">BEGINNER LEVEL</span>
    <span class="ml-2 text-xs text-gray-400">Last Updated: March 2026 &bull; 15 Exercises</span>
  </div>
  <h1 class="text-4xl font-bold text-gray-900 mb-4">SQL Practice Questions for Beginners (With Answers)</h1>
  <p class="text-xl text-gray-600 mb-6 leading-relaxed">Welcome to our <strong>beginner SQL practice exercises</strong>. Whether you are studying for a college exam, preparing for a job interview, or just learning SQL for the first time, these 15 practice questions will build your confidence. Each question comes with a complete answer and explanation so you understand the <em>why</em>, not just the <em>what</em>.</p>
  <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8">
    <h3 class="font-bold text-blue-800 mb-2">How to Use These Exercises</h3>
    <p class="text-blue-700 text-sm">Read each question, attempt to write the SQL yourself, then reveal the answer. For best results, <a href="../editor.php" class="underline font-medium">open our free SQL compiler</a> in another tab and run each query yourself to see live output.</p>
  </div>
  <h2>Sample Database Tables</h2>
  <p>All 15 exercises use these two tables. Run these CREATE/INSERT statements in our compiler first to set up your workspace:</p>
  <pre><code class="language-sql">CREATE TABLE employees (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100),
  department VARCHAR(50),
  salary DECIMAL(10,2),
  hire_date DATE,
  city VARCHAR(50)
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
  dept_name VARCHAR(50),
  manager VARCHAR(100),
  budget DECIMAL(15,2)
);
INSERT INTO departments (dept_name,manager,budget) VALUES
  ('Engineering','Frank Lee',500000),
  ('Marketing','Eva Green',200000),
  ('HR','David Brown',150000),
  ('Finance','Henry Davis',300000);</code></pre>

  <h2>Exercises 1-5: SELECT and Basic Retrieval</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q1: Retrieve all employees from the database.</h3>
    <p class="text-gray-600 text-sm mb-3">Write a query that returns every column and every row from the employees table.</p>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT * FROM employees;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> SELECT * retrieves all columns. FROM employees specifies the table. This returns all 10 rows. In production code, prefer listing specific column names over SELECT * for performance reasons.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q2: Select only the name and salary of all employees.</h3>
    <p class="text-gray-600 text-sm mb-3">Specify only the columns you need instead of using SELECT *.</p>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary FROM employees;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Selecting specific columns reduces data transfer and improves performance. The result has exactly 2 columns and 10 rows.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q3: Select names and salaries with aliased column headers "Employee Name" and "Annual Salary".</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name AS "Employee Name", salary AS "Annual Salary"
FROM employees;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> The AS keyword creates a column alias. Aliases with spaces require quotes. Aliases only change the output header - not the underlying data.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q4: Get a unique list of cities where employees are located (no duplicates).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT DISTINCT city FROM employees;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> DISTINCT filters duplicate values. Without it, cities like New York and Chicago would repeat multiple times. Returns 4 unique cities.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q5: Count the total number of employees in the company.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT COUNT(*) AS total_employees FROM employees;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> COUNT(*) counts all rows, including those with NULL values. This is the most fundamental aggregate function in SQL. Returns: 10.</p>
    </div></details>
  </div>

  <h2>Exercises 6-10: WHERE Clause Filtering</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q6: Find all employees in the Engineering department.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT * FROM employees
WHERE department = 'Engineering';</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> WHERE filters rows. String values need single quotes. Returns: Alice Johnson, Carol White, Frank Lee (3 rows).</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q7: Find all employees earning more than $75,000 per year.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, department, salary FROM employees
WHERE salary > 75000;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Use comparison operators with numbers: &gt; (greater than), &lt; (less than), &gt;= , &lt;=, != (not equal). Returns 4 employees.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q8: Find all Engineering employees who earn over $90,000 (multiple conditions).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary FROM employees
WHERE department = 'Engineering' AND salary > 90000;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> AND requires both conditions to be true. Returns: Carol White (91k), Frank Lee (98k).</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q9: Find all employees who live in New York or Chicago.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">-- Using IN (preferred for multiple values)
SELECT name, city FROM employees
WHERE city IN ('New York', 'Chicago');

-- Using OR (also correct)
SELECT name, city FROM employees
WHERE city = 'New York' OR city = 'Chicago';</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Both methods return identical results. IN is preferred for readability when checking one column against multiple values.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q10: Find employees whose name contains the word "Lee".</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name FROM employees
WHERE name LIKE '%Lee%';</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> LIKE performs pattern matching. % means any sequence of characters. %Lee% means "Lee can appear anywhere in the string". Returns: Frank Lee.</p>
    </div></details>
  </div>

  <h2>Exercises 11-13: ORDER BY, LIMIT, and BETWEEN</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q11: List all employees sorted by salary from highest to lowest, then by name alphabetically.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary FROM employees
ORDER BY salary DESC, name ASC;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> You can sort by multiple columns. DESC = descending (highest first). ASC = ascending (A-Z). The second sort column is used as a tiebreaker.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q12: Show only the top 3 highest-paid employees.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary FROM employees
ORDER BY salary DESC
LIMIT 3;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> LIMIT restricts the number of rows returned. Combined with ORDER BY, this creates the "Top N" pattern used in leaderboards, reports, and dashboards. Returns: Frank (98k), Carol (91k), Alice (85k).</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q13: Find employees earning between $60,000 and $80,000 (inclusive).</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">SELECT name, salary FROM employees
WHERE salary BETWEEN 60000 AND 80000
ORDER BY salary;</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> BETWEEN a AND b is inclusive (includes both boundaries). Equivalent to: salary &gt;= 60000 AND salary &lt;= 80000.</p>
    </div></details>
  </div>

  <h2>Exercises 14-15: INSERT and UPDATE</h2>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q14: Add a new employee: "Lisa Park", Finance, $72,000, hired 2024-06-01, from Boston.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">INSERT INTO employees (name, department, salary, hire_date, city)
VALUES ('Lisa Park', 'Finance', 72000, '2024-06-01', 'Boston');</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>Explanation:</strong> Always list column names explicitly in INSERT statements. The id column is omitted because AUTO_INCREMENT generates it automatically.</p>
    </div></details>
  </div>
  <div class="bg-white border border-gray-200 rounded-xl p-6 mb-5 shadow-sm">
    <h3 class="font-bold text-gray-900 mb-1">Q15: Give all Marketing employees a 10% salary raise.</h3>
    <details><summary class="text-purple-600 font-medium text-sm cursor-pointer">Show Answer</summary>
    <div class="mt-3"><pre><code class="language-sql">UPDATE employees
SET salary = salary * 1.10
WHERE department = 'Marketing';</code></pre>
    <p class="text-sm text-gray-600 mt-2"><strong>WARNING:</strong> Always include a WHERE clause in UPDATE statements. Without WHERE, you would update every employee's salary — a very costly mistake in production databases!</p>
    </div></details>
  </div>

  <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-8 my-10 text-white">
    <h3 class="text-xl font-bold mb-2">Ready for More?</h3>
    <p class="mb-4 text-purple-100">You have completed all 15 beginner SQL practice exercises! Level up to intermediate topics like SQL JOINs and GROUP BY.</p>
    <div class="flex flex-wrap gap-3">
      <a href="sql-practice-intermediate" class="bg-white text-purple-700 font-bold px-5 py-2 rounded-lg hover:bg-purple-50 transition">Intermediate Practice</a>
      <a href="../editor.php" class="border border-white text-white font-medium px-5 py-2 rounded-lg hover:bg-white/10 transition">Open SQL Compiler</a>
    </div>
  </div>

  <h2>Frequently Asked Questions</h2>
  <div class="space-y-4">
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">How do I practice SQL online for free?</summary>
      <p class="mt-3 text-gray-600 text-sm">Use <a href="/" class="text-purple-600 font-medium">SQLCompiler.shop</a> — a free online SQL compiler. No installation required. Open the editor, paste your SQL, and click Run. Your database is isolated from other users.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What SQL commands should a beginner learn first?</summary>
      <p class="mt-3 text-gray-600 text-sm">Start with: <strong>SELECT</strong> (read data), <strong>WHERE</strong> (filter rows), <strong>ORDER BY</strong> (sort), <strong>INSERT</strong> (add rows), <strong>UPDATE</strong> (modify data), and <strong>DELETE</strong> (remove rows). These 6 commands handle 80% of real-world SQL work.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">How long does it take to learn basic SQL?</summary>
      <p class="mt-3 text-gray-600 text-sm">With daily hands-on practice, most beginners master basic SQL in 2 to 4 weeks. Reading tutorials is not enough — you must write and run queries regularly. These exercises are designed to accelerate your learning.</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What is the difference between WHERE and HAVING in SQL?</summary>
      <p class="mt-3 text-gray-600 text-sm">WHERE filters rows before grouping. HAVING filters groups after a GROUP BY. Use WHERE for individual row conditions; use <a href="sql-having" class="text-purple-600">HAVING</a> for aggregate conditions like "departments with average salary above 70,000".</p>
    </details>
    <details class="bg-gray-50 rounded-lg border border-gray-200 p-5">
      <summary class="font-semibold text-gray-800 cursor-pointer">What comes after beginner SQL practice?</summary>
      <p class="mt-3 text-gray-600 text-sm">Move to <a href="sql-practice-intermediate" class="text-purple-600 font-medium">intermediate practice</a> covering SQL JOINs, GROUP BY, and subqueries. Then tackle <a href="sql-practice-advanced" class="text-purple-600 font-medium">advanced topics</a> like Window Functions and CTEs.</p>
    </details>
  </div>

  <div class="mt-10 bg-gray-50 rounded-xl p-6 border border-gray-200">
    <h3 class="font-bold text-gray-800 mb-4">Continue Your SQL Learning</h3>
    <div class="grid grid-cols-2 gap-3 text-sm">
      <a href="sql-basics-tutorial" class="text-purple-600 hover:underline">SQL Basics Tutorial</a>
      <a href="sql-select" class="text-purple-600 hover:underline">SQL SELECT Guide</a>
      <a href="sql-where" class="text-purple-600 hover:underline">SQL WHERE Clause</a>
      <a href="sql-joins-tutorial" class="text-purple-600 hover:underline">SQL JOINs Tutorial</a>
      <a href="sql-interview-questions" class="text-purple-600 hover:underline">SQL Interview Questions</a>
      <a href="sql-practice-intermediate" class="text-purple-600 hover:underline">Intermediate Practice</a>
    </div>
  </div>
</main>
</div>
</div>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"How do I practice SQL online for free?","acceptedAnswer":{"@type":"Answer","text":"Use SQLCompiler.shop - a free online SQL compiler. No installation required. Open the editor, paste your SQL, and click Run."}},{"@type":"Question","name":"What SQL commands should a beginner learn first?","acceptedAnswer":{"@type":"Answer","text":"Start with: SELECT, WHERE, ORDER BY, INSERT, UPDATE, and DELETE. These 6 commands handle 80% of real-world SQL work."}},{"@type":"Question","name":"How long does it take to learn basic SQL?","acceptedAnswer":{"@type":"Answer","text":"With daily hands-on practice, most beginners master basic SQL in 2 to 4 weeks."}}]}
</script>
<?php include("includes/learn_footer.php"); ?>
