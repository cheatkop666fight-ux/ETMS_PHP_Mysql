<?php

require_once "../includes/employee_auth.php";
require_once "../config/database.php";


$userId = $_SESSION["user_id"];


$stmt = mysqli_prepare(
    $conn,
    "SELECT
        id,
        name,
        email,
        username,
        createdAt
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
    $userId
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
}


$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$user) {

    die("Employee not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>ETMS // My Profile</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body>

    <div class="app">


        <?php
        require_once "../includes/employee_sidebar.php";
        ?>


        <div class="main">


            <?php
            require_once "../includes/topbar.php";
            ?>


            <main class="content">


                <div class="page-header">

                    <div>

                        <h1>
                            My Profile
                        </h1>

                        <p>
                            EMPLOYEE PORTAL // ACCOUNT INFORMATION
                        </p>

                    </div>

                </div>


                <div class="detail-grid">


                    <div class="detail-item">

                        <div class="detail-label">
                            Name
                        </div>

                        <div class="detail-value">

                            <?php
                            echo htmlspecialchars(
                                $user["name"]
                            );
                            ?>

                        </div>

                    </div>


                    <div class="detail-item">

                        <div class="detail-label">
                            Username
                        </div>

                        <div class="detail-value">

                            <?php
                            echo htmlspecialchars(
                                $user["username"]
                            );
                            ?>

                        </div>

                    </div>


                    <div class="detail-item">

                        <div class="detail-label">
                            Email
                        </div>

                        <div class="detail-value">

                            <?php
                            echo htmlspecialchars(
                                $user["email"]
                            );
                            ?>

                        </div>

                    </div>


                    <div class="detail-item">

                        <div class="detail-label">
                            Role
                        </div>

                        <div class="detail-value">

                            <span class="badge badge-completed">
                                EMPLOYEE
                            </span>

                        </div>

                    </div>


                </div>


                <div class="actions">

                    <a
                        class="btn btn-primary"
                        href="dashboard.php">
                        Back To Dashboard
                    </a>


                    <a
                        class="btn btn-danger"
                        href="../login/logout.php">
                        Logout
                    </a>

                </div>


            </main>

        </div>

    </div>

</body>

</html>