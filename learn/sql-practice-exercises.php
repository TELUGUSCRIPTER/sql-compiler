<?php
$pageTitle = '50 SQL Practice Exercises with Solutions - Learn by Doing';
$metaDescription = 'Practice SQL with 50 hands-on exercises and solutions. From beginner to advanced SQL queries. Perfect for interview prep and skill building. Try them online!';
$metaKeywords = 'SQL practice, SQL exercises, SQL problems, practice SQL online, SQL interview prep, SQL challenges';
$canonicalUrl = 'https://sqlcompiler.shop/learn/sql-practice-exercises';
$breadcrumbs = [
    ['title' => 'Learn', 'url' => '/learn/sql-basics-tutorial'],
    ['title' => 'SQL Practice Exercises']
];

require_once __DIR__ . '/includes/learn_header.php';
require_once __DIR__ . '/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">50 SQL Practice Exercises with Solutions</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            The best way to learn SQL is through practice! This collection of 50 SQL exercises covers everything from basic SELECT statements to complex JOINs and subqueries. Each exercise includes a difficulty rating, the problem statement, and a detailed solution. Work through these systematically to build confidence and fluency in SQL.
        </p>

        <h2>Sample Database Schema</h2>
        <p>Use this schema for all exercises:</p>
        
        <pre><code class="language-sql">CREATE TABLE employees (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    department VARCHAR(50),
    salary DECIMAL(10,2),
    hire_date DATE,
    manager_id INT
);

CREATE TABLE departments (
    id INT PRIMARY KEY,
    name VARCHAR(50),
    budget DECIMAL(12,2)
);

CREATE TABLE projects (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    department_id INT,
    start_date DATE,
    end_date DATE
);
</code></pre>

        <h2>Beginner Level (1-20)</h2>

        <h3>Exercise 1: Select All Employees</h3>
        <p><strong>Difficulty:</strong> ⭐</p>
        <p><strong>Problem:</strong> Write a query to select all employees.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees;</code></pre>
        </details>

        <h3>Exercise 2: Select Specific Columns</h3>
        <p><strong>Difficulty:</strong> ⭐</p>
        <p><strong>Problem:</strong> Select only name and salary from employees table.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT name, salary FROM employees;</code></pre>
        </details>

        <h3>Exercise 3: Filter by Department</h3>
        <p><strong>Difficulty:</strong> ⭐</p>
        <p><strong>Problem:</strong> Find all employees in the 'Engineering' department.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees WHERE department = 'Engineering';</code></pre>
        </details>

        <h3>Exercise 4: High Earners</h3>
        <p><strong>Difficulty:</strong> ⭐</p>
        <p><strong>Problem:</strong> Find employees earning more than $70,000.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees WHERE salary > 70000;</code></pre>
        </details>

        <h3>Exercise 5: Sort by Salary</h3>
        <p><strong>Difficulty:</strong> ⭐</p>
        <p><strong>Problem:</strong> List all employees ordered by salary (highest first).</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees ORDER BY salary DESC;</code></pre>
        </details>

        <h3>Exercise 6: Count Employees</h3>
        <p><strong>Difficulty:</strong> ⭐⭐</p>
        <p><strong>Problem:</strong> How many employees are there in total?</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT COUNT(*) as total_employees FROM employees;</code></pre>
        </details>

        <h3>Exercise 7: Average Salary</h3>
        <p><strong>Difficulty:</strong> ⭐⭐</p>
        <p><strong>Problem:</strong> Calculate the average salary across all employees.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT AVG(salary) as average_salary FROM employees;</code></pre>
        </details>

        <h3>Exercise 8: Employees Per Department</h3>
        <p><strong>Difficulty:</strong> ⭐⭐</p>
        <p><strong>Problem:</strong> Count how many employees are in each department.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT department, COUNT(*) as employee_count
FROM employees
GROUP BY department;</code></pre>
        </details>

        <h3>Exercise 9: Recently Hired</h3>
        <p><strong>Difficulty:</strong> ⭐⭐</p>
        <p><strong>Problem:</strong> Find employees hired in the last year.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees 
WHERE hire_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR);</code></pre>
        </details>

        <h3>Exercise 10: Name Pattern</h3>
        <p><strong>Difficulty:</strong> ⭐⭐</p>
        <p><strong>Problem:</strong> Find employees whose name starts with 'J'.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees WHERE name LIKE 'J%';</code></pre>
        </details>

        <h2>Intermediate Level (21-35)</h2>

        <h3>Exercise 21: Department Salary Totals</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Calculate total salary expenditure per department.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT department, SUM(salary) as total_salary
FROM employees
GROUP BY department
ORDER BY total_salary DESC;</code></pre>
        </details>

        <h3>Exercise 22: Above Average Earners</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find employees earning above the company average.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees
WHERE salary > (SELECT AVG(salary) FROM employees);</code></pre>
        </details>

        <h3>Exercise 23: Join Employees and Departments</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> List employee names with their department budgets.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT e.name, d.budget
