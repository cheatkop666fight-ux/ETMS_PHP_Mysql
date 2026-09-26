<?php
require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = $_POST["id"] ?? "";
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$username = trim($_POST["username"] ?? "");

if (
    !is_numeric($id) ||
    $name === "" ||
    $email === "" ||
    $username === ""
) {

    die("Invalid data.");
}

$id = (int) $id;


$stmt = mysqli_prepare(
    $conn,
    "UPDATE users
     SET name = ?,
         email = ?,
         username = ?
     WHERE id = ?
     AND role = 'employee'"
);

mysqli_stmt_bind_param(
    $stmt,
    "sssi",
    $name,
    $email,
    $username,
    $id
);


if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: view.php?id=" . $id);

    exit;
}

echo "Failed to update employee.";

echo "<br>";

echo mysqli_stmt_error($stmt);


mysqli_stmt_close($stmt);

?>
