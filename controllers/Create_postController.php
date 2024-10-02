<?php
require '../back/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $userId = $_SESSION['user_id'];

    // Insertion du nouveau post dans la base de données
    $sql = "INSERT INTO posts (content, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $content, $userId);

    if ($stmt->execute()) {
        // Récupération des données du post nouvellement créé
        $post_id = $stmt->insert_id;
        $post_date = date('Y-m-d H:i:s');
        $username = $_SESSION['username'];

        // Retourner les données du post en format JSON
        echo json_encode([
            'post_id' => $post_id,
            'post_content' => $content,
            'post_date' => $post_date,
            'post_author' => $username,
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la création du post.']);
    }
}
?>
