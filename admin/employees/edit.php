<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("Invalid employee ID.");
}


$id = (int) $_GET["id"];


$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, username, role, createdAt
     FROM users
     WHERE id = ?
     AND role = 'employee'"
);


if (!$stmt) {

    die("Prepare failed: " .
        mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
}


$result = mysqli_stmt_get_result($stmt);

$employee = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$employee) {

    die("Employee not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Employee</title>

</head>

<body>

    <h1>Edit Employee</h1>


    <form action="update.php" method="POST">

        <fieldset>

            <legend>Edit Employee</legend>


            <input
                type="hidden"
                name="id"
                value="<?php echo $employee["id"]; ?>">


            <div>

                <label for="name">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="<?php
                            echo htmlspecialchars(
                                $employee["name"]
                            );
                            ?>"
                    required>

            </div>

            <br>


            <div>

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    value="<?php
                            echo htmlspecialchars(
                                $employee["email"]
                            );
                            ?>"
                    required>

            </div>

            <br>


            <div>

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    value="<?php
                            echo htmlspecialchars(
                                $employee["username"]
                            );
                            ?>"
                    required>

            </div>

            <br>


            <button type="submit">
                Update Employee
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