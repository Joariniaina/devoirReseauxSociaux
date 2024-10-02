<?php
    // models/Post.php
class Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllPosts() {
        $sql = "
            SELECT posts.id AS post_id, posts.content AS post_content, posts.created_at AS post_date, posts.user_id AS post_userId,
                   users.username AS post_author 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC";
        return $this->conn->query($sql);
    }

    public function getReactionsForPost($post_id) {
        $sql_reactions = "
            SELECT reaction_type, COUNT(*) AS total 
            FROM post_reactions 
            WHERE post_id = ? 
            GROUP BY reaction_type";
        $stmt = $this->conn->prepare($sql_reactions);
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getCommentsForPost($post_id) {
        $sql_comments = "
            SELECT comments.id AS comment_id, comments.content AS comment_content, comments.created_at AS comment_date, 
                   users.username AS comment_author 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.post_id = ? 
            ORDER BY comments.created_at ASC";
        $stmt = $this->conn->prepare($sql_comments);
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}

?>