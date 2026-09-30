<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";
require_once "../../includes/flash.php";


// 1. POST only

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    die("Method not allowed.");
}


// 2. Verify CSRF

$token = $_POST["csrf_token"] ?? "";

if (!verify_csrf_token($token)) {

    http_response_code(403);

    die("Invalid CSRF token.");
}


// 3. Validate task ID

$taskId = $_POST["taskId"] ?? "";

if ($taskId === "" || !is_numeric($taskId)) {

    set_flash(
        "error",
        "Invalid task ID."
    );

    header("Location: index.php");
    exit;
}

$taskId = (int) $taskId;


// 4. Delete task

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


$deletedRows = mysqli_stmt_affected_rows($stmt);

mysqli_stmt_close($stmt);


// 5. Check result

if ($deletedRows === 0) {

    set_flash(
        "error",
        "Task not found."
    );

} else {

    set_flash(
        "success",
        "Task deleted successfully."
    );
}


// 6. Redirect

header("Location: index.php");
exit;