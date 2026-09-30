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

    <title>ETMS // View Task</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css"
    >

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
                        Task #<?php
                        echo $task["taskId"];
                        ?>
                    </h1>

                    <p>
                        TASK MANAGEMENT // DETAILS
                    </p>

                </div>


                <div class="actions">

                    <a
                        class="btn btn-primary"
                        href="edit.php?id=<?php
                            echo $task["taskId"];
                        ?>"
                    >
                        Edit
                    </a>


                    <a
                        class="btn"
                        href="index.php"
                    >
                        Back
                    </a>

                </div>

            </div>


            <div class="detail-grid">


                <div class="detail-item">

                    <div class="detail-label">
                        Title
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $task["title"]
                        );
                        ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Employee
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $task["employeeName"]
                        );
                        ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Start Date
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $task["startDate"]
                        );
                        ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Due Date
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $task["dueDate"]
                        );
                        ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Status
                    </div>

                    <div class="detail-value">

                        <span
                            class="badge badge-<?php
                                echo htmlspecialchars(
                                    $task["status"]
                                );
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $task["status"]
                            );
                            ?>

                        </span>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Priority
                    </div>

                    <div class="detail-value">

                        <span
                            class="badge badge-<?php
                                echo htmlspecialchars(
                                    $task["priority"]
                                );
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $task["priority"]
                            );
                            ?>

                        </span>

                    </div>

                </div>


            </div>


            <div class="card">

                <div class="card-title">
                    Description
                </div>


                <p>

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $task["description"] ?? ""
                        )
                    );

                    ?>

                </p>

            </div>


        </main>

    </div>

</div>

</body>

</html>

