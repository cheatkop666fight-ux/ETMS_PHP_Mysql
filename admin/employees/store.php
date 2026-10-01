<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: create.php");
    exit;
}


$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirm_password"] ?? "";


// ========================================
// Basic validation
// ========================================

if (
    $name === "" ||
    $email === "" ||
    $username === "" ||
    $password === "" ||
    $confirmPassword === ""
) {
    header("Location: create.php?error=" . urlencode(
        "Please fill in all fields."
    ));
    exit;
}


// ========================================
// Check password confirmation
// ========================================

if ($password !== $confirmPassword) {

    header("Location: create.php?error=" . urlencode(
        "Passwords do not match."
    ));

    exit;
}


// ========================================
// Check duplicate email OR username
// ========================================

$check = $conn->prepare("
    SELECT id, email, username
    FROM users
    WHERE email = ? OR username = ?
");

$check->bind_param(
    "ss",
    $email,
    $username
);

$check->execute();

$result = $check->get_result();


if ($result->num_rows > 0) {

    $existingUser = $result->fetch_assoc();


    // ========================================
    // Duplicate email
    // ========================================

    if (strcasecmp($existingUser["email"], $email) === 0) {

        header("Location: create.php?error=" . urlencode(
            "Email already exists."
        ));

        exit;
    }


    // ========================================
    // Duplicate username
    // ========================================

    if (strcasecmp($existingUser["username"], $username) === 0) {

        header("Location: create.php?error=" . urlencode(
            "Username already exists."
        ));

        exit;
    }
}


// ========================================
// Hash password
// ========================================

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


// ========================================
// Insert employee
// ========================================

$stmt = $conn->prepare("
    INSERT INTO users
    (
        name,
        email,
        username,
        password,
        role
    )
    VALUES (?, ?, ?, ?, 'employee')
");


$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $username,
    $hashedPassword
);


if ($stmt->execute()) {

    header("Location: index.php?success=" . urlencode(
        "Employee created successfully."
    ));

    exit;
}


// ========================================
// Unexpected database error
// ========================================

header("Location: create.php?error=" . urlencode(
    "Failed to create employee."
));

exit;
