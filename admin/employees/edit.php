<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("Invalid employee ID.");
}


$id = (int) $_GET["id"];


$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, username, role, createdAt
     FROM users
     WHERE id = ?
     AND role = 'employee'"
);


if (!$stmt) {

    die("Prepare failed: " .
        mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
}


$result = mysqli_stmt_get_result($stmt);

$employee = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$employee) {

    die("Employee not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ETMS // Edit Employee</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css"
    >

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
                        Edit Employee
                    </h1>

                    <p>
                        USER MANAGEMENT // UPDATE RECORD
                    </p>

                </div>

            </div>


            <div class="form-card">

                <form
                    action="update.php"
                    method="post"
                >


                    <input
                        type="hidden"
                        name="id"
                        value="<?php
                            echo $employee["id"];
                        ?>"
                    >


                    <div class="form-group">

                        <label for="name">
                            Full Name
                        </label>

                        <input
                            class="form-control"
                            type="text"
                            name="name"
                            id="name"
                            value="<?php
                                echo htmlspecialchars(
                                    $employee["name"]
                                );
                            ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            class="form-control"
                            type="email"
                            name="email"
                            id="email"
                            value="<?php
                                echo htmlspecialchars(
                                    $employee["email"]
                                );
                            ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="username">
                            Username
                        </label>

                        <input
                            class="form-control"
                            type="text"
                            name="username"
                            id="username"
                            value="<?php
                                echo htmlspecialchars(
                                    $employee["username"]
                                );
                            ?>"
                            required
                        >

                    </div>


                    <div class="actions">

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >
                            Update Employee
                        </button>


                        <a
                            class="btn"
                            href="index.php"
                        >
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

