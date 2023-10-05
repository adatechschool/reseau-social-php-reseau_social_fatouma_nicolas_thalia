<!doctype html>
<?php session_start(); ?>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Jeremie Patot">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <img src="resoc.jpg" alt="Logo de notre réseau social" />
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="wall.php?user_id=5">Mur</a>
            <a href="feed.php?user_id=5">Flux</a>
            <a href="tags.php?tag_id=1">Mots-clés</a>
        </nav>
        <nav id="user">
            <a href="#">Profil</a>
            <ul>
                <li><a href="settings.php?user_id=5">Paramètres</a></li>
                <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
            </ul>
        </nav>
    </header>

    <div id="wrapper">

        <?php


        /**
         * Etape 1: Le mur concerne un utilisateur en particulier
         * La première étape est donc de trouver quel est l'id de l'utilisateur
         * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
         * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
         * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
         */

        $userId = intval($_GET['user_id']);

        ?>

        <?php


        /**
         * Etape 2: se connecter à la base de donnée
         */

        include "./connexion.php";

        ?>

        <aside>

            <?php


            /**
             * Etape 3: récupérer le nom de l'utilisateur
             */


            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();

            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
            
            ?>

            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice :
                    <?php echo $user['alias'] ?>
                    (n°
                    <?php echo $userId ?>)
                </p>
            </section>
        </aside>


        <main>

            <?php
            $pageUserId = intval($_GET['user_id']);
            $sessionId = $_SESSION['connected_id'];

            if ($pageUserId == $sessionId)
            {

            $enCoursDeTraitement = isset($_POST['post']);
            if ($enCoursDeTraitement) {

                $postContent = $_POST['post'];
                $user_id = $_SESSION['connected_id'];


                //Etape 3 : Ouvrir une connexion avec la base de donnée.
            
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

                $postContent = $mysqli->real_escape_string($postContent);
                $user_id = $mysqli->real_escape_string($user_id);

                //Etape 5 : construction de la requete
                $lInstructionSql = "INSERT INTO posts (id, user_id, content, created, parent_id) "
                    . "VALUES (NULL, "
                    . "'" . $user_id . "', "
                    . "'" . $postContent . "', "
                    . "NOW(),"
                    . "NULL"
                    . ");";


                // Etape 6: exécution de la requete
            
                $ok = $mysqli->query($lInstructionSql);
                if (!$ok) {
                    echo "Le post n'a pas été enregistré : " . $mysqli->error;
                } else {
                    echo "Le post a bien été enregistré " ;
                }
            }

            ?>

            <article>

                <form action="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?> " method="post">
                    <input type='hidden' name='???' value='achanger'>
                    <dl>
                        <dt><label for='post'>Post</label></dt>
                        <dd><input type='post' name='post'></dd>
                    </dl>
                    <input type='submit'>
                </form>

            </article>

            <?php

            }


            /**
             * Etape 3: récupérer tous les messages de l'utilisatrice
             */

            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    users.id as author_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";

            $lesInformations = $mysqli->query($laQuestionEnSql);

            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }


            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             */

            while ($post = $lesInformations->fetch_assoc()) {

                include "post.php";

            } ?>

        </main>
    </div>
</body>

</html>