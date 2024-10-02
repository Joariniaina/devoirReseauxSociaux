<?php
require '../back/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Interdiction d'accès
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $reaction = $_POST['reaction'];
    $userId = $_SESSION['user_id'];

    // Vérification si l'utilisateur a déjà réagi à ce post
    $check_sql = "SELECT * FROM post_reactions WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $existing_reaction = $stmt->get_result()->fetch_assoc();

    if ($existing_reaction) {
        // Si l'utilisateur a déjà réagi, mettre à jour la réaction
        $update_sql = "UPDATE post_reactions SET reaction_type = ? WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sii", $reaction, $postId, $userId);
        $stmt->execute();
    } else {
        // Si l'utilisateur n'a pas encore réagi, insérer une nouvelle réaction
        $insert_sql = "INSERT INTO post_reactions (post_id, user_id, reaction_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iis", $postId, $userId, $reaction);
        $stmt->execute();
    }

    // Récupération du nombre de chaque type de réaction
    $count_sql = "SELECT reaction_type, COUNT(*) as count FROM post_reactions WHERE post_id = ? GROUP BY reaction_type";
    $stmt = $conn->prepare($count_sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    $reactions_count = [];
    while ($row = $result->fetch_assoc()) {
        $reactions_count[$row['reaction_type']] = $row['count'];
    }

    echo json_encode($reactions_count);
}
?>
