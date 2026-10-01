<?php

session_start();

require_once "./config/database.php";


/*
|--------------------------------------------------------------------------
| Check MySQL connection
|--------------------------------------------------------------------------
*/

$sql = "SELECT DATABASE() AS database_name";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " .
        mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);


/*
|--------------------------------------------------------------------------
| Redirect logged-in users
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {

    if ($_SESSION["role"] === "admin") {

        header("Location: admin/index.php");
        exit;
    }

    if ($_SESSION["role"] === "employee") {

        header("Location: employee/index.php");
        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        ETMS // Employee Task Management System
    </title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">


</head>

<body>

    <div class="app">

        <div class="main">

            <main class="content">


                <!-- =========================================================
             HERO
        ========================================================== -->

                <div class="page-header">

                    <div>

                        <p class="detail-label">
                            ETMS // SYSTEM INTRODUCTION
                        </p>

                        <h1>
                            Employee Task
                            Management System
                        </h1>

                        <p>
                            A web-based platform for managing employees,
                            tasks, and attendance in one centralized system.
                        </p>

                    </div>

                </div>


                <!-- =========================================================
             ABOUT
        ========================================================== -->

                <section>

                    <div class="page-header">

                        <div>

                            <h2>
                                About ETMS
                            </h2>

                            <p>
                                EMPLOYEE TASK MANAGEMENT // CENTRALIZED WORKFLOW
                            </p>

                        </div>

                    </div>


                    <div class="detail-grid">


                        <div class="detail-item">

                            <div class="detail-label">
                                PURPOSE
                            </div>

                            <div class="detail-value">

                                ETMS helps organizations manage
                                employee tasks and attendance
                                through a centralized web application.

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                TASK MANAGEMENT
                            </div>

                            <div class="detail-value">

                                Administrators can create,
                                assign, monitor and manage
                                employee tasks.

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                ATTENDANCE
                            </div>

                            <div class="detail-value">

                                Employees can check in,
                                check out and review
                                their attendance history.

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                ACCESS CONTROL
                            </div>

                            <div class="detail-value">

                                Separate Admin and Employee
                                roles provide controlled
                                access to system features.

                            </div>

                        </div>


                    </div>

                </section>


                <!-- =========================================================
             BENEFITS
        ========================================================== -->

                <section>

                    <div class="page-header">

                        <div>

                            <h2>
                                System Benefits
                            </h2>

                            <p>
                                WHY USE ETMS?
                            </p>

                        </div>

                    </div>


                    <div class="detail-grid">


                        <div class="detail-item">

                            <div class="detail-label">
                                01 // CENTRALIZED
                            </div>

                            <div class="detail-value">

                                Keep employee,
                                task and attendance
                                information in one system.

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                02 // ORGANIZED
                            </div>

                            <div class="detail-value">

                                Assign tasks with
                                deadlines, priorities
                                and progress status.

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                03 // TRACKABLE
                            </div>

                            <div class="detail-value">

                                Monitor task progress
                                and employee attendance
                                more easily.

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                04 // SECURE
                            </div>

                            <div class="detail-value">

                                Authentication,
                                role-based access,
                                password hashing and
                                CSRF protection.

                            </div>

                        </div>


                    </div>

                </section>


                <!-- =========================================================
             TECHNOLOGY
        ========================================================== -->

                <section>

                    <div class="page-header">

                        <div>

                            <h2>
                                Technology Stack
                            </h2>

                            <p>
                                BUILT WITH
                            </p>

                        </div>

                    </div>


                    <div class="detail-grid">


                        <div class="detail-item">

                            <div class="detail-label">
                                BACKEND
                            </div>

                            <div class="detail-value">
                                PHP
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                DATABASE
                            </div>

                            <div class="detail-value">
                                MySQL
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                SERVER
                            </div>

                            <div class="detail-value">
                                XAMPP / Apache
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                FRONTEND
                            </div>

                            <div class="detail-value">
                                HTML5 + CSS3
                            </div>

                        </div>


                    </div>

                </section>


                <!-- =========================================================
             SYSTEM STATUS
        ========================================================== -->

                <section>

                    <div class="page-header">

                        <div>

                            <h2>
                                System Status
                            </h2>

                            <p>
                                CURRENT ENVIRONMENT
                            </p>

                        </div>

                    </div>


                    <div class="detail-grid">


                        <div class="detail-item">

                            <div class="detail-label">
                                SYSTEM
                            </div>

                            <div class="detail-value">
                                ETMS
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                DATABASE
                            </div>

                            <div class="detail-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["database_name"]
                                );
                                ?>

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                PHP
                            </div>

                            <div class="detail-value">
                                ONLINE
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                MYSQL
                            </div>

                            <div class="detail-value">
                                CONNECTED
                            </div>

                        </div>


                    </div>

                </section>


                <!-- =========================================================
             LOGIN
        ========================================================== -->

                <section>

                    <div class="page-header">

                        <div>

                            <h2>
                                Access ETMS
                            </h2>

                            <p>
                                AUTHENTICATED USERS ONLY
                            </p>

                        </div>

                    </div>


                    <div class="actions">

                        <a
                            href="auth/login.php"
                            class="btn btn-primary">

                            LOGIN TO ETMS

                        </a>

                    </div>


                </section>


                <!-- =========================================================
             FOOTER
        ========================================================== -->

                <footer>

                    <p>
                        ETMS // EMPLOYEE TASK MANAGEMENT SYSTEM
                    </p>

                    <p>
                        PHP + MYSQL // TASK + ATTENDANCE MANAGEMENT
                    </p>

                </footer>


            </main>

        </div>

    </div>

</body>

</html>