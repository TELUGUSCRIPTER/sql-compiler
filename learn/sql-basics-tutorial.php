<?php
$pageTitle = 'SQL Basics Tutorial for Beginners';
$metaDescription = 'Learn SQL basics from scratch. Complete beginner-friendly tutorial covering SQL fundamentals, syntax, queries, and essential commands with examples. Start learning SQL today!';
$metaKeywords = 'SQL basics, learn SQL, SQL tutorial, SQL for beginners, SQL fundamentals, SQL queries';
$canonicalUrl = 'https://sqlcompiler.shop/learn/sql-basics-tutorial';
$breadcrumbs = [
    ['title' => 'Learn', 'url' => '/learn/sql-basics-tutorial'],
    ['title' => 'SQL Basics Tutorial']
];

require_once __DIR__ . '/includes/learn_header.php';
require_once __DIR__ . '/includes/breadcrumb.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white rounded-lg shadow-sm p-8 content-body">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">SQL Basics Tutorial for Beginners</h1>
        
        <p class="text-lg text-gray-600 mb-8">
            Structured Query Language (SQL) is the standard language for managing and manipulating relational databases. Whether you're aspiring to become a data analyst, database administrator, or web developer, learning SQL is an essential skill. This comprehensive guide will walk you through the fundamentals of SQL, helping you write your first queries and understand how databases work.
        </p>
        
        <!-- Table of Contents -->
        <div class="bg-purple-50 border-l-4 border-purple-600 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">📑 Table of Contents</h2>
            <ul class="space-y-2">
                <li><a href="#what-is-sql" class="text-purple-600 hover:text-purple-800">What is SQL?</a></li>
                <li><a href="#why-learn-sql" class="text-purple-600 hover:text-purple-800">Why Learn SQL?</a></li>
                <li><a href="#sql-syntax" class="text-purple-600 hover:text-purple-800">SQL Syntax Fundamentals</a></li>
                <li><a href="#create-database" class="text-purple-600 hover:text-purple-800">Creating Databases and Tables</a></li>
                <li><a href="#insert-data" class="text-purple-600 hover:text-purple-800">Inserting Data</a></li>
                <li><a href="#select-data" class="text-purple-600 hover:text-purple-800">Selecting and Querying Data</a></li>
                <li><a href="#update-delete" class="text-purple-600 hover:text-purple-800">Updating and Deleting Data</a></li>
                <li><a href="#practice" class="text-purple-600 hover:text-purple-800">Practice Exercises</a></li>
            </ul>
        </div>
        
        <h2 id="what-is-sql">What is SQL?</h2>
        <p>
            SQL (Structured Query Language) is a standardized programming language designed for managing data in relational database management systems (RDBMS). Developed in the 1970s at IBM, SQL has become the universal language for database interaction, supported by virtually all major database systems including MySQL, PostgreSQL, Oracle, SQL Server, and SQLite.
        </p>
        <p>
            SQL allows you to perform various operations on data:
        </p>
        <ul>
            <li><strong>Retrieve</strong> data from databases (SELECT)</li>
            <li><strong>Insert</strong> new records (INSERT)</li>
            <li><strong>Update</strong> existing records (UPDATE)</li>
            <li><strong>Delete</strong> records (DELETE)</li>
            <li><strong>Create</strong> new databases and tables (CREATE)</li>
            <li><strong>Modify</strong> database structures (ALTER)</li>
            <li><strong>Control</strong> access to data (GRANT, REVOKE)</li>
        </ul>
        
        <h2 id="why-learn-sql">Why Learn SQL?</h2>
        <p>
            Learning SQL opens doors to numerous career opportunities and provides valuable skills for data-driven decision making. Here's why SQL is worth your time:
        </p>
        
        <h3>1. High Demand in Job Market</h3>
        <p>
            SQL is consistently ranked among the most in-demand technical skills. Data analysts, business intelligence professionals, backend developers, and data scientists all require SQL proficiency. According to recent surveys, over 50% of data-related job postings require SQL knowledge.
        </p>
        
        <h3>2. Universal Database Language</h3>
        <p>
            Unlike proprietary technologies that come and go, SQL has been the standard for over 40 years. Once you learn SQL, you can work with almost any relational database system with minimal adjustments.
        </p>
        
        <h3>3. Powerful Data Analysis</h3>
        <p>
            SQL enables you to extract meaningful insights from large datasets. You can aggregate data, calculate statistics, join multiple tables, and answer complex business questions - all with simple, readable queries.
        </p>
        
        <h3>4. Easy to Learn</h3>
        <p>
            SQL syntax is designed to be intuitive and close to natural English. Commands like SELECT, FROM, WHERE, and ORDER BY make SQL one of the most beginner-friendly programming languages.
        </p>
        
        <h2 id="sql-syntax">SQL Syntax Fundamentals</h2>
        <p>
            Before diving into specific commands, let's understand the basic syntax rules of SQL:
        </p>
        
        <h3>Key Syntax Rules</h3>
        <ul>
            <li><strong>Semicolon (;)</strong>: SQL statements end with a semicolon. It's the standard way to separate multiple SQL statements.</li>
            <li><strong>Case Insensitive</strong>: SQL keywords are not case-sensitive. SELECT, select, and Select all work the same. However, it's conventional to write keywords in UPPERCASE for readability.</li>
            <li><strong>Whitespace</strong>: Extra spaces and line breaks are ignored. You can format queries however you like for readability.</li>
            <li><strong>Comments</strong>: Use -- for single-line comments and /* */ for multi-line comments.</li>
            <li><strong>String Literals</strong>: Always enclose text values in single quotes: 'text'</li>
        </ul>
        
        <pre><code class="language-sql">-- This is a single-line comment

