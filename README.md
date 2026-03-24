<div align="center">
  <h1>🚀 SQL Compiler & Learning Platform</h1>
  <p>A web-based SQL compiler and learning platform with AI-assisted query checking, built in PHP and MariaDB/MySQL.</p>
</div>

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Gemini AI](https://img.shields.io/badge/Gemini_AI-1A73E8?style=for-the-badge&logo=google&logoColor=white)

## 📌 Overview

**SQL Compiler** is a comprehensive educational platform that bridges the gap between learning SQL syntax and practical execution. Users can browse highly detailed tutorials, practice queries, test logic, and receive proactive feedback on errors with a state-of-the-art AI integration. 

## 🌟 Key Features

- **Live SQL Editor:** Execute queries in real-time straight from your web browser without spinning up local database GUI tools.
- **✨ AI Query Fixer:** Powered by the **Gemini 2.5 Flash** API, our platform automatically catches syntax errors, offers corrections, and fixes queries for invalid SQL operations.
- **Interactive Learning Hub:** Extensive, pre-built SQL tutorials ranging from beginner selections to advanced grouping and sub-queries. 
- **User Dashboard & History:** Users can track their previous queries and monitor history for learning and reference.
- **Secure Authentication:** Account creation backed by secure CSRF middleware, rate limiting, hashing, and SMTP token-based password resets.
- **Admin Control Panel:** Administrator dashboard with live metrics, user management, and query execution review.
- **Sanitized Databases:** Queries are sandboxed to individual sessions ensuring database isolation and total security against malicious table dropping.

## 🛠️ Tech Stack

- **Backend Logic:** Plain PHP
- **Database Architecture:** MySQL / MariaDB (managed via PDO)
- **AI Integration:** Google Generative AI (Gemini Flash API)
- **Email/Auth Services:** Native robust PHP SMTP logic wrapper
- **Frontend / UI Components:** Vanilla HTML/CSS with JavaScript execution layers

## ⚙️ Setup and Installation

### 1. Requirements
Ensure your host server is running:
- **PHP 8.0+** with PDO/MySQL extensions enabled
- **MariaDB** (10.6+) or native **MySQL**

### 2. Configure the Database
1. Import the stripped schema layout onto your local/development database using the included `unmqwlgl_sql.sql` file. 
   *(Note: The schema is fully sanitized and devoid of prior user metrics/data)*.
2. Open `config/db_control.php` and configure the constants to match your server instance:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_CONTROL_NAME', 'your_db_name');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   ```

### 3. Setup the AI Fixer
1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey) and generate a free API key.
2. In the project directory, open `includes/AIHelper.php` and replace the placeholder variable:
   ```php
   $this->apiKey = 'YOUR_GEMINI_API_KEY'; 
   ```

### 4. Setup SMTP Services (Forgot Password)
1. Open `forgot_password.php`.
2. Locate the `SimpleSMTP` initiation block. Replace the configuration variables with your mail server's host, username, and password tokens.

### 5. Launch
You can serve this instantly via PHP's built-in webserver. From the project root, simply run:
```bash
php -S localhost:8000
```
Open your browser to `http://localhost:8000` to begin.

## 📚 Topics (Tags)
```php
#php-application #sql-compiler #gemini-api #educational-tools #database-management #mysql #mariadb
```
