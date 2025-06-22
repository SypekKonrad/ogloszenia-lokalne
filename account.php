<?php
require_once 'session.php'; 
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("
    SELECT ads.*, categories.name AS category_name 
    FROM ads 
    LEFT JOIN categories ON ads.category_id = categories.id 
    WHERE ads.user_id = ? 
    ORDER BY ads.id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_ads = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


?>
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
        <!-- <?php else: ?>
            <p>Nie jesteś zalogowany.</p>
        <?php endif; ?> -->

             <div>
                <p>todo:</p>
                <p>w acc.php zakładki moje ogłoszenia i ulubione</P>

            </div>
        
           <div class="ads-list">
                <?php foreach ($user_ads as $ad): ?>
                    <div class="ad-list-item">
                        <div class="ad-list-thumbnail">
                            <?php if (!empty($ad['image'])): ?>
                                <img src="<?= htmlspecialchars($ad['image']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>">
                            <?php else: ?>
                                <div class="no-image">Brak zdjęcia</div>
                            <?php endif; ?>
                        </div>
                        <div class="ad-list-details">
                            <h3><?= htmlspecialchars($ad['title']) ?></h3>
                            <p class="ad-meta">
                                <span class="location"><?= htmlspecialchars($ad['location']) ?></span>
                            </p>
                           <p class="ad-meta">
                                <span class="category"><?= htmlspecialchars($ad['category_name'] ?? 'Brak kategorii') ?></span>
                            </p>
                            <!-- <p class="description"><?= htmlspecialchars(substr($ad['description'], 0, 120)) ?></p> -->
                        </div>
                        <div class="ad-list-actions">
                            <a href="ad_view.php?id=<?= $ad['id'] ?>&return=account.php" class="btn-action">Podgląd</a>
                            <a href="edit_ad.php?id=<?= $ad['id'] ?>" class="btn-action btn-edit">Edytuj</a>
                            <a href="delete_ad.php?id=<?= $ad['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Na pewno chcesz usunąć to ogłoszenie?');">Usuń</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>



    </main>
</body>
</html>

<!-- class="wybrany" -->