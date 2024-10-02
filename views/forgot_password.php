<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../front/styleFront.css">
</head>
<body>
<div class="content">
    <div class="childContent">
        <img src="../img/personne.png" alt="" class="imgLogo">
        <h2>Mot de passe oublié</h2>
        <form action="../controllers/ForgotPasswordController.php" method="POST" class="forme">
            <div class="fomulaire">
                <div>
                    <p>Votre E-mail : </p>
                    <input type="email" name="email" id="email" placeholder="...................................." required><br><br>
                </div>
            </div>
            <div id="btSubmit">
                <button class="bouton" id="annuler"><a href="../index.html" id="annulerLien">Annuler</a></button>
                <button type="submit" class="bouton" id="connecter">Envoyer</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
