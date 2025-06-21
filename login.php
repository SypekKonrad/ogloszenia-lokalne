<?php
session_start();
if (isset($_SESSION["login"])) {
    $redirect = $_GET['redirect'] ?? 'account.php';
    header("Location: $redirect");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Zaloguj</title>
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
    <h2 style="margin-top: 0;">Zaloguj się</h2>

    <form method="post" action="login_process.php" id="formularz">
    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect'] ?? 'account.php'); ?>">

    <label for="username">Login:</label>
    <input type="text" name="username" id="username" required /><br />

    <label for="password">Hasło:</label>
    <input type="password" name="password" id="password" required /><br />

    <button type="submit" class="btn-primary">Zaloguj się</button>
    </form>

    <p style="margin-top: 20px;">
      Nie masz konta?
      <a href="register.php" style="color: #ff7e5f; font-weight: bold;">Zarejestruj się</a>
    </p>
  </main>
</body>
</html>