/* This is a
   multi-line comment */

SELECT column1, column2
FROM table_name
WHERE condition;  -- Statement ends with semicolon
</code></pre>
        
        <h2 id="create-database">Creating Databases and Tables</h2>
        <p>
            Before storing data, you need to create a database and define its structure using tables. Think of a database as a filing cabinet and tables as individual folders within it.
        </p>
        
        <h3>Creating a Database</h3>
        <pre><code class="language-sql">CREATE DATABASE company_db;
USE company_db;
</code></pre>
        
        <h3>Creating a Table</h3>
        <p>
            Tables consist of columns (fields) and rows (records). Each column has a name and data type. Let's create an employees table:
        </p>
        
        <pre><code class="language-sql">CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    department VARCHAR(50),
    salary DECIMAL(10, 2),
    hire_date DATE
);
</code></pre>
        
        <p>
            Let's break down this CREATE TABLE statement:
        </p>
        <ul>
            <li><code>id INT PRIMARY KEY AUTO_INCREMENT</code> - A unique identifier that automatically increments</li>
            <li><code>VARCHAR(50)</code> - Variable-length string up to 50 characters</li>
            <li><code>NOT NULL</code> - Field cannot be empty</li>
            <li><code>UNIQUE</code> - No duplicate values allowed</li>
            <li><code>DECIMAL(10,2)</code> - Number with up to 10 digits, 2 after decimal point</li>
            <li><code>DATE</code> - Stores date values</li>
        </ul>
        
        <h2 id="insert-data">Inserting Data</h2>
        <p>
            Once you have a table, you can add data using the INSERT INTO statement. There are two common ways to insert data:
        </p>
        
        <h3>Method 1: Specify All Columns</h3>
        <pre><code class="language-sql">INSERT INTO employees (first_name, last_name, email, department, salary, hire_date)
VALUES ('John', 'Doe', 'john.doe@company.com', 'Engineering', 75000.00, '2024-01-15');
</code></pre>
        
        <h3>Method 2: Insert Multiple Rows</h3>
        <pre><code class="language-sql">INSERT INTO employees (first_name, last_name, email, department, salary, hire_date)
VALUES 
    ('Jane', 'Smith', 'jane.smith@company.com', 'Marketing', 65000.00, '2024-02-01'),
    ('Bob', 'Johnson', 'bob.johnson@company.com', 'Sales', 70000.00, '2024-02-15'),
    ('Alice', 'Williams', 'alice.w@company.com', 'Engineering', 80000.00, '2024-03-01');
</code></pre>
        
        <h2 id="select-data">Selecting and Querying Data</h2>
        <p>
            The SELECT statement is the most frequently used SQL command. It retrieves data from one or more tables.
        </p>
        
        <h3>Basic SELECT Syntax</h3>
        <pre><code class="language-sql">-- Select all columns
SELECT * FROM employees;

-- Select specific columns
SELECT first_name, last_name, salary FROM employees;

-- Select with condition
SELECT * FROM employees WHERE department = 'Engineering';

-- Select with multiple conditions
SELECT * FROM employees 
WHERE department = 'Engineering' AND salary > 70000;
</code></pre>
        
        <h3>Filtering with WHERE Clause</h3>
        <p>
            The WHERE clause filters records based on specified conditions:
        </p>
        
        <pre><code class="language-sql">-- Comparison operators
SELECT * FROM employees WHERE salary >= 70000;

-- BETWEEN operator
SELECT * FROM employees WHERE salary BETWEEN 60000 AND 80000;

