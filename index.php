<?php
require_once 'db.php';

$categories = [];
$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
if ($result) {
    $categories = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ogłoszenia z Twojej okolicy</title>
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

       

        <!-- jakies tresci -->
        <section class="searchbar">
        <form action="index.php" method="GET" class="searchbar-form">
            <input type="text" name="q" placeholder="Szukaj" class="search-input">
            <input type="text" name="location" placeholder="Lokalizacja" class="search-input">
            <button type="submit" class="btn-primary search-button">Szukaj</button>
        </form>
        </section>

        <section class="category-buttons">
        <?php foreach ($categories as $category): ?>
            <a href="category.php?id=<?= $category['id'] ?>" class="btn-primary category-button">
            <?= htmlspecialchars($category['name']) ?>
            </a>
        <?php endforeach; ?>
        </section>

        <div>
            <p>wszystkie ogl</p>
        </div>

    </main>
</body>
</html>

<!-- class="wybrany" -->