<div align="center">
  <h1>🚀 SQL Compiler & Learning Platform</h1>
  <p>A beautifully crafted, web-based SQL compiler and interactive learning platform featuring AI-assisted query resolution.</p>
  
  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![Gemini AI](https://img.shields.io/badge/Gemini_AI-1A73E8?style=for-the-badge&logo=google&logoColor=white)](https://ai.google.dev/)
</div>

<br>

---

## 📌 Overview

**SQL Compiler** bridges the gap between theoretical SQL learning and practical execution. Dive into extensive tutorials, practice queries in real-time within your browser, and get instant, proactive feedback on syntax errors powered by state-of-the-art AI integration.

---

## 🌟 Key Features

> ### ⚡ Live SQL Editor
> Execute queries in real-time straight from your web browser without the hassle of spinning up local database GUI tools.
>
> ### ✨ AI Query Fixer (Powered by Gemini)
> Never get stuck on a syntax error again! The integrated **Gemini 2.5 Flash** API automatically catches mistakes, offers deep insights, and reconstructs invalid SQL operations.
>
> ### 📚 Interactive Learning Hub
> Features extensive, pre-built SQL tutorials ranging from basic `SELECT` statements to masterful `JOIN` operations and complex sub-queries.
> 
> ### 🔒 Secure Authentication & Sandboxing
> Secure account creation, password resets via SMTP, CSRF protections, and sandboxed database sessions ensure every user query is isolated and perfectly secure.

---

## 🛠️ Tech Stack

- **Backend:** Native PHP 8.0+
- **Database:** MySQL / MariaDB (Managed via PDO)
- **AI Integration:** Google Generative AI (Gemini API)
- **Frontend / UI:** Vanilla HTML/CSS with JavaScript execution layers

---

## ⚙️ Setup and Installation

### 1. Requirements
Ensure your host server is running:
- **PHP 8.0+** with PDO/MySQL extensions enabled
- **MariaDB** (10.6+) or native **MySQL**

### 2. Configure the Database
1. Import the stripped schema layout onto your local/development database using the included `unmqwlgl_sql.sql` file. 
2. Open `config/db_control.php` and configure the constants to match your server instance:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_CONTROL_NAME', 'your_db_name');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   ```

### 3. Setup the AI Fixer & Mail Services
1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey) and generate a free API key.
2. In `includes/AIHelper.php`, replace the placeholder with your key:
   ```php
   $this->apiKey = 'YOUR_GEMINI_API_KEY'; 
   ```
3. Open `forgot_password.php` and configure the `SimpleSMTP` initiation block with your mail server's credentials.

### 4. Launch
Launch via PHP's built-in webserver. From the project root, simply run:
```bash
php -S localhost:8000
```
Open your browser to `http://localhost:8000` to begin.

---

<div align="center">
  <br>
  <p><b>Created with ❤️ by <a href="https://github.com/TELUGUSCRIPTER">TeluguScripter</a></b></p>
</div>
