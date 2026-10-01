# ETMS — Employee Task Management System

A simple **Employee Task Management System (ETMS)** built with **PHP, MySQL, HTML, and CSS**.

The system allows administrators to manage employees and tasks, while employees can log in and view their assigned tasks.

---

## 📌 Project Overview

**ETMS** is a web-based task management system designed to help an organization manage employees and their assigned tasks.

There are two main types of users:

* **Admin** — manages employees and tasks.
* **Employee** — views and manages their assigned tasks.

---

## ✨ Features

### 👨‍💼 Admin

* Admin login
* Admin dashboard
* View employees
* Add employees
* Edit employees
* Delete employees
* View tasks
* Create tasks
* Edit tasks
* Delete tasks
* Assign tasks to employees
* View task status and priority

### 👨‍💻 Employee

* Employee login
* Employee dashboard
* View assigned tasks
* View task details
* View task status
* View profile
* Logout

---

## 🛠️ Technologies

| Technology | Purpose                           |
| ---------- | --------------------------------- |
| PHP        | Backend / Server-side programming |
| MySQL      | Database                          |
| HTML5      | Page structure                    |
| CSS3       | User interface                    |
| XAMPP      | Local development server          |
| Apache     | Web server                        |
| phpMyAdmin | Database management               |
| Git        | Version control                   |

---

## 🗄️ Database

The project uses a MySQL database named:

```text
ETMS
```

Main tables include:

```text
users
tasks
```

### Users

The `users` table stores information about system users.

Example roles:

```text
admin
employee
```

### Tasks

The `tasks` table stores employee tasks, including:

* Task title
* Description
* Assigned employee
* Start date
* Due date
* Status
* Priority
* Created date

---

## 🔐 Demo Login Accounts

### Admin

```text
Username: admin
Password: 12345
Role: admin
```

### Employee

```text
Username: john
Password: 12345
Role: employee
```

> ⚠️ These credentials are for local development/demo purposes only. Do not use simple passwords like these in a production system.

---

## 📁 Project Structure

```text
ETMS/
│
├── admin/
│   ├── dashboard.php
│   │
│   ├── employees/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── store.php
│   │   ├── edit.php
│   │   ├── update.php
│   │   └── delete.php
│   │
│   └── tasks/
│       ├── index.php
│       ├── create.php
│       ├── store.php
│       ├── edit.php
│       ├── update.php
│       └── delete.php
│
├── employee/
│   ├── dashboard.php
│   ├── tasks.php
│   └── profile.php
│
├── config/
│   └── database.php
│
├── includes/
│   ├── admin_auth.php
│   ├── employee_auth.php
│   ├── sidebar.php
│   └── ...
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── images/
│   │   └── ...
│   │
│   └── js/
│       └── ...
│
├── database.sql
│
├── index.php
│
└── README.md
```

> The exact structure may change as the project is developed.

---

## ⚙️ Requirements

Before running the project, install:

* XAMPP
* PHP
* MySQL
* Web browser
* Git (optional)

---

## 🚀 Installation

### 1. Clone or copy the project

Place the project inside the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\ETMS
```

---

### 2. Start XAMPP

Open XAMPP Control Panel and start:

```text
Apache
MySQL
```

---

### 3. Create the database

Open:

```text
http://localhost/phpmyadmin
```

Create a database:

```text
ETMS
```

---

### 4. Import the database

Import:

```text
database.sql
```

into the `ETMS` database.

---

### 5. Configure the database connection

Open:

```text
config/database.php
```

Example:

```php
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "ETMS"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
```

Adjust the username/password if your MySQL configuration is different.

---

## 🌐 Run the Project

Open your browser and visit:

```text
http://localhost/ETMS/
```

---

## 🔑 Login

### Admin

```text
Username: admin
Password: 12345
```

Admin can access:

```text
Admin Dashboard
Employees
Tasks
```

### Employee

```text
Username: john
Password: 12345
```

Employee can access:

```text
Employee Dashboard
My Tasks
My Profile
```

---

## 🔒 Authentication

The system uses PHP sessions to keep track of logged-in users.

Example:

```php
session_start();

$_SESSION["user_id"] = $user["id"];
$_SESSION["role"] = $user["role"];
```

Admin pages verify that the logged-in user has the `admin` role.

Employee pages verify that the logged-in user has the `employee` role.

---

## 📊 Task Status

Tasks can have different statuses:

```text
incomplete
progressing
completed
```

Example workflow:

```text
Incomplete
    ↓
Progressing
    ↓
Completed
```

---

## 🎯 Project Goals

The main goals of ETMS are:

* Practice PHP development
* Practice MySQL database design
* Practice CRUD operations
* Practice PHP sessions and authentication
* Practice SQL queries
* Practice HTML/CSS UI development
* Understand relationships between users and tasks
* Build a complete PHP/MySQL web application

---

## 📚 Learning Topics

This project demonstrates:

```text
PHP
├── Variables
├── Functions
├── Forms
├── POST / GET
├── Sessions
├── Authentication
├── include / require_once
├── MySQLi
├── CRUD
└── Password hashing

MySQL
├── Database
├── Tables
├── Primary Keys
├── Foreign Keys
├── INSERT
├── SELECT
├── UPDATE
├── DELETE
├── JOIN
└── Constraints

HTML / CSS
├── Forms
├── Tables
├── Navigation
├── Dashboard
├── Responsive layout
└── UI components
```

---

## 👨‍💻 Author

**RielCode**

Employee Task Management System — ETMS

---

## 📄 License

This project is created for **learning and educational purposes**.
