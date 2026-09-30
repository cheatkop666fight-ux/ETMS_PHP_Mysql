<?php

require_once "../../includes/employee_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";
require_once "../../includes/flash.php";

$userId = $_SESSION["user_id"];

$today = date("Y-m-d");


// Get today's attendance
$stmt = mysqli_prepare(
    $conn,
    "SELECT attenId, workDate, checkIn, checkOut
     FROM attendances
     WHERE userId = ?
     AND workDate = ?"
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $userId,
    $today
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$todayAttendance = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// Get attendance history
$stmt = mysqli_prepare(
    $conn,
    "SELECT workDate, checkIn, checkOut
     FROM attendances
     WHERE userId = ?
     ORDER BY workDate DESC"
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);

mysqli_stmt_execute($stmt);

$historyResult = mysqli_stmt_get_result($stmt);

$flash = get_flash();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>ETMS // Attendance</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>

<body>

<div class="app">

    <?php require_once "../../includes/employee_sidebar.php"; ?>

    <div class="main">

        <?php require_once "../../includes/topbar.php"; ?>

        <main class="content">

            <div class="page-header">

                <div>

                    <h1>Attendance</h1>

                    <p>
                        EMPLOYEE PORTAL // ATTENDANCE MANAGEMENT
                    </p>

                </div>

            </div>


            <?php if ($flash): ?>

                <div class="flash flash-<?php echo htmlspecialchars($flash["type"]); ?>">

                    <?php echo htmlspecialchars($flash["message"]); ?>

                </div>

            <?php endif; ?>


            <!-- TODAY -->

            <section class="panel">

                <div class="panel-header">

                    <div>

                        <h2>Today's Attendance</h2>

                        <p>
                            <?php echo htmlspecialchars($today); ?>
                        </p>

                    </div>

                </div>


                <?php if (!$todayAttendance): ?>

                    <div class="attendance-status">

                        <div>

                            <span class="detail-label">
                                STATUS
                            </span>

                            <strong>
                                NOT CHECKED IN
                            </strong>

                        </div>

                        <form
                            action="check_in.php"
                            method="post">

                            <?php csrf_field(); ?>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                CHECK IN

                            </button>

                        </form>

                    </div>


                <?php else: ?>

                    <div class="detail-grid">

                        <div class="detail-item">

                            <div class="detail-label">
                                WORK DATE
                            </div>

                            <div class="detail-value">
                                <?php
                                echo htmlspecialchars(
                                    $todayAttendance["workDate"]
                                );
                                ?>
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                CHECK IN
                            </div>

                            <div class="detail-value">

                                <?php
                                echo htmlspecialchars(
                                    $todayAttendance["checkIn"]
                                );
                                ?>

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                CHECK OUT
                            </div>

                            <div class="detail-value">

                                <?php
                                echo $todayAttendance["checkOut"]
                                    ? htmlspecialchars(
                                        $todayAttendance["checkOut"]
                                    )
                                    : "NOT CHECKED OUT";
                                ?>

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                STATUS
                            </div>

                            <div class="detail-value">

                                <?php if ($todayAttendance["checkOut"]): ?>

                                    <span class="badge badge-completed">
                                        COMPLETED
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-progressing">
                                        WORKING
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <?php if (!$todayAttendance["checkOut"]): ?>

                        <div class="actions">

                            <form
                                action="check_out.php"
                                method="post">

                                <?php csrf_field(); ?>

                                <button
                                    type="submit"
                                    class="btn btn-danger">

                                    CHECK OUT

                                </button>

                            </form>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </section>


            <!-- HISTORY -->

            <section class="panel">

                <div class="panel-header">

                    <div>

                        <h2>Attendance History</h2>

                        <p>
                            YOUR PREVIOUS ATTENDANCE RECORDS
                        </p>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="data-table">

                        <thead>

                            <tr>

                                <th>Date</th>

                                <th>Check In</th>

                                <th>Check Out</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if (mysqli_num_rows($historyResult) === 0): ?>

                            <tr>

                                <td
                                    colspan="4"
                                    class="empty-state">

                                    No attendance records found.

                                </td>

                            </tr>

                        <?php endif; ?>


                        <?php while ($attendance = mysqli_fetch_assoc($historyResult)): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $attendance["workDate"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $attendance["checkIn"]
                                    );
                                    ?>
                                </td>

                                <td>

                                    <?php

                                    echo $attendance["checkOut"]
                                        ? htmlspecialchars(
                                            $attendance["checkOut"]
                                        )
                                        : "—";

                                    ?>

                                </td>

                                <td>

                                    <?php if ($attendance["checkOut"]): ?>

                                        <span class="badge badge-completed">
                                            COMPLETED
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-progressing">
                                            WORKING
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</div>

</body>

</html>

<?php

mysqli_stmt_close($stmt);

?>