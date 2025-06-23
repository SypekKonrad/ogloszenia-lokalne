<?php
require_once 'db.php';
require_once 'session.php';

$returnUrl = $_GET['return'] ?? 'account.php';

$ad_id = (int)($_GET['id'] ?? 0);
$ad = [];
$user = [];
if ($ad_id) {
    $stmt = $conn->prepare("
        SELECT ads.*, users.username, users.email, categories.name AS category_name 
        FROM ads 
        JOIN users ON ads.user_id = users.id 
        JOIN categories ON ads.category_id = categories.id 
        WHERE ads.id = ?
    ");
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ad = $result->fetch_assoc();
    $stmt->close();
}

if (empty($ad)) {
    die("Ogłoszenie nie istnieje lub zostało usunięte.");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ad['title']) ?> - Ogłoszenia Lokalne</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="index.php" class="logo">Ogłoszenia Lokalne</a>
        </div>
        <div class="nav-right">
            <a href="account.php">Twoje Konto</a>
            <a href="post_ad.php" class="highlighted">Dodaj ogłoszenie</a>
        </div>
    </nav>

    <main class="ad-container">
        <h1><?= htmlspecialchars($ad['title']) ?></h1>

        <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn-primary">Powrót</a>
        
        
        <div class="ad-meta">
            <span class="category">Kategoria: <?= htmlspecialchars($ad['category_name']) ?></span>
            <span class="location">Lokalizacja: <?= htmlspecialchars($ad['location']) ?></span>
        </div>

        <?php if (!empty($ad['image'])): ?>
            <div class="ad-image-container">
                <img src="<?= htmlspecialchars($ad['image']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>">
            </div>
        <?php endif; ?>

        <div class="ad-description">
            <h3>Opis:</h3>
            <p><?= nl2br(htmlspecialchars($ad['description'])) ?></p>
        </div>

        <?php if (isset($_SESSION['login'])): ?>
            <div class="ad-contact">
                <h3>Kontakt:</h3>
                <p>Ogłoszeniodawca: <?= htmlspecialchars($ad['username']) ?></p>
                <p>Email: <?= htmlspecialchars($ad['email']) ?></p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>