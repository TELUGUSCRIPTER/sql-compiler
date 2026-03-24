<?php
$pageTitle = "<?= $pageTitle ?> | SQLCompiler.shop";
$pageDesc = "<?= $pageDesc ?>";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
        <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="hidden lg:block space-y-4">
                <h3 class="font-bold text-white uppercase tracking-wider text-sm mb-4">SQL Tutorial</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="sql-interview-questions.php" class="hover:text-cyan-400">Interview Qs</a></li>
                    <li><a href="sql-practice-examples.php" class="text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3 -ml-3">Practice Examples</a></li>
                    <li><a href="sql-data-types.php" class="hover:text-cyan-400">Data Types</a></li>
                </ul>
            </aside>

            <!-- Content -->
            <article class="lg:col-span-3 prose prose-invert prose-lg max-w-none">
                <h1 class="text-4xl font-bold mb-6">SQL Practice Examples</h1>
                <p>The best way to learn is by doing. Copy these examples into our <a href="../index.php">online compiler</a> to see them in action.</p>

                <div class="space-y-12 mt-8">
                    <!-- Example 1 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">1. Creating a Student Database</h3>
                        <p class="text-slate-400 mb-2">First, let's create a table to store student information.</p>
                        <pre><code class="language-sql">CREATE TABLE Students (
    StudentID int,
    FirstName varchar(255),
    LastName varchar(255),
    Age int,
    Grade varchar(5)
);

INSERT INTO Students VALUES (1, 'John', 'Doe', 15, 'A');
INSERT INTO Students VALUES (2, 'Jane', 'Smith', 16, 'B');
INSERT INTO Students VALUES (3, 'Mike', 'Johnson', 15, 'A');

SELECT * FROM Students;</code></pre>
                    </div>

                    <!-- Example 2 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">2. Filtering Data</h3>
                        <p class="text-slate-400 mb-2">Find all students who have an 'A' grade.</p>
                        <pre><code class="language-sql">SELECT FirstName, LastName 
FROM Students 
WHERE Grade = 'A';</code></pre>
                    </div>

                    <!-- Example 3 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">3. Updating Records</h3>
                        <p class="text-slate-400 mb-2">Mike studied hard and improved his grade. Let's update it.</p>
                        <pre><code class="language-sql">UPDATE Students 
SET Grade = 'A+' 
WHERE StudentID = 3;

-- Check the result
SELECT * FROM Students WHERE StudentID = 3;</code></pre>
                    </div>

                     <!-- Example 4 -->
                     <div>
                        <h3 class="text-2xl font-bold text-white mb-3">4. Ordering Data</h3>
                        <p class="text-slate-400 mb-2">List all students sorted by their Age, youngest first.</p>
                        <pre><code class="language-sql">SELECT * FROM Students ORDER BY Age ASC;</code></pre>
                    </div>

                </div>

            </article>
        </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
