<?php
require_once "./config/database.php";
 $sql = "SELECT DATABASE() AS database_name";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Employee Task Management System </title>
</head>

<body>
    <h1>Employee Task Management System</h1>
    <p>PHP is running.</p>
    <p>MySql connection is successful.</p>

    <p>
        Connected database:
        <strong>
            <?php echo htmlspecialchars($row["database_name"]); ?>
        </strong>
    </p>

</body>

</html>