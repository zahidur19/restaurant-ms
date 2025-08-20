<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: /restaurant-ms/auth/login.php");
    exit;
}

// Role check (optional example)
function require_role($role) {
    if ($_SESSION["user"]["role"] !== $role) {
        echo "<div class='alert alert-danger text-center'>Access Denied!</div>";
        exit;
    }
}
