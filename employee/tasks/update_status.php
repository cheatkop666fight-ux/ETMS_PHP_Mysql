<?php

require_once "../../includes/employee_auth.php";
require_once "../../config/database.php";


$userId = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| 1. POST only
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| 2. Get data
|--------------------------------------------------------------------------
*/

$taskId = $_POST["taskId"] ?? "";

$status = $_POST["status"] ?? "";


/*
|--------------------------------------------------------------------------
| 3. Validate task ID
|--------------------------------------------------------------------------
*/

if (
    $taskId === "" ||
    !is_numeric($taskId)
) {

    die("Invalid task ID.");
}


$taskId = (int) $taskId;


/*
|--------------------------------------------------------------------------
| 4. Validate status
|--------------------------------------------------------------------------
*/

$allowedStatus = [
    "incomplete",
    "progressing",
    "completed"
];


if (!in_array(
    $status,
    $allowedStatus,
    true
)) {

    die("Invalid status.");
}


/*
|--------------------------------------------------------------------------
| 5. Update ONLY this employee's task
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "UPDATE tasks
     SET status = ?
     WHERE taskId = ?
     AND assignedTo = ?"
);


if (!$stmt) {

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );
}


mysqli_stmt_bind_param(
    $stmt,
    "sii",
    $status,
    $taskId,
    $userId
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Update failed: " .
        mysqli_stmt_error($stmt)
    );
}


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| 6. Redirect
|--------------------------------------------------------------------------
*/

header(
    "Location: view.php?id=" . $taskId
);

exit;