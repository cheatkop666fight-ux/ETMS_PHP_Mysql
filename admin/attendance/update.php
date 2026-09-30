<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    die("Method not allowed.");
}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$token = $_POST["csrf_token"] ?? "";

if (!verify_csrf_token($token)) {

    http_response_code(403);

    die("Invalid CSRF token.");
}


/*
|--------------------------------------------------------------------------
| Validate ID
|--------------------------------------------------------------------------
*/

$attenId = $_POST["attenId"] ?? "";

if (
    $attenId === "" ||
    !is_numeric($attenId)
) {
    die("Invalid attendance ID.");
}

$attenId = (int) $attenId;


/*
|--------------------------------------------------------------------------
| Get submitted data
|--------------------------------------------------------------------------
*/

$workDate = $_POST["workDate"] ?? "";

$checkIn = $_POST["checkIn"] ?? "";

$checkOut = $_POST["checkOut"] ?? "";


/*
|--------------------------------------------------------------------------
| Required fields
|--------------------------------------------------------------------------
*/

if (
    $workDate === "" ||
    $checkIn === ""
) {
    die("Work date and check-in are required.");
}


/*
|--------------------------------------------------------------------------
| Check-out is optional
|--------------------------------------------------------------------------
*/

$checkOutValue = null;

if ($checkOut !== "") {

    $checkOutValue = $checkOut;
}


/*
|--------------------------------------------------------------------------
| Validate date/time order
|--------------------------------------------------------------------------
*/

$checkInTimestamp =
    strtotime($checkIn);


if ($checkInTimestamp === false) {

    die("Invalid check-in time.");
}


if ($checkOutValue !== null) {

    $checkOutTimestamp =
        strtotime($checkOutValue);


    if ($checkOutTimestamp === false) {

        die("Invalid check-out time.");
    }


    if (
        $checkOutTimestamp <=
        $checkInTimestamp
    ) {

        die(
            "Check-out must be later than check-in."
        );
    }
}


/*
|--------------------------------------------------------------------------
| Get employee ID of this attendance
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,

    "SELECT userId
     FROM attendances
     WHERE attenId = ?"
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
    $attenId
);


mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$attendance =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$attendance) {

    die("Attendance not found.");
}


$userId =
    (int) $attendance["userId"];


/*
|--------------------------------------------------------------------------
| Check duplicate date
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,

    "SELECT attenId
     FROM attendances
     WHERE userId = ?
     AND workDate = ?
     AND attenId != ?"
);


if (!$stmt) {

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );
}


mysqli_stmt_bind_param(
    $stmt,
    "isi",
    $userId,
    $workDate,
    $attenId
);


mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$duplicate =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if ($duplicate) {

    die(
        "This employee already has attendance for this date."
    );
}


/*
|--------------------------------------------------------------------------
| Update attendance
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,

    "UPDATE attendances

     SET
        workDate = ?,
        checkIn = ?,
        checkOut = ?

     WHERE attenId = ?"
);


if (!$stmt) {

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );
}


mysqli_stmt_bind_param(
    $stmt,
    "sssi",
    $workDate,
    $checkIn,
    $checkOutValue,
    $attenId
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Update failed: " .
        mysqli_stmt_error($stmt)
    );
}


mysqli_stmt_close($stmt);


header(
    "Location: index.php"
);

exit;

?>