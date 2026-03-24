<?php
// app/user/editor.php
require_once __DIR__ . '/../config/auth_middleware.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <title>Editor - SQL Playground</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CodeMirror -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/sql/sql.min.js"></script>
    <style>
        .CodeMirror { height: 100%; border-radius: 0.5rem; font-size: 14px; }
    </style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col">
    <!-- Header -->
    <nav class="bg-white border-b px-4 py-2 flex justify-between items-center shadow-sm z-10">
        <div class="flex items-center gap-4">
            <a href="dashboard.php" class="text-gray-500 hover:text-gray-900">← Back</a>
            <span class="font-bold text-lg">SQL Editor</span>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-mono"><?= $_SESSION['db_name'] ?></span>
        </div>
        <div class="flex gap-2">
            <button id="btn-reset-db" class="px-3 py-1 text-red-600 hover:bg-red-50 rounded border border-red-200 text-sm">Reset DB</button>
            <button id="btn-run" class="bg-green-600 hover:bg-green-700 text-white px-6 py-1 rounded font-bold shadow flex items-center gap-2">
                <span>▶ Run</span>
            </button>
        </div>
    </nav>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Editor -->
        <div class="w-1/2 p-4 flex flex-col">
            <div class="flex-1 shadow-lg border rounded-lg bg-white overflow-hidden">
                <textarea id="sql-editor">-- Write your SQL here...
SELECT 1+1 as result;</textarea>
            </div>
            <div class="mt-2 text-xs text-gray-500 flex justify-between">
                <span>Ctrl+Enter to Run</span>
                <span id="status-bar">Ready</span>
            </div>
        </div>

        <!-- Results -->
        <div class="w-1/2 p-4 pl-0 flex flex-col">
            <div class="bg-white rounded-lg shadow-lg border flex-1 flex flex-col overflow-hidden">
                <div class="bg-gray-50 border-b px-4 py-2 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Results</h3>
                    <span id="exec-time" class="text-xs text-gray-500"></span>
                </div>
                <div id="results-area" class="flex-1 overflow-auto p-4 content-start">
                    <p class="text-gray-400 text-center mt-10">Execute a query to see results</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const editor = CodeMirror.fromTextArea(document.getElementById('sql-editor'), {
            mode: 'text/x-mysql',
            theme: 'dracula',
            lineNumbers: true,
            indentWithTabs: true,
            smartIndent: true,
            matchBrackets: true,
            autofocus: true
        });

        // Handle Run
        async function runQuery() {
            const sql = editor.getValue();
            if(!sql.trim()) return;

            const btn = document.getElementById('btn-run');
            const status = document.getElementById('status-bar');
            const resultsArea = document.getElementById('results-area');
            const timeArea = document.getElementById('exec-time');

            btn.disabled = true;
            btn.classList.add('opacity-50');
            status.textContent = 'Running...';
            resultsArea.innerHTML = '<div class="flex justify-center mt-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>'; // Loading spinner

            try {
                const res = await fetch('/app/api/run_sql.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ sql })
                });
                const data = await res.json();

                btn.disabled = false;
                btn.classList.remove('opacity-50');
                status.textContent = 'Ready';
                
                if (data.error) {
                    resultsArea.innerHTML = `<div class="bg-red-50 text-red-700 p-4 rounded border border-red-200 font-mono text-sm whitespace-pre-wrap">${data.error}</div>`;
                    timeArea.textContent = '';
                } else {
                    timeArea.textContent = `${data.time_ms} ms`;
                    if (data.results && Array.isArray(data.results)) {
                        renderTable(data.columns, data.results);
                    } else {
                        resultsArea.innerHTML = `<div class="bg-green-50 text-green-700 p-4 rounded border border-green-200">${data.results}</div>`;
                    }
                }

            } catch (e) {
                console.error(e);
                resultsArea.innerHTML = `<div class="bg-red-50 text-red-700 p-4 rounded border border-red-200">Network Error</div>`;
                btn.disabled = false;
                btn.classList.remove('opacity-50');
            }
        }

        function renderTable(cols, rows) {
            if (rows.length === 0) {
                 document.getElementById('results-area').innerHTML = '<div class="text-gray-500 italic">No rows returned</div>';
                 return;
            }
            
            let html = '<table class="min-w-full text-left text-sm whitespace-nowrap">';
            // Header
            html += '<thead class="bg-gray-100 border-b"><tr>';
            cols.forEach(c => html += `<th class="px-4 py-2 font-medium text-gray-700">${c}</th>`);
            html += '</tr></thead>';
            // Body
            html += '<tbody class="divide-y divide-gray-200">';
            rows.forEach(r => {
                html += '<tr class="hover:bg-gray-50">';
                cols.forEach(c => html += `<td class="px-4 py-2 text-gray-600 font-mono">${r[c] === null ? '<span class="text-gray-400">NULL</span>' : escapeHtml(r[c])}</td>`);
                html += '</tr>';
            });
            html += '</tbody></table>';
            
            document.getElementById('results-area').innerHTML = html;
        }

        function escapeHtml(text) {
             if (text === null || text === undefined) return '';
             return String(text)
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        }

        document.getElementById('btn-run').addEventListener('click', runQuery);
        
        // Ctrl+Enter to Run
        editor.addKeyMap({
            "Ctrl-Enter": function(cm) {
                runQuery();
            }
        });
        
        // Reset DB
        document.getElementById('btn-reset-db').addEventListener('click', async () => {
             if(confirm("Are you sure? This will DROP and RECREATE your database. All data will be lost.")) {
                 const res = await fetch('/app/api/reset_db.php', { method: 'POST' });
                 const d = await res.json();
                 alert(d.message || d.error);
             }
        });
    </script>
</body>
</html>
