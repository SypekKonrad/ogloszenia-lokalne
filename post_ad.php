<?php
require_once 'session.php';
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$userLogin = $_SESSION['login'] ?? null;

$user_id = null;
if ($userLogin) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $userLogin);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $user_id = $row['id'];
    }
    $stmt->close();
}

$categories = [];
$res = $conn->query("SELECT id, name FROM categories ORDER BY name");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $categories[] = $row;
    }
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $category_id = (int)($_POST['category'] ?? 0);

   if (!$title || !$description || !$category_id || !$location) {
        $message = "Wypełnij wszystkie pola.";
    } elseif (!$user_id) {
        $message = "Nie znaleziono użytkownika, zaloguj się ponownie.";
    } else {
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadDir = __DIR__ . '/uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . uniqid() . "_" . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = 'uploads/' . basename($targetFile);
            } else {
                $message = "Błąd podczas przesyłania pliku.";
            }
        }

        if (!$message) {
            $stmt = $conn->prepare("INSERT INTO ads (title, description, image, user_id, category_id, location) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $title, $description, $imagePath, $user_id, $category_id, $location);
            if ($stmt->execute()) {
                // $message = "Ogłoszenie zostało dodane!";
                $new_ad_id = $stmt->insert_id; // Get the ID of the newly created ad
                $stmt->close();
                header("Location: ad_view.php?id=" . $new_ad_id); // Redirect to the ad
                exit();
            } else {
                $message = "Błąd podczas dodawania ogłoszenia.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dodaj ogłoszenie</title>
<link rel="stylesheet" href="style.css" />
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
    <h2>Dodaj ogłoszenie</h2>
    <?php if ($message): ?>
        <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
   <form method="post" enctype="multipart/form-data">
  <label for="title">Tytuł ogłoszenia:</label>
  <input type="text" name="title" id="title" required>

        <label for="category">Kategoria:</label>
        <select name="category" id="category" required>
        <option value="">Wybierz kategorię</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="location">Lokalizacja:</label>
        <input type="text" name="location" id="location" required><br><br>

        <label for="image">Zdjęcie:</label><br>
        <input type="file" name="image" id="image" accept="image/*" class="btn-primary"><br><br>

        <label for="description">Opis:</label><br>
        <textarea name="description" id="description" rows="5" required></textarea><br><br>

        <!-- <div style="margin-top: 30px; padding: 15px; background-color: #f1f5ff; border-radius: 10px;">
            <p>Dane kontaktowe:</p>
            <p>Użytkownik: <?php echo htmlspecialchars($userLogin); ?></p>
            <?php
            if ($userLogin) {
                $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
                $stmt->bind_param("s", $userLogin);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
                }
                $stmt->close();
            }
            ?>
        </div><br> -->

        <button type="submit" class="btn-primary">Dodaj ogłoszenie</button>
    </form>
</main>
</body>
</html>
