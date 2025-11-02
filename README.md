# Vacation Portal

A lightweight, framework-inspired PHP application for managing vacation requests. It was designed with clean architecture, reusability, and security in mind.

For the complete architecture and design info, please see the [ARCHITECTURE.md](./ARCHITECTURE.md) file.

---

## Installation

#### 1️. Clone the repository
```bash
git clone https://github.com/atsavalas/vacation-portal.git
```

#### 2. Install PHP dependencies
```bash
cd vacation-portal
composer install
```

#### 3. Create your environment file
```bash
cp .env.example .env
```
Then edit the .env file to match your local MySQL credentials.

Suggested DB specs:
- Name: vacation_portal
- Charset: utf8mb4
- Collation: utf8mb4_unicode_ci

#### 4. Run migrations and seed demo data
```bash
php database/migrate.php

```
#### 5. (Optional) Run the test suite
```bash
vendor/bin/phpunit tests/Helpers/AuthTest.php  
vendor/bin/phpunit tests/Models/UserTest.php  
vendor/bin/phpunit tests/Models/RequestTest.php
```

#### 6. Start the local development server
```bash
php -S localhost:8000 -t public
```

## Demo Credentials

You can log in using one of the following demo users:

**Manager**

Username: alice </br>
Password: password

**Employee**

Username: alexandros </br>
Password: password
