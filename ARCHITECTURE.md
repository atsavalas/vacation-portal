# Architecture Overview

## Introduction

The **Vacation Portal** is a lightweight, framework-free PHP MVC web application built to demonstrate clean architectural 
principles and Laravel-style conventions without relying on full frameworks. 

It uses **Bramus Router**, **Twig**, and **Medoo** as its core dependencies, combined with small abstractions for routing, 
rendering, database access, and authentication.

The design philosophy focuses on **clarity, reusability, and security**:
- Framework-like structure for maintainability.
- Minimal dependencies.
- Consistent naming and autoloading (PSR-4).
- Clean separation between layers: Routing → Controller → Model → View.

This architecture intentionally mirrors Laravel’s logic but stays light and compliant with the assignment’s *“no framework”* 
restriction.

While static analysis tools (like PHPStan or PHP_CodeSniffer) were considered, the focus of this implementation was on 
building a clean, reusable microframework-like architecture with unit tests, security best practices, and maintainable 
code structure.

---

## Project Structure

```
vacation-portal/
├── app/
│   ├── Controllers/
│   ├── Database/
│   ├── Helpers/
│   ├── Middleware/
│   ├── Models/
│   └── routes.php
├── bootstrap/
│   ├── app.php
│   └── helpers.php
├── public/
│   ├── css/
│   ├── index.php
│   └── .htaccess
├── database/
│   ├── migrations/
│   ├── seeds/
│   └── migrate.php
├── tests/
│   ├── Models/
│   ├── Helpers/
│   └── Controllers/
└── views/
```

---

## App Bootstrapping

### Why Separate `public/index.php` and `bootstrap/app.php`

The application separates **`public/index.php`** (front controller) from **`bootstrap/app.php`** to mirror modern 
frameworks like Laravel and improve clarity between execution and initialization.  

- **`bootstrap/app.php`** handles initialization: environment variables, helpers, dependencies, and routes.  
- **`public/index.php`** handles execution: it loads the bootstrap file and runs the router.  

This structure improves maintainability, keeps the entry point minimal, and allows future extension (e.g. dependency 
injection, environment-specific setups).

---

## Routing

The project uses **Bramus Router** for lightweight and expressive routing.

```php
$router->get('/login', route(AuthController::class, 'login'));
```

Because the callable syntax (`[Class::class, 'method']`) caused type resolution issues, a custom **`route()` helper** 
was introduced to preserve Laravel-like readability.

---

## Database Layer

### Database Connection Class

The app uses a lightweight **`DB` class** (`App\Database\DB`) that manages a singleton Medoo instance.  

It centralizes connection logic, reads credentials from environment variables, and provides a clean, reusable database 
interface for models.

By isolating the connection in one class, the design avoids repetitive setup, simplifies testing, and maintains a clear 
separation between data access and business logic.

---

## Models and Data Abstraction

All models extend an abstract **`BaseModel`**, which defines reusable CRUD methods.

This promotes consistency and reusability across the data layer while maintaining minimal coupling.

Furthermore, this design provides ORM-like convenience without a heavy framework.

---

## Migrations and Seeds

Database initialization is handled via simple, reproducible SQL migrations and seed files under `/database`.

A small PHP runner (`database/migrate.php`) executes them sequentially for quick setup:

```bash
php database/migrate.php
```

Seed files populate users and requests with test data for demonstration and unit testing.

**The password is the same for all seeded users: "password".**

---

## View Layer

### Templating Engine – Twig

The application uses **Twig** for its templating engine to achieve a clean separation between presentation and logic.

Twig offers a Blade-like syntax but remains framework-independent with the following key benefits:
- Template inheritance and block structure.  
- Built-in output escaping for XSS protection.  
- Lightweight and fast to render (deliberately skipped caching for this assignment task).

The frontend template library is Bootstrap 5.

---

### Environment Variables in Views

Environment variables like `APP_NAME` and `APP_ENV` are exposed globally in Twig through the `View` class using `addGlobal()`.

This avoids passing configuration data through every controller, keeping the templates framework-like and clean.

---

### View Rendering and Layouts

All views extend a base layout (`layout.twig`) which provides shared structure (header, navigation, flash messages, 
footer).

Specific pages such as login, user/request dashboards, and form pages extend this base file for consistent styling. 

Views are organized by domain and also a macro has been created for reusability in front-end form validation.

---

## Authentication Flow

- Login and logout are handled by `AuthController`.  
- The `Auth` helper manages sessions and stores minimal user info (ID, name, role, email).  
- Passwords are hashed with `password_hash()` and verified via `password_verify()`.  
- On login success, sessions are regenerated.  
- Logout clears and destroys the session.

---

## Helper Functions

Global helper functions under `/bootstrap/helpers.php` provide small, framework-like utilities.

They are grouped logically by purpose (routing, views, input, debugging) for clarity and maintainability.

---

## Testing

Unit tests are implemented using **PHPUnit** to validate core business logic and model operations.

Each test bootstraps the application environment and runs in isolation:
```bash
vendor/bin/phpunit
```

Tests are stored in `/tests` and use a simple structure mirroring the app’s folders.
