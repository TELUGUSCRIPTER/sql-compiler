    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="text-center text-gray-400 text-sm">
                <p>&copy; <?= date('Y') ?> SQL Compiler. Admin Panel. 
                <span class="hidden md:inline-block ml-4 text-xs">
                    💡 Shortcuts: <kbd class="px-1 bg-gray-800 rounded">G+D</kbd> Dashboard 
                    <kbd class="px-1 bg-gray-800 rounded">G+U</kbd> Users 
                    <kbd class="px-1 bg-gray-800 rounded">G+L</kbd> Logs 
                    <kbd class="px-1 bg-gray-800 rounded">G+S</kbd> Security
                </span>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Keyboard Shortcuts -->
    <script>
        let waitingForSecondKey = false;
        let firstKey = '';
        
        document.addEventListener('keydown', (e) => {
            // Ignore if typing in input/textarea
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                return;
            }
            
            const key = e.key.toLowerCase();
            
            // Handle 'g' as first key
            if (key === 'g' && !waitingForSecondKey) {
                waitingForSecondKey = true;
                firstKey = 'g';
                
                // Show visual feedback
                showShortcutHint('Press D, U, L, or S...');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    waitingForSecondKey = false;
                    firstKey = '';
                    hideShortcutHint();
                }, 2000);
                
                e.preventDefault();
                return;
            }
            
            // Handle second key after 'g'
            if (waitingForSecondKey && firstKey === 'g') {
                waitingForSecondKey = false;
                firstKey = '';
                hideShortcutHint();
                
                switch(key) {
                    case 'd':
                        window.location.href = 'dashboard.php';
                        break;
                    case 'u':
                        window.location.href = 'users.php';
                        break;
                    case 'l':
                        window.location.href = 'logs.php';
                        break;
                    case 's':
                        window.location.href = 'security.php';
                        break;
                }
                e.preventDefault();
            }
            
            // Focus search box with '/'
            if (key === '/' && !waitingForSecondKey) {
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                    e.preventDefault();
                }
            }
            
            // Clear filters with Escape
            if (key === 'escape' && !waitingForSecondKey) {
                const clearBtn = document.querySelector('a[href*=".php"]:not([href*="?"])');
                if (clearBtn && window.location.search) {
                    window.location.href = clearBtn.href;
                }
            }
        });
        
        function showShortcutHint(text) {
            let hint = document.getElementById('shortcut-hint');
            if (!hint) {
                hint = document.createElement('div');
                hint.id = 'shortcut-hint';
                hint.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50';
                document.body.appendChild(hint);
            }
            hint.textContent = text;
            hint.style.display = 'block';
        }
        
        function hideShortcutHint() {
            const hint = document.getElementById('shortcut-hint');
            if (hint) {
                hint.style.display = 'none';
            }
        }
    </script>
</body>
</html>
