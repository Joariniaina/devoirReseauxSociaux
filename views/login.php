<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front/sytleFront.css">
</head>
<body>
    <div class="content">
        <div class="childContent">
            <img src="../img/personne.png" alt="" class="imgLogo">
            <h2>Login</h2>
            <form action="../controllers/LoginController.php" method="POST" class="forme">
                <div class="fomulaire">
                    <div>
                        <p>E-mail :</p>
                        <input type="email" name="email" placeholder="................................................" required>
                    </div>
                    <div>
                        <p>Mot de passe :</p>
                        <input type="password" name="password" placeholder=".................................." required><br>
                    </div>
                </div>    
                <div id="btSubmit">
                    <bouton class="bouton" id="annuler"><a href="../index.html" id="annulerLien">Annuler</a></bouton>
                    <button type="submit" class="bouton" id="connecter">Connect</button>
                </div> 
                <p><a href="../mdp/forgot_password.php">Mot de passe oublié ?</a></p>
            </form>
        </div>
    </div>
</body>
</html>