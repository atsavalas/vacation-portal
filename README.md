# Vacation Portal

A lightweight, framework-inspired PHP application for managing vacation requests. It was designed with clean architecture, reusability, and security in mind.

For the complete architecture and design info, please see the [ARCHITECTURE.md](./ARCHITECTURE.md) file.

---

## Installation

#### 1️. Clone the repository
```bash
git clone https://github.com/atsavalas/vacation-portal.git
```

#### 2. Create your environment file
```bash
cd vacation-portal
cp .env.example .env
```
The `.env` file contains database connection details. For Docker, the defaults should already work

#### 3. Build and start the containers
```bash
docker-compose up --build
```
#### 4. Run migrations and seed demo data
```bash
docker exec -it vacation_portal_app php database/migrate.php
```

This will create all required tables and seed demo users and vacation requests.

#### 5. (Optional) Run the test suite
```bash
docker exec -it vacation_portal_app ./vendor/bin/phpunit tests/Helpers/AuthTest.php  
docker exec -it vacation_portal_app ./vendor/bin/phpunit tests/Models/UserTest.php  
docker exec -it vacation_portal_app ./vendor/bin/phpunit tests/Models/RequestTest.php
```

#### 6. Access the application
Open your browser and visit: </br>
[http://localhost:8000](http://localhost:8000)

## Demo Credentials

You can log in using one of the following demo users:

**Manager**

Username: alice </br>
Password: password

**Employee**

Username: alexandros </br>
Password: password
