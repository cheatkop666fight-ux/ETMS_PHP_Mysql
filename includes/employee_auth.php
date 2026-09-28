<?php
//include/employee_auth.ph
require_once __DIR__ . "/auth.php";

if ( !isset($_SESSION["role"]) || $_SESSION["role"] !== "employee" ){
    http_response_code(403);
    die("Access denied.");
}

?>