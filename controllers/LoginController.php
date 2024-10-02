<?php
require '../back/db.php';
require '../models/User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User($conn);
    $userData = $user->login($email); // Correction ici
    // Vérification du mot de passe
    if ($userData && password_verify($password, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        header("Location: ../test.php");
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>
