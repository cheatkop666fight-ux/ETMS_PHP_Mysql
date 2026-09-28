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
        content="width=device-width, initial-scale=1.0">

    <title>My Tasks</title>

</head>

<body>

    <h1>My Tasks</h1>


    <p>

        <a href="../dashboard.php">
            Dashboard
        </a>

        |

        <a href="../profile.php">
            My Profile
        </a>

    </p>


    <table border="1">

        <thead>

            <tr>

                <th>Task ID</th>

                <th>Title</th>

                <th>Start Date</th>

                <th>Due Date</th>

                <th>Status</th>

                <th>Priority</th>

                <th>Action</th>

            </tr>

        </thead>


        <tbody>

            <?php while ($task = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td>
                        <?php
                        echo $task["taskId"];
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $task["title"]
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
                        <?php
                        echo htmlspecialchars(
                            $task["status"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $task["priority"]
                        );
                        ?>
                    </td>


                    <td>

                        <a
                            href="view.php?id=<?php echo $task["taskId"]; ?>">
                            View
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</body>

</html>

<?php

mysqli_stmt_close($stmt);

?>