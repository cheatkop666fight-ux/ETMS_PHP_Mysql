<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


/*
|--------------------------------------------------------------------------
| 1. Validate task ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("Invalid task ID.");
}


$taskId = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| 2. Prepare SQL
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        t.taskId,
        t.title,
        t.description,
        t.startDate,
        t.dueDate,
        t.status,
        t.priority,
        t.createdAt,
        u.id AS employeeId,
        u.name AS employeeName,
        u.email AS employeeEmail
    FROM tasks t
    INNER JOIN users u
        ON t.assignedTo = u.id
    WHERE t.taskId = ?"
);


if (!$stmt) {

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );
}


/*
|--------------------------------------------------------------------------
| 3. Bind task ID
|--------------------------------------------------------------------------
*/

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $taskId
);


/*
|--------------------------------------------------------------------------
| 4. Execute
|--------------------------------------------------------------------------
*/

if (!mysqli_stmt_execute($stmt)) {

    die(
        "Execute failed: " .
        mysqli_stmt_error($stmt)
    );
}


/*
|--------------------------------------------------------------------------
| 5. Get result
|--------------------------------------------------------------------------
*/

$result = mysqli_stmt_get_result($stmt);

$task = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| 6. Check task
|--------------------------------------------------------------------------
*/

if (!$task) {

    die("Task not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>View Task</title>

</head>

<body>

    <h1>Task Details</h1>


    <p>

        <a href="index.php">
            Back to Tasks
        </a>

        |

        <a href="edit.php?id=<?php echo $task["taskId"]; ?>">
            Edit Task
        </a>

    </p>


    <fieldset>

        <legend>Task Information</legend>


        <p>
            <strong>Task ID:</strong>

            <?php
            echo $task["taskId"];
            ?>
        </p>


        <p>
            <strong>Title:</strong>

            <?php
            echo htmlspecialchars(
                $task["title"]
            );
            ?>
        </p>


        <p>
            <strong>Description:</strong>

            <?php
            echo nl2br(
                htmlspecialchars(
                    $task["description"]
                )
            );
            ?>
        </p>


        <p>
            <strong>Assigned Employee:</strong>

            <?php
            echo htmlspecialchars(
                $task["employeeName"]
            );
            ?>
        </p>


        <p>
            <strong>Employee Email:</strong>

            <?php
            echo htmlspecialchars(
                $task["employeeEmail"]
            );
            ?>
        </p>


        <p>
            <strong>Start Date:</strong>

            <?php
            echo htmlspecialchars(
                $task["startDate"]
            );
            ?>
        </p>


        <p>
            <strong>Due Date:</strong>

            <?php
            echo htmlspecialchars(
                $task["dueDate"]
            );
            ?>
        </p>


        <p>
            <strong>Status:</strong>

            <?php
            echo htmlspecialchars(
                $task["status"]
            );
            ?>
        </p>


        <p>
            <strong>Priority:</strong>

            <?php
            echo htmlspecialchars(
                $task["priority"]
            );
            ?>
        </p>


        <p>
            <strong>Created At:</strong>

            <?php
            echo htmlspecialchars(
                $task["createdAt"]
            );
            ?>
        </p>


    </fieldset>

</body>

</html>