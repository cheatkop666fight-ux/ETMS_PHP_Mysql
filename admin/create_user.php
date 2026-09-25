<?php
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $conpassword  = $_POST["conpassword"];
    $role = $_POST["role"];

    //1. Check passwords
    if ($password !== $conpassword) {
        $message = "Password and Confirm password do not match.";
    } else {
        //2. hast password after confirm success.
        $password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        //3. Insert into database
        $stmt = mysqli_prepare(
            $conn,
            "
            INSERT INTO users 
            (name, email, username, password, role)
            VALUES
            (?, ?, ?, ?,  ?)
            "
        );
        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $name,
            $email,
            $username,
            $password,
            $role
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "User created successfully.";
        } else {
            $message = " Error: " . mysqli_stmt_error($stmt);
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
    <title>Create user</title>
</head>

<body>
    <h1>Create User</h1>
    <?php if ($message):
        echo " <p>" . htmlspecialchars($message) . "</p> ";
    endif
    ?>

    <form action=" <?php echo $_SERVER['PHP_SELF']; ?> " method="post">
        <fieldset>
            <legend>Create new user</legend>
            <div>
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <br>
            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <br>

            <div>
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <br>

            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <br>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="conpassword" required>
            </div>
            <br>

            <div>
                <label>Role</label>
                <select name="role">
                    <option value="employee">Employee</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <br>

            <button type="submit">Create</button>


        </fieldset>
    </form>



</body>

</html>