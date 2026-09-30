<?php
// csrf.php 
function csrf_token()
{
    if (!isset($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function verify_csrf_token($token)
{
    return isset($_SESSION["csrf_token"]) &&
        hash_equals($_SESSION["csrf_token"], $token);
}

/*
|--------------------------------------------------------------------------
| CSRF Hidden Input
|--------------------------------------------------------------------------
|
| This creates:
|
| <input type="hidden"
|        name="csrf_token"
|        value="...">
|
*/

function csrf_field()
{
    echo '<input
        type="hidden"
        name="csrf_token"
        value="' .
        htmlspecialchars(
            csrf_token(),
            ENT_QUOTES,
            "UTF-8"
        ) .
        '">';
}

function csrf_verify()
{
    if (
        $_SERVER["REQUEST_METHOD"] === "POST"
        &&
        (
            !isset($_POST["csrf_token"])
            ||
            !isset($_SESSION["csrf_token"])
            ||
            !hash_equals(
                $_SESSION["csrf_token"],
                $_POST["csrf_token"]
            )
        )
    ) {

        http_response_code(403);

        die("Invalid CSRF token.");
    }
}
