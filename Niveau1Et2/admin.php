<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Administration</title> 
        <meta name="author" content="Jeremie Patot">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
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

        <?php


        /**
         * Etape 1: Ouvrir une connexion avec la base de donnée.
         */
        // on va en avoir besoin pour la suite

        include "./connexion.php"

        ?>

        <div id="wrapper" class='admin'>
            <aside>
                <h2>Mots-clés</h2>
                <?php


                /*
                 * Etape 2 : trouver tous les mots clés
                 */
                
                $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
                $lesInformations = $mysqli->query($laQuestionEnSql);

                // Vérification

                if ( ! $lesInformations)

                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }


                /*
                 * Etape 3 : @todo : Afficher les mots clés en s'inspirant de ce qui a été fait dans news.php
                 * Attention à en pas oublier de modifier tag_id=321 avec l'id du mot dans le lien
                 */

                while ($tag = $lesInformations->fetch_assoc())
                {

                    ?>

                    <article>
                        <h3><?php echo $tag['label']?></h3>
                        <p><?php echo $tag['id']?></p>
                        <nav>
                            <a href=<?php echo "tags.php?tag_id".$tag['id']?>>Post</a>
                        </nav>
                    </article>
                <?php } ?>
            </aside>
            <main>
                <h2>Utilisatrices</h2>
                <?php


                /*
                 * Etape 4 : trouver tous les mots clés
                 * PS: on note que la connexion $mysqli à la base a été faite, pas besoin de la refaire.
                 */

                $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
                $lesInformations = $mysqli->query($laQuestionEnSql);

                // Vérification

                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }


                /*
                 * Etape 5 : @todo : Afficher les utilisatrices en s'inspirant de ce qui a été fait dans news.php
                 * Attention à en pas oublier de modifier dans le lien les "user_id=123" avec l'id de l'utilisatrice
                 */

                while ($tag = $lesInformations->fetch_assoc())
                {

                    ?>

                    <article>
                        <h3><?php echo $tag['alias']?></h3>
                        <p><?php echo $tag['id']?></p>
                        <nav>
                            <a href=<?php echo "wall.php?user_id=".$tag['id']?>>Mur</a>
                            <a href=<?php echo "feed.php?user_id=".$tag['id']?>>Flux</a>
                            <a href=<?php echo "settings.php?user_id=".$tag['id']?>>Paramètres</a>
                            <a href=<?php echo "followers.php?user_id=".$tag['id']?>>Suiveurs</a>
                            <a href=<?php echo "subscriptions.php?user_id=".$tag['id']?>>Abonnements</a>
                        </nav>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
