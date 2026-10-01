<?php
// admin/employees/create.php
require_once "../../includes/admin_auth.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ETMS // Add Employee</title>

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


        <?php if (isset($_GET["error"])): ?>

            <div
                class="popup popup-error"
                id="errorPopup"
            >
                <span>
                    <?= htmlspecialchars($_GET["error"]) ?>
                </span>

                <button
                    type="button"
                    class="popup-close"
                    onclick="closePopup()"
                >
                    ×
                </button>
            </div>

        <?php endif; ?>


        <main class="content">


            <div class="page-header">

                <div>

                    <h1>
                        Add Employee
                    </h1>

                    <p>
                        USER MANAGEMENT // CREATE RECORD
                    </p>

                </div>

            </div>


            <div class="form-card">

                <form
                    action="store.php"
                    method="post"
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
                            maxlength="100"
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
                            maxlength="150"
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
                            maxlength="50"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <input
                            class="form-control"
                            type="password"
                            name="password"
                            id="password"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="confirm_password">
                            Confirm Password
                        </label>

                        <input
                            class="form-control"
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            required
                        >

                    </div>


                    <div class="actions">

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >
                            Create Employee
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


<script>

function closePopup() {

    const popup = document.getElementById("errorPopup");

    if (popup) {
        popup.remove();
    }

}

</script>


</body>

</html>

