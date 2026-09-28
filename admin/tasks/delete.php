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
| 2. Check whether task exists
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT taskId
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
| 3. Delete task
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM tasks
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
        "Delete failed: " .
        mysqli_stmt_error($stmt)
    );
}


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| 4. Redirect
|--------------------------------------------------------------------------
*/

header("Location: index.php");

exit;