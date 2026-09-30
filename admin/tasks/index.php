<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";
require_once "../../includes/flash.php";


$sql =
    "SELECT
        t.taskId,
        t.title,
        t.description,
        t.startDate,
        t.dueDate,
        t.status,
        t.priority,
        t.createdAt,
        u.name AS employeeName

     FROM tasks t

     INNER JOIN users u
        ON t.assignedTo = u.id

     ORDER BY t.taskId DESC";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die("Query failed: " .
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

    <title>ETMS // Tasks</title>

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

                <?php if ($flash): ?>

                    <div class="flash flash-<?php echo htmlspecialchars($flash["type"]); ?>">
                        <?php echo htmlspecialchars($flash["message"]); ?>
                    </div>

                <?php endif; ?>

                <div class="page-header">

                    <div>

                        <h1>
                            Task Management
                        </h1>

                        <p>
                            TASK CONTROL // ALL EMPLOYEE TASKS
                        </p>

                    </div>


                    <a
                        class="btn btn-primary"
                        href="create.php">
                        + Create Task
                    </a>

                </div>


                <div class="table-container">

                    <table>

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Title</th>

                                <th>Employee</th>

                                <th>Start</th>

                                <th>Due</th>

                                <th>Status</th>

                                <th>Priority</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php
                            if (mysqli_num_rows($result) === 0):
                            ?>

                                <tr>

                                    <td colspan="8">

                                        <div class="empty-state">
                                            No tasks found.
                                        </div>

                                    </td>

                                </tr>

                            <?php
                            else:
                            ?>

                                <?php
                                while (
                                    $task =
                                    mysqli_fetch_assoc($result)
                                ):
                                ?>

                                    <tr>

                                        <td>
                                            #
                                            <?php
                                            echo $task["taskId"];
                                            ?>
                                        </td>


                                        <td>

                                            <strong>

                                                <?php
                                                echo htmlspecialchars(
                                                    $task["title"]
                                                );
                                                ?>

                                            </strong>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $task["employeeName"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $task["startDate"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $task["dueDate"]
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <span
                                                class="badge badge-<?php
                                                                    echo htmlspecialchars(
                                                                        $task["status"]
                                                                    );
                                                                    ?>">

                                                <?php
                                                echo htmlspecialchars(
                                                    $task["status"]
                                                );
                                                ?>

                                            </span>

                                        </td>


                                        <td>

                                            <span
                                                class="badge badge-<?php
                                                                    echo htmlspecialchars(
                                                                        $task["priority"]
                                                                    );
                                                                    ?>">

                                                <?php
                                                echo htmlspecialchars(
                                                    $task["priority"]
                                                );
                                                ?>

                                            </span>

                                        </td>


                                        <td>

                                            <div class="actions">

                                                <a
                                                    class="btn"
                                                    href="view.php?id=<?php
                                                                        echo $task["taskId"];
                                                                        ?>">
                                                    View
                                                </a>


                                                <a
                                                    class="btn btn-primary"
                                                    href="edit.php?id=<?php
                                                                        echo $task["taskId"];
                                                                        ?>">
                                                    Edit
                                                </a>


                                                <form
                                                    action="delete.php"
                                                    method="post"
                                                    style="display: inline;">

                                                    <input
                                                        type="hidden"
                                                        name="taskId"
                                                        value="<?php echo $task["taskId"]; ?>">

                                                    <?php csrf_field(); ?>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this task?');">

                                                        Delete

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                <?php
                                endwhile;
                                ?>

                            <?php
                            endif;
                            ?>

                        </tbody>

                    </table>

                </div>


            </main>

        </div>

    </div>


</body>

</html>