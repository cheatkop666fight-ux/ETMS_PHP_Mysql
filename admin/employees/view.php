<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


/*
|--------------------------------------------------------------------------
| Validate employee ID
|--------------------------------------------------------------------------
*/

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    die("Invalid employee ID.");

}


$id = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| Get employee
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,

    "SELECT
        id,
        name,
        email,
        username,
        role,
        createdAt

     FROM users

     WHERE id = ?
     AND role = 'employee'"
);


if (!$stmt) {

    die(
        "Prepare failed: " .
        mysqli_error($conn)
    );

}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Execute failed: " .
        mysqli_stmt_error($stmt)
    );

}


$result =
    mysqli_stmt_get_result($stmt);


$employee =
    mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| Employee not found
|--------------------------------------------------------------------------
*/

if (!$employee) {

    http_response_code(404);

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

    <title>
        ETMS // Employee Details
    </title>


    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body>

<div class="app">


    <!-- ==========================================================
         ADMIN SIDEBAR
         ========================================================== -->

    <?php
    require_once "../../includes/admin_sidebar.php";
    ?>


    <div class="main">


        <!-- ======================================================
             TOPBAR
             ====================================================== -->

        <?php
        require_once "../../includes/topbar.php";
        ?>


        <main class="content">


            <!-- ==================================================
                 PAGE HEADER
                 ================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Employee Details
                    </h1>

                    <p>
                        EMPLOYEE MANAGEMENT //
                        ACCOUNT INFORMATION
                    </p>

                </div>


                <a
                    class="btn"
                    href="index.php">

                    ← Back To Employees

                </a>

            </div>


            <!-- ==================================================
                 EMPLOYEE INFORMATION
                 ================================================== -->

            <div class="detail-grid">


                <!-- ID -->

                <div class="detail-item">

                    <div class="detail-label">
                        Employee ID
                    </div>

                    <div class="detail-value">

                        #
                        <?php
                        echo $employee["id"];
                        ?>

                    </div>

                </div>


                <!-- NAME -->

                <div class="detail-item">

                    <div class="detail-label">
                        Name
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $employee["name"]
                        );
                        ?>

                    </div>

                </div>


                <!-- USERNAME -->

                <div class="detail-item">

                    <div class="detail-label">
                        Username
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $employee["username"]
                        );
                        ?>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="detail-item">

                    <div class="detail-label">
                        Email
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $employee["email"]
                        );
                        ?>

                    </div>

                </div>


                <!-- ROLE -->

                <div class="detail-item">

                    <div class="detail-label">
                        Role
                    </div>

                    <div class="detail-value">

                        <span class="badge badge-completed">

                            <?php
                            echo strtoupper(
                                htmlspecialchars(
                                    $employee["role"]
                                )
                            );
                            ?>

                        </span>

                    </div>

                </div>


                <!-- CREATED -->

                <div class="detail-item">

                    <div class="detail-label">
                        Account Created
                    </div>

                    <div class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $employee["createdAt"]
                        );
                        ?>

                    </div>

                </div>


            </div>


            <!-- ==================================================
                 ACTIONS
                 ================================================== -->

            <div class="actions">


                <a
                    class="btn btn-primary"
                    href="edit.php?id=<?php echo $employee["id"]; ?>">

                    Edit Employee

                </a>


                <a
                    class="btn"
                    href="index.php">

                    Back

                </a>


            </div>


        </main>


    </div>


</div>

</body>

</html>
