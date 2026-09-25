<?php
require_once "../includes/admin_auth.php";
require_once "../config/database.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?></p>
    <p>Username: <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
    <p>Role: <?php echo htmlspecialchars($_SESSION["role"]); ?></p>
    <hr>

    <h2>Admin Menu</h2>
    <ul>
        <li><a href="employees/index.php">Employees</a></li>
        <li><a href="tasks/index.php">Tasks</a></li>
        <li><a href="attendance/index.php">Attendance</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>

</body>

</html>