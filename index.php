<?php
require_once 'db.php';

$categories = [];
$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
if ($result) {
    $categories = $result->fetch_all(MYSQLI_ASSOC);
}

$ads = [];
$search_query = $_GET['q'] ?? '';
$search_location = $_GET['location'] ?? '';

$sql = "SELECT ads.*, categories.name AS category_name 
        FROM ads 
        JOIN categories ON ads.category_id = categories.id 
        WHERE 1";

$params = [];
$types = '';

if ($search_query !== '') {
    $sql .= " AND (ads.title LIKE ? OR ads.description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
    $types .= 'ss';
}

if ($search_location !== '') {
    $sql .= " AND ads.location LIKE ?";
    $params[] = "%$search_location%";
    $types .= 's';
}

$sql .= " ORDER BY ads.id DESC LIMIT 12";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$ads = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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

        <section class="ads-grid">
            <?php foreach ($ads as $ad): ?>
                <a href="ad_view.php?id=<?= $ad['id'] ?>&return=index.php" class="ad-tile">
                    <div class="ad-thumbnail">
                        <?php if (!empty($ad['image'])): ?>
                            <img src="<?= htmlspecialchars($ad['image']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>">
                        <?php else: ?>
                            <div class="no-image">Brak zdjęcia</div>
                        <?php endif; ?>
                    </div>
                    <div class="ad-info">
                        <h3><?= htmlspecialchars($ad['title']) ?></h3>
                        <p><?= htmlspecialchars($ad['category_name']) ?></p>
                        <p><?= htmlspecialchars($ad['location']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>

    </main>
</body>
</html>

<!-- class="wybrany" -->