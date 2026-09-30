<?php

require_once "../includes/employee_auth.php";
require_once "../config/database.php";


$userId = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| Count employee's tasks
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        COUNT(*) AS totalTasks,
        SUM(status = 'incomplete') AS incompleteTasks,
        SUM(status = 'progressing') AS progressingTasks,
        SUM(status = 'completed') AS completedTasks
    FROM tasks
    WHERE assignedTo = ?"
);


if (!$stmt) {

    die("Prepare failed: " .
        mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
}


$result = mysqli_stmt_get_result($stmt);

$statistics = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);

?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>ETMS // Employee Dashboard</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body>

    <div class="app">


        <?php
        require_once "../includes/employee_sidebar.php";
        ?>


        <div class="main">


            <?php
            require_once "../includes/topbar.php";
            ?>


            <main class="content">


                <div class="page-header">

                    <div>

                        <h1>
                            Employee Dashboard
                        </h1>

                        <p>
                            EMPLOYEE PORTAL // TASK OVERVIEW
                        </p>

                    </div>


                    <a
                        class="btn btn-primary"
                        href="tasks/index.php">
                        View My Tasks
                    </a>

                </div>


                <div class="card">

                    <div class="card-title">
                        Current User
                    </div>

                    <p>

                        Welcome,

                        <strong>

                            <?php
                            echo htmlspecialchars(
                                $_SESSION["name"]
                            );
                            ?>

                        </strong>

                    </p>

                </div>


                <br>


                <div class="page-header">

                    <div>

                        <h1>
                            My Task Summary
                        </h1>

                        <p>
                            CURRENT TASK STATISTICS
                        </p>

                    </div>

                </div>


                <div class="cards">


                    <div class="card">

                        <div class="card-title">
                            Total Tasks
                        </div>

                        <div class="card-value">

                            <?php
                            echo $statistics["totalTasks"] ?? 0;
                            ?>

                        </div>

                    </div>


                    <div class="card">

                        <div class="card-title">
                            Incomplete
                        </div>

                        <div class="card-value">

                            <?php
                            echo $statistics["incompleteTasks"] ?? 0;
                            ?>

                        </div>

                    </div>


                    <div class="card">

                        <div class="card-title">
                            Progressing
                        </div>

                        <div class="card-value">

                            <?php
                            echo $statistics["progressingTasks"] ?? 0;
                            ?>

                        </div>

                    </div>


                    <div class="card">

                        <div class="card-title">
                            Completed
                        </div>

                        <div class="card-value">

                            <?php
                            echo $statistics["completedTasks"] ?? 0;
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
                            href="tasks/index.php">
                            My Tasks
                        </a>


                        <a
                            class="btn"
                            href="profile.php">
                            My Profile
                        </a>


                        <a
                            class="btn btn-danger"
                            href="../auth/logout.php">
                            Logout
                        </a>

                    </div>

                </div>


            </main>

        </div>

    </div>

</body>

</html>