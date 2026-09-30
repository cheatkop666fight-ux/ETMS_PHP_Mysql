<?php

require_once "../../includes/employee_auth.php";
require_once "../../config/database.php";


$userId = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| Get only this employee's tasks
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
    WHERE assignedTo = ?
    ORDER BY taskId DESC"
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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ETMS // My Tasks</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css"
    >

</head>


<body>

<div class="app">


    <?php
    require_once "../../includes/employee_sidebar.php";
    ?>


    <div class="main">


        <?php
        require_once "../../includes/topbar.php";
        ?>


        <main class="content">


            <div class="page-header">

                <div>

                    <h1>
                        My Tasks
                    </h1>

                    <p>
                        EMPLOYEE PORTAL // ASSIGNED TASKS
                    </p>

                </div>


                <a
                    class="btn"
                    href="../dashboard.php"
                >
                    Dashboard
                </a>

            </div>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Title</th>

                            <th>Start</th>

                            <th>Due</th>

                            <th>Status</th>

                            <th>Priority</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php
                    if (mysqli_num_rows($result) === 0):
                    ?>

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">
                                    You have no assigned tasks.
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
                                        ?>"
                                    >

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
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $task["priority"]
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <a
                                        class="btn"
                                        href="view.php?id=<?php
                                            echo $task["taskId"];
                                        ?>"
                                    >
                                        View
                                    </a>

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
