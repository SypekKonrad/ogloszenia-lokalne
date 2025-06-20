<?php
session_start();
require("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        $hashed = md5($password); // używasz md5 w register.php

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $_SESSION["login"] = $username;
            header("Location: account.php");
            exit;
        } else {
            echo "<h2>Błąd logowania</h2><p>Nieprawidłowy login lub hasło.</p>";
        }
    } else {
        echo "<h2>Wypełnij wszystkie pola.</h2>";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
