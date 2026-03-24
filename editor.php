<?php
// editor.php
require_once __DIR__ . '/config/auth_middleware.php';
requireLogin();
require_once 'includes/header.php';
?>
<!-- Custom CodeMirror CSS overrides for dark theme embedding -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/nord.min.css">
<style>
    .CodeMirror { height: 100%; font-family: 'Fira Code', monospace; font-size: 14px; background: transparent; }
    .cm-s-nord.CodeMirror { background: transparent; }
    /* Custom Animation for Scroll Hint */
    @keyframes bounce-right {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(3px); }
    }
    .animate-bounce-right {
        animation: bounce-right 1s infinite;
    }
    /* Formatter Button */
    .btn-icon { @apply p-1.5 text-slate-400 hover:text-white rounded hover:bg-white/10 transition; }
    /* Hide scrollbar for cleaner look but keep function */
    /* .scrollbar-hide::-webkit-scrollbar { display: none; } */
    /* .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; } */
    
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

    /* AI Loading Overlay */
    .ai-loading-overlay {
        position: absolute; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);
        display: flex; align-items: center; justify-content: center; z-index: 50;
        opacity: 0; pointer-events: none; transition: opacity 0.3s;
    }
    .ai-loading-overlay.active { opacity: 1; pointer-events: auto; }
    
    /* Toast Notification */
    /* Toast Notification */
    #ai-toast {
        position: fixed; top: auto; bottom: 40px !important; left: 50%; transform: translateX(-50%) translateY(100px);
        background: #1e293b; border: 1px solid rgba(139, 92, 246, 0.3);
        padding: 12px 20px; rounded-lg; shadow-2xl; z-index: 9999;
        display: flex; items-center; gap: 12px; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-radius: 9999px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
    }
    #ai-toast.show { transform: translateX(-50%) translateY(0); }
</style>

<!-- Mobile Menu Toggle -->
<button id="mobile-menu-toggle" class="md:hidden fixed bottom-4 right-4 z-50 w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full shadow-xl flex items-center justify-center text-white hover:shadow-blue-500/50 transition-all">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
</button>

<!-- AI Toast -->
<div id="ai-toast">
    <div class="flex items-center gap-2 text-sm text-slate-200">
        <span class="text-xl">✨</span>
        <span>Stuck? Let AI fix it for you!</span>
    </div>
    <div class="flex gap-2">
        <button id="toast-yes" class="px-3 py-1 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-full transition">Fix it</button>
        <button id="toast-no" class="px-2 py-1 text-slate-400 hover:text-white text-xs transition">Dismiss</button>
    </div>
</div>

