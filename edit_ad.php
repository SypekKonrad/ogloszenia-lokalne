<?php
require_once 'session.php';
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: account.php');
    exit;
}

$ad_id = (int)$_GET['id'];

// Pobierz dane ogłoszenia
$stmt = $conn->prepare("SELECT * FROM ads WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $ad_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ad = $result->fetch_assoc();
$stmt->close();

if (!$ad) {
    echo "Ogłoszenie nie istnieje lub nie należy do Ciebie.";
    exit;
}

$error = '';
$success = '';

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);

    $imagePath = $ad['image']; // Domyślnie stare zdjęcie

    // Obsługa przesyłania nowego zdjęcia
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExt, $allowedExt)) {
            $newFileName = 'uploads/' . uniqid() . '.' . $imageExt;
            if (move_uploaded_file($imageTmp, $newFileName)) {
                $imagePath = $newFileName;
            } else {
                $error = "Nie udało się przesłać zdjęcia.";
            }
        } else {
            $error = "Nieprawidłowy format pliku. Dozwolone: jpg, jpeg, png, gif.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE ads SET title = ?, description = ?, location = ?, image = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssssii", $title, $description, $location, $imagePath, $ad_id, $user_id);
        if ($stmt->execute()) {
            $success = "Ogłoszenie zostało zaktualizowane.";
            // Odśwież dane ogłoszenia po aktualizacji
            $ad['title'] = $title;
            $ad['description'] = $description;
            $ad['location'] = $location;
            $ad['image'] = $imagePath;
        } else {
            $error = "Błąd podczas aktualizacji ogłoszenia.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj ogłoszenie</title>
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
        <h1>Edytuj ogłoszenie</h1>
        <a href="account.php" class="btn-primary">Powrót</a>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success" style="margin-top: 20px; margin-bottom: 20px; font-weight: bold;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="title">Tytuł</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($ad['title']) ?>" required>

            <label for="description">Opis</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($ad['description']) ?></textarea>

            <label for="location">Lokalizacja</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($ad['location']) ?>" required>

            <label for="image">Nowe zdjęcie (opcjonalnie)</label>
            <input type="file" id="image" name="image">

            <?php if (!empty($ad['image'])): ?>
                <p>Aktualne zdjęcie:</p>
                <img src="<?= htmlspecialchars($ad['image']) ?>" alt="Obecne zdjęcie" style="max-width: 200px;">
            <?php endif; ?>

            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn-primary same-size-btn">Zapisz zmiany</button>
            </div>
        </form>


    </main>
</body>
</html>
