<?php

use LDAP\Result;

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

$sql = "
    SELECT id, name, email, username, role, createdAt
    from users where role = 'employee'
    order by id DESC
";

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
    <title>Employees</title>
</head>

<body>
    <h1>Employees</h1>
    <p>
        <a href="../dashboard.php">Dashboard</a>|
        <a href="create.php">Add Employee</a>
    </p>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>USERNAME</th>
                <th>CREATED</th>
                <th>ACTIONS</th>
            </tr>
        </thead>

        <Tbody>
            <?php while($employee = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $employee["id"] ?></td>
                    <td><?php echo htmlspecialchars($employee["name"]) ?></td>
                    <td><?php echo htmlspecialchars($employee["email"]) ?></td>
                    <td><?php echo htmlspecialchars($employee["username"]) ?></td>
                    <td><?php echo htmlspecialchars($employee["createdAt"]) ?></td>
                    <td>
                        <a href="./edit.php?id=<?php echo $employee["id"]; ?>">Edit</a>|
                        <a href="./view.php?id=<?php echo $employee["id"]; ?>">View</a>|
                        <a href="./delete.php?id=<?php echo $employee["id"]; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </Tbody>
    </table>


</body>

</html>