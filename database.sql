-- =========================================================
-- ETMS
-- Employee Task Management System
-- =========================================================


-- =========================================================
-- 1. CREATE DATABASE
-- =========================================================

CREATE DATABASE IF NOT EXISTS ETMS;

USE ETMS;


-- =========================================================
-- 2. DROP TABLES
-- =========================================================
-- Drop tasks first because it has a foreign key to users.

DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS users;


-- =========================================================
-- 3. CREATE USERS TABLE
-- =========================================================

CREATE TABLE users (

    id INT AUTO_INCREMENT,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    username VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('admin', 'employee')
        NOT NULL DEFAULT 'employee',

    createdAt TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    updatedAt TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id)

);


-- =========================================================
-- 4. CREATE TASKS TABLE
-- =========================================================

CREATE TABLE tasks (

    taskId INT AUTO_INCREMENT,

    title VARCHAR(255) NOT NULL,

    description TEXT,

    assignedTo INT NOT NULL,

    startDate DATE NOT NULL,

    dueDate DATE NOT NULL,

    status ENUM(
        'incomplete',
        'progressing',
        'completed'
    )
    NOT NULL DEFAULT 'incomplete',

    priority ENUM(
        'low',
        'medium',
        'high'
    )
    NOT NULL DEFAULT 'medium',

    createdAt TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    updatedAt TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (taskId),

    CONSTRAINT fk_tasks_user
        FOREIGN KEY (assignedTo)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);


-- =========================================================
-- 5. INSERT USERS
-- =========================================================

INSERT INTO users
(
    name,
    email,
    username,
    password,
    role
)
VALUES

(
    'Administrator',
    'admin@example.com',
    'admin',
    '12345',
    'admin'
),

(
    'John Employee',
    'john@example.com',
    'john',
    '12345',
    'employee'
);


-- =========================================================
-- 6. INSERT SAMPLE TASKS
-- =========================================================

INSERT INTO tasks
(
    title,
    description,
    assignedTo,
    startDate,
    dueDate,
    status,
    priority
)
VALUES

(
    'Create Login Page',
    'Create the login page for the system.',
    2,
    '2026-10-01',
    '2026-10-03',
    'completed',
    'high'
),

(
    'Create Dashboard',
    'Develop the employee dashboard.',
    2,
    '2026-10-02',
    '2026-10-06',
    'progressing',
    'medium'
),

(
    'Test Employee System',
    'Test the employee task management functions.',
    2,
    '2026-10-05',
    '2026-10-10',
    'incomplete',
    'low'
);


-- =========================================================
-- 7. CHECK USERS
-- =========================================================

SELECT
    id,
    name,
    email,
    username,
    role,
    createdAt,
    updatedAt
FROM users;


-- =========================================================
-- 8. CHECK TASKS
-- =========================================================

SELECT
    t.taskId,
    t.title,
    t.description,
    u.name AS employeeName,
    t.startDate,
    t.dueDate,
    t.status,
    t.priority,
    t.createdAt
FROM tasks t
INNER JOIN users u
    ON t.assignedTo = u.id
ORDER BY t.taskId DESC;

