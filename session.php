<?php
session_start();

if (!isset($_SESSION["login"])) {
    $redirect = basename($_SERVER["PHP_SELF"]);
    header("Location: login.php?redirect=$redirect");
    exit;
}
?>