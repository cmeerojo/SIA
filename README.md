## How to Setup the Project

Follow these steps to run the project locally:

1. **Clone the Repository**
   ```bash
   git clone https://github.com/cmeerojo/SIA.git
   cd SIA
2. **Install Dependencies**
Make sure you have all prerequisites installed (e.g., PHP, Composer, Node.js, npm)

    ```bash
    composer install
    npm install && npm run dev

3. Configure Environment File

    ```bash
    Copy code
    cp .env.example .env
    
4. Then, edit .env with your specific settings (database credentials, APP_URL, etc.):

    ```bash
    DB_CONNECTION=pgsql/mysql/sqlite
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=yourdatabasename
    DB_USERNAME=yourusername
    DB_PASSWORD=yourpassword

5.Generate Application Key

    ```bash
    php artisan key:generate
    
6.Run Migrations & Seed Database

    ```bash
    php artisan migrate
    php artisan db:seed

7.Serve the Application

    ```bash
    Copy code
    php artisan serve
    Then visit http://localhost:8000 (or, if you're using Laravel Herd or similar, follow your usual local dev flow).

Requirements
PHP >= 8.x

Composer

Laravel 10+ (or whichever version applies)

MySQL or PostgreSQL

Features
User authentication (login/register)

Role-based access and dashboards (e.g., Admin, User)

Audit logs or activity tracking

Responsive design using Tailwind CSS (or your preferred CSS framework)

Tech Stack
Backend: Laravel (PHP)

Frontend: Blade templates + Tailwind CSS (or your front-end stack)

Database: PostgreSQL or MySQL

Package Management: Composer, NPM

Version Control: Git & GitHub
