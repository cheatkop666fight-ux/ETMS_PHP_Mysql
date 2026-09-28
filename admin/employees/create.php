<?php
require_once "../../includes/admin_auth.php"
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>

<body>
    <h1>Add Employee</h1>
    <form action="store.php" method="post">
        <fieldset>
            <legend>Form</legend>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div><br>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div><br>
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div><br>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div><br>
            <div>
                <label for="conpassword">Confirm Password</label>
                <input type="password" name="conpassword" id="conpassword" required>
            </div><br>
            <button type="submit">Create Employee</button>
        </fieldset>
    </form>
    <p><a href="index.php">Back to Employees</a></p>

</body>

</html>