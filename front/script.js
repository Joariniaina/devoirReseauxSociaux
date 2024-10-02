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
