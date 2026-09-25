<?php
session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // check the field are now empty; 
    if ($username === "" || $password === "") {
        $message = "User name and password are required.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, username, password, role
            FROM users 
            where username = ? 
            "
        );
        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $username
        );

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {
            // create session 
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            // Redirect according to role 
            if ($user["role"] === "admin") {

                header("location: ../admin/dashboard.php");
                exit;
            } elseif ($user["role"] === "employee") {
                header("location: ../employee/dashboard.php");
                exit;
            }
        } else {
            $message = " Invalid username or password. ";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Employee Task Management System</h1>
    <h2>Login</h2>
    <?php if ($message !== ""): ?>
        <p><?php echo htmlspecialchars($message); ?></p>

    <?php endif;  ?>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <fieldset>
            <legend>Login</legend>
            <div>
                <label for="">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <br>

            <div>
                <label for="">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <br>

            <button type="submit">Login</button>

        </fieldset>
    </form>

</body>

</html>