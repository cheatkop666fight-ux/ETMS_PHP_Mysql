<?php

require_once "../includes/employee_auth.php";
require_once "../config/database.php";


$userId = $_SESSION["user_id"];


$stmt = mysqli_prepare(
    $conn,
    "SELECT
        id,
        name,
        email,
        username,
        createdAt
    FROM users
    WHERE id = ?
    AND role = 'employee'"
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

$user = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$user) {

    die("Employee not found.");
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

    <title>My Profile</title>

</head>

<body>

    <h1>My Profile</h1>


    <p>

        <a href="dashboard.php">
            Dashboard
        </a>

        |

        <a href="tasks/index.php">
            My Tasks
        </a>

    </p>


    <fieldset>

        <legend>
            Personal Information
        </legend>


        <p>

            <strong>ID:</strong>

            <?php
            echo $user["id"];
            ?>

        </p>


        <p>

            <strong>Name:</strong>

            <?php
            echo htmlspecialchars(
                $user["name"]
            );
            ?>

        </p>


        <p>

            <strong>Email:</strong>

            <?php
            echo htmlspecialchars(
                $user["email"]
            );
            ?>

        </p>


        <p>

            <strong>Username:</strong>

            <?php
            echo htmlspecialchars(
                $user["username"]
            );
            ?>

        </p>


        <p>

            <strong>Account Created:</strong>

            <?php
            echo htmlspecialchars(
                $user["createdAt"]
            );
            ?>

        </p>

    </fieldset>

</body>

</html>