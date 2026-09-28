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
| 2. Get task
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        taskId,
        title,
        description,
        assignedTo,
        startDate,
        dueDate,
        status,
        priority
    FROM tasks
    WHERE taskId = ?"
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
    $taskId
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Execute failed: " .
        mysqli_stmt_error($stmt)
    );
}


$result = mysqli_stmt_get_result($stmt);

$task = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$task) {

    die("Task not found.");
}


/*
|--------------------------------------------------------------------------
| 3. Get employees
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT id, name
    FROM users
    WHERE role = 'employee'
    ORDER BY name ASC
";


$employees = mysqli_query($conn, $sql);


if (!$employees) {

    die(
        "Employee query failed: " .
        mysqli_error($conn)
    );
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

    <title>Edit Task</title>

</head>

<body>

    <h1>Edit Task</h1>


    <form action="update.php" method="post">

        <fieldset>

            <legend>Task Information</legend>


            <!-- Task ID -->

            <input
                type="hidden"
                name="taskId"
                value="<?php echo $task["taskId"]; ?>"
            >


            <!-- Title -->

            <div>

                <label for="title">
                    Title
                </label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    maxlength="150"
                    value="<?php echo htmlspecialchars($task["title"]); ?>"
                    required
                >

            </div>

            <br>


            <!-- Description -->

            <div>

                <label for="description">
                    Description
                </label>

                <br>

                <textarea
                    name="description"
                    id="description"
                    cols="50"
                    rows="6"
                ><?php
                    echo htmlspecialchars(
                        $task["description"]
                    );
                ?></textarea>

            </div>

            <br>


            <!-- Employee -->

            <div>

                <label for="assignedTo">
                    Assign To
                </label>

                <select
                    name="assignedTo"
                    id="assignedTo"
                    required
                >

                    <?php while ($employee = mysqli_fetch_assoc($employees)): ?>

                        <option
                            value="<?php echo $employee["id"]; ?>"
                            <?php
                            if (
                                $employee["id"] == $task["assignedTo"]
                            ) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $employee["name"]
                            );
                            ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <br>


            <!-- Start Date -->

            <div>

                <label for="startDate">
                    Start Date
                </label>

                <input
                    type="datetime-local"
                    name="startDate"
                    id="startDate"
                    value="<?php echo date(
                        "Y-m-d\TH:i",
                        strtotime($task["startDate"])
                    ); ?>"
                    required
                >

            </div>

            <br>


            <!-- Due Date -->

            <div>

                <label for="dueDate">
                    Due Date
                </label>

                <input
                    type="datetime-local"
                    name="dueDate"
                    id="dueDate"
                    value="<?php echo date(
                        "Y-m-d\TH:i",
                        strtotime($task["dueDate"])
                    ); ?>"
                    required
                >

            </div>

            <br>


            <!-- Priority -->

            <div>

                <label for="priority">
                    Priority
                </label>

                <select
                    name="priority"
                    id="priority"
                    required
                >

                    <option
                        value="low"
                        <?php
                        echo $task["priority"] === "low"
                            ? "selected"
                            : "";
                        ?>
                    >
                        Low
                    </option>


                    <option
                        value="medium"
                        <?php
                        echo $task["priority"] === "medium"
                            ? "selected"
                            : "";
                        ?>
                    >
                        Medium
                    </option>


                    <option
                        value="high"
                        <?php
                        echo $task["priority"] === "high"
                            ? "selected"
                            : "";
                        ?>
                    >
                        High
                    </option>

                </select>

            </div>

            <br>


            <!-- Status -->

            <div>

                <label for="status">
                    Status
                </label>

                <select
                    name="status"
                    id="status"
                    required
                >

                    <option
                        value="incomplete"
                        <?php
                        echo $task["status"] === "incomplete"
                            ? "selected"
                            : "";
                        ?>
                    >
                        Incomplete
                    </option>


                    <option
                        value="progressing"
                        <?php
                        echo $task["status"] === "progressing"
                            ? "selected"
                            : "";
                        ?>
                    >
                        Progressing
                    </option>


                    <option
                        value="completed"
                        <?php
                        echo $task["status"] === "completed"
                            ? "selected"
                            : "";
                        ?>
                    >
                        Completed
                    </option>

                </select>

            </div>

            <br>


            <button type="submit">
                Update Task
            </button>

        </fieldset>

    </form>


    <p>

        <a href="index.php">
            Cancel
        </a>

    </p>

</body>

</html>