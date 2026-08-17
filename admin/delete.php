<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? null;

if ($id) {
    if ($type == 'post') {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: index.php");
exit;
?>
