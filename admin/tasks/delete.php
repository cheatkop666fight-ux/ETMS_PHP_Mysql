<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";

// 1. Post only 

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(405);
    die("Method not allowed.");
}

// 2. Verify csrf

$token = $_POST["csrf_token"] ?? "";
if (!verify_csrf_token($token)) {
    http_response_code(403);
    die("Invalid CSRF token.");
}

// 3. validate task ID

$taskId = $_POST["taskId"] ?? "";

if ($taskId === "" || !is_numeric($taskId)){
    die("Invalid task ID.");
}

$taskId = (int) $taskId;

// 4. Delete task
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM tasks WHERE taskId = ?"
);

if (!$stmt){
    die("Prepare failed:" . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt,"i",$taskId);

if(!mysqli_stmt_execute($stmt)) {
    die("Delete failed:" . mysqli_stmt_error($stmt) );
}

mysqli_stmt_close($stmt);

// 5. Redirect

header("Location: index.php");
exit;
?>