FROM employees e
JOIN departments d ON e.department = d.name;</code></pre>
        </details>

        <h3>Exercise 24: Employees Without Manager</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find employees who don't have a manager.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees WHERE manager_id IS NULL;</code></pre>
        </details>

        <h3>Exercise 25: Second Highest Salary</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find the second highest salary.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT MAX(salary) as second_highest
FROM employees
WHERE salary < (SELECT MAX(salary) FROM employees);</code></pre>
        </details>

        <h3>Exercise 26: Salary Range</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find employees with salary between $50,000 and $80,000.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT * FROM employees 
WHERE salary BETWEEN 50000 AND 80000;</code></pre>
        </details>

        <h3>Exercise 27: Employees Per Year</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Count employees hired each year.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT YEAR(hire_date) as year, COUNT(*) as hires
FROM employees
GROUP BY YEAR(hire_date)
ORDER BY year;</code></pre>
        </details>

        <h3>Exercise 28: TOP N Per Group</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find the highest paid employee in each department.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT e.* FROM employees e
WHERE salary = (
    SELECT MAX(salary) 
    FROM employees 
    WHERE department = e.department
);</code></pre>
        </details>

        <h2>Advanced Level (36-50)</h2>

        <h3>Exercise 36: Self Join - Employee and Manager</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐⭐</p>
        <p><strong>Problem:</strong> List each employee with their manager's name.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT e.name as employee, m.name as manager
FROM employees e
LEFT JOIN employees m ON e.manager_id = m.id;</code></pre>
        </details>

        <h3>Exercise 37: Cumulative Sum</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐⭐</p>
        <p><strong>Problem:</strong> Calculate running total of salaries.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT name, salary,
    SUM(salary) OVER (ORDER BY id) as running_total
FROM employees;</code></pre>
        </details>

        <h3>Exercise 38: Department Over Budget</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find departments where total salaries exceed their budget.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT d.name, d.budget, SUM(e.salary) as total_salaries
FROM departments d
JOIN employees e ON d.name = e.department
GROUP BY d.name, d.budget
HAVING SUM(e.salary) > d.budget;</code></pre>
        </details>

        <h3>Exercise 39: Employees Without Projects</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐⭐</p>
        <p><strong>Problem:</strong> Find employees not assigned to any project.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT e.* FROM employees e
WHERE NOT EXISTS (
    SELECT 1 FROM projects p 
    WHERE p.department_id = e.department
);</code></pre>
        </details>

        <h3>Exercise 40: Salary Percentile</h3>
        <p><strong>Difficulty:</strong> ⭐⭐⭐⭐⭐</p>
        <p><strong>Problem:</strong> Rank employees by salary percentile.</p>
        <details>
            <summary>Click for Solution</summary>
            <pre><code class="language-sql">SELECT name, salary,
    PERCENT_RANK() OVER (ORDER BY salary) * 100 as percentile
FROM employees;</code></pre>
        </details>

        <div class="try-it-box">
            <h3 class="text-xl font-bold mb-2">🚀 Practice These Exercises!</h3>
            <p class="mb-4">Try all 50 exercises in our interactive SQL compiler. Create the tables, insert sample data, and test your solutions!</p>
            <a href="/#compiler" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Open SQL Compiler →
            </a>
        </div>

        <h2>Learning Tips</h2>
        <ul>
            <li>Start with beginner exercises even if you have some SQL experience</li>
            < Don't look at solutions immediately - try solving first</li>
            <li>Test your solutions with different data sets</li>
            <li>Try to find multiple ways to solve the same problem</li>
            <li>Practice writing queries from memory</li>
            <li>Time yourself to simulate interview conditions</li>
            <li>Understand WHY a solution works, not just HOW</li>
        </ul>

        <h2>Next Steps</h2>
        <p>After completing these exercises:</p>
        <ul>
            <li>Try solving <a href="/learn/sql-interview-questions" class="text-purple-600 hover:text-purple-800 font-medium">real SQL interview questions</a></li>
            <li>Learn advanced <a href="/learn/sql-best-practices" class="text-purple-600 hover:text-purple-800 font-medium">SQL best practices</a></li>
            <li>Explore <a href="/learn/sql-use-cases" class="text-purple-600 hover:text-purple-800 font-medium">real-world SQL use cases</a></li>
        </ul>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-basics-tutorial" class="text-purple-600 hover:text-purple-800">→ SQL Basics Tutorial</a>
                <a href="/learn/sql-joins-tutorial" class="text-purple-600 hover:text-purple-800">→ SQL JOINs Tutorial</a>
                <a href="/learn/sql-functions" class="text-purple-600 hover:text-purple-800">→ SQL Functions Guide</a>
                <a href="/learn/sql-interview-questions" class="text-purple-600 hover:text-purple-800">→ SQL Interview Questions</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/includes/learn_footer.php'; ?>
