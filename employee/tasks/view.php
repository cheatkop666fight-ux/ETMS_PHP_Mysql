<?php

require_once "../../includes/employee_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";
require_once "../../includes/flash.php";


$userId = $_SESSION["user_id"];


if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    die("Invalid task ID.");
}


$taskId = (int) $_GET["id"];


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


$flash = get_flash();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        ETMS // View Task
    </title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body>

    <div class="app">


        <?php require_once "../../includes/employee_sidebar.php"; ?>


        <div class="main">


            <?php require_once "../../includes/topbar.php"; ?>


            <main class="content">


                <?php if ($flash): ?>

                    <div
                        class="flash flash-<?php echo htmlspecialchars($flash["type"]); ?>">

                        <?php
                        echo htmlspecialchars(
                            $flash["message"]
                        );
                        ?>

                    </div>

                <?php endif; ?>


                <!-- PAGE HEADER -->

                <div class="page-header">

                    <div>

                        <h1>
                            Task Details
                        </h1>

                        <p>
                            EMPLOYEE PORTAL // TASK INFORMATION
                        </p>

                    </div>


                    <a
                        href="index.php"
                        class="btn">

                        ← Back To My Tasks

                    </a>

                </div>


                <!-- TASK HEADER -->

                <section class="task-detail-header">

                    <div>

                        <div class="task-id">

                            TASK #
                            <?php
                            echo htmlspecialchars(
                                $task["taskId"]
                            );
                            ?>

                        </div>


                        <h2>

                            <?php
                            echo htmlspecialchars(
                                $task["title"]
                            );
                            ?>

                        </h2>

                    </div>


                    <div class="task-badges">

                        <span
                            class="badge badge-<?php
                                                echo htmlspecialchars(
                                                    $task["status"]
                                                );
                                                ?>">

                            <?php
                            echo strtoupper(
                                htmlspecialchars(
                                    $task["status"]
                                )
                            );
                            ?>

                        </span>


                        <span
                            class="badge badge-<?php
                                                echo htmlspecialchars(
                                                    $task["priority"]
                                                );
                                                ?>">

                            <?php
                            echo strtoupper(
                                htmlspecialchars(
                                    $task["priority"]
                                )
                            );
                            ?>

                        </span>

                    </div>

                </section>


                <!-- TASK INFORMATION -->

                <section class="panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Task Information
                            </h2>

                            <p>
                                DETAILS AND SCHEDULE
                            </p>

                        </div>

                    </div>


                    <div class="detail-grid">


                        <div class="detail-item">

                            <div class="detail-label">
                                TASK ID
                            </div>

                            <div class="detail-value">

                                #
                                <?php
                                echo htmlspecialchars(
                                    $task["taskId"]
                                );
                                ?>

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                TITLE
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
                                START DATE
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
                                DUE DATE
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
                                CREATED AT
                            </div>

                            <div class="detail-value">

                                <?php
                                echo htmlspecialchars(
                                    $task["createdAt"]
                                );
                                ?>

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                PRIORITY
                            </div>

                            <div class="detail-value">

                                <span
                                    class="badge badge-<?php
                                                        echo htmlspecialchars(
                                                            $task["priority"]
                                                        );
                                                        ?>">

                                    <?php
                                    echo strtoupper(
                                        htmlspecialchars(
                                            $task["priority"]
                                        )
                                    );
                                    ?>

                                </span>

                            </div>

                        </div>


                    </div>

                </section>


                <!-- DESCRIPTION -->

                <section class="panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Description
                            </h2>

                            <p>
                                TASK INSTRUCTIONS
                            </p>

                        </div>

                    </div>


                    <div class="task-description">

                        <?php

                        if (
                            $task["description"] !== null &&
                            $task["description"] !== ""
                        ) {

                            echo nl2br(
                                htmlspecialchars(
                                    $task["description"]
                                )
                            );
                        } else {

                            echo "No description provided.";
                        }

                        ?>

                    </div>

                </section>


                <!-- UPDATE STATUS -->

                <section class="panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Update Status
                            </h2>

                            <p>
                                CHANGE YOUR TASK PROGRESS
                            </p>

                        </div>

                    </div>


                    <form
                        action="update_status.php"
                        method="post"
                        class="form-card">


                        <input
                            type="hidden"
                            name="taskId"
                            value="<?php
                                    echo htmlspecialchars(
                                        $task["taskId"]
                                    );
                                    ?>">


                        <?php csrf_field(); ?>


                        <div class="form-group">

                            <label for="status">
                                Status
                            </label>


                            <select
                                name="status"
                                id="status"
                                class="form-control"
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

                        </div>


                        <div class="actions">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                UPDATE STATUS

                            </button>


                            <a
                                href="index.php"
                                class="btn">

                                CANCEL

                            </a>

                        </div>


                    </form>

                </section>


            </main>

        </div>

    </div>

</body>

</html>