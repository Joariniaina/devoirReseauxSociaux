<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front/sytleFront.css">
</head>
<body>
    <div class="content">
        <div class="childContent">
            <img src="../img/personne.png" alt="" class="imgLogo">
            <h2>Inscription</h2>
            <form action="../controllers/RegisterController.php" method="POST" class="forme">
                <div class="fomulaire">
                    <div>
                        <p>Nom :</p>    
                        <input type="text" name="name" placeholder="........................................" required><br>
                    </div>
                    <div>
                        <p>Prénom :</p>
                        <input type="text" name="username" placeholder=".........................................." required><br>
                    </div>
                    <div>
                        <p>E-mail :</p>
                        <input type="email" name="email" placeholder="..............................................." required><br>
                    </div>
                    <div>    
                        <p>Password :</p>
                        <input type="password" name="password" placeholder=".........................................." required><br>
                    </div>
                </div>
                <div id="btSubmit">
                    <bouton class="bouton" id="annuler"><a href="../index.php" id="annulerLien">Annuler</a></bouton>
                    <button type="submit" class="bouton" id="connecter">S'inscrire</button>
                </div>        
            </form>
        </div>
    </div>
</body>
</html>
