<?php
require_once 'db_connect.php';

$name = isset($_POST['name']) ? trim($_POST['name']) : '';

$stmt = $pdo->prepare("SELECT content FROM chat WHERE name = ?");
$stmt->execute([$name]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo $user['content'];
} else {
    echo "ERROR: User not found.";
}
?>
