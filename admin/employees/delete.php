<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");

    exit;
}


$id = $_POST["id"] ?? "";


if (!is_numeric($id)) {

    header("Location: index.php?error=" . urlencode(
        "Invalid employee ID."
    ));

    exit;
}


$id = (int) $id;


// ========================================
// Delete employee
// ========================================

$stmt = mysqli_prepare(
    $conn,
    "
    DELETE FROM users
    WHERE id = ?
    AND role = 'employee'
    "
);


if (!$stmt) {

    header("Location: index.php?error=" . urlencode(
        "Failed to prepare delete request."
    ));

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if (mysqli_stmt_execute($stmt)) {

    // Check whether an employee was actually deleted
    if (mysqli_stmt_affected_rows($stmt) > 0) {

        mysqli_stmt_close($stmt);

        header("Location: index.php?success=" . urlencode(
            "Employee deleted successfully."
        ));

        exit;
    }

    mysqli_stmt_close($stmt);

    header("Location: index.php?error=" . urlencode(
        "Employee not found."
    ));

    exit;
}


// ========================================
// Database error
// ========================================

$error = mysqli_stmt_error($stmt);

mysqli_stmt_close($stmt);


header("Location: index.php?error=" . urlencode(
    "Failed to delete employee: " . $error
));

exit;

