<?php
require("db.php");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <!-- navbar -->
    <nav>
        <div class="nav-left">
            <a href="index.php" class="logo">Ogłoszenia Lokalne</a>
        </div>
        <div class="nav-right">
            <a href="account.php">Twoje Konto</a>
            <a href="post_ad.php" class="highlighted">Dodaj ogłoszenie</a>
        </div>
    </nav>

    <main>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];

            if (!empty($username) && !empty($password) && !empty($email)) {
                $hashed = md5($password); 

                $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed', '$email')";
                $result = $conn->query($sql);

                if ($result) {
                    echo "<h2>Rejestracja zakończona sukcesem!</h2>
                        <p style='margin-top: 20px;'>
                            Możesz teraz <a href='login.php' style='color: #ff7e5f; font-weight: bold;'>się zalogować</a>.
                        </p>";
                } else {
                    echo "<h2>Błąd przy rejestracji użytkownika.</h2>
                        <p style='margin-top: 20px;'>
                            <a href='register.php' style='color: #ff7e5f; font-weight: bold;'>Spróbuj ponownie</a>
                        </p>";
                }
            } else {
                echo "<h2>Wypełnij wszystkie pola formularza.</h2>";
            }
        } else {
        ?>
            <h2>Załóż konto</h2>
            <form id="formularz" method="post" action="">
                <label for="username">Login:</label>
                <input type="text" name="username" id="username" required><br>

                <label for="password">Hasło:</label>
                <input type="password" name="password" id="password" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br>

                <button type="submit" class="btn-primary">Zarejestruj się</button>
            </form>
            <p style="margin-top: 20px;">
                Masz już konto?
                <a href="login.php" style="color: #ff7e5f; font-weight: bold;">Zaloguj się</a>
            </p>
        <?php
        }
        ?>
    </main>
</body>
</html>
