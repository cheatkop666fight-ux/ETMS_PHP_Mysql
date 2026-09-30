<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/csrf.php";
require_once "../../includes/flash.php";


// Filters
$employeeId = $_GET["employee_id"] ?? "";
$workDate = $_GET["work_date"] ?? "";


// Employee list
$employeeResult = mysqli_query(
    $conn,
    "SELECT id, name
     FROM users
     WHERE role = 'employee'
     ORDER BY name ASC"
);

if (!$employeeResult) {
    die("Employee query failed: " . mysqli_error($conn));
}


// Attendance query
$sql = "
    SELECT
        a.attenId,
        a.workDate,
        a.checkIn,
        a.checkOut,
        u.name AS employeeName,
        u.username
    FROM attendances a
    INNER JOIN users u
        ON a.userId = u.id
    WHERE u.role = 'employee'
";

$params = [];
$types = "";


// Employee filter
if ($employeeId !== "" && is_numeric($employeeId)) {

    $sql .= " AND a.userId = ? ";

    $params[] = (int) $employeeId;

    $types .= "i";
}


// Date filter
if ($workDate !== "") {

    $sql .= " AND a.workDate = ? ";

    $params[] = $workDate;

    $types .= "s";
}


$sql .= "
    ORDER BY a.workDate DESC, a.checkIn DESC
";


$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}


if (!empty($params)) {

    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );
}


if (!mysqli_stmt_execute($stmt)) {
    die("Execute failed: " . mysqli_stmt_error($stmt));
}


$result = mysqli_stmt_get_result($stmt);

$flash = get_flash();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>ETMS // Attendance Management</title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>

<body>

    <div class="app">

        <?php require_once "../../includes/admin_sidebar.php"; ?>

        <div class="main">

            <?php require_once "../../includes/topbar.php"; ?>

            <main class="content">

                <div class="page-header">

                    <div>

                        <h1>Attendance Management</h1>

                        <p>
                            ADMIN PORTAL // EMPLOYEE ATTENDANCE
                        </p>

                    </div>

                </div>


                <?php if ($flash): ?>

                    <div class="flash flash-<?php echo htmlspecialchars($flash["type"]); ?>">

                        <?php echo htmlspecialchars($flash["message"]); ?>

                    </div>

                <?php endif; ?>


                <!-- FILTER -->

                <section class="panel">

                    <div class="panel-header">

                        <div>

                            <h2>Filter Attendance</h2>

                            <p>
                                SEARCH EMPLOYEE ATTENDANCE RECORDS
                            </p>

                        </div>

                    </div>


                    <form
                        action="index.php"
                        method="get"
                        class="filter-form">


                        <div class="form-group">

                            <label for="employee_id">
                                Employee
                            </label>

                            <select
                                name="employee_id"
                                id="employee_id">

                                <option value="">
                                    All Employees
                                </option>

                                <?php while ($employee = mysqli_fetch_assoc($employeeResult)): ?>

                                    <option
                                        value="<?php echo $employee["id"]; ?>"
                                        <?php
                                        echo (
                                            $employeeId == $employee["id"]
                                        )
                                            ? "selected"
                                            : "";
                                        ?>>

                                        <?php
                                        echo htmlspecialchars(
                                            $employee["name"]
                                        );
                                        ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="work_date">
                                Date
                            </label>

                            <input
                                type="date"
                                name="work_date"
                                id="work_date"
                                value="<?php echo htmlspecialchars($workDate); ?>">

                        </div>


                        <div class="filter-actions">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                FILTER

                            </button>

                            <a
                                href="index.php"
                                class="btn">

                                RESET

                            </a>

                        </div>

                    </form>

                </section>


                <!-- TABLE -->

                <section class="panel">

                    <div class="panel-header">

                        <div>

                            <h2>Attendance Records</h2>

                            <p>
                                <?php echo mysqli_num_rows($result); ?>
                                RECORD(S)
                            </p>

                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Username</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>


                            <tbody>

                                <?php if (mysqli_num_rows($result) === 0): ?>

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="empty-state">

                                            No attendance records found.

                                        </td>

                                    </tr>

                                <?php endif; ?>


                                <?php while ($attendance = mysqli_fetch_assoc($result)): ?>

                                    <tr>

                                        <td>
                                            <?php
                                            echo htmlspecialchars(
                                                $attendance["employeeName"]
                                            );
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo htmlspecialchars(
                                                $attendance["username"]
                                            );
                                            ?>
                                        </td>

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
                                        <td>

                                            <div class="actions">

                                                <a
                                                    class="btn"
                                                    href="edit.php?id=<?php echo $attendance["attenId"]; ?>">
                                                    Edit
                                                </a>


                                                <form
                                                    action="delete.php"
                                                    method="post"
                                                    style="display:inline;">

                                                    <?php csrf_field(); ?>

                                                    <input
                                                        type="hidden"
                                                        name="attenId"
                                                        value="<?php echo $attendance["attenId"]; ?>">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Delete this attendance record?');">

                                                        Delete

                                                    </button>

                                                </form>

                                            </div>

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