<div class="flex flex-1 h-full overflow-hidden relative">
    
    <!-- LEFT SIDEBAR: Schema Explorer -->
    <aside id="left-sidebar" class="w-64 md:w-64 bg-slate-900/80 border-r border-white/5 flex flex-col backdrop-blur-sm transition-all duration-300 absolute md:relative h-full z-40 -translate-x-full md:translate-x-0">
        <div class="p-4 border-b border-white/5">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                My Tables
            </h2>
        </div>
        <div id="schema-browser" class="flex-1 overflow-y-auto p-4 space-y-6">
            <!-- Populated by JS -->
            <div class="animate-pulse space-y-3">
                <div class="h-4 bg-slate-800 rounded w-3/4"></div>
                <div class="h-2 bg-slate-800 rounded w-1/2 ml-4"></div>
                <div class="h-2 bg-slate-800 rounded w-1/2 ml-4"></div>
            </div>
        </div>
    </aside>

    <!-- CENTER: Input & Output -->
    <main id="center-panel" class="flex-1 flex flex-col min-w-0 border-r border-white/5 transition-all duration-300">
        <!-- Editor Toolbar -->
        <div class="bg-slate-900/50 border-b border-white/5 px-2 md:px-4 py-2 flex justify-between items-center group">
            <div class="flex items-center gap-2 text-slate-400 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                <span class="hidden sm:inline">Input</span>
            </div>
            <div class="flex gap-1 md:gap-2 items-center flex-wrap">
                <!-- Productivity Tools -->
                <button id="btn-format" class="text-xs text-slate-400 hover:text-white px-2 py-1 hover:bg-white/5 rounded border border-transparent hover:border-white/10 transition" title="Format SQL">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                    Format
                </button>
                <div class="h-4 w-px bg-white/10 mx-1"></div>
                
                <button id="btn-ai-fix" class="text-xs text-purple-400 hover:text-white px-2 py-1 hover:bg-white/5 rounded border border-transparent hover:border-purple-500/30 transition flex items-center gap-1" title="Ask Gemini AI to fix this query">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <span>Ask AI</span>
                </button>
                <div class="h-4 w-px bg-white/10 mx-1"></div>

                <!-- Maximize Editor Button (Hidden on mobile) -->
                <button id="btn-max-editor" class="hidden md:block text-slate-500 hover:text-white transition" title="Maximize Editor">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                </button>
                <div class="hidden md:block h-4 w-px bg-white/10 mx-1"></div>
                
                <button id="btn-history" class="text-slate-400 hover:text-white transition" title="History">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </button>

                <div class="hidden md:block h-4 w-px bg-white/10 mx-1"></div>
                <button id="btn-reset-db" class="px-2 md:px-3 py-1 text-xs text-red-400 hover:bg-red-500/10 rounded border border-transparent hover:border-red-500/20 transition">Reset</button>
                <button id="btn-run" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-3 md:px-4 py-1.5 rounded shadow hover:shadow-blue-500/20 text-xs font-bold flex items-center gap-1.5 transition">
                    <span class="hidden sm:inline">Run SQL</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </button>
            </div>
        </div>

        <!-- Code Editor (Height 50%) -->
        <div id="editor-container" class="h-1/2 relative bg-slate-900/30 transition-all duration-300">
            <textarea id="sql-editor">-- Write your SQL here...
SELECT 1;</textarea>
            <!-- Loading Overlay -->
            <div id="ai-loading" class="ai-loading-overlay">
                 <div class="flex flex-col items-center gap-3">
                     <svg class="animate-spin h-8 w-8 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-purple-300 font-mono animate-pulse">AI is thinking...</span>
                 </div>
            </div>
        </div>

        <!-- Results Toolbar -->
        <div class="bg-slate-900/50 border-y border-white/5 px-4 py-2 flex justify-between items-center group">
            <h3 class="text-xs font-bold text-slate-400 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" /></svg>
                Output
            </h3>
            <div class="flex items-center gap-3">
                 <button id="btn-export-csv" class="hidden text-xs text-slate-500 hover:text-green-400 transition flex items-center gap-1" title="Export CSV">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> CSV
                 </button>
                 <button id="btn-export-json" class="hidden text-xs text-slate-500 hover:text-yellow-400 transition flex items-center gap-1" title="Export JSON">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> JSON
                 </button>
                 <span id="exec-time" class="text-xs font-mono text-emerald-400 border-l border-white/10 pl-3"></span>
                 <!-- Maximize Output Button -->
                 <button id="btn-max-output" class="text-slate-500 hover:text-white transition" title="Maximize Output">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
            </div>
        </div>

        <!-- Results Area (Height 50%) -->
        <div id="results-area" class="h-1/2 overflow-auto bg-slate-900/40 p-4 font-mono text-sm text-slate-300 transition-all duration-300 border-t border-white/5">
            <p class="text-slate-600 text-center mt-10">Results will appear here...</p>
        </div>
    </main>

    <!-- RIGHT SIDEBAR: Data Previews -->
    <aside id="right-sidebar" class="w-80 md:w-80 bg-slate-900/80 border-l border-white/5 flex flex-col backdrop-blur-sm transition-all duration-300 ease-in-out absolute md:relative h-full z-40 right-0 translate-x-full md:translate-x-0">
        <div class="p-4 border-b border-white/5 flex justify-between items-center">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span class="whitespace-nowrap">One-Glance Data</span>
            </h2>
            <button id="btn-expand-right" class="text-slate-500 hover:text-cyan-400 transition" title="Expand View">
                <svg id="icon-expand-right" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
            </button>
        </div>
        <div id="data-previews" class="flex-1 overflow-y-auto p-4 space-y-8">
            <!-- Populated by JS -->
        </div>
    </aside>
