<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser Mot de Passe</title>
    <link rel="stylesheet" href="../front/style.css">
</head>
<body>
    <div id="verify">
        <h2>Réinitialiser Mot de Passe</h2>
        <form action="../controllers/ResetPasswordController.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="password">Nouveau mot de passe:</label><br><br>
            <div class='pass'>
                <input type="password" name="password" id="password" class='passwd' placeholder="Entrez votre nouveau mot de passe" required>
                <img src="../img/option-dinterface-a-oeil-ouvert-visible.png" class="icone">
            </div><br>
            <input type="submit" value="Réinitialiser">
        </form>
    </div>
    <script src="../front/script.js"></script>
</body>
</html>
