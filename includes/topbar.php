<header class="topbar">


    <div class="topbar-title">

        Employee Task Management System

    </div>


    <div class="user-info">

        USER:

        <strong>

            <?php

            echo htmlspecialchars(
                $_SESSION["name"] ?? "UNKNOWN"
            );

            ?>

        </strong>


        &nbsp; // &nbsp;


        ROLE:

        <strong>

            <?php

            echo htmlspecialchars(
                strtoupper(
                    $_SESSION["role"] ?? "UNKNOWN"
                )
            );

            ?>

        </strong>

    </div>


</header>
