<?php
require '../back/db.php';
session_start();

if (isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    
    // Vérifier si l'utilisateur est propriétaire du post ou un admin (si besoin)
    $userId = $_SESSION['user_id'];
    $checkPostOwnerSql = "SELECT user_id FROM posts WHERE id = ?";
    $stmt = $conn->prepare($checkPostOwnerSql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        if ($post['user_id'] == $userId) {
            // Suppression du post
            $deleteSql = "DELETE FROM posts WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $postId);
            if ($deleteStmt->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Vous n\'êtes pas autorisé à supprimer ce post.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Post non trouvé.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aucun ID de post fourni.']);
}
?>
