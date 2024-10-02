<?php
require '../back/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

$userId = $_SESSION['user_id'];
$postId = $_POST['post_id'];
$reactionType = $_POST['reaction_type'];

// Vérifiez si l'utilisateur a déjà réagi à ce post
$sql = "SELECT reaction_type FROM post_reactions WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $userId, $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // L'utilisateur a déjà réagi, mettez à jour la réaction
    $sql = "UPDATE post_reactions SET reaction_type = ? WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $reactionType, $userId, $postId);
} else {
    // L'utilisateur n'a pas encore réagi, ajoutez une nouvelle réaction
    $sql = "INSERT INTO post_reactions (user_id, post_id, reaction_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $userId, $postId, $reactionType);
}

$stmt->execute();
$stmt->close();

// Mettez à jour et renvoyez le nombre total de réactions
$sql = "SELECT COUNT(*) as count FROM post_reactions WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo "Réactions: " . $row['count'];
?>
