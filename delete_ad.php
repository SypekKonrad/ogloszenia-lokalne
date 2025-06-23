<?php
require_once 'session.php';
require_once 'db.php';

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: account.php');
    exit;
}

$ad_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT image FROM ads WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $ad_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ad = $result->fetch_assoc();
$stmt->close();

if (!$ad) {
    // not found
    header('Location: account.php');
    exit;
}

// delete ad
$stmt = $conn->prepare("DELETE FROM ads WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $ad_id, $user_id);
$stmt->execute();
$stmt->close();


if (!empty($ad['image']) && file_exists($ad['image'])) {
    unlink($ad['image']);
}

header('Location: account.php');
exit;
?>
