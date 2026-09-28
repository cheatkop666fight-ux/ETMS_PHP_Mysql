<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


/*
|--------------------------------------------------------------------------
| 1. Make sure request is POST
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| 2. Get form data
|--------------------------------------------------------------------------
*/

$taskId = $_POST["taskId"] ?? "";

$title = trim(
    $_POST["title"] ?? ""
);

$description = trim(
    $_POST["description"] ?? ""
);

$assignedTo = $_POST["assignedTo"] ?? "";

$startDate = $_POST["startDate"] ?? "";

$dueDate = $_POST["dueDate"] ?? "";

$status = $_POST["status"] ?? "";

$priority = $_POST["priority"] ?? "";


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
| 4. Validate required fields
|--------------------------------------------------------------------------
*/

if (
    $title === "" ||
    $assignedTo === "" ||
    $startDate === "" ||
    $dueDate === "" ||
    $status === "" ||
    $priority === ""
) {

    die(
        "Please fill in all required fields."
    );
}


/*
|--------------------------------------------------------------------------
| 5. Validate employee ID
|--------------------------------------------------------------------------
*/

if (!is_numeric($assignedTo)) {

    die("Invalid employee.");
}


$assignedTo = (int) $assignedTo;


/*
|--------------------------------------------------------------------------
| 6. Validate status and priority
|--------------------------------------------------------------------------
*/

$allowedStatus = [
    "incomplete",
    "progressing",
    "completed"
];


$allowedPriority = [
    "low",
    "medium",
    "high"
];


if (!in_array(
    $status,
    $allowedStatus,
    true
)) {

    die("Invalid status.");
}


if (!in_array(
    $priority,
    $allowedPriority,
    true
)) {

    die("Invalid priority.");
}


/*
|--------------------------------------------------------------------------
| 7. Update task
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "UPDATE tasks
     SET
        title = ?,
        description = ?,
        assignedTo = ?,
        startDate = ?,
        dueDate = ?,
        status = ?,
        priority = ?
     WHERE taskId = ?"
);


if (!$stmt) {

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );
}


/*
|--------------------------------------------------------------------------
| 8. Bind values
|--------------------------------------------------------------------------
*/

mysqli_stmt_bind_param(
    $stmt,
    "ssissssi",
    $title,
    $description,
    $assignedTo,
    $startDate,
    $dueDate,
    $status,
    $priority,
    $taskId
);


/*
|--------------------------------------------------------------------------
| 9. Execute
|--------------------------------------------------------------------------
*/

if (!mysqli_stmt_execute($stmt)) {

    die(
        "Execute failed: " .
        mysqli_stmt_error($stmt)
    );
}


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| 10. Redirect
|--------------------------------------------------------------------------
*/

header(
    "Location: view.php?id=" . $taskId
);

exit;