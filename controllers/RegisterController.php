<?php
require '../back/db.php';
require '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $user = new User($conn);

    if ($user->registerReal($name, $username, $email, $password)) {
        echo "Inscription réussie. <a href='../views/login.php'>Connexion</a>";
    } else {
        echo "Erreur: " . $conn->error;
    }
}
?>
