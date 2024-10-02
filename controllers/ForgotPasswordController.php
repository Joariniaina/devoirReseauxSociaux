<?php
require '../back/db.php';
require '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user = new User($conn);

    if ($user->userExists($email)) {
        date_default_timezone_set('Africa/Nairobi');
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        if ($user->setPasswordResetToken($email, $token, $expiry)) {
            $host = $_SERVER['HTTP_HOST'];
            $port = $_SERVER['SERVER_PORT'];
            $reset_link = "http://localhost:$port/views/reset_password.php?token=" . $token;
            $subject = "Réinitialisation de votre mot de passe";
            $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe : " . $reset_link;
            $headers = "From: no-reply@yourwebsite.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "Un lien de réinitialisation a été envoyé à votre adresse e-mail.";
            } else {
                echo "Erreur lors de l'envoi de l'e-mail.";
            }
        } else {
            echo "Erreur lors de la création du token.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet e-mail.";
    }
}
?>
