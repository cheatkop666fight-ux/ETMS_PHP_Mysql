<?php
//include/employee_auth.ph
require_once "auth.php";

if ($_SESSION["role"] !== "employee" ){
    http_response_code(403);
    die("Access denied.");
}

?>