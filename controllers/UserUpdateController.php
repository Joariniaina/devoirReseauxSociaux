<?php
    // Connexion à la base de données
    require 'db.php';
    require '../models/User.php';

    // Vérifier si l'ID est défini
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $content = $_POST['content'];

        // Mettre à jour les données de l'utilisateur
        $user = new User($conn);

        if ($user->updateUsers($content, $id)) {
            // Rediriger vers index.php après la mise à jour
            header("Location: index.php");
            exit();
        } else {
            echo "Erreur : " . $conn->error;
        }
        
        $update_stmt->close();
    }

    // Fermer la connexion
    $conn->close();
?>
