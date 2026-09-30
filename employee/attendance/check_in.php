<?php

require_once "../../includes/employee_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";
require_once "../../includes/flash.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;
}

csrf_verify();

$userId = $_SESSION["user_id"];

$today = date("Y-m-d");

$now = date("Y-m-d H:i:s");


// Check whether already checked in
$stmt = mysqli_prepare(
    $conn,
    "SELECT attenId
     FROM attendances
     WHERE userId = ?
     AND workDate = ?"
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $userId,
    $today
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$attendance = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if ($attendance) {

    set_flash(
        "error",
        "You have already checked in today."
    );

    header("Location: index.php");
    exit;
}


// Create attendance
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO attendances
        (userId, workDate, checkIn)
     VALUES
        (?, ?, ?)"
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "iss",
    $userId,
    $today,
    $now
);


if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    set_flash(
        "error",
        "Unable to check in."
    );

    header("Location: index.php");
    exit;
}

mysqli_stmt_close($stmt);


set_flash(
    "success",
    "You have successfully checked in."
);

header("Location: index.php");
exit;