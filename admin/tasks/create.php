<?php
require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

$sql =
    "SELECT id,name 
FROM users 
Where role = 'employee'
order by name ASC";

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
    <title>Create Task</title>
</head>

<body>
    <h1>Create Task</h1>

    <form action="store.php" method="post">
        <fieldset>
            <legend>Task Information</legend>

            <div>
                <label for="title">Title</label>
                <input type="text" name="title" id="title" maxlength="150" required>
            </div><br>

            <div>
                <label for="description">Description</label><br>
                <textarea name="description" id="description" cols="50" rows="6"></textarea>
            </div><br>

            <div>
                <label for="assignedTo">Assign To</label>
                <select name="assignedTo" id="assignedTo" required>
                    <option value="">-- Select Employee --</option>
                    <?php while ($employee = mysqli_fetch_assoc($result)): ?>

                        <option value=" <?php echo $employee["id"]; ?> ">
                            <?php echo htmlspecialchars($employee["name"]) ?>
                        </option>

                    <?php endwhile; ?>

                </select>
            </div><br>

            <div>
                <label for="startDate">Start Date</label>
                <input type="datetime-local" name="startDate" id="startDate" required>
            </div>
            <br>
            <div>
                <label for="dueDate">Due Date</label>
                <input type="datetime-local" name="dueDate" id="dueDate" required>
            </div>
            <br>

            <div>
                <label for="priority">Priority</label>
                <select name="priority" id="priority" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div><br>

            <div>
                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="incomplete">Incomplete</option>
                    <option value="progressing">progressing</option>
                    <option value="completed">Completed</option>
                </select>
            </div><br>
            <button type="submit">Create Task</button>
        </fieldset>
    </form>
    <p><a href="index.php">Cancel</a></p>
</body>

</html>