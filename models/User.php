<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function userExists($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows > 0;
    }

    public function register($email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $email, $hashed_password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function setPasswordResetToken($email, $token, $expiry) {
        $sql = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sss', $token, $expiry, $email);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUserByToken($token) {
        $sql = "SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function resetPassword($email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $hashed_password, $email);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
// ////////////////////////////////////////////////////////////////////////////////////////////////////
    public function login($email) {
        // Récupération de l'utilisateur
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql); // Correction ici
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function registerReal($name, $username, $email, $password)
    {
         // Insertion dans la base de données
        $sql = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $username, $email, $password);
        return $stmt->execute();
    }

    public function comment($post_id, $user_id, $content)
    {
        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iis', $post_id, $user_id, $content);
        return $stmt->execute();
    }

    public function create_post( $user_id, $content)
    {
        $sql = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('is', $user_id, $content);
        return $stmt->execute();

    }

    public function delete($post_id, $session)
    {
        // Supprimer la publication si elle appartient à l'utilisateur connecté
        $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $post_id, $session);

        return $stmt->execute(); 
    }

    public function edit($post_id, $session)
    {
        // Récupérer les informations du post à modifier
        $sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $post_id, $session);
        return $stmt->execute();
    }

    public function update( $content, $post_id, $session)
    {
        $update_sql = "UPDATE posts SET content = ? WHERE id = ? AND user_id = ?";
        $update_stmt = $this->conn->prepare($update_sql);
        $update_stmt->bind_param('sii', $content, $post_id, $session);
        return $update_stmt->execute();
    }

    public function verifyCommentReact($comment_id, $user_id)
    {
        // Vérification si l'utilisateur a déjà réagi à ce commentaire
        $sql_check = "SELECT * FROM comment_reactions WHERE comment_id = ? AND user_id = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param('ii', $comment_id, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        return $result_check->num_rows;
    }

    public function firstVerifyCommentReact($reaction_type, $comment_id, $user_id)
    {
        $sql = "INSERT INTO comment_reactions (reaction_type, comment_id, user_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sii', $reaction_type, $comment_id, $user_id);
        return $stmt->execute();
    }

    public function secondVerifyCommentReact($reaction_type, $comment_id, $user_id)
    {
        // Si l'utilisateur a déjà réagi, mettre à jour la réaction
        $sql = "UPDATE comment_reactions SET reaction_type = ? WHERE comment_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sii', $reaction_type, $comment_id, $user_id);
        return $stmt->execute();
    }

    public function verifyPubReact($post_id, $user_id)
    {
            // Vérification si l'utilisateur a déjà réagi à ce post
        $sql_check = "SELECT * FROM post_reactions WHERE post_id = ? AND user_id = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param('ii', $post_id, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        return $result_check->num_rows;
    }

    public function updateUsers($content, $id)
    {
        // Mettre à jour les données de l'utilisateur
        $update_sql = "UPDATE posts SET content = ? WHERE id = ?";
        $update_stmt = $this->conn->prepare($update_sql);
        $update_stmt->bind_param("si",$content, $id);
        return $update_stmt->execute();
    }
}
?>