<?php
require_once 'db_connect.php';

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

$stmt = $pdo->prepare("SELECT content FROM chat WHERE name = ? AND password = ?");
$stmt->execute([$name, $password]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if ($user['content'] === $content) {
        echo "OK"; // Treat identical content as success
    } else {
        $update = $pdo->prepare("UPDATE chat SET content = ?, last_updated = NOW() WHERE name = ?");
        $update->execute([$content, $name]);

        if ($update->rowCount() > 0) {
            echo "OK";
        } else {
            echo "Unexpected error: No rows updated.";
        }
    }
} else {
    echo "Name/password not found. Update failed.";
}
?>
