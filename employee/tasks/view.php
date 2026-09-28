<?php

require_once "../../includes/employee_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";


$userId = $_SESSION["user_id"];


if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    die("Invalid task ID.");
}


$taskId = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| IMPORTANT:
| Check BOTH taskId AND assignedTo
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        taskId,
        title,
        description,
        startDate,
        dueDate,
        status,
        priority,
        createdAt
    FROM tasks
    WHERE taskId = ?
    AND assignedTo = ?"
);


if (!$stmt) {

    die("Prepare failed: " .
        mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $taskId,
    $userId
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
}


$result = mysqli_stmt_get_result($stmt);

$task = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$task) {

    http_response_code(404);

    die("Task not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>View My Task</title>

</head>

<body>

    <h1>Task Details</h1>


    <p>

        <a href="index.php">
            Back to My Tasks
        </a>

    </p>


    <fieldset>

        <legend>
            Task Information
        </legend>


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

            <br>

            <?php
            echo nl2br(
                htmlspecialchars(
                    $task["description"]
                )
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


    <p>

        <a href="view.php?id=<?php echo $task["taskId"]; ?>">
            Refresh
        </a>

    </p>

    <h2>Update Status</h2>

    <form action="update_status.php" method="post">

        <input
            type="hidden"
            name="taskId"
            value="<?php echo $task["taskId"]; ?>">
        <input
            type="hidden"
            name="csrf_token"
            value="<?php echo htmlspecialchars(csrf_token()); ?>">

        <label for="status">
            Status
        </label>

        <select
            name="status"
            id="status"
            required>

            <option
                value="incomplete"
                <?php
                echo $task["status"] === "incomplete"
                    ? "selected"
                    : "";
                ?>>
                Incomplete
            </option>


            <option
                value="progressing"
                <?php
                echo $task["status"] === "progressing"
                    ? "selected"
                    : "";
                ?>>
                Progressing
            </option>


            <option
                value="completed"
                <?php
                echo $task["status"] === "completed"
                    ? "selected"
                    : "";
                ?>>
                Completed
            </option>

        </select>

        <button type="submit">
            Update Status
        </button>

    </form>

</body>

</html>