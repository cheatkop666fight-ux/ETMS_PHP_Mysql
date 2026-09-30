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

    die("Prepare failed: " .
        mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $taskId
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
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

    die("Employee query failed: " .
        mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>ETMS // Edit Task</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body>

    <div class="app">


        <?php
        require_once "../../includes/admin_sidebar.php";
        ?>


        <div class="main">


            <?php
            require_once "../../includes/topbar.php";
            ?>


            <main class="content">


                <div class="page-header">

                    <div>

                        <h1>
                            Edit Task
                        </h1>

                        <p>
                            TASK MANAGEMENT // UPDATE RECORD
                        </p>

                    </div>

                </div>


                <div class="form-card">

                    <form
                        action="update.php"
                        method="post">


                        <input
                            type="hidden"
                            name="taskId"
                            value="<?php
                                    echo $task["taskId"];
                                    ?>">


                        <div class="form-group">

                            <label for="title">
                                Task Title
                            </label>

                            <input
                                class="form-control"
                                type="text"
                                name="title"
                                id="title"
                                value="<?php
                                        echo htmlspecialchars(
                                            $task["title"]
                                        );
                                        ?>"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="description">
                                Description
                            </label>

                            <textarea
                                class="form-control"
                                name="description"
                                id="description"><?php
                                                    echo htmlspecialchars(
                                                        $task["description"] ?? ""
                                                    );
                                                    ?></textarea>

                        </div>


                        <div class="form-group">

                            <label for="assignedTo">
                                Assign To
                            </label>

                            <select
                                class="form-control"
                                name="assignedTo"
                                id="assignedTo"
                                required>

                                <?php
                                while (
                                    $employee =
                                    mysqli_fetch_assoc($result)
                                ):
                                ?>

                                    <option
                                        value="<?php
                                                echo $employee["id"];
                                                ?>"
                                        <?php
                                        if (
                                            $employee["id"]
                                            ==
                                            $task["assignedTo"]
                                        ) {
                                            echo "selected";
                                        }
                                        ?>>

                                        <?php
                                        echo htmlspecialchars(
                                            $employee["name"]
                                        );
                                        ?>

                                    </option>

                                <?php
                                endwhile;
                                ?>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="startDate">
                                Start Date
                            </label>

                            <input
                                class="form-control"
                                type="datetime-local"
                                name="startDate"
                                id="startDate"
                                value="<?php
                                        echo date(
                                            "Y-m-d\TH:i",
                                            strtotime(
                                                $task["startDate"]
                                            )
                                        );
                                        ?>"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="dueDate">
                                Due Date
                            </label>

                            <input
                                class="form-control"
                                type="datetime-local"
                                name="dueDate"
                                id="dueDate"
                                value="<?php
                                        echo date(
                                            "Y-m-d\TH:i",
                                            strtotime(
                                                $task["dueDate"]
                                            )
                                        );
                                        ?>"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="status">
                                Status
                            </label>

                            <select
                                class="form-control"
                                name="status"
                                id="status"
                                required>

                                <option
                                    value="incomplete"
                                    <?php
                                    if (
                                        $task["status"]
                                        === "incomplete"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>
                                    INCOMPLETE
                                </option>


                                <option
                                    value="progressing"
                                    <?php
                                    if (
                                        $task["status"]
                                        === "progressing"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>
                                    PROGRESSING
                                </option>


                                <option
                                    value="completed"
                                    <?php
                                    if (
                                        $task["status"]
                                        === "completed"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>
                                    COMPLETED
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="priority">
                                Priority
                            </label>

                            <select
                                class="form-control"
                                name="priority"
                                id="priority"
                                required>

                                <option
                                    value="low"
                                    <?php
                                    if (
                                        $task["priority"]
                                        === "low"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>
                                    LOW
                                </option>


                                <option
                                    value="medium"
                                    <?php
                                    if (
                                        $task["priority"]
                                        === "medium"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>
                                    MEDIUM
                                </option>


                                <option
                                    value="high"
                                    <?php
                                    if (
                                        $task["priority"]
                                        === "high"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>
                                    HIGH
                                </option>

                            </select>

                        </div>


                        <div class="actions">

                            <button
                                class="btn btn-primary"
                                type="submit">
                                Update Task
                            </button>


                            <a
                                class="btn"
                                href="index.php">
                                Cancel
                            </a>

                        </div>


                    </form>

                </div>


            </main>

        </div>

    </div>

</body>

</html>