-- IN operator
SELECT * FROM employees WHERE department IN ('Engineering', 'Sales');

-- LIKE operator (pattern matching)
SELECT * FROM employees WHERE email LIKE '%@company.com';

-- IS NULL / IS NOT NULL
SELECT * FROM employees WHERE email IS NOT NULL;
</code></pre>
        
        <h3>Sorting Results with ORDER BY</h3>
        <pre><code class="language-sql">-- Sort ascending (default)
SELECT * FROM employees ORDER BY salary;

-- Sort descending
SELECT * FROM employees ORDER BY salary DESC;

-- Sort by multiple columns
SELECT * FROM employees ORDER BY department, salary DESC;
</code></pre>
        
        <h3>Limiting Results</h3>
        <pre><code class="language-sql">-- Get top 5 highest paid employees
SELECT * FROM employees ORDER BY salary DESC LIMIT 5;
</code></pre>
        
        <h2 id="update-delete">Updating and Deleting Data</h2>
        
        <h3>UPDATE Statement</h3>
        <p>
            The UPDATE statement modifies existing records. Always use a WHERE clause to avoid updating all rows!
        </p>
        
        <pre><code class="language-sql">-- Update single record
UPDATE employees 
SET salary = 82000.00 
WHERE id = 1;

-- Update multiple columns
UPDATE employees 
SET salary = salary * 1.10, department = 'Senior Engineering'
WHERE id = 4 AND salary > 75000;
</code></pre>
        
        <h3>DELETE Statement</h3>
        <p>
            The DELETE statement removes records from a table. Use with caution!
        </p>
        
        <pre><code class="language-sql">-- Delete specific record
DELETE FROM employees WHERE id = 5;

-- Delete with condition
DELETE FROM employees WHERE hire_date < '2024-01-01';

-- Delete all records (dangerous!)
-- DELETE FROM employees;  -- Use with extreme caution!
</code></pre>
        
        <h2 id="practice">Practice Exercises</h2>
        <p>
            Now it's time to practice what you've learned! Try these exercises:
        </p>
        
        <h3>Exercise 1: Create a Students Table</h3>
        <p>Create a table to store student information with fields for ID, name, age, major, and GPA.</p>
        
        <h3>Exercise 2: Insert Sample Data</h3>
        <p>Insert at least 5 student records into your table.</p>
        
        <h3>Exercise 3: Query Practice</h3>
        <ul>
            <li>Select all students majoring in Computer Science</li>
            <li>Find students with GPA above 3.5</li>
            <li>List students ordered by GPA (highest first)</li>
            <li>Update a student's major</li>
            <li>Count how many students are in each major</li>
        </ul>
        
        <div class="try-it-box">
            <h3 class="text-xl font-bold mb-2">🚀 Try It Yourself!</h3>
            <p class="mb-4">Practice these SQL commands in our interactive online compiler. Write queries, see results instantly, and build your confidence!</p>
            <a href="/#compiler" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Open SQL Compiler →
            </a>
        </div>
        
        <h2>Next Steps</h2>
        <p>
            Congratulations! You've learned the fundamentals of SQL. You now know how to create databases and tables, insert data, query information, and modify records. These are the building blocks of database management.
        </p>
        <p>
            Continue your SQL journey with these advanced topics:
        </p>
        <ul>
            <li><a href="/learn/sql-joins-tutorial" class="text-purple-600 hover:text-purple-800 font-medium">SQL JOINs</a> - Combine data from multiple tables</li>
            <li><a href="/learn/sql-functions" class="text-purple-600 hover:text-purple-800 font-medium">SQL Functions</a> - Aggregate and manipulate data</li>
            <li><a href="/learn/sql-subqueries" class="text-purple-600 hover:text-purple-800 font-medium">SQL Subqueries</a> - Write complex nested queries</li>
        </ul>
        
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-3">📚 Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/learn/sql-select-statement" class="text-purple-600 hover:text-purple-800">→ SQL SELECT Statement Complete Guide</a>
                <a href="/learn/sql-cheat-sheet" class="text-purple-600 hover:text-purple-800">→ SQL Commands Cheat Sheet</a>
                <a href="/learn/sql-practice-exercises" class="text-purple-600 hover:text-purple-800">→ 50 SQL Practice Exercises</a>
                <a href="/learn/common-sql-mistakes" class="text-purple-600 hover:text-purple-800">→ Common SQL Mistakes to Avoid</a>
            </div>
        </div>
    </article>
</main>

<?php require_once __DIR__ . '/includes/learn_footer.php'; ?>
