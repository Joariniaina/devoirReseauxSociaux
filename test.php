<?php
require './back/db.php';
session_start();

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ./views/login.php');
    exit();
}

// Récupération de tous les posts et leurs commentaires
$sql = "
    SELECT posts.id AS post_id, posts.content AS post_content, posts.created_at AS post_date, posts.user_id AS post_userId,
           users.username AS post_author 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC";
$posts_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réseau Social</title>
    <link rel="stylesheet" href="../front/style.css">
</head>
<body>
    <div id="container">
        <div id="head"> 
            <div class="navBar">
                <span><h4 id="nom"><?php echo htmlspecialchars($_SESSION['username']); ?></h4></span>
                <span><img src="../img/personne.png" alt="" class="avatar"></span>
            </div>
        </div>
        <div id="head1"> 
           <h2>.</h2>
        </div>

        <!-- Formulaire de publication d'un post avec AJAX -->
        <div id="formulaire">
            <div id="form1">
            <div class="searchBar">
                <input type="text" id="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search_query); ?>">
                <img src="../img/search.png" alt="Rechercher" id="searchBouton">
            </div>
            <div id="publicationsContainer">
                <!-- Les publications seront ajoutées ici -->
            </div>
    
                <div class="form1Icon">
                    <div class="Iconcontainer">
                        <img src="../img/users_7263418.png" alt="" class="icon">
                        <p>Amis</p>
                    </div>
                    <div class="Iconcontainer">
                        <img src="../img/communication.png" alt="" class="icon">
                        <p>Groupes</p>
                    </div>
                    <div class="Iconcontainer">
                        <img src="../img/business-report_7087423.png" alt="" class="icon">
                        <p>Evenements</p>
                    </div>
                    <div class="Iconcontainer">
                        <img src="../img/gift_12213812.png" alt="" class="icon">
                        <p>Souvenirs</p>
                    </div>
                    <div class="Iconcontainer">
                        <img src="../img/reglages.png" alt="" class="icon">
                        <p>Parametres</p>
                    </div>
                </div>
                <a href="./controllers/logout.php" id="deconnecte">Déconnexion</a>    
            </div>

            <div id="form2">
                <form id="postForm" method="POST">
                    <div class="partage">    
                        <div id="partageText">
                            <textarea name="content" placeholder="Que voulez-vous partager ?" id="partageContent" required></textarea><br>
                            <button type="submit" id="btPartage">Publier</button>
                        </div>   
                    </div>    
                </form>
                <hr>

                <!-- Affichage des posts -->
                <h3>Publications récentes</h3>
                <div id="postsContainer">
                    <?php while ($post = $posts_result->fetch_assoc()) : ?>
                        <div class="publication" id="post_<?php echo $post['post_id']; ?>">
                            <div class="post">
                                <div class="pubHead">
                                    <img src="../img/personne.png" alt="" class="avatar">
                                    <div class="pubHeadContainer">    
                                        <small><?php echo htmlspecialchars($post['post_author']); ?></small>
                                        <small>Publié le <?php echo $post['post_date']; ?></small>
                                    </div>
                                </div>
                                <div class="pubContent">
                                    <div class="pubContentContainer">
                                        <p><?php echo htmlspecialchars($post['post_content']); ?></p>
                                    </div>
                                </div>
                                <div class="pubActions">
                                    <div class="reactionCount" id="reactionCount_<?php echo $post['post_id']; ?>">
                                        J'aime: 0 
                                        Je n'aime pas: 0 
                                        Joie: 0 
                                        Triste: 0
                                    </div>
                                    <div class="pubNav">
                                        <button class="reactionBtn" data-post-id="<?php echo $post['post_id']; ?>" data-reaction="aime"><img src="../img/like_777123.png" alt="Aime" class="reaction-icon"></button>
                                        <button class="reactionBtn" data-post-id="<?php echo $post['post_id']; ?>" data-reaction="haie"><img src="../img/thumb-down_889220.png" alt="Haie" class="reaction-icon"></button>
                                        <button class="reactionBtn" data-post-id="<?php echo $post['post_id']; ?>" data-reaction="joie"><img src="../img/heart_656688.png" alt="Joie" class="reaction-icon"></button>
                                        <button class="reactionBtn" data-post-id="<?php echo $post['post_id']; ?>" data-reaction="triste"><img src="../img/disappointed_17204683.png" alt="Triste" class="reaction-icon"></button>
                                        <div class="pubActionsModif" id="post_<?php echo $post['post_id']; ?>">
                                            <button class="optionsBtn" data-post-id="<?php echo $post['post_id']; ?>" onclick="toggleDropdown(<?php echo $post['post_id']; ?>)">⋮</button>
                                            <div id="dropdown-<?php echo $post['post_id']; ?>" class="dropdownMenu" style="display: none;">
                                                <button class="editPostBtn" data-post-id="<?php echo $post['post_id']; ?>" onclick="window.location.href='./views/edit_post.php?post_id=<?php echo $post['post_id']; ?>'">Modifier</button>
                                                <button class="deletePostBtn" data-post-id="<?php echo $post['post_id']; ?>" onclick="confirmDelete(<?php echo $post['post_id']; ?>)">Supprimer</button>
                                            </div>
                                        </div>
                                    </div> 
                                </div>

                                <div id="editPostForm" style="display: none;">
                                    <textarea id="editContent" required></textarea>
                                    <button id="saveEditBtn">Sauvegarder</button>
                                    <button id="cancelEditBtn">Annuler</button>
                                </div>

                                <div class="reactionCount" id="reactionCount_<?php echo $post['post_id']; ?>"></div>
                            </div>

                            <!-- Section des commentaires -->
                            <!-- Formulaire pour ajouter un commentaire -->
                            <form class="commentForm" data-post-id="<?php echo $post['post_id']; ?>">
                                <textarea class="comment" name="comment_content" placeholder="Ajouter un commentaire..." required></textarea>
                                <button type="submit">envoyer</button>
                            </form>
                            <!-- Lien pour afficher/masquer les commentaires -->
                            <a href="#" class="toggleCommentsLink">Afficher les commentaires</a>

                            <!-- Section des commentaires -->
                            <div class="commentsContainer" id="commentsSection"style="display: none;" >
                                <h4>Commentaires :</h4>
                                <?php
                                // Affichage des commentaires
                                $postId = $post['post_id'];
                                $comments_sql = "SELECT comments.content, comments.created_at, users.username 
                                                 FROM comments 
                                                 JOIN users ON comments.user_id = users.id 
                                                 WHERE comments.post_id = ?";
                                $stmt = $conn->prepare($comments_sql);
                                $stmt->bind_param("i", $postId);
                                $stmt->execute();
                                $comments_result = $stmt->get_result();

                                while ($comment = $comments_result->fetch_assoc()) : ?>
                                    <div class="comment">
                                        <strong><?php echo htmlspecialchars($comment['username']); ?></strong>: 
                                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                                        <small>Publié le <?php echo $comment['created_at']; ?></small>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="form3">
                <h4>lien vers l'admin</h4>
                <a  class="Iconcontainer" href="https://facebook.com" target="_blank">
                    <img src="../img/facebook_733547.png" alt="" class="icon">
                    <p>facebook</p>
                </a>
                <a  class="Iconcontainer" href="https://google.com" target="_blank">
                    <img src="../img/google_2504914.png" alt="" class="icon">
                    <p>google</p>
                </a>
                <a  class="Iconcontainer" href="https://whatsapp.com" target="_blank">
                    <img src="../img/whatsapp_3536445.png" alt="" class="icon">
                    <p>whatsapp</p>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Gestion de l'envoi AJAX pour le formulaire de publication
        document.getElementById('postForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const content = document.getElementById('partageContent').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', './controllers/Create_postController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const post = JSON.parse(xhr.responseText);
            const postHTML = `
                <div class="publication" id="post_${post.post_id}">
                    <div class="post">
                        <div class="pubHead">
                            <img src="../img/personne.png" alt="" class="avatar">
                            <div class="pubHeadContainer">    
                                <small>${post.post_author}</small>
                                <small>Publié le ${post.post_date}</small>
                            </div>
                        </div>
                        <div class="pubContent">
                            <div class="pubContentContainer">
                                <p>${post.post_content}</p>
                            </div>
                        </div>
                        <div class="pubActions">
                            <button class="reactionBtn" data-post-id="${post.post_id}" data-reaction="aime">J'aime</button>
                            <button class="reactionBtn" data-post-id="${post.post_id}" data-reaction="haie">Je n'aime pas</button>
                            <button class="reactionBtn" data-post-id="${post.post_id}" data-reaction="joie">Joie</button>
                            <button class="reactionBtn" data-post-id="${post.post_id}" data-reaction="triste">Triste</button>
                            <div class="reactionCount" id="reactionCount_${post.post_id}">
                                J'aime: ${post.reactions.aime} 
                                Je n'aime pas: ${post.reactions.haie} 
                                Joie: ${post.reactions.joie} 
                                Triste: ${post.reactions.triste}
                            </div>    
                        </div>
                        <div class="commentsContainer">
                            <h4>Commentaires :</h4>
                            <div id="commentsList_${post.post_id}"></div>
                            <form class="commentForm" data-post-id="${post.post_id}">
                                <textarea name="comment_content" placeholder="Ajouter un commentaire..." required></textarea>
                                <button type="submit">Commenter</button>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            // Ajouter le nouveau post en haut de la liste des publications
            document.getElementById('postsContainer').insertAdjacentHTML('afterbegin', postHTML);
            document.getElementById('partageContent').value = ''; // Réinitialiser le champ de texte
        } else {
            alert('Une erreur est survenue lors de la publication.');
        }
    };
    xhr.send(`content=${encodeURIComponent(content)}`);
});

document.querySelectorAll('.toggleCommentsLink').forEach(function(toggleLink, index) {
        toggleLink.addEventListener('click', function(e) {
            e.preventDefault(); // Empêche le comportement par défaut du lien

            // Sélectionne la section de commentaires correspondante
            var commentsContainer = document.querySelectorAll('.commentsContainer')[index];
            if (commentsContainer.style.display === 'none') {
                commentsContainer.style.display = 'block';
                this.textContent = 'Masquer les commentaires'; // Changer le texte du lien
            } else {
                commentsContainer.style.display = 'none';
                this.textContent = 'Afficher les commentaires'; // Changer le texte du lien
            }
        });
    });

        // Gestion des commentaires
        document.querySelectorAll('.commentForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const commentContent = this.querySelector('textarea[name="comment_content"]').value;

                const xhr = new XMLHttpRequest();
                xhr.open('POST', './controllers/Create_commentController.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Mise à jour de l'affichage des commentaires (peut être amélioré)
                        location.reload();
                    } else {
                        alert('Une erreur est survenue lors de l\'ajout du commentaire.');
                    }
                };
                xhr.send(`comment_content=${encodeURIComponent(commentContent)}&post_id=${postId}`);
            });
        });

        // Fonction de confirmation pour la suppression d'un post
        function confirmDelete(postId) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce post ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', './controllers/delete_post.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementById(`post_${postId}`).remove();
                    } else {
                        alert('Une erreur est survenue lors de la suppression.');
                    }
                };
                xhr.send(`post_id=${postId}`);
            }
        }

        // Fonction pour basculer le menu dropdown
        function toggleDropdown(postId) {
            const dropdown = document.getElementById(`dropdown-${postId}`);
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

// Mettre à jour l'affichage des compteurs de réaction
function updateReactionCount(postId, reactionsCount) {
    const reactionCountElement = document.getElementById(`reactionCount_${postId}`);
    reactionCountElement.innerHTML = `
        J'aime: ${reactionsCount.aime || 0} 
        Je n'aime pas: ${reactionsCount.haie || 0} 
        Joie: ${reactionsCount.joie || 0} 
        Triste: ${reactionsCount.triste || 0}
    `;
}

        // Gestion des réactions
    document.querySelectorAll('.reactionBtn').forEach(button => {
    button.addEventListener('click', function() {
        const postId = this.dataset.postId;
        const reaction = this.dataset.reaction;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', './controllers/ReactController.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const reactionsCount = JSON.parse(xhr.responseText);
                updateReactionCount(postId, reactionsCount);
            } else {
                alert('Une erreur est survenue lors de l\'enregistrement de la réaction.');
            }
        };
        xhr.send(`post_id=${postId}&reaction=${reaction}`);
    });
});

// Gestion de la recherche
document.getElementById('searchBouton').addEventListener('click', function() {
            const searchValue = document.getElementById('search').value;
            window.location.href = '?search=' + encodeURIComponent(searchValue);
        });
        document.getElementById('search').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const posts = document.querySelectorAll('.publication');

    posts.forEach(post => {
        const content = post.querySelector('.pubContentContainer p').textContent.toLowerCase();
        const author = post.querySelector('.pubHeadContainer small').textContent.toLowerCase();

        if (content.includes(query) || author.includes(query)) {
            post.style.display = 'flex';
        } else {
            post.style.display = 'none';
        }
    });
});


        
    </script>
</body>
</html>