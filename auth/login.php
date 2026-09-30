<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";


    /*
    |--------------------------------------------------------------------------
    | Validate input
    |--------------------------------------------------------------------------
    */

    if ($username === "" || $password === "") {

        $message = "Username and password are required.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Find user
        |--------------------------------------------------------------------------
        */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT
                id,
                name,
                username,
                password,
                role
             FROM users
             WHERE username = ?"
        );


        if (!$stmt) {

            die(
                "Prepare failed: " .
                mysqli_error($conn)
            );
        }


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $username
        );


        if (!mysqli_stmt_execute($stmt)) {

            die(
                "Execute failed: " .
                mysqli_stmt_error($stmt)
            );
        }


        $result = mysqli_stmt_get_result($stmt);

        $user = mysqli_fetch_assoc($result);


        mysqli_stmt_close($stmt);


        /*
        |--------------------------------------------------------------------------
        | Verify password
        |--------------------------------------------------------------------------
        */

        if (
            $user &&
            password_verify(
                $password,
                $user["password"]
            )
        ) {


            /*
            |--------------------------------------------------------------------------
            | Prevent session fixation
            |--------------------------------------------------------------------------
            */

            session_regenerate_id(true);


            /*
            |--------------------------------------------------------------------------
            | Create session
            |--------------------------------------------------------------------------
            */

            $_SESSION["user_id"] =
                $user["id"];

            $_SESSION["name"] =
                $user["name"];

            $_SESSION["username"] =
                $user["username"];

            $_SESSION["role"] =
                $user["role"];


            /*
            |--------------------------------------------------------------------------
            | Redirect according to role
            |--------------------------------------------------------------------------
            */

            if ($user["role"] === "admin") {

                header(
                    "Location: ../admin/dashboard.php"
                );

                exit;
            }


            if ($user["role"] === "employee") {

                header(
                    "Location: ../employee/dashboard.php"
                );

                exit;
            }

        } else {

            $message =
                "Invalid username or password.";
        }
    }
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
        ETMS // Login
    </title>

    <link
        rel="stylesheet"
        href="/ETMS/assets/css/style.css">

</head>


<body class="login-page">


    <main class="login-container">


        <!-- BRAND -->

        <div class="login-brand">

            <div class="login-brand-mark">
                ETMS
            </div>

            <div>

                <h1>
                    Employee Task
                    Management System
                </h1>

                <p>
                    SYSTEM ACCESS // AUTHENTICATION
                </p>

            </div>

        </div>


        <!-- LOGIN PANEL -->

        <section class="login-panel">


            <div class="login-header">

                <span class="login-label">
                    AUTHENTICATION
                </span>

                <h2>
                    SIGN IN
                </h2>

                <p>
                    ENTER YOUR SYSTEM CREDENTIALS
                </p>

            </div>


            <!-- ERROR MESSAGE -->

            <?php if ($message !== ""): ?>

                <div class="flash flash-error">

                    <?php
                    echo htmlspecialchars(
                        $message
                    );
                    ?>

                </div>

            <?php endif; ?>


            <!-- LOGIN FORM -->

            <form
                action="login.php"
                method="post"
                class="login-form">


                <div class="form-group">

                    <label for="username">
                        USERNAME
                    </label>

                    <input
                        type="text"
                        name="username"
                        id="username"
                        maxlength="50"
                        autocomplete="username"
                        value="<?php
                            echo htmlspecialchars(
                                $_POST["username"] ?? ""
                            );
                        ?>"
                        required>

                </div>


                <div class="form-group">

                    <label for="password">
                        PASSWORD
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="current-password"
                        required>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary login-button">

                    ACCESS SYSTEM →

                </button>


            </form>


        </section>


        <!-- FOOTER -->

        <div class="login-footer">

            <span>
                ETMS
            </span>

            <span>
                EMPLOYEE TASK MANAGEMENT SYSTEM
            </span>

        </div>


    </main>


</body>

</html>

