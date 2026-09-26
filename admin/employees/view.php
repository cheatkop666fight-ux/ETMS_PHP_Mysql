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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employee</title>
</head>

<body>
    <h1>Employee Detail</h1>
    <p>
        <strong>ID: </strong> <?php echo $employee["id"]; ?>
    </p>
    <p>
        <strong>Name: </strong> <?php echo htmlspecialchars($employee["name"]); ?>
    </p>
    <p>
        <strong>Email: </strong> <?php echo htmlspecialchars($employee["email"]); ?>
    </p>
    <p>
        <strong>Username: </strong> <?php echo htmlspecialchars($employee["username"]); ?>
    </p>
    <p>
        <strong>Role: </strong> <?php echo htmlspecialchars($employee["role"]); ?>
    </p>
    <p>
        <strong>Created: </strong> <?php echo htmlspecialchars($employee["createdAt"]); ?>
    </p>
    <p>
        <a href="edit.php?id= <?php echo $employee["id"]; ?> ">Edit</a>|
        <a href="index.php">Back</a>
    </p>
</body>

</html>