<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include 'header.php'
    ?>
    <div id="wrapper">
        <?php
        $userId = intval($_GET['user_id']);
        ?>
        <?php
        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
        ?>

        <aside>
            <?php
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user["alias"] ?>
                </p>
            </section>
        </aside>
        <main>
            <?php
            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
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

            while ($post = $lesInformations->fetch_assoc()) {

                echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post["created"] ?></time>
                    </h3>
                    <address>par <?php echo $post["author_name"] ?></address>
                    <div>
                        <p><?php echo $post["content"] ?></p>
                    </div>
                    <footer>
                        <small>♥ <?php echo $post["like_number"] ?></small>
                        <a href=""><?php echo "#" . $post["taglist"] ?></a>
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>