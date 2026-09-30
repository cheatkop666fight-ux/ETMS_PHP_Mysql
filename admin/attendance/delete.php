<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";


/*
|--------------------------------------------------------------------------
| POST only
|--------------------------------------------------------------------------
*/

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
| Validate attendance ID
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
| Delete
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,

    "DELETE FROM attendances
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


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Delete failed: " .
        mysqli_stmt_error($stmt)
    );
}


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header(
    "Location: index.php"
);

exit;

?>