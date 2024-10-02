<?php
// PostModel.php
class PostModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'username', 'password', 'database');
    }

    public function getPostByIdAndUser($post_id, $user_id) {
        $sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $post_id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePost($post_id, $user_id, $content) {
        $sql = "UPDATE posts SET content = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sii', $content, $post_id, $user_id);
        return $stmt->execute();
    }
}
?>
