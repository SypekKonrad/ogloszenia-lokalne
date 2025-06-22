<?php
require_once 'db.php';
require_once 'session.php';

$category_id = (int)($_GET['id'] ?? 0);
$category_name = '';
$ads = [];

$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $category_name = $row['name'];
} else {
    die("Nie znaleziono kategorii.");
}
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM ads WHERE category_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$ads = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ogłoszenia - <?= htmlspecialchars($category_name) ?></title>
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

  <main>
    <h1>Ogłoszenia w kategorii: <?= htmlspecialchars($category_name) ?></h1>

    <div class="ads-list">
      <?php if (empty($ads)): ?>
        <p>Brak ogłoszeń w tej kategorii.</p>
      <?php else: ?>
        <?php foreach ($ads as $ad): ?>
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
              <p class="description"><?= htmlspecialchars(substr($ad['description'], 0, 100)) ?></p>
            </div>
            <div class="ad-list-actions">
              <a href="ad_view.php?id=<?= $ad['id'] ?>&return=category.php?id=<?= $category_id ?>" class="btn-action">Zobacz</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </main>

</body>
</html>
