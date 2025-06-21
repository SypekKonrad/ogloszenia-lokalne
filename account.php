<?php require_once 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje konto</title>
    <link rel="stylesheet" href="style.css">

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
        <?php if (isset($_SESSION['user'])): ?>
            <div class="account-bar">
                <p>
                    Zalogowano jako: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
                    (ID: <?= (int)$_SESSION['user']['id'] ?>)
                </p>
                <a href="logout.php" class="btn-primary">Wyloguj się</a>
            </div>
        <?php else: ?>
            <p>Nie jesteś zalogowany.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<!-- class="wybrany" -->