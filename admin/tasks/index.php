<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

$sql =
    "SELECT t.taskId, t.title, t.description, t.startDate, t.dueDate, t.status, t.priority, t.createdAt, u.name as employeeName
    From tasks t INNER JOIN users u 
        On t.assignedTo = u.id
    ORDER BY t.taskId DESC";
 
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed." . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
</head>

<body>
    <h1>Task Management</h1>
    <p>
        <a href="../dashboard.php">Dashboard</a>|
        <a href="create.php">Create Task</a>|
    </p>

    <table border="1">
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Title</th>
                <th>Employee</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $task["taskId"]; ?></td>
                    <td><?php echo htmlspecialchars($task["title"]); ?></td>
                    <td><?php echo htmlspecialchars($task["employeeName"]); ?></td>
                    <td><?php echo htmlspecialchars($task["startDate"]); ?></td>
                    <td><?php echo htmlspecialchars($task["dueDate"]); ?></td>
                    <td><?php echo htmlspecialchars($task["status"]); ?></td>
                    <td><?php echo htmlspecialchars($task["priority"]); ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $task["taskId"]; ?> "> View </a>|
                        <a href="edit.php?id=<?php echo $task["taskId"]; ?> "> Edit </a>
                        <a href="delete.php?id=<?php echo $task["taskId"]; ?> " 
                        onclick="return confirm('Are you sure you want to delete this task?')"> Delete </a>

                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>