<?php
require '../back/db.php';
require '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    $user = new User($conn);
    $userData = $user->getUserByToken($token);

    if ($userData) {
        if ($user->resetPassword($userData['email'], $password)) {
            header("Location: ../views/index.html");
        } else {
            echo "Erreur lors de la réinitialisation du mot de passe.";
        }
    } else {
        echo "Le lien de réinitialisation est invalide ou a expiré.";
    }
} else {
    $token = $_GET['token'];
    include '../views/reset_password.php';
}
?>
