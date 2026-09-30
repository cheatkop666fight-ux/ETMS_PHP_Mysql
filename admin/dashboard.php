<?php

require_once "../includes/admin_auth.php";
require_once "../config/database.php";


$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'employee'"
);

$employeeCount = mysqli_fetch_assoc($result);


$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tasks"
);

$taskCount = mysqli_fetch_assoc($result);


$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tasks
     WHERE status = 'progressing'"
);

$progressingCount = mysqli_fetch_assoc($result);


$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tasks
     WHERE status = 'completed'"
);

$completedCount = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ETMS // Admin Dashboard</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css"
    >

</head>


<body>

<div class="app">


    <?php
    require_once "../includes/admin_sidebar.php";
    ?>


    <div class="main">


        <?php
        require_once "../includes/topbar.php";
        ?>


        <main class="content">


            <div class="page-header">

                <div>

                    <h1>
                        Admin Dashboard
                    </h1>

                    <p>
                        SYSTEM OVERVIEW // ADMIN CONTROL
                    </p>

                </div>

            </div>


            <div class="cards">


                <div class="card">

                    <div class="card-title">
                        Employees
                    </div>

                    <div class="card-value">

                        <?php
                        echo $employeeCount["total"] ?? 0;
                        ?>

                    </div>

                </div>


                <div class="card">

                    <div class="card-title">
                        Total Tasks
                    </div>

                    <div class="card-value">

                        <?php
                        echo $taskCount["total"] ?? 0;
                        ?>

                    </div>

                </div>


                <div class="card">

                    <div class="card-title">
                        Progressing
                    </div>

                    <div class="card-value">

                        <?php
                        echo $progressingCount["total"] ?? 0;
                        ?>

                    </div>

                </div>


                <div class="card">

                    <div class="card-title">
                        Completed
                    </div>

                    <div class="card-value">

                        <?php
                        echo $completedCount["total"] ?? 0;
                        ?>

                    </div>

                </div>


            </div>


            <div class="card">

                <div class="card-title">
                    Quick Actions
                </div>


                <div class="actions">

                    <a
                        class="btn btn-primary"
                        href="employees/create.php"
                    >
                        + Add Employee
                    </a>


                    <a
                        class="btn btn-success"
                        href="tasks/create.php"
                    >
                        + Create Task
                    </a>


                    <a
                        class="btn"
                        href="employees/index.php"
                    >
                        Employees
                    </a>


                    <a
                        class="btn"
                        href="tasks/index.php"
                    >
                        Tasks
                    </a>

                </div>

            </div>


        </main>

    </div>

</div>

</body>

</html>

