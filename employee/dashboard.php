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

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Execute failed: " .
        mysqli_stmt_error($stmt)
    );
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
        content="width=device-width, initial-scale=1.0"
    >

    <title>Employee Dashboard</title>

</head>

<body>

    <h1>Employee Dashboard</h1>


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


    <nav>

        <a href="dashboard.php">
            Dashboard
        </a>

        |

        <a href="tasks/index.php">
            My Tasks
        </a>

        |

        <a href="profile.php">
            My Profile
        </a>

        |

        <a href="../login/logout.php">
            Logout
        </a>

    </nav>


    <hr>


    <h2>My Task Summary</h2>


    <p>
        Total Tasks:
        <strong>
            <?php
            echo $statistics["totalTasks"] ?? 0;
            ?>
        </strong>
    </p>


    <p>
        Incomplete:
        <strong>
            <?php
            echo $statistics["incompleteTasks"] ?? 0;
            ?>
        </strong>
    </p>


    <p>
        Progressing:
        <strong>
            <?php
            echo $statistics["progressingTasks"] ?? 0;
            ?>
        </strong>
    </p>


    <p>
        Completed:
        <strong>
            <?php
            echo $statistics["completedTasks"] ?? 0;
            ?>
        </strong>
    </p>


    <p>

        <a href="tasks/index.php">
            View My Tasks
        </a>

    </p>

</body>

</html>