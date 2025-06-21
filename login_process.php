<?php
session_start();
require("db.php");

$redirect = $_GET['redirect'] ?? 'account.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $redirect = $_POST['redirect'] ?? 'account.php';

    if (!empty($username) && !empty($password)) {
        $hashed = md5($password); 

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $_SESSION["login"] = $username;
            header("Location: account.php");
            header("Location: " . $redirect);
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
