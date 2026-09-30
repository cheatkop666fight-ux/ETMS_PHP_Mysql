CREATE DATABASE IF NOT EXISTS ETMS;
USE ETMS; 

-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
-- USER
-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(100) NOT NULL, 
    email VARCHAR(150)  NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL, 
    role ENUM('admin', 'employee') NOT NULL DEFAULT 'employee',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE  CURRENT_TIMESTAMP
);

-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
-- TASK
-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

CREATE TABLE tasks (
    taskId INT AUTO_INCREMENT PRIMARY KEY, 
    title VARCHAR(150) NOT NULL, 
    description VARCHAR(4000),
    assignedTo INT NOT NULL, 
    startDate DATETIME NOT NULL, 
    dueDate DATETIME NOT NULL, 
    status ENUM('incomplete', 'progressing', 'completed') NOT NULL DEFAULT 'incomplete',
    priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium', 
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updateAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_task_user
        FOREIGN KEY (assignedTo) REFERENCES users(id) ON DELETE CASCADE 
);

-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
-- ATTENDANCE
-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
CREATE TABLE attendances (
    attenId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    workDate DATE NOT NULL,
    checkIn DATETIME NOT NULL,
    checkOut DATETIME NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_user
        FOREIGN KEY (userId)
        REFERENCES users(id)
        ON DELETE CASCADE,

    UNIQUE KEY uniqueUserDate (userId, workDate)
);

INSERT INTO users (name, email, username, password, role)
VALUE (
    'System Administrator',
    'admin@gmail.com', 
    'Admin',
    '12345',
    'admin'
);

-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
-- CREATE FIRST EMPLOYEE
-- -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
INSERT INTO users (name, email, username, password, role)
VALUE (
    'yab mes',
    'yebmes@gmail.com', 
    'yebmes',
    'e12345',
    'employee'
);
SELECT * FROM users; 