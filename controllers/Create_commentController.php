<?php
require '../back/db.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

// Insertion du commentaire dans la base de données
$commentContent = $_POST['comment_content'];
$postId = $_POST['post_id'];
$userId = $_SESSION['user_id'];

$sql = "INSERT INTO comments (content, post_id, user_id, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $commentContent, $postId, $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        'username' => htmlspecialchars($_SESSION['username']),
        'content' => htmlspecialchars($commentContent),
        'created_at' => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(500);
}
