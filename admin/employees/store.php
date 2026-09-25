<?php
require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: create.php");
    exit;
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$username = trim($_POST["username"]);

$password = $_POST["password"];
$conpassword = $_POST["conpassword"];

if(
    $name === "" || 
    $email === "" ||
    $username === "" ||
    $password === "" ||
    $conpassword === "" 
){
    die("All fields are required.");
}
if ($password !== $conpassword){
    die("Password and confirm password is not match.");
}

$hashed_password = password_hash(
    $password, PASSWORD_DEFAULT
);

$role = "employee";

$stmt = mysqli_prepare(
    $conn, "
    INSERT INTO users (name,email,username, password, role)
    VALUE (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param(
    $stmt,"sssss",$name,$email,$username,$hashed_password,$role
);

if(mysqli_stmt_execute($stmt)){
    mysqli_stmt_close($stmt);
    header("Location: index.php");
    exit;
}

echo "Failed to create employee. <br> " . mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

?>