<?php
require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/flash.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: create.php");
    exit;
}

$title = trim($_POST["title"] ?? "");
$description = trim($_POST["description"] ?? "");

$assignedTo = $_POST["assignedTo"] ?? "";
$startDate = $_POST["startDate"] ?? "";
$dueDate = $_POST["dueDate"] ?? "";
$status  = $_POST["status"] ?? "";
$priority = $_POST["priority"] ?? "";

if ($title === "" || $assignedTo === "" || $startDate === "" || $dueDate === "" || $status === "" || $priority === "") {
    die("Please fill in all required filed.");
}

if (!is_numeric($assignedTo)) {
    die("Invalid employee.");
}

$assignedTo = (int) $assignedTo;

$allowedStatus = ["incomplete", "progressing", "completed"];
$allowedPriority = ["low", "medium", "high"];

if (!in_array($status, $allowedStatus, true)) {
    die("Invalid status.");
}
if (!in_array($priority, $allowedPriority, true)) {
    die("Invalid status.");
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO tasks (title, description, assignedTo, startDate, dueDate, status, priority)
    VALUE (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    die("Prepare failed." . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssissss",
    $title,
    $description,
    $assignedTo,
    $startDate,
    $dueDate,
    $status,
    $priority
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header("Location:  index.php");
exit;