</div>

<!-- History Modal -->
<div id="history-modal" class="fixed inset-0 bg-black/80 z-[60] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-slate-900 border border-white/10 rounded-xl w-full max-w-2xl max-h-[80vh] flex flex-col shadow-2xl">
        <div class="p-4 border-b border-white/10 flex justify-between items-center">
             <h3 class="text-white font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Query History
             </h3>
             <button id="close-history" class="text-slate-400 hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        <div id="history-list" class="flex-1 overflow-y-auto p-4 space-y-2">
            <!-- Populated by JS -->
            <div class="text-center text-slate-500 py-10">Loading...</div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sql-formatter@4.0.2/dist/sql-formatter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/sql/sql.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
<script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', () => {
        const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
        const leftSidebar = document.getElementById('left-sidebar');
        const rightSidebar = document.getElementById('right-sidebar');
        let mobileMenuOpen = false;
        let activeSidebar = null; // 'left', 'right', or null
        
        // Create backdrop
        const backdrop = document.createElement('div');
        backdrop.id = 'mobile-menu-backdrop';
        backdrop.className = 'fixed inset-0 bg-black/50 z-30 hidden md:hidden backdrop-blur-sm';
        document.body.appendChild(backdrop);
        
        mobileMenuBtn.addEventListener('click', () => {
            if (mobileMenuOpen) {
                // Close menu
                closeMobileMenu();
            } else {
                // Open left sidebar by default
                openMobileSidebar('left');
            }
        });
        
        // Backdrop click closes menu
        backdrop.addEventListener('click', closeMobileMenu);
        
        function openMobileSidebar(side) {
            mobileMenuOpen = true;
            activeSidebar = side;
            backdrop.classList.remove('hidden');
            
            if (side === 'left') {
                leftSidebar.classList.remove('-translate-x-full');
                rightSidebar.classList.add('translate-x-full');
            } else {
                rightSidebar.classList.remove('translate-x-full');
                leftSidebar.classList.add('-translate-x-full');
            }
            
            // Change menu icon to X
            mobileMenuBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
        }
        
        function closeMobileMenu() {
            mobileMenuOpen = false;
            activeSidebar = null;
            backdrop.classList.add('hidden');
            leftSidebar.classList.add('-translate-x-full');
            rightSidebar.classList.add('translate-x-full');
            
            // Change icon back to hamburger
            mobileMenuBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>';
        }
        
        // Add swipe gesture to switch between sidebars on mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            if (!mobileMenuOpen) return;
            
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swiped left - show right sidebar
                    openMobileSidebar('right');
                } else {
                    // Swiped right - show left sidebar  
                    openMobileSidebar('left');
                }
            }
        }
    });

    // Layout Controls
    document.addEventListener('DOMContentLoaded', () => {
        const rightSidebar = document.getElementById('right-sidebar');
        const btnExpandRight = document.getElementById('btn-expand-right');
        const iconExpandRight = document.getElementById('icon-expand-right');
        
        const editorContainer = document.getElementById('editor-container');
        const resultsArea = document.getElementById('results-area');
        const btnMaxEditor = document.getElementById('btn-max-editor');
        const btnMaxOutput = document.getElementById('btn-max-output');

        // Toggle Right Sidebar Expansion
        let rightExpanded = false;
        btnExpandRight.addEventListener('click', () => {
            rightExpanded = !rightExpanded;
            if(rightExpanded) {
                // Expand Right, Shrink Center
                rightSidebar.classList.remove('w-80');
                rightSidebar.classList.add('w-[60%]'); // Take 60% of width
                iconExpandRight.classList.add('rotate-180');
            } else {
                // Restore
                rightSidebar.classList.remove('w-[60%]');
                rightSidebar.classList.add('w-80');
                iconExpandRight.classList.remove('rotate-180');
            }
        });

        // Toggle Center Vertical Split
        let editorMax = false;
        btnMaxEditor.addEventListener('click', () => {
            if(editorMax) {
                // Restore Split
                editorContainer.classList.remove('h-full');
                editorContainer.classList.add('h-1/2');
                resultsArea.classList.remove('hidden');
                btnMaxEditor.classList.remove('text-cyan-400');
            } else {
                // Maximize Editor
                editorContainer.classList.remove('h-1/2');
                editorContainer.classList.add('h-full');
                resultsArea.classList.add('hidden'); // Hide results completely
                btnMaxEditor.classList.add('text-cyan-400');
                // Ensure output max is off
                if(outputMax) btnMaxOutput.click(); 
            }
            editorMax = !editorMax;
            editor.refresh(); // Important for CodeMirror re-render
        });

        let outputMax = false;
        btnMaxOutput.addEventListener('click', () => {
             if(outputMax) {
                // Restore
                resultsArea.classList.remove('h-full');
                resultsArea.classList.add('h-1/2');
                editorContainer.classList.remove('hidden');
                btnMaxOutput.classList.remove('text-cyan-400');
            } else {
                // Maximize Output
                editorContainer.classList.add('hidden');
                resultsArea.classList.remove('h-1/2');
                resultsArea.classList.add('h-full');
                btnMaxOutput.classList.add('text-cyan-400');
                 // Ensure editor max is off
                 if(editorMax) btnMaxEditor.click();
            }
            outputMax = !outputMax;
        });
    });

    // Utility: XSS Protection
    function escapeHtml(text) {
        if (text === null || text === undefined) return 'NULL';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    const editor = CodeMirror.fromTextArea(document.getElementById('sql-editor'), {
        mode: 'text/x-mysql',
        theme: 'nord',
        lineNumbers: true,
        indentWithTabs: true,
        smartIndent: true,
        matchBrackets: true
    });

    // ---------------------------------------------
    // 1. Fetch Schema & Previews
    // ---------------------------------------------
    function renderSchema(data) {
        const schemaEl = document.getElementById('schema-browser');
        const previewEl = document.getElementById('data-previews');

        if (data.error) {
            schemaEl.innerHTML = `<div class="text-red-400 text-xs">${escapeHtml(data.error)}</div>`;
            return;
        }

        // Clear loading
        schemaEl.innerHTML = '';
        previewEl.innerHTML = '';

        if (!data.tables || data.tables.length === 0) {
            schemaEl.innerHTML = '<div class="text-slate-500 text-xs italic">No tables found.<br>Create one to start.</div>';
            previewEl.innerHTML = '<div class="text-slate-500 text-xs italic text-center mt-10">No data available</div>';
            return;
        }

        data.tables.forEach(table => {
            // --- Left Sidebar: Schema ---
            const tableDiv = document.createElement('div');
            let colsHtml = '';
            table.columns.forEach(col => {
                colsHtml += `
                    <div class="flex justify-between text-[11px] text-slate-500 hover:text-slate-300 pl-4 py-0.5">
                        <span>${escapeHtml(col.name)}</span>
                        <span class="text-blue-500/80 font-mono">${escapeHtml(col.type)}</span>
                    </div>
                `;
            });

            tableDiv.innerHTML = `
                <div class="mb-1 text-sm font-bold text-slate-200 flex items-center gap-2">
                    <span class="text-blue-400 inline-block">⊞</span> ${escapeHtml(table.name)}
                </div>
                <div class="border-l border-slate-700 ml-1.5 space-y-0.5">
                    ${colsHtml}
                </div>
            `;
            schemaEl.appendChild(tableDiv);

            // --- Right Sidebar: Preview Grid ---
            const previewDiv = document.createElement('div');
            const tableId = 'table-preview-' + Math.random().toString(36).substr(2, 9);
            
            // Build Mini Table
            let thead = '';
            let tbody = '';
            
            if (table.preview.length > 0) {
                const keys = Object.keys(table.preview[0]);
                
                thead = '<thead><tr class="text-left">';
                keys.forEach(k => thead += `<th class="pb-1 px-2 font-medium whitespace-nowrap">${escapeHtml(k)}</th>`);
                thead += '</tr></thead>';

                tbody = '<tbody class="text-slate-400">';
                table.preview.forEach(row => {
                    tbody += '<tr class="border-b border-white/5 last:border-0">';
                    keys.forEach(k => {
                        tbody += `<td class="py-1 px-2 whitespace-nowrap max-w-[150px] truncate text-[10px]">${escapeHtml(row[k])}</td>`;
                    });
                    tbody += '</tr>';
                });
                tbody += '</tbody>';
            } else {
                tbody = '<tr><td class="text-slate-500 italic py-2 px-2">Empty Table</td></tr>';
            }

            previewDiv.innerHTML = `
                <div class="mb-2 text-xs font-bold text-slate-300 flex justify-between items-center">
                    <span>${escapeHtml(table.name)}</span>
                    <span class="text-[10px] text-slate-500 font-normal">${table.preview.length} rows</span>
                </div>
                <div class="relative group">
                    <!-- Scroll Hint Overlay -->
                    <div id="hint-${tableId}" class="absolute inset-y-0 right-0 w-12 bg-gradient-to-l from-slate-900 via-slate-900/80 to-transparent pointer-events-auto cursor-pointer flex items-center justify-end pr-1 transition-opacity duration-300 opacity-0 hover:bg-slate-900/50">
                        <div class="animate-bounce-right text-cyan-400 group-hover/hint:text-cyan-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </div>
                    </div>

                    <div id="${tableId}" class="bg-slate-800/50 rounded-lg border border-white/5 overflow-x-auto custom-scrollbar">
                        <table class="text-[10px] w-full">
                            ${thead}
                            ${tbody}
                        </table>
                    </div>
                </div>
            `;
            previewEl.appendChild(previewDiv);

            // Scroll Hint Logic
            setTimeout(() => {
                const container = document.getElementById(tableId);
                const hint = document.getElementById('hint-' + tableId);
                
                if (container && hint) {
                    hint.addEventListener('click', () => {
                        container.scrollBy({ left: 150, behavior: 'smooth' });
                    });
                    const checkScroll = () => {
                        const maxScroll = container.scrollWidth - container.clientWidth;
                        if (maxScroll > 0 && container.scrollLeft < maxScroll - 5) {
                            hint.classList.remove('opacity-0', 'pointer-events-none');
                            hint.classList.add('pointer-events-auto');
                        } else {
                            hint.classList.add('opacity-0', 'pointer-events-none');
                            hint.classList.remove('pointer-events-auto');
                        }
                    };
                    container.addEventListener('scroll', checkScroll);
                    checkScroll();
                }
            }, 0);
        });
    }

    async function loadSchema() {
        const cached = localStorage.getItem('db_schema_cache');
        if (cached) {
            try {
                renderSchema(JSON.parse(cached));
            } catch(e) { console.error("Cache parse error", e); }
        }

        try {
            const res = await fetch('api/get_db_details.php');
            const data = await res.json();
            
            console.log("Schema loaded:", data); // Debug log
            
            if (!cached || JSON.stringify(data) !== cached) {
                renderSchema(data);
                localStorage.setItem('db_schema_cache', JSON.stringify(data));
            }
        } catch (e) {
            console.error("Schema load error:", e);
            if (!cached) {
                 document.getElementById('schema-browser').innerHTML = '<div class="text-red-400 text-xs">Failed to load schema</div>';
            }
        }
    }

    const savedDraft = localStorage.getItem('editor_sql_draft');
    if (savedDraft) {
        editor.setValue(savedDraft);
    }
    editor.on('change', () => {
        localStorage.setItem('editor_sql_draft', editor.getValue());
    });
    
    // Clear cache and load fresh schema
    localStorage.removeItem('db_schema_cache');
    loadSchema();

    // ---------------------------------------------
    // 2. Run Query
    // ---------------------------------------------
    async function runQuery() {
        const sql = editor.getValue();
        if(!sql.trim()) return;

        const btn = document.getElementById('btn-run');
        const resultsArea = document.getElementById('results-area');
        const timeArea = document.getElementById('exec-time');

        btn.disabled = true;
        btn.classList.add('opacity-50');
        resultsArea.innerHTML = '<div class="text-center mt-10 text-emerald-500 text-xs">Executing...</div>';

        try {
            const res = await fetch('api/run_sql.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ sql })
            });
            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (err) {
                throw { message: "Invalid JSON Response", responseBody: text };
            }
            
            btn.disabled = false;
            btn.classList.remove('opacity-50');

            if (data.error) {
                resultsArea.innerHTML = `<div class="text-red-400 text-xs font-mono p-4 border border-red-500/20 bg-red-500/10 rounded">${escapeHtml(data.error)}</div>`;
                timeArea.textContent = '';
                
                // Track errors for AI suggestion
                consecutiveErrors++;
                if (consecutiveErrors >= 2) {
                    aiToast.classList.add('show');
                }
            } else {
                timeArea.textContent = `${data.duration} ms`;
                consecutiveErrors = 0; // Reset on success
                
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
                
                if (data.type === 'result') {
                    let extraHtml = '';
                    if (data.message) {
                        extraHtml = `<div class="text-emerald-400 text-xs font-mono p-4 border border-emerald-500/20 bg-emerald-500/10 rounded mb-4">${escapeHtml(data.message)}</div>`;
                    }
                    const cols = data.data.length > 0 ? Object.keys(data.data[0]) : [];
                    renderTable(cols, data.data, extraHtml);
                } else if (data.type === 'affected') {
                    resultsArea.innerHTML = `<div class="text-emerald-400 text-xs font-mono p-4 border border-emerald-500/20 bg-emerald-500/10 rounded">${escapeHtml(data.message)}</div>`;
                }
                
                // Clear cache and force refresh schema
                localStorage.removeItem('db_schema_cache');
                loadSchema();
            }
        } catch(e) {
             btn.disabled = false;
             btn.classList.remove('opacity-50');
             console.error("Query Error:", e);
             
             // If we have a response object but json failed, try to get text
             if (e.responseBody) {
                  resultsArea.innerHTML = `<div class="text-red-400 p-4 border border-red-500/20 bg-red-500/10 rounded">
                    <strong>Error:</strong> ${escapeHtml(e.responseBody)}
                  </div>`;
             } else {
                 resultsArea.innerHTML = `<div class="text-red-400">Network Error: ${escapeHtml(e.message)}</div>`;
             }
             
             // Track errors for AI suggestion
             consecutiveErrors++;
             if (consecutiveErrors >= 2) {
                 aiToast.classList.add('show');
             }
        }
    }

    function renderTable(cols, rows, extraHtml = '') {
        if (rows.length === 0) {
             document.getElementById('results-area').innerHTML = extraHtml + '<div class="text-slate-500 italic mt-4">Empty Result Set</div>';
             return;
        }
        let html = extraHtml + '<div class="overflow-x-auto -mx-4 md:mx-0"><table class="w-full text-left text-xs whitespace-nowrap border-collapse min-w-full">';
        html += '<thead><tr class="bg-white/5 text-slate-300 sticky top-0">';
        cols.forEach(c => html += `<th class="px-4 py-2 font-medium border-b border-white/10">${escapeHtml(c)}</th>`);
        html += '</tr></thead><tbody>';
        rows.forEach(r => {
            html += '<tr class="hover:bg-white/5 border-b border-white/5 last:border-0">';
            cols.forEach(c => html += `<td class="px-4 py-1.5 text-slate-400 font-mono">${escapeHtml(r[c])}</td>`);
            html += '</tr>';
        });
        html += '</tbody></table></div>';
        document.getElementById('results-area').innerHTML = html;
    }

    document.getElementById('btn-run').addEventListener('click', runQuery);
    editor.addKeyMap({ "Ctrl-Enter": runQuery });
    document.getElementById('btn-reset-db').addEventListener('click', async () => {
        if(confirm("⚠️ WARNING: This will permanently delete ALL your tables and data. Continue?")) {
            const resultsArea = document.getElementById('results-area');
            resultsArea.innerHTML = '<div class="text-center mt-10 text-yellow-500 text-xs">Resetting database...</div>';
            
            try {
                const res = await fetch('api/reset_db.php', { 
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'}
                });
                const data = await res.json();
                
                if (data.error) {
                    resultsArea.innerHTML = `<div class="text-red-400 text-xs p-4 border border-red-500/20 bg-red-500/10 rounded">${escapeHtml(data.error)}</div>`;
                } else {
                    resultsArea.innerHTML = `<div class="text-emerald-400 text-xs p-4 border border-emerald-500/20 bg-emerald-500/10 rounded">✅ ${escapeHtml(data.message)}</div>`;
                    
                    // Clear cache and reload schema
                    localStorage.removeItem('db_schema_cache');
                    loadSchema();
                }
            } catch(e) {
                console.error("Reset DB Error:", e);
                resultsArea.innerHTML = `<div class="text-red-400 text-xs p-4">Error: ${escapeHtml(e.message)}</div>`;
            }
        }
    });

    // ---------------------------------------------
    // PRODUCTIVITY FEATURES
    // ---------------------------------------------
    
    // 1. FORMATTER
    document.getElementById('btn-format').addEventListener('click', () => {
        try {
            const raw = editor.getValue();
            // Use sql-formatter library if available, else basic fallback
            if (window.sqlFormatter) {
                const formatted = sqlFormatter.format(raw, { language: 'mysql' });
                editor.setValue(formatted);
            } else {
                // Formatting fallback
                console.warn("SQL Formatter lib not loaded");
            }
        } catch(e) { console.error(e); }
    });

    // 1.5 AI FIX
    let consecutiveErrors = 0;
    const btnAiFix = document.getElementById('btn-ai-fix');
    const aiOverlay = document.getElementById('ai-loading');
    const aiToast = document.getElementById('ai-toast');
    const btnToastYes = document.getElementById('toast-yes');
    const btnToastNo = document.getElementById('toast-no');

    btnAiFix.addEventListener('click', async () => {
        const sql = editor.getValue();
        if (!sql.trim()) return;

        // UI State: Loading
        aiOverlay.classList.add('active');
        btnAiFix.disabled = true;
        
        // Hide Toast if open
        aiToast.classList.remove('show');

        // Get last error if exists
        const errorMsg = document.querySelector('#results-area .text-red-400')?.innerText || '';

        try {
            const res = await fetch('api/ai_fix.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ sql, error: errorMsg })
            });
            const data = await res.json();

            if (res.status === 429) {
                alert("⏳ " + data.error); // Daily limit reached
            } else if (data.sql) {
                editor.setValue(data.sql);
                // Reset errors
                consecutiveErrors = 0;
            } else if (data.error) {
                let msg = "AI Error: " + data.error;
                if (data.details) {
                    try {
                        const detailObj = JSON.parse(data.details);
                        if (detailObj.error && detailObj.error.message) {
                            msg += "\nDetails: " + detailObj.error.message;
                        } else {
                            msg += "\nDetails: " + data.details;
                        }
                    } catch(e) {
                         msg += "\nDetails: " + data.details;
                    }
                }
                alert(msg);
            }
        } catch (e) {
            console.error(e);
            alert("Failed to connect to AI service.");
        } finally {
            aiOverlay.classList.remove('active');
            btnAiFix.disabled = false;
        }
    });

    // Toast Logic
    btnToastYes.addEventListener('click', () => {
        btnAiFix.click();
    });
    btnToastNo.addEventListener('click', () => {
        aiToast.classList.remove('show');
        consecutiveErrors = 0; // Reset to avoid nagging immediately
    });

    // 2. EXPORT TOOLS
    let lastResultData = null; // Store last result for export

    function setupExport(data) {
        lastResultData = data;
        const btnCsv = document.getElementById('btn-export-csv');
        const btnJson = document.getElementById('btn-export-json');
        
        if (data && data.length > 0) {
            btnCsv.classList.remove('hidden');
            btnJson.classList.remove('hidden');
        } else {
            btnCsv.classList.add('hidden');
            btnJson.classList.add('hidden');
        }
    }

    document.getElementById('btn-export-csv').addEventListener('click', () => {
        if (!lastResultData) return;
        const keys = Object.keys(lastResultData[0]);
        const csv = [
            keys.join(','),
            ...lastResultData.map(row => keys.map(k => `"${String(row[k]).replace(/"/g, '""')}"`).join(','))
        ].join('\n');
        downloadFile(csv, 'export.csv', 'text/csv');
    });

    document.getElementById('btn-export-json').addEventListener('click', () => {
        if (!lastResultData) return;
        downloadFile(JSON.stringify(lastResultData, null, 2), 'export.json', 'application/json');
    });

    function downloadFile(content, fileName, mimeType) {
        const a = document.createElement('a');
        const blob = new Blob([content], { type: mimeType });
        a.href = URL.createObjectURL(blob);
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // 3. HISTORY
    const historyModal = document.getElementById('history-modal');
    const historyList = document.getElementById('history-list');

    document.getElementById('btn-history').addEventListener('click', async () => {
        historyModal.classList.remove('hidden');
        
        try {
            const res = await fetch('api/get_history.php');
            const data = await res.json();
            
            if (data.history && data.history.length > 0) {
                 historyList.innerHTML = data.history.map(item => `
                    <div class="bg-black/20 p-3 rounded border border-white/5 hover:bg-white/5 transition group cursor-pointer" onclick="loadHistoryItem(this)">
                        <div class="flex justify-between text-xs text-slate-500 mb-1">
                            <span>${item.created_at}</span>
                            <span class="${item.status === 'success' ? 'text-green-400' : 'text-red-400'}">${item.status}</span>
                        </div>
                        <div class="font-mono text-sm text-slate-300 truncate">${escapeHtml(item.sql_text)}</div>
                        <div class="hidden">${escapeHtml(item.sql_text)}</div> 
                    </div>
                 `).join('');
            } else {
                historyList.innerHTML = '<div class="text-center text-slate-500 py-10">No history found</div>';
            }
        } catch (e) {
            historyList.innerHTML = '<div class="text-center text-red-400 py-10">Failed to load history</div>';
        }
    });

    document.getElementById('close-history').addEventListener('click', () => {
        historyModal.classList.add('hidden');
    });
    
    // Global function to receive click from history items
    window.loadHistoryItem = function(el) {
        // SQL is hidden in the 3rd child div
        const sql = el.children[2].innerText; 
        editor.setValue(sql);
        historyModal.classList.add('hidden');
    };

    // Integrate with runQuery to enable export buttons
    const originalRunQuery = runQuery; // We are essentially modifying the existing runQuery logic via hook if we could, but here we just need to ensure renderTable also calls setupExport
    
    // We need to inject setupExport into the existing renderTable or runQuery
    // Since we can't easily hook, let's redefine renderTable
    const oldRenderTable = renderTable;
    renderTable = function(cols, rows) {
        setupExport(rows);
        oldRenderTable(cols, rows);
    };

</script>


<?php require_once 'includes/footer.php'; ?>
