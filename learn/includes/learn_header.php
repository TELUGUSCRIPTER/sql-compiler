<?php
// learn/includes/learn_header.php
$pageTitle = $pageTitle ?? 'Learn SQL';
$metaDescription = $metaDescription ?? 'Learn SQL with comprehensive tutorials, examples, and practice exercises. Master SQL queries, joins, functions, and more.';
$metaKeywords = $metaKeywords ?? 'SQL tutorial, learn SQL, SQL guide, SQL examples';
$canonicalUrl = $canonicalUrl ?? 'https://sqlcompiler.shop' . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | SQL Compiler - Learn SQL Online</title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords) ?>">
    <meta name="author" content="SQL Compiler">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">


    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?> | SQL Compiler">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="SQLCompiler.shop">
    <meta property="og:image" content="https://sqlcompiler.shop/assets/og-image.jpg">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/logo.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/sql.min.js"></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .content-body {
            line-height: 1.8;
        }

        .content-body h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .content-body h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            color: #374151;
        }

        .content-body p {
            margin-bottom: 1.25rem;
            color: #4b5563;
        }

        .content-body ul,
        .content-body ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }

        .content-body li {
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        .content-body code {
            background: #f3f4f6;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: #db2777;
        }

        .content-body pre {
            margin: 1.5rem 0;
            background: #1f2937;
            border-radius: 0.5rem;
            overflow-x: auto;
        }

        .content-body pre code {
            background: transparent;
            color: #e5e7eb;
            padding: 1rem;
            display: block;
        }

        .content-body table {
            width: 100%;
            margin: 1.5rem 0;
            border-collapse: collapse;
        }

        .content-body th,
        .content-body td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
            text-align: left;
        }

        .content-body th {
            background: #f9fafb;
            font-weight: 600;
        }

        .content-body blockquote {
            border-left: 4px solid #9333ea;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #6b7280;
        }

        .try-it-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 2rem 0;
            color: white;
        }
    </style>

    <!-- Schema.org Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "<?= htmlspecialchars($pageTitle) ?>",
      "description": "<?= htmlspecialchars($metaDescription) ?>",
      "author": {
        "@type": "Organization",
        "name": "SQL Compiler"
      },
      "publisher": {
        "@type": "Organization",
        "name": "SQL Compiler",
        "logo": {
          "@type": "ImageObject",
          "url": "https://sqlcompiler.shop/assets/logo.png"
        }
      }
    }
    </script>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="/assets/logo.png" alt="SQL Compiler Logo" class="h-8 w-8 mr-2">
                        <span class="text-xl font-bold text-gray-900">SQL Compiler</span>
                    </a>
                    <div class="hidden md:flex ml-10 space-x-6">
                        <a href="/" class="text-gray-700 hover:text-purple-600 transition">Home</a>
                        <a href="/learn/sql-basics-tutorial" class="text-purple-600 font-medium">Learn</a>
                        <a href="/learn/sql-practice-beginner"
                            class="text-gray-700 hover:text-purple-600 transition">Practice</a>
                        <a href="/learn/sql-interview-questions"
                            class="text-gray-700 hover:text-purple-600 transition">Interview Q&amp;A</a>
                        <a href="/#compiler" class="text-gray-700 hover:text-purple-600 transition">Compiler</a>
                        <a href="/faq" class="text-gray-700 hover:text-purple-600 transition">FAQ</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/register.php"
                        class="hidden md:inline-block px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                        Start Learning
                    </a>
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
            <div class="px-4 py-3 space-y-2">
                <a href="/" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Home</a>
                <a href="/learn/sql-basics-tutorial"
                    class="block px-3 py-2 text-purple-600 font-medium bg-purple-50 rounded">Learn</a>
                <a href="/#compiler" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Compiler</a>
                <a href="/faq" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">FAQ</a>
                <a href="/register.php"
                    class="block px-3 py-2 bg-purple-600 text-white rounded text-center font-medium">Start Learning</a>
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Initialize syntax highlighting
        document.addEventListener('DOMContentLoaded', function () {
            hljs.highlightAll();
        });
    </script>