<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";


if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {
    die("Invalid attendance ID.");
}


$attenId = (int) $_GET["id"];


$stmt = mysqli_prepare(
    $conn,

    "SELECT
        a.attenId,
        a.userId,
        a.workDate,
        a.checkIn,
        a.checkOut,
        u.name AS employeeName

     FROM attendances a

     INNER JOIN users u
        ON a.userId = u.id

     WHERE a.attenId = ?"
);


if (!$stmt) {

    die("Prepare failed: " .
        mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $attenId
);


if (!mysqli_stmt_execute($stmt)) {

    die("Execute failed: " .
        mysqli_stmt_error($stmt));
}


$result =
    mysqli_stmt_get_result($stmt);

$attendance =
    mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


if (!$attendance) {

    http_response_code(404);

    die("Attendance record not found.");
}


/*
|--------------------------------------------------------------------------
| Convert DATETIME to datetime-local format
|--------------------------------------------------------------------------
*/

$checkIn = "";

if (!empty($attendance["checkIn"])) {

    $checkIn =
        date(
            "Y-m-d\TH:i",
            strtotime(
                $attendance["checkIn"]
            )
        );
}


$checkOut = "";

if (!empty($attendance["checkOut"])) {

    $checkOut =
        date(
            "Y-m-d\TH:i",
            strtotime(
                $attendance["checkOut"]
            )
        );
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
        ETMS // Edit Attendance
    </title>

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
                            Edit Attendance
                        </h1>

                        <p>
                            ATTENDANCE CONTROL //
                            ADMIN CORRECTION
                        </p>

                    </div>

                </div>


                <div class="form-container">

                    <form
                        action="update.php"
                        method="post">


                        <?php csrf_field(); ?>


                        <input
                            type="hidden"
                            name="attenId"
                            value="<?php
                                    echo $attendance["attenId"];
                                    ?>">


                        <div class="form-group">

                            <label>
                                Employee
                            </label>

                            <input
                                type="text"
                                value="<?php
                                        echo htmlspecialchars(
                                            $attendance["employeeName"]
                                        );
                                        ?>"
                                disabled>

                        </div>


                        <div class="form-group">

                            <label for="workDate">
                                Work Date
                            </label>

                            <input
                                type="date"
                                name="workDate"
                                id="workDate"
                                value="<?php
                                        echo htmlspecialchars(
                                            $attendance["workDate"]
                                        );
                                        ?>"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="checkIn">
                                Check In
                            </label>

                            <input
                                type="datetime-local"
                                name="checkIn"
                                id="checkIn"
                                value="<?php
                                        echo htmlspecialchars(
                                            $checkIn
                                        );
                                        ?>"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="checkOut">
                                Check Out
                            </label>

                            <input
                                type="datetime-local"
                                name="checkOut"
                                id="checkOut"
                                value="<?php
                                        echo htmlspecialchars(
                                            $checkOut
                                        );
                                        ?>">

                        </div>


                        <div class="actions">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                SAVE CORRECTION

                            </button>


                            <a
                                href="index.php"
                                class="btn">

                                CANCEL

                            </a>

                        </div>


                    </form>

                </div>

            </main>

        </div>

    </div>

</body>

</html>