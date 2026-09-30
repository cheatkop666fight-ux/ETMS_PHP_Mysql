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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ETMS // Employees</title>

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
                        Employees
                    </h1>

                    <p>
                        USER MANAGEMENT // EMPLOYEE RECORDS
                    </p>

                </div>


                <a
                    class="btn btn-primary"
                    href="create.php"
                >
                    + Add Employee
                </a>

            </div>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Username</th>

                            <th>Role</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php
                    if (mysqli_num_rows($result) === 0):
                    ?>

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">
                                    No employees found.
                                </div>

                            </td>

                        </tr>

                    <?php
                    else:
                    ?>

                        <?php
                        while (
                            $employee =
                            mysqli_fetch_assoc($result)
                        ):
                        ?>

                            <tr>

                                <td>
                                    #
                                    <?php
                                    echo $employee["id"];
                                    ?>
                                </td>


                                <td>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $employee["name"]
                                        );
                                        ?>

                                    </strong>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $employee["email"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $employee["username"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <span class="badge badge-completed">
                                        EMPLOYEE
                                    </span>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $employee["createdAt"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <div class="actions">

                                        <a
                                            class="btn btn-primary"
                                            href="edit.php?id=<?php
                                                echo $employee["id"];
                                            ?>"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            class="btn btn-danger"
                                            href="delete.php?id=<?php
                                                echo $employee["id"];
                                            ?>"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php
                        endwhile;
                        ?>

                    <?php
                    endif;
                    ?>

                    </tbody>

                </table>

            </div>


        </main>

    </div>

</div>

</body>

</html>

