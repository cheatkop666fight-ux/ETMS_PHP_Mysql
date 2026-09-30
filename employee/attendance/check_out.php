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


// Find today's attendance
$stmt = mysqli_prepare(
    $conn,
    "SELECT attenId, checkIn, checkOut
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


// No check-in
if (!$attendance) {

    set_flash(
        "error",
        "You must check in before checking out."
    );

    header("Location: index.php");
    exit;
}


// Already checked out
if ($attendance["checkOut"] !== null) {

    set_flash(
        "error",
        "You have already checked out today."
    );

    header("Location: index.php");
    exit;
}


// Make sure checkout is after check-in
if (strtotime($now) < strtotime($attendance["checkIn"])) {

    set_flash(
        "error",
        "Check-out time cannot be earlier than check-in time."
    );

    header("Location: index.php");
    exit;
}


// Update checkout
$stmt = mysqli_prepare(
    $conn,
    "UPDATE attendances
     SET checkOut = ?
     WHERE attenId = ?"
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $now,
    $attendance["attenId"]
);


if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    set_flash(
        "error",
        "Unable to check out."
    );

    header("Location: index.php");
    exit;
}

mysqli_stmt_close($stmt);


set_flash(
    "success",
    "You have successfully checked out."
);

header("Location: index.php");
exit;