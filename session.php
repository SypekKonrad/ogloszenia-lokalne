<?php
session_start();

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    $redirect = basename($_SERVER["PHP_SELF"]);
    header("Location: login.php?redirect=$redirect");
    exit;
}
?>