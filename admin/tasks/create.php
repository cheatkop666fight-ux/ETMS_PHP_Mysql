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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>ETMS // Create Task</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body>

    <div class="app">


        <?php
        require_once "../../includes/admin_sidebar.php";
        ?>


        <div class="main">


            <?php
            require_once "../../includes/topbar.php";
            ?>


            <main class="content">


                <div class="page-header">

                    <div>

                        <h1>
                            Create Task
                        </h1>

                        <p>
                            TASK MANAGEMENT // NEW RECORD
                        </p>

                    </div>

                </div>


                <div class="form-card">

                    <form
                        action="store.php"
                        method="post">


                        <div class="form-group">

                            <label for="title">
                                Task Title
                            </label>

                            <input
                                class="form-control"
                                type="text"
                                name="title"
                                id="title"
                                maxlength="150"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="description">
                                Description
                            </label>

                            <textarea
                                class="form-control"
                                name="description"
                                id="description"></textarea>

                        </div>


                        <div class="form-group">

                            <label for="assignedTo">
                                Assign To
                            </label>

                            <select
                                class="form-control"
                                name="assignedTo"
                                id="assignedTo"
                                required>

                                <option value="">
                                    -- SELECT EMPLOYEE --
                                </option>


                                <?php
                                while (
                                    $employee =
                                    mysqli_fetch_assoc($result)
                                ):
                                ?>

                                    <option
                                        value="<?php
                                                echo $employee["id"];
                                                ?>">

                                        <?php
                                        echo htmlspecialchars(
                                            $employee["name"]
                                        );
                                        ?>

                                    </option>

                                <?php
                                endwhile;
                                ?>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="startDate">
                                Start Date
                            </label>

                            <input
                                class="form-control"
                                type="datetime-local"
                                name="startDate"
                                id="startDate"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="dueDate">
                                Due Date
                            </label>

                            <input
                                class="form-control"
                                type="datetime-local"
                                name="dueDate"
                                id="dueDate"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="priority">
                                Priority
                            </label>

                            <select
                                class="form-control"
                                name="priority"
                                id="priority"
                                required>

                                <option value="low">
                                    LOW
                                </option>

                                <option
                                    value="medium"
                                    selected>
                                    MEDIUM
                                </option>

                                <option value="high">
                                    HIGH
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="status">
                                Status
                            </label>

                            <select
                                class="form-control"
                                name="status"
                                id="status"
                                required>

                                <option
                                    value="incomplete"
                                    selected>
                                    INCOMPLETE
                                </option>

                                <option value="progressing">
                                    PROGRESSING
                                </option>

                                <option value="completed">
                                    COMPLETED
                                </option>

                            </select>

                        </div>


                        <div class="actions">

                            <button
                                class="btn btn-primary"
                                type="submit">
                                Create Task
                            </button>


                            <a
                                class="btn"
                                href="index.php">
                                Cancel
                            </a>

                        </div>


                    </form>

                </div>


            </main>

        </div>

    </div>

</body>

</html>