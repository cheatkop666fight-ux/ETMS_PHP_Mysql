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
    die(
        "Database query failed: " .
        mysqli_error($conn)
    );
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


            <div class="page-header">

                <div>

                    <h1>
                        Employee Task
                        Management System
                    </h1>

                    <p>
                        ETMS // TASK AND ATTENDANCE
                        MANAGEMENT PLATFORM
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


            <div class="actions">

                <a
                    href="auth/login.php"
                    class="btn btn-primary">

                    LOGIN

                </a>

            </div>


        </main>

    </div>

</div>

</body>

</html>
