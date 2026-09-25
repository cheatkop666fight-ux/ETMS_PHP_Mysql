<?php

require_once "../config/database.php";

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
</head>

<body>
    <h1>Users</h1>
    <?php
    while ($user = mysqli_fetch_assoc($result)):
    ?>
        <div>
            <h2>
                <?php
                echo htmlspecialchars($user["name"]);
                ?>
            </h2>

            <p>
                Username:
                <?php
                echo htmlspecialchars($user["username"]);
                ?>
            </p>

            <p>
                Role:
                <?php
                echo htmlspecialchars($user["role"]);
                ?>
            </p>
        </div>
        <hr>
    <?php
    endwhile;
    ?>
</body>

</html>